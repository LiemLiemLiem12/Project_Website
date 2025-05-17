document.addEventListener("DOMContentLoaded", function () {
  // Lưu giá đơn vị cho mỗi sản phẩm
  initializeProductPrices();
  // Initialize cart functionality
  initCart();
  fetchCartData();
  // Cập nhật giá trị ban đầu
  updateCartTotal();
  updateCartCount();
  checkEmptyCart(); // Kiểm tra giỏ hàng trống
  // Initialize voucher buttons
  initVoucherButtons();
  const cartCount = sessionStorage.getItem("cartCount");
  if (cartCount) {
    const countElement = document.getElementById("item-count");
    if (countElement) {
      countElement.textContent = cartCount;
    }
  }
  const addToOrderBtn = document.getElementById("addToOrderBtn");
  if (addToOrderBtn) {
    addToOrderBtn.addEventListener("click", function () {
      const productId = this.getAttribute("productId");
      const quantity = parseInt(quantityInput.value);
      const productSize = this.getAttribute("productSize");
      // Kiểm tra nếu người dùng chưa chọn size

      // Gửi fetch request đến backend
      fetch(
        `index.php?controller=cart&action=update&id=${productId}&qty=${quantity}&size=${productSize}`
      );
    });
  }
});
/**
 * Update cart item quantity via AJAX
 * @param {number} productId - Product ID
 * @param {string} size - Product size
 * @param {number} quantity - New quantity
 */
function updateCartItemAjax(productId, size, quantity) {
  const data = new FormData();
  data.append("product_id", productId);
  data.append("size", size);
  data.append("quantity", quantity);

  fetch("index.php?controller=cart&action=updateItem", {
    method: "POST",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
    body: data,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Cập nhật số lượng trong sessionStorage
        sessionStorage.setItem("cartCount", data.count);

        // Cập nhật hiển thị số lượng
        const countElement = document.getElementById("item-count");
        if (countElement) {
          countElement.textContent = data.count;
        }

        // Kích hoạt sự kiện tùy chỉnh
        document.dispatchEvent(new CustomEvent("cartUpdated"));
      }
    })
    .catch((error) => {
      console.error("Error updating cart:", error);
    });
}

/**
 * Add to cart via AJAX
 * @param {number} productId - Product ID
 * @param {string} size - Product size
 * @param {number} quantity - Quantity to add
 */
function addToCartAjax(productId, size, quantity) {
  const data = new FormData();
  data.append("product_id", productId);
  data.append("size", size);
  data.append("quantity", quantity);

  fetch("index.php?controller=cart&action=add", {
    method: "POST",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
    body: data,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Cập nhật số lượng trong sessionStorage
        sessionStorage.setItem("cartCount", data.count);

        // Cập nhật hiển thị số lượng
        const countElement = document.getElementById("item-count");
        if (countElement) {
          countElement.textContent = data.count;
        }

        // Hiển thị thông báo
        showNotification(data.message, "success");

        // Kích hoạt sự kiện tùy chỉnh
        document.dispatchEvent(new CustomEvent("cartUpdated"));
      } else {
        showNotification(data.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error adding to cart:", error);
      showNotification("Có lỗi xảy ra khi thêm vào giỏ hàng", "error");
    });
}

/**
 * Hiển thị thông báo
 * @param {string} message - Nội dung thông báo
 * @param {string} type - Loại thông báo (success/error)
 */
function showNotification(message, type) {
  const notificationContainer = document.querySelector(
    ".notification-container"
  );
  if (!notificationContainer) return;

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

  notificationContainer.appendChild(notification);

  // Hiển thị thông báo
  setTimeout(() => {
    notification.classList.add("show");
  }, 10);

  // Ẩn và xóa thông báo sau 3 giây
  setTimeout(() => {
    notification.classList.remove("show");
    setTimeout(() => {
      notification.remove();
    }, 300);
  }, 3000);
}

// Khi trang được tải, cố gắng sử dụng dữ liệu từ sessionStora
// Lưu giá đơn vị cho mỗi sản phẩm
function initializeProductPrices() {
  const cartItems = document.querySelectorAll(".cart-item");

  cartItems.forEach((item) => {
    // Lấy giá từ hiển thị
    const priceElement = item.querySelector(".cart-item-price");
    const originalPriceElement = item.querySelector(
      ".cart-item-original-price"
    );

    if (priceElement) {
      let price;
      // Nếu phần tử có con, lấy text từ phần tử con đầu tiên
      if (priceElement.querySelector("div")) {
        price = parseInt(
          priceElement.querySelector("div").textContent.replace(/\D/g, "")
        );
      } else {
        price = parseInt(priceElement.textContent.replace(/\D/g, ""));
      }

      // Lưu giá đơn vị
      item.dataset.unitPrice = price;

      // Lưu giá gốc nếu có
      if (originalPriceElement) {
        const originalPrice = parseInt(
          originalPriceElement.textContent.replace(/\D/g, "")
        );
        item.dataset.originalUnitPrice = originalPrice;
      }

      console.log(
        `Item ${item.dataset.id}: Unit price initialized to ${price}`
      );
    }
  });
}

function initCart() {
  // Get all cart items
  const cartItems = document.querySelectorAll(".cart-item");

  // Add event listeners for each cart item
  cartItems.forEach((item) => {
    // const decreaseBtn = item.querySelector(".decrease-btn");
    // const increaseBtn = item.querySelector(".increase-btn");
    const quantityInput = item.querySelector(".quantity-input");
    const removeBtn = item.querySelector(".cart-item-remove");

    // Cập nhật nút giảm số lượng
    document.querySelectorAll(".decrease-btn").forEach((button) => {
      button.addEventListener("click", function () {
        const productId = this.getAttribute("productId");
        const size = this.getAttribute("productSize");
        const quantityInput =
          this.closest(".quantity-control").querySelector(".quantity-input");
        let value = parseInt(quantityInput.value);

        if (value > 1) {
          quantityInput.value = value - 1;
          // Cập nhật giỏ hàng qua AJAX
          updateCartItemAjax(productId, size, value - 1);
        }
      });
    });

    // Cập nhật nút tăng số lượng
    document.querySelectorAll(".increase-btn").forEach((button) => {
      button.addEventListener("click", function () {
        const productId = this.getAttribute("productId");
        const size = this.getAttribute("productSize");
        const quantityInput =
          this.closest(".quantity-control").querySelector(".quantity-input");
        let value = parseInt(quantityInput.value);

        quantityInput.value = value + 1;
        // Cập nhật giỏ hàng qua AJAX
        updateCartItemAjax(productId, size, value + 1);
      });
    });

    // Xử lý nút xóa sản phẩm
    document
      .querySelectorAll(".cart-item-remove, .btn-dark.btn-sm")
      .forEach((button) => {
        button.addEventListener("click", function (e) {
          e.preventDefault();

          const href = this.getAttribute("href");

          // Gửi yêu cầu xóa sản phẩm
          fetch(href, {
            method: "GET",
            headers: {
              "X-Requested-With": "XMLHttpRequest",
            },
          })
            .then((response) => {
              // Tải lại trang để cập nhật giỏ hàng
              window.location.reload();
            })
            .catch((error) => {
              console.error("Error removing item:", error);
            });
        });
      });
    // Event for decreasing quantity
    // decreaseBtn.addEventListener("click", function () {
    //   let value = parseInt(quantityInput.value);
    //   if (value > 1) {
    //     quantityInput.value = value - 1;
    //     updateItemPrice(item);
    //     updateCartTotal();
    //     updateCartCount();
    //   }
    // });

    // // Event for increasing quantity
    // increaseBtn.addEventListener("click", function () {
    //   let value = parseInt(quantityInput.value);
    //   if (value < 100) {
    //     quantityInput.value = value + 1;
    //     updateItemPrice(item);
    //     updateCartTotal();
    //     updateCartCount();
    //   }
    // });

    // Event for manual input
    quantityInput.addEventListener("change", function () {
      let value = parseInt(quantityInput.value);
      if (isNaN(value) || value < 1) {
        quantityInput.value = 1;
      } else if (value > 100) {
        quantityInput.value = 100;
      }
      updateItemPrice(item);
      updateCartTotal();
      updateCartCount();
    });

    // Event for removing item
    // if (removeBtn) {
    //   removeBtn.addEventListener("click", function () {
    //     // Hiệu ứng xóa item
    //     item.style.opacity = "0";
    //     item.style.height = "0";
    //     item.style.transition = "opacity 0.3s, height 0.5s";

    //     setTimeout(() => {
    //       item.remove();
    //       updateCartTotal();
    //       updateCartCount();
    //       checkEmptyCart(); // Kiểm tra giỏ hàng trống sau khi xóa
    //     }, 500);
    //   });
    // }
  });

  // Apply coupon button
  const applyBtn = document.getElementById("apply-coupon");
  if (applyBtn) {
    applyBtn.addEventListener("click", function () {
      const couponInput = document.getElementById("coupon-code");
      if (couponInput && couponInput.value.trim() !== "") {
        // Validate coupon code
        const couponCode = couponInput.value.trim().toUpperCase();

        // Giả lập kiểm tra mã giảm giá
        applyVoucherCode(couponCode);
      } else {
        showCouponMessage("Vui lòng nhập mã giảm giá!", "error");
      }
    });
  }

  // Checkout button
  const checkoutBtn = document.querySelector(".checkout-btn");
  if (checkoutBtn) {
    checkoutBtn.addEventListener("click", function () {
      // Kiểm tra giỏ hàng trước khi thanh toán
      const cartItems = document.querySelectorAll(".cart-item");
      if (cartItems.length === 0) {
        alert(
          "Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm trước khi thanh toán!"
        );
        return;
      }

      // Animation hiệu ứng khi click nút thanh toán
      checkoutBtn.innerHTML =
        '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
      checkoutBtn.disabled = true;

      setTimeout(() => {
        window.location.href = "index.php?controller=order";
      }, 1000);
    });
  }

  // Continue shopping button
  const continueShoppingBtn = document.querySelector(".continue-shopping");
  if (continueShoppingBtn) {
    continueShoppingBtn.addEventListener("click", function (e) {
      e.preventDefault();
      window.location.href = "index.php?controller=home";
    });
  }
}

// Initialize voucher buttons
function initVoucherButtons() {
  const voucherButtons = document.querySelectorAll(".apply-voucher-btn");

  voucherButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const voucherItem = this.closest(".voucher-item");
      const voucherCode = voucherItem.dataset.code;

      // Apply the voucher code
      applyVoucherCode(voucherCode);

      // Update button state
      this.textContent = "Đã dùng";
      this.disabled = true;
      this.classList.remove("btn-outline-dark");
      this.classList.add("btn-success");

      // Update input field to show the applied code
      const couponInput = document.getElementById("coupon-code");
      if (couponInput) {
        couponInput.value = voucherCode;
      }
    });
  });
}

// Apply voucher code and update cart
function applyVoucherCode(code) {
  console.log(`Applying voucher code: ${code}`);

  // Match the code to the right discount type
  switch (code) {
    case "SALE10":
      showCouponMessage("Mã giảm giá 10% đã được áp dụng!", "success");
      updateCartTotal(0.1); // Giảm 10%
      updateCartNotification(
        `Đơn hàng của bạn đã được giảm 10% nhờ nhập mã <strong>${code}</strong>`
      );
      break;

    case "APR30":
    case "SALE30K":
      const subtotal = getSubtotal();
      if (subtotal >= 599000) {
        showCouponMessage("Mã giảm giá 30K đã được áp dụng!", "success");
        updateCartTotal(0, false, 30000); // Giảm 30K
        updateCartNotification(
          `Đơn hàng của bạn đã được giảm 30K nhờ nhập mã <strong>${code}</strong>`
        );
      } else {
        showCouponMessage(
          `Mã giảm giá chỉ áp dụng cho đơn hàng từ 599K!`,
          "error"
        );
      }
      break;

    case "APR70":
    case "SALE70K":
      const subtotal2 = getSubtotal();
      if (subtotal2 >= 899000) {
        showCouponMessage("Mã giảm giá 70K đã được áp dụng!", "success");
        updateCartTotal(0, false, 70000); // Giảm 70K
        updateCartNotification(
          `Đơn hàng của bạn đã được giảm 70K nhờ nhập mã <strong>${code}</strong>`
        );
      } else {
        showCouponMessage(
          `Mã giảm giá chỉ áp dụng cho đơn hàng từ 899K!`,
          "error"
        );
      }
      break;

    case "APR100":
    case "SALE100K":
      const subtotal3 = getSubtotal();
      if (subtotal3 >= 1199000) {
        showCouponMessage("Mã giảm giá 100K đã được áp dụng!", "success");
        updateCartTotal(0, false, 100000); // Giảm 100K
        updateCartNotification(
          `Đơn hàng của bạn đã được giảm 100K nhờ nhập mã <strong>${code}</strong>`
        );
      } else {
        showCouponMessage(
          `Mã giảm giá chỉ áp dụng cho đơn hàng từ 1,199K!`,
          "error"
        );
      }
      break;

    case "FREE":
    case "FREESHIP":
      showCouponMessage("Mã miễn phí vận chuyển đã được áp dụng!", "success");
      updateCartTotal(0, true); // Miễn phí vận chuyển
      updateCartNotification(
        `Đơn hàng của bạn đã được miễn phí vận chuyển nhờ nhập mã <strong>${code}</strong>`
      );
      break;

    case "SALE50K":
      showCouponMessage("Mã giảm giá 50K đã được áp dụng!", "success");
      updateCartTotal(0, false, 50000); // Giảm 50K
      updateCartNotification(
        `Đơn hàng của bạn đã được giảm 50K nhờ nhập mã <strong>${code}</strong>`
      );
      break;

    default:
      showCouponMessage("Mã giảm giá không hợp lệ!", "error");
      break;
  }
}

// Update cart notification
function updateCartNotification(message) {
  const notification = document.querySelector(".cart-notification");
  if (notification) {
    notification.innerHTML = `<i class="fas fa-tags me-2"></i> ${message}`;
  }
}

// Get subtotal for validation
function getSubtotal() {
  let subtotal = 0;
  const cartItems = document.querySelectorAll(".cart-item");

  cartItems.forEach((item) => {
    const quantity = parseInt(item.querySelector(".quantity-input").value);
    const unitPrice = parseInt(item.dataset.unitPrice);
    subtotal += unitPrice * quantity;
  });

  return subtotal;
}

// Cập nhật giá tiền cho một item dựa trên số lượng
function updateItemPrice(item) {
  const quantity = parseInt(item.querySelector(".quantity-input").value);
  const priceElement = item.querySelector(".cart-item-price");
  const originalPriceElement = item.querySelector(".cart-item-original-price");

  // Lấy giá đơn vị từ data attribute
  const unitPrice = parseInt(item.dataset.unitPrice);

  // Tính tổng giá dựa trên số lượng
  const totalPrice = unitPrice * quantity;

  // Cập nhật hiển thị giá
  if (priceElement.querySelector("div")) {
    // Nếu có div con (trường hợp có giá gốc và giá khuyến mãi)
    priceElement.querySelector("div").textContent = formatCurrency(totalPrice);
  } else {
    // Trường hợp chỉ có giá thường
    priceElement.textContent = formatCurrency(totalPrice);
  }

  // Cập nhật giá gốc nếu có
  if (originalPriceElement && item.dataset.originalUnitPrice) {
    const originalUnitPrice = parseInt(item.dataset.originalUnitPrice);
    const totalOriginalPrice = originalUnitPrice * quantity;
    originalPriceElement.textContent = formatCurrency(totalOriginalPrice);
  }

  console.log(
    `Item ${item.dataset.id}: Updated price - ${quantity} x ${unitPrice} = ${totalPrice}`
  );
}

function updateCartTotal(
  discountPercent = 0,
  freeShipping = false,
  fixedDiscount = 0
) {
  // Calculate subtotal
  let subtotal = 0;
  const cartItems = document.querySelectorAll(".cart-item");

  cartItems.forEach((item) => {
    const quantity = parseInt(item.querySelector(".quantity-input").value);
    const unitPrice = parseInt(item.dataset.unitPrice);
    subtotal += unitPrice * quantity;
  });

  // Update subtotal
  const subtotalElement = document.getElementById("cart-subtotal");
  if (subtotalElement) {
    subtotalElement.textContent = formatCurrency(subtotal);
  }

  // Calculate discount
  let discount = 0;
  if (discountPercent > 0) {
    discount = Math.round(subtotal * discountPercent);
  } else if (fixedDiscount > 0) {
    discount = fixedDiscount;
  }

  // Update discount
  const discountElement = document.getElementById("cart-discount");
  if (discountElement) {
    discountElement.textContent = "-" + formatCurrency(discount);

    // Show or hide discount row
    const discountRow = document.querySelector(".discount-row");
    if (discountRow) {
      discountRow.style.display = discount > 0 ? "flex" : "none";
    }
  }

  // Calculate shipping
  let shipping = 0;
  const shippingElement = document.getElementById("cart-shipping");

  // Miễn phí vận chuyển nếu subtotal > 200,000đ hoặc có mã miễn phí
  if (subtotal >= 200000 || freeShipping) {
    if (shippingElement) {
      shippingElement.textContent = "Miễn phí";
    }
  } else {
    shipping = 30000; // Phí vận chuyển mặc định
    if (shippingElement) {
      shippingElement.textContent = formatCurrency(shipping);
    }
  }

  // Update shipping progress bar
  updateShippingProgress(subtotal);

  // Calculate total
  const total = subtotal - discount + shipping;

  // Update total
  const totalElement = document.getElementById("cart-total");
  if (totalElement) {
    totalElement.textContent = formatCurrency(total);
  }

  console.log(
    `Cart updated: Subtotal=${subtotal}, Discount=${discount}, Shipping=${shipping}, Total=${total}`
  );

  // Check if we need to disable any vouchers based on the subtotal
  updateVoucherAvailability(subtotal);
}

// Update voucher availability based on subtotal
function updateVoucherAvailability(subtotal) {
  const voucherItems = document.querySelectorAll(".voucher-item");

  voucherItems.forEach((item) => {
    const voucherCode = item.dataset.code;
    const button = item.querySelector(".apply-voucher-btn");

    // Skip if button is already used
    if (button.disabled) return;

    // Check if voucher should be disabled based on subtotal
    let shouldDisable = false;

    switch (voucherCode) {
      case "APR30":
        shouldDisable = subtotal < 599000;
        break;
      case "APR70":
        shouldDisable = subtotal < 899000;
        break;
      case "APR100":
        shouldDisable = subtotal < 1199000;
        break;
      case "FREE":
        shouldDisable = subtotal < 250000;
        break;
    }

    // Update button state
    if (shouldDisable) {
      button.classList.add("disabled");
      button.title = "Đơn hàng chưa đủ điều kiện";
    } else {
      button.classList.remove("disabled");
      button.title = "";
    }
  });
}

function updateCartCount() {
  const cartItems = document.querySelectorAll(".cart-item");
  const countElement = document.getElementById("item-count");

  // Tính tổng số lượng sản phẩm
  let totalItems = 0;
  cartItems.forEach((item) => {
    const quantity = parseInt(item.querySelector(".quantity-input").value);
    totalItems += quantity;
  });

  if (countElement) {
    countElement.textContent = totalItems + " Sản phẩm";
  }
}

function updateShippingProgress(subtotal) {
  const progressBar = document.querySelector(".progress-bar");
  const progressText = document.querySelector(".text-end.text-muted");

  if (progressBar && progressText) {
    const freeShippingThreshold = 200000;
    const percentage = Math.min(100, (subtotal / freeShippingThreshold) * 100);

    progressBar.style.width = percentage + "%";

    if (subtotal >= freeShippingThreshold) {
      progressText.textContent = "Đủ điều kiện ✓";
    } else {
      const remaining = freeShippingThreshold - subtotal;
      progressText.textContent =
        "Còn " + formatCurrency(remaining) + " để được miễn phí vận chuyển";
    }
  }
}

function checkEmptyCart() {
  const cartItems = document.querySelectorAll(".cart-item");
  const emptyCart = document.getElementById("empty-cart");
  const cartItemsContainer = document.getElementById("cart-items");

  if (cartItems.length === 0 && emptyCart && cartItemsContainer) {
    emptyCart.classList.remove("d-none");
    cartItemsContainer.classList.add("d-none");
  } else if (emptyCart && cartItemsContainer) {
    emptyCart.classList.add("d-none");
    cartItemsContainer.classList.remove("d-none");
  }
}

function showCouponMessage(message, type) {
  // Tạo hoặc lấy element thông báo
  let messageElement = document.querySelector(".coupon-message");

  if (!messageElement) {
    messageElement = document.createElement("div");
    messageElement.className = "coupon-message";

    const promotionInput = document.querySelector(".promotion-input");
    promotionInput.insertAdjacentElement("afterend", messageElement);
  }

  // Xóa các class cũ
  messageElement.classList.remove("text-success", "text-danger");

  // Thêm class và nội dung mới
  if (type === "success") {
    messageElement.classList.add("text-success");
    messageElement.innerHTML = '<i class="fas fa-check-circle"></i> ' + message;
  } else {
    messageElement.classList.add("text-danger");
    messageElement.innerHTML =
      '<i class="fas fa-exclamation-circle"></i> ' + message;
  }

  // Hiển thị thông báo
  messageElement.style.display = "block";

  // Ẩn thông báo sau 3 giây
  setTimeout(() => {
    messageElement.style.opacity = "0";
    setTimeout(() => {
      messageElement.style.display = "none";
      messageElement.style.opacity = "1";
    }, 300);
  }, 3000);
}

function formatCurrency(amount) {
  return (
    new Intl.NumberFormat("vi-VN", { style: "decimal" }).format(amount) + "₫"
  );
}
function updateCartCount() {
  const cartItems = document.querySelectorAll(".cart-item");
  const countElement = document.getElementById("item-count");

  // Tính tổng số lượng sản phẩm
  let totalItems = 0;
  cartItems.forEach((item) => {
    const quantity = parseInt(item.querySelector(".quantity-input").value);
    totalItems += quantity;
  });

  if (countElement) {
    countElement.textContent = totalItems + " Sản phẩm";
  }

  // Lưu số lượng vào sessionStorage để các trang khác có thể sử dụng
  sessionStorage.setItem("cartCount", totalItems);

  // Cập nhật số lượng trên biểu tượng giỏ hàng trên header
  const headerCountElement = document.getElementById("item-count");
  if (headerCountElement) {
    // Trên header chỉ hiển thị số, không có "Sản phẩm"
    headerCountElement.textContent = totalItems;
  }

  // Kích hoạt sự kiện cập nhật giỏ hàng để Header.js có thể phản ứng
  document.dispatchEvent(new CustomEvent("cartUpdated"));
}

// Gọi hàm fetchCartData khi trang tải để cập nhật số lượng
function fetchCartData() {
  // Sử dụng Fetch API để lấy dữ liệu giỏ hàng từ server
  fetch("index.php?controller=cart&action=getCount", {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      // Lưu số lượng sản phẩm vào sessionStorage
      sessionStorage.setItem("cartCount", data.count);

      // Cập nhật hiển thị số lượng
      const headerCountElement = document.getElementById("item-count");
      if (headerCountElement) {
        headerCountElement.textContent = data.count;
      }
    })
    .catch((error) => {
      console.error("Error fetching cart data:", error);
    });
}
