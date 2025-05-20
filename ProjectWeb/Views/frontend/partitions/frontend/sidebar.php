<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <style>
        /* Variables for consistent colors and styling */
        :root {
            --sidebar-bg-start: #1e2430;
            --sidebar-bg-end: #121820;
            --sidebar-shadow: rgba(0, 0, 0, 0.2);
            --sidebar-width: 250px;
            --sidebar-border-light: rgba(255, 255, 255, 0.1);
            --active-item-color: #3498db;
            --overlay-transition: rgba(255, 255, 255, 0.03);
        }

        /* CSS cơ bản cho sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--sidebar-bg-start) 0%, var(--sidebar-bg-end) 100%);
            color: #fff;
            padding: 0;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.2),
                0 0 30px rgba(0, 0, 0, 0.15);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        /* Add overlay transition effect to integrate with main content */
        .sidebar::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(to right,
                    rgba(30, 36, 48, 0.6),
                    var(--overlay-transition));
            pointer-events: none;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
        }

        .sidebar .logo {
            padding: 1.5rem 1.5rem;
            text-align: center;
            background: rgba(0, 0, 0, 0.2);
            margin-bottom: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar .logo:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 10%;
            width: 80%;
            height: 1px;
            background: linear-gradient(90deg,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.2) 50%,
                    rgba(255, 255, 255, 0) 100%);
        }

        .sidebar .logo h2 {
            margin: 0;
            font-size: 1.6rem;
            color: #fff;
            font-weight: 600;
            letter-spacing: 1px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .sidebar .nav-links {
            list-style: none;
            padding: 0.5rem 0;
            margin: 0;
            position: relative;
        }

        /* Add subtle vertical guide line */
        .sidebar .nav-links::after {
            content: '';
            position: absolute;
            top: 5%;
            bottom: 5%;
            left: 31px;
            /* Aligned with icons */
            width: 1px;
            background: linear-gradient(to bottom,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.05) 30%,
                    rgba(255, 255, 255, 0.05) 70%,
                    rgba(255, 255, 255, 0) 100%);
            z-index: 0;
        }

        .sidebar .nav-links li {
            margin: 0.25rem 0.75rem;
            border-radius: 8px;
            transition: all 0.3s;
            position: relative;
            z-index: 1;
        }

        .sidebar .nav-links li a {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            padding: 0.75rem 1.25rem;
            display: flex;
            align-items: center;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            z-index: 2;
        }

        .sidebar .nav-links li a i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        /* Enhance hover effect with fluid animation */
        .sidebar .nav-links li a:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.95);
            transform: translateX(3px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-links li a:hover i {
            transform: scale(1.1);
            color: var(--active-item-color);
        }

        .sidebar .nav-links li.active {
            background: linear-gradient(to right,
                    rgba(30, 108, 217, 0.25),
                    rgba(30, 108, 217, 0.0));
            position: relative;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-links li.active a {
            color: rgba(255, 255, 255, 1);
            font-weight: 600;
        }

        .sidebar .nav-links li.active i {
            color: var(--active-item-color);
        }

        .sidebar .nav-links li.active::before {
            content: '';
            position: absolute;
            left: -0.75rem;
            top: 50%;
            transform: translateY(-50%);
            height: 65%;
            width: 4px;
            background: linear-gradient(to bottom, #3498db, #2980b9);
            border-radius: 0 2px 2px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* Add subtle animation for nav items */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .sidebar .nav-links li {
            animation: fadeIn 0.5s ease forwards;
            animation-delay: calc(0.05s * var(--item-index, 0));
            opacity: 0;
        }

        .sidebar .nav-links li:nth-child(1) {
            --item-index: 1;
        }

        .sidebar .nav-links li:nth-child(2) {
            --item-index: 2;
        }

        .sidebar .nav-links li:nth-child(3) {
            --item-index: 3;
        }

        .sidebar .nav-links li:nth-child(4) {
            --item-index: 4;
        }

        .sidebar .nav-links li:nth-child(5) {
            --item-index: 5;
        }

        .sidebar .nav-links li:nth-child(6) {
            --item-index: 6;
        }

        .sidebar .nav-links li:nth-child(7) {
            --item-index: 7;
        }

        .sidebar .nav-links li:nth-child(8) {
            --item-index: 8;
        }

        .sidebar .admin-info {
            margin-top: auto;
            padding: 1rem 1.25rem;
            background: rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            border-top: 1px solid rgba(255, 255, 255, 0.03);
        }

        .sidebar .admin-info:before {
            content: '';
            position: absolute;
            top: 0;
            left: 10%;
            width: 80%;
            height: 1px;
            background: linear-gradient(90deg,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.1) 50%,
                    rgba(255, 255, 255, 0) 100%);
        }

        .sidebar .admin-info .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
            cursor: default;
            /* Thêm con trỏ mặc định (mũi tên) */
        }

        .sidebar .admin-info:hover .profile-image {
            border-color: rgba(255, 255, 255, 0.4);
            transform: scale(1.05);
        }

        .sidebar .admin-info .admin-name {
            margin: 0;
            font-size: 0.95rem;
            color: #fff;
            font-weight: 600;
        }

        .sidebar .admin-info .logout {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.8rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .sidebar .admin-info .logout:hover {
            color: #fff;
        }

        .sidebar .admin-info .logout:hover i {
            transform: translateX(2px);
        }

        .sidebar-close {
            display: none;
            position: absolute;
            top: 18px;
            right: 18px;
            background: rgba(0, 0, 0, 0.2);
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.7);
            z-index: 1200;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .sidebar-close:hover {
            background: rgba(0, 0, 0, 0.4);
            color: rgba(255, 255, 255, 1);
            transform: rotate(90deg);
        }

        /* Modify main content to blend with sidebar */
        .main-content {
            transition: margin-left 0.3s ease, background 0.3s ease;
            background: linear-gradient(to right,
                    rgba(240, 242, 245, 0.9) 0%,
                    rgba(255, 255, 255, 1) 5%);
        }

        /* CSS cho màn hình lớn */
        @media (min-width: 992px) {
            .sidebar {
                left: 0;
            }

            .main-content {
                margin-left: var(--sidebar-width);
            }

            .sidebar-toggle {
                display: none;
            }
        }

        /* CSS cho tablet */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .sidebar {
                left: -250px;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: flex;
            }
        }

        /* CSS cho mobile */
        @media (max-width: 767.98px) {
            .sidebar {
                left: -250px;
                width: 250px;
                transition: left 0.3s ease;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .sidebar-close {
                display: flex;
            }

            .sidebar-toggle {
                display: flex;
            }
        }

        /* CSS cho avatar ở góc trên phải */
        .user-avatar,
        .user-profile-icon,
        .admin-avatar,
        header .profile-image {
            cursor: default !important;
        }

        /* CSS cho tất cả các avatar trên trang */
        .profile-image,
        img.profile-image,
        .user-avatar,
        .user-profile-icon,
        .admin-avatar,
        header .profile-image,
        .admin-info img {
            cursor: default !important;
        }
    </style>

    <div class="logo">
        <h2>SR STORE</h2>
    </div>
    <button class="sidebar-close d-md-none" id="sidebarCloseBtn" aria-label="Đóng menu"><span>&times;</span></button>
    <?php
    // Get the current controller from URL
    $currentController = isset($_GET['controller']) ? strtolower($_GET['controller']) : 'home';
    ?>
    <ul class="nav-links">
        <li class="<?= $currentController === 'admindashboard' ? 'active' : '' ?>">
            <a href="index.php?controller=admindashboard"><i class="fas fa-th-large"></i> Dashboard</a>
        </li>
        <li class="<?= $currentController === 'adminhome' ? 'active' : '' ?>">
            <a href="index.php?controller=adminhome"><i class="fas fa-home"></i> Trang chủ</a>
        </li>
        <li class="<?= $currentController === 'adminproduct' ? 'active' : '' ?>">
            <a href="index.php?controller=adminproduct"><i class="fas fa-tshirt"></i> Sản Phẩm</a>
        </li>
        <li class="<?= $currentController === 'adminorder' ? 'active' : '' ?>">
            <a href="index.php?controller=adminorder"><i class="fas fa-shopping-cart"></i> Đơn Hàng</a>
        </li>
        <li class="<?= $currentController === 'admincustomer' ? 'active' : '' ?>">
            <a href="index.php?controller=admincustomer"><i class="fas fa-users"></i> Tài Khoản</a>
        </li>
        <li class="<?= $currentController === 'admincategory' ? 'active' : '' ?>">
            <a href="index.php?controller=admincategory"><i class="fas fa-tags"></i> Danh Mục</a>
        </li>
        <li class="<?= $currentController === 'adminreport' ? 'active' : '' ?>">
            <a href="index.php?controller=adminreport"><i class="fas fa-chart-bar"></i> Báo Cáo</a>
        </li>
        <li class="<?= $currentController === 'adminsetting' ? 'active' : '' ?>">
            <a href="index.php?controller=adminsetting"><i class="fas fa-cog"></i> Cài Đặt</a>
        </li>
    </ul>
    <div class="admin-info">
        <img src="/Project_Website/ProjectWeb/upload/img/avatar.jpg" alt="Admin Avatar" class="profile-image">
        <div>
            <p class="admin-name">Admin</p>
            <a id="btn-logout" href="index.php?controller=adminlogin&action=logout" class="logout"><i
                    class="fas fa-sign-out-alt"></i> Đăng xuất</a>
        </div>
    </div>

    <script>
        // Script để hoạt động sidebar responsive
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const closeBtn = document.getElementById('sidebarCloseBtn');

            // Xử lý khi click nút đóng sidebar
            closeBtn.addEventListener('click', function () {
                sidebar.classList.remove('show');
            });

            // Thêm sự kiện click khi bấm vào các liên kết nav (cho mobile)
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.addEventListener('click', function () {
                    if (window.innerWidth <= 991.98) {
                        sidebar.classList.remove('show');
                    }
                });
            });

            // Kiểm tra nút toggle sidebar tồn tại không
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                });
            }

            // Xử lý click bên ngoài sidebar để đóng (cho mobile)
            document.addEventListener('click', function (event) {
                const isClickInside = sidebar.contains(event.target) ||
                    (toggleBtn && toggleBtn.contains(event.target));

                if (!isClickInside && sidebar.classList.contains('show') && window.innerWidth <= 991.98) {
                    sidebar.classList.remove('show');
                }
            });

            // Them code de highlight menu item hien tai
            // Lay URL hien tai
            const currentUrl = window.location.href;
            // Lay controller tu URL
            const urlParams = new URLSearchParams(window.location.search);
            const controller = urlParams.get('controller') || 'home';

            // Highlight item tuong ung
            document.querySelectorAll('.nav-links li').forEach(item => {
                const link = item.querySelector('a');
                if (link.href.includes(`controller=${controller}`)) {
                    item.classList.add('active');
                }
            });


        });
    </script>
</div>