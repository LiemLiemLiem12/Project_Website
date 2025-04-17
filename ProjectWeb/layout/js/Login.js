/**
 * Login.js - User authentication functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const loginTab = document.getElementById('login-tab');
    const registerTab = document.getElementById('register-tab');
    const switchToRegister = document.getElementById('switchToRegister');
    const switchToLogin = document.getElementById('switchToLogin');
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');
    
    // Forms
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    
    // Modals
    const forgotPasswordModal = document.getElementById('forgotPasswordModal');
    const verificationModal = document.getElementById('verificationModal');
    
    // Modal instances
    const forgotPasswordModalInstance = new bootstrap.Modal(forgotPasswordModal);
    const verificationModalInstance = new bootstrap.Modal(verificationModal);
    
    // Initialize event listeners
    initTabSwitching();
    initFormSubmission();
    initVerificationCode();
    
    /**
     * Initialize tab switching functionality
     */
    function initTabSwitching() {
        // Switch to register tab when clicking "Register Now" link
        if (switchToRegister) {
            switchToRegister.addEventListener('click', function(e) {
        e.preventDefault();
                const registerTabEl = new bootstrap.Tab(registerTab);
                registerTabEl.show();
    });
        }
    
        // Switch to login tab when clicking "Login" link
        if (switchToLogin) {
            switchToLogin.addEventListener('click', function(e) {
        e.preventDefault();
                const loginTabEl = new bootstrap.Tab(loginTab);
                loginTabEl.show();
    });
        }
        
        // Show forgot password modal when clicking "Forgot Password" link
        if (forgotPasswordLink) {
    forgotPasswordLink.addEventListener('click', function(e) {
        e.preventDefault();
                forgotPasswordModalInstance.show();
    });
        }
    }
    
    /**
     * Initialize form submission handlers
     */
    function initFormSubmission() {
        // Login form submission
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = document.getElementById('loginEmail').value;
                const password = document.getElementById('loginPassword').value;
                
                // Validate input
                if (!email || !password) {
                    showNotification('Vui lòng nhập đầy đủ thông tin', 'error');
                    return;
                }
                
                // Simulate login process - in production, this would call an API
                console.log('Login attempt with:', { email, password });
                
                // For demo: simulate successful login
                showNotification('Đăng nhập thành công!', 'success');
                
                // Redirect to home page after short delay
                setTimeout(() => {
                    window.location.href = 'Home.html';
                }, 1500);
            });
        }
        
        // Register form submission
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const fullName = document.getElementById('fullName').value;
                const email = document.getElementById('email').value;
                const phone = document.getElementById('phone').value;
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirmPassword').value;
                const agreeTerms = document.getElementById('agreeTerms').checked;
                
                // Validate input
                if (!fullName || !email || !phone || !password || !confirmPassword) {
                    showNotification('Vui lòng nhập đầy đủ thông tin', 'error');
                    return;
                }
                
                if (password !== confirmPassword) {
                    showNotification('Mật khẩu không khớp', 'error');
            return;
        }
        
                if (!agreeTerms) {
                    showNotification('Vui lòng đồng ý với điều khoản sử dụng', 'error');
                    return;
                }
                
                // Simulate registration process - in production, this would call an API
                console.log('Registration attempt with:', { fullName, email, phone, password });
        
                // Show verification modal for confirmation code
                document.getElementById('verificationContact').textContent = email;
                verificationModalInstance.show();
            });
        }
        
        // Forgot password form submission
        const recoverButton = document.getElementById('recoverButton');
        if (recoverButton) {
            recoverButton.addEventListener('click', function() {
                const recoveryContact = document.getElementById('recoveryContact').value;
                
                if (!recoveryContact) {
                    showNotification('Vui lòng nhập email hoặc số điện thoại', 'error');
                    return;
                }
                
                // Simulate sending recovery email/SMS - in production, this would call an API
                console.log('Password recovery attempt for:', recoveryContact);
                
                // Close modal and show notification
                forgotPasswordModalInstance.hide();
                showNotification(`Mã xác nhận đã được gửi đến ${recoveryContact}`, 'success');
            });
        }
        
        // Verification form submission
        const verifyButton = document.getElementById('verifyButton');
        if (verifyButton) {
            verifyButton.addEventListener('click', function() {
                // Get verification code
                const codeInputs = document.querySelectorAll('.verificationCode');
                let code = '';
                codeInputs.forEach(input => {
                    code += input.value;
                });
                
                if (code.length !== 6) {
                    showNotification('Vui lòng nhập đủ mã xác nhận', 'error');
                    return;
                }
                
                // Simulate code verification - in production, this would call an API
                console.log('Verification code submitted:', code);
                
                // Close modal and show success
                verificationModalInstance.hide();
                showNotification('Đăng ký thành công!', 'success');
        
                // Redirect to login tab after short delay
                setTimeout(() => {
                    const loginTabEl = new bootstrap.Tab(loginTab);
                    loginTabEl.show();
                }, 1500);
            });
        }
    }
    
    /**
     * Initialize verification code input functionality
     */
    function initVerificationCode() {
        const codeInputs = document.querySelectorAll('.verificationCode');
        
        codeInputs.forEach((input, index) => {
            // Focus on first input when modal is shown
            if (index === 0) {
                verificationModal.addEventListener('shown.bs.modal', function() {
                    input.focus();
                });
            }
            
            // Add input event listeners
            input.addEventListener('keyup', function(e) {
                // If key is backspace, move to previous field
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    codeInputs[index - 1].focus();
                    return;
                }
                
                // If input has a value and there is a next field, move to it
                if (input.value && index < codeInputs.length - 1) {
                    codeInputs[index + 1].focus();
                }
            });
        });
        
        // Handle "Resend Code" functionality
        const resendCodeLink = document.getElementById('resendCodeLink');
        const countdownTimer = document.getElementById('countdownTimer');
        const resendCodeText = document.querySelector('.resendCode');
        const countdownText = document.querySelector('.countdown');
        
        if (resendCodeLink) {
            resendCodeLink.addEventListener('click', function(e) {
    e.preventDefault();
                
                // Hide resend link and show countdown
                resendCodeText.classList.add('d-none');
                countdownText.classList.remove('d-none');
                
                // Start countdown
                let seconds = 60;
                countdownTimer.textContent = seconds;
                
                const countdownInterval = setInterval(function() {
                    seconds--;
                    countdownTimer.textContent = seconds;
                    
                    if (seconds <= 0) {
                        clearInterval(countdownInterval);
                        resendCodeText.classList.remove('d-none');
                        countdownText.classList.add('d-none');
                    }
                }, 1000);
                
                // Simulate resending code - in production, this would call an API
                console.log('Resending verification code');
                showNotification('Mã xác nhận mới đã được gửi', 'success');
            });
        }
    }
    
    /**
     * Show notification message
     * @param {string} message - The message to display
     * @param {string} type - The type of notification (success, error, info)
     */
    function showNotification(message, type) {
        // Look for existing toast container or create one
        let toastContainer = document.querySelector('.toast-container');
        
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }
        
        // Create toast element
        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-white bg-${type === 'success' ? 'dark' : 'danger'}`;
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
        
        // Create toast content
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        // Add toast to container
        toastContainer.appendChild(toastEl);
        
        // Initialize and show toast
        const toast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 3000
        });
        
        toast.show();
        
        // Remove toast after it's hidden
        toastEl.addEventListener('hidden.bs.toast', function() {
            toastEl.remove();
        });
    }
    
    /**
     * Helper function to move to next input field
     * This is used by the verification code inputs
     * @param {HTMLElement} input - The current input element
     */
    window.moveToNext = function(input) {
        if (input.value.length === input.maxLength) {
            const fieldIndex = Array.from(document.querySelectorAll('.verificationCode')).indexOf(input);
            const nextField = document.querySelectorAll('.verificationCode')[fieldIndex + 1];
            
            if (nextField) {
                nextField.focus();
}
        }
    };
});