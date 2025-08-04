<a href="" class="toggle-session-compare" id="toggle-session-compare"> 
    <x-client.compare.btn-bottom />
</a>

<div class="session-compare container" id="session-compare">
    <x-client.compare.list-bottom />
</div>

<div class="offcanvas offcanvas-end session-compare-offcanvas" tabindex="-1" id="offcanvasCompare" aria-labelledby="offcanvasCompareLabel">
    <div class="offcanvas-header flex-wrap">
        <h5 class="offcanvas-title" id="offcanvasCompareLabel">Chọn sản phẩm so sánh</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        <div class="w-100 mt-3 position-relative d-flex align-items-center">
            <input type="text" name="keyword" id="compare_keyword" class="form-control" placeholder="Nhập sản phẩm bạn muốn so sánh">
            <a href="" class="btn compare-search-btn">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.5 16.5L15 15M15.75 8.625C15.75 12.56 12.56 15.75 8.625 15.75C4.68997 15.75 1.5 12.56 1.5 8.625C1.5 4.68997 4.68997 1.5 8.625 1.5C12.56 1.5 15.75 4.68997 15.75 8.625Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </a>
        </div>
    </div>
    <div class="offcanvas-body">
        <div class="offcanvas-body-compare"></div>
    </div>
    <div class="offcanvas-footer">
        <div class="offcanvas-compare">
            <x-client.compare.list-offcanvas />
        </div>
        <div class="offcanvas-compare-control">
            <x-client.compare.btn-offcanvas />
        </div>
    </div>
</div>