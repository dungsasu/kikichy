@extends('admin.contentNavLayout')

@php
    $title =  @$data->id ? 'Chi tiết' : 'Thêm' . ' ' . 'chương trình khuyến mãi';
@endphp
@section('title', $title)

@section('page-script')
    <script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light"></span> {{ @$data->id ? 'Chi tiết' : 'Thêm' }} chương trình khuyến mãi
        </h4>

        <div>
            <button type="submit" id="submit1" class="btn btn-primary me-2">Lưu</button>
            <button type="submit" id="submit2" class="btn btn-primary me-2">Lưu & Mới</button>
            <button type="submit" id="submit3" class="btn btn-primary me-2">Lưu & Thoát</button>
            <button type="reset" class="btn btn-outline-secondary"
                onclick="window.location.href = '{{ route($view.'.index') }}'">Đóng</button>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link waves-effect active" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-top-home" aria-controls="navs-top-home" aria-selected="true">
                        Thông tin chung
                    </button>
                </li> 
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-top-products" aria-controls="navs-top-products" aria-selected="false"
                        tabindex="-1">Sản phẩm</button>
                </li> 
                {{-- <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-top-news" aria-controls="navs-top-news" aria-selected="false"
                        tabindex="-1">Tin tức</button>
                </li>  --}}
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-top-seo" aria-controls="navs-top-seo" aria-selected="false"
                        tabindex="-1">Cấu hình SEO</button>
                </li> 
                <span class="tab-slider" style="left: 0px; width: 91.1719px; bottom: 0px;"></span>
            </ul>
            <div class="tab-content p-0">
                <div class="tab-pane fade show active" id="navs-top-home" role="tabpanel">
                    @include('admin.promotion.discount.info')
                </div> 
                <div class="tab-pane fade" id="navs-top-products" role="tabpanel">
                    @include('admin.promotion.discount.products')
                </div>
                {{-- <div class="tab-pane fade" id="navs-top-news" role="tabpanel">
                    @include('admin.promotion.discount.news')
                </div> --}}
                <div class="tab-pane fade" id="navs-top-seo" role="tabpanel">
                    @include('admin.promotion.discount.seo')
                </div> 
            </div>
        </div>
    </div>
@endsection
