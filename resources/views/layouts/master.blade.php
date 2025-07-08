<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="Smarthr - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title') - FleetJenee</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/material.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/line-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/plugins/morris/morris.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    {{-- <link href="{{ asset('assets/plugins/sweet-alert/sweetalert2.css') }}" rel="stylesheet"> --}}
    <link type="text/css" href="{{ asset('assets/plugins/toastr/css/toastr.min.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('assets/plugins/toastr/css/toastr.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/waitMe/waitMe.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js CDN -->

    @yield('css')
</head>

<body>

    <div class="main-wrapper">

        <div class="header">

            <div class="header-left">
                @if (request()->getHost() == 'test.autospagh.com')
                    <a href="#" class="logo">
                        <img style="height: 28%" src="{{ asset('assets/img/fleetjeneelogo.jpeg') }}" width="50"
                            height="50" alt="">
                    </a>
                    <a href="#" class="logo2">
                        <img style="height: 28%" src="{{ asset('assets/img/fleetjeneelogo.jpeg') }}" width="50"
                            height="50" alt="">
                    </a>
                @else
                
                    <a href="#" class="logo">
                        <img style="width: 100px" src="{{ asset('assets/img/fleetjeneelogo.jpg') }}" width="50"
                            height="50" alt="">
                    </a>
                    <a href="#" class="logo2">
                        <img style="height: 28%" src="{{ asset('assets/img/fleetjeneelogo.png') }}" width="50"
                            height="50" alt="">
                    </a>
                @endif
            </div>

            <a id="toggle_btn" href="javascript:void(0);">
                <span class="bar-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </a>

            <div class="page-title-box">
                <h3>FleetJenee</h3>
            </div>

            <a id="mobile_btn" class="mobile_btn" href="admin-dashboard.html#sidebar"><i class="fa fa-bars"></i></a>

            <ul class="nav user-menu">

                <li class="nav-item">
                    <div class="top-nav-search">
                        <a href="javascript:void(0);" class="responsive-search">
                            <i class="fa fa-search"></i>
                        </a>
                        <form action="search.html">
                            <input class="form-control" type="text" placeholder="Search here">
                            <button class="btn" type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                </li>


                {{--            <li class="nav-item dropdown has-arrow flag-nav"> --}}
                {{--                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="admin-dashboard.html#" role="button"> --}}
                {{--                    <img src="{{ asset('assets/img/flags/us.png') }}" alt="" height="20"> <span>English</span> --}}
                {{--                </a> --}}
                {{--                <div class="dropdown-menu dropdown-menu-right"> --}}
                {{--                    <a href="javascript:void(0);" class="dropdown-item"> --}}
                {{--                        <img src="{{ asset('assets/img/flags/us.png') }}" alt="" height="16"> English --}}
                {{--                    </a> --}}
                {{--                    <a href="javascript:void(0);" class="dropdown-item"> --}}
                {{--                        <img src="{{ asset('assets/img/flags/fr.png') }}" alt="" height="16"> French --}}
                {{--                    </a> --}}
                {{--                    <a href="javascript:void(0);" class="dropdown-item"> --}}
                {{--                        <img src="{{ asset('assets/img/flags/es.png') }}" alt="" height="16"> Spanish --}}
                {{--                    </a> --}}
                {{--                    <a href="javascript:void(0);" class="dropdown-item"> --}}
                {{--                        <img src="{{ asset('assets/img/flags/de.png') }}" alt="" height="16"> German --}}
                {{--                    </a> --}}
                {{--                </div> --}}
                {{--            </li> --}}


                <li class="nav-item dropdown">
                    <a href="admin-dashboard.html#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <i class="fa fa-bell-o"></i> <span
                            class="badge rounded-pill">{{ count($notifications) }}</span>
                    </a>
                    <div class="dropdown-menu notifications">
                        <div class="topnav-dropdown-header">
                            <span class="notification-title">Notifications</span>
                            <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                        </div>
                        <div class="noti-content">
                            <ul class="notification-list">
                                @foreach ($notifications as $notification)
                                    <li class="notification-message">
                                        <a href="#">
                                            <div class="media d-flex">
                                                <span class="avatar flex-shrink-0">
                                                    @if ($notification->toUser && $notification->toUser->normalUrl())
                                                        <img alt=""
                                                            src="{{ $notification->toUser->normalUrl() }}">
                                                    @else
                                                        <img alt=""
                                                            src="{{ asset('assets/img/faces/9.jpg') }}">
                                                    @endif
                                                </span>
                                                <div class="media-body flex-grow-1">
                                                    <p class="noti-details"><span
                                                            class="noti-title">{{ mb_substr($notification->body, 0, 100) }}...</span>
                                                    </p>
                                                    <p class="noti-time"><span
                                                            class="notification-time">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="topnav-dropdown-footer">
                            <a href="#">View all Notifications</a>
                        </div>
                    </div>
                </li>


                {{--            <li class="nav-item dropdown"> --}}
                {{--                <a href="admin-dashboard.html#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown"> --}}
                {{--                    <i class="fa fa-comment-o"></i> <span class="badge rounded-pill">8</span> --}}
                {{--                </a> --}}
                {{--                <div class="dropdown-menu notifications"> --}}
                {{--                    <div class="topnav-dropdown-header"> --}}
                {{--                        <span class="notification-title">Messages</span> --}}
                {{--                        <a href="javascript:void(0)" class="clear-noti"> Clear All </a> --}}
                {{--                    </div> --}}
                {{--                    <div class="noti-content"> --}}
                {{--                        <ul class="notification-list"> --}}
                {{--                            <li class="notification-message"> --}}
                {{--                                <a href="chat.html"> --}}
                {{--                                    <div class="list-item"> --}}
                {{--                                        <div class="list-left"> --}}
                {{-- <span class="avatar"> --}}
                {{-- <img alt="" src="{{ asset('assets/img/profiles/avatar-09.jpg') }}"> --}}
                {{-- </span> --}}
                {{--                                        </div> --}}
                {{--                                        <div class="list-body"> --}}
                {{--                                            <span class="message-author">Richard Miles </span> --}}
                {{--                                            <span class="message-time">12:28 AM</span> --}}
                {{--                                            <div class="clearfix"></div> --}}
                {{--                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span> --}}
                {{--                                        </div> --}}
                {{--                                    </div> --}}
                {{--                                </a> --}}
                {{--                            </li> --}}
                {{--                            <li class="notification-message"> --}}
                {{--                                <a href="chat.html"> --}}
                {{--                                    <div class="list-item"> --}}
                {{--                                        <div class="list-left"> --}}
                {{-- <span class="avatar"> --}}
                {{-- <img alt="" src="{{ asset('assets/img/profiles/avatar-02.jpg') }}"> --}}
                {{-- </span> --}}
                {{--                                        </div> --}}
                {{--                                        <div class="list-body"> --}}
                {{--                                            <span class="message-author">John Doe</span> --}}
                {{--                                            <span class="message-time">6 Mar</span> --}}
                {{--                                            <div class="clearfix"></div> --}}
                {{--                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span> --}}
                {{--                                        </div> --}}
                {{--                                    </div> --}}
                {{--                                </a> --}}
                {{--                            </li> --}}
                {{--                            <li class="notification-message"> --}}
                {{--                                <a href="chat.html"> --}}
                {{--                                    <div class="list-item"> --}}
                {{--                                        <div class="list-left"> --}}
                {{-- <span class="avatar"> --}}
                {{-- <img alt="" src="{{ asset('assets/img/profiles/avatar-03.jpg') }}"> --}}
                {{-- </span> --}}
                {{--                                        </div> --}}
                {{--                                        <div class="list-body"> --}}
                {{--                                            <span class="message-author"> Tarah Shropshire </span> --}}
                {{--                                            <span class="message-time">5 Mar</span> --}}
                {{--                                            <div class="clearfix"></div> --}}
                {{--                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span> --}}
                {{--                                        </div> --}}
                {{--                                    </div> --}}
                {{--                                </a> --}}
                {{--                            </li> --}}
                {{--                            <li class="notification-message"> --}}
                {{--                                <a href="chat.html"> --}}
                {{--                                    <div class="list-item"> --}}
                {{--                                        <div class="list-left"> --}}
                {{-- <span class="avatar"> --}}
                {{-- <img alt="" src="{{ asset('assets/img/profiles/avatar-05.jpg') }}"> --}}
                {{-- </span> --}}
                {{--                                        </div> --}}
                {{--                                        <div class="list-body"> --}}
                {{--                                            <span class="message-author">Mike Litorus</span> --}}
                {{--                                            <span class="message-time">3 Mar</span> --}}
                {{--                                            <div class="clearfix"></div> --}}
                {{--                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span> --}}
                {{--                                        </div> --}}
                {{--                                    </div> --}}
                {{--                                </a> --}}
                {{--                            </li> --}}
                {{--                            <li class="notification-message"> --}}
                {{--                                <a href="chat.html"> --}}
                {{--                                    <div class="list-item"> --}}
                {{--                                        <div class="list-left"> --}}
                {{-- <span class="avatar"> --}}
                {{-- <img alt="" src="{{ asset('assets/img/profiles/avatar-08.jpg') }}"> --}}
                {{-- </span> --}}
                {{--                                        </div> --}}
                {{--                                        <div class="list-body"> --}}
                {{--                                            <span class="message-author"> Catherine Manseau </span> --}}
                {{--                                            <span class="message-time">27 Feb</span> --}}
                {{--                                            <div class="clearfix"></div> --}}
                {{--                                            <span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span> --}}
                {{--                                        </div> --}}
                {{--                                    </div> --}}
                {{--                                </a> --}}
                {{--                            </li> --}}
                {{--                        </ul> --}}
                {{--                    </div> --}}
                {{--                    <div class="topnav-dropdown-footer"> --}}
                {{--                        <a href="chat.html">View all Messages</a> --}}
                {{--                    </div> --}}
                {{--                </div> --}}
                {{--            </li> --}}

                <li class="nav-item dropdown has-arrow main-drop">
                    <a href="admin-dashboard.html#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <span class="user-img"><img src="{{ asset('assets/img/9.jpg') }}" alt="">
                            <span class="status online"></span></span>
                        <span>{{ auth()->user()->full_name() }}</span>
                    </a>
                    <div class="dropdown-menu">
                        {{--                    <a class="dropdown-item" href="#">My Profile</a> --}}
                        {{--                    <a class="dropdown-item" href="#">Settings</a> --}}
                        <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                    </div>
                </li>
            </ul>


            <div class="dropdown mobile-user-menu">
                <a href="admin-dashboard.html#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    {{--                <a class="dropdown-item" href="#">My Profile</a> --}}
                    {{--                <a class="dropdown-item" href="#">Settings</a> --}}
                    <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                </div>
            </div>

        </div>

        @include('layouts.side')
        @yield('content')
        @include('layouts.footer')
 
        <!--my sweet alert codes--->
        <script src="{{ asset('assets/js/mysweet-alert.js') }}"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    </div>

</body>

</html>
