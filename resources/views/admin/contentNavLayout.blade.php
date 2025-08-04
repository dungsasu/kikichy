@extends('admin.layout')

@php
    /* Display elements */
    $contentNavbar = true;
    $containerNav = $containerNav ?? 'container-xxl';
    $isNavbar = $isNavbar ?? true;
    $isMenu = $isMenu ?? true;
    $isFlex = $isFlex ?? false;
    $isFooter = $isFooter ?? true;

    /* HTML Classes */
    $navbarDetached = 'navbar-detached';

    /* Content classes */
    $container = $container ?? 'container-xxl';

@endphp

@section('layoutContent')
    <div class="layout-wrapper layout-content-navbar {{ $isMenu ? '' : 'layout-without-menu' }}">
        <div class="layout-container">
            @if ($isMenu)
                @include('admin.sections.menu.verticalMenu')
            @endif
            <!-- Layout page -->
            <div class="layout-page">
                <!-- BEGIN: Navbar-->
                @if ($isNavbar)
                    @include('admin/sections/navbar/navbar')
                @endif
                <!-- END: Navbar-->


                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content -->
                    @if ($isFlex)
                        <div class="{{ $container }} d-flex align-items-stretch flex-grow-1 p-0">
                        @else
                            <div class="{{ $container }} flex-grow-1 container-p-y">
                    @endif


                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                    @endif

                    @yield('content')

                </div>
                <!-- / Content -->

                <!-- Footer -->
                @if ($isFooter)
                    @include('admin/sections/footer/footer')
                @endif
                <!-- / Footer -->
                <div class="content-backdrop fade"></div>

                <div class="bs-toast toast fade" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; top: 30px; right: 30px;">
                    <div class="toast-header bg-success">
                        <div class="me-auto fw-medium" style="color: white">Thông báo</div>
                        <button type="button" class="btn-close"  data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        Hello, world! This is a toast message.
                    </div>
                </div>
            </div>
            <!--/ Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    @if ($isMenu)
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    @endif
    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->
@endsection
