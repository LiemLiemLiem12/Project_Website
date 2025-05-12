<table class="table align-middle" id="table-order">
    <thead>
        <tr>
            <th class="text-center" style="width: 40px;"><input type="checkbox" id="select-all"
                    class="form-check-input"></th>
            <th>Mã đơn hàng</th>
            <th>Khách hàng</th>
            <th>Ngày đặt</th>
            <th>Tổng tiền</th>
            <th>Thanh toán</th>
            <th>Trạng thái</th>
            <th style="width: 120px;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($orderList as $data) {
            echo '
                                                <tr data-order-id="' . $data['id_Order'] . '" data-status="' . $data['status'] . '">
                                                    <td><input type="checkbox" class="order-checkbox form-check-input"
                                                            data-id="' . $data['id_Order'] . '"></td>
                                                    <td>#0' . $data['id_Order'] . '</td>
                                                    <td>' . $data['name'] . '</td>
                                                    <td>' . $data['created_at'] . '</td>
                                                    <td>' . $data['total_amount'] . '</td>
                                                    <td>' . $data['payment_by'] . '</td>

                                                ';
            if ($data['status'] === 'completed') {
                echo '<td data-label="Trạng thái"><span class="status completed">Hoàn thành</span></td>';
            } elseif ($data['status'] === 'pending') {
                echo '<td data-label="Trạng thái"><span class="status pending">Đang xử lý</span></td>';
            } elseif ($data['status'] === 'cancelled') {
                echo '<td data-label="Trạng thái"><span class="status cancelled">Đã hủy</span></td>';
            } elseif ($data['status'] === 'shipping') {
                echo '<td data-label="Trạng thái"><span class="status shipping">Đang giao</span></td>';
            } elseif ($data['status'] === 'waitConfirm') {
                echo '<td data-label="Trạng thái"><span class="status waitConfirm">Chờ xác nhận</span></td>';
            } else {
                echo '<td data-label="Trạng thái"><span class="status unknown">Không xác định</span></td>';
            }
            echo '
                                                    <td>
                                                        <div class="action-buttons">
                                                ';

            if ($data['status'] === 'completed') {

            } elseif ($data['status'] === 'pending') {
                echo '<button class="btn btn-sm btn-primary d-flex justify-content-center align-items-center status-btn status-pending" data-bs-toggle="tooltip"
                                                                title="Giao hàng">
                                                                <i class="fa-solid fa-truck"></i>
                                                            </button>
                                                        <button class="btn btn-sm btn-danger d-flex justify-content-center align-items-center status-cancel" data-bs-toggle="tooltip"
                                                                title="Hủy bỏ">
                                                                <i class="fa-solid fa-x"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>';
            } elseif ($data['status'] === 'cancelled') {

            } elseif ($data['status'] === 'shipping') {
                echo '<button class="btn btn-sm btn-warning d-flex justify-content-center align-items-center status-btn status-shipping" data-bs-toggle="tooltip"
                                                                title="Nhận hàng">
                                                                <i class="fa-solid fa-inbox"></i>
                                                            </button>
                                                        <button class="btn btn-sm btn-danger d-flex justify-content-center align-items-center status-cancel" data-bs-toggle="tooltip"
                                                                title="Hủy bỏ">
                                                                <i class="fa-solid fa-x"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>';
            } elseif ($data['status'] === 'waitConfirm') {
                echo '<button class="btn btn-sm btn-success d-flex justify-content-center align-items-center status-btn status-wait" data-bs-toggle="tooltip"
                                                                title="Xác nhận">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        <button class="btn btn-sm btn-danger d-flex justify-content-center align-items-center status-cancel" data-bs-toggle="tooltip"
                                                                title="Hủy bỏ">
                                                                <i class="fa-solid fa-x"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>';
            } else {
                echo '<td data-label="Trạng thái"><span class="status unknown">Không xác định</span></td>';
            }

            echo
                '</tr>';
        }
        ?>
    </tbody>
</table>