/**
 * JavaScript cho trang quản lý tài khoản
 */
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý tabs
    initTabs();
    
    // Xử lý form đổi mật khẩu
    initPasswordForm();
    
    // Xử lý upload avatar
    initAvatarUpload();
    
    // Xử lý modal chi tiết đơn hàng
    initOrderDetailsModal();
    
    // Xử lý xóa địa chỉ
    initAddressDeleteConfirmation();
    
    // Xử lý form địa chỉ
    initAddressForm();
});

/**
 * Khởi tạo xử lý tabs
 */
function initTabs() {
    const navLinks = document.querySelectorAll('.account-menu .nav-link');
    
    navLinks.forEach(link => {
        if (!link.classList.contains('text-danger')) { // Bỏ qua link đăng xuất
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Xóa class active từ tất cả links
                navLinks.forEach(item => item.classList.remove('active'));
                
                // Thêm class active cho link được click
                this.classList.add('active');
                
                // Lấy ID của section cần hiển thị
                const sectionId = this.getAttribute('data-section');
                
                // Ẩn tất cả các section
                document.querySelectorAll('.content-section').forEach(section => {
                    section.classList.remove('active');
                });
                
                // Hiển thị section được chọn
                document.getElementById(sectionId).classList.add('active');
                
                // Cập nhật URL với thông tin tab
                const tabName = sectionId.replace('-section', '');
                history.replaceState(null, null, `index.php?controller=account&tab=${tabName}`);
            });
        }
    });
}

/**
 * Khởi tạo xử lý form đổi mật khẩu
 */
function initPasswordForm() {
    const passwordForm = document.getElementById('change-password-form');
    
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            // Kiểm tra các trường bắt buộc
            if (!currentPassword || !newPassword || !confirmPassword) {
                e.preventDefault();
                alert('Vui lòng điền đầy đủ thông tin!');
                return;
            }
            
            // Kiểm tra mật khẩu mới và xác nhận mật khẩu
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Mật khẩu mới và xác nhận mật khẩu không khớp!');
                return;
            }
            
            // Kiểm tra độ dài mật khẩu
            if (newPassword.length < 8) {
                e.preventDefault();
                alert('Mật khẩu mới phải có ít nhất 8 ký tự!');
                return;
            }
        });
    }
}

/**
 * Khởi tạo xử lý upload avatar
 */
function initAvatarUpload() {
    const avatarUpload = document.getElementById('avatar-upload');
    const avatarEdit = document.querySelector('.avatar-edit');
    
    if (avatarUpload && avatarEdit) {
        avatarEdit.addEventListener('click', function() {
            avatarUpload.click();
        });
        
        avatarUpload.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                // Tự động submit form khi đã chọn file
                document.getElementById('avatar-form').submit();
            }
        });
    }
}

/**
 * Khởi tạo xử lý modal chi tiết đơn hàng
 */
function initOrderDetailsModal() {
    const viewOrderButtons = document.querySelectorAll('.view-order-btn');
    const orderDetailModal = document.getElementById('orderDetailModal');
    
    if (viewOrderButtons.length && orderDetailModal) {
        viewOrderButtons.forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                const orderDetailContent = document.getElementById('order-detail-content');
                
                // Hiển thị trạng thái đang tải
                orderDetailContent.innerHTML = `
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                        <p class="mt-3">Đang tải thông tin đơn hàng...</p>
                    </div>
                `;
                
                // Gọi API để lấy thông tin chi tiết đơn hàng
                fetch('index.php?controller=account&action=getOrderDetails', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'order_id=' + orderId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        orderDetailContent.innerHTML = generateOrderDetailHTML(data.order, data.orderDetails);
                    } else {
                        orderDetailContent.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                ${data.message || 'Không thể tải thông tin đơn hàng.'}
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    orderDetailContent.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Đã xảy ra lỗi khi tải thông tin đơn hàng. Vui lòng thử lại sau.
                        </div>
                    `;
                });
            });
        });
    }
}

/**
 * Tạo HTML chi tiết đơn hàng
 */
function generateOrderDetailHTML(order, orderDetails) {
    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    };
    
    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('vi-VN', { 
            day: '2-digit', 
            month: '2-digit', 
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };
    
    // Xử lý trạng thái đơn hàng
    let statusClass = '', statusText = '';
    switch (order.status) {
        case 'pending':
            statusClass = 'bg-warning text-dark';
            statusText = 'Đang xử lý';
            break;
        case 'shipping':
            statusClass = 'bg-primary';
            statusText = 'Đang giao hàng';
            break;
        case 'completed':
            statusClass = 'bg-success';
            statusText = 'Đã hoàn thành';
            break;
        case 'cancelled':
            statusClass = 'bg-danger';
            statusText = 'Đã hủy';
            break;
        case 'waitConfirm':
            statusClass = 'bg-info';
            statusText = 'Chờ xác nhận';
            break;
        default:
            statusClass = 'bg-secondary';
            statusText = 'Không xác định';
            break;
    }
    
    // Xử lý phương thức thanh toán
    let paymentMethod = '';
    switch (order.payment_by) {
        case 'cod':
            paymentMethod = 'Thanh toán khi nhận hàng';
            break;
        case 'vnpay':
            paymentMethod = 'Thanh toán VNPay';
            break;
        case 'momo':
            paymentMethod = 'Thanh toán MoMo';
            break;
        default:
            paymentMethod = order.payment_by;
            break;
    }
    
    // Xử lý phương thức vận chuyển
    let shippingMethod = '';
    switch (order.shipping_method) {
        case 'ghn':
            shippingMethod = 'Giao hàng nhanh';
            break;
        case 'ghtk':
            shippingMethod = 'Giao hàng tiết kiệm';
            break;
        default:
            shippingMethod = order.shipping_method;
            break;
    }
    
    // Xử lý thông tin giao hàng từ ghi chú
    let receiverInfo = { name: '', phone: '', email: '', address: '', note: '' };
    if (order.note) {
        const parts = order.note.split(' - ');
        if (parts.length > 0) receiverInfo.name = parts[0];
        if (parts.length > 1) receiverInfo.phone = parts[1];
        if (parts.length > 2) receiverInfo.email = parts[2];
        if (parts.length > 3) receiverInfo.address = parts[3];
        
        // Ghi chú bổ sung có thể nằm sau dấu |
        const noteParts = parts[parts.length - 1].split('|');
        if (noteParts.length > 1) {
            receiverInfo.note = noteParts[1].trim();
        }
    }
    
    // Tạo HTML
    let html = `
        <div class="order-detail-card mb-3">
            <h6 class="card-header bg-light">Thông tin đơn hàng</h6>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Mã đơn hàng:</strong> ${order.order_number || ('ORD-' + order.id_Order)}</p>
                        <p><strong>Ngày đặt hàng:</strong> ${formatDate(order.created_at)}</p>
                        <p><strong>Trạng thái:</strong> 
                            <span class="badge ${statusClass}">
                                ${statusText}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tổng tiền:</strong> ${formatCurrency(order.total_amount)}</p>
                        <p><strong>Phí vận chuyển:</strong> ${formatCurrency(order.shipping_fee || 0)}</p>
                        <p><strong>Phương thức thanh toán:</strong> ${paymentMethod}</p>
                        <p><strong>Phương thức vận chuyển:</strong> ${shippingMethod}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="order-detail-card mb-3">
            <h6 class="card-header bg-light">Thông tin giao hàng</h6>
            <div class="card-body">
                <p><strong>Người nhận:</strong> ${receiverInfo.name}</p>
                <p><strong>Số điện thoại:</strong> ${receiverInfo.phone}</p>
                ${receiverInfo.email ? `<p><strong>Email:</strong> ${receiverInfo.email}</p>` : ''}
                <p><strong>Địa chỉ giao hàng:</strong> ${receiverInfo.address}</p>
                ${receiverInfo.note ? `<p><strong>Ghi chú:</strong> ${receiverInfo.note}</p>` : ''}
            </div>
        </div>
        
        <div class="order-detail-card">
            <h6 class="card-header bg-light">Sản phẩm đã đặt</h6>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">Size</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
    `;
    
    // Thêm sản phẩm vào bảng
    orderDetails.forEach(item => {
        html += `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="/Project_Website/ProjectWeb/upload/img/Home/${item.product.main_image}" 
                            alt="${item.product.name}" class="me-2" style="width: 50px; height: 50px; object-fit: cover;">
                        <span>${item.product.name}</span>
                    </div>
                </td>
                <td class="text-center">${item.size}</td>
                <td class="text-end">${formatCurrency(item.price)}</td>
                <td class="text-center">${item.quantity}</td>
                <td class="text-end">${formatCurrency(item.sub_total)}</td>
            </tr>
        `;
    });
    
    // Tổng cộng
    html += `
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end"><strong>Tạm tính:</strong></td>
                                <td class="text-end">${formatCurrency(parseFloat(order.total_amount) - parseFloat(order.shipping_fee || 0))}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Phí vận chuyển:</strong></td>
                                <td class="text-end">${formatCurrency(order.shipping_fee || 0)}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                                <td class="text-end fw-bold">${formatCurrency(order.total_amount)}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    `;
    
    return html;
}

/**
 * Khởi tạo xử lý xóa địa chỉ
 */
function initAddressDeleteConfirmation() {
    // Lắng nghe sự kiện submit trên tất cả các form xóa địa chỉ
    document.querySelectorAll('form[action*="deleteAddress"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Khởi tạo xử lý form địa chỉ
 */
function initAddressForm() {
    // Xử lý modal thêm địa chỉ
    const addAddressModal = document.getElementById('addAddressModal');
    if (addAddressModal) {
        const addAddressForm = document.getElementById('add-address-form');
        if (addAddressForm) {
            addAddressForm.addEventListener('submit', function(e) {
                // Kiểm tra các trường bắt buộc
                const requiredFields = this.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Vui lòng điền đầy đủ thông tin cần thiết!');
                }
            });
        }
    }
    
    // Xử lý modal chỉnh sửa địa chỉ
    const editAddressModal = document.getElementById('editAddressModal');
    if (editAddressModal) {
        // Lắng nghe sự kiện khi modal hiển thị để điền dữ liệu
        editAddressModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            
            // Lấy thông tin địa chỉ từ data attributes
            const addressId = button.getAttribute('data-address-id');
            const addressName = button.getAttribute('data-address-name');
            const receiverName = button.getAttribute('data-receiver-name');
            const phone = button.getAttribute('data-phone');
            const street = button.getAttribute('data-street');
            const province = button.getAttribute('data-province');
            const district = button.getAttribute('data-district');
            const ward = button.getAttribute('data-ward');
            const isDefault = button.getAttribute('data-is-default') === '1';
            
            // Điền dữ liệu vào form
            document.getElementById('edit_address_id').value = addressId;
            document.getElementById('edit_address_name').value = addressName;
            document.getElementById('edit_receiver_name').value = receiverName;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_street_address').value = street;
            
            // Thiết lập giá trị cho province
            const provinceSelect = document.getElementById('edit_province');
            if (provinceSelect) {
                provinceSelect.value = province;
                
                // Kích hoạt sự kiện change để load quận/huyện
                const changeEvent = new Event('change');
                provinceSelect.dispatchEvent(changeEvent);
                
                // Chờ để quận/huyện được load
                setTimeout(() => {
                    const districtSelect = document.getElementById('edit_district');
                    if (districtSelect) {
                        districtSelect.value = district;
                        
                        // Kích hoạt sự kiện change để load phường/xã
                        districtSelect.dispatchEvent(changeEvent);
                        
                        // Chờ để phường/xã được load
                        setTimeout(() => {
                            const wardSelect = document.getElementById('edit_ward');
                            if (wardSelect) {
                                wardSelect.value = ward;
                            }
                        }, 100);
                    }
                }, 100);
            }
            
            // Thiết lập checkbox mặc định
            document.getElementById('edit_is_default').checked = isDefault;
        });
        
        // Xử lý khi submit form
        const editAddressForm = document.getElementById('edit-address-form');
        if (editAddressForm) {
            editAddressForm.addEventListener('submit', function(e) {
                // Kiểm tra các trường bắt buộc
                const requiredFields = this.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Vui lòng điền đầy đủ thông tin cần thiết!');
                }
            });
        }
    }
}