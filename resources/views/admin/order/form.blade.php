@extends('admin.contentNavLayout')

@php
    $title = (@$data->id ? 'Chi tiết' : 'Thêm') . ' ' . 'đơn hàng';
@endphp
@section('title', $title)

@section('page-script')
    <script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Header Section -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 gap-3">
                <div class="d-flex flex-column justify-content-center">
                    <div class="d-flex align-items-center mb-1">
                        <h5 class="mb-0">Đơn hàng #{{ $data->order_code }}</h5>
                        @if ($data->payment_status == 1)
                            <span class="badge bg-label-success me-2 ms-2 rounded-pill">Đã thanh toán</span>
                        @elseif($data->payment_status == 0)
                            <span class="badge bg-label-danger me-2 ms-2 rounded-pill">Chưa thanh toán</span>
                        @endif
                    </div>
                    <p class="mt-1 mb-3">{{ $data->created_at }}</p>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-2">
                    @if(!$check_fast_exists)
                    <a href="{{ route('send_fast') . '?order_id=' . $data->id }}"
                    class="btn btn-outline-primary delete-order waves-effect">FAST
                    </a>
                    @endif
                    <a href="{{ route('admin.order.index') }}"
                        class="btn btn-outline-danger delete-order waves-effect">Đóng</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="row">
                <!-- Left Column - Order Details -->
                <div class="col-12 col-lg-8">
                    <!-- Order Items Card -->
                    <div class="card mb-6">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0">Chi tiết đơn hàng</h5>
                        </div>
                        <div class="card-datatable table-responsive">
                            <table class="table table-borderless">
                                <thead class="table-light">
                                    <tr>
                                        <th class="w-50">Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-start align-items-center product-name">
                                                    <div class="avatar-wrapper me-3">
                                                        @if (@$item->product->image)
                                                            <div class="avatar avatar-lg rounded-2 bg-label-secondary" style="width: 60px; height: 60px; overflow: hidden;">
                                                                <img src="{{ @$item->product->image }}"
                                                                    alt="{{ @$item->product->name ?? @$item->name }}" 
                                                                    class="rounded-2"
                                                                    style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                                                            </div>
                                                        @else
                                                            <div class="avatar avatar-lg rounded-2 bg-label-secondary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                                <i class="mdi mdi-image-outline text-muted" style="font-size: 24px;"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="text-nowrap text-heading fw-medium">
                                                            {{ @$item->product->name ? @$item->product->name : @$item->name }}
                                                        </span>
                                                        @php
                                                            $json = $item->options;
                                                            $options = json_decode($json, true);
                                                            $colorLabel = $options['color']['label'];
                                                            $result = "Màu sắc: $colorLabel";
                                                            if (@$options['size']['label']) {
                                                                $sizeLabel = @$options['size']['label'];
                                                                $result .= "; Kích thước: $sizeLabel";
                                                            }
                                                        @endphp
                                                        <small class="text-muted d-none d-sm-block">{{ $result }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="fw-medium">{{ number_format($item->price, 0, ',', '.') }}đ</span></td>
                                            <td><span>{{ $item->quantity }}</span></td>
                                            <td><span class="fw-medium">{{ number_format($item->total, 0, ',', '.') }}đ</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="card-footer">
                            <div class="d-flex justify-content-end">
                                <div class="order-calculations">
                                    <div class="d-flex justify-content-between gap-4 mb-2">
                                        <span class="text-heading">Tạm tính:</span>
                                        <h6 class="mb-0">{{ number_format($data->total, 0, ',', '.') }}đ</h6>
                                    </div>
                                    <div class="d-flex justify-content-between gap-4 mb-2">
                                        <span class="text-heading">Giảm giá:</span>
                                        <h6 class="mb-0">{{ number_format($data->discount ? $data->discount : 0, 0, ',', '.') }}đ</h6>
                                    </div>
                                    <div class="d-flex justify-content-between gap-4 mb-2">
                                        <span class="text-heading">Vận chuyển:</span>
                                        <h6 class="mb-0">{{ number_format($data->total_shipping ? $data->total_shipping : 0, 0, ',', '.') }}đ</h6>
                                    </div>
                                    <hr class="my-3">
                                    <div class="d-flex justify-content-between gap-4">
                                        <h6 class="mb-0">Thành tiền:</h6>
                                        <h6 class="mb-0 text-primary">{{ number_format($data->total_price, 0, ',', '.') }}đ</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Status Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title m-0">Trạng thái đơn hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input name="order_status" class="form-check-input" type="radio" value="0"
                                            id="order_status0" {{ $data->order_status == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="order_status0">
                                            <span class="badge bg-label-danger me-2">Đặt hàng không thành công</span>
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input name="order_status" class="form-check-input" type="radio" value="1"
                                            id="order_status1" {{ $data->order_status == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="order_status1">
                                            <span class="badge bg-label-warning me-2">Đặt hàng</span>
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input name="order_status" class="form-check-input" type="radio" value="2"
                                            id="order_status2" {{ $data->order_status == 2 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="order_status2">
                                            <span class="badge bg-label-info me-2">Xác nhận đặt hàng thành công</span>
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input name="order_status" class="form-check-input" type="radio" value="3"
                                            id="order_status3" {{ $data->order_status == 3 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="order_status3">
                                            <span class="badge bg-label-secondary me-2">Hết hàng</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input name="order_status" class="form-check-input" type="radio" value="4"
                                            id="order_status4" {{ $data->order_status == 4 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="order_status4">
                                            <span class="badge bg-label-primary me-2">Đang vận chuyển</span>
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input name="order_status" class="form-check-input" type="radio" value="5"
                                            id="order_status5" {{ $data->order_status == 5 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="order_status5">
                                            <span class="badge bg-label-success me-2">Giao hàng và thanh toán thành công</span>
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input name="order_status" class="form-check-input" type="radio" value="6"
                                            id="order_status6" {{ $data->order_status == 6 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="order_status6">
                                            <span class="badge bg-label-dark me-2">Hủy đơn hàng</span>
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input name="order_status" class="form-check-input" type="radio" value="7"
                                            id="order_status7" {{ $data->order_status == 7 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="order_status7">
                                            <span class="badge bg-label-success me-2">Đã thanh toán online thành công</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Customer Info -->
                <div class="col-12 col-lg-4">
                    <!-- Customer Info Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Thông tin khách hàng</h5>
                        </div>
                        <div class="card-body">
                            @if ($data->member_id)
                                <div class="d-flex justify-content-start align-items-center mb-4">
                                    <div class="avatar me-3">
                                        <div class="avatar-initial rounded-circle bg-label-primary">
                                            <i class="mdi mdi-account"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <a href="{{ route('edit_members', ['id' => $data->member_id]) }}" class="text-decoration-none">
                                            <small class="text-muted">Tài khoản</small>
                                            <h6 class="mb-0">{{ @$data->member->name }}</h6>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="mb-4">
                                <h6 class="mb-3">Thông tin liên hệ</h6>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="mdi mdi-account-outline me-2 text-muted"></i>
                                    <span>{{ @$data->name }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="mdi mdi-email-outline me-2 text-muted"></i>
                                    <span>{{ @$data->member->email }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-phone-outline me-2 text-muted"></i>
                                    <span>{{ @$data->phone ? @$data->phone : @$data->member->phone }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Địa chỉ nhận hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <i class="mdi mdi-map-marker-outline me-2 text-muted mt-1"></i>
                                <p class="mb-0">{{ $data->address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Card -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Hình thức thanh toán</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $hinh_thuc_thanh_toan = '';
                                $payment_icon = '';
                                switch ($data->payment_method) {
                                    case 'cod':
                                        $hinh_thuc_thanh_toan = 'Chuyển tiền khi nhận hàng (COD)';
                                        $payment_icon = 'mdi-cash';
                                        break;
                                    case 'payoo':
                                        $hinh_thuc_thanh_toan = 'Thanh toán online qua payoo';
                                        $payment_icon = 'mdi-credit-card';
                                        break;
                                }
                            @endphp
                            <div class="d-flex align-items-center mb-4">
                                <i class="mdi {{ $payment_icon }} me-2 text-primary"></i>
                                <span class="fw-medium">{{ $hinh_thuc_thanh_toan }}</span>
                            </div>
                            
                            @if (@$payoo_info)
                                <div class="border-top pt-3">
                                    <h6 class="mb-3">Thông tin thanh toán</h6>
                                    @if (is_object($payoo_info))
                                        @foreach ($payoo_info as $key => $value)
                                            @if ($key == 'BankName' || $key == 'CardNumber' || $key == 'PaymentMethodName')
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-muted">{{ $key }}:</span>
                                                    <span class="fw-medium">{{ $value }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Fast Request JSON Card -->
                    @if (@$log_fast_data && @$data->order_code)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">JSON bắn sang Fast</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Request Data:</label>
                                @php
                                    $decoded_json = json_decode($log_fast_data->request, true);
                                    $formatted_json = json_encode($decoded_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                                @endphp
                                <textarea class="form-control" rows="15" readonly>{{ $formatted_json }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-script')
@endpush
