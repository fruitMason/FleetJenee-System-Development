<?php

namespace App\DataTables\Fleet;

use App\Models\Car;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OldCardsDataTable extends DataTable
{

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)


            ->addColumn('car_details', function ($row) {
                return $row->model;
            })
            ->addColumn('car_group', function ($row) {
                return $row->car_group;
            })
            ->addColumn('assigned_user', function ($row) {
                return !is_null($row->user) ? $row->user->full_name() : 'N/A';
            })
            ->addColumn('sector', function ($row) {
                if (!is_null($row->user))
                    return $row->user->department->region->sector->name ?? 'N/A';

                return $row->sector->name ?? 'N/A';
            })
            ->addColumn('assigned_user_mobile', function ($row) {
                return !is_null($row->user) ? $row->user->mobile : 'N/A';
            })
            ->addColumn('year', function ($row) {
                return Carbon::parse($row->year)->format('Y'); // Outputs only the year, e.g., "2020"
            })
            ->addColumn('assigned_user_department', function ($row) {
                if (!is_null($row->user))
                    return $row->user->department->name ?? 'N/A';

                return $row->department->name ?? 'N/A';
            })
            ->editColumn('status', function ($row) {
                if (strtolower($row->status) == 'due_servicing' || strtolower($row->status) == 'due_maintenance')
                    return '<div class="action-label">' .
                        '<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">' .
                        '<i class="fa fa-dot-circle-o text-danger"></i> Due Maintenance</a></div>';

                elseif (strtolower($row->status) == 'inactive')
                    return '<div class="action-label">' .
                        '<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">' .
                        '<i class="fa fa-dot-circle-o text-danger"></i> Inactive</a></div>';

                elseif (strtolower($row->status) == 'in_repairs')
                    return '<div class="action-label">' .
                        '<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">' .
                        '<i class="fa fa-dot-circle-o text-danger"></i> In Repairs</a></div>';

                elseif (strtolower($row->status) == 'in_maintenance' || strtolower($row->status) == 'maintenance')
                    return '<div class="action-label">' .
                        '<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">' .
                        '<i class="fa fa-dot-circle-o text-danger"></i> In Maintenance</a></div>';

                elseif (strtolower($row->status) == 'active')
                    return '<div class="action-label">' .
                        '<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">' .
                        '<i class="fa fa-dot-circle-o text-success"></i> Active</a></div>';
            })

            ->rawColumns(['status']);
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

        $currentYear = date('Y');
        $threeYearsAgo = $currentYear - 3;
        
        $query = Car::query()->with(['user'])
            ->where('is_archived', false)
            ->where(DB::raw("SUBSTRING(year, 1, 4)"), '<=', $threeYearsAgo)
            ->orderByDesc('created_at');





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


    protected function getColumns()
    {
        return [
            Column::make('car_number')->title('Car&nbsp;Number')->addClass('text-center no-border')->searchable(true),
            Column::make('car_details')->title('Car&nbsp;Details')->addClass('text-center no-border')->searchable(true),
            Column::make('car_group')->title('Car&nbsp;Group')->addClass('text-center no-border')->searchable(true),
            Column::make('assigned_user')->title('Assigned&nbsp;User')->addClass('text-center no-border')->searchable(true),
            Column::make('odometer')->addClass('text-center no-border')->searchable(true),
            Column::make('year')->addClass('text-center no-border')->searchable(true),
            Column::make('sector')->title('Zone')->addClass('text-center no-border')->searchable(true),
            Column::make('assigned_user_mobile')->addClass('text-center no-border')->searchable(true),
            Column::make('assigned_user_department')->addClass('text-center no-border')->searchable(true),
            Column::computed('status')->addClass('text-center no-border')->searchable(false),
            // Column::computed('action')->addClass('text-center no-border')->searchable(false),
        ];
    }




    protected function filename(): string
    {
        return 'OldCardsDataTable_' . date('YmdHis');
    }
}
