<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
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
                return '<input type="checkbox" id="basic_checkbox_'.$row->id.'" class="filled-in">' .
                    '<label for="basic_checkbox_'.$row->id.'" class="mb-0 h-15 ms-15"></label>';
            })
            ->addColumn('name', function ($row) {
                return $row->full_name() ?? 'N/A';
            })
            ->addColumn('department', function ($row) {
                return $row->department->name ?? 'N/A';
            })
            ->editColumn('type', function ($row) {
                return $row->type ?? 'N/A';
            })
            ->editColumn('role', function($row) {
                return $row->getRole();
            })
            ->addColumn('assigned_modules', function($row) {
                $role = $row->roles->first();
                if(!is_null($role))
                    return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                        '<a class="btn btn-xs btn-success" href="'.route('settings.user.permissions', [$row->id, $role->id]).'"' .
                        'onclick=""><i class="fa fa-eye text-white" aria-hidden="true"></i></a>' .
                        '</div>';

                return 'N/A';
            })
            ->addColumn('license', function($row) {
                    return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                        '<a class="btn btn-xs btn-success" href="#"' .
                        'onclick=""><i class="fa fa-eye text-white" aria-hidden="true"></i></a>' .
                        '</div>';
            })
            ->editColumn('status', function ($row) {
                if (strtolower($row->status) == 'due_servicing')
                    return '<div class="action-label">' .
                        '<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">' .
                        '<i class="fa fa-dot-circle-o text-danger"></i> Due Servicing</a></div>';

                elseif (strtolower($row->status) == 'inactive')
                    return '<div class="action-label">' .
                        '<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">' .
                        '<i class="fa fa-dot-circle-o text-danger"></i> Inactive</a></div>';

                elseif (strtolower($row->status) == 'active')
                    return '<div class="action-label">' .
                        '<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">' .
                        '<i class="fa fa-dot-circle-o text-success"></i> Active</a></div>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                    '<a class="btn btn-xs btn-success" href="'.route('settings.users.view', $row->id).'"><i class="fa fa-eye text-white" aria-hidden="true"></i></a>' .
                    '<a class="btn btn-xs btn-primary" href="#"' .
                    'onclick="editNotify('.$row->id.')"><i class="fa fa-edit text-white" aria-hidden="true"></i></a>' .
                    '<a class="btn btn-xs btn-danger" href="#"' .
                    'onclick="deleteNotify('.$row->id.')"><i class="fa fa-trash-o text-white" aria-hidden="true"></i></a>' .
                    '</div>';
            })
            ->rawColumns(['license', 'assigned_modules', 'checkbox', 'status', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query()
    {
        $type = !empty($this->request()->get('type')) ? ['type' => $this->request()->get('type')] : [];
        $query = User::query()->with(['department'])->where($type)->orderByDesc('created_at');
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
            Column::make('name')->addClass('text-center no-border'),
            Column::make('email')->addClass('text-center no-border'),
            Column::make('mobile')->addClass('text-center no-border'),
            Column::make('role')->addClass('text-center no-border'),
            Column::make('type')->addClass('text-center no-border'),
            Column::make('assigned_modules')->title('Assigned&nbsp;Modules')->addClass('text-center no-border'),
            Column::make('department')->addClass('text-center no-border'),
            Column::make('license')->title('License&nbsp;Info')->addClass('text-center no-border'),
            Column::make('status')->addClass('text-center no-border'),
            Column::computed('action')->addClass('text-center no-border')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename():string
    {
        return 'UserDataTable_' . date('YmdHis');
    }
}
