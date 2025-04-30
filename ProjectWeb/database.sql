  -- phpMyAdmin SQL Dump
  -- version 5.2.1
  -- https://www.phpmyadmin.net/
  --
  -- Máy chủ: 127.0.0.1
  -- Thời gian đã tạo: Th4 29, 2025 lúc 09:07 AM
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
    `ID` int(11) NOT NULL,
    `name` varchar(255) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  --
  -- Đang đổ dữ liệu cho bảng `categories`
  --

  INSERT INTO `categories` (`ID`, `name`) VALUES
  (1, 'Quần Nam'),
  (2, 'Áo Nữ');

  -- --------------------------------------------------------

  --
  -- Cấu trúc bảng cho bảng `products`
  --

  CREATE TABLE `products` (
    `id` int(11) NOT NULL,
    `name` varchar(255) NOT NULL,
    `image` varchar(255) NOT NULL,
    `price` decimal(10,2) NOT NULL,
    `hide` tinyint(4) DEFAULT NULL,
    `description` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `category_id` varchar(50) NOT NULL,
    `click_count` int(11) DEFAULT 0,
    `sale` int(11) DEFAULT 0,
    `price_sale` decimal(10,2) DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  --
  -- Đang đổ dữ liệu cho bảng `products`
  --

  INSERT INTO `products` (`id`, `name`, `image`, `price`, `hide`, `description`, `created_at`, `category_id`, `click_count`, `sale`, `price_sale`) VALUES
  (1, 'Áo Polo Nam Procool ICONDENIM Seam Sealing', '/ProjectWeb/upload/img/Home/item1.webp', 329000.00, 0, 'Áo Polo Nam Procool ICONDENIM Seam Sealing', '2025-04-20 17:11:53', '1', 4, 0, NULL),
  (2, 'Áo Thun Nam Procool ICONDENIM Seam Sealing', '/ProjectWeb/upload/img/Home/item2.webp', 299000.00, 0, 'Áo Thun Nam Procool ICONDENIM Seam Sealing', '2025-04-20 17:11:53', '1', 5, 0, NULL),
  (3, 'Quần Jean Nam Procool ICONDENIM CoolMax Black Slim', '/ProjectWeb/upload/img/Home/item3.webp', 549000.00, 0, 'Quần Jean Nam Procool ICONDENIM CoolMax Black Slim', '2025-04-20 17:11:53', '', 5, 0, NULL),
  (4, 'Quần Jean Nam ProCOOL ICONDENIM CoolMax Light Blue Slim', '/ProjectWeb/upload/img/Home/item4.webp', 549000.00, 0, 'Quần Jean Nam ProCOOL ICONDENIM CoolMax Light Blue Slim', '2025-04-20 17:11:53', '', 6, 0, NULL),
  (5, 'Áo Thun Nam ICONDENIM Atheltics Champion', '/ProjectWeb/upload/img/Home/item8.webp', 299000.00, 0, 'Áo Thun Nam ICONDENIM Atheltics Champion', '2025-04-20 17:11:53', '', 0, 0, NULL),
  (6, 'Set Đồ Nam ICONDENIM Rugby Football', '/ProjectWeb/upload/img/Home/item9.webp', 799000.00, 0, 'Set Đồ Nam ICONDENIM Rugby Football', '2025-04-20 17:11:53', '', 0, 0, NULL),
  (7, 'Áo Polo Nam ICONDENIM Horizontal Stripped', '/ProjectWeb/upload/img/Home/item10.webp', 329000.00, 0, 'Áo Polo Nam ICONDENIM Horizontal Stripped', '2025-04-20 17:11:53', '', 0, 0, NULL),
  (8, 'Áo Thun Nam ICONDENIM Edge Striped', '/ProjectWeb/upload/img/Home/item1.webp', 299000.00, 0, 'Áo Thun Nam ICONDENIM Edge Striped', '2025-04-20 17:11:53', '', 0, 0, NULL),
  (9, 'Quần Short Jean Nam ICONDENIM Mid Blue Regular', '/ProjectWeb/upload/img/Home/item2.webp', 359000.00, 0, 'Quần Short Jean Nam ICONDENIM Mid Blue Regular', '2025-04-20 17:11:53', '', 0, 0, NULL),
  (10, 'Áo Thun Nam ICONDENIM Basic Form Regular', '/ProjectWeb/upload/img/Home/item3.webp', 199000.00, 0, 'Áo Thun Nam ICONDENIM Basic Form Regular', '2025-04-20 17:11:53', '', 0, 5, NULL),
  (11, 'Quần Tây Nam ICONDENIM Straight Neutral Basic', '/ProjectWeb/upload/img/Home/item4.webp', 499000.00, 0, 'Quần Tây Nam ICONDENIM Straight Neutral Basic', '2025-04-20 17:11:53', '', 0, 6, NULL),
  (12, 'Quần Short Kaki Nam ICONDENIM Garment Dye', '/ProjectWeb/upload/img/Home/item5.webp', 359000.00, 0, 'Quần Short Kaki Nam ICONDENIM Garment Dye', '2025-04-20 17:11:53', '', 0, 12, NULL),
  (17, 'Áo Thun Nam ICONDENIM Basic Form Regular', '/ProjectWeb/upload/img/Home/item3.webp', 199000.00, 0, 'Áo Thun Nam ICONDENIM Basic Form Regular', '2025-04-20 17:11:53', '', 0, 5, NULL),
  (18, 'Áo Polo Nam ICONDENIM Horizontal Stripped', '/ProjectWeb/upload/img/Home/item10.webp', 329000.00, 0, 'Áo Polo Nam ICONDENIM Horizontal Stripped', '2025-04-20 17:11:53', '', 0, 8, NULL);

  --
  -- Chỉ mục cho các bảng đã đổ
  --

  --
  -- Chỉ mục cho bảng `products`
  --
  ALTER TABLE `products`
    ADD PRIMARY KEY (`id`);

  --
  -- AUTO_INCREMENT cho các bảng đã đổ
  --

  --
  -- AUTO_INCREMENT cho bảng `products`
  --
  ALTER TABLE `products`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
  COMMIT;

  /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
  /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
  /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
