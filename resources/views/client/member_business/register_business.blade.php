@extends('client.layout')

@section('title', 'Đăng ký doanh nghiệp')

@section('style_page')
    <link rel="stylesheet" href="{{ asset('assets/client/css/register_business.css') }}">
@endsection
@section('script_page')
    <script src="{{ asset('assets/client/js/register_business.js') }}"></script>
@endsection

@section('description', 'Đăng ký tài khoản doanh nghiệp')

@section('layoutContent')
    <div class="register-business-container">
        <div class="register-form-wrapper">
            <div class="register-card">
                <div class="register-header">
                    <h2 class="register-title">Đăng ký tài khoản</h2>

                    <!-- Tab navigation -->
                    <div class="tab-navigation">
                        <button type="button" class="tab-btn">Du khách</button>
                        <button type="button" class="tab-btn active">Doanh nghiệp</button>
                    </div>
                </div>

                <div class="register-body">
                    <!-- Hiển thị thông báo lỗi -->
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('client.register_business.store') }}"
                        enctype="multipart/form-data" class="register-form">
                        @csrf
                        <!-- Tên công ty -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <input type="text" class="form-input font-light" id="company_name" name="company_name"
                                    placeholder="Tên công ty của bạn" value="{{ old('company_name') }}" required>
                                <div class="input-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2 21.25C1.58579 21.25 1.25 21.5858 1.25 22C1.25 22.4142 1.58579 22.75 2 22.75V21.25ZM22 22.75C22.4142 22.75 22.75 22.4142 22.75 22C22.75 21.5858 22.4142 21.25 22 21.25V22.75ZM12 2L12.2785 1.30364C12.0475 1.21122 11.7856 1.23943 11.5795 1.37895C11.3734 1.51847 11.25 1.75113 11.25 2H12ZM17 2H17.75C17.75 1.75113 17.6266 1.51847 17.4205 1.37895C17.2144 1.23943 16.9525 1.21122 16.7215 1.30364L17 2ZM17 5L17.2785 5.69636C17.5633 5.58246 17.75 5.30668 17.75 5H17ZM11.25 8C11.25 8.41421 11.5858 8.75 12 8.75C12.4142 8.75 12.75 8.41421 12.75 8H11.25ZM4 22H3.25C3.25 22.4142 3.58579 22.75 4 22.75V22ZM20 22V22.75C20.4142 22.75 20.75 22.4142 20.75 22H20ZM4.58 11.25C4.16579 11.25 3.83 11.5858 3.83 12C3.83 12.4142 4.16579 12.75 4.58 12.75V11.25ZM19.42 12.75C19.8342 12.75 20.17 12.4142 20.17 12C20.17 11.5858 19.8342 11.25 19.42 11.25V12.75ZM2 22V22.75H22V22V21.25H2V22ZM12 2L11.7215 2.69636C13.5003 3.40788 15.4997 3.40788 17.2785 2.69636L17 2L16.7215 1.30364C15.3003 1.87212 13.6997 1.87212 12.2785 1.30364L12 2ZM17 2H16.25V5H17H17.75V2H17ZM17 5L16.7215 4.30364C15.3003 4.87212 13.6997 4.87212 12.2785 4.30364L12 5L11.7215 5.69636C13.5003 6.40788 15.4997 6.40788 17.2785 5.69636L17 5ZM12 5H12.75V2H12H11.25V5H12ZM12 5H11.25V8H12H12.75V5H12ZM17 8V7.25H7V8V8.75H17V8ZM7 8V7.25C5.88439 7.25 4.90993 7.52941 4.21967 8.21967C3.52941 8.90993 3.25 9.88439 3.25 11H4H4.75C4.75 10.1156 4.97059 9.59007 5.28033 9.28033C5.59007 8.97059 6.11561 8.75 7 8.75V8ZM4 11H3.25V22H4H4.75V11H4ZM4 22V22.75H20V22V21.25H4V22ZM20 22H20.75V11H20H19.25V22H20ZM20 11H20.75C20.75 9.88439 20.4706 8.90993 19.7803 8.21967C19.0901 7.52941 18.1156 7.25 17 7.25V8V8.75C17.8844 8.75 18.4099 8.97059 18.7197 9.28033C19.0294 9.59007 19.25 10.1156 19.25 11H20ZM4.58 12V12.75H19.42V12V11.25H4.58V12ZM7.99 12H7.24V22H7.99H8.74V12H7.99ZM11.99 12H11.24V22H11.99H12.74V12H11.99ZM15.99 12H15.24V22H15.99H16.74V12H15.99Z"
                                            fill="#3B3B3B" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <!-- Email liên hệ -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <input type="email" class="form-input font-light" id="email" name="email"
                                    placeholder="Email liên hệ" value="{{ old('email') }}" required>
                                <div class="input-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17 9L13.87 11.5C12.84 12.32 11.15 12.32 10.12 11.5L7 9M17 20.5H7C4 20.5 2 19 2 15.5V8.5C2 5 4 3.5 7 3.5H17C20 3.5 22 5 22 8.5V15.5C22 19 20 20.5 17 20.5Z"
                                            stroke="#3B3B3B" stroke-width="1.5" stroke-miterlimit="10"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <!-- Họ tên người đại diện -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <input type="text" class="form-input font-light" id="representative_name"
                                    name="representative_name" placeholder="Họ tên người đại diện"
                                    value="{{ old('representative_name') }}" required>
                                <div class="input-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15.9999 16.66C15.9999 14.86 14.2099 13.4 11.9999 13.4C9.78991 13.4 7.99991 14.86 7.99991 16.66M21.0799 8.58003V15.42C21.0799 16.54 20.4799 17.58 19.5099 18.15L13.5699 21.58C12.5999 22.14 11.3999 22.14 10.4199 21.58L4.47991 18.15C3.50991 17.59 2.90991 16.55 2.90991 15.42V8.58003C2.90991 7.46003 3.50991 6.41999 4.47991 5.84999L10.4199 2.42C11.3899 1.86 12.5899 1.86 13.5699 2.42L19.5099 5.84999C20.4799 6.41999 21.0799 7.45003 21.0799 8.58003ZM14.3299 8.66998C14.3299 9.95681 13.2867 11 11.9999 11C10.7131 11 9.66991 9.95681 9.66991 8.66998C9.66991 7.38316 10.7131 6.34003 11.9999 6.34003C13.2867 6.34003 14.3299 7.38316 14.3299 8.66998Z"
                                            stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Số điện thoại -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <input type="tel" class="form-input font-light" id="phone" name="phone"
                                    placeholder="Số điện thoại" value="{{ old('phone') }}" required>
                                <div class="input-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M21.97 18.33C21.97 18.69 21.89 19.06 21.72 19.42C21.55 19.78 21.33 20.12 21.04 20.44C20.55 20.98 20.01 21.37 19.4 21.62C18.8 21.87 18.15 22 17.45 22C16.43 22 15.34 21.76 14.19 21.27C13.04 20.78 11.89 20.12 10.75 19.29C9.6 18.45 8.51 17.52 7.47 16.49C6.44 15.45 5.51 14.36 4.68 13.22C3.86 12.08 3.2 10.94 2.72 9.81C2.24 8.67 2 7.58 2 6.54C2 5.86 2.12 5.21 2.36 4.61C2.6 4 2.98 3.44 3.51 2.94C4.15 2.31 4.85 2 5.59 2C5.87 2 6.15 2.06 6.4 2.18C6.66 2.3 6.89 2.48 7.07 2.74L9.39 6.01C9.57 6.26 9.7 6.49 9.79 6.71C9.88 6.92 9.93 7.13 9.93 7.32C9.93 7.56 9.86 7.8 9.72 8.03C9.59 8.26 9.4 8.5 9.16 8.74L8.4 9.53C8.29 9.64 8.24 9.77 8.24 9.93C8.24 10.01 8.25 10.08 8.27 10.16C8.3 10.24 8.33 10.3 8.35 10.36C8.53 10.69 8.84 11.12 9.28 11.64C9.73 12.16 10.21 12.69 10.73 13.22C11.27 13.75 11.79 14.24 12.32 14.69C12.84 15.13 13.27 15.43 13.61 15.61C13.66 15.63 13.72 15.66 13.79 15.69C13.87 15.72 13.95 15.73 14.04 15.73C14.21 15.73 14.34 15.67 14.45 15.56L15.21 14.81C15.46 14.56 15.7 14.37 15.93 14.25C16.16 14.11 16.39 14.04 16.64 14.04C16.83 14.04 17.03 14.08 17.25 14.17C17.47 14.26 17.7 14.39 17.95 14.56L21.26 16.91C21.52 17.09 21.7 17.3 21.81 17.55C21.91 17.8 21.97 18.05 21.97 18.33Z"
                                            stroke="#3B3B3B" stroke-width="1.5" stroke-miterlimit="10" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Tên đăng nhập tài khoản -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <input type="text" class="form-input font-light" id="username" name="username"
                                    placeholder="Tên đăng nhập tài khoản" value="{{ old('username') }}" required>
                                <div class="input-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M3.40991 22C3.40991 18.13 7.25991 15 11.9999 15C12.9599 15 13.8899 15.13 14.7599 15.37M16.4399 18L17.4299 18.99L19.5599 17.02M16.9999 7C16.9999 9.76142 14.7613 12 11.9999 12C9.23848 12 6.99991 9.76142 6.99991 7C6.99991 4.23858 9.23848 2 11.9999 2C14.7613 2 16.9999 4.23858 16.9999 7ZM21.9999 18C21.9999 18.75 21.7899 19.46 21.4199 20.06C21.2099 20.42 20.9399 20.74 20.6299 21C19.9299 21.63 19.0099 22 17.9999 22C16.5399 22 15.2699 21.22 14.5799 20.06C14.2099 19.46 13.9999 18.75 13.9999 18C13.9999 16.74 14.5799 15.61 15.4999 14.88C16.1899 14.33 17.0599 14 17.9999 14C20.2099 14 21.9999 15.79 21.9999 18Z"
                                            stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Mật khẩu -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <input type="password" class="form-input font-light" id="password" name="password"
                                    placeholder="Mật khẩu" required>
                                <div class="field-icon password-toggle" onclick="togglePasswordField('password')"
                                    style="cursor: pointer;">
                                    <svg id="password-hide-icon" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M14.53 9.47L9.47 14.53M14.53 9.47C13.88 8.82 12.99 8.42 12 8.42C10.02 8.42 8.42 10.02 8.42 12C8.42 12.99 8.82 13.88 9.47 14.53M14.53 9.47L22 2M9.47 14.53L2 22M17.82 5.77C16.07 4.45 14.07 3.73 12 3.73C8.47 3.73 5.18 5.81 2.89 9.41C1.99 10.82 1.99 13.19 2.89 14.6C3.68 15.84 4.6 16.91 5.6 17.77M8.42 19.53C9.56 20.01 10.77 20.27 12 20.27C15.53 20.27 18.82 18.19 21.11 14.59C22.01 13.18 22.01 10.81 21.11 9.4C20.78 8.88 20.42 8.39 20.05 7.93M15.51 12.7C15.25 14.11 14.1 15.26 12.69 15.52"
                                            stroke="black" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>


                                    <svg id="password-show-icon" width="25" height="24" viewBox="0 0 25 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">
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
                        </div>

                        <!-- Nhập lại mật khẩu -->
                        <div class="form-group">
                            <div class="input-wrapper">
                                <input type="password" class="form-input font-light" id="password_confirmation"
                                    name="password_confirmation" placeholder="Nhập lại mật khẩu" required>
                                <div class="field-icon password-toggle"
                                    onclick="togglePasswordField('password_confirmation')" style="cursor: pointer;">
                                    <svg id="password-hide-icon" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M14.53 9.47L9.47 14.53M14.53 9.47C13.88 8.82 12.99 8.42 12 8.42C10.02 8.42 8.42 10.02 8.42 12C8.42 12.99 8.82 13.88 9.47 14.53M14.53 9.47L22 2M9.47 14.53L2 22M17.82 5.77C16.07 4.45 14.07 3.73 12 3.73C8.47 3.73 5.18 5.81 2.89 9.41C1.99 10.82 1.99 13.19 2.89 14.6C3.68 15.84 4.6 16.91 5.6 17.77M8.42 19.53C9.56 20.01 10.77 20.27 12 20.27C15.53 20.27 18.82 18.19 21.11 14.59C22.01 13.18 22.01 10.81 21.11 9.4C20.78 8.88 20.42 8.39 20.05 7.93M15.51 12.7C15.25 14.11 14.1 15.26 12.69 15.52"
                                            stroke="black" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>

                                    <svg id="password-confirmation-show-icon" width="25" height="24"
                                        viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                        style="display: none;">
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
                        </div>

                        <!-- Nút đăng ký -->
                        <button type="submit" class="register-btn">
                            <i class="fas fa-user-plus"></i>
                            Đăng ký
                        </button>

                        <!-- Điều khoản -->
                        <div class="terms-text font-light">
                            Bằng cách đăng ký, bạn chấp nhận
                            <a href="#" class="terms-link">Điều khoản sử dụng</a> và
                            <a href="#" class="terms-link">Chính sách về quyền riêng tư</a> &
                            <a href="#" class="terms-link">Cookie</a> của chúng tôi.
                        </div>

                        <!-- Link đăng nhập -->
                        <div class="login-link font-light">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16 16.66C16 14.86 14.21 13.4 12 13.4C9.79003 13.4 8.00003 14.86 8.00003 16.66M21.08 8.58003V15.42C21.08 16.54 20.48 17.58 19.51 18.15L13.57 21.58C12.6 22.14 11.4 22.14 10.42 21.58L4.48003 18.15C3.51003 17.59 2.91003 16.55 2.91003 15.42V8.58003C2.91003 7.46003 3.51003 6.41999 4.48003 5.84999L10.42 2.42C11.39 1.86 12.59 1.86 13.57 2.42L19.51 5.84999C20.48 6.41999 21.08 7.45003 21.08 8.58003ZM14.33 8.66998C14.33 9.95681 13.2869 11 12 11C10.7132 11 9.67003 9.95681 9.67003 8.66998C9.67003 7.38316 10.7132 6.34003 12 6.34003C13.2869 6.34003 14.33 7.38316 14.33 8.66998Z"
                                    stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Đã có tài khoản?
                            <a href="{{ route('client.login_business') }}" class="login-link-text">Đăng nhập tại đây</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
