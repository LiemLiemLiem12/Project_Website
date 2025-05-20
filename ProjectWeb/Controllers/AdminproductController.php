<?php
// Include AdminProductModel
require_once __DIR__ . '/../Models/AdminProductModel.php';

class AdminproductController extends BaseController
{
    public $productModel;
    public $categoryModel;

    public function __construct()
    {
        $this->loadModel("ProductModel");
        $this->loadModel('CategoryModel');
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $this->view(
            "frontend.admin.AdminProduct.index",

            [
                "productList" => $this->productModel->getProductList_AdminProduct(),
                "categoryList" => $this->categoryModel->getAll(['name', 'id_Category'], 10000)
            ]
        );
    }

    public function sort()
    {
        $sortBy = $_POST['sortBy'] ?? '';
        switch ($sortBy) {
            case 'moi-nhat':
                $this->view(
                    "frontend.admin.AdminProduct.sort",
                    [
                        "productList" => $this->productModel->getProductList_AdminProduct_newest()
                    ]
                );
                break;
            case 'cu-nhat':
                $this->view(
                    "frontend.admin.AdminProduct.sort",
                    [
                        "productList" => $this->productModel->getProductList_AdminProduct_oldest()
                    ]
                );
                break;
            case 'gia-tang':
                $this->view(
                    "frontend.admin.AdminProduct.sort",
                    [
                        "productList" => $this->productModel->getProductList_AdminProduct_priceASC()
                    ]
                );
                break;
            case 'gia-giam':
                $this->view(
                    "frontend.admin.AdminProduct.sort",
                    [
                        "productList" => $this->productModel->getProductList_AdminProduct_priceDESC()
                    ]
                );
                break;
            default:
                $this->view(
                    "frontend.admin.AdminProduct.sort",

                    [
                        "productList" => $this->productModel->getProductList_AdminProduct()
                    ]
                );
                break;
        }
    }

    private function copyFile($image)
    {
        $fileName = basename($image['name']);
        $fileTmpName = $image['tmp_name'];
        $fileError = $image['error'];

        if ($fileError === UPLOAD_ERR_OK) {
            $targetDirectory = './upload/img/DetailProduct/';
            $newFileName = $fileName;
            $targetPath = $targetDirectory . $newFileName;
            $count = 1;

            // Tránh trùng tên file
            while (file_exists($targetPath)) {
                $newFileName = $count . "_" . $fileName;
                $targetPath = $targetDirectory . $newFileName;
                $count++;
            }

            if (move_uploaded_file($fileTmpName, $targetPath)) {
                return $newFileName; // ✅ Trả về tên file đã lưu
            } else {
                echo "❌ Có lỗi xảy ra khi tải lên file: " . $fileName;
                http_response_code(500);
                exit;
            }
        } else {
            http_response_code(400);
            echo "❌ File không hợp lệ hoặc lỗi khi upload: " . $fileName;
            exit;
        }
    }


    public function add()
    {
        // Kiểm tra sự tồn tại của tất cả các trường trước
        if (isset($_POST['productName'], $_POST['productDesc'], $_POST['productCategory'], $_POST['productPrice'], $_POST['productStock'], $_FILES['productImage'], $_FILES['policyReturn'], $_FILES['policyWarranty'])) {
            // Nếu tất cả các trường đều có dữ liệu, gán giá trị cho các biến
            $productName = $_POST['productName'];
            $productDesc = $_POST['productDesc'];
            $productCategory = $_POST['productCategory'];
            $productPrice = $_POST['productPrice'];
            $productStock = $_POST['productStock'];
            $productImage = $_FILES['productImage'];
            $policyReturn = $_FILES['policyReturn'];
            $policyWarranty = $_FILES['policyWarranty'];
            $productTags = $_POST['productTags'];
        } else {
            echo "Không có dữ liệu nào được gửi lên!";
            return;
        }

        $size_M_Quantity = isset($_POST['Size_M_quantity']) ? $_POST['Size_M_quantity'] : 0;
        $size_L_Quantity = isset($_POST['Size_L_quantity']) ? $_POST['Size_L_quantity'] : 0;
        $size_XL_Quantity = isset($_POST['Size_XL_quantity']) ? $_POST['Size_XL_quantity'] : 0;

        $main_img = [];
        for ($i = 0; $i < count($productImage['name']); $i++) {
            $fileName = $productImage['name'][$i];
            $fileTmpName = $productImage['tmp_name'][$i];
            $fileError = $productImage['error'][$i];

            if ($fileError === UPLOAD_ERR_OK) {
                $targetDirectory = './upload/img/All-Product/';
                $targetPath = $targetDirectory . basename($fileName);
                $count = 1;

                // Kiểm tra nếu file đã tồn tại, thêm số đếm vào tên file
                while (file_exists($targetPath)) {
                    $fileName = $count . "_" . basename($fileName);
                    $targetPath = $targetDirectory . $fileName;
                    $count++;
                }

                if (move_uploaded_file($fileTmpName, $targetPath)) {
                    $main_img[] = $fileName;
                } else {
                    http_response_code(500);
                    echo "Có lỗi xảy ra khi tải lên file: " . $fileName;
                }
            } else {
                http_response_code(400);
                echo "Lỗi tải lên file: " . $fileName . " với mã lỗi: " . $fileError;
                return;
            }
        }

        $warranty = $this->copyFile($policyWarranty);
        $return = $this->copyFile($policyReturn);
        $productTags = str_replace(' ', '-', $productTags);
        // try {
        // Nếu tất cả dữ liệu hợp lệ, gọi phương thức tạo sản phẩm

        $this->productModel->store([
            "name" => $productName,
            "description" => $productDesc,
            "id_Category" => $productCategory,
            "original_price" => $productPrice,
            "current_price" => $productPrice,
            "store" => $productStock,
            "CSGiaoHang" => $warranty,
            "CSDoiTra" => $return,
            "main_image" => $main_img[0],
            "img2" => $main_img[1],
            "img3" => $main_img[2],
            "tag" => $productTags,
            "M" => $size_M_Quantity,
            "L" => $size_L_Quantity,
            "XL" => $size_XL_Quantity,
            "hide" => 0
        ]);
        // Nếu thêm thành công, hiển thị lại danh sách sản phẩm
            $this->view(
                "frontend.admin.AdminProduct.sort",
                [
                    "productList" => $this->productModel->getProductList_AdminProduct()
                ]
            );
        // } catch (Exception $exception) {
        //     echo "Lỗi: " . $exception->getMessage();
        // }
    }

    public function edit()
    {
        // Kiểm tra sự tồn tại của tất cả các trường trước
        if (isset($_POST['productName'], $_POST['productDesc'], $_POST['productCategory'], $_POST['productPrice'], $_POST['productStock'], $_FILES['productImage'], $_FILES['policyReturn'], $_FILES['policyWarranty'])) {
            // Nếu tất cả các trường đều có dữ liệu, gán giá trị cho các biến
            $productName = $_POST['productName'];
            $productDesc = $_POST['productDesc'];
            $productCategory = $_POST['productCategory'];
            $productPrice = $_POST['productPrice'];
            $productStock = $_POST['productStock'];
            $productImage = $_FILES['productImage'];
            $policyReturn = $_FILES['policyReturn'];
            $policyWarranty = $_FILES['policyWarranty'];
            $productTags = $_POST['productTags'];
            $productID = $_POST['productID'];

        } else {
            echo "Không có dữ liệu nào được gửi lên!";
            http_response_code(404);
            exit;
        }


        $size_M_Quantity = isset($_POST['Size_M_quantity']) ? $_POST['Size_M_quantity'] : 0;
        $size_L_Quantity = isset($_POST['Size_L_quantity']) ? $_POST['Size_L_quantity'] : 0;
        $size_XL_Quantity = isset($_POST['Size_XL_quantity']) ? $_POST['Size_XL_quantity'] : 0;
        $main_img = [];
        for ($i = 0; $i < count($productImage['name']); $i++) {
            $fileName = $productImage['name'][$i];
            $fileTmpName = $productImage['tmp_name'][$i];
            $fileError = $productImage['error'][$i];

            if ($fileError === UPLOAD_ERR_OK) {
                $targetDirectory = './upload/img/All-Product/';
                $targetPath = $targetDirectory . basename($fileName);
                $count = 1;

                // Kiểm tra nếu file đã tồn tại, thêm số đếm vào tên file
                while (file_exists($targetPath)) {
                    $fileName = $count . "_" . basename($fileName);
                    $targetPath = $targetDirectory . $fileName;
                    $count++;
                }

                if (move_uploaded_file($fileTmpName, $targetPath)) {
                    $main_img[] = $fileName;
                } else {
                    // Nếu lỗi khi di chuyển file, chỉ thông báo và tiếp tục vòng lặp
                    continue;  // Tiếp tục với file tiếp theo
                }
            } else {
                // Nếu có lỗi trong quá trình tải lên file (upload error), thông báo và bỏ qua file này
                continue;  // Tiếp tục với file tiếp theo
            }
        }

        $warranty = $this->copyFile($policyWarranty);
        $return = $this->copyFile($policyReturn);
        $productTags = str_replace(' ', '-', $productTags);
        // try {
        // Nếu tất cả dữ liệu hợp lệ, gọi phương thức tạo sản phẩm

        $this->productModel->updateDataForProduct($productID, [
            "name" => $productName,
            "description" => $productDesc,
            "id_Category" => $productCategory,
            "original_price" => $productPrice,
            "current_price" => $productPrice,
            "store" => $productStock,
            "CSGiaoHang" => $warranty,
            "CSDoiTra" => $return,
            "main_image" => $main_img[0],
            "img2" => $main_img[1],
            "img3" => $main_img[2],
            "tag" => $productTags,
            "M" => $size_M_Quantity,
            "L" => $size_L_Quantity,
            "XL" => $size_XL_Quantity,
        ]);
        // Nếu thêm thành công, hiển thị lại danh sách sản phẩm
        $this->view(
            "frontend.admin.AdminProduct.sort",
            [
                "productList" => $this->productModel->getProductList_AdminProduct()
            ]
        );
        // } catch (Exception $exception) {
        //     echo "Lỗi: " . $exception->getMessage();
        // }
    }

    public function delete()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (is_array($data)) {
            foreach ($data as $id) {
                $this->productModel->updateDataForProduct($id, [
                    "hide" => 1
                ]);
            }
        } else {
            echo 'Lỗi truyền dữ liệu';
            http_response_code(404);
        }

        $this->view(
            "frontend.admin.AdminProduct.sort",
            [
                "productList" => $this->productModel->getProductList_AdminProduct()
            ]
        );
    }

    public function upload()
    {
        if (isset($_FILES['upload'])) {
            $file = $_FILES['upload'];
            $target = './upload/img/Description_img/' . basename($file['name']);

            if (move_uploaded_file($file['tmp_name'], $target)) {
                $funcNum = $_GET['CKEditorFuncNum'];
                $url = './upload/img/Description_img/' . basename($file['name']);
                $message = 'Tải ảnh lên thành công';
                echo "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
            } else {
                echo 'Không thể tải ảnh lên.';
            }
        }
    }
    public function getTrashItems() {
        try {
            // Tắt hiển thị lỗi
            error_reporting(0);
            ini_set('display_errors', 0);
            
            // Xóa bất kỳ output nào trước khi trả về JSON
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            // Đảm bảo class AdminProductModel được load
            if (!class_exists('AdminProductModel')) {
                require_once __DIR__ . '/../Models/AdminProductModel.php';
            }
            
            // Đặt header cho JSON
            header('Content-Type: application/json; charset=utf-8');
            
            $model = new AdminProductModel();
            
            // Kiểm tra kết nối database
            if (!$model->conn) {
                echo json_encode(['error' => 'Lỗi kết nối database']);
                exit;
            }
            
            $trashItems = $model->getTrashItems();
            
            // Trả về JSON response
            echo json_encode($trashItems);
            exit; // Ngăn chặn bất kỳ output nào khác
        } catch (Exception $e) {
            // Đảm bảo header JSON được thiết lập
            header('Content-Type: application/json; charset=utf-8');
            
            echo json_encode(['error' => 'Lỗi khi lấy dữ liệu thùng rác: ' . $e->getMessage()]);
            exit;
        }
    }
    
    public function restoreProduct() {
        try {
            // Tắt hiển thị lỗi
            error_reporting(0);
            ini_set('display_errors', 0);
            
            // Xóa bất kỳ output nào trước khi trả về JSON
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            // Đảm bảo class AdminProductModel được load
            if (!class_exists('AdminProductModel')) {
                require_once __DIR__ . '/../Models/AdminProductModel.php';
            }
            
            // Đặt header cho JSON
            header('Content-Type: application/json; charset=utf-8');
            
            // Lấy ID từ tham số
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            
            if (!$id) {
                echo json_encode(['success' => false, 'error' => 'Không có ID hợp lệ']);
                exit;
            }
            
            $model = new AdminProductModel();
            
            // Kiểm tra kết nối database
            if (!$model->conn) {
                echo json_encode(['success' => false, 'error' => 'Lỗi kết nối database']);
                exit;
            }
            
            $result = $model->restoreProduct($id);
            
            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Lỗi cập nhật database']);
            }
            exit;
        } catch (Exception $e) {
            // Đảm bảo header JSON được thiết lập
            header('Content-Type: application/json; charset=utf-8');
            
            echo json_encode(['success' => false, 'error' => 'Lỗi khôi phục sản phẩm: ' . $e->getMessage()]);
            exit;
        }
    }
}
?>