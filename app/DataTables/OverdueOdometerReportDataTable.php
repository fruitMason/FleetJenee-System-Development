<?php

namespace App\DataTables;

use App\Models\Car;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OverdueOdometerReportDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('created_at', function ($row) {
                return $row->latestOdometerHistory->created_at->format('d/m/y H:i:s A');
            })
            ->addColumn('car_model', function ($row) {
                return $row->model ?? 'N/A';
            })
            ->addColumn('car_number', function ($row) {
                return $row->car_number ?? 'N/A';
            })
            ->addColumn('region', function ($row) {
                return $row->user->department->region->name ?? 'N/A';
            })
            ->addColumn('assigned_user', function ($row) {
                return $row->user->full_name() ?? 'N/A';
            })
            ->addColumn('assigned_user_mobile', function ($row) {
                return $row->user->mobile ?? 'N/A';
            })
            ->addColumn('department', function ($row) {
                return $row->user->department->name ?? 'N/A';
            })
            ->addColumn('new_value', function ($row) {
                return $row->latestOdometerHistory->new_value ?? 0;
            })
            ->rawColumns(['checkbox', 'status']);
    }

    public function query()
    {
        $query = Car::query()
            ->with(['user', 'latestOdometerHistory']) // Eager load relationships
            ->whereHas('user') // Ensure that the user exists
            ->whereHas('latestOdometerHistory', function ($q) {
                $q->havingRaw('DATEDIFF(now(), max(created_at)) >= 4');
            });

        // Apply filtering only if department_id or region_id is provided
        if (request()->has('department_id') && request()->department_id != '') {
            $query->whereHas('user.department', function ($q) {
                $q->where('departments.id', request()->department_id);
            });
        }

        if (request()->has('region_id') && request()->region_id != '') {
            $query->whereHas('user.department.region', function ($q) {
                $q->where('regions.id', request()->region_id);
            });
        }

        return $this->applyScopes($query)->orderByDesc('created_at');
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
            ->parameters([
                'buttons' => [
                    'csv',
                    'excel',
                    'print',
                    'reload',
                ],
                'dom' => "<'row'>l<'/row'>Bfrtip",
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::make('created_at')->title('Last&nbsp;Input&nbsp;Date')->printable(true)->searchable(true)->visible(true),
            Column::make('new_value')->title('Value')->addClass('text-center no-border'),
            Column::make('car_model')->title('Car&nbsp;Model')->addClass('text-center no-border'),
            Column::make('car_number')->title('Car&nbsp;Number')->addClass('text-center no-border'),
            Column::make('region')->addClass('text-center no-border'),
            Column::make('department')->title('Department')->addClass('text-center no-border'),
            Column::make('assigned_user')->title('Assigned&nbsp;User')->addClass('text-center no-border'),
            Column::make('assigned_user_mobile')->title('Phone&nbsp;Number')->addClass('text-center no-border'),
        ];
    }

    protected function filename():string
    {
        return 'OverdueOdometerReportDataTable_' . date('YmdHis');
    }
}
