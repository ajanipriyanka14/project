-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2026 at 03:06 AM
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

INSERT INTO `cake` (`id`, `cake_name`, `category`, `price`, `flavour`, `weight`, `image`, `description`, `stock`, `status`) VALUES
(1, 'chocklet', 'birthday', 800.00, 'chocolate', '1 kg', 'choc.jpg', 'fresh chocolate birthday cake', 10, 'Available'),
(2, 'black forest cake', 'anniversary cake', 650.00, 'black forest cake', '2 kg', 'black.jpg', 'soft black forest cake', 3, 'Available'),
(3, 'red velvet cake', 'disigner cake', 900.00, 'red velvet', '1 kg', 'red.jpg', 'delicious red velvet cake', 13, 'Out of Stock'),
(4, 'butterscotch cake', 'regular cake', 450.00, 'butterscotch', '500 gm', 'butter.jpg', 'creamy butterscotch cake', 6, 'Available'),
(5, 'pineapple cake ', 'eggless cake', 400.00, 'pineapple', '500 gm', 'pineapple.jpg', 'fresh eggless pineapple cake', 4, 'Available'),
(6, 'vanilla cake', 'regular cake', 550.00, 'vanilla', '1 kg', 'vanilla.jpg', 'soft vanilla sponge cake ', 2, 'Available'),
(7, 'kitkat cake', 'disigner cake', 900.00, 'kitkat', '2 kg', 'kitkat.jpg', 'chocolate cake with kitkat decoration', 52, 'Available'),
(8, 'strawberry cake', 'birthday cake', 700.00, 'strawberry ', '2 kg', 'strawberry .jpg', 'fresh strawberry  cream cake', 20, 'Out of Stock'),
(9, 'oreo cake', 'premium cake', 750.00, 'oreo', '1 kg', 'oreo.jpg', 'rich oreo cream cake', 50, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(100) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cake_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `otp_status` varchar(20) NOT NULL,
  `cart_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `customer_name`, `email`, `cake_name`, `quantity`, `price`, `total`, `otp`, `otp_status`, `cart_date`) VALUES
(1, 'swiffin', 'swiffincake10@gmail.com', 'chocklet', 4, 900.00, 3600.00, '593561', 'Verified', '2026-07-28 03:04:36'),
(2, 'priyanka ajani', 'ajanipriyanka13@gmail.com', 'pineapple cake ', 1, 400.00, 400.00, '156456', 'Verified', '2026-08-04 02:11:16');

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
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `cake_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `order_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `cake_name`, `quantity`, `amount`, `status`, `order_date`) VALUES
(1, 'priyanka', 'black forest cake', 1, 500, 'pending', '2026-07-26'),
(2, 'trusha', 'oreo cake', 2, 800, 'confirmed', '2026-08-04'),
(3, 'twinsi', 'red velvet cake', 1, 900, 'delivered', '2026-07-26'),
(4, 'dharvi', 'pineapple cake', 1, 900, 'pending', '2026-08-04'),
(5, 'drasti', 'chocolate cake', 1, 500, 'confirmed', '2026-08-04'),
(6, 'prisha', 'venilla cake', 3, 700, 'pending', '2026-08-04');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_name` varchar(200) NOT NULL,
  `payment_method` varchar(200) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` varchar(200) NOT NULL,
  `payment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `order_id`, `customer_name`, `payment_method`, `amount`, `payment_status`, `payment_date`) VALUES
(1, 2, 'nishaa', 'Card', 3000.00, 'Paid', '2026-07-28'),
(2, 3, 'priyanka', 'COD', 300000.00, 'Pending', '2026-07-08'),
(3, 5, 'pinu', 'UPI', 50000.00, 'Failed', '2026-07-07'),
(4, 1, 'rutu', 'Card', 80000.00, 'Paid', '2026-07-29');

-- --------------------------------------------------------

--
-- Table structure for table `reg`
--

CREATE TABLE `reg` (
  `reg_id` int(100) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `mobail` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reg`
--

INSERT INTO `reg` (`reg_id`, `name`, `email`, `password`, `mobail`) VALUES
(1, 'priyanka ajani', 'prish@gmail.com', '123456', '9045634241'),
(2, 'priyanka', 'priyanka@gmail.com', '123456', '9080767543'),
(3, 'priyanka', 'ajani@gmail.come', '123457', '9080767543'),
(4, 'Ajani Priyanka 14', 'ajanipriyanka13@gmail.com', '1234567', '9080767543'),
(5, 'Ajani Priyanka 14', 'ajanipriyanka13@gmail.com', '12345', '9080767543'),
(6, 'piyu', 'ajani13@gmail.com', '45678', '5678945123'),
(7, 'pp', 'pp@gmail.com', '3456', '1234567891'),
(8, 'prii', 'a@yahoo.com', '1234', '5674342323');

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`login_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reg`
--
ALTER TABLE `reg`
  ADD PRIMARY KEY (`reg_id`);

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
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `login_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reg`
--
ALTER TABLE `reg`
  MODIFY `reg_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
