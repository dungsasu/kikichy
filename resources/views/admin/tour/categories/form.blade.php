@extends('admin.contentNavLayout')

@section('title', 'Chỉnh sửa danh mục sản phẩm')

@section('page-script')
    <script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
@endsection
@php
@endphp

@section('content')
    <div class="row">
        <div class="d-flex justify-content-between">
            <h4 class="py-3 mb-4">
                <span class="text-muted fw-light"></span> Danh mục Sản Phẩm
            </h4>
            <div class="mt-4 mb-4">
                <button type="submit" id="submit1" class="btn btn-primary me-2">Lưu</button>
                <button type="submit" id="submit2" class="btn btn-primary me-2">Lưu & Mới</button>
                <button type="submit" id="submit3" class="btn btn-primary me-2">Lưu & Thoát</button>
                <button type="reset" onclick="window.location.href='{{ route('admin.tour.categories.index') }}'"
                    class="btn btn-outline-secondary">Đóng</button>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card mb-4">
                <!-- Account -->
                <div class="card-body pt-2 mt-1">
                    <form action={{ route('admin.tour.categories.save') }} id="formAccountSettings" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ @$data->id }}">
                        <div class="row mt-2 gy-4">
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="name" name="name"
                                        value="{{ @$data->name }}" />
                                    <label for="firstName">Tên</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" name="alias" id="alias"
                                        value="{{ @$data->alias }}" />
                                    <label for="lastName">Alias</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="ordering" name="ordering"
                                        value="{{ @$data->ordering ? @$data->ordering : @$maxOrdering }}" placeholder="" />
                                    <label for="ordering">Thứ tự</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <select name="parent_id" class="form-select select2 form-select-sm">
                                    <option value="0">---Danh mục cha---</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ @$data->parent_id == $category->id ? 'selected' : null }}>
                                            {!! $category->treename !!}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <x-choose-file title="Ảnh đại diện" :type="'Images'" id="image"
                                    name="image" :dataComponent="@$data" field="image" />
                            </div>
                           
                            <div class="col-md-6">
                                <select name="filter_table_id" class="form-select select2 form-select-sm">
                                    <option value="0">---Bảng thông số kỹ thuật---</option>
                                    @foreach ($filterTable as $item)
                                        <option value="{{ $item->id }}" {{ @$data->filter_table_id == $item->id ? 'selected' : null }}>
                                            {!! $item->name !!}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mt-4">
                                    <label for="published">Kích hoạt</label>
                                    <input form="formAccountSettings" id="published" class="form-check-input float-start"
                                        type="checkbox" value="1" name="published" role="switch"
                                        {{ @$data->published || @!$data->id ? 'checked' : null }}>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <x-editor_v2 name="sale_brief" id="sale_brief" title="Khuyến mại"
                                    content="{{ @$data->sale_brief }}" />
                            </div>
                            <div class="col-md-12">
                                <x-editor_v2 name="description" id="description" title="Thông tin"
                                    content="{{ @$data->description }}" />
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>

        </div>
    </div>
@endsection
