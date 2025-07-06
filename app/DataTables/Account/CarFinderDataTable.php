<?php

namespace App\DataTables\Account;

use App\Models\Car;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarFinderDataTable extends DataTable
{

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('created_at', function ($row) {
                return $row->created_at;
            })
            ->addColumn('car_details', function ($row) {
                return $row->model;
            })
            ->addColumn('car_group', function ($row) {
                return ucwords($row->car_group);
            })
            ->addColumn('assigned_user', function ($row) {
                return !is_null($row->user) ? $row->user->full_name()  : 'N/A';
            })
            ->addColumn('assigned_user_department', function ($row) {
                if (!is_null($row->user))
                    return $row->user->department->name ?? 'N/A';

                return $row->department->name ?? 'N/A';
            })
            ->addColumn('year', function ($row) {
                return Carbon::parse($row->year)->format('Y'); // Outputs only the year, e.g., "2020" 
            })

            ->addColumn('features', function ($row) {
                return '<i class="fa fa-car" style="color: ' . $row->color . '"> </i> ' . ucwords($row->body_style) . ' | ' . ucwords($row->color) . ' | ' . ucwords($row->engine_capacity)
                    . ' | ' . ucwords($row->fuel_type);
            })

            ->editColumn('status', function ($row) {
                if (strtolower($row->status) == 'due_servicing' || strtolower($row->status) == 'due_maintenance')
                    return '<div class="action-label">' .
                        '<i class="fa fa-dot-circle-o text-danger"></i> Due Maintenance</div>';

                elseif (strtolower($row->status) == 'inactive')
                    return '<div class="action-label">' .
                        '<i class="fa fa-dot-circle-o text-danger"></i> Inactive</div>';

                elseif (strtolower($row->status) == 'in_repairs')
                    return '<div class="action-label">' .
                        '<i class="fa fa-dot-circle-o text-danger"></i> In Repairs</div>';

                elseif (strtolower($row->status) == 'in_maintenance' || strtolower($row->status) == 'maintenance')
                    return '<div class="action-label">' .
                        '<i class="fa fa-dot-circle-o text-danger"></i> In Maintenance</div>';

                elseif (strtolower($row->status) == 'active')
                    return '<div class="action-label badge-sucess">' .
                        '<i class="fa fa-dot-circle-o text-success"></i> Active</div>';
            })
            ->addColumn('action', function ($row) {
                return
                    '<div class="btn-group" role="group" aria-label="Action buttons">' .
                    '<a class="btn btn-xs btn-success mr-4" href="' . route('accounts.finder.car.details', [$row->id]) . '" target="_self"><i class="fa fa-eye text-white" aria-hidden="true"></i></a>' .

                    '</div>';
            })
            ->rawColumns(['status', 'action', 'features']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Request $request)
    {


        $request->validate([
            'advanced' => 'nullable|string|max:255',
        ]);
        $advanced = $request->input('advanced');


        // $query = Car::query();
        $query = Car::query()->with(['user'])
            ->where('is_archived', false) // Only show non-archived cars
            // ->whereNotNull('year')
            ->orderByDesc('created_at');

        // Filter by car group
        if ($request->has('carGroup')) {
            $carGroup = $request->input('carGroup');
            if ($carGroup === 'pool') {
                $query->where('car_group', 'pool');
            } elseif ($carGroup === 'assigned') {
                $query->where('car_group', 'assigned');
            }
        }
        // Filter cars that are 3 years older
        if ($request->has('age') && $request->input('age') === 'older_than_3_years') {
            $currentYear = date('Y');
            $threeYearsAgo = $currentYear - 3;

            // Extract year from the 'year' field (assumed to be 'YYYY-MM')
            $query->where(DB::raw("SUBSTRING(year, 1, 4)"), '<=', $threeYearsAgo);
        }

        if ($request->has('advanced')) {
            $query->where(function ($inquery) use ($advanced) {
                $inquery->where('model', 'LIKE', '%' . $advanced . '%')
                    ->orWhere('year', 'LIKE', '%' . $advanced . '%')
                    ->orWhere('car_number', 'LIKE', '%' . $advanced . '%')
                    ->orWhere('color', 'LIKE', '%' . $advanced . '%')
                    ->orWhere('car_group', 'LIKE', '%' . $advanced . '%')
                    ->orWhere('fuel_type', 'LIKE', '%' . $advanced . '%')
                    ->orWhere('engine_capacity', 'LIKE', '%' . $advanced . '%')
                ;
            });
        }

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
            Column::make('car_number')->title('Car&nbsp;Number')->addClass('no-border')->searchable(true),
            Column::make('car_details')->title('Car&nbsp;Details')->addClass('no-border')->searchable(true),
            Column::make('car_group')->title('Car&nbsp;Group')->addClass('no-border')->searchable(true),
            Column::make('odometer')->addClass('no-border')->searchable(true),
            Column::make('year')->addClass('text-center')->searchable(true),
            Column::make('assigned_user')->title('Assigned&nbsp;User')->addClass('no-border')->searchable(true),
            Column::make('features')->title('Body| Color| Eng. Cap | Fuel')->addClass('no-border')->searchable(true),
            Column::make('assigned_user_department')->title('Assigned Department')->addClass('no-border')->searchable(true),
            Column::computed('status')->addClass('no-border')->searchable(false),
            Column::computed('action')->addClass('no-border')->searchable(false),
        ];
    }



    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'CarFinderDataTable_' . date('YmdHis');
    }
}
