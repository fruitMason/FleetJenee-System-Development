@extends('layouts.master')
@section('page_title', 'Dashboard')
@section('content')
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Welcome {{ auth()->user()->first_name }}, {{ auth()->user()->getRole() }}
                            ({{ str_replace('_', ' ', auth()->user()->driver_type) }})
                        </h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>

            @php
                $drivertype = Auth::user()->driver_type;
            @endphp
            @if ($drivertype == 'DEPARTMENT_HEAD')
                <div class="row">

                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa fa-cubes"></i></span>
                                <div class="dash-widget-info">
                                    <h3>{{ $total_car_requests }}</h3>
                                    <span>Total Car Requests</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa fa-cubes"></i></span>
                                <div class="dash-widget-info">
                                    <h3>{{ $pending_car_requests }}</h3>
                                    <span>Pending Car Requests</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa fa-cubes"></i></span>
                                <div class="dash-widget-info">
                                    <h3>{{ $approved_car_requests }}</h3>
                                    <span>Approved Car Requests</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa fa-user"></i></span>
                                <div class="dash-widget-info">
                                    <h3>{{ $rejected_car_requests }}</h3>
                                    <span>Rejected Car Requests</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">



                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        @php
                            $class = $my_trip > 0 ? 'card-body alert-success' : 'card-body alert-gray';
                        @endphp
                        <div class="{{ $class }}">
                            <span class="dash-widget-icon"><i class="fa fa-car text-success"></i></span>
                            <div class="dash-widget-info">
                                <h3>{{ $my_trip }}</h3>
                                <span>
                                    @if ($my_trip > 0)
                                        <a href="{{ route('driver.vehicle.request') }}" style="color: black;">Start Trip Now !</a>
                                    @else
                                        No Trip !
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon"><i class="fa fa-cubes"></i></span>
                            <div class="dash-widget-info">
                                <h3>{{ $total_waybills }}</h3>
                                <span>Total Waybill</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon"><i class="fa fa-cubes"></i></span>
                            <div class="dash-widget-info">
                                <h3>{{ $pending_waybills }}</h3>
                                <span>Pending Waybill</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon"><i class="fa fa-cubes"></i></span>
                            <div class="dash-widget-info">
                                <h3>{{ $ongoing_waybills }}</h3>
                                <span>Ongoing Waybill</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon"><i class="fa fa-user"></i></span>
                            <div class="dash-widget-info">
                                <h3>{{ $rejected_waybills }}</h3>
                                <span>Rejected Waybill</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon"><i class="fa fa-user"></i></span>
                            <div class="dash-widget-info">
                                <h3>{{ $completed_waybills }}</h3>
                                <span>Completed Waybill</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
