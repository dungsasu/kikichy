@extends('admin.contentNavLayout')

@php
    $title = (@$data->id ? 'Chi tiết' : 'Thêm') . ' ' . 'bộ sưu tập';
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
            <form id="formAccountSettings" method="POST" action={{ route('save_collection') }}
                enctype="multipart/form-data">
                <input type="hidden" name="id" value="{{ @$data->id }}" />
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="name" name="name"
                                    value="{{ @$data->name }}" placeholder="" />
                                <label for="firstName">Tên bộ sưu tập</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="alias" name="alias"
                                    value="{{ @$data->alias }}" placeholder="" />
                                <label for="ordering">Alias</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <x-choose-file title="Thay ảnh đại diện" :type="'Images'" id="image" :dataComponent="@$data"
                                field="image" />
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                <div class="button-wrapper">
                                    <div class="form-check form-switch mt-4">
                                        <label for="publish">Kích hoạt</label>
                                        <input form="formAccountSettings" id="published"
                                            class="form-check-input float-start" type="checkbox" value="1"
                                            name="published" role="switch"
                                            {{ @$data->published || @!$data->id ? 'checked' : null }}>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="ordering" name="ordering"
                                    value="{{ @$data->ordering ? @$data->ordering : @$maxOrdering }}" placeholder="" />
                                <label for="ordering">Thứ tự</label>
                            </div>
                        </div>
                    </div>

                </div>
                <input type="hidden" name="id" value="{{ @$data->id }}">
            </form>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <div class="card-body">
                @include('admin.collection.product_related')
            </div>
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
