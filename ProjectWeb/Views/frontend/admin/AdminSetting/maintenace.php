<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ?controller=Adminlogin');
    exit;
}
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
    <title>Hệ thống đang bảo trì</title>
    <!-- Favicon -->
    <link rel="icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }
        .maintenance-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 20px;
        }
        h1 {
            color: #e74c3c;
            margin-bottom: 20px;
        }
        p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .icon {
            font-size: 64px;
            margin-bottom: 20px;
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="icon">🛠️</div>
        <h1>Hệ thống đang bảo trì</h1>
        <p>Chúng tôi đang thực hiện bảo trì hệ thống để nâng cao chất lượng dịch vụ.</p>
        <p>Vui lòng quay lại sau 24 giờ.</p>
        <p>Xin lỗi vì sự bất tiện này và cảm ơn sự thông cảm của quý khách!</p>
    </div>
</body>
</html>