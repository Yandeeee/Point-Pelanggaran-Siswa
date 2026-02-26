-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Feb 2026 pada 03.56
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

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
-- Struktur dari tabel `guru`
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
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`id`, `username`, `password`, `nama`, `kode_guru`, `jenis_kelamin`, `email`, `role`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'guru1', '$2y$10$LsM9rjVg6Tq3MgqUMQiwo.wybW6Z8b.L2bqoYFcstjwp19xQOfQ7i', 'Budi Santoso M.Pd', 'GR001', 'Laki-laki', 'budi@guru.sch.id', 'admin', '2026-01-29 03:07:41', NULL, NULL),
(2, 'guru2', 'pass2', 'Siti Aminah', 'GR002', 'Perempuan', 'siti@guru.sch.id', 'guru', '2026-01-29 03:07:41', NULL, NULL),
(3, 'guru3', 'pass3', 'Ahmad Fauzi', 'GR003', 'Laki-laki', 'ahmad@guru.sch.id', 'guru', '2026-01-29 03:07:41', NULL, NULL),
(4, 'guru4', 'pass4', 'Rina Lestari', 'GR004', 'Perempuan', 'rina@guru.sch.id', 'guru', '2026-01-29 03:07:41', NULL, NULL),
(5, 'guru5', 'pass5', 'Dedi Pratama', 'GR005', 'Laki-laki', 'dedi@guru.sch.id', 'bk', '2026-01-29 03:07:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_pelanggaran`
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
-- Dumping data untuk tabel `jenis_pelanggaran`
--

INSERT INTO `jenis_pelanggaran` (`id`, `kode_pelanggaran`, `nama_pelanggaran`, `sanksi_poin`, `deskripsi_sanksi`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'PL001', 'Terlambat', 5, 'Peringatan lisan', '2026-01-29 03:07:41', NULL, NULL),
(2, 'PL002', 'Tidak memakai seragam', 10, 'Peringatan tertulis', '2026-01-29 03:07:41', NULL, NULL),
(3, 'PL003', 'Membolos', 20, 'Pemanggilan orang tua', '2026-01-29 03:07:41', NULL, NULL),
(4, 'PL004', 'Merokok', 30, 'Skorsing', '2026-01-29 03:07:41', NULL, NULL),
(5, 'PL005', 'Berkelahi', 50, 'Dikeluarkan sementara', '2026-01-29 03:07:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurusan`
--

CREATE TABLE `jurusan` (
  `id_jurusan` int(11) NOT NULL,
  `nama_jurusan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jurusan`
--

INSERT INTO `jurusan` (`id_jurusan`, `nama_jurusan`) VALUES
(1, 'Rekayasa Perangkat Lunak'),
(2, 'Desain Komunikasi Visual'),
(3, 'Teknik Komputer Jaringan'),
(4, 'Animasi'),
(5, 'Bisnis Digital');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan`
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
-- Dumping data untuk tabel `laporan`
--

INSERT INTO `laporan` (`id`, `jenis_laporan`, `id_surat`, `keterangan`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Harian', 1, 'Laporan pelanggaran ringan', 'Suli', '2026-01-29 03:07:41', NULL, NULL),
(2, 'Harian', 2, 'Laporan seragam', 'Dharma', '2026-01-29 03:07:41', NULL, NULL),
(3, 'Bulanan', 3, 'Pelanggaran berat', 'Yanto', '2026-01-29 03:07:41', NULL, NULL),
(4, 'Harian', 4, 'Disiplin siswa', 'Eka', '2026-01-29 03:07:41', NULL, NULL),
(5, 'Khusus', 5, 'Kasus skorsing', 'Maha', '2026-01-29 03:07:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `login_logs`
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
-- Dumping data untuk tabel `login_logs`
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
(21, 'guru1', 'admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-26 01:18:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orang_tua`
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
-- Dumping data untuk tabel `orang_tua`
--

INSERT INTO `orang_tua` (`id`, `nama_orangTua`, `telp_orangTua`, `pekerjaan_orangTua`, `alamat`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Andi Wijaya', '081234567001', 'Wiraswasta', 'Jakarta', '2026-01-29 03:07:41', NULL, NULL),
(2, 'Slamet Riyadi', '081234567002', 'Petani', 'Bogor', '2026-01-29 03:07:41', NULL, NULL),
(3, 'Rudi Hartono', '081234567003', 'Karyawan Swasta', 'Depok', '2026-01-29 03:07:41', NULL, NULL),
(4, 'Agus Salim', '081234567004', 'PNS', 'Bekasi', '2026-01-29 03:07:41', NULL, NULL),
(5, 'Joko Susilo', '081234567005', 'Pedagang', 'Tangerang', '2026-01-29 03:07:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggaran`
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
-- Dumping data untuk tabel `pelanggaran`
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
-- Struktur dari tabel `riwayat_pelanggaran`
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
-- Dumping data untuk tabel `riwayat_pelanggaran`
--

INSERT INTO `riwayat_pelanggaran` (`id`, `siswa_id`, `pelanggaran_id`, `tanggal_pelanggaran`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 2, 8, '0000-00-00', 'terlambat datang lewat dari 15 menit jam pelajaran', '2026-02-25 01:01:00', '2026-02-25 07:46:33'),
(2, 2, 2, '0000-00-00', 'terlambat datang lewat dari 15 menit jam pelajaran', '2026-02-25 07:46:33', '2026-02-25 07:46:33'),
(3, 2, 9, '0000-00-00', 'terlambat datang lewat dari 15 menit jam pelajaran', '2026-02-25 07:46:33', '2026-02-25 07:46:33'),
(4, 2, 7, '0000-00-00', 'terlambat datang lewat dari 15 menit jam pelajaran', '2026-02-25 07:46:33', '2026-02-25 07:46:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
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
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id`, `nis`, `nama_siswa`, `kelas`, `email`, `no_telepon`, `deleted_at`, `created_at`, `updated_at`, `nama_orangtua`, `telp_orangtua`, `pekerjaan_orangtua`, `alamat`, `jurusan`) VALUES
(1, '001', 'Ahmad Rizki', 'X', 'ahmad@email.com', '08123456789', NULL, '2026-02-23 01:28:32', '2026-02-25 03:25:52', 'Sumardi', '2054478', 'pegawai swasta', 'gatau arah', 'Rekayasa Perangkat Lunak'),
(2, '002', 'Siti Nurhaliza', 'X-A', 'siti@email.com', '08123456790', NULL, '2026-02-23 01:28:32', '2026-02-23 01:28:32', NULL, NULL, NULL, NULL, NULL),
(3, '003', 'Budi Santoso', 'X-B', 'budi@email.com', '08123456791', NULL, '2026-02-23 01:28:32', '2026-02-23 01:28:32', NULL, NULL, NULL, NULL, NULL),
(4, '004', 'Ani Wijaya', 'X-B', 'ani@email.com', '08123456792', NULL, '2026-02-23 01:28:32', '2026-02-23 01:28:32', NULL, NULL, NULL, NULL, NULL),
(5, '005', 'Rika Septiana', 'X-C', 'rika@email.com', '08123456793', NULL, '2026-02-23 01:28:32', '2026-02-23 01:28:32', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat`
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
-- Dumping data untuk tabel `surat`
--

INSERT INTO `surat` (`id`, `jenis_surat`, `nomor_surat`, `tanggal_surat`, `id_siswa`, `keterangan`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Peringatan', 'SP-001', '2025-01-10', 1, 'Terlambat berulang', 'Budi Santoso', '2026-01-29 03:07:41', NULL, NULL),
(2, 'Peringatan', 'SP-002', '2025-01-11', 2, 'Seragam tidak sesuai', 'Siti Aminah', '2026-01-29 03:07:41', NULL, NULL),
(3, 'Pemanggilan', 'SP-003', '2025-01-12', 3, 'Membolos', 'Ahmad Fauzi', '2026-01-29 03:07:41', NULL, NULL),
(4, 'Peringatan', 'SP-004', '2025-01-13', 4, 'Disiplin waktu', 'Rina Lestari', '2026-01-29 03:07:41', NULL, NULL),
(5, 'Skorsing', 'SP-005', '2025-01-14', 5, 'Merokok', 'Dedi Pratama', '2026-01-29 03:07:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
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
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `kode_guru`, `jenis_kelamin`, `email`, `role`, `created_at`, `updated_at`) VALUES
(12, 'admin', '$2y$10$QyYF5k5Cm3RnRBQggSgIwupsLyktC1gbOKCt05uwPBmKrEL/ly3Jm', 'admin', '', '', '', 'admin', '2026-02-23 01:28:32', '2026-02-25 07:21:37'),
(13, 'guru1', '$2y$10$LsM9rjVg6Tq3MgqUMQiwo.wybW6Z8b.L2bqoYFcstjwp19xQOfQ7i', 'Budi Santoso M.Pd', 'GR001', 'Laki-laki', 'budi@guru.sch.id', 'admin', '2026-02-25 06:32:43', '2026-02-25 07:21:24');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jenis_pelanggaran`
--
ALTER TABLE `jenis_pelanggaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`);

--
-- Indeks untuk tabel `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orang_tua`
--
ALTER TABLE `orang_tua`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pelanggaran`
--
ALTER TABLE `pelanggaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pelanggaran` (`kode_pelanggaran`);

--
-- Indeks untuk tabel `riwayat_pelanggaran`
--
ALTER TABLE `riwayat_pelanggaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `pelanggaran_id` (`pelanggaran_id`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nis` (`nis`);

--
-- Indeks untuk tabel `surat`
--
ALTER TABLE `surat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `guru`
--
ALTER TABLE `guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `jenis_pelanggaran`
--
ALTER TABLE `jenis_pelanggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id_jurusan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `orang_tua`
--
ALTER TABLE `orang_tua`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pelanggaran`
--
ALTER TABLE `pelanggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `riwayat_pelanggaran`
--
ALTER TABLE `riwayat_pelanggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `surat`
--
ALTER TABLE `surat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `riwayat_pelanggaran`
--
ALTER TABLE `riwayat_pelanggaran`
  ADD CONSTRAINT `riwayat_pelanggaran_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `riwayat_pelanggaran_ibfk_2` FOREIGN KEY (`pelanggaran_id`) REFERENCES `pelanggaran` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;