/**
 * Header.js - JavaScript functionality for the RSStore header
 * Merged from both Layout and root JS files
 */

document.addEventListener('DOMContentLoaded', function() {
    // Sticky header functionality
    const header = document.querySelector('.main-header');
    const headerOffset = header?.offsetTop || 0;
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > headerOffset + 200) {
            header?.classList.add('sticky');
        } else {
            header?.classList.remove('sticky');
        }
    });
    
    // Mobile navigation toggle
    const navbarToggler = document.querySelector('.navbar-toggler');
    const mainNav = document.querySelector('.navbar-collapse');
    const htmlBody = document.body;
    
    if (navbarToggler) {
        navbarToggler.addEventListener('click', function() {
            this.classList.toggle('active');
            mainNav.classList.toggle('show');
            htmlBody.classList.toggle('menu-open');
        });
    }
    
    // Account dropdown toggle
    const accountToggle = document.querySelector('.account-toggle');
    const accountDropdown = document.querySelector('.account-dropdown');
    const cartToggle = document.querySelector('.cart-toggle');
    const cartDropdown = document.querySelector('.cart-dropdown');
    
    // Handle account dropdown
    if (accountToggle && accountDropdown) {
        accountToggle.addEventListener('click', function(e) {
            e.preventDefault();
            accountDropdown.classList.toggle('show');
            
            // Close cart dropdown if open
            if (cartDropdown && cartDropdown.classList.contains('show')) {
                cartDropdown.classList.remove('show');
            }
        });
    }
    
    // Handle cart dropdown
    if (cartToggle && cartDropdown) {
        cartToggle.addEventListener('click', function(e) {
            e.preventDefault();
            cartDropdown.classList.toggle('show');
            
            // Close account dropdown if open
            if (accountDropdown && accountDropdown.classList.contains('show')) {
                accountDropdown.classList.remove('show');
            }
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (accountDropdown && accountToggle) {
            if (!accountToggle.contains(e.target) && !accountDropdown.contains(e.target)) {
                accountDropdown.classList.remove('show');
            }
        }
        
        if (cartDropdown && cartToggle) {
            if (!cartToggle.contains(e.target) && !cartDropdown.contains(e.target)) {
                cartDropdown.classList.remove('show');
            }
        }
    });
    
    // Handle dropdown menus for navigation - Hover on desktop, click on mobile
    const navDropdownItems = document.querySelectorAll('.nav-item.dropdown, .megamenu-li');
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    // Function to check if we're on mobile
    const isMobile = function() {
        return window.innerWidth < 992;
    };

    // Handle hover behavior for desktop
    navDropdownItems.forEach(item => {
        // On desktop: use hover
        item.addEventListener('mouseenter', function() {
            if (!isMobile()) {
                const dropdown = this.querySelector('.dropdown-menu');
                if (dropdown) {
                    dropdown.classList.add('show');
                    const toggle = this.querySelector('.dropdown-toggle');
                    if (toggle) toggle.setAttribute('aria-expanded', 'true');
                }
            }
        });
        
        item.addEventListener('mouseleave', function() {
            if (!isMobile()) {
                const dropdown = this.querySelector('.dropdown-menu');
                if (dropdown) {
                    dropdown.classList.remove('show');
                    const toggle = this.querySelector('.dropdown-toggle');
                    if (toggle) toggle.setAttribute('aria-expanded', 'false');
                }
            }
        });
    });
    
    // For mobile: use click toggle
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            if (isMobile()) {
                e.preventDefault();
                const parent = this.closest('.dropdown, .megamenu-li');
                const dropdown = parent.querySelector('.dropdown-menu');
                
                if (dropdown) {
                    dropdown.classList.toggle('show');
                    this.setAttribute('aria-expanded', dropdown.classList.contains('show'));
                }
            } else {
                // On desktop, prevent default only for mega menu
                if (this.closest('.megamenu-li')) {
                    e.preventDefault();
                }
            }
        });
    });
    
    // Notification system
    window.showNotification = function(message, type = 'success', duration = 5000) {
        // Create notification container if it doesn't exist
        let container = document.querySelector('.notification-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'notification-container';
            document.body.appendChild(container);
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        // Create icon based on notification type
        let iconClass = 'fas fa-check-circle';
        if (type === 'error') iconClass = 'fas fa-exclamation-circle';
        if (type === 'warning') iconClass = 'fas fa-exclamation-triangle';
        if (type === 'info') iconClass = 'fas fa-info-circle';
        
        // Build notification HTML structure
        notification.innerHTML = `
            <div class="notification-icon">
                <i class="${iconClass}"></i>
            </div>
            <div class="notification-content">
                <div class="notification-title">${type.charAt(0).toUpperCase() + type.slice(1)}</div>
                <div class="notification-message">${message}</div>
            </div>
            <div class="notification-close">
                <i class="fas fa-times"></i>
            </div>
            <div class="notification-progress"></div>
        `;
        
        // Add to container
        container.appendChild(notification);
        
        // Add close functionality
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', function() {
            notification.remove();
        });
        
        // Auto-remove after specified duration
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, duration);
    };

    // Prevent any dropdowns from showing unexpectedly on initial load
    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
        if (!isMobile()) {
            menu.classList.remove('show');
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (!isMobile()) {
            // On desktop, remove any "show" class added by mobile click events
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                if (menu.closest('.nav-item') && !menu.closest('.nav-item').matches(':hover')) {
                    menu.classList.remove('show');
                }
            });
        }
    });
    
    // Search validation
    const searchForms = document.querySelectorAll('.search-form');
    searchForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const input = this.querySelector('.search-input');
            if (!input.value.trim()) {
                e.preventDefault();
                showNotification('Vui lòng nhập từ khóa tìm kiếm', 'warning');
                input.focus();
            }
        });
    });
    
    // Add to cart functionality (example)
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    if (addToCartButtons.length > 0) {
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.dataset.id;
                const productName = this.dataset.name || 'Sản phẩm';
                
                // Here you would normally make an AJAX call to add to cart
                // For this example, just show a notification
                showNotification(`Đã thêm ${productName} vào giỏ hàng`, 'success');
                
                // Update cart count (example)
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    const currentCount = parseInt(cartCount.textContent);
                    cartCount.textContent = currentCount + 1;
                    cartCount.classList.add('pulse');
                    setTimeout(() => {
                        cartCount.classList.remove('pulse');
                    }, 500);
                }
            });
        });
    }

    // Set up promotional banner scroll
    function setupBannerScroll() {
        const bannerContainer = document.querySelector('.top-banner-carousel');
        if (!bannerContainer) return;
        
        const bannerMessages = [
            "VOUCHER 10% TỐI ĐA 10K",
            "VOUCHER 30K ĐƠN TỪ 599K",
            "VOUCHER 70K ĐƠN TỪ 899K",
            "VOUCHER 100K ĐƠN TỪ 1199K",
            "🚛 FREESHIP ĐƠN TỪ 250K"
        ];
        
        // Clear current items
        bannerContainer.innerHTML = '';
        
        // Create scroll container
        const scrollContainer = document.createElement('div');
        scrollContainer.className = 'banner-scroll';
        
        // Add items to container (repeat twice for continuous effect)
        for (let i = 0; i < 2; i++) {
            bannerMessages.forEach(message => {
                const item = document.createElement('div');
                item.className = 'top-banner-item';
                item.textContent = message;
                scrollContainer.appendChild(item);
            });
        }
        
        // Add container to carousel
        bannerContainer.appendChild(scrollContainer);
        
        // Adjust animation speed based on item count
        const itemCount = bannerMessages.length;
        const scrollDuration = itemCount * 8; // 8 seconds per item
        
        // Update animation duration
        scrollContainer.style.animationDuration = `${scrollDuration}s`;
    }
    
    // Initialize banner scroll
    setupBannerScroll();
});

//////////////////////////
$(document).ready(function() {
    // Kiểm tra xem có phải là mobile không
    const isMobile = function() {
        return window.innerWidth < 992;
    };

    // Toggle account dropdown chỉ cho mobile
    $('#userAccountBtn').on('click', function(e) {
        if (isMobile()) {
            e.preventDefault();
            e.stopPropagation();
            $('.account-dropdown').fadeToggle(200);
            $('.cart-dropdown').fadeOut(200);
        }
    });

    // Toggle cart dropdown chỉ cho mobile
    $('#cartBtn').on('click', function(e) {
        if (isMobile()) {
            e.preventDefault();
            e.stopPropagation();
            $('.cart-dropdown').fadeToggle(200);
            $('.account-dropdown').fadeOut(200);
        }
    });

    // Close dropdowns khi click ra ngoài
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.header-action').length) {
            $('.account-dropdown, .cart-dropdown').fadeOut(200);
        }
    });

    // Thiết lập banner scroll ngang
    const setupBannerScroll = function() {
        const bannerMessages = [
            "VOUCHER 10% TỐI ĐA 10K",
            "VOUCHER 30K ĐƠN TỪ 599K",
            "VOUCHER 70K ĐƠN TỪ 899K",
            "VOUCHER 100K ĐƠN TỪ 1199K",
            "🚛 FREESHIP ĐƠN TỪ 250K"
        ];
        
        // Xóa items hiện tại
        $('.top-banner-carousel').empty();
        
        // Tạo container cho scroll
        const scrollContainer = $('<div class="banner-scroll"></div>');
        
        // Thêm các item vào container (lặp lại 2 lần để tạo hiệu ứng liên tục)
        for (let i = 0; i < 2; i++) {
            bannerMessages.forEach(message => {
                scrollContainer.append(`<div class="top-banner-item">${message}</div>`);
            });
        }
        
        // Thêm container vào carousel
        $('.top-banner-carousel').append(scrollContainer);
        
        // Điều chỉnh tốc độ animation dựa trên số lượng items
        const itemCount = bannerMessages.length;
        const scrollDuration = itemCount * 8; // 8 giây cho mỗi item (thay vì 4 giây)
        
        // Cập nhật thời gian animation
        $('.banner-scroll').css('animation-duration', scrollDuration + 's');
    };

    // Khởi tạo banner scroll
    setupBannerScroll();

    // Xử lý dropdown trên mobile
    const handleMobileDropdowns = function() {
        if (isMobile()) {
            $('.dropdown-toggle').off('click.bs.dropdown');
            $('.dropdown-toggle').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).siblings('.dropdown-menu').slideToggle(300);
            });

            // Ngăn dropdown đóng khi click vào bên trong
            $('.dropdown-menu').on('click', function(e) {
                e.stopPropagation();
            });
        } else {
            // Khi chuyển sang desktop, xóa event handler và đặt lại Bootstrap default
            $('.dropdown-toggle').off('click');
            $('.dropdown-menu').off('click');
        }
    };

    // Khởi tạo xử lý mobile dropdown
    handleMobileDropdowns();

    // Cập nhật khi resize window
    $(window).on('resize', function() {
        handleMobileDropdowns();
    });

    // Hiển thị thông báo
    function showNotification(message) {
        const notification = $('<div class="notification"></div>').text(message);
        $('.notification-container').append(notification);
        
        setTimeout(function() {
            notification.remove();
        }, 3000);
    }

    // Demo: Add to cart buttons
    $(document).on('click', '.add-to-cart', function(e) {
        e.preventDefault();
        showNotification('Sản phẩm đã được thêm vào giỏ hàng');
        
        // Cập nhật số lượng giỏ hàng
        let currentCount = parseInt($('.cart-count').text());
        $('.cart-count').text(currentCount + 1);
        
        // Cập nhật trạng thái giỏ hàng
        updateCartSummary();
    });

    // Cập nhật hiển thị giỏ hàng
    function updateCartSummary() {
        const hasItems = parseInt($('.cart-count').text()) > 0;
        
        if (hasItems) {
            $('.empty-cart').hide();
            $('.cart-summary').show();
        } else {
            $('.empty-cart').show();
            $('.cart-summary').hide();
        }
    }

    // Khởi tạo hiển thị giỏ hàng
    updateCartSummary();

    // Xử lý chuyển đổi navbar toggle
    $('.navbar-toggler').on('click', function() {
        $('#navbarMain').slideToggle(300);
    });
    
    // Xử lý header fixed khi scroll
    let lastScrollTop = 0;
    const headerHeight = $('header').outerHeight();
    const bannerHeight = $('.top-banner').outerHeight();
    const totalHeight = headerHeight + bannerHeight;
    
    $(window).on('scroll', function() {
        const scrollTop = $(this).scrollTop();
        
        // Thêm class scrolled khi scroll xuống
        if (scrollTop > bannerHeight) {
            $('header').addClass('scrolled');
        } else {
            $('header').removeClass('scrolled');
        }
        
        // Ẩn/hiện header khi scroll lên/xuống (chỉ áp dụng khi đã scroll quá header)
        if (scrollTop > totalHeight) {
            if (scrollTop > lastScrollTop) {
                // Scroll xuống - ẩn header
                $('header').addClass('header-hidden');
            } else {
                // Scroll lên - hiện header
                $('header').removeClass('header-hidden');
            }
        }
        
        lastScrollTop = scrollTop;
    });
    
    // Xử lý ẩn hiện search box trên mobile khi scroll
    let mobileSearchVisible = true;
    
    $(window).on('scroll', function() {
        if (isMobile()) {
            const scrollTop = $(this).scrollTop();
            
            if (scrollTop > 100 && mobileSearchVisible) {
                $('.mobile-search').slideUp(300);
                mobileSearchVisible = false;
            } else if (scrollTop <= 100 && !mobileSearchVisible) {
                $('.mobile-search').slideDown(300);
                mobileSearchVisible = true;
            }
        }
    });
}); 