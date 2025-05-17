<div class="container auth-container mt-5">
    <div class="card auth-card">
        <div class="card-header">
            <h4 class="text-center">Xác thực tài khoản</h4>
        </div>
        <div class="card-body">
            <div class="text-center mb-4">
                <p>Chúng tôi đã gửi mã xác thực đến:</p>
                <p><strong><?= $email ?: $phone ?></strong></p>
                <p>Vui lòng nhập mã xác thực để hoàn tất quá trình đăng ký.</p>
            </div>
            
            <form action="index.php?controller=login&action=verify" method="POST">
                <div class="mb-4">
                    <div class="verification-inputs">
                        <input type="text" name="code" maxlength="6" class="form-control form-control-lg text-center" placeholder="Nhập mã xác thực" required>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">Xác nhận</button>
                </div>
            </form>
            
            <div class="text-center mt-4">
                <p class="resendCode">Không nhận được mã? <a href="#" id="resendCodeLink">Gửi lại mã</a></p>
                <p class="countdown d-none">Gửi lại mã sau <span id="countdownTimer">60</span>s</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resendCodeLink = document.getElementById('resendCodeLink');
    const countdownText = document.querySelector('.countdown');
    const resendCodeText = document.querySelector('.resendCode');
    const countdownTimer = document.getElementById('countdownTimer');
    
    if (resendCodeLink) {
        resendCodeLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Hide resend link and show countdown
            resendCodeText.classList.add('d-none');
            countdownText.classList.remove('d-none');
            
            // Start countdown
            let seconds = 60;
            countdownTimer.textContent = seconds;
            
            // Send AJAX request to resend code
            fetch('index.php?controller=login&action=resendCode', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success notification
                    showNotification(data.message, 'success');
                    
                    // Start countdown
                    const countdownInterval = setInterval(function() {
                        seconds--;
                        countdownTimer.textContent = seconds;
                        
                        if (seconds <= 0) {
                            clearInterval(countdownInterval);
                            resendCodeText.classList.remove('d-none');
                            countdownText.classList.add('d-none');
                        }
                    }, 1000);
                } else {
                    // Show error notification
                    showNotification(data.message, 'error');
                    
                    // Show resend link
                    resendCodeText.classList.remove('d-none');
                    countdownText.classList.add('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Đã xảy ra lỗi. Vui lòng thử lại sau.', 'error');
                
                // Show resend link
                resendCodeText.classList.remove('d-none');
                countdownText.classList.add('d-none');
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