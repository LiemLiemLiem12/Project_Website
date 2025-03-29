document.addEventListener('DOMContentLoaded', function() {
    // Location Data (Simplified for example)
    const locationData = {
        "Hà Nội": {
            "Hoàn Kiếm": ["Hàng Bạc", "Hàng Đào"],
            "Ba Đình": ["Liễu Giai", "Ngọc Hà"]
        },
        "Hồ Chí Minh": {
            "Quận 1": ["Bến Nghé", "Bến Thành"],
            "Quận 3": ["Phường 1", "Phường 2"]
        }
    };

    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    const shippingMethodsContainer = document.querySelector('.shipping-methods');

    // Pricing Constants
    const BASE_PRODUCT_PRICE = 329000;
    const SHIPPING_METHODS = {
        standard: { price: 0, label: 'Freeship đơn hàng' },
        express: { price: 50000, label: 'Hỏa tốc' }
    };

    const DISCOUNT_CODES = {
        'HE200': 200000,
        'HE80': 80000,
        'Giảm 50,000₫': 50000,
        'Giảm 10%': BASE_PRODUCT_PRICE * 0.1
    };

    let currentDiscount = 0;
    let currentDiscountCode = '';

    // Populate provinces
    Object.keys(locationData).sort().forEach(province => {
        const option = document.createElement('option');
        option.value = province;
        option.textContent = province;
        provinceSelect.appendChild(option);
    });

    // Province selection handler
    provinceSelect.addEventListener('change', function() {
        districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        districtSelect.disabled = true;
        wardSelect.disabled = true;

        if (this.value) {
            const districts = Object.keys(locationData[this.value]);
            districts.forEach(district => {
                const option = document.createElement('option');
                option.value = district;
                option.textContent = district;
                districtSelect.appendChild(option);
            });
            districtSelect.disabled = false;
        }
    });

    // District selection handler
    districtSelect.addEventListener('change', function() {
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        wardSelect.disabled = true;

        if (this.value) {
            const province = provinceSelect.value;
            const wards = locationData[province][this.value];
            wards.forEach(ward => {
                const option = document.createElement('option');
                option.value = ward;
                option.textContent = ward;
                wardSelect.appendChild(option);
            });
            wardSelect.disabled = false;
            updateShippingMethods();
        }
    });

    // Create shipping methods
    function updateShippingMethods() {
        shippingMethodsContainer.innerHTML = `
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="shipping" id="standard" value="standard" checked>
                <label class="form-check-label d-flex justify-content-between" for="standard">
                    ${SHIPPING_METHODS.standard.label}
                    <span class="text-muted">0₫</span>
                </label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="shipping" id="express" value="express">
                <label class="form-check-label d-flex justify-content-between" for="express">
                    ${SHIPPING_METHODS.express.label}
                    <span class="text-muted">+50,000₫</span>
                </label>
            </div>
        `;

        const shippingOptions = document.querySelectorAll('input[name="shipping"]');
        shippingOptions.forEach(option => {
            option.addEventListener('change', updateTotalPrice);
        });
    }

    // Quantity control
    const minusBtn = document.querySelector('.qty-btn.minus');
    const plusBtn = document.querySelector('.qty-btn.plus');
    const qtyInput = document.querySelector('.qty-input');

    minusBtn.addEventListener('click', () => {
        let currentValue = parseInt(qtyInput.value);
        if (currentValue > 1) {
            qtyInput.value = currentValue - 1;
            updateTotalPrice();
        }
    });

    plusBtn.addEventListener('click', () => {
        let currentValue = parseInt(qtyInput.value);
        qtyInput.value = currentValue + 1;
        updateTotalPrice();
    });

    qtyInput.addEventListener('change', updateTotalPrice);

    // Discount modal functionality
    const discountBtn = document.getElementById('discount-btn');
    const discountModal = document.getElementById('discount-modal');
    const discountModalInstance = new bootstrap.Modal(discountModal);
    const discountList = discountModal.querySelector('.discount-list');

    // Dynamically create discount items
    discountList.innerHTML = Object.entries(DISCOUNT_CODES).map(([code, value]) => `
        <div class="discount-item d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
            <div>
                <span class="discount-code badge bg-primary me-2">${code}</span>
                <span class="discount-desc">Giảm ${value.toLocaleString()}₫</span>
            </div>
            <button class="btn btn-sm btn-outline-primary btn-apply">Áp dụng</button>
        </div>
    `).join('');

    // Add event listeners to discount items
    discountList.querySelectorAll('.btn-apply').forEach(btn => {
        btn.addEventListener('click', function() {
            const discountItem = this.closest('.discount-item');
            const discountCode = discountItem.querySelector('.discount-code').textContent;
            const discountValue = DISCOUNT_CODES[discountCode];
            
            discountBtn.innerHTML = `<i class="bi bi-tag me-2"></i> Mã: ${discountCode}`;
            currentDiscount = discountValue;
            currentDiscountCode = discountCode;
            
            updateTotalPrice();
            discountModalInstance.hide();
        });
    });

    // Initialize shipping methods
    updateShippingMethods();

    function updateTotalPrice() {
        const quantity = parseInt(qtyInput.value);
        const shippingMethod = document.querySelector('input[name="shipping"]:checked');
        const shippingCost = shippingMethod.value === 'express' ? 50000 : 0;
    
        const productTotal = BASE_PRODUCT_PRICE * quantity;
        const totalPrice = productTotal + shippingCost - currentDiscount;
        
        const priceBreakdown = document.querySelector('.order-summary-pricing');
        priceBreakdown.innerHTML = `
            <div class="d-flex justify-content-between mb-2">
                <span>Tổng tiền hàng</span>
                <span>${productTotal.toLocaleString()}₫</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Phương thức vận chuyển</span>
                <span>${shippingCost === 0 ? '0₫' : `+${shippingCost.toLocaleString()}₫`}</span>
            </div>
            ${currentDiscount > 0 ? `
            <div class="d-flex justify-content-between mb-2">
                <span>Mã giảm giá (${currentDiscountCode})</span>
                <span>-${currentDiscount.toLocaleString()}₫</span>
            </div>` : ''}
            <div class="d-flex justify-content-between fw-bold text-danger">
                <strong>Tổng thanh toán</strong>
                <strong>${totalPrice.toLocaleString()}₫</strong>
            </div>
        `;
    }

    // Complete order button
    const completeOrderBtn = document.querySelector('.btn-complete-order');
    completeOrderBtn.addEventListener('click', function() {
        const form = document.getElementById('checkout-form');
        if (form.checkValidity()) {
            alert('Đơn hàng đã được đặt thành công!');
        } else {
            form.reportValidity();
        }
    });
    
});

document.addEventListener('DOMContentLoaded', function() {
    const discountBtn = document.getElementById('discount-btn');
    const discountModal = document.getElementById('discount-modal');
    
    // Ensure the modal is properly initialized with Bootstrap
    const discountModalInstance = new bootstrap.Modal(discountModal, {
        keyboard: false
    });

    // Trigger modal when discount button is clicked
    discountBtn.addEventListener('click', function() {
        discountModalInstance.show();
    });

    // Optional: Close modal on backdrop click or ESC key
    discountModal.addEventListener('click', function(e) {
        if (e.target === discountModal) {
            discountModalInstance.hide();
        }
    });
});