    @if (!@empty($data))
        <div class="nav-carousel">
            <ul>
                @if ($default_color)
                    <li class="nav-item" data-position="0">
                        <img src="{{ $prod->image }}" alt="{{ $prod->image }}">
                    </li>
                @endif
                @foreach ($data as $key => $image)
                    <li class="nav-item" data-position="{{ $default_color == 1 ? $key + 1 : $key }}">
                        <img src="{{ $image->image }}" alt="{{ $image->image }}">
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        @if ($default_color)
            <div class="nav-carousel">
                <ul>
                    <li class="nav-item" data-position="0">
                        <img src="{{ $prod->image }}" alt="{{ $prod->image }}">
                    </li>
                </ul>
            </div>
        @endif
    @endif
    @if (!@empty($data))
        <div class="main-carousel-wrap">
            <div id="gallery" class="owl-carousel owl-theme main-carousel">
                @if ($default_color)
                    <a href="{{ @$prod->image }}" class="item-main" data-fancybox="gallery">
                        <img src="{{ @$prod->image }}" alt="{{ $prod->image }}">
                    </a>
                @endif
                @foreach ($data as $key => $image)
                    <a href="{{ @$image->image }}" class="item-main" data-fancybox="gallery">
                        <img src="{{ $image->image }}" alt="{{ $image->image }}">
                    </a>
                @endforeach
            </div>
        </div>
    @else
        <div style="width: calc(100% - 76px)">
            <div class="owl-carousel owl-theme main-carousel">
                <div class="item-main">
                    <img src="{{ $prod->image }}" alt="{{ $prod->name }}">
                </div>
            </div>
        </div>
    @endif
