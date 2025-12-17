@extends('layouts.master')
@section('page_title', 'Odometer History')
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
                        <h3 class="page-title">Odometer History</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Driver</a></li>
                            <li class="breadcrumb-item active">Odometer History</li>
                        </ul>
                    </div>

                    @if (auth()->user()->hasCar())
                        <div class="col-auto float-end ms-auto">
                            <a href="#" class="btn add-btn" data-bs-toggle="modal"
                                data-bs-target="#add_odometer_modal"><i class="fa fa-plus"></i> Add New</a>
                        </div>
                    @else
                        <div class="col-auto float-end ms-auto">
                            <p class="text-warning">No Car Assigned At The Moment!</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'table table-striped custom-table']) !!}
                    </div>
                </div>
            </div>
        </div>

        @if (auth()->user()->hasCar())
            @include('modal.add_odometer')
        @endif
    </div>

@endsection

@section('js')
    {!! $dataTable->scripts() !!}

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>

@endsection
