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
    let forgotPasswordModalInstance = null;
    let verificationModalInstance = null;
    
    if (forgotPasswordModal) {
        forgotPasswordModalInstance = new bootstrap.Modal(forgotPasswordModal);
    }
    
    if (verificationModal) {
        verificationModalInstance = new bootstrap.Modal(verificationModal);
    }
    
    // Initialize event listeners
    initTabSwitching();
    initFormValidation();
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
        if (forgotPasswordLink && forgotPasswordModalInstance) {
            forgotPasswordLink.addEventListener('click', function(e) {
                e.preventDefault();
                forgotPasswordModalInstance.show();
            });
        }
    }
    
    /**
     * Initialize form validation
     */
    function initFormValidation() {
        // Login form validation
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                const email = document.getElementById('loginEmail').value.trim();
                const password = document.getElementById('loginPassword').value.trim();
                
                if (!email || !password) {
                    e.preventDefault();
                    showNotification('Vui lòng nhập đầy đủ thông tin đăng nhập', 'error');
                }
            });
        }
        
        // Register form validation
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                const fullName = document.getElementById('fullName').value.trim();
                const email = document.getElementById('email').value.trim();
                const phone = document.getElementById('phone').value.trim();
                const password = document.getElementById('password').value.trim();
                const confirmPassword = document.getElementById('confirmPassword').value.trim();
                const agreeTerms = document.getElementById('agreeTerms').checked;
                
                if (!fullName || !email || !phone || !password || !confirmPassword) {
                    e.preventDefault();
                    showNotification('Vui lòng nhập đầy đủ thông tin', 'error');
                    return;
                }
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    showNotification('Mật khẩu không khớp', 'error');
                    return;
                }
                
                if (password.length < 8) {
                    e.preventDefault();
                    showNotification('Mật khẩu phải có ít nhất 8 ký tự', 'error');
                    return;
                }
                
                if (!agreeTerms) {
                    e.preventDefault();
                    showNotification('Vui lòng đồng ý với điều khoản sử dụng', 'error');
                    return;
                }
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
            if (index === 0 && verificationModal) {
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
                if (resendCodeText) resendCodeText.classList.add('d-none');
                if (countdownText) countdownText.classList.remove('d-none');
                
                // Start countdown
                let seconds = 60;
                if (countdownTimer) countdownTimer.textContent = seconds;
                
                const countdownInterval = setInterval(function() {
                    seconds--;
                    if (countdownTimer) countdownTimer.textContent = seconds;
                    
                    if (seconds <= 0) {
                        clearInterval(countdownInterval);
                        if (resendCodeText) resendCodeText.classList.remove('d-none');
                        if (countdownText) countdownText.classList.add('d-none');
                    }
                }, 1000);
                
                // Send AJAX request to resend code
                fetch('index.php?controller=login&action=resendCode', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    showNotification(data.message, data.success ? 'success' : 'error');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Đã xảy ra lỗi khi gửi lại mã. Vui lòng thử lại sau.', 'error');
                });
            });
        }
    }
    
    /**
     * Helper function to move to next input field in verification code
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
    
    /**
     * Show notification message
     */
    window.showNotification = function(message, type) {
        // Look for existing toast container or create one
        let toastContainer = document.querySelector('.toast-container');
        
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }
        
        // Create toast element
        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'}`;
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
});