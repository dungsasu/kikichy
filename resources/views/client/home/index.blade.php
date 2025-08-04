@extends('client.layout')

@section('title', $config['title'])

@section('style_page')
    <link rel="stylesheet" href="{{ asset(mix('assets/client/css/home.css')) }}">
@endsection

@section('script_page')
    <script src="{{ asset(mix('assets/client/js/home.js')) }}"></script>
@endsection

@section('layoutContent')
    <!-- Banner Slider -->
    @if (!empty($banners) && $banners->count() > 0)
        <div class="banner-slider-section position-absolute">
            <div class="banner-carousel owl-carousel owl-theme">
                @foreach ($banners as $banner)
                    <div class="banner-item">
                        @if ($banner->href)
                            <a href="{{ $banner->href }}" target="{{ $banner->target ?? '_self' }}">
                        @endif
                        <div class="banner-image">
                            <img src="{{ asset($banner->image) }}" alt="{{ $banner->name }}" class="img-fluid">
                        </div>
                        @if ($banner->href)
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Navigation Arrows -->
            <div class="banner-nav">
                <button class="banner-prev">
                    <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.75998 0.906738L1.66665 9.00007L9.75998 17.0934M24.3333 9.00007H1.89331" stroke="black"
                            stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                </button>
                <button class="banner-next">
                    <svg width="26" height="18" viewBox="0 0 26 18" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.24 0.906738L24.3334 9.00007L16.24 17.0934M1.66669 9.00007H24.1067" stroke="black"
                            stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                </button>
            </div>
        </div>
    @endif

    <main class="main-content">
        <!-- Nội dung khác của trang chủ -->

    </main>
@endsection
