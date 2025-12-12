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
                        <h3 class="page-title">My Requests</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('finance.requests.home') }}">Finance Requests</a>
                            </li>
                            <li class="breadcrumb-item active">Distribution</li>
                        </ul>
                    </div>

                    <div class="col-auto float-end ms-auto ">
                        <a href="{{ route('finance.requests.home') }}" class="btn add-btn"><i class="fa fa-back"></i>Go
                            Back</a>

                    </div>
                </div>
            </div>



            {{-- values --}}
            <div class="row">

                <h4>Year To Date Expense Overview</h4>
                <!--insurance cost-->
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
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
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
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
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
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


                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget ">
                        <div class="card-body">
                            <span class="dash-widget-icon" style="background-color:  #e7fffd;color : black;"><i
                                    class="fa fa-credit-card"></i></span>
                            <div class="dash-widget-info">
                                <h3> @fmoney($fuel_y2d) </h3>
                                <span class="text-sm text-secondary">Total Fuel Cost</span>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ROW 2 --}}
                <!--road others cost-->
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget ">
                        <div class="card-body">
                            <span class="dash-widget-icon" style="background-color:  #e7fffd;color : rgb(0, 0, 0);"><i
                                    class="fa fa-credit-card"></i></span>
                            <div class="dash-widget-info">
                                <h3> @fmoney($other_y2d)</h3>
                                <span class="text-sm text-secondary">Others costs</span>
                            </div>
                            <div> <span class="text-sm" style="color:gray">General Expenses</span>
                            </div>

                        </div>
                    </div>
                </div>
                <!--//road others cost-->





            </div>


            <!--graphs cost-->
            <div class="row">
                {{-- Expense Type Distribution  --}}
                <div class="col-12 col-lg-6 mb-4">
                    <div class="card shadow-sm h-100 bg-white">
                        <div class="card-body p-4">
                            <div class="pb-3">
                                <h5 class="card-title text-sm" style="color:gray;">Expense Type
                                    Distibution</h5>
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
                                <h5 class="card-title text-sm" style="color:gray;">Expense Type
                                    Distibution</h5>
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
                            <h5 class="card-title text-sm" style="color:gray;">Car Maintenance Cost
                            </h5>
                            <canvas id="lineChartMaintenance"></canvas>
                        </div>
                    </div>
                </div>



            </div>


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



        let PieChart_Finance;
        let PieChart_FinanceDepartment;
        let line_ChartMaintenance;

        const values = lineMaintenance.map(item => parseFloat(item.amount));
        const lables = lineMaintenance.map(item => item.month);
        console.log('value n labe', values);
        console.log('value n labe', lables);

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
                                'gold',
                                'lightgreen',
                                'lightblue',
                                'pink',
                                'indigo',

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

                console.log('current lables', currentLabels);
                console.log('current currentValues', currentValues);

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



        document.addEventListener('DOMContentLoaded', function() {
            renderChartFinance();
            renderChartFinanceDepartment();
            renderChatMaintenaceLine();


        });
    </script>
@endsection

@section('js')



@endsection
