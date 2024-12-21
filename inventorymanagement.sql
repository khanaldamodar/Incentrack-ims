-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2024 at 03:23 PM
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
-- Database: `inventorymanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `passkey` varchar(16) NOT NULL,
  `number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `passkey`, `number`) VALUES
(1, 'root', 'root@gmail.com', 'root', NULL),
(2, 'Deepak', 'deepak@gmail.com', 'root', NULL),
(3, 'deepak khanal', 'deepakkhanal931@gmail.com', 'root', 2147483647),
(4, 'deepak khanal', 'deepakkhanal931@gmail.com', 'root', 2147483647),
(5, 'deepak khanal', 'deepakkhanal931@gmail.com', 'root', 2147483647),
(6, 'deepak khanal', 'root@gmail.com', 'root', 2147483647),
(7, 'deepak khanal', 'root@gmail.com', 'root', 2147483647),
(8, 'Damodar Kanal', 'damodar@gmail.com', '12345678', 2147483647);

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `middle_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) NOT NULL,
  `shop_name` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `address` text NOT NULL,
  `passkey` varchar(16) NOT NULL,
  `postal_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`client_id`, `first_name`, `middle_name`, `last_name`, `shop_name`, `email`, `phone_number`, `address`, `passkey`, `postal_code`) VALUES
(1, 'Hari', 'A', 'Bhandari', 'Johns Groceries', 'hari@gmail.com', '9847483647', '123 Main St, Cityville', 'pass1234', 12345),
(2, 'Jane', NULL, 'Smith', 'Smiths Clothing', 'jane.smith@example.com', '2147483647', '456 Elm St, Townsville', 'secure5678', 23456),
(3, 'Emily', 'R', 'Johnson', 'Emilys Bakery', 'emily.johnson@example.com', '2147483647', '789 Oak St, Villagetown', 'emily2023', 34567),
(4, 'Michael', NULL, 'Brown', 'Brown Electronics', 'michael.brown@example.com', '2147483647', '101 Pine St, Metrocity', 'tech7890', 45678),
(5, 'Chris', 'T', 'Evans', 'Chris Cafeteria', 'chris.evans@example.com', '2147483647', '202 Maple St, Capitaltown', 'food2024', 56789),
(6, 'Ram', 'Prashad', 'Khanal', 'Khanal Shop', 'ram@gmail.com', '2147483647', 'Kapilvastu', '$2y$10$bTdtXUxkN', 32809),
(7, 'Ramm', 's', 'kumar', 'xyz', 'ramm@gmail.com', '2147483647', 'ktm', '$2y$10$VPUM.4AlZ', 123456),
(8, 'Pawan', 'kumar', 'khanal', 'pawans shop', 'p@gmail.com', '1234567894', 'kplvstu', '$2y$10$ePnhHKNjU', 123212),
(9, 'Deepak', '', 'khanal', 'dpk', 'd@gmail.com', '987654321', 'asd', '123456789', 123456);

-- --------------------------------------------------------

--
-- Table structure for table `client_requests`
--

CREATE TABLE `client_requests` (
  `request_id` int(11) NOT NULL,
  `product` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_price` float NOT NULL,
  `price` float NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `status` enum('Pending','Approved','Canceled','') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_requests`
--

INSERT INTO `client_requests` (`request_id`, `product`, `quantity`, `created_at`, `total_price`, `price`, `created_by`, `status`) VALUES
(11, 'ACER', 9, '2024-12-17 13:36:52', 360000, 40000, 'Hari Bhandari', 'Pending'),
(12, 'Lenevo', 9, '2024-12-17 13:53:54', 540018, 60002, 'Hari Bhandari', 'Pending'),
(13, 'Lenevo', 6, '2024-12-17 14:13:10', 360012, 60002, 'Hari Bhandari', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) GENERATED ALWAYS AS (`quantity` * `price`) STORED,
  `created_by` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `product_name`, `supplier_name`, `quantity`, `price`, `created_by`, `created_at`) VALUES
(1, 'demo', 'demo', 20, 100.00, '', '2024-12-03 12:44:20'),
(2, 'ACER', 'Deepak', 9, 0.00, '', '2024-12-03 12:44:20'),
(3, 'MacBook', 'Deepak', 1, 0.00, '', '2024-12-03 12:44:20'),
(4, 'ACER', 'Janaki Kumari Rokaya', 10, 0.00, '', '2024-12-03 12:44:20'),
(5, 'ACER', 'Janaki Kumari Rokaya', 10, 0.00, '', '2024-12-03 12:44:20'),
(11, 'Lenevo', 'Damodar Khanal', 10, 0.00, 'root', '2024-12-03 12:44:20'),
(12, 'Lenevo', 'Janaki Rokaya', 20, 0.00, 'root', '2024-12-03 12:44:20'),
(13, 'Lenevo', 'Janaki Rokaya', 1, 0.00, 'root', '2024-12-03 12:44:20'),
(14, 'Lenevo', 'Janaki Rokaya', 1, 60002.00, 'root', '2024-12-03 12:44:20'),
(15, 'Lenevo', 'Janaki Rokaya', 60, 60002.00, 'root', '2024-12-03 12:44:20'),
(16, 'Asus', 'Hari', 3, 20000.00, 'root', '2024-12-03 12:45:25');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `total_stock` int(11) NOT NULL,
  `product_price` int(11) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category`, `total_stock`, `product_price`, `created_by`, `created_at`) VALUES
(1, 'MacBook', 'Laptops', 0, 200000, '', '2024-12-03 12:43:46'),
(2, 'MacBook', 'Laptops', 0, 100000, '', '2024-12-03 12:43:46'),
(3, 'ACER', 'Laptop', 1, 40000, '', '2024-12-03 12:43:46'),
(4, 'Lenevo', 'Laptops', 100, 60002, 'root', '2024-12-03 12:43:46'),
(5, 'Asus', 'Laptop', 17, 20000, 'root', '2024-12-03 12:44:41'),
(28, 'a', 'a', 1, 2, 'rampoudel', '2024-12-10 12:51:40');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `address` varchar(100) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `email`, `phone`, `address`, `created_by`, `created_at`) VALUES
(3, 'Deepak', 'bcanotes21@gmail.com', '9866437014', 'Buddhabhumi-04', '', '2024-12-03 12:43:57'),
(4, 'Janaki Kumari Rokaya', 'janaki@gmail.com', '9812345678', 'Thankot', '', '2024-12-03 12:43:57'),
(5, 'Damodar Khanal', 'deepakkhanal931@gmail.com', '9866437014', 'Buddhabhumi-2 buddhi kapilvastu\r\ngorusinge', 'root', '2024-12-03 12:43:57'),
(6, 'Janaki Rokaya', 'janaki@gmail.com', '1234567890', 'Thankot', 'root', '2024-12-03 12:43:57'),
(7, 'Hari', 'hari@gmail.com', '9812343212', 'Kathmandu', 'root', '2024-12-03 12:45:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `company_size` varchar(10) DEFAULT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `first_name`, `last_name`, `address`, `state`, `postal_code`, `phone_number`, `company_size`, `company_name`, `email`, `password_hash`) VALUES
('rampoudel', 'Ramu', 'Poudel', 'Sundhara, Kathmandu', 'Bagmati', '44600', '980-1234-5678', 'Small', 'Poudel Enterprises', 'ram.poudel@example.com', 'hashed_password_1'),
('sitasherpa', 'Sita', 'Sherpa', 'Boudha, Kathmandu', 'Bagmati', '44620', '981-8765-4321', 'Medium', 'Sherpa Technologies', 'sita.sherpa@example.com', 'hashed_password_2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `client_requests`
--
ALTER TABLE `client_requests`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `client_requests`
--
ALTER TABLE `client_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
