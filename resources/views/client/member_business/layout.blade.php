@extends('client.layout')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('assets/client/css/business_profile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/business_info.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/business_order.css') }}">

@endsection
@section('script_page')
    <script src="{{ asset('assets/client/js/business_info.js') }}"></script>
    <script src="{{ asset('assets/client/js/business_order.js') }}"></script>
@endsection

@section('description', 'Quản lý hồ sơ doanh nghiệp')

@section('layoutContent')
    <div class="business-profile-container">
        <div class="nav-container">
            <div class="row">
                <div class="col-md-3 col-lg-3">
                    <div class="sidebar-menu">
                        <div class="profile-sidebar">

                            <div class="user-header">
                                <form id="avatar-form" action="{{ route('client.business.update_avatar') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="user-avatar position-relative">
                                        @if (Auth::guard('members')->user()->image)
                                            <img src="{{ asset(Auth::guard('members')->user()->image) }}" alt="Avatar"
                                                id="avatar-preview">
                                        @else
                                            <svg width="36" height="36" viewBox="0 0 36 36" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M30.8852 33C30.8852 27.195 25.1102 22.5 18.0002 22.5C10.8902 22.5 5.11523 27.195 5.11523 33M25.5002 10.5C25.5002 14.6421 22.1424 18 18.0002 18C13.8581 18 10.5002 14.6421 10.5002 10.5C10.5002 6.35786 13.8581 3 18.0002 3C22.1424 3 25.5002 6.35786 25.5002 10.5Z"
                                                    stroke="#E40808" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        @endif
                                        <div class="avatar-upload-overlay">
                                            <label for="avatar-input" class="upload-btn">
                                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M30.8852 33C30.8852 27.195 25.1102 22.5 18.0002 22.5C10.8902 22.5 5.11523 27.195 5.11523 33M25.5002 10.5C25.5002 14.6421 22.1424 18 18.0002 18C13.8581 18 10.5002 14.6421 10.5002 10.5C10.5002 6.35786 13.8581 3 18.0002 3C22.1424 3 25.5002 6.35786 25.5002 10.5Z"
                                                        stroke="#E40808" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </label>
                                            <input type="file" id="avatar-input" name="avatar" accept="image/*"
                                                style="display: none;">
                                        </div>
                                    </div>

                                </form>

                                <!-- Alternative: Using choose-file component -->
                                {{-- <div class="user-avatar-choosefile mt-2">
                                    <x-choose-file title="Ảnh đại diện" :type="'Images'" id="user_avatar" field="image" :dataComponent="Auth::guard('members')->user()" />
                                </div> --}}

                                <div class="user-info">
                                    <h6>{{ Auth::guard('members')->user()->name ?? 'Tên user đăng nhập' }}</h6>
                                    <p>{{ Auth::guard('members')->user()->email ?? 'Đổi ảnh đại diện' }}</p>
                                </div>
                            </div>

                            <nav class="profile-nav">
                                <ul class="nav-list">
                                    <li
                                        class="nav-item {{ Route::currentRouteName() == 'client.business.profile' ? 'active' : '' }}">
                                        <a href="{{ route('client.business.profile') }}" class="nav-link-dashboard">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.99984 6.66699V10.8337M9.99526 13.3337H10.0027M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z"
                                                    stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <span>Tổng quan</span>
                                        </a>
                                    </li>
                                    <li
                                        class="nav-item {{ Route::currentRouteName() == 'client.business.info' ? 'active' : '' }}">
                                        <a href="{{ route('client.business.info') }}" class="nav-link-dashboard">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M1.66675 7.08366C1.66675 4.16699 3.33341 2.91699 5.83341 2.91699H14.1667C16.6667 2.91699 18.3334 4.16699 18.3334 7.08366V12.917C18.3334 15.8337 16.6667 17.0837 14.1667 17.0837H5.83341M14.1667 7.50033L11.5584 9.58366C10.7001 10.267 9.29174 10.267 8.43341 9.58366L5.83341 7.50033M1.66675 13.7503H6.66675M1.66675 10.417H4.16675"
                                                    stroke="#3B3B3B" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>Thông tin liên hệ</span>
                                        </a>
                                    </li>
                                    <li
                                        class="nav-item {{ Route::currentRouteName() == 'client.business.orders' ? 'active' : '' }}">
                                        <a href="{{ route('client.business.orders') }}" class="nav-link-dashboard">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6.6666 5.00033V4.33366C6.6666 2.85866 6.6666 1.66699 9.33327 1.66699H10.6666C13.3333 1.66699 13.3333 2.85866 13.3333 4.33366V5.00033M11.6666 11.6837C11.6666 11.6753 11.6666 11.6753 11.6666 11.667V10.8337C11.6666 10.0003 11.6666 10.0003 10.8333 10.0003H9.1666C8.33327 10.0003 8.33327 10.0003 8.33327 10.8337V11.692M11.6666 11.6837C11.6666 12.592 11.6583 13.3337 9.99994 13.3337C8.34994 13.3337 8.33327 12.6003 8.33327 11.692M11.6666 11.6837C13.9166 11.4003 16.1166 10.567 18.0416 9.16699M8.33327 11.692C6.17494 11.4503 4.05827 10.6753 2.18327 9.39199M6.6666 18.3337H13.3333C16.6833 18.3337 17.2833 16.992 17.4583 15.3587L18.0833 8.69199C18.3083 6.65866 17.7249 5.00033 14.1666 5.00033H5.83327C2.27494 5.00033 1.6916 6.65866 1.9166 8.69199L2.5416 15.3587C2.7166 16.992 3.3166 18.3337 6.6666 18.3337Z"
                                                    stroke="#3B3B3B" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>Trang điều hành</span>
                                        </a>
                                    </li>
                                    <li
                                        class="nav-item {{ Route::currentRouteName() == 'client.business.categories' ? 'active' : '' }}">
                                        <a href="{{ route('client.business.categories') }}" class="nav-link-dashboard">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M13.6749 3.33366C15.2916 3.33366 16.5916 4.64199 16.5916 6.25033C16.5916 7.82533 15.3416 9.10866 13.7833 9.16699C13.7166 9.15866 13.6416 9.15866 13.5666 9.16699M15.2833 16.667C15.8833 16.542 16.4499 16.3003 16.9166 15.942C18.2166 14.967 18.2166 13.3587 16.9166 12.3837C16.4583 12.0337 15.8999 11.8003 15.3083 11.667M7.63327 9.05866C7.54993 9.05033 7.44993 9.05033 7.35827 9.05866C5.37493 8.99199 3.79993 7.36699 3.79993 5.36699C3.79993 3.32533 5.44993 1.66699 7.49993 1.66699C9.5416 1.66699 11.1999 3.32533 11.1999 5.36699C11.1916 7.36699 9.6166 8.99199 7.63327 9.05866ZM3.4666 12.1337C1.44993 13.4837 1.44993 15.6837 3.4666 17.0253C5.75827 18.5587 9.5166 18.5587 11.8083 17.0253C13.8249 15.6753 13.8249 13.4753 11.8083 12.1337C9.52494 10.6087 5.7666 10.6087 3.4666 12.1337Z"
                                                    stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <span>Phân loại khách du lịch</span>
                                        </a>
                                    </li>
                                    <li
                                        class="nav-item {{ Route::currentRouteName() == 'client.business.notifications' ? 'active' : '' }}">
                                        <a href="{{ route('client.business.notifications') }}" class="nav-link-dashboard">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M18.3334 8.75V12.9167C18.3334 15.8333 16.6667 17.0833 14.1667 17.0833H5.83341C3.33341 17.0833 1.66675 15.8333 1.66675 12.9167V7.08333C1.66675 4.16667 3.33341 2.91667 5.83341 2.91667H11.6667M5.83341 7.5L8.44175 9.58333C9.30009 10.2667 10.7084 10.2667 11.5668 9.58333L12.5501 8.8M18.3334 4.58333C18.3334 5.73393 17.4007 6.66667 16.2501 6.66667C15.0995 6.66667 14.1667 5.73393 14.1667 4.58333C14.1667 3.43274 15.0995 2.5 16.2501 2.5C17.4007 2.5 18.3334 3.43274 18.3334 4.58333Z"
                                                    stroke="#3B3B3B" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>Thông báo qua email</span>
                                        </a>
                                    </li>
                                    <li
                                        class="nav-item {{ Route::currentRouteName() == 'client.business.settings' ? 'active' : '' }}">
                                        <a href="{{ route('client.business.settings') }}" class="nav-link-dashboard">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M3.33325 14.9995V9.16619M6.66658 14.9995V9.16619M9.99992 14.9995V9.16619M13.3333 14.9995V9.16619M16.6666 14.9995V9.16619M0.833252 18.3329H19.1666M10.3082 1.79121L17.8082 4.79119C18.0999 4.90786 18.3333 5.25785 18.3333 5.56618V8.33286C18.3333 8.79119 17.9583 9.16619 17.4999 9.16619H2.49992C2.04159 9.16619 1.66659 8.79119 1.66659 8.33286V5.56618C1.66659 5.25785 1.89992 4.90786 2.19159 4.79119L9.69159 1.79121C9.85826 1.72454 10.1416 1.72454 10.3082 1.79121ZM18.3333 18.3329H1.66659V15.8329C1.66659 15.3745 2.04159 14.9995 2.49992 14.9995H17.4999C17.9583 14.9995 18.3333 15.3745 18.3333 15.8329V18.3329ZM11.2499 5.83286C11.2499 6.52321 10.6903 7.08286 9.99992 7.08286C9.30956 7.08286 8.74992 6.52321 8.74992 5.83286C8.74992 5.1425 9.30956 4.58286 9.99992 4.58286C10.6903 4.58286 11.2499 5.1425 11.2499 5.83286Z"
                                                    stroke="#3B3B3B" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>Đặt chỗ và tài chính</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            <div class="support-section text-start">
                                {!! @$config['content_hotline'] !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-lg-9">
                    <div class="main-content">
                        <div class="content-header">
                            <h2>@yield('page-title', 'Thông tin tổng quan')</h2>
                        </div>

                        <div class="overview-content">
                            @yield('page-content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @yield('script_page')

    <script>
        document.getElementById('avatar-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatar-preview');
                    const icon = document.getElementById('avatar-icon');

                    if (preview) {
                        preview.src = e.target.result;
                    } else {
                        // Create img element if not exists
                        const newImg = document.createElement('img');
                        newImg.src = e.target.result;
                        newImg.alt = 'Avatar';
                        newImg.id = 'avatar-preview';

                        // Replace icon with image
                        if (icon) {
                            icon.parentNode.replaceChild(newImg, icon);
                        }
                    }
                };
                reader.readAsDataURL(file);

                // Auto submit form
                document.getElementById('avatar-form').submit();
            }
        });
    </script>
@endsection
