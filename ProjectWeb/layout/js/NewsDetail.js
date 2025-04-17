document.addEventListener('DOMContentLoaded', function() {
    // Initialize responsive image handling
    initResponsiveImages();
    
    // Initialize share buttons
    initShareButtons();
    
    // Initialize newsletter form
    initNewsletterForm();
    
    // Initialize reading time
    calculateReadingTime();
    
    // Initialize related articles slider on mobile
    initRelatedArticlesSlider();
    
    // Initialize new functions
    initScrollReveal();
    addScrollToTopButton();
    addTableOfContents();
    addImageZoomEffect();
    fixImagePaths();
    
    // Add CSS for scroll to top button if not already in the CSS file
    addScrollToTopCSS();
});

// Make article images responsive and add lightbox effect
function initResponsiveImages() {
    const articleImages = document.querySelectorAll('.article-content img');
    
    articleImages.forEach(img => {
        // Add classes for responsiveness
        img.classList.add('img-fluid');
        
        // Wrap in a figure element if not already
        if (img.parentNode.tagName !== 'FIGURE') {
            const figure = document.createElement('figure');
            const figcaption = document.createElement('figcaption');
            
            // Get alt text as caption if exists
            if (img.alt && !img.nextElementSibling?.classList.contains('image-caption')) {
                figcaption.textContent = img.alt;
                figcaption.classList.add('figure-caption', 'text-center', 'mt-2');
                figure.appendChild(figcaption);
            }
            
            // Replace img with figure containing img
            img.parentNode.insertBefore(figure, img);
            figure.appendChild(img);
        }
        
        // Add click event for lightbox (if we had one)
        img.style.cursor = 'pointer';
        img.addEventListener('click', function() {
            // Here you would usually open a lightbox, but for simplicity we'll just log
            console.log('Lightbox would open for: ' + this.src);
        });
    });
}

// Social sharing functionality
function initShareButtons() {
    const shareButtons = document.querySelectorAll('.share-icon');
    
    shareButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get article details
            const articleTitle = document.querySelector('.article-title').textContent;
            const articleUrl = window.location.href;
            
            // Different share URLs based on platform
            let shareUrl = '';
            
            if (this.querySelector('.fa-facebook-f')) {
                shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(articleUrl)}`;
            } else if (this.querySelector('.fa-twitter')) {
                shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(articleTitle)}&url=${encodeURIComponent(articleUrl)}`;
            } else if (this.querySelector('.fa-pinterest')) {
                const mainImage = document.querySelector('.article-banner img').src;
                shareUrl = `https://pinterest.com/pin/create/button/?url=${encodeURIComponent(articleUrl)}&media=${encodeURIComponent(mainImage)}&description=${encodeURIComponent(articleTitle)}`;
            }
            
            // Open share dialog
            if (shareUrl) {
                window.open(shareUrl, 'share-window', 'height=450, width=550, top=' + (window.innerHeight / 2 - 225) + ', left=' + (window.innerWidth / 2 - 275));
            }
        });
    });
}

// Newsletter subscription form
function initNewsletterForm() {
    const newsletterForm = document.querySelector('.newsletter-form');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput.value.trim();
            
            if (validateEmail(email)) {
                // Here you would usually send this to your backend
                console.log('Newsletter subscription for: ' + email);
                
                // Show success message
                showFormMessage(newsletterForm, 'Đăng ký thành công! Cảm ơn bạn đã đăng ký nhận tin.', 'success');
                
                // Reset form
                emailInput.value = '';
            } else {
                // Show error message
                showFormMessage(newsletterForm, 'Vui lòng nhập địa chỉ email hợp lệ.', 'danger');
            }
        });
    }
}

// Email validation
function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

// Show form messages
function showFormMessage(form, message, type) {
    // Remove any existing messages
    const existingMessage = form.querySelector('.form-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Create new message
    const messageElement = document.createElement('div');
    messageElement.className = `form-message alert alert-${type} mt-3`;
    messageElement.textContent = message;
    
    // Add to form
    form.appendChild(messageElement);
    
    // Remove after 3 seconds
    setTimeout(() => {
        messageElement.remove();
    }, 3000);
}

// Calculate and display article reading time
function calculateReadingTime() {
    const articleContent = document.querySelector('.article-content');
    
    if (articleContent) {
        // Get all text content
        const text = articleContent.textContent;
        
        // Count words (approximation)
        const wordCount = text.split(/\s+/).length;
        
        // Average reading speed: 200 words per minute
        const readingTime = Math.ceil(wordCount / 200);
        
        // Create reading time element if it doesn't exist
        if (!document.querySelector('.reading-time')) {
            const readingTimeElement = document.createElement('span');
            readingTimeElement.className = 'reading-time';
            readingTimeElement.innerHTML = `<i class="far fa-clock me-2"></i>${readingTime} phút đọc`;
            
            // Insert after article meta
            const articleMeta = document.querySelector('.article-meta');
            if (articleMeta) {
                articleMeta.appendChild(readingTimeElement);
            }
        }
    }
}

// Initialize slider for related articles on mobile
function initRelatedArticlesSlider() {
    // Only activate on mobile
    if (window.innerWidth <= 767) {
        const relatedArticles = document.querySelector('.related-articles .row');
        
        if (relatedArticles) {
            // Add navigation buttons
            const sliderNav = document.createElement('div');
            sliderNav.className = 'slider-nav d-flex justify-content-center mt-3';
            sliderNav.innerHTML = `
                <button class="btn btn-sm btn-outline-dark me-2 prev-btn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-sm btn-outline-dark next-btn">
                    <i class="fas fa-chevron-right"></i>
                </button>
            `;
            
            relatedArticles.parentNode.appendChild(sliderNav);
            
            // Get all article cards
            const articleCards = document.querySelectorAll('.related-article-card');
            const cardContainer = articleCards[0]?.parentNode;
            
            if (cardContainer && articleCards.length > 1) {
                // Show only first card initially
                let currentIndex = 0;
                articleCards.forEach((card, index) => {
                    if (index !== currentIndex) {
                        card.parentNode.style.display = 'none';
                    }
                });
                
                // Previous button click
                sliderNav.querySelector('.prev-btn').addEventListener('click', function() {
                    articleCards[currentIndex].parentNode.style.display = 'none';
                    currentIndex = (currentIndex - 1 + articleCards.length) % articleCards.length;
                    articleCards[currentIndex].parentNode.style.display = 'block';
                });
                
                // Next button click
                sliderNav.querySelector('.next-btn').addEventListener('click', function() {
                    articleCards[currentIndex].parentNode.style.display = 'none';
                    currentIndex = (currentIndex + 1) % articleCards.length;
                    articleCards[currentIndex].parentNode.style.display = 'block';
                });
            }
        }
    }
}

// Scroll to top button (will be added later)
window.addEventListener('scroll', function() {
    // Check if we should show/hide scroll to top button
    if (document.documentElement.scrollTop > 300) {
        // Show button if it exists
        const scrollTopBtn = document.querySelector('.scroll-top-btn');
        if (scrollTopBtn) {
            scrollTopBtn.classList.add('show');
        }
    } else {
        // Hide button if it exists
        const scrollTopBtn = document.querySelector('.scroll-top-btn');
        if (scrollTopBtn) {
            scrollTopBtn.classList.remove('show');
        }
    }
});

// Add scroll reveal animation
function initScrollReveal() {
    // Elements to animate
    const revealElements = [
        '.article-title',
        '.article-banner',
        '.article-content h2',
        '.article-content h3',
        '.article-content p',
        '.article-content .article-image',
        '.article-tags',
        '.article-author',
        '.sidebar-section',
        '.related-article-card',
        '.newsletter-section'
    ];
    
    // Add reveal class to elements
    revealElements.forEach(selector => {
        document.querySelectorAll(selector).forEach((el, index) => {
            el.classList.add('reveal');
            
            // Add data-delay for staggered animation
            el.dataset.delay = index * 0.1;
        });
    });
    
    // Check if elements are in viewport
    checkRevealElements();
    
    // Check on scroll
    window.addEventListener('scroll', function() {
        checkRevealElements();
    });
}

// Check if reveal elements are in viewport
function checkRevealElements() {
    const revealElements = document.querySelectorAll('.reveal');
    
    revealElements.forEach(el => {
        if (isElementInViewport(el)) {
            // Apply delay if available
            const delay = el.dataset.delay || 0;
            setTimeout(() => {
                el.classList.add('active');
            }, delay * 1000);
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

// Add scroll to top button
function addScrollToTopButton() {
    // Create button
    const scrollButton = document.createElement('button');
    scrollButton.className = 'scroll-top-btn';
    scrollButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
    
    // Add to body
    document.body.appendChild(scrollButton);
    
    // Add click event
    scrollButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // Show/hide on scroll (using existing scroll event listener)
}

// Add table of contents
function addTableOfContents() {
    const articleContent = document.querySelector('.article-content');
    const sidebar = document.querySelector('.sidebar');
    
    if (articleContent && sidebar) {
        // Get all headings
        const headings = articleContent.querySelectorAll('h2, h3');
        
        // If we have headings, create TOC
        if (headings.length > 0) {
            // Create TOC container
            const tocSection = document.createElement('div');
            tocSection.className = 'sidebar-section toc-section';
            tocSection.innerHTML = '<h4 class="sidebar-title">Nội dung bài viết</h4>';
            
            // Create TOC list
            const tocList = document.createElement('ul');
            tocList.className = 'toc-list';
            
            // Add headings to TOC
            headings.forEach((heading, index) => {
                // Create ID for heading if it doesn't exist
                if (!heading.id) {
                    heading.id = 'section-' + index;
                }
                
                // Create list item
                const listItem = document.createElement('li');
                listItem.className = heading.tagName === 'H3' ? 'toc-subitem' : 'toc-item';
                
                // Create link
                const link = document.createElement('a');
                link.href = '#' + heading.id;
                link.textContent = heading.textContent;
                
                // Add click event for smooth scrolling
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
                
                // Add to list
                listItem.appendChild(link);
                tocList.appendChild(listItem);
            });
            
            // Add list to TOC
            tocSection.appendChild(tocList);
            
            // Add TOC as first sidebar element
            sidebar.insertBefore(tocSection, sidebar.firstChild);
            
            // Add active class to current section on scroll
            window.addEventListener('scroll', highlightCurrentSection);
        }
    }
}

// Highlight current section in TOC
function highlightCurrentSection() {
    const scrollPosition = window.scrollY + 100; // Offset
    
    // Get all headings
    const headings = document.querySelectorAll('.article-content h2, .article-content h3');
    
    // Find current section
    let currentSection = null;
    
    headings.forEach(heading => {
        if (heading.offsetTop <= scrollPosition) {
            currentSection = heading.id;
        }
    });
    
    // Remove active class from all TOC items
    document.querySelectorAll('.toc-item a, .toc-subitem a').forEach(item => {
        item.classList.remove('active');
    });
    
    // Add active class to current section
    if (currentSection) {
        const activeLink = document.querySelector(`.toc-list a[href="#${currentSection}"]`);
        if (activeLink) {
            activeLink.classList.add('active');
        }
    }
}

// Add image hover zoom effect
function addImageZoomEffect() {
    const images = document.querySelectorAll('.article-image img, .related-article-card img, .recent-post-img');
    
    images.forEach(img => {
        // Wrap image in zoom container if not already
        if (!img.parentNode.classList.contains('zoom-container')) {
            const zoomContainer = document.createElement('div');
            zoomContainer.className = 'zoom-container';
            
            img.parentNode.insertBefore(zoomContainer, img);
            zoomContainer.appendChild(img);
            
            // Add zoom effect class
            img.classList.add('zoom-effect');
        }
    });
}

// Fix image paths
function fixImagePaths() {
    // Banner image
    const bannerImg = document.querySelector('.article-banner img');
    if (bannerImg && bannerImg.src.includes('summer-outfits.jpg')) {
        bannerImg.src = '../upload/img/News/item1.webp';
    }
    
    // Content images
    const contentImages = document.querySelectorAll('.article-content .article-image img');
    contentImages.forEach((img, index) => {
        if (img.src.includes('cotton-clothing.jpg')) {
            img.src = '../upload/img/News/item2.webp';
        } else if (img.src.includes('tshirt-summer.jpg')) {
            img.src = '../upload/img/News/item3.webp';
        } else if (img.src.includes('summer-accessories.jpg')) {
            img.src = '../upload/img/News/item4.webp';
        }
    });
    
    // Recent posts images
    const recentPostImages = document.querySelectorAll('.recent-post-img');
    recentPostImages.forEach((img, index) => {
        img.src = `../upload/img/News/item${5 + index}.webp`;
    });
    
    // Related article images
    const relatedArticleImages = document.querySelectorAll('.related-article-card img');
    relatedArticleImages.forEach((img, index) => {
        img.src = `../upload/img/News/item${8 + index}.webp`;
    });
    
    // Author avatar
    const authorAvatar = document.querySelector('.author-avatar img');
    if (authorAvatar) {
        authorAvatar.src = '../upload/img/News/item12.webp';
    }
    
    // Promo banner
    const promoBanner = document.querySelector('.promo-banner img');
    if (promoBanner) {
        promoBanner.src = '../upload/img/News/item11.webp';
    }
}

// Add CSS for scroll to top button
function addScrollToTopCSS() {
    // Check if we already have style
    if (!document.querySelector('#scroll-top-style')) {
        const style = document.createElement('style');
        style.id = 'scroll-top-style';
        style.textContent = `
            .scroll-top-btn {
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background-color: var(--primary-color);
                color: white;
                border: none;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                opacity: 0;
                transform: translateY(20px);
                transition: opacity 0.3s ease, transform 0.3s ease;
                z-index: 100;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            }
            
            .scroll-top-btn.show {
                opacity: 1;
                transform: translateY(0);
            }
            
            .scroll-top-btn:hover {
                background-color: #333;
            }
            
            .toc-list {
                list-style: none;
                padding-left: 0;
            }
            
            .toc-item {
                margin-bottom: 10px;
            }
            
            .toc-subitem {
                margin-bottom: 5px;
                padding-left: 15px;
            }
            
            .toc-list a {
                display: block;
                padding: 5px 0;
                color: var(--dark-gray);
                border-left: 2px solid transparent;
                padding-left: 10px;
                transition: all 0.3s ease;
            }
            
            .toc-list a:hover, .toc-list a.active {
                color: var(--primary-color);
                border-left: 2px solid var(--primary-color);
                padding-left: 15px;
                font-weight: 500;
            }
        `;
        
        document.head.appendChild(style);
    }
} 