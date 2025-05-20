console.log("Footer.js đã chạy!");

// Đảm bảo chỉ chạy khi DOM đã sẵn sàng
document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM đã sẵn sàng - Footer.js");
    
    // Kiểm tra xem phần tử có tồn tại không
    const footer = document.querySelector(".footer");
    if (footer) {
        console.log("Footer đã được tìm thấy!");
        initFooterFunctionality();
    } else {
        console.error("Không tìm thấy footer!");
    }
});

// Tách riêng chức năng của Footer để có thể gọi lại nếu cần
function initFooterFunctionality() {
    // Email validation for newsletter subscription
    const emailInput = document.querySelector('.newsletter-form input[type="email"]');
    const btnSubscribe = document.querySelector('.btn-subscribe');
    let emailFeedback;
    
    // Tạo phần tử phản hồi nếu chưa có
    if (emailInput && !document.getElementById('emailFeedback')) {
        emailFeedback = document.createElement('div');
        emailFeedback.id = 'emailFeedback';
        emailFeedback.className = 'invalid-feedback';
        emailFeedback.textContent = 'Vui lòng nhập email hợp lệ';
        emailFeedback.style.display = 'none';
        emailInput.parentNode.appendChild(emailFeedback);
    } else {
        emailFeedback = document.getElementById('emailFeedback');
    }

    if (btnSubscribe && emailInput) {
        console.log("Đã tìm thấy form đăng ký email");
        
        // Ngăn form submit mặc định
        const newsletterForm = document.querySelector('.newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                validateAndSubmitEmail();
            });
        }
        
        btnSubscribe.addEventListener('click', function(e) {
            e.preventDefault();
            validateAndSubmitEmail();
        });
        
        function validateAndSubmitEmail() {
            const email = emailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email)) {
                emailInput.classList.add('is-invalid');
                if (emailFeedback) emailFeedback.style.display = 'block';
            } else {
                emailInput.classList.remove('is-invalid');
                emailInput.classList.add('is-valid');
                if (emailFeedback) emailFeedback.style.display = 'none';
                
                // Here you would typically send the email to your server
                console.log('Subscribing email:', email);
                
                // Show success message
                alert('Cảm ơn bạn đã đăng ký nhận tin!');
                emailInput.value = '';
                emailInput.classList.remove('is-valid');
            }
        }
    }
    
    console.log("Footer script đã chạy thành công!");
}