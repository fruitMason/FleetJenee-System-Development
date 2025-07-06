<?php

namespace App\DataTables\Account;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserFinderDataTable extends DataTable
{

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('created_at', function ($row) {
                return $row->created_at;
            })
            ->addColumn('name', function ($row) {
                return $row->full_name() ?? 'N/A';
            })
            ->addColumn('email', function ($row) {
                return $row->email;
            })
            ->addColumn('mobile', function ($row) {
                return $row->mobile;
            })
            ->editColumn('role', function ($row) {
                return $row->getRole();
            })
            ->addColumn('department', function ($row) {
                return $row->department ?  ucwords($row->department->name) : 'N/A';
            })
            ->editColumn('type', function ($row) {
                return ucwords(strtolower($row->type)) ?? 'N/A';
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'active' || $row->status == 'Approved')
                    return '<div class="action-label">' .
                        '<i class="fa fa-dot-circle-o text-success"></i> ' . ucwords($row->status) . '</div>';
                return '<div class="action-label">' .
                    '<i class="fa fa-dot-circle-o text-dander"></i> ' . ucwords($row->status) . '</div>';
            })
            ->addColumn('action', function ($row) {
                return
                    '<div class="btn-group" role="group" aria-label="Action buttons">' .
                    '<a class="btn btn-xs btn-success" href="' . route('accounts.finder.user.details', $row->id) . '"><i class="fa fa-eye text-white" aria-hidden="true"></i></a>' .
                    '</div>';
            })
            ->rawColumns(['license', 'assigned_modules', 'checkbox', 'status', 'action']);
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

        Log::info($advanced);

        $query = User::query()->with(['department'])->orderBy('first_name');

        if ($request->has('advanced')) {
            $query->where(function ($inquery) use ($advanced) {
                $inquery->where('first_name', 'LIKE', '%' . $advanced . '%')
                    ->orWhere('middle_name', 'LIKE', '%' . $advanced . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $advanced . '%')
                    ->orWhere('email', 'LIKE', '%' . $advanced . '%')
                    ->orWhere('mobile', 'LIKE', '%' . $advanced . '%')
                    ->orWhere('type', 'LIKE', '%' . $advanced . '%')
                    ->orWhere('driver_type', 'LIKE', '%' . $advanced . '%')
                    // ->orWhere('department.name', 'LIKE', '%' . $advanced . '%')
                ;
            });
        }

        return $this->applyScopes($query);
    }

    // public function query(Request $request)
    // {


    //     $request->validate([
    //         'advanced' => 'nullable|string|max:255',
    //     ]);
    //     $advanced = $request->input('advanced');


    //     // $query = Car::query();
    //     $query = Car::query()->with(['user'])
    //         ->where('is_archived', false) // Only show non-archived cars
    //         // ->whereNotNull('year')
    //         ->orderByDesc('created_at');

    //     // Filter by car group
    //     if ($request->has('carGroup')) {
    //         $carGroup = $request->input('carGroup');
    //         if ($carGroup === 'pool') {
    //             $query->where('car_group', 'pool');
    //         } elseif ($carGroup === 'assigned') {
    //             $query->where('car_group', 'assigned');
    //         }
    //     }
    //     // Filter cars that are 3 years older
    //     if ($request->has('age') && $request->input('age') === 'older_than_3_years') {
    //         $currentYear = date('Y');
    //         $threeYearsAgo = $currentYear - 3;

    //         // Extract year from the 'year' field (assumed to be 'YYYY-MM')
    //         $query->where(DB::raw("SUBSTRING(year, 1, 4)"), '<=', $threeYearsAgo);
    //     }

    //     if ($request->has('advanced')) {
    //         $query->where(function ($inquery) use ($advanced) {
    //             $inquery->where('model', 'LIKE', '%' . $advanced . '%')
    //                 ->orWhere('year', 'LIKE', '%' . $advanced . '%')
    //                 ->orWhere('car_number', 'LIKE', '%' . $advanced . '%')
    //                 ->orWhere('color', 'LIKE', '%' . $advanced . '%')
    //                 ->orWhere('car_group', 'LIKE', '%' . $advanced . '%')
    //                 ->orWhere('fuel_type', 'LIKE', '%' . $advanced . '%')
    //                 ->orWhere('engine_capacity', 'LIKE', '%' . $advanced . '%')
    //             ;
    //         });
    //     }

    //     return $this->applyScopes($query);
    // }



    // $query->join('departments', 'departments.id', '=', 'your_main_table.department_id')
    //   ->where(function ($inquery) use ($advanced) {
    //       $inquery->where('first_name', 'LIKE', '%' . $advanced . '%')
    //           ->orWhere('middle_name', 'LIKE', '%' . $advanced . '%')
    //           ->orWhere('last_name', 'LIKE', '%' . $advanced . '%')
    //           ->orWhere('email', 'LIKE', '%' . $advanced . '%')
    //           ->orWhere('mobile', 'LIKE', '%' . $advanced . '%')
    //           ->orWhere('type', 'LIKE', '%' . $advanced . '%')
    //           ->orWhere('driver_type', 'LIKE', '%' . $advanced . '%')
    //           ->orWhere('departments.name', 'LIKE', '%' . $advanced . '%');
    //   });




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
            Column::make('name')->addClass('no-border')->searchable(true),
            Column::make('email')->addClass('no-border')->searchable(true),
            Column::make('mobile')->addClass('no-border')->searchable(true),            // Column::make('car_details')->title('Car&nbsp;Details')->addClass('no-border')->searchable(true),
            Column::make('role')->title('User Permission')->addClass('no-border')->searchable(true),            // Column::make('car_details')->title('Car&nbsp;Details')->addClass('no-border')->searchable(true),
            Column::make('type')->title('Login Type')->addClass('no-border')->searchable(true),            // Column::make('car_details')->title('Car&nbsp;Details')->addClass('no-border')->searchable(true),
            Column::make('department')->addClass('no-border')->searchable(true),            // Column::make('car_details')->title('Car&nbsp;Details')->addClass('no-border')->searchable(true),
            // Column::make('car_group')->title('Car&nbsp;Group')->addClass('no-border')->searchable(true),
            // Column::make('odometer')->addClass('no-border')->searchable(true),
            // Column::make('year')->addClass('text-center')->searchable(true),
            // Column::make('assigned_user')->title('Assigned&nbsp;User')->addClass('no-border')->searchable(true),
            // Column::make('features')->title('Body| Color| Eng. Cap | Fuel')->addClass('no-border')->searchable(true),
            // Column::make('assigned_user_department')->title('Assigned Department')->addClass('no-border')->searchable(true),
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
        return 'UserFinderDataTable_' . date('YmdHis');
    }
}
