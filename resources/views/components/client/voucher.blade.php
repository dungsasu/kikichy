<div>
    @if (empty($vouchers))
        <p class="text-center mt-3">@translate('Không có voucher nào')</p>
    @endif
    @foreach ($vouchers as $item)
        <div class="m-3 p-3 voucher">
            <p class="fw-bold text-uppercase mb-2">GIẢM {{ $item->discount }} VNĐ | Code: {{ $item->code }}</p>
            <p class="small mb-2">HSD: {{ date('d-m-Y', strtotime($item->date_expiration)) }}</p>
            <ul>
                <li>{{ $item->name }}</li>
            </ul>
            <div class="d-flex justify-content-between">
                <p></p>
                <p class="text-uppercase applyVoucher" data-code="{{ $item->code }}"
                    style="color: #2476FF; cursor: pointer">@translate('Áp dụng')</p>
            </div>
        </div>
    @endforeach
</div>

<style lang="scss">
    .voucher {
        box-shadow: -1px 1px 5px 0px rgba(0, 0, 0, 0.2);
        -webkit-box-shadow: -1px 1px 5px 0px rgba(0, 0, 0, 0.2);
        -moz-box-shadow: -1px 1px 5px 0px rgba(0, 0, 0, 0.2);
        transition: all 0.3s;

        &:hover {
            scale: 1.03
        }
    }
</style>
