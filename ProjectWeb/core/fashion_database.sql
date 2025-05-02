-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 01, 2025 lúc 09:27 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `fashion_database`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `id_User` int(11) DEFAULT NULL,
  `id_Product` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `id_User`, `id_Product`, `quantity`, `created_at`) VALUES
(1, 1, 2, 2, '2025-05-01 14:25:49'),
(2, 2, 3, 1, '2025-05-01 14:25:49'),
(3, 3, 5, 3, '2025-05-01 14:25:49'),
(4, 4, 1, 1, '2025-05-01 14:25:49'),
(5, 5, 4, 2, '2025-05-01 14:25:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

CREATE TABLE `category` (
  `id_Category` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `meta` varchar(255) DEFAULT NULL,
  `hide` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `category`
--

INSERT INTO `category` (`id_Category`, `name`, `link`, `meta`, `hide`, `order`) VALUES
(1, 'Áo', '/ao', 'ao-thoi-trang', 0, 1),
(2, 'Quần', '/quan', 'quan-dep', 0, 2),
(3, 'Giày', '/giay', 'giay-thoi-trang', 0, 3),
(4, 'Phụ kiện', '/phu-kien', 'phu-kien', 0, 4),
(5, 'Khuyến mãi', '/sale', 'khuyen-mai', 0, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order`
--

CREATE TABLE `order` (
  `id_Order` int(11) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','canceled') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `id_User` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order`
--

INSERT INTO `order` (`id_Order`, `total_amount`, `status`, `created_at`, `id_User`) VALUES
(1, 315000, 'pending', '2025-05-01 14:25:49', 1),
(2, 400000, 'processing', '2025-05-01 14:25:49', 2),
(3, 595000, 'shipped', '2025-05-01 14:25:49', 3),
(4, 427500, 'delivered', '2025-05-01 14:25:49', 4),
(5, 225000, 'pending', '2025-05-01 14:25:49', 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_detail`
--

CREATE TABLE `order_detail` (
  `id_Order_Detail` int(11) NOT NULL,
  `id_Order` int(11) DEFAULT NULL,
  `id_Product` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `sub_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_detail`
--

INSERT INTO `order_detail` (`id_Order_Detail`, `id_Order`, `id_Product`, `quantity`, `sub_total`) VALUES
(1, 1, 1, 1, 315000.00),
(2, 2, 2, 1, 400000.00),
(3, 3, 3, 1, 595000.00),
(4, 4, 4, 1, 427500.00),
(5, 5, 5, 1, 225000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

CREATE TABLE `product` (
  `id_Product` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `discount_percent` int(11) DEFAULT NULL,
  `current_price` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_Category` int(11) DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `meta` varchar(255) DEFAULT NULL,
  `hide` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT INTO `product` (`id_Product`, `name`, `description`, `original_price`, `discount_percent`, `current_price`, `created_at`, `updated_at`, `id_Category`, `main_image`, `img`, `link`, `meta`, `hide`, `order`) VALUES
(1, 'Áo sơ mi nam', 'Chất liệu cotton thoáng mát', 350000.00, 10, 315000.00, '2025-05-01 14:25:49', '2025-05-01 14:25:49', 1, 'a1.jpg', 'a1.jpg', '/ao-so-mi', 'ao-so-mi', 0, 1),
(2, 'Quần jeans nữ', 'Form ôm body tôn dáng', 500000.00, 20, 400000.00, '2025-05-01 14:25:49', '2025-05-01 14:25:49', 2, 'q1.jpg', 'q1.jpg', '/quan-jeans', 'quan-jeans', 0, 2),
(3, 'Giày thể thao', 'Giày sneaker trẻ trung', 700000.00, 15, 595000.00, '2025-05-01 14:25:49', '2025-05-01 14:25:49', 3, 'g1.jpg', 'g1.jpg', '/giay-sneaker', 'giay-sneaker', 0, 3),
(4, 'Túi xách nữ', 'Túi da PU cao cấp', 450000.00, 5, 427500.00, '2025-05-01 14:25:49', '2025-05-01 14:25:49', 4, 't1.jpg', 't1.jpg', '/tui-xach', 'tui-xach', 0, 4),
(5, 'Áo thun nam', 'Áo thun trơn basic', 250000.00, 10, 225000.00, '2025-05-01 14:25:49', '2025-05-01 14:25:49', 1, 'a2.jpg', 'a2.jpg', '/ao-thun', 'ao-thun', 0, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_tag`
--

CREATE TABLE `product_tag` (
  `id_Product` int(11) NOT NULL,
  `id_Tag` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_tag`
--

INSERT INTO `product_tag` (`id_Product`, `id_Tag`) VALUES
(1, 1),
(1, 4),
(2, 2),
(2, 5),
(3, 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `id_User` int(11) DEFAULT NULL,
  `id_Product` int(11) DEFAULT NULL,
  `rate` float NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `main` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `review`
--

INSERT INTO `review` (`id`, `id_User`, `id_Product`, `rate`, `comment`, `created_at`, `updated_at`, `main`, `status`) VALUES
(1, 1, 1, 4.5, 'Rất đẹp và thoải mái', '2025-05-01 14:25:49', '2025-05-01 14:25:49', 'r1.jpg', 1),
(2, 2, 2, 5, 'Vừa vặn và đúng mô tả', '2025-05-01 14:25:49', '2025-05-01 14:25:49', 'r2.jpg', 1),
(3, 3, 3, 3.5, 'Hơi chật so với size', '2025-05-01 14:25:49', '2025-05-01 14:25:49', 'r3.jpg', 1),
(4, 4, 4, 4, 'Giao hàng nhanh, đóng gói kỹ', '2025-05-01 14:25:49', '2025-05-01 14:25:49', 'r4.jpg', 1),
(5, 5, 5, 5, 'Áo thun chất lượng tốt', '2025-05-01 14:25:49', '2025-05-01 14:25:49', 'r5.jpg', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tag`
--

CREATE TABLE `tag` (
  `id_Tag` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `id_Product` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tag`
--

INSERT INTO `tag` (`id_Tag`, `name`, `slug`, `id_Product`, `type`, `status`) VALUES
(1, 'Mới về', 'moi-ve', '1', 1, 1),
(2, 'Bán chạy', 'ban-chay', '2', 1, 1),
(3, 'Giảm giá', 'giam-gia', '3', 1, 1),
(4, 'Thời trang nam', 'thoi-trang-nam', '1', 2, 1),
(5, 'Thời trang nữ', 'thoi-trang-nu', '2', 2, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `id_User` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `hide` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`id_User`, `name`, `email`, `password`, `phone`, `address`, `role`, `created_at`, `updated_at`, `hide`) VALUES
(1, 'Nguyễn Văn A', 'a@example.com', '123456', '0900000001', 'Hà Nội', 'user', '2025-05-01 14:25:49', '2025-05-01 14:25:49', 0),
(2, 'Trần Thị B', 'b@example.com', '123456', '0900000002', 'Hồ Chí Minh', 'user', '2025-05-01 14:25:49', '2025-05-01 14:25:49', 0),
(3, 'Lê Văn C', 'c@example.com', '123456', '0900000003', 'Đà Nẵng', 'admin', '2025-05-01 14:25:49', '2025-05-01 14:25:49', 0),
(4, 'Phạm Thị D', 'd@example.com', '123456', '0900000004', 'Cần Thơ', 'user', '2025-05-01 14:25:49', '2025-05-01 14:25:49', 0),
(5, 'Hoàng Văn E', 'e@example.com', '123456', '0900000005', 'Hải Phòng', 'user', '2025-05-01 14:25:49', '2025-05-01 14:25:49', 0);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cart_user` (`id_User`),
  ADD KEY `idx_cart_product` (`id_Product`);

--
-- Chỉ mục cho bảng `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id_Category`);

--
-- Chỉ mục cho bảng `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id_Order`),
  ADD KEY `idx_order_user` (`id_User`);

--
-- Chỉ mục cho bảng `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id_Order_Detail`),
  ADD KEY `idx_order_detail_order` (`id_Order`),
  ADD KEY `idx_order_detail_product` (`id_Product`);

--
-- Chỉ mục cho bảng `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id_Product`),
  ADD KEY `idx_product_category` (`id_Category`);

--
-- Chỉ mục cho bảng `product_tag`
--
ALTER TABLE `product_tag`
  ADD PRIMARY KEY (`id_Product`,`id_Tag`),
  ADD KEY `id_Tag` (`id_Tag`);

--
-- Chỉ mục cho bảng `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_review_user` (`id_User`),
  ADD KEY `idx_review_product` (`id_Product`);

--
-- Chỉ mục cho bảng `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id_Tag`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_User`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_user_email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `category`
--
ALTER TABLE `category`
  MODIFY `id_Category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `order`
--
ALTER TABLE `order`
  MODIFY `id_Order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id_Order_Detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `product`
--
ALTER TABLE `product`
  MODIFY `id_Product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `tag`
--
ALTER TABLE `tag`
  MODIFY `id_Tag` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `id_User` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id_User`) REFERENCES `user` (`id_User`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`id_Product`) REFERENCES `product` (`id_Product`);

--
-- Các ràng buộc cho bảng `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`id_User`) REFERENCES `user` (`id_User`);

--
-- Các ràng buộc cho bảng `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`id_Order`) REFERENCES `order` (`id_Order`),
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`id_Product`) REFERENCES `product` (`id_Product`);

--
-- Các ràng buộc cho bảng `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`id_Category`) REFERENCES `category` (`id_Category`);

--
-- Các ràng buộc cho bảng `product_tag`
--
ALTER TABLE `product_tag`
  ADD CONSTRAINT `product_tag_ibfk_1` FOREIGN KEY (`id_Product`) REFERENCES `product` (`id_Product`),
  ADD CONSTRAINT `product_tag_ibfk_2` FOREIGN KEY (`id_Tag`) REFERENCES `tag` (`id_Tag`);

--
-- Các ràng buộc cho bảng `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`id_User`) REFERENCES `user` (`id_User`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`id_Product`) REFERENCES `product` (`id_Product`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
