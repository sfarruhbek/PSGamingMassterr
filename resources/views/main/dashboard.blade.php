@extends('layouts.main')
@section('content')

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">

                <!-- Общая прибыль -->
                <div class="col-lg-4 col-md-4 order-1">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title d-flex align-items-start justify-content-between">
                                        <div class="avatar flex-shrink-0">
                                            <img src="../assets/img/icons/unicons/chart-success.png" alt="chart success" class="rounded" />
                                        </div>
                                    </div>
                                    <span class="fw-semibold d-block mb-1">Общая прибыль</span>
                                    <h3 class="card-title mb-2">{{ $totalProfit ?? 0 }} сум</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title d-flex align-items-start justify-content-between">
                                        <div class="avatar flex-shrink-0">
                                            <img src="../assets/img/icons/unicons/chart-success.png" alt="chart success" class="rounded" />
                                        </div>
                                    </div>
                                    <span class="fw-semibold d-block mb-1">Ежемесячная прибыль</span>
                                    <h3 class="card-title mb-2">{{ $monthlyProfit ?? 0 }} сум</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title d-flex align-items-start justify-content-between">
                                        <div class="avatar flex-shrink-0">
                                            <img src="../assets/img/icons/unicons/chart-success.png" alt="chart success" class="rounded" />
                                        </div>
                                    </div>
                                    <span class="fw-semibold d-block mb-1">Дневная прибыль</span>
                                    <h3 class="card-title mb-2">{{ $todayProfit ?? 0 }} сум</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Расходы -->
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
                                    <span class="d-block mb-1">Общие расходы</span>
                                    <h3 class="card-title text-nowrap mb-2">{{ $totalExpense ?? 0 }} сум</h3>
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
                                    <span class="d-block mb-1">Ежемесячные расходы</span>
                                    <h3 class="card-title text-nowrap mb-2">{{ $monthlyExpense ?? 0 }} сум</h3>
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
                                    <span class="d-block mb-1">Дневные расходы</span>
                                    <h3 class="card-title text-nowrap mb-2">{{ $todayIncome ?? 0 }} сум</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Доходы -->
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
                                    <span class="d-block mb-1">Общий доход</span>
                                    <h3 class="card-title text-nowrap mb-2">{{ $totalExpense ?? 0 }} сум</h3>
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
                                    <span class="d-block mb-1">Ежемесячный доход</span>
                                    <h3 class="card-title text-nowrap mb-2">{{ $monthlyIncome ?? 0 }} сум</h3>
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
                                    <span class="d-block mb-1">Дневной доход</span>
                                    <h3 class="card-title text-nowrap mb-2">{{ $todayIncome ?? 0 }} сум</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <!-- Yillik hisobot -->
                <div class="col-12 col-lg-8 mb-4">
                    <div class="card">
                        <div class="row row-bordered g-0">
                            <div class="col-md-12">
                                <h5 class="card-header m-0 me-2 pb-3">Годовой отчет</h5>
                                <div id="incomeChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Yillik hisobot -->

                <!-- New Date Input Card -->
                <div class="col-12 col-lg-4 mb-4">
                    <div class="card mb-2">
                        <div class="card-body">
                            <form id="dateForm" action="{{ route('main.calculate-income-expense') }}" method="POST">
                                @csrf
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <h5 class="mb-0">Выбор дат</h5>
                                </div>
                                <div class="mb-3">
                                    <label for="startDate" class="form-label">Начальная дата</label>
                                    <input type="datetime-local" id="startDate" name="startDate" class="form-control" required />
                                </div>
                                <div class="mb-3">
                                    <label for="endDate" class="form-label">Конечная дата</label>
                                    <input type="datetime-local" id="endDate" name="endDate" class="form-control" required />
                                </div>
                                <button type="submit" class="btn btn-primary">OK</button>
                            </form>
                        </div>
                    </div>
                    <div class="card" id="financialOverview" style="display: none">
                        <div class="card-body">
                            <h5 class="card-title text-center mb-4">Финансовый обзор</h5>
                            <div class="row text-left">
                                <div class="col">
                                    <h3 class="text-success">Доход: <span id="totalIncome">0</span> сум</h3>
                                </div>
                            </div>
                            <div class="row text-left">
                                <div class="col">
                                    <h3 class="text-danger">Расходы: <span id="totalExpense">0</span> сум</h3>
                                </div>
                            </div>
                            <div class="row text-left">
                                <div class="col">
                                    <h3 class="text-primary">Чистая прибыль: <span id="totalProfit">0</span> сум</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /New Date Input Card -->
            </div>
        </div>
        <!-- / Content -->

        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->

    <!-- ApexCharts -->
    <script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>

    <script>
        document.getElementById('dateForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Standart shakl yuborilishini to'xtatadi

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // CSRF token ni qo'shadi
                },
            })
                .then(response => response.json())
                .then(data => {
                    // Javob ma'lumotlarini yangilash
                    const totalIncome = data.totalIncome;
                    const totalExpense = data.totalExpense;
                    const totalProfit = totalIncome - totalExpense; // Sof foyda hisoblash

                    document.getElementById('totalIncome').textContent = totalIncome;
                    document.getElementById('totalExpense').textContent = totalExpense;
                    document.getElementById('totalProfit').textContent = totalProfit;

                    // Kartani ko'rsatish
                    document.getElementById('financialOverview').style.display = 'block';
                })
                .catch(error => {
                    console.error('Xato:', error);
                });
        });
    </script>

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
                            name: 'Доход',
                            data: @json($monthlyIncomeData ?? [])
                        },
                        {
                            name: 'Расход',
                            data: @json($monthlyExpenseData ?? [])
                        },
                        {
                            name: 'Прибыль',
                            data: @json($monthlyProfitData ?? [])
                        }
                    ],
                    chart: {
                        height: 500,
                        type: 'area',
                        toolbar: { show: false }
                    },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2 },
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
