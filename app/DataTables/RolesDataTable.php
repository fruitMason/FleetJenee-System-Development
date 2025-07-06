<?php

namespace App\DataTables;

use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\DataTableAbstract;

class RolesDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query): DataTableAbstract
    {
        return datatables()->eloquent($query)
            ->addColumn('created_at', function ($row) {
                return $row->created_at;
            })
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" id="basic_checkbox_'.$row->id.'" class="filled-in">' .
                    '<label for="basic_checkbox_'.$row->id.'" class="mb-0 h-15 ms-15"></label>';
            })
            ->addColumn('date', function($row) {
                return Carbon::parse($row->created_at)->format('d M, Y');
            })
            ->editColumn('name', function($row) {
                return $row->name;
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                    '<a class="btn btn-xs btn-success" href="'.route('settings.roles.view', [$row->id]).'" target="_self"><i class="fa fa-eye text-white" aria-hidden="true"></i></a>' .
                    '<a class="btn btn-xs btn-primary" href="#"' .
                    'onclick="resolve('.$row->id.')"><i class="fa fa-check text-white" aria-hidden="true"></i></a>' .
                    '<a class="btn btn-xs btn-info" href="#" onclick="archiveNotify('. $row->id.')"><i class="fa fa-archive text-white" aria-hidden="true"></i></a>' .
                    // '<a class="btn btn-xs btn-danger" href="#"' .
                    // 'onclick="delete_notify('.$row->id.')"><i class="fa fa-trash-o text-white" aria-hidden="true"></i></a>' .
                    '</div>';
            })->rawColumns(['checkbox', 'assigned_modules', 'status', 'action', 'respond']);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        $query = Role::query()
        ->where('is_archived', false)
        ->orderByDesc('created_at');
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): \Yajra\DataTables\Html\Builder
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
    protected function getColumns(): array
    {
        return [
            Column::make('created_at')->printable(false)->searchable(false)->visible(false),
            Column::make('checkbox')->title('<input type="checkbox" id="basic_checkbox" class="filled-in"><label for="basic_checkbox" class="mb-0 h-15 ms-15"></label>')->addClass('text-center no-border')->searchable(false)->printable(false)->exportable(false),
            Column::make('date')->addClass('text-center no-border'),
            Column::make('name')->addClass('text-center no-border'),
            Column::make('guard_name')->addClass('text-center no-border'),
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
        return 'roles_report' . now();
    }
}
