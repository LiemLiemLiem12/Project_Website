document.addEventListener("DOMContentLoaded", function () {
    const addToCartButtons = document.querySelectorAll(".add-to-cart");
    const cartNotification = document.getElementById("cart-notification");
    const closeBtn = document.querySelector(".close-btn");

    addToCartButtons.forEach(button => {
        button.addEventListener("click", function () {
            // Lấy thông tin sản phẩm từ item chứa nút này
            const productCard = this.closest(".product-card");
            const productName = productCard.querySelector(".product-title").textContent;
            const productPrice = productCard.querySelector(".sale-price").textContent;
            const productImage = productCard.querySelector(".product-img").src;

            // Cập nhật nội dung thông báo
            document.getElementById("cart-item-name").textContent = productName;
            document.getElementById("cart-item-price").textContent = productPrice;
            document.getElementById("cart-item-image").src = productImage;

            // Hiển thị thông báo với hiệu ứng
            cartNotification.classList.add("show");

            // Tự động ẩn sau 3 giây
            setTimeout(() => {
                cartNotification.classList.remove("show");
            }, 3000);
        });
    });

    // Đóng thông báo khi nhấn nút đóng
    closeBtn.addEventListener("click", function () {
        cartNotification.classList.remove("show");
    });
});
