<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <style>
        /* CSS cơ bản cho sidebar */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #fff;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .sidebar .logo {
            padding: 1rem 0;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
        }
        
        .sidebar .logo h2 {
            margin: 0;
            font-size: 1.5rem;
            color: #fff;
        }
        
        .sidebar .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar .nav-links li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar .nav-links li a {
            color: #fff;
            text-decoration: none;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-links li a i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
        
        .sidebar .nav-links li.active a,
        .sidebar .nav-links li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .admin-info {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .sidebar .admin-info .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .sidebar .admin-info .admin-name {
            margin: 0;
            font-size: 0.9rem;
            color: #fff;
        }
        
        .sidebar .admin-info .logout {
            color: #fff;
            text-decoration: none;
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        .sidebar .admin-info .logout:hover {
            opacity: 1;
        }
        
        .sidebar-close {
            display: none;
            position: absolute;
            top: 18px;
            right: 18px;
            background: none;
            border: none;
            font-size: 2rem;
            color: #fff;
            z-index: 1200;
            cursor: pointer;
        }

        /* CSS cho màn hình lớn */
        @media (min-width: 992px) {
            .sidebar {
                left: 0;
            }
            
            .main-content {
                margin-left: 250px;
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
                display: block;
            }
            
            .sidebar-toggle {
                display: flex;
            }
        }
    </style>
    
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

    <script>
        // Script để hoạt động sidebar responsive
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const closeBtn = document.getElementById('sidebarCloseBtn');
            
            // Xử lý khi click nút đóng sidebar
            closeBtn.addEventListener('click', function() {
                sidebar.classList.remove('show');
            });
            
            // Thêm sự kiện click khi bấm vào các liên kết nav (cho mobile)
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 991.98) {
                        sidebar.classList.remove('show');
                    }
                });
            });
            
            // Kiểm tra nút toggle sidebar tồn tại không
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
            
            // Xử lý click bên ngoài sidebar để đóng (cho mobile)
            document.addEventListener('click', function(event) {
                const isClickInside = sidebar.contains(event.target) || 
                                    (toggleBtn && toggleBtn.contains(event.target));
                
                if (!isClickInside && sidebar.classList.contains('show') && window.innerWidth <= 991.98) {
                    sidebar.classList.remove('show');
                }
            });
        });
    </script>
</div>