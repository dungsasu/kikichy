@extends('admin.contentNavLayout')

@php
    $title = (@$data->id ? 'Chi tiết' : 'Thêm') . ' ' . 'khách hàng';
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
            <form id="formAccountSettings" method="POST" action={{ route('save_customer') }} enctype="multipart/form-data">
                <input type="hidden" name="id" value="{{ @$data->id }}" />
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
                            <div class="form-check form-switch mt-4">
                                <label for="publish">Kích hoạt</label>
                                <input form="formAccountSettings" id="published" class="form-check-input float-start"
                                    type="checkbox" value="1" name="published" role="switch"
                                    {{ @$data->published || @!$data->id ? 'checked' : null }}>
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
                            <label for="firstName">Tên</label>
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
                </div>
                <div class="col-md-12 mt-4">
                    <div class="form-floating form-floating-outline">
                        <textarea id="comment" style="height: 200px" name="comment" class="form-control" placeholder="Nhận xét" aria-label=""
                            aria-describedby="">{{ @$data->comment }}</textarea>
                        <label for="comment">Nhận xét</label>
                    </div>
                </div>
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
