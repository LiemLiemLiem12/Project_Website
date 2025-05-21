document.addEventListener("DOMContentLoaded", function () {
  // Kiểm tra xem có đơn hàng thành công không
  checkOrderSuccess();

  // Khởi tạo chức năng tăng/giảm số lượng
  initQuantityControls();

  // Khởi tạo các phương thức thanh toán, vận chuyển
  initPaymentOptions();

  // Khởi tạo form xác nhận đặt hàng
  initOrderForm();

  // Khởi tạo xử lý địa chỉ
  initAddressSelection();

  // Khởi tạo xử lý mã giảm giá
  // initDiscountCodeForm();
});
// Trong hàm initOrderForm() hoặc thêm hàm mới
document
  .getElementById("complete-order")
  .addEventListener("click", function () {
    if (validateForm()) {
      // Lấy phương thức thanh toán đã chọn
      const paymentMethod = document.querySelector(
        'input[name="payment_method"]:checked'
      ).value;

      // Xử lý theo phương thức thanh toán
      if (paymentMethod === "cod") {
        // Thanh toán khi nhận hàng - submit form bình thường
        document.getElementById("checkout-form").submit();
      } else if (paymentMethod === "momo") {
        // Thanh toán MoMo - thay đổi action của form
        document.getElementById("checkout-form").action =
          "index.php?controller=payment&action=processMomo";
        document.getElementById("checkout-form").submit();
      } else if (paymentMethod === "vnpay") {
        // Thanh toán VNPay - thay đổi action của form
        document.getElementById("checkout-form").action =
          "index.php?controller=payment&action=processVnpay";
        document.getElementById("checkout-form").submit();
      }
    }
  });
function initAddressSelection() {
  const addressCards = document.querySelectorAll(".address-card");
  const addressRadios = document.querySelectorAll(".address-selector");
  const saveAddressOption = document.querySelector(".save-address-option");
  const selectedAddressIdInput = document.getElementById("selected_address_id");

  if (addressCards.length > 0) {
    // Xử lý khi click vào card địa chỉ
    addressCards.forEach((card) => {
      card.addEventListener("click", function () {
        const radio = this.querySelector(".address-selector");
        if (radio) {
          radio.checked = true;

          // Trigger change event
          const event = new Event("change");
          radio.dispatchEvent(event);
        }
      });
    });

    // Xử lý khi chọn radio button
    addressRadios.forEach((radio) => {
      radio.addEventListener("change", function () {
        // Xóa border cho tất cả card
        addressCards.forEach((card) => {
          card.classList.remove("border-primary");
        });

        // Thêm border cho card được chọn
        const selectedCard = this.closest(".address-card");
        if (selectedCard) {
          selectedCard.classList.add("border-primary");
        }

        // Lưu giá trị địa chỉ được chọn vào input hidden
        if (selectedAddressIdInput) {
          selectedAddressIdInput.value = this.value;
        }

        // Ẩn/hiện tùy chọn lưu địa chỉ
        if (saveAddressOption) {
          saveAddressOption.style.display =
            this.value === "new" ? "block" : "none";
        }

        // Xử lý điền thông tin địa chỉ
        if (this.value === "new") {
          // Đặt lại form
          resetAddressForm();

          // Mở khóa các trường input
          enableAddressFields(true);
        } else {
          // Lấy dữ liệu địa chỉ từ server
          fetchAddressData(this.value);
        }
      });
    });

    // Kích hoạt địa chỉ đã chọn ban đầu
    const selectedRadio = document.querySelector(".address-selector:checked");
    if (selectedRadio) {
      // Lưu giá trị địa chỉ được chọn vào input hidden
      if (selectedAddressIdInput) {
        selectedAddressIdInput.value = selectedRadio.value;
      }

      // Kích hoạt sự kiện change để điền thông tin địa chỉ
      const event = new Event("change");
      selectedRadio.dispatchEvent(event);
    }
  }
}

// Đặt lại form địa chỉ
function resetAddressForm() {
  const addressForm = document.getElementById("checkout-form");
  if (addressForm) {
    // Reset các trường input
    const inputs = addressForm.querySelectorAll(
      'input:not([type="hidden"]):not([type="radio"]), select, textarea'
    );
    inputs.forEach((input) => {
      input.value = "";
      input.classList.remove("is-invalid");
    });
  }
}

// Bật/tắt các trường trong form địa chỉ
function enableAddressFields(enable) {
  const addressForm = document.getElementById("checkout-form");
  if (addressForm) {
    // Các trường địa chỉ
    const fields = addressForm.querySelectorAll(
      "input[name='fullname'], input[name='phone'], input[name='email'], input[name='address'], " +
        "select[name='province'], select[name='district'], select[name='ward']"
    );

    fields.forEach((field) => {
      field.disabled = !enable;
      // Thêm class để chỉ ra rằng trường đã bị vô hiệu hóa
      if (!enable) {
        field.classList.add("bg-light");
      } else {
        field.classList.remove("bg-light");
      }
    });
  }
}

// Lấy dữ liệu địa chỉ từ server
function fetchAddressData(addressId) {
  // Hiển thị loading nếu cần
  showLoading(true);

  fetch("index.php?controller=order&action=loadAddress", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
      "X-Requested-With": "XMLHttpRequest",
    },
    body: "address_id=" + addressId,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Điền dữ liệu vào form
        fillAddressForm(data.address);

        // Khóa các trường input
        enableAddressFields(false);
      } else {
        showNotification(
          data.message || "Không thể lấy thông tin địa chỉ",
          "error"
        );

        // Mở khóa các trường input để người dùng có thể nhập
        enableAddressFields(true);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      // showNotification("Có lỗi xảy ra khi lấy thông tin địa chỉ", "error");

      // Mở khóa các trường input
      enableAddressFields(true);
    })
    .finally(() => {
      // Ẩn loading
      showLoading(false);
    });
}
// Khởi tạo xử lý chọn địa chỉ
function initAddressSelection() {
  const addressCards = document.querySelectorAll(".address-card");
  const addressRadios = document.querySelectorAll(".address-selector");
  const saveAddressOption = document.querySelector(".save-address-option");

  if (addressCards.length > 0) {
    // Xử lý khi click vào card địa chỉ
    addressCards.forEach((card) => {
      card.addEventListener("click", function () {
        const radio = this.querySelector(".address-selector");
        if (radio) {
          radio.checked = true;

          // Trigger change event
          const event = new Event("change");
          radio.dispatchEvent(event);
        }
      });
    });

    // Xử lý khi chọn radio button
    addressRadios.forEach((radio) => {
      radio.addEventListener("change", function () {
        // Xóa border cho tất cả card
        addressCards.forEach((card) => {
          card.classList.remove("border-primary");
        });

        // Thêm border cho card được chọn
        const selectedCard = this.closest(".address-card");
        if (selectedCard) {
          selectedCard.classList.add("border-primary");
        }

        // Ẩn/hiện tùy chọn lưu địa chỉ
        if (saveAddressOption) {
          saveAddressOption.style.display =
            this.value === "new" ? "block" : "none";
        }

        // Xử lý điền thông tin địa chỉ
        if (this.value === "new") {
          // Đặt lại form
          resetAddressForm();

          // Mở khóa các trường input
          enableAddressFields(true);
        } else {
          // Lấy dữ liệu địa chỉ từ server
          fetchAddressData(this.value);
        }
      });
    });

    // Kích hoạt địa chỉ đã chọn ban đầu
    const selectedRadio = document.querySelector(".address-selector:checked");
    if (selectedRadio) {
      const event = new Event("change");
      selectedRadio.dispatchEvent(event);
    }
  }
}

// Đặt lại form địa chỉ
function resetAddressForm() {
  const addressForm = document.getElementById("checkout-form");
  if (addressForm) {
    // Reset các trường input
    const inputs = addressForm.querySelectorAll(
      'input:not([type="hidden"]):not([type="radio"]), select, textarea'
    );
    inputs.forEach((input) => {
      input.value = "";
      input.classList.remove("is-invalid");
    });
  }
}

// Bật/tắt các trường trong form địa chỉ
function enableAddressFields(enable) {
  const addressForm = document.getElementById("checkout-form");
  if (addressForm) {
    // Các trường địa chỉ
    const fields = addressForm.querySelectorAll(
      ".shipping-fields input, .shipping-fields select, .shipping-fields textarea"
    );
    fields.forEach((field) => {
      field.disabled = !enable;
    });
  }
}

// Lấy dữ liệu địa chỉ từ server
function fetchAddressData(addressId) {
  // Hiển thị loading nếu cần
  showLoading(true);

  fetch("index.php?controller=order&action=loadAddress", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
      "X-Requested-With": "XMLHttpRequest",
    },
    body: "address_id=" + addressId,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Điền dữ liệu vào form
        fillAddressForm(data.address);

        // Khóa các trường input
        enableAddressFields(false);
      } else {
        showNotification(
          data.message || "Không thể lấy thông tin địa chỉ",
          "error"
        );

        // Mở khóa các trường input để người dùng có thể nhập
        enableAddressFields(true);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      // showNotification("Có lỗi xảy ra khi lấy thông tin địa chỉ", "error");

      // Mở khóa các trường input
      enableAddressFields(true);
    })
    .finally(() => {
      // Ẩn loading
      showLoading(false);
    });
}

// Điền dữ liệu địa chỉ vào form
function fillAddressForm(address) {
  // Điền các trường input
  document.getElementById("fullname").value = address.fullname;
  document.getElementById("phone").value = address.phone;

  // Nếu có email
  const emailInput = document.getElementById("email");
  if (emailInput && address.email) {
    emailInput.value = address.email;
  }

  document.getElementById("address").value = address.address;

  // Xử lý select tỉnh/quận/huyện
  const provinceSelect = document.getElementById("province");
  const districtSelect = document.getElementById("district");
  const wardSelect = document.getElementById("ward");

  if (provinceSelect) {
    provinceSelect.value = address.province;

    // Kích hoạt sự kiện change để load quận/huyện
    const event = new Event("change");
    provinceSelect.dispatchEvent(event);

    // Chờ một chút để quận/huyện được load
    setTimeout(() => {
      if (districtSelect) {
        districtSelect.value = address.district;
        districtSelect.dispatchEvent(event);

        // Chờ tiếp để phường/xã được load
        setTimeout(() => {
          if (wardSelect) {
            wardSelect.value = address.ward;
          }
        }, 100);
      }
    }, 100);
  }
}

// Hiển thị/ẩn loading
function showLoading(show) {
  // Thêm code hiển thị loading nếu cần
}

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

// Các hàm còn lại giữ nguyên...
// Xử lý chọn địa chỉ từ sổ địa chỉ
function initAddressSelection() {
  const addressCards = document.querySelectorAll(".address-card");
  const addressRadios = document.querySelectorAll(".address-selector");
  const saveAddressOption = document.querySelector(".save-address-option");

  if (addressCards.length > 0) {
    // Xử lý khi click vào card địa chỉ
    addressCards.forEach((card) => {
      card.addEventListener("click", function () {
        const radio = this.querySelector(".address-selector");
        if (radio) {
          radio.checked = true;
          // Kích hoạt sự kiện change
          const event = new Event("change");
          radio.dispatchEvent(event);
        }
      });
    });

    // Xử lý khi chọn radio button
    addressRadios.forEach((radio) => {
      radio.addEventListener("change", function () {
        // Xóa border cho tất cả card
        addressCards.forEach((card) => {
          card.classList.remove("border-primary");
        });

        // Thêm border cho card được chọn
        const selectedCard = this.closest(".address-card");
        if (selectedCard) {
          selectedCard.classList.add("border-primary");
        }

        // Ẩn/hiện tùy chọn lưu địa chỉ
        if (this.value === "new") {
          // Đặt lại form
          document.getElementById("fullname").value = "";
          document.getElementById("phone").value = "";
          document.getElementById("email").value = "";
          document.getElementById("address").value = "";
          document.getElementById("province").value = "";
          document.getElementById("district").value = "";
          document.getElementById("ward").value = "";

          // Hiện tùy chọn lưu địa chỉ
          if (saveAddressOption) {
            saveAddressOption.style.display = "block";
          }
        } else {
          // Lấy dữ liệu địa chỉ từ server
          fetchAddressData(this.value);

          // Ẩn tùy chọn lưu địa chỉ
          if (saveAddressOption) {
            saveAddressOption.style.display = "none";
          }
        }
      });
    });

    // Kích hoạt địa chỉ đã chọn ban đầu
    const selectedRadio = document.querySelector(".address-selector:checked");
    if (selectedRadio) {
      const event = new Event("change");
      selectedRadio.dispatchEvent(event);
    }
  }
}
// Khởi tạo các phương thức thanh toán, vận chuyển
function initPaymentOptions() {
  // Xử lý khi chọn phương thức vận chuyển
  const shippingMethods = document.querySelectorAll(
    'input[name="shipping_method"]'
  );
  shippingMethods.forEach((method) => {
    method.addEventListener("change", function () {
      // Xóa class selected khỏi tất cả các phương thức
      document.querySelectorAll(".shipping-method").forEach((item) => {
        item.classList.remove("selected");
      });

      // Thêm class selected cho phương thức được chọn
      const shippingCard = this.closest(".shipping-method");
      if (shippingCard) {
        shippingCard.classList.add("selected");
      }

      // Tính lại tổng tiền sau khi chọn phương thức vận chuyển
      calculateTotal();
    });
  });

  // Xử lý khi chọn phương thức thanh toán (tương tự)
  const paymentMethods = document.querySelectorAll(
    'input[name="payment_method"]'
  );
  paymentMethods.forEach((method) => {
    method.addEventListener("change", function () {
      document.querySelectorAll(".payment-method").forEach((item) => {
        item.classList.remove("selected");
      });

      const paymentCard = this.closest(".payment-method");
      if (paymentCard) {
        paymentCard.classList.add("selected");
      }
    });
  });
}
// Lấy dữ liệu địa chỉ từ server
// function fetchAddressData(addressId) {
//   // Hiển thị loading
//   showLoading(true);

//   fetch("index.php?controller=order&action=loadAddress", {
//     method: "POST",
//     headers: {
//       "Content-Type": "application/x-www-form-urlencoded",
//       "X-Requested-With": "XMLHttpRequest",
//     },
//     body: "address_id=" + addressId,
//   })
//     .then((response) => response.json())
//     .then((data) => {
//       if (data.success) {
//         // Điền dữ liệu vào form
//         fillAddressForm(data.address);
//       } else {
//         showNotification(
//           data.message || "Không thể lấy thông tin địa chỉ",
//           "error"
//         );
//       }
//     })
//     .catch((error) => {
//       console.error("Error:", error);
//       showNotification("Có lỗi xảy ra khi lấy thông tin địa chỉ", "error");
//     })
//     .finally(() => {
//       showLoading(false);
//     });
// }

// // Điền dữ liệu địa chỉ vào form
// function fillAddressForm(address) {
//   document.getElementById("fullname").value = address.receiver_name;
//   document.getElementById("phone").value = address.phone;

//   // Nếu có email
//   const emailInput = document.getElementById("email");
//   if (emailInput && address.email) {
//     emailInput.value = address.email;
//   }

//   document.getElementById("address").value = address.street_address;

//   // Xử lý select tỉnh/quận/huyện
//   const provinceSelect = document.getElementById("province");
//   const districtSelect = document.getElementById("district");
//   const wardSelect = document.getElementById("ward");

//   if (provinceSelect) {
//     provinceSelect.value = address.province;

//     // Kích hoạt sự kiện change để load quận/huyện
//     const event = new Event("change");
//     provinceSelect.dispatchEvent(event);

//     // Chờ một chút để quận/huyện được load
//     setTimeout(() => {
//       if (districtSelect) {
//         districtSelect.value = address.district;
//         districtSelect.dispatchEvent(event);

//         // Chờ tiếp để phường/xã được load
//         setTimeout(() => {
//           if (wardSelect) {
//             wardSelect.value = address.ward;
//           }
//         }, 100);
//       }
//     }, 100);
//   }
// }

// // Hiển thị/ẩn loading
// function showLoading(show) {
//   // Thêm hiệu ứng loading nếu cần
// }

// Khởi tạo

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
  console.log("Found button and form");
  if (completeOrderBtn && checkoutForm) {
    console.log("Found button and form");
    completeOrderBtn.addEventListener("click", function () {
      console.log("Button clicked");
      if (validateForm()) {
        console.log("Form validation passed");
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
      } else {
        console.log("Form validation failed");
      }
    });
  } else {
    console.log("Button or form not found", {
      button: completeOrderBtn,
      form: checkoutForm,
    });
  }
}

// Xác thực form
function validateForm() {
  let isValid = true;
  console.log("Validating form...");

  // Thêm class was-validated để hiển thị validation feedback
  const checkoutForm = document.getElementById("checkout-form");
  if (checkoutForm) {
    checkoutForm.classList.add("was-validated");

    // Kiểm tra các trường bắt buộc
    const requiredFields = checkoutForm.querySelectorAll("[required]");
    console.log("Required fields:", requiredFields.length);

    requiredFields.forEach((field) => {
      console.log("Checking field:", field.id, "Value:", field.value);
      if (!field.value) {
        isValid = false;
        field.classList.add("is-invalid");
        console.log("Field invalid:", field.id);
      } else {
        field.classList.remove("is-invalid");
      }
    });

    // Kiểm tra phương thức thanh toán và vận chuyển
    const paymentMethod = document.querySelector(
      'input[name="payment_method"]:checked'
    );
    const shippingMethod = document.querySelector(
      'input[name="shipping_method"]:checked'
    );

    if (!paymentMethod) {
      isValid = false;
      console.log("No payment method selected");
      showNotification("Vui lòng chọn phương thức thanh toán", "error");
    }

    if (!shippingMethod) {
      isValid = false;
      console.log("No shipping method selected");
      showNotification("Vui lòng chọn phương thức vận chuyển", "error");
    }
  }

  console.log("Form validation result:", isValid);
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
