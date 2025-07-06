<?php

namespace App\DataTables;

use App\Models\CarMaintenance;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MaintenanceReportDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('car', function ($row) {
                return $row->car->model . ' (' . $row->car->car_number . ')' ?? 'N/A';
            })
            ->addColumn('region', function ($row) {
                return $row->car->user->department->region->name ?? 'N/A';
            })
            ->addColumn('zone', function ($row) {
                return $row->car->sector->name ?? 'N/A';
            })
            ->addColumn('driver', function ($row) {
                return $row->car->user->full_name() ?? 'N/A';
            })
            ->addColumn('department', function ($row) {
                return $row->car->user->department-> name?? 'N/A';
            })
            ->editColumn('start_date', function ($row) {
                return Carbon::parse($row->start_date)->format('D, d F Y') ?? 'N/A';
            })
            ->editColumn('type', function ($row) {
                return strtoupper($row->type) ?? 'N/A';
            })
            ->addColumn('status', function ($row) {
                switch (strtolower($row->status)) {
                    case 'ongoing':
                        return '<span class="badge bg-info text-white">ONGOING</span>';
                    case 'diagnosed':
                        return '<span class="badge bg-success text-white">DIAGNOSED</span>';
                    case 'completed':
                        return '<span class="badge bg-success text-white">COMPLETED</span>';
                    default:
                        return '<span class="badge bg-warning text-white">PENDING</span>';
                }
            })
            ->addColumn('vendor_name', function ($row) {
                return $row->invoice->vendor->name ?? 'N/A'; // Assuming the vendor has a 'name' attribute
            })
            ->addColumn('net_total', function ($row) {
                return $row->invoice->net_total ?? 'N/A'; // Assuming the invoice has a 'net_total' attribute
            })
            ->rawColumns(['status']);
    }

    public function query()
{
    // Start the query with the necessary relationships
    $query = CarMaintenance::with(['car', 'mechanic', 'car.sector'])->orderByDesc('created_at');

    // Apply filtering if department_id is provided
    if (request()->has('department_id') && request()->department_id != '') {
        $query->whereHas('car.user.department', function ($q) {
            $q->where('departments.id', request()->department_id);
        });
    }

    // Apply filtering if region_id is provided
    if (request()->has('region_id') && request()->region_id != '') {
        $query->whereHas('car.user.department.region', function ($q) {
            $q->where('regions.id', request()->region_id);
        });
    }

    // Apply filtering if month is provided
    if (request()->has('month') && request()->month != '') {
        $month = request()->month;
        // Ensure the month is parsed correctly for filtering
        $query->whereMonth('start_date', $month);
    }

    return $query; // Return the built query
}

    


    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->serverSide(true)
            ->processing(true)
            ->dom("<'row'>l<'/row'>Bfrtip")
            ->orderBy(0)
            ->buttons(
                Button::make('colvis'),
                Button::make('copyHtml5'),
                Button::make('excelHtml5'),
                Button::make('csvHtml5'),
                Button::make('pdfHtml5')
            );
    }

    protected function getColumns()
{
    return [
        Column::make('car')->title('Car Detail')->addClass('text-center'),
        Column::make('driver')->addClass('text-center'),
        Column::make('vendor_name')->title('Vendor Name')->addClass('text-center'), // New column
        Column::make('region')->addClass('text-center'),
        Column::make('zone')->addClass('text-center'),
        Column::make('department')->addClass('text-center'),
        Column::make('start_date')->addClass('text-center'),
        Column::make('net_total')->title('Net Total')->addClass('text-center'), // New column
        Column::make('type')->addClass('text-center'),
        Column::make('status')->addClass('text-center'),
    ];
}


    protected function filename():string
    {
        return 'VehicleMaintenanceReport_' . date('YmdHis');
    }
}

