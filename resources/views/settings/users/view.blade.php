@extends('layouts.master')
@section('page_title', 'Users')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Profile</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ul>
                    </div>
                </div>
            </div>



            <div class="card mb-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-view">
                                <div class="profile-img-wrap">
                                    <div class="profile-img">
                                        @if ($user->photo)
                                            <a href="#"><img alt="" src="{{ asset($user->photo) }}"></a>
                                        @else
                                            <a href="#"><img alt="" src="{{ $user->normalUrl() }}"></a>
                                        @endif

                                        <!--src="{{ $user->normalUrl() }}" -->
                                    </div>
                                </div>
                                <div class="profile-basic">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="profile-info-left">
                                                <h3 class="user-name m-t-0 mb-0">{{ $user->full_name() }}</h3>
                                                <h6 class="text-muted">{{ $user->department->name }}</h6>
                                                <small class="text-muted">{{ $user->getRole() }}</small>
                                                <div class="staff-id">TYPE: {{ $user->type }}</div>
                                                <div class="small doj text-muted">Date of Join:
                                                    {{ $user->created_at->format('D, d F Y') }}</div>
                                                <div class="staff-msg"><a class="btn btn-custom" href="#">Send
                                                        Message</a></div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <ul class="personal-info">
                                                <li>
                                                    <div class="title">Phone:</div>
                                                    <div class="text"><a
                                                            href="tel:{{ $user->mobile }}">{{ $user->mobile }}</a></div>
                                                </li>
                                                <li>
                                                    <div class="title">Email:</div>
                                                    <div class="text"><a
                                                            href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">Birthday:</div>
                                                    <div class="text">24th July</div>
                                                </li>
                                                <li>
                                                    <div class="title">Address:</div>
                                                    <div class="text">Dansoman, Accra</div>
                                                </li>
                                                <li>
                                                    <div class="title">Gender:</div>
                                                    <div class="text">{{ $user->gender ?? 'N/A' }}</div>
                                                </li>
                                                <li>
                                                    {{--                                                <div class="title">Reports to:</div> --}}
                                                    {{--                                                <div class="text"> --}}
                                                    {{--                                                    <div class="avatar-box"> --}}
                                                    {{--                                                        <div class="avatar avatar-xs"> --}}
                                                    {{--                                                            <img src="assets/img/profiles/avatar-16.jpg" alt=""> --}}
                                                    {{--                                                        </div> --}}
                                                    {{--                                                    </div> --}}
                                                    {{--                                                    <a href="profile.html"> --}}
                                                    {{--                                                        Jeffery Lalor --}}
                                                    {{--                                                    </a> --}}
                                                    {{--                                                </div> --}}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="pro-edit"><a data-bs-target="#profile_info" data-bs-toggle="modal"
                                        class="edit-icon" href="#"><i class="fa fa-pencil"></i></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card tab-box">
                <div class="row user-tabs">
                    <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                        <ul class="nav nav-tabs nav-tabs-bottom">
                            <li class="nav-item"><a href="#emp_profile" data-bs-toggle="tab"
                                    class="nav-link active">Profile</a></li>
                            <li class="nav-item"><a href="#emp_projects" data-bs-toggle="tab" class="nav-link">Car Request
                                    History</a></li>
                            <li class="nav-item"><a href="#bank_statutory" data-bs-toggle="tab" class="nav-link">Odometer
                                    History <small class="text-danger">(Car)</small></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-content">

                <div id="emp_profile" class="pro-overview tab-pane fade show active">
                    <div class="row">
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <h3 class="card-title">License Information <a href="#" class="edit-icon"
                                            data-bs-toggle="modal" data-bs-target="#personal_info_modal"><i
                                                class="fa fa-pencil"></i></a></h3>
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">License Class</div>
                                            <div class="text">{{ $user->license_class }}</div>
                                        </li>
                                        <li>
                                            <div class="title">License Number</div>
                                            <div class="text">{{ $user->license_number }}</div>
                                        </li>
                                        <li>
                                            <div class="title">License Expiry</div>
                                            <div class="text">{{ $user->license_expiry }}</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <h3 class="card-title">Recent Audit Trails</h3>
                                    <div class="experience-box">
                                        <ul class="experience-list">
                                            <li>
                                                <div class="experience-user">
                                                    <div class="before-circle"></div>
                                                </div>
                                                <div class="experience-content">
                                                    <div class="timeline-content">
                                                        <a href="#/" class="name">Logged In</a>
                                                        <span class="time">Mon, 07 November 2022 [10:15 AM]</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="experience-user">
                                                    <div class="before-circle"></div>
                                                </div>
                                                <div class="experience-content">
                                                    <div class="timeline-content">
                                                        <a href="#/" class="name">Added New Odometer Reading</a>
                                                        <span class="time">Mon, 07 November 2022 [11:00 AM]</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="experience-user">
                                                    <div class="before-circle"></div>
                                                </div>
                                                <div class="experience-content">
                                                    <div class="timeline-content">
                                                        <a href="#/" class="name">Logged Out</a>
                                                        <span class="time">Mon, 07 November 2022 [12:15 PM]</span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="tab-pane fade" id="emp_projects">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                {!! $carRequestDataTable->table([
                                    'id' => 'carRequestDataTable',
                                    'class' => 'table table-hover align-middle mb-0',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="bank_statutory">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                {!! $driverOdometerHistoryDataTable->table([
                                    'id' => 'driverOdometerHistoryDataTable',
                                    'class' => 'table table-hover align-middle mb-0',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('js')

    {!! $carRequestDataTable->scripts() !!}
    {!! $driverOdometerHistoryDataTable->scripts() !!}

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>

@endsection
