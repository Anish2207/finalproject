-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3366
-- Generation Time: Aug 21, 2024 at 02:34 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attendance_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `check_in_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `check_out_time` timestamp NULL DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `check_in_time`, `check_out_time`, `date`) VALUES
(1, 1, '2024-08-20 05:54:43', '2024-08-20 13:02:48', '2024-08-20'),
(2, 1, '2024-08-20 05:54:43', '2024-08-20 13:02:48', '2024-08-20'),
(3, 1, '2024-08-20 05:54:43', '2024-08-20 13:02:48', '2024-08-20'),
(4, 1, '2024-08-20 05:56:24', '2024-08-20 13:02:48', '2024-08-20'),
(5, 2, '2024-08-20 07:34:14', '2024-08-20 12:14:34', '2024-08-20'),
(8, 2, '2024-08-20 07:34:14', '2024-08-20 12:14:34', '2024-08-20'),
(9, 4, '2024-08-20 06:27:17', '2024-08-20 06:27:17', '2024-08-20'),
(12, 4, '2024-08-20 06:29:48', '2024-08-20 07:27:17', '2024-08-20'),
(16, 4, '2024-08-20 06:27:17', '2024-08-20 06:27:17', '2024-08-20'),
(28, 2, '2024-08-20 07:34:14', '2024-08-20 12:14:34', '2024-08-20'),
(32, 2, '2024-08-20 07:45:58', '2024-08-20 12:14:34', '2024-08-20'),
(33, 2, '2024-08-20 08:25:17', '2024-08-20 12:14:34', '2024-08-20'),
(39, 6, '2024-08-20 08:38:55', '2024-08-20 08:39:21', '2024-08-20'),
(40, 6, '2024-08-20 08:39:11', '2024-08-20 08:39:21', '2024-08-20'),
(41, 1, '2024-08-20 08:39:36', '2024-08-20 13:02:48', '2024-08-20'),
(42, 1, '2024-08-20 09:07:57', '2024-08-20 13:02:48', '2024-08-20'),
(43, 1, '2024-08-20 09:14:22', '2024-08-20 13:02:48', '2024-08-20'),
(44, 1, '2024-08-20 09:18:45', '2024-08-20 13:02:48', '2024-08-20'),
(45, 1, '2024-08-20 09:28:24', '2024-08-20 13:02:48', '2024-08-20'),
(46, 1, '2024-08-20 09:54:45', '2024-08-20 13:02:48', '2024-08-20'),
(47, 1, '2024-08-20 10:09:47', '2024-08-20 13:02:48', '2024-08-20'),
(48, 2, '2024-08-20 10:10:01', '2024-08-20 12:14:34', '2024-08-20'),
(49, 2, '2024-08-20 10:10:18', '2024-08-20 12:14:34', '2024-08-20'),
(50, 1, '2024-08-20 10:42:13', '2024-08-20 13:02:48', '2024-08-20'),
(51, 1, '2024-08-20 11:08:31', '2024-08-20 13:02:48', '2024-08-20'),
(52, 1, '2024-08-20 11:11:41', '2024-08-20 13:02:48', '2024-08-20'),
(53, 1, '2024-08-20 12:02:54', '2024-08-20 13:02:48', '2024-08-20'),
(54, 1, '2024-08-20 12:05:10', '2024-08-20 13:02:48', '2024-08-20'),
(55, 1, '2024-08-20 12:05:25', '2024-08-20 13:02:48', '2024-08-20'),
(56, 1, '2024-08-20 12:06:02', '2024-08-20 13:02:48', '2024-08-20'),
(57, 1, '2024-08-20 12:06:05', '2024-08-20 13:02:48', '2024-08-20'),
(58, 2, '2024-08-20 12:07:29', '2024-08-20 12:14:34', '2024-08-20'),
(59, 2, '2024-08-20 12:07:51', '2024-08-20 12:14:34', '2024-08-20'),
(60, 2, '2024-08-20 12:07:52', '2024-08-20 12:14:34', '2024-08-20'),
(61, 2, '2024-08-20 12:08:14', '2024-08-20 12:14:34', '2024-08-20'),
(62, 2, '2024-08-20 12:09:03', '2024-08-20 12:14:34', '2024-08-20'),
(63, 2, '2024-08-20 12:09:20', '2024-08-20 12:14:34', '2024-08-20'),
(64, 2, '2024-08-20 12:09:52', '2024-08-20 12:14:34', '2024-08-20'),
(65, 2, '2024-08-20 12:14:37', NULL, '2024-08-20'),
(66, 1, '2024-08-20 12:19:58', '2024-08-20 13:02:48', '2024-08-20'),
(67, 1, '2024-08-20 12:30:06', '2024-08-20 13:02:48', '2024-08-20'),
(68, 1, '2024-08-20 13:00:18', '2024-08-20 13:02:48', '2024-08-20'),
(69, 1, '2024-08-20 13:00:55', '2024-08-20 13:02:48', '2024-08-20'),
(70, 1, '2024-08-20 13:02:12', '2024-08-20 13:02:48', '2024-08-20'),
(71, 1, '2024-08-20 13:02:12', '2024-08-20 13:02:48', '2024-08-20'),
(72, 1, '2024-08-20 13:02:47', '2024-08-20 13:02:48', '2024-08-20'),
(73, 1, '2024-08-21 04:45:28', '2024-08-21 12:27:57', '2024-08-21'),
(74, 1, '2024-08-21 04:45:36', '2024-08-21 12:27:57', '2024-08-21'),
(75, 1, '2024-08-21 04:45:51', '2024-08-21 12:27:57', '2024-08-21'),
(76, 1, '2024-08-21 04:50:10', '2024-08-21 12:27:57', '2024-08-21'),
(77, 1, '2024-08-21 04:55:27', '2024-08-21 12:27:57', '2024-08-21'),
(78, 2, '2024-08-21 04:57:06', '2024-08-21 07:07:12', '2024-08-21'),
(79, 2, '2024-08-21 04:57:11', '2024-08-21 07:07:12', '2024-08-21'),
(80, 1, '2024-08-21 05:52:03', '2024-08-21 12:27:57', '2024-08-21'),
(81, 1, '2024-08-21 06:50:13', '2024-08-21 12:27:57', '2024-08-21'),
(82, 1, '2024-08-21 06:58:12', '2024-08-21 12:27:57', '2024-08-21'),
(83, 2, '2024-08-21 07:07:14', NULL, '2024-08-21'),
(84, 1, '2024-08-21 08:54:44', '2024-08-21 12:27:57', '2024-08-21'),
(85, 1, '2024-08-21 09:41:22', '2024-08-21 12:27:57', '2024-08-21'),
(86, 1, '2024-08-21 09:42:13', '2024-08-21 12:27:57', '2024-08-21'),
(87, 1, '2024-08-21 09:42:38', '2024-08-21 12:27:57', '2024-08-21'),
(88, 1, '2024-08-21 09:53:52', '2024-08-21 12:27:57', '2024-08-21'),
(89, 1, '2024-08-21 11:10:45', '2024-08-21 12:27:57', '2024-08-21'),
(90, 1, '2024-08-21 11:15:09', '2024-08-21 12:27:57', '2024-08-21'),
(91, 1, '2024-08-21 11:36:15', '2024-08-21 12:27:57', '2024-08-21'),
(92, 1, '2024-08-21 12:08:31', '2024-08-21 12:27:57', '2024-08-21'),
(93, 1, '2024-08-21 12:09:13', '2024-08-21 12:27:57', '2024-08-21'),
(94, 1, '2024-08-21 12:27:58', NULL, '2024-08-21');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_log`
--

CREATE TABLE `attendance_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `first_check_in` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_check_out` timestamp NULL DEFAULT NULL,
  `total_hours` decimal(5,2) DEFAULT 0.00,
  `status` enum('Full Day','Half Day','Absent') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_log`
--

INSERT INTO `attendance_log` (`id`, `user_id`, `date`, `first_check_in`, `last_check_out`, `total_hours`, `status`) VALUES
(1, 1, '2024-08-20', '2024-08-20 04:30:00', '2024-08-20 14:02:00', 7.13, 'Full Day'),
(3, 2, '2024-08-20', '2024-08-20 07:34:14', '2024-08-20 12:14:34', 4.67, 'Half Day'),
(4, 4, '2024-08-20', '2024-08-20 06:27:17', '2024-08-20 06:27:17', 0.00, 'Absent'),
(54, 6, '2024-08-20', '2024-08-20 08:38:55', '2024-08-20 08:39:21', 0.00, 'Absent'),
(81, 1, '2024-08-19', '2024-08-19 04:34:00', '2024-08-19 14:00:00', 8.00, 'Full Day'),
(90, 1, '2024-08-21', '2024-08-21 04:45:28', '2024-08-21 12:27:57', 7.70, 'Half Day'),
(93, 2, '2024-08-21', '2024-08-21 04:57:06', '2024-08-21 07:07:12', 2.17, 'Absent');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`) VALUES
(1, 'Anish', 'anishpatil582@gmail.com', '$2y$10$klhO.kYDWcW9l9loAZpum.MzC378XCFlBP/.Jiat035nHqiLDMStS'),
(2, 'Srushti', 'srushh@gmail.c', '$2y$10$/0rwZDu56ROX3PBvsPXFveHbxZs2VVzojt6t5uXfNDjM1xuqbvzu.'),
(4, 'Anish patil', 'user@example.com', '$2y$10$ZyYhN4T0buvXoz6Y2kcvf.DZgham/mGXU6uarul0sUqSbeKcKrB.i'),
(6, 'test', 'a@b.c', '$2y$10$5qPIRtJdxVwd2Z1Y04HB0.meuYILw04C7hDuyMSUp4NR0PlVvz34q');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `attendance_log`
--
ALTER TABLE `attendance_log`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`date`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `attendance_log`
--
ALTER TABLE `attendance_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `attendance_log`
--
ALTER TABLE `attendance_log`
  ADD CONSTRAINT `attendance_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
