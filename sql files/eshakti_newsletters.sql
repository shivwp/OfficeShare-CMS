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
-- Table structure for table `eshakti_newsletters`
--

CREATE TABLE `eshakti_newsletters` (
  `id` int(11) NOT NULL,
  `api` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `audience_id` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `eshakti_newsletters`
--

INSERT INTO `eshakti_newsletters` (`id`, `api`, `audience_id`, `status`, `created_at`, `updated_at`) VALUES
(2, '86a22b37791bc509ac5d68dad86e5b0b-us7', '44eb7de811', 1, '2021-04-05 10:50:23', '2021-04-05 10:50:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eshakti_newsletters`
--
ALTER TABLE `eshakti_newsletters`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eshakti_newsletters`
--
ALTER TABLE `eshakti_newsletters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
