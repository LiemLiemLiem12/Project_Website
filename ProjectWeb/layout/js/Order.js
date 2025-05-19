document.addEventListener("DOMContentLoaded", function () {
  // Kiểm tra xem có đơn hàng thành công không
  checkOrderSuccess();

  // Khởi tạo chức năng tăng/giảm số lượng
  initQuantityControls();

  // Khởi tạo các phương thức thanh toán, vận chuyển
  initPaymentOptions();

  // Khởi tạo form xác nhận đặt hàng
  initOrderForm();
});

// Kiểm tra đơn hàng thành công
function checkOrderSuccess() {
  const urlParams = new URLSearchParams(window.location.search);
  if (
    urlParams.has("order_success") &&
    urlParams.get("order_success") === "true"
  ) {
    const orderSuccessModal = new bootstrap.Modal(
      document.getElementById("orderSuccessModal")
    );
    orderSuccessModal.show();

    // Xóa tham số từ URL
    const url = new URL(window.location);
    url.searchParams.delete("order_success");
    window.history.replaceState({}, "", url);

    // Xử lý khi modal đóng
    document
      .getElementById("orderSuccessModal")
      .addEventListener("hidden.bs.modal", function () {
        // Chuyển hướng đến trang chủ sau khi xem modal
        window.location.href = "index.php";
      });
  }
}

// Khởi tạo các nút tăng/giảm số lượng
function initQuantityControls() {
  // Xử lý nút giảm số lượng
  const minusBtns = document.querySelectorAll(".qty-btn.minus");
  minusBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const input = this.nextElementSibling;
      const currentValue = parseInt(input.value);
      const productItem = this.closest(".product-item");
      const productId = productItem.dataset.productId;
      const productSize = productItem.dataset.productSize;

      if (currentValue > 1) {
        const newValue = currentValue - 1;
        input.value = newValue;
        updateProductSubtotal(productItem);

        // Cập nhật số lượng trên server thông qua AJAX
        updateCartItemQuantity(productId, productSize, newValue);
      }
    });
  });

  // Xử lý nút tăng số lượng
  const plusBtns = document.querySelectorAll(".qty-btn.plus");
  plusBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const input = this.previousElementSibling;
      const currentValue = parseInt(input.value);
      const productItem = this.closest(".product-item");
      const productId = productItem.dataset.productId;
      const productSize = productItem.dataset.productSize;
      const maxStock = parseInt(productItem.dataset.maxStock || 0);

      // Kiểm tra số lượng tồn kho trước khi tăng
      if (maxStock && currentValue >= maxStock) {
        showNotification(`Chỉ còn ${maxStock} sản phẩm trong kho`, "error");
        return;
      }

      const newValue = currentValue + 1;
      input.value = newValue;
      updateProductSubtotal(productItem);

      // Cập nhật số lượng trên server thông qua AJAX
      updateCartItemQuantity(productId, productSize, newValue);
    });
  });

  // Xử lý khi nhập trực tiếp số lượng
  const quantityInputs = document.querySelectorAll(".qty-input");
  quantityInputs.forEach((input) => {
    input.addEventListener("change", function () {
      let value = parseInt(this.value);
      const productItem = this.closest(".product-item");
      const productId = productItem.dataset.productId;
      const productSize = productItem.dataset.productSize;
      const maxStock = parseInt(productItem.dataset.maxStock || 0);

      // Kiểm tra giá trị hợp lệ
      if (isNaN(value) || value < 1) {
        value = 1;
      } else if (maxStock && value > maxStock) {
        value = maxStock;
        showNotification(`Chỉ còn ${maxStock} sản phẩm trong kho`, "error");
      }

      this.value = value;
      updateProductSubtotal(productItem);

      // Cập nhật số lượng trên server thông qua AJAX
      updateCartItemQuantity(productId, productSize, value);
    });
  });
}

// Cập nhật số lượng sản phẩm trong giỏ hàng
function updateCartItemQuantity(productId, size, quantity) {
  // Hiển thị loading nếu cần
  // showLoading(true);

  fetch("index.php?controller=cart&action=updateItem", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
      "X-Requested-With": "XMLHttpRequest",
    },
    body: `product_id=${productId}&size=${size}&quantity=${quantity}`,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Cập nhật tổng cộng
        calculateTotal();

        // Cập nhật số lượng sản phẩm trên header
        if (document.getElementById("item-count")) {
          document.getElementById("item-count").textContent = data.count;
        }

        // Lưu vào sessionStorage
        sessionStorage.setItem("cartCount", data.count);
      } else {
        showNotification(data.message || "Có lỗi xảy ra", "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("Có lỗi xảy ra khi cập nhật giỏ hàng", "error");
    })
    .finally(() => {
      // Ẩn loading nếu cần
      // showLoading(false);
    });
}

// Cập nhật tổng giá của một sản phẩm
function updateProductSubtotal(productItem) {
  // Lấy thông tin số lượng và giá
  const quantityInput = productItem.querySelector(".qty-input");
  const quantity = parseInt(quantityInput.value);
  const originalPrice = parseInt(
    quantityInput.getAttribute("data-original-price") || 0
  );
  const priceElement = productItem.querySelector(".product-price");

  // Tính giá mới
  const newPrice = originalPrice * quantity;

  // Cập nhật hiển thị và dữ liệu
  if (priceElement) {
    priceElement.textContent = formatCurrency(newPrice);
  }

  // Cập nhật thuộc tính data-price
  quantityInput.setAttribute("data-price", newPrice);

  // Tính lại tổng cộng
  calculateTotal();
}

// Khởi tạo form xác nhận đặt hàng
function initOrderForm() {
  const completeOrderBtn = document.getElementById("complete-order");
  const checkoutForm = document.getElementById("checkout-form");

  if (completeOrderBtn && checkoutForm) {
    completeOrderBtn.addEventListener("click", function () {
      if (validateForm()) {
        // Hiển thị hiệu ứng loading
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
        this.disabled = true;

        // Đảm bảo số lượng sản phẩm đã được cập nhật trước khi thanh toán
        const productItems = document.querySelectorAll(".product-item");

        // Tạo danh sách các promises cập nhật số lượng
        const updatePromises = Array.from(productItems).map((item) => {
          const productId = item.dataset.productId;
          const productSize = item.dataset.productSize;
          const quantityInput = item.querySelector(".qty-input");
          const quantity = parseInt(quantityInput.value);

          return new Promise((resolve) => {
            fetch("index.php?controller=cart&action=updateItem", {
              method: "POST",
              headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "X-Requested-With": "XMLHttpRequest",
              },
              body: `product_id=${productId}&size=${productSize}&quantity=${quantity}`,
            })
              .then((response) => response.json())
              .then((data) => {
                resolve(data.success);
              })
              .catch((error) => {
                console.error("Error:", error);
                resolve(false);
              });
          });
        });

        // Sau khi cập nhật tất cả số lượng, submit form
        Promise.all(updatePromises)
          .then((results) => {
            // Kiểm tra nếu tất cả đều thành công
            if (results.every((result) => result)) {
              // Thêm trường ẩn để đánh dấu là submit bằng nút thanh toán
              const hiddenInput = document.createElement("input");
              hiddenInput.type = "hidden";
              hiddenInput.name = "checkout_submit";
              hiddenInput.value = "1";
              checkoutForm.appendChild(hiddenInput);

              // Submit form
              checkoutForm.submit();
            } else {
              // Có lỗi xảy ra khi cập nhật số lượng
              this.innerHTML =
                '<i class="bi bi-check2-circle me-2"></i> HOÀN TẤT ĐƠN HÀNG';
              this.disabled = false;
              showNotification(
                "Có lỗi xảy ra khi cập nhật số lượng sản phẩm. Vui lòng thử lại!",
                "error"
              );
            }
          })
          .catch((error) => {
            console.error("Error in updatePromises:", error);
            this.innerHTML =
              '<i class="bi bi-check2-circle me-2"></i> HOÀN TẤT ĐƠN HÀNG';
            this.disabled = false;
            showNotification("Có lỗi xảy ra. Vui lòng thử lại!", "error");
          });
      }
    });
  }
}

// Xác thực form
function validateForm() {
  let isValid = true;

  // Thêm class was-validated để hiển thị validation feedback
  const checkoutForm = document.getElementById("checkout-form");
  if (checkoutForm) {
    checkoutForm.classList.add("was-validated");

    // Kiểm tra các trường bắt buộc
    const requiredFields = checkoutForm.querySelectorAll("[required]");
    requiredFields.forEach((field) => {
      if (!field.value) {
        isValid = false;
        field.classList.add("is-invalid");
      } else {
        field.classList.remove("is-invalid");
      }
    });

    // Kiểm tra select fields
    const provinceSelect = document.getElementById("province");
    const districtSelect = document.getElementById("district");
    const wardSelect = document.getElementById("ward");

    if (provinceSelect && !provinceSelect.value) {
      isValid = false;
      provinceSelect.classList.add("is-invalid");
      showNotification("Vui lòng chọn Tỉnh/Thành phố", "error");
    }

    if (districtSelect && districtSelect.required && !districtSelect.value) {
      isValid = false;
      districtSelect.classList.add("is-invalid");
      showNotification("Vui lòng chọn Quận/Huyện", "error");
    }

    if (wardSelect && wardSelect.required && !wardSelect.value) {
      isValid = false;
      wardSelect.classList.add("is-invalid");
      showNotification("Vui lòng chọn Phường/Xã", "error");
    }

    // Kiểm tra chọn phương thức thanh toán và vận chuyển
    const paymentMethod = document.querySelector(
      'input[name="payment_method"]:checked'
    );
    const shippingMethod = document.querySelector(
      'input[name="shipping_method"]:checked'
    );

    if (!paymentMethod) {
      isValid = false;
      showNotification("Vui lòng chọn phương thức thanh toán", "error");
    }

    if (!shippingMethod) {
      isValid = false;
      showNotification("Vui lòng chọn phương thức vận chuyển", "error");
    }
  }

  return isValid;
}

// Tính toán tổng đơn hàng
function calculateTotal() {
  // Tính tổng tiền từ các sản phẩm
  let subtotal = 0;
  const productItems = document.querySelectorAll(".product-item");

  productItems.forEach((item) => {
    const quantityInput = item.querySelector(".qty-input");
    const price = parseInt(quantityInput.getAttribute("data-price") || 0);
    subtotal += price;
  });

  // Cập nhật hiển thị tạm tính
  const subtotalElement = document.getElementById("subtotal");
  if (subtotalElement) {
    subtotalElement.textContent = formatCurrency(subtotal);
  }

  // Lấy phí vận chuyển
  const shippingSelection = document.querySelector(".shipping-method.selected");
  const shippingFee = shippingSelection
    ? parseInt(shippingSelection.getAttribute("data-fee") || 0)
    : 35000;

  // Cập nhật hiển thị phí vận chuyển
  const shippingFeeElement = document.getElementById("shipping-fee");
  if (shippingFeeElement) {
    shippingFeeElement.textContent = formatCurrency(shippingFee);
  }

  // Lấy giảm giá (nếu có)
  let discount = 0;
  const discountRow = document.querySelector(".discount-row");
  if (discountRow && !discountRow.classList.contains("d-none")) {
    const discountElement = document.getElementById("discount");
    if (discountElement) {
      discount = parseCurrency(discountElement.textContent);
    }
  }

  // Tính tổng cộng
  const total = subtotal + shippingFee - discount;

  // Cập nhật hiển thị tổng cộng
  const totalElement = document.getElementById("total");
  if (totalElement) {
    totalElement.textContent = formatCurrency(total);
  }
}

// Hiển thị thông báo
function showNotification(message, type = "success") {
  // Tạo phần tử thông báo
  const notification = document.createElement("div");
  notification.className = `notification notification-${type}`;
  notification.innerHTML = `
        <div class="notification-icon">
            <i class="fas fa-${
              type === "success" ? "check-circle" : "exclamation-circle"
            }"></i>
        </div>
        <div class="notification-content">${message}</div>
    `;

  // Tìm hoặc tạo container thông báo
  let container = document.querySelector(".notification-container");

  if (!container) {
    container = document.createElement("div");
    container.className = "notification-container";
    document.body.appendChild(container);
  }

  // Thêm thông báo vào container
  container.appendChild(notification);

  // Thêm class hiển thị sau khoảng thời gian ngắn
  setTimeout(() => {
    notification.classList.add("show");
  }, 10);

  // Tự động ẩn thông báo sau 3 giây
  setTimeout(() => {
    notification.classList.remove("show");
    setTimeout(() => {
      notification.remove();
    }, 300);
  }, 3000);
}

// Định dạng số tiền
function formatCurrency(amount) {
  return (
    new Intl.NumberFormat("vi-VN", {
      style: "currency",
      currency: "VND",
      maximumFractionDigits: 0,
    })
      .format(amount)
      .replace("₫", "") + "₫"
  );
}

// Chuyển đổi chuỗi tiền thành số
function parseCurrency(currencyString) {
  // Loại bỏ ký tự không phải số
  return parseInt(currencyString.replace(/\D/g, "")) || 0;
}
