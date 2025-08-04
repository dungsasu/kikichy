@extends('client.member_business.layout')

@section('title', 'Hồ sơ doanh nghiệp - Tổng quan')

@section('page-title', 'Thông tin tổng quan')

@section('page-content')
    <!-- Company Info Form -->
    <div class="info-section">
        <form method="POST" action="{{ route('client.business.update_info') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="company_name">Tên công ty <span class="text-danger">*</span></label>
                        <div class="input-wrapper gap-2">
                            <input type="text" class="form-control-custom readonly"
                                id="company_name" name="company_name"
                                value="{{ Auth::guard('members')->user()->name }}"
                                placeholder="Victoriatour and General Commercial Company Limited"
                                readonly>
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                <path
                                    d="M10 1.5C14.6944 1.5 18.5 5.30558 18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5ZM10 3.5C6.41015 3.5 3.5 6.41015 3.5 10C3.5 13.5899 6.41015 16.5 10 16.5C13.5899 16.5 16.5 13.5899 16.5 10C16.5 6.41015 13.5899 3.5 10 3.5ZM10 12.333C10.5522 12.333 10.9998 12.7809 11 13.333V14.166C11 14.7183 10.5523 15.166 10 15.166C9.44772 15.166 9 14.7183 9 14.166V13.333C9.00018 12.7809 9.44782 12.333 10 12.333ZM10 4.83301C11.9329 4.83301 13.4998 6.40016 13.5 8.33301C13.5 10.266 11.933 11.833 10 11.833C9.44772 11.833 9 11.3853 9 10.833C9.00018 10.2809 9.44782 9.83301 10 9.83301C10.8284 9.83301 11.5 9.16143 11.5 8.33301C11.4998 7.50473 10.8283 6.83301 10 6.83301C9.17168 6.83301 8.50018 7.50473 8.5 8.33301C8.5 8.88529 8.05228 9.33301 7.5 9.33301C6.94772 9.33301 6.5 8.88529 6.5 8.33301C6.50018 6.40016 8.06711 4.83301 10 4.83301Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="email">Email liên hệ <span class="text-danger">*</span></label>
                        <div class="input-wrapper gap-2">
                            <input type="email" class="form-control-custom normal"
                                id="email" name="email"
                                value="{{ Auth::guard('members')->user()->email }}"
                                placeholder="benoitlabrunhie@VICTORIATOUR.COM.VN">
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                <path
                                    d="M10 1.5C14.6944 1.5 18.5 5.30558 18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5ZM10 3.5C6.41015 3.5 3.5 6.41015 3.5 10C3.5 13.5899 6.41015 16.5 10 16.5C13.5899 16.5 16.5 13.5899 16.5 10C16.5 6.41015 13.5899 3.5 10 3.5ZM10 12.333C10.5522 12.333 10.9998 12.7809 11 13.333V14.166C11 14.7183 10.5523 15.166 10 15.166C9.44772 15.166 9 14.7183 9 14.166V13.333C9.00018 12.7809 9.44782 12.333 10 12.333ZM10 4.83301C11.9329 4.83301 13.4998 6.40016 13.5 8.33301C13.5 10.266 11.933 11.833 10 11.833C9.44772 11.833 9 11.3853 9 10.833C9.00018 10.2809 9.44782 9.83301 10 9.83301C10.8284 9.83301 11.5 9.16143 11.5 8.33301C11.4998 7.50473 10.8283 6.83301 10 6.83301C9.17168 6.83301 8.50018 7.50473 8.5 8.33301C8.5 8.88529 8.05228 9.33301 7.5 9.33301C6.94772 9.33301 6.5 8.88529 6.5 8.33301C6.50018 6.40016 8.06711 4.83301 10 4.83301Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="representative">Người đại diện <span class="text-danger">*</span></label>
                        <div class="input-wrapper gap-2">
                            <input type="text" class="form-control-custom readonly"
                                id="representative" name="representative"
                                value="{{ Auth::guard('members')->user()->representative_name ?? '' }}"
                                placeholder="Tên người quản lý tài khoản" readonly>
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                <path
                                    d="M10 1.5C14.6944 1.5 18.5 5.30558 18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5ZM10 3.5C6.41015 3.5 3.5 6.41015 3.5 10C3.5 13.5899 6.41015 16.5 10 16.5C13.5899 16.5 16.5 13.5899 16.5 10C16.5 6.41015 13.5899 3.5 10 3.5ZM10 12.333C10.5522 12.333 10.9998 12.7809 11 13.333V14.166C11 14.7183 10.5523 15.166 10 15.166C9.44772 15.166 9 14.7183 9 14.166V13.333C9.00018 12.7809 9.44782 12.333 10 12.333ZM10 4.83301C11.9329 4.83301 13.4998 6.40016 13.5 8.33301C13.5 10.266 11.933 11.833 10 11.833C9.44772 11.833 9 11.3853 9 10.833C9.00018 10.2809 9.44782 9.83301 10 9.83301C10.8284 9.83301 11.5 9.16143 11.5 8.33301C11.4998 7.50473 10.8283 6.83301 10 6.83301C9.17168 6.83301 8.50018 7.50473 8.5 8.33301C8.5 8.88529 8.05228 9.33301 7.5 9.33301C6.94772 9.33301 6.5 8.88529 6.5 8.33301C6.50018 6.40016 8.06711 4.83301 10 4.83301Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="username">Tên đăng nhập <span class="text-danger">*</span></label>
                        <div class="input-wrapper gap-2">
                            <input type="text" class="form-control-custom readonly"
                                id="username" name="username"
                                value="{{ Auth::guard('members')->user()->username }}"
                                placeholder="Tên user đăng nhập" readonly>
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                <path
                                    d="M10 1.5C14.6944 1.5 18.5 5.30558 18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5ZM10 3.5C6.41015 3.5 3.5 6.41015 3.5 10C3.5 13.5899 6.41015 16.5 10 16.5C13.5899 16.5 16.5 13.5899 16.5 10C16.5 6.41015 13.5899 3.5 10 3.5ZM10 12.333C10.5522 12.333 10.9998 12.7809 11 13.333V14.166C11 14.7183 10.5523 15.166 10 15.166C9.44772 15.166 9 14.7183 9 14.166V13.333C9.00018 12.7809 9.44782 12.333 10 12.333ZM10 4.83301C11.9329 4.83301 13.4998 6.40016 13.5 8.33301C13.5 10.266 11.933 11.833 10 11.833C9.44772 11.833 9 11.3853 9 10.833C9.00018 10.2809 9.44782 9.83301 10 9.83301C10.8284 9.83301 11.5 9.16143 11.5 8.33301C11.4998 7.50473 10.8283 6.83301 10 6.83301C9.17168 6.83301 8.50018 7.50473 8.5 8.33301C8.5 8.88529 8.05228 9.33301 7.5 9.33301C6.94772 9.33301 6.5 8.88529 6.5 8.33301C6.50018 6.40016 8.06711 4.83301 10 4.83301Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="password">Mật khẩu <span class="text-danger">*</span></label>
                        <div class="input-wrapper">
                            <input type="password" class="form-control-custom normal"
                                id="password" name="password" placeholder="••••••••">
                            <i class="fas fa-eye-slash input-icon password-toggle" 
                               id="password-icon" onclick="togglePasswordProfile()"></i>
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                <path
                                    d="M10 1.5C14.6944 1.5 18.5 5.30558 18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5ZM10 3.5C6.41015 3.5 3.5 6.41015 3.5 10C3.5 13.5899 6.41015 16.5 10 16.5C13.5899 16.5 16.5 13.5899 16.5 10C16.5 6.41015 13.5899 3.5 10 3.5ZM10 12.333C10.5522 12.333 10.9998 12.7809 11 13.333V14.166C11 14.7183 10.5523 15.166 10 15.166C9.44772 15.166 9 14.7183 9 14.166V13.333C9.00018 12.7809 9.44782 12.333 10 12.333ZM10 4.83301C11.9329 4.83301 13.4998 6.40016 13.5 8.33301C13.5 10.266 11.933 11.833 10 11.833C9.44772 11.833 9 11.3853 9 10.833C9.00018 10.2809 9.44782 9.83301 10 9.83301C10.8284 9.83301 11.5 9.16143 11.5 8.33301C11.4998 7.50473 10.8283 6.83301 10 6.83301C9.17168 6.83301 8.50018 7.50473 8.5 8.33301C8.5 8.88529 8.05228 9.33301 7.5 9.33301C6.94772 9.33301 6.5 8.88529 6.5 8.33301C6.50018 6.40016 8.06711 4.83301 10 4.83301Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label for="confirm_password">Nhập lại mật khẩu <span class="text-danger">*</span></label>
                        <div class="input-wrapper">
                            <input type="password" class="form-control-custom normal"
                                id="confirm_password" name="password_confirmation" placeholder="••••••••">
                            <i class="fas fa-eye-slash input-icon password-toggle" 
                               id="confirm-password-icon" onclick="toggleConfirmPasswordProfile()"></i>
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg" class="input-icon">
                                <path
                                    d="M10 1.5C14.6944 1.5 18.5 5.30558 18.5 10C18.5 14.6944 14.6944 18.5 10 18.5C5.30558 18.5 1.5 14.6944 1.5 10C1.5 5.30558 5.30558 1.5 10 1.5ZM10 3.5C6.41015 3.5 3.5 6.41015 3.5 10C3.5 13.5899 6.41015 16.5 10 16.5C13.5899 16.5 16.5 13.5899 16.5 10C16.5 6.41015 13.5899 3.5 10 3.5ZM10 12.333C10.5522 12.333 10.9998 12.7809 11 13.333V14.166C11 14.7183 10.5523 15.166 10 15.166C9.44772 15.166 9 14.7183 9 14.166V13.333C9.00018 12.7809 9.44782 12.333 10 12.333ZM10 4.83301C11.9329 4.83301 13.4998 6.40016 13.5 8.33301C13.5 10.266 11.933 11.833 10 11.833C9.44772 11.833 9 11.3853 9 10.833C9.00018 10.2809 9.44782 9.83301 10 9.83301C10.8284 9.83301 11.5 9.16143 11.5 8.33301C11.4998 7.50473 10.8283 6.83301 10 6.83301C9.17168 6.83301 8.50018 7.50473 8.5 8.33301C8.5 8.88529 8.05228 9.33301 7.5 9.33301C6.94772 9.33301 6.5 8.88529 6.5 8.33301C6.50018 6.40016 8.06711 4.83301 10 4.83301Z"
                                    fill="#3B3B3B" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i>
                    Lưu lại
                </button>
            </div>
        </form>
    </div>
@endsection

@section('script_page')
    <script>
        function togglePasswordProfile() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('password-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.className = 'fas fa-eye input-icon password-toggle';
            } else {
                passwordInput.type = 'password';
                icon.className = 'fas fa-eye-slash input-icon password-toggle';
            }
        }

        function toggleConfirmPasswordProfile() {
            const passwordInput = document.getElementById('confirm_password');
            const icon = document.getElementById('confirm-password-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.className = 'fas fa-eye input-icon password-toggle';
            } else {
                passwordInput.type = 'password';
                icon.className = 'fas fa-eye-slash input-icon password-toggle';
            }
        }

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        });
    </script>
@endsection
