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
                                                        src="../assets/img/icons/unicons/wallet-info.png"
                                                        alt="Credit Card"
                                                        class="rounded"
                                                    />
                                                </div>
                                            </div>
                                            <span>Oylik foyda</span>
                                            <h3 class="card-title text-nowrap mb-1">{{ $monthlyProfit ?? 0 }} so'm</h3>
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
                                                    <img src="../assets/img/icons/unicons/paypal.png" alt="Credit Card" class="rounded" />
                                                </div>

                                            </div>
                                            <span class="d-block mb-1">Kunlik foyda</span>
                                            <h3 class="card-title text-nowrap mb-2">{{ $todayProfit ?? 0 }} so'm</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title d-flex align-items-start justify-content-between">
                                                <div class="avatar flex-shrink-0">
                                                    <img src="../assets/img/icons/unicons/cc-primary.png" alt="Credit Card" class="rounded" />
                                                </div>
                                            </div>
                                            <span class="fw-semibold d-block mb-1">Kompyuterlar soni</span>
                                            <h3 class="card-title mb-2">{{ $computerCount ?? 0 }} ta</h3>
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
        /**
         * Dashboard Analytics
         */

        'use strict';

        (function () {
            let cardColor, headingColor, axisColor, shadeColor, borderColor;

            cardColor = config.colors.white;
            headingColor = config.colors.headingColor;
            axisColor = config.colors.axisColor;
            borderColor = config.colors.borderColor;


            // Income Chart - Area chart
            // --------------------------------------------------------------------
            const incomeChartEl = document.querySelector('#incomeChart'),
                incomeChartConfig = {
                    series: [
                        {
                            name: "Foyda",
                            data: @json($monthlyProfitData ?? [])
                        }
                    ],
                    chart: {
                        height: 500,
                        parentHeightOffset: 0,
                        parentWidthOffset: 0,
                        toolbar: {
                            show: false
                        },
                        type: 'area'
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 2,
                        curve: 'smooth'
                    },
                    legend: {
                        show: false
                    },
                    markers: {
                        size: 6,
                        colors: 'transparent',
                        strokeColors: 'transparent',
                        strokeWidth: 4,
                        discrete: [
                            {
                                fillColor: config.colors.white,
                                seriesIndex: 0,
                                dataPointIndex: 11,
                                strokeColor: config.colors.primary,
                                strokeWidth: 2,
                                size: 6,
                                radius: 8
                            }
                        ],
                        hover: {
                            size: 7
                        }
                    },
                    colors: [config.colors.primary],
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
                    xaxis: {
                        categories: @json($months ?? []),
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            show: true,
                            style: {
                                fontSize: '13px',
                                colors: axisColor
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            show: false
                        },
                        tickAmount: 4
                    }
                };
            if (typeof incomeChartEl !== undefined && incomeChartEl !== null) {
                const incomeChart = new ApexCharts(incomeChartEl, incomeChartConfig);
                incomeChart.render();
            }

        })();

    </script>
@endsection
