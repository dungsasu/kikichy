@extends('admin.contentNavLayout')

@php
    $title = (@$data->id ? 'Chi tiết' : 'Thêm') . ' ' . 'sản phẩm';
@endphp
@section('title', $title)

@section('page-script')
    <script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
@endsection
@php
@endphp

@section('content')
    <div class="row">
        <div class="d-flex justify-content-between action-save">
            <h4 class="py-3 mb-0">
                <span class="text-muted fw-light"></span> {{ @$data->id ? 'Chi tiết' : 'Thêm' }} sản phẩm
            </h4>
            <div class="mt-4 mb-4"> 
                <button type="submit" id="submit1" class="btn btn-primary me-2">Lưu</button>
                <button type="submit" id="submit2" class="btn btn-primary me-2">Lưu & Mới</button>
                <button type="submit" id="submit3" class="btn btn-primary me-2">Lưu & Thoát</button>
                <button type="reset" onclick="window.location.href='{{ route($view . '.index') }}'"
                    class="btn btn-outline-secondary">Đóng</button>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <span class="btn btn-outline-primary">Bạn đang ở ngôn ngữ "{{ App::getLocale() }}"</span>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-home" aria-controls="navs-top-home" aria-selected="true">Thông tin
                            chung</button>
                    </li>
                    {{-- <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="false"
                            tabindex="-1">Kích thước & màu sắc</button>
                    </li> --}}
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-attributes" aria-controls="navs-top-attributes" aria-selected="false"
                            tabindex="-1">Phân loại</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-version" aria-controls="navs-top-version" aria-selected="false"
                            tabindex="-1">Sản phẩm cùng phân khúc</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-related" aria-controls="navs-top-related" aria-selected="false"
                            tabindex="-1">Sản phẩm mua kèm</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-messages" aria-controls="navs-top-messages" aria-selected="false"
                            tabindex="-1">Cấu hình SEO</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-filter" aria-controls="navs-top-filter" aria-selected="false"
                            tabindex="-1">Thông số kỹ thuật</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-news" aria-controls="navs-top-news" aria-selected="false"
                            tabindex="-1">Tin liên quan</button>
                    </li>
                    <span class="tab-slider" style="left: 0px; width: 91.1719px; bottom: 0px;"></span>
                </ul>
                <div class="tab-content p-0">
                    <div class="tab-pane fade show active" id="navs-top-home" role="tabpanel">
                        @include('admin.tour.info')
                    </div>
                    {{-- <div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
                        @include('admin.product.size_color')
                    </div> --}}
                    <div class="tab-pane fade" id="navs-top-attributes" role="tabpanel">
                        @include('admin.tour.attributes')
                    </div>
                    <div class="tab-pane fade" id="navs-top-messages" role="tabpanel">
                        @include('admin.tour.seo')
                    </div>
                    {{-- <div class="tab-pane fade" id="navs-top-version" role="tabpanel">
                        @include('admin.tour.tour_version')
                    </div> --}}
                    {{-- <div class="tab-pane fade" id="navs-top-related" role="tabpanel">
                        @include('admin.tour.tour_related')
                    </div> --}}
                    <div class="tab-pane fade" id="navs-top-filter" role="tabpanel">
                        @include('admin.tour.filter')
                    </div>
                    <div class="tab-pane fade" id="navs-top-news" role="tabpanel">
                        @include('admin.tour.news_related')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-script')
@endpush
