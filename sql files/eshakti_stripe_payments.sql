-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 21, 2021 at 06:35 AM
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
-- Table structure for table `eshakti_stripe_payments`
--

CREATE TABLE `eshakti_stripe_payments` (
  `id` int(11) NOT NULL,
  `payment_gateway` enum('stripe','paypal','COD') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publishing_key` text COLLATE utf8mb4_unicode_ci,
  `secret_key` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eshakti_stripe_payments`
--

INSERT INTO `eshakti_stripe_payments` (`id`, `payment_gateway`, `publishing_key`, `secret_key`, `status`, `created_at`, `updated_at`) VALUES
(1, 'stripe', 'pk_test_51IVUw2GZXq85JtYzRMYpVEPMlJj32IRys5co67jmCIOUnhavVqGzuPjPTYsw5CFYhlcdPAKcWZW6cQjr4fC9GSPA00dgCogPjO', 'sk_test_51IVUw2GZXq85JtYzP8R34x2dSLqS1E95T3HHhMp6znKbX5Wki35dBFp5p2gkSLwouXQ3JpJ1i42r02rFtPuCZ6FI008W39Z4Rk', 1, '2021-03-16 09:31:43', '2021-04-24 10:53:14'),
(3, 'paypal', 'ATW8piDglcoYb18fQu7pF-RG9GulJGEmirPJz-v3M1F5KcISd3QWjP5avJ_FwARZc6rFswGc8B_qhtIZ', 'EN8BAJe9M2ydP4gy8WD5on1locnBNG4McMpAFhLOYtrAjkDkO95RKnoAFY6IkSwxJYXj-6pbWDJLjFCy', 1, '2021-04-05 10:21:21', '2021-04-28 06:09:00'),
(5, 'COD', NULL, NULL, 1, '2021-04-24 06:52:08', '2021-04-24 06:52:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eshakti_stripe_payments`
--
ALTER TABLE `eshakti_stripe_payments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eshakti_stripe_payments`
--
ALTER TABLE `eshakti_stripe_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
