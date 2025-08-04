@if ($prod->inventory > 0)
    @if (count($availableSizes) > 0)
        <div class="group_btn_popup_prd">
            <div class="buynow_wrapper" data-link-payment="{{ route('client.pay_info') }}"
                style="width: 100%; margin-left: 0" data-product-id="{{ $prod->id }}"
                data-product-price="{{ $prod->price }}" data-product-name="{{ $prod->name }}">
                <button class="">@translate('MUA NGAY')</button>
            </div>
        </div>
    @endif
@endif
