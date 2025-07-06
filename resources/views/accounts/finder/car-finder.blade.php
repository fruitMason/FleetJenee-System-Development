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
                            <li class="breadcrumb-item">
                                <a href="{{ route('accounts.finder.home.car') }}">Car Finder</a>
                            </li>
                            <li class="breadcrumb-item active">Car Details</li>
                        </ul>
                    </div>

                    <div class="col-auto float-end ms-auto">
                    </div>
                </div>
            </div>




            {{-- Car Information --}}
            <div class="row">
                <div class="col-md-12">
                    <section class="panel panel-default">
                        <div class="card mg-b-20" id="card_content">

                            <div class="card-body">
                                <div class="card-title">
                                    <h4 class="text-success">Vehicle Information: {{ $car->model }} </h4>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="text fw-bold">Model:</div>
                                        <div>{{ $car->model }} | Year : {{ $car->year }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fw-bold">Body Style | Trim Level :</div>
                                        <div>{{ ucwords($car->body_style) }} | {{ $car->trim_level }} </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="text fw-bold">Car Group | Color </div>
                                        <div>{{ ucwords($car->car_group) }} |
                                            <i class="fa fa-car" style="color:{{ $car->color }};"> </i>
                                            {{ $car->color }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="title fw-bold">Car Number | Chassis:</div>
                                        <div>
                                            {{ $car->car_number }} | {{ $car->chassis }}
                                        </div>
                                    </div>
                                </div>


                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="title fw-bold">Odometer | Engine Capacity :</div>
                                        <div>
                                            {{ $car->odometer }} | {{ $car->engine_capacity }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="title fw-bold">Fuel Type | Car Status :</div>
                                        <div>
                                            {{ ucwords($car->fuel_type) }} | <span
                                                class="text-primary">{{ ucwords($car->status) }}</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="title fw-bold">Road Worthy Begining:</div>
                                        <div>
                                            {{ $car->road_worthy_start_date ? $car->road_worthy_start_date->format('d F Y') : 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="title fw-bold">Road Worthy Expiry:</div>
                                        <div>
                                            {{ $car->road_worthy_expiry_date ? $car->road_worthy_expiry_date->format('d F Y') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>


                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="title fw-bold">Vehicle Insurance Start:</div>
                                        <div>
                                            {{ $car->insurance_start_date ? $car->insurance_start_date->format('d F Y') : 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="title fw-bold">Vehicle Insurance Expiry:</div>
                                        <div>
                                            {{ $car->insurance_expiry ? $car->insurance_expiry->format('d F Y') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>


                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="title fw-bold">Maintenance Due Date:</div>
                                        <div>
                                            {{ $car->date_due_maintenance ? $car->date_due_maintenance->format('d F Y') : 'NA' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="title fw-bold">Assigned User :</div>
                                        <div>
                                            @if (!is_null($car->user))
                                                {{ $car->user->full_name() }} ({{ $car->user->mobile }}) |

                                                Department : {{ $car->user->department->name ?? 'N/A' }}
                                            @endif

                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">

                                    <div class="col-md-12">
                                        <div class="title fw-bold">Comments On Vehicle Registration:</div>
                                        <div>
                                            {{ $car->comment }}
                                        </div>
                                    </div>
                                </div>












                            </div>
                        </div>

                    </section>
                </div>
            </div>

            {{-- CAR ACTIVITIES --}}
            {{-- filter --}}
            <div>
                <form action="" method="get">
                    <div class="row filter-row">
                        <div class="col-md-4">
                            <div class="form-group form-focus select-focus">
                                <input type="date" name="dtp_from" class="form-control"
                                    value="{{ request()->get('dtp_from') }}">

                                <label class="focus-label">Date From : </label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group form-focus select-focus">
                                <input type="date" name="dtp_to" class="form-control"
                                    value="{{ request()->get('dtp_to') }}">
                                <label class="focus-label">Date To : </label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <button type="submit" id="btnAdvancedFilterButton" class="btn btn-success w-100"> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            {{-- accident report --}}
            <div class="col-md-12">
                <section class="panel panel-default">
                    <div class="card mg-b-20" id="card_content">

                        <div class="card-body">
                            <div class="card-title">
                                <h5 class="text-success">Accident Report : ({{ $accidents->count() }}) {{ $car->model }}
                                </h5>
                            </div>


                            @if ($accidents->count() > 0)
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>[Date]</th>
                                                <th>Location</th>
                                                <th>Description</th>
                                                <th>PostedBy</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($accidents as $g)
                                                <tr>
                                                    <td> {{ $g->date_reported->format('d F Y') }}
                                                    </td>
                                                    <td> {{ $g->location }} </td>
                                                    <td> {{ $g->description }} </td>
                                                    <td> {{ $g->user->full_name() }} </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div> No accidents reports !</div>
                            @endif

                        </div>

                </section>
            </div>


            {{-- Work order --}}
            <div class="col-md-12">
                <section class="panel panel-default">
                    <div class="card mg-b-20" id="card_content">

                        <div class="card-body">
                            <div class="card-title">
                                <h5 class="text-success">Maintanance (Work Orders) : ({{ $work_orders->count() }})
                                    {{ $car->model }} </h5>
                            </div>

                            @if ($work_orders->count() > 0)
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date Created</th>
                                                <th>[Start Date]</th>
                                                <th>Mechanic</th>
                                                <th>Mech Status</th>
                                                <th>Mech Activities #</th>
                                                <th>Wor Type</th>
                                                <th>Fin Status</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($work_orders as $g)
                                                <tr>
                                                    <td> {{ $g->created_at->format('d F Y') }}
                                                    </td>
                                                    <td> {{ $g->start_date->format('d F Y') }} </td>
                                                    <td> {{ $g->first_name }} {{ $g->middle_name }}
                                                        {{ $g->last_name }} </td>
                                                    <td> {{ ucwords($g->status) }} </td>
                                                    <td> {{ $g->countt }} </td>
                                                    <td> {{ ucwords($g->type) }} </td>
                                                    <td> {{ ucwords($g->fin_status) }} </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div> No work order !</div>
                            @endif

                        </div>
                    </div>

                </section>
            </div>


            {{-- elog --}}
            <div class="col-md-12">
                <section class="panel panel-default">
                    <div class="card mg-b-20" id="card_content">

                        <div class="card-body">
                            <div class="card-title">
                                <h5 class="text-success">E Log : ({{ $elog->count() }}) {{ $car->model }} </h5>
                            </div>

                            @if ($elog->count() > 0)
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>[Date Logged]</th>
                                                <th>Start Location</th>
                                                <th>Destination</th>
                                                <th>Log Title</th>
                                                <th>Other Information</th>
                                                <th>Start Odometer</th>
                                                <th>End Odometer</th>
                                                <th>User</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($elog as $g)
                                                <tr>
                                                    <td> {{ $g->date_logged->format('d F Y') }} </td>
                                                    <td> {{ ucwords($g->current_location) }} </td>
                                                    <td> {{ ucwords($g->destination) }} </td>
                                                    <td>({{ $g->activities->count() }}) {{ $g->title }} </td>
                                                    <td> {{ $g->decription }} </td>
                                                    <td> {{ $g->start_odometer }} </td>
                                                    <td> {{ $g->end_odometer }} </td>
                                                    <td> {{ $g->user ? $g->user->full_name() : 'N/A' }} </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div> No ELog Records !</div>
                            @endif

                        </div>
                    </div>

                </section>
            </div>
            {{-- //elog --}}


            {{-- odometer history --}}
            <div class="col-md-12">
                <section class="panel panel-default">
                    <div class="card mg-b-20" id="card_content">

                        <div class="card-body">
                            <div class="card-title">
                                <h5 class="text-success">Odometer History : ({{ $odometer->count() }})
                                    {{ $car->model }} </h5>
                            </div>

                            @if ($odometer->count() > 0)
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>[Created At]</th>
                                                <th>Old Value</th>
                                                <th>New Value</th>
                                                <th>User</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($odometer as $g)
                                                <tr>

                                                    <td> {{ $g->created_at->format('d F Y H:m A') }} </td>
                                                    <td> {{ $g->old_value }} </td>
                                                    <td> {{ $g->new_value }} </td>
                                                    <td> {{ $g->user ? $g->user->full_name() : 'N/A' }} </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div> No Odometer History Record !</div>
                            @endif

                        </div>
                    </div>
                </section>
            </div>
            {{-- //odometer --}}


        </div>



    </div>



@endsection
