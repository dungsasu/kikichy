@extends('admin.contentNavLayout')

@section('title', 'Dashboard')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/apex-charts/apex-charts.css') }}">
@endsection

@section('page-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableContainer = document.querySelector('.table-responsive');
            let isDown = false;
            let startX;
            let scrollLeft;
            let dragTimer;
            let hasMoved = false;

            // Khi bắt đầu kéo chuột
            tableContainer.addEventListener('mousedown', (e) => {
                // Chỉ áp dụng khi click trái chuột và không phải trên các element tương tác
                if (e.button !== 0 || 
                    e.target.matches('input, button, a, .form-check-input, .dropdown-item, i, img') ||
                    e.target.closest('input, button, a, .form-check-input, .dropdown-item')) {
                    return;
                }

                // Nếu click vào text content, cho phép selection
                if (e.target.matches('td, th') && e.target.textContent.trim()) {
                    // Delay để phân biệt giữa click để select text và drag
                    dragTimer = setTimeout(() => {
                        if (!hasMoved) {
                            isDown = true;
                            tableContainer.classList.add('active');
                            startX = e.pageX - tableContainer.offsetLeft;
                            scrollLeft = tableContainer.scrollLeft;
                            tableContainer.style.cursor = 'grabbing';
                        }
                    }, 150);
                    return;
                }
                
                isDown = true;
                tableContainer.classList.add('active');
                startX = e.pageX - tableContainer.offsetLeft;
                scrollLeft = tableContainer.scrollLeft;
                tableContainer.style.cursor = 'grabbing';
                hasMoved = false;
            });

            // Khi thả chuột
            tableContainer.addEventListener('mouseup', () => {
                if (dragTimer) {
                    clearTimeout(dragTimer);
                    dragTimer = null;
                }
                isDown = false;
                hasMoved = false;
                tableContainer.classList.remove('active');
                tableContainer.style.cursor = 'grab';
            });

            // Khi chuột rời khỏi vùng table
            tableContainer.addEventListener('mouseleave', () => {
                if (dragTimer) {
                    clearTimeout(dragTimer);
                    dragTimer = null;
                }
                isDown = false;
                hasMoved = false;
                tableContainer.classList.remove('active');
                tableContainer.style.cursor = 'grab';
            });

            // Khi di chuyển chuột
            tableContainer.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                
                hasMoved = true;
                e.preventDefault();
                const x = e.pageX - tableContainer.offsetLeft;
                const walk = (x - startX) * 2; // Tốc độ cuộn
                tableContainer.scrollLeft = scrollLeft - walk;
            });

            // Thêm style cursor khi hover vào vùng trống
            tableContainer.addEventListener('mouseover', (e) => {
                if (!e.target.matches('td, th') || !e.target.textContent.trim()) {
                    tableContainer.style.cursor = 'grab';
                } else {
                    tableContainer.style.cursor = 'text';
                }
            });
            
            // CSS cho smooth scrolling
            tableContainer.style.scrollBehavior = 'smooth';
        });
    </script>
@endsection

@section('content')
    @php

    @endphp

    <div class="row gy-4">
        @if (@$show_button_action)
            <x-button-action show="create,delete" create="{{ route('admin.vouchers.create') }}" :model="$model" />
        @endif
        <x-filter prefix="{{ $prefix }}" :filter-value="$filter" filterDate="1" />
        <div class="col-12">
            <div class="card">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table" style="min-width: 1320px; white-space: nowrap;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px; min-width: 60px;">
                                    <span class="form-check mb-0">
                                        <input id="selectAllRecord" class="form-record form-check-input me-1"
                                            type="checkbox" value="">
                                    </span>
                                </th>
                                <th style="width: 180px; min-width: 180px;">Tên</th>
                                <th style="width: 120px; min-width: 120px;">Mã voucher</th>
                                <th style="width: 120px; min-width: 120px;">Mã khách hàng</th>
                                <th style="width: 100px; min-width: 100px;">Giá trị</th>
                                <th style="width: 100px; min-width: 100px;">Bill from</th>
                                <th style="width: 100px; min-width: 100px;">Bill to</th>
                                <th style="width: 180px; min-width: 180px;">Loại voucher</th>
                                <th style="width: 100px; min-width: 100px;">Status</th>
                                <th style="width: 140px; min-width: 140px;">Ngày tạo</th>
                                <th style="width: 110px; min-width: 110px;">Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>
                                        <input data-id="{{ $item->id }}" class="form-record form-check-input me-1"
                                            type="checkbox" value="{{ $item->id }}" />
                                    </td>
                                    <td class="text-truncate">
                                        {{ @$item->name }}
                                    </td>
                                    <td>{{ @$item->code }}</td>
                                    <td>{{ @$item->customer }}</td>
                                    <td>{{ number_format(@$item->discount, 0, ',', '.') }}</td>
                                    <td>{{ number_format(@$item->bill_from, 0, ',', '.') }}</td>
                                    <td>{{ number_format(@$item->bill_to, 0, ',', '.') }}</td>
                                    @php
                                        $couponTypes = [
                                            '1' => 'Coupon tiền mặt (Số tiền)',
                                            '2' => 'Coupon giảm giá (Số tiền)',
                                            '3' => 'Coupon tỷ lệ (Tính trên giá trị còn lại)',
                                            '4' => 'Coupon tỷ lệ (Tính trên nguyên giá)',
                                            '5' => 'Coupon giảm giá (Không tính VAT)',
                                        ];
                                    @endphp

                                    <td>
                                        {{ $couponTypes[$item->type] ?? 'Loại coupon không xác định' }}
                                    </td>
                                    <td>{{ @$item->status }}</td>
                                    <td>{{ @$item->created_time }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a style="width: 30px" class="dropdown-item p-1"
                                                href="{{ route($view . '.edit', ['id' => $item->id]) }}">
                                                <i class="mdi mdi-pencil-outline me-1"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{ $list->links() }}

        <!--/ Data Tables -->
    </div>
@endsection
