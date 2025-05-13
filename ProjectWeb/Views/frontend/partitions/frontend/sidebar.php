<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="logo"><h2>SR STORE</h2></div>
    <button class="sidebar-close d-md-none" id="sidebarCloseBtn" aria-label="Đóng menu"><span>&times;</span></button>
    <ul class="nav-links">
        <li class="<?= $controllerName === 'AdmindashboardController' ? 'active' : '' ?>">
            <a href="index.php?controller=admindashboard"><i class="fas fa-th-large"></i> Dashboard</a>
        </li>
        <li class="<?= $controllerName === 'AdminhomeController' ? 'active' : '' ?>">
            <a href="index.php?controller=adminhome"><i class="fas fa-home"></i> Trang chủ</a>
        </li>
        <li class="<?= $controllerName === 'AdminproductController' ? 'active' : '' ?>">
            <a href="index.php?controller=adminproduct"><i class="fas fa-tshirt"></i> Sản Phẩm</a>
        </li>
        <li class="<?= $controllerName === 'AdminorderController' ? 'active' : '' ?>">
            <a href="index.php?controller=adminorder"><i class="fas fa-shopping-cart"></i> Đơn Hàng</a>
        </li>
        <li class="<?= $controllerName === 'AdmincustomerController' ? 'active' : '' ?>">
            <a href="index.php?controller=admincustomer"><i class="fas fa-users"></i> Khách Hàng</a>
        </li>
        <li class="<?= $controllerName === 'AdmincategoryController' ? 'active' : '' ?>">
            <a href="index.php?controller=admincategory"><i class="fas fa-tags"></i> Danh Mục</a>
        </li>
        <li class="<?= $controllerName === 'AdminreportController' ? 'active' : '' ?>">
            <a href="index.php?controller=adminreport"><i class="fas fa-chart-bar"></i> Báo Cáo</a>
        </li>
        <li class="<?= $controllerName === 'AdminsettingController' ? 'active' : '' ?>">
            <a href="index.php?controller=adminsetting"><i class="fas fa-cog"></i> Cài Đặt</a>
        </li>
    </ul>
    <div class="admin-info">
        <img src="/Project_Website/ProjectWeb/upload/img/avatar.jpg" alt="Admin Avatar" class="profile-image">
        <div>
            <p class="admin-name">Admin</p>
            <a href="#" class="logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
        </div>
    </div>
</div>