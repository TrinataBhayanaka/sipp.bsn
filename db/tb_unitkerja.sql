-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2015 at 04:15 AM
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
-- Table structure for table `tb_unitkerja`
--

CREATE TABLE IF NOT EXISTS `tb_unitkerja` (
`id` int(11) NOT NULL,
  `kdunit` varchar(6) COLLATE latin1_general_ci DEFAULT NULL,
  `nmunit` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `visi` text COLLATE latin1_general_ci,
  `tugas` text COLLATE latin1_general_ci,
  `sktunit` varchar(100) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=20 ;

--
-- Dumping data for table `tb_unitkerja`
--

INSERT INTO `tb_unitkerja` (`id`, `kdunit`, `nmunit`, `visi`, `tugas`, `sktunit`) VALUES
(1, '840000', 'Badan Standardisasi Nasional', 'Terwujudnya infrastruktur mutu nasional yang handal untuk meningkatkan daya saing dan kualitas hidup bangsa', 'Melaksanakan tugas pemerintahan di bidang standardisasi nasional sesuai dengan ketentuan peraturan perundang-undangan yang berlaku', 'BSN'),
(2, '841000', 'Sekretariat Utama', 'Terwujudnya birokrasi yang efisien, efektif, dan akuntabel', 'Mengkoordinasikan perencanaan, pembinaan, pengendalian administrasi, dan sumber daya di lingkungan BSN', 'SESTAMA'),
(5, '841100', 'Biro Perencanaan, Keuangan dan Tata Usaha', '', 'Melaksanakan penyiapan rumusan kebijakan, pembinaan, koordinasi program dan penyusunan rencana, pengelolaan keuangan, urusan tata usaha dan urusan rumahtangga serta pengelolaan barang/kekayaan milik negara', 'Biro PKT'),
(6, '841200', 'Biro Hukum, Organisasi dan Humas', '', 'Melaksanakan pengkajian dan penelaahan hukum, perumusan dan penyusunan peraturan perundangan, pemberian bantuan dan penyuluhan hukum, analisis dan penataan kelembagaan, pengawasan dan evaluasi manajemen mutu internal, urusan kepegawaian, hubungan masyarakat dan hubungan antar lembaga serta penyusunan laporan', 'Biro HOH'),
(7, '843000', 'Deputi Bidang Penerapan Standar dan Akreditasi', '', 'Melaksanakan perumusan kebijakan di bidang penerapan standar dan akreditasi', 'Deputi PSA'),
(8, '843100', 'Pusat Akreditasi Laboratorium dan Lembaga Inspeksi', '', 'Melaksanakan penyiapan rumusan kebijakan, pembinaan, koordinasi program akreditasi laboratorium penguji, akreditasi laboratorium kalibrasi dan akreditasi lembaga inspeksi', 'PALLI'),
(9, '843200', 'Pusat Akreditasi Lembaga Sertifikasi', '', 'Melaksanakan penyiapan rumusan kebijakan, pembinaan, koordinasi program dan penyusunan rencana di bidang akreditasi dan sertifikasi bidang sistem manajemen, produk, lembaga pelatihan dan personel, dan sejenisnya serta kerjasama dengan lembaga yang terkait dengan kegiatan akreditasi dan sertifikasi baik secara bilateral, regional dan internasional', 'PALS'),
(10, '843300', 'Pusat Sistem Penerapan Standar', '', 'Melaksanakan penyiapan rumusan kebijakan, pembinaan, koordinasi program dan penyusunan rencana di bidang sistem pemberlakuan standar dan penanganan pengaduan serta pembinaan prasarana penerapan standar dan sistem jaminan mutu', 'PALLI'),
(11, '842000', 'Deputi Bidang Penelitian dan Kerjasama Standardisasi', '', 'Melaksanakan perumusan kebijakan di bidang perumusan standar, penelitian dan pengembangan serta kerjasama di bidang standardisasi', 'Deputi PKS'),
(12, '842100', 'Pusat Kerjasama Standardisasi', '', 'Melaksanakan penyiapan rumusan kebijakan, pembinaan, koordinasi program dan penyusunan rencana di bidang notifikasi dan kerjasama teknis perdagangan, kelembagaan standardisasi dalam negeri maupun luar negeri serta kegiatan lain sesuai dengan lingkup kewenangannya', 'PKS'),
(13, '842200', 'Pusat Penelitian dan Pengembangan Standardisasi', '', 'Melaksanakan penyiapan rumusan kebijakan, pembinaan, koordinasi program dan penyusunan rencana di bidang penelitian dan pengembangan standardisasi dalam aspek perumusan standar, penerapan standar, akreditasi, informasi dan pemasyarakatan standardisasi serta kerjasama standardisasi, dan kegiatan lain yang terkait', 'Puslitbang'),
(14, '842300', 'Pusat Perumusan Standar', '', 'Melaksanakan penyiapan rumusan kebijakan, pembinaan, koordinasi program dan penyusunan rencana di bidang pengembangan sistem perumusan,  perumusan dan evaluasi Standar Nasional Indonesia, serta menyusun pedoman di bidang Metrologi teknik, Standar dan evaluasi Uji dan Kualitas (MSUK), dan pemberian tanggapan terhadap konsep standar baik secara bilateral, regional maupun international.', 'PPS'),
(15, '844000', 'Deputi Bidang Informasi dan Pemasyarakatan Standardisasi', '', NULL, 'Deputi IPS'),
(16, '844100', 'Pusat Informasi dan Dokumentasi Standardisasi', '', 'Melaksanakan penyiapan rumusan kebijakan, pembinaan, koordinasi program dan penyusunan rencana di bidang informasi dan dokumentasi standardisasi', 'Pusido'),
(17, '844200', 'Pusat Pendidikan dan Pemasyarakatan Standardisasi', '', 'Melaksanakan penyiapan rumusan kebijakan, pembinaan, koordinasi program dan penyusunan rencana di bidang pendidikan dan pelatihan serta pemasyarakatan di bidang standardisasi dan jaminan mutu', 'Pusdikmas'),
(18, '845100', 'Inspektorat', '', 'Melaksanakan pengawasan fungsional terhadap pelaksanaan tugas di lingkungan BSN', 'Inspektorat');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_unitkerja`
--
ALTER TABLE `tb_unitkerja`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_unitkerja`
--
ALTER TABLE `tb_unitkerja`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
