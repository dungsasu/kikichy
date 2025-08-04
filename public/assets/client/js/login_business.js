function switchTab(type) {
    const tabs = document.querySelectorAll('.tab-btn');
    tabs.forEach(tab => tab.classList.remove('active'));

    if (type === 'guest') {
        tabs[0].classList.add('active');
        // Redirect to guest login or handle guest login
        console.log('Switch to guest login');
    } else if (type === 'business') {
        tabs[1].classList.add('active');
        // Already on business login page
        console.log('Business login selected');
    }
}

function togglePassword() {
    const passwordInput = document.getElementById('password');
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
}

// Thêm event listener khi DOM đã load
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo trạng thái ban đầu
    const passwordInput = document.getElementById('password');
    const hideIcon = document.getElementById('password-hide-icon');
    const showIcon = document.getElementById('password-show-icon');
    
    if (passwordInput && hideIcon && showIcon) {
        passwordInput.type = 'password';
        hideIcon.style.display = 'block';
        showIcon.style.display = 'none';
    }

    // Thêm validation cho form đăng nhập
    const loginForm = document.querySelector('form[action*="login_business"]');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            let hasError = false;

            // Clear previous errors
            clearErrors();

            // Validate email/username
            if (!email) {
                showError('email', 'Vui lòng nhập email hoặc tên đăng nhập');
                hasError = true;
            }

            // Validate password
            if (!password) {
                showError('password', 'Vui lòng nhập mật khẩu');
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
            }
        });
    }
});

function showError(fieldName, message) {
    const field = document.getElementById(fieldName);
    if (field) {
        field.classList.add('is-invalid');
        
        // Remove existing error message
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
        
        // Add new error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }
}

function clearErrors() {
    const errorFields = document.querySelectorAll('.is-invalid');
    errorFields.forEach(field => {
        field.classList.remove('is-invalid');
    });
    
    const errorMessages = document.querySelectorAll('.invalid-feedback');
    errorMessages.forEach(message => {
        message.remove();
    });
}