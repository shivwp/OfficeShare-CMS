-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 21, 2021 at 05:50 AM
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
-- Table structure for table `eshakti_currency_exchanges`
--

CREATE TABLE `eshakti_currency_exchanges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sign` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_name` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eshakti_currency_exchanges`
--

INSERT INTO `eshakti_currency_exchanges` (`id`, `name`, `code`, `sign`, `country_name`, `country_code`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Indian Rupee', 'INR', 'Rs', 'India', 'IN', 0, '2021-03-01 13:51:45', '2021-03-05 08:57:40'),
(3, 'Dollar', 'USD', '$', 'United State', 'US', 1, '2021-03-02 06:05:10', '2021-03-05 08:58:53'),
(8, 'UAE Dirham', 'GB', '£', 'united kingdom', 'GB', 0, '2021-03-04 04:53:23', '2021-03-08 06:21:57'),
(9, 'INR', 'INR', 'INR', 'India', 'IN', 0, '2021-04-06 07:13:48', '2021-04-06 07:13:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eshakti_currency_exchanges`
--
ALTER TABLE `eshakti_currency_exchanges`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eshakti_currency_exchanges`
--
ALTER TABLE `eshakti_currency_exchanges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
