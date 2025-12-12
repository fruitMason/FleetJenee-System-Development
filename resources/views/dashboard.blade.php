@extends('layouts.master')
@section('page_title', 'Dashboard')
@section('content')
<div class="page-wrapper">

     

    <div class="content container-fluid">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Welcome {{auth()->user()->first_name}}, {{auth()->user()->getRole()}}</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
 
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <a style="color: inherit !important;" href="{{route('fleet.vehicle.registration')}}">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-bus"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{$total_cars}}</h3>
                            <span>Total Cars</span>
                        </div>
                    </div>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-car"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{$total_active_cars}}</h3>
                            <span>Active Cars</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-cab"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{$total_inactive_cars}}</h3>
                            <span>Inactive Cars</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <a style="color: inherit !important;" href="{{route('settings.users', ['type' => 'driver'])}}">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-user"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{$total_drivers}}</h3>
                            <span>Total Drivers</span>
                        </div>
                    </div>
                    </a>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-4 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <a style="color: inherit !important;" href="{{route('fleet.vehicle.request')}}"><h4 class="card-title">Car Requests</h4></a>
                        {{--                        <div class="statistics">--}}
                        {{--                            <div class="row">--}}
                        {{--                                <div class="col-md-6 col-6 text-center">--}}
                        {{--                                    <div class="stats-box mb-4">--}}
                        {{--                                        <p>Total Tasks</p>--}}
                        {{--                                        <h3>385</h3>--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}
                        {{--                                <div class="col-md-6 col-6 text-center">--}}
                        {{--                                    <div class="stats-box mb-4">--}}
                        {{--                                        <p>Overdue Tasks</p>--}}
                        {{--                                        <h3>19</h3>--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        <div class="progress mb-4">
                            @php $pending_car_requests_percentage = ($pending_car_requests * $total_car_requests) / 100 @endphp
                            @php $approved_car_requests_percentage = ($approved_car_requests * $total_car_requests) / 100 @endphp
                            @php $rejected_car_requests_percentage = ($rejected_car_requests * $total_car_requests) / 100 @endphp
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 26%" aria-valuenow="{{$pending_car_requests_percentage}}" aria-valuemin="0" aria-valuemax="100">{{$pending_car_requests_percentage}}%</div>
                            <div class="progress-bar bg-success" role="progressbar" style="width: 24%" aria-valuenow="{{$approved_car_requests_percentage}}" aria-valuemin="0" aria-valuemax="100">{{$approved_car_requests_percentage}}%</div>
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 30%" aria-valuenow="{{$rejected_car_requests_percentage}}" aria-valuemin="0" aria-valuemax="100">{{$rejected_car_requests_percentage}}%</div>
                        </div>
                        <div>
                            <p><i class="fa fa-dot-circle-o text-warning me-2"></i>Pending <span class="float-end">{{$pending_car_requests}}</span></p>
                            <p><i class="fa fa-dot-circle-o text-success me-2"></i>Approved <span class="float-end">{{$approved_car_requests}}</span></p>
                            <p><i class="fa fa-dot-circle-o text-danger me-2"></i>Rejected <span class="float-end">{{$rejected_car_requests}}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <a style="color: inherit !important;" href="{{route('fleet.vehicle.maintenance')}}"><h4 class="card-title">Car Maintenance</h4></a>

                        <div class="progress mb-4">
                            @php $pending_car_maintenance_percentage = ($pending_car_maintenances * $total_maintenances) / 100 @endphp
                            @php $ongoing_car_maintenance_percentage = ($ongoing_car_maintenances * $total_maintenances) / 100 @endphp
                            @php $completed_car_maintenances_percentage = ($completed_car_maintenances * $total_maintenances) / 100 @endphp
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 26%" aria-valuenow="{{$pending_car_maintenance_percentage}}" aria-valuemin="0" aria-valuemax="100">{{$pending_car_maintenance_percentage}}%</div>
                            <div class="progress-bar bg-info" role="progressbar" style="width: 24%" aria-valuenow="{{$ongoing_car_maintenance_percentage}}" aria-valuemin="0" aria-valuemax="100">{{$ongoing_car_maintenance_percentage}}%</div>
                            <div class="progress-bar bg-success" role="progressbar" style="width: 30%" aria-valuenow="{{$completed_car_maintenances_percentage}}" aria-valuemin="0" aria-valuemax="100">{{$completed_car_maintenances_percentage}}%</div>
                        </div>
                        <div>
                            <p><i class="fa fa-dot-circle-o text-danger me-2"></i>In Maintenance <span class="float-end">{{$pending_car_maintenances}}</span></p>
                            <p><i class="fa fa-dot-circle-o text-info me-2"></i>Ongoing Maintenance <span class="float-end">{{$ongoing_car_maintenances}}</span></p>
                            <p><i class="fa fa-dot-circle-o text-success me-2"></i>Completed Maintenance<span class="float-end">{{$completed_car_maintenances_percentage}}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <a style="color: inherit !important;" href="{{route('fleet.vehicle.driver.license')}}"><h4 class="card-title">Drivers License</h4></a>
                        <div class="progress mb-4">
                            @php $total_registered_drivers_licenses_percentage = ($total_registered_drivers_licenses * $total_users) / 100 @endphp
                            @php $active_drivers_licenses_percentage = ($active_drivers_licenses * $total_users) / 100 @endphp
                            @php $expired_drivers_licenses_percentage = ($expired_drivers_licenses * $total_users) / 100 @endphp
                            <div class="progress-bar bg-success" role="progressbar" style="width: 26%" aria-valuenow="{{$total_registered_drivers_licenses_percentage}}" aria-valuemin="0" aria-valuemax="100">{{$total_registered_drivers_licenses_percentage}}%</div>
                            <div class="progress-bar bg-info" role="progressbar" style="width: 24%" aria-valuenow="{{$active_drivers_licenses_percentage}}" aria-valuemin="0" aria-valuemax="100">{{$active_drivers_licenses_percentage}}%</div>
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 30%" aria-valuenow="{{$expired_drivers_licenses_percentage}}" aria-valuemin="0" aria-valuemax="100">{{$expired_drivers_licenses_percentage}}%</div>
                        </div>
                        <div>
                            <p><i class="fa fa-dot-circle-o text-success me-2"></i>Registered <span class="float-end">{{$total_registered_drivers_licenses}}</span></p>
                            <p><i class="fa fa-dot-circle-o text-info me-2"></i>Active License Expiration <span class="float-end">{{$active_drivers_licenses}}</span></p>
                            <p><i class="fa fa-dot-circle-o text-danger me-2"></i>Expired License <span class="float-end">{{$expired_drivers_licenses}}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <a style="color: inherit !important;" href="{{route('fleet.vehicle.insurance')}}"><h4 class="card-title">Insurance</h4></a>
                        <div class="progress mb-4">
                            @php $active_car_insurance_percentage = ($active_car_insurance * $total_cars) / 100 @endphp
                            @php $expired_car_insurance_percentage = ($expired_car_insurance * $total_cars) / 100 @endphp
                            <div class="progress-bar bg-info" role="progressbar" style="width: 24%" aria-valuenow="{{$active_car_insurance_percentage}}" aria-valuemin="0" aria-valuemax="100">{{$active_car_insurance_percentage}}%</div>
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 30%" aria-valuenow="{{$expired_car_insurance_percentage}}" aria-valuemin="0" aria-valuemax="100">{{$expired_car_insurance_percentage}}%</div>
                        </div>
                        <div>
                            <p><i class="fa fa-dot-circle-o text-success me-2"></i>No. of Cars <span class="float-end">{{$total_cars}}</span></p>
                            <p><i class="fa fa-dot-circle-o text-info me-2"></i>Active <span class="float-end">{{$active_car_insurance}}</span></p>
                            <p><i class="fa fa-dot-circle-o text-danger me-2"></i>Expired <span class="float-end">{{$expired_car_insurance}}</span></p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-4 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <a style="color: inherit !important;" href="{{route('settings.vendors')}}"><h4 class="card-title">Vendors</h4></a>

                        <div class="progress mb-4">
                            @php $total_vendors_paid_percentage = ($total_vendors_paid * $total_vendors) / 100 @endphp
                            @php $total_vendors_owed_percentage = ($total_vendors_owed * $total_vendors) / 100 @endphp
                            <div class="progress-bar bg-success" role="progressbar" style="width: 24%" aria-valuenow="{{$total_vendors_paid_percentage}}" aria-valuemin="0" aria-valuemax="100">{{$total_vendors_paid_percentage}}%</div>
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 30%" aria-valuenow="{{$total_vendors_owed_percentage}}" aria-valuemin="0" aria-valuemax="100">{{$total_vendors_owed_percentage}}%</div>
                        </div>
                        <div>
                            <p><i class="fa fa-dot-circle-o text-info me-2"></i>No. of Vendors <span class="float-end">{{$total_vendors}}</span></p>
                            <p><i class="fa fa-dot-circle-o text-success me-2"></i>Vendors (Paid) <span class="float-end">{{$total_vendors_paid}}</span></p>
                            <p><i class="fa fa-dot-circle-o text-danger me-2"></i>Vendors (Owed) <span class="float-end">{{$total_vendors_owed}}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <a style="color: inherit !important;" href="{{route('fleet.vehicle.dvla.road.worthiness')}}"><h4 class="card-title">Road Worthy</h4></a>

                        <div class="progress mb-4">
                            @php $pending_road_worthy_percentage = ($road_worthy_pending * $total_cars) / 100 @endphp
                            @php $active_road_worthy_percentage = ($road_worthy_active * $total_cars) / 100 @endphp
                            @php $expired_road_worthy_percentage = ($road_worthy_expired * $total_cars) / 100 @endphp
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 26%" aria-valuenow="{{$pending_road_worthy_percentage}}" aria-valuemin="0" aria-valuemax="100">{{$pending_road_worthy_percentage}}%</div>
                            <div class="progress-bar bg-success" role="progressbar" style="width: 24%" aria-valuenow="{{$active_road_worthy_percentage}}" aria-valuemin="0" aria-valuemax="100">{{$active_road_worthy_percentage}}%</div>
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 30%" aria-valuenow="{{$expired_road_worthy_percentage}}" aria-valuemin="0" aria-valuemax="100">{{$expired_road_worthy_percentage}}%</div>
                        </div>
                        <div>
                            <p><i class="fa fa-dot-circle-o text-warning me-2"></i>Pending <span class="float-end">{{$road_worthy_pending}}</span></p>
                            <p><i class="fa fa-dot-circle-o text-success me-2"></i>Active <span class="float-end">{{$road_worthy_active}}</span></p>
                            <p><i class="fa fa-dot-circle-o text-danger me-2"></i>Expired <span class="float-end">{{$road_worthy_expired}}</span></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
