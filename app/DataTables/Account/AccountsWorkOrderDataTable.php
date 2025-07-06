<?php

namespace App\DataTables\Account;

use App\Models\CarMaintenance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;

use function Illuminate\Log\log;

class AccountsWorkOrderDataTable extends DataTable
{

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query) //
            ->addColumn('created_at', function ($row) {
                return $row->created_at ? Carbon::parse($row->created_at)->format('d F Y H:i A') : 'N/A';
            })
            ->editColumn('start_date', function ($row) {
                return Carbon::parse($row->start_date)->format('d F Y') ?? 'N/A';
            })
            ->editColumn('end_date', function ($row) {
                return $row->end_date ? Carbon::parse($row->end_date)->format('d F Y') : 'N/A';
            })
            ->addColumn('car', function ($row) {
                return $row->car ? $row->car->model . ' (' . $row->car->car_number . ')' : 'N/A';
            })
            ->addColumn('mechanic', function ($row) {
                return $row->mechanic ? $row->mechanic->full_name() : 'N/A';
            })
            ->addColumn('status', function ($row) {

                if ($row->status == 'paid') {
                    return ' <span class="badge bg-inverse-success">' . ucwords($row->status) . '</span>';
                }
                if ($row->status == 'pending') {
                    return '<span class="badge bg-inverse-info">' . ucwords($row->status) . '</span>';
                }
                if ($row->status == 'diagnosed') {
                    return '<span class="badge bg-inverse-warning">' . ucwords($row->status) . '</span>';
                }
                return '<span class="badge bg-dark">' . ucwords($row->status) . '</span>';
            })
            ->addColumn('type', function ($row) {
                return ucwords($row->type);
            })
            ->addColumn('fin_status', function ($row) {
                if ($row->fin_status == 'pending') {
                    return '<span class="badge bg-warning text-white">' . ucwords($row->fin_status) . '</span>';
                }
                if ($row->fin_status == 'declined') {
                    return '<span class="badge bg-danger text-white">' . ucwords($row->fin_status) . '</span>';
                }
                return '<span class="badge bg-success text-white">' . ucwords($row->fin_status) . '</span>';
            })
            ->addColumn('fin_user', function ($row) {
                return $row->fin_user;
            })

            ->addColumn('action', function ($row) {
                return '<div class="btn-group" role="group" aria-label="Action buttons">' .
                    '<a class="btn btn-xs btn-success" href="' . route('accounts.orders.details.show', [$row->id]) . '" target="_self"><i class="fa fa-eye text-white" aria-hidden="true"></i></a>' .
                    '</div>';
            })
            ->rawColumns(['checkbox', 'status', 'fin_status', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Request $request)
    {
        log($this->request()->get('filter'));
        $query = CarMaintenance::query()
            ->with(['car', 'mechanic']) 
            ->orderByDesc('start_date');

        if ($this->request()->get('filter')) {
            $finStatus = $this->request()->get('filter');
            if ($finStatus === '-- Select Fin Status --') {
            } else
                $query->whereLike('fin_status', $finStatus);
        }

        return $this->applyScopes($query);
    }

    // public function query()
    // {
    //     $query = User::query()->whereNotNull('license_expiry')->orderByDesc('created_at');

    //     if ($this->request()->get('filter') === 'department_heads') {
    //         // Department heads: users with assigned cars
    //         $query->whereHas('car'); // Assuming a relationship `car` exists
    //     } elseif ($this->request()->get('filter') === 'employed_drivers') {
    //         // Employed drivers: users who drive pool cars
    //         $query->whereDoesntHave('car'); // No assigned car
    //     }

    //     return $this->applyScopes($query);
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
            ->orderBy(0)
            ->parameters([
                'buttons' => [
                    'csv',   // Include CSV export
                    'excel', // Include Excel export
                    'print', // Include Print button
                    'reload' // Include Reload button
                    // PDF export button is removed
                ],
                'dom' => "<'row'>l<'/row'>Bfrtip",
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('car')->title('Car Detail'),
            Column::make('mechanic')->title('Mechanic')->addClass('text-center no-border'),
            Column::make('status')->title('Mec Status')->addClass('text-center no-border'),
            Column::make('start_date')->title('Start Date')->addClass('text-center no-border'),
            Column::make('end_date')->title('End Date')->addClass('text-center no-border'),
            Column::make('type')->title('Order Type')->addClass('text-center no-border'),
            Column::make('fin_status')->title('Fin Status')->addClass('text-center no-border'),
            Column::make('created_at')->title('Created At')->addClass('text-center no-border'),
            // Column::make('status')->addClass('text-center no-border'),
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
        return 'AccountsWorkOrderDataTable_' . date('YmdHis');
    }
}
