<?php

namespace App\DataTables;

use App\Models\CarRequest;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CarRequestDataTable extends DataTable
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
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" id="basic_checkbox_' . $row->id . '" class="filled-in">' .
                    '<label for="basic_checkbox_' . $row->id . '" class="mb-0 h-15 ms-15"></label>';
            })
            ->addColumn('date_needed', function ($row) {
                return Carbon::parse($row->date_needed)->format('D, d F Y');
            })
            ->addColumn('return_date', function ($row) {
                return Carbon::parse($row->return_date)->format('D, d F Y');
            })
            ->editColumn('user_id', function ($row) {
                return !is_null($row->user) ? $row->user->full_name() . ' (' . $row->user->getRole() . ')' : 'N/A';
            })
            ->addColumn('car_group', function ($row) {
                return !is_null($row->user) ? 'Non Pool' : 'Pool';
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
                $requester = !is_null($row->user) ? $row->user->full_name() . ' (' . $row->user->getRole() . ')' : 'N/A';
                if ($row->status == 'pending')
                    //'onclick="notifyAccountModal(' . $row->id . ',\'' .  $row->request_reson  . '\',\'' . $requester  . '\')">'
                    return '<div class="dropdown dropdown-action">' .
                        '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                        '<div class="dropdown-menu dropdown-menu-right">' .
                        '<a class="dropdown-item" href="#"' .
                        'onclick="approveNotify(' . $row->id . ',' . $row->user->id . ',\'' .  $row->request_reason  . '\',\'' . $requester  . '\')"><i class="fa fa-check m-r-5"></i> Approve</a>' .
                        // '<a class="dropdown-item" href="#"' .
                        // 'onclick="rejectNotify(' . $row->id . ')"><i class="fa fa-ban m-r-5"></i> Reject</a>' .
                        '</div></div>';
            })
            ->rawColumns(['checkbox', 'status', 'action', 'car_group']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query()
    {
        $user_id = $this->request()->get('user_id') != '-- Select User --' && $this->request()->get('user_id') != null ? ['user_id' => $this->request()->get('user_id')] : [];
        $status   = $this->request()->get('status') != '-- Select Status --' && $this->request()->get('status') != null ? ['status' => $this->request()->get('status')] : [];
        $user = $this->user_id != null ? ['user_id' => $this->user_id] : [];
        $query = CarRequest::query()->with(['user'])->orderByDesc('created_at')->where($user_id)->where($user)->where($status);
        if ($this->request()->get('car_group') == 'pool') {
            $query->whereNull('user_id');
        } elseif ($this->request()->get('car_group') == 'non_pool') {
            $query->whereNotNull('user_id');
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
        if (!is_null($this->user_id))
            return $this->builder()
                ->columns($this->getColumns())
                ->setTableId('carRequestDataTable')
                ->minifiedAjax(route('settings.user.car.requests', $this->user_id))
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
            Column::make('created_at')->printable(false)->searchable(false)->visible(false),
            Column::make('checkbox')->title('<input type="checkbox" id="basic_checkbox" class="filled-in"><label for="basic_checkbox" class="mb-0 h-15 ms-15"></label>')->addClass('text-center no-border')->searchable(false)->printable(false)->exportable(false),
            Column::make('date_needed')->title('Request&nbsp;Date')->addClass('text-center no-border'),
            Column::make('return_date')->title('Return&nbsp;Date')->addClass('text-center no-border'),
            Column::make('user_id')->title('Assigned&nbsp;User')->addClass('text-center no-border'),
            Column::make('request_reason')->title('Request&nbsp;Purpose')->addClass('text-center no-border'),
            Column::computed('car_group')->title('Car Group')->addClass('text-center no-border'),
            Column::computed('status')->addClass('text-center no-border'),
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
        return 'CarRegistrationDataTable_' . date('YmdHis');
    }
}
