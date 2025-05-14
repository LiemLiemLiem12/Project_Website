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
    const discountCodeInput = document.getElementById('discount_code_input');
    
    applyBtn.addEventListener('click', function() {
        const code = couponInput.value.trim();
        if (!code) {
            couponSuccess.classList.add('d-none');
            couponError.classList.remove('d-none');
            discountRow.classList.add('d-none');
            document.getElementById('discount').textContent = '-0₫';
            discountCodeInput.value = '';
            calculateTotal();
            return;
        }
        
        // Check valid discount codes
        const validCodes = {
            'SALE50K': 50000,
            'SALE100K': 100000,
            'FREESHIP': 35000
        };
        
        if (validCodes[code]) {
            // Valid discount code
            couponSuccess.classList.remove('d-none');
            couponError.classList.add('d-none');
            discountRow.classList.remove('d-none');
            document.getElementById('discount').textContent = '-' + formatCurrency(validCodes[code]);
            discountCodeInput.value = code;
            calculateTotal();
        } else {
            // Invalid discount code
            couponSuccess.classList.add('d-none');
            couponError.classList.remove('d-none');
            discountRow.classList.add('d-none');
            document.getElementById('discount').textContent = '-0₫';
            discountCodeInput.value = '';
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
            } else if (this.value === '48') { // Đà Nẵng
                addOption(districtSelect, '480', 'Quận Hải Châu');
                addOption(districtSelect, '481', 'Quận Thanh Khê');
                addOption(districtSelect, '482', 'Quận Sơn Trà');
            } else if (this.value === '92') { // Cần Thơ
                addOption(districtSelect, '920', 'Quận Ninh Kiều');
                addOption(districtSelect, '921', 'Quận Bình Thủy');
                addOption(districtSelect, '922', 'Quận Cái Răng');
            } else if (this.value === '74') { // Bình Dương
                addOption(districtSelect, '740', 'Thành phố Thủ Dầu Một');
                addOption(districtSelect, '741', 'Thành phố Thuận An');
                addOption(districtSelect, '742', 'Thành phố Dĩ An');
            } else if (this.value === '75') { // Đồng Nai
                addOption(districtSelect, '750', 'Thành phố Biên Hòa');
                addOption(districtSelect, '751', 'Huyện Long Thành');
                addOption(districtSelect, '752', 'Huyện Nhơn Trạch');
            }
        } else {
            districtSelect.disabled = true;
            wardSelect.disabled = true;
        }
        
        // Reset ward
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        wardSelect.disabled = true;
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
            } else if (this.value === '010') { // Quận Ba Đình
                addOption(wardSelect, '01001', 'Phường Phúc Xá');
                addOption(wardSelect, '01002', 'Phường Trúc Bạch');
                addOption(wardSelect, '01003', 'Phường Vĩnh Phúc');
            } else {
                // Add some generic wards for other districts
                addOption(wardSelect, '10001', 'Phường 1');
                addOption(wardSelect, '10002', 'Phường 2');
                addOption(wardSelect, '10003', 'Phường 3');
            }
        } else {
            wardSelect.disabled = true;
        }
    });
    
    // Complete order button click
    completeOrderBtn.addEventListener('click', function() {
        if (validateForm()) {
            form.submit();
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
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
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
            const discountText = document.getElementById('discount').textContent;
            discount = parseCurrency(discountText);
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
    
    // Initialize calculations
    calculateTotal();
});