-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2016 at 08:02 AM
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
  `tindaklanjut` text,
  `ygmembantu` text,
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `monev_bulanan`
--

INSERT INTO `monev_bulanan` (`id`, `th`, `kdunitkerja`, `kdgiat`, `kdoutput`, `kdkmpnen`, `keterangan`, `kendala`, `tindaklanjut`, `ygmembantu`, `kategori`, `target_1`, `target_2`, `target_3`, `target_4`, `target_5`, `target_6`, `target_7`, `target_8`, `target_9`, `target_10`, `target_11`, `target_12`, `anggaran_1`, `anggaran_2`, `anggaran_3`, `anggaran_4`, `anggaran_5`, `anggaran_6`, `anggaran_7`, `anggaran_8`, `anggaran_9`, `anggaran_10`, `anggaran_11`, `anggaran_12`) VALUES
(1, '2015', '841100', '3550', '001', '011', 'Pihak yang dapat mengatasi masalah,Pihak yang dapat mengatasi masalah update', 'Kendala yang dihadapi update', 'Tindak Lanjut yang diperlukan update', 'Pihak yang dapat mengatasi masalah update', '1', '0.00', '1.75', '3.45', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(2, '2015', '841100', '3550', '001', '011', NULL, 'Kendala yang dihadapi anggran edit', ' Tindak Lanjut yang diperlukan anggaran edit', 'Pihak yang dapat mengatasi masalah anggaran edit', '2', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '1500000.0000', '3000000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000');

-- --------------------------------------------------------

--
-- Table structure for table `thbp_kak_output`
--

CREATE TABLE IF NOT EXISTS `thbp_kak_output` (
`id` int(11) NOT NULL,
  `th` varchar(4) DEFAULT NULL,
  `kdunitkerja` varchar(10) DEFAULT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `thbp_kak_output`
--

INSERT INTO `thbp_kak_output` (`id`, `th`, `kdunitkerja`, `kdgiat`, `kdoutput`, `tujuan`, `sasaran_1`, `sasaran_2`, `sasaran_3`, `sasaran_4`, `ursasaran_1`, `ursasaran_2`, `ursasaran_3`, `ursasaran_4`, `status`, `tgl_kirim`) VALUES
(2, '2015', '841100', '3550', '001', 'Tersedianya dokumen perencanaan sebagai acuan dalam pelaksanaan kegiatan BSN edit', 11, 11, 33, 44, 'aaq', 'bb ', 'cc ', 'dd ', '1', '2015-12-11'),
(3, '2015', '841100', '3550', '002', 'coba edit', 1, 1, 3, 4, 'q', 'w', 'e', 'r', '1', '2015-12-11');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=155 ;

--
-- Dumping data for table `thbp_kak_output_tahapan`
--

INSERT INTO `thbp_kak_output_tahapan` (`id`, `th`, `kdunitkerja`, `kdgiat`, `kdoutput`, `kdsoutput`, `kdkmpnen`, `kd_tahapan`, `nm_tahapan`, `target_1`, `target_2`, `target_3`, `target_4`, `target_5`, `target_6`, `target_7`, `target_8`, `target_9`, `target_10`, `target_11`, `target_12`, `anggaran_1`, `anggaran_2`, `anggaran_3`, `anggaran_4`, `anggaran_5`, `anggaran_6`, `anggaran_7`, `anggaran_8`, `anggaran_9`, `anggaran_10`, `anggaran_11`, `anggaran_12`) VALUES
(95, '2015', '841100', '3550', '001', '001', '012', '1', 'Administrasi Pendukung kegiatan', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '0.0000', '20080000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(96, '2015', '841100', '3550', '001', '001', '012', '2', 'Penyiapan bahan pembahasan program dan anggaran dengan instansi teknis terkait', '0.50', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '1920000.0000', '0.0000', '0.0000', '1920000.0000', '1920000.0000', '0.0000', '0.0000'),
(97, '2015', '841100', '3550', '001', '001', '012', '3', 'Koordinasi dan sinkronasi penyusunan RKA-KL (nota keuangan/pidato presiden/standar biaya/DIPA)', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '0.0000', '14940000.0000', '14940000.0000', '1920000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(98, '2015', '841100', '3550', '001', '001', '012', '4', 'Sosialisasi aturan perencanaan dan penganggaran (Standar biaya, revisi dipa, RKAKL, BAS)', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '22920000.0000', '0.0000', '22920000.0000', '0.0000', '22920000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(99, '2015', '841100', '3550', '001', '001', '012', '5', 'Reviu kesesuaian TOR&RAB 2015 per ess.I (pagu anggaran dan pagu final)', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '30770000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '30770000.0000', '0.0000'),
(100, '2015', '841100', '3550', '001', '001', '012', '6', 'Workshop penyusunan RKAKL berbasis aplikasi', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '50350000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '50350000.0000', '0.0000'),
(101, '2015', '841100', '3550', '001', '001', '012', '7', 'Pembahasan intensif penyusunan RKAKL', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '32900000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '32900000.0000'),
(102, '2015', '841100', '3550', '001', '001', '011', '1', 'Administrasi Pendukung kegiatan', '0.50', '0.00', '0.75', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '10000000.0000', '16021000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(103, '2015', '841100', '3550', '001', '001', '011', '2', 'Pembahasan Penyusunan Reviu Baseline', '0.50', '0.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '12720000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(104, '2015', '841100', '3550', '001', '001', '011', '3', 'Koordinasi dan SinkronasiProgram dan Anggaran (Renja/inisiatif Baru)', '0.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '14640000.0000', '7000000.0000', '7640000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(105, '2015', '841100', '3550', '001', '001', '011', '4', 'Pembahasan penyusunan inisiatif baru', '0.00', '0.00', '0.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '39040000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(106, '2015', '841100', '3550', '001', '001', '011', '5', 'Pembahasan intensif penyusunan renja berdasarkan Pagu Indikatif', '0.00', '0.00', '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '16530000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(107, '2015', '841100', '3550', '001', '001', '011', '6', 'Trilateral Meeting (Bappenas, Kemenkeu, BSN)', '0.00', '0.00', '0.00', '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '16720000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(108, '2015', '841100', '3550', '001', '001', '011', '7', 'Perbaikan hasil pembahasan Renja/Input Aplikasi Renja', '0.00', '0.00', '0.00', '0.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '1280000.0000', '2560000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(109, '2015', '841100', '3550', '001', '001', '011', '8', 'Pra Raker BSN/evaluasi Raker', '0.00', '2.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '131940000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(110, '2015', '841100', '3550', '001', '001', '011', '9', 'Raker BSN', '0.00', '0.00', '4.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '362558000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(129, '2015', '841100', '3550', '001', '001', '014', '1', 'Administrasi Pendukung kegiatan', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '12008000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(130, '2015', '841100', '3550', '001', '001', '014', '2', 'Koordinasi penyusunan penetaoan kinerja', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '4720000.0000', '0.0000', '4720000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(131, '2015', '841100', '3550', '001', '001', '014', '3', 'Pembahasan reviu Indikator Kinerja Utama (IKU)', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '13410000.0000', '13410000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(132, '2015', '841100', '3550', '001', '001', '014', '4', 'Koordinasi dalam rangka akuntabilitas', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '24920000.0000', '24920000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(133, '2015', '841100', '3550', '001', '001', '014', '5', 'Sosialisasi renstra 2015-2019/Aplikasi Monev', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '23630000.0000', '23630000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(144, '2015', '841100', '3550', '001', '001', '015', '1', 'Administrasi Pendukung kegiatan', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '9148000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(145, '2015', '841100', '3550', '001', '001', '015', '2', 'Pelaksanaan monev kegiatan', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '15140000.0000', '15140000.0000', '11052000.0000', '10604000.0000', '0.0000', '10604000.0000', '0.0000'),
(146, '2015', '841100', '3550', '001', '001', '015', '3', 'Sinkronisasi program dan kegiatan', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '9120000.0000', '0.0000', '9120000.0000', '0.0000', '9120000.0000', '0.0000', '9120000.0000', '0.0000', '0.0000'),
(147, '2015', '841100', '3550', '001', '001', '015', '4', 'Koordinasi monev kegiatan (revisi anggaran 2015)', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '5040000.0000', '22715000.0000', '0.0000', '0.0000', '2240000.0000', '22915000.0000', '0.0000', '5040000.0000', '22715000.0000', '0.0000', '5040000.0000', '22715000.0000'),
(148, '2015', '841100', '3550', '001', '001', '015', '5', 'Penyusunan laporan PP 39 tahun 2006/PP 8 tahun 2006/RKAKL', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '1920000.0000', '0.0000', '0.0000', '1920000.0000', '0.0000', '0.0000', '1920000.0000', '0.0000', '0.0000'),
(149, '2015', '841100', '3550', '001', '001', '015', '6', 'Koordinasi dan sinkronisasi aplikasi monev', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '6680000.0000', '6680000.0000', '6680000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(150, '2015', '841100', '3550', '001', '001', '013', '1', 'Koordinasi penyusunan LAKIP', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '9800000.0000', '5120000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(151, '2015', '841100', '3550', '001', '001', '013', '2', 'Pembahasan intensif penyusunan LAKIP', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '25020000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(152, '2015', '841100', '3550', '001', '001', '013', '3', 'Evaluasi LAKIP', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0000', '0.0000', '0.0000', '10020000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(153, '2015', '841100', '3550', '002', '001', '011', '1', 'coba saja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(154, '2015', '841100', '3550', '002', '001', '012', '1', 'hkjhkj', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `monev_bulanan`
--
ALTER TABLE `monev_bulanan`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `thbp_kak_output`
--
ALTER TABLE `thbp_kak_output`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `thbp_kak_output_tahapan`
--
ALTER TABLE `thbp_kak_output_tahapan`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `monev_bulanan`
--
ALTER TABLE `monev_bulanan`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `thbp_kak_output`
--
ALTER TABLE `thbp_kak_output`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `thbp_kak_output_tahapan`
--
ALTER TABLE `thbp_kak_output_tahapan`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=155;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
