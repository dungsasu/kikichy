<!-- BEGIN: Vendor JS-->
<script src="{{asset('assets/admin/js/jquery.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js') }}">
</script>
<script type="text/javascript" src="{{ asset('assets/admin/js/jquery-menu-editor.js') }} "></script>
<script src="{{ asset('assets/admin/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/libs/node-waves/node-waves.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/js/menu.js') }}"></script>
<script src="{{ asset('assets/admin/js/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/select2.js')}}"></script>
<script src="{{ asset('assets/admin/js/sweet-alert.js')}}"></script>
<script src="{{ asset('assets/admin/js/ckfinder/ckfinder.js') }}"></script>
<script src="{{ asset('assets/admin/js/admin.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery-sortable.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.35.2/ace.js" integrity="sha512-MhhgjhTjy8gf0TeS/nBSTJtNrXAlawcIcTfdPiNQbIdCEmvgZSARRKSlsb8+IdAMn8f+FMCVAqCjNAIkPSPUWw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="{{ asset('assets/admin/js/main.js') }}"></script>

<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->
