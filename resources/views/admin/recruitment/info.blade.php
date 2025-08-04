<form id="formAccountSettings" method="POST" action={{ route($view . '.save') }} enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ @$data->id }}" />
    @csrf
    <div class="card-body">
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
                <input class="form-control" type="number" id="ordering" name="ordering" 
                    value="{{ @$data->ordering ? @$data->ordering : @$maxOrdering }}"
                    placeholder="{{ @$data->ordering }}" type="number" />
                <label for="ordering">Thứ tự</label>
            </div>
        </div>
        <div class="col-md-6">
            @if (isset($provinces) && count($provinces) > 0)
            <select name="province_id" class="form-select select2 form-select-sm">
                <option value="0">---Tỉnh thành---</option>
                @foreach ($provinces as $province)
                @if (@$data->province_id == $province->code && @$province->code)
                <option
                    {{ @$data->province_id == $province->code && @$province->code ? 'selected' : null }}
                    value="{{ $province->code }}">{!! $province->name !!}</option>
                @else
                <option value="{{ $province->code }}">{!! $province->name !!}</option>
                @endif
                @endforeach
            </select>
            @endif
        </div>
    </div>
    <div class="col-md-12">
        <x-editor_v2 name="mo_ta_ngan" id="mo_ta_ngan" title="Mô tả ngắn" content="{{ @$data->mo_ta_ngan }}" />
    </div>
    <div class="col-md-12">
        <x-editor_v2 name="mo_ta_cong_viec" id="mo_ta_cong_viec" title="Mô tả công việc"
            content="{{ @$data->mo_ta_cong_viec }}" />
    </div>
    <div class="col-md-12">
        <x-editor_v2 name="quyen_loi" id="quyen_loi" title="Quyền lợi" content="{{ @$data->quyen_loi }}" />
    </div>

</form>
