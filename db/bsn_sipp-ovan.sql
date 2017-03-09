-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 14, 2015 at 09:54 PM
-- Server version: 5.5.40-0ubuntu0.14.04.1
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
-- Table structure for table `bsn_news_content`
--

CREATE TABLE IF NOT EXISTS `bsn_news_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(200) DEFAULT NULL COMMENT 'kode',
  `brief` varchar(300) DEFAULT NULL COMMENT 'satker',
  `desc` text COMMENT 'singkatan',
  `type` int(11) NOT NULL DEFAULT '0',
  `category` int(11) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL,
  `publish_date` datetime NOT NULL,
  `year` varchar(4) DEFAULT NULL,
  `data` text,
  `filename` varchar(100) DEFAULT NULL,
  `tags` varchar(300) DEFAULT NULL,
  `n_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=205 ;

--
-- Dumping data for table `bsn_news_content`
--

INSERT INTO `bsn_news_content` (`id`, `parent_id`, `title`, `brief`, `desc`, `type`, `category`, `create_date`, `publish_date`, `year`, `data`, `filename`, `tags`, `n_status`) VALUES
(1, 0, 'Badan Standardisasi Nasional', NULL, 'Terwujudnya infrastruktur mutu nasional yang handal untuk meningkatkan daya saing dan kualitas hidup bangsa 12345 sip', 5, 1, '2015-10-27 16:07:32', '2015-10-27 16:07:32', NULL, NULL, NULL, NULL, 1),
(2, 0, 'Badan Standardisasi Nasional', NULL, 'merumuskan, menetapkan, dan memelihara Standar Nasional Indonesia (SNI) yang berkualitas dan bermanfaat bagi pemangku kepentingan', 5, 2, '2015-10-27 15:44:54', '2015-10-27 15:44:54', NULL, NULL, NULL, NULL, 1),
(3, 0, 'Badan Standardisasi Nasional', NULL, 'Mewujudkan sistem pengembangan SNI yang efektif dan efisien mendukung daya saing dan kualitas hidup bangsa', 5, 3, '2015-10-27 15:45:35', '2015-10-27 15:45:35', '2015', NULL, NULL, NULL, 0),
(4, 0, 'Badan Standardisasi Nasional', NULL, 'Mengembangkan dan mengelola Sistem Penerapan Standar, Penilaian Kesesuaian, dan Ketelusuran Pengukuran yang handal untuk mendukung implementasi kebijakan nasional di bidang standardisasi dan Pemangku Kepentingan 123', 5, 2, '2015-10-27 16:00:33', '2015-10-27 16:00:33', NULL, NULL, NULL, NULL, 1),
(5, 0, 'Badan Standardisasi Nasional', NULL, 'Mewujudkan sistem penerapan standar, penilaian kesesuaian, dan ketelusuran pengukuran yang efektif dan efisien mendukung daya saing dan kualitas hidup bangsa', 5, 3, '2015-10-27 16:05:03', '2015-10-27 16:05:03', NULL, NULL, NULL, NULL, 0),
(14, 0, 'Badan Standardisasi Nasional', NULL, 'adasdasaa', 5, 1, '2015-10-28 15:22:00', '2015-10-28 15:22:00', NULL, NULL, NULL, NULL, 1),
(15, 1, 'Badan Standardisasi Nasional', NULL, 'oke', 6, 1, '2015-10-28 15:59:49', '2015-10-28 15:59:49', NULL, NULL, NULL, NULL, 1),
(16, 2, 'Biro Hukum, Organisasi dan Humas', NULL, 'visi eselon 1', 6, 1, '2015-10-28 15:27:22', '2015-10-28 15:27:22', NULL, NULL, NULL, NULL, 1),
(17, 2, 'Biro Hukum, Organisasi dan Humas', NULL, 'aaaaaa', 6, 1, '2015-10-28 15:29:17', '2015-10-28 15:29:17', NULL, NULL, NULL, NULL, 1),
(18, 2, 'Biro Hukum, Organisasi dan Humas', NULL, 'visi biro hukum', 6, 0, '2015-10-28 15:47:52', '2015-10-28 15:47:52', NULL, NULL, NULL, NULL, 1),
(19, 2, 'Biro Hukum, Organisasi dan Humas', NULL, 'aaa', 6, 2, '2015-10-28 15:48:52', '2015-10-28 15:48:52', NULL, NULL, NULL, NULL, 0),
(20, 2, 'Biro Hukum, Organisasi dan Humas', NULL, 'tujuan biro ', 6, 3, '2015-10-28 15:49:43', '2015-10-28 15:49:43', NULL, NULL, NULL, NULL, 1),
(21, 1, 'Badan Standardisasi Nasional', NULL, 'Melaksanakan pelayanan kelembagaan dan administrasi kepegawaian yang proporsional, efektif dan efisien 123', 6, 3, '2015-10-28 16:02:12', '2015-10-28 16:02:12', NULL, NULL, NULL, NULL, 0),
(22, 1, 'Badan Standardisasi Nasional', NULL, 'Mewujudkan tata kelola kepemerintahan yang baik (good governance) sebagai pendukung pelaksanaan pengembangan dan pembinaan standardisasi dan penilaian kesesuaian', 6, 2, '2015-10-28 15:50:46', '2015-10-28 15:50:46', NULL, NULL, NULL, NULL, 1),
(23, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(24, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(25, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(26, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(27, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(28, 2, 'Biro Hukum, Organisasi dan Humas', NULL, 'bbbbb 123', 6, 2, '2015-10-28 16:05:34', '2015-10-28 16:05:34', NULL, NULL, NULL, NULL, 1),
(29, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(30, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(31, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(32, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(33, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(34, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(35, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(36, 0, 'Badan Standardisasi Nasional', NULL, 'Kini, makin banyak beredar rokok elektronik atau personal vapour. Penjualannya makin marak di internet, dan kini mulai merambah di pusat-pusat perbelanjaan. Sehingga, saat ini makin mudah melihat orang menghisap rokok elektronik (vaping). Hingga kini, Indonesia memang belum memiliki regulasi yang mengatur tentang penggunaan rokok elektronik. ', 5, 3, '2015-10-28 16:08:16', '2015-10-28 16:08:16', NULL, NULL, NULL, NULL, 1),
(47, 1, 'Badan Standardisasi Nasional', NULL, 'dsadaasa', 6, 1, '2015-10-28 16:29:50', '2015-10-28 16:29:50', NULL, NULL, NULL, NULL, 1),
(48, 1, 'Badan Standardisasi Nasional', '2015-2019', 'Melindungi keselamatan, keamanan, kesehatan masyarakat, pelestarian fungsi lingkungan hidup', 7, 1, '2015-10-28 16:46:47', '2015-10-28 16:46:47', NULL, NULL, NULL, NULL, 1),
(49, 1, 'Badan Standardisasi Nasional', NULL, 'bbbbb', 7, 1, '2015-10-28 16:35:32', '2015-10-28 16:35:32', NULL, NULL, NULL, NULL, 0),
(50, 2, 'Biro Hukum, Organisasi dan Humas', '2015-2019', 'Tersusunnya dokumen perencanaan dan monitoring evaluasi', 7, 1, '2015-10-28 16:47:48', '2015-10-28 16:47:48', NULL, NULL, NULL, NULL, 1),
(51, 1, 'Badan Standardisasi Nasional', '2015-2019', 'Meningkatnya daya saing produk nasional di pasar domestik', 7, 1, '2015-10-28 16:47:07', '2015-10-28 16:47:07', NULL, NULL, NULL, NULL, 1),
(58, 1, 'Badan Standardisasi Nasional', '2015-2019', 'Meningkatnya akses produk nasional ke pasar domestik', 7, 1, '2015-10-28 16:47:22', '2015-10-28 16:47:22', NULL, NULL, NULL, NULL, 0),
(59, 2, 'Biro Hukum, Organisasi dan Humas', '2015-2019', 'Terselenggaranya pengelolaan keuangan', 7, 1, '2015-10-28 16:48:09', '2015-10-28 16:48:09', NULL, NULL, NULL, NULL, 1),
(67, 1, 'Badan Standardisasi Nasional', 'cover', NULL, 15, 1, '2015-10-28 21:58:03', '2015-10-28 21:58:03', NULL, NULL, '', '1', 0),
(68, 1, 'Badan Standardisasi Nasional', 'aaaa', NULL, 15, 1, '2015-10-28 22:00:29', '2015-10-28 22:00:29', '2016', NULL, NULL, '2', 0),
(69, 1, 'Badan Standardisasi Nasional', 'aaaa', NULL, 15, 1, '2015-10-28 22:00:53', '2015-10-28 22:00:53', NULL, NULL, 'd436c7242c9720fa1f032139346f716f.png', '2', 0),
(70, 2, 'Biro Hukum, Organisasi dan Humas', 'aaaaaaaaaaaa', NULL, 15, 1, '2015-10-28 22:37:39', '2015-10-28 22:37:39', NULL, NULL, '3700da0685258afd33bf324e26fdc040.png', '1', 0),
(71, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(72, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(73, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(74, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(75, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(76, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(77, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(78, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(79, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(80, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(81, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(82, 2, 'Biro Hukum, Organisasi dan Humas', 'testttttt', NULL, 15, 1, '2015-10-29 12:16:43', '2015-10-29 12:16:43', NULL, NULL, '3868e76cb789b3f1768c0d6e8cb1af87.png', '2', 0),
(83, 2, 'Biro Hukum, Organisasi dan Humas', 'siappp', NULL, 15, 1, '2015-10-29 12:22:22', '2015-10-29 12:22:22', NULL, NULL, 'fabf19d22dddadde32fdd369074091d3.png', '3', 1),
(84, 2, 'Biro Hukum, Organisasi dan Humas', 'okokok', NULL, 15, 1, '2015-10-29 12:28:47', '2015-10-29 12:28:47', NULL, NULL, 'fbe88cf958e367621355e97c7f4c34f4.png', '4', 1),
(85, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(86, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(87, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(88, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(89, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(90, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(91, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(101, 48, 'Badan Standardisasi Nasional', NULL, 'csadsada', 8, 1, '2015-10-29 14:02:30', '2015-10-29 14:02:30', NULL, 'a:5:{i:0;s:1:"1";i:1;s:1:"0";i:2;s:1:"0";i:3;s:1:"3";i:4;s:1:"2";}', NULL, NULL, 0),
(102, 48, 'Badan Standardisasi Nasional', NULL, 'dsaadsada', 8, 1, '2015-10-29 14:07:50', '2015-10-29 14:07:50', NULL, 'a:5:{i:0;s:2:"11";i:1;s:1:"1";i:2;s:1:"2";i:3;s:1:"3";i:4;s:1:"4";}', NULL, NULL, 0),
(103, 51, 'Badan Standardisasi Nasional', NULL, 'Meningkatkan daya saing produk nasional di pasar domestik', 8, 1, '2015-10-29 15:17:35', '2015-10-29 15:17:35', '2016', 'a:5:{i:0;s:4:"1000";i:1;s:1:"0";i:2;s:1:"0";i:3;s:1:"0";i:4;s:1:"0";}', NULL, NULL, 0),
(104, 48, 'Badan Standardisasi Nasional', NULL, 'Tingkat persepsi terhadap keamanan dan keselamatan produk bertanda SNI', 8, 1, '2015-10-29 15:15:55', '2015-10-29 15:15:55', '2016', 'a:5:{i:0;s:1:"5";i:1;s:1:"4";i:2;s:1:"3";i:3;s:1:"2";i:4;s:1:"1";}', NULL, NULL, 0),
(105, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(106, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(107, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(108, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(109, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(110, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(111, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(112, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(113, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(114, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(115, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(116, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(117, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(118, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(119, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(120, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, NULL, NULL, NULL, 0),
(121, 0, NULL, NULL, 'tahun_sistem', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, '2016-2020', NULL, NULL, 1),
(122, 0, NULL, NULL, 'tahun_sistem', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, '2015-2019', NULL, NULL, 1),
(123, 0, 'Badan Standardisasi Nasional', NULL, 'tujuan', 5, 3, '2015-10-29 15:47:30', '2015-10-29 15:47:30', NULL, NULL, NULL, NULL, 1),
(124, 0, 'Badan Standardisasi Nasional', NULL, 'sdada', 5, 3, '2015-10-29 15:52:58', '2015-10-29 15:52:58', '2016', NULL, NULL, NULL, 0),
(125, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2016', NULL, NULL, NULL, 0),
(126, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2016', NULL, NULL, NULL, 0),
(127, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2016', NULL, NULL, NULL, 0),
(128, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2016', NULL, NULL, NULL, 0),
(129, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2016', NULL, NULL, NULL, 0),
(130, 51, 'Badan Standardisasi Nasional', NULL, 'dklsadlsad;as, sadas', 8, 1, '2015-10-29 19:47:09', '2015-10-29 19:47:09', '2016', 'a:5:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"4";}', NULL, NULL, 0),
(157, 1, 'Badan Standardisasi Nasional', '084.01.01', 'Program Dukungan Manajemen dan Pelaksanaan Tugas Teknis Lainnya BSN', 9, 1, '2015-10-30 11:26:38', '2015-10-30 11:26:38', '2016', NULL, NULL, NULL, 1),
(158, 1, 'Badan Standardisasi Nasional', '31231', 'dadasda', 9, 1, '2015-10-30 10:10:50', '2015-10-30 10:10:50', '2016', NULL, NULL, NULL, 1),
(159, 1, 'Badan Standardisasi Nasional', '222', 'coba aja', 9, 1, '2015-10-30 10:16:37', '2015-10-30 10:16:37', '2016', NULL, NULL, NULL, 1),
(160, 1, 'Badan Standardisasi Nasional', '3423', 'aaaa', 9, 1, '2015-10-30 10:48:15', '2015-10-30 10:48:15', '2016', NULL, NULL, NULL, 1),
(161, 1, 'Badan Standardisasi Nasional', '12312', 'okokokoko', 9, 2, '2015-10-30 11:03:58', '2015-10-30 11:03:58', '2016', NULL, NULL, NULL, 1),
(162, 157, 'Badan Standardisasi Nasional', '084.01.01', 'Tercapainya pengelolaan dan pengendalian anggaran yang akuntabel, SDM yang profesional, dan organisasi yang efektif', 9, 2, '2015-10-30 12:24:37', '2015-10-30 12:24:37', '2015', NULL, NULL, '2', 1),
(163, 48, 'Badan Standardisasi Nasional', NULL, 'efrefwefw', 8, 1, '2015-10-30 11:39:33', '2015-10-30 11:39:33', '2016', 'a:5:{i:0;s:2:"12";i:1;s:2:"12";i:2;s:2:"12";i:3;s:2:"21";i:4;s:2:"21";}', NULL, NULL, 1),
(164, 162, '', 'Program Dukungan Manajemen dan Pelaksanaan Tugas Teknis Lainnya BSN', 'aaaasdsa', 9, 3, '2015-10-30 13:05:43', '2015-10-30 13:05:43', '2015', 'a:5:{i:0;s:1:"1";i:1;s:1:"1";i:2;s:1:"1";i:3;s:1:"1";i:4;s:1:"1";}', NULL, NULL, 0),
(165, 162, '', 'Program Dukungan Manajemen dan Pelaksanaan Tugas Teknis Lainnya BSN', 'Penilaian PMPRB 123', 9, 3, '2015-10-30 15:24:38', '2015-10-30 15:24:38', '2015', 'a:5:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";}', NULL, NULL, 1),
(166, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(167, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(168, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(169, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(170, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(171, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(172, 157, '21121', NULL, 'dsadaas', 10, 1, '2015-10-30 16:35:43', '2015-10-30 16:35:43', '2015', '1', NULL, '0', 1),
(173, 157, '11111', NULL, 'Peningkatan Pelayanan Hukum, Organisasi dan Humas BSN', 10, 1, '2015-10-30 16:59:25', '2015-10-30 16:59:25', '2015', '1', NULL, 'B', 1),
(174, 157, '21121', NULL, 'Peningkatan Perencanaan, Keuangan dan Tata Usaha BSN', 10, 1, '2015-10-30 16:58:56', '2015-10-30 16:58:56', '2015', '2', NULL, 'K/L', 1),
(175, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(176, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(177, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(178, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(179, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(180, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(181, 158, '111111', NULL, 'aaaaaa', 10, 1, '2015-10-30 17:00:23', '2015-10-30 17:00:23', '2015', '2', NULL, 'NS', 1),
(182, 172, '12', NULL, 'nama output 1', 11, 1, '2015-10-30 17:58:05', '2015-10-30 17:58:05', '2015', 'a:5:{i:0;s:2:"12";i:1;s:2:"12";i:2;s:2:"13";i:3;s:2:"14";i:4;s:2:"15";}', NULL, NULL, 1),
(183, 182, '12', NULL, 'nama kegiatab output 1', 11, 2, '2015-10-30 18:28:31', '2015-10-30 18:28:31', '2015', 'a:5:{i:0;s:1:"1";i:1;s:1:"1";i:2;s:1:"1";i:3;s:1:"1";i:4;s:2:"10";}', NULL, NULL, 1),
(184, 0, 'BSN', NULL, 'tugas 1 2', 1, 1, '2015-10-30 19:35:03', '2015-10-30 19:35:03', '2015', NULL, NULL, NULL, 1),
(185, 184, 'BSN', NULL, 'aaaaa 1', 1, 2, '2015-10-30 20:00:14', '2015-10-30 20:00:14', '2015', NULL, NULL, NULL, 1),
(186, 0, '2', NULL, 'tugasssssssssssssss 1', 2, 1, '2015-10-30 20:46:00', '2015-10-30 20:46:00', '2015', NULL, NULL, NULL, 1),
(187, 186, 'Biro Hukum, Organisasi dan Humas', NULL, 'test aja', 1, 2, '2015-10-30 21:09:30', '2015-10-30 21:09:30', '2015', NULL, NULL, NULL, 1),
(188, 186, 'Biro Hukum, Organisasi dan Humas', NULL, 'aaas dasda ok', 2, 2, '2015-10-30 21:18:38', '2015-10-30 21:18:38', '2015', NULL, NULL, NULL, 1),
(189, 0, '1', NULL, 'aaaa 111', 3, 1, '2015-10-30 21:24:45', '2015-10-30 21:24:45', '2015', NULL, NULL, NULL, 1),
(190, 189, 'Badan Standardisasi Nasional 1', NULL, 'okey', 3, 2, '2015-10-30 21:25:01', '2015-10-30 21:25:01', '2015', NULL, NULL, NULL, 1),
(191, 1, 'Badan Standardisasi Nasional 1', 'dsadasda', NULL, 15, 1, '2015-10-31 08:19:59', '2015-10-31 08:19:59', '2015', NULL, '9a45f05c15c689743a74760b884f1936.png', '1', 1),
(192, 2, 'Biro Hukum, Organisasi dan Humas', 'ggggg', NULL, 15, 1, '2015-10-31 08:21:13', '2015-10-31 08:21:13', '2015', NULL, 'c25a1d1adc24c9ddd39c06651cf91c3e.jpg', '1', 1),
(193, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(194, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(195, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(196, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(197, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(198, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(199, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(200, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(201, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(202, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(203, 0, NULL, NULL, NULL, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015', NULL, NULL, NULL, 0),
(204, 48, 'Badan Standardisasi Nasional', NULL, 'test', 8, 1, '2015-11-02 15:58:43', '2015-11-02 15:58:43', '2015', 'a:5:{i:0;s:1:"2";i:1;s:1:"2";i:2;s:1:"9";i:3;s:1:"2";i:4;s:1:"4";}', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `bsn_sistem_setting`
--

CREATE TABLE IF NOT EXISTS `bsn_sistem_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(4) DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL,
  `data` text,
  `n_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `bsn_sistem_setting`
--

INSERT INTO `bsn_sistem_setting` (`id`, `kode`, `desc`, `data`, `n_status`) VALUES
(1, '2015', 'tahun_sistem', '2015-2019', 1);

-- --------------------------------------------------------

--
-- Table structure for table `bsn_struktur`
--

CREATE TABLE IF NOT EXISTS `bsn_struktur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) DEFAULT NULL,
  `nama_satker` varchar(300) DEFAULT NULL,
  `singkatan` varchar(100) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `n_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `bsn_struktur`
--

INSERT INTO `bsn_struktur` (`id`, `kode`, `nama_satker`, `singkatan`, `create_date`, `n_status`) VALUES
(1, '840000', 'Badan Standardisasi Nasional', 'BSN', '2015-11-02 15:42:11', 1),
(2, '841100', 'Biro Hukum, Organisasi dan Humas', 'Biro HOH', NULL, 1),
(3, '1111', 'aaaa', 'dasdasdsa', '2015-10-30 19:14:15', 1),
(4, '1111', 'satker', 'sa', '2015-11-02 15:36:43', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ck_activity`
--

CREATE TABLE IF NOT EXISTS `ck_activity` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL COMMENT '1:content;2:norma;3:peraturan;4:produk;5:program;6:sig;7:user',
  `activity_value` varchar(50) NOT NULL,
  `n_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ck_activity_log`
--

CREATE TABLE IF NOT EXISTS `ck_activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `activity_desc` varchar(250) NOT NULL,
  `source` varchar(20) NOT NULL,
  `datetimes` datetime NOT NULL,
  `n_status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ck_admin_member`
--

CREATE TABLE IF NOT EXISTS `ck_admin_member` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` varchar(46) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `menu_akses` varchar(300) DEFAULT NULL,
  `username` varchar(46) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '1:admin, 2:verifikator, 3:evaluator, 4: balai',
  `salt` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `n_status` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ck_admin_member`
--

INSERT INTO `ck_admin_member` (`id`, `name`, `nickname`, `email`, `register_date`, `menu_akses`, `username`, `type`, `salt`, `password`, `n_status`) VALUES
(1, 'admin', 'admin', 'admin@example.com', '2014-08-07 15:56:36', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24', 'admin', 1, 'codekir v3.0', 'b2e982d12c95911b6abeacad24e256ff3fa47fdb', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ck_menu`
--

CREATE TABLE IF NOT EXISTS `ck_menu` (
  `menuID` int(2) NOT NULL AUTO_INCREMENT,
  `menuDesc` varchar(50) DEFAULT NULL,
  `menuParent` int(2) DEFAULT NULL,
  `menuPath` varchar(100) DEFAULT NULL,
  `menuIcon` varchar(100) DEFAULT NULL,
  `menuStatus` int(11) NOT NULL,
  `menuAksesLogin` int(11) NOT NULL,
  PRIMARY KEY (`menuID`),
  KEY `menuID` (`menuID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=69 ;

-- --------------------------------------------------------

--
-- Table structure for table `ck_menu_parent`
--

CREATE TABLE IF NOT EXISTS `ck_menu_parent` (
  `menuParentID` int(2) NOT NULL AUTO_INCREMENT,
  `menuParentDesc` varchar(20) DEFAULT NULL,
  `menuOrder` int(11) NOT NULL,
  PRIMARY KEY (`menuParentID`),
  KEY `menuParentID` (`menuParentID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `ck_user_member`
--

CREATE TABLE IF NOT EXISTS `ck_user_member` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `nik` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(16) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `jenis_kelamin` tinyint(1) DEFAULT NULL,
  `tempat_lahir` tinytext,
  `tanggal_lahir` date DEFAULT NULL,
  `pendidikan` varchar(255) DEFAULT NULL,
  `institusi` tinytext,
  `jenis_pekerjaan` varchar(255) DEFAULT NULL,
  `hp` tinytext,
  `alamat` text,
  `other_data` text,
  `type` int(11) DEFAULT NULL COMMENT '1:admin,2:user',
  `salt` varchar(200) DEFAULT NULL,
  `login_count` int(11) NOT NULL DEFAULT '0',
  `email_token` varchar(255) DEFAULT NULL,
  `is_online` int(11) NOT NULL DEFAULT '0',
  `n_status` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
