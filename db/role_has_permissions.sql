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
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 3),
(2, 3),
(3, 3),
(4, 3),
(5, 2),
(5, 3),
(6, 2),
(6, 3),
(7, 2),
(7, 3),
(8, 2),
(8, 3),
(9, 1),
(9, 3),
(10, 3),
(11, 3),
(12, 3),
(13, 2),
(13, 3),
(13, 5),
(13, 8),
(13, 9),
(13, 12),
(13, 13),
(14, 2),
(14, 3),
(14, 8),
(14, 9),
(15, 2),
(15, 3),
(15, 8),
(15, 9),
(16, 3),
(16, 9),
(17, 3),
(17, 10),
(18, 3),
(19, 3),
(20, 3),
(21, 2),
(21, 3),
(21, 5),
(21, 10),
(22, 3),
(23, 3),
(23, 5),
(24, 3),
(25, 3),
(25, 5),
(25, 10),
(26, 3),
(27, 3),
(28, 3),
(29, 2),
(29, 3),
(29, 6),
(30, 2),
(30, 3),
(31, 2),
(31, 3),
(32, 3),
(33, 1),
(33, 2),
(33, 3),
(34, 2),
(34, 3),
(35, 3),
(36, 3),
(37, 2),
(37, 3),
(37, 5),
(37, 6),
(38, 2),
(38, 3),
(39, 3),
(40, 2),
(40, 3),
(41, 2),
(41, 3),
(41, 5),
(41, 6),
(42, 2),
(42, 3),
(43, 2),
(43, 3),
(44, 3),
(45, 2),
(45, 3),
(46, 2),
(46, 3),
(47, 2),
(47, 3),
(48, 3),
(49, 2),
(49, 3),
(50, 1),
(50, 3),
(50, 5),
(50, 10),
(51, 2),
(51, 3),
(51, 6),
(52, 3),
(53, 3),
(54, 3),
(54, 5),
(54, 10),
(55, 3),
(56, 3),
(57, 3),
(58, 2),
(58, 3),
(58, 5),
(58, 11),
(58, 12),
(58, 13),
(59, 3),
(59, 12),
(60, 3),
(60, 11),
(61, 3),
(62, 3),
(63, 3),
(64, 3),
(65, 3),
(66, 3),
(66, 5),
(66, 10),
(67, 3),
(67, 10),
(68, 3),
(69, 3),
(70, 3),
(70, 5),
(71, 3),
(72, 3),
(73, 3),
(74, 2),
(74, 3),
(75, 2),
(75, 3),
(76, 2),
(76, 3),
(77, 3),
(78, 2),
(78, 3),
(79, 2),
(79, 3),
(80, 3),
(81, 3),
(82, 2),
(82, 3),
(82, 6),
(82, 9),
(82, 10),
(82, 11),
(83, 2),
(83, 3),
(84, 3),
(85, 3),
(86, 2),
(86, 3),
(87, 2),
(87, 3),
(88, 2),
(88, 3),
(89, 3),
(91, 3),
(92, 3),
(93, 3),
(94, 3),
(95, 3),
(96, 3),
(97, 3),
(98, 3),
(99, 3),
(100, 3),
(101, 3),
(102, 3),
(103, 3),
(104, 3),
(105, 3),
(106, 3),
(107, 3),
(108, 3),
(109, 3),
(110, 3),
(111, 3),
(112, 13);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
