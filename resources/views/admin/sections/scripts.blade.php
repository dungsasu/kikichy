<!-- BEGIN: Vendor JS-->
<script src="{{ asset(mix('assets/vendor/libs/jquery/jquery.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/popper/popper.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/js/bootstrap.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/node-waves/node-waves.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')) }}"></script>
<script src="{{ asset(mix('assets/vendor/js/menu.js')) }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js') }}">
</script>
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js') }}">
</script>

<script type="text/javascript" src="{{ asset('assets/js/jquery-menu-editor.js') }} "></script>
@yield('vendor-script')
<script src="{{ asset('assets/js/main.js') }}"></script>
@stack('pricing-script')
@yield('page-script')

