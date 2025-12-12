@extends('layouts.master')
@section('page_title', 'Drivers License')
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
                        <h3 class="page-title">Drivers </h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Fleet</a></li>
                            <li class="breadcrumb-item active">Drivers License</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                    </div>
                </div>
            </div>

            <div class="row filter-row" id="regionFilter">
                <div class="col-sm-6 col-md-4">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating" id="carGroupFilter">
                            <option value="">-- Select Driver Type --</option>
                            <option value="department_heads">Department Heads</option>
                            <option value="employed_drivers">Employed Drivers</option>
                        </select>
                        <label class="focus-label">Driver Type</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <button type="button" id="btnGroupFilter" class="btn btn-success w-100">Filter</button>
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
        $(document).ready(function () {
        $('#btnGroupFilter').on('click', function () {
            var filterValue = $('#carGroupFilter').val();

            $('#dataTableBuilder').DataTable().ajax.url('{{ route('fleet.vehicle.driver.license') }}?filter=' + filterValue).load();
        });
    });
    </script>
@endsection
