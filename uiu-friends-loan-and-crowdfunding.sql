-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2024 at 03:46 AM
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
(5, 1, 5.00, '2024-10-23', 'd', 'uploads/Sad Cat.png', 'approved', '2024-10-09 01:34:36'),
(6, 3, 700.00, '2024-10-23', 'ui', 'uploads/Sad Cat.png', 'approved', '2024-10-09 01:41:41');

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
(1, 'Maazz ', 'maazz@gmail.com', '$2y$10$/WfeRMnW2/1ZKLniymVSbekgBoSTyOLvgRibft8bagTrz3aFHEu3y', '12345', 'uploads/Crying cat.png', '2024-10-08 22:11:05', '01795859483'),
(2, 'Sabit', 'sabit@gmail.com', '$2y$10$h5i1HpfTIKFitqQs2cR.G.PjkB.m3cBuePUwAvrsnAN7gQIEu7..a', '1', 'uploads/Crying cat.png', '2024-10-08 22:19:14', '01833797597'),
(3, 'kamrul', 'kamrul@gmail.com', '$2y$10$yyVnxtcUwnxG2VGxaRPk0.FSBxed16g7v3JRLTbDA1.97.QGdLMcu', '12', 'uploads/Crying cat.png', '2024-10-08 22:20:12', '01833797597'),
(4, 'Sabrina', 'sabrina@gmail.com', '$2y$10$BNlEX2afbjHNyZLP1Dia8eI0.n.0egZ1Bw.Y2cCBmliwji69tp9Oy', '011212132', 'uploads/Sad Cat.png', '2024-10-08 23:58:21', '0176543218768');

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
  MODIFY `contribution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `crowdfundings`
--
ALTER TABLE `crowdfundings`
  MODIFY `crowdfunding_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `loanoffers`
--
ALTER TABLE `loanoffers`
  MODIFY `offer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
