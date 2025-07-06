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
                        <h3 class="page-title">User Finder</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Accounts</a></li>
                            <li class="breadcrumb-item active">User Finder</li>
                        </ul>
                    </div>

                    <div class="col-auto float-end ms-auto">
                    </div>
                </div>
            </div>




            <div class="row" id="card_content">
                <div class="col-md-12">
                    <div class="table-responsive">

                    </div>
                </div>
            </div>

            {{-- User Finder User And Vendor --}}
            {{-- <div class="row">
                <div class="col-md-6">
                    <section class="panel panel-default">
                        <div class="card mg-b-20" id="card_content">

                            <div class="card-body">
                                <div class="card-title">
                                    <h4 class="text-success">Select User </h4>
                                </div>

                                <form action="{{ route('accounts.finder.user') }}" method="GET">
                                    <div class="row filter-row">
                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-group form-focus select-focus">
                                                <select class="select floating" name="user" id="user" required>
                                                    <option value="">-- Select User --</option>
                                                    @foreach ($users as $user)
                                                        @if (request()->get('user') == $user->id)
                                                            <option selected value="{{ $user->id }}">
                                                                {{ $user->first_name }} {{ $user->last_name }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $user->id }}"> {{ $user->first_name }}
                                                                {{ $user->last_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <label class="focus-label">Users</label>
                                            </div>
                                        </div>


                                        <div class="col-sm-6 col-md-6">
                                            <button type="submit" id="btnFilter" class="btn btn-success w-100"> View
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </section>
                </div>

                <div class="col-md-6">
                    <section class="panel panel-default">
                        <div class="card mg-b-20" id="card_content">

                            <div class="card-body">
                                <div class="card-title">
                                    <h4 class="text-success">Select Vendor</h4>
                                </div>

                                <form action="{{ route('accounts.finder.vendor') }}" method="GET">
                                    <div class="row filter-row">
                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-group form-focus select-focus">
                                                <select class="select floating" name="vendor" id="vendor" required>
                                                    <option value="">-- Select Vendor --</option>
                                                    @foreach ($vendors as $vendor)
                                                        <option value="{{ $vendor->id }}"> {{ $vendor->name }}</option>
                                                    @endforeach
                                                </select>
                                                <label class="focus-label">Vendors</label>
                                            </div>
                                        </div>


                                        <div class="col-sm-6 col-md-6">
                                            <button type="submit" id="btnFilter" class="btn btn-success w-100"> View
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </section>
                </div>
            </div> --}}
            {{-- //User Finder --}}

        
            <div class="row" id="card_content">
                <div class="col-md-12">
                    <section class="panel panel-default">
                        <div class="card mg-b-20" id="card_content">

                            <div class="card-body">
                                <div class="card-title">
                                    <h4 class="text-success">Select User</h4>
                                </div>


                                <div class="row filter-row">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group form-focus select-focus">
                                            <input class="form-control" type="text" name="advanced_filter"
                                            placeholder="Name, Mobile, Email, Department"
                                                id="advanced_filter">

                                            <label class="focus-label">Advanced Filter</label>
                                        </div>
                                    </div>


                                    <div class="col-sm-6 col-md-3">
                                        <button type="submit" id="btnAdvancedFilterButton" class="btn btn-success w-100"> Filter
                                        </button>
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

            $('#btnAdvancedFilterButton').on('click', function() {
                var filterValue = $('#advanced_filter').val();
                console.log(filterValue);


                $('#dataTableBuilder').DataTable().ajax.url(
                    '{{ route('accounts.finder.home.user') }}?advanced=' + filterValue).load();
            });
        });
    </script>

@endsection
