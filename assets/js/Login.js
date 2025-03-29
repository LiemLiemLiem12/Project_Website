document.addEventListener('DOMContentLoaded', function() {
    // Cache DOM elements
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const loginFormElement = document.getElementById('loginFormElement');
    const registerFormElement = document.getElementById('registerFormElement');
    const showRegisterLink = document.getElementById('showRegister');
    const showLoginLink = document.getElementById('showLogin');
    const loginNav = document.getElementById('loginNav');
    const registerNav = document.getElementById('registerNav');
    const forgotPasswordLink = document.getElementById('forgotPassword');
    
    // Form input elements
    const loginEmail = document.getElementById('loginEmail');
    const loginPassword = document.getElementById('loginPassword');
    const registerPhone = document.getElementById('registerPhone');
    
    // Event Listeners for navigation between forms
    showRegisterLink.addEventListener('click', function(e) {
        e.preventDefault();
        switchForm(loginForm, registerForm);
    });
    
    showLoginLink.addEventListener('click', function(e) {
        e.preventDefault();
        switchForm(registerForm, loginForm);
    });
    
    loginNav.addEventListener('click', function(e) {
        e.preventDefault();
        showForm(loginForm, registerForm);
    });
    
    registerNav.addEventListener('click', function(e) {
        e.preventDefault();
        showForm(registerForm, loginForm);
    });
    
    // Form submission handlers
    loginFormElement.addEventListener('submit', function(e) {
        e.preventDefault();
        handleLogin();
    });
    
    registerFormElement.addEventListener('submit', function(e) {
        e.preventDefault();
        handleRegister();
    });
    
    forgotPasswordLink.addEventListener('click', function(e) {
        e.preventDefault();
        showForgotPasswordAlert();
    });
    
    // Input validation
    loginEmail.addEventListener('blur', validateLoginEmail);
    loginPassword.addEventListener('blur', validateLoginPassword);
    registerPhone.addEventListener('blur', validatePhoneNumber);
    
    // Functions
    function switchForm(hideForm, showForm) {
        hideForm.classList.add('d-none');
        showForm.classList.remove('d-none');
        showForm.classList.add('fade-in');
        // Reset form fields when switching
        resetForms();
    }
    
    function showForm(formToShow, formToHide) {
        formToHide.classList.add('d-none');
        formToShow.classList.remove('d-none');
        formToShow.classList.add('fade-in');
        // Reset form fields when switching
        resetForms();
    }
    
    function resetForms() {
        loginFormElement.reset();
        registerFormElement.reset();
        // Remove any validation classes or messages
        removeValidationStyles(loginEmail);
        removeValidationStyles(loginPassword);
        removeValidationStyles(registerPhone);
    }
    
    function handleLogin() {
        if (!validateLoginEmail() || !validateLoginPassword()) {
            return;
        }
        
        // Demo login logic - in real app, you would call your API here
        console.log('Login attempt with:', {
            email: loginEmail.value,
            password: loginPassword.value
        });
        
        // Simulate successful login
        alert('Đăng nhập thành công!');
    }
    
    function handleRegister() {
        if (!validatePhoneNumber()) {
            return;
        }
        
        // Demo registration logic - in real app, you would call your API here
        console.log('Registration attempt with phone:', registerPhone.value);
        
        // Simulate sending verification code
        alert('Mã xác nhận đã được gửi đến ' + registerPhone.value);
    }
    
    function validateLoginEmail() {
        const value = loginEmail.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phoneRegex = /^[0-9]{10,11}$/;
        
        if (value === '') {
            setInvalidStyle(loginEmail, 'Vui lòng nhập email hoặc số điện thoại');
            return false;
        } else if (!emailRegex.test(value) && !phoneRegex.test(value)) {
            setInvalidStyle(loginEmail, 'Email hoặc số điện thoại không hợp lệ');
            return false;
        } else {
            setValidStyle(loginEmail);
            return true;
        }
    }
    
    function validateLoginPassword() {
        const value = loginPassword.value.trim();
        
        if (value === '') {
            setInvalidStyle(loginPassword, 'Vui lòng nhập mật khẩu');
            return false;
        } else if (value.length < 6) {
            setInvalidStyle(loginPassword, 'Mật khẩu phải có ít nhất 6 ký tự');
            return false;
        } else {
            setValidStyle(loginPassword);
            return true;
        }
    }
    
    function validatePhoneNumber() {
        const value = registerPhone.value.trim();
        const phoneRegex = /^[0-9]{10,11}$/;
        
        if (value === '') {
            setInvalidStyle(registerPhone, 'Vui lòng nhập số điện thoại');
            return false;
        } else if (!phoneRegex.test(value)) {
            setInvalidStyle(registerPhone, 'Số điện thoại không hợp lệ');
            return false;
        } else {
            setValidStyle(registerPhone);
            return true;
        }
    }
    
    function setInvalidStyle(element, message) {
        removeValidationStyles(element);
        element.classList.add('is-invalid');
        
        // Create or update feedback message
        let feedback = element.nextElementSibling;
        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            element.parentNode.insertBefore(feedback, element.nextSibling);
        }
        feedback.textContent = message;
    }
    
    function setValidStyle(element) {
        removeValidationStyles(element);
        element.classList.add('is-valid');
    }
    
    function removeValidationStyles(element) {
        element.classList.remove('is-invalid', 'is-valid');
        
        // Remove any existing feedback element
        const feedback = element.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.remove();
        }
    }
    
    function showForgotPasswordAlert() {
        alert('Chức năng quên mật khẩu đang được phát triển. Vui lòng thử lại sau.');
    }
    // Cập nhật hiển thị của thanh điều hướng dựa trên form đang hiển thị
function updateNavigation(currentForm) {
    if (currentForm === 'login') {
        // Nếu đang ở giao diện đăng nhập, ẩn "Đăng nhập" và hiện "Đăng ký" trên thanh điều hướng
        loginNav.style.display = 'none';
        registerNav.style.display = 'block';
    } else {
        // Nếu đang ở giao diện đăng ký, ẩn "Đăng ký" và hiện "Đăng nhập" trên thanh điều hướng
        loginNav.style.display = 'block';
        registerNav.style.display = 'none';
    }
}
function switchForm(hideForm, showForm) {
    hideForm.classList.add('d-none');
    showForm.classList.remove('d-none');
    showForm.classList.add('fade-in');
    
    // Xác định form hiện tại và cập nhật thanh điều hướng
    const currentForm = showForm === loginForm ? 'login' : 'register';
    updateNavigation(currentForm);
    
    // Reset form fields when switching
    resetForms();
}
// Thiết lập trạng thái điều hướng ban đầu khi trang tải
updateNavigation('login');
});