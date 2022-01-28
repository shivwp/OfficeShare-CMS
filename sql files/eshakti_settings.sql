-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 21, 2021 at 05:55 AM
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
-- Table structure for table `eshakti_settings`
--

CREATE TABLE `eshakti_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_name` text COLLATE utf8mb4_unicode_ci,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `helpline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_country` int(11) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hour_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_hour_from` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_hour_to` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_day_from` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_day_to` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gstin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_url` text COLLATE utf8mb4_unicode_ci,
  `mailtype` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eshakti_settings`
--

INSERT INTO `eshakti_settings` (`id`, `business_name`, `country`, `state`, `city`, `address`, `zip`, `helpline`, `phone_country`, `email`, `hour_type`, `office_hour_from`, `office_hour_to`, `office_day_from`, `office_day_to`, `pan`, `cin`, `gstin`, `logo`, `site_url`, `mailtype`, `created_at`, `updated_at`) VALUES
(1, 'eBOLLY', '231', 'TN', 'Memphis', '10 Fed Ex Pkwy', '38115', '123456789', 3, 'ewt.ebolly@gmail.com', 'Weekly Opened', '09:30', '18:30', 'Monday', 'Saturday', '1234567890', '3242343GHFG', '54654GFH78', 'logo/DjXuWjlwhb3JU5qchSkNcBDFIeys93MylyKjVlRk.png', 'https://dev.e-bolly.com/', 'sendmail', '2020-12-07 07:36:58', '2021-04-23 17:21:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eshakti_settings`
--
ALTER TABLE `eshakti_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eshakti_settings`
--
ALTER TABLE `eshakti_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
