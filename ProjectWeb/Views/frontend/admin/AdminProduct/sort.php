<table id="table-product" class="table">
    <thead>
        <tr>
            <th>
                <input type="checkbox" id="select-all">
            </th>
            <th>Mã SP</th>
            <th>Hình ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Danh mục</th>
            <th>Giá</th>
            <th>Tồn kho</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($productList as $data) {
            echo '
                                    <tr class="product-row" data-id_product="' . $data['id_product'] . '" data-name="' . $data['product_name'] . '"
                                        data-description="' . base64_encode($data['description']) . '"
                                        data-original_price="' . $data['original_price'] . '" data-discount_percent="' . $data['discount_percent'] . '" data-current_price="' . $data['current_price'] . '"
                                        data-created_at="' . $data['created_at'] . '" data-updated_at="' . $data['updated_at'] . '" data-id_category="' . $data['category_name'] . '" id_category = "' . $data['id_Category'] . '"
                                        data-main_image="/Project_Website/ProjectWeb/upload/img/All-Product/' . $data['main_image'] . '"
                                        data-img="/Project_Website/ProjectWeb/upload/img/All-Product/' . $data['img2'] . ',/Project_Website/ProjectWeb/upload/img/All-Product/' . $data['img3'] . '"
                                        data-link="' . $data['link'] . '" data-meta="' . $data['meta'] . '" data-hide="' . $data['hide'] . '" data-order="' . $data['order'] . '"
                                        data-click_count="' . $data['click_count'] . '" data-tags="' . $data['tag'] . '"
                                        data-policy_return="/Project_Website/ProjectWeb/upload/img/DetailProduct/' . $data['CSDoiTra'] . '"
                                        data-policy_warranty="/Project_Website/ProjectWeb/upload/img/DetailProduct/' . $data['CSGiaoHang'] . '" data-stock="' . $data['store'] . '">
                                        <td><input type="checkbox" class="product-checkbox"></td>
                                        <td>' . $data['id_product'] . '</td>
                                        <td><img src="/Project_Website/ProjectWeb/upload/img/All-Product/' . $data['main_image'] . '" alt="Product" width="50"></td>
                                        <td>' . $data['product_name'] . '</td>
                                        <td>' . $data['category_name'] . '</td>
                                        <td>' . $data['current_price'] . '</td>
                                        <td>' . $data['store'] . '</td>';

            if ((int) $data['store'] == 0) {
                echo '
                                                <td><span class="status cancelled">Hết hàng</span></td>
                                            ';
            } else {
                echo '
                                                <td><span class="status completed">Còn hàng</span></td>
                                            ';
            }


            echo '
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-info btn-view" title="Ẩn/Hiện" data-hide="0">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning btn-edit" title="Sửa"><i
                                                        class="fas fa-edit"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                            ';
        }
        ?>
        <!-- Thêm các sản phẩm khác tương tự -->
    </tbody>
</table>