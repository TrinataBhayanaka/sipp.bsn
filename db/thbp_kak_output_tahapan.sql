-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2015 at 08:49 AM
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
-- Table structure for table `thbp_kak_output_tahapan`
--

CREATE TABLE IF NOT EXISTS `thbp_kak_output_tahapan` (
`id` int(11) NOT NULL,
  `th` varchar(4) DEFAULT NULL,
  `kdunitkerja` varchar(6) DEFAULT NULL,
  `kdgiat` varchar(4) DEFAULT NULL,
  `kdoutput` varchar(3) DEFAULT NULL,
  `kdsoutput` varchar(3) DEFAULT NULL,
  `kdkmpnen` varchar(3) DEFAULT NULL,
  `kd_tahapan` varchar(5) DEFAULT NULL,
  `nm_tahapan` varchar(200) DEFAULT NULL,
  `target_1` tinyint(4) DEFAULT NULL,
  `target_2` tinyint(4) DEFAULT NULL,
  `target_3` tinyint(4) DEFAULT NULL,
  `target_4` tinyint(4) DEFAULT NULL,
  `target_5` tinyint(4) DEFAULT NULL,
  `target_6` tinyint(4) DEFAULT NULL,
  `target_7` tinyint(4) DEFAULT NULL,
  `target_8` tinyint(4) DEFAULT NULL,
  `target_9` tinyint(4) DEFAULT NULL,
  `target_10` tinyint(4) DEFAULT NULL,
  `target_11` tinyint(4) DEFAULT NULL,
  `target_12` tinyint(4) DEFAULT NULL,
  `anggaran_1` double DEFAULT NULL,
  `anggaran_2` double DEFAULT NULL,
  `anggaran_3` double DEFAULT NULL,
  `anggaran_4` double DEFAULT NULL,
  `anggaran_5` double DEFAULT NULL,
  `anggaran_6` double DEFAULT NULL,
  `anggaran_7` double DEFAULT NULL,
  `anggaran_8` double DEFAULT NULL,
  `anggaran_9` double DEFAULT NULL,
  `anggaran_10` double DEFAULT NULL,
  `anggaran_11` double DEFAULT NULL,
  `anggaran_12` double DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=153 ;

--
-- Dumping data for table `thbp_kak_output_tahapan`
--

INSERT INTO `thbp_kak_output_tahapan` (`id`, `th`, `kdunitkerja`, `kdgiat`, `kdoutput`, `kdsoutput`, `kdkmpnen`, `kd_tahapan`, `nm_tahapan`, `target_1`, `target_2`, `target_3`, `target_4`, `target_5`, `target_6`, `target_7`, `target_8`, `target_9`, `target_10`, `target_11`, `target_12`, `anggaran_1`, `anggaran_2`, `anggaran_3`, `anggaran_4`, `anggaran_5`, `anggaran_6`, `anggaran_7`, `anggaran_8`, `anggaran_9`, `anggaran_10`, `anggaran_11`, `anggaran_12`) VALUES
(95, '2015', '841100', '3550', '001', '001', '012', '1', 'Administrasi Pendukung kegiatan', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20080000, 0, 0, 0, 0, 0, 0, 0),
(96, '2015', '841100', '3550', '001', '001', '012', '2', 'Penyiapan bahan pembahasan program dan anggaran dengan instansi teknis terkait', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1920000, 0, 0, 1920000, 1920000, 0, 0),
(97, '2015', '841100', '3550', '001', '001', '012', '3', 'Koordinasi dan sinkronasi penyusunan RKA-KL (nota keuangan/pidato presiden/standar biaya/DIPA)', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14940000, 14940000, 1920000, 0, 0, 0, 0, 0),
(98, '2015', '841100', '3550', '001', '001', '012', '4', 'Sosialisasi aturan perencanaan dan penganggaran (Standar biaya, revisi dipa, RKAKL, BAS)', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 22920000, 0, 22920000, 0, 22920000, 0, 0, 0, 0, 0),
(99, '2015', '841100', '3550', '001', '001', '012', '5', 'Reviu kesesuaian TOR&RAB 2015 per ess.I (pagu anggaran dan pagu final)', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 30770000, 0, 0, 0, 0, 30770000, 0),
(100, '2015', '841100', '3550', '001', '001', '012', '6', 'Workshop penyusunan RKAKL berbasis aplikasi', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50350000, 0, 0, 0, 0, 50350000, 0),
(101, '2015', '841100', '3550', '001', '001', '012', '7', 'Pembahasan intensif penyusunan RKAKL', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 32900000, 0, 0, 0, 0, 0, 32900000),
(102, '2015', '841100', '3550', '001', '001', '011', '1', 'Administrasi Pendukung kegiatan', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 10000000, 0, 8441000, 0, 0, 0, 0, 0, 0, 0),
(103, '2015', '841100', '3550', '001', '001', '011', '2', 'Pembahasan Penyusunan Reviu Baseline', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12720000, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(104, '2015', '841100', '3550', '001', '001', '011', '3', 'Koordinasi dan SinkronasiProgram dan Anggaran (Renja/inisiatif Baru)', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14640000, 7000000, 7640000, 0, 0, 0, 0, 0, 0, 0, 0),
(105, '2015', '841100', '3550', '001', '001', '011', '4', 'Pembahasan penyusunan inisiatif baru', 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 39040000, 0, 0, 0, 0, 0, 0, 0, 0),
(106, '2015', '841100', '3550', '001', '001', '011', '5', 'Pembahasan intensif penyusunan renja berdasarkan Pagu Indikatif', 0, 0, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 16530000, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(107, '2015', '841100', '3550', '001', '001', '011', '6', 'Trilateral Meeting (Bappenas, Kemenkeu, BSN)', 0, 0, 0, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 16720000, 0, 0, 0, 0, 0, 0, 0, 0),
(108, '2015', '841100', '3550', '001', '001', '011', '7', 'Perbaikan hasil pembahasan Renja/Input Aplikasi Renja', 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1280000, 2560000, 0, 0, 0, 0, 0, 0, 0),
(109, '2015', '841100', '3550', '001', '001', '011', '8', 'Pra Raker BSN/evaluasi Raker', 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 131940000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(110, '2015', '841100', '3550', '001', '001', '011', '9', 'Raker BSN', 0, 0, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 362558000, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(129, '2015', '841100', '3550', '001', '001', '014', '1', 'Administrasi Pendukung kegiatan', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12008000, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(130, '2015', '841100', '3550', '001', '001', '014', '2', 'Koordinasi penyusunan penetaoan kinerja', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4720000, 0, 4720000, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(131, '2015', '841100', '3550', '001', '001', '014', '3', 'Pembahasan reviu Indikator Kinerja Utama (IKU)', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 13410000, 13410000, 0, 0, 0, 0, 0, 0, 0, 0),
(132, '2015', '841100', '3550', '001', '001', '014', '4', 'Koordinasi dalam rangka akuntabilitas', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 24920000, 24920000, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(133, '2015', '841100', '3550', '001', '001', '014', '5', 'Sosialisasi renstra 2015-2019/Aplikasi Monev', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 23630000, 23630000, 0, 0, 0, 0, 0, 0, 0),
(144, '2015', '841100', '3550', '001', '001', '015', '1', 'Administrasi Pendukung kegiatan', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9148000, 0, 0, 0, 0, 0),
(145, '2015', '841100', '3550', '001', '001', '015', '2', 'Pelaksanaan monev kegiatan', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 15140000, 15140000, 11052000, 10604000, 0, 10604000, 0),
(146, '2015', '841100', '3550', '001', '001', '015', '3', 'Sinkronisasi program dan kegiatan', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9120000, 0, 9120000, 0, 9120000, 0, 9120000, 0, 0),
(147, '2015', '841100', '3550', '001', '001', '015', '4', 'Koordinasi monev kegiatan (revisi anggaran 2015)', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5040000, 22715000, 0, 0, 2240000, 22915000, 0, 5040000, 22715000, 0, 5040000, 22715000),
(148, '2015', '841100', '3550', '001', '001', '015', '5', 'Penyusunan laporan PP 39 tahun 2006/PP 8 tahun 2006/RKAKL', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1920000, 0, 0, 1920000, 0, 0, 1920000, 0, 0),
(149, '2015', '841100', '3550', '001', '001', '015', '6', 'Koordinasi dan sinkronisasi aplikasi monev', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6680000, 6680000, 6680000, 0, 0, 0, 0, 0, 0, 0),
(150, '2015', '841100', '3550', '001', '001', '013', '1', 'Koordinasi penyusunan LAKIP', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9800000, 5120000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(151, '2015', '841100', '3550', '001', '001', '013', '2', 'Pembahasan intensif penyusunan LAKIP', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 25020000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(152, '2015', '841100', '3550', '001', '001', '013', '3', 'Evaluasi LAKIP', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 10020000, 0, 0, 0, 0, 0, 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `thbp_kak_output_tahapan`
--
ALTER TABLE `thbp_kak_output_tahapan`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `thbp_kak_output_tahapan`
--
ALTER TABLE `thbp_kak_output_tahapan`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=153;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
