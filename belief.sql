CREATE DATABASE IF NOT EXISTS `belief`;
USE `belief`;

SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET FOREIGN_KEY_CHECKS=0;
START TRANSACTION;

DROP TABLE IF EXISTS `order_items`,`prescriptions`,`cart`,`purchases`,`orders`,`medicines`,`suppliers`,`users`;

CREATE TABLE `users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(100) NOT NULL,
 `mobile_no` varchar(15) NOT NULL,
 `email` varchar(100) NOT NULL,
 `password` varchar(255) NOT NULL,
 `user_type` enum('user','admin') NOT NULL DEFAULT 'user',
 PRIMARY KEY (`id`), UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` VALUES
(1,'dt','12345566','dt@gmail.com','$2y$10$2yyLvBd522q2qPBBGI8KceeldRGK1KIrnsFfa/UFcUnM764BQwVhu','user'),
(2,'admin','9999999999','admin@gmail.com','admin123','admin'),
(3,'dtr','3468688','desai@gmail.com','$2y$10$YCHulB3yuW0s2eYVGKIiee7EmLJmiaAf313LgywaPCc4ieJ8agtQq','user'),
(4,'td','12345566','desai1@gmail.com','$2y$10$QE58L.R8hRmVBF.qiS/18uuVaelWSkL1LfwZqI5vNlZNoMUwMnKyu','user');

CREATE TABLE `medicines` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `medicine_name` varchar(100) NOT NULL,
 `company_name` varchar(100) NOT NULL,
 `category` varchar(50) NOT NULL,
 `strength` varchar(50) NOT NULL,
 `price` decimal(10,2) NOT NULL,
 `stock` int(11) NOT NULL DEFAULT 0,
 `expiry_date` date NOT NULL,
 `image` varchar(255) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `medicines` VALUES
(4,'cough','sunpharma','Syrup','200ml',200.00,30,'2026-08-28','1787028323_syrup.png'),
(5,'eyedrop','adroventpharma','drop','20ml',45.00,22,'2029-08-14','1787028377_drop.png'),
(6,'eardrop','Hariom','drop','20ml',66.00,19,'2030-09-17','1787028413_drop1.png'),
(7,'cifixime','Hariom','Tablet','20mg',26.00,10,'2029-11-20','1787234268_tablet1.jpg'),
(8,'fever','sunpharma','Cream','20ml',345.00,1,'2026-08-21','1787234643_myaccount-background-image.png'),
(9,'1ceftvent','adroventpharma','Tablet','200mg',30.00,29,'2028-08-20','1787313816_tablet3.webp'),
(11,'creammmmm','Hariom','Cream','20ml',234.00,2,'2026-08-22','1787331125_product-image-slider-1.jpg');

CREATE TABLE `suppliers` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `supplier_name` varchar(100) NOT NULL,
 `company_name` varchar(100) NOT NULL,
 `mobile_no` varchar(15) NOT NULL,
 `email` varchar(100) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `suppliers` VALUES
(2,'priyank','Hariom','90909090','priyank@gmail.com');

CREATE TABLE `orders` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL,
 `total_amount` decimal(10,2) NOT NULL,
 `payment_method` varchar(50) DEFAULT NULL,
 `payment_status` varchar(20) DEFAULT 'Pending',
 `address` text DEFAULT NULL,
 `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
 `status` varchar(20) NOT NULL DEFAULT 'Pending',
 `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
 PRIMARY KEY (`id`), KEY `idx_orders_user_id` (`user_id`),
 CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
 ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `orders` VALUES
(1,1,89.00,NULL,'Pending',NULL,'2026-08-18 04:14:25','Pending',0.00),
(2,1,111.00,NULL,'Pending',NULL,'2026-08-18 04:50:49','Confirmed',0.00),
(3,1,732.00,'Online Payment','Paid','chutal','2026-08-21 12:27:26','Pending',0.00),
(4,1,30.00,NULL,'Pending',NULL,'2026-08-21 12:30:12','Completed',0.00),
(5,1,80.00,'UPI','Pending','fghfudrey','2026-08-21 15:55:14','Pending',0.00),
(6,1,110.00,'Online Payment','Paid','Name: Twinsi Desai | Phone: 24444444444444444 | Email: desai19@gmail.com | Address: sdaaadfffffffffffffffff','2026-08-21 16:01:13','Pending',0.00),
(7,1,110.00,'COD','Pending','Name: Twinsi Desai | Phone: 24444444444444444 | Email: desai19@gmail.com | Address: sdaaadfffffffffffffffff','2026-08-21 16:01:19','Pending',0.00),
(8,1,110.00,'COD','Pending','Name: Twinsi Desai | Phone: 24444444444444444 | Email: desai19@gmail.com | Address: sdaaadfffffffffffffffff','2026-08-21 16:01:36','Pending',0.00),
(9,1,110.00,'COD','Pending','Name: Twinsi Desai | Phone: 24444444444444444 | Email: desai19@gmail.com | Address: sdaaadfffffffffffffffff','2026-08-21 16:03:50','Pending',0.00);

CREATE TABLE `cart` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL,
 `medicine_id` int(11) NOT NULL,
 `quantity` int(11) NOT NULL DEFAULT 1,
 PRIMARY KEY (`id`), KEY `idx_cart_user_id` (`user_id`), KEY `idx_cart_medicine_id` (`medicine_id`),
 CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
 ON UPDATE CASCADE ON DELETE RESTRICT,
 CONSTRAINT `fk_cart_medicine` FOREIGN KEY (`medicine_id`) REFERENCES `medicines`(`id`)
 ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `cart` VALUES (11,1,9,1);

CREATE TABLE `order_items` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `order_id` int(11) NOT NULL,
 `medicine_id` int(11) NOT NULL,
 `quantity` int(11) NOT NULL,
 `price` decimal(10,2) NOT NULL,
 `medicine_ref_id` int(11) DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `idx_oi_order` (`order_id`), KEY `idx_oi_med_ref` (`medicine_ref_id`),
 CONSTRAINT `fk_oi_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`)
 ON UPDATE CASCADE ON DELETE CASCADE,
 CONSTRAINT `fk_oi_medicine` FOREIGN KEY (`medicine_ref_id`) REFERENCES `medicines`(`id`)
 ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `order_items` (`id`,`order_id`,`medicine_id`,`quantity`,`price`,`medicine_ref_id`) VALUES
(1,1,1,1,89.00,NULL),
(2,2,6,1,66.00,6),
(3,2,5,1,45.00,5),
(4,3,9,4,30.00,9),
(5,3,6,2,66.00,6),
(6,3,10,3,30.00,NULL),
(7,3,8,1,345.00,8),
(8,3,5,1,45.00,5),
(9,4,9,1,30.00,9),
(10,5,9,1,30.00,9),
(11,9,9,2,30.00,9);

CREATE TABLE `prescriptions` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `order_id` int(11) NOT NULL,
 `user_id` int(11) NOT NULL,
 `file_name` varchar(255) NOT NULL,
 `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
 `status` varchar(20) DEFAULT 'Pending',
 PRIMARY KEY (`id`), KEY `idx_pres_order` (`order_id`), KEY `idx_pres_user` (`user_id`),
 CONSTRAINT `fk_pres_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`)
 ON UPDATE CASCADE ON DELETE CASCADE,
 CONSTRAINT `fk_pres_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
 ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `prescriptions` VALUES
(1,1,1,'1787026465_1_2026-08-09__5_.png','2026-08-18 04:14:25','Pending'),
(2,2,1,'1787028649_1_1786878507_m1.png','2026-08-18 04:50:49','Pending'),
(3,9,1,'1787328230_6a8876e63405e.png','2026-08-21 16:03:50','Pending');

CREATE TABLE `purchases` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `supplier_id` int(11) NOT NULL,
 `medicine_id` int(11) NOT NULL,
 `quantity` int(11) NOT NULL,
 `purchase_price` decimal(10,2) NOT NULL,
 `purchase_date` timestamp NOT NULL DEFAULT current_timestamp(),
 `medicine_ref_id` int(11) DEFAULT NULL,
 PRIMARY KEY (`id`), KEY `idx_pur_supplier` (`supplier_id`), KEY `idx_pur_med_ref` (`medicine_ref_id`),
 CONSTRAINT `fk_pur_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`)
 ON UPDATE CASCADE ON DELETE RESTRICT,
 CONSTRAINT `fk_pur_medicine` FOREIGN KEY (`medicine_ref_id`) REFERENCES `medicines`(`id`)
 ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `purchases` VALUES
(1,2,1,8,60.00,'2026-08-12 07:00:00',NULL);

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
