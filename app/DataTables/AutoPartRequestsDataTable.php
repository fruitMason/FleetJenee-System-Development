<?php

namespace App\DataTables;

use App\Models\AutoPartRequest;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;

class AutoPartRequestsDataTable extends DataTable
{

    public function dataTable($query)
    {


        return datatables()
            ->eloquent($query)
            ->addColumn('created_at', function ($row) {
                return $row->created_at;
            })

            ->addColumn('requested_by', function ($row) {
                return $row->user->full_name();
            })
            ->addColumn('car', function ($row) {
                return $row->car->car_features();
            })

            ->addColumn('auto_part', function ($row) {
                return $row->auto_part->name;
            })
            ->addColumn('request_type', function ($row) {
                return $row->request_type;
            })
            ->addColumn('qnt_requested', function ($row) {
                return $row->qnt_requested;
            })
            ->addColumn('qnt_approved', function ($row) {
                return $row->qnt_approved;
            })
            ->addColumn('reason_for_request', function ($row) {
                return $row->reason_for_request;
            })



            ->editColumn('status', function ($row) {
                if (strtolower($row->status) == 'pending')
                    return '<div class="action-label">' .
                        '<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">' .
                        '<i class="fa fa-dot-circle-o text-warning"></i> Pending</a></div>';

                elseif (strtolower($row->status) == 'rejected')
                    return '<div class="action-label">' .
                        '<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">' .
                        '<i class="fa fa-dot-circle-o text-danger"></i> Rejected</a></div>';

                elseif (strtolower($row->status) == 'approved')
                    return '<div class="action-label">' .
                        '<a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">' .
                        '<i class="fa fa-dot-circle-o text-success"></i> Approved</a></div>';
            })
            ->addColumn('action', function ($row) {
                if (auth()->user()->hasRole('FLEET MANAGER')) {

                    if ($row->status == 'pending' || $row->status == 'partially paid') {
                        return
                            '<a class="btn btn-success text-white btn-sm" href="' . route('inventory.usage.show', $row->id) . '">'
                            . '<span class="badge badge-light"><i class="fas fa-tools"></i></span>' .
                            'Approve' .
                            '</a>';
                    }
                } else {
                    if ($row->status == 'pending')
                        return
                            '<form method="post" action="' . route('driver.auto.request.destroy', $row->id) . '"
                            onsubmit="return SubmitDelete(this,\'Delete Auto Part [' . $row->auto_part->name . '] Request \');" >' .
                            csrf_field() .
                            method_field('DELETE') .

                            '<button type="submit" class="btn btn-xs btn-danger" href="#" onclick="archiveNotify(' . $row->id . ')">
                            <i class="fa fa-trash text-white" aria-hidden="true"></i>
                            </button>' .
                            '</form>' .
                            '</div>';
                }
            })
            ->rawColumns(['checkbox', 'status', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Request $request)
    {

        $status = $request->input('filter');
        $applied_status = 'pending';


        if ($request->input('filter')) {
            $applied_status =  $status;
        }


        $query = AutoPartRequest::query()->whereBelongsTo(Auth::user())->orderByDesc('created_at');
        if (auth()->user()->hasRole('FLEET MANAGER')) {
            $query = AutoPartRequest::query()
                ->where('status', 'like', '%' . $applied_status . '%')->orderByDesc('created_at');
        }
        return $this->applyScopes($query);




        //  'auto_part_id',
        // 'user_id',
        // 'request_type',
        // 'qnt_requested',
        // 'reason_for_request',
        // 'qnt_given',
        // 'status',
        // 'auth_by',
        // 'reason_for_decline'

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
        $showForAdmin = auth()->user()->hasRole('FLEET MANAGER');
        return [
            Column::make('created_at')->printable(false)->searchable(false)->visible(false),
            Column::makeIf($showForAdmin, 'requested_by')->addClass('text-center no-border'),
            // Column::make('checkbox')->title('<input type="checkbox" id="basic_checkbox" class="filled-in"><label for="basic_checkbox" class="mb-0 h-15 ms-15"></label>')->addClass('text-center no-border')->searchable(false)->printable(false)->exportable(false),
            Column::make('car')->addClass('text-center no-border'),
            Column::make('auto_part')->addClass('text-center no-border'),
            Column::make('request_type')->addClass('text-center no-border'),
            Column::make('qnt_requested')->addClass('text-center no-border'),
            Column::make('qnt_approved')->addClass('text-center no-border'),
            Column::make('reason_for_request')->addClass('text-center no-border'),
            Column::make('status')->addClass('text-center no-border'),
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
        return 'AutoPartRequestsDataTable_' . date('YmdHis');
    }
}
