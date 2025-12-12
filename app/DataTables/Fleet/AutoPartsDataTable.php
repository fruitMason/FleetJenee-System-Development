<?php

namespace App\DataTables\Fleet;

use App\Models\AutoPart;
use App\Models\Department;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AutoPartsDataTable extends DataTable
{

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('created_at', function ($row) {
                return $row->created_at;
            })

            ->addColumn('auto_part', function ($row) {
                return $row->name;
            })
             ->addColumn('unit_cost', function ($row) {
                return $row->unit_cost;
            })
            ->addColumn('description', function ($row) {
                return $row->description;
            })
            ->addColumn('action', function ($row) {
                return
                    '<div class="btn-group mr-3" role="group" aria-label="Action buttons">' .
                    '<a class="btn btn-xs btn-primary" href="' . route('auto.parts.edit', $row->id) . '"
                    "><i class="fa fa-edit text-white" aria-hidden="true"></i></a>' .
                    '<form method="post" action="' . route('auto.parts.destroy', $row->id) . '"
                            onsubmit="return SubmitDelete(this,\'Delete Auto Part [' . $row->name . ']\');" >' .
                    csrf_field() .
                    method_field('DELETE') .

                    '<button type="submit" class="btn btn-xs btn-danger" href="#" onclick="archiveNotify(' . $row->id . ')">
                    <i class="fa fa-trash text-white" aria-hidden="true"></i>
                    </button>' .
                    '</form>' .


                    '</div>';
            })
            ->rawColumns(['status', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query()
    {
        $query = AutoPart::where('is_archived', false)
            ->orderBy('name');
        return $this->applyScopes($query);
    }


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
            Column::make('auto_part')->searchable(true),
            Column::make('unit_cost')->searchable(true),
            Column::make('description')->searchable(true),
            Column::computed('action')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'AutoPartsDataTablee_' . date('YmdHis');
    }
}
