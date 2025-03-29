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


    document.addEventListener('DOMContentLoaded', function () {
        const emailInput = document.getElementById('emailSubscribe');
        const btnSubscribe = document.getElementById('btnSubscribe');
        const emailFeedback = document.getElementById('emailFeedback');

        btnSubscribe.addEventListener('click', function () {
            validateEmail();
        });

        emailInput.addEventListener('input', function () {
            emailInput.classList.remove('is-invalid');
            emailFeedback.style.display = 'none';
        });

        function validateEmail() {
            const email = emailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!email || !emailRegex.test(email)) {
                emailInput.classList.add('is-invalid');
                emailFeedback.style.display = 'block';
            } else {
                // Gửi form hoặc gọi API tại đây
                alert('Đăng ký nhận tin thành công!');
                emailInput.value = '';
            }
        }

        // Xử lý chuyển đổi icon khi đóng/mở dropdown
        const chinhSachCollapse = document.getElementById('chinhSachList');
        const chinhSachIcon = document.getElementById('chinhSachIcon');

        // Lắng nghe sự kiện khi dropdown thay đổi trạng thái
        chinhSachCollapse.addEventListener('hide.bs.collapse', function () {
            chinhSachIcon.classList.remove('bi-chevron-up');
            chinhSachIcon.classList.add('bi-chevron-down');
        });

        chinhSachCollapse.addEventListener('show.bs.collapse', function () {
            chinhSachIcon.classList.remove('bi-chevron-down');
            chinhSachIcon.classList.add('bi-chevron-up');
        });
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
    
