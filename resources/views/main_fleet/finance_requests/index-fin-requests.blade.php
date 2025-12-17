@extends('layouts.master')
@section('page_title', 'Accounts Invoice')
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
                        <h3 class="page-title">General Finance Requests</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Finance Requests</a></li>
                            <li class="breadcrumb-item active">General Finance Requests</li>
                        </ul>
                    </div>

                    <div class="col-auto float-end ms-auto ">
                        <a href="{{ route('finance.requests.general.create') }}" class="btn add-btn"><i
                                class="fa fa-plus"></i> New Request</a>
                        <a href="{{ route('auto.parts.index') }}" class="btn add-btn"><i class="fas fa-tools"></i>
                            Autos Part</a>
                        <a href="{{ route('finance.requests.distribution') }}" class="btn add-btn"><i
                                class="fa fa-pie-chart"></i> Requests Distribution</a>
                    </div>
                </div>
            </div>






            <div class="row" id="card_content">
                <div class="col-md-12">
                    <section class="panel panel-default">
                        <div class="card mg-b-20" id="card_content">

                            <div class="card-body">



                                <div class="row filter-row">

                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group form-focus select-focus">
                                            <select class="select floating" id="statusGroupFilter">
                                                <option value="">-- Select Fin Status --</option>
                                                <option value="pending">Pending</option>
                                                <option value="paid">Paid</option>
                                                <option value="partially paid">Partially Paid</option>
                                            </select>
                                            <label class="focus-label">Status</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <button type="button" id="btnStatusFilter" class="btn btn-success w-100">
                                            Filter </button>
                                    </div>
                                </div>



                                <div class="table-responsive">
                                    {!! $dataTable->table(['class' => 'table table-striped custom-table']) !!}
                                </div>
                            </div>
                        </div>
                    </section> 
                </div>
            </div>
            {{-- //Car Finder --}}

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
                var filterValue = $('#statusGroupFilter').val();
                console.log(filterValue);


                $('#dataTableBuilder').DataTable().ajax.url(
                    '{{ route('finance.requests.home') }}?filter=' + filterValue).load();
            });
        });
    </script>

@endsection
