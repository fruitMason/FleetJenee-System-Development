<?php

namespace App\DataTables;

use App\Models\Car;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InsuranceDataTable extends DataTable
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
            ->addColumn('zone', function ($row) {
                return $row->user->department->region->sector->name ?? 'N/A';
            })
            ->addColumn('assigned_user', function ($row) {
                return !is_null($row->user) ? $row->user->full_name() : 'N/A';
            })
            ->addColumn('department', function ($row) {
                return $row->user->department->name ?? 'N/A';
            })
            ->addColumn('issued_date', function ($row) {
                return $row->insurance_start_date ?? 'N/A';
            })
            ->addColumn('expiry_date', function ($row) {
                return $row->insurance_expiry ?? 'N/A';
            })
            ->editColumn('status', function ($row) {
                if(now()->toDateString() == Carbon::parse($row->insurance_expiry)->toDateString()) {
                    return '<span class="badge bg-warning text-white">PENDING (EXPIRES TODAY)</span>';
                }

                if(now()->toDateString() > Carbon::parse($row->insurance_expiry)->toDateString()) {
                    return '<span class="badge bg-danger text-white">EXPIRED</span>';
                }

                if(Carbon::parse($row->insurance_expiry) > Carbon::now()) {
                    return '<span class="badge bg-success text-white">ACTIVE</span>';
                }
            })
            ->addColumn('action', function ($row) {
                return '<div class="dropdown dropdown-action">' .
                    '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                    '<div class="dropdown-menu dropdown-menu-right">' .
                    '<a class="dropdown-item" href="#"><i class="fa fa-edit m-r-5"></i> Renew  - Insurance</a>' .
                    '</div></div>';
            })
            ->rawColumns(['checkbox', 'status', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query()
    {
        $query = Car::query()->with(['user'])->whereNotNull('insurance_expiry')->orderByDesc('created_at');
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
            Column::make('created_at')->printable(false)->searchable(false)->visible(false),
            Column::make('checkbox')->title('<input type="checkbox" id="basic_checkbox" class="filled-in"><label for="basic_checkbox" class="mb-0 h-15 ms-15"></label>')->addClass('text-center no-border')->searchable(false)->printable(false)->exportable(false),
            Column::make('car_number')->title('Car&nbsp;Number')->addClass('text-center no-border'),
            Column::make('model')->title('Car&nbsp;Model')->addClass('text-center no-border'),
            Column::computed('status')->addClass('text-center no-border'),
            Column::make('zone')->addClass('text-center no-border'),
            Column::make('assigned_user')->title('Assigned&nbsp;User')->addClass('text-center no-border'),
            Column::make('department')->addClass('text-center no-border'),
            Column::make('issued_date')->addClass('text-center no-border'),
            Column::make('expiry_date')->addClass('text-center no-border'),
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
        return 'InsuranceDataTable_' . date('YmdHis');
    }
}
