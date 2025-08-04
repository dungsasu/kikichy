@extends('admin.contentNavLayout')

@php
    $title = @$data->id ? 'Chi tiết' : 'Thêm' . ' ' . 'danh mục thông số KT';
@endphp
@section('title', $title)

@section('page-script')
    <script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light"></span> {{ @$data->id ? 'Chi tiết' : 'Thêm' }} danh mục thông số KT
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
                    <form action={{ route($view . '.save') }} id="formAccountSettings" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ @$data->id }}">
                        <div class="row mt-2 gy-4">
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="name" name="name" value="{{ @$data->name }}" />
                                    <label for="firstName">Tên</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" name="alias" id="alias" value="{{ @$data->alias }}" />
                                    <label for="lastName">Alias</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <select name="group_id" class="form-select select2 form-select-sm">
                                    <option value="0">---Nhóm thông số KT---</option>
                                    @foreach ($group as $item)
                                        <option value="{{ $item->id }}" {{ @$data->group_id == $item->id ? 'selected' : null }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select name="type" class="form-select select2 form-select-sm">
                                    <option value="0">---Kiểu nhập---</option>
                                    <option value="string" {{ @$data->type == 'string' ? 'selected' : null }}>
                                        Chuỗi kỹ tự
                                    </option>
                                    <option value="textarea" {{ @$data->type == 'textarea' ? 'selected' : null }}>
                                        Textarea
                                    </option>
                                    <option value="single" {{ @$data->type == 'single' ? 'selected' : null }}>
                                        Chọn một
                                    </option>
                                    <option value="multiple" {{ @$data->type == 'multiple' ? 'selected' : null }}>
                                        Chọn nhiều
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="ordering" name="ordering" value="1" placeholder="" />
                                    <label for="ordering">Thứ tự</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mt-4">
                                    <label for="publish">Kích hoạt</label>
                                    <input form="formAccountSettings" id="published" class="form-check-input float-start"
                                        type="checkbox" value="1" name="published" role="switch"
                                        {{ @$data->published || @!$data->id ? 'checked' : null }}>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
