<?php

namespace App\DataTables;

use App\Models\CarMaintenance;
use Carbon\Carbon;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class VehicleMaintenanceDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('created_at', function ($row) {
                return $row->created_at ? Carbon::parse($row->created_at)->format('d F Y H:i A') : 'N/A';
            })
            ->editColumn('start_date', function ($row) {
                return Carbon::parse($row->start_date)->format('d F Y') ?? 'N/A';
            })
            ->editColumn('end_date', function ($row) {
                return $row->end_date ? Carbon::parse($row->end_date)->format('d F Y') : 'N/A';
            })
            ->addColumn('car', function ($row) {
                return $row->car ? $row->car->model . ' (' . $row->car->car_number . ')' : 'N/A';
            })
            ->addColumn('mechanic', function ($row) {
                return $row->mechanic ? $row->mechanic->full_name() : 'N/A';
            })
            ->addColumn('fin_status', function ($row) {
                if ($row->fin_status == 'approved') {
                    return   '<span class="text-success">' . strtoupper($row->fin_status) . ' </span><a class="btn btn-xs btn-primary"  target="_self"><i class="fa fa-send-o text-white" aria-hidden="true"></i></a>';
                }
                else if ($row->fin_status == 'declined') {
                    return   '<span class="text-danger">' . strtoupper($row->fin_status) . ' </span>';
                }
                return $row->fin_status ? strtoupper($row->fin_status) : 'N/A';
            })
            ->addColumn('status', function ($row) {
                return
                    strtoupper($row->status);
            })
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" id="basic_checkbox_' . $row->id . '" class="filled-in">' .
                    '<label for="basic_checkbox_' . $row->id . '" class="mb-0 h-15 ms-15"></label>';
            })
            ->addColumn('action', function ($row) {
                return
                    '<div class="btn-group" role="group" aria-label="Action buttons">' .
                    '<a class="btn btn-xs btn-success mr-4" href="' . route('fleet.vehicle.maintenance.view', [$row->id]) . '" target="_self"><i class="fa fa-eye text-white" aria-hidden="true"></i></a>' .
                 
                    '</div>';
            })
            ->rawColumns(['checkbox', 'status', 'fin_status', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query()
    {
        $query = CarMaintenance::query()
            ->with(['car', 'mechanic']) // Assuming these relationships are defined in your CarMaintenance model
            ->orderByDesc('start_date');

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
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
            //Column::make('checkbox')->title('<input type="checkbox" id="basic_checkbox" class="filled-in"><label for="basic_checkbox" class="mb-0 h-15 ms-15"></label>')->addClass('text-center no-border')->searchable(false)->printable(false)->exportable(false),
            Column::make('created_at')->title('Created At')->addClass('text-center no-border'),
            Column::make('start_date')->title('Start Date')->addClass('text-center no-border'),
            Column::make('end_date')->title('End Date')->addClass('text-center no-border'),
            Column::make('car')->title('Car Detail')->addClass('text-center no-border'),
            Column::make('mechanic')->title('Mechanic')->addClass('text-center no-border'),
            Column::make('status')->title('Mec Status')->addClass('text-center no-border'),
            Column::make('fin_status')->title('Fin Status')->addClass('text-center no-border'),
            Column::computed('action')->addClass('text-center no-border')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'VehicleMaintenanceDataTable_' . date('YmdHis');
    }
}
