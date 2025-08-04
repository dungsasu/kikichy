@extends('admin.contentNavLayout')

@php
$title = (@$data->id ? 'Chi tiết' : 'Thêm') . ' ' . 'trình diễn thời trang';
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
        <form id="formAccountSettings" method="POST" action={{ route($view . '.save') }} enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ @$data->id }}" />
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <x-choose-file title="Thay ảnh đại diện" :type="'Images'" id="image" field="image" :dataComponent="@$data" />
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <div class="button-wrapper">
                                <div class="form-check form-switch mt-4">
                                    <label for="publish">Kích hoạt</label>
                                    <input form="formAccountSettings" id="published" class="form-check-input float-start"
                                        type="checkbox" value="1" name="published" role="switch"
                                        {{ @$data->published || @!$data->id ? 'checked' : null }}>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="id" value="{{ @$data->id }}">
            <div class="row mt-2 gy-4">
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="name" name="name" value="<?php echo @$data->name; ?>"
                            placeholder="<?php echo @$data->name; ?>" autofocus />
                        <label for="firstName">Tiêu đề</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating form-floating-outline">
                        <input class="form-control" type="text" id="alias" name="alias" value="<?php echo @$data->alias; ?>"
                            placeholder="<?php echo @$data->alias; ?>" />
                        <label for="email">Alias</label>
                    </div>
                </div>
            </div>
        </form>
        <div class="mt-3">
            <x-gallery name="fashion" href :data-component="@$data->images" field="image" type="Images"></x-gallery>
        </div>
    </div>
</div>

@endsection

