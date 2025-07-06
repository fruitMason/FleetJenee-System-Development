<?php

namespace App\DataTables;

use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DriverLicenseDataTable extends DataTable
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
            ->editColumn('email', function ($row) {
                return $row->email.' ('.$row->mobile.')' ?? 'N/A';
            })
            ->addColumn('zone', function ($row) {
                return $row->department->region->sector->name ?? 'N/A';
            })
            ->addColumn('department', function ($row) {
                return $row->department->name ?? 'N/A';
            })
            ->addColumn('expiry_date', function ($row) {
                return $row->license_expiry ?? 'N/A';
            })
            ->editColumn('status', function ($row) {
                if(now()->toDateString() == Carbon::parse($row->license_expiry)->toDateString()) {
                    return '<span class="badge bg-warning text-white">PENDING (EXPIRES TODAY)</span>';
                }

                if(now()->toDateString() > Carbon::parse($row->license_expiry)->toDateString()) {
                    return '<span class="badge bg-danger text-white">EXPIRED</span>';
                }

                if(Carbon::parse($row->license_expiry) > Carbon::now()) {
                    return '<span class="badge bg-success text-white">ACTIVE</span>';
                }
            })
            ->addColumn('action', function ($row) {
                return '<div class="dropdown dropdown-action">' .
                    '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                    '<div class="dropdown-menu dropdown-menu-right">' .
                    '<a class="dropdown-item" href="#"><i class="fa fa-edit m-r-5"></i> Renew  - License</a>' .
                    '</div></div>';
            })
            ->rawColumns(['checkbox', 'status', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query()
    {
        $query = User::query()->whereNotNull('license_expiry')->orderByDesc('created_at');

        if ($this->request()->get('filter') === 'department_heads') {
            // Department heads: users with assigned cars
            $query->whereHas('car'); // Assuming a relationship `car` exists
        } elseif ($this->request()->get('filter') === 'employed_drivers') {
            // Employed drivers: users who drive pool cars
            $query->whereDoesntHave('car'); // No assigned car
        }

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
            Column::make('zone')->addClass('text-center no-border'),
            Column::computed('status')->addClass('text-center no-border'),
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
