@extends('admin.contentNavLayout')

@php
    $title = @$data->id ? 'Chi tiết' : 'Thêm' . ' ' . ' bộ lọc/ bảng TSKT sản phẩm';
@endphp
@section('title', $title)

@section('page-script')
    <script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light"></span> {{ @$data->id ? 'Chi tiết' : 'Thêm' }} bộ lọc/ bảng TSKT sản phẩm
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
                        <input type="hidden" name="info[id]" value="{{ @$data->id }}">
                        <div class="row mt-2 gy-4">
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="name" name="info[name]" value="{{ @$data->name }}" placeholder="Tên bảng"/>
                                    <label for="name">Tên bảng</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mt-4">
                                    <label for="published">Kích hoạt</label>
                                    <input form="formAccountSettings" id="published"
                                        class="form-check-input float-start" type="checkbox" value="1"
                                        name="info[published]" role="switch"
                                        {{ @$data->published || !@$data->id ? 'checked' : null }}>
                                </div>
                            </div>
                        </div>
                    </form>

                    <table class="table table-hover table-filter-items mt-4">
                        <thead>
                            <th>Tên hiển thị</th>
                            <th style="width: 320px">Danh mục</th>
                            <th>Kích hoạt</th>
                            <th>Nổi bật</th>
                            <th>Lọc</th> 
                            <th>Control</th>
                        </thead>
                        <tbody>
                            @if(!empty(@$data->items))
                                <input type="hidden" form="formAccountSettings" name="items[remove]" value="">
                                @foreach ($data->items as $i => $item)
                                    <tr class="newRow newRow{{ $i }}">
                                        <td>
                                            <input type="hidden" form="formAccountSettings" name="items[id][]" value="{{ $item->id }}">
                                            <input type="text" form="formAccountSettings" class="form-control" placeholder="Tên hiển thị" name="items[name][]" value="{{ $item->name }}" />
                                        </td>
                                        <td>
                                           <select form="formAccountSettings" name="items[filter_category_id][]" class="form-select select2 form-select-sm">
                                                <option value="0">---Danh mục---</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $item->filter_category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input class="form-check-input" type="checkbox" form="formAccountSettings" value="1" name="items[published][{{ $i }}]" {{ $item->published ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <input class="form-check-input" type="checkbox" form="formAccountSettings" value="1" name="items[is_outstanding][{{ $i }}]" {{ $item->is_outstanding ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <input class="form-check-input" type="checkbox" form="formAccountSettings" value="1" name="items[is_filter][{{ $i }}]" {{ $item->is_filter ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <a href="javscript:void(0)" class="removeItem" data-id="{{ $item->id }}">
                                                Xóa
                                            </a>
                                        </td> 
                                    </tr>
                                @endforeach
                            @else
                                <tr class="newRow newRow0">
                                    <td>
                                        <input type="text" form="formAccountSettings" class="form-control" placeholder="Tên hiển thị" name="items[name][]" value="" />
                                    </td>
                                    <td>
                                        <select form="formAccountSettings" name="items[filter_category_id][]" class="form-select select2 form-select-sm">
                                            <option value="0">---Danh mục---</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-check-input" type="checkbox" form="formAccountSettings" value="1" name="items[published][0]" checked>
                                    </td>
                                    <td>
                                        <input class="form-check-input" type="checkbox" form="formAccountSettings" value="1" name="items[is_outstanding][0]">
                                    </td>
                                    <td>
                                        <input class="form-check-input" type="checkbox" form="formAccountSettings" value="1" name="items[is_filter][0]">
                                    </td>
                                    <td>
                                        <a href="javscript:void(0)" class="removeItem" data-id="0">
                                            Xóa
                                        </a>
                                    </td> 
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="text-center mt-4">
                        <a href="javascript:void(0)" id="addItem">Thêm trường</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('push_script')
    <script>
        let exist = {{ !empty(@$data->items) ? count($data->items) : 1 }}
        let remove = []

        const newRow = `
            <tr class="newRow newRow${exist}">
                <td>
                    <input type="text" form="formAccountSettings" class="form-control" placeholder="Tên hiển thị" name="items[name][]" value="" />
                </td>
                <td>
                   <select form="formAccountSettings" name="items[filter_category_id][]" class="form-select select2 form-select-sm">
                        <option value="0">---Danh mục---</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input class="form-check-input" type="checkbox" form="formAccountSettings" value="1" name="items[published][${exist}]" checked>
                </td>
                <td>
                    <input class="form-check-input" type="checkbox" form="formAccountSettings" value="1" name="items[is_outstanding][${exist}]">
                </td>
                <td>
                    <input class="form-check-input" type="checkbox" form="formAccountSettings" value="1" name="items[is_filter][${exist}]">
                </td>
                <td>
                    <a href="javscript:void(0)" class="removeItem" data-id="0">
                        Xóa
                    </a>
                </td> 
            </tr>
        `

        $('#addItem').click(function(e){
            e.preventDefault()

            $('.table-filter-items tbody').append(newRow)
            $('.select2').select2()
 
            exist++
        })

        $(document).on('click', '.removeItem', function(e){
            e.preventDefault()
            let id = $(this).data('id')

            let row = $(this).closest(`.newRow`)
            row.remove()

            if (id != 0) {
                remove.push(id)
                $(`input[name="items[remove]"]`).val(remove)
            }        
             
        })
    </script>
@endpush
