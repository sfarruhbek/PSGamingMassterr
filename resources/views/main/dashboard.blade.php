@extends('layouts.main')
@section('content')

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->

                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 order-1">
                            <div class="row">


                                <div class="col-lg-12 col-md-12 col-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <img
                                                        src="../assets/img/icons/unicons/chart-success.png"
                                                        alt="chart success"
                                                        class="rounded"
                                                    />
                                                </div>
                                            </div>
                                            <span class="fw-semibold d-block mb-1">Umumiy foyda</span>
                                            <h3 class="card-title mb-2">{{ $totalProfit ?? 0 }} so'm</h3>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-12 col-md-12 col-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <img
                                                        src="../assets/img/icons/unicons/chart-success.png"
                                                        alt="chart success"
                                                        class="rounded"
                                                    />
                                                </div>
                                            </div>
                                            <span class="fw-semibold d-block mb-1">Oylik foyda</span>
                                            <h3 class="card-title mb-2">{{ $monthlyProfit ?? 0 }} so'm</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <img
                                                        src="../assets/img/icons/unicons/chart-success.png"
                                                        alt="chart success"
                                                        class="rounded"
                                                    />
                                                </div>
                                            </div>
                                            <span class="fw-semibold d-block mb-1">Kunlik foyda</span>
                                            <h3 class="card-title mb-2">{{ $todayProfit ?? 0 }} so'm</h3>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <button class="btn btn-danger"><i class="bx bx-arrow-to-top"></i></button>
                                                </div>

                                            </div>
                                            <span class="d-block mb-1">Umumiy chiqim</span>
                                            <h3 class="card-title text-nowrap mb-2">{{ $totalExpense ?? 0 }} so'm</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <button class="btn btn-danger"><i class="bx bx-arrow-to-top"></i></button>
                                                </div>

                                            </div>
                                            <span class="d-block mb-1">Oylik chiqim</span>
                                            <h3 class="card-title text-nowrap mb-2">{{ $monthlyExpense ?? 0 }} so'm</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <button class="btn btn-danger"><i class="bx bx-arrow-to-top"></i></button>
                                                </div>

                                            </div>
                                            <span class="d-block mb-1">Kunlik chiqim</span>
                                            <h3 class="card-title text-nowrap mb-2">{{ $todayIncome ?? 0 }} so'm</h3>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <button class="btn btn-primary"><i class="bx bx-arrow-to-bottom"></i></button>
                                                </div>

                                            </div>
                                            <span class="d-block mb-1">Umumiy kirim</span>
                                            <h3 class="card-title text-nowrap mb-2">{{ $todayExpense ?? 0 }} so'm</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <button class="btn btn-primary"><i class="bx bx-arrow-to-bottom"></i></button>
                                                </div>

                                            </div>
                                            <span class="d-block mb-1">Oyik kirim</span>
                                            <h3 class="card-title text-nowrap mb-2">{{ $monthlyIncome ?? 0 }} so'm</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <button class="btn btn-primary"><i class="bx bx-arrow-to-bottom"></i></button>
                                                </div>

                                            </div>
                                            <span class="d-block mb-1">Kunlik kirim</span>
                                            <h3 class="card-title text-nowrap mb-2">{{ $todayIncome ?? 0 }} so'm</h3>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <!-- Total Revenue -->
                        <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
                            <div class="card">
                                <div class="row row-bordered g-0">
                                    <div class="col-md-12">
                                        <h5 class="card-header m-0 me-2 pb-3">Yillik hisobot</h5>
                                        <div id="incomeChart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Total Revenue -->
                    </div>
                </div>
                <!-- / Content -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->


            <!-- Vendors JS -->
            <script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
            <!-- Page JS -->

            <script>
                'use strict';

                (function () {
                    let cardColor = config.colors.white;
                    let headingColor = config.colors.headingColor;
                    let axisColor = config.colors.axisColor;
                    let shadeColor = config.colors.shadeColor;
                    let borderColor = config.colors.borderColor;

                    const incomeChartEl = document.querySelector('#incomeChart'),
                        incomeChartConfig = {
                            series: [
                                {
                                    name: 'Kirim',
                                    data: @json($monthlyIncomeData ?? [])
                                },
                                {
                                    name: 'Chiqim',
                                    data: @json($monthlyExpenseData ?? [])
                                },
                                {
                                    name: 'Foyda',
                                    data: @json($monthlyProfitData ?? [])
                                }
                            ],
                            chart: {
                                height: 500,
                                type: 'area',
                                toolbar: {
                                    show: false
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                curve: 'smooth',
                                width: 2
                            },
                            colors: [config.colors.success, config.colors.danger, config.colors.primary],
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shade: shadeColor,
                                    shadeIntensity: 0.6,
                                    opacityFrom: 0.5,
                                    opacityTo: 0.25,
                                    stops: [0, 95, 100]
                                }
                            },
                            xaxis: {
                                categories: @json($months ?? []),
                                labels: {
                                    style: {
                                        fontSize: '13px',
                                        colors: axisColor
                                    }
                                },
                                axisBorder: { show: false },
                                axisTicks: { show: false }
                            },
                            yaxis: {
                                labels: {
                                    style: {
                                        colors: axisColor
                                    }
                                },
                                tickAmount: 4
                            },
                            grid: {
                                borderColor: borderColor,
                                strokeDashArray: 3,
                                padding: {
                                    top: -20,
                                    bottom: -8,
                                    left: -10,
                                    right: 8
                                }
                            },
                            legend: {
                                position: 'top',
                                horizontalAlign: 'right',
                                labels: {
                                    colors: headingColor
                                }
                            }
                        };

                    if (incomeChartEl !== null) {
                        const incomeChart = new ApexCharts(incomeChartEl, incomeChartConfig);
                        incomeChart.render();
                    }

                })();
            </script>

@endsection
