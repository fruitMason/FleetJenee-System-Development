<?php

namespace App\Http\Controllers\Account;

use App\DataTables\Account\PaymentHistoryDataTable;
use App\DataTables\Account\PaymentRequestsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Invoice;
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

        $payment_mode  = ['Cash', 'Cheque', 'Coupon', 'Bank Transfer', 'MoMo'];
        return view('accounts.payments.process-payment', [
            'request' => $request,
            'payment_mode' => $payment_mode
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
            throw ValidationException::withMessages(['error_field' => 'Payment request is full paid.']);
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
            $invoice = Invoice::find($pay_req->invoice_no);
            $invoice->fin_status = 'partially paid';
            $invoice->status = 'partially paid';
            $invoice->save();

            if ($pay_req->amount_paid ==  $pay_req->amount) {
                $invoice->fin_status = 'paid';
                $invoice->status = 'paid';
                $invoice->save();
            }

            //
            $car = Car::find($pay_req->car_id);
            if ($car->odometer_status == 'Overdue') {
                $car->odometer_level = $car->odometer_level + OdometerSetting::find(1)->value;
                $car->odometer_status = 'Active';
                $car->save();
            }
        }

        //check if full amount has been paid
        if ($pay_req->amount_paid ==  $pay_req->amount) {
            $pay_req->status = 'paid';
            $pay_req->save();
        }


        return back()->with('success', $pay_req->payment_type . ' payment processed successfully !',);
    }
}
