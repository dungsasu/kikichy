<form id="formAccountSettings" method="POST" action={{ route($view . '.save') }} enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ @$data->id }}" />
    @csrf
    <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-4">
            <div class="col-md-4">
                <x-choose-file title="Thay ảnh đại diện" :type="'Images'" id="image" :dataComponent="@$data"
                    field="image" />
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
            @if (isset($types) && count($types) > 0)
            <select name="send_to" class="form-select select2 form-select-sm">
                <option value="0">---Gửi đến---</option>
                @foreach ($types as $item)
                @if (@$item->value == $data->send_to && @$data->send_to)
                <option
                    {{ @$item->value == $data->send_to ? 'selected' : null }}
                    value="{{ $item->value }}">{!! $item->label !!}</option>
                @else
                <option value="{{ $item->value }}">{!! $item->label !!}</option>
                @endif
                @endforeach
            </select>
            @endif
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
