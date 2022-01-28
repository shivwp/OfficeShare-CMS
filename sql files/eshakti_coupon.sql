-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 21, 2021 at 05:46 AM
-- Server version: 5.6.49-cll-lve
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ebolly`
--

-- --------------------------------------------------------

--
-- Table structure for table `eshakti_coupon`
--

CREATE TABLE `eshakti_coupon` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET latin1 NOT NULL,
  `description` longtext CHARACTER SET latin1,
  `discount_type` text CHARACTER SET latin1,
  `coupon_amount` int(11) DEFAULT NULL,
  `allow_free_shipping` tinyint(4) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `minimum_spend` int(11) DEFAULT NULL,
  `maximum_spend` int(11) DEFAULT NULL,
  `is_indivisual` tinyint(4) DEFAULT NULL,
  `exclude_sale_item` tinyint(4) DEFAULT NULL,
  `limit_per_coupon` int(11) DEFAULT NULL,
  `limit_per_user` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `eshakti_coupon`
--

INSERT INTO `eshakti_coupon` (`id`, `code`, `description`, `discount_type`, `coupon_amount`, `allow_free_shipping`, `start_date`, `expiry_date`, `minimum_spend`, `maximum_spend`, `is_indivisual`, `exclude_sale_item`, `limit_per_coupon`, `limit_per_user`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(14, 'eWu0VuID', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to', 'fp', NULL, 1, '2021-05-06 00:00:00', '2021-05-12 00:00:00', 123, 456, 0, 0, 1, 1, 1, '2021-05-05 06:15:57', '2021-05-08 12:10:00', NULL),
(15, 'qHVrRDQk', NULL, 'fp', 12, 0, '2021-05-10 00:00:00', '2021-05-22 12:11:45', 23, 500, 0, 0, NULL, 1, 1, '2021-05-10 13:31:45', '2021-05-14 05:47:18', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eshakti_coupon`
--
ALTER TABLE `eshakti_coupon`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eshakti_coupon`
--
ALTER TABLE `eshakti_coupon`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
