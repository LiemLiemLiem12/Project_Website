document.addEventListener('DOMContentLoaded', function() {
    // Initialize product filtering and quick view functionality
    initFilters();
    initQuickView();
    initAddToCart();
});

function initFilters() {
    // Price range filter
    const priceRange = document.getElementById('price-range');
    const priceValue = document.getElementById('price-value');
    
    if (priceRange) {
        priceRange.addEventListener('input', function() {
            if (priceValue) {
                priceValue.textContent = formatCurrency(this.value);
            }
            filterProducts();
        });
    }
    
    // Category filters
    const categoryFilters = document.querySelectorAll('.filter-checkbox');
    categoryFilters.forEach(filter => {
        filter.addEventListener('change', filterProducts);
    });
    
    // Color filters
    const colorOptions = document.querySelectorAll('.color-option');
    colorOptions.forEach(color => {
        color.addEventListener('click', function() {
            this.classList.toggle('selected');
            filterProducts();
        });
    });
    
    // Sort dropdown
    const sortSelect = document.getElementById('sort-by');
    if (sortSelect) {
        sortSelect.addEventListener('change', sortProducts);
    }
}

function filterProducts() {
    // This is a placeholder for actual filtering logic
    // In a real implementation, this would filter products based on selected criteria
    console.log('Filtering products...');
    
    // Example of how to show/hide products based on filters
    // const products = document.querySelectorAll('.product-card');
    // products.forEach(product => {
    //     // Apply filter logic here
    //     // product.style.display = 'none'; // Hide products that don't match filters
    // });
}

function sortProducts() {
    const sortSelect = document.getElementById('sort-by');
    if (!sortSelect) return;
    
    const value = sortSelect.value;
    const productGrid = document.querySelector('.row.product-grid');
    
    if (!productGrid) return;
    
    // Get all product cards
    const products = Array.from(document.querySelectorAll('.product-card').parentNode);
    
    // Sort products based on selected option
    products.sort((a, b) => {
        const productA = a.querySelector('.product-card');
        const productB = b.querySelector('.product-card');
        
        if (!productA || !productB) return 0;
        
        const priceA = getPriceValue(productA.querySelector('.product-price'));
        const priceB = getPriceValue(productB.querySelector('.product-price'));
        
        if (value === 'price-asc') {
            return priceA - priceB;
        } else if (value === 'price-desc') {
            return priceB - priceA;
        }
        
        return 0;
    });
    
    // Re-append sorted products to the grid
    products.forEach(product => {
        productGrid.appendChild(product);
    });
}

function getPriceValue(priceElement) {
    if (!priceElement) return 0;
    
    const priceText = priceElement.textContent;
    return parseInt(priceText.replace(/[^\d]/g, '')) || 0;
}

function initQuickView() {
    const quickViewButtons = document.querySelectorAll('.btn-quickview');
    
    quickViewButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // In a real implementation, this would open a modal with product details
            console.log('Quick view clicked for product');
            
            // Example code to open a modal (would need corresponding HTML)
            // const productId = this.closest('.product-card').dataset.productId;
            // openQuickViewModal(productId);
        });
    });
}

function initAddToCart() {
    const addToCartButtons = document.querySelectorAll('.btn-add-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productCard = this.closest('.product-card') || this.closest('.product-info').closest('.card');
            if (!productCard) return;
            
            // Get product information
            const productTitle = productCard.querySelector('.product-title')?.textContent || 'Product';
            const productPrice = productCard.querySelector('.product-price')?.textContent || '';
            const productImage = productCard.querySelector('.card-img-top')?.getAttribute('src') || '';
            
            // Add to cart (in a real implementation, this would add to a cart object or send to server)
            console.log(`Added to cart: ${productTitle} - ${productPrice}`);
            
            // Show success notification
            showCartNotification(productTitle, productPrice, productImage);
        });
    });
}

function showCartNotification(title, price, image) {
    // Create notification element (in a real implementation)
    // This is just an example and would need corresponding HTML/CSS
    alert(`Product added to cart: ${title} - ${price}`);
}

function formatCurrency(value) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
}
