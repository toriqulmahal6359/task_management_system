-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 02, 2022 at 02:21 PM
-- Server version: 8.0.27
-- PHP Version: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `task_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `task_list`
--

CREATE TABLE `task_list` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(350) NOT NULL,
  `parent` int DEFAULT NULL,
  `status` enum('0','1') NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `task_list`
--

INSERT INTO `task_list` (`id`, `name`, `description`, `parent`, `status`, `date_created`, `end_date`) VALUES
(1, 'Wake up from bed', 'I always do that', 0, '1', '2022-02-27 12:19:38', NULL),
(2, 'Computer Programming', 'I spend much time into this', 0, '0', '2022-02-28 21:39:34', NULL),
(3, 'Toothbrush1', 'Always do that before bed', 0, '1', '2022-02-28 21:55:54', '2022-03-18 16:50:00'),
(4, 'Go to School', 'All the time I am not go to school', 0, '0', '2022-03-01 02:00:57', '2022-03-30 02:00:00'),
(6, 'PHP Learning', 'I have learn that almost 2 hours', 2, '0', '2022-03-01 14:34:05', '2022-03-17 14:34:00'),
(7, 'Machine Learning', 'I frequently Do that', 2, '0', '2022-03-01 16:36:03', '2022-03-18 16:35:00'),
(8, 'Going To Walk', 'Everyday I take fresh breath', 0, '1', '2022-03-01 16:37:07', '2022-03-10 16:36:00'),
(10, 'Eating Habbit', 'I eat Everyday', 0, '1', '2022-03-01 18:31:57', '2022-03-17 18:31:00'),
(12, 'expired task', 'This is expired task', 0, '1', '2022-03-01 20:12:53', '2022-02-23 20:12:00'),
(15, 'Algorithm Learning', 'I always do that in the class', 4, '0', '2022-03-02 17:56:48', '2022-03-24 17:56:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `task_list`
--
ALTER TABLE `task_list`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `task_list`
--
ALTER TABLE `task_list`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
