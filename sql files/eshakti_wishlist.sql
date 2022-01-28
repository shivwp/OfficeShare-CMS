-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 21, 2021 at 05:57 AM
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
-- Table structure for table `eshakti_wishlist`
--

CREATE TABLE `eshakti_wishlist` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eshakti_wishlist`
--

INSERT INTO `eshakti_wishlist` (`id`, `product_id`, `user_id`, `created_at`, `updated_at`) VALUES
(43, 16, 19, '2021-03-19 11:32:35', '2021-03-24 11:51:11'),
(61, 11, 19, '2021-03-24 12:19:38', '2021-03-24 12:19:38'),
(62, 21, 35, '2021-03-24 12:20:02', '2021-04-29 20:32:26'),
(67, 73, 35, '2021-04-21 14:33:58', '2021-05-03 12:14:02'),
(68, 15, 35, '2021-04-21 15:38:25', '2021-04-21 15:38:25'),
(69, 22, 35, '2021-04-21 15:38:43', '2021-04-21 15:38:43'),
(70, 89, 35, '2021-04-21 15:39:00', '2021-04-21 15:39:00'),
(74, 13, 77, '2021-04-21 15:49:29', '2021-04-23 14:29:21'),
(75, 91, 35, '2021-04-21 15:52:26', '2021-05-03 12:14:21'),
(76, 82, 35, '2021-04-21 15:57:25', '2021-05-03 12:14:31'),
(78, 81, 75, '2021-04-22 02:19:47', '2021-04-22 02:19:47'),
(79, 103, 35, '2021-04-23 22:48:03', '2021-05-03 12:14:26'),
(80, 100, 77, '2021-04-29 10:55:22', '2021-04-29 10:55:22'),
(81, 20, 7, '2021-04-29 23:53:49', '2021-04-29 23:53:49'),
(83, 96, 7, '2021-04-29 23:54:59', '2021-04-29 23:54:59'),
(84, 78, 35, '2021-05-03 12:14:08', '2021-05-03 12:14:08'),
(85, 88, 35, '2021-05-03 12:14:10', '2021-05-03 12:14:10'),
(86, 86, 35, '2021-05-03 12:14:12', '2021-05-03 12:14:12'),
(87, 85, 35, '2021-05-03 12:14:13', '2021-05-03 12:14:13'),
(88, 83, 35, '2021-05-03 12:14:15', '2021-05-03 12:14:15'),
(89, 92, 35, '2021-05-03 12:14:22', '2021-05-03 12:14:22'),
(90, 93, 35, '2021-05-03 12:14:24', '2021-05-03 12:14:24'),
(91, 87, 35, '2021-05-03 12:14:27', '2021-05-03 12:14:27'),
(92, 84, 35, '2021-05-03 12:14:29', '2021-05-03 12:14:29'),
(93, 106, 89, '2021-05-06 14:38:22', '2021-05-06 14:38:22'),
(94, 104, 89, '2021-05-06 14:38:35', '2021-05-06 14:38:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eshakti_wishlist`
--
ALTER TABLE `eshakti_wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlist_product_id_foreign` (`product_id`),
  ADD KEY `wishlist_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eshakti_wishlist`
--
ALTER TABLE `eshakti_wishlist`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `eshakti_wishlist`
--
ALTER TABLE `eshakti_wishlist`
  ADD CONSTRAINT `wishlist_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `eshakti_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wishlist_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `eshakti_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
