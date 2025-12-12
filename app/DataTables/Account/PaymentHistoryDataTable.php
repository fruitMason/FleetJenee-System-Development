<?php

namespace App\DataTables\Account;

use App\Models\Payment;
use App\Models\PaymentRequest;
use Carbon\Carbon;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class PaymentHistoryDataTable extends DataTable
{

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query) //
            ->addColumn('created_at', function ($row) {
                return $row->created_at ? Carbon::parse($row->created_at)->format('d F Y H:i A') : 'N/A';
            })
            ->addColumn('payment_date', function ($row) {
                return  $row->payment_date ? Carbon::parse($row->payment_date)->format('d F Y') : 'N/A';
            })
            ->addColumn('car', function ($row) {
                return $row->model . ', ' . $row->year .    ' , ' . $row->car_number;
            })
            ->addColumn('payment_type', function ($row) {
                return ucwords($row->payment_type);
            })
            ->addColumn('payment_mode', function ($row) {
                return ucwords($row->payment_mode);
            })
            ->addColumn('amount_paid', function ($row) {
                return number_format($row->amount_paid, 2);
            })
            ->addColumn('reference', function ($row) {
                return $row->payment_reference;
            })
            ->addColumn('narration', function ($row) {
                return $row->narration;
            })

            ->addColumn('in_favor_of', function ($row) {
                $name = $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name;
                return trim(str_replace('  ', ' ', ucwords($name)));
            })
            ->addColumn('action', function ($row) {

                if ($row->status == 'pending' || $row->status == 'partially paid') {
                    return
                        '<a class="btn btn-success text-white btn-sm" href="' . route('accounts.payment.process.payment', $row->id) . '">'
                        . '<span class="badge badge-light"><i class="fas fa-credit-card"></i></span>' .
                        'Pay' .
                        '</a>';
                }
                return 'Paid !';
            })
            ->rawColumns(['checkbox', 'status', 'fin_status', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Request $request)
    {
        $type = $request->input('type') ?? '';

        if ($request->input('type')) {
            $type = $request->input('type') ?? '';
        }
        $query = Payment::select(
            'payments.payment_date',
            'payments.amount_paid',
            'req.payment_type',
            'payments.payment_mode',
            'payments.payment_reference',
            'payments.narration',
            'payments.payment_status',
            'payments.created_at',
            'u.first_name',
            'u.middle_name',
            'u.last_name',
            'cars.model',
            'cars.year',
            'cars.car_number',
        )


            ->join('payment_requests as req', 'req.id', '=', 'payments.payment_request_id')
            ->join('users as u', 'u.id', '=', 'req.for_user_id')
            ->join('cars', 'cars.id', '=', 'req.car_id')
            ->where('payments.payment_status', 'paid')
            ->where('req.payment_type', 'like', '%' . $type . '%')
            ->orderByDesc('payments.payment_date');



        return $this->applyScopes($query);
    }


    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->serverSide(true)
            ->processing(true)
            ->orderBy(0)
            ->parameters([
                'buttons' => [
                    'csv',   // Include CSV export
                    'excel', // Include Excel export
                    'print', // Include Print button
                    'reload' // Include Reload button
                    // PDF export button is removed
                ],
                'dom' => "<'row'>l<'/row'>Bfrtip",
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('created_at'),
            Column::make('payment_date'),
            Column::make('car'),
            Column::make('payment_type'),
            Column::make('payment_mode'),
            Column::make('amount_paid'),
            Column::make('reference'),
            Column::make('in_favor_of'),
            Column::make('narration'),
            Column::computed('action')->addClass('text-center no-border')
        ];
    }


    protected function filename(): string
    {
        return 'PaymentHistoryDataTable_' . date('YmdHis');
    }
}
