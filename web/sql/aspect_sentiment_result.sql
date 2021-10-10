-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2019 at 07:32 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `datamining`
--

-- --------------------------------------------------------

--
-- Table structure for table `aspect_sentiment_result`
--

CREATE TABLE `aspect_sentiment_result` (
  `ID` int(5) DEFAULT NULL,
  `Uploadkey` varchar(11) DEFAULT NULL,
  `Groupname` varchar(20) DEFAULT NULL,
  `Precision` double(5,2) DEFAULT NULL,
  `Recall` double(5,2) DEFAULT NULL,
  `F1Score` double(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `aspect_sentiment_result`
--

INSERT INTO `aspect_sentiment_result` (`ID`, `Uploadkey`, `Groupname`, `Precision`, `Recall`, `F1Score`) VALUES
(1, '58521', 'Kelompok 1', 0.00, 0.00, 0.00),
(2, '95929', 'Kelompok 2', 0.00, 0.00, 0.00),
(3, '13105', 'Kelompok 3', 0.00, 0.00, 0.00),
(4, '45843', 'Kelompok 4', 0.00, 0.00, 0.00),
(5, '95659', 'Kelompok 5', 0.00, 0.00, 0.00),
(6, '74650', 'Kelompok 6', 0.00, 0.00, 0.00),
(7, '32632', 'Kelompok 7', 0.00, 0.00, 0.00),
(8, '13245', 'Kelompok 8', 0.00, 0.00, 0.00),
(9, '70261', 'Kelompok 9', 0.00, 0.00, 0.00),
(10, '74060', 'Kelompok 10', 0.00, 0.00, 0.00);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;