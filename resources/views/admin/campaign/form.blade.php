@extends('admin.contentNavLayout')

@php
    $title = (@$data->id ? 'Chi tiết' : 'Thêm') . ' ' . 'chiến dịch';
@endphp
@section('title', $title)

@section('page-style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection
@section('page-script')
    <script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection

@section('content')
    <div class="d-flex justify-content-between">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light"></span> {{ $title }}
        </h4>
        <div class="mt-4 mb-4">
            <button type="submit" id="submit1" class="btn btn-primary me-2">Lưu</button>
            <button type="submit" id="submit2" class="btn btn-primary me-2">Lưu & Mới</button>
            <button type="submit" id="submit3" class="btn btn-primary me-2">Lưu & Thoát</button>
            <button type="reset" onclick="window.location.href='{{ route($view . '.index') }}'"
                class="btn btn-outline-secondary">Đóng</button>
        </div>
    </div>
    <div class="card">
        <div class="card-body pt-2 mt-1">
            <form id="formAccountSettings" method="POST" action={{ route($view . '.save') }} enctype="multipart/form-data">
                <input type="hidden" name="id" value="{{ @$data->id }}" />
                @csrf
                <div class="row mt-2 gy-4">
                    <div class="col-md-12">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <div class="button-wrapper">
                                <x-choose-file title="Thay ảnh đại diện" :type="'Images'" id="image" :dataComponent="@$data"
                                    field="image" />
                                <div class="form-check form-switch mt-4">
                                    <label for="publish">Kích hoạt</label>
                                    <input form="formAccountSettings" id="published" class="form-check-input float-start"
                                        type="checkbox" value="1" name="published" role="switch"
                                        {{ @$data->published || @!$data->id ? 'checked' : null }}>
                                </div>
                                <div class="form-check form-switch mt-4">
                                    <label for="publish">Flash Sale</label>
                                    <input form="formAccountSettings" id="flash_sale" class="form-check-input float-start"
                                        type="checkbox" value="1" name="flash_sale"
                                        {{ @$data->flash_sale || @$data->flash_sale ? 'checked' : null }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="text" id="name" name="name"
                                value="<?php echo @$data->name; ?>" placeholder="<?php echo @$data->name; ?>" autofocus />
                            <label for="name">Tên</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="text" id="alias" name="alias"
                                value="{{ @$data->alias }}" placeholder="{{ @$data->alias }}" />
                            <label for="alias">Alias</label>
                        </div>
                    </div>
                    <div class="col-md-6" id="discount_value_container">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="text" id="discount_value" name="discount_value"
                                value="{{ @$data->discount_value ? @$data->discount_value : @$discount_value }}"
                                placeholder="{{ @$data->discount_value }}" />
                            <label for="discount_value">Giá trị giảm</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <select name="discount_unit" id="discount_unit" class="form-select select2 form-select-sm">
                            <option value="0">---Đơn vị tính(*)---</option>
                            <option value="1" {{ @$data->discount_unit == 1 ? 'selected' : '' }}>%</option>
                            <option value="2" {{ @$data->discount_unit == 2 ? 'selected' : '' }}>VND</option>
                            <option value="3" {{ @$data->discount_unit == 3 ? 'selected' : '' }}>Đồng giá</option>
                            <option value="4" {{ @$data->discount_unit == 4 ? 'selected' : '' }}>Tổng đơn (VNĐ)
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6 total-order-inputs" id="bill_from_container">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="text" id="bill_from" name="bill_from"
                                value="{{ @$data->bill_from ? @$data->bill_from : @$bill_from }}"
                                placeholder="{{ @$data->bill_from }}" />
                            <label for="bill_from">Giá trị hoá đơn từ</label>
                        </div>
                    </div>
                    <div class="col-md-6 total-order-inputs" id="bill_to_container">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="text" id="bill_to" name="bill_to"
                                value="{{ @$data->bill_to ? @$data->bill_to : @$bill_to }}"
                                placeholder="{{ @$data->bill_to }}" />
                            <label for="bill_to">Giá trị hoá đơn đến</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control datetimepicker" type="date" id="start_date" name="start_date"
                                value="{{ @$data->start_date ? date('d-m-Y H:i:s', strtotime(@$data->start_date)) : '' }}" />
                            <label for="start_date">Ngày bắt đầu</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control datetimepicker" type="date" id="end_date" name="end_date"
                                value="{{ @$data->end_date ? date('Y-m-d H:i:s', strtotime(@$data->end_date)) : '' }}" />
                            <label for="end_date">Ngày kết thúc</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="text" id="ordering" name="ordering"
                                value="{{ @$data->ordering ? @$data->ordering : @$maxOrdering }}"
                                placeholder="{{ @$data->ordering }}" />
                            <label for="email">Sắp xếp</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="applicable_platform">Nền tảng áp dụng (*)</label>
                        <select name="applicable_platform" class="form-select select2 form-select-sm">
                            <option value="0">---Nền tảng áp dụng---</option>
                            <option value="1" {{ @$data->applicable_platform == '1' ? 'selected' : '' }}>Website
                            </option>
                            <option value="2" {{ @$data->applicable_platform == '2' ? 'selected' : '' }}>APP</option>
                            <option value="3" {{ @$data->applicable_platform == '3' ? 'selected' : '' }}>Tất cả</option>
                        </select>
                        <p class="small btn-danger">Áp dụng khuyến mại dành cho nền tảng</p>
                    </div>
                    <div class="col-md-6" id="discount_file_container">
                        <div class="form-floating form-floating-outline">
                            <input class="form-control" type="file" id="discount_file" name="discount_file"
                                value="{{ @$data->discount_value ? @$data->discount_value : @$discount_value }}"
                                placeholder="{{ @$data->discount_value }}" />
                            <label for="discount_value">Chọn sản phẩm bằng file Excel</label>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="medium">Tải tệp mẫu sau đó gửi
                                tệp lên tại <a style="color: blue; text-decoration: underline" download href="{{ asset('files/khuyen_mai_demo.xlsx') }}">đây</a></span>
                        </div>

                    </div>
                    <div class="col-md-12" id="category_wrapper">
                        @if (isset($categories) && count($categories) > 0)
                            <label for="discount_value">Danh mục áp dụng</label>
                            <select name="categories[]" multiple class="form-select select2 form-select-sm">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ isset($data->categories) && in_array($category->id, explode(',', $data->categories)) ? 'selected' : '' }}>
                                        {!! $category->treename !!}
                                    </option>
                                @endforeach
                            </select>
                            <p class="small btn-danger">Chọn danh mục cha sẽ áp dụng cho toàn danh mục con</p>
                        @endif
                    </div>
                </div>
            </form>
            @if (@$products_campaign)
                <div class="products-campaign">
                    <p>Sản phẩm khuyến mại</p>
                    <div style="column-count: 4; column-gap: 10px">
                        @foreach ($products_campaign as $item)
                            <div data-id="{{ $item->id }}" class="product-related-item-right d-flex mb-1">
                                <img style="width: 50px; height: 50px; object-fit: cover"
                                    onerror="this.src='/img/no-image.png'" src="{{ @$item->product->image }}"
                                    alt="{{ $item->product_code }}">
                                <div class="ms-3">
                                    <span class="fw-bold"
                                        style="{{ $item->product_id ? '' : 'text-decoration: line-through' }}">{{ @$item->product->name }}</span>
                                    <span
                                        style="{{ $item->product_id ? '' : 'text-decoration: line-through' }}">{{ $item->product_code }}</span>
                                    <br>
                                    @if (!$item->product_id)
                                        <span class="small">Sản phẩm không tồn tại hoặc đã xoá
                                            <br>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection


@section('script_page')
    <script>
        $(document).ready(function() {
            $('#confirm-pass').on('blur', function() {
                var newPassword = $('#new-pass').val();
                var confirmPassword = $('#confirm-pass').val();
                if (newPassword !== confirmPassword) {
                    alert('Mật khẩu mới và mật khẩu nhập lại không khớp.');
                }
            });
        });
        $(document).on('click', '.delete-icon', function() {
            $(this).parent().remove();
        });

        $('.delete-icon-ajax').click(function() {
            let _this = this;
            let id_image = $(this).data('id-image');
            $.ajax({
                url: '/admin_baohieman/members/galleryImages/' + id_image,
                type: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $(_this).parent().remove();
                    } else {
                        // handle error
                    }
                    alert(response.message);

                }
            });
        });

        const discountUnitSelect = $('#discount_unit');
        const totalOrderInputs = $('.total-order-inputs');

        function toggleTotalOrderInputs() {
            if (discountUnitSelect.val() == '4') {
                totalOrderInputs.show();
            } else {
                totalOrderInputs.hide();
            }

            // if (discountUnitSelect.val() == 5) { // Theo sản phẩm
            //     $('#discount_value_container').hide();
            //     $('#bill_from_container').hide();
            //     $('#bill_to_container').hide();
            //     $("#category_wrapper").hide();
            //     $("#discount_file_container").show();
            //     $(".products-campaign").show();
            // } else {
            //     $('#discount_value_container').show();
            //     $("#discount_file_container").hide()
            //     $("#category_wrapper").show();
            //     $(".products-campaign").hide();
            // }
        }

        // Gọi hàm khi trang được tải
        toggleTotalOrderInputs();
        discountUnitSelect.change(toggleTotalOrderInputs);


        const startDatePicker = flatpickr('#start_date', {
            enableTime: true,
            dateFormat: "Y-m-d H:i:ss",
            time_24hr: true,
            defaultDate: "{{ isset($data->start_date) ? date('Y-m-d H:i:s', strtotime($data->start_date)) : '' }}",
            onChange: function(selectedDates, dateStr, instance) {
                endDatePicker.set('minDate', dateStr);
            }
        });

        const endDatePicker = flatpickr('#end_date', {
            enableTime: true,
            dateFormat: "Y-m-d H:i:ss",
            time_24hr: true,
            defaultDate: "{{ isset($data->end_date) ? date('Y-m-d H:i:s', strtotime($data->end_date)) : '' }}",
            onChange: function(selectedDates, dateStr, instance) {
                const startDate = startDatePicker.selectedDates[0];
                if (startDate && selectedDates[0] <= startDate) {
                    alert('Ngày kết thúc phải lớn hơn ngày bắt đầu');
                    instance.clear();
                }
            }
        });
    </script>
@endsection
