// News.js - Handles functionality for the News page

document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    const blogPosts = document.querySelectorAll('.fade-in');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const searchInput = document.getElementById('searchInput');
    
    // Enable fade-in animation on scroll
    animateOnScroll();
    
    // Set up filter buttons
    setupFilterButtons();
    
    // Set up search functionality
    setupSearch();
    
    // Function to handle category filtering
    function setupFilterButtons() {
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Get filter value
                const filterValue = this.getAttribute('data-filter');
                
                // Filter posts
                filterPosts(filterValue);
            });
        });
    }
    
    // Function to filter posts based on category
    function filterPosts(filter) {
        // Get all blog posts
        const posts = document.querySelectorAll('#blogPosts > div');
        
        // Loop through posts
        posts.forEach(post => {
            // Reset animation
            post.classList.remove('fade-in-visible');
            
            // Show/hide based on filter
            if (filter === 'all' || post.getAttribute('data-category') === filter) {
                post.style.display = 'block';
                
                // Add animation with delay
                setTimeout(() => {
                    post.classList.add('fade-in-visible');
                }, 100);
            } else {
                post.style.display = 'none';
            }
        });
    }
    
    // Function to setup search functionality
    function setupSearch() {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            // Get all blog posts
            const posts = document.querySelectorAll('.blog-card');
            
            // Loop through posts
            posts.forEach(post => {
                const title = post.querySelector('.card-title').textContent.toLowerCase();
                const content = post.querySelector('.card-text').textContent.toLowerCase();
                const parent = post.closest('.col-md-6');
                
                // Show/hide based on search term
                if (title.includes(searchTerm) || content.includes(searchTerm)) {
                    parent.style.display = 'block';
                } else {
                    parent.style.display = 'none';
                }
            });
        });
    }
    
    // Function to animate elements on scroll
    function animateOnScroll() {
        // Initial check for elements in viewport
        checkVisibility();
        
        // Check visibility on scroll
        window.addEventListener('scroll', function() {
            checkVisibility();
        });
    }
    
    // Function to check if elements are visible
    function checkVisibility() {
        blogPosts.forEach(post => {
            if (isElementInViewport(post)) {
                // Add visible class with a slight delay for cascading effect
                setTimeout(() => {
                    post.classList.add('fade-in-visible');
                }, 100 * Array.from(blogPosts).indexOf(post) % 3);
            }
        });
    }
    
    // Helper function to check if element is in viewport
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.9 &&
            rect.bottom >= 0
        );
    }
    
    // Function to go back to previous page
    window.goBack = function() {
        window.history.back();
    }
    
    // Add hover effects to blog cards
    const cards = document.querySelectorAll('.blog-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('card-hover');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('card-hover');
        });
    });
});

// Add loading effect for images
window.addEventListener('load', function() {
    // Replace placeholder images with actual images
    const cardImages = document.querySelectorAll('.card-img-top');
    
    cardImages.forEach(img => {
        // Get the image path and update it
        const src = img.getAttribute('src');
        if (src.includes('detailItem2.webp')) {
            // Replace placeholder with an image from our News folder based on its parent index
            const cardIndex = Array.from(document.querySelectorAll('.blog-card')).indexOf(img.closest('.blog-card')) % 9 + 1;
            const newSrc = `../upload/img/News/item${cardIndex}.webp`;
            
            // Create a new image to preload
            const newImg = new Image();
            newImg.onload = function() {
                // Once loaded, update the src
                img.src = newSrc;
                img.classList.add('loaded');
            };
            newImg.src = newSrc;
        }
    });
}); 