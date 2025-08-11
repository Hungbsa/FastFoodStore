-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th8 10, 2025 lúc 03:47 AM
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
-- Cơ sở dữ liệu: `fastfood`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `label` varchar(100) NOT NULL COMMENT 'Tên gọi nhớ',
  `fullname` varchar(100) NOT NULL COMMENT 'Họ tên đầy đủ',
  `email` varchar(100) DEFAULT NULL COMMENT 'Địa chỉ email',
  `phone` varchar(20) NOT NULL COMMENT 'Số điện thoại',
  `address` text NOT NULL COMMENT 'Địa chỉ chi tiết',
  `city` varchar(50) DEFAULT NULL COMMENT 'Thành phố',
  `district` varchar(50) DEFAULT NULL COMMENT 'Quận/Huyện',
  `is_default` tinyint(1) DEFAULT 0 COMMENT 'Địa chỉ mặc định (1: mặc định, 0: không mặc định)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Thời gian tạo',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Thời gian cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `label`, `fullname`, `email`, `phone`, `address`, `city`, `district`, `is_default`, `created_at`, `updated_at`) VALUES
(10, 13, 'see', '', NULL, '0277294123', 'Hẻm 462 Nguyễn Tri Phương, Phường Vườn Lài, Thành phố Hồ Chí Minh, 72712, Việt Nam', NULL, NULL, 0, '2025-07-27 01:43:08', '2025-07-27 01:43:08'),
(11, 14, 'ds', '', NULL, '02984729482', '337, Đường Hòa Hảo, Phường Vườn Lài, Thành phố Hồ Chí Minh, 72712, Việt Nam', NULL, NULL, 0, '2025-08-04 01:52:28', '2025-08-04 02:03:35'),
(12, 14, 'hung', '', NULL, '0828947744', 'Lương Nhữ Học, Phường Chợ Lớn, Thành phố Hồ Chí Minh, 73000, Việt Nam', NULL, NULL, 1, '2025-08-04 01:53:29', '2025-08-04 02:03:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `adm_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`adm_id`, `username`, `password`, `email`, `code`, `date`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin@gmail.com', NULL, '2025-07-26 10:52:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_type` enum('fixed','percentage') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `times_used` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_type`, `discount_value`, `max_discount`, `expiry_date`, `usage_limit`, `times_used`, `active`, `created_at`) VALUES
(1, 'SALE10', 'percentage', 10.00, 10.00, '2023-12-31 23:59:59', 100, 0, 1, '2025-06-30 03:51:20'),
(2, 'DISCOUNT5', 'percentage', 50.00, 95000.00, '2026-10-31 00:00:00', 200, 0, 1, '2025-06-30 03:51:20'),
(3, 'SUMMER15', 'percentage', 15.00, NULL, '2023-12-31 23:59:59', NULL, 0, 1, '2025-06-30 03:51:20'),
(4, 'WELCOME2025', 'fixed', 50000.00, 25000.00, '2025-12-01 00:00:00', NULL, 0, 1, '2025-06-30 03:51:20'),
(5, 'SPECIAL8', 'fixed', 200000.00, NULL, '2023-12-31 23:59:59', 1, 0, 1, '2025-06-30 03:51:20'),
(6, 'LOYALTY5', 'percentage', 5.00, NULL, NULL, NULL, 0, 0, '2025-06-30 03:51:20'),
(7, 'EXPIRED', 'percentage', 10.00, NULL, '2022-12-31 23:59:59', NULL, 0, 1, '2025-06-30 03:51:20'),
(8, 'LIMITED', 'fixed', 125000.00, NULL, NULL, 10, 10, 1, '2025-06-30 03:51:20'),
(9, 'INACTIVE', 'percentage', 15.00, NULL, NULL, NULL, 0, 0, '2025-06-30 03:51:20'),
(10, 'MEGA25', 'percentage', 25.00, 30.00, NULL, NULL, 0, 1, '2025-06-30 03:51:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dishes`
--

CREATE TABLE `dishes` (
  `d_id` int(222) NOT NULL,
  `rs_id` int(222) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slogan` text NOT NULL,
  `description` text NOT NULL,
  `dish_category_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `calories` int(11) DEFAULT NULL,
  `img` varchar(222) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dishes`
--

INSERT INTO `dishes` (`d_id`, `rs_id`, `title`, `slogan`, `description`, `dish_category_id`, `price`, `calories`, `img`, `is_available`) VALUES
(1, 1, 'Yorkshire Lamb Patties', 'Lamb patties which melt in your mouth, and are quick and easy to make. Served hot with a crisp salad.', 'Succulent lamb patties, a flavorful and easy-to-make dish, best served with a fresh salad.', 2, 350000.00, 350, '62908867a48e4.jpg', 1),
(2, 1, 'Lobster Thermidor', 'Lobster Thermidor is a French dish of lobster meat cooked in a rich wine sauce, stuffed back into a lobster shell, and browned.', 'A classic French dish featuring rich lobster meat in a creamy wine sauce, baked to perfection in its shell.', 2, 900000.00, 600, '629089fee52b9.jpg', 1),
(3, 4, 'Chicken Madeira', 'Chicken Madeira, like Chicken Marsala, is made with chicken, mushrooms, and a special fortified wine. But, the wines are different;', 'Chicken Madeira, like Chicken Marsala, is made with chicken, mushrooms, and a special fortified wine. But, the wines are different;', 2, 575000.00, 480, '62908bdf2f581.jpg', 1),
(4, 1, 'Stuffed Jacket Potatoes', 'Deep fry whole potatoes in oil for 8-10 minutes or coat each potato with little oil. Mix the onions, garlic, tomatoes and mushrooms. Add yoghurt, ginger, garlic, chillies, coriander', 'Whole potatoes deep-fried or lightly oiled, mixed with a savory blend of onions, garlic, tomatoes, mushrooms, and spices.', 1, 200000.00, 280, '62908d393465b.jpg', 1),
(5, 2, 'Pink Spaghetti Gamberoni', 'Spaghetti with prawns in a fresh tomato sauce. This dish originates from Southern Italy and with the combination of prawns, garlic, chilli and pasta. Garnish each with remaining tablespoon parsley.', 'Delicious spaghetti with fresh prawns in a vibrant tomato sauce. This dish originates from Southern Italy and with the combination of prawns, garlic, chilli and pasta. Garnish each with remaining tablespoon parsley.', 2, 525000.00, 450, '606d7491a9d13.jpg', 1),
(6, 2, 'Cheesy Mashed Potato', 'Deliciously Cheesy Mashed Potato. The ultimate mash for your Thanksgiving table or the perfect accompaniment to vegan sausage casserole. Everyone will love it\'s fluffy, cheesy.', 'Deliciously Cheesy Mashed Potato. The ultimate mash for your Thanksgiving table or the perfect accompaniment to vegan sausage casserole. Everyone will love it\'s fluffy, cheesy.', 5, 125000.00, 220, '606d74c416da5.jpg', 1),
(7, 2, 'Crispy Chicken Strips', 'Fried chicken strips, served with special honey mustard sauce.', 'Fried chicken strips, served with special honey mustard sauce.', 1, 200000.00, 380, '606d74f6ecbbb.jpg', 1),
(8, 2, 'Lemon Grilled Chicken And Pasta', 'Marinated rosemary grilled chicken breast served with mashed potatoes and your choice of pasta.', 'Marinated rosemary grilled chicken breast served with mashed potatoes and your choice of pasta.', 2, 275000.00, 550, '606d752a209c3.jpg', 1),
(9, 3, 'Vegetable Fried Rice', 'Chinese rice wok with cabbage, beans, carrots, and spring onions.', 'Chinese rice wok with cabbage, beans, carrots, and spring onions.', 2, 125000.00, 320, '606d7575798fb.jpg', 1),
(10, 3, 'Prawn Crackers', '12 pieces deep-fried prawn crackers', '12 pieces deep-fried prawn crackers', 1, 175000.00, 180, '606d75a7e21ec.jpg', 1),
(11, 3, 'Spring Rolls', 'Lightly seasoned shredded cabbage, onion and carrots, wrapped in house made spring roll wrappers, deep fried to golden brown.', 'Lightly seasoned shredded cabbage, onion and carrots, wrapped in house made spring roll wrappers, deep fried to golden brown.', 1, 150000.00, 250, '606d75ce105d0.jpg', 1),
(12, 3, 'Manchurian Chicken', 'Chicken pieces slow cooked with spring onions in our house made manchurian style sauce.', 'Chicken pieces slow cooked with spring onions in our house made manchurian style sauce.', 2, 275000.00, 480, '606d7600dc54c.jpg', 1),
(13, 4, ' Buffalo Wings', 'Fried chicken wings tossed in spicy Buffalo sauce served with crisp celery sticks and Blue cheese dip.', 'Fried chicken wings tossed in spicy Buffalo sauce served with crisp celery sticks and Blue cheese dip.', 1, 275000.00, 420, '606d765f69a19.jpg', 1),
(22, 14, 'Cơm gà mắm tỏi', 'Cơm gà mắm tỏi - Ngon khó cưỡng', 'Cơm gà mắm tỏi là sự kết hợp hoàn hảo giữa đùi hoặc ức gà chiên giòn rụm, được bao phủ bởi lớp sốt mắm tỏi đậm đà, mặn ngọt cay cay. Món này thường ăn kèm với cơm trắng, dưa leo, cà chua và một chút tỏi phi thơm lừng', 4, 35000.00, 550, 'dish_688d85b8ec3c74.13083890.jpg', 1),
(23, 14, 'Burger tôm+nước ngọt', 'Burger tôm giòn rụm, ngọt tự nhiên kết hợp cùng nước ngọt mát lạnh – combo hoàn hảo cho bữa ăn đầy năng lượng', 'ự kết hợp tuyệt vời giữa nhân tôm tươi 100% chiên giòn, được tẩm ướp đậm đà, kẹp trong vỏ bánh burger mềm mại, thơm lừng cùng rau xanh tươi mát và sốt đặc biệt. Thưởng thức kèm một ly nước ngọt sảng khoái để cân bằng hương vị và giải khát, mang đến trải nghiệm ẩm thực trọn vẹn', 4, 50000.00, 487, 'dish_688d8a3fd3f428.56925782.png', 1),
(24, 14, 'Cặp đôi ăn ý', 'Niềm vui trọn vẹn - khi ăn theo cặp', '2 phần gà rán (một phần đùi, một phần ức)  2 phần mì Ý Jollibee (Jolly Spaghetti) với sốt cà chua, xúc xích và thịt bằm  2 ly nước ngọt có ga (có thể là Coca-Cola)  1 phần khoai tây chiên cỡ vừa  1 chén tương cà', 4, 145000.00, 1500, 'dish_688f0ac6ac48f4.76497921.png', 1),
(25, 14, 'Combo no phê', 'Cả nhà vui Jolibee cũng vui', '3 phần gà rán (một phần đùi, một phần cánh và một phần ức)  3 phần mì Ý Jollibee (Jolly Spaghetti) với sốt cà chua, xúc xích và thịt bằm  3 ly nước ngọt có ga cỡ vừa (có thể là Coca-Cola)  1 phần khoai tây chiên cỡ vừa  1 chén tương cà', 4, 195000.00, 3500, 'dish_688f0b3ac5b610.26101157.png', 1),
(26, 14, '4 miếng gà giòn', 'Niềm vui trọn vẹn - khi ăn theo cặp', '4 miếng gà rán giòn rụm. Có thể thấy có 2 miếng đùi và 2 miếng gà phần khác', 4, 126000.00, 1600, 'dish_688f0ca8bb27f3.67079441.png', 1),
(27, 14, '2 miếng gà giòn', 'Niềm vui trọn vẹn - khi ăn theo cặp', '2 phần gà rán (một phần đùi, một phần ức)  2 phần mì Ý Jollibee (Jolly Spaghetti) với sốt cà chua, xúc xích và thịt bằm  2 ly nước ngọt có ga (có thể là Coca-Cola)  1 phần khoai tây chiên cỡ vừa và 1 chén tương)', 4, 66000.00, 700, 'dish_688f0cff123fe6.69753381.png', 1),
(28, 14, 'Cơm gà giòn (không cay)', 'Niềm vui trọn vẹn - khi ăn theo cặp', 'một miếng gà rán giòn rụm (có vẻ là phần cánh), một phần cơm trắng và một ít rau salad (xà lách, cà chua, dưa leo)', 4, 50000.00, 250, 'dish_688f0d64c337b5.46218603.png', 1),
(29, 14, 'Cơm gà giòn (cay)', 'Niềm vui trọn vẹn - khi ăn theo cặp', 'một miếng gà rán giòn rụm (có vẻ là phần cánh), một phần cơm trắng, một ít rau salad (xà lách, cà chua, dưa leo) và một ly nước ngọt có ga.', 4, 59000.00, 300, 'dish_688f0d8aadb055.74409509.png', 1),
(30, 5, 'Gà tôm chiên rắc bột phô mai', 'vui khi ăn gà - gà o key', '200 gram gà không xương  150 gram tôm  10 gram bột phô mai', 4, 197000.00, 500, 'dish_688f0f6d320a28.99959679.jpg', 1),
(31, 5, 'Xốt hoàng kim gà không xương', 'vui khi ăn gà - gà o key', '300 gram gà không xương  250 gram tôm  50 gram phô mai tươi', 4, 282000.00, 1000, 'dish_688f10360de701.67716624.jpg', 1),
(32, 5, 'Gà Tôm Chiên Xốt Kem Hành', 'vui khi ăn gà - gà o key', '200 gram gà không xương  150 gram tôm  100 gram xốt kem hành', 4, 179000.00, 577, 'dish_688f108fc13e58.49830517.jpg', 1),
(33, 5, 'Gà Tôm Chiên Xốt Tỏi Stamina', 'vui khi ăn gà - gà o key', '200 gram gà không xương  150 gram tôm  100 gram xốt tỏi Stamina', 4, 199000.00, 500, 'dish_688f10acc2ce98.86988844.jpg', 1),
(34, 5, 'Đùi Gà Ôm Phô Mai Rắc Bột Phô Mai', 'vui khi ăn gà - gà o key', '300 gram gà không xương  250 gram tôm  50 gram phô mai', 4, 299000.00, 900, 'dish_688f110d3a1757.54850990.jpg', 1),
(35, 5, 'Cơm đùi gà (Xốt ngũ vị)', 'vui khi ăn gà - gà o key', '1 phần cơm 1 đùi gà xốt ngũ vị và rau củ quả', 4, 45000.00, 300, 'dish_688f1177680c49.35184068.jpg', 1),
(36, 5, 'Cơm gà không xương (Xốt ngũ vị)', 'vui khi ăn gà - gà o key', '1miếng gà rán, 1 phần cơm trắng và một ít rau salad', 4, 59000.00, 600, 'dish_688f11b716b6f4.12144472.jpg', 1),
(37, 15, 'Cơm trộn hàn quốc', 'bonbon', 'Trứng ốp la: 1 quả Rau củ: 50g Thịt/hải sản: 50g Lá rong biển: 2 lát Nước sốt: 20g trọng lượng 160g', 4, 89000.00, 400, 'dish_688f245c664e90.79497972.jpg', 1),
(38, 15, 'Khoai tây sốt xương tỏi Mayo', 'bonbon', 'Khoai tây chiên: 300g Sốt tỏi đậu nành kem: 20g', 4, 59000.00, 500, 'dish_688f24d0194ed6.85680492.jpg', 1),
(39, 15, 'Gà viên Bonchon', 'bonbon', 'Gà popcorn:150-200g Rau ăn kèm:70g', 4, 79000.00, 700, 'dish_688f2526163643.58247787.jpg', 1),
(40, 15, 'Đùi gà 2 miếng ', 'bonbon', '2 phần gà rán: 300g ', 4, 79000.00, 400, 'dish_688f2578115455.58744921.jpg', 1),
(41, 15, 'Bánh gạo cay chả cá hàn quốc', 'bonbon', 'Bánh gạo và chả cá được nấu trong nước sốt cay hàn quốc 350g ', 4, 129000.00, 450, 'dish_688f2631127840.64123008.jpg', 1),
(42, 15, 'Combo tiệc gà', 'bonbon', ' 10 miếng gà, trọng lượng 920g', 4, 459000.00, 1200, 'dish_688f2674484503.66314518.jpg', 1),
(43, 15, 'Cá chiên bonchon', 'bonbon', 'Cá 100g, khoai tây chiên 150g, nước sốt 50g', 4, 109000.00, 400, 'dish_688f26cc9a3545.16603580.jpg', 1),
(44, 15, 'Combo gà cỡ lớn (cay)', 'bonbon', '8 cánh và 3 đùi gà, trọng lượng 500g.', 4, 329000.00, 500, 'dish_688f273878f497.08095533.jpg', 1),
(45, 15, 'Gà không xương sốt Mala ', 'bonbon', '10 miếng gà trọng lượng 600g', 4, 249000.00, 700, 'dish_688f2792f33b65.12139163.jpg', 1),
(46, 15, 'Miến trộn hàn quốc ', 'bonbon', 'miến khoai lang trộn với các loại rau củ thái sợi trọng lượng 300g', 4, 69000.00, 200, 'dish_688f27dbd2fbb6.61667212.jpg', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dish_categories`
--

CREATE TABLE `dish_categories` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(222) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dish_categories`
--

INSERT INTO `dish_categories` (`cat_id`, `cat_name`, `created_at`, `updated_at`) VALUES
(1, 'món chay', '2025-07-18 07:45:33', '2025-07-18 07:45:33'),
(2, 'Đồ Khô', '2025-07-18 07:45:33', '2025-07-18 07:45:33'),
(3, 'Nước Uống', '2025-07-18 07:45:33', '2025-07-18 07:45:33'),
(4, 'Đồ ăn nhanh', '2025-07-18 07:45:33', '2025-08-01 06:59:30'),
(5, 'Phở', '2025-07-18 07:45:33', '2025-07-18 07:45:33'),
(6, 'Rau', '2025-07-18 07:45:33', '2025-07-18 07:45:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dish_ingredients`
--

CREATE TABLE `dish_ingredients` (
  `id` int(11) NOT NULL,
  `dish_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL COMMENT 'Số lượng nguyên liệu cần cho 1 món'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dish_ingredients`
--

INSERT INTO `dish_ingredients` (`id`, `dish_id`, `ingredient_id`, `quantity`) VALUES
(1, 7, 1, 0.20),
(2, 7, 2, 0.10),
(3, 7, 3, 0.02),
(4, 7, 4, 0.05),
(5, 13, 4, 13.00),
(6, 13, 8, 9.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ingredients`
--

CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `unit` varchar(20) NOT NULL COMMENT 'Đơn vị tính (kg, g, l, ml, cái...)',
  `unit_price` decimal(10,2) NOT NULL COMMENT 'Giá trên 1 đơn vị',
  `current_quantity` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Số lượng hiện có',
  `min_stock` decimal(10,2) DEFAULT NULL COMMENT 'Mức tồn kho tối thiểu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_used_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ingredients`
--

INSERT INTO `ingredients` (`ingredient_id`, `name`, `unit`, `unit_price`, `current_quantity`, `min_stock`, `created_at`, `updated_at`, `last_used_at`) VALUES
(1, 'Thịt gà', 'kg', 80000.00, 65.00, 10.00, '2025-07-18 09:32:17', '2025-07-18 10:01:40', NULL),
(2, 'Khoai tây', 'kg', 20000.00, 115.00, 16.00, '2025-07-18 09:32:17', '2025-07-18 11:06:12', NULL),
(3, 'Dầu ăn', 'lít', 50000.00, 40.00, 3.00, '2025-07-18 09:32:17', '2025-07-28 03:05:19', NULL),
(4, 'Bột chiên xù', 'kg', 30000.00, 373.00, 1.00, '2025-07-18 09:32:17', '2025-07-19 02:47:59', NULL),
(8, 'chinsu', 'mm', 10000.00, 91.00, 10.00, '2025-07-28 02:43:37', '2025-07-28 03:13:32', NULL),
(9, 'rau muống', 'kg', 8000.00, 200.00, 100.00, '2025-07-28 02:49:52', '2025-07-28 02:49:52', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `inventory_receipts`
--

CREATE TABLE `inventory_receipts` (
  `receipt_id` int(11) NOT NULL,
  `receipt_date` datetime NOT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL COMMENT 'Người tạo đơn',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `inventory_receipts`
--

INSERT INTO `inventory_receipts` (`receipt_id`, `receipt_date`, `total_amount`, `notes`, `created_by`, `created_at`) VALUES
(1, '2025-07-01 08:30:00', 4500000.00, 'Nhập hàng đầu tháng', 1, '2025-07-01 01:30:00'),
(2, '2025-07-10 14:15:00', 3200000.00, 'Bổ sung nguyên liệu', 1, '2025-07-10 07:15:00'),
(22, '2025-07-18 17:57:24', 1.00, 'hauiwhida', 1, '2025-07-18 10:57:24'),
(23, '2025-07-18 17:57:31', 1.00, 'hauiwhida', 1, '2025-07-18 10:57:31'),
(24, '2025-07-18 18:00:19', 42000000.00, 'nhập hàng', 1, '2025-07-18 11:00:19'),
(25, '2025-07-19 09:47:35', 20000000.00, 'Nhập số lượng 100 - bột chiên xù', 1, '2025-07-19 02:47:35'),
(26, '2025-07-28 10:05:19', 250000.00, 'thiếu nguyên liệu', 1, '2025-07-28 03:05:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `inventory_receipt_items`
--

CREATE TABLE `inventory_receipt_items` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `ingredient_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) GENERATED ALWAYS AS (`quantity` * `unit_price`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `inventory_receipt_items`
--

INSERT INTO `inventory_receipt_items` (`id`, `receipt_id`, `ingredient_id`, `quantity`, `unit_price`) VALUES
(1, 1, 1, 30.00, 75000.00),
(2, 1, 2, 50.00, 18000.00),
(3, 1, 3, 10.00, 48000.00),
(4, 1, 4, 15.00, 28000.00),
(5, 2, 1, 20.00, 80000.00),
(6, 2, 3, 8.00, 50000.00),
(7, 2, 4, 10.00, 30000.00),
(29, 22, 4, 1.00, 1.00),
(30, 23, 4, 1.00, 1.00),
(31, 24, 4, 21.00, 2000000.00),
(32, 25, 4, 100.00, 200000.00),
(33, 26, 3, 5.00, 50000.00);

--
-- Bẫy `inventory_receipt_items`
--
DELIMITER $$
CREATE TRIGGER `after_receipt_item_insert` AFTER INSERT ON `inventory_receipt_items` FOR EACH ROW BEGIN
    UPDATE ingredients 
    SET current_quantity = current_quantity + NEW.quantity
    WHERE ingredient_id = NEW.ingredient_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender` varchar(50) DEFAULT NULL,
  `receiver` varchar(50) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `mng_reports`
--

CREATE TABLE `mng_reports` (
  `id` int(11) NOT NULL,
  `shipper_id` int(11) DEFAULT NULL,
  `shipper_name` varchar(255) DEFAULT NULL,
  `report_type` varchar(100) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `mng_reports`
--

INSERT INTO `mng_reports` (`id`, `shipper_id`, `shipper_name`, `report_type`, `content`, `created_at`) VALUES
(1, 1001285, 'vipro', 'Chậm trễ', 'tôi mắc đi vệ sinh bảo khách đợi tí', '2025-07-21 01:26:26'),
(2, 1001285, 'vipro', 'Khách hàng không nhận', 'khách khó tính', '2025-07-28 03:21:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rating`
--

CREATE TABLE `rating` (
  `rating_id` int(11) NOT NULL,
  `rs_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `review` text DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `comment` text DEFAULT NULL,
  `image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `rating`
--

INSERT INTO `rating` (`rating_id`, `rs_id`, `user_id`, `rating`, `review`, `date`, `comment`, `image`) VALUES
(1, 1, 1, 4.5, 'Great atmosphere and friendly staff. Burgers are amazing!', '2025-06-15 11:30:00', NULL, NULL),
(2, 1, 2, 5.0, 'Best tavern in town! Love their craft beer selection.', '2025-06-20 13:15:00', NULL, NULL),
(3, 1, 3, 4.0, 'Good food but a bit noisy during weekends.', '2025-06-25 12:45:00', NULL, NULL),
(4, 1, 4, 4.5, 'Excellent service and delicious food.', '2025-07-01 14:00:00', NULL, NULL),
(5, 1, 5, 3.5, 'Decent place but a bit overpriced.', '2025-07-05 10:30:00', NULL, NULL),
(6, 2, 1, 5.0, 'Authentic Italian food. The pasta is to die for!', '2025-06-10 06:45:00', NULL, NULL),
(7, 2, 2, 4.5, 'Great selection of Italian products and dishes.', '2025-06-18 07:30:00', NULL, NULL),
(8, 2, 3, 4.0, 'Lovely place but can get crowded.', '2025-06-22 05:15:00', NULL, NULL),
(9, 2, 4, 5.0, 'Perfect for a romantic dinner. Wine list is impressive.', '2025-06-28 13:00:00', NULL, NULL),
(10, 2, 5, 4.5, 'High quality ingredients and professional staff.', '2025-07-03 12:30:00', NULL, NULL),
(11, 3, 1, 4.5, 'The soup dumplings are incredible!', '2025-06-12 04:30:00', NULL, NULL),
(12, 3, 2, 5.0, 'Authentic Shanghainese cuisine. Highly recommended!', '2025-06-17 05:45:00', NULL, NULL),
(13, 3, 3, 4.0, 'Good but the wait can be long during peak hours.', '2025-06-24 06:15:00', NULL, NULL),
(14, 3, 4, 4.5, 'Best xiao long bao I have ever had outside of China.', '2025-06-30 11:30:00', NULL, NULL),
(15, 3, 5, 3.5, 'Tasty but portions are a bit small.', '2025-07-04 05:00:00', NULL, NULL),
(16, 4, 1, 5.0, 'Fine dining at its best. The steak was perfect!', '2025-06-14 13:45:00', NULL, NULL),
(17, 4, 2, 4.5, 'Elegant atmosphere and exceptional service.', '2025-06-19 12:30:00', NULL, NULL),
(18, 4, 3, 4.0, 'Great food but quite expensive.', '2025-06-26 14:00:00', NULL, NULL),
(19, 4, 4, 5.0, 'Perfect for special occasions. Wine pairing was excellent.', '2025-07-02 13:15:00', NULL, NULL),
(20, 4, 5, 4.0, 'High quality ingredients but limited vegetarian options.', '2025-07-06 11:45:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `remark`
--

CREATE TABLE `remark` (
  `id` int(11) NOT NULL,
  `frm_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarkDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `remark`
--

INSERT INTO `remark` (`id`, `frm_id`, `status`, `remark`, `remarkDate`) VALUES
(1, 2, 'in process', 'none', '2022-05-01 05:17:49'),
(2, 3, 'in process', 'none', '2022-05-27 11:01:30'),
(3, 2, 'closed', 'thank you for your order!', '2022-05-27 11:11:41'),
(4, 3, 'closed', 'none', '2022-05-27 11:42:35'),
(5, 4, 'in process', 'none', '2022-05-27 11:42:55'),
(6, 1, 'rejected', 'none', '2022-05-27 11:43:26'),
(7, 7, 'in process', 'none', '2022-05-27 13:03:24'),
(8, 8, 'in process', 'none', '2022-05-27 13:03:38'),
(9, 9, 'rejected', 'thank you', '2022-05-27 13:03:53'),
(10, 7, 'closed', 'thank you for your ordering with us', '2022-05-27 13:04:33'),
(11, 8, 'closed', 'thanks ', '2022-05-27 13:05:24'),
(12, 5, 'closed', 'none', '2022-05-27 13:18:03'),
(13, 59, 'closed', 'đơn hàng đã giao', '2025-08-03 03:00:05'),
(14, 59, 'in process', 'có lời', '2025-08-03 03:00:54'),
(15, 59, 'in process', 'ads', '2025-08-03 03:03:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `restaurant`
--

CREATE TABLE `restaurant` (
  `rs_id` int(222) NOT NULL,
  `c_id` int(222) NOT NULL,
  `title` varchar(222) NOT NULL,
  `email` varchar(222) NOT NULL,
  `phone` varchar(222) NOT NULL,
  `url` varchar(222) NOT NULL,
  `o_hr` varchar(222) NOT NULL,
  `c_hr` varchar(222) NOT NULL,
  `o_days` varchar(222) NOT NULL,
  `address` mediumtext NOT NULL,
  `image` mediumtext NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `restaurant`
--

INSERT INTO `restaurant` (`rs_id`, `c_id`, `title`, `email`, `phone`, `url`, `o_hr`, `c_hr`, `o_days`, `address`, `image`, `date`, `status`) VALUES
(1, 1, 'North Street Tavern', 'nthavern@mail.com', '3547854700', 'www.northstreettavern.com', '08:00:00', '23:00:00', 'mon-sat', '1128 North St, White Plains', '6290877b473ce.jpg', '2025-07-07 02:53:49', 1),
(2, 2, 'Eataly', 'eataly@gmail.com', '0557426406', 'www.eataly.com', '10:00:00', '22:00:00', 'Mon-Sat', '800 Boylston St, Boston', '606d720b5fc71.jpg', '2025-07-07 02:53:49', 1),
(3, 3, 'Nan Xiang Xiao Long Bao', 'nanxiangbao45@mail.com', '1458745855', 'www.nanxiangbao45.com', '09:30:00', '22:00:00', 'mon-sat', 'Queens, New York', '6290860e72d1e.jpg', '2025-07-07 02:53:49', 1),
(4, 4, 'Highlands Bar & Grill', 'hbg@mail.com', '6545687458', 'www.hbg.com', '07:00:00', '21:00:00', 'mon-sat', '812 Walter Street', '6290af6f81887.jpg', '2025-07-07 02:53:49', 1),
(5, 3, 'Chicken Plus', 'chickenplusvn@gmail.com', '19000015', 'chickenplus.com.vn', '10:30:00', '21:30:00', 'mon-sat', 'DP07, Khu biệt thự Song Lập Phú Long Nguyễn Hữu Thọ, Phước Kiển, Nhà Bè, Hồ Chí Minh.', 'chken.jpg', '2025-07-15 09:20:32', 1),
(13, 4, 'Dokki Việt Nam', 'dokivn@gmail.com', '028 3620 9295‬', 'https://dookkivietnam.com/', '11:11', '23:11', 'mon-sat', 'Vincom Plaza, ĐT743, Dĩ An, Bình Dương', 'doki.png', '2025-08-01 22:13:15', 1),
(14, 2, 'jolibee', 'jolibeevn@gmail.com.vn', '028 3930 9168', 'https://jollibee.com.vn/', '01:24', '13:24', 'mon-sat', 'Tầng 26, Tòa nhà CII Tower, số 152 Điện Biên Phủ, Phường 25, Quận Bình Thạnh, Thành phố Hồ Chí Minh, Việt Nam', 'jolibee.jpg', '2025-08-01 22:24:19', 1),
(15, 1, 'BonBon', 'info@bonchon.com.vn', '1900 8947', 'https://bonchon.com.vn/menu', '10:00', '22:00', 'mon-sat', 'Tầng 2, Tòa nhà EBM, 683-685 Điện Biên Phủ, P.25, Quận Bình Thạnh, TP.HCM', 'bonbon.png', '2025-08-03 08:25:29', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `res_category`
--

CREATE TABLE `res_category` (
  `c_id` int(222) NOT NULL,
  `c_name` varchar(222) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `country_code` varchar(2) DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lng` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `res_category`
--

INSERT INTO `res_category` (`c_id`, `c_name`, `date`, `country_code`, `lat`, `lng`) VALUES
(1, 'Tp.HCM', '2025-08-03 08:26:48', 'VN', NULL, NULL),
(2, 'Bình Dương', '2025-08-03 08:27:47', 'VN', NULL, NULL),
(3, 'Vũng Tàu', '2025-08-03 08:27:56', 'VN', NULL, NULL),
(4, 'Shang Hai', '2025-08-03 08:28:05', 'CN', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shippers`
--

CREATE TABLE `shippers` (
  `shipper_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `id_card_number` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `vehicle_type` varchar(50) DEFAULT NULL COMMENT 'e.g., Motorcycle, Car, Bicycle',
  `license_plate` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1 COMMENT '1: active, 0: inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `current_latitude` decimal(10,8) DEFAULT NULL,
  `current_longitude` decimal(11,8) DEFAULT NULL,
  `last_location_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `shippers`
--

INSERT INTO `shippers` (`shipper_id`, `full_name`, `phone_number`, `email`, `id_card_number`, `password`, `vehicle_type`, `license_plate`, `is_active`, `created_at`, `updated_at`, `current_latitude`, `current_longitude`, `last_location_update`, `picture`) VALUES
(1001285, 'vipro', '09827472424', 'kfii@gmail.com', '0887888267247', '$2y$10$e2Z.VtoQ4YheYM9mvSk0.eI.fRXcTexLYOjOUnAl5xgp81TMlQxza', 'ô tô', '61D-95783', 1, '2025-07-20 04:15:48', '2025-07-27 01:29:28', 10.12345600, 106.65432100, '2025-07-27 01:29:28', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shops`
--

CREATE TABLE `shops` (
  `id` int(11) NOT NULL,
  `shop_name` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `owner_name` varchar(100) DEFAULT NULL,
  `shop_type` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `shops`
--

INSERT INTO `shops` (`id`, `shop_name`, `address`, `phone`, `email`, `open_time`, `close_time`, `logo`, `owner_name`, `shop_type`, `created_at`) VALUES
(1, 'porw', 'Đường số 9, Linh Xuân, Thủ Đức, Hồ Chí Minh, Việt Nam', '09872568223', 'hungbs@gmail.com', '03:57:00', '20:00:00', NULL, 'hungbs', 'Nha hang', '2025-07-15 14:59:19'),
(2, 'shopee', '02 Lưu Chí Hiếu, Tây Thạnh, Tân Phú, Hồ Chí Minh 700000, Việt Nam', '0934573945', 'shppe@gmail.com', '07:30:00', '19:30:00', NULL, 'pra', 'Quán ăn', '2025-08-02 11:13:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'Liên kết -> users if nhân viên là người dùng',
  `full_name` varchar(255) NOT NULL,
  `position` varchar(100) NOT NULL,
  `hire_date` date NOT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `id_card_number` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `staff`
--

INSERT INTO `staff` (`staff_id`, `user_id`, `full_name`, `position`, `hire_date`, `salary`, `phone`, `email`, `address`, `date_of_birth`, `id_card_number`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 'nguyễn minh chí', 'staff', '2025-07-19', 21000.00, '0958934532', 'mc@gmail.com', 'jouhoifsefs', '2025-07-20', '094442786142', 1, '2025-07-19 03:17:05', '2025-07-19 03:26:28'),
(3, 3, 'nguyễn kh', 'manager', '2025-04-03', 300000.00, '0837457352', 'uiii11@gmail.com', 'pdoosudguawd', '1992-07-19', '0982718623618', 0, '2025-07-19 03:20:33', '2025-07-19 03:26:24'),
(4, 1, 'nguyễn da', 'staff', '2025-07-05', 89573.00, '08728974234', 'poroo2@gmail.com', 'wdadasadaw2', '2025-07-20', '089937729834', 1, '2025-07-19 03:21:34', '2025-07-19 03:26:19'),
(5, 5, 'Trần Thị B', 'cashier', '2024-11-01', 15000.00, '0912345678', 'btran@example.com', '789 Trần Hưng Đạo, Q.3, TP.HCM', '2000-05-10', '0123456789', 0, '2025-07-19 03:31:35', '2025-07-19 03:34:39'),
(6, 5, 'Lê Văn C', 'chef', '2023-08-15', 25000.00, '0901234567', 'cle@example.com', '101 Nguyễn Văn Cừ, Q.5, TP.HCM', '1995-11-22', '0987654321', 1, '2025-07-19 03:31:35', '2025-07-28 01:34:00'),
(7, 6, 'Phạm Thị D', 'delivery driver', '2025-01-20', 18000.00, '0934567890', 'dpham@example.com', '202 Lê Lợi, Q.1, TP.HCM', '1998-03-01', '1231231234', 0, '2025-07-19 03:31:35', '2025-07-19 03:34:34'),
(8, 6, 'Vũ Đình E', 'cleaner', '2024-03-01', 12000.00, '0978901234', 'evu@example.com', '303 Hùng Vương, Q.10, TP.HCM', '1990-07-15', '4564564567', 0, '2025-07-19 03:31:35', '2025-07-19 03:34:31'),
(9, 6, 'Đinh Thị F', 'manager', '2022-01-01', 35000.00, '0945678901', 'fdinh@example.com', '404 Hai Bà Trưng, Q. Bình Thạnh, TP.HCM', '1985-01-25', '7897897890', 0, '2025-07-19 03:31:35', '2025-07-19 03:31:35'),
(10, 11, 'Nguyễn Văn G', 'kitchen staff', '2023-04-10', 16000.00, '0967890123', 'gnguyen@example.com', '505 Hoàng Sa, Q. Phú Nhuận, TP.HCM', '1997-09-05', '0101010101', 0, '2025-07-19 03:31:35', '2025-07-19 03:34:26'),
(11, 11, 'Hoàng Thị H', 'waiter', '2024-09-01', 14000.00, '0989012345', 'hhoang@example.com', '606 Trường Sa, Q. Tân Bình, TP.HCM', '2001-04-18', '2020202020', 1, '2025-07-19 03:31:35', '2025-07-28 01:34:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `u_id` int(222) NOT NULL,
  `username` varchar(222) NOT NULL,
  `f_name` varchar(222) NOT NULL,
  `l_name` varchar(222) NOT NULL,
  `email` varchar(222) NOT NULL,
  `phone` varchar(222) NOT NULL,
  `password` varchar(222) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` int(222) NOT NULL DEFAULT 1,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `picture` varchar(255) DEFAULT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`u_id`, `username`, `f_name`, `l_name`, `email`, `phone`, `password`, `address`, `status`, `date`, `picture`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'eric', 'Eric', 'Lopez', 'eric@mail.com', '1458965547', 'a32de55ffd7a9c4101a0c5c8788b38ed', '87 Armbrester Drive', 1, '2022-05-27 08:40:36', NULL, NULL, NULL),
(2, 'harry', 'Harry', 'Holt', 'harryh@mail.com', '3578545458', 'bc28715006af20d0e961afd053a984d9', '33 Stadium Drive', 1, '2022-05-27 08:41:07', NULL, NULL, NULL),
(3, 'james', 'James', 'Duncan', 'james@mail.com', '0258545696', '58b2318af54435138065ee13dd8bea16', '67 Hiney Road', 1, '2022-05-27 08:41:37', NULL, NULL, NULL),
(4, 'christine', 'Christine', 'Moore', 'christine@mail.com', '7412580010', '5f4dcc3b5aa765d61d8327deb882cf99', '114 Test Address', 1, '2022-05-01 05:14:42', NULL, NULL, NULL),
(5, 'scott', 'Scott', 'Miller', 'scott@mail.com', '7896547850', '5f4dcc3b5aa765d61d8327deb882cf99', '63 Charack Road', 1, '2022-05-27 10:53:51', NULL, NULL, NULL),
(9, 'dadd', 'aaww', 'ssss', 'add@gmail.com', '0947238527', '25f9e794323b453885f5181f1b624d0b', 'saewfsefsef', 1, '2025-07-06 02:20:16', NULL, NULL, NULL),
(10, 'hunvipro', 'anhug', 'nguyen', 'potu@gmail.com', '0992374819', '25f9e794323b453885f5181f1b624d0b', '99,1/nuguyendu,nunanim,dian,dsd', 1, '2025-07-06 04:02:27', NULL, NULL, NULL),
(13, 'viper', 'thor', 'snake', 'thor@gmail.com', '0987374568', '25f9e794323b453885f5181f1b624d0b', 'Tân Long, Khu phố Tân Long, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', 1, '2025-07-27 01:03:40', NULL, NULL, NULL),
(14, 'porr2', 'dk', 'nguyen', 'nguyenjason504@gmail.com', '0947624877', '25f9e794323b453885f5181f1b624d0b', 'Đường tỉnh 122A, Khu phố Tân Bình, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', 1, '2025-08-01 06:42:14', NULL, '8a8cdea848cad96c02ec6b75641a051289562fe5ed92b302c1c631da6f9adea0', '2025-08-01 09:12:14'),
(16, 'hungb', 'du', 'nguyen', 'k.hung090804@gmail.com', '0987473845', '25f9e794323b453885f5181f1b624d0b', 'Đường tỉnh 743A, Tân Long, Khu phố Tân Long, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', 1, '2025-08-09 10:43:51', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users_orders`
--

CREATE TABLE `users_orders` (
  `o_id` int(222) NOT NULL,
  `rs_id` int(222) NOT NULL,
  `u_id` int(222) NOT NULL,
  `title` varchar(222) NOT NULL,
  `quantity` int(222) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` varchar(222) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `coupon_code` varchar(50) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) DEFAULT 0.00,
  `order_time` datetime NOT NULL DEFAULT current_timestamp(),
  `shipper_id` int(11) DEFAULT NULL,
  `distance` decimal(10,2) DEFAULT 0.00,
  `rating` decimal(3,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users_orders`
--

INSERT INTO `users_orders` (`o_id`, `rs_id`, `u_id`, `title`, `quantity`, `price`, `status`, `address`, `date`, `coupon_code`, `discount`, `total`, `order_time`, `shipper_id`, `distance`, `rating`) VALUES
(59, 1, 13, 'Yorkshire Lamb Patties', 22, 14.00, 'in process', 'Tân Long, Khu phố Tân Long, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-03 03:00:54', '', 0.00, 308.00, '2025-07-27 09:06:16', 1001285, 0.00, NULL),
(60, 2, 13, 'Pink Spaghetti Gamberoni', 2000, 21.00, 'in process', 'Tân Long, Khu phố Tân Long, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-03 03:20:20', '', 0.00, 42000.00, '2025-07-27 09:06:55', 1001285, 0.00, NULL),
(61, 3, 1, 'Vegetable Fried Rice', 100, 5.00, 'in process', '87 Armbrester Drive', '2025-07-28 03:18:53', '', 0.00, 500.00, '2025-07-28 10:17:59', 1001285, 0.00, NULL),
(66, 4, 15, 'Chicken Madeira', 1, 575000.00, 'in process', 'awiudhaiuhdisluduiawd', '2025-08-01 07:05:36', 'WELCOME20', 20.00, 574980.00, '2025-08-01 14:05:19', 1001285, 0.00, NULL),
(67, 4, 15, 'Chicken Madeira', 1, 575000.00, 'pending', 'awiudhaiuhdisluduiawd', '2025-08-01 07:35:16', 'WELCOME2025', 50000.00, 525000.00, '2025-08-01 14:35:16', NULL, 0.00, NULL),
(68, 2, 15, 'Pink Spaghetti Gamberoni', 2, 525000.00, 'pending', 'awiudhaiuhdisluduiawd', '2025-08-01 07:45:09', 'DISCOUNT5', 95000.00, 1080000.00, '2025-08-01 14:45:09', NULL, 0.00, NULL),
(69, 2, 15, 'Cheesy Mashed Potato', 1, 125000.00, 'pending', 'awiudhaiuhdisluduiawd', '2025-08-01 07:45:09', 'DISCOUNT5', 95000.00, 1080000.00, '2025-08-01 14:45:09', NULL, 0.00, NULL),
(70, 3, 15, 'Prawn Crackers', 1, 175000.00, 'pending', 'awiudhaiuhdisluduiawd', '2025-08-01 07:54:39', 'DISCOUNT5', 95000.00, 505000.00, '2025-08-01 14:54:39', NULL, 0.00, NULL),
(71, 3, 15, 'Spring Rolls', 1, 150000.00, 'pending', 'awiudhaiuhdisluduiawd', '2025-08-01 07:54:39', 'DISCOUNT5', 95000.00, 505000.00, '2025-08-01 14:54:39', NULL, 0.00, NULL),
(72, 3, 15, 'Manchurian Chicken', 1, 275000.00, 'pending', 'awiudhaiuhdisluduiawd', '2025-08-01 07:54:39', 'DISCOUNT5', 95000.00, 505000.00, '2025-08-01 14:54:39', NULL, 0.00, NULL),
(73, 4, 15, 'Chicken Madeira', 1, 575000.00, 'pending', 'awiudhaiuhdisluduiawd', '2025-08-01 07:55:30', 'DISCOUNT5', 95000.00, 480000.00, '2025-08-01 14:55:30', NULL, 0.00, NULL),
(74, 3, 15, 'Manchurian Chicken', 1, 275000.00, 'pending', 'awiudhaiuhdisluduiawd', '2025-08-01 08:01:30', '', 0.00, 275000.00, '2025-08-01 15:01:30', NULL, 0.00, NULL),
(75, 3, 14, 'Vegetable Fried Rice', 1, 125000.00, 'rejected', 'Đường tỉnh 122A, Khu phố Tân Bình, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-04 01:24:07', '', 0.00, 125000.00, '2025-08-04 08:24:00', NULL, 0.00, NULL),
(76, 14, 14, 'Cặp đôi ăn ý', 2, 145000.00, 'pending', 'Đường tỉnh 122A, Khu phố Tân Bình, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-04 01:25:51', '', 0.00, 485000.00, '2025-08-04 08:25:51', NULL, 0.00, NULL),
(77, 14, 14, 'Combo no phê', 1, 195000.00, 'pending', 'Đường tỉnh 122A, Khu phố Tân Bình, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-04 01:25:51', '', 0.00, 485000.00, '2025-08-04 08:25:51', NULL, 0.00, NULL),
(78, 4, 14, 'Chicken Madeira', 1, 575000.00, 'pending', 'Đường tỉnh 122A, Khu phố Tân Bình, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-04 02:03:53', '', 0.00, 575000.00, '2025-08-04 09:03:53', NULL, 0.00, NULL),
(79, 14, 14, 'Burger tôm+nước ngọt', 1, 50000.00, 'rejected', 'Đường tỉnh 122A, Khu phố Tân Bình, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-04 02:24:45', '', 0.00, 195000.00, '2025-08-04 09:11:00', NULL, 0.00, NULL),
(80, 14, 14, 'Cặp đôi ăn ý', 1, 145000.00, 'pending', 'Đường tỉnh 122A, Khu phố Tân Bình, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-04 02:11:00', '', 0.00, 195000.00, '2025-08-04 09:11:00', NULL, 0.00, NULL),
(81, 14, 14, 'Combo no phê', 1, 195000.00, 'rejected', 'Đường tỉnh 122A, Khu phố Tân Bình, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-04 02:24:42', 'WELCOME2025', 50000.00, 145000.00, '2025-08-04 09:12:07', NULL, 0.00, NULL),
(82, 14, 14, '4 miếng gà giòn', 1, 126000.00, 'rejected', 'Đường tỉnh 122A, Khu phố Tân Bình, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-04 02:24:36', 'WELCOME2025', 50000.00, 466000.00, '2025-08-04 09:24:00', NULL, 0.00, NULL),
(83, 14, 14, 'Combo no phê', 2, 195000.00, 'rejected', 'Đường tỉnh 122A, Khu phố Tân Bình, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-04 02:24:34', 'WELCOME2025', 50000.00, 466000.00, '2025-08-04 09:24:00', NULL, 0.00, NULL),
(84, 14, 14, 'Combo no phê', 3, 195000.00, 'pending', 'Đường tỉnh 122A, Khu phố Tân Bình, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-04 02:25:44', 'WElCOME2025', 50000.00, 535000.00, '2025-08-04 09:25:44', NULL, 0.00, NULL),
(85, 5, 14, 'Gà Tôm Chiên Xốt Kem Hành', 1, 179000.00, 'pending', 'Đường tỉnh 122A, Khu phố Tân Bình, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-04 02:28:54', 'WELCOME2025', 50000.00, 129000.00, '2025-08-04 09:28:54', NULL, 0.00, NULL),
(86, 14, 14, 'Cơm gà mắm tỏi', 1, 35000.00, 'pending', 'Đường tỉnh 122A, Khu phố Tân Bình, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-04 02:30:00', '', 0.00, 35000.00, '2025-08-04 09:30:00', NULL, 0.00, NULL),
(87, 2, 14, 'Pink Spaghetti Gamberoni', 1, 525000.00, 'pending', 'Đường tỉnh 122A, Khu phố Tân Bình, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-04 02:32:51', '', 0.00, 525000.00, '2025-08-04 09:32:51', NULL, 0.00, NULL),
(88, 5, 14, 'Gà tôm chiên rắc bột phô mai', 1, 197000.00, 'pending', 'Đường tỉnh 122A, Khu phố Tân Bình, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-04 02:34:44', '', 0.00, 197000.00, '2025-08-04 09:34:44', NULL, 0.00, NULL),
(89, 2, 13, 'Pink Spaghetti Gamberoni', 1, 525000.00, 'pending', 'Tân Long, Khu phố Tân Long, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-04 02:36:05', 'WELCOME2025', 50000.00, 475000.00, '2025-08-04 09:36:05', NULL, 0.00, NULL),
(90, 4, 16, ' Buffalo Wings', 1, 275000.00, 'in process', 'Đường tỉnh 743A, Tân Long, Khu phố Tân Long, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-10 01:43:13', 'WELCOME2025', 50000.00, 225000.00, '2025-08-04 09:37:54', NULL, 0.00, NULL),
(91, 4, 16, 'Chicken Madeira', 1, 575000.00, 'in process', 'Đường tỉnh 743A, Tân Long, Khu phố Tân Long, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-10 01:43:10', 'WELCOME2025', 50000.00, 525000.00, '2025-08-04 09:49:31', NULL, 0.00, NULL),
(92, 14, 16, 'Burger tôm+nước ngọt', 1, 50000.00, 'in process', 'Đường tỉnh 743A, Tân Long, Khu phố Tân Long, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-10 01:43:07', '', 0.00, 50000.00, '2025-08-09 17:44:21', NULL, 0.00, NULL),
(93, 4, 16, ' Buffalo Wings', 1, 275000.00, 'in process', 'Đường tỉnh 743A, Tân Long, Khu phố Tân Long, Phường Dĩ An, Thành phố Hồ Chí Minh, 75207, Việt Nam', '2025-08-10 01:43:03', '', 0.00, 275000.00, '2025-08-09 17:46:28', NULL, 0.00, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users_shop`
--

CREATE TABLE `users_shop` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('owner','manager','staff') DEFAULT 'owner',
  `shop_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users_shop`
--

INSERT INTO `users_shop` (`id`, `username`, `password`, `role`, `shop_id`, `created_at`) VALUES
(1, 'shopmng', '$2y$10$FeiRmm0HtY83vuZ9Jh4ITuTVnUs6D1z7nHuZHi8MImDsMKmRpJVAW', 'owner', 1, '2025-07-15 14:59:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `withdraw_requests`
--

CREATE TABLE `withdraw_requests` (
  `id` int(11) NOT NULL,
  `shipper_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `withdraw_requests`
--

INSERT INTO `withdraw_requests` (`id`, `shipper_id`, `amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 1001285, 1200.00, 'approved', '2025-07-14 01:05:26', '2025-07-16 01:05:26'),
(2, 1001285, 2500.00, 'rejected', '2025-06-21 01:05:26', '2025-06-22 01:05:26'),
(3, 1001285, 150.50, 'pending', '2025-07-21 01:05:26', NULL),
(4, 1001285, 750.25, 'approved', '2025-07-20 01:05:26', '2025-07-21 01:05:26'),
(5, 1001285, 10000.00, 'pending', '2025-07-21 01:16:09', NULL),
(6, 1001285, 100000.00, 'pending', '2025-07-21 01:54:10', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `worthy`
--

CREATE TABLE `worthy` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `worthy`
--

INSERT INTO `worthy` (`id`, `user_id`, `res_id`, `created_at`) VALUES
(17, 9, 4, '2025-07-07 03:25:51'),
(19, 1, 2, '2025-07-28 04:02:33'),
(25, 16, 4, '2025-08-10 01:41:14');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adm_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Chỉ mục cho bảng `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `dishes`
--
ALTER TABLE `dishes`
  ADD PRIMARY KEY (`d_id`),
  ADD KEY `fk_dish_dish_category` (`dish_category_id`);

--
-- Chỉ mục cho bảng `dish_categories`
--
ALTER TABLE `dish_categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Chỉ mục cho bảng `dish_ingredients`
--
ALTER TABLE `dish_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_dish_ingredient` (`dish_id`,`ingredient_id`),
  ADD KEY `dish_ingredients_ibfk_2` (`ingredient_id`);

--
-- Chỉ mục cho bảng `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredient_id`);

--
-- Chỉ mục cho bảng `inventory_receipts`
--
ALTER TABLE `inventory_receipts`
  ADD PRIMARY KEY (`receipt_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Chỉ mục cho bảng `inventory_receipt_items`
--
ALTER TABLE `inventory_receipt_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receipt_id` (`receipt_id`),
  ADD KEY `ingredient_id` (`ingredient_id`);

--
-- Chỉ mục cho bảng `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `mng_reports`
--
ALTER TABLE `mng_reports`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `rs_id` (`rs_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `remark`
--
ALTER TABLE `remark`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `restaurant`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`rs_id`);

--
-- Chỉ mục cho bảng `res_category`
--
ALTER TABLE `res_category`
  ADD PRIMARY KEY (`c_id`);

--
-- Chỉ mục cho bảng `shippers`
--
ALTER TABLE `shippers`
  ADD PRIMARY KEY (`shipper_id`),
  ADD UNIQUE KEY `phone_number` (`phone_number`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `id_card_number` (`id_card_number`);

--
-- Chỉ mục cho bảng `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `id_card_number` (`id_card_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`);

--
-- Chỉ mục cho bảng `users_orders`
--
ALTER TABLE `users_orders`
  ADD PRIMARY KEY (`o_id`),
  ADD KEY `fk_users_orders_restaurant` (`rs_id`),
  ADD KEY `fk_users_orders_shippers` (`shipper_id`);

--
-- Chỉ mục cho bảng `users_shop`
--
ALTER TABLE `users_shop`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Chỉ mục cho bảng `withdraw_requests`
--
ALTER TABLE `withdraw_requests`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `worthy`
--
ALTER TABLE `worthy`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_favorite` (`user_id`,`res_id`),
  ADD KEY `res_id` (`res_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `adm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `dishes`
--
ALTER TABLE `dishes`
  MODIFY `d_id` int(222) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT cho bảng `dish_categories`
--
ALTER TABLE `dish_categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `dish_ingredients`
--
ALTER TABLE `dish_ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `inventory_receipts`
--
ALTER TABLE `inventory_receipts`
  MODIFY `receipt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `inventory_receipt_items`
--
ALTER TABLE `inventory_receipt_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `mng_reports`
--
ALTER TABLE `mng_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `rating`
--
ALTER TABLE `rating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT cho bảng `remark`
--
ALTER TABLE `remark`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `restaurant`
--
ALTER TABLE `restaurant`
  MODIFY `rs_id` int(222) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `res_category`
--
ALTER TABLE `res_category`
  MODIFY `c_id` int(222) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `shippers`
--
ALTER TABLE `shippers`
  MODIFY `shipper_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001287;

--
-- AUTO_INCREMENT cho bảng `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(222) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `users_orders`
--
ALTER TABLE `users_orders`
  MODIFY `o_id` int(222) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT cho bảng `users_shop`
--
ALTER TABLE `users_shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `withdraw_requests`
--
ALTER TABLE `withdraw_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `worthy`
--
ALTER TABLE `worthy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`u_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `dishes`
--
ALTER TABLE `dishes`
  ADD CONSTRAINT `fk_dish_dish_category` FOREIGN KEY (`dish_category_id`) REFERENCES `dish_categories` (`cat_id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `dish_ingredients`
--
ALTER TABLE `dish_ingredients`
  ADD CONSTRAINT `dish_ingredients_ibfk_1` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`d_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dish_ingredients_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `inventory_receipts`
--
ALTER TABLE `inventory_receipts`
  ADD CONSTRAINT `inventory_receipts_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`u_id`);

--
-- Các ràng buộc cho bảng `inventory_receipt_items`
--
ALTER TABLE `inventory_receipt_items`
  ADD CONSTRAINT `inventory_receipt_items_ibfk_1` FOREIGN KEY (`receipt_id`) REFERENCES `inventory_receipts` (`receipt_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_receipt_items_ibfk_2` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`ingredient_id`);

--
-- Các ràng buộc cho bảng `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`rs_id`) REFERENCES `restaurant` (`rs_id`),
  ADD CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`u_id`);

--
-- Các ràng buộc cho bảng `users_orders`
--
ALTER TABLE `users_orders`
  ADD CONSTRAINT `fk_users_orders_restaurant` FOREIGN KEY (`rs_id`) REFERENCES `restaurant` (`rs_id`),
  ADD CONSTRAINT `fk_users_orders_shippers` FOREIGN KEY (`shipper_id`) REFERENCES `shippers` (`shipper_id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `worthy`
--
ALTER TABLE `worthy`
  ADD CONSTRAINT `worthy_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`u_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `worthy_ibfk_2` FOREIGN KEY (`res_id`) REFERENCES `restaurant` (`rs_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
