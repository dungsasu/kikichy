@extends('admin.contentNavLayout')

@section('title', 'Log SMS')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/apex-charts/apex-charts.css') }}">
    <style>
        .stats-card {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        .status-success {
            background-color: #d4edda;
            color: #155724;
        }
        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
        }
        .message-preview {
            max-width: 400px;
            white-space: wrap;
            /* overflow: hidden; */
            text-overflow: ellipsis;
        }
    </style>
@endsection

@section('page-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableContainer = document.querySelector('.table-responsive');
            let isDown = false;
            let startX;
            let scrollLeft;

            // Drag to scroll functionality
            tableContainer.addEventListener('mousedown', (e) => {
                if (e.button !== 0 || 
                    e.target.matches('input, button, a, .form-check-input, .dropdown-item, i') ||
                    e.target.closest('input, button, a, .form-check-input, .dropdown-item')) {
                    return;
                }
                
                isDown = true;
                tableContainer.classList.add('active');
                startX = e.pageX - tableContainer.offsetLeft;
                scrollLeft = tableContainer.scrollLeft;
                tableContainer.style.cursor = 'grabbing';
                e.preventDefault();
            });

            tableContainer.addEventListener('mouseup', () => {
                isDown = false;
                tableContainer.classList.remove('active');
                tableContainer.style.cursor = 'grab';
            });

            tableContainer.addEventListener('mouseleave', () => {
                isDown = false;
                tableContainer.classList.remove('active');
                tableContainer.style.cursor = 'grab';
            });

            tableContainer.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - tableContainer.offsetLeft;
                const walk = (x - startX) * 2;
                tableContainer.scrollLeft = scrollLeft - walk;
            });

            tableContainer.style.cursor = 'grab';
            tableContainer.style.scrollBehavior = 'smooth';

            // View response data modal
            window.viewResponse = function(id) {
                fetch(`/admin/smshistory/response/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('responseData').textContent = data.formatted_data || 'Không có dữ liệu';
                        new bootstrap.Modal(document.getElementById('responseModal')).show();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi tải dữ liệu');
                    });
            };
        });
    </script>
@endsection

@section('content')
    <div class="row gy-4">
        <!-- Thống kê -->
        <div class="col-12">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card stats-card text-center p-3">
                        <h5 class="text-primary mb-1">{{ number_format(@$stats['total']) }}</h5>
                        <small class="text-muted">Tổng SMS</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card text-center p-3">
                        <h5 class="text-success mb-1">{{ number_format(@$stats['success']) }}</h5>
                        <small class="text-muted">Thành công</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card text-center p-3">
                        <h5 class="text-danger mb-1">{{ number_format(@$stats['failed']) }}</h5>
                        <small class="text-muted">Thất bại</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card text-center p-3">
                        <h5 class="text-info mb-1">{{ number_format(@$stats['today']) }}</h5>
                        <small class="text-muted">Hôm nay</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bộ lọc -->
        {{-- <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.smshistory.index') }}">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="">Tất cả</option>
                                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Thành công</option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Loại SMS</label>
                                <select name="type" class="form-select">
                                    <option value="">Tất cả</option>
                                    <option value="1" {{ request('type') == '1' ? 'selected' : '' }}>Thông báo</option>
                                    <option value="2" {{ request('type') == '2' ? 'selected' : '' }}>Khuyến mãi</option>
                                    <option value="3" {{ request('type') == '3' ? 'selected' : '' }}>OTP</option>
                                    <option value="4" {{ request('type') == '4' ? 'selected' : '' }}>CSKH</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nhà mạng</label>
                                <select name="telco" class="form-select">
                                    <option value="">Tất cả</option>
                                    <option value="VTE" {{ request('telco') == 'VTE' ? 'selected' : '' }}>Viettel</option>
                                    <option value="VNA" {{ request('telco') == 'VNA' ? 'selected' : '' }}>VinaPhone</option>
                                    <option value="VMS" {{ request('telco') == 'VMS' ? 'selected' : '' }}>MobiFone</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Từ ngày</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Đến ngày</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tìm kiếm</label>
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="SĐT, tin nhắn..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}

        <!-- Action buttons -->
        @if (@$show_button_action)
            <x-button-action show="delete" create="{{ route('create_smshistory') }}" :model="$model" />
        @endif
        <x-filter prefix="{{ $prefix }}" :filter-value="$filter" field="image" />

        <!-- Bảng dữ liệu -->
        <div class="col-12">
            <div class="card">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table" style="min-width: 1400px; white-space: nowrap;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px; min-width: 60px;">
                                    <span class="form-check mb-0">
                                        <input id="selectAllRecord" class="form-record form-check-input me-1"
                                            type="checkbox" value="">
                                    </span>
                                </th>
                                <th style="width: 120px; min-width: 120px;">Số điện thoại</th>
                                <th style="width: 80px; min-width: 80px;">Nhà mạng</th>
                                <!-- <th style="width: 100px; min-width: 100px;">Loại SMS</th>
                                <th style="width: 100px; min-width: 100px;">Người gửi</th> -->
                                <th style="width: 200px; min-width: 400px;">Nội dung</th>
                                <th style="width: 120px; min-width: 120px;">OTP</th>
                                <th style="width: 80px; min-width: 80px;">Độ dài</th>
                                <th style="width: 100px; min-width: 100px;">Trạng thái</th>
                                <th style="width: 120px; min-width: 120px;">Mã lỗi</th>
                                <th style="width: 140px; min-width: 140px;">Thời gian</th>
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
                                    <td>{{ $item->phone }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $item->telco ?? 'N/A' }}</span>
                                    </td>
                                    <!-- <td>{{ $item->type_label }}</td>
                                    <td>{{ $item->sender }}</td> -->
                                    <td>
                                        <div class="message-preview" title="{{ $item->message }}">
                                            {{ $item->message }}
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $item->request_id }}</small>
                                    </td>
                                    <td>{{ $item->msg_length }}</td>
                                    <td>
                                        <span class="status-badge {{ $item->status === 'success' ? 'status-success' : 'status-failed' }}">
                                            {{ $item->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->error_code)
                                            <span class="badge bg-danger" title="{{ $item->error_message }}">
                                                {{ $item->error_code }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $item->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            {{-- <button class="btn btn-sm btn-outline-info me-1" 
                                                onclick="viewResponse({{ $item->id }})" 
                                                title="Xem response">
                                                <i class="mdi mdi-eye"></i>
                                            </button> --}}
                                            <a style="width: 30px" class="dropdown-item p-1"
                                                href="{{ route('edit_smshistory', ['id' => $item->id]) }}">
                                                <i class="mdi mdi-pencil-outline me-1"></i>
                                            </a>
                                            <a style="width: 30px" class="dropdown-item p-1 delete-record"
                                                data-link="{{ route('delete_smshistory', ['id' => $item->id, 'agreeDelete' => $item->id, 'route' => 'admin.smshistory.index']) }}">
                                                <i class="mdi mdi-trash-can-outline me-1"></i>
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

    <!-- Modal xem response data -->
    <div class="modal fade" id="responseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Response Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <pre id="responseData" class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;"></pre>
                </div>
            </div>
        </div>
    </div>
@endsection
