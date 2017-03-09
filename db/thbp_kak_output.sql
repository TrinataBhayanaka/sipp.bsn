-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2015 at 08:23 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_sipp2`
--

-- --------------------------------------------------------

--
-- Table structure for table `thbp_kak_output`
--

CREATE TABLE IF NOT EXISTS `thbp_kak_output` (
`id` int(11) NOT NULL,
  `th` varchar(4) DEFAULT NULL,
  `kdunitkerja` varchar(6) DEFAULT NULL,
  `kdgiat` varchar(4) DEFAULT NULL,
  `kdoutput` varchar(3) DEFAULT NULL,
  `tujuan` varchar(200) DEFAULT NULL,
  `sasaran_1` int(5) DEFAULT NULL,
  `sasaran_2` int(5) DEFAULT NULL,
  `sasaran_3` int(5) DEFAULT NULL,
  `sasaran_4` int(5) DEFAULT NULL,
  `ursasaran_1` varchar(200) DEFAULT NULL,
  `ursasaran_2` varchar(200) DEFAULT NULL,
  `ursasaran_3` varchar(200) DEFAULT NULL,
  `ursasaran_4` varchar(200) DEFAULT NULL,
  `status` varchar(1) NOT NULL DEFAULT '0',
  `tgl_kirim` date DEFAULT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `thbp_kak_output`
--

INSERT INTO `thbp_kak_output` (`id`, `th`, `kdunitkerja`, `kdgiat`, `kdoutput`, `tujuan`, `sasaran_1`, `sasaran_2`, `sasaran_3`, `sasaran_4`, `ursasaran_1`, `ursasaran_2`, `ursasaran_3`, `ursasaran_4`, `status`, `tgl_kirim`) VALUES
(2, '2015', '841100', '3550', '001', 'Tersedianya dokumen perencanaan sebagai acuan dalam pelaksanaan kegiatan BSN ', 48, 30, 7, 15, 'Tersusunnya LAKIP BSN, Penetapan Kinerja, dan dokumen draft RENJA BSN ', ' Tersusunnya dokumen RENJA BSN dan draft dokumen RKAKL berdasarkan Pagu Anggaran', ' Tersusunnya draft dokumen RKAKL berdasarkan Pagu Definitif', 'Tersusunnya dokumen RKAKL BSN, Usulan DIPA dan Laporan Monev', '0', '0000-00-00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `thbp_kak_output`
--
ALTER TABLE `thbp_kak_output`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `thbp_kak_output`
--
ALTER TABLE `thbp_kak_output`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
