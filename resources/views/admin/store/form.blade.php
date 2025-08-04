@extends('admin.contentNavLayout')

@php
    $title = (@$data->id ? 'Chi tiết' : 'Thêm') . ' ' . 'cửa hàng';
@endphp
@section('title', $title)


@section('page-script')
    <script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
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
            <form id="formAccountSettings" method="POST" action={{ route('admin.store.save') }} enctype="multipart/form-data">
                <input type="hidden" name="id" value="{{ @$data->id }}" />
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="name" name="name"
                                    value="{{ @$data->name }}" placeholder="" />
                                <label for="firstName">Tên cửa hàng</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="alias" name="alias"
                                    value="{{ @$data->alias }}" placeholder="" />
                                <label for="firstName">Alias</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="address" name="address"
                                    value="{{ @$data->address }}" placeholder="" />
                                <label for="firstName">Địa chỉ</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="phone" name="phone"
                                    value="{{ @$data->phone }}" placeholder="" />
                                <label for="ordering">Số điện thoại</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="ordering" name="ordering"
                                    value="{{ @$data->ordering ? @$data->ordering : @$maxOrdering }}" placeholder="" />
                                <label for="firstName">Thứ tự</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            @if (isset($cities) && count($cities) > 0)
                                <select name="province_id" class="form-select select2 form-select-sm">
                                    <option value="0">---Tỉnh thành phố---</option>
                                    @foreach ($cities as $city)
                                        @if (@$data->province_id == $city->code && @$data->province_id)
                                            <option
                                                {{ @$data->province_id == $city->code && @$data->province_id ? 'selected' : null }}
                                                value="{{ $city->code }}">{{ $city->name }}</option>
                                        @else
                                            <option value="{{ $city->code }}">{!! $city->name !!}</option>
                                        @endif
                                    @endforeach
                                </select>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="email" name="email"
                                    value="{{ @$data->email }}" placeholder="" />
                                <label for="ordering">Email</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-check form-switch mt-4">
                                <label for="published">Kích hoạt</label>
                                <input form="formAccountSettings" id="publish" class="form-check-input float-start"
                                    type="checkbox" value="1" name="published" role="switch"
                                    {{ @$data->published || !@$data->id ? 'checked' : null }}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="latitude" name="latitude"
                                    value="{{ @$data->latitude }}" placeholder="" />
                                <label for="ordering">Vĩ độ</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="longitude" name="longitude"
                                    value="{{ @$data->longitude }}" placeholder="" />
                                <label for="ordering">Kinh độ</label>
                            </div>
                        </div>

                    </div>
                    <div class="mt-4">
                        <div class="">
                            <div class="col-md-12">
                                <x-editor_v2 name="description" id="description" title="Nhúng bản đồ"
                                    content="{{ @$data->description }}" />
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="id" value="{{ @$data->id }}">
            </form>
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
    </script>
@endsection
