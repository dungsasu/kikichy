<a href="" class="compare-control-btn compare-remove {{ count(session('compare', [])) ? '' : 'disable' }}">Xóa tất cả</a>
            <a href="{{ route('client.compare.index') }}" class="compare-control-btn compare-action {{ count(session('compare', [])) > 1 ? '' : 'disable' }}">So sánh ngay</a>
            <a href="" class="toggle-offcanvas-compare">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8.47 10.74L12 14.26L15.53 10.74M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>