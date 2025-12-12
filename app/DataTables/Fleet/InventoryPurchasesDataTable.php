<?php

namespace App\DataTables\Fleet;

use App\Models\AutoPartPurchase; 
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;
use Illuminate\Http\Request;


class InventoryPurchasesDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query) //

            ->addColumn('request_date', function ($row) {
                return  $row->request_date ? Carbon::parse($row->request_date)->format('d F Y') : 'N/A';
            })
            ->addColumn('reference', function ($row) {
                return ucwords($row->reference);
            })
            ->addColumn('request_amount', function ($row) {
                return number_format($row->net_amount, 2);
            })

            ->addColumn('description', function ($row) {
                return $row->message;
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
            ->addColumn('fin_status', function ($row) {
                if ($row->fin_status == 'pending') {
                    return '<div class="text-danger">' . ucwords($row->fin_status) . "</div>";
                } else if ($row->fin_status == 'paid') {
                    return '<div class="text-success">' . ucwords($row->fin_status) . "</div>";
                }
                return '<div class="text-info">' . ucwords($row->fin_status) . "</div>";
            })


            ->addColumn('action', function ($row) {
                if ($row->fin_status == 'pending') {
                    return
                        '<div class="btn-group mr-3" role="group" aria-label="Action buttons">' .
                        '<a class="btn btn-xs btn-primary" href="' . route('inventory.purchase.show', $row->id) . '">
                            <i class="fa fa-eye text-white" aria-hidden="true"></i>
                        </a>' .
                        '<form method="post" action="' . route('auto.parts.destroy', $row->id) . '"
                            onsubmit="return SubmitDelete(this,\'Delete Auto Part [' . $row->name . ']\');" >' .
                        csrf_field() .
                        method_field('DELETE') .

                        '<button type="submit" class="btn btn-xs btn-danger" href="#" onclick="archiveNotify(' . $row->id . ')">
                            <i class="fa fa-trash text-white" aria-hidden="true"></i>
                            </button>' .
                        '</form>' .
                        '</div>';
                }

                return
                    '<div class="btn-group mr-3" role="group" aria-label="Action buttons">' .
                    '<a class="btn btn-xs btn-primary" href="' . route('inventory.purchase.show', $row->id) . '">
                        <i class="fa fa-eye text-white" aria-hidden="true"></i>
                    </a>'  .

                    '</div>';
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
            $query =
                AutoPartPurchase::query()
                ->with(['user'])
                ->where('status', $applied_status)->orderByDesc('created_at');
        } else {
            $query = AutoPartPurchase::query()
                ->with(['user']) //->whereColumn('amount', '>', 'amount_paid')
                ->where('status', '<>', $applied_status)->orderByDesc('created_at');
        }

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
            Column::make('request_date'),
            Column::make('reference'),
            Column::make('request_amount'),


            Column::make('description'),
            Column::make('status'),
            Column::make('fin_status'),
            Column::computed('action')->addClass('text-center no-border')
        ];
    }
}
