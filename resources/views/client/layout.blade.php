<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="@translate('vi-vn')" lang="@translate('vi-vn')">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', $config['title'])</title>
    <meta name="description" content="@yield('description')" />
    <meta name="keywords" content="@yield('keywords')">
    <meta property="og:type" content="website" />
    <meta name=robots content="index, follow">
    <meta property="og:url" content="@yield('og_url')" />
    <meta property="og:title" content="@yield('og_title')" />
    <meta property="og:description" content="@yield('og_description')" />
    <meta property="og:image" content="@yield('og_image', asset($config['image_share']))" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name='dmca-site-verification' content='bm41NHFhbzFBZFhHZU5NY3dJclk3UHpod2tjMHJYVFcrOTRTek9ocjRqUT01' />
    <meta name="google-site-verification" content="VF9zmmBbPFBcgd39qas9MojAPTkR_vdmjKghEubBV-c" />
    <link rel="canonical" href="@yield('canonical')">
    <link rel="icon" type="image/png" href="{{ asset('/img/favicon/favicon-kikichy.ico') }}" />
    <link rel="stylesheet" href="{{ asset('assets/js/ui-5.0.36/dist/fancybox/fancybox.css') }}" />
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset(mix('assets/client/css/styles.css')) }}">
    @include('client/styles')
    @yield('style_page')
    @stack('push_style')

    {!! @$config['script_under_head'] != '<p>&nbsp;</p>' ? html_entity_decode(@$config['script_under_head']) : '' !!}
</head>
<script>
    window.editors = {};
    window.ace_editor = {};
</script>

<body>
    {!! @$config['script_body'] != '<p>&nbsp;</p>' ? html_entity_decode(@$config['script_body']) : '' !!}

    <!-- Header Top -->
    <div class="layout-relative position-relative">
        <div class="nav-container p-4 bg-white">
            @if (
                !Auth::guard('members')->check() ||
                    (!Str::startsWith(Route::currentRouteName(), 'client.business') &&
                        !in_array(Route::currentRouteName(), ['client.login_business', 'client.register_business'])))
                <!-- Header-top hiển thị khi chưa đăng nhập hoặc không ở trong module thành viên -->
                <div class="header-top d-flex justify-content-between align-items-center">
                    <div class="header-top-left d-flex align-items-center gap-3">
                        <div class="total-mem d-flex gap-1 align-items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16 16.66C16 14.86 14.21 13.4 12 13.4C9.78997 13.4 7.99997 14.86 7.99997 16.66M21.08 8.58003V15.42C21.08 16.54 20.48 17.58 19.51 18.15L13.57 21.58C12.6 22.14 11.4 22.14 10.42 21.58L4.47997 18.15C3.50997 17.59 2.90997 16.55 2.90997 15.42V8.58003C2.90997 7.46003 3.50997 6.41999 4.47997 5.84999L10.42 2.42C11.39 1.86 12.59 1.86 13.57 2.42L19.51 5.84999C20.48 6.41999 21.08 7.45003 21.08 8.58003ZM14.33 8.66998C14.33 9.95681 13.2868 11 12 11C10.7131 11 9.66997 9.95681 9.66997 8.66998C9.66997 7.38316 10.7131 6.34003 12 6.34003C13.2868 6.34003 14.33 7.38316 14.33 8.66998Z"
                                    stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span class="total-mem-text">2500 thành viên tham gia</span>
                        </div>
                        <div class="info-mail d-flex gap-1 align-items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17 9L13.87 11.5C12.84 12.32 11.15 12.32 10.12 11.5L7 9M17 20.5H7C4 20.5 2 19 2 15.5V8.5C2 5 4 3.5 7 3.5H17C20 3.5 22 5 22 8.5V15.5C22 19 20 20.5 17 20.5Z"
                                    stroke="#3B3B3B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span class="info-mail-text">Email: <a
                                    href="mailto:{{ $config['admin-email'] }}">{{ $config['admin-email'] }}</a></span>
                        </div>
                    </div>
                    <div class="header-top-right d-flex align-items-center gap-2">
                        <div class="hotline d-flex gap-1 align-items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M21.04 20.44L21.5954 20.944L21.5957 20.9436L21.04 20.44ZM19.4 21.62L19.1156 20.926L19.1115 20.9277L19.4 21.62ZM10.75 19.29L10.3076 19.8956L10.3086 19.8963L10.75 19.29ZM7.47 16.49L6.93709 17.0178L6.94224 17.0229L7.47 16.49ZM4.68 13.22L4.07114 13.658L4.07368 13.6614L4.68 13.22ZM2.72 9.81L2.02877 10.101L2.0297 10.1032L2.72 9.81ZM2.36 4.61L3.05637 4.88855L3.05792 4.88459L2.36 4.61ZM3.51 2.94L4.02467 3.48555L4.03046 3.48008L4.03614 3.47449L3.51 2.94ZM6.4 2.18L6.07541 2.85622L6.08571 2.86097L6.4 2.18ZM7.07 2.74L6.45332 3.16694L6.45831 3.17398L7.07 2.74ZM9.39 6.01L8.7783 6.44399L8.78135 6.44823L9.39 6.01ZM9.79 6.71L9.09574 6.99401L9.10064 7.00544L9.79 6.71ZM9.72 8.03L9.07935 7.64004L9.07305 7.6504L9.06708 7.66096L9.72 8.03ZM9.16 8.74L8.62967 8.20967L8.62454 8.2148L8.61951 8.22003L9.16 8.74ZM8.4 9.53L8.93033 10.0603L8.93546 10.0552L8.94049 10.05L8.4 9.53ZM8.27 10.16L7.54239 10.3419L7.55275 10.3833L7.56775 10.4233L8.27 10.16ZM8.35 10.36L7.63849 10.5972L7.6596 10.6605L7.69158 10.7191L8.35 10.36ZM9.28 11.64L8.70742 12.1245L8.71287 12.1308L9.28 11.64ZM10.73 13.22L10.1946 13.7453L10.1996 13.7503L10.2046 13.7553L10.73 13.22ZM12.32 14.69L11.8346 15.2617L11.8355 15.2625L12.32 14.69ZM13.61 15.61L13.2591 16.2728L13.2944 16.2915L13.3315 16.3064L13.61 15.61ZM13.79 15.69L13.4946 16.3794L13.5105 16.3862L13.5267 16.3922L13.79 15.69ZM14.45 15.56L13.9232 15.0262L13.9197 15.0297L14.45 15.56ZM15.21 14.81L15.7368 15.3438L15.7403 15.3403L15.21 14.81ZM15.93 14.25L16.2769 14.9149L16.2988 14.9035L16.32 14.8906L15.93 14.25ZM17.95 14.56L18.3842 13.9485L18.378 13.9441L18.3717 13.9398L17.95 14.56ZM21.26 16.91L20.8258 17.5216L20.8331 17.5266L21.26 16.91ZM21.81 17.55L22.5064 17.2715L22.5016 17.2596L22.4965 17.2479L21.81 17.55ZM17.75 9C17.75 9.41421 18.0858 9.75 18.5 9.75C18.9142 9.75 19.25 9.41421 19.25 9H17.75ZM17.33 6.73L16.7801 7.24004L16.7817 7.24174L17.33 6.73ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5C14.25 5.91421 14.5858 6.25 15 6.25V4.75ZM21.25 9C21.25 9.41421 21.5858 9.75 22 9.75C22.4142 9.75 22.75 9.41421 22.75 9H21.25ZM15 1.25C14.5858 1.25 14.25 1.58579 14.25 2C14.25 2.41421 14.5858 2.75 15 2.75V1.25ZM21.97 18.33H21.22C21.22 18.5775 21.1653 18.8383 21.0418 19.0997L21.72 19.42L22.3982 19.7403C22.6147 19.2817 22.72 18.8025 22.72 18.33H21.97ZM21.72 19.42L21.0418 19.0997C20.9016 19.3967 20.7219 19.6742 20.4843 19.9364L21.04 20.44L21.5957 20.9436C21.9381 20.5658 22.1984 20.1633 22.3982 19.7403L21.72 19.42ZM21.04 20.44L20.4846 19.936C20.0606 20.4032 19.6105 20.7232 19.1156 20.926L19.4 21.62L19.6844 22.314C20.4095 22.0168 21.0394 21.5568 21.5954 20.944L21.04 20.44ZM19.4 21.62L19.1115 20.9277C18.6064 21.1382 18.0548 21.25 17.45 21.25V22V22.75C18.2452 22.75 18.9936 22.6018 19.6885 22.3123L19.4 21.62ZM17.45 22V21.25C16.5486 21.25 15.5587 21.0379 14.484 20.58L14.19 21.27L13.896 21.96C15.1213 22.4821 16.3114 22.75 17.45 22.75V22ZM14.19 21.27L14.484 20.58C13.3915 20.1145 12.2905 19.4839 11.1914 18.6837L10.75 19.29L10.3086 19.8963C11.4895 20.7561 12.6885 21.4455 13.896 21.96L14.19 21.27ZM10.75 19.29L11.1924 18.6844C10.0733 17.8669 9.01176 16.9614 7.99776 15.9571L7.47 16.49L6.94224 17.0229C8.00824 18.0786 9.12671 19.0331 10.3076 19.8956L10.75 19.29ZM7.47 16.49L8.00289 15.9622C6.9985 14.9481 6.09323 13.8868 5.28632 12.7786L4.68 13.22L4.07368 13.6614C4.92677 14.8332 5.8815 15.9519 6.93711 17.0178L7.47 16.49ZM4.68 13.22L5.28885 12.7821C4.49525 11.6787 3.8649 10.587 3.4103 9.51677L2.72 9.81L2.0297 10.1032C2.5351 11.293 3.22475 12.4813 4.07115 13.6579L4.68 13.22ZM2.72 9.81L3.41123 9.51896C2.9628 8.45396 2.75 7.46299 2.75 6.54H2H1.25C1.25 7.69701 1.5172 8.88604 2.02877 10.101L2.72 9.81ZM2 6.54H2.75C2.75 5.94888 2.85414 5.39408 3.05636 4.88854L2.36 4.61L1.66364 4.33146C1.38586 5.02592 1.25 5.77112 1.25 6.54H2ZM2.36 4.61L3.05792 4.88459C3.25506 4.38355 3.56991 3.91456 4.02467 3.48555L3.51 2.94L2.99533 2.39445C2.39009 2.96544 1.94494 3.61645 1.66208 4.33541L2.36 4.61ZM3.51 2.94L4.03614 3.47449C4.56293 2.95593 5.08187 2.75 5.59 2.75V2V1.25C4.61813 1.25 3.73707 1.66407 2.98386 2.40551L3.51 2.94ZM5.59 2V2.75C5.76498 2.75 5.93314 2.78783 6.07545 2.85614L6.4 2.18L6.72455 1.50386C6.36686 1.33217 5.97502 1.25 5.59 1.25V2ZM6.4 2.18L6.08571 2.86097C6.23429 2.92955 6.35581 3.02601 6.45336 3.16691L7.07 2.74L7.68664 2.31309C7.42419 1.93399 7.08571 1.67045 6.71429 1.49903L6.4 2.18ZM7.07 2.74L6.45831 3.17398L8.77831 6.44398L9.39 6.01L10.0017 5.57602L7.68169 2.30602L7.07 2.74ZM9.39 6.01L8.78135 6.44823C8.93418 6.66049 9.03246 6.83904 9.09584 6.99397L9.79 6.71L10.4842 6.42603C10.3675 6.14096 10.2058 5.85951 9.99865 5.57177L9.39 6.01ZM9.79 6.71L9.10064 7.00544C9.16141 7.14722 9.18 7.25575 9.18 7.32H9.93H10.68C10.68 7.00425 10.5986 6.69278 10.4794 6.41456L9.79 6.71ZM9.93 7.32H9.18C9.18 7.40903 9.15514 7.51553 9.07935 7.64004L9.72 8.03L10.3606 8.41996C10.5649 8.08447 10.68 7.71097 10.68 7.32H9.93ZM9.72 8.03L9.06708 7.66096C8.97896 7.81685 8.83569 8.00365 8.62967 8.20967L9.16 8.74L9.69033 9.27033C9.96431 8.99635 10.201 8.70315 10.3729 8.39904L9.72 8.03ZM9.16 8.74L8.61951 8.22003L7.85951 9.01003L8.4 9.53L8.94049 10.05L9.70049 9.25997L9.16 8.74ZM8.4 9.53L7.86967 8.99967C7.60741 9.26193 7.49 9.58812 7.49 9.93H8.24H8.99C8.99 9.92933 8.98982 9.95268 8.97601 9.98752C8.96164 10.0238 8.94194 10.0487 8.93033 10.0603L8.4 9.53ZM8.24 9.93H7.49C7.49 10.081 7.51008 10.2127 7.54239 10.3419L8.27 10.16L8.99761 9.9781C8.98992 9.94735 8.99 9.93895 8.99 9.93H8.24ZM8.27 10.16L7.56775 10.4233C7.58932 10.4809 7.61099 10.5313 7.62302 10.5597C7.63761 10.5942 7.63904 10.5988 7.63849 10.5972L8.35 10.36L9.06151 10.1228C9.04096 10.0612 9.01739 10.0058 9.00448 9.97527C8.98902 9.93873 8.98068 9.91915 8.97225 9.89666L8.27 10.16ZM8.35 10.36L7.69158 10.7191C7.90702 11.1141 8.25419 11.5888 8.70746 12.1245L9.28 11.64L9.85254 11.1555C9.42581 10.6512 9.15298 10.2659 9.00842 10.0009L8.35 10.36ZM9.28 11.64L8.71287 12.1308C9.1705 12.6596 9.66123 13.2016 10.1946 13.7453L10.73 13.22L11.2654 12.6947C10.7588 12.1784 10.2895 11.6604 9.84713 11.1492L9.28 11.64ZM10.73 13.22L10.2046 13.7553C10.7507 14.2912 11.2851 14.7952 11.8346 15.2617L12.32 14.69L12.8054 14.1183C12.2949 13.6848 11.7893 13.2088 11.2554 12.6847L10.73 13.22ZM12.32 14.69L11.8355 15.2625C12.3777 15.7213 12.8536 16.0582 13.2591 16.2728L13.61 15.61L13.9609 14.9472C13.6864 14.8018 13.3023 14.5387 12.8045 14.1175L12.32 14.69ZM13.61 15.61L13.3315 16.3064C13.3366 16.3084 13.3476 16.3132 13.38 16.328C13.4073 16.3406 13.4492 16.3599 13.4946 16.3794L13.79 15.69L14.0854 15.0006C14.0608 14.9901 14.0377 14.9794 14.005 14.9645C13.9774 14.9518 13.9334 14.9316 13.8885 14.9136L13.61 15.61ZM13.79 15.69L13.5267 16.3922C13.7163 16.4633 13.8919 16.48 14.04 16.48V15.73V14.98C14.0224 14.98 14.0188 14.979 14.0236 14.9797C14.0262 14.9801 14.0305 14.9809 14.036 14.9823C14.0416 14.9837 14.0475 14.9856 14.0533 14.9878L13.79 15.69ZM14.04 15.73V16.48C14.4196 16.48 14.7374 16.3333 14.9803 16.0903L14.45 15.56L13.9197 15.0297C13.9221 15.0272 13.9402 15.0105 13.9736 14.9962C14.0077 14.9817 14.0339 14.98 14.04 14.98V15.73ZM14.45 15.56L14.9768 16.0938L15.7368 15.3438L15.21 14.81L14.6832 14.2762L13.9232 15.0262L14.45 15.56ZM15.21 14.81L15.7403 15.3403C15.96 15.1207 16.1389 14.987 16.2769 14.9149L15.93 14.25L15.5831 13.5851C15.2611 13.753 14.96 13.9993 14.6797 14.2797L15.21 14.81ZM15.93 14.25L16.32 14.8906C16.4472 14.8132 16.5438 14.79 16.64 14.79V14.04V13.29C16.2362 13.29 15.8728 13.4068 15.54 13.6094L15.93 14.25ZM16.64 14.04V14.79C16.7201 14.79 16.8238 14.806 16.966 14.8642L17.25 14.17L17.534 13.4758C17.2362 13.354 16.9399 13.29 16.64 13.29V14.04ZM17.25 14.17L16.966 14.8642C17.1231 14.9284 17.3061 15.0291 17.5283 15.1802L17.95 14.56L18.3717 13.9398C18.0939 13.7509 17.8169 13.5916 17.534 13.4758L17.25 14.17ZM17.95 14.56L17.5158 15.1715L20.8258 17.5215L21.26 16.91L21.6942 16.2985L18.3842 13.9485L17.95 14.56ZM21.26 16.91L20.8331 17.5266C20.9966 17.6398 21.0773 17.747 21.1235 17.8521L21.81 17.55L22.4965 17.2479C22.3227 16.853 22.0434 16.5402 21.6869 16.2934L21.26 16.91ZM21.81 17.55L21.1136 17.8285C21.1862 18.01 21.22 18.1649 21.22 18.33H21.97H22.72C22.72 17.9351 22.6338 17.59 22.5064 17.2715L21.81 17.55ZM18.5 9H19.25C19.25 8.53018 19.075 8.02657 18.8445 7.57833C18.6063 7.11492 18.2729 6.64111 17.8783 6.21826L17.33 6.73L16.7817 7.24174C17.087 7.56889 17.3387 7.93008 17.5105 8.26417C17.69 8.61343 17.75 8.86982 17.75 9H18.5ZM17.33 6.73L17.8799 6.21997C17.1773 5.46247 16.1421 4.75 15 4.75V5.5V6.25C15.5379 6.25 16.2027 6.61753 16.7801 7.24003L17.33 6.73ZM22 9H22.75C22.75 4.71579 19.2842 1.25 15 1.25V2V2.75C18.4558 2.75 21.25 5.54421 21.25 9H22Z"
                                    fill="#3B3B3B" />
                            </svg>
                            <p class="hotline-text m-0">Hotline: <a href="tel:{{ $config['hotline'] }}">
                                    <span class="">Hỗ trợ 24/7:</span> <b
                                        class="font-semi-bold text-red">{{ $config['hotline'] }}</b>
                                </a></p>
                        </div>
                        <div class="box-icon d-flex gap-2 align-items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18 2H15C13.6739 2 12.4021 2.52678 11.4645 3.46447C10.5268 4.40215 10 5.67392 10 7V10H7V14H10V22H14V14H17L18 10H14V7C14 6.73478 14.1054 6.48043 14.2929 6.29289C14.4804 6.10536 14.7348 6 15 6H18V2Z"
                                    fill="#3B3B3B" />
                            </svg>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M23 3.00029C22.0424 3.67577 20.9821 4.1924 19.86 4.53029C19.2577 3.8378 18.4573 3.34698 17.567 3.12422C16.6767 2.90145 15.7395 2.95749 14.8821 3.28474C14.0247 3.612 13.2884 4.19469 12.773 4.95401C12.2575 5.71332 11.9877 6.61263 12 7.53029V8.53029C10.2426 8.57586 8.50128 8.1861 6.93101 7.39574C5.36074 6.60537 4.01032 5.43893 3 4.00029C3 4.00029 -1 13.0003 8 17.0003C5.94053 18.3983 3.48716 19.0992 1 19.0003C10 24.0003 21 19.0003 21 7.50029C20.9991 7.22174 20.9723 6.94388 20.92 6.67029C21.9406 5.66378 22.6608 4.393 23 3.00029Z"
                                    fill="#3B3B3B" />
                            </svg>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17.5 6.5H17.51M7 2H17C19.7614 2 22 4.23858 22 7V17C22 19.7614 19.7614 22 17 22H7C4.23858 22 2 19.7614 2 17V7C2 4.23858 4.23858 2 7 2ZM16 11.37C16.1234 12.2022 15.9813 13.0522 15.5938 13.799C15.2063 14.5458 14.5931 15.1514 13.8416 15.5297C13.0901 15.9079 12.2384 16.0396 11.4078 15.9059C10.5771 15.7723 9.80976 15.3801 9.21484 14.7852C8.61992 14.1902 8.22773 13.4229 8.09407 12.5922C7.9604 11.7616 8.09206 10.9099 8.47033 10.1584C8.84859 9.40685 9.45419 8.79374 10.201 8.40624C10.9478 8.01874 11.7978 7.87659 12.63 8C13.4789 8.12588 14.2648 8.52146 14.8717 9.12831C15.4785 9.73515 15.8741 10.5211 16 11.37Z"
                                    stroke="#3B3B3B" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

                        </div>
                    </div>
                </div>
            @endif
            <div class="header-bottom pt-3 d-flex align-items-center justify-content-between">
                @if (Auth::guard('members')->check() &&
                        (Str::startsWith(Route::currentRouteName(), 'client.business') ||
                            in_array(Route::currentRouteName(), ['client.login_business', 'client.register_business'])))
                    <!-- Header khi đã đăng nhập và ở trong module thành viên -->
                    <div class="logo">
                        <a href="{{ url('/') }}" class="logo-link">
                            <img src="{{ asset($config['logo']) }}" alt="{{ $config['title'] }}"
                                class="logo-image" />
                        </a>
                    </div>
                    <div class="member-navigation d-flex align-items-center gap-4">
                        <nav class="main-menu">
                            <ul class="nav-menu d-flex align-items-center gap-4 m-0 list-unstyled">
                                <li class="nav-item">
                                    <a href="{{ route('client.business.profile') }}" 
                                       class="nav-link {{ in_array(Route::currentRouteName(), [
                                           'client.business.profile', 
                                           'client.business.info', 
                                           'client.business.orders', 
                                           'client.business.categories', 
                                           'client.business.notifications', 
                                           'client.business.settings'
                                       ]) ? 'active' : '' }}">
                                        Hồ sơ
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('client.business.tour_management') }}" 
                                       class="nav-link {{ Route::currentRouteName() === 'client.business.tour_management' ? 'active' : '' }}">
                                        Quản lý tour
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">Đánh giá</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">Khuyến mãi</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">Dịch vụ đăng tin</a>
                                </li>
                            </ul>
                        </nav>
                        <div class="user-info dropdown">
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button"
                                    id="memberDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if (Auth::guard('members')->user()->image)
                                        <img src="{{ asset(Auth::guard('members')->user()->image) }}" alt="Avatar"
                                            class="rounded-circle me-2"
                                            style="width: 32px; height: 32px; object-fit: cover;">
                                    @else
                                        <div class="avatar-placeholder rounded-circle me-2 d-flex align-items-center justify-content-center"
                                            style="width: 32px; height: 32px; background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z"
                                                    stroke="#6c757d" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="ms-1">{{ Auth::guard('members')->user()->name ?? 'User' }}</span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="memberDropdown">
                                    <li><a class="dropdown-item" href="{{ route('client.business.profile') }}">Hồ sơ
                                            của tôi</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ route('client.logout_business') }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Đăng xuất</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Header thông thường (chưa đăng nhập hoặc đã đăng nhập nhưng không ở trong module thành viên) -->
                    <div class="logo">
                        <a href="{{ url('/') }}" class="logo-link">
                            <img src="{{ asset($config['logo']) }}" alt="{{ $config['title'] }}"
                                class="logo-image" />
                        </a>
                    </div>
                    <div class="bottom-header-right d-flex align-items-center ">
                        <div class="main-navigation">
                            @if (!empty($main_menu))
                                {!! $main_menu !!}
                            @else
                                <p>No menu data available</p>
                            @endif

                        </div>
                        <div class="language">
                            <div class="language-select dropdown">
                                <button class="btn btn-light dropdown-toggle d-flex align-items-center bg-white"
                                    type="button" id="dropdownLang" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <span class="me-1">🇻🇳</span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownLang">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center"
                                            href="{{ url('lang/vi') }}">
                                            🇻🇳
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center"
                                            href="{{ url('lang/en') }}">
                                            🇬🇧
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="layout-icon d-flex align-items-center gap-3">
                            <div class="search">
                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="17.5" cy="18" r="17" fill="white" stroke="#BFBFBF" />
                                    <path
                                        d="M26.3333 26.3334L24.6667 24.6667M25.5 17.5834C25.5 21.9557 21.9556 25.5001 17.5833 25.5001C13.2111 25.5001 9.66666 21.9557 9.66666 17.5834C9.66666 13.2112 13.2111 9.66675 17.5833 9.66675C21.9556 9.66675 25.5 13.2112 25.5 17.5834Z"
                                        stroke="#0D0D0D" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>

                            </div>
                            <div class="favourite">
                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="17.5" cy="18" r="17" fill="white" stroke="#BFBFBF" />
                                    <path
                                        d="M18.5167 25.3416C18.2334 25.4416 17.7667 25.4416 17.4834 25.3416C15.0667 24.5166 9.66669 21.0749 9.66669 15.2416C9.66669 12.6666 11.7417 10.5833 14.3 10.5833C15.8167 10.5833 17.1584 11.3166 18 12.4499C18.8417 11.3166 20.1917 10.5833 21.7 10.5833C24.2584 10.5833 26.3334 12.6666 26.3334 15.2416C26.3334 21.0749 20.9334 24.5166 18.5167 25.3416Z"
                                        stroke="#0D0D0D" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="member">
                                @if (Auth::guard('members')->check())
                                    <!-- Nếu đã đăng nhập nhưng không ở module thành viên, hiển thị dropdown user -->
                                    <div class="dropdown">
                                        <button class="btn btn-light dropdown-toggle d-flex align-items-center"
                                            type="button" id="memberDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            @if (Auth::guard('members')->user()->image)
                                                <img src="{{ asset(Auth::guard('members')->user()->image) }}"
                                                    alt="Avatar" class="rounded-circle me-2"
                                                    style="width: 32px; height: 32px; object-fit: cover;">
                                            @else
                                                <div class="avatar-placeholder rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                                    <svg width="16" height="16" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z"
                                                            stroke="#6c757d" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="memberDropdown">
                                            <li><a class="dropdown-item"
                                                    href="{{ route('client.business.profile') }}">Hồ sơ
                                                    của tôi</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('client.logout_business') }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">Đăng xuất</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                @else
                                    <!-- Nếu chưa đăng nhập, hiển thị icon login -->
                                    <a href="{{ route('client.login_business') }}">
                                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="17.5" cy="18" r="17" fill="white"
                                                stroke="#BFBFBF" />
                                            <path
                                                d="M25.1583 26.3334C25.1583 23.1084 21.95 20.5001 18 20.5001C14.05 20.5001 10.8417 23.1084 10.8417 26.3334M22.1667 13.8334C22.1667 16.1346 20.3012 18.0001 18 18.0001C15.6988 18.0001 13.8333 16.1346 13.8333 13.8334C13.8333 11.5322 15.6988 9.66675 18 9.66675C20.3012 9.66675 22.1667 11.5322 22.1667 13.8334Z"
                                                stroke="#0D0D0D" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success') || session('error') || session('message'))
            <div class="container-fluid">
                <div class="nav-container">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('message'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @yield('layoutContent')
    </div>


    <footer class="kikichy_footer">
        <div class="nav-container ">
            <div class="layout-footer row">
                <div class="footer-wrap col-md-4 col-lg-4">
                    <div class="footer-logo mb-3">
                        <a href="{{ url('/') }}" class="logo-link">
                            <img src="{{ asset($config['logo']) }}" alt="{{ $config['title'] }}"
                                class="logo-image" />
                        </a>
                    </div>
                    <div class="footer-first">
                        <h3 class="my-3">24/7 Customer Support</h3>
                        <div class="content-footer">
                            {!! @$config['content_footer'] !!}
                        </div>
                    </div>
                </div>
                <div class="info col-md-3 col-lg-3">
                    <h3> Thông tin liên hệ</h3>
                    <div class="hotline d-flex gap-2 align-items-center mb-2">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="20" cy="20.0029" r="19.5" fill="white" stroke="#BFBFBF" />
                            <path
                                d="M29.04 28.4429L29.4103 28.7789L29.4105 28.7787L29.04 28.4429ZM27.4 29.6229L27.2104 29.1603L27.2077 29.1614L27.4 29.6229ZM18.75 27.2929L18.4551 27.6967L18.4557 27.6971L18.75 27.2929ZM15.47 24.4929L15.1147 24.8448L15.1182 24.8482L15.47 24.4929ZM12.68 21.2229L12.2741 21.5149L12.2758 21.5172L12.68 21.2229ZM10.72 17.8129L10.2592 18.007L10.2598 18.0084L10.72 17.8129ZM10.36 12.6129L10.8242 12.7986L10.8253 12.796L10.36 12.6129ZM11.51 10.9429L11.8532 11.3067L11.8608 11.2993L11.51 10.9429ZM14.4 10.1829L14.1836 10.6337L14.1905 10.6369L14.4 10.1829ZM15.07 10.7429L14.6589 11.0276L14.6622 11.0322L15.07 10.7429ZM17.39 14.0129L16.9822 14.3023L16.9842 14.3051L17.39 14.0129ZM17.79 14.7129L17.3272 14.9023L17.3304 14.9099L17.79 14.7129ZM17.72 16.0329L17.2929 15.773L17.2887 15.7799L17.2847 15.7869L17.72 16.0329ZM17.16 16.7429L16.8064 16.3893L16.7997 16.3963L17.16 16.7429ZM16.4 17.5329L16.7536 17.8865L16.7603 17.8796L16.4 17.5329ZM16.27 18.1629L15.7849 18.2842L15.7918 18.3118L15.8018 18.3385L16.27 18.1629ZM16.35 18.3629L15.8757 18.521L15.8897 18.5633L15.9111 18.6024L16.35 18.3629ZM17.28 19.6429L16.8983 19.9659L16.9019 19.9701L17.28 19.6429ZM18.73 21.2229L18.373 21.5732L18.3798 21.5798L18.73 21.2229ZM20.32 22.6929L19.9964 23.0741L19.997 23.0746L20.32 22.6929ZM21.61 23.6129L21.3761 24.0548L21.3996 24.0673L21.4243 24.0772L21.61 23.6129ZM21.79 23.6929L21.593 24.1525L21.6036 24.157L21.6144 24.1611L21.79 23.6929ZM22.45 23.5629L22.0988 23.207L22.0964 23.2094L22.45 23.5629ZM23.21 22.8129L23.5612 23.1688L23.5636 23.1665L23.21 22.8129ZM23.93 22.2529L24.1613 22.6962L24.1759 22.6886L24.19 22.68L23.93 22.2529ZM25.95 22.5629L26.2395 22.1551L26.2312 22.1495L25.95 22.5629ZM29.26 24.9129L28.9705 25.3207L28.9754 25.324L29.26 24.9129ZM29.81 25.5529L30.2742 25.3672L30.2711 25.3593L30.2677 25.3516L29.81 25.5529ZM26 17.0029C26 17.2791 26.2239 17.5029 26.5 17.5029C26.7761 17.5029 27 17.2791 27 17.0029H26ZM25.33 14.7329L24.9634 15.073L24.9645 15.0741L25.33 14.7329ZM23 13.0029C22.7239 13.0029 22.5 13.2268 22.5 13.5029C22.5 13.7791 22.7239 14.0029 23 14.0029V13.0029ZM29.5 17.0029C29.5 17.2791 29.7239 17.5029 30 17.5029C30.2761 17.5029 30.5 17.2791 30.5 17.0029H29.5ZM23 9.50293C22.7239 9.50293 22.5 9.72679 22.5 10.0029C22.5 10.2791 22.7239 10.5029 23 10.5029V9.50293ZM29.97 26.3329H29.47C29.47 26.6179 29.4068 26.9151 29.2679 27.2094L29.72 27.4229L30.1721 27.6364C30.3731 27.2107 30.47 26.7679 30.47 26.3329H29.97ZM29.72 27.4229L29.2679 27.2094C29.1177 27.5274 28.9246 27.8257 28.6695 28.1072L29.04 28.4429L29.4105 28.7787C29.7354 28.4202 29.9823 28.0384 30.1721 27.6364L29.72 27.4229ZM29.04 28.4429L28.6697 28.1069C28.2238 28.5984 27.7437 28.9417 27.2104 29.1603L27.4 29.6229L27.5896 30.0856C28.2763 29.8041 28.8762 29.3675 29.4103 28.7789L29.04 28.4429ZM27.4 29.6229L27.2077 29.1614C26.6709 29.385 26.0865 29.5029 25.45 29.5029V30.0029V30.5029C26.2135 30.5029 26.9291 30.3608 27.5923 30.0845L27.4 29.6229ZM25.45 30.0029V29.5029C24.5091 29.5029 23.4858 29.2816 22.386 28.8129L22.19 29.2729L21.994 29.7329C23.1942 30.2443 24.3509 30.5029 25.45 30.5029V30.0029ZM22.19 29.2729L22.386 28.8129C21.2743 28.3393 20.157 27.6989 19.0443 26.8887L18.75 27.2929L18.4557 27.6971C19.623 28.547 20.8057 29.2266 21.994 29.7329L22.19 29.2729ZM18.75 27.2929L19.0449 26.8892C17.9155 26.0642 16.8445 25.1505 15.8218 24.1377L15.47 24.4929L15.1182 24.8482C16.1755 25.8954 17.2845 26.8416 18.4551 27.6967L18.75 27.2929ZM15.47 24.4929L15.8253 24.1411C14.8123 23.1183 13.8988 22.0475 13.0842 20.9286L12.68 21.2229L12.2758 21.5172C13.1212 22.6784 14.0677 23.7875 15.1147 24.8448L15.47 24.4929ZM12.68 21.2229L13.0859 20.931C12.2835 19.8154 11.6433 18.7076 11.1802 17.6174L10.72 17.8129L10.2598 18.0084C10.7567 19.1783 11.4365 20.3504 12.2741 21.5149L12.68 21.2229ZM10.72 17.8129L11.1808 17.6189C10.7219 16.5289 10.5 15.5049 10.5 14.5429H10H9.5C9.5 15.6609 9.75813 16.817 10.2592 18.007L10.72 17.8129ZM10 14.5429H10.5C10.5 13.9222 10.6094 13.3357 10.8242 12.7986L10.36 12.6129L9.89576 12.4272C9.63057 13.0902 9.5 13.8037 9.5 14.5429H10ZM10.36 12.6129L10.8253 12.796C11.0367 12.2586 11.3733 11.7593 11.8531 11.3066L11.51 10.9429L11.1669 10.5792C10.5867 11.1266 10.1633 11.7472 9.89472 12.4299L10.36 12.6129ZM11.51 10.9429L11.8608 11.2993C12.4253 10.7435 13.0046 10.5029 13.59 10.5029V10.0029V9.50293C12.6954 9.50293 11.8747 9.88231 11.1592 10.5866L11.51 10.9429ZM13.59 10.0029V10.5029C13.8 10.5029 14.0054 10.5482 14.1836 10.6337L14.4 10.1829L14.6164 9.73217C14.2946 9.57771 13.94 9.50293 13.59 9.50293V10.0029ZM14.4 10.1829L14.1905 10.6369C14.3762 10.7226 14.5339 10.8469 14.6589 11.0275L15.07 10.7429L15.4811 10.4583C15.2461 10.1189 14.9438 9.88323 14.6095 9.72895L14.4 10.1829ZM15.07 10.7429L14.6622 11.0322L16.9822 14.3022L17.39 14.0129L17.7978 13.7236L15.4778 10.4536L15.07 10.7429ZM17.39 14.0129L16.9842 14.3051C17.1461 14.5299 17.255 14.7256 17.3272 14.9022L17.79 14.7129L18.2528 14.5236C18.145 14.2602 17.9939 13.9959 17.7958 13.7208L17.39 14.0129ZM17.79 14.7129L17.3304 14.9099C17.4009 15.0744 17.43 15.2168 17.43 15.3229H17.93H18.43C18.43 15.0491 18.3591 14.7714 18.2496 14.516L17.79 14.7129ZM17.93 15.3229H17.43C17.43 15.4623 17.3901 15.6133 17.2929 15.773L17.72 16.0329L18.1471 16.2929C18.3299 15.9926 18.43 15.6636 18.43 15.3229H17.93ZM17.72 16.0329L17.2847 15.7869C17.1826 15.9675 17.0238 16.172 16.8064 16.3894L17.16 16.7429L17.5136 17.0965C17.7762 16.8338 17.9974 16.5584 18.1553 16.279L17.72 16.0329ZM17.16 16.7429L16.7997 16.3963L16.0397 17.1863L16.4 17.5329L16.7603 17.8796L17.5203 17.0896L17.16 16.7429ZM16.4 17.5329L16.0464 17.1794C15.8349 17.3909 15.74 17.6517 15.74 17.9329H16.24H16.74C16.74 17.919 16.7411 17.91 16.742 17.905C16.7428 17.9003 16.7436 17.8984 16.7436 17.8984C16.7436 17.8982 16.7437 17.8982 16.7437 17.8982C16.7437 17.8981 16.7439 17.8978 16.7443 17.8972C16.7451 17.896 16.7476 17.8924 16.7536 17.8865L16.4 17.5329ZM16.24 17.9329H15.74C15.74 18.0603 15.7567 18.1714 15.7849 18.2842L16.27 18.1629L16.7551 18.0417C16.7433 17.9945 16.74 17.9656 16.74 17.9329H16.24ZM16.27 18.1629L15.8018 18.3385C15.8212 18.3902 15.8407 18.4354 15.8533 18.4652C15.8676 18.4991 15.8727 18.5121 15.8757 18.521L16.35 18.3629L16.8243 18.2048C16.8073 18.1537 16.7874 18.1068 16.7742 18.0756C16.7593 18.0404 16.7488 18.0157 16.7382 17.9874L16.27 18.1629ZM16.35 18.3629L15.9111 18.6024C16.1147 18.9757 16.4495 19.4354 16.8983 19.9659L17.28 19.6429L17.6617 19.32C17.2305 18.8104 16.9453 18.4102 16.7889 18.1235L16.35 18.3629ZM17.28 19.6429L16.9019 19.9701C17.357 20.496 17.8442 21.034 18.3731 21.5731L18.73 21.2229L19.0869 20.8728C18.5758 20.3519 18.103 19.8299 17.6581 19.3157L17.28 19.6429ZM18.73 21.2229L18.3798 21.5798C18.9238 22.1137 19.4534 22.613 19.9964 23.0741L20.32 22.6929L20.6436 22.3118C20.1266 21.8728 19.6162 21.3921 19.0802 20.8661L18.73 21.2229ZM20.32 22.6929L19.997 23.0746C20.5318 23.5271 20.9924 23.8517 21.3761 24.0548L21.61 23.6129L21.8439 23.171C21.5476 23.0141 21.1482 22.7387 20.643 22.3112L20.32 22.6929ZM21.61 23.6129L21.4243 24.0772C21.4361 24.0819 21.4526 24.0892 21.4842 24.1037C21.5124 24.1166 21.5511 24.1345 21.593 24.1525L21.79 23.6929L21.987 23.2334C21.9589 23.2213 21.9326 23.2092 21.9008 23.1946C21.8724 23.1816 21.8339 23.164 21.7957 23.1487L21.61 23.6129ZM21.79 23.6929L21.6144 24.1611C21.7675 24.2185 21.9113 24.2329 22.04 24.2329V23.7329V23.2329C21.9887 23.2329 21.9725 23.2274 21.9656 23.2248L21.79 23.6929ZM22.04 23.7329V24.2329C22.3497 24.2329 22.6049 24.1151 22.8036 23.9165L22.45 23.5629L22.0964 23.2094C22.0798 23.2261 22.0718 23.2291 22.0716 23.2292C22.0708 23.2295 22.0689 23.2302 22.065 23.231C22.0609 23.2318 22.0529 23.2329 22.04 23.2329V23.7329ZM22.45 23.5629L22.8012 23.9188L23.5612 23.1688L23.21 22.8129L22.8588 22.457L22.0988 23.207L22.45 23.5629ZM23.21 22.8129L23.5636 23.1665C23.7933 22.9367 23.9926 22.7842 24.1613 22.6962L23.93 22.2529L23.6987 21.8096C23.4074 21.9616 23.1267 22.1892 22.8564 22.4594L23.21 22.8129ZM23.93 22.2529L24.19 22.68C24.3514 22.5817 24.4925 22.5429 24.64 22.5429V22.0429V21.5429C24.2875 21.5429 23.9686 21.6441 23.67 21.8258L23.93 22.2529ZM24.64 22.0429V22.5429C24.7567 22.5429 24.8925 22.5669 25.0607 22.6357L25.25 22.1729L25.4393 21.7102C25.1675 21.599 24.9033 21.5429 24.64 21.5429V22.0429ZM25.25 22.1729L25.0607 22.6357C25.2387 22.7085 25.4374 22.819 25.6688 22.9764L25.95 22.5629L26.2312 22.1495C25.9626 21.9668 25.7013 21.8173 25.4393 21.7102L25.25 22.1729ZM25.95 22.5629L25.6605 22.9706L28.9705 25.3206L29.26 24.9129L29.5495 24.5052L26.2395 22.1552L25.95 22.5629ZM29.26 24.9129L28.9754 25.324C29.1711 25.4595 29.2849 25.6009 29.3523 25.7543L29.81 25.5529L30.2677 25.3516C30.1151 25.0049 29.8689 24.7264 29.5446 24.5018L29.26 24.9129ZM29.81 25.5529L29.3458 25.7386C29.4275 25.9429 29.47 26.1295 29.47 26.3329H29.97H30.47C30.47 25.9763 30.3925 25.6629 30.2742 25.3672L29.81 25.5529ZM26.5 17.0029H27C27 16.5897 26.8441 16.1273 26.6222 15.6956C26.395 15.2537 26.0753 14.7987 25.6955 14.3918L25.33 14.7329L24.9645 15.0741C25.2847 15.4172 25.55 15.7971 25.7328 16.1528C25.9209 16.5186 26 16.8161 26 17.0029H26.5ZM25.33 14.7329L25.6966 14.3929C25.0149 13.6579 24.0414 13.0029 23 13.0029V13.5029V14.0029C23.6386 14.0029 24.3651 14.4279 24.9634 15.073L25.33 14.7329ZM30 17.0029H30.5C30.5 12.8568 27.1461 9.50293 23 9.50293V10.0029V10.5029C26.5939 10.5029 29.5 13.4091 29.5 17.0029H30Z"
                                fill="#3B3B3B" />
                        </svg>
                        <div class="content-hotline d-flex flex-column">
                            <span class="hotline-text"> <a
                                    href="tel:{{ $config['hotline'] }}">{{ $config['hotline'] }}</a></span>
                            <span class="hotline-text"><a
                                    href="tel:{{ $config['hotline2'] }}">{{ $config['hotline2'] }}</a></span>
                        </div>

                    </div>
                    <div class="email d-flex gap-2 align-items-center mb-2">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="20" cy="20.0029" r="19.5" fill="white" stroke="#BFBFBF" />
                            <path
                                d="M25 17.0029L21.87 19.5029C20.84 20.3229 19.15 20.3229 18.12 19.5029L15 17.0029M25 28.5029H15C12 28.5029 10 27.0029 10 23.5029V16.5029C10 13.0029 12 11.5029 15 11.5029H25C28 11.5029 30 13.0029 30 16.5029V23.5029C30 27.0029 28 28.5029 25 28.5029Z"
                                stroke="#3B3B3B" stroke-miterlimit="10" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>

                        <div class="content-email">
                            <span> <a
                                    href="mailto:{{ $config['admin-email'] }}">{{ $config['admin-email'] }}</a></span>
                            <span> <a href="mailto:{{ $config['email2'] }}">{{ $config['email2'] }}</a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-lg-5">
                    <div class="footer-menu-wrapper">
                        {!! $footer_menu !!}
                    </div>
                </div>
            </div>
            <div class="footer-finish">
                {!! @$config['footer'] !!}
            </div>

        </div>

    </footer>
    <div class="backdrop" style="opacity: 0; pointer-events: none"></div>

    <div class="my-container add-cart-modal" style="position: relative"></div>

    @include('client/scripts')

    <script src="{{ asset('assets/admin/js/select2.js') }}"></script>
    <script src="{{ asset('assets/admin/js/sweet-alert.js') }}"></script>
    <script src="{{ asset('assets/client/js/styles.js') }}"></script>
    <script src="{{ asset('assets/admin/js/ui-5.0.36/dist/fancybox/fancybox.umd.js') }}"></script>

    <!-- Owl Carousel JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto hide flash messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>


    @yield('script_page')

    @push('push_script')
        @if (session('message'))
            <script>
                Swal.fire({
                    position: 'top',
                    icon: '{{ session('status') ? session('status') : 'success' }}',
                    text: '{{ session('message') }}',
                    showConfirmButton: false,
                    timer: 3000
                }).then(result => {
                    {!! session('script') !!}
                })
            </script>
            <script>
                // Khởi tạo tất cả dropdown
                document.addEventListener('DOMContentLoaded', function() {
                    var dropdowns = document.querySelectorAll('.dropdown-toggle');
                    dropdowns.forEach(function(dropdown) {
                        new bootstrap.Dropdown(dropdown);
                    });
                });
            </script>
        @endif
    @endpush

    @stack('push_script')
</body>

</html>
