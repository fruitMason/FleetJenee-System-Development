<?php

namespace App\Http\Controllers\Account;


use Carbon\Carbon;
use App\Models\Tax;
use App\Models\Vendor;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CarMaintenance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\DataTables\InvoiceDataTable;
use App\DataTables\v1\AccountsWorkOrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\AccidentReport;
use App\Models\Car;
use App\Models\Payment;
use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AccountDashboardController extends Controller
{

    public function dashboard(Request $request)
    {
        $total_invoice = 20; //WorkOrder::query()->count();
        $currentYear = date('Y');
        $threeYearsAgo = $currentYear - 3;
        $old_cars = Car::where(DB::raw("SUBSTRING(year, 1, 4)"), '<=', $threeYearsAgo)->count();

        $data = User::selectRaw("UPPER(left(first_name,1)) as initial,count(*) as total")
            ->groupBy('initial')->orderBy('initial')->pluck('initial', 'total');

        $pay_requests_count = PaymentRequest::whereColumn('amount', '>', 'amount_paid')->count();
        $pay_requests_amount = PaymentRequest::whereColumn('amount', '>', 'amount_paid')->where('status', '<>', 'declined')
            ->selectRaw('SUM(amount - amount_paid) as total')
            ->value('total');

        $pending_orders = CarMaintenance::where('fin_status', 'pending')->count();

        $pending_invoices = Invoice::where('status', 'pending')->count();
        $pending_invoices_cost = Invoice::select()->where('status', 'pending')->sum('net_total');

        $year = date('Y');
        $insurance_y2d = Payment::join('payment_requests as req', 'req.id', '=', 'payments.payment_request_id')
            ->where('payment_type', 'insurance')->whereYear('payment_date', $year)
            ->sum('payments.amount_paid');

        $maintenance_y2d = Payment::join('payment_requests as req', 'req.id', '=', 'payments.payment_request_id')
            ->where('payment_type', 'maintenance')->whereYear('payment_date', $year)
            ->sum('payments.amount_paid');

        $road_worthy_y2d = Payment::join('payment_requests as req', 'req.id', '=', 'payments.payment_request_id')
            ->where('payment_type', 'road worthy')->whereYear('payment_date', $year)
            ->sum('payments.amount_paid');

        $fuel_y2d = Payment::join('payment_requests as req', 'req.id', '=', 'payments.payment_request_id')
            ->where('payment_type', 'fuel')->whereYear('payment_date', $year)
            ->sum('payments.amount_paid');

        $others_y2d = Payment::join('payment_requests as req', 'req.id', '=', 'payments.payment_request_id')
            ->where('payment_type', 'other')->whereYear('payment_date', $year)
            ->sum('payments.amount_paid');

        $department_cost = Payment::select(
            DB::raw("SUM(payments.amount_paid) as total_amount_paid"),
            'dep.name as department_name'
        )
            ->join('payment_requests as req', 'payments.payment_request_id', '=', 'req.id')
            ->join('users', 'req.for_user_id', '=', 'users.id')
            ->join('departments as dep', 'users.department_id', '=', 'dep.id')
            ->whereYear('payment_date', $year)
            ->where('payments.payment_status', 'paid')
            ->groupBy('dep.id', 'dep.name') // Group by both id and name for consistency
            ->get();

        $mechanic_cost = Payment::select(
            DB::raw("SUM(payments.amount_paid) as total_amount_paid"),
            'ven.name as vendor_name'
        )
            ->join('payment_requests as req', 'payments.payment_request_id', '=', 'req.id')
            ->join('invoices as i', 'req.invoice_no', '=', 'i.id')
            ->join('vendors as ven', 'i.vendor_id', '=', 'ven.id')
            ->whereYear('payment_date', $year)
            ->where('payments.payment_status', 'paid')
            ->groupBy('ven.id', 'ven.name') // Group by both id and name for consistency
            ->get();



        $maintenance_line =  $this->maintenanceData();
        $all_expenses_month_on_month =  $this->allPaymentTypes();




        return view('accounts.dashboard', [
            'pending_invoices' => $pending_invoices,
            'pending_orders' => $pending_orders,
            'total_invoice' => $total_invoice,
            'pending_invoices_cost' => $pending_invoices_cost,
            'pending_requests' => $pay_requests_count,
            'pending_requests_amount' => $pay_requests_amount,
            'old_cars' => $old_cars,
            'insurance_y2d' => $insurance_y2d,
            'maintenance_y2d' => $maintenance_y2d,
            'road_worthy_y2d' => $road_worthy_y2d,
            'fuel_y2d' => $fuel_y2d,
            'other_y2d' => $others_y2d,
            'department_cost' => $department_cost,
            'maintenance_line' => $maintenance_line,
            'all_expenses_month_on_month' => $all_expenses_month_on_month,
            'mechanic_cost' =>               $mechanic_cost
        ]);
    }

    protected function maintenanceData()
    {
        $year = date('Y');
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        $payments = Payment::select(
            DB::raw("DATE_FORMAT(payment_date, '%M') as month"),
            DB::raw("SUM(amount_paid) as amount")
        )
            ->whereYear('payment_date', $year)
            ->groupBy('month')
            ->orderBy(DB::raw("MONTH(payment_date)"))
            ->get()
            ->keyBy('month');

        return collect($months)->map(function ($month) use ($payments) {
            return (object)[
                'month' => $month,
                'amount' => $payments->has($month) ? $payments[$month]->amount : 0
            ];
        });
    }

    protected function allPaymentTypes()
    {
        $year = date('Y');


        $query =  Payment::select([
            DB::raw('YEAR(payments.payment_date) as year'),
            DB::raw('MONTH(payments.payment_date) as month'),
            DB::raw("SUM(payments.amount_paid) as total"),
            'req.payment_type'
        ])
            ->join('payment_requests as req', 'req.id', '=', 'payments.payment_request_id')
            ->where('payments.payment_status', 'paid')
            ->orderBy('month')
            ->groupBy('year', 'month', 'payment_type');




        return $query->get();
    }

    // protected function maintenanceData()
    // {
    //     // First get all payments grouped by month
    //     $payments = Payment::select(
    //         DB::raw("DATE_FORMAT(payment_date, '%M') as month"),
    //         DB::raw("MONTH(payment_date) as month_num"),
    //         DB::raw("SUM(amount_paid) as amount")
    //     )
    //         ->groupBy('month', 'month_num')
    //         ->orderBy('month_num')
    //         ->get();

    //     // Find the last month with transactions
    //     $lastMonthWithPayment = $payments->sortByDesc('month_num')->first();
    //     $lastMonthNumber = $lastMonthWithPayment ? $lastMonthWithPayment->month_num : null;

    //     // Define all months
    //     $allMonths = [
    //         1 => 'January',
    //         2 => 'February',
    //         3 => 'March',
    //         4 => 'April',
    //         5 => 'May',
    //         6 => 'June',
    //         7 => 'July',
    //         8 => 'August',
    //         9 => 'September',
    //         10 => 'October',
    //         11 => 'November',
    //         12 => 'December'
    //     ];

    //     // Filter months up to the last month with payment
    //     $monthsToShow = $allMonths;
    //     if ($lastMonthNumber) {
    //         $monthsToShow = array_filter($allMonths, function ($monthNum) use ($lastMonthNumber) {
    //             return $monthNum <= $lastMonthNumber;
    //         }, ARRAY_FILTER_USE_KEY);
    //     }

    //     // Create the final collection
    //     return collect($monthsToShow)->map(function ($monthName, $monthNum) use ($payments) {
    //         $payment = $payments->firstWhere('month', $monthName);
    //         return (object)[
    //             'month' => $monthName,
    //             'amount' => $payment ? $payment->amount : 0
    //         ];
    //     });
    // }


    public function showInvoice(Request $request, InvoiceDataTable $dataTable)
    {
        $invoices = Invoice::all();
        return $dataTable->render('finance.invoice.index', ['invoices' => $invoices]);
    }

    public function invoice(Request $request, InvoiceDataTable $dataTable)
    {
        $invoices = Invoice::all();
        return $dataTable->render('accounts.invoice', ['invoices' => $invoices]);
    }



    public function payments(Request $request, InvoiceDataTable $dataTable)
    {
        $invoices = Invoice::all();
        return $dataTable->render('accounts.payments', [
            'invoices' => $invoices
        ]);
    }

    public function showCreateInvoice(Request $request)
    {
        //        $vendors = Vendor::query()->whereHas('region', function ($q){
        //            $q->whereBelongsTo(auth()->user()->getSector(), 'sector');
        //        })->get();
        $vendors = Vendor::all();
        $taxes = Tax::all();
        $maintenances = CarMaintenance::query()->with(['car'])->get();

        return view('finance.invoice.create', [
            'vendors' => $vendors,
            'taxes' => $taxes,
            'maintenances' => $maintenances
        ]);
    }

    public function storeInvoice(Request $request)
    {

        $_user = Auth::user(); // auth()->user();

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

            if ($request->filled('maintenance_id')) {
                CarMaintenance::query()->find($request->maintenance_id)->update(['invoice_id' => $invoice->id]);
            }

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
        return view('finance.invoice.view', [
            'data' => $invoice
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
            'created_by' => Auth::user()->id, // auth()->id(),
            'edited_by' => Auth::user()->id, // auth()->id(),
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
}
