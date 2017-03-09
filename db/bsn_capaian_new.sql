-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 16, 2016 at 11:05 AM
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
  `categoryType` int(1) NOT NULL,
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
  `iduser` int(10) NOT NULL,
  `kodeUser` int(10) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `change_date` date NOT NULL,
  `n_status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `bsn_capaian`
--

INSERT INTO `bsn_capaian` (`id`, `categoryType`, `sasaran`, `indikator`, `target`, `formula`, `twn1`, `twn2`, `twn3`, `twn4`, `permasalahan`, `perbaikan`, `iduser`, `kodeUser`, `create_date`, `change_date`, `n_status`) VALUES
(1, 3, '48', '146', 'mampu', 'ewwe', '{"trgtO":"wqwe","trgtP":"sad","ntrgt":"1","realO":"sd","realP":"asd","nreal":"asd"}', '{"trgtO":"asd","trgtP":"asd","ntrgt":"asd","realO":"asd","realP":"asd","nreal":"asd"}', '{"trgtO":"asd","trgtP":"asd","ntrgt":"asd","realO":"ad","realP":"asd","nreal":"sad"}', '{"trgtO":"asd","trgtP":"asd","ntrgt":"asd","realO":"sad","realP":"sad","nreal":"sad"}', 'sad', 'asd', 7, 843100, '2016-03-08 18:07:32', '2016-03-08', 1),
(2, 3, '48', '146', 'mampu', 'dsfds', '{"trgtO":"sd","trgtP":"ds","ntrgt":"3","realO":"sad","realP":"sd","nreal":"sd"}', '{"trgtO":"ds","trgtP":"sd","ntrgt":"sdf","realO":"sd","realP":"sd","nreal":"sd"}', '{"trgtO":"ds","trgtP":"ds","ntrgt":"sd","realO":"sd","realP":"sd","nreal":"sd"}', '{"trgtO":"sdd","trgtP":"sd","ntrgt":"ds","realO":"sd","realP":"sd","nreal":"sd"}', 'sad', 'sd', 7, 840000, '2016-03-16 09:55:55', '2016-03-16', 1),
(3, 3, '48', '146', 'mampu', 'dsss', '{"trgtO":"9","trgtP":"9","ntrgt":"9","realO":"9","realP":"9","nreal":"9"}', '{"trgtO":"9","trgtP":"9","ntrgt":"9","realO":"9","realP":"9","nreal":"99"}', '{"trgtO":"9","trgtP":"9","ntrgt":"9","realO":"9","realP":"9","nreal":""}', '{"trgtO":"99","trgtP":"9","ntrgt":"9","realO":"9","realP":"9","nreal":"9"}', '9', '99', 7, 841100, '2016-03-16 09:57:19', '2016-03-16', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
