-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 30, 2024
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
-- Cơ sở dữ liệu: `database`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `hide` tinyint(1) DEFAULT 0,
  `order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`name`, `link`, `meta_title`, `meta_description`, `meta_keywords`, `hide`, `order`) VALUES
('Quần Nam', 'quan-nam', 'Quần Nam Thời Trang', 'Quần nam đa dạng kiểu dáng, chất liệu', 'quan nam, thoi trang nam', 0, 1),
('Áo Nam', 'ao-nam', 'Áo Nam Thời Trang', 'Áo nam phong cách, trẻ trung', 'ao nam, thoi trang nam', 0, 2),
('Quần Nữ', 'quan-nu', 'Quần Nữ Thời Trang', 'Quần nữ đa dạng kiểu dáng, chất liệu', 'quan nu, thoi trang nu', 0, 3),
('Áo Nữ', 'ao-nu', 'Áo Nữ Thời Trang', 'Áo nữ phong cách, trẻ trung', 'ao nu, thoi trang nu', 0, 4),
('Phụ Kiện', 'phu-kien', 'Phụ Kiện Thời Trang', 'Phụ kiện thời trang đa dạng', 'phu kien, thoi trang', 0, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `link` varchar(255) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `hide` tinyint(1) DEFAULT 0,
  `order` int(11) DEFAULT 0,
  `sale` int(11) DEFAULT 0,
  `price_sale` decimal(10,2) DEFAULT NULL,
  `click_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`name`, `image`, `price`, `description`, `category_id`, `link`, `meta_title`, `meta_description`, `meta_keywords`, `hide`, `order`, `sale`, `price_sale`, `click_count`) VALUES
('Quần Jean Nam Slim', '/upload/products/jean-nam-slim.jpg', 499000.00, 'Quần jean nam slim fit', 1, 'quan-jean-nam-slim', 'Quần Jean Nam Slim', 'Quần jean nam slim fit chất lượng cao', 'quan jean, quan nam', 0, 1, 10, 449000.00, 0),
('Áo Thun Nam Cổ Tròn', '/upload/products/ao-thun-nam.jpg', 199000.00, 'Áo thun nam cổ tròn', 2, 'ao-thun-nam-co-tron', 'Áo Thun Nam Cổ Tròn', 'Áo thun nam cổ tròn chất liệu cotton', 'ao thun, ao nam', 0, 2, 0, NULL, 0),
('Quần Jean Nữ Skinny', '/upload/products/jean-nu-skinny.jpg', 399000.00, 'Quần jean nữ skinny', 3, 'quan-jean-nu-skinny', 'Quần Jean Nữ Skinny', 'Quần jean nữ skinny fit', 'quan jean, quan nu', 0, 3, 15, 339000.00, 0),
('Áo Sơ Mi Nữ', '/upload/products/ao-so-mi-nu.jpg', 299000.00, 'Áo sơ mi nữ', 4, 'ao-so-mi-nu', 'Áo Sơ Mi Nữ', 'Áo sơ mi nữ công sở', 'ao so mi, ao nu', 0, 4, 0, NULL, 0),
('Túi Xách Nữ', '/upload/products/tui-xach-nu.jpg', 599000.00, 'Túi xách nữ', 5, 'tui-xach-nu', 'Túi Xách Nữ', 'Túi xách nữ thời trang', 'tui xach, phu kien', 0, 5, 20, 479000.00, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `hide` tinyint(1) DEFAULT 0,
  `order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `news`
--

INSERT INTO `news` (`title`, `content`, `image`, `link`, `meta_title`, `meta_description`, `meta_keywords`, `hide`, `order`) VALUES
('Xu hướng thời trang 2024', 'Nội dung về xu hướng thời trang 2024...', '/upload/news/xu-huong-2024.jpg', 'xu-huong-thoi-trang-2024', 'Xu hướng thời trang 2024', 'Cập nhật xu hướng thời trang mới nhất 2024', 'xu huong, thoi trang', 0, 1),
('Cách mix đồ mùa hè', 'Hướng dẫn cách mix đồ mùa hè...', '/upload/news/mix-do-mua-he.jpg', 'cach-mix-do-mua-he', 'Cách mix đồ mùa hè', 'Bí quyết mix đồ mùa hè đẹp', 'mix do, thoi trang', 0, 2),
('Bảo quản quần áo đúng cách', 'Hướng dẫn bảo quản quần áo...', '/upload/news/bao-quan.jpg', 'bao-quan-quan-ao', 'Bảo quản quần áo đúng cách', 'Cách bảo quản quần áo bền đẹp', 'bao quan, quan ao', 0, 3),
('Phong cách thời trang công sở', 'Gợi ý phong cách thời trang công sở...', '/upload/news/cong-so.jpg', 'phong-cach-cong-so', 'Phong cách thời trang công sở', 'Phong cách thời trang công sở chuyên nghiệp', 'cong so, thoi trang', 0, 4),
('Mẹo chọn size quần áo', 'Hướng dẫn chọn size quần áo...', '/upload/news/chon-size.jpg', 'meo-chon-size', 'Mẹo chọn size quần áo', 'Cách chọn size quần áo phù hợp', 'chon size, quan ao', 0, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `link` varchar(255) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `hide` tinyint(1) DEFAULT 0,
  `order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customers`
--

INSERT INTO `customers` (`name`, `email`, `password`, `phone`, `address`, `link`, `meta_title`, `meta_description`, `meta_keywords`, `hide`, `order`) VALUES
('Nguyễn Văn A', 'nguyenvana@email.com', 'password123', '0123456789', 'Hà Nội', 'nguyen-van-a', 'Nguyễn Văn A', 'Thông tin khách hàng Nguyễn Văn A', 'khach hang', 0, 1),
('Trần Thị B', 'tranthib@email.com', 'password123', '0987654321', 'TP.HCM', 'tran-thi-b', 'Trần Thị B', 'Thông tin khách hàng Trần Thị B', 'khach hang', 0, 2),
('Lê Văn C', 'levanc@email.com', 'password123', '0369852147', 'Đà Nẵng', 'le-van-c', 'Lê Văn C', 'Thông tin khách hàng Lê Văn C', 'khach hang', 0, 3),
('Phạm Thị D', 'phamthid@email.com', 'password123', '0587412369', 'Hải Phòng', 'pham-thi-d', 'Phạm Thị D', 'Thông tin khách hàng Phạm Thị D', 'khach hang', 0, 4),
('Hoàng Văn E', 'hoangvane@email.com', 'password123', '0741258963', 'Cần Thơ', 'hoang-van-e', 'Hoàng Văn E', 'Thông tin khách hàng Hoàng Văn E', 'khach hang', 0, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `link` varchar(255) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `hide` tinyint(1) DEFAULT 0,
  `order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`customer_id`, `total_amount`, `status`, `payment_method`, `shipping_address`, `link`, `meta_title`, `meta_description`, `meta_keywords`, `hide`, `order`) VALUES
(1, 898000.00, 'completed', 'COD', 'Hà Nội', 'don-hang-1', 'Đơn hàng #1', 'Đơn hàng của Nguyễn Văn A', 'don hang', 0, 1),
(2, 499000.00, 'pending', 'Bank Transfer', 'TP.HCM', 'don-hang-2', 'Đơn hàng #2', 'Đơn hàng của Trần Thị B', 'don hang', 0, 2),
(3, 1298000.00, 'processing', 'COD', 'Đà Nẵng', 'don-hang-3', 'Đơn hàng #3', 'Đơn hàng của Lê Văn C', 'don hang', 0, 3),
(4, 299000.00, 'completed', 'Bank Transfer', 'Hải Phòng', 'don-hang-4', 'Đơn hàng #4', 'Đơn hàng của Phạm Thị D', 'don hang', 0, 4),
(5, 599000.00, 'cancelled', 'COD', 'Cần Thơ', 'don-hang-5', 'Đơn hàng #5', 'Đơn hàng của Hoàng Văn E', 'don hang', 0, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 449000.00),
(1, 2, 1, 199000.00),
(2, 3, 1, 399000.00),
(3, 1, 2, 449000.00),
(3, 4, 1, 299000.00),
(4, 2, 1, 199000.00),
(5, 5, 1, 599000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `link` varchar(255) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `hide` tinyint(1) DEFAULT 0,
  `order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `settings`
--

INSERT INTO `settings` (`key`, `value`, `link`, `meta_title`, `meta_description`, `meta_keywords`, `hide`, `order`) VALUES
('site_name', 'RentEase', 'site-name', 'Tên website', 'Tên của website', 'ten website', 0, 1),
('site_description', 'Thời trang nam nữ', 'site-description', 'Mô tả website', 'Mô tả của website', 'mo ta website', 0, 2),
('contact_email', 'contact@rentease.com', 'contact-email', 'Email liên hệ', 'Email liên hệ của website', 'email, lien he', 0, 3),
('contact_phone', '0123456789', 'contact-phone', 'Số điện thoại', 'Số điện thoại liên hệ', 'dien thoai, lien he', 0, 4),
('address', 'Hà Nội, Việt Nam', 'address', 'Địa chỉ', 'Địa chỉ của cửa hàng', 'dia chi', 0, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `policies`
--

CREATE TABLE `policies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `link` varchar(255) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `hide` tinyint(1) DEFAULT 0,
  `order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `policies`
--

INSERT INTO `policies` (`title`, `content`, `type`, `link`, `meta_title`, `meta_description`, `meta_keywords`, `hide`, `order`) VALUES
('Chính sách đổi trả', 'Nội dung chính sách đổi trả...', 'return', 'chinh-sach-doi-tra', 'Chính sách đổi trả', 'Chính sách đổi trả hàng', 'doi tra, chinh sach', 0, 1),
('Chính sách bảo mật', 'Nội dung chính sách bảo mật...', 'privacy', 'chinh-sach-bao-mat', 'Chính sách bảo mật', 'Chính sách bảo mật thông tin', 'bao mat, chinh sach', 0, 2),
('Chính sách vận chuyển', 'Nội dung chính sách vận chuyển...', 'shipping', 'chinh-sach-van-chuyen', 'Chính sách vận chuyển', 'Chính sách vận chuyển hàng', 'van chuyen, chinh sach', 0, 3),
('Chính sách thanh toán', 'Nội dung chính sách thanh toán...', 'payment', 'chinh-sach-thanh-toan', 'Chính sách thanh toán', 'Chính sách thanh toán', 'thanh toan, chinh sach', 0, 4),
('Điều khoản sử dụng', 'Nội dung điều khoản sử dụng...', 'terms', 'dieu-khoan-su-dung', 'Điều khoản sử dụng', 'Điều khoản sử dụng website', 'dieu khoan, su dung', 0, 5);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */; 