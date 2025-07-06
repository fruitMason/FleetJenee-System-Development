@extends('layouts.master')
@section('page_title', 'Accounts - Orders')
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
                        <h3 class="page-title">Payment History</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Accounts</a></li>
                            <li class="breadcrumb-item active">Payment History</li>
                        </ul>
                    </div>

                </div>
            </div>


            <div class="row filter-row">
                <div class="col-md-3">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating" id="paymentTypeGroupFilter">
                            <option value="">-- Select Payment Type --</option>
                            @foreach ($payment_types as $item)
                                <option value="{{ $item }}">{{ ucwords($item) }}</option>
                            @endforeach

                        </select>
                        <label class="focus-label">Status</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <button type="button" id="btnStatusFilter" class="btn btn-success w-100"> Filter </button>
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


    </div>

@endsection

@section('js')
    {!! $dataTable->scripts() !!}


    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            $('#btnStatusFilter').on('click', function() {
                var filterValue = $('#paymentTypeGroupFilter').val();                
                console.log(filterValue);


                $('#dataTableBuilder').DataTable().ajax.url(
                    '{{ route('accounts.payment.history') }}?type=' + filterValue).load();
            });
        });
    </script>

@endsection
