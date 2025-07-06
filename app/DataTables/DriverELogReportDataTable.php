<?php

namespace App\DataTables;

use App\Models\ELog;
use App\Models\ELogActivity;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DriverELogReportDataTable extends DataTable
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
            ->addColumn('title', function ($row) {
                return $row->title;
            })
            ->editColumn('date_logged', function ($row) {
                return $row->date_logged ? Carbon::parse($row->date_logged)->format('D, d F Y H:i A') : 'N/A';
            })
            ->addColumn('car_model', function ($row) {
                return $row->car->model.' ('.$row->car->car_number.')' ?? 'N/A';
            })
            ->addColumn('start_odometer', function ($row) {
                return $row->start_odometer ? $row->start_odometer  : 'N/A';
            }) 
            ->addColumn('end_odometer', function ($row) {
                return $row->end_odometer ? $row->end_odometer : 'N/A';
            })
            ->addColumn('ended_date', function ($row) {
                return $row->ended_date ? Carbon::parse($row->ended_date)->format('D, d F Y H:i A') : 'N/A';
            })
            ->addColumn('driver', function ($row) {
                return $row->car->user->full_name() ?? 'N/A';
            })
//            ->addColumn('other_information', function ($row) {
//                return '<div class="btn-group" role="group" aria-label="Action buttons">' .
//                    '<a class="btn btn-xs btn-success" href="#"' .
//                    'onclick=""><i class="fa fa-eye text-white" aria-hidden="true"></i></a>' .
//                    '</div>';
//            })
            ->addColumn('other_information', function ($row) {
                if(!empty($row->description))
                    return $row->description;
                return 'N/A';
            })
            ->addColumn('status', function ($row) {
                if (strtolower($row->status) == 'completed')
                    return '<span class="badge bg-success text-white">RESOLVED</span>';

                return '<span class="badge bg-info text-white">ACTIVE</span>';
            })
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" id="basic_checkbox_'.$row->id.'" class="filled-in">' .
                    '<label for="basic_checkbox_'.$row->id.'" class="mb-0 h-15 ms-15"></label>';
            })
            ->addColumn('action', function ($row) {
                // Hide the View ELog Activity button if the trip is ended
                $viewButton = $row->ended_date ? '' : 
                    '<a title="View ELog Activity" class="btn btn-xs btn-primary" href="' . route('driver.report.elog.activity.view', $row->id) . '"><i class="fa fa-eye text-white" aria-hidden="true"></i></a>';
    
                // Disable the End Trip button if the trip is ended
                $endTripButton = $row->ended_date ? 
                    '<span class="btn btn-xs btn-secondary" disabled>Ended</span>' : 
                    '<a href="#" class="btn btn-xs btn-danger" onclick="showEndTripModal(' . $row->id . ')">End Trip</a>';
    
                return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                    $viewButton .
                    $endTripButton .
                    '</div>';
            })
            ->rawColumns(['checkbox', 'status', 'media', 'other_information', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query()
    {
        $query = auth()->user()->can('fleet_management') ? ELog::query()->with(['activities', 'car', 'user'])->orderByDesc('created_at') : ELog::query()->with(['activities', 'car'])->whereBelongsTo(auth()->user())->orderByDesc('created_at');
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
        $columns = [
            Column::make('checkbox')->title('<input type="checkbox" id="basic_checkbox" class="filled-in"><label for="basic_checkbox" class="mb-0 h-15 ms-15"></label>')
                ->addClass('text-center no-border')->searchable(false)->printable(false)->exportable(
                    false,
                ),
            Column::make('title')->addClass('text-center no-border'),
            Column::make('date_logged')->title('Date&nbsp;Logged')->addClass('text-center no-border'),
            Column::make('car_model')->title('Car&nbsp;Detail')->addClass('text-center no-border'),
            Column::make('start_odometer')->addClass('text-center no-border'),
            Column::make('end_odometer')->title('End Odometer')->addClass('text-center no-border'),
            Column::make('ended_date')->title('Ended Date')->addClass('text-center no-border'),
            Column::make('current_location')->addClass('text-center no-border'),
            Column::make('destination')->addClass('text-center no-border'),
            Column::make('status')->addClass('text-center no-border'),
            Column::make('other_information')->addClass('text-center no-border'),
            Column::make('action')->addClass('text-center no-border'),
        ];

        // Only add the driver column if the user has fleet_management permission
        if (auth()->user()->can('fleet_management')) {
            $columns[] = Column::make('driver')->addClass('text-center');
        }

        return $columns;
    }


    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename():string
    {
        return 'DriverElogReportDataTable_' . date('YmdHis');
    }
}
