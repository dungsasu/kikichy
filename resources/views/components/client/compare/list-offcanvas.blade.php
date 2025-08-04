@foreach (session('compare', []) as $item)
                <div class="compare-item">
                    <div class="compare-add compare-added d-flex align-items-center justify-content-center">
                        <a href="{{ $item->href }}" class="compare-item-product">
                            <div>
                                <img src="{{ asset($item->image) }}" alt="" class="img-fluid" onerror="this.src='{{ asset('img/no-image.png') }}'">
                            </div>
                            <div>
                                {{ $item->name }}
                            </div>
                        </a>
                        <a href="" class="compare-remove" data-id="{{ $item->id }}">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 5L5 15M5 5L15 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
            @for ($i = 0; $i < 3 - count(session('compare', [])); $i++)
                <div class="compare-item">
                    <div class="compare-add d-flex align-items-center justify-content-center">
                        Sản phẩm so sánh
                    </div>
                </div>
            @endfor