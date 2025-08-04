@extends('admin.contentNavLayout')

@php
$title = (@$data->id ? 'Chi tiết' : 'Thêm') . ' ' . 'thành viên';
@endphp
@section('title', $title)


@section('page-script')
<script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
@endsection

@section('content')

<div class="d-flex justify-content-between">
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light"></span> {{ @$data->id ? 'Chi tiết' : 'Thêm' }} thành viên
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
        <form id="formAccountSettings" method="POST" action={{ route('admin.member.save') }} enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ @$data->id }}" />
            @csrf
            <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-center gap-4">
                    <div>
                        <img onerror="this.src='{{ asset('img/no-image.png') }}'" src="{{ @$data->image }}"
                            alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" style="object-fit:cover"
                            id="uploadedAvatar" />
                        <button type="button" class="btn btn-outline-info mt-4" data-bs-toggle="modal"
                            data-bs-target="#modalCenter">
                            Đổi mật khẩu
                        </button>
                    </div>

                    <div class="button-wrapper">
                        <label for="upload" class="btn btn-primary me-2 mb-3" tabindex="0">
                            <span class="d-none d-sm-block">Thay ảnh đại diện</span>
                            <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
                            <input form="formAccountSettings" type="file" id="upload" name="image"
                                class="account-file-input" hidden />
                        </label>
                        <div class="form-check form-switch mt-4">
                            <label for="publish">Kích hoạt</label>
                            <input form="formAccountSettings" id="published" class="form-check-input float-start"
                                type="checkbox" value="1" name="published" role="switch"
                                {{ @$data->published ? 'checked' : null }}>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="id" value="{{ @$data->id }}">
            <div class="row mt-2 gy-4">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="name" name="name"
                            value="<?php echo @$data->name; ?>" placeholder="<?php echo @$data->name; ?>" autofocus />
                        <label for="firstName">Tên thành viên</label>
                    </div>
                </div>
                {{-- XÓA Mã khách hàng Fast --}}
                {{-- <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="ma_kh" readonly
                            value="<?php echo @$data->ma_kh; ?>" placeholder="<?php echo @$data->ma_kh; ?>" />
                        <label for="email">Mã khách hàng Fast</label>
                    </div>
                </div> --}}
            </div>
            {{-- XÓA Hạng thẻ và Điểm tích luỹ --}}
            {{-- <div class="row mt-2 gy-4">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="hang_the" readonly
                            value="<?php echo @$data->hang_the; ?>" placeholder="<?php echo @$data->hang_the; ?>" autofocus />
                        <label for="firstName">Hạng thẻ</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="diem_tich_luy" readonly
                            value="<?php echo @$data->diem_tich_luy; ?>" placeholder="<?php echo @$data->diem_tich_luy; ?>" />
                        <label for="email">Điểm tích luỹ</label>
                    </div>
                </div>
            </div> --}}
            {{-- XÓA Điểm thưởng và Điểm lên hạng --}}
            {{-- <div class="row mt-2 gy-4">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="hang_the" readonly
                            value="<?php echo @$data->diem_thuong; ?>" placeholder="<?php echo @$data->diem_thuong; ?>" autofocus />
                        <label for="firstName">Điểm thưởng</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="diem_tich_luy" readonly
                            value="<?php echo @$data->diem_len_hang; ?>" placeholder="<?php echo @$data->diem_len_hang; ?>" />
                        <label for="email">Điểm lên hạng</label>
                    </div>
                </div>
            </div> --}}
            <div class="row mt-2 gy-4">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="email" name="email"
                            value="<?php echo @$data->email; ?>" placeholder="<?php echo @$data->email; ?>" autofocus />
                        <label for="firstName">Email</label>
                    </div>
                </div>
                {{-- XÓA Tỉ lệ lên hạng --}}
                {{-- <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="diem_tich_luy" readonly
                            value="<?php echo @$data->ty_le_len_hang; ?>" placeholder="<?php echo @$data->ty_le_len_hang; ?>" />
                        <label for="email">Tỉ lệ lên hạng</label>
                    </div>
                </div> --}}
            </div>
            <div class="row mt-2 gy-4">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="phone" name="phone"
                            value="<?php echo @$data->phone; ?>" placeholder="<?php echo @$data->phone; ?>" />
                        <label for="email">Số điện thoại</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline mb-4">
                        @if (@$data->dob)
                        <input class="form-control" type="date" name="dob"
                            value="{{ date('Y-m-d', strtotime(@$data->dob)) }}" id="html5-date-input">
                        @else
                        <input class="form-control" type="date" name="dob" value=""
                            id="html5-date-input">
                        @endif
                        <label for="html5-date-input">Ngày sinh</label>
                    </div>
                </div>
            </div>
            <div class="row gy-4">
                <div class="col-md-6">
                    @if (isset($provinces) && count($provinces) > 0)
                    <select name="province_id" class="form-select select2 form-select-sm">
                        <option value="0">---Tỉnh thành---</option>
                        @foreach ($provinces as $province)
                        @if (@$data->province_id == $province->code && @$province->code)
                        <option
                            {{ @$data->province_id == $province->code && @$province->code ? 'selected' : null }}
                            value="{{ $province->code }}">{!! $province->name !!}</option>
                        @else
                        <option value="{{ $province->code }}">{!! $province->name !!}</option>
                        @endif
                        @endforeach
                    </select>
                    @endif
                </div>
                <div class="col-md-6">
                    @if (isset($districts) && count($districts) > 0)
                    <select name="district_id" class="form-select select2 form-select-sm">
                        <option value="0">---Quận huyện---</option>
                        @foreach ($districts as $district)
                        @if (@$data->district_id == $district->code && @$district->code)
                        <option
                            {{ @$data->district_id == $district->code && @$district->code ? 'selected' : null }}
                            value="{{ $district->code }}">{!! $district->name !!}</option>
                        @else
                        <option value="{{ $district->code }}">{!! $district->name !!}</option>
                        @endif
                        @endforeach
                    </select>
                    @endif
                </div>
                <div class="col-md-6">
                    @if (isset($wards) && count($wards) > 0)
                    <select name="ward_id" class="form-select select2 form-select-sm">
                        <option value="0">---Phường xã---</option>
                        @foreach ($wards as $ward)
                        @if (@$data->ward_id == $ward->code && @$ward->code)
                        <option
                            {{ @$data->ward_id == $ward->code && @$ward->code ? 'selected' : null }}
                            value="{{ $ward->code }}">{!! $ward->name !!}</option>
                        @else
                        <option value="{{ $ward->code }}">{!! $ward->name !!}</option>
                        @endif
                        @endforeach
                    </select>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalCenterTitle">Đổi mật khẩu</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('member_change_password') }}" method="POST" id="member_change_password">
                <input type="hidden" name="id" value="{{ @$data->id }}">

                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4 mt-2">
                            <div class="form-floating form-floating-outline mt-3">
                                <input type="password" id="new-pass" name="password" class="form-control"
                                    placeholder="Mật khẩu cũ">
                                <label for="nameWithTitle">Mật khẩu mới</label>
                            </div>
                            <div class="form-floating form-floating-outline mt-3">
                                <input type="password" id="confirm-pass" name="confirm-pass" class="form-control"
                                    placeholder="Mật khẩu cũ">
                                <label for="nameWithTitle">Nhập lại mật khẩu</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>

        </div>
    </div>
</div>


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