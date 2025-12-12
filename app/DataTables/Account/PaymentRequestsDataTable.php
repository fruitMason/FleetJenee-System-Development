<?php

namespace App\DataTables\Account;

use App\Models\PaymentRequest;
use Carbon\Carbon;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class PaymentRequestsDataTable extends DataTable
{

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query) //
            // ->addColumn('created_at', function ($row) {
            //     return $row->created_at ? Carbon::parse($row->created_at)->format('d F Y H:i A') : 'N/A';
            // })
            ->addColumn('request_date', function ($row) {
                return  $row->request_date ? Carbon::parse($row->request_date)->format('d F Y') : 'N/A';
            })
            ->addColumn('car', function ($row) {
                return $row->car? $row->car->car_features() : 'N/A';
            })
            ->addColumn('payment_type', function ($row) {
                return ucwords($row->payment_type);
            })
            ->addColumn('amount', function ($row) {
                return number_format($row->amount, 2);
            })
            ->addColumn('amount_paid', function ($row) {
                return number_format($row->amount_paid, 2);
            })
            ->addColumn('description', function ($row) {
                return $row->description;
            })
            ->addColumn('status', function ($row) {
                if ($row->status == 'paid') {
                    return ' <span class="badge bg-inverse-success">' . ucwords($row->status) . '</span>';
                }
                if ($row->status == 'pending') {
                    return '<span class="badge bg-inverse-info">' . ucwords($row->status) . '</span>';
                }
                if ($row->status == 'partialy paid') {
                    return '<span class="badge bg-inverse-warning">' . ucwords($row->status) . '</span>';
                }
                return '<span class="badge bg-dark">' . ucwords($row->status) . '</span>';
            })
            ->addColumn('requested_by', function ($row) {
                return !is_null($row->user) ? $row->user->full_name()  : 'N/A';
            })
            ->addColumn('in_favor_of', function ($row) {
                return !is_null($row->for_user) ? $row->for_user->full_name()  : 'N/A';
            })
            ->addColumn('action', function ($row) {

                if ($row->status == 'pending' || $row->status == 'partially paid') {
                    return
                        '<a class="btn btn-success text-white btn-sm" href="' . route('accounts.payment.process.payment', $row->id) . '">'
                        . '<span class="badge badge-light"><i class="fas fa-credit-card"></i></span>' .
                        'Pay' .
                        '</a>'
                    ;
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
        $status = $request->input('filter');
        $type = $request->input('type') ?? '';
        $applied_status = 'declined';

        if ($request->input('filter')) {
            $applied_status =  $status;
            $query = PaymentRequest::query()
                ->with(['user'])
                ->where('status', $applied_status)
                ->where('payment_type', 'like', '%' . $type . '%')
                ->orderByDesc('created_at');
        } else {
            $query = PaymentRequest::query()
                ->with(['user'])->whereColumn('amount', '>', 'amount_paid')
                ->where('payment_type', 'like', '%' . $type . '%')
                ->where('status', '<>', $applied_status)->orderByDesc('created_at');
        }




        return $this->applyScopes($query);


        // $query = PaymentRequest::query()
        //     ->with(['user'])->orderByDesc('created_at');

        // if ($request->input('filter')) {
        //     $query->where('status', $status);
        // }
        // return $this->applyScopes($query);
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
            Column::make('request_date'),
            Column::make('car'),
            Column::make('payment_type'),
            Column::make('amount'),
            Column::make('amount_paid'),
            Column::make('description'),
            Column::make('status'),
            Column::make('requested_by'),
            Column::computed('action')->addClass('text-center no-border')
        ];
    }


    protected function filename(): string
    {
        return 'PaymentRequestsDataTable_' . date('YmdHis');
    }
}
