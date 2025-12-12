<?php

namespace App\DataTables;

use App\Models\CarMaintenance;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

use function Illuminate\Log\log;

class GarageDataTable extends DataTable
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
                return $row->car->model . ' (' . $row->car->car_number . ')' ?? 'N/A';
            })
            ->addColumn('zone', function ($row) {
                return $row->car->sector->name ?? 'N/A';
            })
            ->addColumn('mechanic', function ($row) {
                if (!is_null($row->mechanic))
                    return $row->mechanic->full_name();

                return 'N/A';
            })
            ->editColumn('start_date', function ($row) {
                return Carbon::parse($row->start_date)->format('D, d F Y') ?? 'N/A';
            })
            ->editColumn('type', function ($row) {
                return strtoupper($row->type) ?? 'N/A';
            })
            ->addColumn('status', function ($row) {
                if (strtolower($row->status) == 'ongoing')
                    return '<span class="badge bg-info text-white">ONGOING</span>';

                if (strtolower($row->status) == 'diagnosed')
                    return '<span class="badge bg-success text-white">DIAGNOSED</span>';

                if (strtolower($row->status) == 'completed')
                    return '<span class="badge bg-success text-white">COMPLETED</span>';

                return '<span class="badge bg-warning text-white">PENDING</span>';
            })
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" id="basic_checkbox_' . $row->id . '" class="filled-in">' .
                    '<label for="basic_checkbox_' . $row->id . '" class="mb-0 h-15 ms-15"></label>';
            })
            ->addColumn('action', function ($row) {
                $actions = '';
                
                if (auth()->user()->can('fleet_management')) {
                    if (strtolower($row->status) == 'diagnosed') {
                        $actions .= '<div class="dropdown dropdown-action">' .
                            '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                            '<div class="dropdown-menu dropdown-menu-right">' .
                            '<a class="dropdown-item" href="' . route('mechanic.garage.diagnosis.view', $row->id) . '"><i class="fa fa-eye m-r-5"></i> Diagnosis Details</a>' .
                             '<a class="dropdown-item" href="' . route('fleet.vehicle.maintenance.view', $row->id) . '"><i class="fa fa-eye m-r-5"></i> Order Full History</a>' .
                            '</div></div>';
                    } elseif (strtolower($row->status) == 'completed') {
                        $actions .= '<div class="dropdown dropdown-action">' .
                            '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                            '<div class="dropdown-menu dropdown-menu-right">' .
                            '<a class="dropdown-item" href="' . route('mechanic.garage.receipt.confirm.view', $row->id) . '"><i class="fa fa-eye m-r-5"></i> Receipt Details</a>' .
                            '<a class="dropdown-item" href="' . route('mechanic.garage.diagnosis.view', $row->id) . '"><i class="fa fa-eye m-r-5"></i> Diagnosis Details</a>' .
                            '<a class="dropdown-item" href="' . route('mechanic.garage.completed.view', $row->id) . '"><i class="fa fa-eye m-r-5"></i> Completed Details</a>' .
                            '</div></div>';
                    }
                } else {
                    // Actions for mechanics or other users
                    if (strtolower($row->status) == 'pending')
                        $actions .= '<div class="dropdown dropdown-action">' .
                            '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                            '<div class="dropdown-menu dropdown-menu-right">' .
                            '<a class="dropdown-item" href="#" onclick="confirmReceiptNotify(' . $row->id . ', ' . $row->car->id . ')"><i class="fa fa-check m-r-5"></i> Confirm Receipt</a>' .
                            '<a class="dropdown-item" href="' . route('mechanic.garage.order.detail', $row->id) . '"><i class="fa fa-eye m-r-5"></i> Receipt Details</a>' .
                            '<a class="dropdown-item" href="#" onclick=""><i class="fa fa-eye m-r-5"></i> Completed Details</a>' .
                            '</div></div>';

                    if (strtolower($row->status) == 'ongoing')
                        $actions .= '<div class="dropdown dropdown-action">' .
                            '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                            '<div class="dropdown-menu dropdown-menu-right">' .
                            '<a class="dropdown-item" href="#" onclick="uploadDiagnosisNotify(' . $row->id . ', ' . $row->car->id . ')"><i class="fa fa-check-circle-o m-r-5"></i> Upload Diagnosis</a>' .
                            '<a class="dropdown-item" href="' . route('mechanic.garage.invoice.create', $row->id) . '"><i class="fa fa-check-circle-o m-r-5"></i> Generate Invoice</a>' .
                            '<a class="dropdown-item" href="#" onclick="confirmCompletedNotify(' . $row->id . ', ' . $row->car->id . ')"><i class="fa fa-check-circle-o m-r-5"></i> Confirm Completed</a>' .
                            '<a class="dropdown-item" href="' . route('mechanic.garage.order.detail', $row->id) . '" ><i class="fa fa-eye m-r-5"></i> Receipt Details</a>' . //href="' . route('mechanic.garage.receipt.confirm.view', $row->id) . '"
                            '<a class="dropdown-item" href="' . route('mechanic.garage.diagnosis.view', $row->id) . '"><i class="fa fa-eye m-r-5"></i> Diagnosis Details</a>' .
                            '<a class="dropdown-item" href="#" onclick=""><i class="fa fa-eye m-r-5"></i> Completed Details</a>' .
                            '</div></div>';

                    if (strtolower($row->status) == 'diagnosed')
                        $actions .= '<div class="dropdown dropdown-action">' .
                            '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                            '<div class="dropdown-menu dropdown-menu-right">' .
                            '<a class="dropdown-item" href="' . route('mechanic.garage.invoice.create', $row->id) . '"><i class="fa fa-check-circle-o m-r-5"></i> Generate Invoice</a>' .
                            '<a class="dropdown-item" href="#" onclick="confirmCompletedNotify(' . $row->id . ', ' . $row->car->id . ')"><i class="fa fa-check-circle-o m-r-5"></i> Confirm Completed</a>' .
                            '<a class="dropdown-item" href="' . route('mechanic.garage.order.detail', $row->id) . '"><i class="fa fa-eye m-r-5"></i> Receipt Details</a>' .
                            '<a class="dropdown-item" href="' . route('mechanic.garage.diagnosis.view', $row->id) . '"><i class="fa fa-eye m-r-5"></i> Diagnosis Details</a>' .
                            '<a class="dropdown-item" href="#" onclick=""><i class="fa fa-eye m-r-5"></i> Completed Details</a>' .
                            '</div></div>';

                    if (strtolower($row->status) == 'completed')
                        $actions .= '<div class="dropdown dropdown-action">' .
                            '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                            '<div class="dropdown-menu dropdown-menu-right">' .
                            '<a class="dropdown-item" href="' . route('mechanic.garage.order.detail', $row->id) . '"><i class="fa fa-eye m-r-5"></i> Receipt Details</a>' .
                            '<a class="dropdown-item" href="' . route('mechanic.garage.diagnosis.view', $row->id) . '"><i class="fa fa-eye m-r-5"></i> Diagnosis Details</a>' .
                            '<a class="dropdown-item" href="' . route('mechanic.garage.completed.view', $row->id) . '"><i class="fa fa-eye m-r-5"></i> Completed Details</a>' .
                            '</div></div>';
                }

                return $actions ?: '<div class="dropdown dropdown-action"><a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a><div class="dropdown-menu dropdown-menu-right"></div></div>';
            })
            ->rawColumns(['checkbox', 'status', 'action']);
    }


    /**
     * Get query source of dataTable.
     */
    public function query()
    {
        $user = auth()->user();
        $query = $user->can('fleet_management')
            ? CarMaintenance::query()->with(['car', 'mechanic'])->orderByDesc('created_at') :
            CarMaintenance::query()->with(['car', 'mechanic'])->where('fin_status', 'approved')->whereBelongsTo($user, 'mechanic')->orderByDesc('created_at');
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
            // Column::make('checkbox')->title('<input type="checkbox" id="basic_checkbox" class="filled-in"><label for="basic_checkbox" class="mb-0 h-15 ms-15"></label>')->addClass('text-center no-border')->searchable(false)->printable(false)->exportable(false),
            // Column::make('created_at')->printable(true)->searchable(true)->visible(true),
            Column::make('car')->title('Car&nbsp;Detail'),
            Column::make('zone')->addClass('text-center no-border'),
            Column::make('status')->title('Mech Status')->addClass('text-center no-border'),
            Column::make('type')->addClass('text-center no-border'),
            Column::make('comment')->addClass('text-center no-border'),
            Column::make('start_date')->addClass('text-center no-border'),
            Column::computed('action')->addClass('text-center no-border')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'VehicleMaintenanceDataTable_' . date('YmdHis');
    }
}
