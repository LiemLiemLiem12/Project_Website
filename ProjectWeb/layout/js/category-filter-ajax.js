// Lưu vị trí cuộn trước khi tải lại trang
window.addEventListener("beforeunload", function () {
  // Lưu vị trí cuộn hiện tại vào sessionStorage
  sessionStorage.setItem(
    "scrollPosition",
    window.pageYOffset || document.documentElement.scrollTop
  );
});

// Khôi phục vị trí cuộn sau khi trang được tải
document.addEventListener("DOMContentLoaded", function () {
  // Kiểm tra nếu có vị trí cuộn được lưu
  if (sessionStorage.getItem("scrollPosition")) {
    // Đợi DOM được tải hoàn toàn
    setTimeout(function () {
      // Khôi phục vị trí cuộn
      window.scrollTo(0, sessionStorage.getItem("scrollPosition"));
    }, 100);
  }
});
document.addEventListener("DOMContentLoaded", function () {
  // Khởi tạo các thành phần lọc
  initFilters();

  // Khởi tạo chức năng xem nhanh và thêm vào giỏ hàng
  initProductActions();
});

// Khởi tạo các bộ lọc và sắp xếp
function initFilters() {
  // Lấy ID danh mục từ URL
  const categoryId = getCurrentCategoryId();

  // Lọc theo giá
  const priceRange = document.getElementById("price-range");
  const priceValue = document.getElementById("price-value");

  if (priceRange && priceValue) {
    // Cập nhật hiển thị giá khi người dùng kéo thanh trượt
    priceRange.addEventListener("input", function () {
      const formattedPrice = Number(this.value).toLocaleString("vi-VN") + "₫";
      priceValue.textContent = formattedPrice;
    });

    // Áp dụng lọc khi người dùng thả thanh trượt
    priceRange.addEventListener("change", function () {
      applyFiltersWithAjax();
    });
  }

  // Lọc theo kích cỡ
  const sizeCheckboxes = document.querySelectorAll(
    '.filter-option input[type="checkbox"]'
  );
  sizeCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      applyFiltersWithAjax();
    });
  });

  // Sắp xếp sản phẩm
  const sortSelect = document.getElementById("sort-by");
  if (sortSelect) {
    sortSelect.addEventListener("change", function () {
      applyFiltersWithAjax();
    });
  }

  // Đặt lại bộ lọc
  const resetButton = document.querySelector(".reset-filters");
  if (resetButton) {
    resetButton.addEventListener("click", function () {
      // Reset các bộ lọc về mặc định
      if (priceRange) priceRange.value = 1000000;
      if (priceValue) priceValue.textContent = "1,000,000₫";

      sizeCheckboxes.forEach((checkbox) => {
        checkbox.checked = false;
      });

      if (sortSelect) sortSelect.value = "newest";

      // Áp dụng bộ lọc mặc định
      applyFiltersWithAjax();
    });
  }
}

// Áp dụng bộ lọc bằng AJAX
function applyFiltersWithAjax() {
  // Hiển thị loading
  showLoading(true);

  // Lấy tham số lọc
  const params = getFilterParams();

  // Tạo URL API
  const apiUrl =
    "index.php?controller=category&action=filterProducts&" + params;

  // Lưu vị trí cuộn hiện tại
  const scrollPosition =
    window.pageYOffset || document.documentElement.scrollTop;

  // Gọi API lọc sản phẩm
  fetch(apiUrl, {
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        
        // Cập nhật danh sách sản phẩm
        document.querySelector(".product-grid").innerHTML = data.html;

        // Cập nhật số lượng sản phẩm
        const countDisplay = document.getElementById("product-count-display");
        if (countDisplay) {
          countDisplay.innerHTML = `<span>Hiển thị ${data.count} sản phẩm</span>`;
        }

        // Cập nhật URL để có thể làm mới trang
        updateBrowserUrl(params);

        // Khởi tạo lại sự kiện cho các sản phẩm vừa được tải
        initProductActions();

        // Hiển thị thông báo thành công
        showNotification("Đã cập nhật sản phẩm", "success");
      } else {
        showNotification(data.error || "Có lỗi xảy ra", "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showNotification("Có lỗi xảy ra khi tải sản phẩm", "error");
    })
    .finally(() => {
      // Ẩn loading
      showLoading(false);

      // Khôi phục vị trí cuộn
      window.scrollTo(0, scrollPosition);
    });
}

// Xây dựng chuỗi tham số lọc
function getFilterParams() {
  const categoryId = getCurrentCategoryId();
  let params = `id=${categoryId}`;

  // Thêm tham số sắp xếp
  const sortSelect = document.getElementById("sort-by");
  if (sortSelect) {
    params += `&sort=${sortSelect.value}`;
  }

  // Thêm khoảng giá
  const priceRange = document.getElementById("price-range");
  if (priceRange) {
    params += `&price=${priceRange.value}`;
  }

  // Thêm kích cỡ đã chọn
  const sizeCheckboxes = document.querySelectorAll(
    '.filter-option input[type="checkbox"]:checked'
  );
  sizeCheckboxes.forEach((checkbox) => {
    const size = checkbox.id.replace("size-", "").toUpperCase();
    params += `&size[]=${size}`;
  });

  return params;
}

// Cập nhật URL trong trình duyệt mà không làm mới trang
function updateBrowserUrl(params) {
  const baseUrl = "index.php?controller=category&action=show";
  const newUrl = `${baseUrl}&${params}`;

  // Cập nhật URL mà không làm mới trang
  window.history.pushState({ path: newUrl }, "", newUrl);
}

// Khởi tạo các chức năng sản phẩm (xem nhanh, thêm vào giỏ)
function initProductActions() {
  // Xem nhanh sản phẩm
  const quickViewButtons = document.querySelectorAll(".btn-quickview");
  quickViewButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();

      const productId = this.getAttribute("data-product-id");
      window.location.href = `index.php?controller=product&action=show&id=${productId}`;
    });
  });

  // Thêm vào giỏ hàng
  const addToCartButtons = document.querySelectorAll(".btn-add-cart");
  addToCartButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();

      const productId = this.getAttribute("data-product-id");

      fetch(`index.php?controller=cart&action=add&id=${productId}&qty=1&size=M`)
        .then((response) => response.text())
        .then((data) => {
          showNotification("Đã thêm sản phẩm vào giỏ hàng!", "success");

          // Kích hoạt sự kiện cập nhật giỏ hàng
          document.dispatchEvent(new CustomEvent("cartUpdated"));

          // Cập nhật số lượng trong giỏ hàng
          return fetch("index.php?controller=cart&action=getCount", {
            headers: {
              "X-Requested-With": "XMLHttpRequest",
            },
          });
        })
        .then((response) => response.json())
        .then((data) => {
          // Cập nhật số lượng trên header
          const cartCount = document.getElementById("item-count");
          if (cartCount && data.count) {
            cartCount.textContent = data.count;
            sessionStorage.setItem("cartCount", data.count);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          showNotification("Có lỗi xảy ra khi thêm vào giỏ hàng!", "error");
        });
    });
  });
}

// Hiển thị/ẩn loading
function showLoading(show) {
  const loadingIndicator = document.getElementById("loading-indicator");
  if (loadingIndicator) {
    loadingIndicator.style.display = show ? "block" : "none";
  }
}

// Lấy ID danh mục hiện tại từ URL
function getCurrentCategoryId() {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get("id");
}

// Hiển thị thông báo
function showNotification(message, type = "success") {
  // Tạo phần tử thông báo
  const notification = document.createElement("div");
  notification.className = `notification notification-${type}`;
  notification.innerHTML = `
        <div class="notification-icon">
            <i class="fas fa-${
              type === "success"
                ? "check-circle"
                : type === "info"
                ? "info-circle"
                : "exclamation-circle"
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

  // Thêm class hiển thị sau một khoảng thời gian ngắn
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
