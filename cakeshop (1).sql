-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2026 at 01:37 AM
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
-- Database: `cakeshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'admin', 'admin@gmail.com', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `cake`
--

CREATE TABLE `cake` (
  `id` int(100) NOT NULL,
  `cake_name` varchar(200) NOT NULL,
  `category` varchar(200) NOT NULL,
  `category_id` int(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `flavour` varchar(200) NOT NULL,
  `weight` varchar(200) NOT NULL,
  `image` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `stock` int(11) NOT NULL,
  `status` enum('Available','Out of Stock') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cake`
--

INSERT INTO `cake` (`id`, `cake_name`, `category`, `category_id`, `price`, `flavour`, `weight`, `image`, `description`, `stock`, `status`) VALUES
(1, 'chocklet', 'birthday', NULL, 800.00, 'chocolate', '1 kg', 'choc.jpg', 'fresh chocolate birthday cake', 7, 'Available'),
(2, 'black forest cake', 'anniversary cake', NULL, 650.00, 'black forest cake', '2 kg', 'black.jpg', 'soft black forest cake', 0, 'Out of Stock'),
(3, 'red velvet cake', 'disigner cake', NULL, 900.00, 'red velvet', '1 kg', 'red.jpg', 'delicious red velvet cake', 13, 'Available'),
(4, 'butterscotch cake', 'regular cake', NULL, 450.00, 'butterscotch', '500 gm', 'butter.jpg', 'creamy butterscotch cake', 6, 'Available'),
(5, 'pineapple cake ', 'eggless cake', NULL, 400.00, 'pineapple', '500 gm', 'pineapple.jpg', 'fresh eggless pineapple cake', 4, 'Available'),
(6, 'vanilla cake', 'regular cake', NULL, 550.00, 'vanilla', '1 kg', 'vanilla.jpg', 'soft vanilla sponge cake ', 0, 'Available'),
(7, 'kitkat cake', 'disigner cake', NULL, 900.00, 'kitkat', '2 kg', 'kitkat.jpg', 'chocolate cake with kitkat decoration', 55, 'Available'),
(8, 'strawberry cake', 'birthday cake', 1, 700.00, 'strawberry ', '2 kg', 'strawberry .jpg', 'fresh strawberry  cream cake', 25, 'Available'),
(9, 'oreo cake', 'premium cake', NULL, 750.00, 'oreo', '1 kg', 'oreo.jpg', 'rich oreo cream cake', 52, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(100) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `cake_name` varchar(100) NOT NULL,
  `cake_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `otp_status` varchar(20) NOT NULL,
  `cart_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `mobile` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `customer_name`, `email`, `customer_id`, `cake_name`, `cake_id`, `quantity`, `price`, `total`, `otp`, `otp_status`, `cart_date`, `mobile`, `address`, `payment_method`) VALUES
(2, 'priyanka ajani', 'ajanipriyanka13@gmail.com', NULL, 'pineapple cake ', 5, 1, 400.00, 400.00, '156456', 'Verified', '2026-08-24 23:24:50', NULL, NULL, NULL),
(3, 'priyanka', 'ajanipriyanka13@gmail.com', 1, 'black forest cake', 2, 1, 650.00, 650.00, '', 'Verified', '2026-08-24 23:24:50', NULL, NULL, NULL),
(5, 'priyanka', 'ajanipriyanka13@gmail.com', 1, 'black forest cake', 2, 1, 650.00, 650.00, '', '', '2026-08-24 23:24:50', NULL, NULL, NULL),
(6, 'priyanka', 'ajanipriyanka13@gmail.com', 1, 'chocklet', 1, 3, 800.00, 2400.00, '', '', '2026-08-24 23:24:50', NULL, NULL, NULL),
(7, 'yash', 'yash@gmail.com', NULL, 'vanilla cake', 6, 2, 550.00, 1100.00, '', '', '2026-08-24 23:24:50', '9087654326', 'amreli', NULL),
(9, 'priyanka', 'ajanipriyanka13@gmail.com', 1, 'pineapple cake ', 5, 1, 400.00, 400.00, '', '', '2026-08-24 23:24:50', '8767564545', 'amreli', 'PhonePe'),
(18, '', '', NULL, 'kitkat cake', 7, 1, 900.00, 900.00, '', '', '2026-08-24 23:24:50', NULL, NULL, NULL),
(25, '', '', NULL, 'strawberry cake', 8, 1, 700.00, 700.00, '', '', '2026-08-24 23:24:50', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(100) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`, `description`, `image`, `status`, `created_at`) VALUES
(1, 'birthday cake', 'Delicious and beautifully designed cakes made specially for birthday celebrations. Available in different flavors, sizes and customized designs.', '1786841867_6a810b0b45ca2.png', 'Active', '2026-08-16 00:57:47'),
(2, 'Oreo Cake', 'A delicious and creamy chocolate cake made with soft chocolate sponge, rich Oreo cream, and crunchy Oreo cookie pieces. Perfect for birthdays, celebrations, and Oreo lovers.', '1786841832_6a810ae84f7bf.jpg', 'Active', '2026-08-16 00:57:12'),
(4, 'Chocolate Cake', 'Delicious and moist chocolate cake with rich chocolate cream and smooth frosting, perfect for every celebration.', '1786841957_6a810b65e0245.png', 'Active', '2026-08-16 00:59:17');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `customer_name`, `email`, `mobile`, `address`) VALUES
(1, 'priyanka', 'priyanka@gmail.com', '8767564545', 'amreli'),
(2, 'dharvi', 'dharvi2@gmail.com', '8907654432', 'surat gujrat'),
(3, 'twinsi', 'twinsi45@gmail.com', '9087654326', 'rajkot gujrat'),
(4, 'trusha', 'trusha23@gmail.com', '8765409873', 'ahemdabad gujrat');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `delivery_person` varchar(100) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `delivery_status` varchar(50) NOT NULL DEFAULT 'Pending',
  `delivery_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`id`, `order_id`, `customer_name`, `mobile`, `address`, `delivery_person`, `staff_id`, `delivery_status`, `delivery_date`, `created_at`) VALUES
(1, 19, 'miraa', '0987654321', 'lilyaa gujrat chora pase', 'priyanka', NULL, 'Delivered', '2026-08-16 21:16:26', '2026-08-16 19:13:56'),
(2, 20, 'krishna', '0987654321', 'amrpur varudi', 'priyanka', NULL, 'Pending', '2026-08-16 22:26:47', '2026-08-16 20:26:47'),
(3, 21, 'rutu', '8765409873', 'amreli', 'priyanka', NULL, 'Delivered', '2026-08-17 00:54:55', '2026-08-16 22:35:04'),
(4, 22, 'Ajani Priyanka 14', '8907654432', 'lilyaaa', 'priyanka', NULL, 'Out For Delivery', '2026-08-17 00:38:01', '2026-08-16 22:38:01'),
(5, 23, 'Ajani', '0987654321', 'surat', '', NULL, 'Pending', '2026-08-17 00:41:52', '2026-08-16 22:41:52'),
(6, 24, 'twinsi', '8769504324', 'chital', 'nisha', NULL, 'Delivered', '2026-08-17 00:54:24', '2026-08-16 22:42:59'),
(7, 25, 'riya', '0987654321', 'surat', 'priyanka', NULL, 'Delivered', '2026-08-17 01:39:19', '2026-08-16 23:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `enquiry`
--

CREATE TABLE `enquiry` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `enquiry_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enquiry`
--

INSERT INTO `enquiry` (`id`, `name`, `email`, `mobile`, `subject`, `message`, `status`, `enquiry_date`) VALUES
(1, 'priyanka', 'prisha@gmail.com', '8769504324', 'Birthday Cake Enquiry', 'I want to order a chocolate cake for a birthday celebration. Please provide the available sizes and prices.', 'Pending', '2026-08-16 18:52:30');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cake_name` varchar(100) DEFAULT NULL,
  `cake_id` int(11) DEFAULT NULL,
  `rating` int(1) NOT NULL,
  `message` text NOT NULL,
  `feedback_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `order_id`, `customer_id`, `customer_name`, `email`, `cake_name`, `cake_id`, `rating`, `message`, `feedback_date`) VALUES
(1, NULL, NULL, 'priyanka ajani', 'ajanipriyanka13@gmail.com', NULL, NULL, 5, 'Cake bahut tasty tha aur delivery bhi time par hui.', '2026-08-16 21:21:16'),
(2, 24, 3, 'twinsi', 'twinsi45@gmail.com', 'pineapple cake ', 5, 5, 'Cake ખૂબ જ fresh અને delicious હતો 😍 Chocolate flavor બહુ જ સરસ હતો અને cake soft & creamy હતો. Delivery પણ time પર મળી. Overall ખૂબ જ સરસ experience. Definitely recommended! ❤️', '2026-08-16 15:58:36'),
(3, 25, NULL, 'riya', 'riya@gmail.com', 'oreo cake', 9, 5, 'best cake and happy moment thankyou so much❤️👌', '2026-08-16 16:41:04');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `login_id` int(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(20) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`login_id`, `email`, `password`, `role`, `name`) VALUES
(1, 'admin@gmail.com', '12345', 'admin', ''),
(2, 'priyanka@gmail.com', '123456', '', ''),
(3, 'prisha@gmail.com', '2714', '', 'prisha'),
(4, 'pinuu@gmail.com', '2714', '', 'pinuu');

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `cake_id` int(11) NOT NULL,
  `offer_title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `discount` int(3) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `cake_id`, `offer_title`, `description`, `discount`, `start_date`, `end_date`, `status`) VALUES
(3, 7, 'KitKat Cake Delight', '🍫 Enjoy our delicious KitKat Cake with a special discount! Perfectly layered with rich chocolate cream and KitKat chocolates. Treat yourself and your loved ones to this sweet delight at a special price.', 5, '2026-08-16', '2026-09-05', 'Active'),
(4, 9, 'Oreo Cake Festive Offer', 'Enjoy our delicious Oreo Cake with a special discount! 🍪🎂 Rich chocolate cake layered with creamy Oreo filling and crunchy Oreo cookies. Perfect for birthdays and every special celebration. Order now and enjoy this sweet delight!”', 30, '2026-08-28', '2026-09-08', 'Active'),
(5, 5, 'Pineapple Cake Fresh Delight 🍍🎂', '“Enjoy our delicious Pineapple Cake made with soft vanilla sponge, creamy pineapple filling and juicy pineapple pieces. A perfect combination of sweetness and freshness for every celebration. Order now and enjoy this delightful cake!”', 50, '2026-08-16', '2026-09-10', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `cake_name` varchar(100) NOT NULL,
  `cake_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `order_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `customer_id`, `cake_name`, `cake_id`, `quantity`, `amount`, `payment_method`, `status`, `order_date`) VALUES
(6, 'prisha', NULL, 'venilla cake', NULL, 3, 700, NULL, 'pending', '2026-08-04'),
(8, 'twinsi', 3, 'black forest cake', 2, 1, 650, NULL, 'Pending', '2026-08-13'),
(11, 'priyanka', 1, 'pineapple cake ', 5, 1, 400, 'PhonePe', 'Pending', '0000-00-00'),
(12, 'niva', NULL, 'butterscotch cake', 4, 1, 450, NULL, 'Pending', '2026-08-16'),
(13, 'dharvi', 2, 'pineapple cake ', 5, 1, 400, NULL, 'Pending', '2026-08-16'),
(14, 'krishna', NULL, 'pineapple cake ', 5, 1, 400, NULL, 'Pending', '2026-08-16'),
(15, 'nisha', NULL, 'strawberry cake', 8, 1, 700, NULL, 'Pending', '2026-08-16'),
(16, 'gudi', NULL, 'pineapple cake ', 5, 1, 400, NULL, 'Pending', '2026-08-16'),
(17, 'drasti', NULL, 'butterscotch cake', 4, 1, 450, NULL, 'Pending', '2026-08-16'),
(18, 'pratik', NULL, 'strawberry cake', 8, 1, 700, NULL, 'Pending', '2026-08-16'),
(19, 'miraa', NULL, 'strawberry cake', 8, 1, 700, NULL, 'Pending', '2026-08-16'),
(20, 'krishna', NULL, 'pineapple cake ', 5, 1, 400, NULL, 'Pending', '2026-08-16'),
(21, 'rutu', NULL, 'butterscotch cake', 4, 1, 450, NULL, 'Pending', '2026-08-17'),
(22, 'Ajani Priyanka 14', NULL, 'butterscotch cake', 4, 1, 450, NULL, 'Pending', '2026-08-17'),
(23, 'Ajani', NULL, 'chocklet', 1, 1, 800, NULL, 'Pending', '2026-08-17'),
(24, 'twinsi', 3, 'pineapple cake ', 5, 1, 400, NULL, 'Pending', '2026-08-17'),
(25, 'riya', NULL, 'oreo cake', 9, 1, 750, NULL, 'Pending', '2026-08-17');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_ref_id` int(11) DEFAULT NULL,
  `customer_name` varchar(200) NOT NULL,
  `payment_method` varchar(200) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` varchar(200) NOT NULL,
  `payment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `order_id`, `order_ref_id`, `customer_name`, `payment_method`, `amount`, `payment_status`, `payment_date`) VALUES
(2, 3, NULL, 'priyanka', 'COD', 300000.00, 'Pending', '2026-07-08'),
(3, 5, NULL, 'pinu', 'UPI', 50000.00, 'Failed', '2026-07-07'),
(4, 1, NULL, 'rutu', 'Card', 80000.00, 'Paid', '2026-07-29'),
(5, 1, NULL, 'yash', 'Cash On Delivery', 3600.00, 'Pending', '2026-08-13'),
(6, 4, NULL, 'twinsi', 'PhonePe', 650.00, 'Paid', '2026-08-13'),
(7, 8, 8, 'priyanka', 'Debit / Credit Card', 450.00, 'Paid', '2026-08-16');

-- --------------------------------------------------------

--
-- Table structure for table `reg`
--

CREATE TABLE `reg` (
  `reg_id` int(100) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `Mobile` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reg`
--

INSERT INTO `reg` (`reg_id`, `name`, `email`, `password`, `Mobile`) VALUES
(1, 'priyanka ajani', 'prish@gmail.com', '123456', '9045634241'),
(2, 'priyanka', 'priyanka@gmail.com', '123456', '9080767543'),
(3, 'priyanka', 'ajani@gmail.come', '123457', '9080767543'),
(4, 'Ajani Priyanka 14', 'ajanipriyanka13@gmail.com', '1234567', '9080767543'),
(5, 'Ajani Priyanka 14', 'ajanipriyanka13@gmail.com', '12345', '9080767543'),
(6, 'piyu', 'ajani13@gmail.com', '45678', '5678945123'),
(7, 'pp', 'pp@gmail.com', '3456', '1234567891'),
(8, 'prii', 'a@yahoo.com', '1234', '5674342323');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `shop_name` varchar(100) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `shop_name`, `admin_name`, `email`, `mobile`, `address`) VALUES
(1, 'SWIFFIN CAKE SHOP', 'Admin', 'swiffin10@gmail.com', '9876543210', 'amreli, Gujarat\r\n\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `position` varchar(50) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `email`, `mobile`, `position`, `salary`, `status`) VALUES
(1, 'yag', 'yag@gmail.com', '8767564545', 'Manager', 10000.00, 'Active'),
(2, 'yug', 'yug@gamil.com', '8767564545', 'Cashier', 9000.00, 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cake`
--
ALTER TABLE `cake`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cake_category_id` (`category_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_carts_cake_id` (`cake_id`),
  ADD KEY `idx_carts_customer_id` (`customer_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_delivery_order_id` (`order_id`),
  ADD KEY `idx_delivery_staff_id` (`staff_id`);

--
-- Indexes for table `enquiry`
--
ALTER TABLE `enquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_feedback_order_id` (`order_id`),
  ADD KEY `idx_feedback_customer_id` (`customer_id`),
  ADD KEY `idx_feedback_cake_id` (`cake_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`login_id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_offers_cake_id` (`cake_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_orders_cake_id` (`cake_id`),
  ADD KEY `idx_orders_customer_id` (`customer_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_payment_order_ref_id` (`order_ref_id`);

--
-- Indexes for table `reg`
--
ALTER TABLE `reg`
  ADD PRIMARY KEY (`reg_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cake`
--
ALTER TABLE `cake`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `enquiry`
--
ALTER TABLE `enquiry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `login_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reg`
--
ALTER TABLE `reg`
  MODIFY `reg_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cake`
--
ALTER TABLE `cake`
  ADD CONSTRAINT `fk_cake_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_carts_cake` FOREIGN KEY (`cake_id`) REFERENCES `cake` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_carts_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `fk_delivery_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_delivery_staff` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_feedback_cake` FOREIGN KEY (`cake_id`) REFERENCES `cake` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_feedback_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_feedback_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `fk_offers_cake` FOREIGN KEY (`cake_id`) REFERENCES `cake` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_cake` FOREIGN KEY (`cake_id`) REFERENCES `cake` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_order_ref` FOREIGN KEY (`order_ref_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
