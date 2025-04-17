function loadComponent(id, file, script) {
    fetch(file)
        .then(response => response.text())
        .then(data => {
            document.getElementById(id).innerHTML = data;

            // Kiểm tra nếu script đã tồn tại trước đó, xóa nó trước khi thêm mới
            if (script) {
                let oldScript = document.querySelector(`script[src="${script}"]`);
                if (oldScript) {
                    oldScript.remove();
                }

                let scriptTag = document.createElement('script');
                scriptTag.src = script;
                scriptTag.async = true; // Đảm bảo script chạy sau khi thêm vào DOM
                document.body.appendChild(scriptTag);
            }
        })
        .catch(error => console.error(`Lỗi khi tải ${file}:`, error));
}

// Gọi hàm để tải Header và Footer
loadComponent("header", "../../Header.html", "Header.js");
loadComponent("footer", "../../Footer.html", "Footer.js");
