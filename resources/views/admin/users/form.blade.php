@extends('admin.contentNavLayout')

@section('title', 'Tài khoản')

@section('page-script')
    <script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="py-3">
            <span class="text-muted fw-light">Chi tiết </span> Tài khoản
        </h4>

        <div>
            <button type="submit" id="submit1" class="btn btn-primary me-2">Lưu</button>
            <button type="submit" id="submit2" class="btn btn-primary me-2">Lưu & Mới</button>
            <button type="submit" id="submit3" class="btn btn-primary me-2">Lưu & Thoát</button>
            <button type="reset" class="btn btn-outline-secondary"
                onclick="window.location.href = '{{ route('dashboard') }}'">Đóng</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h4 class="card-header">Thông tin</h4>
                <!-- Account -->
                <div class="card-body pt-2 mt-1">
                    <form id="formAccountSettings" method="POST" action={{ route('admin.user.save') }}
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                <img onerror="this.src='{{ asset('img/no-image.png') }}'" src="{{ @$data->image }}"
                                    alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" style="object-fit:cover"
                                    id="uploadedAvatar" />
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-3" tabindex="0">
                                        <span class="d-none d-sm-block">Thay ảnh đại diện</span>
                                        <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
                                        <input form="formAccountSettings" type="file" id="upload" name="image"
                                            class="account-file-input" hidden />
                                    </label>
                                    <button type="button" class="btn btn-outline-danger account-image-reset mb-3">
                                        <i class="mdi mdi-reload d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Reset</span>
                                    </button>
                                    <div class="form-check form-switch mt-4">
                                        <label for="published">Kích hoạt</label>
                                        <input form="formAccountSettings" id="published"
                                            class="form-check-input float-start" type="checkbox" value="1"
                                            name="published" role="switch" {{ @$data->published ? 'checked' : null }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="{{ @$data->id }}">
                        <div class="row mt-2 gy-4">
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="username" name="username"
                                        value="<?php echo @$data->username; ?>" autofocus />
                                    <label for="username">Tên đăng nhập</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="fullname" name="fullname"
                                        value="<?php echo @$data->fullname; ?>" autofocus />
                                    <label for="fullname">Họ và tên</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="email" name="email"
                                        value="<?php echo @$data->email; ?>" placeholder="<?php echo @$data->email; ?>" />
                                    <label for="email">E-mail</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <select id="selectTypeOpt" name="role_id" class="form-select color-dropdown select2">
                                        <option value="0">Chọn vai trò</option>
                                        @foreach (@$roles as $role)
                                            <option {{ $role->id == @$data->role_id ? 'selected' : null }}
                                                value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="selectTypeOpt">Vai trò</label>
                                </div>
                            </div>
                            @if (!@$data->id)
                                <div class="col-md-6">
                                    <div class="form-password-toggle">
                                        <div class="input-group input-group-merge">
                                            <div class="form-floating form-floating-outline">
                                                <input type="password" id="password" class="form-control" name="password"
                                                    placeholder="Mật khẩu của bạn" aria-describedby="password" />
                                                <label for="password">Mật khẩu</label>
                                            </div>
                                            <span class="input-group-text cursor-pointer"><i
                                                    class="mdi mdi-eye-off-outline"></i></span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal"
                                        data-bs-target="#modalCenter">
                                        Đổi mật khẩu
                                    </button>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>
            @if (@$data->id)
                <div class="card">
                    <h5 class="card-header fw-normal">Xoá bản ghi</h5>
                    <div class="card-body">
                        <div class="mb-3 col-12 mb-0">
                            <div class="alert alert-warning">
                                <h6 class="alert-heading mb-1">Bạn chắc chắn muốn xoá tài khoản?</h6>
                                <p class="mb-0">Hãy cẩn trọng, một khi đã xoá tài khoản thì sẽ không thể khôi phục lại
                                    được
                                </p>
                            </div>
                        </div>
                        <form id="formAccountDeactivation" method="POST" action="{{ route('admin.user.delete') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ @$data->id }}">
                            <div class="form-check mb-3 ms-3">
                                <input class="form-check-input" value="1" type="checkbox" name="accountDelete"
                                    id="accountActivation" />
                                <label class="form-check-label" for="accountActivation">Tôi đồng ý xác nhận xoá tài
                                    khoản</label>
                            </div>
                            <button type="submit" class="btn btn-danger">Xoá</button>
                        </form>
                    </div>
                </div>
            @endif
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
            <form action="{{ route('change_password') }}" method="POST" id="change-password">
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
    </script>
@endsection
