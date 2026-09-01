-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2026 at 03:43 AM
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
-- Database: `belief`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `medicine_id`, `quantity`) VALUES
(11, 1, 9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(2, 'tablet');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(11) NOT NULL,
  `medicine_name` varchar(100) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `strength` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `expiry_date` date NOT NULL,
  `image` varchar(255) NOT NULL,
  `is_popular` int(11) DEFAULT 0,
  `requires_prescription` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `medicine_name`, `company_name`, `category`, `strength`, `price`, `stock`, `expiry_date`, `image`, `is_popular`, `requires_prescription`) VALUES
(4, 'cough', 'sunpharma', 'Syrup', '200ml', 200.00, 30, '2026-08-28', '1787028323_syrup.png', 0, 0),
(5, 'eyedrop', 'adroventpharma', 'drop', '20ml', 45.00, 22, '2029-08-14', '1787028377_drop.png', 0, 0),
(6, 'eardrop', 'Hariom', 'drop', '20ml', 66.00, 19, '2030-09-17', '1787028413_drop1.png', 0, 0),
(7, 'cifixime', 'Hariom', 'Tablet', '20mg', 26.00, 10, '2029-11-20', '1787234268_tablet1.jpg', 0, 0),
(8, 'fever', 'sunpharma', 'Cream', '20ml', 345.00, 1, '2026-08-21', '1787234643_myaccount-background-image.png', 0, 0),
(9, '1ceftvent', 'adroventpharma', 'Tablet', '200mg', 30.00, 29, '2028-08-20', '1787313816_tablet3.webp', 0, 0),
(11, 'creammmmm', 'Hariom', 'Cream', '20ml', 234.00, 2, '2026-08-22', '1787331125_product-image-slider-1.jpg', 0, 0),
(12, 'antiboitics', 'sunpharma', 'tablet', '200mg', 456.00, 56, '2026-08-21', '1787354165_h3.jpg', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT 'Pending',
  `address` text DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `payment_method`, `payment_status`, `address`, `order_date`, `status`, `total_price`) VALUES
(1, 1, 89.00, NULL, 'Pending', NULL, '2026-08-18 04:14:25', 'Pending', 0.00),
(2, 1, 111.00, NULL, 'Pending', NULL, '2026-08-18 04:50:49', 'Confirmed', 0.00),
(3, 1, 732.00, 'Online Payment', 'Paid', 'chutal', '2026-08-21 12:27:26', 'Pending', 0.00),
(4, 1, 30.00, NULL, 'Pending', NULL, '2026-08-21 12:30:12', 'Completed', 0.00),
(5, 1, 80.00, 'UPI', 'Pending', 'fghfudrey', '2026-08-21 15:55:14', 'Pending', 0.00),
(6, 1, 110.00, 'Online Payment', 'Paid', 'Name: Twinsi Desai | Phone: 24444444444444444 | Email: desai19@gmail.com | Address: sdaaadfffffffffffffffff', '2026-08-21 16:01:13', 'Pending', 0.00),
(7, 1, 110.00, 'COD', 'Pending', 'Name: Twinsi Desai | Phone: 24444444444444444 | Email: desai19@gmail.com | Address: sdaaadfffffffffffffffff', '2026-08-21 16:01:19', 'Cancelled', 0.00),
(8, 1, 110.00, 'COD', 'Pending', 'Name: Twinsi Desai | Phone: 24444444444444444 | Email: desai19@gmail.com | Address: sdaaadfffffffffffffffff', '2026-08-21 16:01:36', 'Order Placed', 0.00),
(9, 1, 110.00, 'COD', 'Pending', 'Name: Twinsi Desai | Phone: 24444444444444444 | Email: desai19@gmail.com | Address: sdaaadfffffffffffffffff', '2026-08-21 16:03:50', 'Packed', 0.00),
(10, 1, 588.00, 'COD', 'Pending', 'Name: fgfhg | Phone: fgfh | Email: sai19@gmail.com | Address: fghuhgj', '2026-08-21 19:12:53', 'Completed', 0.00),
(11, 1, 742.00, 'COD', 'Pending', 'Name: yuytu | Phone: 23455677 | Email: abc@gmail.com | Address: 45646', '2026-08-21 19:38:32', 'Order Placed', 0.00),
(12, 1, 80.00, 'COD', 'Pending', 'Name: fgfg | Phone: 24444444444444444 | Email: sai19@gmail.com | Address: tytuyt', '2026-08-21 21:12:44', 'Out for Delivery', 0.00),
(13, 1, 80.00, 'COD', 'Pending', 'Name: 5tt5 | Phone: 24444444444444444 | Email: esai19@gmail.com | Address: rtry', '2026-08-21 21:23:39', 'Order Placed', 0.00),
(14, 1, 80.00, 'COD', 'Pending', 'Name: tytu | Phone: 9878 | Email: fdg@gmail.com | Address: dhfugtrh', '2026-08-21 22:20:03', 'Pending', 0.00),
(15, 5, 121.00, 'COD', 'Pending', 'Name: eretr | Phone: 334354546 | Email: sdsf@gmail.com | Address: amreli', '2026-08-22 01:21:29', 'Pending', 0.00),
(16, 5, 80.00, 'COD', 'Pending', 'Name: fdgf | Phone: 45465 | Email: dsgfg@gmail.com | Address: asdhbsfjd', '2026-08-22 01:22:27', 'Pending', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `medicine_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 89.00),
(2, 2, 6, 1, 66.00),
(3, 2, 5, 1, 45.00),
(4, 3, 9, 4, 30.00),
(5, 3, 6, 2, 66.00),
(6, 3, 10, 3, 30.00),
(7, 3, 8, 1, 345.00),
(8, 3, 5, 1, 45.00),
(9, 4, 9, 1, 30.00),
(10, 5, 9, 1, 30.00),
(11, 9, 9, 2, 30.00),
(12, 10, 11, 2, 234.00),
(13, 10, 9, 4, 30.00),
(14, 11, 8, 2, 345.00),
(15, 11, 7, 2, 26.00),
(16, 12, 9, 1, 30.00),
(17, 13, 9, 1, 30.00),
(18, 14, 9, 1, 30.00),
(19, 15, 5, 1, 45.00),
(20, 15, 7, 1, 26.00),
(21, 16, 9, 1, 30.00);

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`id`, `order_id`, `user_id`, `file_name`, `uploaded_at`, `status`) VALUES
(1, 1, 1, '1787026465_1_2026-08-09__5_.png', '2026-08-18 04:14:25', 'Pending'),
(2, 2, 1, '1787028649_1_1786878507_m1.png', '2026-08-18 04:50:49', 'Pending'),
(3, 9, 1, '1787328230_6a8876e63405e.png', '2026-08-21 16:03:50', 'Pending'),
(4, 10, 1, '1787339573_6a88a335805ac.png', '2026-08-21 19:12:53', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `supplier_id`, `medicine_id`, `quantity`, `purchase_price`, `purchase_date`) VALUES
(1, 2, 1, 8, 60.00, '2026-08-12 07:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `order_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 11, 1, 5, 'great ', '2026-08-21 21:24:11'),
(2, 10, 1, 4, 'excellence', '2026-08-21 21:24:29');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `supplier_name`, `company_name`, `mobile_no`, `email`) VALUES
(2, 'priyank', 'Hariom', '90909090', 'priyank@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `mobile_no`, `email`, `password`, `user_type`) VALUES
(1, 'dt', '12345566', 'dt@gmail.com', '$2y$10$2yyLvBd522q2qPBBGI8KceeldRGK1KIrnsFfa/UFcUnM764BQwVhu', 'user'),
(2, 'admin', '9999999999', 'admin@gmail.com', 'admin123', 'admin'),
(3, 'dtr', '3468688', 'desai@gmail.com', '$2y$10$YCHulB3yuW0s2eYVGKIiee7EmLJmiaAf313LgywaPCc4ieJ8agtQq', 'user'),
(4, 'td', '12345566', 'desai1@gmail.com', '$2y$10$QE58L.R8hRmVBF.qiS/18uuVaelWSkL1LfwZqI5vNlZNoMUwMnKyu', 'user'),
(5, 'aryan', '8090809080', 'aryan@gmail.com', '$2y$10$sYA1LNMEWEe6XmrG.zoz7eo/mHJrtoncNhNLfHM79zxpXu6MHqPka', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
