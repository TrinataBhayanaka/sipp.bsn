-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 15, 2016 at 12:51 PM
-- Server version: 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_sipp_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `thbp_kak_output_bobot`
--

CREATE TABLE IF NOT EXISTS `thbp_kak_output_bobot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `th` varchar(4) NOT NULL,
  `kdunitkerja` varchar(6) NOT NULL,
  `kdgiat` varchar(4) NOT NULL,
  `kdoutput` varchar(3) NOT NULL,
  `kdsoutput` varchar(3) NOT NULL,
  `kdkmpnen` varchar(3) NOT NULL,
  `bobot` decimal(5,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `thbp_kak_output_bobot`
--

INSERT INTO `thbp_kak_output_bobot` (`id`, `th`, `kdunitkerja`, `kdgiat`, `kdoutput`, `kdsoutput`, `kdkmpnen`, `bobot`) VALUES
(1, '2015', '841100', '3550', '001', '001', '011', 20.00);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
