<?php

class HomeController extends BaseController
{
    // Khai báo các thuộc tính mô hình
    protected $categoryModel;
    protected $productModel;
    protected $bannerModel;
    protected $sectionModel;
    public function __construct()
    {
        $this->loadModel('CategoryModel');
        $this->loadModel('ProductModel');
        $this->loadModel('BannerModel');
        $this->loadModel('SectionModel');

        $this->categoryModel = new CategoryModel;
        $this->productModel = new ProductModel;
        $this->bannerModel = new BannerModel();
        $this->sectionModel = new SectionModel();
    }

    public function index()
    {

        // $categoriesMenu = $this->categoryModel->getCategoryForMenu();
        // $categoryHome = $this->categoryModel->getCategoryPickHome();
        $mostViewProducts = $this->productModel->getAll(['*'], 4, ['column' => 'click_count', 'order' => 'desc']);
        $mostSaleProducts = $this->productModel->getAll(['*'], 4, ['column' => 'discount_percent', 'order' => 'desc']);
        $newProducts = $this->productModel->getAll(['*'], 4, ['column' => 'created_at', 'order' => 'desc']);
        $bannerList = $this->bannerModel->getBanner();
        $sectionList = $this->sectionModel->getAll();
        // $categories = $this->categoryModel->getAll(['*'],10001000);

        $this->view('frontend.home.index', [
            // 'menus' => $categoriesMenu,
            // 'categoryHome' => $categoryHome,
            'mostViewProducts' => $mostViewProducts,
            'mostSaleProducts' => $mostSaleProducts,
            'newProducts' => $newProducts,
            'bannerList' => $bannerList,
            'sectionList' => $sectionList,
            // 'categories' => $categories
        ]);
    }
}
?>