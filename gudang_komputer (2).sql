-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2024 at 10:44 AM
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
(34, 'Monitor LENOVO', 1000000, 2, 1, '7', '2024-06-03 12:47:09', NULL, NULL),
(35, 'Mouse Rexus', 120000, 1, 3, '12', '2024-06-03 12:47:42', NULL, '2024-06-03 07:49:53'),
(36, 'Mouse Logitech', 200000, 1, 1, '7', '2024-06-03 12:49:08', '2024-06-03 07:49:16', NULL),
(37, 'Mouse Fantech', 250000, 1, 2, '12', '2024-06-04 14:42:20', NULL, NULL),
(38, 'Keyboard Ajazz', 400000, 3, 2, '55', '2024-06-04 14:48:17', NULL, NULL),
(39, 'Keyboard Ajazz', 400000, 3, 2, '55', '2024-06-04 15:12:13', NULL, '2024-06-04 10:21:46');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
