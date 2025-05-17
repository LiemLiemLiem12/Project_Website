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
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">160STORE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=product&action=index">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?controller=login">Tài khoản</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=cart&action=index">Giỏ hàng <i class="fas fa-shopping-cart"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Message -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="container mt-3">
            <div class="alert alert-<?= $_SESSION['flash_message']['type'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['flash_message']['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                    <form id="loginForm" action="index.php?controller=login&action=login" method="POST">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="loginEmail" name="email" 
                                   placeholder="Email hoặc số điện thoại" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="loginPassword" name="password" 
                                   placeholder="Mật khẩu" required>
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
                    
                    <div class="social-buttons">
                        <button class="social-button facebook">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </button>
                        <button class="social-button google">
                            <i class="fab fa-google"></i> Google
                        </button>
                    </div>
                    
                    <div class="auth-option">
                        Bạn chưa có tài khoản? <a href="#" id="switchToRegister">Đăng ký ngay</a>
                    </div>
                </div>
            </div>

            <!-- Register Tab -->
            <div class="tab-pane fade <?= isset($_GET['tab']) && $_GET['tab'] === 'register' ? 'show active' : '' ?>" 
                 id="register" role="tabpanel" aria-labelledby="register-tab">
                <div class="auth-body">
                    <form id="registerForm" action="index.php?controller=login&action=register" method="POST">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="fullName" name="fullName" 
                                   placeholder="Họ và tên" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   placeholder="Số điện thoại" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Mật khẩu" required>
                            <div class="form-text">
                                Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ và số
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" 
                                   placeholder="Xác nhận mật khẩu" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="agreeTerms" name="agreeTerms" required>
                            <label class="form-check-label" for="agreeTerms">
                                Tôi đồng ý với <a href="#">Điều khoản sử dụng</a> và <a href="#">Chính sách bảo mật</a>
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
    
<!-- Verification Modal -->
<div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verificationModalLabel">Xác thực tài khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Chúng tôi đã gửi mã xác thực đến <span id="verificationContact"></span></p>
                <p>Nhập mã xác thực để hoàn tất đăng ký:</p>
                <div class="verification-inputs">
                    <input type="text" maxlength="1" class="verificationCode" oninput="moveToNext(this)">
                    <input type="text" maxlength="1" class="verificationCode" oninput="moveToNext(this)">
                    <input type="text" maxlength="1" class="verificationCode" oninput="moveToNext(this)">
                    <input type="text" maxlength="1" class="verificationCode" oninput="moveToNext(this)">
                    <input type="text" maxlength="1" class="verificationCode" oninput="moveToNext(this)">
                    <input type="text" maxlength="1" class="verificationCode" oninput="moveToNext(this)">
                </div>
                <div class="text-center mt-3">
                    <p class="resendCode">Không nhận được mã? <a href="#" id="resendCodeLink">Gửi lại mã</a></p>
                    <p class="countdown d-none">Gửi lại mã sau <span id="countdownTimer">60</span>s</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="verifyButton">Xác nhận</button>
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
                <button type="button" class="btn-close
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Vui lòng nhập email hoặc số điện thoại để khôi phục mật khẩu</p>
                <div class="mb-3">
                    <input type="text" class="form-control" id="recoveryContact" 
                           placeholder="Email hoặc số điện thoại" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="recoverButton">Gửi mã xác nhận</button>
            </div>
        </div>
    </div>
</div>

<script src="/Project_Website/ProjectWeb/layout/js/Login.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add AJAX functionality for forgot password
        const recoverButton = document.getElementById('recoverButton');
        const recoveryContact = document.getElementById('recoveryContact');
        
        if (recoverButton) {
            recoverButton.addEventListener('click', function() {
                const contact = recoveryContact.value.trim();
                
                if (!contact) {
                    showNotification('Vui lòng nhập email hoặc số điện thoại', 'error');
                    return;
                }
                
                // Send AJAX request to forgot password endpoint
                fetch('index.php?controller=login&action=forgotPassword', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'contact=' + encodeURIComponent(contact)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal and show success notification
                        const modal = bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal'));
                        modal.hide();
                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Đã xảy ra lỗi. Vui lòng thử lại sau.', 'error');
                });
            });
        }
        
        // Function to show notification
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
</script>
</body>
</html>

