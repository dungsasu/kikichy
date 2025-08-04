<form action={{ route($view . '.save') }} id="formAccountSettings" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="info[id]" value="{{ @$data->id }}">
    <div class="row mt-2 gy-4">
        <div class="col-sm-12 col-md-3 d-flex flex-column gap-4">
            <div class="form-floating form-floating-outline w-100">
                <input class="form-control" type="text" id="name" name="info[name]" value="{{ @$data->name }}" />
                <label for="name">Tên</label>
            </div>
            <div class="form-floating form-floating-outline w-100">
                <input class="form-control" type="text" id="code" name="info[code]" value="{{ @$data->code }}" {{ @$data->id ? 'readonly' : '' }} />
                <label for="code">Mã voucher</label>
            </div>
        </div>

        <div class="col-sm-12 col-md-3 d-flex flex-column gap-4">
            <div class="form-floating form-floating-outline w-100">
                <input type="date" form="formAccountSettings" name="info[date_start]" id="date_start" value="{{ @$data->date_start }}" class="datetimepicker form-control">
                <label for="date_start">Ngày bắt đầu</label>
            </div>
            <div class="form-floating form-floating-outline w-100">
                <input type="date" form="formAccountSettings" name="info[date_end]" id="date_end" value="{{ @$data->date_end }}" class="datetimepicker form-control">
                <label for="date_end">Ngày kết thúc</label>
            </div>
        </div>

        <div class="col-sm-12 col-md-3 d-flex flex-column gap-4">
            <div class="form-floating form-floating-outline w-100 position-relative">
                <input class="form-control" type="text" id="price" name="info[price]" value="{{ format_money(@$data->price) }}" />
                <label for="price">Tiền giảm</label>
            </div> 
            <div class="form-floating form-floating-outline w-100">
                <input class="form-control" type="text" id="percent" name="info[percent]" value="{{ @$data->percent }}" />
                <label for="percent">hoặc % giảm</label>
            </div>
        </div>

        <div class="col-sm-12 col-md-3 d-flex flex-column gap-4">
            <div class="form-floating form-floating-outline w-100">
                <input class="form-control" type="text" id="min_price" name="info[min_price]" value="{{ format_money(@$data->min_price) }}" />
                <label for="min_price">Số tiền chi tối thiểu</label>
            </div>
            <div class="form-floating form-floating-outline w-100">
                <input class="form-control" type="number" id="quantity" name="info[quantity]" value="{{ @$data->quantity }}" />
                <label for="quantity">Số lượng có thể nhận</label>
            </div>
            <div class="form-floating form-floating-outline w-100">
                <input class="form-control" type="number" id="used" name="info[used]" value="{{ @$data->used ?: 0 }}" readonly disabled />
                <label for="used">Đã dùng</label>
            </div>
        </div> 

        <div class="col-sm-12 col-md-3 d-flex flex-column gap-4">
            <div class="form-check form-switch mt-4">
                <label for="publish">Kích hoạt</label>
                <input form="formAccountSettings" id="published" class="form-check-input float-start" type="checkbox"
                    value="1" name="info[published]" role="switch"
                    {{ @$data->published || @!$data->id ? 'checked' : null }}>
            </div> 
        </div> 
    </div>
</form>

@push('push_style')
    
@endpush
@push('push_script') 
    
@endpush
