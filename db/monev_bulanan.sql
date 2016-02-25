-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2016 at 03:37 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

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
-- Table structure for table `monev_bulanan`
--

CREATE TABLE IF NOT EXISTS `monev_bulanan` (
`id` int(11) NOT NULL,
  `th` varchar(4) DEFAULT NULL,
  `kdunitkerja` varchar(6) DEFAULT NULL,
  `kdgiat` varchar(4) DEFAULT NULL,
  `kdoutput` varchar(3) DEFAULT NULL,
  `kdkmpnen` varchar(3) DEFAULT NULL,
  `keterangan` text,
  `kendala` text,
  `kendala_2` text NOT NULL,
  `kendala_3` text NOT NULL,
  `kendala_4` text NOT NULL,
  `kendala_5` text NOT NULL,
  `kendala_6` text NOT NULL,
  `kendala_7` text NOT NULL,
  `kendala_8` text NOT NULL,
  `kendala_9` text NOT NULL,
  `kendala_10` text NOT NULL,
  `kendala_11` text NOT NULL,
  `kendala_12` text NOT NULL,
  `tindaklanjut` text,
  `tindaklanjut_2` text NOT NULL,
  `tindaklanjut_3` text NOT NULL,
  `tindaklanjut_4` text NOT NULL,
  `tindaklanjut_5` text NOT NULL,
  `tindaklanjut_6` text NOT NULL,
  `tindaklanjut_7` text NOT NULL,
  `tindaklanjut_8` text NOT NULL,
  `tindaklanjut_9` text NOT NULL,
  `tindaklanjut_10` text NOT NULL,
  `tindaklanjut_11` text NOT NULL,
  `tindaklanjut_12` text NOT NULL,
  `ygmembantu` text,
  `ygmembantu_2` text NOT NULL,
  `ygmembantu_3` text NOT NULL,
  `ygmembantu_4` text NOT NULL,
  `ygmembantu_5` text NOT NULL,
  `ygmembantu_6` text NOT NULL,
  `ygmembantu_7` text NOT NULL,
  `ygmembantu_8` text NOT NULL,
  `ygmembantu_9` text NOT NULL,
  `ygmembantu_10` text NOT NULL,
  `ygmembantu_11` text NOT NULL,
  `ygmembantu_12` text NOT NULL,
  `kategori` varchar(5) DEFAULT NULL,
  `target_1` decimal(3,2) DEFAULT '0.00',
  `target_2` decimal(3,2) DEFAULT '0.00',
  `target_3` decimal(3,2) DEFAULT '0.00',
  `target_4` decimal(3,2) DEFAULT '0.00',
  `target_5` decimal(3,2) DEFAULT '0.00',
  `target_6` decimal(3,2) DEFAULT '0.00',
  `target_7` decimal(3,2) DEFAULT '0.00',
  `target_8` decimal(3,2) DEFAULT '0.00',
  `target_9` decimal(3,2) DEFAULT '0.00',
  `target_10` decimal(3,2) DEFAULT '0.00',
  `target_11` decimal(3,2) DEFAULT '0.00',
  `target_12` decimal(3,2) DEFAULT '0.00',
  `anggaran_1` decimal(50,4) DEFAULT '0.0000',
  `anggaran_2` decimal(50,4) DEFAULT '0.0000',
  `anggaran_3` decimal(50,4) DEFAULT '0.0000',
  `anggaran_4` decimal(50,4) DEFAULT '0.0000',
  `anggaran_5` decimal(50,4) DEFAULT '0.0000',
  `anggaran_6` decimal(50,4) DEFAULT '0.0000',
  `anggaran_7` decimal(50,4) DEFAULT '0.0000',
  `anggaran_8` decimal(50,4) DEFAULT '0.0000',
  `anggaran_9` decimal(50,4) DEFAULT '0.0000',
  `anggaran_10` decimal(50,4) DEFAULT '0.0000',
  `anggaran_11` decimal(50,4) DEFAULT '0.0000',
  `anggaran_12` decimal(50,4) DEFAULT '0.0000'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `monev_bulanan`
--

INSERT INTO `monev_bulanan` (`id`, `th`, `kdunitkerja`, `kdgiat`, `kdoutput`, `kdkmpnen`, `keterangan`, `kendala`, `kendala_2`, `kendala_3`, `kendala_4`, `kendala_5`, `kendala_6`, `kendala_7`, `kendala_8`, `kendala_9`, `kendala_10`, `kendala_11`, `kendala_12`, `tindaklanjut`, `tindaklanjut_2`, `tindaklanjut_3`, `tindaklanjut_4`, `tindaklanjut_5`, `tindaklanjut_6`, `tindaklanjut_7`, `tindaklanjut_8`, `tindaklanjut_9`, `tindaklanjut_10`, `tindaklanjut_11`, `tindaklanjut_12`, `ygmembantu`, `ygmembantu_2`, `ygmembantu_3`, `ygmembantu_4`, `ygmembantu_5`, `ygmembantu_6`, `ygmembantu_7`, `ygmembantu_8`, `ygmembantu_9`, `ygmembantu_10`, `ygmembantu_11`, `ygmembantu_12`, `kategori`, `target_1`, `target_2`, `target_3`, `target_4`, `target_5`, `target_6`, `target_7`, `target_8`, `target_9`, `target_10`, `target_11`, `target_12`, `anggaran_1`, `anggaran_2`, `anggaran_3`, `anggaran_4`, `anggaran_5`, `anggaran_6`, `anggaran_7`, `anggaran_8`, `anggaran_9`, `anggaran_10`, `anggaran_11`, `anggaran_12`) VALUES
(1, '2015', '841100', '3550', '001', '011', 'Pihak yang dapat mengatasi masalah,Pihak yang dapat mengatasi masalah update', 'Kendala yang dihadapi jan update ', ' Kendala yang dihadapi feb update', '', '', '', '', '', '', '', '', '', '', 'Tindak Lanjut yang diperlukan jan update ', 'Tindak Lanjut yang diperlukan jan update\nTindak Lanjut yang diperlukan feb update', '', '', '', '', '', '', '', '', '', '', 'Pihak yang dapat mengatasi masalah jan update ', 'Pihak yang dapat mengatasi masalah jan update \nPihak yang dapat mengatasi masalah feb update ', '', '', '', '', '', '', '', '', '', '', '1', '0.50', '2.00', '3.45', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(2, '2015', '841100', '3550', '001', '011', NULL, 'Kendala yang dihadapi anggran edit', 'Kendala yang dihadapi anggran feb', '', '', '', '', '', '', '', '', '', '', ' Tindak Lanjut yang diperlukan anggaran edit', ' Tindak Lanjut yang diperlukan anggaran feb', '', '', '', '', '', '', '', '', '', '', 'Pihak yang dapat mengatasi masalah anggaran edit', 'Pihak yang dapat mengatasi masalah anggaran feb', '', '', '', '', '', '', '', '', '', '', '2', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '1500000.0000', '3000000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(3, '2015', '841100', '3550', '001', '011', 'capian Kinerja\nIntensitas hujan tinggi berpeluang terjadi di sebagian Sumbar, Jambi, Sumsel, Bengkulu, Lampung, Jawa, Bali, NTB, NTT, Sulsel, Sulbar, Sulteng, Sultra, Papua. ', 'Kendala-kendala\nIntensitas hujan tinggi berpeluang terjadi di sebagian Sumbar, Jambi, Sumsel, Bengkulu, Lampung, Jawa, Bali, NTB, NTT, Sulsel, Sulbar, Sulteng, Sultra, Papua. ', '', '', '', '', '', '', '', '', '', '', '', 'Rencana Tindak Lanjut\nIntensitas hujan tinggi berpeluang terjadi di sebagian Sumbar, Jambi, Sumsel, Bengkulu, Lampung, Jawa, Bali, NTB, NTT, Sulsel, Sulbar, Sulteng, Sultra, Papua. ', '', '', '', '', '', '', '', '', '', '', '', 'Pihak yang dapat membantu penyelesaian kendala/permasalahan\nIntensitas hujan tinggi berpeluang terjadi di sebagian Sumbar, Jambi, Sumsel, Bengkulu, Lampung, Jawa, Bali, NTB, NTT, Sulsel, Sulbar, Sulteng, Sultra, Papua. ', '', '', '', '', '', '', '', '', '', '', '', '3', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(4, '2015', '841100', '3550', '002', '012', 'Capaian Kinerja 3550.02', 'Kendala yang dihadapi 3550.02 edit', '', '', '', '', '', '', '', '', '', '', '', 'Tindak Lanjut yang diperlukan 3550.02 edit', '', '', '', '', '', '', '', '', '', '', '', ' Pihak yang dapat mengatasi masalah 3550.02 edit', '', '', '', '', '', '', '', '', '', '', '', '3', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(5, '2015', '841100', '3550', '002', '012', 'bobot ', 'Kendala yang dihadapi bobot 3550.002', '', '', '', '', '', '', '', '', '', '', '', 'Tindak Lanjut yang diperlukan 3550.002', '', '', '', '', '', '', '', '', '', '', '', 'Pihak yang dapat mengatasi masalah 3550.002', '', '', '', '', '', '', '', '', '', '', '', '1', '0.25', '0.75', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(7, '2015', '841100', '3550', '002', '012', NULL, 'Kendala yang dihadapi anggaran 3550.002', '', '', '', '', '', '', '', '', '', '', '', 'Tindak Lanjut yang diperlukan 3550.002\n', '', '', '', '', '', '', '', '', '', '', '', 'Pihak yang dapat mengatasi masalah 3550.002', '', '', '', '', '', '', '', '', '', '', '', '2', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100000.0000', '200000.0000', '150000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(8, '2015', '841100', '3550', '001', '012', 'coba aja edit', '012 kendala yang dihadapi jan update', '012 Kendala yang dihadapi feb\n', '', '', '', '', '', '', '', '', '', '', '012 Tindak Lanjut yang diperlukan jan update', '012 indak Lanjut yang diperlukan feb', '', '', '', '', '', '', '', '', '', '', '012 Pihak yang dapat mengatasi masalah jan update', '012 Pihak yang dapat mengatasi masalah feb', '', '', '', '', '', '', '', '', '', '', '1', '0.60', '0.40', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(9, '2015', '841100', '3550', '001', '012', NULL, NULL, '012  Kendala yang dihadapi feb', '', '', '', '', '', '', '', '', '', '', NULL, '012  Tindak Lanjut yang diperlukan feb', '', '', '', '', '', '', '', '', '', '', NULL, '012  Pihak yang dapat mengatasi masalah feb', '', '', '', '', '', '', '', '', '', '', '2', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '100000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `monev_bulanan`
--
ALTER TABLE `monev_bulanan`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `monev_bulanan`
--
ALTER TABLE `monev_bulanan`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
