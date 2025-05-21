-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 04:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fashion_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL COMMENT 'Tiêu đề banner',
  `image_path` varchar(255) NOT NULL COMMENT 'Đường dẫn ảnh',
  `link` varchar(255) DEFAULT '#' COMMENT 'Link khi click vào banner',
  `meta` varchar(255) DEFAULT NULL COMMENT 'Thông tin meta',
  `start_date` date NOT NULL COMMENT 'Ngày bắt đầu hiển thị',
  `end_date` date NOT NULL COMMENT 'Ngày kết thúc hiển thị',
  `hide` tinyint(4) DEFAULT 0 COMMENT 'Trạng thái ẩn (0: hiển thị, 1: ẩn)',
  `order` int(11) DEFAULT 0 COMMENT 'Vị trí hiển thị',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `image_path`, `link`, `meta`, `start_date`, `end_date`, `hide`, `order`, `created_at`, `updated_at`) VALUES
(4, 'Banner 1', '1747623540_cropped.png', '#', '123', '2025-05-19', '2025-06-18', 0, 2, '2025-05-19 02:59:00', '2025-05-19 07:12:15'),
(5, 'SP MẮC NHẤT', '1747633830_cropped.png', '#', 'grtgrgt', '2025-05-19', '2025-06-18', 0, 1, '2025-05-19 05:50:30', '2025-05-19 07:12:15'),
(6, 'Banner 3', '1747666207_cropped.png', 'KOKOKOK', 'vedve', '2025-05-19', '2025-06-18', 0, 3, '2025-05-19 14:50:07', '2025-05-19 14:50:07');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `id_User` int(11) NOT NULL,
  `id_Product` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `size` varchar(10) NOT NULL DEFAULT 'M',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `id_User`, `id_Product`, `quantity`, `size`, `created_at`, `updated_at`) VALUES
(25, 6, 35, 6, 'L', '2025-05-17 22:34:42', '2025-05-18 03:58:50'),
(29, 14, 35, 4, 'L', '2025-05-19 17:02:53', '2025-05-19 22:02:53'),
(30, 3, 32, 1, 'M', '2025-05-21 16:46:39', '2025-05-21 21:46:39');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id_Category` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `meta` varchar(255) DEFAULT NULL,
  `hide` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id_Category`, `name`, `image`, `link`, `meta`, `hide`, `order`, `banner`) VALUES
(1, 'Áo', 'ca1.jpg', '/ao', 'ao-thoi-trang', 0, 1, NULL),
(2, 'Quần', 'ca4.jpg', '/quan', 'quan-dep', 0, 2, NULL),
(3, 'Giày', 'ca5.jpg', '/giay', 'giay-thoi-trang', 0, 3, NULL),
(4, 'Phụ kiện', 'ca3.jpg', '/phu-kien', 'phu-kien', 0, 4, NULL),
(5, 'Khuyến mãi', 'ca2.jpg', '/sale', 'khuyen-mai', 0, 5, NULL),
(11, 'HEHE', '1747803607_1_1_160_ao_thun_486-11_048e677a305a4d288f3a40f83e0cd12b_1024x1024.jpg', NULL, NULL, 0, NULL, 'banner_1747803607.jpg'),
(12, 'KOKOKO', '1747808360_1_2_1_1_160_ao_thun_486-13_61b0f05164cd46859fb972f67dfc2d33_1024x1024.webp', NULL, NULL, 0, NULL, NULL),
(13, 'KJKJKJK', '1747813467_1_1_160_ao_thun_486-13_61b0f05164cd46859fb972f67dfc2d33_1024x1024.webp', NULL, NULL, 0, 6, 'banner_1747813477.jpg'),
(14, 'KO`', '1747817176_1747666069_ThanhToanSpay.webp', NULL, NULL, 0, 7, 'banner_1747817176.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `footer_payment_methods`
--

CREATE TABLE `footer_payment_methods` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL COMMENT 'Tên phương thức thanh toán',
  `image` varchar(255) NOT NULL COMMENT 'Tên file ảnh logo',
  `link` varchar(255) NOT NULL COMMENT 'Đường dẫn khi click vào phương thức (nếu có)',
  `order` int(11) NOT NULL DEFAULT 0 COMMENT 'Thứ tự hiển thị',
  `hide` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Hiển thị, 1: Ẩn',
  `meta` text DEFAULT NULL COMMENT 'Thông tin bổ sung',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `footer_payment_methods`
--

INSERT INTO `footer_payment_methods` (`id`, `title`, `image`, `link`, `order`, `hide`, `meta`, `created_at`, `updated_at`) VALUES
(7, 'SHOPPEEEEEEEEEEEEEEEEEEEEEEE', '1747638484_Đăng ký thông tin.png', 'https://www.google.com/', 2, 0, '13213', '2025-05-19 05:22:11', '2025-05-19 07:08:04'),
(9, 'SP MẮC NHẤT', '1747637035_Đăng ký thông tin.png', 'https://www.google.com/', 4, 0, 'ffgdgf', '2025-05-19 06:43:55', '2025-05-19 06:43:55'),
(10, 'TIKI', '1747666069_ThanhToanSpay.webp', 'http://localhost/phpmyadmin/index.php?route=/sql&pos=0&db=fashion_database&table=home_sections', 5, 0, 'htyht', '2025-05-19 14:47:49', '2025-05-19 14:47:49');

-- --------------------------------------------------------

--
-- Table structure for table `footer_policies`
--

CREATE TABLE `footer_policies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL COMMENT 'Tên chính sách',
  `image` varchar(255) DEFAULT NULL COMMENT 'Tên file ảnh',
  `link` varchar(255) NOT NULL COMMENT 'Đường dẫn đến trang chính sách',
  `order` int(11) NOT NULL DEFAULT 0 COMMENT 'Thứ tự hiển thị',
  `hide` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Hiển thị, 1: Ẩn',
  `meta` text DEFAULT NULL COMMENT 'Thông tin bổ sung',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image2` varchar(255) DEFAULT NULL COMMENT 'Tên file ảnh',
  `image3` varchar(255) DEFAULT NULL COMMENT 'Tên file ảnh'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `footer_policies`
--

INSERT INTO `footer_policies` (`id`, `title`, `image`, `link`, `order`, `hide`, `meta`, `created_at`, `updated_at`, `image2`, `image3`) VALUES
(19, 'Chính Sách Sinh Nhật', '1747813663_sinhnhat.webp', '/Project_Website/ProjectWeb/index.php?controller=policy&action=show&id=19', 1, 0, 'Ưu đãi sinh nhật 5 năm ', '2025-05-21 07:47:43', '2025-05-21 07:47:43', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `footer_social_media`
--

CREATE TABLE `footer_social_media` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL COMMENT 'Tên mạng xã hội',
  `icon` varchar(255) NOT NULL COMMENT 'Tên icon hoặc tên file icon',
  `link` varchar(255) NOT NULL COMMENT 'Đường dẫn đến trang mạng xã hội',
  `order` int(11) NOT NULL DEFAULT 0 COMMENT 'Thứ tự hiển thị',
  `hide` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Hiển thị, 1: Ẩn',
  `meta` text DEFAULT NULL COMMENT 'Thông tin bổ sung',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `footer_social_media`
--

INSERT INTO `footer_social_media` (`id`, `title`, `icon`, `link`, `order`, `hide`, `meta`, `created_at`, `updated_at`) VALUES
(7, 'Tik Tok', 'fab fa-tiktok', 'https://www.google.com/', 7, 0, '123', '2025-05-19 04:43:49', '2025-05-19 07:34:48'),
(8, 'FB', 'fab fa-facebook', 'https://www.google.com/', 8, 0, 'hehehe', '2025-05-19 07:01:23', '2025-05-19 07:01:23'),
(9, 'IG', 'fab fa-twitter', 'http://localhost/phpmyadmin/index.php?route=/sql&pos=0&db=fashion_database&table=home_sections', 9, 0, 'hththth', '2025-05-19 14:46:32', '2025-05-19 14:46:41');

-- --------------------------------------------------------

--
-- Table structure for table `home_sections`
--

CREATE TABLE `home_sections` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL COMMENT 'Tiêu đề vùng hiển thị',
  `section_type` enum('product','category') NOT NULL COMMENT 'Loại vùng',
  `display_style` varchar(100) NOT NULL DEFAULT 'grid' COMMENT 'Style hiển thị',
  `product_count` int(11) NOT NULL DEFAULT 4 COMMENT 'Số lượng sản phẩm/danh mục muốn hiển thị',
  `hide` tinyint(4) DEFAULT 0 COMMENT 'Trạng thái ẩn (0: hiển thị, 1: ẩn)',
  `link` varchar(255) DEFAULT NULL COMMENT 'Đường dẫn liên kết',
  `meta` varchar(255) DEFAULT NULL COMMENT 'Thông tin meta',
  `order` int(11) DEFAULT 0 COMMENT 'Vị trí sắp xếp',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_sections`
--

INSERT INTO `home_sections` (`id`, `title`, `section_type`, `display_style`, `product_count`, `hide`, `link`, `meta`, `order`, `created_at`, `updated_at`) VALUES
(16, 'DANH MỤC 1', 'category', 'grid', 2, 0, '#', '123', 2, '2025-05-19 05:31:34', '2025-05-19 05:31:34'),
(17, 'Vùng 2', 'product', 'grid', 4, 0, '#', '123', 3, '2025-05-19 14:40:11', '2025-05-19 14:40:11');

-- --------------------------------------------------------

--
-- Table structure for table `home_section_items`
--

CREATE TABLE `home_section_items` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL COMMENT 'ID của section',
  `item_id` int(11) NOT NULL COMMENT 'ID của sản phẩm hoặc danh mục',
  `item_type` enum('product','category') NOT NULL COMMENT 'Loại item',
  `hide` tinyint(4) DEFAULT 0 COMMENT 'Trạng thái ẩn (0: hiển thị, 1: ẩn)',
  `link` varchar(255) DEFAULT NULL COMMENT 'Đường dẫn liên kết',
  `meta` varchar(255) DEFAULT NULL COMMENT 'Thông tin meta',
  `order` int(11) DEFAULT 0 COMMENT 'Vị trí hiển thị',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_section_items`
--

INSERT INTO `home_section_items` (`id`, `section_id`, `item_id`, `item_type`, `hide`, `link`, `meta`, `order`, `created_at`, `updated_at`) VALUES
(26, 17, 33, 'product', 0, '', '', 2, '2025-05-19 14:40:36', '2025-05-19 14:40:36');

-- --------------------------------------------------------

--
-- Table structure for table `order`
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
  `shipping_address_id` int(11) DEFAULT NULL COMMENT 'ID địa chỉ giao hàng',
  `note` text DEFAULT NULL,
  `shipping_fee` decimal(10,2) DEFAULT NULL,
  `hide` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id_Order`, `order_number`, `total_amount`, `payment_by`, `shipping_method`, `status`, `created_at`, `updated_at`, `id_User`, `shipping_address_id`, `note`, `shipping_fee`, `hide`) VALUES
(1, NULL, 315000.00, NULL, NULL, 'pending', '2025-05-01 14:25:49', '2025-05-13 02:51:10', 1, NULL, NULL, NULL, 0),
(2, NULL, 400000.00, NULL, NULL, 'completed', '2025-05-01 14:25:49', '2025-05-13 02:51:10', 2, NULL, NULL, NULL, 0),
(3, NULL, 595000.00, NULL, NULL, 'cancelled', '2025-05-01 14:25:49', '2025-05-13 02:51:10', 3, NULL, NULL, NULL, 0),
(4, NULL, 427500.00, NULL, NULL, 'cancelled', '2025-05-01 14:25:49', '2025-05-13 02:51:10', 4, NULL, NULL, NULL, 0),
(5, NULL, 225000.00, NULL, NULL, 'cancelled', '2025-05-01 14:25:49', '2025-05-13 02:51:10', 5, NULL, NULL, NULL, 0),
(6, NULL, 12.00, NULL, NULL, 'completed', '2025-05-02 18:56:30', '2025-05-13 02:51:10', 1, NULL, NULL, NULL, 0),
(7, NULL, 1000000.00, NULL, NULL, 'shipping', '2025-05-02 13:05:02', '2025-05-13 02:51:10', 3, NULL, NULL, NULL, 0),
(8, NULL, 1000000.00, NULL, NULL, 'pending', '2025-02-09 13:05:23', '2025-05-13 02:51:10', 3, NULL, NULL, NULL, 0),
(9, NULL, 10000000.00, NULL, NULL, 'completed', '2025-04-16 13:36:31', '2025-05-13 02:51:10', 5, NULL, NULL, NULL, 0),
(10, NULL, 10000000.00, NULL, NULL, 'completed', '2025-04-17 21:57:49', '2025-05-13 02:51:10', 1, NULL, NULL, NULL, 0),
(14, 'SR202505178641', 331100.00, 'cod', 'ghn', 'pending', '2025-05-17 19:14:49', '2025-05-18 00:14:49', 6, NULL, 'Minh Quân - 0783318569 - liem@ - Đà Lạt, 79101, 791, 79 | Xin chàoo', 35000.00, 0),
(15, 'SR202505176086', 331100.00, 'cod', 'ghn', 'pending', '2025-05-17 19:32:28', '2025-05-18 00:32:28', 6, NULL, 'Minh Quân - 0785054969 - liem@ - Đà Lạt, 79001, 790, 79 | Xin Chàooo', 35000.00, 0),
(16, 'SR202505176903', 331100.00, 'cod', 'ghn', 'pending', '2025-05-17 21:06:44', '2025-05-18 02:06:44', 6, NULL, 'Minh Quân - 1234567890 - liem@ - 123, 79001, 790, 79 | dfsaf', 35000.00, 0),
(17, 'SR202505173443', 331100.00, 'cod', 'ghn', 'pending', '2025-05-17 21:07:11', '2025-05-18 02:07:11', 6, NULL, 'Minh Quân - 1234567890 - liem@ - 123, 79102, 791, 79 | dsf', 35000.00, 0),
(18, 'SR202505174919', 1811600.00, 'cod', 'ghn', 'pending', '2025-05-17 22:34:17', '2025-05-18 03:34:17', 6, NULL, 'Minh Quân - 123 - liem@ - 123, 79001, 790, 79', 35000.00, 0),
(19, 'SR202505194798', 1811600.00, 'cod', 'ghn', 'pending', '2025-05-19 15:39:26', '2025-05-19 20:39:26', 12, NULL, 'Minh Quân - 0783318539 - nguyentranminhquan02062005@gmail.com - Đà Lạt, 79003, 790, 79 | dsfgdsfdsfgdsfgdfsfg', 35000.00, 0),
(20, 'SR202505196032', 3630500.00, 'cod', 'ghn', 'pending', '2025-05-19 15:44:15', '2025-05-19 20:44:15', 12, NULL, 'Minh Quân - 0783318539 - nguyentranminhquan02062005@gmail.com - Đà Lạt, 79102, 791, 79 | dfgfdg', 35000.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id_Order` int(11) NOT NULL,
  `id_Product` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` varchar(10) NOT NULL,
  `sub_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id_Order`, `id_Product`, `quantity`, `size`, `sub_total`) VALUES
(19, 35, 6, 'M', 1776600.00),
(20, 34, 4, 'L', 2397000.00),
(20, 34, 2, 'XL', 1198500.00);

-- --------------------------------------------------------

--
-- Table structure for table `product`
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
-- Dumping data for table `product`
--

INSERT INTO `product` (`id_product`, `name`, `description`, `original_price`, `discount_percent`, `current_price`, `created_at`, `updated_at`, `id_Category`, `main_image`, `link`, `meta`, `hide`, `order`, `click_count`, `store`, `img2`, `img3`, `tag`, `CSDoiTra`, `CSGiaoHang`, `M`, `L`, `XL`) VALUES
(32, 'Áo Polo Nam Procool ICONDENIM Seam Sealing', 'Áo polo nam với công nghệ Procool và chi tiết seam sealing hiện đại, mang đến sự thoải mái và phong cách thời trang.', 329000.00, 15, 279650.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'item1.webp', 'ao-polo-nam-procool-icondenim-seam-sealing', 'Áo Polo Nam Procool ICONDENIM Seam Sealing', 0, 1, 0, 0, '', '', 'áo polo, nam giới, procool', 'Có', 'Có', 10, 10, 10),
(33, 'Áo Thun Nam ICONDENIM Atheltics Champion', 'Áo thun thể thao với thiết kế năng động, chất liệu thoáng mát phù hợp cho vận động và sinh hoạt hàng ngày.', 299000.00, 20, 239200.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'item8.webp', 'ao-thun-nam-icondenim-atheltics-champion', 'Áo Thun Nam ICONDENIM Atheltics Champion', 0, 2, 0, 0, '', '', 'áo thun, nam giới, thể thao', 'Có', 'Có', 10, 10, 10),
(34, 'Set Đồ Nam ICONDENIM Rugby Football', 'Bộ đồ thể thao phong cách rugby football, chất liệu cao cấp mang đến sự thoải mái tối đa khi vận động.', 799000.00, 25, 599250.00, '2025-05-11 20:14:04', '2025-05-19 20:44:15', 1, 'item9.webp', 'set-do-nam-icondenim-rugby-football', 'Set Đồ Nam ICONDENIM Rugby Football', 0, 3, 0, 0, '', '', 'set đồ, nam giới, thể thao', 'Có', 'Có', 10, 6, 8),
(35, 'Áo Polo Nam ICONDENIM Horizontal Stripped', 'Áo polo sọc ngang thời trang, thiết kế trẻ trung, phù hợp cho cả môi trường công sở và dạo phố.', 329000.00, 10, 296100.00, '2025-05-11 20:14:04', '2025-05-19 20:39:26', 1, 'item10.webp', 'ao-polo-nam-icondenim-horizontal-stripped', 'Áo Polo Nam ICONDENIM Horizontal Stripped', 0, 4, 0, 0, '', '', 'áo polo, nam giới, sọc ngang', 'Có', 'Có', 4, 6, 4),
(36, 'Áo Thun Nam ICONDENIM Edge Striped', 'Áo thun với chi tiết sọc viền độc đáo, thiết kế hiện đại, chất liệu cotton thoáng mát.', 299000.00, 15, 254150.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'item1.webp', 'ao-thun-nam-icondenim-edge-striped', 'Áo Thun Nam ICONDENIM Edge Striped', 0, 5, 0, 0, '', '', 'áo thun, nam giới, sọc viền', 'Có', 'Có', 10, 10, 10),
(37, 'Áo Thun Nam Procool ICONDENIM Seam Sealing', 'Áo thun công nghệ Procool với chi tiết seam sealing, mang đến sự khô thoáng và thoải mái suốt cả ngày.', 299000.00, 20, 239200.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'item2.webp', 'ao-thun-nam-procool-icondenim-seam-sealing', 'Áo Thun Nam Procool ICONDENIM Seam Sealing', 0, 6, 0, 0, '', '', 'áo thun, nam giới, procool', 'Có', 'Có', 10, 10, 10),
(38, 'Quần Jean Nam Procool ICONDENIM CoolMax Black Slim', 'Quần jean đen ôm với công nghệ CoolMax, mang đến sự thoải mái và phong cách trong mọi hoạt động.', 549000.00, 10, 494100.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'item3.webp', 'quan-jean-nam-procool-icondenim-coolmax-black-slim', 'Quần Jean Nam Procool ICONDENIM CoolMax Black Slim', 0, 7, 0, 0, '', '', 'quần jean, nam giới, slim fit', 'Có', 'Có', 10, 10, 10),
(39, 'Quần Jean Nam ProCOOL ICONDENIM CoolMax Light Blue Slim', 'Quần jean xanh nhạt ôm với công nghệ CoolMax và ProCOOL, kết hợp hoàn hảo giữa phong cách và tính năng.', 549000.00, 15, 466650.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'item4.webp', 'quan-jean-nam-procool-icondenim-coolmax-light-blue-slim', 'Quần Jean Nam ProCOOL ICONDENIM CoolMax Light Blue Slim', 0, 8, 0, 0, '', '', 'quần jean, nam giới, xanh nhạt', 'Có', 'Có', 10, 10, 10),
(40, 'Quần Short Jean Nam ICONDENIM Mid Blue Regular', 'Quần short jean màu xanh trung bình, dáng regular thoải mái, phù hợp cho mùa hè.', 359000.00, 30, 251300.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'item2.webp', 'quan-short-jean-nam-icondenim-mid-blue-regular', 'Quần Short Jean Nam ICONDENIM Mid Blue Regular', 0, 9, 0, 0, '', '', 'quần short, nam giới, jean', 'Có', 'Có', 10, 10, 10),
(41, 'Áo Thun Nam ICONDENIM Basic Form Regular', 'Áo thun basic form với kiểu dáng regular, chất liệu cotton mềm mại, màu sắc đơn giản dễ phối đồ.', 199000.00, 20, 159200.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'item3.webp', 'ao-thun-nam-icondenim-basic-form-regular', 'Áo Thun Nam ICONDENIM Basic Form Regular', 0, 10, 0, 0, '', '', 'áo thun, nam giới, basic', 'Có', 'Có', 10, 10, 10),
(42, 'Quần Tây Nam ICONDENIM Straight Neutral Basic', 'Quần tây dáng straight với màu sắc trung tính, phù hợp cho môi trường công sở và dạo phố.', 499000.00, 15, 424150.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'item4.webp', 'quan-tay-nam-icondenim-straight-neutral-basic', 'Quần Tây Nam ICONDENIM Straight Neutral Basic', 0, 11, 0, 0, '', '', 'quần tây, nam giới, công sở', 'Có', 'Có', 10, 10, 10),
(43, 'Quần Short Kaki Nam ICONDENIM Garment Dye', 'Quần short kaki với công nghệ garment dye, màu sắc tự nhiên, phong cách casual thoải mái.', 359000.00, 25, 269250.00, '2025-05-11 20:14:04', '2025-05-11 20:53:11', 1, 'item5.webp', 'quan-short-kaki-nam-icondenim-garment-dye', 'Quần Short Kaki Nam ICONDENIM Garment Dye', 0, 12, 0, 0, '', '', 'quần short, nam giới, kaki', 'Có', 'Có', 10, 10, 10),
(61, 'KOKOKOKOK', '<p>KOKOKOKOKOKOO</p>\r\n', 131231.00, NULL, 131231.00, '2025-05-21 21:43:55', '2025-05-21 21:43:55', 11, '1747637035_Đăng ký thông tin.png', NULL, NULL, 0, NULL, 0, 12, '1747637107_vt2.png', '1747638012_img2.jpg', 'KOKO', '1747722164_sinhnhat.webp', '1747722853_baomat_1.webp', 12, 0, 0);

--
-- Triggers `product`
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
-- Table structure for table `review`
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
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_group` varchar(50) DEFAULT 'general',
  `setting_type` varchar(20) DEFAULT 'text',
  `setting_label` varchar(255) NOT NULL,
  `setting_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_group`, `setting_type`, `setting_label`, `setting_description`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'SR Store', 'general', 'text', 'Tên website', 'Tên hiển thị của website', '2025-05-16 08:07:15', '2025-05-16 09:52:02'),
(2, 'site_description', 'Website bán quần áo thời trang', 'general', 'textarea', 'Mô tả website', 'Mô tả ngắn về website', '2025-05-16 08:07:15', '2025-05-16 09:52:02'),
(3, 'admin_email', 'admin@example.com', 'contact', 'email', 'Email quản trị', 'Email liên hệ của quản trị viên', '2025-05-16 08:07:15', '2025-05-16 09:52:02'),
(4, 'contact_phone', '0123456789', 'contact', 'text', 'Số điện thoại liên hệ', 'Số điện thoại hiển thị trên website', '2025-05-16 08:07:15', '2025-05-16 09:52:02'),
(5, 'contact_address', '123 Đường ABC, Quận XYZ, TP HCM', 'contact', 'textarea', 'Địa chỉ', 'Địa chỉ liên hệ', '2025-05-16 08:07:15', '2025-05-16 09:52:02'),
(6, 'logo_path', '/Project_Website/ProjectWeb/upload/img/Header/logo.png', 'appearance', 'file', 'Logo', 'Đường dẫn đến file logo', '2025-05-16 08:07:15', '2025-05-16 08:07:15'),
(7, 'favicon_path', '/Project_Website/ProjectWeb/upload/img/Header/favicon.png', 'appearance', 'file', 'Favicon', 'Đường dẫn đến file favicon', '2025-05-16 08:07:15', '2025-05-16 09:52:02'),
(8, 'currency', 'VND', 'shop', 'select', 'Đơn vị tiền tệ', 'Đơn vị tiền tệ sử dụng trên website', '2025-05-16 08:07:15', '2025-05-16 09:52:02'),
(9, 'shipping_fee', '30000', 'shop', 'number', 'Phí vận chuyển', 'Phí vận chuyển mặc định', '2025-05-16 08:07:15', '2025-05-16 09:52:02'),
(10, 'tax_rate', '10', 'shop', 'number', 'Thuế VAT (%)', 'Tỷ lệ thuế VAT áp dụng', '2025-05-16 08:07:15', '2025-05-16 09:52:02'),
(11, 'maintenance_mode', '0', 'system', 'boolean', 'Chế độ bảo trì', 'Bật/tắt chế độ bảo trì website', '2025-05-16 08:07:15', '2025-05-16 08:07:15');

-- --------------------------------------------------------

--
-- Table structure for table `user`
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
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_User`, `name`, `email`, `password`, `phone`, `address`, `role`, `verification_code`, `verified`, `reset_token`, `reset_token_expiry`, `created_at`, `updated_at`, `hide`) VALUES
(1, 'Nguyễn Văn A', 'a@example.com', '123456', '0900000001', 'Hà Nội', 'user', NULL, 0, NULL, NULL, '2025-05-01 14:25:49', '2025-05-01 14:25:49', 0),
(2, 'Trần Thị B', 'b@example.com', '123456', '0900000002', 'Hồ Chí Minh', 'user', NULL, 0, NULL, NULL, '2025-05-01 14:25:49', '2025-05-01 14:25:49', 0),
(3, 'Lê Văn C', 'c@example.com', '123456', '0900000003', 'Đà Nẵng', 'admin', NULL, 0, NULL, NULL, '2025-05-01 14:25:49', '2025-05-01 14:25:49', 0),
(4, 'Phạm Thị D', 'd@example.com', '123456', '0900000004', 'Cần Thơ', 'user', NULL, 0, NULL, NULL, '2025-05-01 14:25:49', '2025-05-01 14:25:49', 0),
(5, 'Hoàng Văn E', 'e@example.com', '123456', '0900000005', 'Hải Phòng', 'user', NULL, 0, NULL, NULL, '2025-05-01 14:25:49', '2025-05-01 14:25:49', 0),
(6, 'Tran Thanh Liem', 'liem@', '123', '123', '123', 'user', '820775', 0, NULL, NULL, '2025-04-15 21:59:36', '2025-05-17 21:19:24', NULL),
(7, 'Nguyen Van A', 'ss@example.com', 'pass123', '0912345678', 'Hanoi', 'user', NULL, 0, NULL, NULL, '2025-04-16 08:00:00', '2025-05-02 22:01:25', NULL),
(8, 'Le Thi B', 'bbb@example.com', 'abc123', '0987654321', 'HCM City', 'admin', NULL, 0, NULL, NULL, '2025-04-16 09:15:00', '2025-05-02 22:01:25', NULL),
(9, 'Pham Van C', 'cccc@example.com', 'qwerty', '0909090909', 'Da Nang', 'user', NULL, 0, NULL, NULL, '2025-04-16 10:30:00', '2025-05-02 22:01:25', NULL),
(11, 'Quân', 'MinhQuan@gmail.com', '12345', '0785054969', NULL, 'user', '', 1, NULL, NULL, '2025-05-17 21:21:43', '2025-05-18 02:23:57', NULL),
(12, 'Quân', 'nguyentranminhquan02062005@gmail.com', '$2y$10$OvTenCZ/vJpojJuy6S0fzuB15no5MRZDNBQtpNs7ZTpc6dSxeAEOS', '0783318539', NULL, 'user', '31546', 1, NULL, NULL, '2025-05-17 21:36:53', '2025-05-19 17:38:12', NULL),
(13, 'Quânnguyen', 'nguyentranminhquan.dl2018@gmail.com', '$2y$10$mFqRigHSQ27dqoVksWwbxO6qPoRXXFkDM21kKaMjYiptGmI4vOJfK', '0783318579', NULL, 'user', '09496', 1, '4dcc271a54f24b002d5ea599011d123958fa6ae7961b6f4001673e1b69acb6ae', '2025-05-19 12:09:34', '2025-05-17 21:46:42', '2025-05-19 16:09:34', NULL),
(14, 'Thanh Liêm', 'tranthanhliemvvt@gmail.com', '$2y$10$oLzYH2zQSSkwgD5zx5D5IeMHDQXzLndevfVkyzSo3WgoqfrlaCLJa', '1234567890', NULL, 'user', '40485', 1, NULL, NULL, '2025-05-19 17:00:10', '2025-05-19 22:02:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_address`
--

CREATE TABLE `user_address` (
  `id` int(11) NOT NULL,
  `id_User` int(11) NOT NULL,
  `address_name` varchar(100) DEFAULT NULL COMMENT 'Tên địa chỉ (Nhà, Công ty...)',
  `receiver_name` varchar(255) NOT NULL COMMENT 'Tên người nhận',
  `phone` varchar(20) NOT NULL COMMENT 'Số điện thoại',
  `street_address` varchar(255) NOT NULL COMMENT 'Số nhà, tên đường',
  `province` varchar(100) NOT NULL COMMENT 'Tỉnh/Thành phố',
  `district` varchar(100) NOT NULL COMMENT 'Quận/Huyện',
  `ward` varchar(100) NOT NULL COMMENT 'Phường/Xã',
  `is_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Địa chỉ mặc định',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visits`
--

CREATE TABLE `visits` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `visited_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visits`
--

INSERT INTO `visits` (`id`, `ip_address`, `visited_at`) VALUES
(1, '', '2025-05-02 21:14:27'),
(2, '::1', '2025-05-02 21:17:50'),
(3, '127.0.0.1', '2025-05-02 21:22:12'),
(4, '127.0.0.1', '2025-05-02 21:30:24'),
(5, '::1', '2025-05-18 01:25:07'),
(6, '::1', '2025-05-19 14:58:59'),
(7, '::1', '2025-05-21 14:44:04'),
(8, '::1', '2025-05-21 21:31:27'),
(9, '::1', '2025-05-21 21:49:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cart_user` (`id_User`),
  ADD KEY `idx_cart_product` (`id_Product`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id_Category`);

--
-- Indexes for table `footer_payment_methods`
--
ALTER TABLE `footer_payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer_policies`
--
ALTER TABLE `footer_policies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer_social_media`
--
ALTER TABLE `footer_social_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_sections`
--
ALTER TABLE `home_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_section_items`
--
ALTER TABLE `home_section_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id_Order`),
  ADD KEY `idx_order_user` (`id_User`),
  ADD KEY `order_shipping_address_fk` (`shipping_address_id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id_Order`,`id_Product`,`size`),
  ADD KEY `idx_order_detail_order` (`id_Order`),
  ADD KEY `idx_order_detail_product` (`id_Product`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `idx_product_category` (`id_Category`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_review_user` (`id_User`),
  ADD KEY `idx_review_product` (`id_Product`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_User`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_user_email` (`email`);

--
-- Indexes for table `user_address`
--
ALTER TABLE `user_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_User` (`id_User`);

--
-- Indexes for table `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id_Category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `footer_payment_methods`
--
ALTER TABLE `footer_payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `footer_policies`
--
ALTER TABLE `footer_policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `footer_social_media`
--
ALTER TABLE `footer_social_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `home_sections`
--
ALTER TABLE `home_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `home_section_items`
--
ALTER TABLE `home_section_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id_Order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_User` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_address`
--
ALTER TABLE `user_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visits`
--
ALTER TABLE `visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id_User`) REFERENCES `user` (`id_User`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`id_Product`) REFERENCES `product` (`id_product`);

--
-- Constraints for table `home_section_items`
--
ALTER TABLE `home_section_items`
  ADD CONSTRAINT `home_section_items_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `home_sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`id_User`) REFERENCES `user` (`id_User`),
  ADD CONSTRAINT `order_shipping_address_fk` FOREIGN KEY (`shipping_address_id`) REFERENCES `user_address` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`id_Order`) REFERENCES `order` (`id_Order`),
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`id_Product`) REFERENCES `product` (`id_product`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`id_Category`) REFERENCES `category` (`id_Category`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`id_User`) REFERENCES `user` (`id_User`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`id_Product`) REFERENCES `product` (`id_product`);

--
-- Constraints for table `user_address`
--
ALTER TABLE `user_address`
  ADD CONSTRAINT `user_address_ibfk_1` FOREIGN KEY (`id_User`) REFERENCES `user` (`id_User`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
