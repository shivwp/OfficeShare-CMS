-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 21, 2021 at 05:53 AM
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
-- Table structure for table `eshakti_feedbacks`
--

CREATE TABLE `eshakti_feedbacks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` float NOT NULL,
  `permit_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eshakti_feedbacks`
--

INSERT INTO `eshakti_feedbacks` (`id`, `user_id`, `product_id`, `title`, `comment`, `rate`, `permit_status`, `created_at`, `updated_at`) VALUES
(1, 23, 11, 'asdas', 'dasdasdasd', 4.5, 1, '2021-03-10 09:33:54', '2021-03-10 09:33:54'),
(2, 23, 11, 'product is very good', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum', 3, 1, '2021-03-10 10:37:41', '2021-03-10 10:37:41'),
(3, 23, 11, 'descent product', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 3, 1, '2021-03-10 10:54:39', '2021-03-10 10:54:39'),
(4, 23, 11, 'worst product', 'worst product ever', 1.5, 1, '2021-03-10 10:59:52', '2021-03-10 10:59:52'),
(5, 23, 13, 'nice dress', 'very nice dress', 4, 1, '2021-03-10 11:04:43', '2021-03-10 11:04:43'),
(6, 23, 13, 'value for money', 'value for money ...you can buy', 4, 1, '2021-03-10 11:06:07', '2021-03-10 11:06:07'),
(7, 23, 13, 'nice dress for party', 'nice dress for party', 4, 1, '2021-03-10 11:14:18', '2021-03-10 11:14:18'),
(8, 23, 20, 'dress is good', 'dress is very good', 3.5, 1, '2021-03-10 11:58:58', '2021-03-10 11:58:58'),
(9, 23, 20, 'ddfd', 'dsfsdfsadfsadfasdfsdf', 3.5, 1, '2021-03-16 08:35:38', '2021-03-16 08:35:38'),
(10, 19, 103, 'test', 'test', 2.5, 0, '2021-04-20 18:19:41', '2021-04-20 18:19:41'),
(11, 19, 82, 'adsfs', 'sdfsfdf', 4, 1, '2021-04-20 18:33:00', '2021-04-20 18:33:00'),
(12, 19, 82, 'testing123', 'testig data', 4, 1, '2021-04-20 18:34:05', '2021-04-20 18:34:05'),
(13, 19, 82, 'testing', 'testing', 1, 1, '2021-04-20 18:34:35', '2021-04-20 18:34:35'),
(14, 19, 96, 'testing', 'testing', 3, 0, '2021-04-20 18:37:54', '2021-04-20 18:37:54'),
(15, 19, 96, 'testing', 'testing', 4, 1, '2021-04-20 18:38:22', '2021-04-20 18:38:22'),
(16, 19, 103, 'test', 'testing', 3, 1, '2021-04-20 19:36:52', '2021-04-20 19:36:52'),
(17, 19, 103, 'testing', 'testing', 5, 1, '2021-04-20 19:37:21', '2021-04-20 19:37:21'),
(18, 19, 103, 'test', 'test', 1, 1, '2021-04-20 19:37:41', '2021-04-20 19:37:41'),
(19, 19, 103, 'test', 'test', 1, 1, '2021-04-20 19:38:16', '2021-04-20 19:38:16'),
(20, 35, 13, 'Test', 'Sugg....', 1, 1, '2021-04-21 15:49:48', '2021-04-21 15:49:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eshakti_feedbacks`
--
ALTER TABLE `eshakti_feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedbacks_user_id_foreign` (`user_id`),
  ADD KEY `eshakti_feedbacks_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eshakti_feedbacks`
--
ALTER TABLE `eshakti_feedbacks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `eshakti_feedbacks`
--
ALTER TABLE `eshakti_feedbacks`
  ADD CONSTRAINT `eshakti_feedbacks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `eshakti_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `feedbacks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `eshakti_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
