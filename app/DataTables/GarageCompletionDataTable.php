<?php

namespace App\DataTables;

use App\Models\CarMaintenance;
use App\Models\CarMaintenanceMedia;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GarageCompletionDataTable extends DataTable
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
            ->addColumn('car', function ($row) {
                return $row->car->model.' ('.$row->car->car_number.')' ?? 'N/A';
            })
            ->addColumn('mechanic', function ($row) {
                if(!is_null($row->mechanic))
                    return $row->mechanic->full_name();

                return 'N/A';
            })
            ->editColumn('completed_date', function ($row) {
                return Carbon::parse($row->completed_date)->format('D, d F Y') ?? 'N/A';
            })
            ->editColumn('completed_comment', function ($row) {
                return $row->completed_comment ?? 'N/A';
            })
            ->addColumn('media', function ($row) {
                if(count($row->media) > 2)
                    return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                        '<a class="btn btn-xs btn-success" href="'.route('downloader', ['path' => $row->media[2]->path]).'"' .
                        'onclick=""><i class="fa fa-eye text-white" aria-hidden="true"></i></a>' .
                        '</div>';

                if(count($row->media) == 2)
                    return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                        '<a class="btn btn-xs btn-success" href="'.route('downloader', ['path' => $row->media[1]->path]).'"' .
                        'onclick=""><i class="fa fa-eye text-white" aria-hidden="true"></i></a>' .
                        '</div>';

                if(count($row->media) == 1)
                    return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                        '<a class="btn btn-xs btn-success" href="'.route('downloader', ['path' => $row->media[0]->path]).'"' .
                        'onclick=""><i class="fa fa-eye text-white" aria-hidden="true"></i></a>' .
                        '</div>';

                return 'N/A';
            })
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" id="basic_checkbox_'.$row->id.'" class="filled-in">' .
                    '<label for="basic_checkbox_'.$row->id.'" class="mb-0 h-15 ms-15"></label>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="dropdown dropdown-action">' .
                    '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                    '<div class="dropdown-menu dropdown-menu-right">' .
                    '</div></div>';
            })
            ->rawColumns(['checkbox', 'status', 'media', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query()
    {
        $query = CarMaintenance::query()->with(['media'])->where('id', $this->id)->orderByDesc('created_at');
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
            Column::make('created_at')->printable(true)->searchable(true)->visible(true),
            Column::make('car')->title('Car&nbsp;Detail')->addClass('text-center no-border'),
            Column::make('mechanic')->addClass('text-center no-border'),
            Column::make('completed_date')->addClass('text-center no-border'),
            Column::make('completed_comment')->addClass('text-center no-border'),
            Column::make('media')->addClass('text-center no-border'),
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
        return 'GarageReceiptDataTable_' . date('YmdHis');
    }
}
