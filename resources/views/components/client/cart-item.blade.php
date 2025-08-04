<div class="cart-item-layout {{ @$className }}" data-hash="{{ $item->hash }}">
    <div class="cart-item-image">
        <a href="{{ @$item->href }}" title="{{ $item->name }}" class="img">
            <img src="{{ $item->image }}" alt="{{ $item->name }}" class="img-fluid" onerror="this.src='{{ asset('img/no-image.png') }}'">
        </a>
        
        <div class="cart-item-title">
            <a href="{{ @$item->href }}" title="{{ $item->name }}" class="name">
                {{ $item->name }}
            </a> 
            @if ($item->attribute_name)
                <div class="attribute-name">{{ $item->attribute_name }}</div>
            @endif 
        </div>
    </div>   

    <div class="cart-item-info-control">
        <div class="cart-item-info">
            <div class="cart-item-title">
                <a href="{{ $item->href }}" title="{{ $item->name }}" class="name">
                    {{ $item->name }}
                </a> 
                @if ($item->attribute_name)
                    <div class="attribute-name">{{ $item->attribute_name }}</div>
                @endif 
            </div>
            <div class="cart-item-price">
                @if ($item->price_old && $item->price_old > $item->price_public)
                    <div class="price">
                        <del>{{ $item->price_old_format }}</del>
                        <div class="percent">
                            -{{ round( (1 - $item->price_public / $item->price_old) * 100) }}%
                        </div>
                    </div>
                @endif
                <div class="price-public">
                    {{ @$item->price_public_format }}
                </div>
            </div>
        </div>
        <div class="cart-items-controls">
            <div class="cart-item-sub-add">
                <a href="" class="cart-item-btn cart-item-sub">
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect y="4" width="10" height="2" rx="1" fill="currentColor"/>
                    </svg>
                </a>
                <input type="text" value="{{ $item->quantity }}" class="form-control cart-item-quantity">
                <a href="" class="cart-item-btn cart-item-add">
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" width="2" height="10" rx="1" fill="currentColor"/>
                        <rect y="4" width="10" height="2" rx="1" fill="currentColor"/>
                    </svg>
                </a>
            </div>
            <a href="" class="cart-item-remove">Xóa</a>
        </div>
    </div>
</div>
