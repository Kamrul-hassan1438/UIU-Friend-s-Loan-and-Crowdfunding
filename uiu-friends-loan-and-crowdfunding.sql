-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2024 at 12:07 AM
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
-- Database: `uiu-friends-loan-and-crowdfunding`
--

-- --------------------------------------------------------

--
-- Table structure for table `contributions`
--

CREATE TABLE `contributions` (
  `contribution_id` int(11) NOT NULL,
  `crowdfunding_id` int(11) DEFAULT NULL,
  `contributor_id` int(11) DEFAULT NULL,
  `contribution_amount` decimal(10,2) DEFAULT NULL,
  `contribution_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crowdfundings`
--

CREATE TABLE `crowdfundings` (
  `crowdfunding_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `target_amount` decimal(10,2) DEFAULT NULL,
  `collected_amount` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deadline` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crowdfundings`
--

INSERT INTO `crowdfundings` (`crowdfunding_id`, `user_id`, `title`, `description`, `target_amount`, `collected_amount`, `created_at`, `deadline`, `image`) VALUES
(2, 8, 'Help Nigga', 'To help the niggas around you', 2000.00, 0.00, '2024-10-05 06:10:22', '2024-10-31', NULL),
(3, 8, 'Help Niggas (02)', 'tttttttttttt', 2000.00, 0.00, '2024-10-05 06:29:46', '2024-10-31', 'uploads/Screenshot 2024-09-26 105248.png'),
(4, 8, 'Lets Helps the Cats', 'The poem uses a short rhythmic dialogue to describe how cats get or choose their names. It states that \"a cat must have THREE DIFFERENT NAMES\"; specifically, one that is \"familiar\", one that is \"particular\", and one that is \"secretive\".[2] English professor Dorothy Dodge Robbins noted that the many examples of feline names given in the poem by the Missouri-born poet were heavily influenced by his love and adoption of British culture: \"After all, his are the monikers of distinctly London cats; they are not the practical names of Midwestern barn cats.', 5000.00, 0.00, '2024-10-05 06:37:17', '2024-10-31', 'uploads/loans.png'),
(5, 6, 'Lets Helps the Cats -3', 'Kamrul Is a Nigga', 50000.00, 0.00, '2024-10-05 18:18:51', '2024-10-30', '');

-- --------------------------------------------------------

--
-- Table structure for table `loanoffers`
--

CREATE TABLE `loanoffers` (
  `offer_id` int(11) NOT NULL,
  `loan_id` int(11) DEFAULT NULL,
  `lender_id` int(11) DEFAULT NULL,
  `amount_offered` decimal(10,2) DEFAULT NULL,
  `interest_rate` decimal(5,2) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `installments` int(11) DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','accepted','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loanoffers`
--

INSERT INTO `loanoffers` (`offer_id`, `loan_id`, `lender_id`, `amount_offered`, `interest_rate`, `due_date`, `installments`, `additional_info`, `created_at`, `status`) VALUES
(10, 15, 8, 0.00, 5.00, '2024-11-28', 3, 'Please give me the money', '2024-10-07 21:40:11', 'pending'),
(11, 13, 8, 0.00, 3.00, '2024-10-31', 1, 'q', '2024-10-07 21:43:34', 'pending'),
(12, 13, 8, 0.00, 10.00, '2024-10-31', 2, '', '2024-10-07 21:57:14', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `loan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expected_return_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`loan_id`, `user_id`, `amount`, `expected_return_date`, `description`, `document`, `status`, `created_at`) VALUES
(13, 6, 1200.00, '2024-10-31', 'Hello', 'uploads/Sad Cat.png', 'pending', '2024-10-07 21:03:21'),
(15, 6, 400.00, '2024-10-30', 'Hello Give me the money', 'uploads/Sad Cat.png', 'pending', '2024-10-07 21:38:32'),
(16, 6, 7000.00, '2024-11-30', 'I am poor :(', 'uploads/Sad Cat.png', 'pending', '2024-10-07 21:39:14');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `notification_type` enum('crowdfunding','loan','repayment') NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `repayments`
--

CREATE TABLE `repayments` (
  `repayment_id` int(11) NOT NULL,
  `loan_id` int(11) DEFAULT NULL,
  `lender_id` int(11) DEFAULT NULL,
  `repayment_amount` decimal(10,2) DEFAULT NULL,
  `repayment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `installment_number` int(11) DEFAULT NULL,
  `loanoffer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `uiu_id` varchar(50) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `uiu_id`, `profile_image`, `created_at`, `phone`) VALUES
(4, 'Molla', 'mollamdsabit317@gmail.com', '$2y$10$nqb6jPoJHP2IAefp6RqU8Ow9YGrXum26G5SZBGDEMqjfpKITqMDgi', '1234', 'uploads/WhatsApp Image 2024-08-12 at 2.28.41 AM.jpeg', '2024-10-03 19:48:46', '01795859483'),
(6, 'Sabit', 'alisabit12t@gmail.com', '$2y$10$WDi/tPsJxNKacC02EL9En.DFjpmJn88pDve7dJqDhgXrQ.h9olJdW', '0112121671', 'uploads/Screenshot 2024-09-23 113344.png', '2024-10-04 14:44:58', '0111212121'),
(7, 'maazzz', 'maaz@gmail.com', '$2y$10$wXIoNj2abIkwS5PncYjCru0AEAGDFwL/yN2vV1/5Gkew4gRrTlWru', '56789', 'uploads/Screenshot 2024-09-23 112738.png', '2024-10-04 17:57:15', '2344567'),
(8, 'kamrul', 'kamrul@gmail.com', '$2y$10$epbVtnPjT1IGizIB8NLneOqCzYP0RMRF.Ib3SRojaRda6RQgd6aVa', '011212153', 'uploads/Screenshot 2024-09-26 102534.png', '2024-10-04 18:05:56', '01833797597');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contributions`
--
ALTER TABLE `contributions`
  ADD PRIMARY KEY (`contribution_id`),
  ADD KEY `crowdfunding_id` (`crowdfunding_id`),
  ADD KEY `contributor_id` (`contributor_id`);

--
-- Indexes for table `crowdfundings`
--
ALTER TABLE `crowdfundings`
  ADD PRIMARY KEY (`crowdfunding_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `loanoffers`
--
ALTER TABLE `loanoffers`
  ADD PRIMARY KEY (`offer_id`),
  ADD KEY `loan_id` (`loan_id`),
  ADD KEY `lender_id` (`lender_id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `repayments`
--
ALTER TABLE `repayments`
  ADD PRIMARY KEY (`repayment_id`),
  ADD KEY `loan_id` (`loan_id`),
  ADD KEY `lender_id` (`lender_id`),
  ADD KEY `fk_loanoffer` (`loanoffer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `uiu_id` (`uiu_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contributions`
--
ALTER TABLE `contributions`
  MODIFY `contribution_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crowdfundings`
--
ALTER TABLE `crowdfundings`
  MODIFY `crowdfunding_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `loanoffers`
--
ALTER TABLE `loanoffers`
  MODIFY `offer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `repayments`
--
ALTER TABLE `repayments`
  MODIFY `repayment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contributions`
--
ALTER TABLE `contributions`
  ADD CONSTRAINT `contributions_ibfk_1` FOREIGN KEY (`crowdfunding_id`) REFERENCES `crowdfundings` (`crowdfunding_id`),
  ADD CONSTRAINT `contributions_ibfk_2` FOREIGN KEY (`contributor_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `crowdfundings`
--
ALTER TABLE `crowdfundings`
  ADD CONSTRAINT `crowdfundings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `loanoffers`
--
ALTER TABLE `loanoffers`
  ADD CONSTRAINT `loanoffers_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`loan_id`),
  ADD CONSTRAINT `loanoffers_ibfk_2` FOREIGN KEY (`lender_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `repayments`
--
ALTER TABLE `repayments`
  ADD CONSTRAINT `fk_loanoffer` FOREIGN KEY (`loanoffer_id`) REFERENCES `loanoffers` (`offer_id`),
  ADD CONSTRAINT `repayments_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`loan_id`),
  ADD CONSTRAINT `repayments_ibfk_2` FOREIGN KEY (`lender_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
