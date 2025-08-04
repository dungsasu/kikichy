// Validate form
$(document).ready(function () {
    console.log('Register business JavaScript loaded');
    
    // Toggle password visibility
    $('#togglePassword').on('click', function () {
        const passwordField = $('#password');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $(this).toggleClass('fa-eye fa-eye-slash');
    });

    $('#togglePasswordConfirm').on('click', function () {
        const passwordField = $('#password_confirmation');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $(this).toggleClass('fa-eye fa-eye-slash');
    });

    // Form validation
    $('.register-form').on('submit', function (e) {
        // Clear all existing validation errors first
        $('.password-error, .confirm-password-error').remove();
        $('.form-input').removeClass('is-invalid');

        // Check if there are any duplicate errors
        if ($('.duplicate-error').length > 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Vui lòng sửa các lỗi trùng lặp trước khi đăng ký!'
            });
            return false;
        }

        const password = $('#password').val();
        const confirmPassword = $('#password_confirmation').val();
        const phone = $('#phone').val();
        const email = $('#email').val();
        const companyName = $('#company_name').val();
        const representativeName = $('#representative_name').val();
        const username = $('#username').val();

        // Validate required fields
        if (!companyName.trim()) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Vui lòng nhập tên công ty!'
            });
            return false;
        }

        if (!email.trim()) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Vui lòng nhập email!'
            });
            return false;
        }

        if (!representativeName.trim()) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Vui lòng nhập tên người đại diện!'
            });
            return false;
        }

        if (!username.trim()) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Vui lòng nhập tên đăng nhập!'
            });
            return false;
        }

        // Validate phone number
        if (!/^\d{10}$/.test(phone)) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Số điện thoại phải có đúng 10 số!'
            });
            return false;
        }

        // Validate email format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Email không hợp lệ!'
            });
            return false;
        }

        // Validate password length
        if (password.length < 6) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Mật khẩu phải có ít nhất 6 ký tự!'
            });
            return false;
        }

        // Validate password starts with uppercase letter
        if (!/^[A-Z]/.test(password)) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Mật khẩu phải bắt đầu bằng chữ cái viết hoa!'
            });
            return false;
        }

        // Validate password contains at least one number
        if (!/\d/.test(password)) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Mật khẩu phải chứa ít nhất 1 số!'
            });
            return false;
        }

        // Validate password confirmation
        if (password !== confirmPassword) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Mật khẩu xác nhận không khớp!'
            });
            return false;
        }
    });

    // Tab switching (for future implementation)
    $('.tab-btn').on('click', function () {
        $('.tab-btn').removeClass('active');
        $(this).addClass('active');
    });

    // Real-time password validation
    $('#password').on('input', function() {
        const password = $(this).val();
        let isValid = true;
        let errorMessage = '';

        // Remove all existing error messages first
        $(this).closest('.form-group').find('.password-error').remove();
        $(this).removeClass('is-invalid');

        if (password.length > 0) {
            if (password.length < 6) {
                isValid = false;
                errorMessage = 'Mật khẩu phải có ít nhất 6 ký tự';
            } else if (!/^[A-Z]/.test(password)) {
                isValid = false;
                errorMessage = 'Mật khẩu phải bắt đầu bằng chữ cái viết hoa';
            } else if (!/\d/.test(password)) {
                isValid = false;
                errorMessage = 'Mật khẩu phải chứa ít nhất 1 số';
            }
            
            if (!isValid) {
                $(this).closest('.form-group').append('<div class="password-error text-danger small mt-1">' + errorMessage + '</div>');
                $(this).addClass('is-invalid');
            }
        }

        // Trigger validation for password confirmation field
        $('#password_confirmation').trigger('input');
    });

    // Real-time password confirmation validation
    $('#password_confirmation').on('input', function() {
        const password = $('#password').val();
        const confirmPassword = $(this).val();

        // Remove all existing error messages first
        $(this).closest('.form-group').find('.confirm-password-error').remove();
        $(this).removeClass('is-invalid');

        if (confirmPassword.length > 0 && password !== confirmPassword) {
            $(this).closest('.form-group').append('<div class="confirm-password-error text-danger small mt-1">Mật khẩu xác nhận không khớp</div>');
            $(this).addClass('is-invalid');
        }
    });

    // Phone number validation - only allow numbers and max 10 digits

    // Clear validation errors when user focuses on input
    $('.form-input').on('focus', function() {
        $(this).removeClass('is-invalid');
        $(this).closest('.form-group').find('.password-error, .confirm-password-error, .duplicate-error, .duplicate-success, .checking-duplicate').remove();
    });

    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Check duplicate email with immediate feedback
    $('#email').on('input', debounce(function() {
        console.log('Email input event triggered');
        const email = $(this).val().trim();
        console.log('Email value:', email);
        if (email && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            checkDuplicate('email', email, $(this));
        } else if (email && email.length > 3) {
            // Clear previous messages if email is invalid format
            $(this).closest('.form-group').find('.duplicate-error, .duplicate-success, .checking-duplicate').remove();
        }
    }, 500));

    $('#email').on('blur', function() {
        const email = $(this).val().trim();
        if (email && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            checkDuplicate('email', email, $(this));
        }
    });

    // Check duplicate username with immediate feedback
    $('#username').on('input', debounce(function() {
        const username = $(this).val().trim();
        if (username && username.length >= 3) {
            checkDuplicate('username', username, $(this));
        } else if (username && username.length > 0 && username.length < 3) {
            // Clear previous messages if username is too short
            $(this).closest('.form-group').find('.duplicate-error, .duplicate-success, .checking-duplicate').remove();
        }
    }, 500));

    $('#username').on('blur', function() {
        const username = $(this).val().trim();
        if (username && username.length >= 3) {
            checkDuplicate('username', username, $(this));
        }
    });

    // Check duplicate phone with immediate feedback
    $('#phone').on('input', debounce(function() {
        const phone = $(this).val().trim();
        if (phone && /^\d{10}$/.test(phone)) {
            checkDuplicate('phone', phone, $(this));
        } else if (phone && phone.length > 5) {
            // Clear previous messages if phone is invalid format
            $(this).closest('.form-group').find('.duplicate-error, .duplicate-success, .checking-duplicate').remove();
        }
    }, 500));

    $('#phone').on('blur', function() {
        const phone = $(this).val().trim();
        if (phone && /^\d{10}$/.test(phone)) {
            checkDuplicate('phone', phone, $(this));
        }
    });

    // Function to check duplicate
    function checkDuplicate(type, value, element) {
        console.log('Checking duplicate for:', type, value);
        
        // Remove existing duplicate error and loading
        element.closest('.form-group').find('.duplicate-error, .checking-duplicate, .duplicate-success').remove();
        element.removeClass('is-invalid');
        
        // Show checking indicator
        element.closest('.form-group').append('<div class="checking-duplicate text-info small mt-1"><i class="fas fa-spinner fa-spin"></i> Đang kiểm tra...</div>');
        
        $.ajax({
            url: '/check-duplicate',
            method: 'POST',
            data: {
                type: type,
                value: value,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Response:', response);
                // Remove checking indicator
                element.closest('.form-group').find('.checking-duplicate').remove();
                
                if (response.exists) {
                    element.closest('.form-group').append('<div class="duplicate-error text-danger small mt-1"><i class="fas fa-times-circle"></i> ' + response.message + '</div>');
                    element.addClass('is-invalid');
                } else {
                    // Show success message briefly
                    element.closest('.form-group').append('<div class="duplicate-success text-success small mt-1"><i class="fas fa-check-circle"></i> Có thể sử dụng</div>');
                    setTimeout(function() {
                        element.closest('.form-group').find('.duplicate-success').fadeOut(function() {
                            $(this).remove();
                        });
                    }, 2000);
                }
            },
            error: function(xhr, status, error) {
                console.log('Error checking duplicate:', error);
                // Remove checking indicator
                element.closest('.form-group').find('.checking-duplicate').remove();
            }
        });
    }
   
});


function togglePasswordField(fieldId) {
    const passwordInput = document.getElementById(fieldId);

    if (fieldId === 'password') {
        const hideIcon = document.getElementById('password-hide-icon');
        const showIcon = document.getElementById('password-show-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            hideIcon.style.display = 'none';
            showIcon.style.display = 'block';
        } else {
            passwordInput.type = 'password';
            hideIcon.style.display = 'block';
            showIcon.style.display = 'none';
        }
    } else if (fieldId === 'password_confirmation') {
        const hideIcon = document.getElementById('password-confirmation-hide-icon');
        const showIcon = document.getElementById('password-confirmation-show-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            hideIcon.style.display = 'none';
            showIcon.style.display = 'block';
        } else {
            passwordInput.type = 'password';
            hideIcon.style.display = 'block';
            showIcon.style.display = 'none';
        }
    }
}

// Hàm cũ để tương thích ngược
function togglePassword() {
    togglePasswordField('password');
}

// Thêm event listener khi DOM đã load
document.addEventListener('DOMContentLoaded', function () {
    // Khởi tạo trạng thái ban đầu cho mật khẩu
    const passwordInput = document.getElementById('password');
    const hideIcon = document.getElementById('password-hide-icon');
    const showIcon = document.getElementById('password-show-icon');

    if (passwordInput && hideIcon && showIcon) {
        passwordInput.type = 'password';
        hideIcon.style.display = 'block';
        showIcon.style.display = 'none';
    }

    // Khởi tạo trạng thái ban đầu cho xác nhận mật khẩu
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const confirmHideIcon = document.getElementById('password-confirmation-hide-icon');
    const confirmShowIcon = document.getElementById('password-confirmation-show-icon');

    if (passwordConfirmationInput && confirmHideIcon && confirmShowIcon) {
        passwordConfirmationInput.type = 'password';
        confirmHideIcon.style.display = 'block';
        confirmShowIcon.style.display = 'none';
    }
});