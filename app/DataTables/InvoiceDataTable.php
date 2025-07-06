<?php

namespace App\DataTables;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class InvoiceDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('created_at', function ($row) {
                return $row->created_at;
            })
            ->addColumn('created_date', function ($row) {
                return Carbon::parse($row->created_at)->format('D, d F Y');
            })
            ->addColumn('due_date', function ($row) {
                return Carbon::parse($row->due_date)->format('D, d F Y');
            })
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" id="basic_checkbox_' . $row->id . '" class="filled-in">' .
                    '<label for="basic_checkbox_' . $row->id . '" class="mb-0 h-15 ms-15"></label>';
            })
            ->editColumn('vendor_id', function ($row) {
                return $row->vendor->name;
            })
            ->editColumn('invoice_number', function ($row) {
                return '#' . $row->invoice_number;
            })
            ->editColumn('net_total', function ($row) {
                return number_format($row->net_total, 2);
            })
            ->editColumn('sub_total', function ($row) {
                return number_format($row->sub_total, 2);
            })
            ->editColumn('tax_total', function ($row) {
                return number_format($row->tax_total, 2);
            })
            ->editColumn('status', function ($row) {
                return $this->formatStatus($row->status);
            })
            ->editColumn('fin_status', function ($row) {

                //'onclick="notifyAccountModal(' . $row->id . ',\'' .  $row->invoice_number . '\',\'' . $row->vendor->name . ' (Msg: ' . $row->message . ' )' . '\',\'' . number_format($row->net_total, 2) . '\')">'
                // return $row->fin_status ?
                //     $this->formatFinStatus($row->fin_status) : 
                //           $role == "ADMINISTRATOR" ?
                //         '<a class="btn btn-success text-white btn-sm" href="' . route('finance.invoice.submittofinance.create', $row->id) . '">Push To Finance</a>'
                //         : 'Finance Not Notified');
                $role = Auth::user()->type;

                return $row->fin_status ?
                    $this->formatFinStatus($row->fin_status) : ($role == "ADMINISTRATOR"  ?
                        '<a class="btn btn-success text-white btn-sm" href="' . route('finance.invoice.submittofinance.create', $row->id) . '">Push To Finance</a>'
                        : 'Finance Not Notified');
            })
            ->addColumn('action', function ($row) {
                return $this->generateActionButtons($row);
            })
            ->rawColumns(['checkbox', 'status', 'action', 'fin_status']);
    }

    protected function formatStatus($status)
    {
        switch (strtolower($status)) {
            case 'paid':
                return '<span class="badge bg-inverse-success">Paid</span>';
            case 'partially paid':
                return '<span class="badge bg-inverse-warning">Partially Paid</span>';
            default:
                return '<span class="badge bg-inverse-info">Pending</span>';
        }
    }

    protected function formatFinStatus($status)
    {
        switch (strtolower($status)) {
            case 'paid':
                return '<span class="text-success">Paid</span>';
            case 'partially paid':
                return '<span class="text-warning">Partially Paid</span>';
            default:
                return '<span class="text-info">Pending</span>';
        }
    }

    protected function generateActionButtons($row)
    {

        if ($row->fin_status == null) {

            return      '<div class="dropdown dropdown-action">' .
                '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                '<div class="dropdown-menu dropdown-menu-right">' .
                '<a class="dropdown-item" href="#"><i class="fa fa-pencil m-r-5"></i> Edit</a>' .
                '<a class="dropdown-item" href="' . route('finance.invoice.view', $row->id) . '"><i class="fa fa-eye m-r-5"></i> View</a>' .
                '<a class="dropdown-item" href="#"><i class="fa fa-file-pdf-o m-r-5"></i> Download</a>' .
                '<a class="dropdown-item" href="#"><i class="fa fa-trash-o m-r-5"></i> Delete</a>' .
                '</div></div>';
        } else {
            if ($row->fin_status == 'pending') {
                return      '<div class="dropdown dropdown-action">' .
                    '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
                    '<div class="dropdown-menu dropdown-menu-right">' .
                    '<a class="dropdown-item" href="#"><i class="fa fa-pencil m-r-5"></i> Edit</a>' .
                    '<a class="dropdown-item" href="' . route('finance.invoice.view', $row->id) . '"><i class="fa fa-eye m-r-5"></i> View</a>' .
                    '<a class="dropdown-item" href="#"><i class="fa fa-file-pdf-o m-r-5"></i> Download</a>' .
                    '<a class="dropdown-item" href="#"><i class="fa fa-trash-o m-r-5"></i> Delete</a>' .
                    '</div></div>';
            }
        }

        return      '<div class="dropdown dropdown-action">' .
            '<a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>' .
            '<div class="dropdown-menu dropdown-menu-right">' .
            // '<a class="dropdown-item" href="#"><i class="fa fa-pencil m-r-5"></i> Edit</a>' .
            '<a class="dropdown-item" href="' . route('finance.invoice.view', $row->id) . '"><i class="fa fa-eye m-r-5"></i> View</a>' .
            // '<a class="dropdown-item" href="#" onclick="changeStatusNotify(' . $row->id . ')"><i class="fa fa-edit m-r-5"></i> Change Status</a>' .
            '<a class="dropdown-item" href="#"><i class="fa fa-file-pdf-o m-r-5"></i> Download</a>' .
            '</div></div>';
    }

    public function query()
    {
        $from = $this->request()->filled('from') ? Carbon::createFromFormat('d/m/Y', $this->request()->get('from'))->toDateString() : Carbon::now()->startOfYear();
        $to = $this->request()->filled('to') ? Carbon::createFromFormat('d/m/Y', $this->request()->get('to'))->toDateString() : Carbon::now()->endOfYear();
        $date = [$from . ' 00:00:00', $to . ' 23:59:59'];

        $status = $this->request()->get('status') != '-- Select Status --' && $this->request()->get('status') != null ? ['status' => $this->request()->get('status')] : [];
        Log::info($date);
        Log::info($status);
        $query = Invoice::query()->with(['vendor', 'invoiceItem'])->whereBetween('created_at', $date)->where($status)->orderByDesc('created_at');

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
            Column::make('created_at')->printable(false)->searchable(false)->visible(false),
            Column::make('invoice_number')->title('Invoice&nbsp;Number')->addClass('text-center no-border'),
            Column::make('status')->addClass('text-center no-border'),
            Column::make('vendor_id')->title('Vendor')->addClass('text-center no-border'),
            Column::make('created_date')->addClass('text-center no-border'),
            Column::make('due_date')->addClass('text-center no-border'),
            Column::make('sub_total')->addClass('text-center no-border'),
            Column::make('tax_total')->title('Tax')->addClass('text-center no-border'),
            Column::make('net_total')->addClass('no-border'),
            Column::make('fin_status')->addClass('no-border'),
            Column::computed('action')->addClass('text-center no-border'),
        ];
    }

    protected function filename(): string
    {
        return 'InvoiceDataTable_' . date('YmdHis');
    }
}
