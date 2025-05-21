/**
 * SweetAlert2 Configuration for Admin Pages
 * Replaces standard JavaScript alerts with beautiful SweetAlert2 notifications
 */

// Override standard alert with SweetAlert2
window.originalAlert = window.alert;
window.alert = function(message) {
    Swal.fire({
        title: 'Thông báo',
        text: message,
        icon: 'info',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
        customClass: {
            container: 'my-swal'
        }
    });
};

// Success alert function
window.showSuccessAlert = function(message) {
    Swal.fire({
        title: 'Thành công!',
        text: message,
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#28a745',
        timer: 2500,
        timerProgressBar: true
    });
};

// Error alert function
window.showErrorAlert = function(message) {
    Swal.fire({
        title: 'Lỗi!',
        text: message,
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc3545'
    });
};

// Warning alert function
window.showWarningAlert = function(message) {
    Swal.fire({
        title: 'Cảnh báo!',
        text: message,
        icon: 'warning',
        confirmButtonText: 'OK',
        confirmButtonColor: '#ffc107'
    });
};

// Confirmation dialog
window.showConfirmDialog = function(message, callback) {
    Swal.fire({
        title: 'Xác nhận',
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Đồng ý',
        cancelButtonText: 'Hủy',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        customClass: {
            container: 'my-swal'
        }
    }).then((result) => {
        if (result.isConfirmed && typeof callback === 'function') {
            callback();
        }
    });
};

// Toast notification
window.showToast = function(message, type = 'success') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
    
    Toast.fire({
        icon: type,
        title: message
    });
};

// Add some custom CSS for SweetAlert
const style = document.createElement('style');
style.textContent = `
    .my-swal {
        z-index: 9999;
    }
    
    .swal2-popup {
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    
    .swal2-title {
        font-size: 1.5rem;
        font-weight: 600;
    }
    
    .swal2-content {
        font-size: 1rem;
    }
    
    .swal2-confirm {
        font-weight: 500 !important;
        padding: 0.5rem 1.5rem !important;
    }
    
    .swal2-cancel {
        font-weight: 500 !important;
        padding: 0.5rem 1.5rem !important;
    }
`;
document.head.appendChild(style);

console.log('SweetAlert2 configuration loaded'); 