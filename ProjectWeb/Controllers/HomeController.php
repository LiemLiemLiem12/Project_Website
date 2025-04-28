<?php
    
class HomeController extends BaseController
{
    // Khai báo các thuộc tính mô hình
    protected $categoryModel;
    protected $productModel;
    public function __construct()
    {
        $this->loadModel('CategoryModel');
        $this->loadModel('ProductModel');
        
        $this->categoryModel = new CategoryModel;
        $this->productModel = new ProductModel;
    }

    public function index()
    {
       
        // $categoriesMenu = $this->categoryModel->getCategoryForMenu();
        // $categoryHome = $this->categoryModel->getCategoryPickHome();
        $mostViewProducts = $this->productModel->getAll(['id','name','image','price','description'],4,['column'=>'click_count','order'=>'desc']);
        $mostSaleProducts = $this->productModel->getAll(['id','name','image','price','description'],4,['column'=>'sale','order'=>'desc']);
        $newProducts = $this->productModel->getAll(['id','name','image','price','description'],4,['column'=>'created_at','order'=>'desc']);
        // $categories = $this->categoryModel->getAll(['*'],10001000);

        $this->view('frontend.home.index', [
            // 'menus' => $categoriesMenu,
            // 'categoryHome' => $categoryHome,
            'mostViewProducts' =>  $mostViewProducts,
            'mostSaleProducts' =>  $mostSaleProducts,
            'newProducts' =>  $newProducts,
            // 'categories' => $categories
        ]);
    }
}
?>