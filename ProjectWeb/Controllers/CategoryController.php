<?php
class CategoryController extends BaseController{
    private $categoryModel;

    public function __construct()
    {
        $this->loadModel('CategoryModel');
        $this->loadModel('ProductModel');
        $this->categoryModel = new CategoryModel;
        $this->productModel = new ProductModel;
    }

    public function index()
    {
        $categories = $this->categoryModel->getAll(['*'],10001000);

        return $this->view('frontend.categories.index', [
            'categories' => $categories,
        ]);
    }
    public function show()
    {
        $categoryId = $_GET['id'] ?? null;
        // $categoriesMenu = $this->categoryModel->getCategoryForMenu();
        $category = $this->categoryModel->findById($categoryId);
        $product = $this ->productModel->getByCategoryId($categoryId);
        $this->view('frontend.categories.show', [
            // 'menus' => $categoriesMenu,
            'category' => $category,
            'products'=> $product,
        ]);
    }

    // public function update()
    // {
    //     $id = $_GET['id'];
    //     $data = ['name' => 'Printer'];
    //     $this->categoryModel->updateData($id, $data);
    // }

//     public function store()
//     {
  
  
//     }
}