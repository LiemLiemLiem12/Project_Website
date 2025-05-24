<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
    header('Location: ?controller=Admindashboard');
    exit;
}

// Lấy đường dẫn favicon từ cài đặt hoặc mặc định
require_once 'Controllers/FooterController.php';
$footerController = new FooterController();
$storeSettings = $footerController->getStoreSettings();
$faviconPath = !empty($storeSettings['favicon_path']) ? $storeSettings['favicon_path'] : '/Project_Website/ProjectWeb/upload/img/Header/favicon.ico';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin</title>
    <!-- Favicon -->
    <link rel="icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
    <style>
        body {
            background: url('/Project_Website/ProjectWeb/upload/img/wallpaper.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            font-family: 'Segoe UI', Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: rgba(34, 34, 34, 0.95);
            padding: 40px 32px 32px 32px;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 370px;
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 24px;
            text-align: center;
            letter-spacing: 1px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f5f7fa;
            color: #222;
            font-size: 1rem;
            margin-bottom: 2px;
            box-sizing: border-box;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: 2px solid #444;
            background: #222;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #222, #444);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: background 0.2s, color 0.2s;
        }

        .login-btn:hover {
            background: #fff;
            color: #222;
        }

        .error-message {
            color: #ff4d4f;
            background: #222;
            border-left: 4px solid #ff4d4f;
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 18px;
            font-size: 1rem;
        }

        @media (max-width: 500px) {
            .login-container {
                padding: 24px 8px 16px 8px;
                max-width: 98vw;
            }
        }
    </style>
</head>

<body>
    <form class="login-container" method="post" autocomplete="off">
        <div class="login-title">Đăng nhập Admin</div>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required autofocus style="background:#f5f7fa;color:#222;">
        </div>
        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" required style="background:#f5f7fa;color:#222;">
        </div>
        <button type="submit" class="login-btn">Đăng nhập</button>
    </form>
</body>

</html>