@extends('admin.layout')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/css/pages/page-auth.css') }}">
@endsection

@section('layoutContent')
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
    <div class="position-relative" style="z-index: 1; position: relative">
        <div class="authentication-wrapper authentication-basic container-p-y" >
            <div class="authentication-inner py-4">
                <div class="card p-2">
                    <div class="app-brand justify-content-center mt-5">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 20, 'withbg' => 'fill: #fff;'])</span>
                        </a>
                    </div>
                    <div class="card-body mt-2">
                        <h4 class="mb-2">Xin chào bạn đến với {{ config('variables.templateName') }}! 👋</h4>
                        <p class="mb-4">Hãy đăng nhập để tiếp tục</p>
                        <form id="formAuthentication" class="mb-3" action="{{ route('authenticate') }}" method="POST">
                            @csrf
                            <div class="form-floating form-floating-outline mb-3">
                                <input type="text" class="form-control" id="email" name="username"
                                    placeholder="Tài khoản của bạn" autofocus>
                                <label for="name">Tên tài khoản</label>
                            </div>
                            <div class="mb-3">
                                <div class="form-password-toggle">
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input type="password" id="password" class="form-control" name="password"
                                                placeholder="Mật khẩu của bạn" aria-describedby="password" />
                                            <label for="password">Mật khẩu</label>
                                        </div>
                                        <span class="input-group-text cursor-pointer"><i
                                                class="mdi mdi-eye-off-outline"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 d-flex justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember-me" value="1">
                                    <label class="form-check-label" for="remember-me">
                                        Ghi nhớ đăng nhập
                                    </label>
                                </div>
                                {{-- <a href="{{ url('auth/forgot-password-basic') }}" class="float-end mb-1">
                                <span>Forgot Password?</span>
                            </a> --}}
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Đăng nhập</button>
                            </div>
                        </form>

                        {{-- <p class="text-center">
                        <span>Bạn chưa có tài khoản</span>
                        <a href="{{ url('auth/register-basic') }}">
                            <span>Create an account</span>
                        </a>
                    </p> --}}
                    </div>
                </div>
                <!-- /Login -->

                <img src="{{ asset('img/illustrations/auth-basic-mask-light.png') }}"
                    class="authentication-image d-none d-lg-block" alt="triangle-bg">
                    <canvas style="position: absolute; bottom: 0px; z-index: -1; left: 0px" id="canvas"></canvas>

            </div>
        </div>
    </div>

@endsection

@section('script_page')
    <script src="{{ asset('assets/js/login.js') }}"></script>
@endsection
