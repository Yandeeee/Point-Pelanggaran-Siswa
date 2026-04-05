-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2026 at 08:46 AM
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
-- Database: `point_pelanggaran_siswa`
--

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `kode_guru` varchar(20) NOT NULL,
  `jenis_kelamin` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id`, `username`, `password`, `nama`, `kode_guru`, `jenis_kelamin`, `email`, `role`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'guru1', '$2y$10$LsM9rjVg6Tq3MgqUMQiwo.wybW6Z8b.L2bqoYFcstjwp19xQOfQ7i', 'Budi Santoso M.Pd', 'GR001', 'Laki-laki', 'budi@guru.sch.id', 'admin', '2026-01-29 03:07:41', NULL, NULL),
(2, 'guru2', 'pass2', 'Siti Aminah', 'GR002', 'Perempuan', 'siti@guru.sch.id', 'guru', '2026-01-29 03:07:41', NULL, NULL),
(3, 'guru3', 'pass3', 'Ahmad Fauzi', 'GR003', 'Laki-laki', 'ahmad@guru.sch.id', 'guru', '2026-01-29 03:07:41', NULL, NULL),
(4, 'guru4', 'pass4', 'Rina Lestari', 'GR004', 'Perempuan', 'rina@guru.sch.id', 'guru', '2026-01-29 03:07:41', NULL, NULL),
(5, 'guru5', 'pass5', 'Dedi Pratama', 'GR005', 'Laki-laki', 'dedi@guru.sch.id', 'bk', '2026-01-29 03:07:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pelanggaran`
--

CREATE TABLE `jenis_pelanggaran` (
  `id` int(11) NOT NULL,
  `kode_pelanggaran` varchar(20) DEFAULT NULL,
  `nama_pelanggaran` varchar(100) DEFAULT NULL,
  `sanksi_poin` int(11) DEFAULT NULL,
  `deskripsi_sanksi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis_pelanggaran`
--

INSERT INTO `jenis_pelanggaran` (`id`, `kode_pelanggaran`, `nama_pelanggaran`, `sanksi_poin`, `deskripsi_sanksi`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'PL001', 'Terlambat', 5, 'Peringatan lisan', '2026-01-29 03:07:41', NULL, NULL),
(2, 'PL002', 'Tidak memakai seragam', 10, 'Peringatan tertulis', '2026-01-29 03:07:41', NULL, NULL),
(3, 'PL003', 'Membolos', 20, 'Pemanggilan orang tua', '2026-01-29 03:07:41', NULL, NULL),
(4, 'PL004', 'Merokok', 30, 'Skorsing', '2026-01-29 03:07:41', NULL, NULL),
(5, 'PL005', 'Berkelahi', 50, 'Dikeluarkan sementara', '2026-01-29 03:07:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id_jurusan` int(11) NOT NULL,
  `nama_jurusan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id_jurusan`, `nama_jurusan`) VALUES
(1, 'Rekayasa Perangkat Lunak'),
(2, 'Desain Komunikasi Visual'),
(3, 'Teknik Komputer Jaringan'),
(4, 'Animasi'),
(5, 'Bisnis Digital');

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `jenis_laporan` varchar(50) DEFAULT NULL,
  `id_surat` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id`, `jenis_laporan`, `id_surat`, `keterangan`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Harian', 1, 'Laporan pelanggaran ringan', 'Suli', '2026-01-29 03:07:41', NULL, NULL),
(2, 'Harian', 2, 'Laporan seragam', 'Dharma', '2026-01-29 03:07:41', NULL, NULL),
(3, 'Bulanan', 3, 'Pelanggaran berat', 'Yanto', '2026-01-29 03:07:41', NULL, NULL),
(4, 'Harian', 4, 'Disiplin siswa', 'Eka', '2026-01-29 03:07:41', NULL, NULL),
(5, 'Khusus', 5, 'Kasus skorsing', 'Maha', '2026-01-29 03:07:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `logged_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`id`, `username`, `role`, `ip_address`, `user_agent`, `logged_at`) VALUES
(1, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-19 02:06:08'),
(2, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-19 02:28:01'),
(3, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-19 02:46:38'),
(4, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-19 05:17:32'),
(5, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-23 00:53:27'),
(6, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-23 01:03:10'),
(7, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-23 01:09:02'),
(8, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-23 01:19:33'),
(9, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-23 01:24:31'),
(10, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-23 01:28:46'),
(11, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-23 02:29:57'),
(12, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-23 23:37:41'),
(13, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-24 00:42:33'),
(14, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-25 00:39:31'),
(15, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-25 03:15:25'),
(16, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-25 06:09:43'),
(17, 'guru1', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-25 06:35:21'),
(18, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-25 08:04:00'),
(19, 'guru1', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-25 08:05:25'),
(20, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-26 00:47:26'),
(21, 'guru1', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-26 01:18:58'),
(22, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-26 11:43:06'),
(23, 'guru1', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-26 12:53:33'),
(24, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-26 12:59:48'),
(25, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-26 23:37:09'),
(26, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-02 00:41:43'),
(27, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-02 01:31:13'),
(28, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-02 02:32:25'),
(29, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-04 00:50:34'),
(30, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-04 14:17:39'),
(31, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 00:16:23'),
(32, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 03:45:59'),
(33, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 23:30:07'),
(34, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 23:34:29'),
(35, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 23:54:21'),
(36, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-12 00:58:53'),
(37, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-01 08:38:56'),
(38, 'guru1', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-01 10:05:31'),
(39, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-01 10:16:07'),
(40, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-01 10:19:44'),
(41, 'admin', 'admin', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-02 13:09:28'),
(42, 'admin', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-05 06:03:48');

-- --------------------------------------------------------

--
-- Table structure for table `orang_tua`
--

CREATE TABLE `orang_tua` (
  `id` int(11) NOT NULL,
  `nama_orangTua` varchar(100) DEFAULT NULL,
  `telp_orangTua` varchar(20) DEFAULT NULL,
  `pekerjaan_orangTua` varchar(100) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orang_tua`
--

INSERT INTO `orang_tua` (`id`, `nama_orangTua`, `telp_orangTua`, `pekerjaan_orangTua`, `alamat`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Andi Wijaya', '081234567001', 'Wiraswasta', 'Jakarta', '2026-01-29 03:07:41', NULL, NULL),
(2, 'Slamet Riyadi', '081234567002', 'Petani', 'Bogor', '2026-01-29 03:07:41', NULL, NULL),
(3, 'Rudi Hartono', '081234567003', 'Karyawan Swasta', 'Depok', '2026-01-29 03:07:41', NULL, NULL),
(4, 'Agus Salim', '081234567004', 'PNS', 'Bekasi', '2026-01-29 03:07:41', NULL, NULL),
(5, 'Joko Susilo', '081234567005', 'Pedagang', 'Tangerang', '2026-01-29 03:07:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggaran`
--

CREATE TABLE `pelanggaran` (
  `id` int(11) NOT NULL,
  `kode_pelanggaran` varchar(10) NOT NULL,
  `nama_pelanggaran` varchar(100) NOT NULL,
  `sanksi_poin` int(11) NOT NULL,
  `deskripsi_sanksi` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggaran`
--

INSERT INTO `pelanggaran` (`id`, `kode_pelanggaran`, `nama_pelanggaran`, `sanksi_poin`, `deskripsi_sanksi`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'P001', 'Tidak Masuk Tanpa Keterangan', 10, 'Siswa tidak hadir dan tidak memberi keterangan', NULL, '2026-02-23 01:28:32', '2026-02-23 01:28:32'),
(2, 'P002', 'Datang Terlambat', 5, 'Siswa datang ke sekolah lebih dari 15 menit setelah jam masuk', NULL, '2026-02-23 01:28:32', '2026-02-23 01:28:32'),
(3, 'P003', 'Tidak Mengerjakan PR', 8, 'Siswa tidak mengumpulkan pekerjaan rumah yang diberikan guru', NULL, '2026-02-23 01:28:32', '2026-02-23 01:28:32'),
(4, 'P004', 'Menyontek', 15, 'Siswa ketahuan menyontek saat ujian atau kuis', NULL, '2026-02-23 01:28:32', '2026-02-23 01:28:32'),
(5, 'P005', 'Tidak Pakai Seragam Lengkap', 5, 'Siswa tidak memakai seragam sesuai peraturan', NULL, '2026-02-23 01:28:32', '2026-02-23 01:28:32'),
(6, 'P006', 'Berambut Panjang/Tidak Rapi', 5, 'Siswa memiliki gaya rambut yang tidak sesuai peraturan', NULL, '2026-02-23 01:28:32', '2026-02-23 01:28:32'),
(7, 'P007', 'Mengganggu Pelajaran', 10, 'Siswa mengganggu jalannya proses pembelajaran di kelas', NULL, '2026-02-23 01:28:32', '2026-02-23 01:28:32'),
(8, 'P008', 'Bicara Tidak Sopan', 12, 'Siswa berbicara tidak sopan kepada guru atau teman', NULL, '2026-02-23 01:28:32', '2026-02-23 01:28:32'),
(9, 'P009', 'Membawa HP ke Sekolah', 8, 'Siswa membawa handphone ke lingkungan sekolah', NULL, '2026-02-23 01:28:32', '2026-02-23 01:28:32'),
(10, 'P010', 'Tidur di Kelas', 7, 'Siswa tertidur atau tidak memperhatikan saat proses belajar', NULL, '2026-02-23 01:28:32', '2026-02-23 01:28:32');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_pelanggaran`
--

CREATE TABLE `riwayat_pelanggaran` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `pelanggaran_id` int(11) NOT NULL,
  `tanggal_pelanggaran` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat_pelanggaran`
--

INSERT INTO `riwayat_pelanggaran` (`id`, `siswa_id`, `pelanggaran_id`, `tanggal_pelanggaran`, `keterangan`, `created_at`, `updated_at`) VALUES
(7, 1, 1, '0000-00-00', '', '2026-03-02 00:45:42', '2026-03-02 00:45:42'),
(8, 3, 8, '0000-00-00', '', '2026-03-05 02:33:06', '2026-03-05 02:33:06');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'siswa',
  `no_telepon` varchar(15) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nama_orangtua` varchar(150) DEFAULT NULL,
  `telp_orangtua` varchar(15) DEFAULT NULL,
  `pekerjaan_orangtua` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `jurusan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `nis`, `nama_siswa`, `kelas`, `email`, `password`, `role`, `no_telepon`, `deleted_at`, `created_at`, `updated_at`, `nama_orangtua`, `telp_orangtua`, `pekerjaan_orangtua`, `alamat`, `jurusan`) VALUES
(1, '001', 'Ahmad Rizki', 'X', 'ahmad@email.com', '001', 'siswa', '08123456789', NULL, '2026-02-23 01:28:32', '2026-04-05 06:45:15', 'Andi Wijaya', '081234567001', 'Wiraswasta', 'Jakarta', 'Rekayasa Perangkat Lunak'),
(2, '002', 'Siti Nurhaliza', 'X', 'siti@email.com', '002', 'siswa', '08123456790', NULL, '2026-02-23 01:28:32', '2026-04-05 06:45:15', 'Slamet Riyadi', '081234567002', 'Petani', 'Bogor', 'Bisnis Digital'),
(3, '003', 'Budi Santoso', 'XI', 'budi@email.com', '003', 'siswa', '08123456791', NULL, '2026-02-23 01:28:32', '2026-04-05 06:45:15', 'Rudi Hartono', '081234567003', 'Karyawan Swasta', 'Depok', 'Animasi'),
(4, '004', 'Ani Wijaya', 'X', 'ani@email.com', '004', 'siswa', '08123456792', NULL, '2026-02-23 01:28:32', '2026-04-05 06:45:15', 'Agus Salim', '081234567004', 'PNS', 'Bekasi', 'Teknik Komputer Jaringan'),
(5, '005', 'Rika Septiari', 'X', 'rika@email.com', '005', 'siswa', '08123456793', NULL, '2026-02-23 01:28:32', '2026-04-05 06:45:15', 'Joko Susilo', '081234567005', 'Pedagang', 'Tangerang', 'Desain Komunikasi Visual'),
(6, '006', 'Bagus Renata', 'X', 'Renata@gmail.yahoo', '006', 'siswa', '08123456789', NULL, '2026-02-26 13:01:51', '2026-04-05 06:45:15', 'Darsono', '13214124121', 'pegawai swasta', 'Jl. Pengacara, gg pengangguan banyak acara', 'Desain Komunikasi Visual'),
(9, '007', 'Aristya', 'XI', 'aristya@gmail.com', '007', 'siswa', '2420690205', NULL, '2026-03-05 23:41:01', '2026-04-05 06:45:15', 'Diah', '2311', 'PNS', 'jalan sampai bensin habis', 'Desain Komunikasi Visual');

-- --------------------------------------------------------

--
-- Table structure for table `surat`
--

CREATE TABLE `surat` (
  `id` int(11) NOT NULL,
  `jenis_surat` varchar(50) DEFAULT NULL,
  `nomor_surat` varchar(50) DEFAULT NULL,
  `tanggal_surat` date DEFAULT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `surat`
--

INSERT INTO `surat` (`id`, `jenis_surat`, `nomor_surat`, `tanggal_surat`, `id_siswa`, `keterangan`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Peringatan', 'SP-001', '2025-01-10', 1, 'Terlambat berulang', 'Budi Santoso', '2026-01-29 03:07:41', NULL, NULL),
(2, 'Peringatan', 'SP-002', '2025-01-11', 2, 'Seragam tidak sesuai', 'Siti Aminah', '2026-01-29 03:07:41', NULL, NULL),
(3, 'Pemanggilan', 'SP-003', '2025-01-12', 3, 'Membolos', 'Ahmad Fauzi', '2026-01-29 03:07:41', NULL, NULL),
(4, 'Peringatan', 'SP-004', '2025-01-13', 4, 'Disiplin waktu', 'Rina Lestari', '2026-01-29 03:07:41', NULL, NULL),
(5, 'Skorsing', 'SP-005', '2025-01-14', 5, 'Merokok', 'Dedi Pratama', '2026-01-29 03:07:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `kode_guru` varchar(20) DEFAULT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `kode_guru`, `jenis_kelamin`, `email`, `role`, `created_at`, `updated_at`) VALUES
(12, 'admin', '$2y$10$QyYF5k5Cm3RnRBQggSgIwupsLyktC1gbOKCt05uwPBmKrEL/ly3Jm', 'admin', '', '', '', 'admin', '2026-02-23 01:28:32', '2026-02-25 07:21:37'),
(13, 'guru1', '$2y$10$LsM9rjVg6Tq3MgqUMQiwo.wybW6Z8b.L2bqoYFcstjwp19xQOfQ7i', 'Drs. I Gusti Made Murjana,M.Pd', 'GR001', 'Laki-laki', 'budi@guru.sch.id', 'admin', '2026-02-25 06:32:43', '2026-02-26 12:54:41'),
(18, 'guru2', '$2y$10$xnJ4Eh7mwb4Tz/pEjLrMNOjIlGQrQ42LwFNZccrKsMD5ukPnN1nfO', 'Siti Aminah', NULL, NULL, NULL, 'guru', '2026-03-05 02:20:19', '2026-03-05 02:20:19'),
(19, 'guru5', '$2y$10$WvxXPhlb3vJqKZS2AX3EWu3pHJuTjYZ5qkS2V.YO8w9DywrXzJoFu', 'Dedi Pratama', NULL, NULL, NULL, 'guru_bk', '2026-03-05 02:20:19', '2026-03-05 02:20:19'),
(20, 'guru3', '$2y$10$yGWZfPLMwz7B6VZF1XYQHekf0RDxeUTi6Bl7sqStWNZhRtdQy6wPu', 'Ahmad Fauzi', NULL, NULL, NULL, 'guru', '2026-03-05 02:35:14', '2026-03-05 02:35:14'),
(21, 'guru4', '$2y$10$mx4gOGHmL6jctPEYr3IV5uBJnie9pZJzt51VEnlbdn3X269/7AFuO', 'Rina Lestari', NULL, NULL, NULL, 'guru', '2026-03-05 02:35:14', '2026-03-05 02:35:14'),
(22, '001', '$2y$10$833epaLG3OBTrWnzEMd5NuKsttVgeUHE5ETxOEv0HRikc4lTEqoEm', 'Ahmad Rizki', NULL, NULL, NULL, 'siswa', '2026-03-05 03:34:32', NULL),
(24, '002', '$2y$10$833epaLG3OBTrWnzEMd5NuKsttVgeUHE5ETxOEv0HRikc4lTEqoEm', 'Siti Nurhaliza', '', 'Perempuan', '', 'admin', '2026-03-05 03:38:22', '2026-03-05 23:41:42'),
(25, '003', '$2y$10$833epaLG3OBTrWnzEMd5NuKsttVgeUHE5ETxOEv0HRikc4lTEqoEm', 'Budi Santoso', NULL, NULL, NULL, 'siswa', '2026-03-05 03:38:22', NULL),
(26, '004', '$2y$10$833epaLG3OBTrWnzEMd5NuKsttVgeUHE5ETxOEv0HRikc4lTEqoEm', 'Ani Wijaya', NULL, NULL, NULL, 'siswa', '2026-03-05 03:38:22', NULL),
(27, '005', '$2y$10$833epaLG3OBTrWnzEMd5NuKsttVgeUHE5ETxOEv0HRikc4lTEqoEm', 'Rika Septiana', NULL, NULL, NULL, 'siswa', '2026-03-05 03:38:22', NULL),
(28, '006', '$2y$10$833epaLG3OBTrWnzEMd5NuKsttVgeUHE5ETxOEv0HRikc4lTEqoEm', 'Bagus Renata', NULL, NULL, NULL, 'siswa', '2026-03-05 03:38:22', NULL),
(29, '007', '$2y$10$uW9Tu7moqz794APOQTx3vOPMq2Mp42N3yrrO/K6W3gA5FTRjyVJmi', 'Aristya', '', 'Perempuan', 'aristya@gmail.com', 'siswa', '2026-03-05 23:51:56', '2026-03-05 23:51:56'),
(30, 'Kepala Sekolah', '$2y$10$i9K1qbWZnOMXUdQD7vYXa.HQwYVRo.kF97J8j6DqwVNH/iKJclJLW', '\'Drs. I Gusti Made Murjana,M.Pd', 'GR001', 'Laki-laki', 'murjana@gmail.com', 'kepala_sekolah', '2026-04-01 10:18:08', '2026-04-01 10:18:08'),
(31, 'wakasek', '$2y$10$i75acb.aER5b6pK2KGpOZu4BVooHhDeUyMpzUeoEJKf7cQovy2akC', 'Nyoman Sucana', 'GR007', 'Laki-laki', 'sucana@gmail.com', 'waka_kesiswaan', '2026-04-02 13:29:45', '2026-04-02 13:44:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_pelanggaran`
--
ALTER TABLE `jenis_pelanggaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orang_tua`
--
ALTER TABLE `orang_tua`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pelanggaran`
--
ALTER TABLE `pelanggaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pelanggaran` (`kode_pelanggaran`);

--
-- Indexes for table `riwayat_pelanggaran`
--
ALTER TABLE `riwayat_pelanggaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `pelanggaran_id` (`pelanggaran_id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nis` (`nis`);

--
-- Indexes for table `surat`
--
ALTER TABLE `surat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jenis_pelanggaran`
--
ALTER TABLE `jenis_pelanggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id_jurusan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `orang_tua`
--
ALTER TABLE `orang_tua`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pelanggaran`
--
ALTER TABLE `pelanggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `riwayat_pelanggaran`
--
ALTER TABLE `riwayat_pelanggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `surat`
--
ALTER TABLE `surat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `riwayat_pelanggaran`
--
ALTER TABLE `riwayat_pelanggaran`
  ADD CONSTRAINT `riwayat_pelanggaran_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `riwayat_pelanggaran_ibfk_2` FOREIGN KEY (`pelanggaran_id`) REFERENCES `pelanggaran` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
