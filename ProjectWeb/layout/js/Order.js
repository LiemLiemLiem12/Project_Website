// Order.js
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('checkout-form');
    const completeOrderBtn = document.getElementById('complete-order');
    
    // Payment method selection
    const paymentMethods = document.querySelectorAll('.payment-method');
    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            // Remove selected class from all methods
            paymentMethods.forEach(m => m.classList.remove('selected'));
            // Add selected class to clicked method
            this.classList.add('selected');
            // Check the radio button
            this.querySelector('input[type="radio"]').checked = true;
        });
    });
    
    // Shipping method selection
    const shippingMethods = document.querySelectorAll('.shipping-method');
    shippingMethods.forEach(method => {
        method.addEventListener('click', function() {
            // Remove selected class from all methods
            shippingMethods.forEach(m => m.classList.remove('selected'));
            // Add selected class to clicked method
            this.classList.add('selected');
            // Check the radio button
            this.querySelector('input[type="radio"]').checked = true;
            
            // Update shipping fee in summary
            const shippingFee = this.getAttribute('data-fee');
            document.getElementById('shipping-fee').textContent = formatCurrency(shippingFee);
            
            // Recalculate total
            calculateTotal();
        });
    });
    
    // Quantity controls
    const minusBtns = document.querySelectorAll('.qty-btn.minus');
    const plusBtns = document.querySelectorAll('.qty-btn.plus');
    
    minusBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.nextElementSibling;
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
                updateProductSubtotal(this.closest('.product-item'));
            }
        });
    });
    
    plusBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.previousElementSibling;
            input.value = parseInt(input.value) + 1;
            updateProductSubtotal(this.closest('.product-item'));
        });
    });
    
    // Coupon code handling
    const applyBtn = document.getElementById('apply-coupon');
    const couponInput = document.getElementById('coupon-code');
    const couponSuccess = document.getElementById('coupon-success');
    const couponError = document.getElementById('coupon-error');
    const discountRow = document.querySelector('.discount-row');
    
    applyBtn.addEventListener('click', function() {
        const code = couponInput.value.trim();
        if (code === 'SALE100K') {
            // Valid discount code
            couponSuccess.classList.remove('d-none');
            couponError.classList.add('d-none');
            discountRow.classList.remove('d-none');
            document.getElementById('discount').textContent = '-100,000₫';
            calculateTotal();
        } else {
            // Invalid discount code
            couponSuccess.classList.add('d-none');
            couponError.classList.remove('d-none');
            discountRow.classList.add('d-none');
            calculateTotal();
        }
    });
    
    // Province/District/Ward dropdowns
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    
    provinceSelect.addEventListener('change', function() {
        if (this.value) {
            districtSelect.disabled = false;
            // Clear and reset district select
            districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
            
            // Add mock districts based on selected province
            if (this.value === '79') { // TP. HCM
                addOption(districtSelect, '790', 'Quận 1');
                addOption(districtSelect, '791', 'Quận 2');
                addOption(districtSelect, '792', 'Quận 3');
                addOption(districtSelect, '793', 'Quận Tân Bình');
                addOption(districtSelect, '794', 'Quận Bình Thạnh');
            } else if (this.value === '01') { // Hà Nội
                addOption(districtSelect, '010', 'Quận Ba Đình');
                addOption(districtSelect, '011', 'Quận Hoàn Kiếm');
                addOption(districtSelect, '012', 'Quận Hai Bà Trưng');
                addOption(districtSelect, '013', 'Quận Đống Đa');
            }
        } else {
            districtSelect.disabled = true;
            wardSelect.disabled = true;
        }
    });
    
    districtSelect.addEventListener('change', function() {
        if (this.value) {
            wardSelect.disabled = false;
            // Clear and reset ward select
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            
            // Add mock wards based on selected district
            if (this.value === '790') { // Quận 1
                addOption(wardSelect, '79001', 'Phường Bến Nghé');
                addOption(wardSelect, '79002', 'Phường Bến Thành');
                addOption(wardSelect, '79003', 'Phường Đa Kao');
            } else if (this.value === '791') { // Quận 2
                addOption(wardSelect, '79101', 'Phường Thảo Điền');
                addOption(wardSelect, '79102', 'Phường An Phú');
                addOption(wardSelect, '79103', 'Phường Bình An');
            }
        } else {
            wardSelect.disabled = true;
        }
    });
    
    // Complete order button click
    completeOrderBtn.addEventListener('click', function() {
        if (validateForm()) {
            // Show order success modal
            const successModal = new bootstrap.Modal(document.getElementById('orderSuccessModal'));
            successModal.show();
        }
    });
    
    // Helper functions
    function validateForm() {
        let isValid = true;
        
        // Add was-validated class to form
        form.classList.add('was-validated');
        
        // Check required fields
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value) {
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    function updateProductSubtotal(productItem) {
        // Get quantity and unit price for this product
        const quantityInput = productItem.querySelector('.qty-input');
        const quantity = parseInt(quantityInput.value);
        const priceElement = productItem.querySelector('.product-price');
        
        // Get the original price from data attribute
        const originalPrice = parseInt(quantityInput.getAttribute('data-original-price'));
        
        // Calculate new price based on quantity
        const newPrice = originalPrice * quantity;
        
        // Update the product price display
        priceElement.textContent = formatCurrency(newPrice);
        
        // Update the current price data attribute
        quantityInput.setAttribute('data-price', newPrice);
        
        // Recalculate the total
        calculateTotal();
    }
    
    function calculateTotal() {
        // Calculate the actual subtotal from all product items
        let subtotal = 0;
        const productItems = document.querySelectorAll('.product-item');
        
        productItems.forEach(item => {
            const quantityInput = item.querySelector('.qty-input');
            const price = parseInt(quantityInput.getAttribute('data-price'));
            subtotal += price;
        });
        
        // Get shipping fee
        const selectedShipping = document.querySelector('.shipping-method.selected');
        const shippingFee = selectedShipping ? parseInt(selectedShipping.getAttribute('data-fee')) : 35000;
        
        // Check if discount is applied
        let discount = 0;
        if (!discountRow.classList.contains('d-none')) {
            discount = 100000; // Demo discount amount
        }
        
        // Calculate total
        const total = subtotal + shippingFee - discount;
        
        // Update UI
        document.getElementById('subtotal').textContent = formatCurrency(subtotal);
        document.getElementById('shipping-fee').textContent = formatCurrency(shippingFee);
        document.getElementById('total').textContent = formatCurrency(total);
    }
    
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
            maximumFractionDigits: 0
        }).format(amount).replace('₫', '') + '₫';
    }
    
    function parseCurrency(currencyString) {
        // Remove non-digit characters and convert to number
        return parseInt(currencyString.replace(/\D/g, ''));
    }
    
    function addOption(selectElement, value, text) {
        const option = document.createElement('option');
        option.value = value;
        option.textContent = text;
        selectElement.appendChild(option);
    }
    
    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });
    
    // Initialize calculations
    calculateTotal();
}); 