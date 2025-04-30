// Mobile menu handling
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    // Toggle menu when button is clicked
    menuToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        // Add overlay when menu is open
        if (sidebar.classList.contains('active')) {
            createOverlay();
        } else {
            removeOverlay();
        }
    });

    // Create overlay
    function createOverlay() {
        let overlay = document.createElement('div');
        overlay.className = 'menu-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        `;
        document.body.appendChild(overlay);
        
        // Close menu when overlay is clicked
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            removeOverlay();
        });
    }

    // Remove overlay
    function removeOverlay() {
        const overlay = document.querySelector('.menu-overlay');
        if (overlay) {
            overlay.remove();
        }
    }

    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!sidebar.contains(event.target) && 
            !menuToggle.contains(event.target) && 
            !event.target.classList.contains('menu-overlay')) {
            sidebar.classList.remove('active');
            removeOverlay();
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 767) {
            sidebar.classList.remove('active');
            removeOverlay();
        }
    });
}); 