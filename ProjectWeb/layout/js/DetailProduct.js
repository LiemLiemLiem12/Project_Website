// Document Ready Function
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all functionality
    initProductGallery();
    initColorSelection();
    initQuantityControl();
    initTabs();
    initAddToCart();
    initRelatedProductsCarousel();
    initStoreCheck();
    initPromoCodeButtons();
    animateProductInfo();
    setupAutoScroll();
    initScrollEffects();
    initImageZoom();
    initWishlistButton();
    initProductStatistics();
    initShareButtons();
});

// Product Image Gallery
function initProductGallery() {
    const mainImage = document.getElementById('mainProductImage');
    const thumbnails = document.querySelectorAll('.product-thumbnail');
    
    if (!mainImage || thumbnails.length === 0) return;
    
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            // Remove active class from all thumbnails
            thumbnails.forEach(thumb => thumb.classList.remove('active'));
            
            // Add active class to clicked thumbnail
            this.classList.add('active');
            
            // Update main image source
            mainImage.src = this.src;
            
            // Add fade effect
            mainImage.classList.add('fade');
            setTimeout(() => {
                mainImage.classList.remove('fade');
            }, 500);
        });
    });
    
    // Add zoom effect on hover for larger screens
    if (window.innerWidth >= 768) {
        const imageContainer = mainImage.parentElement;
        
        imageContainer.addEventListener('mousemove', function(e) {
            const { left, top, width, height } = this.getBoundingClientRect();
            const x = (e.clientX - left) / width;
            const y = (e.clientY - top) / height;
            
            mainImage.style.transformOrigin = `${x * 100}% ${y * 100}%`;
            mainImage.style.transform = 'scale(1.3)';
        });
        
        imageContainer.addEventListener('mouseleave', function() {
            mainImage.style.transform = 'scale(1)';
        });
    }
}

// Color Selection
function initColorSelection() {
    const colorOptions = document.querySelectorAll('.color-option');
    
    if (colorOptions.length === 0) return;
    
    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            colorOptions.forEach(o => o.classList.remove('active'));
            
            // Add active class to clicked option
            this.classList.add('active');
            
            // Get selected color
            const selectedColor = this.getAttribute('data-color');
            
            // Update product info based on color if needed
            console.log(`Selected color: ${selectedColor}`);
        });
    });
}

// Quantity Control
function initQuantityControl() {
    const decreaseBtn = document.getElementById('decreaseQuantity');
    const increaseBtn = document.getElementById('increaseQuantity');
    const quantityInput = document.getElementById('quantityInput');
    const totalPriceElement = document.getElementById('totalPrice');
    
    if (!decreaseBtn || !increaseBtn || !quantityInput || !totalPriceElement) return;
    
    // Extract current price
    const productPrice = parseFloat(totalPriceElement.textContent.replace('₫', '').replace(',', ''));
    
    // Update total price based on quantity
    function updateTotalPrice() {
        const quantity = parseInt(quantityInput.value);
        const totalPrice = productPrice * quantity;
        totalPriceElement.textContent = '₫' + totalPrice.toLocaleString('vi-VN');
    }
    
    // Decrease button click
    decreaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            updateTotalPrice();
        }
    });
    
    // Increase button click
    increaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
            updateTotalPrice();
    });
    
    // Input change
    quantityInput.addEventListener('input', function() {
        let currentValue = parseInt(this.value);
        
        if (isNaN(currentValue) || currentValue < 1) {
            this.value = 1;
        }
        
        updateTotalPrice();
    });
}

// Tabs Functionality
function initTabs() {
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    if (tabs.length === 0 || tabContents.length === 0) return;
    
    // Function to open tab
    window.openTab = function(tabId) {
        // Hide all tab contents
        tabContents.forEach(content => {
            content.classList.remove('active');
        });
        
        // Remove active class from all tabs
        tabs.forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Show the selected tab content
        document.getElementById(tabId).classList.add('active');
        
        // Add active class to the clicked tab
        tabs.forEach(tab => {
            if (tab.getAttribute('onclick').includes(tabId)) {
                tab.classList.add('active');
            }
        });
    };
}

// Add to Cart Functionality
function initAddToCart() {
    const addToCartBtn = document.getElementById('addToCartBtn');
    const buyNowBtn = document.querySelector('.buy-now-btn');
    const cartToast = document.getElementById('cartToast');
    
    if (!addToCartBtn || !cartToast) return;
    
    // Add to Cart button click
    addToCartBtn.addEventListener('click', function() {
        const productTitle = document.querySelector('.product-main-title').textContent;
        const quantity = parseInt(document.getElementById('quantityInput').value);
        const selectedColor = document.querySelector('.color-option.active')?.getAttribute('data-color') || 'Default';
        const price = document.getElementById('totalPrice').textContent;
        
        // Get existing cart from localStorage or create new one
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        // Add item to cart
        cart.push({
            title: productTitle,
            quantity: quantity,
            color: selectedColor,
            price: price,
            image: document.getElementById('mainProductImage').src
        });
        
        // Save cart to localStorage
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Show toast notification
        showToast('Thêm sản phẩm vào giỏ hàng thành công!');
    });
    
    // Buy Now button click (if exists)
    if (buyNowBtn) {
    buyNowBtn.addEventListener('click', function() {
        // Add to cart first
        addToCartBtn.click();
        
        // Then redirect to cart page
        setTimeout(() => {
            window.location.href = 'Cart.html';
        }, 1000);
    });
}
}

// Related Products Carousel
function initRelatedProductsCarousel() {
    const productsWrapper = document.getElementById('productsWrapper');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const productsSlider = document.getElementById('productsSlider');
    
    if (!productsWrapper || !prevBtn || !nextBtn || !productsSlider) return;
    
    const productCards = productsSlider.querySelectorAll('.related-product-card');
    if (productCards.length === 0) return;
    
    let position = 0;
    const cardWidth = productCards[0].offsetWidth + 20; // 20px for gap
    const visibleCards = Math.floor(productsWrapper.offsetWidth / cardWidth);
    const maxPosition = Math.max(0, productCards.length - visibleCards);
    
    // Scroll amount for buttons - move by visible cards
    const scrollAmount = visibleCards > 1 ? visibleCards - 1 : 1;
    
    // Update carousel position
    function updateCarousel() {
        productsSlider.style.transform = `translateX(-${position * cardWidth}px)`;
        
        // Update button states
        prevBtn.style.opacity = position <= 0 ? '0.5' : '1';
        prevBtn.style.cursor = position <= 0 ? 'default' : 'pointer';
        
        nextBtn.style.opacity = position >= maxPosition ? '0.5' : '1';
        nextBtn.style.cursor = position >= maxPosition ? 'default' : 'pointer';
    }
    
    // Previous button click with smooth scrolling
    prevBtn.addEventListener('click', function() {
        if (position > 0) {
            // Temporarily pause animation
            productsSlider.style.animation = 'none';
            
            // Calculate new position
            position = Math.max(0, position - scrollAmount);
            
            // Apply smooth transition
            productsSlider.style.transition = 'transform 0.5s ease';
            updateCarousel();
            
            // Resume animation after transition
            setTimeout(() => {
                productsSlider.style.transition = '';
                productsSlider.style.animation = 'slideProducts 30s linear infinite';
            }, 500);
        }
    });
    
    // Next button click with smooth scrolling
    nextBtn.addEventListener('click', function() {
        if (position < maxPosition) {
            // Temporarily pause animation
            productsSlider.style.animation = 'none';
            
            // Calculate new position
            position = Math.min(maxPosition, position + scrollAmount);
            
            // Apply smooth transition
            productsSlider.style.transition = 'transform 0.5s ease';
            updateCarousel();
            
            // Resume animation after transition
            setTimeout(() => {
                productsSlider.style.transition = '';
                productsSlider.style.animation = 'slideProducts 30s linear infinite';
            }, 500);
        }
    });
    
    // Initialize carousel
    updateCarousel();
    
    // Update on window resize
    window.addEventListener('resize', function() {
        // Recalculate visible cards
        const newVisibleCards = Math.floor(productsWrapper.offsetWidth / cardWidth);
        const newMaxPosition = Math.max(0, productCards.length - newVisibleCards);
        
        // Adjust position if needed
        if (position > newMaxPosition) {
            position = newMaxPosition;
        }
        
        updateCarousel();
    });
}

// Store Check Functionality
function initStoreCheck() {
    const storeCheckBtn = document.querySelector('.store-check-btn');
    
    if (!storeCheckBtn) return;
    
    storeCheckBtn.addEventListener('click', function() {
        alert('Chức năng đang được phát triển. Vui lòng quay lại sau!');
    });
}

// Promo Code Buttons
function initPromoCodeButtons() {
    const promoButtons = document.querySelectorAll('.promo-code-btn');
    
    if (promoButtons.length === 0) return;
    
    promoButtons.forEach(button => {
        button.addEventListener('click', function() {
            const promoCode = this.textContent.trim();
            
            // Copy to clipboard
            navigator.clipboard.writeText(promoCode).then(() => {
                showToast(`Đã sao chép mã "${promoCode}" vào clipboard`);
            }).catch(err => {
                console.error('Copy failed:', err);
                showToast(`Không thể sao chép mã. Vui lòng tự sao chép: ${promoCode}`);
            });
        });
    });
}

// Show Toast Notification
function showToast(message) {
    const cartToast = document.getElementById('cartToast');
    if (!cartToast) return;
    
    // Set message
    const toastBody = cartToast.querySelector('.toast-body');
    if (toastBody) {
        toastBody.textContent = message;
    }
    
    // Show toast
    cartToast.classList.add('show');
    
    // Hide after 3 seconds
    setTimeout(() => {
        cartToast.classList.remove('show');
    }, 3000);
    
    // Close button
    const closeBtn = cartToast.querySelector('.btn-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            cartToast.classList.remove('show');
        });
    }
}

// Add animated entrance for product information
function animateProductInfo() {
    const elements = [
        '.product-main-title',
        '.product-code',
        '.current-price',
        '.original-price',
        '.promo-section',
        '.color-option',
        '.quantity-btn',
        '#quantityInput',
        '.buy-now-btn',
        '#addToCartBtn',
        '.store-check',
        '.guarantees-section'
    ];
    
    elements.forEach((selector, index) => {
        const element = document.querySelector(selector);
        if (!element) return;
        
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, 100 + (index * 100)); // Staggered animation
    });
}

// Setup auto-scroll for related products
function setupAutoScroll() {
    const productsSlider = document.getElementById('productsSlider');
    const productsWrapper = document.getElementById('productsWrapper');
    
    if (!productsSlider || !productsWrapper) return;
    
    // Clone items for infinite scrolling effect
    const items = productsSlider.querySelectorAll('.related-product-card');
    if (items.length === 0) return;
    
    // Clone each item and append to the slider
    items.forEach(item => {
        const clone = item.cloneNode(true);
        productsSlider.appendChild(clone);
    });
    
    // Pause animation on hover
    productsWrapper.addEventListener('mouseenter', function() {
        productsSlider.style.animationPlayState = 'paused';
    });
    
    productsWrapper.addEventListener('mouseleave', function() {
        productsSlider.style.animationPlayState = 'running';
    });
    
    // Restart animation when it ends
    productsSlider.addEventListener('animationiteration', function() {
        // You can add code here to handle iteration (not needed for infinite animation)
    });
}

// Add scrolling animation effects
function initScrollEffects() {
    // Add a 'scrolled' class to body when page is scrolled
    window.addEventListener('scroll', function() {
        const scrollPosition = window.scrollY;
        
        // Fade in elements as they come into view
        const fadeElements = document.querySelectorAll('.fade-in-element');
        fadeElements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.3;
            
            if (elementPosition < screenPosition) {
                element.classList.add('visible');
            }
        });
        
        // Parallax effect for product images
        const productImage = document.querySelector('.product-main-image-container');
        if (productImage) {
            const speed = 0.2;
            productImage.style.transform = `translateY(${scrollPosition * speed}px)`;
        }
        
        // Show/hide back to top button
        const backToTopBtn = document.querySelector('.back-to-top');
        if (backToTopBtn) {
            if (scrollPosition > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        }
    });
    
    // Add fade-in class to elements 
    document.querySelectorAll('.section-title, .tab-content, .customer-support-section, .related-product-card').forEach(el => {
        el.classList.add('fade-in-element');
    });
    
    // Create back to top button if it doesn't exist
    if (!document.querySelector('.back-to-top')) {
        const backToTopBtn = document.createElement('button');
        backToTopBtn.className = 'back-to-top';
        backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
        document.body.appendChild(backToTopBtn);
    }
}

// Enhanced image zoom effect
function initImageZoom() {
    const mainImage = document.getElementById('mainProductImage');
    if (!mainImage) return;
    
    const imageContainer = mainImage.parentElement;
    
    // Create lens element for zoom effect
    const lens = document.createElement('div');
    lens.className = 'img-zoom-lens';
    imageContainer.appendChild(lens);
    
    // Create zoom result container
    const resultContainer = document.createElement('div');
    resultContainer.className = 'img-zoom-result';
    imageContainer.appendChild(resultContainer);
    
    let active = false;
    
    // Show zoom on mouseenter
    imageContainer.addEventListener('mouseenter', function(e) {
        if (window.innerWidth < 768) return; // Disable on mobile
        
        active = true;
        lens.style.display = 'block';
        resultContainer.style.display = 'block';
        updateZoom(e);
    });
    
    // Hide zoom on mouseleave
    imageContainer.addEventListener('mouseleave', function() {
        active = false;
        lens.style.display = 'none';
        resultContainer.style.display = 'none';
    });
    
    // Update zoom position on mousemove
    imageContainer.addEventListener('mousemove', updateZoom);
    
    function updateZoom(e) {
        if (!active) return;
        
        // Get cursor position
        const pos = getCursorPos(e);
        
        // Calculate position of lens
        let x = pos.x - (lens.offsetWidth / 2);
        let y = pos.y - (lens.offsetHeight / 2);
        
        // Prevent lens from going outside image
        if (x > mainImage.width - lens.offsetWidth) {x = mainImage.width - lens.offsetWidth;}
        if (x < 0) {x = 0;}
        if (y > mainImage.height - lens.offsetHeight) {y = mainImage.height - lens.offsetHeight;}
        if (y < 0) {y = 0;}
        
        // Set lens position
        lens.style.left = x + "px";
        lens.style.top = y + "px";
        
        // Calculate ratio between result container and lens
        const cx = resultContainer.offsetWidth / lens.offsetWidth;
        const cy = resultContainer.offsetHeight / lens.offsetHeight;
        
        // Set background of result container
        resultContainer.style.backgroundImage = "url('" + mainImage.src + "')";
        resultContainer.style.backgroundSize = (mainImage.width * cx) + "px " + (mainImage.height * cy) + "px";
        resultContainer.style.backgroundPosition = "-" + (x * cx) + "px -" + (y * cy) + "px";
    }
    
    function getCursorPos(e) {
        const rect = mainImage.getBoundingClientRect();
        const x = e.pageX - rect.left - window.pageXOffset;
        const y = e.pageY - rect.top - window.pageYOffset;
        return {x: x, y: y};
    }
}

// Add wishlist functionality
function initWishlistButton() {
    // Create wishlist button
    const actionButtons = document.querySelector('.d-grid.gap-2.mb-4');
    if (!actionButtons) return;
    
    const wishlistRow = document.createElement('div');
    wishlistRow.className = 'row mt-2';
    
    const wishlistCol = document.createElement('div');
    wishlistCol.className = 'col-12';
    
    const wishlistBtn = document.createElement('button');
    wishlistBtn.className = 'btn btn-outline-secondary w-100 wishlist-btn';
    wishlistBtn.innerHTML = '<i class="far fa-heart"></i> Thêm vào yêu thích';
    
    wishlistCol.appendChild(wishlistBtn);
    wishlistRow.appendChild(wishlistCol);
    actionButtons.appendChild(wishlistRow);
    
    // Toggle wishlist state
    wishlistBtn.addEventListener('click', function() {
        this.classList.toggle('active');
        
        if (this.classList.contains('active')) {
            this.innerHTML = '<i class="fas fa-heart"></i> Đã thêm vào yêu thích';
        } else {
            this.innerHTML = '<i class="far fa-heart"></i> Thêm vào yêu thích';
        }
        
        // Animation
        this.classList.add('pulse-animation');
        setTimeout(() => {
            this.classList.remove('pulse-animation');
        }, 700);
        
        // Show toast
        showToast(this.classList.contains('active') ? 
                 'Đã thêm sản phẩm vào danh sách yêu thích!' : 
                 'Đã xóa sản phẩm khỏi danh sách yêu thích!');
    });
}

// Add product statistics
function initProductStatistics() {
    const productInfo = document.querySelector('.product-code');
    if (!productInfo) return;
    
    const statsContainer = document.createElement('div');
    statsContainer.className = 'product-stats mt-3';
    
    // Random values for demo
    const views = Math.floor(Math.random() * 500) + 100;
    const sales = Math.floor(Math.random() * 50) + 10;
    
    statsContainer.innerHTML = `
        <div class="stat-item">
            <i class="fas fa-eye"></i> ${views} lượt xem
        </div>
        <div class="stat-item">
            <i class="fas fa-shopping-bag"></i> ${sales} đã bán
        </div>
        <div class="stat-item">
            <div class="stock-indicator in-stock"></div> Còn hàng
        </div>
    `;
    
    productInfo.after(statsContainer);
}

// Add social share buttons
function initShareButtons() {
    const productTitle = document.querySelector('.product-main-title');
    if (!productTitle) return;
    
    const shareContainer = document.createElement('div');
    shareContainer.className = 'social-share-container mt-4';
    
    shareContainer.innerHTML = `
        <span class="share-label">Chia sẻ: </span>
        <button class="share-btn facebook"><i class="fab fa-facebook-f"></i></button>
        <button class="share-btn twitter"><i class="fab fa-twitter"></i></button>
        <button class="share-btn pinterest"><i class="fab fa-pinterest-p"></i></button>
        <button class="share-btn copy-link"><i class="fas fa-link"></i></button>
    `;
    
    // Add share container after product guarantees
    const productGuarantees = document.querySelector('.guarantees-section');
    if (productGuarantees) {
        productGuarantees.after(shareContainer);
    } else {
        const productContainer = document.querySelector('.product-details-container');
        if (productContainer) {
            productContainer.appendChild(shareContainer);
        }
    }
    
    // Add click handlers
    const shareButtons = document.querySelectorAll('.share-btn');
    shareButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Animation
            this.classList.add('share-animation');
            setTimeout(() => {
                this.classList.remove('share-animation');
            }, 500);
            
            // Demo share functionality
            const productUrl = window.location.href;
            const productName = document.querySelector('.product-main-title').textContent;
            
            if (this.classList.contains('facebook')) {
                showToast('Đã chia sẻ sản phẩm lên Facebook!');
            } else if (this.classList.contains('twitter')) {
                showToast('Đã chia sẻ sản phẩm lên Twitter!');
            } else if (this.classList.contains('pinterest')) {
                showToast('Đã chia sẻ sản phẩm lên Pinterest!');
            } else if (this.classList.contains('copy-link')) {
                navigator.clipboard.writeText(productUrl).then(() => {
                    showToast('Đã sao chép liên kết sản phẩm!');
                });
            }
        });
    });
}
