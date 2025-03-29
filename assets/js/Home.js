document.addEventListener('DOMContentLoaded', function() {
    // Sample product data
    const productData = [
        {
            id: 1,
            name: 'Áo Thun Unisex Oversize Cottonink Phối Viền',
            image: 'assets/img/Home/item1.jpg',
            price: 249000,
            oldPrice: 349000,
            isNew: true,
            isSale: true
        },
        {
            id: 2,
            name: 'Áo Sơ Mi Trơn Dáng Rộng Form Regular',
            image: 'assets/img/Home/item1.jpg',
            price: 299000,
            oldPrice: 399000,
            isNew: true,
            isSale: false
        },
        {
            id: 3,
            name: 'Quần Jean Nam Trơn Basic Dáng Slim Fit',
            image: 'assets/img/Home/item1.jpg',
            price: 399000,
            oldPrice: 499000,
            isNew: false,
            isSale: true
        },
        {
            id: 4,
            name: 'Áo Polo Nam Trơn Form Loose Cotton Mix',
            image: 'assets/img/Home/item1.jpg',
            price: 289000,
            oldPrice: 359000,
            isNew: true,
            isSale: false
        },
        {
            id: 5,
            name: 'Quần Jogger Dáng Suông Trơn Form Regular',
            image: 'assets/img/Home/item1.jpg',
            price: 359000,
            oldPrice: 459000,
            isNew: false,
            isSale: true
        },
        {
            id: 6,
            name: 'Áo Khoác Jean Nam Trơn Form Regular',
            image: 'assets/img/Home/item1.jpg',
            price: 459000,
            oldPrice: 559000,
            isNew: true,
            isSale: true
        },
        {
            id: 7,
            name: 'Áo Thun Nam Trơn Đơn Giản Form Regular',
            image: 'assets/img/Home/item1.jpg',
            price: 199000,
            oldPrice: 249000,
            isNew: true,
            isSale: false
        },
        {
            id: 8,
            name: 'Quần Short Nam Trơn Form Slim Fit',
            image: 'assets/img/Home/item1.jpg',
            price: 259000,
            oldPrice: 329000,
            isNew: false,
            isSale: true
        }
    ];

    // Initialize products
    initializeProducts();

    // Initialize Slider
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');

    if (slides.length > 0 && dots.length > 0) {
        // Show first slide
        showSlide(currentSlide);

        // Event listeners for slider controls
        prevBtn.addEventListener('click', () => {
            changeSlide(currentSlide - 1);
        });

        nextBtn.addEventListener('click', () => {
            changeSlide(currentSlide + 1);
        });

        // Event listeners for dots
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                changeSlide(index);
            });
        });

        // Auto slide
        setInterval(() => {
            changeSlide(currentSlide + 1);
        }, 5000);
    }

    // Initialize dropdown menus
    const dropdownMenus = document.querySelectorAll('.has-dropdown');
    
    dropdownMenus.forEach(menu => {
        // Mouse events for desktop
        menu.addEventListener('mouseenter', () => {
            menu.classList.add('active');
        });
        
        menu.addEventListener('mouseleave', () => {
            menu.classList.remove('active');
        });
        
        // Touch events for mobile
        const menuLink = menu.querySelector('a');
        menuLink.addEventListener('click', (e) => {
            if (window.innerWidth <= 991) {
                e.preventDefault();
                menu.classList.toggle('active');
                
                // Close other open dropdowns
                dropdownMenus.forEach(otherMenu => {
                    if (otherMenu !== menu && otherMenu.classList.contains('active')) {
                        otherMenu.classList.remove('active');
                    }
                });
            }
        });
    });

    // Cart badge update
    const cart = document.querySelector('.cart');
    if (cart) {
        cart.setAttribute('data-count', '0');
    }

    // Mobile menu toggle
    const mobileMenuToggle = document.createElement('button');
    mobileMenuToggle.className = 'mobile-menu-toggle';
    mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
    
    const header = document.querySelector('header');
    if (header) {
        header.querySelector('.container').appendChild(mobileMenuToggle);
        
        mobileMenuToggle.addEventListener('click', () => {
            document.querySelector('.main-nav').classList.toggle('active');
        });
    }

    // Functions
    function initializeProducts() {
        const productsGrid = document.querySelector('.products-grid');
        if (!productsGrid) return;

        // Clear products grid
        productsGrid.innerHTML = '';

        // Add products to grid
        productData.forEach(product => {
            const productElement = createProductElement(product);
            productsGrid.appendChild(productElement);
        });

        // Add event listeners to product actions
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-id');
                addToCart(productId);
            });
        });

        const wishlistButtons = document.querySelectorAll('.add-to-wishlist');
        wishlistButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-id');
                addToWishlist(productId);
            });
        });

        const quickViewButtons = document.querySelectorAll('.quick-view');
        quickViewButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-id');
                showQuickView(productId);
            });
        });
    }

    function createProductElement(product) {
        const productElement = document.createElement('div');
        productElement.className = 'product-item';
        
        // Format price with comma separator
        const formattedPrice = product.price.toLocaleString('vi-VN');
        const formattedOldPrice = product.oldPrice.toLocaleString('vi-VN');
        
        // Determine discount percentage
        const discountPercentage = Math.round((product.oldPrice - product.price) / product.oldPrice * 100);
        
        productElement.innerHTML = `
            <div class="product-image">
                <img src="${product.image}" alt="${product.name}">
                <div class="product-badges">
                    ${product.isNew ? '<span class="product-badge badge-new">Mới</span>' : ''}
                    ${product.isSale ? `<span class="product-badge badge-sale">-${discountPercentage}%</span>` : ''}
                </div>
                <div class="product-actions">
                    <a href="#" class="product-action add-to-cart" data-id="${product.id}">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                    <a href="#" class="product-action add-to-wishlist" data-id="${product.id}">
                        <i class="fas fa-heart"></i>
                    </a>
                    <a href="#" class="product-action quick-view" data-id="${product.id}">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </div>
            <div class="product-info">
                <h3 class="product-name">
                    <a href="product-detail.html?id=${product.id}">${product.name}</a>
                </h3>
                <div class="product-price">
                    <span class="current-price">${formattedPrice}đ</span>
                    ${product.oldPrice > product.price ? `<span class="old-price">${formattedOldPrice}đ</span>` : ''}
                </div>
            </div>
        `;
        
        return productElement;
    }

    function showSlide(n) {
        // Reset slides and dots
        slides.forEach(slide => {
            slide.classList.remove('active');
        });
        
        dots.forEach(dot => {
            dot.classList.remove('active');
        });
        
        // Calculate correct slide index
        currentSlide = (n + slides.length) % slides.length;
        
        // Show current slide and dot
        slides[currentSlide].classList.add('active');
        dots[currentSlide].classList.add('active');
    }

    function changeSlide(n) {
        showSlide(n);
    }

    function addToCart(productId) {
        // Find the product
        const product = productData.find(p => p.id == productId);
        if (!product) return;
        
        // Update cart count
        const cart = document.querySelector('.cart');
        if (cart) {
            const currentCount = parseInt(cart.getAttribute('data-count') || '0');
            cart.setAttribute('data-count', currentCount + 1);
        }
        
        // Show notification
        showNotification(`Đã thêm "${product.name}" vào giỏ hàng`);
    }

    function addToWishlist(productId) {
        // Find the product
        const product = productData.find(p => p.id == productId);
        if (!product) return;
        
        // Show notification
        showNotification(`Đã thêm "${product.name}" vào danh sách yêu thích`);
    }

    function showQuickView(productId) {
        // Find the product
        const product = productData.find(p => p.id == productId);
        if (!product) return;
        
        // Create quick view modal
        const modal = document.createElement('div');
        modal.className = 'quick-view-modal';
        
        // Format price with comma separator
        const formattedPrice = product.price.toLocaleString('vi-VN');
        const formattedOldPrice = product.oldPrice.toLocaleString('vi-VN');
        
        modal.innerHTML = `
            <div class="quick-view-content">
                <button class="close-modal"><i class="fas fa-times"></i></button>
                <div class="quick-view-image">
                    <img src="${product.image}" alt="${product.name}">
                </div>
                <div class="quick-view-info">
                    <h2>${product.name}</h2>
                    <div class="product-price">
                        <span class="current-price">${formattedPrice}đ</span>
                        ${product.oldPrice > product.price ? `<span class="old-price">${formattedOldPrice}đ</span>` : ''}
                    </div>
                    <div class="product-description">
                        <p>Sản phẩm chất lượng cao từ 160STORE, thiết kế tinh tế, kiểu dáng thời trang, phù hợp với nhiều phong cách khác nhau.</p>
                    </div>
                    <div class="product-actions">
                        <div class="quantity-selector">
                            <button class="quantity-decrease">-</button>
                            <input type="text" value="1" min="1">
                            <button class="quantity-increase">+</button>
                        </div>
                        <button class="add-to-cart-btn">Thêm vào giỏ hàng</button>
                    </div>
                </div>
            </div>
        `;
        
        // Add modal to body
        document.body.appendChild(modal);
        
        // Prevent body scrolling
        document.body.style.overflow = 'hidden';
        
        // Close modal event
        const closeBtn = modal.querySelector('.close-modal');
        closeBtn.addEventListener('click', () => {
            document.body.removeChild(modal);
            document.body.style.overflow = '';
        });
        
        // Close when clicking outside content
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                document.body.removeChild(modal);
                document.body.style.overflow = '';
            }
        });
        
        // Quantity selector events
        const quantityInput = modal.querySelector('.quantity-selector input');
        const decreaseBtn = modal.querySelector('.quantity-decrease');
        const increaseBtn = modal.querySelector('.quantity-increase');
        
        decreaseBtn.addEventListener('click', () => {
            let qty = parseInt(quantityInput.value);
            if (qty > 1) {
                quantityInput.value = qty - 1;
            }
        });
        
        increaseBtn.addEventListener('click', () => {
            let qty = parseInt(quantityInput.value);
            quantityInput.value = qty + 1;
        });
        
        // Add to cart button event
        const addToCartBtn = modal.querySelector('.add-to-cart-btn');
        addToCartBtn.addEventListener('click', () => {
            const qty = parseInt(quantityInput.value);
            
            // Update cart count
            const cart = document.querySelector('.cart');
            if (cart) {
                const currentCount = parseInt(cart.getAttribute('data-count') || '0');
                cart.setAttribute('data-count', currentCount + qty);
            }
            
            // Show notification
            showNotification(`Đã thêm "${product.name}" (${qty}) vào giỏ hàng`);
            
            // Close modal
            document.body.removeChild(modal);
            document.body.style.overflow = '';
        });
    }

    function showNotification(message) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-check-circle"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Add to body
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Hide and remove after timeout
        setTimeout(() => {
            notification.classList.remove('show');
            
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Add styles for notification and quick view modal
    const customStyles = document.createElement('style');
    customStyles.textContent = `
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            transform: translateX(120%);
            transition: transform 0.3s ease;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification-content {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 20px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 10px;
            max-width: 300px;
        }
        
        .notification-content i {
            color: #4caf50;
            font-size: 18px;
        }
        
        .quick-view-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        .quick-view-content {
            background-color: #fff;
            border-radius: 8px;
            max-width: 900px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            display: flex;
            position: relative;
        }
        
        .close-modal {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            z-index: 1;
        }
        
        .quick-view-image {
            flex: 1;
            padding: 20px;
        }
        
        .quick-view-image img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .quick-view-info {
            flex: 1;
            padding: 30px 20px;
        }
        
        .quick-view-info h2 {
            margin-bottom: 20px;
        }
        
        .product-description {
            margin: 20px 0;
            color: #666;
        }
        
        .quantity-selector {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .quantity-selector button {
            width: 30px;
            height: 30px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            cursor: pointer;
        }
        
        .quantity-selector input {
            width: 40px;
            height: 30px;
            text-align: center;
            border: 1px solid #ddd;
            border-left: none;
            border-right: none;
        }
        
        .add-to-cart-btn {
            padding: 10px 30px;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .add-to-cart-btn:hover {
            background-color: #333;
        }
        
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }
        
        @media (max-width: 767px) {
            .quick-view-content {
                flex-direction: column;
            }
            
            .mobile-menu-toggle {
                display: block;
                position: absolute;
                top: 20px;
                right: 20px;
            }
            
            .main-nav {
                display: none;
            }
            
            .main-nav.active {
                display: block;
            }
            
            .menu {
                flex-direction: column;
            }
            
            .dropdown-menu {
                position: static;
                box-shadow: none;
                opacity: 0;
                visibility: hidden;
                height: 0;
                transform: none;
                transition: all 0.3s;
            }
            
            .menu-item.has-dropdown.active .dropdown-menu {
                opacity: 1;
                visibility: visible;
                height: auto;
            }
        }
    `;
    
    document.head.appendChild(customStyles);
});