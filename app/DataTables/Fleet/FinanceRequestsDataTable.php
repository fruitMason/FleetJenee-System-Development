<?php

namespace App\DataTables\Fleet;

use App\Models\PaymentRequest;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceRequestsDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query) //

            ->addColumn('request_date', function ($row) {
                return  $row->request_date ? Carbon::parse($row->request_date)->format('d F Y') : 'N/A';
            })
            ->addColumn('car', function ($row) {
                return $row->car->car_features();
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

            ->addColumn('in_favor_of', function ($row) {
                return !is_null($row->for_user) ? $row->for_user->full_name() . ' ('.$row->car_assigned.')'  : 'N/A';
            })
            ->addColumn('action', function ($row) {
                // if (Auth::user()->hasRole('FLEET MANAGER')) {
                //     if ($row->status == 'pending' || $row->status == 'partially paid') {
                //         return
                //             '<a class="btn btn-success text-white btn-sm" href="' . route('accounts.payment.process.payment', $row->id) . '">'
                //             . '<span class="badge badge-light"><i class="fas fa-credit-card"></i></span>' .
                //             'Approve' .
                //             '</a>';
                //     }
                // }else{

                // }
                if ($row->status == 'pending') {
                    return '<div class="text-danger">' . ucwords($row->status) . "</div>";
                } else if ($row->status == 'paid') {
                    return '<div class="text-success">' . ucwords($row->status) . "</div>";
                }
                return '<div class="text-info">' . ucwords($row->status) . "</div>";
            })
            ->rawColumns(['checkbox', 'status', 'fin_status', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Request $request)
    {
        $status = $request->input('filter');
        $applied_status = 'declined';


        if ($request->input('filter')) {
            $applied_status =  $status;
            $query = PaymentRequest::query()
                ->with(['user'])
                ->where('status', $applied_status)->orderByDesc('created_at');
        } else {
            $query = PaymentRequest::query()
                ->with(['user'])->whereColumn('amount', '>', 'amount_paid')
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
            Column::make('in_favor_of'),
            Column::computed('action')->addClass('text-center no-border')
        ];
    }
}
