// ProjectWeb/layout/js/Header.js
document.addEventListener('DOMContentLoaded', function () {
    const topBanner = document.querySelector('.top-banner');
    const mainHeader = document.querySelector('header'); // Thẻ <header>
    const mobileSearch = document.querySelector('.mobile-search'); // class .mobile-search
    const mainNav = document.querySelector('.main-nav'); // class .main-nav

    function adjustFixedElementPositions() {
        let currentTopOffset = 0;
        let totalFixedHeightForBodyPadding = 0;

        // 1. Top Banner
        if (topBanner && getComputedStyle(topBanner).display !== 'none') {
            topBanner.style.top = '0px'; // Luôn ở trên cùng
            currentTopOffset += topBanner.offsetHeight;
            totalFixedHeightForBodyPadding += topBanner.offsetHeight;
        }

        // 2. Main Header
        if (mainHeader && getComputedStyle(mainHeader).display !== 'none') {
            mainHeader.style.top = currentTopOffset + 'px';
            currentTopOffset += mainHeader.offsetHeight;
            totalFixedHeightForBodyPadding += mainHeader.offsetHeight;
        }

        // 3. Mobile Search (chỉ hiển thị trên mobile, class d-md-none)
        const isMobileSearchVisible = mobileSearch && window.innerWidth < 768 && getComputedStyle(mobileSearch).display !== 'none';
        if (isMobileSearchVisible) {
            mobileSearch.style.top = currentTopOffset + 'px';
            currentTopOffset += mobileSearch.offsetHeight;
            totalFixedHeightForBodyPadding += mobileSearch.offsetHeight;
        } else if (mobileSearch) {
            // Ẩn nếu không dùng để không ảnh hưởng layout
            // mobileSearch.style.top = '-1000px'; 
        }
        
        // 4. Main Navigation
        if (mainNav && getComputedStyle(mainNav).display !== 'none') {
            mainNav.style.top = currentTopOffset + 'px';
            totalFixedHeightForBodyPadding += mainNav.offsetHeight;
        }

        document.body.style.paddingTop = totalFixedHeightForBodyPadding + 'px';
    }

    // Gọi hàm khi tải trang và khi thay đổi kích thước cửa sổ
    adjustFixedElementPositions();
    window.addEventListener('resize', adjustFixedElementPositions);
    // Có thể gọi lại sau một khoảng trễ nhỏ để đảm bảo các element đã render xong chiều cao
    setTimeout(adjustFixedElementPositions, 250); 

    // Đóng mobile menu khi một link được click (nếu dùng Bootstrap collapse)
    const mainNavbarCollapse = document.getElementById('navbarMain');
    if (mainNavbarCollapse) {
        mainNavbarCollapse.addEventListener('click', function (event) {
            if (event.target.classList.contains('nav-link') || event.target.closest('.nav-link')) {
                let bsCollapse = bootstrap.Collapse.getInstance(mainNavbarCollapse);
                if (bsCollapse && mainNavbarCollapse.classList.contains('show')) {
                    bsCollapse.hide();
                }
            }
        });
    }

    // Xử lý dropdowns (Bootstrap đã tự xử lý qua data attributes, phần này có thể không cần)
    // Ví dụ: đóng dropdown khác khi mở một dropdown
    const dropdownToggles = document.querySelectorAll('.header-action .dropdown-toggle');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('show.bs.dropdown', function () {
            // Đóng tất cả các dropdown khác trong .header-action
            document.querySelectorAll('.header-action .dropdown-menu.show').forEach(openDropdown => {
                if (openDropdown !== this.nextElementSibling) {
                     let instance = bootstrap.Dropdown.getInstance(openDropdown.previousElementSibling);
                     if(instance) instance.hide();
                }
            });
        });
    });

});