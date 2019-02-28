-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2019 at 12:04 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `amsw`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(5) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `pass` varchar(20) NOT NULL,
  `pno` int(20) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `fname`, `lname`, `email`, `pass`, `pno`, `role`) VALUES
(1, 'admin', 's', 'admin@iot.in', '123456', 1234567890, 'auth'),
(13, 'Test', 'Test', 'test@iot.in', '123456', 2147483647, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `dumps`
--

CREATE TABLE `dumps` (
  `id` int(5) NOT NULL,
  `lat` float NOT NULL,
  `longi` float NOT NULL,
  `height` float NOT NULL,
  `capacity` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dumps`
--

INSERT INTO `dumps` (`id`, `lat`, `longi`, `height`, `capacity`) VALUES
(1, 12.8627, 77.4379, 56, 80),
(2, 19.1345, 72.9105, 55, 5);

-- --------------------------------------------------------

--
-- Table structure for table `gd1`
--

CREATE TABLE `gd1` (
  `id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `y1` float NOT NULL,
  `y2` float NOT NULL,
  `y3` float NOT NULL,
  `y4` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gd1`
--

INSERT INTO `gd1` (`id`, `time`, `y1`, `y2`, `y3`, `y4`) VALUES
(1, '2019-01-28 07:11:53', 20, 30, 40, 80),
(2, '2019-01-29 16:49:52', 89, 40, 95, 20),
(3, '2019-01-31 07:05:58', 50, 40, 95, 20),
(4, '2019-02-26 18:34:21', 80, 40, 95, 20);

-- --------------------------------------------------------

--
-- Table structure for table `gd2`
--

CREATE TABLE `gd2` (
  `id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `y1` float NOT NULL,
  `y2` float NOT NULL,
  `y3` float NOT NULL,
  `y4` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gd2`
--

INSERT INTO `gd2` (`id`, `time`, `y1`, `y2`, `y3`, `y4`) VALUES
(1, '2019-01-28 07:12:11', 80, 40, 30, 20),
(2, '2019-01-29 11:46:30', 80, 40, 30, 20),
(3, '2019-01-29 11:48:22', 80, 40, 30, 20);

-- --------------------------------------------------------

--
-- Table structure for table `sample`
--

CREATE TABLE `sample` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `y1` float NOT NULL,
  `y2` float NOT NULL,
  `y3` float NOT NULL,
  `y4` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(5) NOT NULL,
  `vol` int(1) NOT NULL,
  `history` int(1) NOT NULL,
  `pred` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `vol`, `history`, `pred`) VALUES
(1, 1, 0, 0),
(2, 1, 1, 0),
(3, 0, 1, 0),
(4, 0, 0, 0),
(5, 1, 1, 0),
(6, 1, 1, 1),
(7, 0, 0, 1),
(8, 1, 1, 1),
(9, 1, 1, 1),
(10, 1, 1, 1),
(11, 0, 0, 1),
(12, 1, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dumps`
--
ALTER TABLE `dumps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lat` (`lat`),
  ADD UNIQUE KEY `long` (`longi`);

--
-- Indexes for table `gd1`
--
ALTER TABLE `gd1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gd2`
--
ALTER TABLE `gd2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `gd1`
--
ALTER TABLE `gd1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gd2`
--
ALTER TABLE `gd2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
