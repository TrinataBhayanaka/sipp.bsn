-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 27, 2015 at 04:29 PM
-- Server version: 5.5.40-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.13

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
-- Table structure for table `bns_struktur`
--

CREATE TABLE IF NOT EXISTS `bsn_struktur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) DEFAULT NULL,
  `nama_satker` varchar(300) DEFAULT NULL,
  `singkatan` varchar(100) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `n_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `bns_struktur`
--

INSERT INTO `bsn_struktur` (`id`, `kode`, `nama_satker`, `singkatan`, `create_date`, `n_status`) VALUES
(1, '840000', 'Badan Standardisasi Nasional', 'BSN', NULL, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
