<?php
    // Chỉ include header ở đây, không include trong _detail.php
    view('frontend.partitions.frontend.header');
    
    view('frontend.categories._detail', [
        'products' => $products,
        'category' => $category ?? null, // Thêm category nếu có
        'filters' => $filters ?? [], // Thêm filters nếu có
        'skipHeader' => true // Flag để tránh load header lần nữa
    ]);
    
    view('frontend.partitions.frontend.footer');
?>