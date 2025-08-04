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
                e.preventDefault(); // Ngăn chặn selection text
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
                const walk = (x - startX) * 2; // Tốc độ cuộn
                tableContainer.scrollLeft = scrollLeft - walk;
            });

            // Thêm style cursor khi hover
            tableContainer.style.cursor = 'grab';
            
            // CSS cho smooth scrolling
            tableContainer.style.scrollBehavior = 'smooth';
        });

        // Xử lý click button xem voucher
        document.addEventListener('click', function(e) {
            if (e.target.closest('.view-vouchers')) {
                e.preventDefault();
                const button = e.target.closest('.view-vouchers');
                const customer = button.getAttribute('data-customer');
                const customerName = button.getAttribute('data-name');
                
                if (!customer || customer === 'null' || customer === '') {
                    alert('Khách hàng chưa có mã khách hàng hoặc mã thẻ!');
                    return;
                }
                
                // Cập nhật title modal
                document.getElementById('voucherModalLabel').textContent = `Voucher của ${customerName} (${customer})`;
                
                // Hiển thị modal và loading
                const modal = new bootstrap.Modal(document.getElementById('voucherModal'));
                modal.show();
                
                // Gọi Ajax để lấy voucher
                fetchVouchers(customer);
            }
        });

        function fetchVouchers(customer) {
            const voucherContent = document.getElementById('voucher-content');
            
            // Hiển thị loading
            voucherContent.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Đang tải voucher...</p>
                </div>
            `;
            
            // Gọi Ajax
            fetch(`{{ route('admin.member.vouchers') }}?customer=${encodeURIComponent(customer)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayVouchers(data.vouchers);
                    
                    // Hiển thị nút tạo welcome voucher nếu cần
                    if (data.canCreateWelcome && data.member) {
                        showWelcomeVoucherButton(data.member, customer);
                    }
                } else {
                    voucherContent.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="mdi mdi-alert-outline me-2"></i>
                            ${data.message || 'Không thể tải danh sách voucher'}
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                voucherContent.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="mdi mdi-alert-circle-outline me-2"></i>
                        Có lỗi xảy ra khi tải voucher
                    </div>
                `;
            });
        }

        function displayVouchers(vouchers) {
            const voucherContent = document.getElementById('voucher-content');
            
            if (!vouchers || vouchers.length === 0) {
                voucherContent.innerHTML = `
                    <div class="text-center">
                        <i class="mdi mdi-ticket-outline display-4 text-muted"></i>
                        <p class="text-muted mt-2">Khách hàng chưa có voucher nào</p>
                        <div id="welcome-voucher-section"></div>
                    </div>
                `;
                return;
            }
            
            let html = '<div class="row">';
            
            vouchers.forEach(voucher => {
                const statusBadge = getStatusBadge(voucher);
                const discountText = voucher.type == 3 || voucher.type == 4 
                    ? `${voucher.discount}%` 
                    : `${parseInt(voucher.discount).toLocaleString('vi-VN')}₫`;
                
                html += `
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="card-title mb-2" style="color: #5a5a5a; font-weight: 600;">${voucher.name || voucher.code}</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="card-text mb-1"><strong>Mã voucher:</strong> <span class="text-primary">${voucher.code}</span></p>
                                                <p class="card-text mb-1"><strong>Giảm giá:</strong> <span class="text-success fw-bold">${discountText}</span></p>
                                                <p class="card-text mb-1"><strong>Năm:</strong> ${voucher.year}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="card-text mb-1"><strong>Ngày tạo:</strong> ${formatDate(voucher.created_time)}</p>
                                                <p class="card-text mb-1"><strong>Ngày bắt đầu:</strong> ${formatDate(voucher.date_start)}</p>
                                                <p class="card-text mb-1"><strong>Ngày hết hạn:</strong> ${formatDate(voucher.date_expiration)}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <div class="d-flex flex-column align-items-end h-100 justify-content-between">
                                            ${statusBadge}
                                            <small class="text-muted mt-2">ID: ${voucher.id || 'N/A'}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            html += '<div id="welcome-voucher-section"></div>';
            voucherContent.innerHTML = html;
        }

        function getStatusBadge(voucher) {
            // Kiểm tra xem voucher có hết hạn không
            const now = new Date();
            const expirationDate = new Date(voucher.date_expiration);
            const isExpired = expirationDate < now;
            
            // Nếu voucher hết hạn, hiển thị "Hết hạn" 
            if (isExpired) {
                return '<span class="badge bg-danger">Hết hạn</span>';
            }
            
            // Nếu chưa hết hạn, kiểm tra status
            switch(parseInt(voucher.status)) {
                case 1:
                    return '<span class="badge bg-success">Hoạt động</span>';
                case 2:
                    return '<span class="badge bg-warning">Đã sử dụng</span>';
                case 0:
                    return '<span class="badge bg-secondary">Chưa kích hoạt</span>';
                default:
                    return '<span class="badge bg-info">Khác</span>';
            }
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            
            // Kiểm tra xem date có hợp lệ không
            if (isNaN(date.getTime())) return 'N/A';
            
            // Format ngắn gọn: dd/mm/yyyy
            return date.toLocaleDateString('vi-VN', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            });
        }

        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'mdi-check-circle' : 'mdi-alert-circle';
            
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <i class="mdi ${icon} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            // Thêm alert vào đầu modal body
            const modalBody = document.querySelector('#voucherModal .modal-body');
            const existingAlert = modalBody.querySelector('.alert');
            if (existingAlert) {
                existingAlert.remove();
            }
            modalBody.insertAdjacentHTML('afterbegin', alertHtml);
            
            // Tự động ẩn sau 5 giây
            setTimeout(() => {
                const alert = modalBody.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }
    </script>
@endsection

@section('content')
    @php

    @endphp

    <div class="row gy-4">
            <x-button-action show="create,duplicate,active,unactive,delete" :view="$view" create="{{ route('admin.member.create') }}" :model="$model" />

        <x-filter prefix="{{ $prefix }}" :filter-value="$filter" />
        <div class="col-12">
            <div class="card">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table" style="min-width: 1560px; white-space: nowrap;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px; min-width: 60px;">
                                    <span class="form-check mb-0">
                                        <input id="selectAllRecord" class="form-record form-check-input me-1"
                                            type="checkbox" value="">
                                    </span>
                                </th>
                                <th style="width: 220px; min-width: 220px;">Tên thành viên</th>
                                <th style="width: 120px; min-width: 120px;">SĐT</th>
                                <th style="width: 110px; min-width: 110px;">Ngày sinh</th>
                                <th style="width: 110px; min-width: 110px;">Mật khẩu</th>
                                <th style="width: 100px; min-width: 100px;">Trạng thái</th>
                                <th style="width: 120px; min-width: 120px;">Đăng nhập App</th>
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
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3 flex-shrink-0">
                                                <img src="{{ $item->image }}"
                                                    onerror="this.src='{{ asset('img/no-image.png') }}'" alt="Avatar"
                                                    class="rounded-circle">
                                            </div>
                                            <div class="text-truncate">
                                                {{ @$item->name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ @$item->phone }}</td>
                                    <td>{{ @$item->dob }}</td>
                                    <td>
                                        @if(@$item->password)
                                            <span class="text-muted">*****</span>
                                        @else
                                            <span class="text-muted">Chưa có</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input data-model="{{ $model }}" data-id="{{ $item->id }}"
                                                data-link="{{ $item->published == 1 ? '/api/active' : '/api/unactive' }}"
                                                id="published" class="form-check-input form-check-input-status float-start"
                                                type="checkbox" value="1" name="published" role="switch"
                                                {{ $item->published == 1 ? 'checked' : null }}>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->token_fcm)
                                            <span class="badge bg-success">Đã đăng nhập</span>
                                        @else
                                            <span class="badge bg-secondary">Chưa đăng nhập</span>
                                        @endif
                                    </td>
                                    <td>{{ @$item->created_at }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a style="width: 30px" class="dropdown-item p-1"
                                                href="{{ route('edit_members', ['id' => $item->id]) }}" title="Chỉnh sửa">
                                                <i class="mdi mdi-pencil-outline me-1"></i>
                                            </a>
                                            <a style="width: 30px" class="dropdown-item p-1 view-vouchers"
                                                data-customer="{{ $item->ma_kh ?: $item->ma_the }}"
                                                data-name="{{ $item->name }}" title="Xem voucher">
                                                <i class="mdi mdi-ticket-outline me-1"></i>
                                            </a>
                                            <a style="width: 30px" class="dropdown-item p-1 delete-record"
                                                data-link="{{ route('admin.member.delete', ['id' => $item->id, 'agreeDelete' => $item->id, 'route' => 'admin.member.index']) }}" title="Xóa">
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

        <!--/ Data Tables -->
    </div>

    <!-- Modal hiển thị voucher -->
    <div class="modal fade" id="voucherModal" tabindex="-1" aria-labelledby="voucherModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="voucherModalLabel">Danh sách voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div id="voucher-content">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
