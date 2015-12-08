-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 04, 2015 at 11:11 AM
-- Server version: 5.5.46-0ubuntu0.14.04.2
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bsn_sipp`
--

-- --------------------------------------------------------

--
-- Table structure for table `bsn_capaian`
--

CREATE TABLE IF NOT EXISTS `bsn_capaian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sasaran` varchar(50) NOT NULL,
  `indikator` varchar(50) NOT NULL,
  `target` varchar(50) NOT NULL,
  `formula` varchar(50) NOT NULL,
  `twn1` text NOT NULL,
  `twn2` text NOT NULL,
  `twn3` text NOT NULL,
  `twn4` text NOT NULL,
  `permasalahan` text NOT NULL,
  `perbaikan` text NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `change_date` date NOT NULL,
  `n_status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `bsn_capaian`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
