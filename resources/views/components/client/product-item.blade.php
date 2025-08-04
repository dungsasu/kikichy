<div class="product-item-layout {{ @$className }}" data-id="{{ $item->id }}">
    <a href="{{ @$item->href }}" class="product-image" title="{{ @$item->name }}">
        <img src="{{ asset(@$item->image) }}" alt="{{ @$item->name }}" class="img-fluid" onerror="this.src='{{ asset('img/no-image.png') }}'">
        {{-- <div class="installment">Trả góp 0%</div> --}}
    </a>
    
    <div class="product-info">
        <a href="{{ @$item->href }}" class="product-name" title="{{ @$item->name }}">
            {{ @$item->name }} 
        </a>

        {{-- <div class="product-price">
            @if ($item->price_old > $item->price_public && $item->price_old > 0 && $item->price_public > 0)
                <del>{{ $item->price_old_format }}</del>
                <span>-{{ round( (1 - $item->price_public / $item->price_old) * 100) }}%</span> 
            @endif
        </div> --}}

        <div class="product-public-price">
            {{-- @if ($item->quantity > 0)  --}}
                {{ $item->price_public_format ?: 'Liên hệ' }}
            {{-- @else
                Tạm hết hàng
            @endif --}}
        </div>

        @if ($item->attributes && count($item->attributes) > 1)
            <div class="product-attribute">
                @foreach ($item->attributes as $a => $attribute)
                    @if ($a < 4) 
                        <div class="attribute" style="background: {{ $attribute->color_code }};"></div>
                    @endif
                @endforeach
                @if (count($item->attributes) > 4)
                    <div class="attribute-more">+{{ count($item->attributes) - 4 }} màu</div>
                @endif
            </div>
        @endif

        @if ($item->versions_related)
            <div class="product-version">
                @foreach ($item->versions_related as $version)
                    <a href="{{ $version->href }}" title="{{ $version->name}}" class="{{ $item->id == $version->id ? 'active' : '' }}">
                        {{ $version->version }}
                    </a>
                @endforeach
            </div>
        @endif

        <a href="" class="product-compare">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.00016 1.33337C4.32683 1.33337 1.3335 4.32671 1.3335 8.00004C1.3335 11.6734 4.32683 14.6667 8.00016 14.6667C11.6735 14.6667 14.6668 11.6734 14.6668 8.00004C14.6668 4.32671 11.6735 1.33337 8.00016 1.33337ZM10.6668 8.50004H8.50016V10.6667C8.50016 10.94 8.2735 11.1667 8.00016 11.1667C7.72683 11.1667 7.50016 10.94 7.50016 10.6667V8.50004H5.3335C5.06016 8.50004 4.8335 8.27337 4.8335 8.00004C4.8335 7.72671 5.06016 7.50004 5.3335 7.50004H7.50016V5.33337C7.50016 5.06004 7.72683 4.83337 8.00016 4.83337C8.2735 4.83337 8.50016 5.06004 8.50016 5.33337V7.50004H10.6668C10.9402 7.50004 11.1668 7.72671 11.1668 8.00004C11.1668 8.27337 10.9402 8.50004 10.6668 8.50004Z" fill="#2D52A0"/>
            </svg>
            So sánh
        </a>
    </div>
    
    {{ @$slot }}
</div> 