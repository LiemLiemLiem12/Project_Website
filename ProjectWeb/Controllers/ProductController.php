<?php

class ProductController extends BaseController
{
    private $productModel;
    private $categoryModel;
    public function __construct()
    {
        $this->loadModel('ProductModel');
        $this->loadModel('CategoryModel');
        $this->productModel = new ProductModel;
        $this->categoryModel = new CategoryModel();

    }
    public function index()
    {
        $product = $this->productModel->getAll(['id', 'name', 'image', 'price', 'description'], 15, ['column' => 'id', 'order' => 'desc']);
        return $this->view('frontend.products.index', [
            'products' => $product,

        ]);
    }
    public function show()
    {
        $productId = $_GET['id'] ?? null;
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
}