@extends('layouts.master')
@section('page_title', 'Invoice')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="col-md-12">@include('includes.error')</div>

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Invoice</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Finance</a></li>
                            <li class="breadcrumb-item active">Invoice</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="{{ route('finance.invoice.create') }}" class="btn add-btn"><i class="fa fa-plus"></i> Add
                            New</a>
                    </div>
                    <div class="col-auto float-end ms-auto">
                    </div>
                </div>
            </div>

            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text" id="from">
                        </div>
                        <label class="focus-label">From</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text" id="to">
                        </div>
                        <label class="focus-label">To</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating" id="status">
                            <option>-- Select Status --</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="partially_paid">Partially Paid</option>
                        </select>
                        <label class="focus-label">Status</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <button type="button" id="btnFilter" class="btn btn-success w-100"> Filter </button>
                </div>
            </div>

            <div class="row" id="card_content">
                <div class="col-md-12">
                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'table table-striped custom-table']) !!}
                    </div>
                </div>
            </div>
        </div>

        @include('modal.update_invoice_status')

        @include('modal.push_maintenance_to_finance')

    </div>

@endsection

@section('js')
    {!! $dataTable->scripts() !!}

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>


    <script>
        $(document).ready(function() {
            console.log('document ready');

            $('.user-search2').select2();

        });

        modal_select_search($('.create_select_search'), $('#form_create'));
        modal_select_search($('.edit_select_search'), $('#form_edit'));

        $(document).ready(function() {
            $('#dataTableBuilder')
                .on('preXhr.dt', function(e, settings, data) {
                    data.from = $('#from').val();
                    data.to = $('#to').val();
                    data.status = $('#status option:selected').val();
                });

            $('#btnFilter').on('click', function() {
                $('#dataTableBuilder').DataTable().ajax.reload();
                return false;
            });
        });

        function changeStatusNotify(id) {
            waitme('card_content');
            setTimeout(function() {
                hidewaitme('card_content');
                $('#id').val(id).change();
                $('#update_invoice_status_modal').modal('show');
            }, 500);
        }



        function notifyAccountModal(id, invoice_number, vendor, total) { //id,invoice_number,total||
            console.log(id);

            waitme('card_content');
            setTimeout(function() {
                hidewaitme('card_content');
                document.getElementById('invoice').value = invoice_number;
                document.getElementById('tid').value = id;
                document.getElementById('vendor').value = vendor;
                document.getElementById('total').value = total;
                $('#push_maintenance_to_finance').modal('show');
            }, 500);
        }
    </script>
@endsection
