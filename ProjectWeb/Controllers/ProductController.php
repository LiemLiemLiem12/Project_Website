<?php

class ProductController extends BaseController
{
    private $productModel;
    public function __construct()
    {
        parent::__construct();
        
        // Sau đó mới khởi tạo các model riêng của ProductController
        $this->loadModel('ProductModel');
        $this->productModel = new ProductModel();
    }
    public function index()
    {
        $product = $this->productModel->getAll(['*'], 15, ['column' => 'id', 'order' => 'desc']);
        return $this->view('frontend.products.index', [
            'products' => $product,

        ]);
    }
    public function show()
    {
        $productId = $_GET['id'] ?? null;
        $this->productModel->incrementClickCount($productId);
        $productDetail = $this->productModel->findById($productId);
        $this->view('frontend.products.show', [
            // 'menus' => $categoriesMenu,

            'productDetail' => $productDetail,
        ]);
    }
    public function store()
    {
        $data = [

            'name' => 'Quần Tây Nam ICONDENIM Straight Neutral Basic',
            'image' => '/WEB_BAN_THOI_TRANG/upload/img/Home/item4.webp',
            'price' => 499000.00,
            'description' => 'Quần Tây Nam ICONDENIM Straight Neutral Basic'

        ];
        $this->productModel->store($data);

    }
    public function update()
    {
        $id = $_GET['id'];
        $data = [

            'name' => 'Quần Tây Nam ICONDENIM Straight Neutral Basic',
            'image' => '/WEB_BAN_THOI_TRANG/upload/img/Home/item4.webp',
            'price' => 52352345.00,
            'description' => 'Quần Tây Nam ICONDENIM Straight Neutral Basic'

        ];
        $this->productModel->updateData($id, $data);
    }
    public function delete()
    {
        $id = $_GET['id'];
        $this->productModel->deleteData($id);
    }
    public function trash()
    {
        $this->view(
            "frontend.admin.AdminProduct.index",
            [
                "productList" => $this->productModel->getProductList_AdminProduct_Trash(),
                "categoryList" => $this->categoryModel->getAll(['name', 'id_Category'], 10000),
                "isTrash" => true
            ]
        );
    }
    public function restore()
{
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (is_array($data)) {
        foreach ($data as $id) {
            $this->productModel->updateDataForProduct($id, [
                "hide" => 0
            ]);
        }
    } else {
        echo 'Lỗi truyền dữ liệu';
        http_response_code(404);
    }

    $this->view(
        "frontend.admin.AdminProduct.sort",
        [
            "productList" => $this->productModel->getProductList_AdminProduct_Trash(),
            "isTrash" => true
        ]
    );
}
}