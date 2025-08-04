<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
    <div class="{{ !empty($containerNav) ? $containerNav : 'container-fluid' }}">
        <div class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
            <div class="text-body mb-2 mb-md-0" style="color: #d9d9d9 !important">
                ©
                <script>
                    document.write(new Date().getFullYear())
                </script> <span class="text-danger"><i class="tf-icons mdi mdi-heart"></i></span> <a
                    href="{{ !empty(config('variables.creatorUrl')) ? config('variables.creatorUrl') : '' }}"
                    target="_blank"
                    class="footer-link fw-medium" style="color: white">{{ !empty(config('variables.creatorName')) ? config('variables.creatorName') : '' }}</a>
            </div>

        </div>
    </div>
</footer>
<!--/ Footer-->
