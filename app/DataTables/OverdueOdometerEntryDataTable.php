<?php

namespace App\DataTables;

use App\Models\Car;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OverdueOdometerEntryDataTable extends DataTable
{
    public function dataTable($query)
    {
        $dataTable = datatables()
            ->eloquent($query)
            ->addColumn('created_at', function ($row) {
                return $row->latestOdometerHistory->created_at->format('d/m/y H:i:s A');
            })
            ->addColumn('car_model', function ($row) {
                return $row->model ?? 'N/A';
            })
            ->addColumn('car_number', function ($row) {
                return $row->car_number ?? 'N/A';
            })
            ->addColumn('zone', function ($row) {
                return $row->user->department->region->sector->name ?? 'N/A';
            })
            ->addColumn('region', function ($row) {
                return $row->user->department->region-> name ?? 'N/A';
            })
            ->addColumn('assigned_user', function ($row) {
                return $row->user->full_name() ?? 'N/A';
            })
            ->addColumn('assigned_user_mobile', function ($row) {
                return $row->user->mobile ?? 'N/A';
            })
            ->addColumn('department', function ($row) {
                return $row->user->department->name ?? 'N/A';
            })
            ->addColumn('new_value', function ($row) {
                return $row->latestOdometerHistory->new_value ?? 'N/A';
            })
            ->addColumn('action', function ($row) {
                $showOdometersUrl = route('fleet.vehicle.odometer.history', ['car_id' => $row->id]);
                return '<div class="dropdown dropdown-action">' .
                    '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                    '<div class="dropdown-menu dropdown-menu-right">' .
                     '<a class="dropdown-item" href="'. route('fleet.vehicle.odometer.overdue.workorder',$row->id) .'"><i class="fas fa-tools m-r-5"></i>Initiate Maintenance</a>' .
                    '<a class="dropdown-item" href="tel:'.$row->user->mobile.'"><i class="fa fa-phone m-r-5"></i> Call User</a>' .
                    '<a class="dropdown-item" href="mailto:'.$row->user->email.'"><i class="fa fa-mail-reply m-r-5"></i> Mail</a>' .
                    '</div></div>';
            })
            ->rawColumns(['checkbox', 'status', 'action']);

        return $dataTable;
    }

    public function query()
    {
        $query = Car::query()->whereHas('user')->whereHas('latestOdometerHistory', function ($q) {
              $q->where('odometer_status','Overdue');
           // $q->havingRaw('DATEDIFF(now(), max(created_at)) >= 4');
        });

        return $this->applyScopes($query)->orderByDesc('created_at');
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
            ->parameters([
                'buttons' => [
                    'csv',
                    'excel',
                    'print',
                    'reload'
                ],
                'dom' => "<'row'>l<'/row'>Bfrtip",
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::make('created_at')->title('Last&nbsp;Input&nbsp;Date')->printable(true)->searchable(true)->visible(true),
            Column::make('new_value')->title('Value')->addClass('text-center no-border'),
            Column::make('car_model')->title('Car&nbsp;Model')->addClass('text-center no-border'),
            Column::make('car_number')->title('Car&nbsp;Number')->addClass('text-center no-border'),
            Column::make('region')->addClass('text-center no-border'),
            Column::make('assigned_user')->title('Assigned&nbsp;User')->addClass('text-center no-border'),
            Column::make('assigned_user_mobile')->title('Phone&nbsp;Number')->addClass('text-center no-border'),
            Column::make('department')->addClass('text-center no-border'),
        ];
        // Always include the action column
        $columns[] = Column::computed('action')->addClass('text-center no-border');

        return $columns;
    }

    protected function filename():string
    {
        return 'OverdueOdometerEntryDataTable_' . date('YmdHis');
    }
}
