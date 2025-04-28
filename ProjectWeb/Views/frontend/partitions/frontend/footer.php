<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/WEB_BAN_THOI_TRANG/layout/css/Footer.css">    
</head>

<body>
    <!-- FOOTER SECTION -->
    <footer class="footer bg-black text-white py-4">
        <div class="container">
            <!-- ĐĂNG KÍ NHẬN TIN + SOCIAL MEDIA ROW -->
            <div class="row mb-4 pb-2 border-bottom border-secondary">
                <!-- <div class="col-6">
                    <h5 class="text-uppercase mb-3">ĐĂNG KÍ NHẬN TIN</h5>
                    <div class="d-flex">
                        <input type="email" class="form-control" id="emailSubscribe" placeholder="Email">
                        <button type="button" class="btn text-white px-3" style="background-color: #212529;"
                            id="btnSubscribe">
                            <i class="bi bi-envelope-fill me-1"></i> ĐĂNG KÝ
                        </button>
                    </div>
                    <div class="invalid-feedback" id="emailFeedback">Email không hợp lệ</div>
                </div> -->
                <div class="col-12 text-end">
                    <div class="d-inline-block">
                        <a href="https://www.youtube.com/@storehanghieu1608"
                            class="text-white d-inline-flex justify-content-center align-items-center rounded me-1"
                            style="background-color: #FF0000; width: 35px; height: 35px;">
                            <i class="bi bi-youtube"></i>
                        </a>
                        <a href="https://www.instagram.com/RSStore"
                            class="text-white d-inline-flex justify-content-center align-items-center rounded me-1"
                            style="background-color: #C13584; width: 35px; height: 35px;">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://www.facebook.com/RSStore" class="text-white d-inline-flex justify-content-center align-items-center rounded"
                            style="background-color: #4267B2; width: 35px; height: 35px;">
                            <i class="bi bi-facebook"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- MAIN FOOTER SECTIONS -->
            <div class="row">
                <!-- GIỚI THIỆU -->
                <div class="col-md-4">
                    <h5 class="text-uppercase mb-3">GIỚI THIỆU</h5>
                    <p class="mb-2">RSStore - Chuỗi Phân Phối Thời Trang Nam Chuẩn Hiệu</p>
                    <ul class="list-unstyled">
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-telephone me-2"></i>
                            <span>02871006789</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-envelope me-2"></i>
                            <span>cs@RSStore.com</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-clock me-2"></i>
                            <span>Giờ mở cửa : 08:30 - 22:00</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-headset me-2"></i>
                            <span>Nhân viên tư vấn phản hồi tin nhắn đến 24:00 (Mỗi ngày)</span>
                        </li>
                    </ul>
                    <div class="mt-3">
                        <a href="http://online.gov.vn/Home/WebDetails/121880">
                            <img src="../upload/img/Footer/FooterBCT.webp" alt="Đã thông báo Bộ Công Thương" class="img-fluid mb-2"
                            style="max-width: 180px; background-color: rgba(255, 255, 255, 0); border-radius: 5px;">
                        </a>
                        <a href="https://www.dmca.com/Protection/Status.aspx?ID=9049de26-d97b-48dc-ab97-1e5fcb221fba&refurl=https://RSStore.com/">
                            <div>
                                <img src="../upload/img/Footer/FooterProtected.png" alt="DMCA Protected" class="img-fluid"
                                    style="max-width: 180px; background-color: rgba(255, 255, 255, 0); border-radius: 5px;">
                            </div>
                        </a>   
                    </div>
                </div>

                <!-- CHÍNH SÁCH --> 
                <div class="col-md-4">
                    <h5 class="text-uppercase mb-3">CHÍNH SÁCH</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-circle-fill me-2" style="font-size: 0.5rem;"></i>
                                <a href="#" class="text-white text-decoration-none">Hướng dẫn đặt hàng</a>
                            </div>
                        </li>
                        <li class="mb-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="bi bi-circle-fill me-2" style="font-size: 0.5rem;"></i>
                                    <a href="#" class="text-white text-decoration-none" data-bs-toggle="collapse"
                                        data-bs-target="#chinhSachList" aria-expanded="true"
                                        aria-controls="chinhSachList">Chính sách</a>
                                </div>
                                <button class="btn btn-link text-white p-0 border-0" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#chinhSachList" aria-expanded="true"
                                    aria-controls="chinhSachList">
                                    <i class="bi bi-chevron-up" id="chinhSachIcon"></i>
                                </button>
                            </div>
                        </li>
                    </ul>

                    <!-- Dropdown content - Expanded by default to match image -->
                    <div class="collapse show ms-3" id="chinhSachList">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="bi bi-circle-fill me-2" style="font-size: 0.5rem;"></i>
                                <a href="#" class="text-white text-decoration-none">Chính Sách Ưu Đãi Sinh Nhật</a>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-circle-fill me-2" style="font-size: 0.5rem;"></i>
                                <a href="#" class="text-white text-decoration-none">Chính Sách Khách Hàng Thân Thiết</a>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-circle-fill me-2" style="font-size: 0.5rem;"></i>
                                <a href="#" class="text-white text-decoration-none">Chính Sách Giao Hàng</a>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-circle-fill me-2" style="font-size: 0.5rem;"></i>
                                <a href="#" class="text-white text-decoration-none">Chính Sách Bảo Mật</a>
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-circle-fill me-2" style="font-size: 0.5rem;"></i>
                                <a href="#" class="text-white text-decoration-none">Chính Sách Đổi Hàng Và Bảo Hành</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- PHƯƠNG THỨC THANH TOÁN -->
                <div class="col-md-4">
                    <h5 class="text-uppercase mb-3">PHƯƠNG THỨC THANH TOÁN</h5>
                    <div class="mb-4">
                        <img src="../upload/img/Footer/ThanhToanSpay.webp" alt="Pay" class="img-fluid me-2"
                            style="height: 30px; background-color: #cccccc00; border-radius: 4px;">
                        <img src="../upload/img/Footer/ThanhToanVNPay.webp" alt="VNPay" class="img-fluid me-2"
                            style="height: 30px; background-color: #cccccc00; border-radius: 4px;">
                        <img src="../upload/img/Footer/ThanhToanCOD.webp" alt="COD" class="img-fluid"
                            style="height: 30px; background-color: #cccccc00; border-radius: 4px;">
                    </div>
                    
                    <h5 class="text-uppercase mb-3 mt-4">KẾT NỐI VỚI CHÚNG TÔI</h5>
                    <div class="d-flex">
                        <a href="https://www.youtube.com/@storehanghieu1608"
                            class="text-white d-inline-flex justify-content-center align-items-center rounded me-2"
                            style="background-color: #FF0000; width: 40px; height: 40px;">
                            <i class="bi bi-youtube"></i>
                        </a>
                        <a href="https://www.instagram.com/RSStore"
                            class="text-white d-inline-flex justify-content-center align-items-center rounded me-2"
                            style="background-color: #C13584; width: 40px; height: 40px;">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://www.facebook.com/RSStore" 
                            class="text-white d-inline-flex justify-content-center align-items-center rounded"
                            style="background-color: #4267B2; width: 40px; height: 40px;">
                            <i class="bi bi-facebook"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- COPYRIGHT SECTION -->
        <div class="py-3 mt-4">
            <div class="container text-center">
                <p class="mb-0">BẢN QUYỀN THUỘC VỀ RSSTORE</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/Footer.js"></script>
</body>

</html>