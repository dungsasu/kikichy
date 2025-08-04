<form id="formAccountSettings" method="POST" action={{ route('admin.news.save') }} enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ @$data->id }}" />
    @csrf
    <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-4">
            <div class="col-md-4">
                <x-choose-file title="Thay ảnh đại diện" :type="'Images'" id="image" :dataComponent="@$data"
                    field="image" />
            </div>
            <div class="button-wrapper">

                <div class="form-check form-switch mt-4">
                    <label for="publish">Kích hoạt</label>
                    <input form="formAccountSettings" id="published" class="form-check-input float-start"
                        type="checkbox" value="1" name="published" role="switch"
                        {{ @$data->published || @!$data->id ? 'checked' : null }}>
                </div>
                <div class="col">
                    <div class="form-check form-switch mt-4">
                        <label for="hot">Hot</label>
                        <input form="formAccountSettings" id="hot" class="form-check-input float-start"
                            type="checkbox" value="1" name="hot" role="switch"
                            {{ @$data->hot ? 'checked' : null }}>
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
    <div class="row mt-2 gy-4">
        <div class="col-md-6">
            <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" id="ordering" name="ordering"
                    value="{{ @$data->ordering ? @$data->ordering : @$maxOrdering }}"
                    placeholder="{{ @$data->ordering }}" />
                <label for="ordering">Sắp xếp</label>
            </div>
        </div>
        <div class="col-md-6">
            <select name="category_id" class="form-select select2 form-select-sm">
                <option value="0">---Danh mục---</option>
                @foreach ($categories as $category)
                    @if (@$data->category_id == $category->id && @$data->id)
                        <option {{ @$data->category_id == $category->id && @$data->id ? 'selected' : null }}
                            value="{{ $category->id }}">{{ $category->name }}</option>
                    @else
                        <option value="{{ $category->id }}">{!! $category->treename !!}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-4">
        <div class="form-floating form-floating-outline">
            <textarea id="summary" name="summary" style="height: 100px" class="form-control" placeholder="Tóm tắt" aria-label=""
                aria-describedby="">{{ @$data->summary }}</textarea>
            <label for="summary">Tóm tắt</label>
        </div>
    </div>
    <div class="mt-4">
        <div class="">
            <div class="col-md-12">
                <x-editor_v2 name="description" id="description" title="Mô tả" content="{{ @$data->description }}" />
            </div>
        </div>
    </div>
</form>
