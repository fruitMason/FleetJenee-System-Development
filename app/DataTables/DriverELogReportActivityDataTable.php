<?php

namespace App\DataTables;

use App\Models\ELog;
use App\Models\ELogActivity;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DriverELogReportActivityDataTable extends DataTable
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
                return $row->elog->title;
            })
            ->editColumn('date_logged', function ($row) {
                return Carbon::parse($row->date_logged)->format('D, d F Y H:i A');
            })
            ->addColumn('car_model', function ($row) {
                return $row->car->model.' ('.$row->car->car_number.')' ?? 'N/A';
            })
            ->addColumn('media', function ($row) {
                if(count($row->media) > 0)
                    return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                        '<a class="btn btn-xs btn-success" href="'.route('downloader', ['path' => $row->media[0]->path]).'"' .
                        'onclick=""><i class="fa fa-eye text-white" aria-hidden="true"></i></a>' .
                        '</div>';
                return 'N/A';
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
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" id="basic_checkbox_'.$row->id.'" class="filled-in">' .
                    '<label for="basic_checkbox_'.$row->id.'" class="mb-0 h-15 ms-15"></label>';
            })
            ->rawColumns(['checkbox', 'status', 'media', 'other_information', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query()
    {
        $query = auth()->user()->can('fleet_management') ? ELogActivity::query()->with(['elog', 'car', 'user'])->where('e_log_id', $this->id)->orderByDesc('created_at') : ELogActivity::query()->with(['elog', 'car'])->where('e_log_id', $this->id)->whereBelongsTo(auth()->user())->orderByDesc('created_at');
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
            Column::make('checkbox')->title('<input type="checkbox" id="basic_checkbox" class="filled-in"><label for="basic_checkbox" class="mb-0 h-15 ms-15"></label>')->addClass('text-center no-border')->searchable(false)->printable(false)->exportable(false),
//            Column::make('created_at')->printable(true)->searchable(true)->visible(true),
//            Column::make('title')->addClass('text-center no-border'),
            Column::make('date_logged')->title('Date&nbsp;Logged')->addClass('text-center no-border'),
            Column::make('car_model')->title('Car&nbsp;Detail')->addClass('text-center no-border'),
            Column::make('current_location')->addClass('text-center no-border'),
            Column::make('destination')->addClass('text-center no-border'),
            Column::make('other_information')->addClass('text-center no-border'),
            Column::make('media')->addClass('text-center no-border'),
//            Column::computed('action')->addClass('text-center no-border'),
        ];
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
