-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 12, 2025 lúc 10:09 AM
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
  `status` enum('pending','shipping','completed','cancelled','waitConfirm') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `id_User` int(11) DEFAULT NULL,
  `payment_by` varchar(255) NOT NULL,
  `hide` int(11) NOT NULL DEFAULT 0,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order`
--

INSERT INTO `order` (`id_Order`, `total_amount`, `status`, `created_at`, `id_User`, `payment_by`, `hide`, `note`) VALUES
(1, 315000, 'completed', '2025-05-01 14:25:49', 1, 'COD', 0, 'Giao giờ hành chính'),
(2, 400000, 'completed', '2025-05-01 14:25:49', 2, 'COD', 0, 'Giao giờ hành chính'),
(3, 595000, 'cancelled', '2025-05-01 14:25:49', 3, 'COD', 0, 'Giao giờ hành chính'),
(4, 427500, 'cancelled', '2025-05-01 14:25:49', 4, 'COD', 0, 'Giao giờ hành chính'),
(5, 225000, 'completed', '2025-05-01 14:25:49', 5, 'COD', 0, 'Giao giờ hành chính'),
(6, 12, 'cancelled', '2025-05-02 18:56:30', 1, 'COD', 0, 'Giao giờ hành chính'),
(7, 1000000, 'completed', '2025-05-02 13:05:02', 3, 'COD', 0, 'Giao giờ hành chính'),
(8, 1000000, 'cancelled', '2025-02-09 13:05:23', 3, 'COD', 0, 'Giao giờ hành chính'),
(9, 10000000, 'shipping', '2025-04-16 13:36:31', 5, 'COD', 0, 'Giao giờ hành chính'),
(10, 10000000, 'waitConfirm', '2025-04-17 21:57:49', 1, 'COD', 0, 'Giao giờ hành chính'),
(11, 10000000, 'completed', '2025-05-03 13:01:01', 8, 'COD', 0, 'Giao giờ hành chính'),
(12, 10000000, 'waitConfirm', '2025-03-19 13:36:06', 6, 'COD', 0, 'Giao giờ hành chính'),
(13, 1000000, 'waitConfirm', '2025-05-11 21:50:51', 5, 'MOMO', 0, 'Giao giờ hành chính');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_detail`
--

CREATE TABLE `order_detail` (
  `id_Order` int(11) NOT NULL,
  `id_Product` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `sub_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_detail`
--

INSERT INTO `order_detail` (`id_Order`, `id_Product`, `quantity`, `sub_total`) VALUES
(1, 1, 1, 315000.00),
(2, 2, 1, 400000.00),
(3, 3, 1, 595000.00),
(4, 4, 1, 427500.00),
(5, 5, 1, 225000.00),
(6, 1, 5, 400000.00),
(9, 2, 10, 400000.00),
(10, 2, 10, 400000.00),
(10, 3, 20, 400000.00),
(12, 5, 100000, 400000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

CREATE TABLE `product` (
  `id_product` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `discount_percent` int(11) DEFAULT NULL,
  `current_price` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_Category` int(11) DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `meta` varchar(255) DEFAULT NULL,
  `hide` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `click_count` int(11) DEFAULT 0,
  `store` int(11) NOT NULL DEFAULT 0,
  `img2` varchar(255) NOT NULL,
  `img3` varchar(255) NOT NULL,
  `tag` text NOT NULL,
  `CSDoiTra` varchar(255) NOT NULL,
  `CSGiaoHang` varchar(255) NOT NULL,
  `M` int(11) NOT NULL DEFAULT 0,
  `L` int(11) NOT NULL DEFAULT 0,
  `XL` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT INTO `product` (`id_product`, `name`, `description`, `original_price`, `discount_percent`, `current_price`, `created_at`, `updated_at`, `id_Category`, `main_image`, `link`, `meta`, `hide`, `order`, `click_count`, `store`, `img2`, `img3`, `tag`, `CSDoiTra`, `CSGiaoHang`, `M`, `L`, `XL`) VALUES
(1, 'Áo sơ mi nam BỰ HONWwwwww', '<p><img alt=\"Map\" src=\"./upload/img/Description_img/Untitled_snapshot_05-06-2025_22_16_39.jpeg\" style=\"border-width: 2px; border-style: solid; float: right; width: 200px; height: 113px;\" /><em><strong>Xin ch&agrave;o c&aacute;c bạn</strong></em></p>\n', 123456.00, 10, 123456.00, '2025-05-01 14:25:49', '2025-05-08 17:44:13', 4, '1_6_5_4_3_2_1__a76f4802-679e-4ab5-a961-ec8cca3c3e49.jpg', '/ao-so-mi', 'ao-so-mi', 0, 1, 20, 100, '1_6_5_4_3_2_1_ODOT.png', '1_6_5_4_3_2_1_ngya.png', 'tag-tag', '1_3_2_1_doitra_1.webp', '1_3_2_1_cs_giaohanh.webp', 90, 5, 5),
(2, 'JEAN FEMALE', '<p>Form &ocirc;m body t&ocirc;n d&aacute;ng</p>\r\n', 400000.00, 20, 400000.00, '2025-05-01 14:25:49', '2025-05-08 15:17:26', 1, '1_2_1_item2.webp', '/quan-jeans', 'quan-jeans', 0, 2, 12, 10, '1_1_item2.webp', '1_1_item2.jpg', 'ao-thun,ao-somi,quan-tay', 'buffet.png', '1_3_cs_giaohanh.webp', 0, 0, 0),
(3, 'Giày thể thao', '<p>Gi&agrave;y sneaker trẻ trung</p>\r\n', 595000.00, 15, 595000.00, '2025-05-01 14:25:49', '2025-05-08 15:17:26', 3, '2_1_bruh.png', '/giay-sneaker', 'giay-sneaker', 0, 3, 5, 0, '2_1_really.png', 'Whiskers Meme Sticker Aegean Cat PNG - Free Download.jpg', 'ao-thun,ao-somi,quan-tay', '4_doitra_1.webp', '4_cs_giaohanh.webp', 0, 0, 0),
(4, 'Túi xách', '<p>T&uacute;i da PU cao cấp</p>\r\n', 427500.00, 5, 427500.00, '2025-05-01 14:25:49', '2025-05-08 15:17:26', 1, '1_item4.jpg', '/tui-xach', 'tui-xach', 0, 4, 7, 10, '1_item4.webp', '2_1_item4.webp', 'ao-thun,ao-somi,quan-tay', '2_doitra_1.webp', '2_cs_giaohanh.webp', 0, 0, 0),
(5, 'Áo thun nam', 'Áo thun trơn basic', 250000.00, 10, 225000.00, '2025-05-01 14:25:49', '2025-05-08 15:17:26', 1, 'item5.jpg', '/ao-thun', 'ao-thun', 0, 5, 30, 0, 'item5.webp', 'item5.webp', 'ao-thun, ao-somi, quan-tay', 'doitra_1.webp', 'cs_giaohanh.webp', 0, 0, 0),
(6, 'Quần KAKI', 'Mô tả quần kaki', 400000.00, 10, 360000.00, '2025-05-04 13:59:31', '2025-05-08 15:17:26', 2, 'item1.jpg', '/item1', 'quan-kaki', 0, 5, 0, 20, 'item1.webp', 'item1.webp', 'quan', '', '', 0, 0, 0),
(32, 'CHiec ao thun', '<p>ccwqcwqcwqwqqc</p>\r\n', 1000000.00, NULL, 1000000.00, '2025-05-08 13:15:28', '2025-05-08 15:17:26', 1, '1_2_1_1_160_ao_thun_486-13_61b0f05164cd46859fb972f67dfc2d33_1024x1024.webp', NULL, NULL, 0, NULL, 0, 13, '1_2_1_1_160_ao_thun_486-11_048e677a305a4d288f3a40f83e0cd12b_1024x1024.jpg', '1_2_1_1_160_ao_thun_486-7_192a8a84946943ba809cf83eda40e528_1024x1024.jpg', 'tagf-tag', '1_2_1_1_Untitled_snapshot_05-06-2025_23_35_55.jpeg', '1_2_2_Untitled_snapshot_05-06-2025_23_35_55.jpeg', 8, 5, 0);

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
(5, 'Hoàng Văn E', 'e@example.com', '123456', '0900000005', 'Hải Phòng', 'user', '2025-05-01 14:25:49', '2025-05-01 14:25:49', 0),
(6, 'Tran Thanh Liem', 'liem@', '123', '123', '123', 'user', '2025-04-15 21:59:36', '2025-05-02 22:00:43', NULL),
(7, 'Nguyen Van A', 'ss@example.com', 'pass123', '0912345678', 'Hanoi', 'user', '2025-04-16 08:00:00', '2025-05-02 22:01:25', NULL),
(8, 'Le Thi B', 'bbb@example.com', 'abc123', '0987654321', 'HCM City', 'admin', '2025-04-16 09:15:00', '2025-05-02 22:01:25', NULL),
(9, 'Pham Van C', 'cccc@example.com', 'qwerty', '0909090909', 'Da Nang', 'user', '2025-04-16 10:30:00', '2025-05-02 22:01:25', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `visits`
--

CREATE TABLE `visits` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `visited_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `visits`
--

INSERT INTO `visits` (`id`, `ip_address`, `visited_at`) VALUES
(1, '', '2025-05-02 21:14:27'),
(2, '::1', '2025-05-02 21:17:50'),
(3, '127.0.0.1', '2025-05-02 21:22:12'),
(4, '127.0.0.1', '2025-05-02 21:30:24');

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
  ADD PRIMARY KEY (`id_Order`,`id_Product`),
  ADD KEY `idx_order_detail_order` (`id_Order`),
  ADD KEY `idx_order_detail_product` (`id_Product`);

--
-- Chỉ mục cho bảng `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `idx_product_category` (`id_Category`);

--
-- Chỉ mục cho bảng `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_review_user` (`id_User`),
  ADD KEY `idx_review_product` (`id_Product`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_User`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_user_email` (`email`);

--
-- Chỉ mục cho bảng `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id_Order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `product`
--
ALTER TABLE `product`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT cho bảng `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `id_User` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `visits`
--
ALTER TABLE `visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- Các ràng buộc cho bảng `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`id_User`) REFERENCES `user` (`id_User`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`id_Product`) REFERENCES `product` (`id_Product`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;