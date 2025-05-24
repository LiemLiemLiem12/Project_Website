// Search functionality JavaScript
document.addEventListener("DOMContentLoaded", function () {
  initializeSearchPage();
  initializeSearchSuggestions();
  initializeSearchFilters();
  initializeSearchAnimations();
});

// Initialize search page functionality
function initializeSearchPage() {
  // Add search result animations
  const productCards = document.querySelectorAll(".search-product-card");
  productCards.forEach((card, index) => {
    card.style.animationDelay = `${index * 0.1}s`;
    card.classList.add("fade-in");
  });

  // Initialize sort functionality
  const sortSelect = document.querySelector(".sort-select");
  if (sortSelect) {
    sortSelect.addEventListener("change", function () {
      handleSortChange(this.value);
    });
  }

  // Initialize search input focus
  const searchInputs = document.querySelectorAll(".search-input");
  searchInputs.forEach((input) => {
    input.addEventListener("focus", function () {
      this.parentElement.classList.add("search-active");
    });

    input.addEventListener("blur", function () {
      setTimeout(() => {
        this.parentElement.classList.remove("search-active");
      }, 200);
    });
  });
}

// Handle sort change
function handleSortChange(sortValue) {
  const url = new URL(window.location);
  url.searchParams.set("sort", sortValue);
  url.searchParams.set("page", "1"); // Reset to first page

  // Add loading state
  showLoadingState();

  // Redirect to new URL
  window.location.href = url.toString();
}

// Initialize search suggestions
function initializeSearchSuggestions() {
  const searchInputs = document.querySelectorAll(".search-input");

  searchInputs.forEach((input) => {
    let searchTimeout;
    let currentRequest;

    input.addEventListener("input", function () {
      const query = this.value.trim();
      const suggestionsContainer = this.parentElement.nextElementSibling;

      // Clear previous timeout
      clearTimeout(searchTimeout);

      // Cancel previous request
      if (currentRequest) {
        currentRequest.abort();
      }

      if (query.length < 2) {
        hideSuggestions(suggestionsContainer);
        return;
      }

      // Show loading
      showSuggestionsLoading(suggestionsContainer);

      // Debounce search
      searchTimeout = setTimeout(() => {
        currentRequest = fetchSearchSuggestions(query, suggestionsContainer);
      }, 300);
    });

    // Handle keyboard navigation
    input.addEventListener("keydown", function (e) {
      const suggestionsContainer = this.parentElement.nextElementSibling;
      handleSuggestionNavigation(e, suggestionsContainer);
    });
  });
}

// Fetch search suggestions
function fetchSearchSuggestions(query, container) {
  const controller = new AbortController();

  fetch(
    `index.php?controller=search&action=suggestions&q=${encodeURIComponent(
      query
    )}`,
    {
      signal: controller.signal,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    }
  )
    .then((response) => {
      if (!response.ok) throw new Error("Network response was not ok");
      return response.json();
    })
    .then((data) => {
      displaySearchSuggestions(data, container);
    })
    .catch((error) => {
      if (error.name !== "AbortError") {
        console.error("Search suggestions error:", error);
        hideSuggestions(container);
      }
    });

  return controller;
}

// Display search suggestions
function displaySearchSuggestions(suggestions, container) {
  const content = container.querySelector(".suggestions-content");

  if (!suggestions || suggestions.length === 0) {
    content.innerHTML = `
            <div class="no-suggestions">
                <i class="fas fa-search"></i>
                <p>Không tìm thấy sản phẩm phù hợp</p>
            </div>
        `;
  } else {
    const html = suggestions
      .map(
        (item, index) => `
            <div class="suggestion-item" 
                 data-index="${index}"
                 onclick="selectSuggestion(${item.id_product}, '${escapeHtml(
          item.name
        )}')">
                <img src="/Project_Website/ProjectWeb/upload/img/All-Product/${
                  item.main_image
                }" 
                     alt="${escapeHtml(item.name)}" 
                     class="suggestion-image"
                     onerror="this.src='/Project_Website/ProjectWeb/upload/img/All-Product/default.jpg'">
                <div class="suggestion-info">
                    <div class="suggestion-name">${highlightSearchTerm(
                      item.name,
                      getCurrentSearchQuery()
                    )}</div>
                    <div class="suggestion-price">${formatCurrency(
                      item.current_price
                    )}</div>
                </div>
                <div class="suggestion-action">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        `
      )
      .join("");

    content.innerHTML = html;
  }

  showSuggestions(container);
}

// Handle suggestion navigation with keyboard
function handleSuggestionNavigation(e, container) {
  const suggestions = container.querySelectorAll(".suggestion-item");
  if (suggestions.length === 0) return;

  const currentActive = container.querySelector(".suggestion-item.active");
  let activeIndex = currentActive
    ? Array.from(suggestions).indexOf(currentActive)
    : -1;

  switch (e.key) {
    case "ArrowDown":
      e.preventDefault();
      activeIndex = Math.min(activeIndex + 1, suggestions.length - 1);
      setActiveSuggestion(suggestions, activeIndex);
      break;
    case "ArrowUp":
      e.preventDefault();
      activeIndex = Math.max(activeIndex - 1, -1);
      setActiveSuggestion(suggestions, activeIndex);
      break;
    case "Enter":
      if (currentActive) {
        e.preventDefault();
        currentActive.click();
      }
      break;
    case "Escape":
      hideSuggestions(container);
      break;
  }
}

// Set active suggestion
function setActiveSuggestion(suggestions, index) {
  suggestions.forEach((item) => item.classList.remove("active"));
  if (index >= 0 && suggestions[index]) {
    suggestions[index].classList.add("active");
    suggestions[index].scrollIntoView({ block: "nearest" });
  }
}

// Select suggestion
function selectSuggestion(productId, productName) {
  // Add to recent searches
  addToRecentSearches(productName);

  // Navigate to product page
  window.location.href = `index.php?controller=product&action=show&id=${productId}`;
}

// Initialize search filters
function initializeSearchFilters() {
  const filterOptions = document.querySelectorAll(".filter-option");

  filterOptions.forEach((option) => {
    option.addEventListener("click", function () {
      this.classList.toggle("active");
      applyFilters();
    });
  });

  // Price range filter
  const priceRange = document.getElementById("priceRange");
  if (priceRange) {
    priceRange.addEventListener("input", debounce(applyFilters, 500));
  }
}

// Apply search filters
function applyFilters() {
  const activeFilters = {
    categories: [],
    tags: [],
    priceRange: null,
    sizes: [],
  };

  // Get active category filters
  document
    .querySelectorAll('.filter-option[data-type="category"].active')
    .forEach((el) => {
      activeFilters.categories.push(el.dataset.value);
    });

  // Get active tag filters
  document
    .querySelectorAll('.filter-option[data-type="tag"].active')
    .forEach((el) => {
      activeFilters.tags.push(el.dataset.value);
    });

  // Get active size filters
  document
    .querySelectorAll('.filter-option[data-type="size"].active')
    .forEach((el) => {
      activeFilters.sizes.push(el.dataset.value);
    });

  // Get price range
  const priceRange = document.getElementById("priceRange");
  if (priceRange) {
    activeFilters.priceRange = priceRange.value;
  }

  // Apply filters to URL
  const url = new URL(window.location);

  // Clear existing filter params
  url.searchParams.delete("categories");
  url.searchParams.delete("tags");
  url.searchParams.delete("price_max");
  url.searchParams.delete("sizes");

  // Add new filter params
  if (activeFilters.categories.length > 0) {
    url.searchParams.set("categories", activeFilters.categories.join(","));
  }
  if (activeFilters.tags.length > 0) {
    url.searchParams.set("tags", activeFilters.tags.join(","));
  }
  if (activeFilters.priceRange) {
    url.searchParams.set("price_max", activeFilters.priceRange);
  }
  if (activeFilters.sizes.length > 0) {
    url.searchParams.set("sizes", activeFilters.sizes.join(","));
  }

  // Reset to first page
  url.searchParams.set("page", "1");

  // Show loading and redirect
  showLoadingState();
  window.location.href = url.toString();
}

// Initialize search animations
function initializeSearchAnimations() {
  // Animate product cards on scroll
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("animate-in");
      }
    });
  }, observerOptions);

  // Observe product cards
  document.querySelectorAll(".search-product-card").forEach((card) => {
    observer.observe(card);
  });

  // Add hover effects to product cards
  document.querySelectorAll(".search-product-card").forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-8px) scale(1.02)";
    });

    card.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0) scale(1)";
    });
  });
}

// Utility Functions
function showSuggestions(container) {
  container.style.display = "block";
  container.classList.add("slide-up");
}

function hideSuggestions(container) {
  if (container) {
    container.style.display = "none";
    container.classList.remove("slide-up");
  }
}

function showSuggestionsLoading(container) {
  const content = container.querySelector(".suggestions-content");
  content.innerHTML = `
        <div class="search-loading">
            <i class="fas fa-spinner fa-spin"></i>
            Đang tìm kiếm...
        </div>
    `;
  showSuggestions(container);
}

function showLoadingState() {
  // Show loading overlay
  const loadingOverlay = document.createElement("div");
  loadingOverlay.className = "search-loading-overlay";
  loadingOverlay.innerHTML = `
        <div class="search-loader-container">
            <div class="search-loader"></div>
            <p>Đang tải kết quả...</p>
        </div>
    `;
  document.body.appendChild(loadingOverlay);
}

function getCurrentSearchQuery() {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get("q") || "";
}

function highlightSearchTerm(text, searchTerm) {
  if (!searchTerm) return escapeHtml(text);

  const regex = new RegExp(`(${escapeRegex(searchTerm)})`, "gi");
  return escapeHtml(text).replace(
    regex,
    '<span class="search-highlight">$1</span>'
  );
}

function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text;
  return div.innerHTML;
}

function escapeRegex(string) {
  return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
}

function formatCurrency(amount) {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
    minimumFractionDigits: 0,
  }).format(amount);
}

function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Recent searches functionality
function addToRecentSearches(searchTerm) {
  let recentSearches = JSON.parse(
    localStorage.getItem("recentSearches") || "[]"
  );

  // Remove if already exists
  recentSearches = recentSearches.filter((term) => term !== searchTerm);

  // Add to beginning
  recentSearches.unshift(searchTerm);

  // Keep only last 5 searches
  recentSearches = recentSearches.slice(0, 5);

  localStorage.setItem("recentSearches", JSON.stringify(recentSearches));
}

function getRecentSearches() {
  return JSON.parse(localStorage.getItem("recentSearches") || "[]");
}

// Global search functions
window.performSearch = function (query) {
  if (!query.trim()) return;

  addToRecentSearches(query);
  window.location.href = `index.php?controller=search&q=${encodeURIComponent(
    query
  )}`;
};

window.clearSearchFilters = function () {
  const url = new URL(window.location);
  const searchQuery = url.searchParams.get("q");

  // Keep only the search query
  const newUrl = new URL(window.location.origin + window.location.pathname);
  if (searchQuery) {
    newUrl.searchParams.set("controller", "search");
    newUrl.searchParams.set("q", searchQuery);
  }

  showLoadingState();
  window.location.href = newUrl.toString();
};

// Search analytics (optional)
function trackSearchEvent(eventType, data) {
  // Implement search analytics tracking here
  console.log("Search Event:", eventType, data);
}

// Export functions for external use
window.SearchModule = {
  performSearch,
  clearSearchFilters,
  addToRecentSearches,
  getRecentSearches,
  trackSearchEvent,
};
