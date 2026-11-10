-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2026 at 09:59 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `irmaa_12rpl1_paket1`
--

-- --------------------------------------------------------

--
-- Table structure for table `alat`
--

CREATE TABLE `alat` (
  `id_alat` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `nama_alat` varchar(100) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `kondisi` varchar(50) NOT NULL DEFAULT 'Baik'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alat`
--

INSERT INTO `alat` (`id_alat`, `id_kategori`, `nama_alat`, `kategori`, `stok`, `kondisi`) VALUES
(1, 1, 'Laptop', 'Umum', 10, 'Baik'),
(2, 1, 'Proyektor', 'Umum', 3, 'Baik'),
(3, 2, 'Bola Basket', 'Umum', 16, 'Baik'),
(4, 3, 'Kamera', 'Umum', 12, 'Baik'),
(5, 5, 'Keyboard', 'Umum', 12, 'Baik'),
(8, 1, 'lampu', 'Umum', 21, 'Baik'),
(11, 2, 'bola sepak', NULL, 32, 'Baik'),
(13, 4, 'sapu ', NULL, 12, 'Baik');

-- --------------------------------------------------------

--
-- Table structure for table `detail_peminjaman`
--

CREATE TABLE `detail_peminjaman` (
  `id_detail` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `nama_peminjam` varchar(100) DEFAULT NULL,
  `nama_alat` varchar(100) DEFAULT NULL,
  `id_alat` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_peminjaman`
--

INSERT INTO `detail_peminjaman` (`id_detail`, `id_peminjaman`, `nama_peminjam`, `nama_alat`, `id_alat`, `jumlah`) VALUES
(1, 1, 'tasya julyanti', 'Laptop', 1, 2),
(2, 2, 'aina talita zahran', 'Proyektor', 2, 1),
(3, 3, 'tasya julyanti', 'Bola Basket', 3, 2),
(4, 4, 'aina talita zahran', 'Kamera', 4, 1),
(5, 5, 'tasya julyanti', 'Keyboard', 5, 3),
(6, 8, 'tasya julyanti', 'Laptop', 1, 2),
(7, 9, 'tasya julyanti', 'Keyboard', 5, 3),
(8, 12, 'aina talita zahran', 'Laptop', 1, 2),
(9, 13, 'aina talita zahran', 'Laptop', 1, 2),
(10, 14, 'tasya julyanti', 'Laptop', 1, 2),
(11, 15, 'tasya julyanti', 'Bola Basket', 3, 1),
(12, 16, 'tasya julyanti', 'Kamera', 4, 2),
(13, 17, 'tasya julyanti', 'Laptop', 1, 4),
(14, 18, 'tasya julyanti', 'Kamera', 4, 1),
(15, 19, 'aina talita ', 'Proyektor', 2, 2),
(16, 20, 'aina talita ', 'Kamera', 4, 1),
(17, 21, 'aina talita ', 'Bola Basket', 3, 2),
(18, 25, 'tasya julyanti', 'lampu', 8, 1),
(19, 26, 'tasya julyanti', 'lampu', 8, 1),
(20, 27, 'aina talita ', 'lampu', 8, 1),
(21, 28, 'aina talita ', 'Bola Basket', 3, 1),
(25, 32, 'a', 'Bola Basket', 3, 1),
(26, 33, 'tasya julyanti', 'Bola Basket', 3, 1),
(27, 34, 'a', 'bola sepak', 11, 1),
(28, 35, 'tasya julyanti', 'Proyektor', 2, 1),
(29, 36, 'tasya julyanti', 'Bola Basket', 3, 1),
(33, 40, 'aina talita ', 'bola sepak', 11, 1),
(34, 41, 'a', 'bola sepak', 11, 4),
(35, 42, 'a', 'bola sepak', 11, 10),
(36, 43, 'a', 'bola sepak', 11, 2),
(37, 44, 'aina talita ', 'sapu ', 13, 2),
(39, 46, 'tasya julyanti', 'bola sepak', 11, 2),
(40, 47, 'tasya julyanti', 'bola sepak', 11, 2),
(41, 48, 'mama', 'bola sepak', 11, 3),
(42, 49, 'mama', 'Bola Basket', 3, 2),
(43, 50, 'mama', 'Bola Basket', 3, 3),
(44, 51, 'mama', 'Proyektor', 2, 2),
(48, 55, 'tasya julyanti', 'bola sepak', 11, 1),
(49, 56, 'tasya julyanti', 'Bola Basket', 3, 1),
(50, 57, 'tasya julyanti', 'bola sepak', 11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Elektronik'),
(2, 'Olahraga'),
(3, 'Multimedia'),
(4, 'Kebersihan'),
(5, 'Praktik RPL');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `waktu` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_user`, `aktivitas`, `waktu`) VALUES
(1, 4, 'Mengajukan peminjaman', '2026-09-14 08:40:03'),
(3, 2, 'Menyetujui peminjaman', '2026-09-14 08:40:03'),
(4, 2, 'Memproses pengembalian', '2026-09-14 08:40:03'),
(5, 4, 'Mengembalikan alat', '2026-09-14 08:40:03'),
(6, 1, 'User Irma Diana berhasil login', '2026-09-23 09:15:23'),
(7, 2, 'User ellen junithan berhasil login', '2026-09-23 09:18:45'),
(8, 4, 'User tasya julyanti berhasil login', '2026-09-23 09:23:27'),
(9, 2, 'User ellen junithan berhasil login', '2026-09-23 09:26:42'),
(10, 1, 'User Irma Diana berhasil login', '2026-09-23 09:31:23'),
(11, 2, 'User ellen junithan berhasil login', '2026-09-23 09:41:23'),
(12, 2, 'User ellen junithan berhasil login', '2026-09-23 11:14:37'),
(13, 4, 'User tasya julyanti berhasil login', '2026-09-23 14:25:01'),
(14, 2, 'User ellen junithan berhasil login', '2026-09-23 14:56:21'),
(15, 4, 'User tasya julyanti berhasil login', '2026-09-23 14:57:07'),
(16, 4, 'User tasya julyanti berhasil login', '2026-09-23 15:08:18'),
(17, 4, 'User tasya julyanti berhasil login', '2026-09-23 17:21:58'),
(18, 4, 'Mengajukan peminjaman lampu', '2026-09-23 17:25:07'),
(19, 2, 'User ellen junithan berhasil login', '2026-09-23 17:26:55'),
(20, 2, 'Memproses pengembalian Kamera (Denda: Rp 0)', '2026-09-23 17:31:29'),
(21, 2, 'Memproses pengembalian Proyektor (Kondisi: Baik, Denda: Rp 0)', '2026-09-23 17:37:26'),
(22, 1, 'User Irma Diana berhasil login', '2026-09-23 17:41:23'),
(23, 4, 'User tasya julyanti berhasil login', '2026-09-23 17:42:17'),
(24, 1, 'User Irma Diana berhasil login', '2026-09-23 17:47:04'),
(25, 4, 'User tasya julyanti berhasil login', '2026-09-23 17:49:34'),
(26, 4, 'Mengajukan peminjaman lampu', '2026-09-23 17:52:04'),
(27, 2, 'User ellen junithan berhasil login', '2026-09-23 17:52:15'),
(28, 2, 'Menyetujui peminjaman ID 26 (lampu, 1 unit) - Stok berkurang', '2026-09-23 17:52:28'),
(29, 4, 'User tasya julyanti berhasil login', '2026-09-23 17:55:13'),
(30, 4, 'Mengembalikan alat lampu', '2026-09-23 17:55:39'),
(31, 2, 'User ellen junithan berhasil login', '2026-09-23 17:55:56'),
(32, 2, 'Memproses pengembalian Laptop (Kondisi: Baik, Denda: Rp 0)', '2026-09-23 17:56:22'),
(35, 3, 'User alisa khairan berhasil login', '2026-09-24 07:07:32'),
(36, 3, 'Menyetujui peminjaman ID 27 (lampu, 1 unit) - Stok berkurang', '2026-09-24 07:07:53'),
(37, 2, 'User ellen junithan berhasil login', '2026-09-24 07:08:13'),
(40, 3, 'User alisa khairan berhasil login', '2026-09-24 07:09:28'),
(43, 3, 'User alisa khairan berhasil login', '2026-09-24 07:10:52'),
(44, 3, 'Menyetujui peminjaman ID 28 (Bola Basket, 1 unit) - Stok berkurang', '2026-09-24 07:11:02'),
(46, 3, 'User alisa khairan berhasil login', '2026-09-24 07:12:43'),
(47, 3, 'Memproses pengembalian Bola Basket (Kondisi: Terlambat, Denda: Rp 10.000)', '2026-09-24 07:13:31'),
(49, 4, 'User tasya julyanti berhasil login', '2026-09-24 07:26:13'),
(50, 2, 'User ellen junithan berhasil login', '2026-09-24 07:26:25'),
(51, 1, 'User Irma Diana berhasil login', '2026-09-24 07:27:37'),
(52, 2, 'User ellen junithan berhasil login', '2026-09-24 07:28:30'),
(53, 1, 'User Irma Diana berhasil login', '2026-09-24 07:32:01'),
(54, 2, 'User ellen junithan berhasil login', '2026-09-24 07:34:18'),
(55, 1, 'User Irma Diana berhasil login', '2026-09-24 07:39:20'),
(56, 4, 'User tasya julyanti berhasil login', '2026-09-24 07:39:55'),
(57, 1, 'User Irma Diana berhasil login', '2026-09-24 07:40:45'),
(58, 2, 'User ellen junithan berhasil login', '2026-09-24 07:41:40'),
(59, 1, 'User Irma Diana berhasil login', '2026-09-24 07:43:50'),
(60, 2, 'User ellen junithan berhasil login', '2026-09-24 07:44:09'),
(61, 4, 'User tasya julyanti berhasil login', '2026-09-24 07:54:51'),
(62, 1, 'User Irma Diana berhasil login', '2026-09-24 08:24:42'),
(63, 1, 'User Irma Diana berhasil login', '2026-09-24 08:36:58'),
(64, 2, 'User ellen junithan berhasil login', '2026-09-24 08:37:33'),
(65, 1, 'User Irma Diana berhasil login', '2026-09-24 08:40:17'),
(68, 1, 'User Irma Diana berhasil login', '2026-09-24 08:42:11'),
(69, 2, 'User ellen junithan berhasil login', '2026-09-24 08:49:13'),
(70, 1, 'User Irma Diana berhasil login', '2026-09-24 09:10:34'),
(73, 1, 'User Irma Diana berhasil login', '2026-09-24 09:15:35'),
(74, 1, 'User Irma Diana berhasil login', '2026-09-24 09:16:11'),
(75, 2, 'User ellen junithan berhasil login', '2026-09-24 09:16:36'),
(76, 8, 'User afnan sabila berhasil login', '2026-09-24 14:52:24'),
(79, 2, 'User ellen junithan berhasil login', '2026-09-24 14:54:53'),
(80, 1, 'User Irma Diana berhasil login', '2026-09-24 15:12:46'),
(81, 12, 'User a berhasil login', '2026-09-24 15:22:09'),
(82, 12, 'Mengajukan peminjaman Bola Basket', '2026-09-24 15:22:26'),
(83, 3, 'User alisa khairan berhasil login', '2026-09-24 15:22:42'),
(84, 12, 'User a berhasil login', '2026-09-24 15:23:08'),
(85, 1, 'User Irma Diana berhasil login', '2026-09-24 15:23:26'),
(86, 1, 'User Irma Diana berhasil login', '2026-09-24 15:24:17'),
(87, 1, 'User Irma Diana berhasil login', '2026-09-25 12:32:41'),
(88, 12, 'User a berhasil login', '2026-09-25 12:33:40'),
(89, 1, 'User Irma Diana berhasil login', '2026-09-25 12:33:56'),
(90, 1, 'User Irma Diana berhasil login', '2026-09-25 12:48:43'),
(91, 2, 'User ellen junithan berhasil login', '2026-09-25 12:49:48'),
(92, 2, 'User ellen junithan berhasil login', '2026-09-25 13:03:56'),
(93, 2, 'User ellen junithan berhasil login', '2026-09-25 13:05:34'),
(94, 4, 'User tasya julyanti berhasil login', '2026-09-25 13:13:43'),
(95, 4, 'Mengajukan peminjaman Bola Basket', '2026-09-25 13:14:05'),
(96, 2, 'User ellen junithan berhasil login', '2026-09-25 13:14:16'),
(97, 4, 'User tasya julyanti berhasil login', '2026-09-25 13:15:01'),
(98, 4, 'Mengembalikan alat Bola Basket', '2026-09-25 13:23:59'),
(99, 12, 'User a berhasil login', '2026-09-25 15:32:08'),
(100, 12, 'Mengembalikan alat Bola Basket', '2026-09-25 15:32:21'),
(101, 2, 'User ellen junithan berhasil login', '2026-09-25 15:32:52'),
(102, 12, 'User a berhasil login', '2026-09-25 15:35:58'),
(103, 12, 'Mengajukan peminjaman bola sepak', '2026-09-25 15:36:14'),
(104, 2, 'User ellen junithan berhasil login', '2026-09-25 15:36:23'),
(105, 12, 'User a berhasil login', '2026-09-25 15:36:48'),
(106, 4, 'User tasya julyanti berhasil login', '2026-09-25 15:37:35'),
(107, 2, 'User ellen junithan berhasil login', '2026-09-25 15:41:16'),
(108, 4, 'User tasya julyanti berhasil login', '2026-09-25 15:44:07'),
(109, 4, 'Mengembalikan alat lampu', '2026-09-25 15:44:22'),
(110, 2, 'User ellen junithan berhasil login', '2026-09-26 17:03:55'),
(111, 4, 'User tasya julyanti berhasil login', '2026-09-26 17:06:04'),
(112, 4, 'Mengajukan peminjaman Proyektor', '2026-09-26 17:06:24'),
(113, 2, 'User ellen junithan berhasil login', '2026-09-26 17:06:32'),
(114, 4, 'User tasya julyanti berhasil login', '2026-09-26 17:06:57'),
(115, 4, 'Mengajukan peminjaman Bola Basket', '2026-09-26 17:15:14'),
(116, 2, 'User ellen junithan berhasil login', '2026-09-26 17:15:29'),
(117, 4, 'User tasya julyanti berhasil login', '2026-09-26 17:15:49'),
(118, 2, 'User ellen junithan berhasil login', '2026-09-26 17:16:28'),
(119, 4, 'User tasya julyanti berhasil login', '2026-09-26 17:25:03'),
(120, 2, 'User ellen junithan berhasil login', '2026-09-26 17:29:12'),
(121, 12, 'User a berhasil login', '2026-09-26 17:37:16'),
(122, 12, 'Mengajukan peminjaman bola sepak', '2026-09-26 17:37:36'),
(123, 2, 'User ellen junithan berhasil login', '2026-09-26 17:37:46'),
(124, 12, 'User a berhasil login', '2026-09-26 17:38:10'),
(125, 2, 'User ellen junithan berhasil login', '2026-09-26 17:38:39'),
(126, 12, 'User a berhasil login', '2026-09-26 17:39:19'),
(127, 2, 'User ellen junithan berhasil login', '2026-09-26 17:41:12'),
(128, 12, 'User a berhasil login', '2026-09-26 17:46:49'),
(129, 12, 'Mengajukan peminjaman bola sepak', '2026-09-26 17:49:07'),
(130, 2, 'User ellen junithan berhasil login', '2026-09-26 17:49:15'),
(131, 12, 'User a berhasil login', '2026-09-26 17:49:35'),
(132, 2, 'User ellen junithan berhasil login', '2026-09-26 17:49:58'),
(133, 12, 'User a berhasil login', '2026-09-26 17:50:43'),
(134, 12, 'User a berhasil login', '2026-09-26 18:12:14'),
(135, 12, 'Mengajukan peminjaman bola sepak', '2026-09-26 18:12:26'),
(136, 2, 'User ellen junithan berhasil login', '2026-09-26 18:12:37'),
(137, 12, 'User a berhasil login', '2026-09-26 18:13:01'),
(138, 2, 'User ellen junithan berhasil login', '2026-09-26 18:13:20'),
(139, 12, 'User a berhasil login', '2026-09-26 18:14:06'),
(140, 2, 'User ellen junithan berhasil login', '2026-09-26 18:14:33'),
(141, 12, 'User a berhasil login', '2026-09-26 18:15:10'),
(142, 1, 'User Irma Diana berhasil login', '2026-09-26 18:31:43'),
(143, 1, 'User Irma Diana berhasil login', '2026-09-26 18:54:27'),
(144, 2, 'User ellen junithan berhasil login', '2026-09-26 18:54:45'),
(145, 2, 'User ellen junithan berhasil login', '2026-09-26 18:56:24'),
(146, 1, 'User Irma Diana berhasil login', '2026-09-26 18:56:36'),
(147, 2, 'User ellen junithan berhasil login', '2026-09-26 19:10:14'),
(148, 1, 'User Irma Diana berhasil login', '2026-09-26 19:10:53'),
(149, 2, 'User ellen junithan berhasil login', '2026-09-26 19:15:16'),
(150, 2, 'User ellen junithan berhasil login', '2026-09-26 19:35:52'),
(151, 1, 'User Irma Diana berhasil login', '2026-09-26 19:37:51'),
(152, 1, 'User Irma Diana berhasil login', '2026-09-26 20:07:17'),
(153, 2, 'User ellen junithan berhasil login', '2026-09-26 20:07:50'),
(154, 5, 'User aina talita  berhasil login', '2026-09-27 19:21:52'),
(155, 5, 'Mengajukan peminjaman bola sepak', '2026-09-27 19:22:20'),
(156, 2, 'User ellen junithan berhasil login', '2026-09-27 19:22:37'),
(157, 5, 'User aina talita  berhasil login', '2026-09-27 19:23:15'),
(158, 2, 'User ellen junithan berhasil login', '2026-09-27 19:46:09'),
(159, 5, 'User aina talita  berhasil login', '2026-09-27 19:47:00'),
(160, 2, 'User ellen junithan berhasil login', '2026-09-27 19:47:35'),
(161, 1, 'User Irma Diana berhasil login', '2026-09-27 19:48:02'),
(162, 1, 'User Irma Diana berhasil login', '2026-09-27 20:02:15'),
(163, 1, 'User Irma Diana berhasil login', '2026-09-27 20:03:00'),
(164, 2, 'User ellen junithan berhasil login', '2026-09-27 20:04:37'),
(165, 4, 'User tasya julyanti berhasil login', '2026-09-27 20:05:08'),
(166, 1, 'User Irma Diana berhasil login', '2026-09-28 08:42:38'),
(167, 2, 'User ellen junithan berhasil login', '2026-09-28 08:50:24'),
(168, 1, 'User Irma Diana berhasil login', '2026-09-28 08:52:30'),
(169, 1, 'User Irma Diana berhasil login', '2026-09-28 09:07:40'),
(170, 2, 'User ellen junithan berhasil login', '2026-09-28 09:18:56'),
(171, 12, 'User a berhasil login', '2026-09-28 09:20:55'),
(172, 12, 'Mengajukan peminjaman bola sepak', '2026-09-28 09:21:31'),
(173, 12, 'Mengajukan peminjaman bola sepak', '2026-09-28 09:25:42'),
(174, 12, 'User a berhasil login', '2026-09-28 09:40:24'),
(175, 12, 'Mengajukan peminjaman bola sepak', '2026-09-28 09:40:42'),
(176, 2, 'User ellen junithan berhasil login', '2026-09-28 09:40:50'),
(177, 12, 'User a berhasil login', '2026-09-28 09:41:33'),
(178, 2, 'User ellen junithan berhasil login', '2026-09-28 09:42:18'),
(179, 1, 'User Irma Diana berhasil login', '2026-09-28 11:20:25'),
(180, 5, 'User aina talita  berhasil login', '2026-09-28 13:05:08'),
(181, 1, 'User Irma Diana berhasil login', '2026-09-28 13:05:34'),
(182, 5, 'User aina talita  berhasil login', '2026-09-28 13:06:15'),
(183, 5, 'Mengajukan peminjaman sapu ', '2026-09-28 13:06:45'),
(184, 3, 'User alisa khairan berhasil login', '2026-09-28 13:07:00'),
(185, 4, 'User tasya julyanti berhasil login', '2026-09-28 13:07:27'),
(186, 4, 'Mengajukan peminjaman Keyboard', '2026-09-28 13:07:43'),
(187, 2, 'User ellen junithan berhasil login', '2026-09-28 13:07:59'),
(188, 4, 'User tasya julyanti berhasil login', '2026-09-28 13:12:21'),
(189, 2, 'User ellen junithan berhasil login', '2026-09-28 13:22:58'),
(190, 4, 'User tasya julyanti berhasil login', '2026-09-28 13:23:40'),
(191, 2, 'User ellen junithan berhasil login', '2026-09-28 13:23:57'),
(192, 2, 'User ellen junithan berhasil login', '2026-09-28 17:55:45'),
(193, 2, 'User ellen junithan berhasil login', '2026-09-28 17:56:35'),
(194, 1, 'User Irma Diana berhasil login', '2026-09-28 17:56:50'),
(195, 2, 'User ellen junithan berhasil login', '2026-09-28 18:03:46'),
(196, 5, 'User aina talita  berhasil login', '2026-09-28 18:08:11'),
(197, 2, 'User ellen junithan berhasil login', '2026-09-28 18:08:47'),
(198, 5, 'User aina talita  berhasil login', '2026-09-28 18:14:33'),
(199, 2, 'User ellen junithan berhasil login', '2026-09-28 18:16:41'),
(200, 5, 'User aina talita  berhasil login', '2026-09-28 18:24:57'),
(201, 4, 'User tasya julyanti berhasil login', '2026-09-28 18:27:00'),
(202, 2, 'User ellen junithan berhasil login', '2026-09-28 18:40:20'),
(203, 1, 'User Irma Diana berhasil login', '2026-09-28 18:40:47'),
(204, 2, 'User ellen junithan berhasil login', '2026-09-28 18:44:27'),
(205, 1, 'User Irma Diana berhasil login', '2026-09-28 19:08:47'),
(206, 2, 'User ellen junithan berhasil login', '2026-09-28 19:09:08'),
(207, 5, 'User aina talita  berhasil login', '2026-09-28 19:10:23'),
(208, 2, 'User ellen junithan berhasil login', '2026-09-28 19:21:30'),
(209, 4, 'User tasya julyanti berhasil login', '2026-09-29 11:33:14'),
(210, 4, 'Mengajukan peminjaman bola sepak', '2026-09-29 11:34:03'),
(211, 2, 'User ellen junithan berhasil login', '2026-09-29 11:34:11'),
(212, 4, 'User tasya julyanti berhasil login', '2026-09-29 11:34:33'),
(213, 2, 'User ellen junithan berhasil login', '2026-09-29 11:35:11'),
(214, 4, 'User tasya julyanti berhasil login', '2026-09-29 11:36:03'),
(215, 2, 'User ellen junithan berhasil login', '2026-09-29 11:36:23'),
(216, 2, 'User ellen junithan berhasil login', '2026-09-30 10:20:36'),
(217, 1, 'User Irma Diana berhasil login', '2026-09-30 10:22:05'),
(218, 1, 'User Irma Diana berhasil login', '2026-09-30 10:56:18'),
(219, 4, 'User tasya julyanti berhasil login', '2026-09-30 10:57:22'),
(220, 4, 'Mengajukan peminjaman bola sepak', '2026-09-30 10:58:15'),
(221, 2, 'User ellen junithan berhasil login', '2026-09-30 10:58:57'),
(222, 4, 'User tasya julyanti berhasil login', '2026-09-30 10:59:26'),
(223, 2, 'User ellen junithan berhasil login', '2026-09-30 11:00:22'),
(224, 4, 'User tasya julyanti berhasil login', '2026-09-30 11:01:22'),
(225, 2, 'User ellen junithan berhasil login', '2026-09-30 11:02:30'),
(226, 1, 'User Irma Diana berhasil login', '2026-09-30 11:03:50'),
(227, 14, 'User mama berhasil login', '2026-09-30 16:43:19'),
(228, 14, 'User mama berhasil login', '2026-09-30 16:49:10'),
(229, 14, 'Mengajukan peminjaman bola sepak', '2026-09-30 16:49:35'),
(230, 1, 'User Irma Diana berhasil login', '2026-09-30 16:57:58'),
(231, 2, 'User ellen junithan berhasil login', '2026-09-30 17:06:54'),
(232, 14, 'User mama berhasil login', '2026-09-30 17:07:12'),
(233, 2, 'User ellen junithan berhasil login', '2026-09-30 17:09:52'),
(234, 14, 'User mama berhasil login', '2026-09-30 17:17:53'),
(235, 14, 'Mengajukan peminjaman Bola Basket', '2026-09-30 17:18:18'),
(236, 2, 'User ellen junithan berhasil login', '2026-09-30 17:18:25'),
(237, 1, 'User Irma Diana berhasil login', '2026-09-30 17:37:10'),
(238, 14, 'User mama berhasil login', '2026-09-30 17:41:55'),
(239, 14, 'Mengajukan peminjaman Bola Basket', '2026-09-30 17:42:13'),
(240, 2, 'User ellen junithan berhasil login', '2026-09-30 17:42:28'),
(241, 14, 'User mama berhasil login', '2026-09-30 17:42:55'),
(242, 2, 'User ellen junithan berhasil login', '2026-09-30 17:48:21'),
(243, 14, 'User mama berhasil login', '2026-09-30 17:55:31'),
(244, 14, 'Mengajukan peminjaman Proyektor', '2026-09-30 17:56:00'),
(245, 2, 'User ellen junithan berhasil login', '2026-09-30 17:56:06'),
(246, 14, 'User mama berhasil login', '2026-09-30 17:59:55'),
(247, 1, 'User Irma Diana berhasil login', '2026-09-30 18:16:36'),
(248, 2, 'User ellen junithan berhasil login', '2026-09-30 18:18:55'),
(249, 2, 'User ellen junithan berhasil login', '2026-10-01 10:20:32'),
(250, 14, 'User mama berhasil login', '2026-10-01 13:14:45'),
(251, 14, 'Mengajukan peminjaman Proyektor', '2026-10-01 13:15:10'),
(252, 2, 'User ellen junithan berhasil login', '2026-10-01 13:15:16'),
(253, 14, 'User mama berhasil login', '2026-10-01 13:15:33'),
(254, 2, 'User ellen junithan berhasil login', '2026-10-01 13:16:04'),
(255, 14, 'User mama berhasil login', '2026-10-01 13:24:53'),
(256, 14, 'Mengajukan peminjaman Proyektor', '2026-10-01 13:25:16'),
(257, 2, 'User ellen junithan berhasil login', '2026-10-01 13:25:24'),
(258, 14, 'User mama berhasil login', '2026-10-01 13:25:45'),
(259, 2, 'User ellen junithan berhasil login', '2026-10-01 13:26:24'),
(260, 2, 'User ellen junithan berhasil login', '2026-10-05 10:28:41'),
(261, 14, 'User mama berhasil login', '2026-10-06 07:01:54'),
(262, 2, 'User ellen junithan berhasil login', '2026-10-06 07:02:29'),
(263, 4, 'User tasya julyanti berhasil login', '2026-10-09 17:34:36'),
(264, 4, 'User tasya julyanti berhasil login', '2026-10-10 12:30:02'),
(265, 2, 'User ellen junithan berhasil login', '2026-10-10 12:35:13'),
(266, 1, 'User Irma Diana berhasil login', '2026-10-10 13:18:02'),
(267, 1, 'User Irma Diana berhasil login', '2026-10-10 13:38:10'),
(268, 1, 'User Irma Diana berhasil login', '2026-10-18 22:02:36'),
(269, 2, 'User ellen junithan berhasil login', '2026-10-18 22:08:55'),
(270, 1, 'User Irma Diana berhasil login', '2026-10-18 22:20:07'),
(271, 14, 'User mama berhasil login', '2026-10-18 22:20:31'),
(272, 1, 'User Irma Diana berhasil login', '2026-10-19 05:40:46'),
(273, 2, 'User ellen junithan berhasil login', '2026-10-19 05:42:39'),
(274, 4, 'User tasya julyanti berhasil login', '2026-10-19 05:43:19'),
(275, 2, 'User ellen junithan berhasil login', '2026-10-19 05:46:35'),
(276, 1, 'User Irma Diana berhasil login', '2026-10-19 05:46:48'),
(277, 1, 'User Irma Diana berhasil login', '2026-10-19 05:48:40'),
(278, 1, 'User Irma Diana berhasil login', '2026-10-19 08:14:27'),
(279, 1, 'User Irma Diana berhasil login', '2026-10-19 09:40:52'),
(280, 2, 'User ellen junithan berhasil login', '2026-10-19 09:42:32'),
(281, 1, 'User Irma Diana berhasil login', '2026-10-20 16:36:52'),
(282, 1, 'User Irma Diana berhasil login', '2026-10-20 16:40:31'),
(283, 2, 'User ellen junithan berhasil login', '2026-10-20 17:06:04'),
(284, 1, 'User Irma Diana berhasil login', '2026-10-20 17:11:10'),
(285, 1, 'User Irma Diana berhasil login', '2026-10-20 17:22:58'),
(286, 1, 'User Irma Diana berhasil login', '2026-10-22 18:45:51'),
(287, 1, 'User Irma Diana berhasil login', '2026-10-22 19:30:05'),
(288, 1, 'User Irma Diana berhasil login', '2026-10-23 13:50:45'),
(289, 1, 'User Irma Diana berhasil login', '2026-10-26 09:31:50'),
(290, 1, 'User Irma Diana berhasil login', '2026-10-26 09:38:32'),
(291, 2, 'User ellen junithan berhasil login', '2026-10-26 10:08:32'),
(292, 4, 'User tasya julyanti berhasil login', '2026-10-26 10:10:41'),
(293, 4, 'User tasya julyanti berhasil login', '2026-10-26 10:30:24'),
(294, 2, 'User ellen junithan berhasil login', '2026-10-26 10:30:42'),
(295, 4, 'User tasya julyanti berhasil login', '2026-10-26 10:31:29'),
(296, 4, 'Mengajukan peminjaman bola sepak', '2026-10-26 10:31:46'),
(297, 2, 'User ellen junithan berhasil login', '2026-10-26 10:31:52'),
(298, 4, 'User tasya julyanti berhasil login', '2026-10-26 10:36:03'),
(299, 2, 'User ellen junithan berhasil login', '2026-10-26 10:36:38'),
(300, 1, 'User Irma Diana berhasil login', '2026-10-26 14:13:02'),
(301, 4, 'User tasya julyanti berhasil login', '2026-10-26 14:37:31'),
(302, 2, 'User ellen junithan berhasil login', '2026-10-26 14:38:34'),
(303, 1, 'User Irma Diana berhasil login', '2026-10-27 08:40:23'),
(304, 1, 'User Irma Diana berhasil login', '2026-10-28 07:25:03'),
(305, 1, 'User Irma Diana berhasil login', '2026-10-29 11:28:05'),
(306, 1, 'User Irma Diana berhasil login', '2026-10-29 16:13:43'),
(307, 2, 'User ellen junithan berhasil login', '2026-10-30 08:08:28'),
(308, 2, 'User ellen junithan berhasil login', '2026-10-30 08:15:18'),
(309, 4, 'User tasya julyanti berhasil login', '2026-10-30 08:15:39'),
(310, 4, 'Mengajukan peminjaman bola sepak', '2026-10-30 08:16:03'),
(311, 2, 'User ellen junithan berhasil login', '2026-10-30 08:16:20'),
(312, 4, 'User tasya julyanti berhasil login', '2026-10-30 08:18:05'),
(313, 2, 'User ellen junithan berhasil login', '2026-10-30 08:18:43'),
(314, 4, 'User tasya julyanti berhasil login', '2026-10-30 08:47:29'),
(315, 1, 'User Irma Diana berhasil login', '2026-10-30 14:57:54'),
(316, 1, 'User Irma Diana berhasil login', '2026-10-30 15:40:41'),
(317, 1, 'User Irma Diana berhasil login', '2026-11-10 14:52:47'),
(318, 2, 'User ellen junithan berhasil login', '2026-11-10 14:53:15'),
(319, 4, 'User tasya julyanti berhasil login', '2026-11-10 14:53:32'),
(320, 4, 'User tasya julyanti berhasil login', '2026-11-10 14:53:55'),
(321, 5, 'User aina talita  berhasil login', '2026-11-10 14:54:17'),
(322, 2, 'User ellen junithan berhasil login', '2026-11-10 14:55:01'),
(323, 4, 'User tasya julyanti berhasil login', '2026-11-10 14:55:22'),
(324, 2, 'User ellen junithan berhasil login', '2026-11-10 15:02:08'),
(325, 1, 'User Irma Diana berhasil login', '2026-11-10 15:02:25'),
(326, 4, 'User tasya julyanti berhasil login', '2026-11-10 15:08:34'),
(327, 4, 'Mengajukan peminjaman Bola Basket', '2026-11-10 15:09:44'),
(328, 4, 'Mengajukan peminjaman bola sepak', '2026-11-10 15:14:43'),
(329, 1, 'User Irma Diana berhasil login', '2026-11-10 15:55:02');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_peminjam` varchar(100) DEFAULT NULL,
  `nama_alat` varchar(100) DEFAULT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_rencana_kembali` date NOT NULL,
  `status` enum('Menunggu','Disetujui','Ditolak','Dikembalikan') DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_user`, `nama_peminjam`, `nama_alat`, `tanggal_pinjam`, `tanggal_rencana_kembali`, `status`) VALUES
(1, 4, 'tasya julyanti', 'Laptop', '2026-09-10', '2026-09-12', 'Dikembalikan'),
(2, 5, 'aina talita zahran', 'Proyektor', '2026-09-11', '2026-09-13', 'Disetujui'),
(3, 4, 'tasya julyanti', 'Bola Basket', '2026-09-12', '2026-09-14', 'Ditolak'),
(4, 5, 'aina talita zahran', 'Kamera', '2026-09-13', '2026-09-15', 'Disetujui'),
(5, 4, 'tasya julyanti', 'Keyboard', '2026-09-14', '2026-09-16', 'Dikembalikan'),
(8, 4, 'tasya julyanti', 'Laptop', '2026-09-15', '2026-09-16', 'Dikembalikan'),
(9, 4, 'tasya julyanti', 'Keyboard', '2026-09-14', '2026-09-14', 'Disetujui'),
(12, 5, 'aina talita zahran', 'Laptop', '2026-09-16', '2026-09-16', 'Ditolak'),
(13, 5, 'aina talita zahran', 'Laptop', '2026-09-16', '2026-09-16', 'Dikembalikan'),
(14, 4, 'tasya julyanti', 'Laptop', '2026-09-15', '2026-09-15', 'Disetujui'),
(15, 4, 'tasya julyanti', 'Bola Basket', '2026-09-21', '2026-09-21', 'Disetujui'),
(16, 4, 'tasya julyanti', 'Kamera', '2026-09-21', '2026-09-21', 'Disetujui'),
(17, 4, 'tasya julyanti', 'Laptop', '2026-09-21', '2026-09-21', 'Dikembalikan'),
(18, 4, 'tasya julyanti', 'Kamera', '2026-09-21', '2026-09-21', 'Dikembalikan'),
(19, 5, 'aina talita ', 'Proyektor', '2026-09-21', '2026-09-21', 'Dikembalikan'),
(20, 4, 'tasya julyanti', 'Kamera', '2026-09-21', '2026-09-21', 'Dikembalikan'),
(21, 5, 'aina talita ', 'Bola Basket', '2026-09-22', '2026-09-22', 'Dikembalikan'),
(22, 4, 'tasya julyanti', 'lampu', '2026-09-23', '2026-09-23', 'Dikembalikan'),
(24, 4, 'tasya julyanti', 'lampu', '2026-09-23', '2026-09-23', 'Dikembalikan'),
(25, 4, 'tasya julyanti', 'lampu', '2026-09-23', '2026-09-24', 'Ditolak'),
(26, 4, 'tasya julyanti', 'lampu', '2026-09-23', '2026-09-24', 'Dikembalikan'),
(27, 5, 'aina talita ', 'lampu', '2026-09-24', '2026-09-25', 'Dikembalikan'),
(28, 5, 'aina talita ', 'Bola Basket', '2026-09-24', '2026-09-24', 'Dikembalikan'),
(32, 12, 'a', 'Bola Basket', '2026-09-24', '2026-09-24', 'Dikembalikan'),
(33, 4, 'tasya julyanti', 'Bola Basket', '2026-09-25', '2026-09-25', 'Dikembalikan'),
(34, 12, 'a', 'bola sepak', '2026-09-25', '2026-09-25', 'Disetujui'),
(35, 4, 'tasya julyanti', 'Proyektor', '2026-09-26', '2026-09-26', 'Ditolak'),
(36, 4, 'tasya julyanti', 'Bola Basket', '2026-09-26', '2026-09-26', 'Dikembalikan'),
(40, 5, 'aina talita ', 'bola sepak', '2026-09-27', '2026-09-27', 'Dikembalikan'),
(41, 12, 'a', 'bola sepak', '2026-09-28', '2026-09-28', 'Ditolak'),
(42, 12, 'a', 'bola sepak', '2026-09-28', '2026-09-28', 'Ditolak'),
(43, 12, 'a', 'bola sepak', '2026-09-28', '2026-09-28', 'Dikembalikan'),
(44, 5, 'aina talita ', 'sapu ', '2026-09-28', '2026-09-28', 'Ditolak'),
(46, 4, 'tasya julyanti', 'bola sepak', '2026-09-29', '2026-09-29', 'Dikembalikan'),
(47, 4, 'tasya julyanti', 'bola sepak', '2026-09-30', '2026-09-30', 'Disetujui'),
(48, 14, 'mama', 'bola sepak', '2026-09-30', '2026-09-30', 'Dikembalikan'),
(49, 14, 'mama', 'Bola Basket', '2026-09-30', '2026-09-30', 'Dikembalikan'),
(50, 14, 'mama', 'Bola Basket', '2026-09-30', '2026-09-30', 'Dikembalikan'),
(51, 14, 'mama', 'Proyektor', '2026-09-30', '2026-09-30', 'Disetujui'),
(55, 4, 'tasya julyanti', 'bola sepak', '2026-10-30', '2026-10-30', 'Dikembalikan'),
(56, 4, 'tasya julyanti', 'Bola Basket', '2026-11-09', '2026-11-09', 'Menunggu'),
(57, 4, 'tasya julyanti', 'bola sepak', '2026-11-09', '2026-11-09', 'Menunggu');

-- --------------------------------------------------------

--
-- Table structure for table `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id_pengembalian` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `nama_peminjam` varchar(100) DEFAULT NULL,
  `nama_alat` varchar(100) DEFAULT NULL,
  `tanggal_kembali` date NOT NULL,
  `kondisi_kembali` varchar(50) NOT NULL,
  `denda` decimal(12,2) DEFAULT 0.00,
  `status_denda` enum('Lunas','Belum Lunas') DEFAULT 'Lunas',
  `status_pembayaran` enum('Belum Dibayar','Sudah Dibayar') DEFAULT 'Belum Dibayar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengembalian`
--

INSERT INTO `pengembalian` (`id_pengembalian`, `id_peminjaman`, `nama_peminjam`, `nama_alat`, `tanggal_kembali`, `kondisi_kembali`, `denda`, `status_denda`, `status_pembayaran`) VALUES
(1, 1, 'tasya julyanti', 'Laptop', '2026-09-12', 'Baik', 0.00, 'Belum Lunas', 'Belum Dibayar'),
(3, 3, 'tasya julyanti', 'Bola Basket', '2026-09-14', 'Rusak', 0.00, 'Lunas', 'Belum Dibayar'),
(5, 5, 'tasya julyanti', 'Keyboard', '2026-09-16', 'Baik', 0.00, 'Lunas', 'Belum Dibayar'),
(6, 1, 'tasya julyanti', 'Laptop', '2026-09-17', 'Rusak', 0.00, 'Lunas', 'Sudah Dibayar'),
(7, 1, 'tasya julyanti', 'Laptop', '2026-09-16', 'Baik', 0.00, 'Lunas', 'Belum Dibayar'),
(8, 8, 'tasya julyanti', 'Laptop', '2026-09-16', 'Baik', 0.00, 'Lunas', 'Belum Dibayar'),
(11, 1, 'tasya julyanti', 'Laptop', '2026-09-21', 'Baik', 0.00, 'Lunas', 'Belum Dibayar'),
(12, 18, 'tasya julyanti', 'Kamera', '2026-09-21', 'Baik', 0.00, 'Lunas', 'Belum Dibayar'),
(14, 24, 'tasya julyanti', 'lampu', '2026-09-23', 'Baik', 0.00, 'Lunas', 'Belum Dibayar'),
(15, 20, 'tasya julyanti', 'Kamera', '2026-09-23', 'Baik', 0.00, 'Lunas', 'Sudah Dibayar'),
(16, 19, 'aina talita ', 'Proyektor', '2026-09-23', 'Baik', 0.00, 'Lunas', 'Sudah Dibayar'),
(17, 26, 'tasya julyanti', 'lampu', '2026-09-23', 'Baik', 0.00, 'Lunas', 'Sudah Dibayar'),
(18, 17, 'tasya julyanti', 'Laptop', '2026-09-23', 'Baik', 0.00, 'Lunas', 'Sudah Dibayar'),
(19, 27, 'aina talita ', 'lampu', '2026-09-24', 'Baik', 0.00, 'Lunas', 'Sudah Dibayar'),
(20, 28, 'aina talita ', 'Bola Basket', '2026-09-24', 'Baik', 0.00, 'Lunas', 'Belum Dibayar'),
(21, 33, 'tasya julyanti', 'Bola Basket', '2026-09-25', 'Baik', 0.00, 'Lunas', 'Sudah Dibayar'),
(22, 32, 'a', 'Bola Basket', '2026-09-25', 'Baik', 0.00, 'Lunas', 'Sudah Dibayar'),
(23, 22, 'tasya julyanti', 'lampu', '2026-09-25', 'baik', 0.00, 'Lunas', 'Sudah Dibayar'),
(29, 40, 'aina talita ', 'bola sepak', '2026-10-05', 'Terlambat', 10000.00, 'Belum Lunas', 'Sudah Dibayar'),
(32, 36, NULL, NULL, '2026-09-28', 'Rusak Parah', 50000.00, 'Belum Lunas', 'Sudah Dibayar'),
(34, 13, NULL, NULL, '2026-09-28', 'Terlambat', 10000.00, 'Belum Lunas', 'Sudah Dibayar'),
(35, 46, NULL, NULL, '2026-09-29', 'Rusak Ringan', 25000.00, 'Belum Lunas', 'Sudah Dibayar'),
(37, 48, NULL, NULL, '2026-09-30', 'terlambat', 10000.00, '', 'Sudah Dibayar'),
(38, 50, NULL, NULL, '2026-09-30', 'terlambat', 10000.00, '', 'Sudah Dibayar'),
(43, 55, NULL, NULL, '2026-10-30', 'rusak ringan', 25000.00, '', 'Sudah Dibayar');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','petugas') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_lengkap`, `role`) VALUES
(1, 'irma', '12345', 'Irma Diana', 'admin'),
(2, 'petugas1', '12345', 'ellen junithan', 'petugas'),
(3, 'petugas2', '12345', 'alisa khairan', 'petugas'),
(4, 'siswa1', '12345', 'tasya julyanti', ''),
(5, 'siswa2', '12345', 'aina talita ', ''),
(8, 'afnan', '12345', 'afnan sabil', 'admin'),
(12, 'aa', 'a', 'a', ''),
(14, 'ma', '12345', 'mama', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alat`
--
ALTER TABLE `alat`
  ADD PRIMARY KEY (`id_alat`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_peminjaman` (`id_peminjaman`),
  ADD KEY `id_alat` (`id_alat`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`id_pengembalian`),
  ADD KEY `id_peminjaman` (`id_peminjaman`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alat`
--
ALTER TABLE `alat`
  MODIFY `id_alat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=330;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id_pengembalian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alat`
--
ALTER TABLE `alat`
  ADD CONSTRAINT `alat_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);

--
-- Constraints for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD CONSTRAINT `detail_peminjaman_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`),
  ADD CONSTRAINT `detail_peminjaman_ibfk_2` FOREIGN KEY (`id_alat`) REFERENCES `alat` (`id_alat`);

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `pengembalian_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
