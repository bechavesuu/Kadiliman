-- Table for storing user balances and conversion restrictions
CREATE TABLE `user_balance` (
  `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(6) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `standard_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `premium_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `conversions_used` int(1) NOT NULL DEFAULT 0,
  `conversion_reset_time` timestamp NULL DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_balance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table for storing transaction history
CREATE TABLE `balance_transactions` (
  `transaction_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(6) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `transaction_type` enum('purchase','conversion','usage','refund') NOT NULL,
  `standard_change` decimal(10,2) DEFAULT 0.00,
  `premium_change` decimal(10,2) DEFAULT 0.00,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`transaction_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `balance_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add some initial data for testing
INSERT INTO `user_balance` (`user_id`, `username`, `standard_balance`, `premium_balance`, `conversions_used`, `conversion_reset_time`) 
VALUES 
(34, 'miguel', 5.00, 2.00, 0, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 DAY)),
(37, 'ryoshi', 3.00, 4.00, 1, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 DAY)),
(39, 'test', 10.00, 5.00, 0, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 DAY));

-- Add some example transactions
INSERT INTO `balance_transactions` (`user_id`, `username`, `transaction_type`, `standard_change`, `premium_change`, `description`) 
VALUES 
(34, 'miguel', 'purchase', 5.00, 0.00, 'Initial standard time purchase'),
(34, 'miguel', 'purchase', 0.00, 2.00, 'Initial premium time purchase'),
(37, 'ryoshi', 'purchase', 6.00, 4.00, 'Welcome package purchase'),
(37, 'ryoshi', 'conversion', -3.00, 2.00, 'Converted standard to premium time'),
(39, 'test', 'purchase', 10.00, 5.00, 'New user special offer');