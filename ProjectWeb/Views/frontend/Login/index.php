<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>160STORE - Đăng nhập/Đăng ký</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/Project_Website/ProjectWeb/layout/css/Login.css">
    <style>
        .toast-body.toast-error {
    border: 1px solid red;
    background-color: #ffe5e5;
}
        .danger.alert-dismissible.fade.show {
            color: #dc3545 !important;
        }
        .password-requirements {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
        .password-requirements ul {
            margin-bottom: 0;
            padding-left: 1rem;
        }
        .password-requirements li {
            margin-bottom: 0.25rem;
        }
        .requirement-met {
            color: #28a745;
        }
        .requirement-unmet {
            color: #dc3545;
        }
        .form-text.enhanced {
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .password-strength {
            font-weight: 500;
        }
        .input-group-text {
            cursor: pointer;
            border-left: none;
            background-color: transparent;
            padding: 0.375rem 0.75rem;
        }
        .input-group .form-control {
            border-right: none;
        }
        .input-group .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgb(13 110 253 / 25%);
            border-right: none;
        }
        .input-group .input-group-text {
            border-color: #ced4da;
        }
        .input-group .form-control:focus + .input-group-text {
            border-color: #86b7fe;
        }
        .phone-hint {
            font-size: 0.75rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
        
        /* Alternative approach - position absolute for password toggle */
        .password-field-wrapper {
            position: relative;
        }
      
.alert-custom-red {
    background-color: #f44336;  /* màu nền đỏ */
    color: #fff;
    border-color: #d32f2f;
}


        .password-toggle-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 0;
            z-index: 3;
        }
        
        .password-field-wrapper .form-control {
            padding-right: 40px;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">160STORE</a>
        </div>
    </nav>

    <!-- Flash Message -->
   <?php if (isset($_SESSION['flash_message'])): ?>
    <?php
        $type = $_SESSION['flash_message']['type'];
        $message = $_SESSION['flash_message']['message'];
        $customClass = $type === 'error' ? 'alert-custom-red' : "alert-$type";
    ?>
    <div class="container mt-3">
        <div class="alert <?= $customClass ?> alert-dismissible fade show" role="alert">
            <?= $message ?>
           <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>

        </div>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>



    <!-- Main Content -->
    <div class="container auth-container">
        <div class="card auth-card">
            <!-- Auth Header Tabs -->
            <ul class="nav nav-tabs auth-header" id="authTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= !isset($_GET['tab']) || $_GET['tab'] !== 'register' ? 'active' : '' ?>" 
                            id="login-tab" data-bs-toggle="tab" data-bs-target="#login" 
                            type="button" role="tab" aria-controls="login" 
                            aria-selected="<?= !isset($_GET['tab']) || $_GET['tab'] !== 'register' ? 'true' : 'false' ?>">
                        Đăng nhập
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= isset($_GET['tab']) && $_GET['tab'] === 'register' ? 'active' : '' ?>" 
                            id="register-tab" data-bs-toggle="tab" data-bs-target="#register" 
                            type="button" role="tab" aria-controls="register" 
                            aria-selected="<?= isset($_GET['tab']) && $_GET['tab'] === 'register' ? 'true' : 'false' ?>">
                        Đăng ký
                    </button>
                </li>
            </ul>

            <!-- Auth Content -->
            <div class="tab-content" id="authTabContent">
                <!-- Login Tab -->
                <div class="tab-pane fade <?= !isset($_GET['tab']) || $_GET['tab'] !== 'register' ? 'show active' : '' ?>" 
                     id="login" role="tabpanel" aria-labelledby="login-tab">
                    <div class="auth-body">
                        <form id="loginForm" action="index.php?controller=login&action=login" method="POST" novalidate>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="loginEmail" name="email" 
                                       placeholder="Email hoặc số điện thoại" required>
                                <div class="form-text enhanced">
                                    Nhập email (vd: user@example.com) hoặc số điện thoại (vd: 0912345678)
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="password-field-wrapper">
                                    <input type="password" class="form-control" id="loginPassword" name="password" 
                                           placeholder="Mật khẩu" required>
                                    <button type="button" class="password-toggle-btn" onclick="togglePassword('loginPassword')">
                                        <i class="fas fa-eye" id="loginPasswordToggle"></i>
                                    </button>
                                </div>
                                <div id="passwordHelp" class="form-text text-end">
                                    <a href="#" id="forgotPasswordLink">Quên mật khẩu?</a>
                                </div>
                            </div>
                            
                            <!-- Hidden redirect field -->
                            <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect ?? '') ?>">
                            
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary">Đăng nhập</button>
                            </div>
                        </form>
                        
                        <div class="divider">hoặc đăng nhập với</div>
                        
                      
                        
                        <div class="auth-option">
                            Bạn chưa có tài khoản? <a href="#" id="switchToRegister">Đăng ký ngay</a>
                        </div>
                    </div>
                </div>

                <!-- Register Tab -->
                <div class="tab-pane fade <?= isset($_GET['tab']) && $_GET['tab'] === 'register' ? 'show active' : '' ?>" 
                     id="register" role="tabpanel" aria-labelledby="register-tab">
                    <div class="auth-body">
                        <form id="registerForm" action="index.php?controller=login&action=register" method="POST" novalidate>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="fullName" name="fullName" 
                                       placeholder="Họ và tên" required>
                                <div class="form-text enhanced">
                                    Từ 2-50 ký tự, chỉ chữ cái và khoảng trắng, không được toàn số
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="Email" required>
                                <div class="form-text enhanced">
                                    Định dạng: user@example.com
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       placeholder="Số điện thoại" maxlength="11" required>
                                <div class="phone-hint">
                                    Số điện thoại Việt Nam: 03x, 05x, 07x, 08x, 09x (10 số)
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="password-field-wrapper">
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Mật khẩu" required>
                                    <button type="button" class="password-toggle-btn" onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="passwordToggle"></i>
                                    </button>
                                </div>
                                <div class="password-requirements">
                                    <small>Mật khẩu phải có:</small>
                                    <ul class="mb-1">
                                        <li id="length-req" class="requirement-unmet">Ít nhất 8 ký tự</li>
                                        <li id="lowercase-req" class="requirement-unmet">Ít nhất 1 chữ thường (a-z)</li>
                                        <li id="uppercase-req" class="requirement-unmet">Ít nhất 1 chữ hoa (A-Z)</li>
                                        <li id="number-req" class="requirement-unmet">Ít nhất 1 chữ số (0-9)</li>
                                        <li id="special-req" class="requirement-unmet">Ít nhất 1 ký tự đặc biệt (!@#$%^&*)</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="password-field-wrapper">
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" 
                                           placeholder="Xác nhận mật khẩu" required>
                                    <button type="button" class="password-toggle-btn" onclick="togglePassword('confirmPassword')">
                                        <i class="fas fa-eye" id="confirmPasswordToggle"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="agreeTerms" name="agreeTerms" required>
                                <label class="form-check-label" for="agreeTerms">
                                    Tôi đồng ý với <a href="#" target="_blank">Điều khoản sử dụng</a> và <a href="#" target="_blank">Chính sách bảo mật</a>
                                </label>
                            </div>
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary">Đăng ký</button>
                            </div>
                        </form>
                        
                        <div class="auth-option">
                            Đã có tài khoản? <a href="#" id="switchToLogin">Đăng nhập</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Khôi phục mật khẩu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Vui lòng nhập email hoặc số điện thoại để khôi phục mật khẩu</p>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="recoveryContact" 
                               placeholder="Email hoặc số điện thoại" required>
                        <div class="form-text">
                            Email: user@example.com hoặc SĐT: 0912345678
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="recoverButton">Gửi mã xác nhận</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Code Modal -->
    <div class="modal fade" id="verificationCodeModal" tabindex="-1" aria-labelledby="verificationCodeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verificationCodeModalLabel">Nhập mã xác thực</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Chúng tôi đã gửi mã xác thực 5 chữ số đến thông tin liên hệ của bạn.</p>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="verificationCode" 
                               placeholder="Nhập mã xác thực 5 ký tự" maxlength="5" required 
                               pattern="[0-9]{5}" inputmode="numeric">
                        <div class="form-text">
                            Mã xác thực gồm 5 chữ số
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="verifyCodeButton">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordModalLabel">Đặt lại mật khẩu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">Mật khẩu mới</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="newPassword" required>
                            <span class="input-group-text" onclick="togglePassword('newPassword')">
                                <i class="fas fa-eye" id="newPasswordToggle"></i>
                            </span>
                        </div>
                        <div class="password-requirements">
                            <small>Mật khẩu phải có: ít nhất 8 ký tự, chữ hoa, chữ thường, số và ký tự đặc biệt</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="confirmNewPassword" class="form-label">Xác nhận mật khẩu mới</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirmNewPassword" required>
                            <span class="input-group-text" onclick="togglePassword('confirmNewPassword')">
                                <i class="fas fa-eye" id="confirmNewPasswordToggle"></i>
                            </span>
                        </div>
                    </div>
                    <input type="hidden" id="resetToken">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="resetPasswordButton">Lưu mật khẩu mới</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
    // Enhanced password toggle function
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const toggle = document.getElementById(inputId + 'Toggle');
        
        if (input.type === 'password') {
            input.type = 'text';
            toggle.classList.remove('fa-eye');
            toggle.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            toggle.classList.remove('fa-eye-slash');
            toggle.classList.add('fa-eye');
        }
    }

    // Real-time password requirements checker
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                checkPasswordRequirements(this.value);
            });
        }
        
        function checkPasswordRequirements(password) {
            const requirements = {
                'length-req': { met: password.length >= 8, text: 'Ít nhất 8 ký tự' },
                'lowercase-req': { met: /[a-z]/.test(password), text: 'Ít nhất 1 chữ thường (a-z)' },
                'uppercase-req': { met: /[A-Z]/.test(password), text: 'Ít nhất 1 chữ hoa (A-Z)' },
                'number-req': { met: /[0-9]/.test(password), text: 'Ít nhất 1 chữ số (0-9)' },
                'special-req': { met: /[!@#$%^&*(),.?":{}|<>]/.test(password), text: 'Ít nhất 1 ký tự đặc biệt (!@#$%^&*)' }
            };
            
            Object.keys(requirements).forEach(reqId => {
                const element = document.getElementById(reqId);
                if (element) {
                    const req = requirements[reqId];
                    if (req.met) {
                        element.classList.remove('requirement-unmet');
                        element.classList.add('requirement-met');
                        element.innerHTML = '✓ ' + req.text;
                    } else {
                        element.classList.remove('requirement-met');
                        element.classList.add('requirement-unmet');
                        element.innerHTML = req.text;
                    }
                }
            });
        }
    });

    // Hàm hiển thị thông báo
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
        toastEl.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'}`;
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
        
        // Create toast content
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body" >
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
            delay: 5000
        });
        
        toast.show();
        
        // Remove toast after it's hidden
        toastEl.addEventListener('hidden.bs.toast', function() {
            toastEl.remove();
        });
    }

    // Hàm di chuyển đến ô input tiếp theo khi nhập mã xác thực
    function moveToNext(input) {
        if (input.value.length === input.maxLength) {
            const fieldIndex = Array.from(document.querySelectorAll('.verificationCode')).indexOf(input);
            const nextField = document.querySelectorAll('.verificationCode')[fieldIndex + 1];
            
            if (nextField) {
                nextField.focus();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Khai báo các modal
        const forgotPasswordModal = new bootstrap.Modal(document.getElementById('forgotPasswordModal'));
        const verificationCodeModal = new bootstrap.Modal(document.getElementById('verificationCodeModal'));
        const resetPasswordModal = new bootstrap.Modal(document.getElementById('resetPasswordModal'));
        
        // Xử lý chuyển tab
        const switchToRegister = document.getElementById('switchToRegister');
        const switchToLogin = document.getElementById('switchToLogin');
        const loginTab = document.getElementById('login-tab');
        const registerTab = document.getElementById('register-tab');
        
        if (switchToRegister && registerTab) {
            switchToRegister.addEventListener('click', function(e) {
                e.preventDefault();
                const registerTabEl = new bootstrap.Tab(registerTab);
                registerTabEl.show();
            });
        }
        
        if (switchToLogin && loginTab) {
            switchToLogin.addEventListener('click', function(e) {
                e.preventDefault();
                const loginTabEl = new bootstrap.Tab(loginTab);
                loginTabEl.show();
            });
        }
        
        // Kết nối nút "Quên mật khẩu?" với modal
        const forgotPasswordLink = document.getElementById('forgotPasswordLink');
        if (forgotPasswordLink) {
            forgotPasswordLink.addEventListener('click', function(e) {
                e.preventDefault();
                forgotPasswordModal.show();
            });
        }
        
        // Xử lý nút "Gửi mã xác nhận"
        const recoverButton = document.getElementById('recoverButton');
        if (recoverButton) {
            recoverButton.addEventListener('click', function() {
                const contact = document.getElementById('recoveryContact').value.trim();
                
                if (!contact) {
                    showNotification('Vui lòng nhập email hoặc số điện thoại', 'error');
                    return;
                }
                
                console.log('Gửi yêu cầu lấy lại mật khẩu cho:', contact);
                
                fetch('index.php?controller=login&action=forgotPassword', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'contact=' + encodeURIComponent(contact)
                })
                .then(response => {
                    return response.text();
                })
                .then(text => {
                    console.log('Phản hồi từ máy chủ:', text);
                    
                    try {
                        const data = JSON.parse(text);
                        console.log('Phân tích JSON thành công:', data);
                        
                        if (data.success) {
                            forgotPasswordModal.hide();
                            setTimeout(() => {
                                verificationCodeModal.show();
                            }, 500);
                            showNotification(data.message, 'success');
                        } else {
                            showNotification(data.message || 'Có lỗi xảy ra', 'error');
                        }
                    } catch (e) {
                        console.error('Lỗi phân tích JSON:', e);
                        console.log('Nội dung phản hồi không phải JSON:', text.substring(0, 100));
                        showNotification('Lỗi hệ thống, vui lòng thử lại sau.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Lỗi fetch:', error);
                    showNotification('Đã xảy ra lỗi khi kết nối đến máy chủ.', 'error');
                });
            });
        }
        
        // Xử lý nút xác nhận mã
        const verifyCodeButton = document.getElementById('verifyCodeButton');
        if (verifyCodeButton) {
            verifyCodeButton.addEventListener('click', function() {
                const code = document.getElementById('verificationCode').value.trim();
                
                if (!code || code.length !== 5) {
                    showNotification('Vui lòng nhập mã xác thực 5 ký tự', 'error');
                    return;
                }
                
                console.log('Gửi yêu cầu xác thực mã:', code);
                
                fetch('index.php?controller=login&action=verifyResetCode', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'code=' + encodeURIComponent(code)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Phản hồi từ server về xác thực mã:', data);
                    
                    if (data.success) {
                        verificationCodeModal.hide();
                        document.getElementById('resetToken').value = data.resetToken;
                        setTimeout(() => {
                            resetPasswordModal.show();
                        }, 500);
                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message || 'Mã xác thực không hợp lệ', 'error');
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    showNotification('Đã xảy ra lỗi. Vui lòng thử lại sau.', 'error');
                });
            });
        }
        
        // Xử lý nút lưu mật khẩu mới
        const resetPasswordButton = document.getElementById('resetPasswordButton');
        if (resetPasswordButton) {
            resetPasswordButton.addEventListener('click', function() {
                const newPassword = document.getElementById('newPassword').value;
                const confirmNewPassword = document.getElementById('confirmNewPassword').value;
                const resetToken = document.getElementById('resetToken').value;
                
                if (!newPassword || !confirmNewPassword) {
                    showNotification('Vui lòng nhập đầy đủ thông tin', 'error');
                    return;
                }
                
                if (newPassword !== confirmNewPassword) {
                    showNotification('Mật khẩu xác nhận không khớp', 'error');
                    return;
                }
                
                // Enhanced password validation
                if (newPassword.length < 8) {
                    showNotification('Mật khẩu phải có ít nhất 8 ký tự', 'error');
                    return;
                }
                
                if (!/[a-z]/.test(newPassword)) {
                    showNotification('Mật khẩu phải chứa ít nhất một chữ cái thường', 'error');
                    return;
                }
                
                if (!/[A-Z]/.test(newPassword)) {
                    showNotification('Mật khẩu phải chứa ít nhất một chữ cái hoa', 'error');
                    return;
                }
                
                if (!/[0-9]/.test(newPassword)) {
                    showNotification('Mật khẩu phải chứa ít nhất một chữ số', 'error');
                    return;
                }
                
                if (!/[!@#$%^&*(),.?":{}|<>]/.test(newPassword)) {
                    showNotification('Mật khẩu phải chứa ít nhất một ký tự đặc biệt', 'error');
                    return;
                }
                
                console.log('Gửi yêu cầu đặt lại mật khẩu');
                
                fetch('index.php?controller=login&action=processResetPassword', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'token=' + encodeURIComponent(resetToken) + 
                          '&password=' + encodeURIComponent(newPassword) + 
                          '&confirmPassword=' + encodeURIComponent(confirmNewPassword)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Phản hồi từ server về đặt lại mật khẩu:', data);
                    
                    if (data.success) {
                        resetPasswordModal.hide();
                        showNotification(data.message, 'success');
                        setTimeout(function() {
                            window.location.href = 'index.php?controller=login';
                        }, 2000);
                    } else {
                        showNotification(data.message || 'Có lỗi xảy ra khi đặt lại mật khẩu', 'error');
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    showNotification('Đã xảy ra lỗi. Vui lòng thử lại sau.', 'error');
                });
            });
        }
    });
    </script>
    
    <!-- Include Enhanced Login JS -->
    <script src="/Project_Website/ProjectWeb/layout/js/Login.js"></script>
</body>
</html>