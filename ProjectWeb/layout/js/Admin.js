document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const closeBtn = document.getElementById('sidebarCloseBtn');

    //fix anh
    // (function() {
    //     // Lưu lại tham chiếu đến modal (thay 'addProductModal' bằng ID modal của bạn)
    //     var modalElement = document.getElementById('addProductModal');
        
    //     // Hàm để đảm bảo tiêu điểm luôn ở trong modal
    //     function enforceFocus() {
    //         // Gỡ bỏ sự kiện focusin nếu đã đăng ký trước đó
    //         document.removeEventListener('focusin', onFocusIn);
    
    //         // Thêm sự kiện focusin để kiểm tra tiêu điểm mỗi khi có thay đổi
    //         document.addEventListener('focusin', onFocusIn);
    //     }
    
    //     // Hàm kiểm tra và xử lý tiêu điểm
    //     function onFocusIn(e) {
    //         // Kiểm tra nếu tiêu điểm không phải là trong modal hoặc CKEditor
    //         if (
    //             modalElement !== e.target && 
    //             !modalElement.contains(e.target) && 
    //             !e.target.closest('.cke_dialog') && 
    //             !e.target.closest('.cke')
    //         ) {
    //             // Đưa tiêu điểm trở lại modal
    //             modalElement.focus();
    //         }
    //     }
    
    //     // Khi modal được mở, gọi hàm enforceFocus
    //     modalElement.style.display = 'block'; // Hoặc sử dụng cách mở modal khác
    //     enforceFocus(); // Đảm bảo tiêu điểm được giữ trong modal
    // })();
    

    setUpCheckbox('Size_M', 'Size_M_quantity');
    setUpCheckbox('Size_L', 'Size_L_quantity');
    setUpCheckbox('Size_XL', 'Size_XL_quantity');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }
    if (closeBtn && sidebar) {
        closeBtn.addEventListener('click', function() {
            sidebar.classList.remove('show');
        });
    }
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 991.98) {
                sidebar.classList.remove('show');
            }
        });
    });
    
    // Close sidebar when clicking outside
    document.addEventListener('click', function(event) {
        const isClickInside = sidebar.contains(event.target) || 
                            (toggleBtn && toggleBtn.contains(event.target));
        
        if (!isClickInside && sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
        }
    });
    
    productSearching()
    addProduct()
    
    // Handle sidebar navigation
    const navLinks = document.querySelectorAll('.nav-links li');
    const contentViews = document.querySelectorAll('.page-content');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Remove active class from all links
            navLinks.forEach(l => l.classList.remove('active'));
            // Add active class to clicked link
            this.classList.add('active');
            
            // Hide all content views
            contentViews.forEach(view => view.style.display = 'none');
            
            // Show selected content view
            const page = this.getAttribute('data-page');
            document.getElementById(`${page}-content`).style.display = 'block';
        });
    });

    // Handle logout
    const logoutBtn = document.querySelector('.logout');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                window.location.href = 'login.html';
            }
        });
    }
    
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Handle "Select All" checkbox
    const selectAll = document.getElementById('select-all');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.order-checkbox, .product-checkbox, .category-checkbox, .customer-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        });
    }

    // Initialize charts if elements exist
    if (document.getElementById('yearlySalesChart')) {
        const yearlySalesCtx = document.getElementById('yearlySalesChart').getContext('2d');
        new Chart(yearlySalesCtx, {
            type: 'line',
            data: {
                labels: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
                datasets: [{
                    label: 'Đơn hàng',
                    data: [65, 78, 90, 85, 92, 110, 120, 130, 125, 132, 145, 150],
                    borderColor: '#6344dd',
                    backgroundColor: 'rgba(99, 68, 221, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    if (document.getElementById('monthlyRevenueChart')) {
        const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(monthlyRevenueCtx, {
            type: 'bar',
            data: {
                labels: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
                datasets: [{
                    label: 'Doanh thu (triệu đồng)',
                    data: [25, 29, 32, 28, 30, 35, 38, 40, 42, 45, 48, 52],
                    backgroundColor: 'rgba(99, 68, 221, 0.7)',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Doanh thu (triệu đồng)'
                        }
                    }
                }
            }
        });
    }
    
    if (document.getElementById('categoryChart')) {
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Áo thun', 'Quần jean', 'Áo khoác', 'Áo sơ mi', 'Phụ kiện'],
                datasets: [{
                    data: [35, 25, 15, 15, 10],
                    backgroundColor: [
                        '#6344dd',
                        '#4ca3ff',
                        '#ff6b6b',
                        '#feca57',
                        '#1dd1a1'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '65%'
            }
        });
    }

    // Check if we're on the AdminOrder page
    if (document.querySelector('.order-checkbox')) {
        initializeAdminOrder();
    }

    // Handle Status Changes
    function updateOrderStatus(orderId, status) {
        console.log(`Updating order ${orderId} to status: ${status}`);
    }

    // Handle Delete Confirmations
    function confirmDelete(type, id) {
        if (confirm(`Bạn có chắc chắn muốn xóa ${type} này?`)) {
            console.log(`Deleting ${type} with ID: ${id}`);
        }
    }

    // Handle Form Submissions
    function handleFormSubmit(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        console.log('Form submitted:', Object.fromEntries(formData));
    }

    // Handle Image Preview
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imagePreview');
                if (preview) {
                    preview.src = e.target.result;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Handle Search
    function handleSearch(searchTerm) {
        console.log('Searching for:', searchTerm);
    }

    // Handle Export Functions
    function exportToExcel() {
        console.log('Exporting to Excel...');
    }

    function exportToPDF() {
        console.log('Exporting to PDF...');
    }

    // --- Product Form Submit ---
    if (document.getElementById('addProductForm')) {
        const form = document.getElementById('addProductForm');
        const countWord = document.getElementById('wordCount');
        
        function countWords(str) {
            // Loại bỏ tất cả thẻ HTML
            let text = str.replace(/<[^>]*>/g, '').trim();
            // Chia văn bản thành mảng từ dựa trên khoảng trắng
            
            return text.length;
        }
        
        CKEDITOR.replace('productDesc', {
            removePlugins: 'image2',            // ❌ Loại bỏ image2
            extraPlugins: 'image',              // ✅ Thêm plugin image cũ
            uploadUrl: '?controller=Adminproduct&action=upload',
            filebrowserImageUploadUrl: '?controller=Adminproduct&action=upload',
            filebrowserUploadMethod: 'form',
            allowedContent: true                // Cho phép tất cả HTML (bao gồm width, height, alt,...)
        });

        CKEDITOR.replace('test2', {
            removePlugins: 'image2',            // ❌ Loại bỏ image2
            extraPlugins: 'image',              // ✅ Thêm plugin image cũ
            uploadUrl: '?controller=Adminproduct&action=upload',
            filebrowserImageUploadUrl: '?controller=Adminproduct&action=upload',
            filebrowserUploadMethod: 'form',
            allowedContent: true                // Cho phép tất cả HTML (bao gồm width, height, alt,...)
        });


        CKEDITOR.instances.productDesc.on('change', function () {
            let content = CKEDITOR.instances.productDesc.getData();
            countWord.textContent = `${countWords(content)}/2000`;
            if (countWords(content) > 2000) {
                countWord.style.setProperty('color', 'red', 'important');
            } else {
                countWord.style.setProperty('color', 'black', 'important'); // Hoặc màu khác tùy thích
            }
        });
        
        // form.addEventListener('submit', function (e) {
        //     e.preventDefault(); // Ngăn gửi mặc định
        //     CKEDITOR.instances.productDesc.updateElement();
        //     const productDesc = document.getElementById('productDesc')
        //     const content = productDesc.value.trim()
        //     const errorDiv = document.getElementById('productDescError');
        //     if (countWords(content) < 10 || countWords(content) > 1000) {
        //         productDesc.setCustomValidity('Mô tả phải từ 10 đến 1000 ký tự');
        //         errorDiv.classList.remove('d-none');
        //       } else {
        //         productDesc.setCustomValidity('');
        //         errorDiv.classList.add('d-none');
        //       }
      
        //   if (!form.checkValidity()) {
        //     // Nếu form không hợp lệ, hiển thị lỗi bằng Bootstrap
        //     form.classList.add('was-validated');
        //     return;
        //   }
      
        //   // ✅ Form hợp lệ → xử lý dữ liệu
        //   const formData = new FormData(this);
      
        // //   for (let [key, value] of formData.entries()) {
        // //     console.log(key, value);
        // //   }
          
        //   fetch('?controller=adminproduct&action=add',{
        //     method: 'POST',
        //     body: formData
        //   })
        //   .then(response => {
        //     if (!response.ok) throw new Error('Thêm thất bại');
        //     return response.text();
        //   })
        //   .then(data => {
        //     console.log(data)
        //     const addProductModal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
        //     if (addProductModal) addProductModal.hide();
        
        //     // Reset form + reset trạng thái validate
        //     this.reset();
        //     this.classList.remove('was-validated');
            
        //     document.getElementById('table-product').innerHTML = data
        //     // Thông báo
        //     alert('✅ Đã thêm sản phẩm thành công');
        //   })
        //   .catch(error => {
        //     console.error('❌ Lỗi gửi dữ liệu:', error);
        //     alert('❌ Lỗi gửi dữ liệu: ' + error.message);
        //   })
        //   // Đóng modal (giả lập)
        // });
      }
      

    // --- Product Checkbox Delete ---
    if (document.querySelectorAll('.product-checkbox').length && document.getElementById('deleteSelectedBtn')) {
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const deleteBtn = document.getElementById('deleteSelectedBtn');
        function updateDeleteBtn() {
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            deleteBtn.disabled = !anyChecked;
        }
        checkboxes.forEach(cb => cb.addEventListener('change', updateDeleteBtn));
        deleteBtn.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa các sản phẩm đã chọn?')) {
                let arrayIDDelete = []
                checkboxes.forEach((element) => {
                    if(element.checked) {
                        arrayIDDelete.push (element.closest('tr').getAttribute('data-id_product'))
                    }
                })

                fetch('?controller=Adminproduct&action=delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(arrayIDDelete)
                }).then(response => response.text())
                .then(data => {
                    document.getElementById('table-product').innerHTML = data
                })
                .catch(error => {
                    alert("Lỗi khi xóa sản phẩm: " + error)
                })
                updateDeleteBtn();
            }
        });
    }

    // --- Product Image Preview ---
    if (document.getElementById('productImage') && document.getElementById('imagePreview')) {
        document.getElementById('productImage').addEventListener('change', function(event) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            const files = event.target.files;
            if (files) {
                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'rounded border';
                            img.style.height = '60px';
                            img.style.width = '60px';
                            img.style.objectFit = 'cover';
                            img.style.marginRight = '8px';
                            preview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    }

    // --- Product Tag Input ---
    if (document.getElementById('tagInput') && document.getElementById('tagInputContainer') && document.getElementById('productTags')) {
        const tagInput = document.getElementById('tagInput');
        const tagInputContainer = document.getElementById('tagInputContainer');
        const productTags = document.getElementById('productTags');
        let tags = [];
        function renderTags() {
            tagInputContainer.querySelectorAll('.tag-item').forEach(e => e.remove());
            tags.forEach((tag, idx) => {
                const tagEl = document.createElement('span');
                tagEl.className = 'tag-item';
                tagEl.textContent = tag;
                const removeBtn = document.createElement('span');
                removeBtn.className = 'remove-tag';
                removeBtn.textContent = '×';
                removeBtn.onclick = () => {
                    tags.splice(idx, 1);
                    renderTags();
                };
                tagEl.appendChild(removeBtn);
                tagInputContainer.insertBefore(tagEl, tagInput);
            });
            productTags.value = tags.join(',');
        }
        tagInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && this.value.trim() !== '') {
                e.preventDefault();
                const newTag = this.value.trim();
                if (!tags.includes(newTag)) {
                    tags.push(newTag);
                    renderTags();
                }
                this.value = '';
            } else if (e.key === 'Backspace' && this.value === '' && tags.length > 0) {
                tags.pop();
                renderTags();
            }
        });
    }

    // --- Product Double Click Row ---
    // if (document.querySelectorAll('.product-table tbody tr.product-row').length) {
    //     document.querySelectorAll('.product-table tbody tr.product-row').forEach(function(row) {
    //         row.addEventListener('dblclick', function() {
    //             // Lấy dữ liệu từ data-attribute
    //             document.getElementById('detailCode').textContent = row.getAttribute('data-id_product') || '';
    //             document.getElementById('detailName').textContent = row.getAttribute('data-name') || '';
    //             document.getElementById('detailCategory').textContent = row.getAttribute('data-id_category') || '';
    //             document.getElementById('detailOriginalPrice').textContent = row.getAttribute('data-original_price') || '';
    //             document.getElementById('detailCurrentPrice').textContent = row.getAttribute('data-current_price') || '';
    //             document.getElementById('detailDiscount').textContent = row.getAttribute('data-discount_percent') ? '-' + row.getAttribute('data-discount_percent') + '%' : '';
    //             document.getElementById('detailStock').textContent = row.getAttribute('data-stock') || '';
    //             document.getElementById('detailStatus').textContent = row.getAttribute('data-hide') === "0" ? "Còn hàng" : "Hết hàng";
    //             document.getElementById('detailClick').textContent = row.getAttribute('data-click_count') || '';
    //             document.getElementById('detailCreated').textContent = row.getAttribute('data-created_at') || '';
    //             document.getElementById('detailUpdated').textContent = row.getAttribute('data-updated_at') || '';
    //             document.getElementById('detailLink').textContent = row.getAttribute('data-link') || '';
    //             document.getElementById('detailMeta').textContent = row.getAttribute('data-meta') || '';
    //             document.getElementById('detailOrder').textContent = row.getAttribute('data-order') || '';
    //             document.getElementById('detailTags').textContent = row.getAttribute('data-tags') || '';
    //             document.getElementById('detailPolicyReturn').src = row.getAttribute('data-policy_return') || '';
    //             document.getElementById('detailPolicyWarranty').src = row.getAttribute('data-policy_warranty') || '';
    //             document.getElementById('detailDesc').innerHTML = row.getAttribute('data-description') || '';
    //             document.getElementById('detailImage').src = row.getAttribute('data-main_image') || '';
    //             // Gallery ảnh phụ
    //             const gallery = document.getElementById('detailGallery');
    //             gallery.innerHTML = '';
    //             const imgs = (row.getAttribute('data-img') || '').split(',');
    //             imgs.forEach(src => {
    //                 if (src.trim()) {
    //                     const img = document.createElement('img');
    //                     img.src = src.trim();
    //                     img.className = 'rounded border';
    //                     img.style.height = '50px';
    //                     img.style.width = '50px';
    //                     img.style.objectFit = 'cover';
    //                     img.style.marginRight = '6px';
    //                     gallery.appendChild(img);
    //                 }
    //             });
    //             // Hiện modal
    //             var modal = new bootstrap.Modal(document.getElementById('productDetailModal'));
    //             modal.show();
    //         });
    //     });
    // }

    // AdminOrder specific functions
    function initializeAdminOrder() {
        // Initialize multiple delete functionality
        const orderCheckboxes = document.querySelectorAll('.order-checkbox');
        const deleteOrderBtn = document.getElementById('deleteSelectedOrderBtn');
        
        if (orderCheckboxes.length > 0 && deleteOrderBtn) {
            // Update delete button state based on checkbox selection
            function updateDeleteOrderBtn() {
                const anyChecked = Array.from(orderCheckboxes).some(cb => cb.checked);
                deleteOrderBtn.disabled = !anyChecked;
            }

            // Add event listeners to checkboxes
            orderCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateDeleteOrderBtn);
            });

            // Handle delete button click
            deleteOrderBtn.addEventListener('click', function() {
                // Lấy tất cả checkbox đơn hàng được chọn
                const checkedOrderCheckboxes = document.querySelectorAll('.order-checkbox:checked');
                const selectedOrders = Array.from(checkedOrderCheckboxes).map(cb => cb.getAttribute('data-id'));
                if (selectedOrders.length === 0) {
                    alert('Vui lòng chọn ít nhất một đơn hàng để xóa!');
                    return;
                }
                if (confirm('Bạn có chắc chắn muốn xóa các đơn hàng đã chọn?')) {
                    // Demo: Xóa trên giao diện
                    selectedOrders.forEach(id => {
                        const row = document.querySelector(`tr[data-order-id="${id}"]`);
                        if (row) row.remove();
                    });
                    // TODO: Gửi request xóa về backend nếu cần
                    alert('Đã xóa các đơn hàng đã chọn (demo, cần kết nối backend để thực hiện xóa thực tế)');
                }
            });
        }

        // Initialize view order details functionality
        const viewButtons = document.querySelectorAll('.btn-view-order');
        if (viewButtons.length > 0) {
            viewButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    // Đảo trạng thái hide
                    let hide = btn.getAttribute('data-hide');
                    hide = hide === '1' ? '0' : '1';
                    btn.setAttribute('data-hide', hide);
                    // Đổi icon và style
                    const icon = btn.querySelector('i');
                    if (hide === '1') {
                        btn.classList.add('hide');
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        btn.classList.remove('hide');
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
                // Khởi tạo đúng trạng thái khi load trang
                const icon = btn.querySelector('i');
                if (btn.getAttribute('data-hide') === '1') {
                    btn.classList.add('hide');
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    btn.classList.remove('hide');
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }
    }

    // Function to populate order details modal
    function populateOrderDetailsModal(order) {
        // Update modal content with order details
        document.getElementById('orderId').textContent = order.id;
        document.getElementById('orderDate').textContent = order.created_at;
        document.getElementById('customerName').textContent = order.customer_name;
        document.getElementById('customerEmail').textContent = order.customer_email;
        document.getElementById('orderStatus').textContent = order.status;
        document.getElementById('orderTotal').textContent = order.total_amount;
        
        // Populate order items table
        const itemsTable = document.getElementById('orderItemsTable');
        itemsTable.innerHTML = ''; // Clear existing content
        
        order.items.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.product_name}</td>
                <td>${item.quantity}</td>
                <td>${item.price}</td>
                <td>${item.subtotal}</td>
            `;
            itemsTable.appendChild(row);
        });
    }

    // --- AdminOrder Double Click Row ---
    if (document.querySelector('tr[data-order-id]')) {
        document.querySelectorAll('tr[data-order-id]').forEach(function(row) {
            row.addEventListener('dblclick', function() {
                // Lấy thông tin từ các ô trong dòng
                const orderId = row.getAttribute('data-order-id');
                const tds = row.querySelectorAll('td');
                // Demo dữ liệu chi tiết
                document.getElementById('orderId').textContent = orderId;
                document.getElementById('orderDate').textContent = tds[3]?.textContent.trim() || '';
                document.getElementById('orderStatus').textContent = tds[6]?.textContent.trim() || '';
                document.getElementById('orderTotal').textContent = tds[4]?.textContent.trim() || '';
                // Thông tin khách hàng (demo)
                document.getElementById('customerName').textContent = tds[2]?.textContent.trim() || '';
                document.getElementById('customerEmail').textContent = 'khachhang@email.com';
                document.getElementById('customerPhone').textContent = '0901234567';
                document.getElementById('customerAddress').textContent = '123 Đường ABC, Quận 1, TP.HCM';
                // Thông tin thanh toán (demo)
                document.getElementById('paymentMethod').textContent = tds[5]?.textContent.trim() || '';
                document.getElementById('orderNote').textContent = 'Giao giờ hành chính';
                // Sản phẩm đặt (demo)
                const itemsTable = document.getElementById('orderItemsTable');
                itemsTable.innerHTML = `
                    <tr>
                        <td>Áo thun nam</td>
                        <td>2</td>
                        <td>250,000đ</td>
                        <td>500,000đ</td>
                    </tr>
                    <tr>
                        <td>Quần jeans nữ</td>
                        <td>1</td>
                        <td>499,000đ</td>
                        <td>499,000đ</td>
                    </tr>
                `;
                // Hiện modal
                var modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
                modal.show();
            });
        });
    }

    // --- Customer Checkbox Delete ---
    if (document.getElementById('deleteSelectedCustomerBtn')) {
        const deleteBtn = document.getElementById('deleteSelectedCustomerBtn');
        const checkboxes = document.querySelectorAll('.customer-checkbox');
        function updateDeleteBtn() {
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            deleteBtn.disabled = !anyChecked;
        }
        checkboxes.forEach(cb => cb.addEventListener('change', updateDeleteBtn));
        deleteBtn.addEventListener('click', function() {
            const checked = Array.from(checkboxes).filter(cb => cb.checked);
            if (checked.length === 0) {
                alert('Vui lòng chọn ít nhất một khách hàng để xóa!');
                return;
            }
            if (confirm('Bạn có chắc chắn muốn xóa các khách hàng đã chọn?')) {
                checked.forEach(cb => {
                    const row = cb.closest('tr[data-customer-id]');
                    if (row) row.remove();
                });
                alert('Đã xóa các khách hàng đã chọn (demo, cần kết nối backend để thực hiện xóa thực tế)');
                updateDeleteBtn();
            }
        });
        // Chọn tất cả
        const selectAll = document.getElementById('select-all-customer');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateDeleteBtn();
            });
        }
    }

    // --- Customer Eye Button Effect ---
    if (document.querySelector('.btn-view-customer')) {
        document.querySelectorAll('.btn-view-customer').forEach(function(btn) {
            btn.addEventListener('click', function() {
                let hide = btn.getAttribute('data-hide');
                hide = hide === '1' ? '0' : '1';
                btn.setAttribute('data-hide', hide);
                const icon = btn.querySelector('i');
                if (hide === '1') {
                    btn.classList.add('hide');
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    btn.classList.remove('hide');
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
            // Khởi tạo đúng trạng thái khi load trang
            const icon = btn.querySelector('i');
            if (btn.getAttribute('data-hide') === '1') {
                btn.classList.add('hide');
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                btn.classList.remove('hide');
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }

    // --- Customer Add/Edit Modal Logic ---
    if (document.getElementById('customers-content')) {
        // Mở modal khi nhấn Thêm khách hàng
        const addBtn = document.getElementById('addCustomerBtn');
        if (addBtn) {
            addBtn.addEventListener('click', function() {
                document.getElementById('customerModalLabel').textContent = 'Thêm khách hàng';
                document.getElementById('customerForm').reset();
                document.getElementById('customerId').value = '';
                document.getElementById('customerPasswordInput').required = true;
                var modal = new bootstrap.Modal(document.getElementById('customerModal'));
                modal.show();
            });
        }
        // Mở modal khi nhấn chỉnh sửa
        document.querySelectorAll('.btn-edit-customer').forEach(function(editBtn) {
            editBtn.addEventListener('click', function() {
                const row = editBtn.closest('tr[data-customer-id]');
                if (!row) return;
                document.getElementById('customerModalLabel').textContent = 'Chỉnh sửa khách hàng';
                document.getElementById('customerId').value = row.getAttribute('data-customer-id') || '';
                document.getElementById('customerNameInput').value = row.children[1]?.textContent.trim() || '';
                document.getElementById('customerPhoneInput').value = row.children[2]?.textContent.trim() || '';
                document.getElementById('customerEmailInput').value = row.children[3]?.textContent.trim() || '';
                document.getElementById('customerPasswordInput').value = '';
                document.getElementById('customerPasswordInput').required = false;
                // Các trường khác nếu có (address, role, hide) có thể lấy từ data-attribute hoặc backend nếu cần
                var modal = new bootstrap.Modal(document.getElementById('customerModal'));
                modal.show();
            });
        });
        // Xử lý submit (demo)
        document.getElementById('customerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Đã lưu thông tin khách hàng (demo, cần kết nối backend để lưu thực tế)');
            var modal = bootstrap.Modal.getInstance(document.getElementById('customerModal'));
            modal.hide();
        });
    }

    // --- Product Edit Button Logic ---
    // if (document.querySelector('.product-table')) {
    //     document.querySelectorAll('.btn-edit').forEach(function(editBtn) {
    //         editBtn.addEventListener('click', function() {
    //             const row = editBtn.closest('tr.product-row');
    //             if (!row) return;
    //             document.getElementById('addProductModalLabel').textContent = 'Chỉnh sửa Sản Phẩm';
    //             document.getElementById('productName').value = row.getAttribute('data-name') || '';
    //             document.getElementById('productCategory').value = row.getAttribute('data-id_category') || '';
    //             document.getElementById('productPrice').value = row.getAttribute('data-current_price') || '';
    //             if (window.CKEDITOR && CKEDITOR.instances['productDesc']) {
    //                 CKEDITOR.instances['productDesc'].setData(row.getAttribute('data-description') || '');
    //             }
    //             // ... fill các trường khác nếu có ...
    //             var modal = new bootstrap.Modal(document.getElementById('addProductModal'));
    //             modal.show();
    //         });
    //     });
    // }

    document.querySelector('.product-table').addEventListener('dblclick', function(e) {
        const row = e.target.closest('tr.product-row')

        function decodeHtmlEntities(encodedString) {
            const tempDiv = document.createElement("div");
            tempDiv.innerHTML = encodedString;
            return tempDiv.textContent || tempDiv.innerText;
        }

        if(row) {
            document.getElementById('detailCode').textContent = row.getAttribute('data-id_product') || '';
            document.getElementById('detailName').textContent = row.getAttribute('data-name') || '';
            document.getElementById('detailCategory').textContent = row.getAttribute('data-id_category') || '';
            document.getElementById('detailOriginalPrice').textContent = row.getAttribute('data-original_price') || '';
            document.getElementById('detailCurrentPrice').textContent = row.getAttribute('data-current_price') || '';
            document.getElementById('detailDiscount').textContent = row.getAttribute('data-discount_percent') ? '-' + row.getAttribute('data-discount_percent') + '%' : '';
            document.getElementById('detailStock').textContent = row.getAttribute('data-stock') || '';
            document.getElementById('M_quantity').textContent = row.getAttribute('data-M-quantity') || 0;
            document.getElementById('L_quantity').textContent = row.getAttribute('data-L-quantity') || 0;
            document.getElementById('XL_quantity').textContent = row.getAttribute('data-XL-quantity') || 0;
            document.getElementById('detailStatus').textContent = row.getAttribute('data-hide') === "0" ? "Còn hàng" : "Hết hàng";
            document.getElementById('detailClick').textContent = row.getAttribute('data-click_count') || '';
            document.getElementById('detailCreated').textContent = row.getAttribute('data-created_at') || '';
            document.getElementById('detailUpdated').textContent = row.getAttribute('data-updated_at') || '';
            document.getElementById('detailLink').textContent = row.getAttribute('data-link') || '';
            document.getElementById('detailMeta').textContent = row.getAttribute('data-meta') || '';
            document.getElementById('detailOrder').textContent = row.getAttribute('data-order') || '';
            document.getElementById('detailTags').textContent = row.getAttribute('data-tags') || '';
            document.getElementById('detailPolicyReturn').src = row.getAttribute('data-policy_return') || '';
            document.getElementById('detailPolicyWarranty').src = row.getAttribute('data-policy_warranty') || '';
            document.getElementById('detailDesc').textContent = decodeURIComponent(escape(atob(row.getAttribute('data-description')))) || '';
            document.getElementById('detailImage').src = row.getAttribute('data-main_image') || '';
            // Gallery ảnh phụ
            const gallery = document.getElementById('detailGallery');
            gallery.innerHTML = '';
            const imgs = (row.getAttribute('data-img') || '').split(',');
            imgs.forEach(src => {
                if (src.trim()) {
                    const img = document.createElement('img');
                    img.src = src.trim();
                    img.className = 'rounded border';
                    img.style.height = '50px';
                    img.style.width = '50px';
                    img.style.objectFit = 'cover';
                    img.style.marginRight = '6px';
                    gallery.appendChild(img);
                }
            });
            // Hiện modal
            var modal = new bootstrap.Modal(document.getElementById('productDetailModal'));
            modal.show();
        }
    })

    document.querySelector('.product-table').addEventListener('click', function(e) {
        const btnView = e.target.closest('.btn-view')
        const btnEdit = e.target.closest('.btn-edit')
        const checkboxAll = e.target.closest('#select-all')
        const tagInputContainer = document.getElementById('tagInputContainer');
        const tagInput = document.getElementById('tagInput');
        
        function renderTags() {
            tagInputContainer.querySelectorAll('.tag-item').forEach(e => e.remove());
            tags.forEach((tag, idx) => {
                const tagEl = document.createElement('span');
                tagEl.className = 'tag-item';
                tagEl.textContent = tag;
                const removeBtn = document.createElement('span');
                removeBtn.className = 'remove-tag';
                removeBtn.textContent = '×';
                removeBtn.onclick = () => {
                    tags.splice(idx, 1);
                    renderTags();
                };
                tagEl.appendChild(removeBtn);
                tagInputContainer.insertBefore(tagEl, tagInput);
            });
            productTags.value = tags.join(',');
        }

        if(btnView) {
            var row = btnView.closest('tr');
            if (!row) return;
    
            // Toggle trạng thái ẩn/hiện
            var isHidden = row.classList.toggle('product-hidden');
            // Đổi icon mắt
            var icon = btnView.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            }
            // Đổi trạng thái text nếu muốn
            var statusCell = row.querySelector('.status');
            if (statusCell) {
                if (isHidden) {
                    statusCell.textContent = 'Đã ẩn';
                    statusCell.className = 'status cancelled';
                } else {
                    statusCell.textContent = 'Còn hàng';
                    statusCell.className = 'status completed';
                }
            }
        }else if(btnEdit) {
            const row = btnEdit.closest('tr.product-row');
            const form = document.getElementById('addProductForm')
            const Size_M = document.getElementById('Size_M')
            const Size_L = document.getElementById('Size_L')
            const Size_XL = document.getElementById('Size_XL')
            var Size_M_quantity = document.getElementById('Size_M_quantity')
            var Size_L_quantity = document.getElementById('Size_L_quantity')
            var Size_XL_quantity = document.getElementById('Size_XL_quantity')

            

            Size_M.checked = true
            Size_L.checked = true
            Size_XL.checked = true

            Size_M_quantity.disabled = false
            Size_L_quantity.disabled = false
            Size_XL_quantity.disabled = false

            
            var tags = []
            if (!row) return;
            document.getElementById('addProductModalLabel').textContent = 'Chỉnh sửa Sản Phẩm';
            document.getElementById('productName').value = row.getAttribute('data-name') || '';
            document.getElementById('productCategory').value = row.getAttribute('id_category') || '';
            document.getElementById('productPrice').value = parseInt((row.getAttribute('data-current_price')).replace(/[đ.]/g, '')) || 0;
            document.getElementById('productStock').value = parseInt(row.getAttribute('data-stock')) || 0;
            document.getElementById('productTags').value = row.getAttribute('data-tags') || '';
            Size_M_quantity.value = row.getAttribute('data-M-quantity')
            Size_L_quantity.value = row.getAttribute('data-L-quantity')
            Size_XL_quantity.value = row.getAttribute('data-XL-quantity')
            tags = row.getAttribute('data-tags').replace(/\s+/g, '').replace(/-/g, ' ').split(',')
            renderTags()

            let dataImg = row.getAttribute('data-img')
            let toArray = dataImg.split(',')
            arrayImg = [
                row.getAttribute('data-main_image'),
                toArray[0], 
                toArray[1]
            ]
            
            const preview = document.getElementById('imagePreview')
            preview.innerHTML = ''
            arrayImg.forEach(source => {
                const img = document.createElement('img');
                            img.src = source;
                            img.className = 'rounded border';
                            img.style.height = '60px';
                            img.style.width = '60px';
                            img.style.objectFit = 'cover';
                            img.style.marginRight = '8px';
                            preview.appendChild(img);
            });
            const tmpPreview = preview.innerHTML;
            if (window.CKEDITOR && CKEDITOR.instances['productDesc']) {
                const base64 = row.getAttribute('data-description') || '';
                const decoded = decodeURIComponent(escape(atob(base64)));
                CKEDITOR.instances['productDesc'].setData(decoded);
            }

            document.getElementById('productImage').removeAttribute('required')
            document.getElementById('policyWarranty').removeAttribute('required')
            document.getElementById('policyReturn').removeAttribute('required')


            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Ngăn gửi mặc định
                CKEDITOR.instances.productDesc.updateElement();
                const productDesc = document.getElementById('productDesc')
                const content = productDesc.value.trim()

                const size_Validator = document.getElementById('size_total_validator')
                Size_M_quantity = document.getElementById('Size_M_quantity')
                Size_L_quantity = document.getElementById('Size_L_quantity')
                Size_XL_quantity = document.getElementById('Size_XL_quantity')
                const size_Feedback = size_Validator.nextElementSibling; // Lấy div.invalid-feedback kế tiếp
                const totalSizeQuantity = 
                    Number(Size_M_quantity.value) +
                    Number(Size_L_quantity.value) +
                    Number(Size_XL_quantity.value);            // Kiểm tra xem có ít nhất một size được chọn không
                const anySizeChecked = Size_M.checked || Size_L.checked || Size_XL.checked;
                let productStock = Number(document.getElementById('productStock').value);
                if (!anySizeChecked) {
                    size_Validator.setCustomValidity('Vui lòng chọn ít nhất một size');
                    size_Feedback.textContent = 'Vui lòng chọn ít nhất một size';
                } else if (totalSizeQuantity !== productStock) {
                    console.log(totalSizeQuantity + " " + productStock)
                    size_Validator.setCustomValidity('Tổng số lượng các size phải bằng tồn kho tổng');
                    size_Feedback.textContent = 'Tổng số lượng các size phải bằng tồn kho tổng';
                } else {
                    size_Validator.setCustomValidity('');
                    size_Feedback.textContent = '';
                }
                const image = document.getElementById('productImage')
                const files = image.files;
                const isValid = files.length === 3 && [...files].every(file => file.type.startsWith('image/'));
                if(tmpPreview != document.getElementById('imagePreview').innerHTML) {
                    if (!isValid) {
                        image.setCustomValidity('Bạn phải chọn đúng 3 ảnh');
                      } else {
                        image.setCustomValidity(''); 
                      }
                }else {
                    image.setCustomValidity('');
                }
                const errorDiv = document.getElementById('productDescError');
                if (countWords(content) < 10 || countWords(content) > 1000) {
                    productDesc.setCustomValidity('Mô tả phải từ 10 đến 1000 ký tự');
                    errorDiv.classList.remove('d-none');
                  } else {
                    productDesc.setCustomValidity('');
                    errorDiv.classList.add('d-none');
                  }
          
              if (!form.checkValidity()) {
                // Nếu form không hợp lệ, hiển thị lỗi bằng Bootstrap
                form.classList.add('was-validated');
                return;
              }
          
              // ✅ Form hợp lệ → xử lý dữ liệu
              const formData = new FormData(this);
                const arrayImgFile = [];

                async function createFileFromImageUrl(url) {
                    const response = await fetch(url);
                    const blob = await response.blob();
                    const fileName = url.split('/').pop();
                    const file = new File([blob], fileName, { type: blob.type });
                    return file;
                }

                const processSubmit = async () => {
                if (document.getElementById('policyReturn').files.length == 0) {
                    const returnFile = await createFileFromImageUrl(
                        row.getAttribute('data-policy_return')
                    );
                    formData.set('policyReturn', returnFile);
                }

                if (document.getElementById('policyWarranty').files.length == 0) {
                    const warrantyFile = await createFileFromImageUrl(
                        row.getAttribute('data-policy_warranty')
                    );
                    formData.set('policyWarranty', warrantyFile);
                }

                // Xử lý các ảnh phụ
                await Promise.all(
                    arrayImg.map(async (url) => {
                    const response = await fetch(url);
                    const blob = await response.blob();
                    const fileName = url.split('/').pop();
                    const file = new File([blob], fileName, { type: blob.type });
                    arrayImgFile.push(file);
                    })
                );

                if (image.files.length == 0) {
                    arrayImgFile.forEach(file => {
                    formData.append('productImage[]', file);
                    });
                }

                formData.append('productID', row.getAttribute('data-id_product'));

                // Kiểm tra dữ liệu
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }

                // Gửi dữ liệu
                fetch('?controller=adminproduct&action=edit', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`Sửa thất bại: ${text}`);
                    });
                    }
                    return response.text();
                })
                .then(data => {
                    console.log(data);
                    const addProductModal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
                    if (addProductModal) addProductModal.hide();

                    this.reset();
                    this.classList.remove('was-validated');
                    document.getElementById('table-product').innerHTML = data;

                    alert('✅ Đã sửa sản phẩm thành công');
                })
                .catch(error => {
                    console.error('❌ Lỗi gửi dữ liệu:', error);
                    alert('❌ Lỗi gửi dữ liệu: ' + error.message);
                });
                };

                // Gọi hàm xử lý chính
                processSubmit();

              
              
              
              
            });
            // ... fill các trường khác nếu có ...
            var modal = new bootstrap.Modal(document.getElementById('addProductModal'));
            modal.show();
        }else if(checkboxAll) {
            const checkboxes = document.querySelectorAll('.order-checkbox, .product-checkbox, .category-checkbox, .customer-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = checkboxAll.checked;
            });
            if(document.getElementById('deleteSelectedBtn').disabled == true) {
                document.getElementById('deleteSelectedBtn').disabled = false
            }else {
                document.getElementById('deleteSelectedBtn').disabled = true
            }
        }
    });

    
      

    function addProduct() {
        document.getElementById('btn-add-product').addEventListener('click', function () {
            const modal = document.getElementById('addProductModal');
            const form = document.getElementById('addProductForm');
            const descError = document.getElementById('productDescError')
            document.getElementById('productImage').setAttribute('required', '')
            document.getElementById('policyWarranty').setAttribute('required', '')
            document.getElementById('policyReturn').setAttribute('required', '')

            const inputQuantity = document.querySelectorAll('input[type="number"][id^="Size_"]');
            inputQuantity.forEach(element => {
                element.value = '';
                element.disabled = true;
            })
            descError.classList.add('d-none')
            form.classList.remove('was-validated')

            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Ngăn gửi mặc định
                CKEDITOR.instances.productDesc.updateElement();
                const productDesc = document.getElementById('productDesc')
                const content = productDesc.value.trim()
                const errorDiv = document.getElementById('productDescError');

                const image = document.getElementById('productImage')
                const files = image.files;

                const Size_M = document.getElementById('Size_M');
                const Size_L = document.getElementById('Size_L');
                const Size_XL = document.getElementById('Size_XL');

                const Size_M_quantity = Size_M.checked ? parseInt(document.getElementById('Size_M_quantity').value) || 0 : 0;
                const Size_L_quantity = Size_L.checked ? parseInt(document.getElementById('Size_L_quantity').value) || 0 : 0;
                const Size_XL_quantity = Size_XL.checked ? parseInt(document.getElementById('Size_XL_quantity').value) || 0 : 0;
                totalSizeQuantity = Size_M_quantity + Size_L_quantity + Size_XL_quantity
                const productStock = parseInt(document.getElementById('productStock').value) || 0;

                const size_Validator = document.getElementById('size_total_validator')

                const size_Feedback = size_Validator.nextElementSibling; // Lấy div.invalid-feedback kế tiếp
            
                // Kiểm tra xem có ít nhất một size được chọn không
                const anySizeChecked = Size_M.checked || Size_L.checked || Size_XL.checked;
                
                if (!anySizeChecked) {
                    size_Validator.setCustomValidity('Vui lòng chọn ít nhất một size');
                    size_Feedback.textContent = 'Vui lòng chọn ít nhất một size';
                } else if (totalSizeQuantity !== productStock) {
                    size_Validator.setCustomValidity('Tổng số lượng các size phải bằng tồn kho tổng');
                    size_Feedback.textContent = 'Tổng số lượng các size phải bằng tồn kho tổng';
                } else {
                    size_Validator.setCustomValidity('');
                    size_Feedback.textContent = '';
                }


                const isValid = files.length === 3 && [...files].every(file => file.type.startsWith('image/'));
                if (!isValid) {
                    image.setCustomValidity('Bạn phải chọn đúng 3 ảnh');
                  } else {
                    image.setCustomValidity(''); 
                  }
                  
                if (countWords(content) < 10 || countWords(content) > 1000) {
                    productDesc.setCustomValidity('Mô tả phải từ 10 đến 1000 ký tự');
                    errorDiv.classList.remove('d-none');
                  } else {
                    productDesc.setCustomValidity('');
                    errorDiv.classList.add('d-none');
                  }
          
              if (!form.checkValidity()) {
                // Nếu form không hợp lệ, hiển thị lỗi bằng Bootstrap
                form.classList.add('was-validated');
                return;
              }
          
              // ✅ Form hợp lệ → xử lý dữ liệu
              const formData = new FormData(this);
          
            //   for (let [key, value] of formData.entries()) {
            //     console.log(key, value);
            //   }
              
              fetch('?controller=adminproduct&action=add',{
                method: 'POST',
                body: formData
              })
              .then(response => {
                if (!response.ok) throw new Error('Thêm thất bại');
                return response.text();
              })
              .then(data => {
                const addProductModal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
                if (addProductModal) addProductModal.hide();
            
                // Reset form + reset trạng thái validate
                this.reset();
                this.classList.remove('was-validated');
                
                document.getElementById('table-product').innerHTML = data
                // Thông báo
                alert('✅ Đã thêm sản phẩm thành công');
              })
              .catch(error => {
                console.error('❌ Lỗi gửi dữ liệu:', error);
                alert('❌ Lỗi gửi dữ liệu: ' + error.message);
              })
              // Đóng modal (giả lập)
            });

        // Reset tất cả các input, select, textarea
        modal.querySelectorAll('input, select, textarea').forEach(function (el) {
            if (el.type === 'checkbox' || el.type === 'radio') {
                el.checked = false;
            } else {
                el.value = '';
            }
        });

        // Nếu dùng CKEditor
        if (window.CKEDITOR && CKEDITOR.instances['productDesc']) {
            CKEDITOR.instances['productDesc'].setData('');
        }

        modal.querySelectorAll('input[type="file"]').forEach(function(fileInput) {
            // Tạo một bản sao của input file
            const newFileInput = fileInput.cloneNode(true);
            // Thay thế phần tử cũ bằng phần tử mới
            fileInput.parentNode.replaceChild(newFileInput, fileInput);
            preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            // Gán lại sự kiện change cho input mới
           
        });
        document.getElementById('productImage').addEventListener('change', function(event) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = ''; // Xóa ảnh cũ trong preview
            const files = event.target.files;
            if (files) {
                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'rounded border';
                            img.style.height = '60px';
                            img.style.width = '60px';
                            img.style.objectFit = 'cover';
                            img.style.marginRight = '8px';
                            preview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });

        // Đặt lại tiêu đề modal nếu cần
        document.getElementById('addProductModalLabel').textContent = 'Thêm sản phẩm';
        })
    }
    

    function productSearching() {
    //Code để tìm kiếm trong trang product
        const input = document.getElementById('searchInputProduct')
        const table = document.getElementById('table-product')
        const rows = table.getElementsByTagName('tr')
        const category = document.getElementById('filter-dropdown-product').addEventListener('change', function () {
            const selectedCategory = this.value
            for(let i = 1; i < rows.length; i ++) {
                const categoryCell = rows[i].getElementsByTagName("td")[4];
                const categoryText = normalizeText2Meta(categoryCell.textContent);
                if (selectedCategory === "" || categoryText === selectedCategory) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }

            
        })

        const status = document.getElementById('filter-dropdown-status-product').addEventListener('change', function () {
            const selectedStatus = this.value
            for(let i = 1; i < rows.length; i ++) {
                const selectedCell = rows[i].getElementsByTagName("td")[7];
                const selectedText = normalizeText2Meta(selectedCell.textContent);
                console.log(selectedText)
                if (selectedStatus === "" || selectedText === selectedStatus) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        })
        const sort = document.getElementById('filter-dropdown-sort-product').addEventListener('change', function () {
            const selectedStatus = this.value
            fetch('?controller=adminproduct&action=sort', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'sortBy=' + encodeURIComponent(selectedStatus)
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('table-product').innerHTML = data;
            });
        })

        input.addEventListener("keyup", function () {
            const filter = normalizeText(input.value);
            for (let i = 1; i < rows.length; i++) { // Bỏ qua hàng tiêu đề (i = 0)
            const cells = rows[i].getElementsByTagName("td");
            let match = false;
        
            for (let j = 0; j < cells.length; j++) {
                const text = normalizeText(cells[j].textContent);
                if (text.includes(filter)) {
                match = true;
                break;
                }
            }
        
            rows[i].style.display = match ? "" : "none";
        }})

        



        function normalizeText(text) {
            return text
              .normalize("NFD")                   // Tách dấu ra khỏi ký tự gốc
              .replace(/[\u0300-\u036f]/g, "")   // Xóa dấu
              .toLowerCase(); 
        }

        function normalizeText2Meta(text) {
            return text
              .normalize("NFD")                   // Tách dấu ra khỏi ký tự gốc
              .replace(/[\u0300-\u036f]/g, "")   // Xóa dấu
              .toLowerCase().replace(" ", "-"); 
        }


    }

    function setUpCheckbox(checkboxID, inputID) {
        const checkbox = document.getElementById(checkboxID);
        const input = document.getElementById(inputID);

        checkbox.addEventListener('change', function() {
            if(this.checked) {
                input.disabled = false;
                input.value = 0;
            } else {
                input.disabled = true;
                input.value = '';
            }
        })
    }
    

});

