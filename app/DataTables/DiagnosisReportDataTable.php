<?php

namespace App\DataTables;

use App\Models\CarMaintenance;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DiagnosisReportDataTable extends DataTable
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
            ->addColumn('driver', function ($row) {
                return $row->car->user->full_name() ?? 'N/A';
            })
            ->addColumn('department', function ($row) {
                return $row->car->user->department->name ?? 'N/A';
            })
            ->editColumn('start_date', function ($row) {
                return Carbon::parse($row->start_date)->format('D, d F Y') ?? 'N/A';
            })
            ->addColumn('vendor_name', function ($row) {
                return $row->invoice->vendor->name ?? 'N/A'; // Assuming the vendor has a 'name' attribute
            })
            ->addColumn('price', function ($row) {
                return $row->invoice->invoiceitem->sum('price') ?? 0; // Correct relationship name
            })
            ->addColumn('quantity', function ($row) {
                return $row->invoice->invoiceitem->sum('quantity') ?? 0; // Correct relationship name
            })
            ->addColumn('description', function ($row) {
                return $row->invoice->invoiceItem->pluck('description')->implode(', ') ?? 'N/A'; // Concatenate descriptions
            })

            ->rawColumns(['']);
    }

    public function query()
    {
        $query = CarMaintenance::with(['car', 'mechanic', 'car.sector', 'invoice', 'invoice.invoiceItem'])
            ->where('status', 'diagnosed')
            ->orderByDesc('created_at');

        if (request()->has('department_id') && request()->department_id != '') {
            $query->whereHas('car.user.department', function ($q) {
                $q->where('departments.id', request()->department_id);
            });
        }

        if (request()->has('region_id') && request()->region_id != '') {
            $query->whereHas('car.user.department.region', function ($q) {
                $q->where('regions.id', request()->region_id);
            });
        }

        if (request()->has('month') && request()->month != '') {
            $month = request()->month;
            $query->whereMonth('start_date', $month);
        }

        return $query;
    }






    public function html()
    {
        return $this->builder()
            ->setTableId('diagnosisReportTable')
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
            Column::make('start_date')->addClass('text-center'), // 1st
            Column::make('car')->title('Car Detail')->addClass('text-center'), // 2nd
            Column::make('driver')->addClass('text-center'), // 3rd
            Column::make('vendor_name')->title('Vendor Name')->addClass('text-center'), // 4th
            Column::make('region')->addClass('text-center'), // 5th
            Column::make('department')->addClass('text-center'), // 6th
            Column::make('quantity')->addClass('text-center'), // 7th
            Column::make('price')->title('Cost')->addClass('text-center'), // 8th
            Column::make('description')->title('Description')->addClass('text-center'), // 9th
        ];
    }


    protected function filename(): string
    {
        return 'VehicleDiagnosisReport_' . date('YmdHis');
    }
}
