@extends('admin.contentNavLayout')

@section('title', 'Danh sách đơn hàng')

@section('page-script')
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
    <div class="row gy-4">
        <x-filter prefix="{{ $prefix }}" filterDate :categories="$categories" :custom-filters="$customFilters ?? []" :filter-value="$filter" />
        <div class="col-12">
            <div class="card">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table" style="min-width: 1200px; white-space: nowrap;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px; min-width: 60px;">
                                    <span class="form-check mb-0">
                                        <input id="selectAllRecord" class="form-record form-check-input me-1" type="checkbox"
                                            value="">
                                    </span>
                                </th>
                                <th style="width: 120px; min-width: 120px;" class="text-truncate">Đơn hàng</th>
                                <th style="width: 150px; min-width: 150px;" class="text-truncate">Tên khách hàng</th>
                                <th style="width: 140px; min-width: 140px;" class="text-truncate">Trạng thái đơn hàng</th>
                                <th style="width: 100px; min-width: 100px;" class="text-truncate">Thanh toán</th>
                                <th style="width: 120px; min-width: 120px;" class="text-truncate">Phương thức thanh toán</th>
                                <th style="width: 80px; min-width: 80px;" class="text-truncate">Nguồn</th>
                                <th style="width: 140px; min-width: 140px;" class="text-truncate">Ngày đặt hàng</th>
                                <th style="width: 100px; min-width: 100px;" class="text-truncate">Tổng tiền</th>
                                <th style="width: 110px; min-width: 110px;" class="text-truncate">Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($list->isEmpty())
                                <tr>
                                    <td colspan="10" class="text-center">Không có dữ liệu</td>
                                </tr>
                            @endif
                            @foreach ($list as $item)
                                <tr>
                                    <td>
                                        <input data-id="{{ $item->id }}" class="form-record form-check-input me-1"
                                            type="checkbox" value="{{ $item->id }}" />
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.order.edit', ['id' => $item->id]) }}">
                                            {{ $item->type == 'fast' ? $item->fast_id : $item->order_code }}
                                        </a>
                                        @if ($item->new)
                                            <span class="badge rounded-pill bg-label-warning me-1">Mới</span>
                                        @endif
                                    </td>
                                    <td class="text-truncate">
                                        {{ @$item->name }}
                                    </td>
                                    @php
                                        switch ($item->order_status) {
                                            case 0:
                                                $order_status = 'Đặt hàng không thành công';
                                                break;
                                            case 1:
                                                $order_status = 'Đặt hàng';
                                                break;
                                            case 2:
                                                $order_status = 'Xác nhận đặt hàng thành công';
                                                break;
                                            case 3:
                                                $order_status = 'Hết hàng';
                                                break;
                                            case 4:
                                                $order_status = 'Đang vận chuyển';
                                                break;
                                            case 5:
                                                $order_status = 'Giao hàng và thanh toán thành công';
                                                break;
                                            case 6:
                                                $order_status = 'Huỷ đơn hàng';
                                                break;
                                            case 7:
                                                $order_status = 'Đã thanh toán online thành công';
                                                break;
                                        }
                                    @endphp
                                    <td>{{ $item->type !== 'fast' ? $order_status : '' }}</td>
                                    <td>
                                        @if ($item->type !== 'fast')
                                            @if ($item->payment_status == 1)
                                                <span class="badge bg-label-success me-2 ms-2 rounded-pill">Đã thanh
                                                    toán</span>
                                            @elseif($item->payment_status == 0)
                                                <span class="badge bg-label-danger me-2 ms-2 rounded-pill">Chưa thanh
                                                    toán</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-uppercase">{{ $item->type !== 'fast' ? $item->payment_method : '' }}</td>
                                    <td class="text-uppercase">{{ $item->type ? $item->type : 'web'  }}</td>
                                    <td>{{ date('H:i:s d-m-Y', strtotime($item->created_at)) }}</td>
                                    <td>{{ $item->total_price }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a style="width: 30px" class="dropdown-item p-1"
                                                href="{{ route('admin.order.edit', ['id' => $item->id]) }}">
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

    </div>

@endsection
