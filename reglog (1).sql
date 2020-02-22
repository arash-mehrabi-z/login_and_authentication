-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 22, 2020 at 06:15 AM
-- Server version: 5.7.29-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reglog`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_id` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `account_enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `username`, `password`, `reg_time`, `account_enabled`) VALUES
(1, 'arash_mehrabi_z', '$2y$10$X2JmIHDws0MvfVM.qkweuu4JYC.B3nx1h712YxCNJIUjdRdfgGRZS', '2020-02-21 10:46:59', 1),
(3, 'arash_mehrabi_z2', '$2y$10$1JiY4jiqjy7/d2DUhEmCgutd8WwkJ.LWZJ3znfEz8.bEJ7Ppc9JRy', '2020-02-21 11:44:33', 1),
(4, 'arash_mehrabi_z3', '$2y$10$th2NNt7edEG5.gCKBy7geucYFKWvPZUkOSfhab.S/yToS9ro.HLmm', '2020-02-21 11:48:12', 1),
(5, 'arash_mehrabi_z4', '$2y$10$RKjlZpSLyJRIVouxK2XGuOy7Pn.oFpHvJi1FmjkGVLphF0iK1rCkm', '2020-02-21 12:49:44', 1),
(6, 'arash_mehrabi_z5', '$2y$10$0BJG.tznG1/0Me/smLVahOs4qGL4YYYsFxmkGneQ8Nt7TDTb5cJa2', '2020-02-21 13:17:28', 1),
(7, 'arash_mehrabi_z8', '$2y$10$EKVg4T.sLVpzVm0L9POfau.W1sF2BLX4LceegXHzxdf2e8m6YdHOi', '2020-02-21 13:18:27', 1),
(8, 'arash_mehrabi', '$2y$10$tM/er95UehtfDM6Zp3E4.eUPVsbusXaHgT.Cc1L0hKF/1rxpctN/2', '2020-02-21 21:29:57', 1),
(9, 'arashmehrabi', '$2y$10$QzjCA9wZ16rew33qVX2uaeMdvAfQKV5uu5c48s/qRYcbSSf5Ttdj2', '2020-02-22 05:25:19', 1),
(11, 'arashmehrabi3', '$2y$10$M2cl/4nZL6qR.Xw2LF2Q3ODsRe.MgoADD5.7NNw9jmVpsLr73mLG2', '2020-02-22 05:25:46', 1),
(12, '123456789', '$2y$10$LtlSOXLZt1PW2bJp8kPcweqdldnqWO6ahxtPfoR9r5Qy44tQ9hJXO', '2020-02-22 06:08:26', 1);

-- --------------------------------------------------------

--
-- Table structure for table `account_sessions`
--

CREATE TABLE `account_sessions` (
  `session_id` varchar(255) NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `account_sessions`
--

INSERT INTO `account_sessions` (`session_id`, `account_id`, `login_time`) VALUES
('1204cha1ffuiakcd6khp1ra5ur', 12, '2020-02-22 06:13:38'),
('8c0fmj7c0rg3jpj6r1do04j2k8', 7, '2020-02-22 06:12:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `account_sessions`
--
ALTER TABLE `account_sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
