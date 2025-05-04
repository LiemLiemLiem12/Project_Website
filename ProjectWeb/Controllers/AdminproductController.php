<?php
class AdminproductController extends BaseController
{
    private $productModel;

    public function __construct()
    {
        $this->loadModel("ProductModel");

        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $this->view(
            "frontend.admin.AdminProduct.index",

            [
                "productList" => $this->productModel->getProductList_AdminProduct()
            ]
        );
    }
}
?>