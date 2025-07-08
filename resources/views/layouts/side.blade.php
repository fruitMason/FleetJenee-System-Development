<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">

            {{-- FLEET MANAGER MENU --}}
            <ul class="sidebar-vertical">
                @can('Admin Dashboard')
                    <li>
                        <a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> <span>Home</span></a>
                    </li>
                @endcan

                <!-- settings-->
                @can('Settings >>')
                    <li class="menu-title">
                        <span>MAIN SETTINGS</span>
                    </li>
                @endcan
                <li class="submenu @if (str_contains(url()->current(), '/settings')) active @endif">
                    @can('Settings >>')
                        <a href="#" class="noti-dot"><i class="fa fa-cog"></i> <span> Settings</span> <span
                                class="menu-arrow"></span></a>
                        <ul style="display: none;">
                        @endcan
                        <!--settings content-->

                        @can(' Zones')
                            <li><a class="@if (str_contains(url()->current(), '/settings/sectors')) active @endif"
                                    href="{{ route('settings.sectors') }}">Zones <span
                                        class="badge rounded-pill bg-primary float-end">{{ $total_sectors }}</span></a>
                            </li>
                        @endcan
                        @can(' Regions')
                            <li><a class="@if (str_contains(url()->current(), '/settings/regions')) active @endif"
                                    href="{{ route('settings.regions') }}">Regions <span
                                        class="badge rounded-pill bg-primary float-end">{{ $total_regions }}</span></a>
                            </li>
                        @endcan
                        @can(' Departments')
                            <li><a class="@if (str_contains(url()->current(), '/settings/departments')) active @endif"
                                    href="{{ route('settings.departments') }}">Departments <span
                                        class="badge rounded-pill bg-primary float-end">{{ $total_departments }}</span></a>
                            </li>
                        @endcan
                        @can(' Users')
                            <li><a class="@if (str_contains(url()->current(), '/settings/users')) active @endif"
                                    href="{{ route('settings.users') }}">Users <span
                                        class="badge rounded-pill bg-primary float-end">{{ $total_users }}</span></a></li>
                        @endcan
                        @can(' Permissions')
                            <li><a class="@if (str_contains(url()->current(), '/settings/roles')) active @endif"
                                    href="{{ route('settings.roles') }}">Roles <span
                                        class="badge rounded-pill bg-primary float-end">{{ $total_roles }}</span></a>
                            </li>
                        @endcan
                        {{-- <li> <a class="@if (str_contains(url()->current(), '/settings/permissions')) active @endif"
                                        href="{{ route('settings.permissions') }}">Permissions <span
                                            class="badge rounded-pill bg-primary float-end">{{ $total_permissions }}</span>
                                    </a>  </li> --}}

                        @can(' Taxes')
                            <li><a class="@if (str_contains(url()->current(), '/settings/taxes')) active @endif"
                                    href="{{ route('settings.taxes') }}">Taxes <span
                                        class="badge rounded-pill bg-primary float-end">{{ $total_taxes }}</span></a>
                            </li>
                        @endcan
                        @can(' Odometer Setting')
                            <li><a class="@if (str_contains(url()->current(), '/settings/odometer')) active @endif"
                                    href="{{ route('settings.odometer') }}">Odometer Settings</a>
                            </li>
                        @endcan

                        @can('Settings >>')
                        </ul>
                    @endcan
                </li>
                <!-- //settings-->

                @can('Admin Dashboard')
                    <li class="menu-title"> <span>FLEET</span> </li>
                @endcan

                @can('Service Providers')
                    <li class="@if (str_contains(url()->current(), '/settings/vendors')) active @endif">
                        <a href="{{ route('settings.vendors') }}"><i class="fa fa-suitcase"></i> <span>Service
                                Providers</span> <span
                                class="badge rounded-pill bg-primary float-end">{{ $total_vendors }}</span></a>
                    </li>
                @endcan

                @can('Vehicle Registration')
                    <li class="@if (str_contains(url()->current(), '/vehicle-registration-transfer')) active @endif">
                        <a href="{{ route('fleet.vehicle.registration') }}"><i class="fa fa-fax"></i> <span>Vehicle
                                Registration <br>& Transfer</span></a>
                    </li>
                @endcan
                @can('DVLA Road Worthy')
                    <li>
                        <a href="{{ route('fleet.vehicle.dvla.road.worthiness') }}"><i class="fa fa-road"></i> <span>Dvla
                                - Road Worthy</span></a>
                    </li>
                @endcan
                @can('Overdue Odometers')
                    <li class="@if (str_contains(url()->current(), '/odometer/overdue')) active @endif">
                        <a href="{{ route('fleet.vehicle.odometer.overdue') }}"><i class="fa fa-tachometer"></i>
                            Overdue Odometers <span
                                        class="badge rounded-pill bg-primary float-end">{{ $odo_overdue  }}</span></a>
                    </li>
                @endcan
                @can('Driver License')
                    <li class="@if (str_contains(url()->current(), '/fleet/driver/license')) active @endif">
                        <a href="{{ route('fleet.vehicle.driver.license') }}"><i class="fa fa-drivers-license"></i>
                            <span>Drivers - Drivers License</span></a>
                    </li>
                @endcan

                {{-- car requests --}}
                @can('Car Requests >>')
                    <li class="submenu @if (str_contains(url()->current(), '/car-requests')) active @endif">
                        <a href="#" class="noti-dot"><i class="fa fa-car"></i> <span> Car Requests</span> <span
                                class="menu-arrow"></span></a>
                        <ul style="display: none;">
                        @endcan

                        @can(' Car Requests')
                            <li><a class="@if (str_contains(url()->current(), '/car-requests/index')) active @endif"
                                    href="{{ route('fleet.vehicle.request') }}">Car Requests </a></li>
                            @endcan @can(' Car Request History')
                            <li><a class="@if (str_contains(url()->current(), '/car-requests/my-car-requests')) active @endif"
                                    href="{{ route('fleet.vehicle.myrequests') }}">My Car Request <br>History <span
                                        class="badge rounded-pill bg-primary float-end">{{ $car_requests_history_total }}</span></a>
                            </li>
                        @endcan

                        @can('Car Requests >>')
                        </ul>
                    </li>
                @endcan


                {{-- finance requests --}}
                @can('Finance Requests >>')
                    <li class="submenu @if (str_contains(url()->current(), '/finance-requests')) active @endif">
                        <a href="#" class="noti-dot"><i class="fas fa-credit-card"></i> <span> Finance
                                Requests</span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                        @endcan
                        @can(' General Requests')
                            <li> <a class="@if (str_contains(url()->current(), '/finance-requests/index')) active @endif"
                                    href="{{ route('finance.requests.home') }}">General Requests</a>
                            </li>
                        @endcan
                        @can(' Parts Purchase Request')
                            <li> <a class="@if (str_contains(url()->current(), '/finance-parts/purchase')) active @endif"
                                    href="{{ route('parts.purchase.order') }}">Parts Purchase Request </a>
                            </li>
                        @endcan

                        @can('Finance Requests >>')
                        </ul>
                    </li>
                @endcan


                {{-- Auto Parts Store --}}
                @can('Auto Parts Store >>')
                    <li class="submenu @if (str_contains(url()->current(), '/inventory')) active @endif">
                        <a href="#" class="noti-dot"><i class="fas fa-tools"></i> <span> Auto Parts Store
                            </span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                        @endcan

                        @can(' Parts Inventory')
                            <li> <a class="@if (str_contains(url()->current(), '/inventory/index')) active @endif"
                                    href="{{ route('inventory.index') }}">Parts Inventory</a>
                            </li>
                        @endcan
                        @can(' Parts Usage Request')
                            <li> <a class="@if (str_contains(url()->current(), '/finance-parts/purchase')) active @endif"
                                    href="{{ route('parts.purchase.order') }}">Parts Usage Request</a>
                            </li>
                        @endcan
                        @can(' Damaged Parts Processing')
                            <li> <a class="@if (str_contains(url()->current(), '/finance-parts/purchase')) active @endif"
                                    href="{{ route('auto.parts.index') }}">Damaged Parts Processing</a>
                            </li>
                        @endcan
                        @can(' Auto Parts')
                            <li> <a class="@if (str_contains(url()->current(), '/auto-parts')) active @endif"
                                    href="{{ route('auto.parts.index') }}">Auto Parts</a>
                            </li>
                        @endcan

                        @can('Auto Parts Store >>')
                        </ul>
                    </li>
                @endcan


                @can('Invoices')
                    <li class="@if (str_contains(url()->current(), '/finance/invoice')) active @endif">
                        <a href="{{ route('finance.invoice.index') }}"><i class="fa fa-picture-o"></i>
                            <span>Invoice</span> <span
                                class="badge rounded-pill bg-primary float-end">{{ $invoices }}</span></a>
                    </li>
                @endcan

                {{-- Reporst --}}
                @can('Reports >>')
                    <li class="submenu @if (str_contains(url()->current(), '/reports')) active @endif">
                        <a href="#" class="noti-dot"><i class="fa fa-list-alt"></i> <span> Reports</span> <span
                                class="menu-arrow"></span></a>
                        <ul style="display: none;">
                        @endcan

                        @can(' Accidents')
                            <li><a class="@if (str_contains(url()->current(), '/reports/accident')) active @endif"
                                    href="{{ route('fleet.vehicle.reports.accident') }}">Accident </a></li>
                        @endcan
                        @can(' Elog')
                            <li><a class="@if (str_contains(url()->current(), '/reports/elog')) active @endif"
                                    href="{{ route('fleet.vehicle.reports.elog') }}">ELog </a></li>
                        @endcan
                        @can(' Odometer')
                            <li><a class="@if (str_contains(url()->current(), '/reports/odometer')) active @endif"
                                    href="{{ route('fleet.vehicle.odometer.report') }}">Odometer </a></li>
                        @endcan
                        @can(' Maintenance')
                            <li><a class="@if (str_contains(url()->current(), '/reports/maintenance')) active @endif"
                                    href="{{ route('fleet.vehicle.maintenance.report') }}">Maintenance </a></li>
                        @endcan
                        @can(' Diagnosis')
                            <li><a class="@if (str_contains(url()->current(), '/reports/diagnosis')) active @endif"
                                    href="{{ route('fleet.vehicle.diagnosis.report') }}">Diagnosis </a></li>
                        @endcan
                        @can('Reports >>')
                        </ul>
                    </li>
                @endcan

                {{-- Archive --}}
                @can('Archive >>')
                    <li class="submenu @if (str_contains(url()->current(), '/archive')) active @endif">
                        <a href="#" class="noti-dot"><i class="fa fa-archive"></i> <span> Archive</span> <span
                                class="menu-arrow"></span></a>
                        <ul style="display: none;">
                        @endcan
                        @can(' Car')
                            <li><a class="@if (str_contains(url()->current(), '/cars/archived')) active @endif"
                                    href="{{ route('cars.archived') }}">Car </a></li>
                        @endcan
                        @can(' Region')
                            <li><a class="@if (str_contains(url()->current(), '/archived')) active @endif"
                                    href="{{ route('regions.archived') }}">Region </a></li>
                        @endcan
                        @can(' Department')
                            <li><a class="@if (str_contains(url()->current(), '/archived')) active @endif"
                                    href="{{ route('departments.archived') }}">Department </a></li>
                        @endcan
                        @can(' Zone')
                            <li><a class="@if (str_contains(url()->current(), '/archived')) active @endif"
                                    href="{{ route('sectors.archived') }}">Zone </a></li>
                        @endcan
                        @can('Archive >>')
                        </ul>
                    </li>
                @endcan


                @can('Work Order')
                    <li class="@if (str_contains(url()->current(), '/vehicle/maintenance')) active @endif">
                        <a href="{{ route('fleet.vehicle.maintenance') }}"><i class="fa fa-wrench"></i> <span>Work
                                Order</span></a>
                    </li>
                @endcan
                @can('Garage')
                    <li class="@if (str_contains(url()->current(), '/vehicle/garage')) active @endif">
                        <a href="{{ route('fleet.vehicle.garage') }}"><i class="fa fa-building"></i>
                            <span>Garage</span></a>
                    </li>
                @endcan
                @can('Insurance')
                    <li class="@if (str_contains(url()->current(), '/fleet/insurance')) active @endif">
                        <a href="{{ route('fleet.vehicle.insurance') }}"><i class="fa fa-id-card"></i>
                            <span>Insurance</span></a>
                    </li>
                @endcan
                @can('Waybills')
                    <li class="@if (str_contains(url()->current(), '/fleet/waybill')) active @endif">
                        <a href="{{ route('fleet.waybill.index') }}"><i class="fa fa-file-text"></i>
                            <span>Waybill</span></a>
                    </li>
                @endcan

            </ul> <!--FLEET MANAGER MENU END-->


            {{-- DRIVER MENU --}}
            <ul class="sidebar-vertical">
                @can('Driver Dashboard')
                    <li class="menu-title">
                        <span>Main</span>
                    </li>

                    <li class="@if (str_contains(url()->current(), '/driver/dashboard')) active @endif">
                        <a href="{{ route('driver.dashboard') }}"><i class="fa fa-home"></i> <span>Home</span></a>
                    </li>
                @endcan

                @if (auth()->user()->hasCar())
                    @can('Odometer Manager')
                        <li class="@if (str_contains(url()->current(), '/driver/odometer')) active @endif">
                            <a href="{{ route('driver.odometer') }}"><i class="fa fa-tachometer"></i> <span>Odometer
                                    Manager</span></a>
                        </li>
                    @endcan
                    @can('Accident Report')
                        <li class="@if (str_contains(url()->current(), '/driver/report/accident')) active @endif">
                            <a href="{{ route('driver.report.accident') }}"><i class="fa fa-ambulance"></i>
                                <span>Accident Report</span></a>
                        </li>
                    @endcan
                    @can('Elog Report')
                        <li class="@if (str_contains(url()->current(), '/driver/report/elog')) active @endif">
                            <a href="{{ route('driver.report.elog') }}"><i class="fa fa-list-alt"></i> <span>ELog
                                    Report</span></a>
                        </li>
                    @endcan
                    @can('Waybill')
                        <li class="@if (str_contains(url()->current(), '/driver/waybill')) active @endif">
                            <a href="{{ route('driver.waybill.index') }}"><i class="fa fa-file-text"></i>
                                <span>Waybill</span></a>
                        </li>
                    @endif
                @endcan
                @can('Car Requests')
                    <li class="@if (str_contains(url()->current(), '/driver/car-requests')) active @endif">
                        <a href="{{ route('driver.vehicle.request') }}"><i class="fa fa-car"></i> <span>Car
                                Requests</span></a>
                    </li>
                @endcan
            </ul>

            {{-- MECHANIC MENUS --}}
            <ul class="sidebar-vertical">
                @can('Mechanic Dashboard')
                    <li class="menu-title">
                        <span>Main</span>
                    </li>
                    <li class="@if (str_contains(url()->current(), '/mechanic/dashboard')) active @endif">
                        <a href="{{ route('mechanic.dashboard') }}"><i class="fa fa-home"></i> <span>Home</span></a>
                    </li>
                @endcan
                @can('Mechanic Garage')
                    <li class="@if (str_contains(url()->current(), '/mechanic/garage')) active @endif">
                        <a href="{{ route('mechanic.garage') }}"><i class="fa fa-building"></i>
                            <span>Garage</span></a>
                    </li>
                @endcan
            </ul>

            {{-- ACCOUNT MENUS --}}
            <ul class="sidebar-vertical">
                @can('Account Dashboard')
                    <li class="menu-title">
                        <span>Main</span>
                    </li>
                    <li class="@if (str_contains(url()->current(), '/accounts/dashboard')) active @endif">
                        <a href="{{ route('account.dashboard') }}"><i class="fa fa-home"></i> <span> Home</span></a>
                    </li>
                @endcan

                @can('Finder >>')
                    <li class="submenu @if (str_contains(url()->current(), '/finder')) active @endif">
                        <a href="#" class="noti-dot"><i class="fa fa-search"></i> <span>Finder</span> <span
                                class="menu-arrow"></span></a>
                        <ul style="display: none;">
                        @endcan
                        @can(' Finder-Car')
                            <li><a class="@if (str_contains(url()->current(), '/finder/car')) active @endif"
                                    href="{{ route('accounts.finder.home.car') }}">Car </a></li>
                        @endcan
                        @can(' Finder-User')
                            <li><a class="@if (str_contains(url()->current(), '/finder/user')) active @endif"
                                    href="{{ route('accounts.finder.home.user') }}">User </a></li>
                        @endcan
                        @can('Finder >>')
                        </ul>
                    </li>
                @endcan

                @can('Payments >>')
                    <li class="submenu @if (str_contains(url()->current(), '/payment')) active @endif">
                        <a href="#" class="noti-dot"><i class="fas fa-credit-card"></i> <span>Payments</span>
                            <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                        @endcan
                        @can(' Payment Requests')
                            <li><a class="@if (str_contains(url()->current(), '/payment/requests')) active @endif"
                                    href="{{ route('accounts.payment.requests') }}">Payment Requests </a></li>
                        @endcan
                        @can(' Payment History')
                            <li><a class="@if (str_contains(url()->current(), '/payment/history')) active @endif"
                                    href="{{ route('accounts.payment.history') }}">Payment History </a></li>
                        @endcan
                        @can('Payments >>')
                        </ul>
                    </li>
                @endcan

                @can('Work Orders')
                    <li class="@if (str_contains(url()->current(), '/accounts/orders')) active @endif">
                        <a href="{{ route('accounts.orders') }}"><i class="fa fa-building"
                                style="margin-right: 15px;"></i>Work Orders</a>
                    </li>
                @endcan

                @can('FM Invoices')
                    <li class="@if (str_contains(url()->current(), '/accounts/invoice')) active @endif">
                        <a href="{{ route('accounts.invoice') }}"><i class="fa fa-building" style="margin-right: 15px;">
                            </i>Invoice</a>
                    </li>
                @endcan
            </ul>

        </div>
    </div>
</div>

{{-- <div class="two-col-bar" id="two-col-bar">
    <div class="sidebar sidebar-twocol" id="navbar-nav">
        <div class="sidebar-left slimscroll">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-dashboard-tab" title="Dashboard" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-dashboard" role="tab" aria-controls="v-pills-dashboard"
                    aria-selected="true">
                    <span class="material-icons-outlined">
                        home
                    </span>
                </a>
                <a class="nav-link" id="v-pills-apps-tab" title="Apps" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-apps" role="tab" aria-controls="v-pills-apps"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        dashboard
                    </span>
                </a>
                <a class="nav-link" id="v-pills-employees-tab" title="Employees" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-employees" role="tab" aria-controls="v-pills-employees"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        people
                    </span>
                </a>
                <a class="nav-link" id="v-pills-clients-tab" title="Clients" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-clients" role="tab" aria-controls="v-pills-clients"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        person
                    </span>
                </a>
                <a class="nav-link" id="v-pills-projects-tab" title="Projects" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-projects" role="tab" aria-controls="v-pills-projects"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        topic
                    </span>
                </a>
                <a class="nav-link" id="v-pills-leads-tab" title="Leads" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-leads" role="tab" aria-controls="v-pills-leads"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        leaderboard
                    </span>
                </a>
                <a class="nav-link" id="v-pills-tickets-tab" title="Tickets" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-tickets" role="tab" aria-controls="v-pills-tickets"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        confirmation_number
                    </span>
                </a>
                <a class="nav-link" id="v-pills-sales-tab" title="Sales" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-sales" role="tab" aria-controls="v-pills-sales"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        shopping_bag
                    </span>
                </a>
                <a class="nav-link" id="v-pills-accounting-tab" title="Accounting" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-accounting" role="tab" aria-controls="v-pills-accounting"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        account_balance_wallet
                    </span>
                </a>
                <a class="nav-link" id="v-pills-payroll-tab" title="Payroll" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-payroll" role="tab" aria-controls="v-pills-payroll"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        request_quote
                    </span>
                </a>
                <a class="nav-link" id="v-pills-policies-tab" title="Policies" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-policies" role="tab" aria-controls="v-pills-policies"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        verified_user
                    </span>
                </a>
                <a class="nav-link" id="v-pills-reports-tab" title="Reports" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-reports" role="tab" aria-controls="v-pills-reports"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        report_gmailerrorred
                    </span>
                </a>
                <a class="nav-link" id="v-pills-performance-tab" title="Performance" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-performance" role="tab"
                    aria-controls="v-pills-performance" aria-selected="false">
                    <span class="material-icons-outlined">
                        shutter_speed
                    </span>
                </a>
                <a class="nav-link" id="v-pills-goals-tab" title="Goals" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-goals" role="tab" aria-controls="v-pills-goals"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        track_changes
                    </span>
                </a>
                <a class="nav-link" id="v-pills-training-tab" title="Training" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-training" role="tab" aria-controls="v-pills-training"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        checklist_rtl
                    </span>
                </a>
                <a class="nav-link" id="v-pills-promotion-tab" title="Promotions" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-promotion" role="tab" aria-controls="v-pills-promotion"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        auto_graph
                    </span>
                </a>
                <a class="nav-link" id="v-pills-resignation-tab" title="Resignation" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-resignation" role="tab"
                    aria-controls="v-pills-resignation" aria-selected="false">
                    <span class="material-icons-outlined">
                        do_not_disturb_alt
                    </span>
                </a>
                <a class="nav-link" id="v-pills-termination-tab" title="Termination" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-termination" role="tab"
                    aria-controls="v-pills-termination" aria-selected="false">
                    <span class="material-icons-outlined">
                        indeterminate_check_box
                    </span>
                </a>
                <a class="nav-link" id="v-pills-assets-tab" title="Assets" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-assets" role="tab" aria-controls="v-pills-assets"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        web_asset
                    </span>
                </a>
                <a class="nav-link " id="v-pills-jobs-tab" title="Jobs" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-jobs" role="tab" aria-controls="v-pills-jobs"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        work_outline
                    </span>
                </a>
                <a class="nav-link" id="v-pills-knowledgebase-tab" title="Knowledgebase" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-knowledgebase" role="tab"
                    aria-controls="v-pills-knowledgebase" aria-selected="false">
                    <span class="material-icons-outlined">
                        school
                    </span>
                </a>
                <a class="nav-link" id="v-pills-activities-tab" title="Activities" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-activities" role="tab" aria-controls="v-pills-activities"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        toggle_off
                    </span>
                </a>
                <a class="nav-link" id="v-pills-users-tab" title="Users" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-users" role="tab" aria-controls="v-pills-users"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        group_add
                    </span>
                </a>
                <a class="nav-link" id="v-pills-settings-tab" title="Settings" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-settings" role="tab" aria-controls="v-pills-settings"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        settings
                    </span>
                </a>
                <a class="nav-link" id="v-pills-profile-tab" title="Profile" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-profile" role="tab" aria-controls="v-pills-profile"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        manage_accounts
                    </span>
                </a>
                <a class="nav-link" id="v-pills-authentication-tab" title="Authentication" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-authentication" role="tab"
                    aria-controls="v-pills-authentication" aria-selected="false">
                    <span class="material-icons-outlined">
                        perm_contact_calendar
                    </span>
                </a>
                <a class="nav-link" id="v-pills-errorpages-tab" title="Error Pages" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-errorpages" role="tab" aria-controls="v-pills-errorpages"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        announcement
                    </span>
                </a>
                <a class="nav-link" id="v-pills-subscriptions-tab" title="Subscriptions" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-subscriptions" role="tab"
                    aria-controls="v-pills-subscriptions" aria-selected="false">
                    <span class="material-icons-outlined">
                        loyalty
                    </span>
                </a>
                <a class="nav-link" id="v-pills-pages-tab" title="Pages" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-pages" role="tab" aria-controls="v-pills-pages"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        layers
                    </span>
                </a>
                <a class="nav-link" id="v-pills-forms-tab" title="Forms" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-forms" role="tab" aria-controls="v-pills-forms"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        view_day
                    </span>
                </a>
                <a class="nav-link" id="v-pills-tables-tab" title="Tables" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-tables" role="tab" aria-controls="v-pills-tables"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        table_rows
                    </span>
                </a>
                <a class="nav-link" id="v-pills-documentation-tab" title="Documentation" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-documentation" role="tab"
                    aria-controls="v-pills-documentation" aria-selected="false">
                    <span class="material-icons-outlined">
                        description
                    </span>
                </a>
                <a class="nav-link" id="v-pills-changelog-tab" title="Changelog" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-changelog" role="tab" aria-controls="v-pills-changelog"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        sync_alt
                    </span>
                </a>
                <a class="nav-link" id="v-pills-multilevel-tab" title="Multilevel" data-bs-toggle="pill"
                    href="admin-dashboard.html#v-pills-multilevel" role="tab" aria-controls="v-pills-multilevel"
                    aria-selected="false">
                    <span class="material-icons-outlined">
                        library_add_check
                    </span>
                </a>
            </div>
        </div>
        <div class="sidebar-right">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel"
                    aria-labelledby="v-pills-dashboard-tab">
                    <p>Dashboard</p>
                    <ul>
                        <li>
                            <a href="admin-dashboard.html" class="active">Admin Dashboard</a>
                        </li>
                        <li>
                            <a href="employee-dashboard.html">Employee Dashboard</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-apps" role="tabpanel" aria-labelledby="v-pills-apps-tab">
                    <p>App</p>
                    <ul>
                        <li>
                            <a href="chat.html">Chat</a>
                        </li>
                        <li class="sub-menu">
                            <a href="admin-dashboard.html#">Calls <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a href="voice-call.html">Voice Call</a></li>
                                <li><a href="video-call.html">Video Call</a></li>
                                <li><a href="outgoing-call.html">Outgoing Call</a></li>
                                <li><a href="incoming-call.html">Incoming Call</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="calender.html">Calendar</a>
                        </li>
                        <li>
                            <a href="contacts.html">Contacts</a>
                        </li>
                        <li>
                            <a href="inbox.html">Email</a>
                        </li>
                        <li>
                            <a href="file-manager.html">File Manager</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-employees" role="tabpanel"
                    aria-labelledby="v-pills-employees-tab">
                    <p>Employees</p>
                    <ul>
                        <li><a href="employees.html">All Employees</a></li>
                        <li><a href="holidays.html">Holidays</a></li>
                        <li><a href="leaves.html">Leaves (Admin) <span
                                    class="badge rounded-pill bg-primary float-end">1</span></a></li>
                        <li><a href="leaves-employee.html">Leaves (Employee)</a></li>
                        <li><a href="leave-settings.html">Leave Settings</a></li>
                        <li><a href="attendance.html">Attendance (Admin)</a></li>
                        <li><a href="attendance-employee.html">Attendance (Employee)</a></li>
                        <li><a href="departments.html">Departments</a></li>
                        <li><a href="designations.html">Designations</a></li>
                        <li><a href="timesheet.html">Timesheet</a></li>
                        <li><a href="shift-scheduling.html">Shift & Schedule</a></li>
                        <li><a href="overtime.html">Overtime</a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-clients" role="tabpanel"
                    aria-labelledby="v-pills-clients-tab">
                    <p>Clients</p>
                    <ul>
                        <li><a href="clients.html">Clients</a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-projects" role="tabpanel"
                    aria-labelledby="v-pills-projects-tab">
                    <p>Projects</p>
                    <ul>
                        <li><a href="projects.html">Projects</a></li>
                        <li><a href="tasks.html">Tasks</a></li>
                        <li><a href="task-board.html">Task Board</a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-leads" role="tabpanel" aria-labelledby="v-pills-leads-tab">
                    <p>Leads</p>
                    <ul>
                        <li><a href="leads.html">Leads</a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-tickets" role="tabpanel"
                    aria-labelledby="v-pills-tickets-tab">
                    <p>Tickets</p>
                    <ul>
                        <li><a href="tickets.html">Tickets</a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-sales" role="tabpanel" aria-labelledby="v-pills-sales-tab">
                    <p>Sales</p>
                    <ul>
                        <li><a href="estimates.html">Estimates</a></li>
                        <li><a href="invoices.html">Invoices</a></li>
                        <li><a href="payments.html">Payments</a></li>
                        <li><a href="expenses.html">Expenses</a></li>
                        <li><a href="provident-fund.html">Provident Fund</a></li>
                        <li><a href="taxes.html">Taxes</a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-accounting" role="tabpanel"
                    aria-labelledby="v-pills-accounting-tab">
                    <p>Accounting</p>
                    <ul>
                        <li><a href="categories.html">Categories</a></li>
                        <li><a href="budgets.html">Budgets</a></li>
                        <li><a href="budget-expenses.html">Budget Expenses</a></li>
                        <li><a href="budget-revenues.html">Budget Revenues</a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-payroll" role="tabpanel"
                    aria-labelledby="v-pills-payroll-tab">
                    <p>Payroll</p>
                    <ul>
                        <li><a href="salary.html"> Employee Salary </a></li>
                        <li><a href="salary-view.html"> Payslip </a></li>
                        <li><a href="payroll-items.html"> Payroll Items </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-policies" role="tabpanel"
                    aria-labelledby="v-pills-policies-tab">
                    <p>Policies</p>
                    <ul>
                        <li><a href="policies.html"> Policies </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-reports" role="tabpanel"
                    aria-labelledby="v-pills-reports-tab">
                    <p>Reports</p>
                    <ul>
                        <li><a href="expense-reports.html"> Expense Report </a></li>
                        <li><a href="invoice-reports.html"> Invoice Report </a></li>
                        <li><a href="payments-reports.html"> Payments Report </a></li>
                        <li><a href="project-reports.html"> Project Report </a></li>
                        <li><a href="task-reports.html"> Task Report </a></li>
                        <li><a href="user-reports.html"> User Report </a></li>
                        <li><a href="employee-reports.html"> Employee Report </a></li>
                        <li><a href="payslip-reports.html"> Payslip Report </a></li>
                        <li><a href="attendance-reports.html"> Attendance Report </a></li>
                        <li><a href="leave-reports.html"> Leave Report </a></li>
                        <li><a href="daily-reports.html"> Daily Report </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-performance" role="tabpanel"
                    aria-labelledby="v-pills-performance-tab">
                    <p>Performance</p>
                    <ul>
                        <li><a href="performance-indicator.html"> Performance Indicator </a></li>
                        <li><a href="performance.html"> Performance Review </a></li>
                        <li><a href="performance-appraisal.html"> Performance Appraisal </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-goals" role="tabpanel" aria-labelledby="v-pills-goals-tab">
                    <p>Goals</p>
                    <ul>
                        <li><a href="goal-tracking.html"> Goal List </a></li>
                        <li><a href="goal-type.html"> Goal Type </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-training" role="tabpanel"
                    aria-labelledby="v-pills-training-tab">
                    <p>Training</p>
                    <ul>
                        <li><a href="training.html"> Training List </a></li>
                        <li><a href="trainers.html"> Trainers</a></li>
                        <li><a href="training-type.html"> Training Type </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-promotion" role="tabpanel"
                    aria-labelledby="v-pills-promotion-tab">
                    <p>Promotion</p>
                    <ul>
                        <li><a href="promotion.html"> Promotion </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-resignation" role="tabpanel"
                    aria-labelledby="v-pills-resignation-tab">
                    <p>Resignation</p>
                    <ul>
                        <li><a href="resignation.html"> Resignation </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-termination" role="tabpanel"
                    aria-labelledby="v-pills-termination-tab">
                    <p>Termination</p>
                    <ul>
                        <li><a href="termination.html"> Termination </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-assets" role="tabpanel" aria-labelledby="v-pills-assets-tab">
                    <p>Assets</p>
                    <ul>
                        <li><a href="assets.html"> Assets </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade " id="v-pills-jobs" role="tabpanel" aria-labelledby="v-pills-jobs-tab">
                    <p>Jobs</p>
                    <ul>
                        <li><a href="user-dashboard.html" class="active"> User Dasboard </a></li>
                        <li><a href="jobs-dashboard.html"> Jobs Dasboard </a></li>
                        <li><a href="jobs.html"> Manage Jobs </a></li>
                        <li><a href="job-applicants.html"> Applied Jobs </a></li>
                        <li><a href="manage-resumes.html"> Manage Resumes </a></li>
                        <li><a href="shortlist-candidates.html"> Shortlist Candidates </a></li>
                        <li><a href="interview-questions.html"> Interview Questions </a></li>
                        <li><a href="offer_approvals.html"> Offer Approvals </a></li>
                        <li><a href="experiance-level.html"> Experience Level </a></li>
                        <li><a href="candidates.html"> Candidates List </a></li>
                        <li><a href="schedule-timing.html"> Schedule timing </a></li>
                        <li><a href="apptitude-result.html"> Aptitude Results </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-knowledgebase" role="tabpanel"
                    aria-labelledby="v-pills-knowledgebase-tab">
                    <p>Knowledgebase</p>
                    <ul>
                        <li><a href="knowledgebase.html"> Knowledgebase </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-activities" role="tabpanel"
                    aria-labelledby="v-pills-activities-tab">
                    <p>Activities</p>
                    <ul>
                        <li><a href="activities.html"> Activities </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-users" role="tabpanel"
                    aria-labelledby="v-pills-activities-tab">
                    <p>Users</p>
                    <ul>
                        <li><a href="users.html"> Users </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel"
                    aria-labelledby="v-pills-settings-tab">
                    <p>Settings</p>
                    <ul>
                        <li><a href="settings.html"> Settings </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                    aria-labelledby="v-pills-profile-tab">
                    <p>Profile</p>
                    <ul>
                        <li><a href="profile.html"> Employee Profile </a></li>
                        <li><a href="client-profile.html"> Client Profile </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-authentication" role="tabpanel"
                    aria-labelledby="v-pills-authentication-tab">
                    <p>Authentication</p>
                    <ul>
                        <li><a href="index.html"> Login </a></li>
                        <li><a href="register.html"> Register </a></li>
                        <li><a href="forgot-password.html"> Forgot Password </a></li>
                        <li><a href="otp.html"> OTP </a></li>
                        <li><a href="lock-screen.html"> Lock Screen </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-errorpages" role="tabpanel"
                    aria-labelledby="v-pills-errorpages-tab">
                    <p>Error Pages</p>
                    <ul>
                        <li><a href="error-404.html">404 Error </a></li>
                        <li><a href="error-500.html">500 Error </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-subscriptions" role="tabpanel"
                    aria-labelledby="v-pills-subscriptions-tab">
                    <p>Subscriptions</p>
                    <ul>
                        <li><a href="subscriptions.html"> Subscriptions (Admin) </a></li>
                        <li><a href="subscriptions-company.html"> Subscriptions (Company) </a></li>
                        <li><a href="subscribed-companies.html"> Subscribed Companies</a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-pages" role="tabpanel" aria-labelledby="v-pills-pages-tab">
                    <p>Pages</p>
                    <ul>
                        <li><a href="search.html"> Search </a></li>
                        <li><a href="faq.html"> FAQ </a></li>
                        <li><a href="terms.html"> Terms </a></li>
                        <li><a href="privacy-policy.html"> Privacy Policy </a></li>
                        <li><a href="blank-page.html"> Blank Page </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-forms" role="tabpanel" aria-labelledby="v-pills-forms-tab">
                    <p>Forms</p>
                    <ul>
                        <li><a href="form-basic-inputs.html">Basic Inputs </a></li>
                        <li><a href="form-input-groups.html">Input Groups </a></li>
                        <li><a href="form-horizontal.html">Horizontal Form </a></li>
                        <li><a href="form-vertical.html"> Vertical Form </a></li>
                        <li><a href="form-mask.html"> Form Mask </a></li>
                        <li><a href="form-validation.html"> Form Validation </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-tables" role="tabpanel" aria-labelledby="v-pills-tables-tab">
                    <p>Tables</p>
                    <ul>
                        <li><a href="tables-basic.html">Basic Tables </a></li>
                        <li><a href="data-tables.html">Data Table </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-documentation" role="tabpanel"
                    aria-labelledby="v-pills-documentation-tab">
                    <p>Documentation</p>
                    <ul>
                        <li><a href="admin-dashboard.html#">Documentation </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-changelog" role="tabpanel"
                    aria-labelledby="v-pills-changelog-tab">
                    <p>Change Log</p>
                    <ul>
                        <li><a href="admin-dashboard.html#"><span>Change Log</span> <span
                                    class="badge badge-primary ms-auto">v3.4</span> </a></li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-multilevel" role="tabpanel"
                    aria-labelledby="v-pills-multilevel-tab">
                    <p>Multi Level</p>
                    <ul>
                        <li class="sub-menu">
                            <a href="javascript:void(0);">Level 1 <span class="menu-arrow"></span></a>
                            <ul style="display: none;" class="ms-3">
                                <li class="sub-menu">
                                    <a href="javascript:void(0);">Level 1 <span class="menu-arrow"></span></a>
                                    <ul>
                                        <li><a href="javascript:void(0);">Level 2</a></li>
                                        <li><a href="javascript:void(0);">Level 3</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="javascript:void(0);">Level 2</a></li>
                        <li><a href="javascript:void(0);">Level 3</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div> --}}
