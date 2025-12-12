<?php

namespace App\DataTables;

use App\Models\Car;
use App\Models\OdometerHistory;
use App\Models\Sector;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DriverOdometerHistoryDataTable extends DataTable
{
    /**
     * Build DataTable class.n
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
            ->addColumn('car_model', function ($row) {
                return $row->car->model ?? 'N/A';
            })
            ->addColumn('car_number', function ($row) {
                return $row->car->car_number ?? 'N/A';
            })
            ->addColumn('region', function ($row) {
                return $row->car->user->department->region->name ?? 'N/A';
            })
            ->addColumn('department', function ($row) {
                return $row->car->user->department->name ?? 'N/A';
            })
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" id="basic_checkbox_'.$row->id.'" class="filled-in">' .
                    '<label for="basic_checkbox_'.$row->id.'" class="mb-0 h-15 ms-15"></label>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="dropdown dropdown-action">' .
                    '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                    '<div class="dropdown-menu dropdown-menu-right">' .
                    '<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_employee"><i class="fa fa-pencil m-r-5"></i> Edit</a>' .
                    '<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#delete_employee"><i class="fa fa-trash-o m-r-5"></i> Delete</a>' .
                    '</div></div>';
            })
            ->rawColumns(['checkbox', 'status', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query()
    {
        $user = $this->user_id;
        if(is_null($user))
            $query = OdometerHistory::query()->whereBelongsTo(auth()->user())->orderByDesc('created_at');
        else
            $query = OdometerHistory::query()->where('user_id', $user)->orderByDesc('created_at');
        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        if(!is_null($this->user_id))
            return $this->builder()
                ->columns($this->getColumns())
                ->setTableId('driverOdometerHistoryDataTable')
                ->minifiedAjax(route('settings.user.car.odometer.history', $this->user_id))
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
            Column::make('checkbox')->title('<input type="checkbox" id="basic_checkbox" class="filled-in"><label for="basic_checkbox" class="mb-0 h-15 ms-15"></label>')->addClass('text-center no-border')->searchable(false)->printable(false)->exportable(false),
            Column::make('created_at')->printable(true)->searchable(true)->visible(true),
            Column::make('car_model')->title('Car&nbsp;Model')->addClass('text-center no-border'),
            Column::make('car_number')->title('Car&nbsp;Number')->addClass('text-center no-border'),
            Column::make('region')->addClass('text-center no-border'),
            Column::make('department')->addClass('text-center no-border'),
            Column::make('old_value')->addClass('text-center no-border'),
            Column::make('new_value')->addClass('text-center no-border'),
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
        return 'SectorDataTable_' . date('YmdHis');
    }
}
