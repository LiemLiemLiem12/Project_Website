document.addEventListener('DOMContentLoaded', () => {
    productSearching()
    checkboxAll()
    deleteBtn()
    detailModalEvent()
    changeStatus()
    exportPDFBtn()
    exportExcelBtn()


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
})

function detailModalEvent() {
    
    document.getElementById('order-table').addEventListener('dblclick', function(event) {
        const row = event.target.closest('tr');
        const orderId = row.getAttribute('data-order-id');
        const tds = row.querySelectorAll('td');

        // Gán dữ liệu đơn hàng
        document.getElementById('orderId').textContent = orderId;
        document.getElementById('orderDate').textContent = tds[3]?.textContent.trim() || '';
        document.getElementById('orderStatus').textContent = tds[6]?.textContent.trim() || '';
        document.getElementById('orderTotal').textContent = tds[4]?.textContent.trim() || '';
        document.getElementById('paymentMethod').textContent = tds[5]?.textContent.trim() || '';
        document.getElementById('orderNote').textContent = row.getAttribute('data-order-note') || '';

        // Gọi AJAX để lấy cả customer + items
        fetch(`?controller=adminorder&action=getDetailInfo`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                orderID : orderId 
            })
        })
            .then(response => response.json())
            .then(data => {
                // Gán thông tin khách hàng
                const customer = data.customer[0] || {};
                document.getElementById('customerName').textContent = customer.NAME || 'Không rõ';
                document.getElementById('customerEmail').textContent = customer.email || '';
                document.getElementById('customerPhone').textContent = customer.phone || '';
                document.getElementById('customerAddress').textContent = customer.address || '';

                // Gán bảng sản phẩm
                const items = data.detail_product || [];
                const itemsTable = document.getElementById('orderItemsTable');
                itemsTable.innerHTML = '';
                items.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.name}</td>
                        <td>${item.quantity}</td>
                        <td>${item.current_price.toLocaleString()}</td>
                        <td>${item.sub_total.toLocaleString()}</td>
                    `;
                    itemsTable.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Lỗi lấy chi tiết đơn hàng:', error);
            });

        // Hiện modal
        var modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
        modal.show();
    });

    // Lắng nghe sự kiện click của nút đóng
    document.getElementById('closeModalBtn').addEventListener('click', function (e) {
        e.stopPropagation(); // Ngăn chặn sự kiện lan ra ngoài
        forceCloseModal();
    });
    
    // Lắng nghe sự kiện click của nút Đóng ở footer modal
    const closeFooterBtn = document.querySelector('#orderDetailsModal .modal-footer .btn-secondary');
    if (closeFooterBtn) {
        closeFooterBtn.addEventListener('click', function(e) {
            e.stopPropagation(); // Ngăn chặn sự kiện lan ra ngoài
            forceCloseModal();
        });
    }
    
    // Lắng nghe sự kiện khi modal được đóng
    const orderDetailsModal = document.getElementById('orderDetailsModal');
    orderDetailsModal.addEventListener('hidden.bs.modal', function() {
        setTimeout(forceRemoveBackdrop, 100);
    });
    
    // Thêm sự kiện cho toàn bộ document để bắt backdrop-clicks
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            forceRemoveBackdrop();
        }
    });
    
    // Hàm đóng modal với nhiều chiến lược
    function forceCloseModal() {
        // Chiến lược 1: Sử dụng Bootstrap API
        try {
            const modalEl = document.getElementById('orderDetailsModal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }
        } catch(e) {
            console.error('Error hiding modal with Bootstrap API:', e);
        }
        
        // Chiến lược 2: Xóa các thuộc tính CSS và class
        const modalEl = document.getElementById('orderDetailsModal');
        modalEl.style.display = 'none';
        modalEl.classList.remove('show');
        modalEl.setAttribute('aria-hidden', 'true');
        modalEl.removeAttribute('aria-modal');
        modalEl.removeAttribute('role');
        
        // Chiến lược 3: Force timeout để đảm bảo backdrop bị xóa
        setTimeout(forceRemoveBackdrop, 100);
        setTimeout(forceRemoveBackdrop, 300);
        setTimeout(forceRemoveBackdrop, 500);
    }
    
    // Hàm xóa backdrop với nhiều phương pháp
    function forceRemoveBackdrop() {
        // Xóa tất cả các backdrop
        const backdrops = document.querySelectorAll('.modal-backdrop');
        if (backdrops.length > 0) {
            backdrops.forEach(backdrop => {
                backdrop.remove();
            });
            
            // Khôi phục các thuộc tính của body
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            
            // Đặt lại các thuộc tính CSS quan trọng
            document.body.removeAttribute('data-bs-overflow');
            document.body.removeAttribute('data-bs-padding-right');
            
            console.log('Backdrop removed successfully');
        }
    }
}


function changeStatus() {
    document.getElementById('order-table').addEventListener('click', function(event) { 
        const btnChangeStatus = event.target.closest('.status-btn')
        if(!btnChangeStatus) return;

        const row = btnChangeStatus.closest('tr')
        if(!row) return;

        const orderId = row.getAttribute('data-order-id');
        const status = row.getAttribute('data-status');
        fetch(`?controller=adminorder&action=changeStatus`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                orderID : orderId,
                status: status
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'waitConfirm') {
                row.cells[6].innerHTML = '<td data-label="Trạng thái"><span class="status waitConfirm">Chờ xác nhận</span></td>'
                row.cells[7].innerHTML = `
                    <div class="action-buttons">
                        <button class="btn btn-sm btn-success d-flex justify-content-center align-items-center status-btn status-wait" data-bs-toggle="tooltip"
                            title="Xác nhận">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn btn-sm btn-danger d-flex justify-content-center align-items-center status-cancel" data-bs-toggle="tooltip"
                            title="Hủy bỏ">
                            <i class="fa-solid fa-x"></i>
                        </button>
                    </div>
                `;
                row.setAttribute('data-status', data.status);
            }
            if(data.status === 'pending') {
                row.cells[6].innerHTML = '<td data-label="Trạng thái"><span class="status pending">Đang xử lý</span></td>'
                row.cells[7].innerHTML = `
                    <div class="action-buttons">
                        <button class="btn btn-sm btn-primary d-flex justify-content-center align-items-center status-btn status-pending" data-bs-toggle="tooltip"
                            title="Giao hàng">
                            <i class="fa-solid fa-truck"></i>
                        </button>
                        <button class="btn btn-sm btn-danger d-flex justify-content-center align-items-center status-cancel" data-bs-toggle="tooltip"
                            title="Hủy bỏ">
                            <i class="fa-solid fa-x"></i>
                        </button>
                    </div>
                `;
                row.setAttribute('data-status', data.status);
                
            }
            if(data.status === 'shipping') {
                row.cells[6].innerHTML = '<td data-label="Trạng thái"><span class="status shipping">Đang giao</span></td>'
                row.cells[7].innerHTML = `
                    <div class="action-buttons">
                        <button class="btn btn-sm btn-warning d-flex justify-content-center align-items-center status-btn status-shipping" data-bs-toggle="tooltip"
                            title="Nhận hàng">
                            <i class="fa-solid fa-inbox"></i>
                        </button>
                        <button class="btn btn-sm btn-danger d-flex justify-content-center align-items-center status-cancel" data-bs-toggle="tooltip"
                            title="Hủy bỏ">
                            <i class="fa-solid fa-x"></i>
                        </button>
                    </div>
                `;
                row.setAttribute('data-status', data.status);

            }
            if(data.status === 'completed') {
                row.cells[6].innerHTML = '<td data-label="Trạng thái"><span class="status completed">Hoàn thành</span></td>'
                row.cells[7].innerHTML = ''
                row.setAttribute('data-status', data.status);

            }
            if(data.status === 'cancelled') {
                row.cells[6].innerHTML = '<td data-label="Trạng thái"><span class="status cancelled">Đã hủy</span></td>'
                row.cells[7].innerHTML = ''
                row.setAttribute('data-status', data.status);

            }

           
            

        })
        .catch(err => {
            console.error(err);
            alert('Lỗi mạng khi đổi trạng thái');
        });
    })

    document.getElementById('order-table').addEventListener('click', function(event) { 
        const btnCancelStatus = event.target.closest('.status-cancel')
        if(!btnCancelStatus) return;

        const row = btnCancelStatus.closest('tr')
        if(!row) return;
        if(confirm("Bạn có muốn hủy đơn hàng này?")) {
            const orderId = row.getAttribute('data-order-id');
            const status = row.getAttribute('data-status');
            fetch(`?controller=adminorder&action=cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    orderID : orderId,
                    status: status
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'cancelled') {
                row.cells[6].innerHTML = '<td data-label="Trạng thái"><span class="status cancelled">Đã hủy</span></td>'
                row.cells[7].innerHTML = ''
                row.setAttribute('data-status', data.status);
            }
            })
            .catch(err => {
                console.error(err);
                alert('Lỗi mạng khi đổi trạng thái');
            });
        }
        
    })
}


function productSearching() {
    //Code để tìm kiếm trong trang product
        const input = document.getElementById('searchingOrder')
        const table = document.getElementById('table-order')
        const rows = table.getElementsByTagName('tr')
        const status = document.getElementById('status')
        const paymentBy = document.getElementById('payment-by')
        const sortBy = document.getElementById('sort-by')
        // const category = document.getElementById('filter-dropdown-product').addEventListener('change', function () {
        //     const selectedCategory = this.value
        //     for(let i = 1; i < rows.length; i ++) {
        //         const categoryCell = rows[i].getElementsByTagName("td")[4];
        //         const categoryText = normalizeText2Meta(categoryCell.textContent);
        //         if (selectedCategory === "" || categoryText === selectedCategory) {
        //             rows[i].style.display = "";
        //         } else {
        //             rows[i].style.display = "none";
        //         }
        //     }
        // })

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

        status.addEventListener('change', function() {
            const selectedStatus = this.value
            for(let i = 1; i < rows.length; i ++) {
                const selectedCell = rows[i].getElementsByTagName("td")[6];
                const selectedText = normalizeText2Meta(selectedCell.textContent);
                if (selectedStatus === "" || selectedText === selectedStatus) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        })

        paymentBy.addEventListener('change', function() {
            const selectedStatus = this.value
            for(let i = 1; i < rows.length; i ++) {
                const selectedCell = rows[i].getElementsByTagName("td")[5];
                const selectedText = normalizeText2Meta(selectedCell.textContent);
                if (selectedStatus === "" || selectedText === selectedStatus) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        })

        sortBy.addEventListener('change', function() {
            const selectedStatus = this.value
            fetch('?controller=Adminorder&action=sort', {
                method: 'POST',
                headers: {
                    'Content-Type': 'text/plain',
                },
                body: selectedStatus
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('order-table').innerHTML = data
                productSearching()
            })
        })
    }

function checkboxAll() {
    document.getElementById('order-table').addEventListener('change', (event) => {
        const checkboxes = document.querySelectorAll('.order-checkbox')
        const allCheckboxes = document.querySelectorAll('input[type="checkbox"]')
        
        if (event.target.id === 'select-all') {
            const isChecked = event.target.checked;
            checkboxes.forEach(cb => cb.checked = isChecked);
        }
        
        let deleteBtn = document.getElementById('deleteSelectedOrderBtn');
        let anyChecked = false;

        allCheckboxes.forEach(element => {
            if (element.checked) {
                anyChecked = true;
            }
        });

        deleteBtn.disabled = !anyChecked;
    })
}

function normalizeText(text) {
    return text
        .normalize("NFD")                   // Tách dấu ra khỏi ký tự gốc
        .replace(/[\u0300-\u036f]/g, "")   // Xóa dấu
        .toLowerCase(); 
}

function normalizeText2Meta(text) {
    return text
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "") // xóa dấu
        .replace(/đ/g, "d")              // chuyển đ → d
        .replace(/Đ/g, "d")              // chuyển Đ → d
        .toLowerCase()
        .replace(/\s+/g, "-");           // thay khoảng trắng → dấu gạch ngang
    }

function deleteBtn() {
    document.getElementById('deleteSelectedOrderBtn').addEventListener('click', () => {
        let checkboxes = document.querySelectorAll('.order-checkbox');
        
        // Kiểm tra nếu có checkbox được chọn
        let selectedCheckboxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);

        if (selectedCheckboxes.length > 0 && confirm('Bạn có muốn xóa các đơn hàng này?')) {
            selectedCheckboxes.forEach(element => {
                let row = element.closest('tr'); // Tìm phần tử <tr> gần nhất
                if (row) {  // Kiểm tra xem có tìm thấy row không
                    let orderID = row.getAttribute('data-order-id');
                    
                    fetch('?controller=adminorder&action=delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded' // Content-Type
                        },
                        body: new URLSearchParams({
                            orderID: orderID // Gửi dữ liệu qua POST
                        })
                    })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('order-table').innerHTML = data
                    })
                    .catch(error => {
                        console.error('Error:', error); // Log nếu có lỗi
                    });
                }
            });
        } else {
            console.log('Không có đơn hàng được chọn hoặc người dùng huỷ thao tác.');
        }
    });
}

function exportPDFBtn() {
    $('#exportPDF').click(function() {

        let checkboxes = document.querySelectorAll('.order-checkbox');
        // Kiểm tra nếu có checkbox được chọn
        let selectedCheckboxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);

        if(selectedCheckboxes.length != 0 && confirm('Bạn có muốn xuất hóa đơn các đơn hàng này không?')) {
            selectedCheckboxes.forEach(element => {
                let row = element.closest('tr')
                orderId = row.getAttribute('data-order-id')
                fetch(`?controller=adminorder&action=getDetailInfo`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        orderID : orderId 
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const customer = data.customer[0] || {};
                    const products = data.detail_product || {};

                    var tableBody = [
                        [
                            { text: 'STT', bold: true },
                            { text: 'Tên sản phẩm', bold: true },
                            { text: 'Số lượng', bold: true },
                            { text: 'Đơn giá', bold: true },
                            { text: 'Thành tiền', bold: true }
                        ]
                    ]

                    products.forEach((product, index) => {
                    tableBody.push([
                        (index + 1).toString(),
                        product.name,
                        product.quantity.toString(),
                        product.current_price,
                        product.sub_total
                    ]);
                    });

                    var docDefinition = {
                        pageOrientation: 'landscape', // Khổ ngang

                        content: [
                            { text: 'HÓA ĐƠN BÁN HÀNG', style: 'header' },

                            {
                                columns: [
                                    {
                                        width: '50%',
                                        stack: [
                                            { text: 'Thông tin người mua:', bold: true, margin: [0, 10, 0, 5] },
                                            { text: `Họ tên: ${customer.NAME}` },
                                            { text: `Số điện thoại: ${customer.phone}` },
                                            { text: `Địa chỉ: ${customer.address}` }
                                        ]
                                    },
                                    {
                                        width: '50%',
                                        stack: [
                                            { text: `Mã hóa đơn: HD00${orderId}`, alignment: 'right' },
                                            { text: `Ngày: ${products[0].created_at}`, alignment: 'right' }
                                        ]
                                    }
                                ]
                            },

                            {
                                text: 'Danh sách sản phẩm đã mua:',
                                style: 'subheader'
                            },

                            {
                                table: {
                                    widths: ['auto', '*', 'auto', 'auto', 'auto'],
                                    body: tableBody
                                },
                                margin: [0, 10, 0, 10]
                            },

                            {
                                text: `Tổng cộng: ${products[0].total_amount}`,
                                alignment: 'right',
                                bold: true,
                                fontSize: 13,
                                margin: [0, 0, 0, 20]
                            },

                            {
                                columns: [
                                    { text: 'Người bán hàng\n\n(Ký tên)', alignment: 'center' },
                                    { text: 'Người mua hàng\n\n(Ký tên)', alignment: 'center' }
                                ]
                            }
                        ],

                        styles: {
                            header: {
                                fontSize: 18,
                                bold: true,
                                alignment: 'center',
                                margin: [0, 0, 0, 20]
                            },
                            subheader: {
                                fontSize: 14,
                                bold: true,
                                margin: [0, 10, 0, 5]
                            }
                        }
                    };
                    pdfMake.createPdf(docDefinition).open(); // hoặc .download("hoa-don.pdf")
                })
            })
            

                // Xuất hóa đơn
        }else {
            alert('Vui lòng chọn hóa đơn')
        }
        
    
    })

}

function exportExcelBtn() {
    $('#exportExcel').click(function () {

        let checkboxes = document.querySelectorAll('.order-checkbox');
        let selectedCheckboxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);

        if (selectedCheckboxes.length !== 0 && confirm('Bạn có muốn xuất Excel các đơn hàng này không?')) {
            selectedCheckboxes.forEach(element => {
                let row = element.closest('tr');
                let orderId = row.getAttribute('data-order-id');

                fetch(`?controller=adminorder&action=getDetailInfo`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({ orderID: orderId })
                })
                .then(response => response.json())
                .then(data => {
                    const customer = data.customer[0] || {};
                    const products = data.detail_product || [];

                    // Tạo workbook & worksheet
                    const workbook = new ExcelJS.Workbook();
                    const sheet = workbook.addWorksheet('Hóa đơn');

                    // HÓA ĐƠN BÁN HÀNG - Merge A1:E1
                    sheet.mergeCells('A1:E1');
                    const titleCell = sheet.getCell('A1');
                    titleCell.value = 'HÓA ĐƠN BÁN HÀNG';
                    titleCell.font = { size: 16, bold: true };
                    titleCell.alignment = { vertical: 'middle', horizontal: 'center' };

                    let rowIdx = 3;

                    // Thông tin khách hàng
                    sheet.getCell(`A${rowIdx++}`).value = `Họ tên: ${customer.NAME}`;
                    sheet.getCell(`A${rowIdx++}`).value = `Số điện thoại: ${customer.phone}`;
                    sheet.getCell(`A${rowIdx++}`).value = `Địa chỉ: ${customer.address}`;
                    sheet.getCell(`A${rowIdx++}`).value = `Mã hóa đơn: HD00${orderId}`;
                    sheet.getCell(`A${rowIdx++}`).value = `Ngày: ${products[0].created_at}`;

                    rowIdx++; // dòng trống

                    // Tiêu đề bảng
                    const headerRow = sheet.getRow(rowIdx++);
                    headerRow.values = ['STT', 'Tên sản phẩm', 'Số lượng', 'Đơn giá', 'Thành tiền'];
                    headerRow.font = { bold: true };
                    headerRow.alignment = { horizontal: 'center' };

                    // Dữ liệu sản phẩm
                    products.forEach((product, index) => {
                        sheet.addRow([
                            index + 1,
                            product.name,
                            product.quantity,
                            product.current_price,
                            product.sub_total
                        ]);
                    });

                    rowIdx = sheet.lastRow.number + 2;

                    // Tổng cộng
                    sheet.mergeCells(`A${rowIdx}:D${rowIdx}`);
                    const totalCell = sheet.getCell(`A${rowIdx}`);
                    totalCell.value = 'Tổng cộng:';
                    totalCell.font = { bold: true };
                    totalCell.alignment = { horizontal: 'right' };

                    sheet.getCell(`E${rowIdx}`).value = products[0].total_amount;
                    sheet.getCell(`E${rowIdx}`).font = { bold: true };

                    rowIdx += 3;

                    // Ký tên
                    sheet.getCell(`B${rowIdx}`).value = 'Người bán hàng\n(Ký tên)';
                    sheet.getCell(`B${rowIdx}`).alignment = { horizontal: 'center' };
                    sheet.getCell(`D${rowIdx}`).value = 'Người mua hàng\n(Ký tên)';
                    sheet.getCell(`D${rowIdx}`).alignment = { horizontal: 'center' };

                    // Set độ rộng cột
                    sheet.columns = [
                        { width: 6 },
                        { width: 30 },
                        { width: 12 },
                        { width: 15 },
                        { width: 18 }
                    ];

                    // Xuất file Excel
                    workbook.xlsx.writeBuffer().then(function (buffer) {
                        saveAs(new Blob([buffer]), `hoa_don_${orderId}.xlsx`);
                    });

                });
            });
        } else {
            alert('Vui lòng chọn hóa đơn');
        }
    });
}


