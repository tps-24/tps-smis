-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2025 at 03:53 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.0.10

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
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2025-01-03 03:48:44', '2025-01-03 03:48:44'),
(2, 'Academic Coordinator', 'web', '2025-01-05 10:58:10', '2025-01-05 10:58:10'),
(3, 'Super Administrator', 'web', '2025-01-10 01:56:28', '2025-01-10 01:56:28'),
(5, 'Student', 'web', '2025-01-16 05:35:43', '2025-01-17 01:46:52'),
(6, 'Teacher', 'web', '2025-01-16 12:00:53', '2025-01-16 12:00:53'),
(7, 'Chief Instructor', 'web', '2025-01-16 12:07:22', '2025-01-16 12:07:22'),
(8, 'Registrar', 'web', '2025-01-17 01:59:59', '2025-01-17 01:59:59'),
(9, 'Verifier', 'web', '2025-01-17 02:01:03', '2025-01-17 02:01:03'),
(10, 'Staff Officer', 'web', '2025-01-17 02:03:43', '2025-01-17 02:03:43'),
(11, 'Doctor', 'web', '2025-01-17 02:04:56', '2025-01-17 02:04:56'),
(12, 'Sir Major', 'web', '2025-01-28 11:24:37', '2025-01-28 11:24:37'),
(13, 'Receptionist', 'web', '2025-01-28 11:25:16', '2025-01-28 11:25:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
