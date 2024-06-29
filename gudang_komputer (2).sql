-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2024 at 10:05 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gudang_komputer`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id_category` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id_category`, `name`, `created_at`) VALUES
(1, 'Mouse\r\n', '2024-06-03 00:40:57'),
(2, 'Monitor', '2024-06-03 00:40:57'),
(3, 'Keyboard', '2024-06-04 07:47:25'),
(4, '', '2024-06-04 07:47:25');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(10,0) DEFAULT NULL,
  `id_category` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `stock` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `id_category`, `id_supplier`, `stock`, `created_at`, `updated_at`, `deleted_at`) VALUES
(17, 'rexus jovan', 150000, 1, 1, '100', '2024-06-03 07:46:22', NULL, '2024-06-03 07:21:29'),
(18, 'Monitor HP', 1000000, 2, 2, '2', '2024-06-03 08:07:45', '2024-06-03 03:07:54', '2024-06-03 06:42:22'),
(33, 'aoc 123', 1200000, 2, 1, '10', '2024-06-03 12:35:33', '2024-06-03 07:46:43', '2024-06-03 07:46:51'),
(34, 'Monitor LENOVO', 1000000, 2, 1, '-2', '2024-06-03 12:47:09', NULL, '2024-06-11 03:48:07'),
(35, 'Mouse Rexus', 120000, 1, 3, '12', '2024-06-03 12:47:42', NULL, '2024-06-03 07:49:53'),
(36, 'Mouse Logitech', 200000, 1, 1, '20', '2024-06-03 12:49:08', '2024-06-03 07:49:16', NULL),
(37, 'Mouse Fantech', 250000, 1, 2, '-11', '2024-06-04 14:42:20', NULL, '2024-06-11 03:48:11'),
(38, 'Keyboard Ajazz', 400000, 3, 2, '55', '2024-06-04 14:48:17', NULL, '2024-06-10 15:47:49'),
(39, 'Keyboard Ajazz', 400000, 3, 2, '55', '2024-06-04 15:12:13', NULL, '2024-06-04 10:21:46'),
(40, 'Monitor LG', 1500000, 2, 2, '8', '2024-06-05 19:47:25', NULL, NULL),
(41, 'Rozez', 450000, 1, 1, '12', '2024-06-05 19:48:32', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `name`, `created_at`) VALUES
(1, 'Showi', '2024-06-03 04:35:14'),
(2, 'Dafit', '2024-06-03 04:35:14'),
(3, 'Danny', '2024-06-03 05:16:50'),
(4, 'Faren', '2024-06-03 05:16:50');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `buyer_name` varchar(255) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `buyer_name`, `product_id`, `quantity`, `created_at`, `total_amount`) VALUES
(7, 'Sandi', 36, 2, '2024-06-11 01:32:42', 0.00),
(15, 'Panpan', 40, 3, '2024-06-11 01:45:52', 0.00),
(16, 'Ahm', 40, 1, '2024-06-11 01:48:56', 0.00),
(18, 'Dafit', 41, 2, '2024-06-11 04:19:33', 0.00),
(19, 'Rapi', 40, 2, '2024-06-11 06:51:55', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `full_name`, `password`, `role`, `created_at`) VALUES
(1, 'ahmadshowi15@gmail.com', 'ahmad showi', '$2y$10$n6HJ3a3swF.pD0ceGp995.b1sqI1tGsI9hU6gm.p1iVXEyiOiVWz.', 'admin', '2024-06-10 10:18:02'),
(2, 'ahmadshowi123@gmail.com', 'ahmad showi', '$2y$10$Vl6RPEi8vcJuQRxww7lEVOXDRUHMKc2PnyZ97mWi8e0r12zX3SKQq', 'admin', '2024-06-10 15:25:32'),
(3, 'dafitmtq@gmail.com', 'dafit muttaqin', '$2y$10$SRackx/fEnKegN39BwcLCeY5xfQ7vQxqDdvdiMQ/QZFWgR0.LyVQC', 'admin', '2024-06-11 06:18:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category` (`id_category`),
  ADD KEY `fk_supplier` (`id_supplier`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
