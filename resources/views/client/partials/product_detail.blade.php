<div class="left">
    @if (!@empty($colors[0]->images))
        <div class="nav-carousel">
            <ul>
                <li class="nav-item" data-position="0">
                    <img src="{{ $data->image }}" alt="{{ $data->image }}">
                </li>
                @foreach ($colors[0]->images as $key => $image)
                    <li class="nav-item" data-position="{{ $key + 1 }}">
                        <img src="{{ @$image->image }}" alt="{{ @$image->image }}">
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="nav-carousel">
            <ul>
                <li class="nav-item" data-position="0">
                    <img src="{{ @$data->image }}" alt="{{ @$data->image }}">
                </li>
            </ul>
        </div>
    @endif
    @if (!@empty($colors[0]->images))
        <div class="prd_carousel_wrapper" style="width: calc(100% - 76px)">
            <div class="owl-carousel owl-theme main-carousel-modal">
                <div class="item-main">
                    <img src="{{ @$data->image }}" alt="{{ $data->image }}">
                </div>
                @foreach ($colors[0]->images as $key => $image)
                    <div class="item-main">
                        <img src="{{ $image->image }}" alt="{{ $image->image }}">
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="prd_carousel_wrapper" style="width: calc(100% - 76px)">
            <div class="owl-carousel owl-theme main-carousel">
                <div class="item-main">
                    <img src="{{ @$data->image }}" alt="{{ @$data->name }}">
                </div>
            </div>
        </div>
    @endif
</div>
<div class="right">
    <img class="logo_hotsale" style="width: 25%" src="{{ asset('/images/icon/Status.svg') }}" alt="status" />
    <a href="{{ route('client.product', ['alias' => @$data->alias, 'category' => @$data->category->alias]) }}"
        class="h1 mt-3 title-product">
        {{ @$data->name }}
    </a>
    {{-- <span class="des-title">Mỏng nhẹ, năng động, thoáng mát</span>
    <div class="bst mt-2 mb-2">
        <span style="color: #757575">BST: </span>
        <span class=""> DMC FASHION SHOW | SS'24 collection Xuân xanh </span>
    </div> --}}
    {{-- <div class="d-flex rating_block">
        <div class="mt-3 mb-3 d-flex">
            <div data-point="0" class="my-rating-8"></div>
            <span class="mt-1 ms-2">0</span>
        </div>
    </div> --}}
    <div class="public_price_row">
        <div class="sale_price">
            {{ @$data->price }}
        </div>
        <div class="old_price">
            {{ @$data->price_old }}
        </div>
        {{-- <div class="discount">
            0<span>%</span>
        </div> --}}
    </div>
    @if (!empty($colors))
        <div class="d-flex mt-2" style="gap: 15px">
            @foreach ($colors as $key => $color)
                @php
                    $isAvailable = array_key_exists($color->alias, $availableColorsSizes);
                @endphp
                <div data-id-product="{{ $data->id }}" data-color-name="{{ $color->name }}"
                    data-fcolor={{ $color->alias }} data-color-code="{{ $color->code }}"
                    data-color-id="{{ $color->color_id }}" data-default-color={{ $key == 0 ? '1' : '0' }}
                    class="color-item-outside {{ $key == 0 ? 'active' : null }}">
                    <div class="color-item-inside" style="background-color: {{ $color->code }};"></div>
                </div>
            @endforeach
        </div>
    @endif
    @if (!empty($sizes))
        <div class="mt-3 d-flex justify-content-between">
            <span style="font-weight: 300">@translate('Kích thước'):</span>
            {{-- <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal"
            style="color: #2476FF; font-weight: 300;">Giúp
            bạn chọn size</a> --}}
        </div>
        @php
            if (!empty($colors) && array_key_exists($colors[0]->alias, $availableColorsSizes)) {
                if (count($availableColorsSizes[$colors[0]->alias]) > 0) {
                    $availableColorsSizes = reset($availableColorsSizes);
                }
            } else {
                $availableColorsSizes = [];
            }
        @endphp
        <div class="d-flex mt-3 size-available" style="gap: 10px">
            @php
                $firstAvailableSize = null;
            @endphp
            @foreach ($sizes as $key => $size)
                @php
                    if (
                        in_array(strtoupper($size->alias), array_map('strtoupper', $availableColorsSizes)) &&
                        !$firstAvailableSize
                    ) {
                        $firstAvailableSize = strtoupper($size->alias);
                    }
                @endphp

                <span
                    @unless (in_array(strtoupper($size->alias), $availableColorsSizes))
                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="@translate('Hết hàng')"
                @endunless
                    data-size-name="{{ @$size->name }}" data-size-fname="{{ @$size->alias }}"
                    class="size-item {{ strtoupper($size->alias) == $firstAvailableSize ? 'active' : null }} {{ !in_array(strtoupper($size->alias), $availableColorsSizes) ? 'item-disabled' : '' }}">
                    {{ @$size->name }}
                </span>
            @endforeach
        </div>
    @endif
    <div class="d-flex justify-content-between">
        <div class="change-quantity col">
            <button class="subtract">
                <img src="/images/icon/subtract.svg" alt="subtract">
            </button>
            <input class="number-only" id="quantity-order" type="text" value="1">
            <button class="plus">
                <img src="/images/icon/plus.svg" alt="plus">
            </button>
        </div>
        <div class="add_to_cart_wrapper" style="width: 70%">
            <div class="wrapper-button-add-cart ps-3" style="width: 100%">
                @if (count($availableColorsSizes) > 0)
                    <div class="ps-4">
                        <button class="btn-add-to-cart" data-product-id="{{ $data->id }}"
                            data-product-price="{{ $data->price }}" data-product-name="{{ $data->name }}">
                            @translate('Thêm vào giỏ hàng')
                        </button>
                    </div>
                @else
                    <button class="btn-add-to-cart-out-of-stock" data-product-id="{{ $data->id }}"
                        data-product-price="{{ $data->price }}" data-product-name="{{ $data->name }}">
                        @translate('Hết hàng')
                    </button>
                @endif
            </div>
        </div>
    </div>
    @if ($data->inventory > 0)
        @if (count($availableColorsSizes) > 0)
            <div class="group_btn_popup_prd">
                <div class="buynow_wrapper" data-link-payment="{{ route('client.pay_info') }}"
                    style="width: 100%; margin-left: 0" data-product-id="{{ $data->id }}"
                    data-product-price="{{ $data->price }}" data-product-name="{{ $data->name }}">
                    <button class="">@translate('MUA NGAY')</button>
                </div>
            </div>
        @endif
    @endif
    @if (@$data)
        <div class="link_to_detail">
            <a href="{{ route('client.product', ['alias' => @$data->alias, 'category' => @$data->category->alias]) }}">
                @translate('Xem chi tiết')
                <img src="{{ asset('img/default_img/chevron-right.svg') }}" alt="">
            </a>
        </div>
    @endif
</div>
<script>
    $(".main-carousel-modal").owlCarousel({
        loop: false,
        margin: 10,
        nav: false,
        items: 1,
        autoplay: true,
        slideSpeed: 300,
        animateOut: 'animate__fadeOutDown',
        animateIn: 'animate__fadeInDown',
    })
    var owl = $('.main-carousel-modal');
    owl.on('changed.owl.carousel', function(event) {
        console.log(event);
        let item = event.item.index - 2;
        $(".nav-item").removeClass('active');
        $(`.nav-item[data-position=${item}]`).addClass('active');
    })
    $(document).on('click', '.nav-item', function() {
        let position = $(this).data('position');
        owl.trigger('to.owl.carousel', [position, 1000]);
        $(".nav-item").removeClass('active');
        $(this).addClass('active');
    })
    $(document).on('hover', '.nav-item', function() {
        owl.trigger('stop.owl.autoplay');
    })
</script>
