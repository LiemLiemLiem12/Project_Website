<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài khoản - RSSTORE</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #000000;
            --secondary-color: #ffffff;
            --accent-color: #212529;
            --text-color: #212121;
            --text-light: #757575;
            --border-color: #e0e0e0;
            --bg-color: #f0f0f0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            padding: 0;
            margin: 0;
        }

        .container {
            max-width: 1440px;
            padding: 0 20px;
        }

        main.container {
            background-color: var(--bg-color);
            padding: 20px;
            min-height: 100vh;
        }

        .row {
            margin: 0;
        }

        .account-sidebar {
            background-color: var(--secondary-color);
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 20px;
            height: fit-content;
            padding: 20px;
            width: 100%;
        }

        .account-content {
            background-color: var(--secondary-color);
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 25px;
            min-height: calc(100% - 20px); /* Trừ đi margin-bottom */
        }

        /* Đảm bảo bên phải có chiều cao tương đương với sidebar */
        .account-content-wrapper {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .user-avatar {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border: 3px solid var(--border-color);
        }

        .avatar-wrapper {
            position: relative;
            display: inline-block;
            margin-top: 15px;
        }

        .avatar-edit {
            position: absolute;
            right: 5px;
            bottom: 5px;
            width: 32px;
            height: 32px;
            background-color: var(--primary-color);
            color: var(--secondary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
        }

        .user-info {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
        }

        .user-name {
            font-size: 22px;
            margin-top: 10px;
        }

        .user-email {
            font-size: 15px;
        }

        .account-menu {
            padding-top: 15px;
        }

        .account-menu .nav-link {
            color: var(--text-color);
            padding: 12px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 16px;
        }

        .account-menu .nav-link:hover, 
        .account-menu .nav-link.active {
            background-color: var(--primary-color);
            color: var(--secondary-color);
        }

        .account-menu .nav-link i {
            width: 24px;
            text-align: center;
            font-size: 18px;
        }

        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 25px;
            font-weight: 600;
            font-size: 24px;
            color: var(--accent-color);
            border-bottom: 2px solid var(--border-color);
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 80px;
            height: 2px;
            background-color: var(--primary-color);
        }

        .btn {
            padding: 10px 24px;
            font-size: 16px;
            border-radius: 5px;
        }

        .btn-sm {
            padding: 6px 14px;
            font-size: 14px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        /* Hide all content sections by default */
        .content-section {
            display: none;
            height: 100%;
        }

        /* Show active section */
        .content-section.active {
            display: block;
        }

        /* Card styles */
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid var(--border-color);
            margin-bottom: 20px;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
        }

        .table {
            margin-bottom: 0;
            font-size: 15px;
        }

        .table th {
            font-weight: 600;
            border-top: none;
            padding: 15px 12px;
            font-size: 16px;
        }

        .table td {
            padding: 15px 12px;
            vertical-align: middle;
        }

        /* Form elements */
        .form-control {
            padding: 12px 15px;
            font-size: 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.15);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .form-check-label {
            font-size: 15px;
        }

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .account-sidebar {
                margin-bottom: 20px;
                position: static;
            }
            
            .container {
                padding: 10px;
            }
        }

        /* Product cards in viewed/wishlist */
        .product-card-img {
            height: 220px;
            object-fit: cover;
        }

        /* Order status badges */
        .badge {
            font-weight: normal;
            padding: 7px 12px;
            font-size: 14px;
        }

        /* Links and buttons */
        a {
            color: var(--accent-color);
            text-decoration: none;
        }

        a:hover {
            color: var(--primary-color);
        }

        .btn-outline-dark {
            border-color: var(--accent-color);
            color: var(--accent-color);
        }

        .btn-outline-dark:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--secondary-color);
        }

        /* Fix cho chiều cao các phần */
        .col-md-9, .col-md-3 {
            display: flex;
            flex-direction: column;
        }

        .account-sidebar {
            height: 100%;
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <main class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="account-sidebar">
                    <div class="user-info text-center">
                        <div class="avatar-wrapper mb-3">
                            <img src="https://via.placeholder.com/110" alt="Avatar" class="user-avatar rounded-circle">
                            <div class="avatar-edit">
                                <i class="fas fa-camera"></i>
                            </div>
                        </div>
                        <h5 class="user-name mb-2">Nguyễn Văn A</h5>
                        <p class="user-email text-muted mb-0">nguyenvana@example.com</p>
                    </div>
                    <ul class="nav flex-column account-menu">
                        <li class="nav-item">
                            <a class="nav-link active" href="#profile-info" data-section="profile-section">
                                <i class="fas fa-user me-2"></i> Thông tin cá nhân
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#order-history" data-section="orders-section">
                                <i class="fas fa-shopping-bag me-2"></i> Đơn hàng của tôi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#address-book" data-section="address-section">
                                <i class="fas fa-map-marker-alt me-2"></i> Sổ địa chỉ
                            </a>
                        </li>
                      
                       
                      
                        <li class="nav-item">
                            <a class="nav-link" href="#change-password" data-section="password-section">
                                <i class="fas fa-lock me-2"></i> Đổi mật khẩu
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <a class="nav-link text-danger" href="#logout">
                                <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <div class="account-content-wrapper">
                    <!-- Profile Section -->
                    <div id="profile-section" class="account-content content-section active">
                        <h4 class="section-title">Thông tin cá nhân</h4>
                        <form id="profile-form">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="fullName" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control" id="fullName" value="Nguyễn Văn A">
                                </div>
                                <div class="col-md-6">
                                    <label for="phoneNumber" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="phoneNumber" value="0901234567">
                                </div>
                                <div class="col-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" value="nguyenvana@example.com" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label d-block">Giới tính</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="male" value="male" checked>
                                        <label class="form-check-label" for="male">Nam</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                                        <label class="form-check-label" for="female">Nữ</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="other" value="other">
                                        <label class="form-check-label" for="other">Khác</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="birthday" class="form-label">Ngày sinh</label>
                                    <input type="date" class="form-control" id="birthday" value="1990-01-01">
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Orders Section -->
                    <div id="orders-section" class="account-content content-section">
                        <h4 class="section-title">Đơn hàng của tôi</h4>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Ngày đặt</th>
                                        <th>Sản phẩm</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th class="text-center">Chi tiết</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>#ORD-12345</strong></td>
                                        <td>15/11/2023</td>
                                        <td>Áo Thun Basic (2), Quần Jean Slim (1)</td>
                                        <td class="text-danger fw-bold">950.000đ</td>
                                        <td><span class="badge bg-success">Đã giao</span></td>
                                        <td class="text-center"><a href="#" class="btn btn-sm btn-outline-dark">Xem</a></td>
                                    </tr>
                                    <tr>
                                        <td><strong>#ORD-12346</strong></td>
                                        <td>20/12/2023</td>
                                        <td>Áo Sơ Mi Trắng (1), Quần Kaki (1)</td>
                                        <td class="text-danger fw-bold">820.000đ</td>
                                        <td><span class="badge bg-primary">Đang giao</span></td>
                                        <td class="text-center"><a href="#" class="btn btn-sm btn-outline-dark">Xem</a></td>
                                    </tr>
                                    <tr>
                                        <td><strong>#ORD-12347</strong></td>
                                        <td>05/01/2024</td>
                                        <td>Áo Khoác Da (1)</td>
                                        <td class="text-danger fw-bold">1.250.000đ</td>
                                        <td><span class="badge bg-warning text-dark">Đang xử lý</span></td>
                                        <td class="text-center"><a href="#" class="btn btn-sm btn-outline-dark">Xem</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Address Section -->
                    <div id="address-section" class="account-content content-section">
                        <h4 class="section-title">Sổ địa chỉ</h4>
                        <div class="mb-4">
                            <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="fas fa-plus me-2"></i>Thêm địa chỉ mới
                            </button>
                            
                            <div class="row g-4">
                                <!-- Address Card 1 -->
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h5 class="card-title">Nhà riêng</h5>
                                                <span class="badge bg-dark">Mặc định</span>
                                            </div>
                                            <p class="card-text mb-1"><strong>Nguyễn Văn A</strong></p>
                                            <p class="card-text mb-1">0901234567</p>
                                            <p class="card-text mb-1">123 Nguyễn Văn Cừ, Phường 4, Quận 5</p>
                                            <p class="card-text">TP Hồ Chí Minh</p>
                                            <div class="d-flex mt-3">
                                                <button class="btn btn-sm btn-outline-dark me-2">Sửa</button>
                                                <button class="btn btn-sm btn-outline-danger">Xóa</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Address Card 2 -->
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h5 class="card-title">Công ty</h5>
                                            </div>
                                            <p class="card-text mb-1"><strong>Nguyễn Văn A</strong></p>
                                            <p class="card-text mb-1">0909876543</p>
                                            <p class="card-text mb-1">456 Lê Hồng Phong, Phường 10, Quận 10</p>
                                            <p class="card-text">TP Hồ Chí Minh</p>
                                            <div class="d-flex mt-3">
                                                <button class="btn btn-sm btn-outline-dark me-2">Sửa</button>
                                                <button class="btn btn-sm btn-outline-danger">Xóa</button>
                                                <button class="btn btn-sm btn-outline-primary ms-2">Đặt làm mặc định</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                 

                    <!-- Change Password Section -->
                    <div id="password-section" class="account-content content-section">
                        <h4 class="section-title">Đổi mật khẩu</h4>
                        
                        <form id="change-password-form">
                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Mật khẩu hiện tại</label>
                                <input type="password" class="form-control" id="currentPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control" id="newPassword" required>
                                <div class="form-text">Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường và số.</div>
                            </div>
                            <div class="mb-4">
                                <label for="confirmPassword" class="form-label">Xác nhận mật khẩu mới</label>
                                <input type="password" class="form-control" id="confirmPassword" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Cập nhật mật khẩu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Address Modal -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAddressModalLabel">Thêm địa chỉ mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="address-form">
                        <div class="mb-3">
                            <label for="addressName" class="form-label">Tên địa chỉ</label>
                            <input type="text" class="form-control" id="addressName" placeholder="Nhà, Công ty, ...">
                        </div>
                        <div class="mb-3">
                            <label for="fullNameAddress" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="fullNameAddress">
                        </div>
                        <div class="mb-3">
                            <label for="phoneAddress" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phoneAddress">
                        </div>
                        <div class="mb-3">
                            <label for="streetAddress" class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" id="streetAddress" placeholder="Số nhà, tên đường">
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="province" class="form-label">Tỉnh/TP</label>
                                <select class="form-select" id="province">
                                    <option selected>Chọn tỉnh/TP</option>
                                    <option value="HCM">TP. Hồ Chí Minh</option>
                                    <option value="HN">Hà Nội</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="district" class="form-label">Quận/Huyện</label>
                                <select class="form-select" id="district">
                                    <option selected>Chọn quận/huyện</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="ward" class="form-label">Phường/Xã</label>
                                <select class="form-select" id="ward">
                                    <option selected>Chọn phường/xã</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="defaultAddress">
                            <label class="form-check-label" for="defaultAddress">
                                Đặt làm địa chỉ mặc định
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary">Lưu địa chỉ</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Support Request Modal -->
    <div class="modal fade" id="newSupportRequestModal" tabindex="-1" aria-labelledby="newSupportRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newSupportRequestModalLabel">Tạo yêu cầu hỗ trợ mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="support-form">
                        <div class="mb-3">
                            <label for="supportType" class="form-label">Loại yêu cầu</label>
                            <select class="form-select" id="supportType">
                                <option selected>Chọn loại yêu cầu</option>
                                <option value="order">Vấn đề về đơn hàng</option>
                                <option value="product">Vấn đề về sản phẩm</option>
                                <option value="return">Đổi/Trả hàng</option>
                                <option value="payment">Vấn đề thanh toán</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="orderNumber" class="form-label">Mã đơn hàng (nếu có)</label>
                            <input type="text" class="form-control" id="orderNumber">
                        </div>
                        <div class="mb-3">
                            <label for="supportTitle" class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" id="supportTitle">
                        </div>
                        <div class="mb-3">
                            <label for="supportDescription" class="form-label">Mô tả chi tiết</label>
                            <textarea class="form-control" id="supportDescription" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="supportAttachment" class="form-label">Đính kèm hình ảnh (nếu có)</label>
                            <input class="form-control" type="file" id="supportAttachment" multiple>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary">Gửi yêu cầu</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all nav links
            const navLinks = document.querySelectorAll('.account-menu .nav-link');
            
            // Add click event to each nav link
            navLinks.forEach(link => {
                if (link.getAttribute('href') !== '#logout') {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Remove active class from all links
                        navLinks.forEach(item => item.classList.remove('active'));
                        
                        // Add active class to clicked link
                        this.classList.add('active');
                        
                        // Get the section to show
                        const sectionId = this.getAttribute('data-section');
                        
                        // Hide all content sections
                        document.querySelectorAll('.content-section').forEach(section => {
                            section.classList.remove('active');
                        });
                        
                        // Show the target section
                        document.getElementById(sectionId).classList.add('active');
                    });
                }
            });
        });
    </script>
</body>
</html>
