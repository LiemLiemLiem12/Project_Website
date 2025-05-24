window.addEventListener("beforeunload", function () {
  sessionStorage.setItem(
    "scrollPosition",
    window.pageYOffset || document.documentElement.scrollTop
  );
});

document.addEventListener("DOMContentLoaded", function () {
  if (sessionStorage.getItem("scrollPosition")) {
    setTimeout(function () {
      window.scrollTo(0, sessionStorage.getItem("scrollPosition"));
    }, 100);
  }
  initFilters();
  initProductActions();
  initTagSearch();
  initPagination();
});

function initFilters() {
  const categoryId = getCurrentCategoryId();
  const priceRange = document.getElementById("price-range");
  const priceValue = document.getElementById("price-value");

  if (priceRange && priceValue) {
    priceRange.addEventListener("input", function () {
      const formattedPrice = Number(this.value).toLocaleString("vi-VN") + "₫";
      priceValue.textContent = formattedPrice;
    });
    priceRange.addEventListener("change", function () {
      applyFiltersWithAjax(1);
    });
  }

  const sizeCheckboxes = document.querySelectorAll(
    '.filter-option input[type="checkbox"]'
  );
  sizeCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      applyFiltersWithAjax(1);
    });
  });

  const sortSelect = document.getElementById("sort-by");
  if (sortSelect) {
    sortSelect.addEventListener("change", function () {
      applyFiltersWithAjax(1);
    });
  }

  const resetButton = document.querySelector(".reset-filters");
  if (resetButton) {
    resetButton.addEventListener("click", function () {
      if (priceRange) priceRange.value = 1000000;
      if (priceValue) priceValue.textContent = "1,000,000₫";
      sizeCheckboxes.forEach((checkbox) => {
        checkbox.checked = false;
      });
      if (sortSelect) sortSelect.value = "newest";
      applyFiltersWithAjax(1);
    });
  }
}

function initTagSearch() {
  const relatedTags = document.querySelectorAll(".related-tags a");
  relatedTags.forEach((tagLink) => {
    tagLink.addEventListener("click", function (e) {
      e.preventDefault();
      const tagUrl = this.getAttribute("href");
      window.location.href = tagUrl;
    });
  });
}

function initPagination() {
  const paginationLinks = document.querySelectorAll(".pagination .page-link");
  paginationLinks.forEach((link) => {
    link.removeEventListener("click", handlePaginationClick);
    link.addEventListener("click", handlePaginationClick);
  });
}

function handlePaginationClick(e) {
  e.preventDefault();
  const page = this.getAttribute("data-page");
  if (page && !this.parentElement.classList.contains("disabled")) {
    applyFiltersWithAjax(page);
  }
}

function applyFiltersWithAjax(page = 1) {
  showLoading(true);
  const params = getFilterParams(page);
  let apiUrl =
    typeof isTagSearch !== "undefined" && isTagSearch
      ? "index.php?controller=search&action=filterByTag&" + params
      : "index.php?controller=category&action=filterProducts&" + params;

  const scrollPosition =
    window.pageYOffset || document.documentElement.scrollTop;

  fetch(apiUrl, {
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const productsContainer = document.getElementById("products-container");
        if (productsContainer) {
          // Thay thế hoàn toàn nội dung của #products-container
          productsContainer.innerHTML = data.html;
        } else {
          console.error("Không tìm thấy #products-container");
        }
        const countDisplay = document.getElementById("product-count-display");
        if (countDisplay) {
          let countText = `<span>Hiển thị ${data.count} sản phẩm (Tổng: ${data.totalProducts})</span>`;
          if (
            typeof isTagSearch !== "undefined" &&
            isTagSearch &&
            typeof searchTag !== "undefined"
          ) {
            countText += `<small class="text-muted d-block">cho tag "${searchTag}"</small>`;
          }
          countDisplay.innerHTML = countText;
        }
        updateBrowserUrl(params);
        initProductActions();
        initPagination();
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
      showLoading(false);
      window.scrollTo(0, scrollPosition);
    });
}

function getFilterParams(page = 1) {
  let params =
    typeof isTagSearch !== "undefined" &&
    isTagSearch &&
    typeof searchTag !== "undefined"
      ? `tag=${encodeURIComponent(searchTag)}`
      : `id=${getCurrentCategoryId()}`;
  const sortSelect = document.getElementById("sort-by");
  if (sortSelect) {
    params += `&sort=${sortSelect.value}`;
  }
  const priceRange = document.getElementById("price-range");
  if (priceRange) {
    params += `&price=${priceRange.value}`;
  }
  const sizeCheckboxes = document.querySelectorAll(
    '.filter-option input[type="checkbox"]:checked'
  );
  sizeCheckboxes.forEach((checkbox) => {
    const size = checkbox.id.replace("size-", "").toUpperCase();
    params += `&size[]=${size}`;
  });
  params += `&page=${page}`;
  return params;
}

function updateBrowserUrl(params) {
  let baseUrl =
    typeof isTagSearch !== "undefined" && isTagSearch
      ? "index.php?controller=search&action=showByTag"
      : "index.php?controller=category&action=show";
  const newUrl = `${baseUrl}&${params}`;
  window.history.pushState({ path: newUrl }, "", newUrl);
}

function initProductActions() {
  const quickViewButtons = document.querySelectorAll(".btn-quickview");
  quickViewButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      const productId = this.getAttribute("data-product-id");
      window.location.href = `index.php?controller=product&action=show&id=${productId}`;
    });
  });

  const addToCartButtons = document.querySelectorAll(".btn-add-cart");
  addToCartButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const productId = this.getAttribute("data-product-id");
      fetch(`index.php?controller=cart&action=add&id=${productId}&qty=1&size=M`)
        .then((response) => response.text())
        .then((data) => {
          showNotification("Đã thêm sản phẩm vào giỏ hàng!", "success");
          document.dispatchEvent(new CustomEvent("cartUpdated"));
          return fetch("index.php?controller=cart&action=getCount", {
            headers: {
              "X-Requested-With": "XMLHttpRequest",
            },
          });
        })
        .then((response) => response.json())
        .then((data) => {
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

function showLoading(show) {
  const loadingIndicator = document.getElementById("loading-indicator");
  if (loadingIndicator) {
    loadingIndicator.style.display = show ? "block" : "none";
  }
}

function getCurrentCategoryId() {
  if (typeof categoryId !== "undefined" && categoryId !== "null") {
    return categoryId;
  }
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get("id") || "null";
}

function showNotification(message, type = "success") {
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
  let container = document.querySelector(".notification-container");
  if (!container) {
    container = document.createElement("div");
    container.className = "notification-container";
    document.body.appendChild(container);
  }
  container.appendChild(notification);
  setTimeout(() => {
    notification.classList.add("show");
  }, 10);
  setTimeout(() => {
    notification.classList.remove("show");
    setTimeout(() => {
      notification.remove();
    }, 300);
  }, 3000);
}
