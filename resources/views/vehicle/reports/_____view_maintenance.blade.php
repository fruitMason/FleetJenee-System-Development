@extends('layouts.master')

@section('page_title', 'View Maintenance Work Order')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="col-md-12">
                @include('includes.error')
            </div>

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Work Order</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Vehicle</a></li>
                            <li class="breadcrumb-item active">Work Order</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="{{ route('fleet.vehicle.maintenance') }}" class="btn add-btn"><i
                                class="fa fa-arrow-left"></i> Back to List</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <section class="panel panel-default">
                        <div class="card mg-b-20" id="card_content">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="title">Car:</div>
                                        <div class="text">{{ $maintenance->car->model ?? 'N/A' }}
                                            ({{ $maintenance->car->car_number ?? 'N/A' }}) </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>Mechanic:</div>
                                        <div>{{ $maintenance->mechanic->full_name() ?? 'N/A' }}</div>
                                    </div>
                                </div>

                                <div class="row mb-4">

                                    <div class="col-md-6">
                                        <div>Maintenance Type:</div>
                                        <div>{{ $maintenance->type ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>Mechanic Status:</div>
                                        <div>{{ $maintenance->status ?? 'N/A' }}</div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div>Start Date:</div>
                                        <div>
                                            {{ $maintenance->start_date ? $maintenance->start_date->format('D, d F Y H:i A') : 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>End Date:</div>
                                        <div>
                                            {{ $maintenance->end_date ? $maintenance->end_date->format('D, d F Y H:i A') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div>Comments:</div>
                                        <div>{{ $maintenance->comment ?? 'N/A' }}</div>
                                    </div>
                                </div>

                                <hr>
                                {{-- finance detials --}}
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div>Finance Review Status:</div>
                                        <div>
                                            {{ $maintenance->fin_status }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>Date Authorized:</div>
                                        <div>
                                            {{ $maintenance->findate ? $maintenance->fin_date->format('D, d F Y H:i A') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div>Finance Comments:</div>
                                        <div>{{ $maintenance->fin_comment ?? 'N/A' }}</div>
                                    </div>

                                    <div class="col-md-6">
                                        <div>Finance Officer:</div>
                                        <div>{{ $maintenance->fin_user ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                {{--  --}}

                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{ route('fleet.vehicle.maintenance') }}" class="btn btn-primary"><i
                                                class="fa fa-arrow-left"></i> Back to List</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection
