-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 13, 2025 lúc 07:54 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `fashion_database1`
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

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

CREATE TABLE `category` (
  `id_Category` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `meta` varchar(255) DEFAULT NULL,
  `hide` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `category`
--

INSERT INTO `category` (`id_Category`, `name`, `image`, `link`, `meta`, `hide`, `order`) VALUES
(1, 'Áo', NULL, '/ao', 'ao-thoi-trang', 0, 1),
(2, 'Quần', NULL, '/quan', 'quan-dep', 0, 2),
(3, 'Giày', NULL, '/giay', 'giay-thoi-trang', 0, 3),
(4, 'Phụ kiện', NULL, '/phu-kien', 'phu-kien', 0, 4),
(5, 'Khuyến mãi', NULL, '/sale', 'khuyen-mai', 0, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order`
--

CREATE TABLE `order` (
  `id_Order` int(11) NOT NULL,
  `order_number` varchar(50) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_by` varchar(50) DEFAULT NULL,
  `shipping_method` varchar(50) DEFAULT NULL,
  `status` enum('pending','shipping','completed','cancelled','waitConfirm') DEFAULT 'waitConfirm',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_User` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `shipping_fee` decimal(10,2) DEFAULT NULL,
  `hide` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order`
--

INSERT INTO `order` (`id_Order`, `order_number`, `total_amount`, `payment_by`, `shipping_method`, `status`, `created_at`, `updated_at`, `id_User`, `note`, `shipping_fee`, `hide`) VALUES
(1, NULL, 315000.00, NULL, NULL, 'pending', '2025-05-01 14:25:49', '2025-05-13 02:51:10', 1, NULL, NULL, 0),
(2, NULL, 400000.00, NULL, NULL, 'completed', '2025-05-01 14:25:49', '2025-05-13 02:51:10', 2, NULL, NULL, 0),
(3, NULL, 595000.00, NULL, NULL, 'cancelled', '2025-05-01 14:25:49', '2025-05-13 02:51:10', 3, NULL, NULL, 0),
(4, NULL, 427500.00, NULL, NULL, 'cancelled', '2025-05-01 14:25:49', '2025-05-13 02:51:10', 4, NULL, NULL, 0),
(5, NULL, 225000.00, NULL, NULL, 'cancelled', '2025-05-01 14:25:49', '2025-05-13 02:51:10', 5, NULL, NULL, 0),
(6, NULL, 12.00, NULL, NULL, 'completed', '2025-05-02 18:56:30', '2025-05-13 02:51:10', 1, NULL, NULL, 0),
(7, NULL, 1000000.00, NULL, NULL, 'shipping', '2025-05-02 13:05:02', '2025-05-13 02:51:10', 3, NULL, NULL, 0),
(8, NULL, 1000000.00, NULL, NULL, 'pending', '2025-02-09 13:05:23', '2025-05-13 02:51:10', 3, NULL, NULL, 0),
(9, NULL, 10000000.00, NULL, NULL, 'completed', '2025-04-16 13:36:31', '2025-05-13 02:51:10', 5, NULL, NULL, 0),
(10, NULL, 10000000.00, NULL, NULL, 'completed', '2025-04-17 21:57:49', '2025-05-13 02:51:10', 1, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_detail`
--

CREATE TABLE `order_detail` (
  `id_Order` int(11) NOT NULL,
  `id_Product` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` varchar(10) DEFAULT NULL,
  `sub_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(32, 'Áo Polo Nam Procool ICONDENIM Seam Sealing', 'Áo polo nam với công nghệ Procool và chi tiết seam sealing hiện đại, mang đến sự thoải mái và phong cách thời trang.', 329000.00, 15, 279650.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'item1.webp', 'ao-polo-nam-procool-icondenim-seam-sealing', 'Áo Polo Nam Procool ICONDENIM Seam Sealing', 0, 1, 0, 0, '', '', 'áo polo, nam giới, procool', 'Có', 'Có', 10, 10, 10),
(33, 'Áo Thun Nam ICONDENIM Atheltics Champion', 'Áo thun thể thao với thiết kế năng động, chất liệu thoáng mát phù hợp cho vận động và sinh hoạt hàng ngày.', 299000.00, 20, 239200.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'item8.webp', 'ao-thun-nam-icondenim-atheltics-champion', 'Áo Thun Nam ICONDENIM Atheltics Champion', 0, 2, 0, 0, '', '', 'áo thun, nam giới, thể thao', 'Có', 'Có', 10, 10, 10),
(34, 'Set Đồ Nam ICONDENIM Rugby Football', 'Bộ đồ thể thao phong cách rugby football, chất liệu cao cấp mang đến sự thoải mái tối đa khi vận động.', 799000.00, 25, 599250.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'item9.webp', 'set-do-nam-icondenim-rugby-football', 'Set Đồ Nam ICONDENIM Rugby Football', 0, 3, 0, 0, '', '', 'set đồ, nam giới, thể thao', 'Có', 'Có', 10, 10, 10),
(35, 'Áo Polo Nam ICONDENIM Horizontal Stripped', 'Áo polo sọc ngang thời trang, thiết kế trẻ trung, phù hợp cho cả môi trường công sở và dạo phố.', 329000.00, 10, 296100.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'item10.webp', 'ao-polo-nam-icondenim-horizontal-stripped', 'Áo Polo Nam ICONDENIM Horizontal Stripped', 0, 4, 0, 0, '', '', 'áo polo, nam giới, sọc ngang', 'Có', 'Có', 10, 10, 10),
(36, 'Áo Thun Nam ICONDENIM Edge Striped', 'Áo thun với chi tiết sọc viền độc đáo, thiết kế hiện đại, chất liệu cotton thoáng mát.', 299000.00, 15, 254150.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'upload/img/Home/item1.webp', 'ao-thun-nam-icondenim-edge-striped', 'Áo Thun Nam ICONDENIM Edge Striped', 0, 5, 0, 0, '', '', 'áo thun, nam giới, sọc viền', 'Có', 'Có', 10, 10, 10),
(37, 'Áo Thun Nam Procool ICONDENIM Seam Sealing', 'Áo thun công nghệ Procool với chi tiết seam sealing, mang đến sự khô thoáng và thoải mái suốt cả ngày.', 299000.00, 20, 239200.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'upload/img/Home/item2.webp', 'ao-thun-nam-procool-icondenim-seam-sealing', 'Áo Thun Nam Procool ICONDENIM Seam Sealing', 0, 6, 0, 0, '', '', 'áo thun, nam giới, procool', 'Có', 'Có', 10, 10, 10),
(38, 'Quần Jean Nam Procool ICONDENIM CoolMax Black Slim', 'Quần jean đen ôm với công nghệ CoolMax, mang đến sự thoải mái và phong cách trong mọi hoạt động.', 549000.00, 10, 494100.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'upload/img/Home/item3.webp', 'quan-jean-nam-procool-icondenim-coolmax-black-slim', 'Quần Jean Nam Procool ICONDENIM CoolMax Black Slim', 0, 7, 0, 0, '', '', 'quần jean, nam giới, slim fit', 'Có', 'Có', 10, 10, 10),
(39, 'Quần Jean Nam ProCOOL ICONDENIM CoolMax Light Blue Slim', 'Quần jean xanh nhạt ôm với công nghệ CoolMax và ProCOOL, kết hợp hoàn hảo giữa phong cách và tính năng.', 549000.00, 15, 466650.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'upload/img/Home/item4.webp', 'quan-jean-nam-procool-icondenim-coolmax-light-blue-slim', 'Quần Jean Nam ProCOOL ICONDENIM CoolMax Light Blue Slim', 0, 8, 0, 0, '', '', 'quần jean, nam giới, xanh nhạt', 'Có', 'Có', 10, 10, 10),
(40, 'Quần Short Jean Nam ICONDENIM Mid Blue Regular', 'Quần short jean màu xanh trung bình, dáng regular thoải mái, phù hợp cho mùa hè.', 359000.00, 30, 251300.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'upload/img/Home/item2.webp', 'quan-short-jean-nam-icondenim-mid-blue-regular', 'Quần Short Jean Nam ICONDENIM Mid Blue Regular', 0, 9, 0, 0, '', '', 'quần short, nam giới, jean', 'Có', 'Có', 10, 10, 10),
(41, 'Áo Thun Nam ICONDENIM Basic Form Regular', 'Áo thun basic form với kiểu dáng regular, chất liệu cotton mềm mại, màu sắc đơn giản dễ phối đồ.', 199000.00, 20, 159200.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'upload/img/Home/item3.webp', 'ao-thun-nam-icondenim-basic-form-regular', 'Áo Thun Nam ICONDENIM Basic Form Regular', 0, 10, 0, 0, '', '', 'áo thun, nam giới, basic', 'Có', 'Có', 10, 10, 10),
(42, 'Quần Tây Nam ICONDENIM Straight Neutral Basic', 'Quần tây dáng straight với màu sắc trung tính, phù hợp cho môi trường công sở và dạo phố.', 499000.00, 15, 424150.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'upload/img/Home/item4.webp', 'quan-tay-nam-icondenim-straight-neutral-basic', 'Quần Tây Nam ICONDENIM Straight Neutral Basic', 0, 11, 0, 0, '', '', 'quần tây, nam giới, công sở', 'Có', 'Có', 10, 10, 10),
(43, 'Quần Short Kaki Nam ICONDENIM Garment Dye', 'Quần short kaki với công nghệ garment dye, màu sắc tự nhiên, phong cách casual thoải mái.', 359000.00, 25, 269250.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'upload/img/Home/item5.webp', 'quan-short-kaki-nam-icondenim-garment-dye', 'Quần Short Kaki Nam ICONDENIM Garment Dye', 0, 12, 0, 0, '', '', 'quần short, nam giới, kaki', 'Có', 'Có', 10, 10, 10);

--
-- Bẫy `product`
--
DELIMITER $$
CREATE TRIGGER `auto_calculate_current_price` BEFORE INSERT ON `product` FOR EACH ROW BEGIN
    -- Kiểm tra xem có giá gốc và phần trăm giảm giá không
    IF NEW.original_price IS NOT NULL AND NEW.discount_percent IS NOT NULL THEN
        -- Tính giá hiện tại = giá gốc × (1 - phần trăm giảm/100)
        -- ROUND() giúp làm tròn số để tránh số lẻ như .000001
        SET NEW.current_price = ROUND(NEW.original_price * (1 - NEW.discount_percent / 100));
    ELSE
        -- Nếu không có discount hoặc giá gốc, giữ giá hiện tại = giá gốc
        SET NEW.current_price = NEW.original_price;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `auto_update_current_price` BEFORE UPDATE ON `product` FOR EACH ROW BEGIN
    -- Chỉ tính lại nếu giá gốc hoặc discount thay đổi
    IF NEW.original_price != OLD.original_price OR NEW.discount_percent != OLD.discount_percent THEN
        IF NEW.original_price IS NOT NULL AND NEW.discount_percent IS NOT NULL THEN
            SET NEW.current_price = ROUND(NEW.original_price * (1 - NEW.discount_percent / 100));
        ELSE
            SET NEW.current_price = NEW.original_price;
        END IF;
    END IF;
END
$$
DELIMITER ;

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
  `verification_code` varchar(50) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `hide` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`id_User`, `name`, `email`, `password`, `phone`, `address`, `role`, `verification_code`, `verified`, `reset_token`, `reset_token_expiry`, `created_at`, `updated_at`, `hide`) VALUES
(1, 'Nguyễn Văn A', 'a@example.com', '123456', '0900000001', 'Hà Nội', 'user', NULL, 0, NULL, NULL, '2025-05-01 14:25:49', '2025-05-01 14:25:49', 0),
(2, 'Trần Thị B', 'b@example.com', '123456', '0900000002', 'Hồ Chí Minh', 'user', NULL, 0, NULL, NULL, '2025-05-01 14:25:49', '2025-05-01 14:25:49', 0),
(3, 'Lê Văn C', 'c@example.com', '123456', '0900000003', 'Đà Nẵng', 'admin', NULL, 0, NULL, NULL, '2025-05-01 14:25:49', '2025-05-01 14:25:49', 0),
(4, 'Phạm Thị D', 'd@example.com', '123456', '0900000004', 'Cần Thơ', 'user', NULL, 0, NULL, NULL, '2025-05-01 14:25:49', '2025-05-01 14:25:49', 0),
(5, 'Hoàng Văn E', 'e@example.com', '123456', '0900000005', 'Hải Phòng', 'user', NULL, 0, NULL, NULL, '2025-05-01 14:25:49', '2025-05-01 14:25:49', 0),
(6, 'Tran Thanh Liem', 'liem@', '123', '123', '123', 'user', NULL, 0, NULL, NULL, '2025-04-15 21:59:36', '2025-05-02 22:00:43', 0),
(7, 'Nguyen Van A', 'ss@example.com', 'pass123', '0912345678', 'Hanoi', 'user', NULL, 0, NULL, NULL, '2025-04-16 08:00:00', '2025-05-02 22:01:25', 0),
(8, 'Le Thi B', 'bbb@example.com', 'abc123', '0987654321', 'HCM City', 'admin', NULL, 0, NULL, NULL, '2025-04-16 09:15:00', '2025-05-02 22:01:25', 0),
(9, 'Pham Van C', 'cccc@example.com', 'qwerty', '0909090909', 'Da Nang', 'user', NULL, 0, NULL, NULL, '2025-04-16 10:30:00', '2025-05-02 22:01:25', 0);

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
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

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
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`id_Product`) REFERENCES `product` (`id_product`);

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
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`id_Product`) REFERENCES `product` (`id_product`);

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
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`id_Product`) REFERENCES `product` (`id_product`);
COMMIT;

-- NEW 14 / 05 / 2025
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text,
  `setting_group` varchar(50) DEFAULT 'general',
  `setting_type` varchar(20) DEFAULT 'text',
  `setting_label` varchar(255) NOT NULL,
  `setting_description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Thêm dữ liệu cài đặt mặc định
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`, `setting_type`, `setting_label`, `setting_description`) VALUES
('site_name', 'SR Store', 'general', 'text', 'Tên website', 'Tên hiển thị của website'),
('site_description', 'Website bán quần áo thời trang', 'general', 'textarea', 'Mô tả website', 'Mô tả ngắn về website'),
('admin_email', 'admin@example.com', 'contact', 'email', 'Email quản trị', 'Email liên hệ của quản trị viên'),
('contact_phone', '0123456789', 'contact', 'text', 'Số điện thoại liên hệ', 'Số điện thoại hiển thị trên website'),
('contact_address', '123 Đường ABC, Quận XYZ, TP HCM', 'contact', 'textarea', 'Địa chỉ', 'Địa chỉ liên hệ'),
('logo_path', '/Project_Website/ProjectWeb/upload/img/Header/logo.png', 'appearance', 'file', 'Logo', 'Đường dẫn đến file logo'),
('favicon_path', '/Project_Website/ProjectWeb/upload/img/favicon.ico', 'appearance', 'file', 'Favicon', 'Đường dẫn đến file favicon'),
('currency', 'VND', 'shop', 'select', 'Đơn vị tiền tệ', 'Đơn vị tiền tệ sử dụng trên website'),
('shipping_fee', '30000', 'shop', 'number', 'Phí vận chuyển', 'Phí vận chuyển mặc định'),
('tax_rate', '10', 'shop', 'number', 'Thuế VAT (%)', 'Tỷ lệ thuế VAT áp dụng'),
('maintenance_mode', '0', 'system', 'boolean', 'Chế độ bảo trì', 'Bật/tắt chế độ bảo trì website');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
