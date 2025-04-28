<?php
    view('frontend.partitions.frontend.header');
    view('frontend.products._detail', ['productDetail' => $productDetail ?? null]);
    view('frontend.partitions.frontend.footer');
?>