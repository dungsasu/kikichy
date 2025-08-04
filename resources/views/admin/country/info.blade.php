<form id="formAccountSettings" method="POST" action={{ route('admin.country.save') }} enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{ @$data->id }}" />
    @csrf
    <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-4">
            <div class="button-wrapper">
                <div class="form-check form-switch mt-4">
                    <label for="published">Kích hoạt</label>
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
                    placeholder="<?php echo @$data->name; ?>" autofocus required />
                <label for="name">Tên quốc gia</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" id="code" name="code" value="<?php echo @$data->code; ?>"
                    placeholder="<?php echo @$data->code; ?>" />
                <label for="code">Mã quốc gia</label>
            </div>
        </div>
    </div>
    <div class="row mt-2 gy-4">
        <div class="col-md-6">
            <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" id="alias" name="alias" value="<?php echo @$data->alias; ?>"
                    placeholder="<?php echo @$data->alias; ?>" />
                <label for="alias">Alias</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating form-floating-outline">
                <input class="form-control" type="number" id="ordering" name="ordering"
                    value="{{ @$data->ordering ? @$data->ordering : @$maxOrdering + 1 }}"
                    placeholder="{{ @$data->ordering ? @$data->ordering : @$maxOrdering + 1 }}" />
                <label for="ordering">Thứ tự</label>
            </div>
        </div>
    </div>
    <div class="pt-4">
        <button type="submit" class="btn btn-primary me-2">Lưu thay đổi</button>
        <button type="reset" class="btn btn-outline-secondary">Đặt lại</button>
    </div>
</form>
