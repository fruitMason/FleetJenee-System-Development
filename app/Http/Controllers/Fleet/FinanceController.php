<?php

namespace App\Http\Controllers\Fleet;

use App\DataTables\InvoiceDataTable;
use App\Helpers\Notifications;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarMaintenance;
use App\Models\CarMaintenanceNote;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PaymentRequest;
use App\Models\Tax;
use App\Models\User;
use App\Models\Vendor;
use App\Traits\GlobalValueCore;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;



class FinanceController extends Controller
{
    use GlobalValueCore;
    public function showInvoice(Request $request, InvoiceDataTable $dataTable)
    {
        $invoices = Invoice::all();
        $users =  User::select('id', 'first_name', 'middle_name', 'last_name', 'type')->where('type', '<>', 'MECHANIC')->orderBy('first_name')->get();


        return $dataTable->render('finance.invoice.index', [
            'invoices' => $invoices,
            'users' => $users
        ]);
    }

    public function showCreateInvoice(Request $request)
    {
        //        $vendors = Vendor::query()->whereHas('region', function ($q){
        //            $q->whereBelongsTo(auth()->user()->getSector(), 'sector');
        //        })->get();
        $vendors = Vendor::all();
        $taxes = Tax::all();
        $maintenances = CarMaintenance::query()->with(['car'])->where('status','<>','completed')->get();

        return view('finance.invoice.create', [
            'vendors' => $vendors,
            'taxes' => $taxes,
            'maintenances' => $maintenances
        ]);
    }

    public function storeInvoice(Request $request)
    {

        $_user = Auth::user();

        $data = $request->all();

        $validate = Validator::make($data, $this->validateRules($data));

        if ($validate->fails()) {
            return response()->json(['code' => 401, 'message' => 'The data given is invalid.',  'errors' => $validate->errors()->all()]);
        }

        if (count($request->product_rows) === 0) {
            return response()->json(['message' => 'Invoice Items can\'t be empty. Please select one or more line items']);
        }



        DB::beginTransaction();

        try {

            $invoice_data = $this->dumpInvoiceData($data, 'create');

            $invoice = Invoice::query()->create($invoice_data);
            $selected_taxes = [];

            foreach ($data['product_rows'] as $row_from_grid) {
                $invoice_item_data = $this->dumpInvoiceItems($row_from_grid);

                $nhil = false;
                $getfund = false;
                $covid = false;
                $taxes = $invoice_item_data['selected_tax_ids_from_grid'] ?? [];

                foreach ($taxes as $tax) {
                    $invoice_item_data['taxed'] = true;
                    $stax = Tax::query()->find($tax);
                    if ($stax) {

                        $tax_name = strtolower($stax->name);

                        if (Str::contains($tax_name, ['cst', 'cst tax'])) {
                            $invoice_item_data['is_cst'] = 1;
                        }

                        if (Str::contains($tax_name, ['vat flat', 'flat vat', 'vat flax tax', 'vat flat tax'])) {
                            $invoice_item_data['is_vat_flat'] = 1;
                        }

                        if (Str::contains($tax_name, ['nhil']))
                            $nhil = true;

                        if (Str::contains($tax_name, ['getfund']))
                            $getfund = true;

                        if (Str::contains($tax_name, ['covid', 'covid 19 tax', 'covid 19', 'covid levy']))
                            $covid = true;


                        if ($nhil && $getfund && $covid)
                            $invoice_item_data['is_vat_standard'] = 1;
                    }
                }

                $invoice_item_data['total'] = $invoice_item_data['price'] * ($invoice_item_data['quantity'] == 0 ? 1 : $invoice_item_data['quantity']);

                $row_from_grid['selected_tax_ids_from_grid'] = $row_from_grid['selected_tax_ids_from_grid'] ?? [];
                $_selected_taxes = json_encode($row_from_grid['selected_tax_ids_from_grid']);

                $invoice_item_data['invoice_id'] = $invoice->id;
                $invoice_item = InvoiceItem::query()->create($invoice_item_data);
                $invoice_item->update([
                    'tax_amount' => array_key_exists('tax_amount', $row_from_grid) ? $row_from_grid['tax_amount'] : 0,
                    'total' => $row_from_grid['amount'],
                    'selected_taxes' => $_selected_taxes
                ]);
            }

            $invoice->update([
                'sub_total' => $data['sub_total'],
                'tax_total' => $data['total_applied_tax'],
                'net_total' => $data['net_total'],
            ]);

            $selected_taxes['vat_total'] = $data['vat_total'];
            $selected_taxes['vat_flat_total'] = $data['vat_flat_total'];
            $selected_taxes['getfund_total'] = $data['getfund_total'];
            $selected_taxes['nhil_total'] = $data['nhil_total'];
            $selected_taxes['covid_total'] = $data['covid_total'];
            $selected_taxes['cst_total'] = $data['cst_total'];
            $invoice->update($selected_taxes);


            Log::info($data);

            //this is no longer used --:: there are be multiple invoices from one work order
            // if ($request->filled('maintenance_id')) {
            //     Log::info($request->maintenance_id);
            //     CarMaintenance::where('id', $request->maintenance_id)->update(['invoice_id' => $invoice->id]);
            //     Log::info('maintenance updated');
            // }

            // Log::info($request->all());
            // return $request->all();
            // insert into car_maintenance notes
            $note = CarMaintenanceNote::create([
                'car_maintenance_id' => $request->maintenance_id,
                'status' => 'Invoice Generated',
                'receipt_comment' => 'Invoice Generated. Msg: ' . $request->message . '. Ref: ' . $request->reference,
                'receipt_date' => Carbon::now(),
                'user_email' => $request->user()->id,
            ]);

            DB::commit();

            $request->session()->flash('success', 'Invoice was ADDED successfully!');

            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function viewInvoice(Request $request, $id)
    {
        $invoice = Invoice::query()->with(['invoiceItem'])->find($id);
        $work_order = CarMaintenance::find($invoice->car_maintenance_id);

        return view('finance.invoice.view', [
            'data' => $invoice,
            'work_data' =>  $work_order
        ]);
    }

    public function getBatchNo($int = 1)
    {
        $batch = "INV" . sprintf('%06d', Invoice::query()->withTrashed()->count() + $int);
        $exist = Invoice::query()->withTrashed()->where('invoice_number', $batch)->first();
        return empty($exist) ? $batch : $this->getBatchNo(++$int);
    }

    public function dumpInvoiceData($data, $type)
    {

        if ($data['invoice_number_type'] == 'auto')
            $invoice_number = $this->getBatchNo();
        else
            $invoice_number = $data['invoice_number'];

        return [
            'vendor_id' => $data['vendor_id'],
            'invoice_number_type' => $data['invoice_number_type'],
            'invoice_number' => $invoice_number,
            'due_date' => Carbon::createFromFormat('d/m/Y', $data['due_date'])->toDateString(),
            'reference' => $data['reference'],
            'message' => $data['message'],
            'sub_total' => $data['sub_total'],
            'net_total' => $data['net_total'],
            'tax_total' => $data['total_applied_tax'],
            'status' => 'pending',
            'nhil_total' => $data['nhil_total'],
            'cst_total' => $data['cst_total'],
            'getfund_total' => $data['getfund_total'],
            'vat_total' => $data['vat_total'],
            'covid_total' => $data['covid_total'],
            'vat_flat_total' => $data['vat_flat_total'],
            'created_by' => Auth::user()->id,
            'edited_by' => Auth::user()->id,
            'car_maintenance_id' => $data['maintenance_id']
        ];
    }

    public function validateRules($data)
    {
        return [
            'vendor_id' => 'required|exists:vendors,id',
            'invoice_number' => 'nullable',
            'reference' => 'nullable',
            'message' => 'nullable',
            'due_date' => 'required',
            'initial_sales_date' => 'nullable',
            'sub_total' => 'required',
            'net_total' => 'required',
        ];
    }

    public function dumpInvoiceItems($row)
    {
        if (key_exists('selected_tax_ids_from_grid', $row)) {
            $selected_taxes = json_encode($row['selected_tax_ids_from_grid']);
        } else {
            $selected_taxes = null;
        }

        return [
            'item_id' => $row['selected_product_id'] ?? 0,
            'price' => $row['unit_price'],
            'quantity' => $row['quantity'] ?? 0,
            'total' => $row['amount'],
            'item_type' => $row['item_type'] ?? 'car',
            'selected_taxes' => $selected_taxes,
            'description' => $row['description'],
            'tax_amount' => array_key_exists('tax_amount', $row) ? $row['tax_amount'] : 0,
            'vat' => $row['VAT'] ?? 0,
            'nhil' => $row['NHIL'] ?? 0,
            'getfund' => $row['GETFund'] ?? 0,
            'cst' => $row['CST'] ?? 0,
        ];
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:invoices,id',
            'status' => 'required|in:pending,paid,partially_paid',
        ]);

        $invoice = Invoice::find($request->id);

        if ($invoice) {
            $invoice->status = $request->status;
            $invoice->save();

            return redirect()->back()->with('success', 'Invoice Status UPDATED Successfully!');
        }

        return redirect()->back()->with('error', 'Invoice not found.');
    }

    public function createSubmitToFinance(Invoice $invoice)
    {
        $users =  User::select('id', 'first_name', 'middle_name', 'last_name', 'type')
            ->where('type', '<>', 'MECHANIC')->orderBy('first_name')->get();

        return view(
            'main_fleet.finance_requests.push-invoice-to-finance',
            ['invoice' => $invoice, 'users' => $users]
        );
    }

    public function submitToFinance(Request $request)
    {

        $request->validate([
            'tidnew' => 'required|exists:invoices,id'
        ]);
        $notify_user = $request->notify_user;
        $invoice = Invoice::find($request->tidnew);
        //Log::info($invoice);
        $work_order = CarMaintenance::find($invoice->car_maintenance_id);
        //Log::info($work_order);
        $SMSStatus = "No SMS";
        if ($notify_user) {
            $user =  User::find($notify_user);
            $formattedDate = Carbon::parse($invoice->due_date)->format('d/m/Y');
            $car = $work_order->car->model . ' (' . $work_order->car->car_number . ')';
            $amount = 'GHS ' . number_format($invoice->net_total, 2);
            $message = 'New invoice ' . $invoice->invoice_number . ' generated for ' . $car . ' - ' . $invoice->vendor->name . '. Amount : ' . $amount . '. Due ' . $formattedDate . '. Kindly process payment.';
            $naloMes = str_replace(' ', '+', $message);
            try {

                Notifications::createNotification($user->user_id, 'Finance Request - Invoice', $message);

                $SMSStatus =    "SMS Send To " . $user->mobile;
                GlobalValueCore::SendSMS_ViaHubtelAPI($user->mobile, $naloMes);
            } catch (\Exception $e) {
                $SMSStatus = $SMSStatus . "|| sms not sent : " . $e->getMessage();
                Log::error('sms-error' . $e->getMessage());
            }
        }





        if (DB::table('payment_requests')->where('description', 'Invoice Payment Request. Invoice No: ' . $invoice->invoice_number)->exists()) {
            return redirect()->back()->with('error', 'Invoice Already Subitted To Finance !!');
        }

        $car = Car::find($work_order->car_id);
        $user = "1";
        $user_ass = "Unassigned";
        if ($car->user) {
            $user = $car->user->id;
            $user_ass = "Assigned";
        }



        if ($invoice) {
            $invoice->fin_status = 'pending';
            $invoice->save();

            //create payment request for account
            PaymentRequest::create([
                'user_id' => $request->user()->id,
                'payment_type' => 'maintenance',
                'description' => 'Invoice Payment Request. Invoice No: ' . $invoice->invoice_number,
                'amount' => $invoice->net_total,
                'status' => 'pending',
                'amount_paid' => 0,
                'for_user_id' => $user,
                'request_date' => now(),
                'car_id' => $work_order->car_id,
                'car_assigned' => $user_ass,
                'invoice_no' => $invoice->id

            ]);

            // return redirect()->back()->with('success', 'Invoice Submitted To Finance!');
        }
        return redirect()->route('finance.invoice.index')->with('success', 'Invoice forwarded to Finance successfully [' . $SMSStatus . ']!');
    }



    public function printInvoice(Request $request, $id)
    {
        set_time_limit(1000); // Increase execution time limit
        $item = Invoice::query()->with(['invoiceItem'])->find($id);
        if (!$item) {
            return redirect()->back()->with('error', 'Invoice not found.');
        }
        view()->share('data', $item);
        $pdf = PDF::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'enable-local-file-access' => true
        ])->loadView('pdfview')->setPaper('A4');

        return $pdf->stream($item->invoice_number . '.pdf');
    }

    // public function storeUsersBulk(Request $request)
    // {
    //     $_user = auth()->user();
    //     if ($request->isMethod('POST')) {
    //         if ($request->hasFile("file")) {

    //             $file = $request->file('file');
    //             try {
    //                 Excel::import(new UserImport(), $file);
    //             } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
    //                 $failures = $e->failures();
    //                 return Redirect::back()->with(['errors' => $e->validator->errors()]);
    //             }
    //             return back()->with('success', 'Users have been imported successfully!');

    //         }
    //         return back()->withErrors(['msg' => 'Upload a file!']);
    //     }
    // }



    // public function workOrderByVendor(Request $request)
    // {
    //     $vendorId = $request->input('vendor_id');

    //     //   {{ $maintenance->car->model }}
    //     //                                         ({{ $maintenance->car->car_number }})
    //     //                                         -
    //     //                                         {{ $maintenance->start_date->format('d-m-Y') }}
    //     //                                         [{{ $maintenance->comment }}]

    //     $items = CarMaintenance::where('mechanic_id', $vendorId)
    //         ->join('cars as car', 'car.id', '=', 'car_maintenances.car_id')
    //         ->select(
    //             'car_maintenances.id',
    //             'car_maintenances.comment',
    //             'car_maintenances.start_date',
    //             'car.model',
    //             'car.car_number'
    //         ) // Only get what we need
    //         ->get();

    //     return response()->json($items);
    // }
}
