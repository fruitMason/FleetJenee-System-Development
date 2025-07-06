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
                        <h3 class="page-title">Car Finder</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Accounts</a></li>
                            <li class="breadcrumb-item active">Car Finder</li>
                        </ul>
                    </div>

                    <div class="col-auto float-end ms-auto">
                    </div>
                </div>
            </div>

 
            {{-- Car Finder --}}

            <div class="row" id="card_content">
                <div class="col-md-12">
                    <section class="panel panel-default">
                        <div class="card mg-b-20" id="card_content">

                            <div class="card-body">
                                <div class="card-title">
                                    <h4 class="text-success">Find Vehicle</h4>
                                </div>


                                <div class="row filter-row">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group form-focus select-focus">
                                            <input class="form-control" type="text" name="advanced_filter"
                                                placeholder="Car Model, Year, Number, Color, Group, Fuel"
                                                id="advanced_filter">

                                            <label class="focus-label">Advanced Filter</label>
                                        </div>
                                    </div>


                                    <div class="col-sm-6 col-md-3">
                                        <button type="submit" id="btnAdvancedFilterButton" class="btn btn-success w-100">
                                            Filter
                                        </button>
                                    </div>
                                </div>


                                <div class="table-responsive">
                                    {!! $dataTable->table(['class' => 'table custom-table table-stripped table-hover']) !!}
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

            $('#btnAdvancedFilterButton').on('click', function() {
                var filterValue = $('#advanced_filter').val();
                console.log(filterValue);


                $('#dataTableBuilder').DataTable().ajax.url(
                    '{{ route('accounts.finder.home.car') }}?advanced=' + filterValue).load();
            });
        });
    </script>

@endsection
