@php
    $containerNav = $containerNav ?? 'container-fluid';
    $navbarDetached = $navbarDetached ?? '';

@endphp

<!-- Navbar -->
@if (isset($navbarDetached) && $navbarDetached == 'navbar-detached')
    <nav class="layout-navbar {{ $containerNav }} navbar navbar-expand-xl {{ $navbarDetached }} align-items-center bg-navbar-theme"
        id="layout-navbar">
@endif
@if (isset($navbarDetached) && $navbarDetached == '')
    <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="{{ $containerNav }}">
@endif

<!--  Brand demo (display only for navbar-full and hide on below xl) -->
@if (isset($navbarFull))
    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="{{ url('/') }}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">
                @include('_partials.macros', ['height' => 20])
            </span>
            <span class="app-brand-text demo menu-text fw-semibold ms-1">{{ config('variables.templateName') }}</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="mdi menu-toggle-icon d-xl-block align-middle mdi-20px"></i>
        </a>
    </div>
@endif

<!-- ! Not required for layout-without-menu -->
@if (!isset($navbarHideToggle))
    <div
        class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="mdi mdi-menu mdi-24px"></i>
        </a>
    </div>
@endif

@if (Route::currentRouteName() !== 'edit_user')
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        {{-- <div class="navbar-nav align-items-center">
        <div class="nav-item d-flex align-items-center">
            <i class="mdi mdi-magnify mdi-24px lh-0"></i>
            <input type="text" class="form-control border-0 shadow-none bg-body" placeholder="Search..."
                aria-label="Search...">
        </div>
    </div> --}}
        <!-- /Search -->
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- User -->
            <li>
                <div class="dropdown">
                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ App::getLocale() == 'vi' ? 'VN' : 'EN' }}
                    </button>
                    <ul class="dropdown-menu">
                        @if (App::getLocale() == 'vi')
                            <li><a class="dropdown-item" href="{{ route('locale.change', ['locale' => 'en']) }}">EN</a>
                            </li>
                        @elseif(App::getLocale() == 'en')
                            <li><a class="dropdown-item" href="{{ route('locale.change', ['locale' => 'vi']) }}">VN</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ @$user->image }}" onerror="this.src='{{ asset('img/no-image.png') }}'"
                            style="object-fit: cover" alt class="w-px-40 h-px-40 rounded-circle">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end mt-3 py-2">
                    <li>
                        <a class="dropdown-item pb-2 mb-1" href="javascript:void(0);">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2 pe-1">
                                    <div class="avatar avatar-online">
                                        <img src="{{ @$user->image }}"
                                            style="width: 100% !important; height: 100% !important"
                                            onerror="this.src='{{ asset('img/no-image.png') }}'" alt
                                            class="w-px-40 h-auto rounded-circle">
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ @$user->username }}</h6>
                                    <small class="text-muted">{{ @$user->fullname }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" $class>
                            <i class="mdi mdi-account-outline me-1 mdi-20px"></i>
                            <span class="align-middle">Trang cá nhân</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                            <i class='mdi mdi-power me-1 mdi-20px'></i>
                            <span class="align-middle">Đăng xuât</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
@endif

@if (!isset($navbarDetached))
    </div>
@endif
</nav>
<!-- / Navbar -->
