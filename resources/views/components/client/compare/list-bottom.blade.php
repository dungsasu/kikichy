<div class="compare-wrapper">
    @foreach (session('compare', []) as $item)
        <div class="compare-item">
            <div class="compare-added">
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
                        <path d="M15 5L5 15M5 5L15 15" stroke="#A3A3A3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>
    @endforeach
    @for ($i = 0; $i < 3 - count(session('compare', [])); $i++)
        <div class="compare-item">
            <a href="" class="compare-add" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCompare" aria-controls="offcanvasCompare">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="48" height="48" rx="12" fill="#F3F4F6"/>
                    <path d="M16 24H32" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M24 32V16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Thêm sản phẩm
            </a>
        </div>
    @endfor
    <div class="compare-item">
        <div class="compare-control">
            <a href="" class="compare-control-btn compare-remove {{ count(session('compare', [])) ? '' : 'disable' }}">Xóa tất cả</a>
            <a href="{{ route('client.compare.index') }}" class="compare-control-btn compare-action {{ count(session('compare', [])) > 1 ? '' : 'disable' }}">So sánh ngay</a>
        </div>
    </div>
    <a href="" class="toggle-session-compare">
        Thu gọn
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11.6199 5.22083L7.81655 9.02416C7.36738 9.47333 6.63238 9.47333 6.18322 9.02416L2.37988 5.22083" stroke="#2E2E38" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
</div>