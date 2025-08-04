@extends('client.layout')

@section('title', 'Đăng nhập doanh nghiệp')
@section('style_page')
    <link rel="stylesheet" href="{{ asset('assets/client/css/login_business.css') }}">
@endsection
@section('script_page')
    <script src="{{ asset('assets/client/js/login_business.js') }}"></script>
@endsection
@section('description', 'Đăng nhập tài khoản doanh nghiệp')

@section('layoutContent')
    <div class="login-page">
        <div class="nav-container h-100">
            <div class="row justify-content-center align-items-center">

                <div class="login-container row my-5 p-0">
                    <!-- Left side - Illustration -->
                    <div class="col-md-6 p-0">
                        <div class="login-illustration">
                            <img class="image-log w-100 h-auto" src="{{ asset('/img/images/login.jpg') }}" alt="">
                        </div>
                    </div>

                    <!-- Right side - Login Form -->
                    <div class="col-md-6 login-right">
                        <div class="login-header">
                            <h2>Đăng nhập tài khoản</h2>
                            <!-- Tab buttons -->
                            <div class="tab-buttons">
                                <button type="button" class="tab-btn" onclick="switchTab('guest')">Du khách</button>
                                <button type="button" class="tab-btn active" onclick="switchTab('business')">Doanh
                                    nghiệp</button>
                            </div>
                            <p class="login-description">
                                Vui lòng đăng nhập vào Tài khoản Đối tác Kikichy của bạn bằng địa chỉ email và mật khẩu
                                để tiếp tục
                            </p>
                        </div>
                        <div class="login-form">
                            <!-- Hiển thị thông báo thành công -->
                            @if (session('success'))
                                <div class="alert alert-success mb-3">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <!-- Hiển thị thông báo lỗi -->
                            @if (session('error'))
                                <div class="alert alert-danger mb-3">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('client.login_business.store') }}">
                                @csrf
                                <!-- Email -->
                                <div class="form-group mb-2">
                                    <input type="text"
                                        class="form-control font-light @error('email') is-invalid @enderror" id="email"
                                        name="email" placeholder="Email hoặc tên đăng nhập" value="{{ old('email') }}"
                                        required>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="field-icon">
                                        <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17.5 9L14.37 11.5C13.34 12.32 11.65 12.32 10.62 11.5L7.5 9M17.5 20.5H7.5C4.5 20.5 2.5 19 2.5 15.5V8.5C2.5 5 4.5 3.5 7.5 3.5H17.5C20.5 3.5 22.5 5 22.5 8.5V15.5C22.5 19 20.5 20.5 17.5 20.5Z"
                                                stroke="#3B3B3B" stroke-width="1.5" stroke-miterlimit="10"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="form-group mb-3">
                                    <input type="password"
                                        class="form-control font-light @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Mật khẩu" required>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="field-icon password-toggle" onclick="togglePassword()"
                                        style="cursor: pointer;">
                                        <svg id="password-hide-icon" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M14.53 9.47L9.47 14.53M14.53 9.47C13.88 8.82 12.99 8.42 12 8.42C10.02 8.42 8.42 10.02 8.42 12C8.42 12.99 8.82 13.88 9.47 14.53M14.53 9.47L22 2M9.47 14.53L2 22M17.82 5.77C16.07 4.45 14.07 3.73 12 3.73C8.47 3.73 5.18 5.81 2.89 9.41C1.99 10.82 1.99 13.19 2.89 14.6C3.68 15.84 4.6 16.91 5.6 17.77M8.42 19.53C9.56 20.01 10.77 20.27 12 20.27C15.53 20.27 18.82 18.19 21.11 14.59C22.01 13.18 22.01 10.81 21.11 9.4C20.78 8.88 20.42 8.39 20.05 7.93M15.51 12.7C15.25 14.11 14.1 15.26 12.69 15.52"
                                                stroke="black" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>


                                        <svg id="password-show-icon" width="25" height="24" viewBox="0 0 25 24"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M16.08 11.9997C16.08 13.9797 14.48 15.5797 12.5 15.5797C10.52 15.5797 8.92003 13.9797 8.92003 11.9997C8.92003 10.0197 10.52 8.41973 12.5 8.41973C14.48 8.41973 16.08 10.0197 16.08 11.9997Z"
                                                stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M12.5 20.2697C16.03 20.2697 19.32 18.1897 21.61 14.5897C22.51 13.1797 22.51 10.8097 21.61 9.39973C19.32 5.79973 16.03 3.71973 12.5 3.71973C8.97003 3.71973 5.68003 5.79973 3.39003 9.39973C2.49003 10.8097 2.49003 13.1797 3.39003 14.5897C5.68003 18.1897 8.97003 20.2697 12.5 20.2697Z"
                                                stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Login button -->
                                <button type="submit" class="login-button">
                                    <svg width="21" height="20" viewBox="0 0 21 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.91666 6.3002C8.17499 3.3002 9.71666 2.0752 13.0917 2.0752H13.2C16.925 2.0752 18.4167 3.56686 18.4167 7.29186V12.7252C18.4167 16.4502 16.925 17.9419 13.2 17.9419H13.0917C9.74166 17.9419 8.19999 16.7335 7.92499 13.7835M2.16666 10.0002H12.9M11.0417 7.20853L13.8333 10.0002L11.0417 12.7919"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    Đăng nhập
                                </button>

                                <!-- Form links -->
                                <div class="form-links">
                                    <a class="font-light" href="#" class="forgot-password">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M5.74166 14.5749L7.65833 16.4916M16.4917 12.4416C14.775 14.1499 12.3167 14.6749 10.1583 13.9999L6.23333 17.9166C5.95 18.2083 5.39166 18.3833 4.99166 18.3249L3.175 18.0749C2.575 17.9916 2.01667 17.4249 1.925 16.8249L1.675 15.0083C1.61667 14.6083 1.80833 14.0499 2.08333 13.7666L6 9.84994C5.33333 7.68327 5.85 5.22494 7.56666 3.5166C10.025 1.05827 14.0167 1.05827 16.4833 3.5166C18.95 5.97494 18.95 9.98327 16.4917 12.4416ZM13.3333 7.9166C13.3333 8.60696 12.7737 9.1666 12.0833 9.1666C11.393 9.1666 10.8333 8.60696 10.8333 7.9166C10.8333 7.22625 11.393 6.6666 12.0833 6.6666C12.7737 6.6666 13.3333 7.22625 13.3333 7.9166Z"
                                                stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        Quên mật khẩu?
                                    </a>
                                </div>

                                <div class="form-links">
                                    <a class="font-light" href="{{ route('client.register_business') }}">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17.1583 18.3332C17.1583 15.1082 13.95 12.4998 10 12.4998C6.05 12.4998 2.84167 15.1082 2.84167 18.3332M14.1667 5.83317C14.1667 8.13436 12.3012 9.99984 10 9.99984C7.69881 9.99984 5.83333 8.13436 5.83333 5.83317C5.83333 3.53198 7.69881 1.6665 10 1.6665C12.3012 1.6665 14.1667 3.53198 14.1667 5.83317Z"
                                                stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        Bạn chưa có tài khoản? <span style="color: #DC3545;">Đăng ký tại đây</span>
                                    </a>
                                </div>

                                <!-- Terms -->
                                <p class="terms-text">
                                    Bằng cách đăng nhập, bạn chấp nhận <a href="#">Điều khoản sử dụng</a> và
                                    <a href="#">Chính sách về quyền riêng tư & Cookie</a> của chúng tôi.
                                </p>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
