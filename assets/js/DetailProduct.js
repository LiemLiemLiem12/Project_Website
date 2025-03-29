document.addEventListener('DOMContentLoaded', function() {
    // Thumbnail Image Selection
    const thumbnails = document.querySelectorAll('.product-thumbnail');
    const mainImage = document.getElementById('mainProductImage');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            // Remove active class from all thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked thumbnail
            this.classList.add('active');
            
            // Change main image
            mainImage.src = this.src;
        });
    });

    // Add to Cart Toast
    const addToCartBtn = document.getElementById('addToCartBtn');
    const cartToast = new bootstrap.Toast(document.getElementById('cartToast'));

    addToCartBtn.addEventListener('click', function() {
        cartToast.show();
    });
});
function openTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab').forEach(el => el.classList.remove('active'));
    
    document.getElementById(tabId).classList.add('active');
    event.currentTarget.classList.add('active');
}
document.addEventListener("DOMContentLoaded", function () {
    let pricePerItem = 98000; // Giá mỗi sản phẩm
    let quantityInput = document.getElementById("quantityInput");
    let totalPrice = document.getElementById("totalPrice");
    let decreaseBtn = document.getElementById("decreaseQuantity");
    let increaseBtn = document.getElementById("increaseQuantity");

    function updatePrice() {
        let quantity = parseInt(quantityInput.value);
        let total = pricePerItem * quantity;
        totalPrice.innerText = "₫" + total.toLocaleString("vi-VN");
    }

    increaseBtn.addEventListener("click", function () {
        quantityInput.value = parseInt(quantityInput.value) + 1;
        updatePrice();
    });

    decreaseBtn.addEventListener("click", function () {
        if (quantityInput.value > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
            updatePrice();
        }
    });

    // Gọi cập nhật giá khi trang tải
    updatePrice();
});
document.addEventListener('DOMContentLoaded', function() {
    const wrapper = document.getElementById('productsWrapper');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    // Number of visible items
    const visibleItems = 5;
    // Total number of items
    const totalItems = wrapper.children.length;
    // Width of each item (including padding)
    const itemWidth = wrapper.children[0].offsetWidth;
    
    let currentPosition = 0;
    let maxPosition = (totalItems - visibleItems) * itemWidth;

    nextBtn.addEventListener('click', function() {
        if (currentPosition > -maxPosition) {
            currentPosition -= itemWidth;
            wrapper.style.transform = `translateX(${currentPosition}px)`;
        }
    });

    prevBtn.addEventListener('click', function() {
        if (currentPosition < 0) {
            currentPosition += itemWidth;
            wrapper.style.transform = `translateX(${currentPosition}px)`;
        }
    });
});
