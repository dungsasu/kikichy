<form action={{ route($view . '.save') }} id="formAccountSettings" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="info[id]" value="{{ @$data->id }}">
    <div class="row mt-2 gy-4">
        <div class="col-md-6">
            <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" id="name" name="info[name]" value="{{ @$data->name }}" />
                <label for="name">Tên</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" id="alias" name="info[alias]" value="{{ @$data->alias }}" />
                <label for="alias">Alias</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" id="ordering" name="info[ordering]" value="{{ @$data->ordering ?: 1 }}"
                    placeholder="" />
                <label for="ordering">Thứ tự</label>
            </div>
        </div>


        <div class="col-md-6 d-flex gap-3">
            <div class="form-floating form-floating-outline w-100">
                <input type="date" form="formAccountSettings" name="info[date_start]" id="date_start" value="{{ @$data->date_start }}" class="datetimepicker form-control">
                <label for="date_start">Ngày bắt đầu</label>
            </div>
            <div class="form-floating form-floating-outline w-100">
                <input type="date" form="formAccountSettings" name="info[date_end]" id="date_end" value="{{ @$data->date_end }}" class="datetimepicker form-control">
                <label for="date_end">Ngày kết thúc</label>
            </div>
        </div>

        <div class="col-md-6 d-flex gap-3 flex-wrap">
            <div class="form-check form-switch mt-4">
                <label for="publish">Kích hoạt</label>
                <input form="formAccountSettings" id="published" class="form-check-input float-start" type="checkbox"
                    value="1" name="info[published]" role="switch"
                    {{ @$data->published || @!$data->id ? 'checked' : null }}>
            </div>
            <div class="form-check form-switch mt-4">
                <label for="show_home_page">Hiển thị trang chủ</label>
                <input form="formAccountSettings" id="show_home_page" class="form-check-input float-start"
                    type="checkbox" value="1" name="info[show_home_page]" role="switch"
                    {{ @$data->show_home_page || @!$data->id ? 'checked' : null }}>
            </div>
        </div>

        <div class="col-md-6">
            <x-choose-file title="Ảnh" :type="'Images'" id="image" name="info[image]" :dataComponent="@$data"
                field="image" />
        </div>

        <div class="col-md-6">
            @if (isset($productCategories) && count($productCategories) > 0)
                <select name="product_category_id" class="form-select select2 form-select-sm">
                    <option value="0">---Danh mục sản phẩm---</option>
                    @foreach ($productCategories as $category)
                        @if (@$data->product_category_id == $category->id && @$data->id)
                            <option {{ @$data->product_category_id == $category->id && @$data->id ? 'selected' : null }}
                                value="{{ $category->id }}">{!! $category->treename !!}</option>
                        @else
                            <option value="{{ $category->id }}">{!! $category->treename !!}</option>
                        @endif
                    @endforeach
                </select>
            @endif
        </div>
    </div>
</form>

@push('push_style')
    
@endpush
@push('push_script') 
    
@endpush
