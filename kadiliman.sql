-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2025 at 10:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kadiliman`
--

-- --------------------------------------------------------

--
-- Table structure for table `balance_transactions`
--

CREATE TABLE `balance_transactions` (
  `transaction_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(6) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `transaction_type` enum('purchase','conversion','usage','refund') NOT NULL,
  `standard_change` decimal(10,2) DEFAULT 0.00,
  `premium_change` decimal(10,2) DEFAULT 0.00,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `balance_transactions`
--

INSERT INTO `balance_transactions` (`transaction_id`, `user_id`, `username`, `transaction_type`, `standard_change`, `premium_change`, `transaction_date`, `description`) VALUES
(3, 37, 'ryoshi', 'purchase', 6.00, 4.00, '2025-04-05 11:57:20', 'Welcome package purchase'),
(4, 37, 'ryoshi', 'conversion', -3.00, 2.00, '2025-04-05 11:57:20', 'Converted standard to premium time'),
(6, 37, 'ryoshi', 'conversion', -3.00, 2.00, '2025-04-05 12:17:41', 'Converted 3 standard hours to 2 premium hours'),
(7, 37, 'ryoshi', 'conversion', -3.00, 2.00, '2025-04-05 12:21:36', 'Converted 3 standard hours to 2 premium hours'),
(10, 51, 'test', 'purchase', 0.00, 0.00, '2025-04-06 09:55:51', 'Initial standard time balance'),
(11, 51, 'test', 'purchase', 0.00, 0.00, '2025-04-06 09:55:51', 'Initial premium time balance'),
(12, 37, 'ryoshi', 'conversion', 3.00, -2.00, '2025-04-07 07:27:20', 'Converted 2h 0m premium hours to 3h 0m standard hours'),
(13, 37, 'ryoshi', 'conversion', 3.00, -2.00, '2025-04-07 07:28:14', 'Converted 2h 0m premium hours to 3h 0m standard hours'),
(14, 37, 'ryoshi', 'conversion', 0.00, 0.00, '2025-04-07 07:29:21', 'Converted 0h 0m standard hours to 0h 0m premium hours'),
(15, 37, 'ryoshi', 'conversion', 6.00, -4.00, '2025-04-07 07:30:17', 'Converted 4h 0m premium hours to 6h 0m standard hours'),
(16, 37, 'ryoshi', 'conversion', -6.00, 4.00, '2025-04-07 07:30:29', 'Converted 6h 0m standard hours to 4h 0m premium hours'),
(17, 37, 'ryoshi', 'conversion', -6.00, 4.00, '2025-04-07 07:31:31', 'Converted 6h 0m standard hours to 4h 0m premium hours'),
(18, 37, 'ryoshi', 'conversion', 3.00, -2.00, '2025-04-07 07:46:04', 'Converted 2h 0m premium hours to 3h 0m standard hours'),
(19, 37, 'ryoshi', 'conversion', 3.00, -2.00, '2025-04-07 07:50:29', 'Converted 2h 0m premium hours to 3h 0m standard hours'),
(20, 37, 'ryoshi', 'conversion', -6.00, 4.00, '2025-04-07 07:50:45', 'Converted 6h 0m standard hours to 4h 0m premium hours'),
(21, 37, 'ryoshi', 'conversion', 0.00, 0.00, '2025-04-07 08:02:53', 'Converted 0h 0m standard hours to 0h 0m premium hours'),
(22, 37, 'ryoshi', 'conversion', -3.00, 2.00, '2025-04-07 08:03:04', 'Converted 3h 0m standard hours to 2h 0m premium hours'),
(23, 37, 'ryoshi', 'conversion', 3.00, -2.00, '2025-04-07 08:04:28', 'Converted 2h 0m premium hours to 3h 0m standard hours');

-- --------------------------------------------------------

--
-- Table structure for table `pc_sessions`
--

CREATE TABLE `pc_sessions` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(6) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `pc_type` enum('standard','premium') NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_time` timestamp NULL DEFAULT NULL,
  `minutes_used` int(5) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(6) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `surname` varchar(30) NOT NULL,
  `branch` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `firstname`, `surname`, `branch`, `password`, `reg_date`) VALUES
(37, 'ryoshi', 'adrianojonathan.official@gmail.com', 'JONATHAN', 'ADRIANO', 'Makati', '$2y$10$i0QyQkqoUk7ix7Ye6knb7uP4XYlnWNRnXR9csJvIOW9uEmacgnuDq', '2025-04-04 14:05:40'),
(51, 'test', 'test@gmail.com', 'jo', 'ad', 'Makati', '$2y$10$RszGSzwuyjdqZhdLt6FOx.5Ol6eQbF6QdHfNmwpTcGnCWE7b4JXWi', '2025-04-06 09:55:36');

-- --------------------------------------------------------

--
-- Table structure for table `user_balance`
--

CREATE TABLE `user_balance` (
  `id` int(6) UNSIGNED NOT NULL,
  `user_id` int(6) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `standard_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `premium_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `conversions_used` int(1) NOT NULL DEFAULT 0,
  `conversion_reset_time` timestamp NULL DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_balance`
--

INSERT INTO `user_balance` (`id`, `user_id`, `username`, `standard_balance`, `premium_balance`, `conversions_used`, `conversion_reset_time`, `last_updated`) VALUES
(2, 37, 'ryoshi', 4.00, 4.50, 3, '2025-04-08 00:41:36', '2025-04-07 08:04:28'),
(5, 51, 'test', 0.00, 0.00, 0, '2025-04-07 03:55:51', '2025-04-06 10:59:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `balance_transactions`
--
ALTER TABLE `balance_transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pc_sessions`
--
ALTER TABLE `pc_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_balance`
--
ALTER TABLE `user_balance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `balance_transactions`
--
ALTER TABLE `balance_transactions`
  MODIFY `transaction_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `pc_sessions`
--
ALTER TABLE `pc_sessions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `user_balance`
--
ALTER TABLE `user_balance`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `balance_transactions`
--
ALTER TABLE `balance_transactions`
  ADD CONSTRAINT `balance_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pc_sessions`
--
ALTER TABLE `pc_sessions`
  ADD CONSTRAINT `pc_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_balance`
--
ALTER TABLE `user_balance`
  ADD CONSTRAINT `user_balance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
