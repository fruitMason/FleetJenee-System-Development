@extends('layouts.master')
@section('page_title', 'Dashboard')
@section('content')
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Welcome {{ auth()->user()->first_name }}, {{ auth()->user()->getRole() }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="row">
                <h4>Year To Date Expense Overview</h4>
                <!--insurance cost-->
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon" style="background-color:  #e7fffd;color : rgb(0, 0, 0);"><i
                                    class="fa fa-credit-card"></i></span>
                            <div class="dash-widget-info">
                                <h3> @fmoney($insurance_y2d)</h3>
                                <span class="text-sm text-secondary">Total Insurance Cost</span>
                            </div>

                        </div>
                    </div>
                </div>
                <!--//insurance cost-->

                <!--maintenance cost-->
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
                    <div class="card dash-widget ">
                        <div class="card-body">
                            <span class="dash-widget-icon" style="background-color:  #e7fffd;color : rgb(0, 0, 0);"><i
                                    class="fa fa-credit-card"></i></span>
                            <div class="dash-widget-info">
                                <h3> @fmoney($maintenance_y2d)</h3>
                                <span class="text-sm text-secondary">Total Maintenance Cost</span>
                            </div>

                        </div>
                    </div>
                </div>
                <!--//maintenance cost-->

                <!--road worth cost-->
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
                    <div class="card dash-widget ">
                        <div class="card-body">
                            <span class="dash-widget-icon" style="background-color: #e2ffff; color:black;"><i
                                    class="fa fa-credit-card"></i></span>
                            <div class="dash-widget-info">
                                <h3> @fmoney($road_worthy_y2d)</h3>
                                <span class="text-sm text-secondary">Road Worthy Cost</span>
                            </div>

                        </div>
                    </div>
                </div>
                <!--//road worth cost-->

                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
                    <div class="card dash-widget ">
                        <div class="card-body">
                            <span class="dash-widget-icon" style="background-color:  #e7fffd;color : black;"><i
                                    class="fa fa-credit-card"></i></span>
                            <div class="dash-widget-info">
                                <h3> @fmoney($fuel_y2d) </h3>
                                <span class="text-sm text-secondary">Total Fuel Cost</span>
                            </div>
                            <div> <span class="text-sm" style="color:gray">Fuel Expenses</span> </div>
                        </div>
                    </div>
                </div>

                {{-- ROW 2 --}}
                <!--road others cost-->
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
                    <div class="card dash-widget ">
                        <div class="card-body">
                            <span class="dash-widget-icon" style="background-color:  #e7fffd;color : rgb(0, 0, 0);"><i
                                    class="fa fa-credit-card"></i></span>
                            <div class="dash-widget-info">
                                <h3> @fmoney($other_y2d)</h3>
                                <span class="text-sm text-secondary">Others costs</span>
                            </div>
                            <div> <span class="text-sm" style="color:gray">General Expenses</span> </div>

                        </div>
                    </div>
                </div>
                <!--//road others cost-->

                <!--payment Old Cars cost-->
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon" style="background-color:  #e7fffd;color : rgb(0, 0, 0);"><i
                                    class="fa fa-car"></i></span>
                            <div class="dash-widget-info">
                                <h3>{{ $old_cars }}</h3>
                                <span class="text-sm text-secondary">Old Cars <span
                                        class="text-secondary text-small font-weight-light">(3yrs
                                        over)</span>
                                </span>
                            </div>
                            <div class="row">
                                <a href="{{ route('accounts.old.cars') }}">view more</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--//end payment Old Cars cost-->
            </div>

            {{-- row 3 --}}
            <div class="row">
                <!--payment requests -->
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon" style="background-color:  #e7fffd;color : rgb(0, 0, 0);"><i
                                    class="fas fa-credit-card"></i></span>
                            <div class="dash-widget-info">
                                <h3> @fmoney($pending_requests_amount) </h3>
                                <span class="text-sm text-secondary">Payment Requests <span
                                        class="text-secondary text-sm font-weight-light">({{ $pending_requests }})</span>
                                </span>
                            </div>
                            <div class="row">
                                <a href="{{ route('accounts.payment.requests') }}">view more</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--//end payment requests -->

                <!--payment invoices-->
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
                    <div class="card dash-widget ">
                        <div class="card-body">
                            <span class="dash-widget-icon" style="background-color: #daffe9;color : green;"><i
                                    class="fa fa-credit-card"></i></span>
                            <div class="dash-widget-info">
                                <h3> @fmoney($pending_invoices_cost) </h3>
                                <span class="text-sm text-secondary">Pending Invoices
                                    <span
                                        class="text-secondary text-small font-weight-light">({{ $pending_invoices }})</span>
                                </span>

                            </div>
                            <div class="col-auto"> <a href="{{ route('accounts.invoice') }}">view more</a></div>
                        </div>
                    </div>
                </div>
                <!--payment invoices-->

                <!--pending orders-->
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
                    <div class="card dash-widget ">
                        <div class="card-body">
                            <span class="dash-widget-icon" style="background-color: #f7f8c3;color : rgb(128, 128, 0);"><i
                                    class="fa fa-credit-card"></i></span>
                            <div class="dash-widget-info">
                                <h3> {{ $pending_orders }} </h3>
                                <span class="text-sm text-secondary">Pending Work Orders
                                    <span class="text-secondary text-small font-weight-light">({{ $pending_orders }})</span>
                                </span>

                            </div>
                            <div class="col-auto"> <a href="{{ route('accounts.orders') }}">view more</a></div>
                        </div>
                    </div>
                </div>
                <!--pending orders-->
            </div>



            <!--graphs cost-->
            <div class="row">
                {{-- Expense Type Distribution  --}}
                <div class="col-12 col-lg-6 mb-4">
                    <div class="card shadow-sm h-100 bg-white">
                        <div class="card-body p-4">
                            <div class="pb-3">
                                <h5 class="card-title text-sm" style="color:gray;">Expense Type Distibution</h5>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    {{-- <canvas id="ChurchActiveInactive" class="w-100" style="max-width: 193px; max-height: 193px;"></canvas> --}}
                                    <canvas id="expenseDistribution"></canvas>
                                </div>


                                <div class="col-md-4">
                                    <div class="border-bottom py-3 d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold small">Insurance</span>
                                        <span class="fw-semibold text-primary">@fmoney($insurance_y2d)</span>
                                    </div>
                                    <div class="border-bottom py-3 d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold small">Maintenance</span>
                                        <span class="fw-semibold text-primary">@fmoney($maintenance_y2d)</span>
                                    </div>
                                    <div class="border-bottom py-3 d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold small">Road Worthy</span>
                                        <span class="fw-semibold text-primary">@fmoney($road_worthy_y2d)</span>
                                    </div>
                                    <div class="border-bottom py-3 d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold small">Fuel</span>
                                        <span class="fw-semibold text-primary">@fmoney($fuel_y2d)</span>
                                    </div>
                                    <div class="border-bottom py-3 d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold small">Others</span>
                                        <span class="fw-semibold text-primary">@fmoney($other_y2d)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--  --}}

                {{-- Expense By Department --}}
                <div class="col-12 col-lg-6 mb-4">
                    <div class="card shadow-sm h-100 bg-white">
                        <div class="card-body p-4">
                            <div class="pb-3">
                                <h5 class="card-title text-sm" style="color:gray;">Expense Type Distibution</h5>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <canvas id="expenseDistributionDepartment"></canvas>
                                </div>
                                <div class="col-md-4">
                                    @foreach ($department_cost as $item)
                                        <div class="border-bottom py-3 d-flex justify-content-between align-items-center">
                                            <span class="fw-semibold text-sm">
                                                {{ ucwords($item->department_name) }}</span>
                                            <span class="fw-semibold text-primary text-sm">@fmoney($item->total_amount_paid)</span>
                                        </div>
                                    @endforeach


                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-lg-6 mb-4">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <h5 class="card-title text-sm" style="color:gray;">Car Maintenance Cost</h5>
                            <canvas id="lineChartMaintenance"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6 mb-4">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <h5 class="card-title text-sm" style="color:gray;">Expense Distrubutions</h5>
                            <canvas id="LineChart_AllExpenses_Month_On_Month"></canvas>
                        </div>
                    </div>
                </div>

            </div>


            <div class="row">
                {{-- {{ $mechanic_cost }} --}}
                 {{-- Expense By Department --}}
                 <div class="col-12 col-lg-6 mb-4">
                    <div class="card shadow-sm h-100 bg-white">
                        <div class="card-body p-4">
                            <div class="pb-3">
                                <h5 class="card-title text-sm" style="color:gray;">Expense Type Distibution</h5>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <canvas id="canvasVendor"></canvas>
                                </div>
                                <div class="col-md-4">
                                    @foreach ($mechanic_cost as $item)
                                        <div class="border-bottom py-3 d-flex justify-content-between align-items-center">
                                            <span class="fw-semibold text-sm">
                                                {{ ucwords($item->vendor_name) }}</span>
                                            <span class="fw-semibold text-primary text-sm">@fmoney($item->total_amount_paid)</span>
                                        </div>
                                    @endforeach


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- 
                <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <h5 class="card-title">Department Cost</h5>
                            <canvas id="barChart" style="height: 100px;"></canvas>
                        </div>
                    </div>
                </div> --}}



            </div>

        </div>
        <script>
            //cost distribution
            const insurance = @json($insurance_y2d);
            const maintenance = @json($maintenance_y2d);
            const road_worthy = @json($road_worthy_y2d);
            const fuels = @json($fuel_y2d);
            const others = @json($other_y2d);



            const departCost = @json($department_cost);
            const lineMaintenance = @json($maintenance_line);
            // console.log(lineMaintenance);


            let PieChart_Finance;
            let PieChart_FinanceDepartment;
            let line_ChartMaintenance;

            const values = lineMaintenance.map(item => parseFloat(item.amount));
            const lables = lineMaintenance.map(item => item.month);


            async function renderChartFinance(chartData = null) {
                const finance_data = document.getElementById("expenseDistribution");

                // Destroy previous chart if exists
                if (PieChart_Finance) {
                    PieChart_Finance.destroy();
                    PieChart_Finance = null;
                }


                if (finance_data != null) {

                    const currentLabels = chartData ? chartData.map(item => item.currency) : ['Insurance', 'Maintenance',
                        'Road Worthy', 'Fuel', 'Other'
                    ];
                    const currentValues = chartData ? chartData.map(item => parseFloat(item.total_income)) : [insurance,
                        maintenance, road_worthy, fuels, others
                    ];

                    const ctp = finance_data.getContext('2d');
                    PieChart_Finance = new Chart(ctp, {
                        type: 'pie',
                        data: {
                            labels: currentLabels,
                            datasets: [{
                                label: 'cost',
                                data: currentValues,
                                backgroundColor: [
                                    'lightgreen',
                                    'gold',
                                    'lightblue',
                                    'pink',
                                    'aqua'
                                ],
                                hoverOffset: 4,
                                tooltip: {
                                    callbacks: {
                                        label: (Item) => (Item.label) + ': ' + 'GH₵' + (Item
                                            .formattedValue)
                                    }
                                }
                            }]
                        },
                        options: {
                            animation: {
                                delay: 500
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: "bottom",
                                }
                            }
                        }
                    });
                }
            }
            async function renderChartFinanceDepartment(chartData = null) {
                const finance_data = document.getElementById("expenseDistributionDepartment");

                // Destroy previous chart if exists
                if (PieChart_FinanceDepartment) {
                    PieChart_FinanceDepartment.destroy();
                    PieChart_FinanceDepartment = null;
                }


                if (finance_data != null) {
                    const amountNumbers = departCost.map(item => parseFloat(item.total_amount_paid));
                    const departments = departCost.map(item => item.department_name);

                    const currentLabels = chartData ? chartData.map(item => item.currency) : departments;
                    const currentValues = chartData ? chartData.map(item => parseFloat(item.total_income)) : amountNumbers;

                    const ctp = finance_data.getContext('2d');
                    PieChart_FinanceDepartment = new Chart(ctp, {
                        type: 'pie',
                        data: {
                            labels: currentLabels,
                            datasets: [{
                                label: 'cost',
                                data: currentValues,
                                backgroundColor: [
                                    'lightgreen',
                                    'gold',
                                    'lightblue',
                                    'pink',
                                    'indigo'
                                ],
                                hoverOffset: 4,
                                tooltip: {
                                    callbacks: {
                                        label: (Item) => (Item.label) + ': ' + 'GH₵' + (Item
                                            .formattedValue)
                                    }
                                }
                            }]
                        },
                        options: {
                            animation: {
                                delay: 500
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: "bottom",
                                }
                            }
                        }
                    });
                }
            }
            async function renderChartVendor(chartData = null) {
                const dataVendor = @json($mechanic_cost);
                let pieVendor;
                const finance_data = document.getElementById("canvasVendor");

                // Destroy previous chart if exists
                if (pieVendor) {
                    pieVendor.destroy();
                    pieVendor = null;
                }


                if (finance_data != null) {
                    const amountNumbers = dataVendor.map(item => parseFloat(item.total_amount_paid));
                    const vendor = dataVendor.map(item => item.vendor_name);

                    const currentLabels = vendor;
                    const currentValues = amountNumbers;

                    const ctp = finance_data.getContext('2d');
                    PieChart_FinanceDepartment = new Chart(ctp, {
                        type: 'pie',
                        data: {
                            labels: currentLabels,
                            datasets: [{
                                label: 'cost',
                                data: currentValues,
                                backgroundColor: [                                  
                                    'lightblue',
                                    'pink',
                                    'indigo'
                                ],
                                hoverOffset: 4,
                                tooltip: {
                                    callbacks: {
                                        label: (Item) => (Item.label) + ': ' + 'GH₵' + (Item
                                            .formattedValue)
                                    }
                                }
                            }]
                        },
                        options: {
                            animation: {
                                delay: 500
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: "bottom",
                                }
                            }
                        }
                    });
                }
            }

            async function renderChatMaintenaceLine(chartData = null) {
                const finance_data = document.getElementById("lineChartMaintenance");

                // Destroy previous chart if exists
                if (line_ChartMaintenance) {
                    line_ChartMaintenance.destroy();
                    line_ChartMaintenance = null;
                }


                if (finance_data != null) {
                    const values = lineMaintenance.map(item => parseFloat(item.amount));
                    const lables = lineMaintenance.map(item => item.month);

                    const currentLabels = chartData ? chartData.map(item => item.currency) : lables;
                    const currentValues = chartData ? chartData.map(item => parseFloat(item.total_income)) : values;

                    // console.log('current lables', currentLabels);
                    // console.log('current currentValues', currentValues);

                    const ctp = finance_data.getContext('2d');
                    line_ChartMaintenance = new Chart(ctp, {
                        type: 'line',
                        data: {
                            labels: currentLabels,
                            datasets: [{
                                label: 'cost',
                                data: currentValues,
                                fill: true,
                                tension: 0.4,
                                borderWidth: 2,
                                hoverOffset: 4,
                                tooltip: {
                                    callbacks: {
                                        label: (Item) => (Item.label) + ': ' + 'GH₵' + (Item
                                            .formattedValue)
                                    }
                                }
                            }]
                        },
                        options: {
                            animation: {
                                delay: 500
                            },
                            plugins: {
                                legend: {
                                    display: false,
                                    position: "bottom",
                                }
                            }
                        }
                    });

                }
            }


            let LineChart_AllExpenses_Month_On_Month;
            async function renderLineChartAmalgamationMonthOnMonth() {
                const transactions = @json($all_expenses_month_on_month);
                const finance_data = document.getElementById("LineChart_AllExpenses_Month_On_Month");

                const colorScheme = {
                    'fuel': {
                        border: 'rgba(255, 99, 132, 1)',
                        background: 'rgba(255, 99, 132, 0.2)'
                    },
                    'insurance': {
                        border: 'rgba(54, 162, 235, 1)',
                        background: 'rgba(54, 162, 235, 0.2)'
                    },
                    'road worthy': {
                        border: 'rgba(255, 206, 86, 1)',
                        background: 'rgba(255, 206, 86, 0.2)'
                    },
                    'other': {
                        border: 'rgba(75, 192, 192, 1)',
                        background: 'rgba(75, 192, 192, 0.2)'
                    },
                    // Add more payment types with colors as needed
                    'default': {
                        border: 'rgba(153, 102, 255, 1)',
                        background: 'rgba(153, 102, 255, 0.2)'
                    }
                };

                // Get all unique payment types
                const paymentTypes = [...new Set(transactions.map(t => t.payment_type))];

                // Get all unique month-year combinations and sort them
                const monthYearCombos = [...new Set(transactions.map(t =>
                    `${t.year}-${t.month.toString().padStart(2, '0')}`))];
                monthYearCombos.sort();

                // Create month names for labels
                const monthNames = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];

                // Prepare labels (month names + year if needed)
                const labelsNew = monthYearCombos.map(combo => {
                    const [year, month] = combo.split('-');
                    return `${monthNames[parseInt(month) - 1]} ${year}`;
                });

                // Create dataset for each payment type
                const datasetsNew = paymentTypes.map(type => {
                    // Initialize all months with 0
                    const data = monthYearCombos.map(combo => {
                        const [year, month] = combo.split('-');
                        const transaction = transactions.find(t =>
                            t.year === parseInt(year) &&
                            t.month === parseInt(month) &&
                            t.payment_type === type
                        );
                        return transaction ? parseFloat(transaction.total) : 0;
                    });

                    // Generate random color for each payment type
                    const colors = colorScheme[type.toLowerCase()] || colorScheme['default'];

                    return {
                        label: type.charAt(0).toUpperCase() + type.slice(1), // Capitalize
                        data,
                        borderColor: colors.border,
                        backgroundColor: colors.background,
                        tension: 0.4,
                        borderWidth: 2,
                        fill: true,
                    };
                });

                console.log('datasets -', datasetsNew);

                // Destroy previous chart if exists
                if (LineChart_AllExpenses_Month_On_Month) {
                    LineChart_AllExpenses_Month_On_Month.destroy();
                    LineChart_AllExpenses_Month_On_Month = null;
                }

                // Final chart data structure
                const chartData = {
                    labels: labelsNew,
                    datasets: datasetsNew
                };



                const lineChartConfig = {
                    type: 'line',
                    data: chartData,
                    options: {
                        responsive: true,
                        animation: {
                            delay: 600,
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            tooltip: {
                                // callbacks: {
                                //     label: function(context) {
                                //         return `${context.dataset.label}: ${context.parsed.y.toFixed(2)}`;
                                //     }
                                // }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                };



                if (finance_data != null) {
                    const ctp = finance_data.getContext('2d');
                    LineChart_AllExpenses_Month_On_Month = new Chart(ctp, lineChartConfig);
                }
            }


            document.addEventListener('DOMContentLoaded', function() {
                renderChartFinance();
                renderChartFinanceDepartment();
                renderChatMaintenaceLine();
                renderLineChartAmalgamationMonthOnMonth();
                renderChartVendor();

            });
        </script>
    @endsection
