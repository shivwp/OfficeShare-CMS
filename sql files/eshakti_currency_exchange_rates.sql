-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 21, 2021 at 05:51 AM
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
-- Table structure for table `eshakti_currency_exchange_rates`
--

CREATE TABLE `eshakti_currency_exchange_rates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `source_id` bigint(20) UNSIGNED NOT NULL,
  `target_id` bigint(20) UNSIGNED NOT NULL,
  `source_rate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_rate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eshakti_currency_exchange_rates`
--

INSERT INTO `eshakti_currency_exchange_rates` (`id`, `source_id`, `target_id`, `source_rate`, `target_rate`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 3, 1, '1', '74', 0, NULL, '2021-03-02 06:24:11', '2021-03-02 06:24:11'),
(4, 3, 8, '1', '3.67', 0, NULL, '2021-03-05 06:37:54', '2021-03-05 06:37:54'),
(5, 3, 3, '1', '1', 0, NULL, '2021-03-05 07:20:09', '2021-03-05 07:20:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eshakti_currency_exchange_rates`
--
ALTER TABLE `eshakti_currency_exchange_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `currency_exchange_rates_source_id_foreign` (`source_id`),
  ADD KEY `currency_exchange_rates_target_id_foreign` (`target_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eshakti_currency_exchange_rates`
--
ALTER TABLE `eshakti_currency_exchange_rates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `eshakti_currency_exchange_rates`
--
ALTER TABLE `eshakti_currency_exchange_rates`
  ADD CONSTRAINT `currency_exchange_rates_source_id_foreign` FOREIGN KEY (`source_id`) REFERENCES `eshakti_currency_exchanges` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `currency_exchange_rates_target_id_foreign` FOREIGN KEY (`target_id`) REFERENCES `eshakti_currency_exchanges` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
