-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2024 at 08:20 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `t_admin`
--

CREATE TABLE `t_admin` (
  `f_id` int(11) NOT NULL,
  `f_nama` varchar(200) NOT NULL,
  `f_username` varchar(200) NOT NULL,
  `f_password` varchar(200) NOT NULL,
  `f_level` enum('Admin','Pustakawan') DEFAULT NULL,
  `f_status` enum('Aktif','Tidak Aktif') DEFAULT NULL,
  `f_input` datetime DEFAULT NULL,
  `f_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_admin`
--

INSERT INTO `t_admin` (`f_id`, `f_nama`, `f_username`, `f_password`, `f_level`, `f_status`, `f_input`, `f_update`) VALUES
(64, 'budi', 'budi', '202cb962ac59075b964b07152d234b70', 'Admin', 'Aktif', '2024-04-23 13:47:33', '2024-04-23 13:47:33'),
(66, 'nurul', 'pustakawan1', '202cb962ac59075b964b07152d234b70', 'Pustakawan', 'Aktif', '2024-04-23 13:49:11', '2024-04-23 13:49:11'),
(67, 'kiki', 'pustakawan2', '202cb962ac59075b964b07152d234b70', 'Pustakawan', 'Tidak Aktif', '2024-04-23 13:49:11', '2024-04-23 13:49:11'),
(73, 'fadli', 'fadli', '202cb962ac59075b964b07152d234b70', 'Admin', 'Tidak Aktif', '2024-04-24 08:38:06', '2024-04-24 08:38:06');

-- --------------------------------------------------------

--
-- Table structure for table `t_anggota`
--

CREATE TABLE `t_anggota` (
  `f_id` int(11) NOT NULL,
  `f_nama` varchar(200) NOT NULL,
  `f_username` varchar(200) NOT NULL,
  `f_password` varchar(200) NOT NULL,
  `f_tempatlahir` varchar(100) DEFAULT NULL,
  `f_tanggallahir` date DEFAULT NULL,
  `t_input` datetime DEFAULT NULL,
  `t_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_anggota`
--

INSERT INTO `t_anggota` (`f_id`, `f_nama`, `f_username`, `f_password`, `f_tempatlahir`, `f_tanggallahir`, `t_input`, `t_update`) VALUES
(108, 'Andi', 'andi', '202cb962ac59075b964b07152d234b70', 'Jakarta', '2024-04-24', '2024-04-24 07:52:02', '2024-04-24 07:52:02'),
(110, 'kim', 'kimbal', '202cb962ac59075b964b07152d234b70', 'Jakarta', '2024-04-24', '2024-04-24 08:40:05', '2024-04-24 08:40:05');

-- --------------------------------------------------------

--
-- Table structure for table `t_buku`
--

CREATE TABLE `t_buku` (
  `f_id` int(11) NOT NULL,
  `f_idkategori` int(11) NOT NULL,
  `f_judul` varchar(200) NOT NULL,
  `f_gambar` varchar(100) NOT NULL,
  `f_pengarang` varchar(200) NOT NULL,
  `f_penerbit` varchar(200) NOT NULL,
  `f_deskripsi` varchar(500) NOT NULL,
  `f_tglinput` datetime DEFAULT NULL,
  `f_tglupdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_buku`
--

INSERT INTO `t_buku` (`f_id`, `f_idkategori`, `f_judul`, `f_gambar`, `f_pengarang`, `f_penerbit`, `f_deskripsi`, `f_tglinput`, `f_tglupdate`) VALUES
(45, 58, 'Gadis Kretek', '9789792281415_Gadis_Kretek_.jpg', 'Ratih Kumala', 'Gramedia Pustaka Utama', 'Pak Raja sekarat. Dalam menanti ajal, ia memanggil satu nama perempuan yang bukan istrinya; Jeng Yah. Tiga anaknya, pewaris Kretek Djagad Raja, dimakan gundah. Sang ibu pun terbakar cemburu terlebih karena permintaan terakhir suaminya ingin bertemu Jeng Yah. Maka berpacu dengan malaikat maut, Lebas, Karim, dan Tegar, pergi ke pelosok Jawa untuk mencari Jeng Yah, sebelum ajal menjemput sang Ayah.  Perjalanan itu bagai napak tilas bisnis dan rahasia keluarga. Lebas, Karim, dan Tegar bertemu dengan', '2024-04-23 09:33:44', '2024-04-24 09:38:17'),
(46, 58, 'Keep Up with Us!', '722030292_Keep_up_With_Us_.jpg', 'G. Dani', 'Elex Media Komputindo', 'Gilang si Tengah, kesal saat mengetahui bahwa Gita ternyata sudah kenal duluan dengan si Sulung, Gara. Yang bikin gawat, orangtua Gita sangat berharap agar Gara menjadi menantu mereka. Nggak heran juga, karena si Tengah sadar kalau si Sulung akan selalu dianggap lebih baik dari dirinya. Ironisnya, sejak malam itu pula, Gara justru lebih terbuka pada Gita dibanding keluarganya sendiri.', '2024-04-23 09:36:26', '2024-04-24 09:37:57'),
(48, 58, 'tes', '8dvskhdzjvubrt5crmlfbd.jpeg', 'yss', 'nj', ' Bertahun-tahun Blu naksir berat sama Erik Pentagon sejak dari dulu. Tapi berbeda dengan Erik yang juga sudah bertahun-tahun naksir cewek lain, cewek yang terlarang buat dia. Semua orang bisa melihat perasaan Blu ke Erik, kecuali Erik sendiri. Sampai ketika, di satu siang yang gerah, melibatkan kolam renang, air mata, dan seseorang shirtless, dinamika hubungan Erik-Blu berubah drastis! Pelukan yang biasanya mereka lakukan dengan santai, jadi sesuatu yang bikin keduanya panas-dingin. Terutama Eri', '2024-04-24 09:28:03', '2024-04-24 09:44:13');

-- --------------------------------------------------------

--
-- Table structure for table `t_detailbuku`
--

CREATE TABLE `t_detailbuku` (
  `f_id` int(11) NOT NULL,
  `f_idbuku` int(11) NOT NULL,
  `f_status` enum('Tersedia','Tidak Tersedia') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_detailbuku`
--

INSERT INTO `t_detailbuku` (`f_id`, `f_idbuku`, `f_status`) VALUES
(197, 46, 'Tersedia'),
(198, 46, 'Tersedia'),
(199, 46, 'Tidak Tersedia'),
(203, 45, 'Tersedia'),
(204, 45, 'Tidak Tersedia'),
(205, 48, 'Tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `t_detailpeminjaman`
--

CREATE TABLE `t_detailpeminjaman` (
  `f_id` int(11) NOT NULL,
  `f_idpeminjaman` int(11) NOT NULL,
  `f_iddetailbuku` int(11) NOT NULL,
  `f_tanggalkembali` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_detailpeminjaman`
--

INSERT INTO `t_detailpeminjaman` (`f_id`, `f_idpeminjaman`, `f_iddetailbuku`, `f_tanggalkembali`) VALUES
(103, 123, 204, '2024-04-24'),
(105, 125, 205, '0000-00-00'),
(107, 127, 198, '0000-00-00'),
(108, 128, 198, '2024-04-24'),
(109, 129, 199, '2024-04-24'),
(110, 130, 204, '0000-00-00'),
(111, 131, 199, '0000-00-00'),
(112, 132, 203, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `t_kategori`
--

CREATE TABLE `t_kategori` (
  `f_id` int(11) NOT NULL,
  `f_kategori` varchar(200) NOT NULL,
  `f_tglupdt` datetime DEFAULT NULL,
  `f_tglinpt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_kategori`
--

INSERT INTO `t_kategori` (`f_id`, `f_kategori`, `f_tglupdt`, `f_tglinpt`) VALUES
(58, 'Novel', '2024-04-23 09:58:22', '2024-04-23 09:29:09'),
(59, 'Manga', '2024-04-23 09:58:05', '2024-04-23 09:41:27'),
(60, 'Horror', '2024-04-24 07:40:37', '2024-04-24 07:40:37'),
(63, 'Komik', '2024-04-24 08:34:32', '2024-04-24 08:34:32'),
(64, 'Fiksi', '2024-04-24 12:51:54', '2024-04-24 12:51:54');

-- --------------------------------------------------------

--
-- Table structure for table `t_peminjaman`
--

CREATE TABLE `t_peminjaman` (
  `f_id` int(11) NOT NULL,
  `f_idadmin` int(11) NOT NULL,
  `f_idanggota` int(11) NOT NULL,
  `f_tanggalpeminjaman` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_peminjaman`
--

INSERT INTO `t_peminjaman` (`f_id`, `f_idadmin`, `f_idanggota`, `f_tanggalpeminjaman`) VALUES
(122, 64, 110, '2024-04-24'),
(123, 64, 110, '2024-04-16'),
(124, 64, 110, '2024-04-24'),
(125, 64, 108, '2024-04-22'),
(126, 64, 110, '2024-04-24'),
(127, 64, 110, '2024-04-24'),
(128, 64, 110, '2024-04-15'),
(129, 66, 108, '2024-04-22'),
(130, 66, 108, '2024-04-24'),
(131, 66, 110, '2024-04-24'),
(132, 66, 110, '2024-04-14');

-- --------------------------------------------------------

--
-- Table structure for table `t_riwayat`
--

CREATE TABLE `t_riwayat` (
  `f_id` int(11) NOT NULL,
  `f_idadmin` int(11) DEFAULT NULL,
  `f_tanggalriwayat` datetime DEFAULT NULL,
  `f_catatan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_riwayat`
--

INSERT INTO `t_riwayat` (`f_id`, `f_idadmin`, `f_tanggalriwayat`, `f_catatan`) VALUES
(193, 64, '2024-04-23 08:55:10', 'budi telah Menghapus buku dengan judul The Little Prince'),
(194, 64, '2024-04-23 08:56:17', 'budi telah Menghapus buku dengan judul Sherlock : The Great Game'),
(195, 64, '2024-04-23 09:16:22', 'budi telah Menghapus kategori dengan nama kategori Non-Fiksi'),
(196, 64, '2024-04-23 09:16:25', 'budi telah Menghapus kategori dengan nama kategori Novel'),
(197, 64, '2024-04-23 09:16:27', 'budi telah Menghapus kategori dengan nama kategori Fiksi'),
(198, 64, '2024-04-23 09:28:51', 'budi telah menambahkan kategori baru dengan nama kategori Komik'),
(199, 64, '2024-04-23 09:28:56', 'budi telah Menghapus kategori dengan nama kategori Novel'),
(200, 64, '2024-04-23 09:28:59', 'budi telah Menghapus kategori dengan nama kategori Anak-anak'),
(201, 64, '2024-04-23 09:29:01', 'budi telah Menghapus kategori dengan nama kategori Self help'),
(202, 64, '2024-04-23 09:29:02', 'budi telah Menghapus kategori dengan nama kategori Pelajaran'),
(203, 64, '2024-04-23 09:29:06', 'budi telah Menghapus kategori dengan nama kategori Manga'),
(204, 64, '2024-04-23 09:29:07', 'budi telah Menghapus kategori dengan nama kategori Horror'),
(205, 64, '2024-04-23 09:29:09', 'budi telah Menghapus kategori dengan nama kategori Komik'),
(206, 64, '2024-04-23 09:29:18', 'budi telah menambahkan kategori baru dengan nama kategori Novel'),
(207, 64, '2024-04-23 09:39:51', 'budi telah menambahkan buku baru dengan judul Keep Up with Us!'),
(208, 64, '2024-04-23 09:41:38', 'budi telah menambahkan kategori baru dengan nama kategori Manga'),
(209, 64, '2024-04-23 09:44:21', 'budi telah menambahkan buku baru dengan judul tes'),
(210, 64, '2024-04-23 09:44:27', 'budi telah Menghapus buku dengan judul tes'),
(211, 64, '2024-04-23 09:44:58', 'budi telah Menghapus buku dengan judul Gadis Kretek'),
(212, 64, '2024-04-23 09:57:53', 'Admin budi berhasil melakukan perubahan nama kategori dari \'Manga\' menjadi \'Mangan\' dengan nama kategori sebelumnya \'Manga\''),
(213, 64, '2024-04-23 09:58:08', 'Admin budi berhasil melakukan perubahan nama kategori dari \'Mangan\' menjadi \'Manga\' dengan nama kategori sebelumnya \'Mangan\''),
(214, 64, '2024-04-23 09:58:15', 'Admin budi berhasil melakukan perubahan nama kategori dari \'Novel\' menjadi \'Noveli\' dengan nama kategori sebelumnya \'Novel\''),
(215, 64, '2024-04-23 09:58:26', 'Admin budi berhasil melakukan perubahan nama kategori dari \'Noveli\' menjadi \'Novel\' dengan nama kategori sebelumnya \'Noveli\''),
(216, 64, '2024-04-24 02:18:31', 'budi telah menambahkan admin baru dengan Username tes'),
(217, 64, '2024-04-24 02:26:08', 'Admin budi berhasil melakukan perubahan username dari \'tes\' menjadi \'tesa\' di-akun admin dengan username \'tes\''),
(218, 64, '2024-04-24 02:26:18', 'budi telah Menghapus admin dengan Username tesa'),
(219, 64, '2024-04-24 02:36:26', 'budi telah menambahkan admin baru dengan Username engkim'),
(220, 64, '2024-04-24 07:37:52', 'budi telah menambahkan admin baru dengan Username rev'),
(221, 64, '2024-04-24 02:38:07', 'budi telah Menghapus admin dengan Username engkim'),
(222, 64, '2024-04-24 07:40:11', 'budi telah menambahkan admin baru dengan Username engkim'),
(223, 64, '2024-04-24 07:40:46', 'budi telah menambahkan kategori baru dengan nama kategori Horror'),
(224, 64, '2024-04-24 07:41:11', 'budi telah Menghapus admin dengan Username rev'),
(225, 64, '2024-04-24 07:41:15', 'budi telah Menghapus admin dengan Username engkim'),
(226, 64, '2024-04-24 07:52:56', 'budi telah menambahkan anggota baru dengan Username andi'),
(227, 64, '2024-04-24 07:54:55', 'budi telah menambahkan anggota baru dengan Username rev'),
(228, 64, '2024-04-24 07:55:09', 'Admin budi berhasil melakukan perubahan username dari \'rev\' menjadi \'revalina\' di-akun anggota dengan username \'rev\''),
(229, 64, '2024-04-24 07:55:27', 'budi telah Menghapus anggota dengan Username revalina'),
(230, 64, '2024-04-24 08:00:19', 'Admin budi melakukan perubahan kategori dari \'Novel\' (ID: 58) menjadi \'Manga\' (ID: 59) pada buku dengan judul \'Gadis Kretek\''),
(231, 64, '2024-04-24 08:00:30', 'Admin budi melakukan perubahan kategori dari \'Manga\' (ID: 59) menjadi \'Novel\' (ID: 58) pada buku dengan judul \'Gadis Kretek\''),
(232, 64, '2024-04-24 08:24:08', 'Admin budi telah menambahkan kategori baru dengan nama kategori Komik'),
(233, 64, '2024-04-24 08:24:16', 'Admin budi berhasil melakukan perubahan nama kategori dari \'Komik\' menjadi \'Komika\' dengan nama kategori sebelumnya \'Komik\''),
(234, 64, '2024-04-24 08:24:19', 'Admin budi telah Menghapus kategori dengan nama kategori Komika'),
(235, 64, '2024-04-24 08:30:14', 'Admin budi berhasil melakukan perubahan status dari \'Tidak Aktif\' menjadi \'Aktif\' di-akun admin dengan username \'fadli\''),
(237, 64, '2024-04-24 08:31:15', 'Admin budi telah Menghapus admin dengan Username fadli'),
(238, 64, '2024-04-24 08:32:23', 'Admin budi telah Menghapus kategori dengan nama kategori Mangan'),
(239, 64, '2024-04-24 08:32:47', 'Admin budi telah menambahkan admin baru dengan Username fadli'),
(240, 64, '2024-04-24 08:34:11', 'Admin budi berhasil melakukan perubahan status dari \'Tidak Aktif\' menjadi \'Aktif\' di-akun admin dengan username \'fadli\''),
(242, 64, '2024-04-24 08:35:13', 'Admin budi telah Menghapus admin dengan Username fadli'),
(243, 64, '2024-04-24 08:37:42', 'Admin budi telah Menghapus admin dengan Username fadli'),
(244, 64, '2024-04-24 08:38:25', 'Admin budi telah menambahkan admin baru dengan Username fadli'),
(245, 64, '2024-04-24 08:40:26', 'Admin budi telah menambahkan anggota baru dengan Username kimbal'),
(246, 64, '2024-04-24 08:41:33', 'Admin budi telah menambahkan peminjaman baru dengan ID Peminjaman: 122'),
(247, 64, '2024-04-24 08:51:02', 'budi telah melakukan perubahan pada peminjaman buku dengan id peminjaman 122'),
(248, 64, '2024-04-24 08:51:11', 'budi telah melakukan perubahan pada peminjaman buku dengan id peminjaman 122'),
(249, 64, '2024-04-24 09:07:39', 'budi telah melakukan perubahan pada peminjaman buku dengan id peminjaman 122'),
(250, 64, '2024-04-24 09:22:40', 'Admin budi telah menambahkan peminjaman baru dengan ID Peminjaman: 123'),
(251, 64, '2024-04-24 09:28:31', 'Admin budi telah menambahkan buku baru dengan judul tes'),
(252, 64, '2024-04-24 09:28:51', 'Admin budi telah menambahkan peminjaman baru dengan ID Peminjaman: 124'),
(253, 64, '2024-04-24 09:29:01', 'budi telah Menghapus data pengembalian buku dengan id peminjaman 124'),
(254, 64, '2024-04-24 09:34:14', 'Admin budi melakukan perubahan kategori dari \'Komik\' (ID: 63) menjadi \'Novel\' (ID: 58) pada buku dengan judul \'tes\''),
(255, 64, '2024-04-24 09:34:54', 'Admin budi telah menambahkan peminjaman baru dengan ID Peminjaman: 125'),
(256, 64, '2024-04-24 09:35:24', 'budi telah Menghapus data pengembalian buku dengan id peminjaman 122'),
(257, 64, '2024-04-24 09:36:44', 'Admin budi telah Menghapus buku dengan judul tes'),
(258, 64, '2024-04-24 09:36:58', 'Admin budi melakukan perubahan  pada buku dengan judul \'Keep Up with Us!\''),
(259, 64, '2024-04-24 09:37:29', 'Admin budi melakukan perubahan  pada buku dengan judul \'Keep Up with Us!\''),
(260, 64, '2024-04-24 09:37:42', 'Admin budi melakukan perubahan  pada buku dengan judul \'Gadis Kretek\''),
(261, 64, '2024-04-24 09:38:03', 'Admin budi melakukan perubahan  pada buku dengan judul \'Keep Up with Us!\''),
(262, 64, '2024-04-24 09:38:22', 'Admin budi melakukan perubahan  pada buku dengan judul \'Gadis Kretek\''),
(263, 64, '2024-04-24 09:40:39', 'Admin budi telah menambahkan peminjaman baru dengan ID Peminjaman: 126'),
(264, 64, '2024-04-24 09:40:47', 'budi telah Menghapus data pengembalian buku dengan id peminjaman 126'),
(265, 64, '2024-04-24 09:43:23', 'Admin budi melakukan perubahan  pada buku dengan judul \'tes\''),
(266, 64, '2024-04-24 09:44:18', 'Admin budi melakukan perubahan  pada buku dengan judul \'tes\''),
(267, 64, '2024-04-24 09:44:36', 'Admin budi telah menambahkan peminjaman baru dengan ID Peminjaman: 127'),
(268, 64, '2024-04-24 09:46:06', 'budi telah melakukan perubahan pada peminjaman buku dengan id peminjaman 125'),
(269, 64, '2024-04-24 09:54:26', 'budi telah melakukan perubahan pada peminjaman buku dengan id peminjaman 123'),
(270, 64, '2024-04-24 09:54:35', 'budi telah melakukan perubahan pada peminjaman buku dengan id peminjaman 125'),
(271, 64, '2024-04-24 09:55:32', 'budi telah melakukan perubahan pada peminjaman buku dengan id peminjaman 125'),
(272, 64, '2024-04-24 10:03:53', 'budi telah melakukan perubahan pada peminjaman buku dengan id peminjaman 123'),
(273, 64, '2024-04-24 10:06:31', 'budi telah menerima pengembalian buku dengan id peminjaman 123'),
(274, 64, '2024-04-24 10:13:10', 'Admin budi telah menambahkan peminjaman baru dengan ID Peminjaman: 128'),
(275, 64, '2024-04-24 10:13:20', 'budi telah menerima pengembalian buku dengan id peminjaman 128'),
(276, 66, '2024-04-24 10:14:50', 'Pustakawan pustakawan1 telah menambahkan peminjaman baru dengan ID Peminjaman: 129'),
(277, 66, '2024-04-24 10:30:34', 'Pustakawan pustakawan1 telah menambahkan peminjaman baru dengan ID Peminjaman: 130'),
(278, 66, '2024-04-24 10:30:44', 'pustakawan1 telah melakukan perubahan pada peminjaman buku dengan id peminjaman 130'),
(279, 66, '2024-04-24 10:57:17', 'Pustakawan pustakawan1 telah menerima pengembalian buku dengan id peminjaman 129'),
(280, 66, '2024-04-24 10:57:43', 'Pustakawan pustakawan1 telah menambahkan peminjaman baru dengan ID Peminjaman: 131'),
(281, 66, '2024-04-24 10:58:04', 'Pustakawan pustakawan1 telah menambahkan peminjaman baru dengan ID Peminjaman: 132'),
(282, 64, '2024-04-24 12:52:03', 'Admin budi telah menambahkan kategori baru dengan nama kategori Fiksi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_admin`
--
ALTER TABLE `t_admin`
  ADD PRIMARY KEY (`f_id`);

--
-- Indexes for table `t_anggota`
--
ALTER TABLE `t_anggota`
  ADD PRIMARY KEY (`f_id`);

--
-- Indexes for table `t_buku`
--
ALTER TABLE `t_buku`
  ADD PRIMARY KEY (`f_id`),
  ADD KEY `idkategori` (`f_idkategori`) USING BTREE;

--
-- Indexes for table `t_detailbuku`
--
ALTER TABLE `t_detailbuku`
  ADD PRIMARY KEY (`f_id`),
  ADD KEY `idbuku` (`f_idbuku`) USING BTREE;

--
-- Indexes for table `t_detailpeminjaman`
--
ALTER TABLE `t_detailpeminjaman`
  ADD PRIMARY KEY (`f_id`),
  ADD KEY `idpeminjaman` (`f_idpeminjaman`) USING BTREE,
  ADD KEY `iddetailbuku` (`f_iddetailbuku`) USING BTREE;

--
-- Indexes for table `t_kategori`
--
ALTER TABLE `t_kategori`
  ADD PRIMARY KEY (`f_id`),
  ADD UNIQUE KEY `t_buku_ibfk_kategori_1` (`f_kategori`) USING BTREE;

--
-- Indexes for table `t_peminjaman`
--
ALTER TABLE `t_peminjaman`
  ADD PRIMARY KEY (`f_id`),
  ADD KEY `idadmin` (`f_idadmin`) USING BTREE,
  ADD KEY `idanggota` (`f_idanggota`) USING BTREE;

--
-- Indexes for table `t_riwayat`
--
ALTER TABLE `t_riwayat`
  ADD PRIMARY KEY (`f_id`),
  ADD KEY `f_idadmin` (`f_idadmin`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_admin`
--
ALTER TABLE `t_admin`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `t_anggota`
--
ALTER TABLE `t_anggota`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `t_buku`
--
ALTER TABLE `t_buku`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `t_detailbuku`
--
ALTER TABLE `t_detailbuku`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT for table `t_detailpeminjaman`
--
ALTER TABLE `t_detailpeminjaman`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `t_kategori`
--
ALTER TABLE `t_kategori`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `t_peminjaman`
--
ALTER TABLE `t_peminjaman`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `t_riwayat`
--
ALTER TABLE `t_riwayat`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_buku`
--
ALTER TABLE `t_buku`
  ADD CONSTRAINT `t_buku_ibfk_1` FOREIGN KEY (`f_idkategori`) REFERENCES `t_kategori` (`f_id`);

--
-- Constraints for table `t_detailbuku`
--
ALTER TABLE `t_detailbuku`
  ADD CONSTRAINT `t_detailbuku_ibfk_1` FOREIGN KEY (`f_idbuku`) REFERENCES `t_buku` (`f_id`);

--
-- Constraints for table `t_detailpeminjaman`
--
ALTER TABLE `t_detailpeminjaman`
  ADD CONSTRAINT `t_detailpeminjaman_ibfk_1` FOREIGN KEY (`f_idpeminjaman`) REFERENCES `t_peminjaman` (`f_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_detailpeminjaman_ibfk_2` FOREIGN KEY (`f_iddetailbuku`) REFERENCES `t_detailbuku` (`f_id`) ON UPDATE CASCADE;

--
-- Constraints for table `t_peminjaman`
--
ALTER TABLE `t_peminjaman`
  ADD CONSTRAINT `t_peminjaman_ibfk_1` FOREIGN KEY (`f_idanggota`) REFERENCES `t_anggota` (`f_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_peminjaman_ibfk_2` FOREIGN KEY (`f_idadmin`) REFERENCES `t_admin` (`f_id`);

--
-- Constraints for table `t_riwayat`
--
ALTER TABLE `t_riwayat`
  ADD CONSTRAINT `t_riwayat_ibfk_1` FOREIGN KEY (`f_idadmin`) REFERENCES `t_admin` (`f_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
