console.log("Footer.js đã chạy!");

// Kiểm tra xem phần tử có tồn tại không
document.addEventListener("DOMContentLoaded", function() {
    const footer = document.getElementById("footer-content");
    if (footer) {
        footer.style.backgroundColor = "lightblue";
        console.log("Đã đổi màu nền footer!");
    } else {
        console.error("Không tìm thấy #footer-content!");
    }
});

// Footer script
document.addEventListener('DOMContentLoaded', function() {
    // Email validation for newsletter subscription
    const emailInput = document.getElementById('emailSubscribe');
    const btnSubscribe = document.getElementById('btnSubscribe');
    const emailFeedback = document.getElementById('emailFeedback');

    if (btnSubscribe) {
        btnSubscribe.addEventListener('click', function() {
            const email = emailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email)) {
                emailInput.classList.add('is-invalid');
                emailFeedback.style.display = 'block';
            } else {
                emailInput.classList.remove('is-invalid');
                emailInput.classList.add('is-valid');
                emailFeedback.style.display = 'none';
                
                // Here you would typically send the email to your server
                console.log('Subscribing email:', email);
                
                // Show success message
                alert('Cảm ơn bạn đã đăng ký nhận tin!');
                emailInput.value = '';
                emailInput.classList.remove('is-valid');
            }
        });
    }

    // Collapse toggle icon animation
    const chinhSachBtn = document.querySelector('[data-bs-target="#chinhSachList"]');
    const chinhSachIcon = document.getElementById('chinhSachIcon');

    if (chinhSachBtn && chinhSachIcon) {
        chinhSachBtn.addEventListener('click', function() {
            chinhSachIcon.classList.toggle('bi-chevron-up');
            chinhSachIcon.classList.toggle('bi-chevron-down');
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    console.log("Footer script đã chạy!");

    let footer = document.getElementById("footer");
    if (footer) {
        let p = document.createElement("p");
        p.textContent = "© 2025 - Footer đã tải thành công!";
        footer.appendChild(p);
    }
});
    
