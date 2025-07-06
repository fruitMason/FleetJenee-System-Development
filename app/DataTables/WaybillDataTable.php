<?php

namespace App\DataTables;

use App\Models\Waybill;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class WaybillDataTable extends DataTable
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
                return Carbon::parse($row->created_at)->format('D, d F Y H:i A') ?? 'N/A';
            })
            ->addColumn('driver', function ($row) {
                if(!is_null($row->driver))
                    return $row->driver->full_name();
                return 'N/A';
            })
            ->addColumn('item', function ($row) {
                if(!is_null($row->item))
                    return $row->item;
                return 'N/A';
            })
            ->addColumn('weight', function ($row) {
                $no_of_packages = $row->no_of_packages ?? '0';
                if(!is_null($row->weight))
                    return $row->weight.' ('.$no_of_packages.' Packages)';
                return 'N/A';
            })
            ->addColumn('media', function ($row) {
                if(count($row->media) > 0)
                    return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                        '<a class="btn btn-xs btn-success" href="'.route('downloader', ['path' => $row->media[0]->path]).'"' .
                        'onclick=""><i class="fa fa-eye text-white" aria-hidden="true"></i></a>' .
                        '</div>';
                return 'N/A';
            })
            ->addColumn('status', function ($row) {
                if($row->status == 'pending') {
                    return '<span class="badge bg-warning text-white">PENDING</span>';
                }

                if($row->status == 'rejected') {
                    return '<span class="badge bg-danger text-white">REJECTED</span>';
                }

                if($row->status == 'ongoing') {
                    return '<span class="badge bg-info text-white">ONGOING</span>';
                }

                if($row->status == 'completed') {
                    return '<span class="badge bg-success text-white">COMPLETED</span>';
                }
            })
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" id="basic_checkbox_'.$row->id.'" class="filled-in">' .
                    '<label for="basic_checkbox_'.$row->id.'" class="mb-0 h-15 ms-15"></label>';
            })
            ->addColumn('action', function ($row) {
                if (auth()->user()->can('driver_management')){
                    if ($row->status == 'pending')
                        return '<div class="dropdown dropdown-action">' .
                            '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                            '<div class="dropdown-menu dropdown-menu-right">' .
                            '<a class="dropdown-item" href="#"' .
                            'onclick="acceptNotify('.$row->id.')"><i class="fa fa-check m-r-5"></i> Accept</a>' .
                            '<a class="dropdown-item" href="#"' .
                            'onclick="rejectNotify('.$row->id.')"><i class="fa fa-ban m-r-5"></i> Reject</a>' .
                            '</div></div>';

                    if ($row->status == 'ongoing')
                        return '<div class="dropdown dropdown-action">' .
                            '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                            '<div class="dropdown-menu dropdown-menu-right">' .
                            '<a class="dropdown-item" href="#"' .
                            'onclick="completeNotify('.$row->id.')"><i class="fa fa-check m-r-5"></i> Complete</a>' .
                            '</div></div>';
                }
                return 'N/A';
            })
            ->rawColumns(['checkbox', 'status', 'media', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query()
    {
        $query = Waybill::query()->with(['driver', 'media'])->orderByDesc('created_at');
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
            Column::make('destination')->addClass('text-center no-border'),
            Column::make('driver')->addClass('text-center no-border'),
            Column::make('status')->addClass('text-center no-border'),
            Column::make('item')->addClass('text-center no-border'),
            Column::make('weight')->addClass('text-center no-border'),
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
        return 'VehicleMaintenanceDataTable_' . date('YmdHis');
    }
}
