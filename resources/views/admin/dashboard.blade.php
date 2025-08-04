@extends('admin.contentNavLayout')

@section('title', 'Dashboard')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/apex-charts/apex-charts.css') }}">
    <style>
        .stats-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
        }
        .stats-icon {
            font-size: 1.5rem;
        }
        .stats-number {
            font-weight: 700;
            font-size: 2rem;
            line-height: 1.2;
        }
        .stats-label {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        .stats-subtext {
            font-size: 0.875rem;
            opacity: 0.8;
        }
        .dashboard-container {
            padding: 1.5rem 0;
        }
        .chart-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        .chart-container:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }
        .card-title {
            color: #5d596c;
            font-weight: 600;
        }
        .apexcharts-tooltip {
            border-radius: 8px !important;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15) !important;
        }
        .chart-legend {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }
    </style>
@endsection

@section('page-script')
    <script src="{{ asset('assets/admin/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/admin/js/dashboards-analytics.js') }}"></script>
    <script>
        'use strict';

        // Revenue Chart
        const revenueChartEl = document.querySelector('#revenueChart');
        const revenueChartConfig = {
            chart: {
                height: 300,
                type: 'area',
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 3,
                curve: 'smooth'
            },
            legend: {
                show: true
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'vertical',
                    shadeIntensity: 0.5,
                    gradientToColors: undefined,
                    inverseColors: true,
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                    stops: [0, 50, 100]
                }
            },
            series: [
                {
                    name: 'Doanh thu (VNĐ)',
                    data: {!! json_encode($revenueData ?? []) !!}
                }
            ],
            xaxis: {
                categories: {!! json_encode($monthLabels ?? []) !!}
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return (val / 1000000).toFixed(0) + 'M';
                    }
                }
            },
            colors: ['#696cff']
        };
        if (typeof revenueChartEl !== undefined && revenueChartEl !== null) {
            const revenueChart = new ApexCharts(revenueChartEl, revenueChartConfig);
            revenueChart.render();
        }

        // Orders Status Chart
        const orderStatusChartEl = document.querySelector('#orderStatusChart');
        const orderStatusChartConfig = {
            chart: {
                height: 240,
                type: 'donut'
            },
            labels: ['Hoàn thành', 'Đang xử lý', 'Đã hủy'],
            series: [{{ $completedOrders ?? 65 }}, {{ $processingOrders ?? 25 }}, {{ $cancelledOrders ?? 10 }}],
            colors: ['#28c76f', '#ff9f43', '#ea5455'],
            stroke: {
                width: 5,
                colors: ['#fff']
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return parseInt(val) + '%';
                }
            },
            legend: {
                show: false
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            value: {
                                fontSize: '1.5rem',
                                fontFamily: 'Public Sans',
                                color: '#646E73',
                                fontWeight: 500,
                                offsetY: 16,
                                formatter: function (val) {
                                    return parseInt(val) + '%';
                                }
                            },
                            name: {
                                offsetY: -10,
                                fontFamily: 'Public Sans'
                            },
                            total: {
                                show: true,
                                fontSize: '0.875rem',
                                color: '#9aa0ac',
                                label: 'Tổng đơn hàng',
                                formatter: function (w) {
                                    return '{{ $totalOrders }}';
                                }
                            }
                        }
                    }
                }
            }
        };
        if (typeof orderStatusChartEl !== undefined && orderStatusChartEl !== null) {
            const orderStatusChart = new ApexCharts(orderStatusChartEl, orderStatusChartConfig);
            orderStatusChart.render();
        }

        // Product Categories Chart
        const productCategoriesChartEl = document.querySelector('#productCategoriesChart');
        const productCategoriesChartConfig = {
            chart: {
                height: 300,
                type: 'bar',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    borderRadius: 4
                }
            },
            dataLabels: {
                enabled: false
            },
            series: [
                {
                    name: 'Số lượng sản phẩm',
                    data: {!! json_encode($categoryCounts ?? []) !!}
                }
            ],
            xaxis: {
                categories: {!! json_encode($categoryNames ?? []) !!}
            },
            colors: ['#ff9f43'],
            fill: {
                opacity: 1
            }
        };
        if (typeof productCategoriesChartEl !== undefined && productCategoriesChartEl !== null) {
            const productCategoriesChart = new ApexCharts(productCategoriesChartEl, productCategoriesChartConfig);
            productCategoriesChart.render();
        }

        // User Growth Chart
        const userGrowthChartEl = document.querySelector('#userGrowthChart');
        const userGrowthChartConfig = {
            chart: {
                height: 300,
                type: 'line',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight',
                width: 3
            },
            series: [
                {
                    name: 'Thành viên mới',
                    data: {!! json_encode($memberGrowthData ?? []) !!}
                }
            ],
            xaxis: {
                categories: {!! json_encode($monthLabels ?? []) !!}
            },
            colors: ['#28c76f'],
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'],
                    opacity: 0.5
                }
            }
        };
        if (typeof userGrowthChartEl !== undefined && userGrowthChartEl !== null) {
            const userGrowthChart = new ApexCharts(userGrowthChartEl, userGrowthChartConfig);
            userGrowthChart.render();
        }
    </script>
@endsection

@section('content')
    <div class="dashboard-container">
        <div class="row g-4">
            <!-- Orders Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="bg-label-primary card card-border-shadow-primary h-100 stats-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="avatar avatar-lg me-3">
                                <span class="avatar-initial rounded-3 bg-label-primary d-flex align-items-center justify-content-center">
                                    <i class="mdi mdi-cart stats-icon"></i>
                                </span>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-0 stats-number text-primary">{{ $totalOrders }}</h3>
                            </div>
                        </div>
                        <h6 class="mb-2 stats-label">Đơn hàng</h6>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-label-primary me-2">+{{ $month_Orders }}</span>
                            <small class="text-muted stats-subtext">Đơn hàng mới tháng này</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="bg-label-warning card card-border-shadow-warning h-100 stats-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="avatar avatar-lg me-3">
                                <span class="avatar-initial rounded-3 bg-label-warning d-flex align-items-center justify-content-center">
                                    <i class="mdi mdi-hanger stats-icon"></i>
                                </span>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-0 stats-number text-warning">{{ $products }}</h3>
                            </div>
                        </div>
                        <h6 class="mb-2 stats-label">Sản phẩm</h6>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-label-warning me-2">+{{ $month_Products }}</span>
                            <small class="text-muted stats-subtext">Sản phẩm mới trong tháng</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- News Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="bg-label-danger card card-border-shadow-danger h-100 stats-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="avatar avatar-lg me-3">
                                <span class="avatar-initial rounded-3 bg-label-danger d-flex align-items-center justify-content-center">
                                    <i class="mdi mdi-newspaper stats-icon"></i>
                                </span>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-0 stats-number text-danger">{{ $totalPosts }}</h3>
                            </div>
                        </div>
                        <h6 class="mb-2 stats-label">Tin tức</h6>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-label-danger me-2">+{{ $month_Posts }}</span>
                            <small class="text-muted stats-subtext">Tin tức mới trong tháng</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Members Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                <div class="bg-label-success card card-border-shadow-success h-100 stats-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="avatar avatar-lg me-3">
                                <span class="avatar-initial rounded-3 bg-label-success d-flex align-items-center justify-content-center">
                                    <i class="mdi mdi-account-box-outline stats-icon"></i>
                                </span>
                            </div>
                            <div class="text-end">
                                <h3 class="mb-0 stats-number text-success">{{ $total_members }}</h3>
                            </div>
                        </div>
                        <h6 class="mb-2 stats-label">Thành viên</h6>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-label-success me-2">+{{ $month_Member }}</span>
                            <small class="text-muted stats-subtext">Thành viên mới trong tháng</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row g-4 mt-4">
            <!-- Revenue Chart -->
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Doanh thu theo tháng</h5>
                        <div class="dropdown">
                            <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button" id="revenueChartDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical mdi-20px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="revenueChartDropdown">
                                <a class="dropdown-item" href="javascript:void(0);">6 tháng gần đây</a>
                                <a class="dropdown-item" href="javascript:void(0);">12 tháng gần đây</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="revenueChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Charts Row -->
        <div class="row g-4 mt-4">
            <!-- Product Categories Chart -->
            <div class="col-xl-6 col-lg-6 col-md-12">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Sản phẩm theo danh mục</h5>
                    </div>
                    <div class="card-body">
                        <div id="productCategoriesChart"></div>
                    </div>
                </div>
            </div>

            <!-- User Growth Chart -->
            <div class="col-xl-6 col-lg-6 col-md-12">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tăng trưởng thành viên</h5>
                    </div>
                    <div class="card-body">
                        <div id="userGrowthChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
