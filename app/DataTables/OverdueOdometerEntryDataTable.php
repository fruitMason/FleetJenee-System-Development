<?php

namespace App\DataTables;

use App\Models\Car;
use App\Models\OdometerHistory;
use Illuminate\Support\Facades\Log;
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
                return optional($row->latestOdometer)
                    ->created_at?->format('d/m/Y h:i:s A') ?? 'N/A';
                // $odo = OdometerHistory::where('car_id',$row->id)->orderBy('created_at','desc')->first();
                // return $odo->created_at->format('d/m/yy H:i:s A');// $row->latestOdometerHistory->created_at->format('d/m/y H:i:s A');
            })
            ->addColumn('car_model', function ($row) {
                return $row->model ?? 'N/A';
            })
            ->addColumn('car_number', function ($row) {
                return $row->car_number ?? 'N/A';
            })
            ->addColumn('zone', function ($row) {
                return optional($row->latestOdometer)->user->department->region->sector->name ?? 'N/A';
            })
            ->addColumn('region', function ($row) {
                return optional($row->latestOdometer)->user->department->region->name ?? 'N/A';
            })
            ->addColumn('assigned_user', function ($row) {
                return optional($row->latestOdometer)->user->full_name() ?? 'N/A'; // $row->user->;
            })
            ->addColumn('assigned_user_mobile', function ($row) {
                return  optional($row->latestOdometer)->user->mobile ?? 'N/A';
            })
            ->addColumn('department', function ($row) {
                return optional($row->latestOdometer)->user->department->name ?? 'N/A';
            })
            ->addColumn('new_value', function ($row) {
                return optional($row->latestOdometer)
                    ->new_value ?? 'N/A';
                return ''; //$row->latestOdometerHistory->new_value ?? 'N/A';
            })
            ->addColumn('action', function ($row) {
                
                $showOdometersUrl = route('fleet.vehicle.odometer.history', ['car_id' => $row->id]);
                return '<div class="dropdown dropdown-action">' .
                    '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                    '<div class="dropdown-menu dropdown-menu-right">' .
                    '<a class="dropdown-item" href="' . route('fleet.vehicle.odometer.overdue.workorder', $row->id) . '"><i class="fas fa-tools m-r-5"></i>Initiate Maintenance</a>' .
                    // '<a class="dropdown-item" href="tel:' . optional($row->latestOdometer)->user->mobile . '"><i class="fa fa-phone m-r-5"></i> Call User</a>' .
                    // '<a class="dropdown-item" href="mailto:' . optional($row->latestOdometer)->user->email . '"><i class="fa fa-mail-reply m-r-5"></i> Mail</a>' .
                    '</div></div>';
            })
            ->rawColumns(['checkbox', 'status', 'action']);

        return $dataTable;
    }

    public function query()
    {
        $query = Car::with('latestOdometer')->where('odometer_status', 'Overdue');
        //  Car::query()->select('model','car_number','id','odometer')->
        // $q->havingRaw('DATEDIFF(now(), max(created_at)) >= 4');

        $qu = $query->get();
        // Log::info($qu->latestOdometerHistory());
        //  Car::query()->whereHas('user')->whereHas('latestOdometerHistory', function ($q) {
        //       $q->where('odometer_status','Overdue');
        //    // $q->havingRaw('DATEDIFF(now(), max(created_at)) >= 4');
        // });
        //  $query =
        //          Car::query()->where('odometer_status','Overdue');

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

    protected function filename(): string
    {
        return 'OverdueOdometerEntryDataTable_' . date('YmdHis');
    }
}
