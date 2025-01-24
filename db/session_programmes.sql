-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2025 at 01:45 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tps_rms`
--

-- --------------------------------------------------------

--
-- Table structure for table `session_programmes`
--

CREATE TABLE `session_programmes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `programme_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `is_current` tinyint(4) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `session_programmes`
--

INSERT INTO `session_programmes` (`id`, `programme_name`, `description`, `year`, `startDate`, `endDate`, `is_current`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'BASIC RECRUIT COURSE NO.1/2024/2025', 'Depo la maelekezo kutoka kwa mama', '2024/25', '2024-10-07', '2025-04-25', 1, 1, '2025-01-11 07:48:06', '2025-01-21 02:25:26'),
(2, 'CORPORAL COURSE NO.1/2024/2025', 'Depo la mananga wakiwa kilele pori', '2024/25', NULL, NULL, 1, 0, '2025-01-11 07:49:04', '2025-01-21 02:25:26'),
(3, 'SERGEANT COURSE NO.1/2024/2025', 'Depo la waungwana kamba pori', '2024/25', NULL, NULL, 1, 0, '2025-01-11 07:50:01', '2025-01-21 02:25:26'),
(4, 'POLICE SCIENCE (PST) 2024/2025', 'Basic Technician Certificate in Policing and Security Management', '2024/25', '2024-07-01', '2025-03-28', 1, 0, '2025-01-21 17:27:38', '2025-01-21 17:27:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `session_programmes`
--
ALTER TABLE `session_programmes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `session_programmes`
--
ALTER TABLE `session_programmes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
