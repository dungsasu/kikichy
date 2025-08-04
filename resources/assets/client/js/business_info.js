document.addEventListener('DOMContentLoaded', function() {
    // Form validation for business info
    const form = document.querySelector('form[action*="update_contact_info"]');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Clear previous validation errors
            clearValidationErrors();
            
            let isValid = true;
            
            // Validate required fields
            const requiredFields = [
                {name: 'company_legal_name', message: 'Vui lòng nhập tên pháp lý của công ty'},
                {name: 'phone', message: 'Vui lòng nhập số điện thoại'},
                {name: 'country_id', message: 'Vui lòng chọn quốc gia'},
                {name: 'city_id', message: 'Vui lòng chọn thành phố'},
                {name: 'address', message: 'Vui lòng nhập địa chỉ'}
            ];
            
            requiredFields.forEach(field => {
                const input = form.querySelector(`[name="${field.name}"]`);
                if (input && (!input.value || input.value.trim() === '')) {
                    showFieldError(input, field.message);
                    isValid = false;
                }
            });
            
            // Validate phone number format
            const phoneInput = form.querySelector('[name="phone"]');
            if (phoneInput && phoneInput.value.trim()) {
                const phoneRegex = /^[0-9]{10,11}$/;
                if (!phoneRegex.test(phoneInput.value.trim())) {
                    showFieldError(phoneInput, 'Số điện thoại phải có 10-11 chữ số');
                    isValid = false;
                }
            }
            
            // Validate URL fields (optional - only if user enters data)
            const urlFields = ['website', 'youtube', 'facebook', 'instagram', 'twitter'];
            urlFields.forEach(fieldName => {
                const input = form.querySelector(`[name="${fieldName}"]`);
                if (input && input.value.trim()) {
                    // More flexible URL validation - accept URLs with or without protocol
                    const value = input.value.trim();
                    const urlRegex = /^(https?:\/\/)?([\w\-]+(\.[\w\-]+)+\.?(:\d+)?(\/.*)?)?$/;
                    
                    if (!urlRegex.test(value)) {
                        showFieldError(input, 'Vui lòng nhập URL hợp lệ');
                        isValid = false;
                    }
                }
            });
            
            // If validation fails, prevent form submission
            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = form.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        });
        
        // Real-time validation for phone number
        const phoneInput = form.querySelector('[name="phone"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                // Remove non-numeric characters
                this.value = this.value.replace(/[^0-9]/g, '');
                
                // Clear validation error when user types
                clearFieldError(this);
                
                // Validate length
                if (this.value.length > 0 && (this.value.length < 10 || this.value.length > 11)) {
                    showFieldError(this, 'Số điện thoại phải có 10-11 chữ số');
                }
            });
        }
        
        // Real-time validation for URL fields (optional)
        const urlFields = ['website', 'youtube', 'facebook', 'instagram', 'twitter'];
        urlFields.forEach(fieldName => {
            const input = form.querySelector(`[name="${fieldName}"]`);
            if (input) {
                input.addEventListener('blur', function() {
                    clearFieldError(this);
                    
                    if (this.value.trim()) {
                        // More flexible URL validation
                        const value = this.value.trim();
                        const urlRegex = /^(https?:\/\/)?([\w\-]+(\.[\w\-]+)+\.?(:\d+)?(\/.*)?)?$/;
                        
                        if (!urlRegex.test(value)) {
                            showFieldError(this, 'Vui lòng nhập URL hợp lệ');
                        }
                    }
                });
                
                // Clear error when user focuses
                input.addEventListener('focus', function() {
                    clearFieldError(this);
                });
            }
        });
        
        // Clear errors when user starts typing in required fields
        const requiredFields = ['company_legal_name', 'phone', 'country_id', 'city_id', 'address'];
        requiredFields.forEach(fieldName => {
            const input = form.querySelector(`[name="${fieldName}"]`);
            if (input) {
                input.addEventListener('input', function() {
                    clearFieldError(this);
                });
                
                input.addEventListener('change', function() {
                    clearFieldError(this);
                });
            }
        });
    }
    
    function showFieldError(input, message) {
        // Add error class to input
        input.classList.add('is-invalid');
        
        // Find or create error message element
        let errorElement = input.parentNode.querySelector('.js-validation-error');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.classList.add('invalid-feedback', 'd-block', 'js-validation-error');
            input.parentNode.appendChild(errorElement);
        }
        
        errorElement.textContent = message;
    }
    
    function clearFieldError(input) {
        // Remove error class
        input.classList.remove('is-invalid');
        
        // Remove error message
        const errorElement = input.parentNode.querySelector('.js-validation-error');
        if (errorElement) {
            errorElement.remove();
        }
    }
    
    function clearValidationErrors() {
        // Remove all error classes
        const invalidInputs = document.querySelectorAll('.is-invalid');
        invalidInputs.forEach(input => {
            input.classList.remove('is-invalid');
        });
        
        // Remove all JS validation error messages
        const errorMessages = document.querySelectorAll('.js-validation-error');
        errorMessages.forEach(error => {
            error.remove();
        });
    }
});