document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle for mobile
    const toggleSidebar = document.getElementById('toggleSidebar');
    const closeSidebar = document.getElementById('closeSidebar');
    const sidebar = document.getElementById('sidebar');
    
    if (toggleSidebar) {
        toggleSidebar.addEventListener('click', function() {
            sidebar.classList.add('show');
        });
    }
    
    if (closeSidebar) {
        closeSidebar.addEventListener('click', function() {
            sidebar.classList.remove('show');
        });
    }
    
    // Close sidebar when clicking outside
    document.addEventListener('click', function(event) {
        const isClickInside = sidebar.contains(event.target) || 
                            (toggleSidebar && toggleSidebar.contains(event.target));
        
        if (!isClickInside && sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
        }
    });
    
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
});

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


