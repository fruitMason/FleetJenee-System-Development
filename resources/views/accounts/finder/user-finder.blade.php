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
                            <li class="breadcrumb-item"> <a href="{{ route('accounts.finder.home.user') }}">User Finder
                                </a> </li>
                            <li class="breadcrumb-item active">User Details</li>
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

            {{-- User Information --}}
            <div class="row">
                <div class="col-md-12">
                    <section class="panel panel-default">
                        <div class="card mg-b-20" id="card_content">

                            <div class="card-body">
                                <div class="card-title">
                                    <h4 class="text-success">User Information : {{ $user_data->full_name() }} </h4>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="text fw-bold">User Name:</div>
                                        <div>{{ $user_data->full_name() }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fw-bold">Email:</div>
                                        <div>{{ $user_data->email }} - {{ $user_data->mobile }}</div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="text fw-bold">Login Permision:</div>
                                        <div>{{ $user_data->getRoleNames()->first() }}

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="col-md-6">
                                            <div class="title fw-bold">Login Type | Last Login :</div>

                                            <div>
                                                {{ ucwords(strtolower($user_data->type)) }} |
                                                {{ $user_data->last_login_at ? $user_data->last_login_at->format('d F Y H:i A') : 'Never' }}
                                                [{{ $user_data->last_login_at ? $user_data->last_login_at->diffForHumans() : 'Never' }}]

                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row mb-4">


                                    <div class="col-md-6">
                                        <div class="col-md-6">
                                            <div class="title fw-bold">Department:</div>

                                            <div>
                                                @if ($user_data->department != null)
                                                    {{ $user_data->department->name ?? 'N/A' }}
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="fw-bold">Driver Type</div>
                                        <div>
                                            {{ $user_data->driver_type ?? 'N/A' }}
                                            | <span class="fw-bold">Lisence Class </span> :
                                            {{ $user_data->license_class ?? 'N/A' }}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="text fw-bold">License #:</div>
                                        <div class="">{{ $user_data->license_number ?? 'N/A' }}
                                            | <span class="fw-bold">Expiry</span> : @if ($user_data->license_expiry != null)
                                                {{ $user_data->license_expiry->format('d F Y') ?? 'N/A' }}
                                            @endif
                                        </div>
                                    </div>
                                </div>




                                <div class="row mb-4">

                                    <div class="col-md-6">
                                        <div class="title text fw-bold">Vendor:</div>
                                        <div class="">
                                            @if ($user_data->vendor != null)
                                                {{ $user_data->vendor->name ?? 'N/A' }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </div>
                                </div>





                            </div>
                        </div>

                    </section>
                </div>
            </div>
            {{-- //User Information --}}

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
            {{-- //end finder --}}

            {{-- user accident report --}}
            <div class="col-md-12">
                <section class="panel panel-default">
                    <div class="card mg-b-20" id="card_content">

                        <div class="card-body">
                            <div class="card-title">
                                <h5 class="text-success">Accident Report : ({{ $accidents->count() }})
                                    {{ $user_data->full_name() }}
                                </h5>
                            </div>

                            @if ($accidents->count() > 0)
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Created At</th>
                                                <th>[Date]</th>
                                                <th>Car Info</th>
                                                <th>Location</th>
                                                <th>Description</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($accidents as $g)
                                                <tr>
                                                    <td> {{ $g->created_at->format('d F Y') }} </td>
                                                    <td> {{ $g->date_reported->format('d F Y') }}
                                                    </td>
                                                    <td> {{ $g->car->car_features() }} </td>
                                                    <td> {{ $g->location }} </td>
                                                    <td> {{ $g->description }} </td>

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
            {{-- //end user accident reports --}}


            {{-- car requests --}}
            <div class="col-md-12">
                <section class="panel panel-default">
                    <div class="card mg-b-20" id="card_content">

                        <div class="card-body">
                            <div class="card-title">
                                <h5 class="text-success">Car Requests: ({{ $car_requests->count() }})
                                    {{ $user_data->full_name() }}
                                </h5>
                            </div>

                            @if ($car_requests->count() > 0)
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Created At</th>
                                                <th>[Date Needed]</th>
                                                <th>Date Returned</th>
                                                <th>Car Info</th>
                                                <th>Request Reason</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($car_requests as $g)
                                                <tr>
                                                    <td> {{ $g->created_at->format('d F Y') }} </td>
                                                    <td> {{ $g->date_needed->format('d F Y') }} </td>
                                                    <td> {{ $g->return_date->format('d F Y') }} </td>
                                                    <td> {{ $g->car->car_features() }} </td>
                                                    <td> {{ ucwords($g->request_reason) }} </td>
                                                    <td> {{ ucwords($g->status) }} </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div> No car requests !</div>
                            @endif

                        </div>

                </section>
            </div>
            {{-- //end car request --}}



            {{-- elog --}}
            <div class="col-md-12">
                <section class="panel panel-default">
                    <div class="card mg-b-20" id="card_content">

                        <div class="card-body">
                            <div class="card-title">
                                <h5 class="text-success">E Log : ({{ $elogs->count() }}) {{ $user_data->full_name() }}
                                </h5>
                            </div>

                            @if ($elogs->count() > 0)
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>[Date Logged]</th>
                                                <th>Car Info</th>
                                                <th>Start Location</th>
                                                <th>Destination</th>
                                                <th>Log Title</th>
                                                <th>Other Information</th>
                                                <th>Start Odometer</th>
                                                <th>End Odometer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($elogs as $g)
                                                <tr>
                                                    <td> {{ $g->date_logged->format('d F Y') }} </td>
                                                    <td> {{ $g->car->car_features() }} </td>
                                                    <td> {{ ucwords($g->current_location) }} </td>
                                                    <td> {{ ucwords($g->destination) }} </td>
                                                    <td>({{ $g->activities->count() }}) {{ $g->title }} </td>
                                                    <td> {{ $g->decription }} </td>
                                                    <td> {{ $g->start_odometer }} </td>
                                                    <td> {{ $g->end_odometer }} </td>

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
                                    {{ $user_data->full_name() }}</h5>
                            </div>

                            @if ($odometer->count() > 0)
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>[Created At]</th>
                                                <th>Car Info</th>
                                                <th>Old Value</th>
                                                <th>New Value</th>
                                                <th>User</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($odometer as $g)
                                                <tr>

                                                    <td> {{ $g->created_at->format('d F Y H:m A') }} </td>
                                                    <td> {{ $g->car->car_features() }} </td>
                                                    <td> {{ $g->old_value }} </td>
                                                    <td> {{ $g->new_value }} </td>
                                                    <td> {{ $g->user->full_name() }} </td>
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


             {{-- waybills  --}}
             <div class="col-md-12">
                <section class="panel panel-default">
                    <div class="card mg-b-20" id="card_content">

                        <div class="card-body">
                            <div class="card-title">
                                <h5 class="text-success">Waybills : ({{ $waybills->count() }})
                                    {{ $user_data->full_name() }}</h5>
                            </div>

                            @if ($waybills->count() > 0)
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>[Created At]</th>
                                                <th>Destination</th>
                                                <th>Item</th>
                                                <th># of Packages</th>
                                                <th>Description</th>
                                                <th>Weight</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($waybills as $g)
                                                <tr>
                                                    <td> {{ $g->created_at->format('d F Y H:m A') }} </td>
                                                    <td> {{ ucwords($g->destination) }} </td>
                                                    <td> {{ ucwords($g->item) }} </td>
                                                    <td> {{ $g->no_of_packages }} </td>
                                                    <td> {{ ucwords($g->description) }} </td>
                                                    <td> {{ $g->weight }} </td>
                                                    <td> {{ ucwords($g->status) }} </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div> No Waybill !</div>
                            @endif

                        </div>
                    </div>
                </section>
            </div>
            {{-- //end waybill --}}


           
        </div>



    </div>

@endsection
