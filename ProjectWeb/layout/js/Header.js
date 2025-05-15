// ProjectWeb/layout/js/Header.js
document.addEventListener("DOMContentLoaded", function () {
  const topBanner = document.querySelector(".top-banner");
  const mainHeader = document.querySelector("header"); // Thẻ <header>
  const mobileSearch = document.querySelector(".mobile-search"); // class .mobile-search
  const mainNav = document.querySelector(".main-nav"); // class .main-nav

  // Cập nhật số lượng sản phẩm trong giỏ hàng khi trang được tải
  updateCartCount();

  function updateCartCount() {
    // Nếu đang ở trang giỏ hàng, sử dụng các phần tử .cart-item có sẵn
    const cartItems = document.querySelectorAll(".cart-item");
    const countElement = document.getElementById("item-count");

    if (cartItems.length > 0) {
      // Tính tổng số lượng sản phẩm từ các phần tử hiện có
      let totalItems = 0;
      cartItems.forEach((item) => {
        const quantityInput = item.querySelector(".quantity-input");
        if (quantityInput) {
          const quantity = parseInt(quantityInput.value);
          totalItems += quantity;
        }
      });

      if (countElement) {
        // Chỉ hiển thị số, không hiển thị "Sản phẩm" trong header
        countElement.textContent = totalItems;
      }
    } else {
      // Nếu không có phần tử .cart-item trên trang, lấy giá trị từ sessionStorage
      const cartCount = sessionStorage.getItem("cartCount") || "0";
      if (countElement) {
        countElement.textContent = cartCount;
      }
    }
  }

  // Lưu số lượng sản phẩm vào sessionStorage khi có thay đổi
  function saveCartCount() {
    const countElement = document.getElementById("item-count");
    if (countElement) {
      sessionStorage.setItem("cartCount", countElement.textContent);
    }
  }

  // Đăng ký sự kiện cho các nút thay đổi số lượng trong giỏ hàng
  document.addEventListener("click", function (event) {
    if (
      event.target.classList.contains("decrease-btn") ||
      event.target.classList.contains("increase-btn")
    ) {
      // Đợi một chút để số lượng được cập nhật
      setTimeout(updateCartCount, 100);
      setTimeout(saveCartCount, 100);
    }
  });

  // Lắng nghe sự kiện thay đổi số lượng sản phẩm
  document.addEventListener("change", function (event) {
    if (event.target.classList.contains("quantity-input")) {
      updateCartCount();
      saveCartCount();
    }
  });

  // Lắng nghe sự kiện xóa sản phẩm
  const removeButtons = document.querySelectorAll(".cart-item-remove");
  removeButtons.forEach((button) => {
    button.addEventListener("click", function () {
      // Đợi một chút để phần tử được xóa
      setTimeout(updateCartCount, 100);
      setTimeout(saveCartCount, 100);
    });
  });

  // Tạo một sự kiện tùy chỉnh để cập nhật số lượng sản phẩm
  document.addEventListener("cartUpdated", function () {
    updateCartCount();
    saveCartCount();
  });

  function adjustFixedElementPositions() {
    let currentTopOffset = 0;
    let totalFixedHeightForBodyPadding = 0;

    // 1. Top Banner
    if (topBanner && getComputedStyle(topBanner).display !== "none") {
      topBanner.style.top = "0px"; // Luôn ở trên cùng
      currentTopOffset += topBanner.offsetHeight;
      totalFixedHeightForBodyPadding += topBanner.offsetHeight;
    }

    // 2. Main Header
    if (mainHeader && getComputedStyle(mainHeader).display !== "none") {
      mainHeader.style.top = currentTopOffset + "px";
      currentTopOffset += mainHeader.offsetHeight;
      totalFixedHeightForBodyPadding += mainHeader.offsetHeight;
    }

    // 3. Mobile Search (chỉ hiển thị trên mobile, class d-md-none)
    const isMobileSearchVisible =
      mobileSearch &&
      window.innerWidth < 768 &&
      getComputedStyle(mobileSearch).display !== "none";
    if (isMobileSearchVisible) {
      mobileSearch.style.top = currentTopOffset + "px";
      currentTopOffset += mobileSearch.offsetHeight;
      totalFixedHeightForBodyPadding += mobileSearch.offsetHeight;
    } else if (mobileSearch) {
      // Ẩn nếu không dùng để không ảnh hưởng layout
      // mobileSearch.style.top = '-1000px';
    }

    // 4. Main Navigation
    if (mainNav && getComputedStyle(mainNav).display !== "none") {
      mainNav.style.top = currentTopOffset + "px";
      totalFixedHeightForBodyPadding += mainNav.offsetHeight;
    }

    document.body.style.paddingTop = totalFixedHeightForBodyPadding + "px";
  }

  // Gọi hàm khi tải trang và khi thay đổi kích thước cửa sổ
  adjustFixedElementPositions();
  window.addEventListener("resize", adjustFixedElementPositions);
  // Có thể gọi lại sau một khoảng trễ nhỏ để đảm bảo các element đã render xong chiều cao
  setTimeout(adjustFixedElementPositions, 250);

  // Đóng mobile menu khi một link được click (nếu dùng Bootstrap collapse)
  const mainNavbarCollapse = document.getElementById("navbarMain");
  if (mainNavbarCollapse) {
    mainNavbarCollapse.addEventListener("click", function (event) {
      if (
        event.target.classList.contains("nav-link") ||
        event.target.closest(".nav-link")
      ) {
        let bsCollapse = bootstrap.Collapse.getInstance(mainNavbarCollapse);
        if (bsCollapse && mainNavbarCollapse.classList.contains("show")) {
          bsCollapse.hide();
        }
      }
    });
  }

  // Xử lý dropdowns (Bootstrap đã tự xử lý qua data attributes, phần này có thể không cần)
  // Ví dụ: đóng dropdown khác khi mở một dropdown
  const dropdownToggles = document.querySelectorAll(
    ".header-action .dropdown-toggle"
  );
  dropdownToggles.forEach((toggle) => {
    toggle.addEventListener("show.bs.dropdown", function () {
      // Đóng tất cả các dropdown khác trong .header-action
      document
        .querySelectorAll(".header-action .dropdown-menu.show")
        .forEach((openDropdown) => {
          if (openDropdown !== this.nextElementSibling) {
            let instance = bootstrap.Dropdown.getInstance(
              openDropdown.previousElementSibling
            );
            if (instance) instance.hide();
          }
        });
    });
  });
});
