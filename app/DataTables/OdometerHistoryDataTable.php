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

class OdometerHistoryDataTable extends DataTable
{
    protected $car_id;




    // public function __construct($car_id = null)
    // {
    //     $this->car_id = $car_id;
    // }

    // Setter method for car_id
    public function setCarId($car_id)
    {
        $this->car_id = $car_id;
        return $this; // Fluent interface
    }
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
            ->addColumn('user', function ($row) {
                $user = $row->user ? $row->user->full_name() : 'N/A';
                return  $user;
            })
            ->rawColumns(['user']);
    }

    public function query()
    {
        return OdometerHistory::with('user')->where('car_id', $this->car_id)->select('odometer_history.*');
    }

    // public function __construct($car_id)
    // {
    //     $this->car_id = $car_id;
    // }


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
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->serverSide(true)
            ->processing(true)
            ->dom("<'row'>l<'/row'>Bfrtip")
            ->orderBy(0)
            ->buttons(/* Add your buttons here */);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')->title('ID'), 
            Column::make('old_value')->title('Old Value'),
            Column::make('new_value')->title('New Value'),
            Column::make('created_at')->title('Created At'),
            Column::make('user')->title('User'),
            // Add more columns as needed
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'SectorDataTable_' . date('YmdHis');
    }
}
