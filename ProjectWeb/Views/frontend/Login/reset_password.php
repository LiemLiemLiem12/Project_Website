<div class="container auth-container mt-5">
    <div class="card auth-card">
        <div class="card-header">
            <h4 class="text-center">Đặt lại mật khẩu</h4>
        </div>
        <div class="card-body">
            <form action="index.php?controller=login&action=processResetPassword" method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu mới</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="form-text">
                        Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ và số
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="confirmPassword" class="form-label">Xác nhận mật khẩu</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Đặt lại mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
</div>