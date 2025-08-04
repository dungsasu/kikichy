@extends('admin.contentNavLayout')
@php
    $title = @$data->id ? 'Chi tiết' : 'Thêm' . ' ' . ' hình ảnh';
@endphp

@section('title', $title)

@section('page-script')
    <script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
@endsection


@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="py-3">
            {{ @$data->id ? 'Chi tiết' : 'Thêm' }} hình ảnh
        </h4>
        <div>
            <button type="submit" id="submit1" class="btn btn-primary me-2">Lưu</button>
            <button type="submit" id="submit2" class="btn btn-primary me-2">Lưu & Mới</button>
            <button type="submit" id="submit3" class="btn btn-primary me-2">Lưu & Thoát</button>
            <button type="reset" class="btn btn-outline-secondary" onclick="window.location.href = '{{ route($view . '.index') }}'">Đóng</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body pt-2 mt-1">
                    <form action={{ route($view . '.save') }} id="formAccountSettings" method="POST"
                        enctype="multipart/form-data">
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
                                <div class="form-floating form-floating-outline">
                                    <select id="selectTypeOpt" name="category_id" class="form-select color-dropdown select2">
                                        <option value="">--- Chọn danh mục ---</option>
                                        @if (isset($categories) && count($categories) > 0)
                                            @foreach ($categories as $category)
                                                <option {{ $category->id == @$data->category_id ? 'selected' : null }}
                                                    value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <label for="selectTypeOpt">Danh mục</label>
                                </div>
                            </div>
 
                            <div class="col-md-6">
                                <x-create-link name="href" :data-component="@$data->href" ></x-create-link>
                                
                                <div class="form-check form-switch mt-4">
                                    <label for="publish">Kích hoạt</label>
                                    <input form="formAccountSettings" id="published" class="form-check-input float-start"
                                        type="checkbox" value="1" name="published" role="switch"
                                        {{ @$data->published || @!$data->id ? 'checked' : null }}>
                                </div>
                                <div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <x-choose-file title="Ảnh" :type="'Images'" id="image" name="image" :dataComponent="@$data" field="image" />
                            </div>

                            
                        </div>
                    </form>
                    
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
@endsection
