<?php

namespace App\Http\Controllers\Account;

use App\DataTables\Account\PaymentHistoryDataTable;
use App\DataTables\Account\PaymentRequestsDataTable;
use App\Helpers\Notifications;
use App\Http\Controllers\Controller;
use App\Models\AutoPart;
use App\Models\AutoPartPurchase;
use App\Models\AutoPartPurchaseHistory;
use App\Models\AutoPartPurchaseItem;
use App\Models\AutoPartStatement;
use App\Models\Car;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\OdometerSetting;
use App\Models\Payment;
use App\Models\PaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentProcessingController extends Controller
{


    public function paymentRequests(PaymentRequestsDataTable $dataTable)
    {
        $payment_types = PaymentRequest::select('payment_type')->distinct()->orderBy('payment_type')->get()
            ->pluck('payment_type');

        return $dataTable->render('accounts.payments.payment-requests', [
            'payment_types' => $payment_types,
        ]);
    }

    public function paymentHistory(PaymentHistoryDataTable $dataTable)
    {
        $payment_types = PaymentRequest::select('payment_type')->distinct()->orderBy('payment_type')->get()
            ->pluck('payment_type');

        return $dataTable->render('accounts.payments.payment-history', [
            'payment_types' => $payment_types,
        ]);
    }

    public function processPayment($request_id)
    {
        $request = PaymentRequest::findOrFail($request_id);

        $invoice_item = "N/A";
        if ($request->payment_type == 'auto part') {
            $invoice_item = InvoiceItem::select('quantity', 'total', 'tax_amount', 'p.name')
                ->join('auto_parts as p', 'p.id', '=', 'invoice_items.item_id')
                ->where('invoice_items.invoice_id', $request->invoice_no)->get();
        }

        $payment_mode  = ['Cash', 'Cheque', 'Coupon', 'Bank Transfer', 'MoMo'];
        return view('accounts.payments.process-payment', [
            'request' => $request,
            'payment_mode' => $payment_mode,
            'invoice_items' => $invoice_item
        ]);
    }

    public function actualPayment(Request $request)
    {
        //validate
        $request->validate([
            'id' => 'required|exists:payment_requests,id',
            'payment_day' => 'required|date',
            'amount' => 'numeric|min:1', //|max:1024
            'fin_status' => 'required',
            'naration' => 'nullable',
            'fin_status' => 'nullable',
        ]);

        $pay_req = PaymentRequest::findOrFail($request->id);

        if ($pay_req->status == 'paid') {
            //return $this->sendFailureJsonResponse('Payment request is full paid.');
            throw ValidationException::withMessages(['error_field' => 'Payment request is already fully paid.']);
        }

        //check if balance is less than amount to be paid
        $bal = $pay_req->amount -  $pay_req->amount_paid;
        if ($bal <  $request->amount) {
            //return $this->sendFailureJsonResponse('Payment Amount Is More Than Balance On Request.');
            throw ValidationException::withMessages(['error_field' => 'Payment Amount Is More Than Balance On Request.']);
        }

        //if pament is or maintenance, upate status of invoice
        Payment::create([
            'user_id' => $request->user()->id,
            'payment_request_id' => $pay_req->id,
            'payment_date' => $request->payment_day,
            'amount_paid' => $request->amount,
            'payment_mode' => $request->payment_mode,
            'payment_reference' => $request->ref,
            'narration' => $request->naration,
            'payment_status' => 'paid',
        ]);

        $pay_req->amount_paid = $pay_req->amount_paid + $request->amount;
        $pay_req->status = 'partially paid';
        $pay_req->save();

        //update invoice finance status
        if ($pay_req->payment_type == 'maintenance') {
            // $invoice = Invoice::find($pay_req->invoice_no);
            // $invoice->fin_status = 'partially paid';
            // $invoice->status = 'partially paid';
            // $invoice->save();

            // if ($pay_req->amount_paid ==  $pay_req->amount) {
            //     $invoice->fin_status = 'paid';
            //     $invoice->status = 'paid';
            //     $invoice->save();
            // }

            $this->updateInvoiceStatusPaidOrPartiallyPaid($pay_req);

            //check if the maintenance is Odometer Overdue
            $car = Car::find($pay_req->car_id);
            if ($car->odometer_status == 'Overdue') {
                $car->odometer_level = $car->odometer_level + OdometerSetting::find(1)->value;
                $car->odometer_status = 'Active';
                $car->save();

                Notifications::createNotification(
                    '1', // Notifying each fleet manager
                    'Odomemter Reset',
                    'Odometer limit for next maintenance for ' . $car->model . ' car with number ' . $car->car_number . ' has been successfully resset to ' . number_format($car->odometer_level, 2) . 'km'
                );
            }
        } else if ($pay_req->payment_type == 'auto part') {
            $pstatus = $this->updateInvoiceStatusPaidOrPartiallyPaid($pay_req);

            //check if Fully Paid
            if ($pstatus) {
                $invoice_item = InvoiceItem::select('quantity', 'total', 'tax_amount', 'item_id', 'i.created_by', 'i.invoice_number')
                    ->join('invoices as i', 'i.id', '=', 'invoice_items.invoice_id')
                    ->where('invoice_id', $pay_req->invoice_no)->get();

                foreach ($invoice_item as $key => $value) {
                    //update balance and insert into statement
                    AutoPartStatement::create([
                        'auto_part_id' => $value->item_id,
                        'user_id' => $value->created_by,
                        'stock_in' => $value->quantity,
                        'stock_out' => 0,
                        'trans_type' => 'Purchase',
                        'narration' => 'Auto Part Purchase - Invoice : ' . $value->invoice_number,
                        'trans_status' => 'active',
                    ]);

                    //update auto history cost
                    AutoPartPurchaseHistory::create([
                        'auto_part_id' => $value->item_id,
                        'cost' => ($value->total + $value->tax_amount) / $value->quantity
                    ]);
                    //update auto part balance
                    $autoPart = AutoPart::find($value->item_id);
                    $autoPart->increment('balance', $value->quantity);

                    Notifications::createNotification(
                        $value->created_by, // Notifying each fleet manager
                        'Auto Part Inventory',
                        'Auto part (' . $autoPart->name . ') stock quantity has been updated by ' . $value->quantity
                    );
                }
            }
        }

        ///CHECK IF FULL AMOUNT HAS BEEN PAID
        if ($pay_req->amount_paid ==  $pay_req->amount) {
            $pay_req->status = 'paid';
            $pay_req->save();
        }


        return back()->with('success', $pay_req->payment_type . ' payment processed successfully !',);
    }


    //private function 
    private function updateInvoiceStatusPaidOrPartiallyPaid($pay_req)
    {
        $paid_status = false;
        $invoice = Invoice::find($pay_req->invoice_no);
        $invoice->fin_status = 'partially paid';
        $invoice->status = 'partially paid';
        $invoice->save();

        if ($pay_req->amount_paid ==  $pay_req->amount) {
            $invoice->fin_status = 'paid';
            $invoice->status = 'paid';
            $invoice->save();
            $paid_status = true;
        }

        return $paid_status;
    }
}
