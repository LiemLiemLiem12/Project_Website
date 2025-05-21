/**
 * JavaScript cho trang Account
 */
document.addEventListener("DOMContentLoaded", function () {
  /**
   * Xử lý hiển thị chi tiết đơn hàng
   */
  const viewOrderButtons = document.querySelectorAll(".view-order-btn");
  const orderDetailModal = document.getElementById("orderDetailModal");

  if (viewOrderButtons.length > 0 && orderDetailModal) {
    const orderModalInstance = new bootstrap.Modal(orderDetailModal);
    const orderDetailContent = document.getElementById("order-detail-content");

    viewOrderButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const orderId = this.dataset.orderId;

        // Hiển thị trạng thái đang tải
        orderDetailContent.innerHTML = `
                    <div class="text-center p-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                        <p class="mt-3">Đang tải thông tin đơn hàng...</p>
                    </div>
                `;

        // Gọi AJAX để lấy thông tin đơn hàng
        fetch("index.php?controller=account&action=getOrderDetails", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "X-Requested-With": "XMLHttpRequest",
          },
          body: "order_id=" + orderId,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              // Tạo HTML chi tiết đơn hàng
              orderDetailContent.innerHTML = generateOrderDetailHTML(
                data.order,
                data.orderDetails
              );
            } else {
              orderDetailContent.innerHTML = `
                            <div class="text-center">
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    ${
                                      data.message ||
                                      "Không thể tải thông tin đơn hàng."
                                    }
                                </div>
                            </div>
                        `;
            }
          })
          .catch((error) => {
            console.error("Lỗi:", error);
            orderDetailContent.innerHTML = `
                        <div class="text-center">
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Đã xảy ra lỗi khi tải thông tin đơn hàng. Vui lòng thử lại sau.
                            </div>
                        </div>
                    `;
          });
      });
    });
  }

  /**
   * Xử lý hiệu ứng chọn tab
   */
  const navLinks = document.querySelectorAll(".account-menu .nav-link");
  if (navLinks.length > 0) {
    navLinks.forEach((link) => {
      if (!link.classList.contains("text-danger")) {
        // Không xử lý nút đăng xuất
        link.addEventListener("click", function (e) {
          e.preventDefault();

          // Xóa class active từ tất cả các link
          navLinks.forEach((item) => item.classList.remove("active"));

          // Thêm class active cho link được click
          this.classList.add("active");

          // Lấy ID section cần hiển thị
          const sectionId = this.getAttribute("data-section");

          // Ẩn tất cả các section
          document.querySelectorAll(".content-section").forEach((section) => {
            section.classList.remove("active");
          });

          // Hiển thị section cần hiển thị
          document.getElementById(sectionId).classList.add("active");

          // Cập nhật URL với tab parameter
          const tabName = sectionId.replace("-section", "");
          history.replaceState(
            null,
            null,
            `index.php?controller=account&tab=${tabName}`
          );
        });
      }
    });
  }

  /**
   * Xử lý modal chỉnh sửa địa chỉ
   */
  const editAddressModal = document.getElementById("editAddressModal");
  if (editAddressModal) {
    editAddressModal.addEventListener("show.bs.modal", function (event) {
      const button = event.relatedTarget;

      // Lấy dữ liệu từ button
      const addressId = button.getAttribute("data-address-id");
      const addressName = button.getAttribute("data-address-name");
      const receiverName = button.getAttribute("data-receiver-name");
      const phone = button.getAttribute("data-phone");
      const street = button.getAttribute("data-street");
      const province = button.getAttribute("data-province");
      const district = button.getAttribute("data-district");
      const ward = button.getAttribute("data-ward");
      const isDefault = button.getAttribute("data-is-default") === "1";

      // Điền dữ liệu vào form
      document.getElementById("edit_address_id").value = addressId;
      document.getElementById("edit_address_name").value = addressName;
      document.getElementById("edit_receiver_name").value = receiverName;
      document.getElementById("edit_phone").value = phone;
      document.getElementById("edit_street_address").value = street;

      // Thiết lập các select box
      const provinceSelect = document.getElementById("edit_province");
      provinceSelect.value = province;

      // Kích hoạt sự kiện change để load quận/huyện
      const changeEvent = new Event("change");
      provinceSelect.dispatchEvent(changeEvent);

      // Chờ một chút để quận/huyện được load sau đó thiết lập giá trị
      setTimeout(() => {
        const districtSelect = document.getElementById("edit_district");
        districtSelect.value = district;
        districtSelect.dispatchEvent(changeEvent);

        // Chờ tiếp để phường/xã được load
        setTimeout(() => {
          document.getElementById("edit_ward").value = ward;
        }, 100);
      }, 100);

      // Thiết lập checkbox mặc định
      document.getElementById("edit_is_default").checked = isDefault;
    });
  }

  /**
   * Xử lý form đổi mật khẩu
   */
  const passwordForm = document.getElementById("change-password-form");
  if (passwordForm) {
    passwordForm.addEventListener("submit", function (e) {
      const newPassword = document.getElementById("newPassword").value;
      const confirmPassword = document.getElementById("confirmPassword").value;

      if (newPassword !== confirmPassword) {
        e.preventDefault();
        alert("Mật khẩu mới và xác nhận mật khẩu không khớp!");
      } else if (newPassword.length < 8) {
        e.preventDefault();
        alert("Mật khẩu mới phải có ít nhất 8 ký tự!");
      }
    });
  }

  /**
   * Xử lý upload ảnh đại diện
   */
  const avatarUpload = document.getElementById("avatar-upload");
  const avatarEdit = document.querySelector(".avatar-edit");

  if (avatarUpload && avatarEdit) {
    avatarEdit.addEventListener("click", function () {
      avatarUpload.click();
    });
  }
});

/**
 * Tạo HTML chi tiết đơn hàng
 */
function generateOrderDetailHTML(order, orderDetails) {
  const formatCurrency = (amount) => {
    return new Intl.NumberFormat("vi-VN", {
      style: "currency",
      currency: "VND",
    }).format(amount);
  };

  const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("vi-VN", {
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  };

  // Xử lý trạng thái đơn hàng
  let statusClass = "",
    statusText = "";
  switch (order.status) {
    case "pending":
      statusClass = "bg-warning text-dark";
      statusText = "Đang xử lý";
      break;
    case "shipping":
      statusClass = "bg-primary";
      statusText = "Đang giao hàng";
      break;
    case "completed":
      statusClass = "bg-success";
      statusText = "Đã hoàn thành";
      break;
    case "cancelled":
      statusClass = "bg-danger";
      statusText = "Đã hủy";
      break;
    default:
      statusClass = "bg-secondary";
      statusText = "Không xác định";
      break;
  }

  // Xử lý thông tin giao hàng từ ghi chú
  let receiverName = "",
    phone = "",
    email = "",
    address = "",
    note = "";
  if (order.note) {
    const noteParts = order.note.split(" - ");
    if (noteParts.length >= 1) receiverName = noteParts[0];
    if (noteParts.length >= 2) phone = noteParts[1];
    if (noteParts.length >= 3) email = noteParts[2];
    if (noteParts.length >= 4) address = noteParts[3];
    if (noteParts.length >= 5) note = noteParts[4];
  }

  // Tạo HTML
  let html = `
        <div class="order-detail-card mb-3">
            <h6 class="bg-light p-2 border-bottom">Thông tin đơn hàng</h6>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Mã đơn hàng:</strong> ${
                          order.order_number || "ORD-" + order.id_Order
                        }</p>
                        <p><strong>Ngày đặt hàng:</strong> ${formatDate(
                          order.created_at
                        )}</p>
                        <p><strong>Trạng thái:</strong> 
                            <span class="badge ${statusClass}">
                                ${statusText}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tổng tiền:</strong> ${formatCurrency(
                          order.total_amount
                        )}</p>
                        <p><strong>Phí vận chuyển:</strong> ${formatCurrency(
                          order.shipping_fee || 0
                        )}</p>
                        <p><strong>Phương thức thanh toán:</strong> 
                            ${
                              order.payment_by === "cod"
                                ? "Thanh toán khi nhận hàng"
                                : order.payment_by === "vnpay"
                                ? "VNPay"
                                : order.payment_by === "momo"
                                ? "MoMo"
                                : order.payment_by
                            }
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="order-detail-card mb-3">
            <h6 class="bg-light p-2 border-bottom">Thông tin giao hàng</h6>
            <div class="card-body">
                <p><strong>Họ và tên:</strong> ${receiverName}</p>
                <p><strong>Số điện thoại:</strong> ${phone}</p>
                ${email ? `<p><strong>Email:</strong> ${email}</p>` : ""}
                <p><strong>Địa chỉ giao hàng:</strong> ${address}</p>
                ${note ? `<p><strong>Ghi chú:</strong> ${note}</p>` : ""}
            </div>
        </div>
        
        <div class="order-detail-card">
            <h6 class="bg-light p-2 border-bottom">Sản phẩm đã đặt</h6>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Size</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
    `;

  let totalAmount = 0;
  orderDetails.forEach((item) => {
    totalAmount += parseFloat(item.sub_total);
    html += `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="/Project_Website/ProjectWeb/upload/img/Home/${
                          item.product.main_image
                        }" 
                            alt="${
                              item.product.name
                            }" class="img-thumbnail me-2" style="width: 50px; height: 50px; object-fit: cover;">
                        <span>${item.product.name}</span>
                    </div>
                </td>
                <td>${item.size}</td>
                <td>${formatCurrency(item.price)}</td>
                <td>${item.quantity}</td>
                <td>${formatCurrency(item.sub_total)}</td>
            </tr>
        `;
  });

  html += `
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                                <td><strong>${formatCurrency(
                                  totalAmount
                                )}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Phí vận chuyển:</strong></td>
                                <td>${formatCurrency(
                                  order.shipping_fee || 0
                                )}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Thành tiền:</strong></td>
                                <td class="text-danger"><strong>${formatCurrency(
                                  order.total_amount
                                )}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    `;

  return html;
}
