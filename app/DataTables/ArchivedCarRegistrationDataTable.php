<?php

namespace App\DataTables;

use App\Models\Car;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchivedCarRegistrationDataTable extends DataTable
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
                return $row->created_at;
            })
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" id="basic_checkbox_' . $row->id . '" class="filled-in">' .
                    '<label for="basic_checkbox_' . $row->id . '" class="mb-0 h-15 ms-15"></label>';
            })
            ->addColumn('car_details', function ($row) {
                return $row->model;
            })
            ->addColumn('car_group', function ($row) {
                return $row->car_group;
            })
            ->addColumn('assigned_user', function ($row) {
                return !is_null($row->user) ? $row->user->full_name() : 'N/A';
            })
            ->addColumn('sector', function ($row) {
                if (!is_null($row->user))
                    return $row->user->department->region->sector->name ?? 'N/A';

                return $row->sector->name ?? 'N/A';
            })
            ->addColumn('assigned_user_mobile', function ($row) {
                return !is_null($row->user) ? $row->user->mobile : 'N/A';
            })
            ->addColumn('year', function ($row) {
                return Carbon::parse($row->year)->format('Y'); // Outputs only the year, e.g., "2020"
            })
            ->addColumn('assigned_user_department', function ($row) {
                if (!is_null($row->user))
                    return $row->user->department->name ?? 'N/A';

                return $row->department->name ?? 'N/A';
            })
            ->editColumn('status', function ($row) {
                if (strtolower($row->status) == 'due_servicing' || strtolower($row->status) == 'due_maintenance')
                    return '<div class="action-label">' .
                        '<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">' .
                        '<i class="fa fa-dot-circle-o text-danger"></i> Due Maintenance</a></div>';

                elseif (strtolower($row->status) == 'inactive')
                    return '<div class="action-label">' .
                        '<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">' .
                        '<i class="fa fa-dot-circle-o text-danger"></i> Inactive</a></div>';

                elseif (strtolower($row->status) == 'in_repairs')
                    return '<div class="action-label">' .
                        '<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">' .
                        '<i class="fa fa-dot-circle-o text-danger"></i> In Repairs</a></div>';

                elseif (strtolower($row->status) == 'in_maintenance' || strtolower($row->status) == 'maintenance')
                    return '<div class="action-label">' .
                        '<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">' .
                        '<i class="fa fa-dot-circle-o text-danger"></i> In Maintenance</a></div>';

                elseif (strtolower($row->status) == 'active')
                    return '<div class="action-label">' .
                        '<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">' .
                        '<i class="fa fa-dot-circle-o text-success"></i> Active</a></div>';
            })
            ->addColumn('action', function ($row) {
                if (strtolower($row->status) == 'due_maintenance')
                    return '<div class="dropdown dropdown-action">' .
                        '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                        '<div class="dropdown-menu dropdown-menu-right">' .
                        '<a class="dropdown-item" href="#" onclick="unarchiveNotify(' . $row->id . ')"><i class="fa fa-archive m-r-5"></i> Unarchive</a>' .
                        '<a class="dropdown-item" href="#"' .
                        'onclick="deleteNotify(' . $row->id . ')"><i class="fa fa-trash-o m-r-5"></i> Delete</a>' .
                        '<a class="dropdown-item" href="#"' .
                        'onclick="maintainNotify(' . $row->id . ')"><i class="fa fa-car m-r-5"></i> Send to Maintenance</a>' .
                        '</div></div>';

                return '<div class="dropdown dropdown-action">' .
                    '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                    '<div class="dropdown-menu dropdown-menu-right">' .
                    '<a class="dropdown-item" href="#" onclick="unarchiveNotify(' . $row->id . ')"><i class="fa fa-archive m-r-5"></i> Unarchive</a>' .
                    '<a class="dropdown-item" href="#"' .
                    'onclick="deleteNotify(' . $row->id . ')"><i class="fa fa-trash-o m-r-5"></i> Delete</a>' .
                    '</div></div>';
            })
            ->rawColumns(['checkbox', 'status', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Request $request)
    {
        return Car::query()->with(['user'])
            ->where('is_archived', true) // Only show archived cars
            ->orderByDesc('created_at');
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

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('created_at')->printable(false)->searchable(false)->visible(false),
            Column::make('checkbox')->title('<input type="checkbox" id="basic_checkbox" class="filled-in"><label for="basic_checkbox" class="mb-0 h-15 ms-15"></label>')->addClass('text-center no-border')->searchable(false)->printable(false)->exportable(false),
            Column::make('car_number')->title('Car&nbsp;Number')->addClass('text-center no-border')->searchable(true),
            Column::make('car_details')->title('Car&nbsp;Details')->addClass('text-center no-border')->searchable(true),
            Column::make('car_group')->title('Car&nbsp;Group')->addClass('text-center no-border')->searchable(true),
            Column::make('assigned_user')->title('Assigned&nbsp;User')->addClass('text-center no-border')->searchable(true),
            Column::make('odometer')->addClass('text-center no-border')->searchable(true),
            Column::make('year')->addClass('text-center no-border')->searchable(true),
            Column::make('sector')->title('Zone')->addClass('text-center no-border')->searchable(true),
            Column::make('assigned_user_mobile')->addClass('text-center no-border')->searchable(true),
            Column::make('assigned_user_department')->addClass('text-center no-border')->searchable(true),
            Column::computed('status')->addClass('text-center no-border')->searchable(false),
            Column::computed('action')->addClass('text-center no-border')->searchable(false),
        ];
    }



    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'ArchivedCarRegistrationDataTable_' . date('YmdHis');
    }
}
