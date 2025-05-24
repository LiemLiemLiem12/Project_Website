-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2025 at 11:10 AM
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
(7, 'Banner 1', '1748076949_cropped.webp', '#', 'Banner 1', '2025-05-24', '2025-06-23', 0, 1, '2025-05-24 08:55:49', '2025-05-24 08:55:49'),
(8, 'Banner 2', '1748076992_cropped.webp', '#', 'Banner 2', '2025-05-24', '2025-06-23', 0, 2, '2025-05-24 08:56:32', '2025-05-24 08:56:32'),
(9, 'Banner 3', '1748077019_cropped.webp', '#', 'Banner 3', '2025-05-24', '2025-06-23', 0, 3, '2025-05-24 08:56:59', '2025-05-24 08:56:59');

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
(11, 'Áo', '1748073821_item2.jpg', NULL, NULL, 0, 1, 'banner_1748073821.jpg'),
(12, 'Quần', '1748073923_item3.jpg', NULL, NULL, 0, 2, 'banner_1748073923.jpg'),
(13, 'Giày', '1748074043_giay-nike-run-defy-nam-trang-den-01-300x300.jpg', NULL, NULL, 0, 3, 'banner_1748074043.jpg'),
(14, 'Phụ Kiện', '1748074113_item7.jpg', NULL, NULL, 0, 4, 'banner_1748074113.jpg');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `qr_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `footer_payment_methods`
--

INSERT INTO `footer_payment_methods` (`id`, `title`, `image`, `link`, `order`, `hide`, `meta`, `created_at`, `updated_at`, `qr_image`) VALUES
(11, 'SPAY', '1748077457_1747726447_1747666069_ThanhToanSpay.webp', '', 1, 0, 'Mô tả SPAY', '2025-05-24 09:04:17', '2025-05-24 09:04:17', '1748077457_qr_1748077274_qr_Đăng ký thông tin.png'),
(12, 'VNPAY', '1748077477_1747934281_1747726389_ThanhToanVNPay.webp', '', 2, 0, 'Mô tả VNPAY', '2025-05-24 09:04:37', '2025-05-24 09:04:37', '1748077477_qr_1748077274_qr_Đăng ký thông tin.png');

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
(19, 'Chính Sách Sinh Nhật', '1748077102_1747813663_sinhnhat.webp', '/Project_Website/ProjectWeb/index.php?controller=policy&action=show&id=19', 1, 0, '<p>Chương tr&igrave;nh &aacute;p dụng v&agrave;o những dịp sinh nhật</p>\r\n', '2025-05-24 08:58:22', '2025-05-24 08:58:22', NULL, NULL),
(20, 'Chính Sách Bảo Mật', '1748077134_baomat_1.webp', '/Project_Website/ProjectWeb/index.php?controller=policy&action=show&id=20', 2, 0, '<p>Kh&aacute;ch h&agrave;ng lưu &yacute; đọc kĩ ch&iacute;nh s&aacute;ch trước khi tham gia mua h&agrave;ng</p>\r\n', '2025-05-24 08:58:54', '2025-05-24 08:58:54', NULL, NULL),
(21, 'Chính Sách Khách Hàng Thân Thiết', '1748077187_cs_khtt.webp', '/Project_Website/ProjectWeb/index.php?controller=policy&action=show&id=21', 3, 0, '<p>Chương tr&igrave;nh tr&ecirc;n chỉ d&agrave;nh cho kh&aacute;ch h&agrave;ng đ&atilde; mua h&agrave;ng tr&ecirc;n 2 năm</p>\r\n', '2025-05-24 08:59:47', '2025-05-24 08:59:47', NULL, NULL);

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
(10, 'Facebook', 'fab fa-facebook', 'https://www.facebook.com/nhuthuyhkvn', 1, 0, 'Liên Hệ ', '2025-05-24 09:00:13', '2025-05-24 09:00:13'),
(11, 'Youtu', 'fab fa-youtube', 'https://www.youtube.com/@phamnhuthuy7330', 2, 0, 'Liên Hệ ', '2025-05-24 09:00:43', '2025-05-24 09:00:43');

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
(18, 'Danh Mục Áo Tiêu Biểu', 'product', 'grid', 4, 0, '#', '', 1, '2025-05-24 08:52:32', '2025-05-24 08:52:32'),
(19, 'Sản Phẩm Quần', 'product', 'grid', 4, 0, '#', '', 2, '2025-05-24 08:53:05', '2025-05-24 08:53:05'),
(20, 'Danh Mục Hiện Có', 'category', 'grid', 4, 0, '#', '', 3, '2025-05-24 08:53:19', '2025-05-24 08:53:19');

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
(27, 18, 62, 'product', 0, '', '', 1, '2025-05-24 08:53:30', '2025-05-24 08:53:30'),
(28, 18, 63, 'product', 0, '', '', 2, '2025-05-24 08:53:34', '2025-05-24 08:53:34'),
(29, 18, 74, 'product', 0, '', '', 3, '2025-05-24 08:53:38', '2025-05-24 08:53:38'),
(30, 19, 61, 'product', 0, '', '', 1, '2025-05-24 08:53:50', '2025-05-24 08:53:50'),
(31, 19, 71, 'product', 0, '', '', 2, '2025-05-24 08:53:54', '2025-05-24 08:53:54'),
(32, 19, 69, 'product', 0, '', '', 3, '2025-05-24 08:53:59', '2025-05-24 08:53:59'),
(33, 19, 65, 'product', 0, '', '', 4, '2025-05-24 08:54:03', '2025-05-24 08:54:03'),
(34, 20, 13, 'category', 0, '', '', 1, '2025-05-24 08:54:14', '2025-05-24 08:54:14'),
(35, 20, 14, 'category', 0, '', '', 2, '2025-05-24 08:54:17', '2025-05-24 08:54:17'),
(36, 20, 11, 'category', 0, '', '', 3, '2025-05-24 08:54:22', '2025-05-24 08:54:22'),
(37, 20, 12, 'category', 0, '', '', 4, '2025-05-24 08:54:25', '2025-05-24 08:54:25');

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
  `payment_status` enum('pending','completed','failed','refunded') DEFAULT 'pending' COMMENT 'Trạng thái thanh toán',
  `transaction_id` varchar(255) DEFAULT NULL COMMENT 'Mã giao dịch của cổng thanh toán',
  `payment_bank` varchar(50) DEFAULT NULL COMMENT 'Ngân hàng thanh toán (nếu thanh toán qua ngân hàng)',
  `payment_response` text DEFAULT NULL COMMENT 'Thông tin phản hồi từ cổng thanh toán',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_User` int(11) DEFAULT NULL,
  `shipping_address_id` int(11) DEFAULT NULL COMMENT 'ID địa chỉ giao hàng',
  `note` text DEFAULT NULL,
  `shipping_fee` decimal(10,2) DEFAULT NULL,
  `hide` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id_product` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `import_price` decimal(10,2) DEFAULT NULL,
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

INSERT INTO `product` (`id_product`, `name`, `description`, `original_price`, `import_price`, `discount_percent`, `current_price`, `created_at`, `updated_at`, `id_Category`, `main_image`, `link`, `meta`, `hide`, `order`, `click_count`, `store`, `img2`, `img3`, `tag`, `CSDoiTra`, `CSGiaoHang`, `M`, `L`, `XL`) VALUES
(61, 'Quần Jean Nam', '<p><strong>Bảng Size</strong></p>\r\n\r\n<ul>\r\n	<li>Size 29 C&acirc;n nặng 50-60kg; V&ograve;ng Lưng: 76cm; V&ograve;ng Đ&ugrave;i: 59cm; D&agrave;i Quần: 95cm; Rộng Ống: 36cm</li>\r\n	<li>Size 30: C&acirc;n nặng 61-65kg; V&ograve;ng Lưng: 80cm; V&ograve;ng Đ&ugrave;i: 61cm; D&agrave;i Quần: 96cm; Rộng Ống: 37cm</li>\r\n	<li>Size 31: C&acirc;n nặng 66-70kg; V&ograve;ng Lưng: 84cm; V&ograve;ng Đ&ugrave;i: 63cm; D&agrave;i Quần: 97cm; Rộng Ống: 38cm</li>\r\n	<li>Size 32: C&acirc;n nặng 71-75kg; V&ograve;ng Lưng: 88cm; V&ograve;ng Đ&ugrave;i: 65&nbsp;cm; D&agrave;i Quần: 98cm; Rộng Ống: 39cm</li>\r\n	<li>Size 34: C&acirc;n nặng 76-80kg; V&ograve;ng Lưng: 92cm; V&ograve;ng Đ&ugrave;i: 67cm; D&agrave;i Quần 99cm; Rộng Ống: 40cm</li>\r\n	<li>Size 36: C&acirc;n nặng 81-85kg; V&ograve;ng Lưng: 96cm; V&ograve;ng Đ&ugrave;i: 69cm; D&agrave;i Quần: 100cm; Rộng Ống: 41cm</li>\r\n</ul>\r\n', 700000.00, 500000.00, 0, 700000.00, '2025-05-24 15:10:49', '2025-05-24 15:10:49', 11, '160_jean_239-7_0218d9e7d0e34ed9916235864e9f5966_1024x1024.jpg', NULL, NULL, 0, NULL, 0, 12, '160_jean_239-5_059cdc750a7a4879a80708e690e1ba7e_1024x1024.jpg', '160_jean_239-1_89fcf87205c843e399c5a8c97b7b693d_1024x1024.webp', 'Ao', '1747723170_doitra_1.webp', '1_cs_giaohanh.webp', 4, 4, 4),
(62, 'Áo Sơ Mi Linen Trắng', '<p>M&ocirc; tả: &Aacute;o sơ mi d&agrave;i tay chất vải linen cao cấp, kiểu d&aacute;ng thanh lịch.</p>\r\n\r\n<p>&nbsp;</p>\r\n', 200000.00, 100000.00, 0, 200000.00, '2025-05-24 15:24:53', '2025-05-24 15:24:53', 11, '160_somi_314-10_83d5c563ca6c4b51913d6a3c316be865_1024x1024.webp', NULL, NULL, 0, NULL, 0, 100, '160_somi_314-1_2ddf5926e8554b0a942714724245b710_1024x1024.jpg', '160_somi_314-7_ae81be7886b14c06845f4deb84c0c1dd_1024x1024.jpg', 'Ao', '1_doitra_1.webp', '2_cs_giaohanh.webp', 25, 50, 25),
(63, 'Áo Sơ Mi Sọc Trắng', '<p>M&ocirc; tả: &Aacute;o sơ mi d&agrave;i tay chất vải linen cao cấp, kiểu d&aacute;ng thanh lịch.</p>\r\n', 900000.00, 700000.00, 0, 900000.00, '2025-05-24 15:26:17', '2025-05-24 15:26:17', 11, 'ao-so-mi-nam-tay-ngan-icondenim-signature-form-slim__4__e916fe8202fd43edba25db144c767e47_1024x1024.webp', NULL, NULL, 0, NULL, 0, 50, 'ao-so-mi-nam-tay-ngan-icondenim-signature-form-slim__10__a568849eea3a42c083ad43c4d2bc4e88_1024x1024.webp', 'ao-so-mi-nam-tay-ngan-icondenim-signature-form-slim__8__5f40ac8f5f3c462abca6f415a18fc573_1024x1024.webp', '', '2_doitra_1.webp', '3_cs_giaohanh.webp', 25, 10, 15),
(65, 'Quần Tây Lưng Cao Dáng Rộng', '<p>M&ocirc; tả: Quần t&acirc;y ống rộng, ph&ugrave; hợp đi l&agrave;m hoặc phối phong c&aacute;ch vintage.</p>\r\n', 600000.00, 500000.00, 0, 600000.00, '2025-05-24 15:33:42', '2025-05-24 15:33:42', 12, '160_jean_239-7_0218d9e7d0e34ed9916235864e9f5966_1024x1024.jpg', NULL, NULL, 0, NULL, 0, 30, '160_jean_239-5_059cdc750a7a4879a80708e690e1ba7e_1024x1024.jpg', '160_jean_239-1_89fcf87205c843e399c5a8c97b7b693d_1024x1024.webp', 'Quan', '4_doitra_1.webp', '5_cs_giaohanh.webp', 10, 10, 10),
(68, 'Quần Jogger Thun Bo Gấu', '<p>M&ocirc; tả: Jogger thun thể thao, năng động, ph&ugrave; hợp cho tập gym hoặc dạo phố.</p>\r\n', 300000.00, 200000.00, 10, 270000.00, '2025-05-24 15:35:16', '2025-05-24 15:35:16', 12, 'procool_d562c83496a147e89e22e10d5c824b5b_1024x1024.webp', NULL, NULL, 0, NULL, 0, 5, 'quan-jeans-procool-icondenim-bright-sky-blue-form-slim_41a0bce260794e628bb6f82fd3ba40c7_1024x1024.webp', '3_2_1_160_jean_239-7_0218d9e7d0e34ed9916235864e9f5966_1024x1024.jpg', 'Quan', '7_doitra_1.webp', '8_cs_giaohanh.webp', 5, 0, 0),
(69, 'Quần Kaki Nam', '<p>&Aacute;o sử dụng vải lưới dệt với bề mặt tho&aacute;ng, nhẹ m&aacute;t, tạo hiệu ứng sọc dệt tự nhi&ecirc;n sống động. Chất vải mỏng nhẹ, tho&aacute;t kh&iacute; nhanh, gi&uacute;p người mặc lu&ocirc;n cảm thấy dễ chịu, m&aacute;t mẻ ngay cả khi vận động dưới trời nắng n&oacute;ng.</p>\r\n', 200000.00, 100000.00, 10, 180000.00, '2025-05-24 15:36:45', '2025-05-24 15:36:45', 12, 'qu_n_jean_nam_procool_icondenim_coolmax_indigo_slim_986387d69f914621b6f9b60299d55666_1024x1024.webp', NULL, NULL, 0, NULL, 0, 10, 'qjid0228_7608f66900dc4f94aa5d4213615e0cf6_1024x1024.webp', 'quan_jeans_straight_53d90d47aefa4a7b9490a3552a0fc484_1024x1024.webp', '', '8_doitra_1.webp', '9_cs_giaohanh.webp', 0, 10, 0),
(71, 'Quần Jeans Nam Procool ICONDENIM ', '<p>Chất liệu Denim&nbsp;đặc biệt si&ecirc;u m&aacute;t lạnh đạt chuẩn Coolmax&nbsp;gi&uacute;p cho người mặc vẫn thoải m&aacute;i trong c&aacute;c hoạt động thường ng&agrave;y. Chất liệu c&oacute; độ co d&atilde;n tốt, vừa vặn với mọi loại d&aacute;ng ch&acirc;n của ae. Với c&aacute;c chi tiết kh&aacute;c được tối ưu cho việc di chuyển, c&agrave;ng di chuyển c&agrave;ng m&aacute;t.</p>\r\n', 300000.00, 200000.00, 10, 270000.00, '2025-05-24 15:38:15', '2025-05-24 15:38:15', 12, 'quan-jeans-icondenim-checkered-jacquard-straight__1__88af88a47137497b8eec78ea686ca5a6_1024x1024.webp', NULL, NULL, 0, NULL, 0, 20, '2_1_qu_n_jean_nam_procool_icondenim_coolmax_indigo_slim_986387d69f914621b6f9b60299d55666_1024x1024.webp', '2_1_qjid0228_7608f66900dc4f94aa5d4213615e0cf6_1024x1024.webp', '', '10_doitra_1.webp', '11_cs_giaohanh.webp', 0, 10, 10),
(74, 'Áo Sơ Mi Trắng Sọc Caro', '<p>M&ocirc; tả: &Aacute;o croptop năng động, ph&ugrave; hợp phong c&aacute;ch trẻ trung, c&aacute; t&iacute;nh.</p>\r\n', 800000.00, 700000.00, 20, 640000.00, '2025-05-24 15:43:47', '2025-05-24 15:43:47', 11, '2_1_ao-so-mi-nam-tay-ngan-icondenim-signature-form-slim__4__e916fe8202fd43edba25db144c767e47_1024x1024.webp', NULL, NULL, 0, NULL, 0, 90, '2_1_ao-so-mi-nam-tay-ngan-icondenim-signature-form-slim__10__a568849eea3a42c083ad43c4d2bc4e88_1024x1024.webp', '2_1_ao-so-mi-nam-tay-ngan-icondenim-signature-form-slim__8__5f40ac8f5f3c462abca6f415a18fc573_1024x1024.webp', 'Ao', '13_doitra_1.webp', '14_cs_giaohanh.webp', 30, 30, 30),
(75, 'Thắt Lưng Nâu Da Cá Sấu', '<p>Bề mặt d&acirc;y được tạo điểm nhấn với họa tiết v&acirc;n nổi gi&uacute;p&nbsp;tăng độ b&aacute;m cho thắt lưng. Đầu kh&oacute;a kim loại gun satin sắc sảo kết hợp c&ugrave;ng logo ICONDENIM khắc laser tr&ecirc;n khoen da, tạo n&ecirc;n diện mạo nam t&iacute;nh v&agrave; sang trọng.</p>\r\n', 200000.00, 100000.00, 10, 180000.00, '2025-05-24 15:45:29', '2025-05-24 15:45:29', 14, '160_that_lung_028-2_8746d287a174450b9cb131b7822aee0c_1024x1024.webp', NULL, NULL, 0, NULL, 0, 30, '160_that_lung_028-8_cfae8cb2c2e449a8b6ce3dd7566290f7_1024x1024.webp', 'z5905294055603_25d25c7c2604726100b0718c9000105c_598bc3fc118245bdaf21e2514b7b0763_1024x1024.webp', 'ThatLung', '14_doitra_1.webp', '15_cs_giaohanh.webp', 10, 10, 10),
(77, 'Mũ Lưỡi Trai Nam ICONDENIM America', '<p><strong>▶️ CHẤT LIỆU KAKI</strong></p>\r\n\r\n<p>N&oacute;n lưỡi trai được l&agrave;m từ chất liệu kaki, c&oacute; độ bền cao, khả năng tho&aacute;ng kh&iacute; v&agrave; mang đến sự thoải m&aacute;i khi đội. Chất liệu n&agrave;y cũng giữ được form d&aacute;ng ổn định, tạo cảm gi&aacute;c chắc chắn v&agrave; vừa vặn khi sử dụng, th&iacute;ch hợp để sử dụng h&agrave;ng ng&agrave;y trong nhiều ho&agrave;n cảnh kh&aacute;c nhau, từ dạo phố đến c&aacute;c hoạt động ngo&agrave;i trời.</p>\r\n', 300000.00, 200000.00, 5, 285000.00, '2025-05-24 15:48:53', '2025-05-24 15:48:53', 14, '160_non_037-9_915e4a02cbf245e292c353e1fbefea41_large.jpg', NULL, NULL, 0, NULL, 0, 14, '160_non_037-10_78e0dc32877c4b78892e7c0fb2473777_1024x1024.jpg', '160_non_037-7_fae6d201b3f84a92846a230ff7e37b29_1024x1024.jpg', 'PhuKien,Non', '16_doitra_1.webp', '17_cs_giaohanh.webp', 7, 7, 0),
(78, 'Nón Bucket Nam ICONDENIM Orgnls Jeans Wash', '<p><strong>▶️ CHẤT LIỆU DENIM</strong></p>\r\n\r\n<p>Mũ sử dụng chất liệu denim jean wash bền bỉ, mềm mại v&agrave; phong c&aacute;ch. Denim kh&ocirc;ng chỉ chịu m&agrave;i m&ograve;n tốt m&agrave; c&ograve;n mang lại vẻ bụi bặm, thời trang, rất ph&ugrave; hợp với phong c&aacute;ch streetwear năng động</p>\r\n\r\n<p><strong>▶️ THIẾT KẾ &Yacute; NGHĨA</strong></p>\r\n\r\n<p>Điểm nhấn của mũ l&agrave; chi tiết th&ecirc;u nổi với biểu tượng vương miện &quot;ORGNLS&quot; v&agrave; slogan &quot;Embrace the journey and let the current guide you&quot; truyền tải th&ocirc;ng điệp sống t&iacute;ch cực. Font chữ graphic viết tay tạo cảm gi&aacute;c tự do, quyền lực v&agrave; dứt kho&aacute;t.</p>\r\n\r\n<p><strong>▶️ FORM BUCKET</strong></p>\r\n\r\n<p>D&aacute;ng mũ bucket với v&agrave;nh rộng vừa phải mang đến sự thoải m&aacute;i v&agrave; tiện dụng, ph&ugrave; hợp cho nhiều khu&ocirc;n mặt. Form d&aacute;ng vừa gi&uacute;p che chắn &aacute;nh nắng tốt vừa tạo n&ecirc;n vẻ năng động, dễ d&agrave;ng phối hợp với c&aacute;c loại trang phục từ casual đến sporty.</p>\r\n', 400000.00, 250000.00, 5, 380000.00, '2025-05-24 15:51:03', '2025-05-24 15:51:03', 14, 'non-bucket-icondenim-orgnls-jean-wash__2__4400f254814d468cba441160bbb7e856_1024x1024.jpg', NULL, NULL, 0, NULL, 0, 6, 'z6025237282432_3201164e89de26a638dfd644f9d01750_2c567e0534754ffdae70d74a0d481fcd_1024x1024.webp', 'z6025237351828_9a2dcff5e55996bb4b561ac3cad34e83_1209ba8acbb944378244e1ed40d73fe4_1024x1024.webp', '', '17_doitra_1.webp', '18_cs_giaohanh.webp', 6, 0, 0),
(79, 'Nón Bucket Nam ICONDENIM Orgnls Jeans Wash', '<p><strong>▶️ CHẤT LIỆU DENIM</strong></p>\r\n\r\n<p>Mũ sử dụng chất liệu denim jean wash bền bỉ, mềm mại v&agrave; phong c&aacute;ch. Denim kh&ocirc;ng chỉ chịu m&agrave;i m&ograve;n tốt m&agrave; c&ograve;n mang lại vẻ bụi bặm, thời trang, rất ph&ugrave; hợp với phong c&aacute;ch streetwear năng động</p>\r\n\r\n<p><strong>▶️ THIẾT KẾ &Yacute; NGHĨA</strong></p>\r\n\r\n<p>Điểm nhấn của mũ l&agrave; chi tiết th&ecirc;u nổi với biểu tượng vương miện &quot;ORGNLS&quot; v&agrave; slogan &quot;Embrace the journey and let the current guide you&quot; truyền tải th&ocirc;ng điệp sống t&iacute;ch cực. Font chữ graphic viết tay tạo cảm gi&aacute;c tự do, quyền lực v&agrave; dứt kho&aacute;t.</p>\r\n\r\n<p><strong>▶️ FORM BUCKET</strong></p>\r\n\r\n<p>D&aacute;ng mũ bucket với v&agrave;nh rộng vừa phải mang đến sự thoải m&aacute;i v&agrave; tiện dụng, ph&ugrave; hợp cho nhiều khu&ocirc;n mặt. Form d&aacute;ng vừa gi&uacute;p che chắn &aacute;nh nắng tốt vừa tạo n&ecirc;n vẻ năng động, dễ d&agrave;ng phối hợp với c&aacute;c loại trang phục từ casual đến sporty.</p>\r\n', 400000.00, 250000.00, 5, 380000.00, '2025-05-24 15:51:03', '2025-05-24 16:05:59', 14, '1_non-bucket-icondenim-orgnls-jean-wash__2__4400f254814d468cba441160bbb7e856_1024x1024.jpg', NULL, NULL, 1, NULL, 0, 6, '1_z6025237282432_3201164e89de26a638dfd644f9d01750_2c567e0534754ffdae70d74a0d481fcd_1024x1024.webp', '1_z6025237351828_9a2dcff5e55996bb4b561ac3cad34e83_1209ba8acbb944378244e1ed40d73fe4_1024x1024.webp', '', '18_doitra_1.webp', '19_cs_giaohanh.webp', 6, 0, 0),
(80, 'Áo Thun Nam Superior Form Oversize', '<p><strong>▶️ CHẤT&nbsp;VẢI INTERLOCK M&Aacute;T MẺ</strong></p>\r\n\r\n<p>Chất vải Interlock dệt đ&ocirc;i cho cảm gi&aacute;c mềm mịn, tho&aacute;ng kh&iacute;.&nbsp;Mặc l&ecirc;n m&aacute;t, kh&ocirc;ng b&iacute;, l&yacute; tưởng cho thời tiết oi n&oacute;ng. Giữ form ổn định, hạn chế bai d&atilde;o&nbsp;đồng h&agrave;nh c&ugrave;ng bạn suốt ng&agrave;y d&agrave;i hoạt động.</p>\r\n\r\n<p>▶️&nbsp;<strong>HỌA TIẾT SỐ ĐẬM CHẤT THỂ THAO STREETWEAR</strong></p>\r\n\r\n<p>Thiết kế số nổi bật trước ngực đi c&ugrave;ng d&ograve;ng chữ&nbsp;SUPERIOR&nbsp;ph&iacute;a sau lưng sẽ tạo n&ecirc;n c&aacute; t&iacute;nh mạnh mẽ, năng động. M&agrave;u in tương phản sắc n&eacute;t, bền m&agrave;u sau khi sử dụng.</p>\r\n\r\n<p>▶️&nbsp;<strong>FORM OVERSIZE PH&Oacute;NG KHO&Aacute;NG DỄ MẶC</strong></p>\r\n\r\n<p>Form rộng nhẹ, che khuyết điểm tốt m&agrave; vẫn giữ được&nbsp;t&ocirc;n d&aacute;ng. Ph&ugrave; hợp với nhiều v&oacute;c d&aacute;ng nam giới hiện đại, đặc biệt l&agrave; c&aacute;c bạn trẻ y&ecirc;u phong c&aacute;ch H&agrave;n &ndash; Nhật.</p>\r\n', 300000.00, 200000.00, 10, 270000.00, '2025-05-24 16:05:48', '2025-05-24 16:05:48', 11, '1_160_ao_thun_486-7_192a8a84946943ba809cf83eda40e528_1024x1024.jpg', NULL, NULL, 0, NULL, 0, 4, '1_160_ao_thun_486-11_048e677a305a4d288f3a40f83e0cd12b_1024x1024.jpg', '1_160_ao_thun_486-13_61b0f05164cd46859fb972f67dfc2d33_1024x1024.webp', 'AO', '19_doitra_1.webp', '20_cs_giaohanh.webp', 4, 0, 0),
(81, 'Áo Thun Nam Sundaze Rush Form Regular', '<p>Vải cotton định lượng 220GSM d&agrave;y dặn vừa phải, bề mặt mịn, &iacute;t x&ugrave; l&ocirc;ng. Ưu điểm thấm h&uacute;t tốt v&agrave; tho&aacute;ng kh&iacute;, mang lại cảm gi&aacute;c thoải m&aacute;i khi mặc trong ng&agrave;y nắng n&oacute;ng. Chất vải giữ phom chuẩn sau nhiều lần giặt.</p>\r\n', 400000.00, 200000.00, 20, 320000.00, '2025-05-24 16:07:40', '2025-05-24 16:07:40', 11, '1_item2.webp', NULL, NULL, 0, NULL, 0, 50, '1_item10.webp', '2_1_1_160_ao_thun_486-13_61b0f05164cd46859fb972f67dfc2d33_1024x1024.webp', 'Ao', '20_doitra_1.webp', '21_cs_giaohanh.webp', 0, 50, 0);

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
(1, 'site_name', 'SR Store', 'general', 'text', 'Tên website', 'Tên hiển thị của website', '2025-05-16 08:07:15', '2025-05-24 08:51:42'),
(2, 'site_description', 'Website bán quần áo thời trang', 'general', 'textarea', 'Mô tả website', 'Mô tả ngắn về website', '2025-05-16 08:07:15', '2025-05-24 08:51:42'),
(3, 'admin_email', 'admin@example.com', 'contact', 'email', 'Email quản trị', 'Email liên hệ của quản trị viên', '2025-05-16 08:07:15', '2025-05-24 08:51:42'),
(4, 'contact_phone', '0123456789', 'contact', 'text', 'Số điện thoại liên hệ', 'Số điện thoại hiển thị trên website', '2025-05-16 08:07:15', '2025-05-24 08:51:42'),
(5, 'contact_address', '123 Đường ABC, Quận XYZ, TP HCM', 'contact', 'textarea', 'Địa chỉ', 'Địa chỉ liên hệ', '2025-05-16 08:07:15', '2025-05-24 08:51:42'),
(6, 'logo_path', '/Project_Website/ProjectWeb/upload/img/Header/logo.png', 'appearance', 'file', 'Logo', 'Đường dẫn đến file logo', '2025-05-16 08:07:15', '2025-05-16 08:07:15'),
(7, 'favicon_path', '/Project_Website/ProjectWeb/upload/img/Header/favicon.ico', 'appearance', 'file', 'Favicon', 'Đường dẫn đến file favicon', '2025-05-16 08:07:15', '2025-05-24 08:51:42'),
(8, 'currency', 'VND', 'shop', 'select', 'Đơn vị tiền tệ', 'Đơn vị tiền tệ sử dụng trên website', '2025-05-16 08:07:15', '2025-05-24 08:51:42'),
(9, 'shipping_fee', '30000', 'shop', 'number', 'Phí vận chuyển', 'Phí vận chuyển mặc định', '2025-05-16 08:07:15', '2025-05-24 08:51:42'),
(10, 'tax_rate', '10', 'shop', 'number', 'Thuế VAT (%)', 'Tỷ lệ thuế VAT áp dụng', '2025-05-16 08:07:15', '2025-05-24 08:51:42'),
(11, 'maintenance_mode', '0', 'system', 'boolean', 'Chế độ bảo trì', 'Bật/tắt chế độ bảo trì website', '2025-05-16 08:07:15', '2025-05-24 08:51:42');

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
(1, 'Phạm Nhựt Huy', 'nhuthuyhk9@gmail.com', 'Huy123@gmail.com', '1234567890', 'Thành Phố Hồ Chí Minh', 'admin', 'NULL', 0, NULL, NULL, '2025-05-24 14:55:14', '2025-05-24 14:55:14', NULL),
(16, 'Phan Văn Huy', 'huy@gmail.com', 'NhutHuy@123', '1234567811', 'Tiền Giang', 'admin', NULL, 0, NULL, NULL, '2025-05-24 16:09:35', '2025-05-24 16:09:35', 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id_Category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `footer_payment_methods`
--
ALTER TABLE `footer_payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `footer_policies`
--
ALTER TABLE `footer_policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `footer_social_media`
--
ALTER TABLE `footer_social_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `home_sections`
--
ALTER TABLE `home_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `home_section_items`
--
ALTER TABLE `home_section_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id_Order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

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
  MODIFY `id_User` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_address`
--
ALTER TABLE `user_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `visits`
--
ALTER TABLE `visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
