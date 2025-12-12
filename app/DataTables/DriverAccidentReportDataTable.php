<?php

namespace App\DataTables;

use App\Models\AccidentReport;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DriverAccidentReportDataTable extends DataTable
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
            ->editColumn('date_reported', function ($row) {
                return Carbon::parse($row->date_reported)->format('D, d F Y H:i A');
            })
            ->addColumn('user', function ($row) {
                return $row->user ? $row->user->full_name() : 'N/A';
            })
            ->addColumn('car_model', function ($row) {
                return $row->car ? $row->car->model . ' (' . $row->car->car_number . ')' : 'N/A';
            })
            ->addColumn('description', function ($row) {
                return $row->description ? $row->description . ' (' . $row->description . ')' :'N/A';
            })
            ->addColumn('status', function ($row) {
                return strtolower($row->status) === 'resolved'
                    ? '<span class="badge bg-success text-white">RESOLVED</span>'
                    : '<span class="badge bg-danger text-white">UNRESOLVED</span>';
            })
            ->addColumn('media', function ($row) {
                if ($row->media->count() > 0) {
                    $media = $row->media->first();
                    return '<a class="btn btn-xs btn-success" href="'.route('downloader', ['path' => $media->path]).'" target="_blank"><i class="fa fa-eye text-white" aria-hidden="true"></i></a>';
                }
                return 'N/A';
            })
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" id="basic_checkbox_'.$row->id.'" class="filled-in">' .
                    '<label for="basic_checkbox_'.$row->id.'" class="mb-0 h-15 ms-15"></label>';
            })
            ->addColumn('action', function ($row) {
                return auth()->user()->can('fleet_management')
                    ? '<a class="btn btn-xs btn-primary" href="#" onclick="resolveNotify('.$row->id.')"><i class="fa fa-check text-white" aria-hidden="true"></i></a>'
                    : 'N/A';
            })
            ->rawColumns(['checkbox', 'status', 'media', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = auth()->user()->can('fleet_management')
            ? AccidentReport::with(['media', 'car', 'user'])->orderByDesc('created_at')
            : AccidentReport::with(['media', 'car'])->whereBelongsTo(auth()->user())->orderByDesc('created_at');

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
            Column::make('checkbox')
                ->title('<input type="checkbox" id="basic_checkbox" class="filled-in"><label for="basic_checkbox" class="mb-0 h-15 ms-15"></label>')
                ->addClass('text-center no-border')
                ->searchable(false)
                ->printable(false)
                ->exportable(false),
            Column::make('created_at')->title('Created At')->addClass('text-center no-border'),
            Column::make('date_reported')->title('Date Reported')->addClass('text-center no-border'),
            Column::make('user')->title('User')->addClass('text-center no-border'),
            Column::make('car_model')->title('Car Detail')->addClass('text-center no-border'),
            Column::make('location')->title('Location')->addClass('text-center no-border'),
            Column::make('description')->title('Short Description')->addClass('text-center no-border'),
            Column::make('status')->title('Status')->addClass('text-center no-border'),
            Column::make('media')->title('Media')->addClass('text-center no-border'),
            Column::computed('action')->title('Actions')->addClass('text-center no-border'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename():string
    {
        return 'DriverAccidentReportDataTable_' . date('YmdHis');
    }
}
