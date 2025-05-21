<?php

class ProductDetailController
{
    private $productDetailModel;
    
    public function __construct()
    {
        // Load our custom model
        require_once('Models/ProductDetailModel.php');
        $this->productDetailModel = new ProductDetailModel();
    }
    
    public function getProductDetails($productId)
    {
        // Fetch product details including description and policies
        $productDetails = $this->productDetailModel->getProductDetailsById($productId);
        return $productDetails;
    }
    
    public function getProductDescription($productId)
    {
        // Get just the description
        $description = $this->productDetailModel->getProductDescription($productId);
        return $description;
    }
    
    public function getProductPolicies($productId)
    {
        // Get just the policies
        $policies = $this->productDetailModel->getProductPolicies($productId);
        return $policies;
    }
    
    public function getProductImages($productId)
    {
        // Get product images
        $images = $this->productDetailModel->getProductImages($productId);
        return $images;
    }
} 