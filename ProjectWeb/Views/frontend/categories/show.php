<?php
    view('frontend.partitions.frontend.header');
    // view('partitions.frontend.header', ['menus' => $menus ?? []]);
    view('frontend.categories._detail', [
        // 'category' => $category,
        'products' => $products,
    ]);
    view('frontend.partitions.frontend.footer');
    
?>