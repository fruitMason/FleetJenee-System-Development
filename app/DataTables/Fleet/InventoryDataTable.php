<?php

namespace App\DataTables\Fleet;

use App\Models\AutoPart;
use App\Models\AutoPartPurchase;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query) //


            ->addColumn('name', function ($row) {
                return ucwords($row->name);
            })
            ->addColumn('unit_cost', function ($row) {
                return number_format($row->unit_cost, 2);
            })
            ->addColumn('qnt_available', function ($row) {
                return $row->balance;
            })


            ->addColumn('action', function ($row) {
                if ($row->fin_status == 'pending') {
                    return
                        '<div class="btn-group mr-3" role="group" aria-label="Action buttons">' .
                        '<a class="btn btn-xs btn-primary" href="' . route('inventory.show', $row->id) . '">
                            <i class="fa fa-eye text-white" aria-hidden="true"></i>
                        </a>' .

                        '</div>';
                }

                return
                    '<div class="btn-group mr-3" role="group" aria-label="Action buttons">' .
                    '<a class="btn btn-xs btn-primary" href="' . route('inventory.show', $row->id) . '">
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
        $applied_status = 'available';
        Log::info($status);
        if ($request->input('filter')) {
            $applied_status =  $status;
            Log::info('applied_status ' . $applied_status);
            if ($applied_status == 'available') {
                $query =   AutoPart::query()->where('balance', '>', 0);
            } else {
                $query =   AutoPart::query()->where('balance', 0);
            }
        } else {
            $query = AutoPart::query()->where('balance', '>', 0);
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

            Column::make('name'),
            Column::make('unit_cost'),
            Column::make('qnt_available'),
            Column::computed('action')->addClass('text-center no-border')
        ];
    }
}
