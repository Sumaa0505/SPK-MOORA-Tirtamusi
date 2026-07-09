-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2026 at 10:30 PM
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
-- Database: `spk_moora_final`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_user_approval_log`
--

CREATE TABLE `admin_user_approval_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `registration_id` int(11) NOT NULL,
  `admin_id` int(10) UNSIGNED DEFAULT NULL,
  `action` enum('approved','rejected','reviewed') NOT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_user_approval_log`
--

INSERT INTO `admin_user_approval_log` (`id`, `registration_id`, `admin_id`, `action`, `catatan`, `created_at`) VALUES
(1, 1, 1, 'approved', 'Akun disetujui dan diaktifkan oleh Administrator.', '2026-06-14 23:51:31'),
(2, 2, 1, 'rejected', 'maaf identitas belum jelas', '2026-06-15 09:09:46'),
(3, 3, 1, 'approved', 'disetujui', '2026-06-15 09:12:38'),
(4, 4, 1, 'approved', 'selamat datang', '2026-06-16 12:27:50'),
(5, 8, 1, 'approved', 'Akun disetujui dan diaktifkan oleh Administrator.', '2026-06-17 12:33:46');

-- --------------------------------------------------------

--
-- Table structure for table `alternatif`
--

CREATE TABLE `alternatif` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_alternatif` varchar(20) NOT NULL,
  `nama_alternatif` varchar(150) NOT NULL,
  `kategori_barang` varchar(100) NOT NULL,
  `jenis_barang` enum('alat','material','aset') DEFAULT 'alat',
  `spesifikasi` text DEFAULT NULL,
  `satuan` varchar(30) NOT NULL DEFAULT 'unit',
  `stok` int(11) DEFAULT 0,
  `stok_minimum` int(11) DEFAULT 0,
  `last_stock_update` datetime DEFAULT NULL,
  `kondisi_barang` enum('baik','rusak','diperbaiki','tidak_layak') DEFAULT 'baik',
  `movement_type` enum('first_moving','slow_moving','non_moving') NOT NULL DEFAULT 'slow_moving',
  `estimasi_harga` decimal(15,2) NOT NULL DEFAULT 0.00,
  `keterangan` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alternatif`
--

INSERT INTO `alternatif` (`id`, `kode_alternatif`, `nama_alternatif`, `kategori_barang`, `jenis_barang`, `spesifikasi`, `satuan`, `stok`, `stok_minimum`, `last_stock_update`, `kondisi_barang`, `movement_type`, `estimasi_harga`, `keterangan`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'A001', 'Mesin Pompa Air Sentrifugal', 'Peralatan Mekanikal', 'alat', 'Pompa sentrifugal untuk distribusi air bersih kapasitas menengah', 'unit', 9, 2, NULL, 'diperbaiki', 'slow_moving', 45000000.00, 'Digunakan untuk mendukung distribusi air di instalasi', 0, '2026-04-16 15:05:21', '2026-06-15 00:29:36'),
(2, 'A002', 'Pipa Distribusi HDPE', 'Jaringan Pipa', 'alat', 'Pipa HDPE PN10 diameter menengah untuk distribusi air', 'batang', 50, 5, NULL, 'baik', 'slow_moving', 2500000.00, 'Kebutuhan penggantian pipa pada area rawan kebocoran', 1, '2026-04-16 15:05:21', '2026-06-16 19:59:19'),
(3, 'A003', 'Sensor Monitoring Tekanan Air', 'Instrumentasi', 'alat', 'Sensor digital untuk monitoring tekanan air jaringan distribusi', 'unit', 3, 1, NULL, 'baik', 'slow_moving', 8500000.00, 'Mendukung monitoring tekanan secara real-time', 1, '2026-04-16 15:05:21', '2026-06-12 13:48:12'),
(4, 'A004', 'Water Meter Industri', 'Metering', 'alat', 'Water meter industri untuk pengukuran debit air', 'unit', 299, 400, NULL, 'baik', 'first_moving', 6700000.00, 'Digunakan pada titik kontrol distribusi', 1, '2026-04-16 15:05:21', '2026-06-15 20:36:37'),
(5, 'A005', 'Genset Operasional', 'Peralatan Penunjang', 'alat', 'Genset diesel untuk cadangan daya operasional', 'unit', 9, 2, NULL, 'diperbaiki', 'slow_moving', 78000000.00, 'Cadangan listrik saat gangguan operasional', 1, '2026-04-16 15:05:21', '2026-06-12 13:47:32'),
(20, 'A007', 'Mesin Pompa Air Sentrifugal Besar', 'Peralatan Mekanikal', 'alat', '', 'unit', 0, 0, NULL, 'baik', 'first_moving', 45000000.00, '', 1, '2026-04-29 16:15:04', '2026-06-15 20:36:37'),
(21, 'MAT001', 'Pipa PE 4 cm', 'Jaringan Pipa', 'material', '', 'Meter', 0, 0, NULL, 'baik', 'first_moving', 250000.00, '', 1, '2026-04-29 16:15:48', '2026-06-15 20:36:37'),
(22, 'MAT002', 'Pipa PE 3 cm', 'Jaringan Pipa', 'material', '', 'Meter', 0, 0, NULL, 'baik', 'first_moving', 250000.00, '', 1, '2026-05-02 14:09:23', '2026-06-15 20:36:37'),
(23, 'BRG260612001', 'Pompa sentrifugal kapasitas menengah untuk distribusi air', 'RKA Sub Unit', 'alat', 'Pompa sentrifugal kapasitas menengah untuk distribusi air', 'unit', 0, 0, NULL, 'baik', 'first_moving', 45000000.00, 'Dibuat otomatis dari barang pada dokumen RKA Sub Unit', 1, '2026-06-12 08:54:12', '2026-06-23 12:57:35'),
(24, 'BRG260612002', 'Pipa HDPE PN10 diameter menengah', 'RKA Sub Unit', 'alat', 'Pipa HDPE PN10 diameter menengah', 'batang', 0, 0, NULL, 'baik', 'first_moving', 2500000.00, 'Dibuat otomatis dari barang pada dokumen RKA Sub Unit', 1, '2026-06-12 08:54:12', '2026-06-15 20:36:37'),
(25, 'BRG260612003', 'Sensor digital untuk monitoring tekanan air jaringan distribusi', 'RKA Sub Unit', 'alat', 'Sensor digital untuk monitoring tekanan air jaringan distribusi', 'unit', 0, 0, NULL, 'baik', 'first_moving', 8500000.00, 'Dibuat otomatis dari barang pada dokumen RKA Sub Unit', 1, '2026-06-12 08:54:12', '2026-06-23 12:57:35'),
(26, 'BRG260612004', 'Water meter industri untuk pengukuran debit air', 'RKA Sub Unit', 'alat', 'Water meter industri untuk pengukuran debit air', 'unit', 0, 0, NULL, 'baik', 'first_moving', 6700000.00, 'Dibuat otomatis dari barang pada dokumen RKA Sub Unit', 1, '2026-06-12 08:54:12', '2026-06-23 12:57:35'),
(27, 'BRG260612005', 'Genset diesel cadangan daya operasional', 'RKA Sub Unit', 'alat', 'Genset diesel cadangan daya operasional', 'unit', 0, 0, NULL, 'baik', 'first_moving', 78000000.00, 'Dibuat otomatis dari barang pada dokumen RKA Sub Unit', 1, '2026-06-12 08:54:12', '2026-06-23 12:57:35'),
(28, 'AFIX000000', 'Auto Detail Usulan Recovery', 'Auto Recovery Data Flow', 'alat', 'Fallback non-destruktif dari Patch Auto Fix Full System.', 'unit', 0, 0, NULL, 'baik', 'slow_moving', 0.00, 'Dipakai hanya untuk usulan lama yang belum memiliki detail_usulan.', 1, '2026-06-17 08:29:18', '2026-06-17 08:29:18');

-- --------------------------------------------------------

--
-- Table structure for table `approval_direktur`
--

CREATE TABLE `approval_direktur` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_usulan` int(10) UNSIGNED NOT NULL,
  `tahap_approval` enum('direktur_bidang','direktur_utama','direktur_umum') NOT NULL,
  `urutan` tinyint(3) UNSIGNED NOT NULL,
  `aksi` enum('menunggu','setujui','tolak','revisi','disposisi') NOT NULL DEFAULT 'menunggu',
  `catatan` text DEFAULT NULL,
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `approval_direktur`
--

INSERT INTO `approval_direktur` (`id`, `id_usulan`, `tahap_approval`, `urutan`, `aksi`, `catatan`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'direktur_bidang', 1, 'setujui', NULL, NULL, NULL, '2026-06-15 20:36:38', '2026-06-16 19:33:18'),
(2, 15, 'direktur_bidang', 1, 'menunggu', NULL, NULL, NULL, '2026-06-15 20:36:38', '2026-06-15 20:36:38'),
(4, 1, 'direktur_utama', 2, 'setujui', NULL, NULL, NULL, '2026-06-16 19:33:18', '2026-06-16 19:33:18'),
(5, 1, 'direktur_umum', 3, 'disposisi', NULL, NULL, NULL, '2026-06-16 19:33:18', '2026-06-16 19:33:18'),
(6, 23, 'direktur_bidang', 1, 'menunggu', NULL, NULL, NULL, '2026-06-17 11:25:04', '2026-06-17 11:25:04');

-- --------------------------------------------------------

--
-- Table structure for table `detail_realisasi_pengadaan`
--

CREATE TABLE `detail_realisasi_pengadaan` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_realisasi` int(10) UNSIGNED NOT NULL,
  `id_alternatif` int(10) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(15,2) GENERATED ALWAYS AS (`qty` * `harga_satuan`) STORED,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_usulan`
--

CREATE TABLE `detail_usulan` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_usulan` int(10) UNSIGNED NOT NULL,
  `id_alternatif` int(10) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL,
  `estimasi_harga_satuan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_estimasi` decimal(15,2) GENERATED ALWAYS AS (`jumlah` * `estimasi_harga_satuan`) STORED,
  `alasan_kebutuhan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('draft','diajukan','diverifikasi','dikembalikan','ditolak','diproses_pengadaan','menunggu_penerimaan','diterima','selesai') NOT NULL DEFAULT 'draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_usulan`
--

INSERT INTO `detail_usulan` (`id`, `id_usulan`, `id_alternatif`, `jumlah`, `estimasi_harga_satuan`, `alasan_kebutuhan`, `created_at`, `updated_at`, `status`) VALUES
(43, 3, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'draft'),
(44, 5, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'draft'),
(45, 6, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'draft'),
(46, 7, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'draft'),
(47, 8, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'draft'),
(48, 10, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'draft'),
(49, 11, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'draft'),
(50, 9, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'diajukan'),
(51, 12, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-19 13:55:54', 'diverifikasi'),
(52, 21, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-18 14:55:03', 'diverifikasi'),
(53, 4, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'diajukan'),
(54, 13, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'diajukan'),
(55, 15, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'diajukan'),
(56, 16, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'diverifikasi'),
(57, 17, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'diverifikasi'),
(58, 18, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'diverifikasi'),
(59, 19, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'diverifikasi'),
(60, 20, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'diverifikasi'),
(61, 22, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'diverifikasi'),
(62, 1, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'menunggu_penerimaan'),
(63, 2, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'ditolak'),
(64, 14, 28, 1, 0.00, 'AUTO FIX SQL: detail dibuat otomatis karena usulan belum memiliki detail barang. Edit detail jika ingin mengganti barang asli.', '2026-06-17 08:29:19', '2026-06-17 08:29:19', 'ditolak'),
(65, 23, 23, 2, 45000000.00, 'Operasional distribusi air', '2026-06-17 08:45:28', '2026-06-17 11:25:04', 'diverifikasi'),
(66, 23, 24, 20, 2500000.00, 'Perbaikan titik distribusi rawan bocor', '2026-06-17 08:45:28', '2026-06-17 11:25:04', 'diverifikasi'),
(67, 23, 25, 3, 8500000.00, 'Monitoring tekanan air belum optimal', '2026-06-17 08:45:28', '2026-06-17 11:25:04', 'diverifikasi'),
(68, 23, 26, 5, 6700000.00, 'Kontrol titik distribusi', '2026-06-17 08:45:28', '2026-06-17 11:25:04', 'diverifikasi'),
(69, 23, 27, 1, 78000000.00, 'Cadangan listrik saat gangguan operasional', '2026-06-17 08:45:28', '2026-06-17 11:25:04', 'diverifikasi'),
(70, 24, 23, 1, 45000000.00, 'Urgent', '2026-06-19 14:01:03', '2026-06-19 14:02:39', 'diverifikasi'),
(71, 25, 23, 2, 45000000.00, 'Operasional distribusi air', '2026-06-23 12:57:35', '2026-06-23 12:58:10', 'diajukan'),
(72, 25, 24, 20, 2500000.00, 'Perbaikan titik distribusi rawan bocor', '2026-06-23 12:57:35', '2026-06-23 12:58:10', 'diajukan'),
(73, 25, 25, 3, 8500000.00, 'Monitoring tekanan air belum optimal', '2026-06-23 12:57:35', '2026-06-23 12:58:10', 'diajukan'),
(74, 25, 26, 5, 6700000.00, 'Kontrol titik distribusi', '2026-06-23 12:57:35', '2026-06-23 12:58:10', 'diajukan'),
(75, 25, 27, 1, 78000000.00, 'Cadangan listrik saat gangguan operasional', '2026-06-23 12:57:35', '2026-06-23 12:58:10', 'diajukan'),
(76, 26, 23, 2, 45000000.00, 'Operasional distribusi air', '2026-06-23 12:57:35', '2026-06-23 12:59:27', 'diverifikasi'),
(77, 26, 24, 20, 2500000.00, 'Perbaikan titik distribusi rawan bocor', '2026-06-23 12:57:35', '2026-06-23 12:59:27', 'diverifikasi'),
(78, 26, 25, 3, 8500000.00, 'Monitoring tekanan air belum optimal', '2026-06-23 12:57:35', '2026-06-23 12:59:27', 'diverifikasi'),
(79, 26, 26, 5, 6700000.00, 'Kontrol titik distribusi', '2026-06-23 12:57:35', '2026-06-23 12:59:27', 'diverifikasi'),
(80, 26, 27, 1, 78000000.00, 'Cadangan listrik saat gangguan operasional', '2026-06-23 12:57:35', '2026-06-23 12:59:27', 'diverifikasi'),
(81, 27, 4, 1, 6700000.00, 'operasi lapangan', '2026-06-23 13:08:55', '2026-06-23 13:10:01', 'diverifikasi'),
(82, 28, 5, 1, 78000000.00, 'Urgent', '2026-06-23 13:20:35', '2026-06-23 13:21:31', 'diverifikasi');

-- --------------------------------------------------------

--
-- Table structure for table `distribusi_barang`
--

CREATE TABLE `distribusi_barang` (
  `id` int(11) NOT NULL,
  `id_usulan` int(11) NOT NULL,
  `id_detail_usulan` int(11) DEFAULT NULL,
  `id_pengadaan_serah` int(10) UNSIGNED DEFAULT NULL,
  `id_alternatif` int(11) NOT NULL,
  `id_user_pengusul` int(11) NOT NULL,
  `jenis_distribusi` enum('diambil','diantar') DEFAULT 'diambil',
  `status_distribusi` enum('menunggu_pengambilan','diambil','akan_diantar','diantar','selesai') DEFAULT 'menunggu_pengambilan',
  `jumlah` int(11) DEFAULT 1,
  `tanggal_jadwal` date DEFAULT NULL,
  `tanggal_realisasi` date DEFAULT NULL,
  `diterima_oleh_pengusul_at` datetime DEFAULT NULL,
  `catatan_gudang` text DEFAULT NULL,
  `catatan_pengusul` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `distribusi_barang`
--

INSERT INTO `distribusi_barang` (`id`, `id_usulan`, `id_detail_usulan`, `id_pengadaan_serah`, `id_alternatif`, `id_user_pengusul`, `jenis_distribusi`, `status_distribusi`, `jumlah`, `tanggal_jadwal`, `tanggal_realisasi`, `diterima_oleh_pengusul_at`, `catatan_gudang`, `catatan_pengusul`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 2, 2, 'diambil', 'menunggu_pengambilan', 20, '2026-06-16', NULL, NULL, 'Barang pengadaan sudah diterima Gudang. Menunggu pengambilan/konfirmasi Sub Unit.', NULL, '2026-06-16 19:58:26', '2026-06-16 19:58:26');

-- --------------------------------------------------------

--
-- Table structure for table `dokumen_disposisi`
--

CREATE TABLE `dokumen_disposisi` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_usulan` int(10) UNSIGNED NOT NULL,
  `nomor_dokumen` varchar(100) NOT NULL,
  `judul_dokumen` varchar(180) DEFAULT 'Dokumen Disposisi Pengadaan',
  `file_path` varchar(255) DEFAULT NULL,
  `status_dokumen` enum('draft','preview','tervalidasi','dibatalkan') NOT NULL DEFAULT 'draft',
  `hash_dokumen` varchar(128) DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dokumen_disposisi`
--

INSERT INTO `dokumen_disposisi` (`id`, `id_usulan`, `nomor_dokumen`, `judul_dokumen`, `file_path`, `status_dokumen`, `hash_dokumen`, `created_by`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'PREVIEW/DISP/2026/06/3227', 'Dokumen Disposisi Pengadaan', NULL, 'preview', '4c90cf890c8139ec25352b74e4a645bb65878512d9cc460fdb8efd123cb40528', 4, NULL, NULL, '2026-06-16 19:49:10', '2026-06-16 19:49:10'),
(2, 23, 'PREVIEW/DISP/2026/06/4801', 'Dokumen Disposisi Pengadaan', NULL, 'preview', '2b5c12fe1f00f8d4fa322605b572fc76395809830f4779d862c2dd3e19e703d4', 4, NULL, NULL, '2026-06-17 11:25:52', '2026-06-17 11:25:52');

-- --------------------------------------------------------

--
-- Table structure for table `hasil_moora`
--

CREATE TABLE `hasil_moora` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_usulan` int(10) UNSIGNED NOT NULL,
  `id_detail_usulan` int(10) UNSIGNED NOT NULL,
  `nilai_yi` decimal(16,8) NOT NULL,
  `ranking` int(11) NOT NULL,
  `tanggal_hitung` datetime NOT NULL DEFAULT current_timestamp(),
  `versi_hitung` bigint(20) NOT NULL,
  `mode_hitung` varchar(30) NOT NULL DEFAULT 'item_based',
  `jenis_keputusan` varchar(30) DEFAULT NULL,
  `nilai_benefit` decimal(16,8) DEFAULT NULL,
  `nilai_cost` decimal(16,8) DEFAULT NULL,
  `rincian_json` longtext DEFAULT NULL,
  `catatan_hitung` varchar(255) DEFAULT NULL,
  `checksum_hash` varchar(128) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nilai_y` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hasil_moora`
--

INSERT INTO `hasil_moora` (`id`, `id_usulan`, `id_detail_usulan`, `nilai_yi`, `ranking`, `tanggal_hitung`, `versi_hitung`, `mode_hitung`, `jenis_keputusan`, `nilai_benefit`, `nilai_cost`, `rincian_json`, `catatan_hitung`, `checksum_hash`, `created_at`, `updated_at`, `nilai_y`) VALUES
(124, 23, 65, 0.48900000, 1, '2026-06-17 08:50:02', 1781661002813, 'rka_aggregate', 'RKA', 0.68900000, 0.20000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":39,\"pembagi\":39,\"normalisasi\":1,\"terbobot\":0.2},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kerusakan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.13999999999999999},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":46.5,\"pembagi\":46.5,\"normalisasi\":1,\"terbobot\":0.15},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":9.6,\"pembagi\":10,\"normalisasi\":0.96,\"terbobot\":0.144}},\"source_details\":[65,66,67,68,69],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":5}}', 'Hasil agregasi seluruh barang dalam dokumen RKA.', 'b278e03d0c90b00417a54de96138e88e78b5c95d059b7d2c5f39c6000c351a53', '2026-06-17 08:50:02', '2026-06-17 08:50:02', NULL),
(125, 12, 51, 0.60000000, 1, '2026-06-19 13:55:54', 1781852154961, 'item_based', 'Pesan Cepat', 0.62000000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":10,\"pembagi\":10,\"normalisasi\":1,\"terbobot\":0.3},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kerusakan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.13999999999999999},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[51],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', '0596ff63091b2d8cc2343036c2020e9ba87bba998ec3acf4a8cb2304d71053af', '2026-06-19 13:55:54', '2026-06-19 13:55:54', NULL),
(126, 24, 70, 0.56100000, 1, '2026-06-19 14:02:39', 1781852559764, 'item_based', 'Pesan Cepat', 0.70100000, 0.14000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":10,\"pembagi\":10,\"normalisasi\":1,\"terbobot\":0.3},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.13999999999999999},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kerusakan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.13999999999999999},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":9,\"pembagi\":10,\"normalisasi\":0.9,\"terbobot\":0.135},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":8.4,\"pembagi\":10,\"normalisasi\":0.8400000000000001,\"terbobot\":0.126}},\"source_details\":[70],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', '44dc6e81a06819829d2e4f432d39b5a3eaf02090ef1a95cb8e8ef45fd2b1ac46', '2026-06-19 14:02:39', '2026-06-19 14:02:39', NULL),
(127, 26, 76, 0.48900000, 1, '2026-06-23 12:59:27', 1782194367265, 'rka_aggregate', 'RKA', 0.68900000, 0.20000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":39,\"pembagi\":39,\"normalisasi\":1,\"terbobot\":0.2},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kerusakan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.13999999999999999},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":46.5,\"pembagi\":46.5,\"normalisasi\":1,\"terbobot\":0.15},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":9.6,\"pembagi\":10,\"normalisasi\":0.96,\"terbobot\":0.144}},\"source_details\":[76,77,78,79,80],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":5}}', 'Hasil agregasi seluruh barang dalam dokumen RKA.', 'cef75fd9726bd6d636ea23262a0b1fc6eb6869469e9bcabd12cc70ce59edf846', '2026-06-23 12:59:27', '2026-06-23 12:59:27', NULL),
(128, 27, 81, 0.53750000, 1, '2026-06-23 13:10:01', 1782195001023, 'item_based', 'Pesan Cepat', 0.63750000, 0.10000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":9.75,\"pembagi\":10,\"normalisasi\":0.975,\"terbobot\":0.2925},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":5,\"pembagi\":10,\"normalisasi\":0.5,\"terbobot\":0.1},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kerusakan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":9,\"pembagi\":10,\"normalisasi\":0.9,\"terbobot\":0.135},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[81],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', 'a7a5d5415e260d58a1ea1e30760ae5cfbda49e341d76ba0b6a43a5ff7a76e0ae', '2026-06-23 13:10:01', '2026-06-23 13:10:01', NULL),
(129, 28, 82, 0.47600000, 1, '2026-06-23 13:21:31', 1782195691268, 'item_based', 'Pesan Cepat', 0.65600000, 0.18000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":10,\"pembagi\":10,\"normalisasi\":1,\"terbobot\":0.3},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":9,\"pembagi\":10,\"normalisasi\":0.9,\"terbobot\":0.18000000000000002},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kerusakan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.13999999999999999},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":8.4,\"pembagi\":10,\"normalisasi\":0.8400000000000001,\"terbobot\":0.126}},\"source_details\":[82],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', 'c81d4c2eb59c31db774ebadd27175bb41f49a7173c14aafe617dc97a5a40820f', '2026-06-23 13:21:31', '2026-06-23 13:21:31', NULL),
(130, 28, 82, 0.47100000, 1, '2026-07-10 02:54:32', 1783626872736, 'item_based', 'Pesan Cepat', 0.65100000, 0.18000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":10,\"pembagi\":10,\"normalisasi\":1,\"terbobot\":0.3},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":9,\"pembagi\":10,\"normalisasi\":0.9,\"terbobot\":0.18000000000000002},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":8.4,\"pembagi\":10,\"normalisasi\":0.8400000000000001,\"terbobot\":0.126}},\"source_details\":[82],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', 'd4c4b98492903814df8a5c9fd27f8be9a0f0b37c0236b4656c1ed30147bf0613', '2026-07-10 02:54:32', '2026-07-10 02:54:32', NULL),
(131, 27, 81, 0.55250000, 1, '2026-07-10 02:54:32', 1783626872805, 'item_based', 'Pesan Cepat', 0.65250000, 0.10000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":9.75,\"pembagi\":10,\"normalisasi\":0.975,\"terbobot\":0.2925},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":5,\"pembagi\":10,\"normalisasi\":0.5,\"terbobot\":0.1},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":9,\"pembagi\":10,\"normalisasi\":0.9,\"terbobot\":0.18000000000000002},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[81],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', '5ec3fa576e4ec4fd15c1169cfc768e715b36eec67fe7d5bc3e4d41ca1cb26ac6', '2026-07-10 02:54:32', '2026-07-10 02:54:32', NULL),
(132, 26, 76, 0.50400000, 1, '2026-07-10 02:54:32', 1783626872989, 'rka_aggregate', 'RKA', 0.70400000, 0.20000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":39,\"pembagi\":39,\"normalisasi\":1,\"terbobot\":0.2},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":46.5,\"pembagi\":46.5,\"normalisasi\":1,\"terbobot\":0.2},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":9.6,\"pembagi\":10,\"normalisasi\":0.96,\"terbobot\":0.144}},\"source_details\":[76,77,78,79,80],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":5}}', 'Hasil agregasi seluruh barang dalam dokumen RKA.', '2d55bcc0915596dc7c26a4fb6e2fefca4c992038dfdee0ea7902a82e32769ba5', '2026-07-10 02:54:32', '2026-07-10 02:54:32', NULL),
(133, 24, 70, 0.57100000, 1, '2026-07-10 02:54:33', 1783626873037, 'item_based', 'Pesan Cepat', 0.71100000, 0.14000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":10,\"pembagi\":10,\"normalisasi\":1,\"terbobot\":0.3},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.13999999999999999},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":9,\"pembagi\":10,\"normalisasi\":0.9,\"terbobot\":0.18000000000000002},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":8.4,\"pembagi\":10,\"normalisasi\":0.8400000000000001,\"terbobot\":0.126}},\"source_details\":[70],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', 'f4c0703c9c195f70e66eae8a9e1d5adabb361aa3eea3da5ea93935c7029fd6ea', '2026-07-10 02:54:33', '2026-07-10 02:54:33', NULL),
(134, 12, 51, 0.59500000, 1, '2026-07-10 02:54:33', 1783626873076, 'item_based', 'Pesan Cepat', 0.61500000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":10,\"pembagi\":10,\"normalisasi\":1,\"terbobot\":0.3},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[51],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', '1cba81b765207860aa458047ef538565901a8d1e939178b01a57de51602b25dd', '2026-07-10 02:54:33', '2026-07-10 02:54:33', NULL),
(135, 21, 52, 0.55000000, 1, '2026-07-10 02:54:33', 1783626873116, 'rka_aggregate', 'RKA', 0.57000000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[52],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":1}}', 'Hasil agregasi seluruh barang dalam dokumen RKA.', 'e7959dd6725980130078194b1a046455bf39945af63d1b1157296effc10c5da9', '2026-07-10 02:54:33', '2026-07-10 02:54:33', NULL),
(136, 23, 65, 0.50400000, 1, '2026-07-10 02:54:33', 1783626873250, 'rka_aggregate', 'RKA', 0.70400000, 0.20000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":39,\"pembagi\":39,\"normalisasi\":1,\"terbobot\":0.2},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":46.5,\"pembagi\":46.5,\"normalisasi\":1,\"terbobot\":0.2},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":9.6,\"pembagi\":10,\"normalisasi\":0.96,\"terbobot\":0.144}},\"source_details\":[65,66,67,68,69],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":5}}', 'Hasil agregasi seluruh barang dalam dokumen RKA.', '9f1d8ae64b76482a5512c15528b7c154b20142092de979789758f16bd3e4cb76', '2026-07-10 02:54:33', '2026-07-10 02:54:33', NULL),
(137, 19, 59, 0.55000000, 1, '2026-07-10 02:54:33', 1783626873292, 'rka_aggregate', 'RKA', 0.57000000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[59],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":1}}', 'Hasil agregasi seluruh barang dalam dokumen RKA.', '29bc2fb30ce259a06e95c805e6afdccba99971fcfa06c750f047199cf079a053', '2026-07-10 02:54:33', '2026-07-10 02:54:33', NULL),
(138, 22, 61, 0.59500000, 1, '2026-07-10 02:54:33', 1783626873334, 'item_based', 'Pesan Cepat', 0.61500000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":10,\"pembagi\":10,\"normalisasi\":1,\"terbobot\":0.3},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[61],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', '4725670ac52314ecda24c5e609bc121a6b2f0b87aef52b2472503a90acedb861', '2026-07-10 02:54:33', '2026-07-10 02:54:33', NULL),
(139, 18, 58, 0.59500000, 1, '2026-07-10 02:54:33', 1783626873372, 'item_based', 'Pesan Cepat', 0.61500000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":10,\"pembagi\":10,\"normalisasi\":1,\"terbobot\":0.3},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[58],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', '938711894566eb5e9792e905d6a5012cb2e2f400f8ca0c84a50d119b4ed8604a', '2026-07-10 02:54:33', '2026-07-10 02:54:33', NULL),
(140, 20, 60, 0.59500000, 1, '2026-07-10 02:54:33', 1783626873410, 'item_based', 'Pesan Cepat', 0.61500000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":10,\"pembagi\":10,\"normalisasi\":1,\"terbobot\":0.3},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[60],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', '153ada1120e4609183c75bc372ecd9bccba0f9a44640af291bf01945cd3d223c', '2026-07-10 02:54:33', '2026-07-10 02:54:33', NULL),
(141, 1, 62, 0.56800000, 1, '2026-07-10 02:54:33', 1783626873448, 'rka_aggregate', 'RKA', 0.58800000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7.2,\"pembagi\":10,\"normalisasi\":0.72,\"terbobot\":0.108}},\"source_details\":[62],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":1}}', 'Hasil agregasi seluruh barang dalam dokumen RKA.', '1bc58ba89151527b9e8cca9d9a790de6d0ee9f40d743734c302654d367cac306', '2026-07-10 02:54:33', '2026-07-10 02:54:33', NULL),
(142, 17, 57, 0.55000000, 1, '2026-07-10 02:54:33', 1783626873485, 'rka_aggregate', 'RKA', 0.57000000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[57],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":1}}', 'Hasil agregasi seluruh barang dalam dokumen RKA.', 'f5559a8e5f9876b5a536e01a534b184c59162c114dc1ff2300c437b35aa0d4d0', '2026-07-10 02:54:33', '2026-07-10 02:54:33', NULL),
(143, 16, 56, 0.55000000, 1, '2026-07-10 02:54:33', 1783626873521, 'rka_aggregate', 'RKA', 0.57000000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[56],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":1}}', 'Hasil agregasi seluruh barang dalam dokumen RKA.', '4a44e6ca80e281e0765e348a4474530b3fc483eac3936d8778f5ea39a496e029', '2026-07-10 02:54:33', '2026-07-10 02:54:33', NULL),
(144, 26, 76, 0.50400000, 1, '2026-07-10 02:57:23', 1783627043635, 'rka_aggregate', 'RKA', 0.70400000, 0.20000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":39,\"pembagi\":39,\"normalisasi\":1,\"terbobot\":0.2},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":46.5,\"pembagi\":46.5,\"normalisasi\":1,\"terbobot\":0.2},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":9.6,\"pembagi\":10,\"normalisasi\":0.96,\"terbobot\":0.144}},\"source_details\":[76,77,78,79,80],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":5}}', 'Patch 11: Ranking global RKA dihitung dalam satu dataset antar dokumen RKA aktif.', '486b9bf29c49b6a5a3ff46c3d1ac66c092abb3f349183f6ca1aef8143912de4f', '2026-07-10 02:57:23', '2026-07-10 02:57:23', NULL),
(145, 26, 76, 0.50400000, 1, '2026-07-10 02:58:21', 1783627101534, 'rka_aggregate', 'RKA', 0.70400000, 0.20000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":39,\"pembagi\":39,\"normalisasi\":1,\"terbobot\":0.2},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":46.5,\"pembagi\":46.5,\"normalisasi\":1,\"terbobot\":0.2},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":9.6,\"pembagi\":10,\"normalisasi\":0.96,\"terbobot\":0.144}},\"source_details\":[76,77,78,79,80],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":5}}', 'Patch 11: Ranking global RKA dihitung dalam satu dataset antar dokumen RKA aktif.', 'd50489aad59cab9c318fbcf8870715c00f9f718b8fd54ac1adf100e7b03017f5', '2026-07-10 02:58:21', '2026-07-10 02:58:21', NULL),
(146, 28, 82, 0.47100000, 1, '2026-07-10 02:59:32', 1783627172801, 'item_based', 'Pesan Cepat', 0.65100000, 0.18000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":10,\"pembagi\":10,\"normalisasi\":1,\"terbobot\":0.3},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":9,\"pembagi\":10,\"normalisasi\":0.9,\"terbobot\":0.18000000000000002},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":8.4,\"pembagi\":10,\"normalisasi\":0.8400000000000001,\"terbobot\":0.126}},\"source_details\":[82],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', '0c2d0a75caa3e31766e91b37ff6d90f8345225dfa85c35662502fc0add8e41ee', '2026-07-10 02:59:32', '2026-07-10 02:59:32', NULL),
(147, 27, 81, 0.55250000, 1, '2026-07-10 02:59:32', 1783627172860, 'item_based', 'Pesan Cepat', 0.65250000, 0.10000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":9.75,\"pembagi\":10,\"normalisasi\":0.975,\"terbobot\":0.2925},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":5,\"pembagi\":10,\"normalisasi\":0.5,\"terbobot\":0.1},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":9,\"pembagi\":10,\"normalisasi\":0.9,\"terbobot\":0.18000000000000002},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[81],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', '94a7635e2cfd43e2ea157e3b5f018313a41196d17dd96ad3244cd3c120fbff7a', '2026-07-10 02:59:32', '2026-07-10 02:59:32', NULL),
(148, 26, 76, 0.50400000, 1, '2026-07-10 02:59:33', 1783627173009, 'rka_aggregate', 'RKA', 0.70400000, 0.20000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":39,\"pembagi\":39,\"normalisasi\":1,\"terbobot\":0.2},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":46.5,\"pembagi\":46.5,\"normalisasi\":1,\"terbobot\":0.2},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":9.6,\"pembagi\":10,\"normalisasi\":0.96,\"terbobot\":0.144}},\"source_details\":[76,77,78,79,80],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":5}}', 'Hasil agregasi seluruh barang dalam dokumen RKA.', '532b1891a65a8555ef73e3410367720e7c7d4b2a37675a048442bd180046b280', '2026-07-10 02:59:33', '2026-07-10 02:59:33', NULL),
(149, 24, 70, 0.57100000, 1, '2026-07-10 02:59:33', 1783627173050, 'item_based', 'Pesan Cepat', 0.71100000, 0.14000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":10,\"pembagi\":10,\"normalisasi\":1,\"terbobot\":0.3},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.13999999999999999},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":9,\"pembagi\":10,\"normalisasi\":0.9,\"terbobot\":0.18000000000000002},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":8.4,\"pembagi\":10,\"normalisasi\":0.8400000000000001,\"terbobot\":0.126}},\"source_details\":[70],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', '260c52a01f92bcd911469a13567934f45ac3df221b2ad875ae54d0598d9b0a87', '2026-07-10 02:59:33', '2026-07-10 02:59:33', NULL),
(150, 12, 51, 0.59500000, 1, '2026-07-10 02:59:33', 1783627173087, 'item_based', 'Pesan Cepat', 0.61500000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":10,\"pembagi\":10,\"normalisasi\":1,\"terbobot\":0.3},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[51],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', '0e532e2cd457f5a8541a32b5f8e568b2cbb7b5758f655a3858dce1df021c9439', '2026-07-10 02:59:33', '2026-07-10 02:59:33', NULL),
(151, 21, 52, 0.55000000, 1, '2026-07-10 02:59:33', 1783627173122, 'rka_aggregate', 'RKA', 0.57000000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[52],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":1}}', 'Hasil agregasi seluruh barang dalam dokumen RKA.', '65d77befd8743a510214027872eef7540a0eca4de2f36c6ac6bbd1892163d4d3', '2026-07-10 02:59:33', '2026-07-10 02:59:33', NULL),
(152, 23, 65, 0.50400000, 1, '2026-07-10 02:59:33', 1783627173240, 'rka_aggregate', 'RKA', 0.70400000, 0.20000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":39,\"pembagi\":39,\"normalisasi\":1,\"terbobot\":0.2},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":46.5,\"pembagi\":46.5,\"normalisasi\":1,\"terbobot\":0.2},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":9.6,\"pembagi\":10,\"normalisasi\":0.96,\"terbobot\":0.144}},\"source_details\":[65,66,67,68,69],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":5}}', 'Hasil agregasi seluruh barang dalam dokumen RKA.', '80ab1b164394768cfc5db419cb810f0c9cee08921016b555cf1cc2ec2b39e8ce', '2026-07-10 02:59:33', '2026-07-10 02:59:33', NULL),
(153, 19, 59, 0.55000000, 1, '2026-07-10 02:59:33', 1783627173277, 'rka_aggregate', 'RKA', 0.57000000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[59],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":1}}', 'Hasil agregasi seluruh barang dalam dokumen RKA.', 'cc3d103e750a8f04003988e7664b4b66d2040e6c8af0fea6c771382f63778929', '2026-07-10 02:59:33', '2026-07-10 02:59:33', NULL),
(154, 22, 61, 0.59500000, 1, '2026-07-10 02:59:33', 1783627173313, 'item_based', 'Pesan Cepat', 0.61500000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":10,\"pembagi\":10,\"normalisasi\":1,\"terbobot\":0.3},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[61],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', 'a0666e0ed248ed43d28f54e1c2d2b8094e31941a564631833835409ceaf4d488', '2026-07-10 02:59:33', '2026-07-10 02:59:33', NULL),
(155, 18, 58, 0.59500000, 1, '2026-07-10 02:59:33', 1783627173349, 'item_based', 'Pesan Cepat', 0.61500000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":10,\"pembagi\":10,\"normalisasi\":1,\"terbobot\":0.3},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[58],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', '871f21f821b6d3e9e70631590c9d61f18e82549cd6374b1974e75cfc35f01e39', '2026-07-10 02:59:33', '2026-07-10 02:59:33', NULL),
(156, 20, 60, 0.59500000, 1, '2026-07-10 02:59:33', 1783627173383, 'item_based', 'Pesan Cepat', 0.61500000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":10,\"pembagi\":10,\"normalisasi\":1,\"terbobot\":0.3},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[60],\"aggregate_meta\":null}', 'Hasil per item barang Pesan Cepat.', 'a0e798758b26b97edbcee7a30f88e04e7dc62b4636bcfafdc187980d6826b193', '2026-07-10 02:59:33', '2026-07-10 02:59:33', NULL),
(157, 1, 62, 0.56800000, 1, '2026-07-10 02:59:33', 1783627173421, 'rka_aggregate', 'RKA', 0.58800000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7.2,\"pembagi\":10,\"normalisasi\":0.72,\"terbobot\":0.108}},\"source_details\":[62],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":1}}', 'Hasil agregasi seluruh barang dalam dokumen RKA.', '459eca3150a3559d0048a7362bb48d5ee3680abbbe7ce4f1e645b54a66c70dfd', '2026-07-10 02:59:33', '2026-07-10 02:59:33', NULL),
(158, 17, 57, 0.55000000, 1, '2026-07-10 02:59:33', 1783627173457, 'rka_aggregate', 'RKA', 0.57000000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[57],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":1}}', 'Hasil agregasi seluruh barang dalam dokumen RKA.', 'f4d0817de4c1688ada5b3c8461cd099574b8a3fe31e1c091a36594586135512f', '2026-07-10 02:59:33', '2026-07-10 02:59:33', NULL),
(159, 16, 56, 0.55000000, 1, '2026-07-10 02:59:33', 1783627173494, 'rka_aggregate', 'RKA', 0.57000000, 0.02000000, '{\"kriteria\":{\"1\":{\"kode_kriteria\":\"C1\",\"nama_kriteria\":\"Tingkat Urgensi\",\"jenis\":\"benefit\",\"bobot\":0.3,\"nilai_awal\":8.5,\"pembagi\":10,\"normalisasi\":0.85,\"terbobot\":0.255},\"2\":{\"kode_kriteria\":\"C2\",\"nama_kriteria\":\"Biaya Pengadaan\",\"jenis\":\"cost\",\"bobot\":0.2,\"nilai_awal\":1,\"pembagi\":10,\"normalisasi\":0.1,\"terbobot\":0.020000000000000004},\"3\":{\"kode_kriteria\":\"C3\",\"nama_kriteria\":\"Tingkat Kelayakan\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":7,\"pembagi\":10,\"normalisasi\":0.7,\"terbobot\":0.105},\"4\":{\"kode_kriteria\":\"C4\",\"nama_kriteria\":\"Frekuensi Penggunaan\",\"jenis\":\"benefit\",\"bobot\":0.2,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.12},\"5\":{\"kode_kriteria\":\"C5\",\"nama_kriteria\":\"Dampak Operasional\",\"jenis\":\"benefit\",\"bobot\":0.15,\"nilai_awal\":6,\"pembagi\":10,\"normalisasi\":0.6,\"terbobot\":0.09}},\"source_details\":[56],\"aggregate_meta\":{\"jenis_agregasi\":{\"C1\":\"MAX\",\"C2\":\"SUM\",\"C3\":\"AVG\",\"C4\":\"SUM\",\"C5\":\"MAX\"},\"jumlah_barang\":1}}', 'Hasil agregasi seluruh barang dalam dokumen RKA.', '9c4de8004b0c966d756819d0d9ef58b7112ccb5bbd3bc6f7e637385621b69cf2', '2026-07-10 02:59:33', '2026-07-10 02:59:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_kriteria` varchar(10) NOT NULL,
  `nama_kriteria` varchar(100) NOT NULL,
  `jenis` enum('benefit','cost') NOT NULL,
  `bobot` decimal(5,2) NOT NULL,
  `skala_min` int(11) NOT NULL DEFAULT 1,
  `skala_max` int(11) NOT NULL DEFAULT 10,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id`, `kode_kriteria`, `nama_kriteria`, `jenis`, `bobot`, `skala_min`, `skala_max`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'C1', 'Tingkat Urgensi', 'benefit', 0.30, 1, 10, 1, '2026-04-16 15:05:21', '2026-06-17 08:42:49'),
(2, 'C2', 'Biaya Pengadaan', 'cost', 0.20, 1, 10, 1, '2026-04-16 15:05:21', '2026-06-17 08:42:49'),
(3, 'C3', 'Tingkat Kelayakan', 'benefit', 0.15, 1, 10, 1, '2026-04-16 15:05:21', '2026-06-25 11:29:06'),
(4, 'C4', 'Frekuensi Penggunaan', 'benefit', 0.20, 1, 10, 1, '2026-04-16 15:05:21', '2026-06-25 11:29:21'),
(5, 'C5', 'Dampak Operasional', 'benefit', 0.15, 1, 10, 1, '2026-04-16 15:05:21', '2026-06-17 08:42:49');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED DEFAULT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `modul` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `id_user`, `aktivitas`, `modul`, `keterangan`, `ip_address`, `created_at`) VALUES
(1, 1, 'Membuat seed awal database final SPK MOORA', 'database', NULL, '127.0.0.1', '2026-04-16 15:05:21'),
(2, 1, 'Login', '', NULL, NULL, '2026-04-26 16:52:51'),
(3, 1, 'Logout', '', NULL, NULL, '2026-04-26 17:39:27'),
(4, 5, 'Login', '', NULL, NULL, '2026-04-26 17:40:21'),
(5, 5, 'Logout', '', NULL, NULL, '2026-04-26 18:00:46'),
(6, 2, 'Login', '', NULL, NULL, '2026-04-26 18:27:13'),
(7, 2, 'Logout', '', NULL, NULL, '2026-04-26 19:10:49'),
(8, 2, 'Login', '', NULL, NULL, '2026-04-26 19:10:57'),
(9, 2, 'Login', '', NULL, NULL, '2026-04-27 08:54:16'),
(10, 2, 'Login', '', NULL, NULL, '2026-04-27 18:32:02'),
(11, 2, 'Penerimaan Barang', 'Gudang', 'Menerima barang Genset Operasional sebanyak 1 unit. Stok awal: 26, stok akhir: 27. Catatan: sa', '::1', '2026-04-27 19:40:47'),
(12, 2, 'Penerimaan Barang', 'Gudang', 'Menerima barang Genset Operasional sebanyak 3 unit. Stok awal: 27, stok akhir: 30. Catatan: -', '::1', '2026-04-27 19:40:58'),
(13, 2, 'Penerimaan Barang', 'Gudang', 'User: Seksi Gudang. Menerima barang: Genset Operasional. Jumlah diterima: 1 unit. Asal/Sumber barang: Umum. Stok awal: 30. Stok akhir: 31. Catatan: kak dedi', '::1', '2026-04-27 20:02:17'),
(14, 2, 'Penerimaan Barang', 'Gudang', 'User: Seksi Gudang. Menerima barang: Genset Operasional. Jumlah diterima: 1 unit. Asal/Sumber barang: pengadaan. Stok awal: 31. Stok akhir: 32. Catatan: Resi 1209182012', '::1', '2026-04-27 20:21:30'),
(15, 2, 'Update Stok Barang', 'gudang', 'Mengubah stok barang Genset Operasional. Stok awal: 32, stok baru: 20. Minimum awal: 5, minimum baru: 5.', '::1', '2026-04-27 20:22:03'),
(16, 2, 'Stock Opname', 'gudang', 'Melakukan stock opname barang Genset Operasional. Stok sistem awal: 20, stok fisik baru: 20. Minimum awal: 5, minimum baru: 30.', '::1', '2026-04-27 20:22:25'),
(17, 2, 'Pengambilan Barang', 'Gudang', 'User: Seksi Gudang. Mengeluarkan barang: Genset Operasional. Jumlah diambil: 5 unit. Tujuan/Unit penerima: pka. Stok awal: 20. Stok akhir: 15. Catatan: perbaikan', '::1', '2026-04-27 20:23:00'),
(18, 2, 'Stock Opname', 'gudang', 'Melakukan stock opname barang Genset Operasional. Stok sistem awal: 15, stok fisik baru: 0. Minimum awal: 30, minimum baru: 30.', '::1', '2026-04-27 20:24:16'),
(19, 2, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-04-27 20:26:23'),
(20, 4, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-04-27 20:47:06'),
(21, 4, 'Validasi Direktur', 'Direktur', 'Direktur menyetujui usulan UP-2026-001.', '::1', '2026-04-27 20:52:29'),
(22, 4, 'Penolakan Direktur', 'Direktur', 'Direktur menolak usulan UP-20260426-001. Catatan: perbaiki', '::1', '2026-04-27 20:53:03'),
(23, 1, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-04-29 14:20:42'),
(24, 1, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-04-29 14:33:11'),
(25, 4, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-04-29 14:33:20'),
(26, 4, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-04-29 14:46:02'),
(27, 2, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-04-29 14:46:10'),
(28, 2, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-04-29 14:52:34'),
(29, 1, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-04-29 14:52:46'),
(30, 1, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-04-30 21:14:44'),
(31, 1, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-05-02 14:07:02'),
(32, 1, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-05-02 14:08:26'),
(33, 1, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-05-02 14:08:29'),
(34, 1, 'Menambahkan data perbaikan alat', 'Perbaikan Alat', 'Alat Genset Operasional diperbaiki oleh unit Produksi', '::1', '2026-05-02 14:23:25'),
(35, 1, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-05-07 13:02:33'),
(36, 1, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-05-07 14:07:18'),
(37, 5, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-05-07 14:07:40'),
(38, 1, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-08 22:21:20'),
(39, 1, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-08 22:25:48'),
(40, 5, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-08 22:27:18'),
(41, 5, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-08 23:24:34'),
(42, 1, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-08 23:25:59'),
(43, 5, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-11 19:39:15'),
(44, 5, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-11 23:18:02'),
(45, 2, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-11 23:42:56'),
(46, 2, 'Pengambilan Barang', 'Gudang', 'User: Seksi Gudang. Mengeluarkan barang: Mesin Pompa Air Sentrifugal. Jumlah diambil: 1 unit. Tujuan/Unit penerima: Umum. Stok awal: 10. Stok akhir: 9. Catatan: pinjam', '::1', '2026-06-12 03:12:14'),
(47, 2, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 03:23:37'),
(48, 5, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 03:24:12'),
(49, 5, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 03:25:10'),
(50, 2, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 03:25:30'),
(51, 2, 'Stock Opname', 'gudang', 'Melakukan stock opname barang Genset Operasional. Stok sistem awal: 0, stok fisik baru: 10. Minimum awal: 30, minimum baru: 2.', '::1', '2026-06-12 05:24:18'),
(52, 2, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 06:27:33'),
(53, 2, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 06:28:10'),
(54, 2, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 06:31:52'),
(55, 2, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 06:32:03'),
(56, 2, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 06:34:52'),
(57, 3, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 06:52:09'),
(58, 3, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 07:09:20'),
(59, 3, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 07:09:46'),
(60, 3, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 07:09:54'),
(61, 4, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 07:10:12'),
(62, 4, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 07:10:26'),
(63, 3, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 07:10:50'),
(64, 3, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 07:22:04'),
(65, 3, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 07:22:23'),
(66, 3, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 07:23:44'),
(67, 3, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 07:24:03'),
(68, 3, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 07:37:48'),
(69, 3, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 07:38:08'),
(70, 3, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 08:52:21'),
(71, 5, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 08:53:30'),
(72, 5, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 08:54:34'),
(73, 2, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 08:54:46'),
(74, 2, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 08:57:37'),
(75, 3, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 08:57:53'),
(76, 3, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 09:03:03'),
(77, 5, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 09:03:20'),
(78, 5, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 09:03:48'),
(79, 3, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 09:05:23'),
(80, 3, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 10:24:56'),
(81, 4, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 10:25:17'),
(82, 4, 'Penolakan Direktur', 'Direktur', 'Direktur menolak usulan UP-20260612-001. Catatan: belum disetujui', '::1', '2026-06-12 10:26:15'),
(83, 4, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 10:26:49'),
(84, 3, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 10:27:08'),
(85, 3, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 10:45:30'),
(86, 5, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 10:45:59'),
(87, 5, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 10:46:43'),
(88, 3, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 10:47:38'),
(89, 3, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 11:33:39'),
(90, 1, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 11:33:46'),
(91, 1, 'Memperbarui data perbaikan alat', 'Perbaikan Alat', 'Data perbaikan alat pada unit Produksi diperbarui.', '::1', '2026-06-12 11:37:28'),
(92, 1, 'Memperbarui data perbaikan alat', 'Perbaikan Alat', 'Data perbaikan alat pada unit Produksi diperbarui.', '::1', '2026-06-12 11:37:45'),
(93, 1, 'Menambahkan data perbaikan alat', 'Perbaikan Alat', 'Alat Mesin Pompa Air Sentrifugal diperbaiki oleh unit distribusi', '::1', '2026-06-12 11:38:45'),
(94, 1, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 11:46:16'),
(95, 1, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 11:50:17'),
(96, 1, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 13:18:00'),
(97, 4, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 13:19:26'),
(98, 4, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 13:20:30'),
(99, 3, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 13:21:09'),
(100, 3, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 13:45:03'),
(101, 2, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 13:45:52'),
(102, 2, 'Pengambilan Barang', 'Gudang', 'User: Seksi Gudang. Mengeluarkan barang: Water Meter Industri. Jumlah diambil: 1 unit. Tujuan/Unit penerima: produksi. Stok awal: 300. Stok akhir: 299. Catatan: nando', '::1', '2026-06-12 13:47:06'),
(103, 2, 'Pengambilan Barang', 'Gudang', 'User: Seksi Gudang. Mengeluarkan barang: Genset Operasional. Jumlah diambil: 1 unit. Tujuan/Unit penerima: produksi. Stok awal: 10. Stok akhir: 9. Catatan: redo', '::1', '2026-06-12 13:47:32'),
(104, 2, 'Penerimaan Barang', 'Gudang', 'User: Seksi Gudang. Menerima barang: Sensor Monitoring Tekanan Air. Jumlah diterima: 1 unit. Asal/Sumber barang: pengadaan. Stok awal: 2. Stok akhir: 3. Catatan: rudi', '::1', '2026-06-12 13:48:12'),
(105, 2, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 13:48:33'),
(106, 4, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 13:48:47'),
(107, 1, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 18:04:02'),
(108, 1, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 18:05:19'),
(109, 3, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 18:05:52'),
(110, 3, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-12 18:06:48'),
(111, 5, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-12 18:06:56'),
(112, 3, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-13 08:17:44'),
(113, 3, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-13 08:18:16'),
(114, 5, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-13 08:18:26'),
(115, 5, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-13 08:19:39'),
(116, 2, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-13 08:19:53'),
(117, 2, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-13 08:20:54'),
(118, 3, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-13 08:21:02'),
(119, 3, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-13 08:27:14'),
(120, 1, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-13 08:27:20'),
(121, 1, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-13 08:30:52'),
(122, 5, 'Login', '', 'Pengguna login ke sistem', NULL, '2026-06-13 10:13:42'),
(123, 5, 'Logout', '', 'Pengguna logout dari sistem', NULL, '2026-06-13 12:10:42'),
(124, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-13 13:09:42'),
(125, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-13 13:09:51'),
(126, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-13 13:10:10'),
(127, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-13 13:10:19'),
(128, 3, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-13 13:10:33'),
(129, 3, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-13 13:10:38'),
(130, 4, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-13 13:10:52'),
(131, 4, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-13 13:10:59'),
(132, 5, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-13 13:11:17'),
(133, 5, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-13 13:11:27'),
(134, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-13 13:13:01'),
(135, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-13 13:13:12'),
(136, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-13 13:13:41'),
(137, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-13 13:13:48'),
(138, 3, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-13 13:14:02'),
(139, 3, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-13 13:14:20'),
(140, 4, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-13 13:14:27'),
(141, 4, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-13 13:14:33'),
(142, 5, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-13 13:14:45'),
(143, 5, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-13 13:14:51'),
(144, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-13 13:31:57'),
(145, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-14 15:42:51'),
(146, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-14 23:02:40'),
(147, 1, 'Approval Registrasi User', 'Administrator', 'Menyetujui akun Suma123 sebagai Sub Unit.', '::1', '2026-06-14 23:51:31'),
(148, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-15 09:08:46'),
(149, 1, 'Penolakan Registrasi User', 'Administrator', 'Menolak akun dima. Catatan: maaf identitas belum jelas', '::1', '2026-06-15 09:09:46'),
(150, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-15 09:10:23'),
(151, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-15 09:10:41'),
(152, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-15 09:10:51'),
(153, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-15 09:12:10'),
(154, 1, 'Approval Registrasi User', 'Administrator', 'Menyetujui akun suma_Sub Unit sebagai Sub Unit.', '::1', '2026-06-15 09:12:38'),
(155, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-15 10:30:45'),
(156, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-15 10:31:59'),
(157, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-15 10:35:51'),
(158, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-15 10:36:05'),
(159, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-16 00:34:26'),
(160, 1, 'Kalkulasi MOORA', 'Administrator', 'Administrator memproses ulang MOORA untuk usulan UP-2026-001 dengan versi hitung 1781552173.', '::1', '2026-06-16 02:36:13'),
(161, 1, 'Update Setting Sistem', 'Administrator', 'Administrator memperbarui konfigurasi umum dan MOORA.', '::1', '2026-06-16 02:45:07'),
(162, 1, 'Update Setting Sistem', 'Administrator', 'Administrator memperbarui konfigurasi umum dan MOORA.', '::1', '2026-06-16 02:45:15'),
(163, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-16 02:57:36'),
(164, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-16 12:19:08'),
(165, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-16 12:22:58'),
(166, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-16 12:27:18'),
(167, 1, 'Approval Registrasi User', 'Administrator', 'Menyetujui akun Fia_SekretUmum sebagai Sub Unit.', '::1', '2026-06-16 12:27:50'),
(168, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-16 13:09:12'),
(169, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-16 13:09:19'),
(170, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-16 13:10:27'),
(171, 9, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-16 13:10:49'),
(172, 9, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-16 13:19:15'),
(173, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-16 13:19:24'),
(174, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-16 17:16:28'),
(175, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-16 17:22:00'),
(176, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-16 17:22:08'),
(177, 1, 'Normalisasi Workflow Status', 'Administrator', 'Patch workflow status final dijalankan: status usulan diselaraskan ke alur antar-role final tanpa penghapusan data.', '127.0.0.1', '2026-06-16 17:50:02'),
(178, 1, 'Patch Workflow V3 Enterprise', 'Administrator', 'Patch workflow sisa, notifikasi UI, dokumen disposisi digital, dan pengadaan lanjutan dijalankan secara non-destruktif.', '127.0.0.1', '2026-06-16 19:33:18'),
(179, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-16 19:39:40'),
(180, 5, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-16 19:40:22'),
(181, 5, 'Ajukan', 'Sub Unit', 'Status usulan UP-20260616-001 diubah dari draft ke diajukan. Catatan: Usulan diajukan Sub Unit ke Seksi Gudang.', '::1', '2026-06-16 19:41:53'),
(182, 5, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-16 19:42:34'),
(183, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-16 19:42:45'),
(184, 2, 'Verifikasi', 'Gudang', 'Status usulan UP-20260616-001 diubah dari diajukan ke diverifikasi. Catatan: Usulan diverifikasi Gudang dan siap masuk dataset MOORA.', '::1', '2026-06-16 19:43:06'),
(185, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-16 19:46:14'),
(186, 3, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-16 19:46:36'),
(187, 3, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-16 19:48:23'),
(188, 4, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-16 19:48:30'),
(189, 4, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-16 19:53:41'),
(190, 9, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-16 19:53:53'),
(191, 9, 'Pembelian', 'Pengadaan', 'Status usulan UP-2026-001 diubah dari disposisi_pengadaan ke diproses_pengadaan. Catatan: sudah dibeli semua', '::1', '2026-06-16 19:55:44'),
(192, 9, 'Update Status Pengadaan', 'Pengadaan', 'Status pengadaan ID 1 diperbarui menjadi menunggu.', '::1', '2026-06-16 19:56:21'),
(193, 9, 'Update Status Pengadaan', 'Pengadaan', 'Status pengadaan ID 1 diperbarui menjadi diproses.', '::1', '2026-06-16 19:56:26'),
(194, 9, 'Update Status Pengadaan', 'Pengadaan', 'Status pengadaan ID 1 diperbarui menjadi po_terbit.', '::1', '2026-06-16 19:56:31'),
(195, 9, 'Update Status Pengadaan', 'Pengadaan', 'Status pengadaan ID 1 diperbarui menjadi barang_datang.', '::1', '2026-06-16 19:56:40'),
(196, 9, 'Serah Barang', 'Pengadaan', 'Status usulan UP-2026-001 diubah dari diproses_pengadaan ke menunggu_penerimaan. Catatan: proses diserahkan', '::1', '2026-06-16 19:57:35'),
(197, 9, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-16 19:57:59'),
(198, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-16 19:58:05'),
(199, 2, 'Penerimaan', 'Gudang', 'Status usulan UP-2026-001 diubah dari menunggu_penerimaan ke selesai. Catatan: Diterima Gudang', '::1', '2026-06-16 19:58:26'),
(200, 2, 'Penerimaan Barang', 'Gudang', 'User: Seksi Gudang. Menerima barang: Pipa Distribusi HDPE. Jumlah diterima: 20 batang. Asal/Sumber barang: pengadaan. Stok awal: 30. Stok akhir: 50. Catatan: barang masuk dari hasil rka psdm', '::1', '2026-06-16 19:59:19'),
(201, 1, 'Patch V4 Final Engine', 'Administrator', 'Patch V4 Final Engine Ready Install dijalankan: Gudang menjadi engine MOORA operasional, RKA agregat, Pesan Cepat per item, metadata hasil dan audit engine ditambahkan secara non-destruktif.', '127.0.0.1', '2026-06-16 22:38:23'),
(202, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-16 22:42:55'),
(203, 1, 'Update Setting Sistem', 'Administrator', 'Administrator memperbarui konfigurasi umum dan MOORA.', '::1', '2026-06-16 22:43:37'),
(204, 1, 'Update Bobot MOORA', 'Administrator', 'Administrator memperbarui bobot dan jenis kriteria. Total bobot aktif: 1.0000', '::1', '2026-06-16 22:43:49'),
(205, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-16 22:46:10'),
(206, 5, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-16 22:46:34'),
(207, 5, 'Ajukan', 'Sub Unit', 'Status usulan UP-20260616-002 diubah dari draft ke diajukan. Catatan: Usulan diajukan Sub Unit ke Seksi Gudang.', '::1', '2026-06-16 22:48:01'),
(208, 5, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-16 22:48:18'),
(209, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-16 22:48:25'),
(210, 1, 'Patch V5 Konsolidasi Engine', 'Administrator', 'Patch V5 dijalankan: konsolidasi V4 engine, audit moora_engine_log, mode RKA agregat dan Pesan Cepat item-based, serta UI hasil final diperkuat.', '127.0.0.1', '2026-06-16 23:04:35'),
(211, 1, 'Patch V6 Bugfix Audit Engine', 'Administrator', 'Patch V6 dijalankan: mode RKA dikunci ke rka_aggregate, Pesan Cepat ke item_based, checksum dan audit engine dibackfill secara non-destruktif.', '127.0.0.1', '2026-06-16 17:09:20'),
(212, 1, 'Patch 7.2 Final Lock Enterprise', 'MOORA Engine', 'Patch 7.2 dijalankan: v_latest_moora dual-mode safe, workflow locked view, global final ranking, dan setting lock diperbarui secara non-destruktif.', '127.0.0.1', '2026-06-17 01:02:22'),
(213, 1, 'Patch 8 Final Demo Workflow Lock', 'Workflow', 'Patch 8 dijalankan: RKA latest satu baris, closing loop distribusi dikunci, usulan selesai hanya setelah konfirmasi Sub Unit.', '127.0.0.1', '2026-06-17 01:59:32'),
(214, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 01:59:52'),
(215, 5, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 02:00:21'),
(216, 5, 'Ajukan', 'Sub Unit', 'Status usulan ID 19 diubah menjadi diajukan. Usulan diajukan Sub Unit ke Seksi Gudang.', '::1', '2026-06-17 02:01:14'),
(217, 5, 'Ajukan', 'Sub Unit', 'Status usulan ID 20 diubah menjadi diajukan. Usulan diajukan Sub Unit ke Seksi Gudang.', '::1', '2026-06-17 02:02:51'),
(218, 5, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 02:03:11'),
(219, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 02:03:23'),
(220, 2, 'Verifikasi', 'Gudang', 'Status usulan ID 20 diubah menjadi diverifikasi. Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '::1', '2026-06-17 02:03:56'),
(221, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 02:05:16'),
(222, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 02:05:41'),
(223, 2, 'Verifikasi', 'Gudang', 'Status usulan ID 18 diubah menjadi diverifikasi. Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '::1', '2026-06-17 02:06:27'),
(224, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 02:07:03'),
(225, 3, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 02:07:11'),
(226, 3, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 02:08:03'),
(227, 4, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 02:08:09'),
(228, 4, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 02:08:45'),
(229, 9, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 02:08:58'),
(230, 1, 'Patch 9 Final Stabilization Lock', 'Workflow/MOORA', 'Patch 9 dijalankan: route/view path dikunci, Admin penilaian lama dialihkan, hasil aktif memakai v_latest_moora_context, histori hasil_moora tidak dihapus, dokumen RKA terintegrasi.', '127.0.0.1', '2026-06-17 03:20:22'),
(231, 1, 'Patch 10 Final Completion Enterprise', 'Workflow/MOORA/Pengadaan', 'Patch 10 dijalankan: active workflow completion, single-source result query, RKA Excel+dokumen, guard dokumen pengadaan, dan closing loop Sub Unit dikunci secara non-destruktif.', '127.0.0.1', '2026-06-17 04:05:00'),
(232, 5, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 04:15:03'),
(233, 5, 'Ajukan', 'Sub Unit', 'Status usulan ID 21 diubah menjadi diajukan. Usulan diajukan Sub Unit ke Seksi Gudang.', '::1', '2026-06-17 04:16:22'),
(234, 5, 'Ajukan', 'Sub Unit', 'Status usulan ID 22 diubah menjadi diajukan. Usulan diajukan Sub Unit ke Seksi Gudang.', '::1', '2026-06-17 04:16:53'),
(235, 5, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 04:17:09'),
(236, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 04:17:24'),
(237, 2, 'Verifikasi', 'Gudang', 'Status usulan ID 22 diubah menjadi diverifikasi. Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '::1', '2026-06-17 04:17:49'),
(238, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 04:23:47'),
(239, 3, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 04:23:59'),
(240, 3, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 04:29:50'),
(241, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 04:29:57'),
(242, NULL, 'Patch 11 Urgent MOORA Workflow Fix', 'Workflow/MOORA/Gudang', 'Patch 11 dijalankan: updateOrInsert fix, global ranking view, sinkron stok, admin training, dan dokumen pengadaan metadata backfill.', '127.0.0.1', '2026-06-17 04:40:05'),
(243, 2, 'Verifikasi', 'Gudang', 'Status usulan ID 19 diubah menjadi diverifikasi. Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '::1', '2026-06-17 05:37:06'),
(244, NULL, 'Patch Auto Fix Full System', 'Workflow/MOORA/DataFlow', 'SQL auto fix dijalankan: detail_usulan fallback, penilaian minimum, dan health view dibuat secara non-destruktif.', '127.0.0.1', '2026-06-17 08:29:19'),
(245, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 08:29:26'),
(246, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 08:41:06'),
(247, 1, 'Update Bobot MOORA', 'Administrator', 'Administrator memperbarui bobot dan jenis kriteria. Total bobot aktif: 1.0000', '::1', '2026-06-17 08:42:49'),
(248, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 08:44:29'),
(249, 5, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 08:44:45'),
(250, 5, 'Ajukan', 'Sub Unit', 'Status usulan ID 23 diubah menjadi diajukan. Usulan diajukan Sub Unit ke Seksi Gudang.', '::1', '2026-06-17 08:46:45'),
(251, 5, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 08:47:17'),
(252, 3, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 08:47:33'),
(253, 3, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 08:49:31'),
(254, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 08:49:39'),
(255, 2, 'Verifikasi', 'Gudang', 'Status usulan ID 23 diubah menjadi diverifikasi. Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '::1', '2026-06-17 08:49:54'),
(256, 2, 'Proses Moora', 'Gudang', 'Status usulan ID 23 diubah menjadi moora_selesai. Gudang memproses MOORA final mode RKA - Agregasi Dokumen. Versi hitung: 1781661002813', '::1', '2026-06-17 08:50:02'),
(257, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 11:16:44'),
(258, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 11:18:02'),
(259, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 11:18:34'),
(260, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 11:18:42'),
(261, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 11:22:29'),
(262, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 11:23:14'),
(263, 5, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 11:23:23'),
(264, 5, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 11:24:30'),
(265, 3, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 11:24:39'),
(266, 3, 'Rekomendasi', 'Manajer Umum', 'Status usulan ID 23 diubah menjadi menunggu_direktur_bidang. Direkomendasikan ke Direktur berdasarkan hasil MOORA.', '::1', '2026-06-17 11:25:04'),
(267, 3, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 11:25:26'),
(268, 4, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 11:25:33'),
(269, 4, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 11:30:23'),
(270, 9, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 11:30:51'),
(271, 9, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 12:06:58'),
(272, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 12:07:17'),
(273, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 12:08:19'),
(274, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 12:10:22'),
(275, 1, 'Patch Recovery Register Direktur Disposisi', 'Workflow/Auth/Dokumen', 'SQL recovery dijalankan: approved registration disinkronkan ke users dan patch recovery tercatat.', '127.0.0.1', '2026-06-17 12:29:30'),
(276, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 12:29:55'),
(277, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 12:33:06'),
(278, 1, 'Approval Registrasi User', 'Administrator', 'Menyetujui akun justinsubunit sebagai Sub Unit.', '::1', '2026-06-17 12:33:46'),
(279, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-17 12:41:47'),
(280, 4, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-17 12:41:54'),
(281, 9, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 14:19:05'),
(282, 9, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 14:25:56'),
(283, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 14:26:22'),
(284, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 14:27:03'),
(285, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 14:27:12'),
(286, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 14:39:59'),
(287, 5, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 14:40:10'),
(288, 5, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 14:47:46'),
(289, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 14:48:03'),
(290, 2, 'Verifikasi', 'Gudang', 'Status usulan ID 21 diubah menjadi diverifikasi. Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '::1', '2026-06-18 14:55:03'),
(291, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 15:02:50'),
(292, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 15:03:07'),
(293, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 15:05:13'),
(294, 4, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 15:05:22'),
(295, 4, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 15:15:12'),
(296, 9, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 15:15:27'),
(297, 9, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 15:19:08'),
(298, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 15:19:19'),
(299, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 15:32:34'),
(300, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 15:32:47'),
(301, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 17:52:20'),
(302, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 18:06:53'),
(303, 5, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 18:07:18'),
(304, 5, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 18:11:34'),
(305, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 18:11:40'),
(306, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 18:15:20'),
(307, 3, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 18:15:35'),
(308, 3, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 18:22:38'),
(309, 4, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 18:22:45'),
(310, 4, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 18:27:23'),
(311, 9, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 18:27:31'),
(312, 9, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 18:29:49'),
(313, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 18:29:56'),
(314, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 18:34:31'),
(315, 5, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 18:34:42'),
(316, 5, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 18:36:25'),
(317, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 18:36:31'),
(318, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 18:51:10'),
(319, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 18:51:15'),
(320, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 19:03:08'),
(321, 4, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 19:03:14'),
(322, 4, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 19:06:43'),
(323, 9, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 19:06:55'),
(324, 9, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 19:10:55'),
(325, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 19:11:05'),
(326, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-18 19:29:05'),
(327, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-18 19:29:40'),
(328, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-19 01:21:16'),
(329, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-19 13:54:52'),
(330, 2, 'Verifikasi', 'Gudang', 'Status usulan ID 12 diubah menjadi diverifikasi. Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '::1', '2026-06-19 13:55:10'),
(331, 2, 'Proses Moora', 'Gudang', 'Status usulan ID 12 diubah menjadi moora_selesai. Gudang memproses MOORA final mode Pesan Cepat - Per Item Barang. Versi hitung: 1781852154961', '::1', '2026-06-19 13:55:55'),
(332, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-19 13:59:12'),
(333, 5, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-19 13:59:19'),
(334, 5, 'Ajukan', 'Sub Unit', 'Status usulan ID 24 diubah menjadi diajukan. Usulan diajukan Sub Unit ke Seksi Gudang.', '::1', '2026-06-19 14:01:07'),
(335, 5, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-19 14:01:29'),
(336, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-19 14:01:34'),
(337, 2, 'Verifikasi', 'Gudang', 'Status usulan ID 24 diubah menjadi diverifikasi. Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '::1', '2026-06-19 14:01:58'),
(338, 2, 'Proses Moora', 'Gudang', 'Status usulan ID 24 diubah menjadi moora_selesai. Gudang memproses MOORA final mode Pesan Cepat - Per Item Barang. Versi hitung: 1781852559764', '::1', '2026-06-19 14:02:39'),
(339, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-20 05:18:56'),
(340, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-20 05:26:55'),
(341, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-20 05:27:01'),
(342, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-21 11:36:19'),
(343, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-21 11:43:12'),
(344, 4, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-21 11:43:30'),
(345, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-23 12:46:45'),
(346, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-23 12:51:22'),
(347, 5, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-23 12:51:43'),
(348, 5, 'Ajukan', 'Sub Unit', 'Status usulan ID 26 diubah menjadi diajukan. Usulan diajukan Sub Unit ke Seksi Gudang.', '::1', '2026-06-23 12:57:53'),
(349, 5, 'Ajukan', 'Sub Unit', 'Status usulan ID 25 diubah menjadi diajukan. Usulan diajukan Sub Unit ke Seksi Gudang.', '::1', '2026-06-23 12:58:10'),
(350, 5, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-23 12:58:22'),
(351, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-23 12:58:28'),
(352, 2, 'Verifikasi', 'Gudang', 'Status usulan ID 26 diubah menjadi diverifikasi. Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '::1', '2026-06-23 12:59:11'),
(353, 2, 'Proses Moora', 'Gudang', 'Status usulan ID 26 diubah menjadi moora_selesai. Gudang memproses MOORA final mode RKA - Agregasi Dokumen. Versi hitung: 1782194367265', '::1', '2026-06-23 12:59:27'),
(354, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-23 13:05:24'),
(355, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-23 13:05:36'),
(356, 1, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-23 13:08:11'),
(357, 5, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-23 13:08:24'),
(358, 5, 'Ajukan', 'Sub Unit', 'Status usulan ID 27 diubah menjadi diajukan. Usulan diajukan Sub Unit ke Seksi Gudang.', '::1', '2026-06-23 13:08:58'),
(359, 5, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-23 13:09:19'),
(360, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-23 13:09:35'),
(361, 2, 'Verifikasi', 'Gudang', 'Status usulan ID 27 diubah menjadi diverifikasi. Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '::1', '2026-06-23 13:09:51'),
(362, 2, 'Proses Moora', 'Gudang', 'Status usulan ID 27 diubah menjadi moora_selesai. Gudang memproses MOORA final mode Pesan Cepat - Per Item Barang. Versi hitung: 1782195001023', '::1', '2026-06-23 13:10:01'),
(363, 2, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-23 13:17:00'),
(364, 3, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-23 13:17:07'),
(365, 5, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-23 13:19:36'),
(366, 5, 'Ajukan', 'Sub Unit', 'Status usulan ID 28 diubah menjadi diajukan. Usulan diajukan Sub Unit ke Seksi Gudang.', '::1', '2026-06-23 13:20:38'),
(367, 5, 'Logout', 'auth', 'User logout dari sistem', NULL, '2026-06-23 13:20:57'),
(368, 2, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-23 13:21:05'),
(369, 2, 'Verifikasi', 'Gudang', 'Status usulan ID 28 diubah menjadi diverifikasi. Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '::1', '2026-06-23 13:21:19'),
(370, 2, 'Proses Moora', 'Gudang', 'Status usulan ID 28 diubah menjadi moora_selesai. Gudang memproses MOORA final mode Pesan Cepat - Per Item Barang. Versi hitung: 1782195691268', '::1', '2026-06-23 13:21:31'),
(371, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-06-25 11:12:18'),
(372, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-07-09 14:41:46'),
(373, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-07-09 14:41:47'),
(374, 1, 'Login', 'auth', 'User login ke sistem', NULL, '2026-07-10 02:53:46'),
(375, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260623-004 mode Pesan Cepat - Per Item Barang. Status usulan tidak diubah. Versi hitung: 1783626872736', '::1', '2026-07-10 02:54:32'),
(376, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260623-003 mode Pesan Cepat - Per Item Barang. Status usulan tidak diubah. Versi hitung: 1783626872805', '::1', '2026-07-10 02:54:32'),
(377, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260623-002 mode RKA - Agregasi Dokumen. Status usulan tidak diubah. Versi hitung: 1783626872989', '::1', '2026-07-10 02:54:32'),
(378, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260619-001 mode Pesan Cepat - Per Item Barang. Status usulan tidak diubah. Versi hitung: 1783626873037', '::1', '2026-07-10 02:54:33'),
(379, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260611-008 mode Pesan Cepat - Per Item Barang. Status usulan tidak diubah. Versi hitung: 1783626873076', '::1', '2026-07-10 02:54:33'),
(380, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260617-003 mode RKA - Agregasi Dokumen. Status usulan tidak diubah. Versi hitung: 1783626873116', '::1', '2026-07-10 02:54:33'),
(381, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260617-005 mode RKA - Agregasi Dokumen. Status usulan tidak diubah. Versi hitung: 1783626873250', '::1', '2026-07-10 02:54:33'),
(382, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260617-001 mode RKA - Agregasi Dokumen. Status usulan tidak diubah. Versi hitung: 1783626873292', '::1', '2026-07-10 02:54:33'),
(383, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260617-004 mode Pesan Cepat - Per Item Barang. Status usulan tidak diubah. Versi hitung: 1783626873334', '::1', '2026-07-10 02:54:33'),
(384, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260616-002 mode Pesan Cepat - Per Item Barang. Status usulan tidak diubah. Versi hitung: 1783626873372', '::1', '2026-07-10 02:54:33'),
(385, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260617-002 mode Pesan Cepat - Per Item Barang. Status usulan tidak diubah. Versi hitung: 1783626873410', '::1', '2026-07-10 02:54:33'),
(386, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-2026-001 mode RKA - Agregasi Dokumen. Status usulan tidak diubah. Versi hitung: 1783626873448', '::1', '2026-07-10 02:54:33'),
(387, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260616-001 mode RKA - Agregasi Dokumen. Status usulan tidak diubah. Versi hitung: 1783626873485', '::1', '2026-07-10 02:54:33'),
(388, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260613-001 mode RKA - Agregasi Dokumen. Status usulan tidak diubah. Versi hitung: 1783626873521', '::1', '2026-07-10 02:54:33'),
(389, 1, 'Patch V6 Bugfix Audit Engine', 'Administrator', 'Konsolidasi historis V6 dijalankan. Berhasil: 14, gagal: 0.', '::1', '2026-07-10 02:54:33'),
(390, 1, 'Patch 11 Global RKA Ranking', 'MOORA Engine', 'Ranking global RKA dijalankan untuk 1 dokumen. Gagal: 0.', '::1', '2026-07-10 02:57:23'),
(391, 1, 'Patch 11 Global RKA Ranking', 'MOORA Engine', 'Ranking global RKA dijalankan untuk 1 dokumen. Gagal: 0.', '::1', '2026-07-10 02:58:21'),
(392, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260623-004 mode Pesan Cepat - Per Item Barang. Status usulan tidak diubah. Versi hitung: 1783627172801', '::1', '2026-07-10 02:59:32'),
(393, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260623-003 mode Pesan Cepat - Per Item Barang. Status usulan tidak diubah. Versi hitung: 1783627172860', '::1', '2026-07-10 02:59:32'),
(394, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260623-002 mode RKA - Agregasi Dokumen. Status usulan tidak diubah. Versi hitung: 1783627173009', '::1', '2026-07-10 02:59:33'),
(395, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260619-001 mode Pesan Cepat - Per Item Barang. Status usulan tidak diubah. Versi hitung: 1783627173050', '::1', '2026-07-10 02:59:33'),
(396, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260611-008 mode Pesan Cepat - Per Item Barang. Status usulan tidak diubah. Versi hitung: 1783627173087', '::1', '2026-07-10 02:59:33'),
(397, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260617-003 mode RKA - Agregasi Dokumen. Status usulan tidak diubah. Versi hitung: 1783627173122', '::1', '2026-07-10 02:59:33'),
(398, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260617-005 mode RKA - Agregasi Dokumen. Status usulan tidak diubah. Versi hitung: 1783627173240', '::1', '2026-07-10 02:59:33'),
(399, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260617-001 mode RKA - Agregasi Dokumen. Status usulan tidak diubah. Versi hitung: 1783627173277', '::1', '2026-07-10 02:59:33'),
(400, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260617-004 mode Pesan Cepat - Per Item Barang. Status usulan tidak diubah. Versi hitung: 1783627173313', '::1', '2026-07-10 02:59:33'),
(401, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260616-002 mode Pesan Cepat - Per Item Barang. Status usulan tidak diubah. Versi hitung: 1783627173349', '::1', '2026-07-10 02:59:33'),
(402, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260617-002 mode Pesan Cepat - Per Item Barang. Status usulan tidak diubah. Versi hitung: 1783627173383', '::1', '2026-07-10 02:59:33'),
(403, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-2026-001 mode RKA - Agregasi Dokumen. Status usulan tidak diubah. Versi hitung: 1783627173421', '::1', '2026-07-10 02:59:33'),
(404, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260616-001 mode RKA - Agregasi Dokumen. Status usulan tidak diubah. Versi hitung: 1783627173457', '::1', '2026-07-10 02:59:33'),
(405, 1, 'Konsolidasi MOORA V6', 'MOORA Engine', 'Recalculate historis untuk usulan UP-20260613-001 mode RKA - Agregasi Dokumen. Status usulan tidak diubah. Versi hitung: 1783627173494', '::1', '2026-07-10 02:59:33'),
(406, 1, 'Patch V6 Bugfix Audit Engine', 'Administrator', 'Konsolidasi historis V6 dijalankan. Berhasil: 14, gagal: 0.', '::1', '2026-07-10 02:59:33');

-- --------------------------------------------------------

--
-- Table structure for table `moora_engine_log`
--

CREATE TABLE `moora_engine_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_usulan` int(10) UNSIGNED NOT NULL,
  `mode_hitung` varchar(30) NOT NULL,
  `versi_hitung` bigint(20) NOT NULL,
  `jumlah_detail` int(11) NOT NULL DEFAULT 0,
  `jumlah_hasil` int(11) NOT NULL DEFAULT 0,
  `processed_by` int(10) UNSIGNED DEFAULT NULL,
  `processed_role` varchar(50) DEFAULT NULL,
  `checksum_hash` varchar(128) DEFAULT NULL,
  `catatan_hitung` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `moora_engine_log`
--

INSERT INTO `moora_engine_log` (`id`, `id_usulan`, `mode_hitung`, `versi_hitung`, `jumlah_detail`, `jumlah_hasil`, `processed_by`, `processed_role`, `checksum_hash`, `catatan_hitung`, `created_at`) VALUES
(1, 1, 'rka_aggregate', 1781552173, 3, 3, NULL, 'v6_sql_backfill', '202501eedea53e683796587c603b548ee0fc23abed88b1d8016d5378085b5bc3', 'Patch V6 SQL backfill: log engine dibuat dari hasil_moora versi terbaru.', '2026-06-16 17:09:20'),
(2, 2, 'rka_aggregate', 1, 1, 1, NULL, 'v6_sql_backfill', '74db7f151d86e9ef8a02316655fd1a5fb98f693e82b8c4124231fce13ed98ce7', 'Patch V6 SQL backfill: log engine dibuat dari hasil_moora versi terbaru.', '2026-06-16 17:09:20'),
(3, 3, 'rka_aggregate', 1, 1, 1, NULL, 'v6_sql_backfill', '3b489f4947a32bd095c84698ef222123e1691f27bd6e03bb04aeff49c6a7170b', 'Patch V6 SQL backfill: log engine dibuat dari hasil_moora versi terbaru.', '2026-06-16 17:09:20'),
(4, 4, 'rka_aggregate', 1, 1, 1, NULL, 'v6_sql_backfill', '1dcda4a7d78e25b2c7ee1cef6178c4ff7710728b81e26e6aab79454037bd6eb2', 'Patch V6 SQL backfill: log engine dibuat dari hasil_moora versi terbaru.', '2026-06-16 17:09:20'),
(5, 9, 'item_based', 1, 1, 1, NULL, 'v6_sql_backfill', '0e8e4b7db9dcfb13c8931669ad4b299ea5e22d1f19c91796794caf21dbbcae8a', 'Patch V6 SQL backfill: log engine dibuat dari hasil_moora versi terbaru.', '2026-06-16 17:09:20'),
(6, 12, 'item_based', 1781229439, 1, 1, NULL, 'v6_sql_backfill', 'f5e8ac3712ba3057d23e5e1d04fb79d46da4b72541a0c108abe24a3e88966759', 'Patch V6 SQL backfill: log engine dibuat dari hasil_moora versi terbaru.', '2026-06-16 17:09:20'),
(7, 13, 'rka_aggregate', 1781246779, 5, 5, NULL, 'v6_sql_backfill', 'e0dbe8a53d1f87253364c070344e27ec08a4e3c74056d0100e30071a50fb7b49', 'Patch V6 SQL backfill: log engine dibuat dari hasil_moora versi terbaru.', '2026-06-16 17:09:20'),
(8, 14, 'item_based', 1, 1, 1, NULL, 'v6_sql_backfill', 'e993313f5437655c9c1a666946c8f1709c69c24bddd3f1b71aa2cd930e063a45', 'Patch V6 SQL backfill: log engine dibuat dari hasil_moora versi terbaru.', '2026-06-16 17:09:20'),
(9, 23, 'rka_aggregate', 1781661002813, 5, 1, 2, 'gudang', '64dde141e0b8d932554e015227972a64dd8ef47999809c09e793cd4f6f894ee3', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-06-17 08:50:02'),
(10, 12, 'item_based', 1781852154961, 1, 1, 2, 'gudang', '5cb98d66d04bac5d0d81472b0e10099d24da16d5f0504e0cfbc71035f2b16542', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-06-19 13:55:54'),
(11, 24, 'item_based', 1781852559764, 1, 1, 2, 'gudang', '6fa54e255dfaf1f41afdb161c64739614b2cccfdbb7003738da0efc74b7c2611', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-06-19 14:02:39'),
(12, 26, 'rka_aggregate', 1782194367265, 5, 1, 2, 'gudang', '46e91b0a5f039a343c8b19e8754399ff1ab1418110428f59d2f56b6ee5bafead', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-06-23 12:59:27'),
(13, 27, 'item_based', 1782195001023, 1, 1, 2, 'gudang', 'e479cae24bdcf395f4962a3c51e51eab5a46fcbed0cee69a30193a6e400a17ce', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-06-23 13:10:01'),
(14, 28, 'item_based', 1782195691268, 1, 1, 2, 'gudang', '7aebd34a9c714fb1d820fad899cdea68e0eef769d5a3405d35dcffc800cf7e68', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-06-23 13:21:31'),
(15, 28, 'item_based', 1783626872736, 1, 1, 1, 'administrator_maintenance', 'd3ea33c7275f7d9ceb376b5b7a7ffdf47860788cbc054a763c7114be857cc47a', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-07-10 02:54:32'),
(16, 27, 'item_based', 1783626872805, 1, 1, 1, 'administrator_maintenance', '8c8f29c0e6a0afd1dc48e7233ef3e906a2627e5c36fa08f11459b7042a94e405', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-07-10 02:54:32'),
(17, 26, 'rka_aggregate', 1783626872989, 5, 1, 1, 'administrator_maintenance', 'fbef66b38ddc6285e246dc259b182243f7740960563ef4a24d000c612e477773', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-07-10 02:54:32'),
(18, 24, 'item_based', 1783626873037, 1, 1, 1, 'administrator_maintenance', '580c0d2a2543c7462bf1bcc0a656954f10c86273e451294dc4156c2f68be481f', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-07-10 02:54:33'),
(19, 12, 'item_based', 1783626873076, 1, 1, 1, 'administrator_maintenance', '5823e538306a66db0dc658b5bbdd73949a0be8d76fd5e518be62968fa740318a', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-07-10 02:54:33'),
(20, 21, 'rka_aggregate', 1783626873116, 1, 1, 1, 'administrator_maintenance', '513fd2f427a2437cba56d2b12547b834e8fd7163a464958322b3bedf76ae8801', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-07-10 02:54:33'),
(21, 23, 'rka_aggregate', 1783626873250, 5, 1, 1, 'administrator_maintenance', '9a206bfa745213725dd48f43b0c43defc2becbef31397519f5b1eca043c749b7', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-07-10 02:54:33'),
(22, 19, 'rka_aggregate', 1783626873292, 1, 1, 1, 'administrator_maintenance', '16d78621fe70a93cd93fa9638f871f6e020456d5b940ab2325b8d4332a93e485', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-07-10 02:54:33'),
(23, 22, 'item_based', 1783626873334, 1, 1, 1, 'administrator_maintenance', '7ae5e27b8e347a315edb9578208af36f583649088a32b596e80debb991f42360', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-07-10 02:54:33'),
(24, 18, 'item_based', 1783626873372, 1, 1, 1, 'administrator_maintenance', 'fb9b028fb9011b41cc66fb4469e17629e9632efc9764eb6930dbb2efbacd7abd', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-07-10 02:54:33'),
(25, 20, 'item_based', 1783626873410, 1, 1, 1, 'administrator_maintenance', '720449ec9c01e872766da5ae466863ac05a61fdb969735ab125e6d748d176f45', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-07-10 02:54:33'),
(26, 1, 'rka_aggregate', 1783626873448, 1, 1, 1, 'administrator_maintenance', 'ba0947e20396db05035531e3dd46a8889f46e99acb91eeaca7d8406768977d63', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-07-10 02:54:33'),
(27, 17, 'rka_aggregate', 1783626873485, 1, 1, 1, 'administrator_maintenance', 'e01ecc427edeb106007b0f46a0e1bd831206b11e30b51f3768965fc2e8ce9ce0', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-07-10 02:54:33'),
(28, 16, 'rka_aggregate', 1783626873521, 1, 1, 1, 'administrator_maintenance', 'dc0a962a093778fbe7dbd808d91152a391dc9ead4cf2f511ca3f9766c6906c31', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-07-10 02:54:33'),
(29, 26, 'rka_aggregate', 1783627043635, 5, 1, 1, 'administrator_global_rka_patch_11', '80ca7c1b96e3c7366379474c3571013672de2c0ddc88a57a624ed0e7af9f068d', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-07-10 02:57:23'),
(30, 26, 'rka_aggregate', 1783627101534, 5, 1, 1, 'administrator_global_rka_patch_11', '69f382582e8fbd3a3ca21cfd8ff02de266a38e890de1384586d1a51136b1f02b', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-07-10 02:58:21'),
(31, 28, 'item_based', 1783627172801, 1, 1, 1, 'administrator_maintenance', '8d296d0568b851bc925a38128307225341a81c519c5998a1ad484c9052e71b7f', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-07-10 02:59:32'),
(32, 27, 'item_based', 1783627172860, 1, 1, 1, 'administrator_maintenance', 'f4a72c6c6763c8069a74af91bef7aa33090d0fee6a0fb83dae99701ba48f22b9', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-07-10 02:59:32'),
(33, 26, 'rka_aggregate', 1783627173009, 5, 1, 1, 'administrator_maintenance', 'fc92ab1c4330f11898876458f3380e0b264663086788689d1224000e99604330', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-07-10 02:59:33'),
(34, 24, 'item_based', 1783627173050, 1, 1, 1, 'administrator_maintenance', '69fa188932d056e53d751ba1ced04452ea1b9dd54a4b33890692a31c6cfd4abf', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-07-10 02:59:33'),
(35, 12, 'item_based', 1783627173087, 1, 1, 1, 'administrator_maintenance', '5b1a1f77a53243c260d55361b1fd118d0695375536c7a5706e2725f23e9f4450', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-07-10 02:59:33'),
(36, 21, 'rka_aggregate', 1783627173122, 1, 1, 1, 'administrator_maintenance', 'f451cd61a5200a877ab2962b4417f10fc1037d9fd6f5705c42d769ef08fd4bda', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-07-10 02:59:33'),
(37, 23, 'rka_aggregate', 1783627173240, 5, 1, 1, 'administrator_maintenance', '8706df2dce50cdb32dbb75edcb0398d0395706d30ea4ccf8e1eabfde06a894f3', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-07-10 02:59:33'),
(38, 19, 'rka_aggregate', 1783627173277, 1, 1, 1, 'administrator_maintenance', '3b6cc6378420470cbdd37419f1e1b28cf0c9daef03f6fad69dcf0d9d73e3bd38', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-07-10 02:59:33'),
(39, 22, 'item_based', 1783627173313, 1, 1, 1, 'administrator_maintenance', 'bffdf982e0d883ee666db5006c94290eaf2434be875b8fe7e016a8943eb356b1', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-07-10 02:59:33'),
(40, 18, 'item_based', 1783627173349, 1, 1, 1, 'administrator_maintenance', '5513b948ca9ceeb5e67b8a4d9cf6290ebc70c9d5b49f19c3a5f322ec029fae4e', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-07-10 02:59:33'),
(41, 20, 'item_based', 1783627173383, 1, 1, 1, 'administrator_maintenance', 'aa7ad6e2c396f4eeef59a09c3e9981554f96b7b3816945c1cb9de0394f199ba6', 'V6 audit: Pesan Cepat dihitung per item/detail barang.', '2026-07-10 02:59:33'),
(42, 1, 'rka_aggregate', 1783627173421, 1, 1, 1, 'administrator_maintenance', 'ba2778c4c3ec734cad6dd7da6dc8738b456cea3df8aec88fba7dfd61f31bf1b0', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-07-10 02:59:33'),
(43, 17, 'rka_aggregate', 1783627173457, 1, 1, 1, 'administrator_maintenance', 'eae1dabff6604fff9515f5e014d0ca1122ae398eb3d04273590b0384ac7e28e6', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-07-10 02:59:33'),
(44, 16, 'rka_aggregate', 1783627173494, 1, 1, 1, 'administrator_maintenance', '2029caa760896309660c03449a1d27dc3edcaff851f892c6a49ed390f1d00650', 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.', '2026-07-10 02:59:33');

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user_penerima` int(10) UNSIGNED DEFAULT NULL,
  `role_penerima` enum('administrator','gudang','sub_unit','manajer_umum','direktur','pengadaan') DEFAULT NULL,
  `id_usulan` int(10) UNSIGNED DEFAULT NULL,
  `judul` varchar(160) NOT NULL,
  `pesan` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `tipe` enum('info','success','warning','danger','approval','pengadaan','moora') NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `id_user_penerima`, `role_penerima`, `id_usulan`, `judul`, `pesan`, `link`, `tipe`, `is_read`, `read_at`, `created_by`, `created_at`) VALUES
(1, 9, 'pengadaan', NULL, 'Modul Pengadaan Siap Diintegrasikan', 'Database sudah memiliki struktur pengadaan. Lanjutkan patch sistem CodeIgniter untuk mengaktifkan dashboard dan proses pembelian.', 'pengadaan/dashboard', 'info', 1, '2026-06-16 19:56:50', NULL, '2026-06-15 20:36:38'),
(2, NULL, 'gudang', 17, 'Usulan Baru Masuk', 'Usulan UP-20260616-001 menunggu verifikasi Seksi Gudang.', 'gudang/usulan-masuk/detail/17', 'info', 1, '2026-06-16 19:42:59', 5, '2026-06-16 19:41:53'),
(3, NULL, 'gudang', 17, 'Usulan Siap Diproses MOORA', 'Usulan ID 17 sudah diverifikasi dan masuk antrian engine MOORA Gudang.', 'gudang/penilaian/detail/17', 'moora', 0, NULL, 2, '2026-06-16 19:43:06'),
(4, NULL, 'gudang', 1, 'Pengadaan Sedang Diproses', 'Usulan UP-2026-001 sedang diproses pembelian oleh Pengadaan.', 'gudang/penerimaan', 'pengadaan', 0, NULL, 9, '2026-06-16 19:55:44'),
(5, NULL, 'gudang', 1, 'Barang Menunggu Penerimaan', 'Bagian Pengadaan menyerahkan barang untuk diterima Gudang.', 'gudang/penerimaan', 'pengadaan', 1, '2026-06-16 19:58:20', 9, '2026-06-16 19:57:35'),
(6, 2, NULL, 1, 'Barang Pengadaan Sudah Diterima Gudang', 'Barang untuk usulan UP-2026-001 sudah diterima Gudang.', 'sub-unit/barang-pengadaan', 'success', 1, '2026-06-17 07:24:31', 2, '2026-06-16 19:58:26'),
(7, NULL, 'gudang', 18, 'Usulan Baru Masuk', 'Usulan UP-20260616-002 menunggu verifikasi Seksi Gudang.', 'gudang/usulan-masuk/detail/18', 'info', 0, NULL, 5, '2026-06-16 22:48:01'),
(8, NULL, 'gudang', 19, 'Usulan Baru Masuk', 'Usulan UP-20260617-001 menunggu verifikasi Seksi Gudang.', 'gudang/usulan-masuk/detail/19', 'info', 0, NULL, 5, '2026-06-17 02:01:14'),
(9, NULL, 'gudang', 20, 'Usulan Baru Masuk', 'Usulan UP-20260617-002 menunggu verifikasi Seksi Gudang.', 'gudang/usulan-masuk/detail/20', 'info', 0, NULL, 5, '2026-06-17 02:02:51'),
(10, NULL, 'gudang', 20, 'Usulan Siap Diproses MOORA', 'Usulan UP-20260617-002 sudah diverifikasi dan masuk antrian engine MOORA Gudang.', 'gudang/penilaian/detail/20', 'moora', 0, NULL, 2, '2026-06-17 02:03:56'),
(11, NULL, 'gudang', 18, 'Usulan Siap Diproses MOORA', 'Usulan UP-20260616-002 sudah diverifikasi dan masuk antrian engine MOORA Gudang.', 'gudang/penilaian/detail/18', 'moora', 0, NULL, 2, '2026-06-17 02:06:27'),
(12, NULL, 'gudang', 21, 'Usulan Baru Masuk', 'Usulan UP-20260617-003 menunggu verifikasi Seksi Gudang.', 'gudang/usulan-masuk/detail/21', 'info', 0, NULL, 5, '2026-06-17 04:16:22'),
(13, NULL, 'gudang', 22, 'Usulan Baru Masuk', 'Usulan UP-20260617-004 menunggu verifikasi Seksi Gudang.', 'gudang/usulan-masuk/detail/22', 'info', 1, '2026-06-17 04:18:22', 5, '2026-06-17 04:16:53'),
(14, NULL, 'gudang', 22, 'Usulan Siap Diproses MOORA', 'Usulan UP-20260617-004 sudah diverifikasi dan masuk antrian engine MOORA Gudang.', 'gudang/penilaian/detail/22', 'moora', 1, '2026-06-17 05:22:21', 2, '2026-06-17 04:17:49'),
(15, NULL, 'gudang', 19, 'Usulan Siap Diproses MOORA', 'Usulan UP-20260617-001 sudah diverifikasi dan masuk antrian engine MOORA Gudang.', 'gudang/penilaian/detail/19', 'moora', 1, '2026-06-17 08:26:17', 2, '2026-06-17 05:37:06'),
(16, NULL, 'gudang', 23, 'Usulan Baru Masuk', 'Usulan UP-20260617-005 menunggu verifikasi Seksi Gudang.', 'gudang/usulan-masuk/detail/23', 'info', 0, NULL, 5, '2026-06-17 08:46:45'),
(17, NULL, 'gudang', 23, 'Usulan Siap Diproses MOORA', 'Usulan UP-20260617-005 sudah diverifikasi dan masuk antrian engine MOORA Gudang.', 'gudang/penilaian/detail/23', 'moora', 0, NULL, 2, '2026-06-17 08:49:54'),
(18, NULL, 'manajer_umum', 23, 'Hasil MOORA Siap Direview', 'Usulan UP-20260617-005 selesai dihitung oleh Gudang dengan mode RKA - Agregasi Dokumen.', 'manajer-umum/usulan/detail/23', 'moora', 1, '2026-06-17 11:24:56', 2, '2026-06-17 08:50:02'),
(19, NULL, 'direktur', 23, 'Usulan Siap Approval Direktur Bidang', 'Usulan UP-20260617-005 sudah direkomendasikan Manajer Umum.', 'direktur/validasi/detail/23', 'approval', 1, '2026-06-17 11:25:43', 3, '2026-06-17 11:25:04'),
(20, NULL, 'gudang', 21, 'Usulan Siap Diproses MOORA', 'Usulan UP-20260617-003 sudah diverifikasi dan masuk antrian engine MOORA Gudang.', 'gudang/penilaian/detail/21', 'moora', 0, NULL, 2, '2026-06-18 14:55:03'),
(21, NULL, 'gudang', 12, 'Usulan Siap Diproses MOORA', 'Usulan UP-20260611-008 sudah diverifikasi dan masuk antrian engine MOORA Gudang.', 'gudang/penilaian/detail/12', 'moora', 0, NULL, 2, '2026-06-19 13:55:10'),
(22, NULL, 'manajer_umum', 12, 'Hasil MOORA Siap Direview', 'Usulan UP-20260611-008 selesai dihitung oleh Gudang dengan mode Pesan Cepat - Per Item Barang.', 'manajer-umum/usulan/detail/12', 'moora', 0, NULL, 2, '2026-06-19 13:55:55'),
(23, NULL, 'gudang', 24, 'Usulan Baru Masuk', 'Usulan UP-20260619-001 menunggu verifikasi Seksi Gudang.', 'gudang/usulan-masuk/detail/24', 'info', 0, NULL, 5, '2026-06-19 14:01:08'),
(24, NULL, 'gudang', 24, 'Usulan Siap Diproses MOORA', 'Usulan UP-20260619-001 sudah diverifikasi dan masuk antrian engine MOORA Gudang.', 'gudang/penilaian/detail/24', 'moora', 1, '2026-06-19 14:03:23', 2, '2026-06-19 14:01:58'),
(25, NULL, 'manajer_umum', 24, 'Hasil MOORA Siap Direview', 'Usulan UP-20260619-001 selesai dihitung oleh Gudang dengan mode Pesan Cepat - Per Item Barang.', 'manajer-umum/usulan/detail/24', 'moora', 0, NULL, 2, '2026-06-19 14:02:39'),
(26, NULL, 'gudang', 26, 'Usulan Baru Masuk', 'Usulan UP-20260623-002 menunggu verifikasi Seksi Gudang.', 'gudang/usulan-masuk/detail/26', 'info', 0, NULL, 5, '2026-06-23 12:57:53'),
(27, NULL, 'gudang', 25, 'Usulan Baru Masuk', 'Usulan UP-20260623-001 menunggu verifikasi Seksi Gudang.', 'gudang/usulan-masuk/detail/25', 'info', 0, NULL, 5, '2026-06-23 12:58:10'),
(28, NULL, 'gudang', 26, 'Usulan Siap Diproses MOORA', 'Usulan UP-20260623-002 sudah diverifikasi dan masuk antrian engine MOORA Gudang.', 'gudang/penilaian/detail/26', 'moora', 0, NULL, 2, '2026-06-23 12:59:11'),
(29, NULL, 'manajer_umum', 26, 'Hasil MOORA Siap Direview', 'Usulan UP-20260623-002 selesai dihitung oleh Gudang dengan mode RKA - Agregasi Dokumen.', 'manajer-umum/usulan/detail/26', 'moora', 0, NULL, 2, '2026-06-23 12:59:27'),
(30, NULL, 'gudang', 27, 'Usulan Baru Masuk', 'Usulan UP-20260623-003 menunggu verifikasi Seksi Gudang.', 'gudang/usulan-masuk/detail/27', 'info', 1, '2026-06-23 13:09:42', 5, '2026-06-23 13:08:58'),
(31, NULL, 'gudang', 27, 'Usulan Siap Diproses MOORA', 'Usulan UP-20260623-003 sudah diverifikasi dan masuk antrian engine MOORA Gudang.', 'gudang/penilaian/detail/27', 'moora', 0, NULL, 2, '2026-06-23 13:09:51'),
(32, NULL, 'manajer_umum', 27, 'Hasil MOORA Siap Direview', 'Usulan UP-20260623-003 selesai dihitung oleh Gudang dengan mode Pesan Cepat - Per Item Barang.', 'manajer-umum/usulan/detail/27', 'moora', 0, NULL, 2, '2026-06-23 13:10:01'),
(33, NULL, 'gudang', 28, 'Usulan Baru Masuk', 'Usulan UP-20260623-004 menunggu verifikasi Seksi Gudang.', 'gudang/usulan-masuk/detail/28', 'info', 0, NULL, 5, '2026-06-23 13:20:38'),
(34, NULL, 'gudang', 28, 'Usulan Siap Diproses MOORA', 'Usulan UP-20260623-004 sudah diverifikasi dan masuk antrian engine MOORA Gudang.', 'gudang/penilaian/detail/28', 'moora', 0, NULL, 2, '2026-06-23 13:21:19'),
(35, NULL, 'manajer_umum', 28, 'Hasil MOORA Siap Direview', 'Usulan UP-20260623-004 selesai dihitung oleh Gudang dengan mode Pesan Cepat - Per Item Barang.', 'manajer-umum/usulan/detail/28', 'moora', 0, NULL, 2, '2026-06-23 13:21:31');

-- --------------------------------------------------------

--
-- Table structure for table `penerimaan_barang`
--

CREATE TABLE `penerimaan_barang` (
  `id` int(11) NOT NULL,
  `id_usulan` int(10) UNSIGNED DEFAULT NULL,
  `id_detail_usulan` int(10) UNSIGNED DEFAULT NULL,
  `id_pengadaan_serah` int(10) UNSIGNED DEFAULT NULL,
  `id_alternatif` int(11) NOT NULL,
  `id_user_gudang` int(10) UNSIGNED DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `status_penerimaan` enum('diterima','ditolak','parsial') NOT NULL DEFAULT 'diterima',
  `tanggal` date NOT NULL,
  `sumber` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penerimaan_barang`
--

INSERT INTO `penerimaan_barang` (`id`, `id_usulan`, `id_detail_usulan`, `id_pengadaan_serah`, `id_alternatif`, `id_user_gudang`, `jumlah`, `status_penerimaan`, `tanggal`, `sumber`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 2, 2, 20, 'diterima', '2026-06-16', 'Pengadaan', 'Diterima Gudang', '2026-06-16 19:58:26', '2026-06-16 19:58:26'),
(2, NULL, NULL, NULL, 2, 2, 20, 'diterima', '2026-06-16', 'pengadaan', 'barang masuk dari hasil rka psdm', '2026-06-16 19:59:19', '2026-06-16 19:59:19');

-- --------------------------------------------------------

--
-- Table structure for table `pengadaan_dokumen`
--

CREATE TABLE `pengadaan_dokumen` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_pengadaan` int(10) UNSIGNED DEFAULT NULL,
  `id_usulan` int(10) UNSIGNED NOT NULL,
  `jenis_dokumen` enum('po','invoice','bast','surat_jalan','bukti_pembayaran','lainnya') NOT NULL DEFAULT 'lainnya',
  `nomor_dokumen` varchar(100) DEFAULT NULL,
  `nama_file` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `mime_type` varchar(120) DEFAULT NULL,
  `uploaded_by` int(10) UNSIGNED DEFAULT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp(),
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengadaan_dokumen`
--

INSERT INTO `pengadaan_dokumen` (`id`, `id_pengadaan`, `id_usulan`, `jenis_dokumen`, `nomor_dokumen`, `nama_file`, `file_path`, `mime_type`, `uploaded_by`, `uploaded_at`, `catatan`) VALUES
(1, 1, 1, 'lainnya', 'BACKFILL/PATCH11/1', 'metadata-backfill-pengadaan-lama.txt', NULL, 'text/plain', 9, '2026-06-17 04:40:05', 'Patch 11: metadata backfill non-destruktif untuk pengadaan lama yang sudah berjalan sebelum guard dokumen aktif.'),
(2, 1, 1, 'bukti_pembayaran', '01234pgd123', 'Hasil RKA.pdf', 'writable/uploads/pengadaan/1781670731_880bec4fb05f9545f87c.pdf', 'application/pdf', 9, '2026-06-17 11:32:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengadaan_pembelian`
--

CREATE TABLE `pengadaan_pembelian` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_usulan` int(10) UNSIGNED NOT NULL,
  `nomor_pengadaan` varchar(80) NOT NULL,
  `nomor_po` varchar(80) DEFAULT NULL,
  `vendor` varchar(150) DEFAULT NULL,
  `tanggal_pengadaan` date DEFAULT NULL,
  `tanggal_po` date DEFAULT NULL,
  `total_pengadaan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status_pengadaan` enum('menunggu','diproses','po_terbit','barang_datang','diserahkan_gudang','selesai','dibatalkan') NOT NULL DEFAULT 'menunggu',
  `catatan` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengadaan_pembelian`
--

INSERT INTO `pengadaan_pembelian` (`id`, `id_usulan`, `nomor_pengadaan`, `nomor_po`, `vendor`, `tanggal_pengadaan`, `tanggal_po`, `total_pengadaan`, `status_pengadaan`, `catatan`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'PGD-20260616-001', '1', 'CV. Mitra Amanah', '2026-06-16', '2026-06-16', 120500000.00, 'selesai', 'sudah dibeli semua', 9, '2026-06-16 19:55:44', '2026-06-16 19:58:26');

-- --------------------------------------------------------

--
-- Table structure for table `pengadaan_serah_barang`
--

CREATE TABLE `pengadaan_serah_barang` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_pengadaan` int(10) UNSIGNED DEFAULT NULL,
  `id_usulan` int(10) UNSIGNED NOT NULL,
  `id_detail_usulan` int(10) UNSIGNED DEFAULT NULL,
  `id_alternatif` int(10) UNSIGNED NOT NULL,
  `jumlah_diserahkan` int(11) NOT NULL DEFAULT 1,
  `tanggal_serah` date DEFAULT NULL,
  `status_serah` enum('menunggu_gudang','diterima_gudang','ditolak_gudang') NOT NULL DEFAULT 'menunggu_gudang',
  `catatan_pengadaan` text DEFAULT NULL,
  `catatan_gudang` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `received_by` int(10) UNSIGNED DEFAULT NULL,
  `received_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengadaan_serah_barang`
--

INSERT INTO `pengadaan_serah_barang` (`id`, `id_pengadaan`, `id_usulan`, `id_detail_usulan`, `id_alternatif`, `jumlah_diserahkan`, `tanggal_serah`, `status_serah`, `catatan_pengadaan`, `catatan_gudang`, `created_by`, `received_by`, `received_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 2, 20, '2026-06-16', 'diterima_gudang', 'proses diserahkan', 'Diterima Gudang', 9, 2, '2026-06-16 19:58:26', '2026-06-16 19:57:35', '2026-06-16 19:58:26');

-- --------------------------------------------------------

--
-- Table structure for table `pengambilan_barang`
--

CREATE TABLE `pengambilan_barang` (
  `id` int(11) NOT NULL,
  `id_usulan` int(10) UNSIGNED DEFAULT NULL,
  `id_detail_usulan` int(10) UNSIGNED DEFAULT NULL,
  `id_alternatif` int(11) NOT NULL,
  `id_user_gudang` int(10) UNSIGNED DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `tujuan` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penilaian`
--

CREATE TABLE `penilaian` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_detail_usulan` int(10) UNSIGNED NOT NULL,
  `id_kriteria` int(10) UNSIGNED NOT NULL,
  `nilai` decimal(10,4) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penilaian`
--

INSERT INTO `penilaian` (`id`, `id_detail_usulan`, `id_kriteria`, `nilai`, `created_at`, `updated_at`) VALUES
(21, 62, 1, 8.5000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(22, 62, 2, 1.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(23, 62, 3, 7.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(24, 62, 4, 6.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(25, 62, 5, 7.2000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(26, 63, 1, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(27, 63, 2, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(28, 63, 3, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(29, 63, 4, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(30, 63, 5, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(31, 43, 1, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(32, 43, 2, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(33, 43, 3, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(34, 43, 4, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(35, 43, 5, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(36, 53, 1, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(37, 53, 2, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(38, 53, 3, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(39, 53, 4, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(40, 53, 5, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(41, 44, 1, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(42, 44, 2, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(43, 44, 3, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(44, 44, 4, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(45, 44, 5, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(46, 45, 1, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(47, 45, 2, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(48, 45, 3, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(49, 45, 4, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(50, 45, 5, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(51, 46, 1, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(52, 46, 2, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(53, 46, 3, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(54, 46, 4, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(55, 46, 5, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(56, 47, 1, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(57, 47, 2, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(58, 47, 3, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(59, 47, 4, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(60, 47, 5, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(61, 50, 1, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(62, 50, 2, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(63, 50, 3, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(64, 50, 4, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(65, 50, 5, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(66, 48, 1, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(67, 48, 2, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(68, 48, 3, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(69, 48, 4, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(70, 48, 5, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(71, 49, 1, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(72, 49, 2, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(73, 49, 3, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(74, 49, 4, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(75, 49, 5, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(76, 51, 1, 10.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(77, 51, 2, 1.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(78, 51, 3, 7.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(79, 51, 4, 6.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(80, 51, 5, 6.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(81, 54, 1, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(82, 54, 2, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(83, 54, 3, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(84, 54, 4, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(85, 54, 5, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(86, 64, 1, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(87, 64, 2, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(88, 64, 3, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(89, 64, 4, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(90, 64, 5, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(91, 55, 1, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(92, 55, 2, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(93, 55, 3, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(94, 55, 4, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(95, 55, 5, 5.0000, '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(96, 56, 1, 8.5000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(97, 56, 2, 1.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(98, 56, 3, 7.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(99, 56, 4, 6.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(100, 56, 5, 6.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(101, 57, 1, 8.5000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(102, 57, 2, 1.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(103, 57, 3, 7.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(104, 57, 4, 6.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(105, 57, 5, 6.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(106, 58, 1, 10.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(107, 58, 2, 1.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(108, 58, 3, 7.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(109, 58, 4, 6.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(110, 58, 5, 6.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(111, 59, 1, 8.5000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(112, 59, 2, 1.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(113, 59, 3, 7.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(114, 59, 4, 6.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(115, 59, 5, 6.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(116, 60, 1, 10.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(117, 60, 2, 1.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(118, 60, 3, 7.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(119, 60, 4, 6.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(120, 60, 5, 6.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(121, 52, 1, 8.5000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(122, 52, 2, 1.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(123, 52, 3, 7.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(124, 52, 4, 6.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(125, 52, 5, 6.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(126, 61, 1, 10.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(127, 61, 2, 1.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(128, 61, 3, 7.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(129, 61, 4, 6.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(130, 61, 5, 6.0000, '2026-06-17 08:29:19', '2026-07-10 02:59:33'),
(131, 65, 1, 7.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(132, 65, 2, 9.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(133, 65, 3, 7.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(134, 65, 4, 9.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(135, 65, 5, 9.6000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(136, 66, 1, 8.5000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(137, 66, 2, 7.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(138, 66, 3, 7.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(139, 66, 4, 10.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(140, 66, 5, 7.2000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(141, 67, 1, 7.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(142, 67, 2, 7.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(143, 67, 3, 7.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(144, 67, 4, 9.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(145, 67, 5, 7.2000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(146, 68, 1, 7.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(147, 68, 2, 7.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(148, 68, 3, 7.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(149, 68, 4, 9.5000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(150, 68, 5, 6.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(151, 69, 1, 8.5000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(152, 69, 2, 9.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(153, 69, 3, 7.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(154, 69, 4, 9.0000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(155, 69, 5, 9.4000, '2026-06-17 08:50:02', '2026-07-10 02:59:33'),
(156, 70, 1, 10.0000, '2026-06-19 14:02:39', '2026-07-10 02:59:33'),
(157, 70, 2, 7.0000, '2026-06-19 14:02:39', '2026-07-10 02:59:33'),
(158, 70, 3, 7.0000, '2026-06-19 14:02:39', '2026-07-10 02:59:33'),
(159, 70, 4, 9.0000, '2026-06-19 14:02:39', '2026-07-10 02:59:33'),
(160, 70, 5, 8.4000, '2026-06-19 14:02:39', '2026-07-10 02:59:33'),
(161, 76, 1, 7.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(162, 76, 2, 9.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(163, 76, 3, 7.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(164, 76, 4, 9.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(165, 76, 5, 9.6000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(166, 77, 1, 8.5000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(167, 77, 2, 7.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(168, 77, 3, 7.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(169, 77, 4, 10.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(170, 77, 5, 7.2000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(171, 78, 1, 7.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(172, 78, 2, 7.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(173, 78, 3, 7.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(174, 78, 4, 9.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(175, 78, 5, 7.2000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(176, 79, 1, 7.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(177, 79, 2, 7.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(178, 79, 3, 7.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(179, 79, 4, 9.5000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(180, 79, 5, 6.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(181, 80, 1, 8.5000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(182, 80, 2, 9.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(183, 80, 3, 7.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(184, 80, 4, 9.0000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(185, 80, 5, 9.4000, '2026-06-23 12:59:27', '2026-07-10 02:59:32'),
(186, 81, 1, 9.7500, '2026-06-23 13:10:00', '2026-07-10 02:59:32'),
(187, 81, 2, 5.0000, '2026-06-23 13:10:00', '2026-07-10 02:59:32'),
(188, 81, 3, 6.0000, '2026-06-23 13:10:00', '2026-07-10 02:59:32'),
(189, 81, 4, 9.0000, '2026-06-23 13:10:00', '2026-07-10 02:59:32'),
(190, 81, 5, 6.0000, '2026-06-23 13:10:00', '2026-07-10 02:59:32'),
(191, 82, 1, 10.0000, '2026-06-23 13:21:31', '2026-07-10 02:59:32'),
(192, 82, 2, 9.0000, '2026-06-23 13:21:31', '2026-07-10 02:59:32'),
(193, 82, 3, 7.0000, '2026-06-23 13:21:31', '2026-07-10 02:59:32'),
(194, 82, 4, 6.0000, '2026-06-23 13:21:31', '2026-07-10 02:59:32'),
(195, 82, 5, 8.4000, '2026-06-23 13:21:31', '2026-07-10 02:59:32');

-- --------------------------------------------------------

--
-- Table structure for table `perbaikan_alat`
--

CREATE TABLE `perbaikan_alat` (
  `id` int(11) NOT NULL,
  `id_alternatif` int(11) NOT NULL,
  `unit_pemakai` varchar(100) DEFAULT NULL,
  `lokasi_unit` varchar(150) DEFAULT NULL,
  `penanggung_jawab` varchar(100) DEFAULT NULL,
  `tanggal_perbaikan` date NOT NULL,
  `tanggal_target` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `kerusakan` text DEFAULT NULL,
  `tindakan_perbaikan` text DEFAULT NULL,
  `biaya_perbaikan` decimal(15,2) DEFAULT 0.00,
  `prioritas` enum('rendah','sedang','tinggi','darurat') DEFAULT 'sedang',
  `status_perbaikan` enum('diajukan','diproses','selesai') DEFAULT 'diajukan',
  `catatan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `perbaikan_alat`
--

INSERT INTO `perbaikan_alat` (`id`, `id_alternatif`, `unit_pemakai`, `lokasi_unit`, `penanggung_jawab`, `tanggal_perbaikan`, `tanggal_target`, `tanggal_selesai`, `kerusakan`, `tindakan_perbaikan`, `biaya_perbaikan`, `prioritas`, `status_perbaikan`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 5, 'Produksi', 'Rambutan', 'Getra', '2026-05-02', '2026-05-02', '2026-05-05', 'karusakan di bagian filter', 'penggantian filter', 25000000.00, 'sedang', 'diajukan', 'kerusakan sedang', '2026-05-02 14:23:25', '2026-06-12 11:37:45'),
(2, 1, 'distribusi', 'Rambutan', 'Getra', '2026-06-12', '2026-06-12', '2026-06-25', 'rusak', 'diganti partnya', 2000000.00, 'rendah', 'diajukan', '', '2026-06-12 11:38:45', '2026-06-12 11:38:45');

-- --------------------------------------------------------

--
-- Table structure for table `qr_disposisi`
--

CREATE TABLE `qr_disposisi` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_dokumen` int(10) UNSIGNED NOT NULL,
  `id_usulan` int(10) UNSIGNED NOT NULL,
  `qr_hash` varchar(128) NOT NULL,
  `verification_url` varchar(255) DEFAULT NULL,
  `qr_file_path` varchar(255) DEFAULT NULL,
  `is_valid` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `qr_disposisi`
--

INSERT INTO `qr_disposisi` (`id`, `id_dokumen`, `id_usulan`, `qr_hash`, `verification_url`, `qr_file_path`, `is_valid`, `created_at`) VALUES
(1, 1, 1, '4c90cf890c8139ec25352b74e4a645bb65878512d9cc460fdb8efd123cb40528', 'http://localhost:8080/verifikasi-dokumen/4c90cf890c8139ec25352b74e4a645bb65878512d9cc460fdb8efd123cb40528', 'writable/uploads/disposisi/qr/4c90cf890c8139ec25352b74e4a645bb65878512d9cc460fdb8efd123cb40528.svg', 1, '2026-06-16 19:49:10'),
(2, 2, 23, '2b5c12fe1f00f8d4fa322605b572fc76395809830f4779d862c2dd3e19e703d4', 'http://localhost:8080/verifikasi-dokumen/2b5c12fe1f00f8d4fa322605b572fc76395809830f4779d862c2dd3e19e703d4', 'writable/uploads/disposisi/qr/2b5c12fe1f00f8d4fa322605b572fc76395809830f4779d862c2dd3e19e703d4.svg', 1, '2026-06-17 11:25:52');

-- --------------------------------------------------------

--
-- Table structure for table `realisasi_pengadaan`
--

CREATE TABLE `realisasi_pengadaan` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_usulan` int(10) UNSIGNED NOT NULL,
  `id_pengadaan` int(10) UNSIGNED DEFAULT NULL,
  `nomor_dokumen` varchar(50) NOT NULL,
  `nomor_po` varchar(80) DEFAULT NULL,
  `vendor` varchar(150) DEFAULT NULL,
  `tanggal_realisasi` date NOT NULL,
  `tanggal_po` date DEFAULT NULL,
  `total_realisasi` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status_realisasi` enum('draft','diproses','selesai','dibatalkan') NOT NULL DEFAULT 'draft',
  `keterangan` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_validasi`
--

CREATE TABLE `riwayat_validasi` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_usulan` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `role_user` enum('administrator','admin','sub_unit','gudang','seksi_gudang','manajer_umum','direktur','direksi','pengadaan') NOT NULL DEFAULT 'administrator',
  `aksi` enum('ajukan','verifikasi','banding','revisi','nilai_moora','proses_moora','rekomendasi','kembalikan','setujui','tolak','approval_bidang','approval_utama','approval_umum','disposisi','pengadaan','pembelian','upload_dokumen','serah_barang','penerimaan','realisasi','selesai') NOT NULL,
  `catatan` text DEFAULT NULL,
  `tanggal_aksi` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `riwayat_validasi`
--

INSERT INTO `riwayat_validasi` (`id`, `id_usulan`, `id_user`, `role_user`, `aksi`, `catatan`, `tanggal_aksi`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'gudang', 'ajukan', 'Usulan diajukan oleh Seksi Gudang untuk diproses lebih lanjut', '2026-04-16 15:05:21', '2026-04-16 15:05:21', '2026-06-15 20:36:37'),
(2, 13, 2, 'gudang', 'banding', 'tidak sesuai', '2026-06-12 02:59:56', '2026-06-12 02:59:56', '2026-06-15 20:36:37'),
(3, 13, 2, 'gudang', 'banding', 'tidak sesuai', '2026-06-12 03:03:03', '2026-06-12 03:03:03', '2026-06-15 20:36:37'),
(4, 15, 2, 'gudang', 'banding', 'pipa pe sudah ada', '2026-06-12 08:55:16', '2026-06-12 08:55:16', '2026-06-15 20:36:37'),
(5, 15, 2, 'gudang', 'banding', 'tidak sesuai', '2026-06-12 13:46:08', '2026-06-12 13:46:08', '2026-06-15 20:36:37'),
(6, 17, 5, 'sub_unit', 'ajukan', 'Usulan diajukan Sub Unit ke Seksi Gudang.', '2026-06-16 19:41:53', '2026-06-16 19:41:53', '2026-06-16 19:41:53'),
(7, 17, 2, 'gudang', 'verifikasi', 'Usulan diverifikasi Gudang dan siap masuk dataset MOORA.', '2026-06-16 19:43:06', '2026-06-16 19:43:06', '2026-06-16 19:43:06'),
(8, 1, 9, 'pengadaan', 'pembelian', 'sudah dibeli semua', '2026-06-16 19:55:44', '2026-06-16 19:55:44', '2026-06-16 19:55:44'),
(9, 1, 9, 'pengadaan', 'pengadaan', 'Status pengadaan diperbarui menjadi menunggu. sudah dibeli semua', '2026-06-16 19:56:21', '2026-06-16 19:56:21', '2026-06-16 19:56:21'),
(10, 1, 9, 'pengadaan', 'pengadaan', 'Status pengadaan diperbarui menjadi diproses. sudah dibeli semua', '2026-06-16 19:56:26', '2026-06-16 19:56:26', '2026-06-16 19:56:26'),
(11, 1, 9, 'pengadaan', 'pengadaan', 'Status pengadaan diperbarui menjadi po_terbit. sudah dibeli semua', '2026-06-16 19:56:31', '2026-06-16 19:56:31', '2026-06-16 19:56:31'),
(12, 1, 9, 'pengadaan', 'pengadaan', 'Status pengadaan diperbarui menjadi barang_datang. sudah dibeli semua', '2026-06-16 19:56:40', '2026-06-16 19:56:40', '2026-06-16 19:56:40'),
(13, 1, 9, 'pengadaan', 'serah_barang', 'proses diserahkan', '2026-06-16 19:57:35', '2026-06-16 19:57:35', '2026-06-16 19:57:35'),
(14, 1, 2, 'gudang', 'penerimaan', 'Diterima Gudang', '2026-06-16 19:58:26', '2026-06-16 19:58:26', '2026-06-16 19:58:26'),
(15, 18, 5, 'sub_unit', 'ajukan', 'Usulan diajukan Sub Unit ke Seksi Gudang.', '2026-06-16 22:48:01', '2026-06-16 22:48:01', '2026-06-16 22:48:01'),
(16, 19, 5, 'sub_unit', 'ajukan', 'Usulan diajukan Sub Unit ke Seksi Gudang.', '2026-06-17 02:01:14', '2026-06-17 02:01:14', '2026-06-17 02:01:14'),
(17, 20, 5, 'sub_unit', 'ajukan', 'Usulan diajukan Sub Unit ke Seksi Gudang.', '2026-06-17 02:02:51', '2026-06-17 02:02:51', '2026-06-17 02:02:51'),
(18, 20, 2, 'gudang', 'verifikasi', 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '2026-06-17 02:03:56', '2026-06-17 02:03:56', '2026-06-17 02:03:56'),
(19, 18, 2, 'gudang', 'verifikasi', 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '2026-06-17 02:06:27', '2026-06-17 02:06:27', '2026-06-17 02:06:27'),
(20, 21, 5, 'sub_unit', 'ajukan', 'Usulan diajukan Sub Unit ke Seksi Gudang.', '2026-06-17 04:16:22', '2026-06-17 04:16:22', '2026-06-17 04:16:22'),
(21, 22, 5, 'sub_unit', 'ajukan', 'Usulan diajukan Sub Unit ke Seksi Gudang.', '2026-06-17 04:16:53', '2026-06-17 04:16:53', '2026-06-17 04:16:53'),
(22, 22, 2, 'gudang', 'verifikasi', 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '2026-06-17 04:17:49', '2026-06-17 04:17:49', '2026-06-17 04:17:49'),
(23, 19, 2, 'gudang', 'verifikasi', 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '2026-06-17 05:37:06', '2026-06-17 05:37:06', '2026-06-17 05:37:06'),
(24, 23, 5, 'sub_unit', 'ajukan', 'Usulan diajukan Sub Unit ke Seksi Gudang.', '2026-06-17 08:46:45', '2026-06-17 08:46:45', '2026-06-17 08:46:45'),
(25, 23, 2, 'gudang', 'verifikasi', 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '2026-06-17 08:49:54', '2026-06-17 08:49:54', '2026-06-17 08:49:54'),
(26, 23, 2, 'gudang', 'proses_moora', 'Gudang memproses MOORA final mode RKA - Agregasi Dokumen. Versi hitung: 1781661002813', '2026-06-17 08:50:02', '2026-06-17 08:50:02', '2026-06-17 08:50:02'),
(27, 23, 3, 'manajer_umum', 'rekomendasi', 'Direkomendasikan ke Direktur berdasarkan hasil MOORA.', '2026-06-17 11:25:04', '2026-06-17 11:25:04', '2026-06-17 11:25:04'),
(28, 1, 9, 'pengadaan', 'upload_dokumen', 'Upload dokumen pengadaan: Hasil RKA.pdf', '2026-06-17 11:32:11', '2026-06-17 11:32:11', '2026-06-17 11:32:11'),
(29, 21, 2, 'gudang', 'verifikasi', 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '2026-06-18 14:55:03', '2026-06-18 14:55:03', '2026-06-18 14:55:03'),
(30, 12, 2, 'gudang', 'verifikasi', 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '2026-06-19 13:55:10', '2026-06-19 13:55:10', '2026-06-19 13:55:10'),
(31, 12, 2, 'gudang', 'proses_moora', 'Gudang memproses MOORA final mode Pesan Cepat - Per Item Barang. Versi hitung: 1781852154961', '2026-06-19 13:55:54', '2026-06-19 13:55:54', '2026-06-19 13:55:54'),
(32, 24, 5, 'sub_unit', 'ajukan', 'Usulan diajukan Sub Unit ke Seksi Gudang.', '2026-06-19 14:01:07', '2026-06-19 14:01:07', '2026-06-19 14:01:07'),
(33, 24, 2, 'gudang', 'verifikasi', 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '2026-06-19 14:01:58', '2026-06-19 14:01:58', '2026-06-19 14:01:58'),
(34, 24, 2, 'gudang', 'proses_moora', 'Gudang memproses MOORA final mode Pesan Cepat - Per Item Barang. Versi hitung: 1781852559764', '2026-06-19 14:02:39', '2026-06-19 14:02:39', '2026-06-19 14:02:39'),
(35, 26, 5, 'sub_unit', 'ajukan', 'Usulan diajukan Sub Unit ke Seksi Gudang.', '2026-06-23 12:57:53', '2026-06-23 12:57:53', '2026-06-23 12:57:53'),
(36, 25, 5, 'sub_unit', 'ajukan', 'Usulan diajukan Sub Unit ke Seksi Gudang.', '2026-06-23 12:58:10', '2026-06-23 12:58:10', '2026-06-23 12:58:10'),
(37, 26, 2, 'gudang', 'verifikasi', 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '2026-06-23 12:59:11', '2026-06-23 12:59:11', '2026-06-23 12:59:11'),
(38, 26, 2, 'gudang', 'proses_moora', 'Gudang memproses MOORA final mode RKA - Agregasi Dokumen. Versi hitung: 1782194367265', '2026-06-23 12:59:27', '2026-06-23 12:59:27', '2026-06-23 12:59:27'),
(39, 27, 5, 'sub_unit', 'ajukan', 'Usulan diajukan Sub Unit ke Seksi Gudang.', '2026-06-23 13:08:58', '2026-06-23 13:08:58', '2026-06-23 13:08:58'),
(40, 27, 2, 'gudang', 'verifikasi', 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '2026-06-23 13:09:51', '2026-06-23 13:09:51', '2026-06-23 13:09:51'),
(41, 27, 2, 'gudang', 'proses_moora', 'Gudang memproses MOORA final mode Pesan Cepat - Per Item Barang. Versi hitung: 1782195001023', '2026-06-23 13:10:01', '2026-06-23 13:10:01', '2026-06-23 13:10:01'),
(42, 28, 5, 'sub_unit', 'ajukan', 'Usulan diajukan Sub Unit ke Seksi Gudang.', '2026-06-23 13:20:38', '2026-06-23 13:20:38', '2026-06-23 13:20:38'),
(43, 28, 2, 'gudang', 'verifikasi', 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', '2026-06-23 13:21:19', '2026-06-23 13:21:19', '2026-06-23 13:21:19'),
(44, 28, 2, 'gudang', 'proses_moora', 'Gudang memproses MOORA final mode Pesan Cepat - Per Item Barang. Versi hitung: 1782195691268', '2026-06-23 13:21:31', '2026-06-23 13:21:31', '2026-06-23 13:21:31');

-- --------------------------------------------------------

--
-- Table structure for table `setting_sistem`
--

CREATE TABLE `setting_sistem` (
  `id` int(10) UNSIGNED NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_label` varchar(150) DEFAULT NULL,
  `setting_group` varchar(50) NOT NULL DEFAULT 'umum',
  `setting_type` varchar(30) NOT NULL DEFAULT 'text',
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `setting_sistem`
--

INSERT INTO `setting_sistem` (`id`, `setting_key`, `setting_value`, `setting_label`, `setting_group`, `setting_type`, `description`, `created_at`, `updated_at`) VALUES
(1, 'nama_perusahaan', 'Perumda Tirta Musi Palembang', 'Nama Perusahaan', 'umum', 'text', 'Nama instansi yang tampil pada sistem.', '2026-06-16 02:44:47', '2026-06-16 22:43:37'),
(2, 'nama_aplikasi', 'SPK MOORA Pengadaan Barang', 'Nama Aplikasi', 'umum', 'text', 'Nama aplikasi sistem.', '2026-06-16 02:44:47', '2026-06-16 22:43:37'),
(3, 'moora_mode', 'per_periode', 'Mode Perhitungan MOORA', 'moora', 'text', 'Pilihan per_usulan atau per_periode.', '2026-06-16 02:44:47', '2026-06-16 22:43:37'),
(4, 'moora_status_dataset', 'diverifikasi', 'Status Dataset Default', 'moora', 'text', 'Status usulan yang otomatis tampil sebagai dataset default pada halaman kalkulasi.', '2026-06-16 02:44:47', '2026-06-16 22:43:37'),
(5, 'moora_cost_mode', 'standard_moora', 'Mode Cost MOORA', 'moora', 'text', 'V4 Final menggunakan standard_moora agar kriteria cost dikurangkan secara normal.', '2026-06-16 02:44:47', '2026-06-16 22:43:37'),
(6, 'moora_auto_recalculate', '1', 'Auto Recalculate', 'moora', 'text', '1 aktif, 0 nonaktif.', '2026-06-16 02:44:47', '2026-06-16 22:43:37'),
(7, 'approval_direktur_multilevel', '1', 'Aktifkan Multi-Level Approval Direktur', 'approval', 'boolean', '1 aktif, 0 nonaktif.', '2026-06-15 20:36:38', '2026-06-15 20:36:38'),
(8, 'enable_notifikasi', '1', 'Aktifkan Notifikasi Internal', 'notifikasi', 'boolean', '1 aktif, 0 nonaktif.', '2026-06-15 20:36:38', '2026-06-16 19:33:17'),
(9, 'format_nomor_disposisi', 'DISP/{YYYY}/{MM}/{SEQ}', 'Format Nomor Disposisi', 'dokumen', 'text', 'Format nomor dokumen disposisi digital.', '2026-06-15 20:36:38', '2026-06-16 19:33:17'),
(10, 'qr_base_url', 'http://localhost:8080/verifikasi-dokumen', 'Base URL Verifikasi QR', 'dokumen', 'text', 'URL dasar untuk verifikasi dokumen disposisi melalui QR Code.', '2026-06-15 20:36:38', '2026-06-16 19:33:17'),
(11, 'workflow_status_final', 'draft,diajukan,diverifikasi,moora_selesai,menunggu_direktur_bidang,menunggu_direktur_utama,menunggu_direktur_umum,disposisi_pengadaan,diproses_pengadaan,menunggu_penerimaan,selesai', 'Workflow Status Final Usulan', 'workflow', 'text', 'Status utama antar-role final yang dipakai source Patch 9.', '2026-06-16 17:50:02', '2026-06-17 03:20:22'),
(16, 'moora_engine_owner', 'gudang', 'Role Pemroses MOORA Operasional', 'moora', 'text', 'V4 Final: Gudang menjadi engine utama pemroses MOORA operasional.', '2026-06-16 22:38:23', '2026-06-16 22:38:23'),
(17, 'moora_rka_mode', 'rka_aggregate', 'Mode MOORA RKA', 'moora', 'text', 'RKA dihitung sebagai satu keputusan agregat dokumen. Alias lama aggregate_per_usulan dinormalisasi.', '2026-06-16 22:38:23', '2026-06-16 17:09:20'),
(18, 'moora_pesan_cepat_mode', 'item_based', 'Mode MOORA Pesan Cepat', 'moora', 'text', 'Pesan Cepat dihitung per item/detail barang.', '2026-06-16 22:38:23', '2026-06-16 17:09:20'),
(19, 'moora_auto_generate_penilaian', '1', 'Auto Generate Penilaian Gudang', 'moora', 'boolean', 'Nilai C1-C5 dibuat otomatis dari data barang, stok, biaya, kondisi, movement type, dan alasan kebutuhan.', '2026-06-16 22:38:23', '2026-06-16 22:38:23'),
(20, 'moora_engine_version', 'PATCH_10_FINAL_COMPLETION_ENTERPRISE', 'Versi Engine MOORA', 'moora', 'text', 'Patch 10: Gudang engine operasional, hasil aktif via v_latest_moora_context, workflow completion, dokumen dan distribusi dikunci.', '2026-06-16 22:38:23', '2026-06-17 04:05:00'),
(22, 'moora_maintenance_recalculate', 'enabled', 'Maintenance Recalculate Historis', 'moora', 'text', 'Mengizinkan Admin menjalankan konsolidasi historis non-destruktif tanpa mengubah status workflow.', '2026-06-16 23:04:35', '2026-06-16 23:04:35'),
(23, 'moora_audit_engine_enabled', '1', 'Audit Engine MOORA', 'moora', 'boolean', 'Audit engine aktif: hasil_moora, moora_engine_log, checksum, dan mode final dipantau.', '2026-06-16 23:04:35', '2026-06-16 17:09:20'),
(25, 'moora_legacy_mode_alias', 'aggregate_per_usulan=>rka_aggregate;item_per_detail=>item_based', 'Alias Legacy Mode MOORA', 'moora', 'text', 'Catatan migrasi Patch V6 agar audit tidak membaca dua istilah untuk konsep yang sama.', '2026-06-16 17:09:20', '2026-06-16 17:09:20'),
(26, 'moora_operational_owner', 'gudang', 'Pemilik Engine Operasional', 'moora', 'text', 'Gudang menjadi pemroses resmi MOORA untuk usulan aktif.', '2026-06-16 17:09:20', '2026-06-17 03:20:22'),
(31, 'moora_latest_view_strategy', 'id_usulan+mode_hitung', 'Strategi Latest MOORA', 'moora', 'text', 'Single source of truth memakai v_latest_moora dual-mode safe.', '2026-06-17 01:02:22', '2026-06-17 01:02:22'),
(32, 'moora_global_rank_status', 'moora_selesai', 'Status Ranking Global Aktif', 'moora', 'text', 'Ranking global aktif hanya membaca status moora_selesai agar tidak bercampur approval/pengadaan.', '2026-06-17 01:02:22', '2026-06-17 01:02:22'),
(33, 'moora_workflow_locked_status', 'moora_selesai,disposisi_pengadaan,diproses_pengadaan,menunggu_penerimaan,selesai', 'Status MOORA Terkunci Workflow', 'workflow', 'text', 'Status yang boleh tampil sebagai hasil MOORA locked lintas role.', '2026-06-17 01:02:22', '2026-06-17 01:02:22'),
(35, 'patch_8_final_demo_workflow_lock', 'installed', 'Patch 8 Final Demo Workflow Lock', 'workflow', 'text', 'Mengunci closing loop: penerimaan Gudang -> distribusi -> konfirmasi Sub Unit -> selesai.', '2026-06-17 01:59:32', '2026-06-17 01:59:32'),
(36, 'workflow_close_owner', 'sub_unit_confirmation', 'Pemilik Closing Workflow', 'workflow', 'text', 'Status selesai hanya dibuat setelah Sub Unit konfirmasi semua distribusi barang.', '2026-06-17 01:59:32', '2026-06-17 04:05:00'),
(37, 'moora_rka_latest_row_policy', 'single_best_row', 'Kebijakan Latest RKA', 'moora', 'text', 'View latest MOORA hanya menampilkan 1 baris RKA agregat walaupun data historis masih menyimpan beberapa ranking lama.', '2026-06-17 01:59:32', '2026-06-17 01:59:32'),
(38, 'patch_9_final_stabilization_lock', 'installed', 'Patch 9 Final Stabilization Lock', 'workflow', 'text', 'Route/view path, Admin lock, single source MOORA, file RKA, dan audit history no-delete sudah dikunci.', '2026-06-17 03:20:22', '2026-06-17 03:20:22'),
(39, 'moora_result_source', 'v_latest_moora_context', 'Sumber Tunggal Hasil MOORA', 'moora', 'text', 'Semua role membaca hasil aktif dari v_latest_moora_context/v_moora_workflow_locked, bukan raw hasil_moora.', '2026-06-17 03:20:22', '2026-06-17 04:05:00'),
(40, 'moora_no_delete_history', '1', 'Histori MOORA Tidak Dihapus', 'moora', 'boolean', 'hasil_moora menyimpan semua versi audit; view latest memilih hasil aktif terbaru.', '2026-06-17 03:20:22', '2026-06-17 03:20:22'),
(41, 'admin_moora_access_mode', 'audit_training_only', 'Mode Akses MOORA Admin', 'moora', 'text', 'Admin hanya monitoring, audit, maintenance historis, dan training/simulasi; bukan proses usulan aktif.', '2026-06-17 03:20:22', '2026-06-17 03:20:22'),
(42, 'rka_document_storage', 'writable/uploads/rka', 'Lokasi Upload Dokumen RKA', 'dokumen', 'text', 'File RKA Sub Unit disimpan di writable/uploads/rka dan dibuka melalui route dokumen-rka.', '2026-06-17 03:20:22', '2026-06-17 03:20:22'),
(46, 'patch_10_final_completion_enterprise', 'installed', 'Patch 10 Final Completion Enterprise', 'workflow', 'text', 'Patch 10 menggabungkan active workflow completion, single source hasil MOORA, RKA upload final, dan guard pengadaan-dokumen-distribusi secara non-destruktif.', '2026-06-17 04:05:00', '2026-06-17 04:05:00'),
(47, 'rka_upload_final_policy', 'excel_import+official_document', 'Kebijakan Upload RKA Final', 'dokumen', 'text', 'Excel RKA dipakai untuk import barang, dokumen resmi RKA dipakai sebagai lampiran bukti.', '2026-06-17 04:05:00', '2026-06-17 04:05:00'),
(48, 'pengadaan_document_guard', 'enabled', 'Guard Dokumen Pengadaan', 'pengadaan', 'boolean', 'Pengadaan wajib memiliki dokumen sebelum serah barang ke Gudang.', '2026-06-17 04:05:00', '2026-06-17 04:05:00'),
(52, 'patch_11_urgent_moora_workflow_fix', 'installed', 'Patch 11 Urgent MOORA Workflow Fix', 'workflow', 'text', 'Fix error updateOrInsert, ranking global RKA, training MOORA Admin, sinkron stok, dan metadata dokumen pengadaan lama.', '2026-06-17 04:40:05', '2026-06-17 04:40:05'),
(53, 'moora_global_ranking_source', 'v_moora_global_final.global_ranking', 'Sumber Ranking Global MOORA', 'moora', 'text', 'Manajer Umum membaca ranking global dari v_moora_global_final jika kolom global_ranking tersedia.', '2026-06-17 04:40:05', '2026-06-17 04:40:05'),
(54, 'stok_single_source_policy', 'alternatif_synced_to_stok_barang', 'Kebijakan Single Source Stok', 'gudang', 'text', 'Operasional stok tetap memakai alternatif.stok dan otomatis disinkronkan ke stok_barang agar tidak terjadi beda angka.', '2026-06-17 04:40:05', '2026-06-17 04:40:05'),
(55, 'admin_training_moora_enabled', '1', 'Training MOORA Admin Aktif', 'moora', 'boolean', 'Admin dapat menjalankan simulator sensitivitas bobot tanpa memproses workflow aktif.', '2026-06-17 04:40:05', '2026-06-17 04:40:05'),
(56, 'patch_auto_fix_full_system', 'installed', 'Patch Auto Fix Full System', 'workflow', 'text', 'Auto detail_usulan, auto penilaian, MooraEngine wrapper, dan command repair data flow terpasang.', '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(57, 'moora_auto_fix_detail_usulan', '1', 'Auto Fix Detail Usulan', 'moora', 'boolean', 'Jika detail_usulan kosong, sistem membuat fallback non-destruktif agar MOORA tetap berjalan.', '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(58, 'moora_runtime_safe_engine', '1', 'Runtime Safe Engine MOORA', 'moora', 'boolean', 'Route lama dan baru diarahkan ke MooraService final dengan versi_hitung dan audit lengkap.', '2026-06-17 08:29:19', '2026-06-17 08:29:19'),
(59, 'patch_recovery_register_direktur_disposisi', 'installed', 'Patch Recovery Register Direktur Disposisi', 'workflow', 'text', 'Fix duplicate register #1062, sinkron approved registration ke users, tombol kembali dokumen disposisi, dan kejelasan 3 approval Direktur.', '2026-06-17 12:29:30', '2026-06-17 12:29:30'),
(60, 'moora_global_rka_last_run', '2026-07-10 02:58:21', 'Terakhir Ranking Global RKA', 'moora', 'text', 'Waktu terakhir Patch 11 menjalankan ranking global antar dokumen RKA aktif.', '2026-07-10 02:57:23', '2026-07-10 02:58:21');

-- --------------------------------------------------------

--
-- Table structure for table `stok_barang`
--

CREATE TABLE `stok_barang` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_alternatif` int(10) UNSIGNED NOT NULL,
  `stok_saat_ini` int(11) NOT NULL DEFAULT 0,
  `stok_minimum` int(11) NOT NULL DEFAULT 0,
  `lokasi_gudang` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stok_barang`
--

INSERT INTO `stok_barang` (`id`, `id_alternatif`, `stok_saat_ini`, `stok_minimum`, `lokasi_gudang`, `created_at`, `updated_at`) VALUES
(1, 1, 9, 2, 'Gudang Utama', '2026-04-16 15:05:21', '2026-06-17 04:40:04'),
(2, 2, 50, 5, 'Gudang Pipa', '2026-04-16 15:05:21', '2026-06-17 04:40:04'),
(3, 3, 3, 1, 'Gudang Instrumentasi', '2026-04-16 15:05:21', '2026-06-17 04:40:04'),
(4, 4, 299, 400, 'Gudang Metering', '2026-04-16 15:05:21', '2026-06-17 04:40:04'),
(5, 5, 9, 2, 'Gudang Peralatan Berat', '2026-04-16 15:05:21', '2026-06-17 04:40:04'),
(6, 20, 0, 0, 'Gudang Utama', '2026-06-17 04:40:04', '2026-06-17 04:40:04'),
(7, 21, 0, 0, 'Gudang Utama', '2026-06-17 04:40:04', '2026-06-17 04:40:04'),
(8, 22, 0, 0, 'Gudang Utama', '2026-06-17 04:40:04', '2026-06-17 04:40:04'),
(9, 23, 0, 0, 'Gudang Utama', '2026-06-17 04:40:04', '2026-06-17 04:40:04'),
(10, 24, 0, 0, 'Gudang Utama', '2026-06-17 04:40:04', '2026-06-17 04:40:04'),
(11, 25, 0, 0, 'Gudang Utama', '2026-06-17 04:40:04', '2026-06-17 04:40:04'),
(12, 26, 0, 0, 'Gudang Utama', '2026-06-17 04:40:04', '2026-06-17 04:40:04'),
(13, 27, 0, 0, 'Gudang Utama', '2026-06-17 04:40:04', '2026-06-17 04:40:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Gunakan password_hash() di CodeIgniter 4 untuk produksi',
  `role` enum('administrator','gudang','sub_unit','manajer_umum','direktur','pengadaan') NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `registration_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `username`, `email`, `password`, `role`, `is_active`, `last_login`, `created_at`, `updated_at`, `registration_id`) VALUES
(1, 'Administrator Sistem', 'admin', 'admin@spkmoora.local', 'admin123', 'administrator', 1, NULL, '2026-04-16 15:05:21', '2026-07-10 03:28:26', NULL),
(2, 'Seksi Gudang', 'gudang', 'gudang@spkmoora.local', 'gudang123', 'gudang', 1, NULL, '2026-04-16 15:05:21', '2026-07-10 03:28:39', NULL),
(3, 'Manajer Umum', 'manajer', 'manajer@spkmoora.local', 'Mumum123', 'manajer_umum', 1, NULL, '2026-04-16 15:05:21', '2026-07-10 03:28:54', NULL),
(4, 'Direktur', 'direksi', 'direksi@spkmoora.local', 'direksi123', 'direktur', 1, NULL, '2026-04-16 15:05:21', '2026-07-10 03:29:01', NULL),
(5, 'Sub Unit Pengusul', 'Subunit', 'subunit@spkmoora.local', 'unit123', 'sub_unit', 1, NULL, '2026-04-24 17:12:31', '2026-07-10 03:29:08', NULL),
(9, 'Bagian Pengadaan', 'pengadaan', 'pengadaan@spkmoora.local', 'pengadaan123', 'pengadaan', 1, NULL, '2026-06-15 20:36:37', '2026-07-10 03:29:16', NULL),
(12, 'M. Agung Kusuma Bangun', 'suma_Sub Unit', 'sumaa0904@gmail.com', '$2y$10$sVHc5XrvN781WbkCHpveLOTbMEanDzusnSZGZChwzQ0ZafGz0zIa6', 'sub_unit', 1, NULL, '2026-06-15 09:11:54', '2026-07-10 03:02:44', 3),
(13, 'Fia Utami', 'Fia_SekretUmum', 'fiautami12r@gmail.com', '$2y$10$5nCWyPskNiS2oRsP0udr/.5295t8zSlpripjaG1LYp609yiXgLSie', 'sub_unit', 1, NULL, '2026-06-16 12:27:02', '2026-06-17 12:29:30', 4),
(14, 'Justin Andrea', 'justinsubunit', 'justn123@gmail.com', '$2y$10$3V22qN4ZjfRLNN9rXrJhR.w/Ltr8./Sl05uy8m1nUEsmadvAOetKG', 'sub_unit', 1, NULL, '2026-06-17 12:33:46', '2026-06-17 12:33:46', 8);

-- --------------------------------------------------------

--
-- Table structure for table `user_registration`
--

CREATE TABLE `user_registration` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('administrator','gudang','sub_unit','manajer_umum','direktur','pengadaan') DEFAULT 'sub_unit',
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_registration`
--

INSERT INTO `user_registration` (`id`, `nama_lengkap`, `username`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'M. Agung Kusuma Bangun', 'Suma123', 'Suma123@gmail.com', '$2y$10$E5g.TUoANhCKzJHPla0lAOyVVkXGQamf.X43HW/Pw0uYTY2e2SeEC', 'sub_unit', 'approved', '2026-06-13 12:54:25', '2026-06-14 23:51:31'),
(2, 'Dimas Kurnia Putra', 'dima', 'dimas123@gmail.com', '$2y$10$HlbBHMfwgSQx.zNK0DNZ6.txaY5N6QtNqb0x4Rs1N/bcTz.VAoKT2', 'sub_unit', 'rejected', '2026-06-15 09:08:32', '2026-06-15 09:09:46'),
(3, 'M. Agung Kusuma Bangun', 'suma_Sub Unit', 'sumaa0904@gmail.com', '$2y$10$sVHc5XrvN781WbkCHpveLOTbMEanDzusnSZGZChwzQ0ZafGz0zIa6', 'sub_unit', 'approved', '2026-06-15 09:11:54', '2026-06-15 09:12:38'),
(4, 'Fia Utami', 'Fia_SekretUmum', 'fiautami12r@gmail.com', '$2y$10$5nCWyPskNiS2oRsP0udr/.5295t8zSlpripjaG1LYp609yiXgLSie', 'sub_unit', 'approved', '2026-06-16 12:27:02', '2026-06-16 12:27:50'),
(8, 'Justin Andrea', 'justinsubunit', 'justn123@gmail.com', '$2y$10$3V22qN4ZjfRLNN9rXrJhR.w/Ltr8./Sl05uy8m1nUEsmadvAOetKG', 'sub_unit', 'approved', '2026-06-17 12:32:27', '2026-06-17 12:33:46');

-- --------------------------------------------------------

--
-- Table structure for table `user_registration_approval_log`
--

CREATE TABLE `user_registration_approval_log` (
  `id` int(11) NOT NULL,
  `registration_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `action` enum('approved','rejected') NOT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_registration_history`
--

CREATE TABLE `user_registration_history` (
  `id` int(11) NOT NULL,
  `registration_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL,
  `changed_by` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_registration_history`
--

INSERT INTO `user_registration_history` (`id`, `registration_id`, `status`, `changed_by`, `note`, `created_at`) VALUES
(1, 1, 'approved', 1, 'Akun disetujui dan diaktifkan oleh Administrator.', '2026-06-14 23:51:31'),
(2, 2, 'rejected', 1, 'maaf identitas belum jelas', '2026-06-15 09:09:46'),
(3, 3, 'approved', 1, 'disetujui', '2026-06-15 09:12:38'),
(4, 4, 'approved', 1, 'selamat datang', '2026-06-16 12:27:50'),
(5, 8, 'approved', 1, 'Akun disetujui dan diaktifkan oleh Administrator.', '2026-06-17 12:33:46');

-- --------------------------------------------------------

--
-- Table structure for table `usulan_pengadaan`
--

CREATE TABLE `usulan_pengadaan` (
  `id` int(10) UNSIGNED NOT NULL,
  `nomor_usulan` varchar(50) NOT NULL,
  `tanggal_usulan` date NOT NULL,
  `unit_pengusul` varchar(100) NOT NULL,
  `id_user_pengusul` int(10) UNSIGNED NOT NULL,
  `status` enum('draft','diajukan','verifikasi_gudang','banding_gudang','direvisi','diverifikasi','menunggu_moora','moora_diproses','moora_selesai','direkomendasikan','dikembalikan','menunggu_direktur_bidang','disetujui_direktur_bidang','menunggu_direktur_utama','disetujui_direktur_utama','menunggu_direktur_umum','disposisi_pengadaan','diproses_pengadaan','selesai_pengadaan','menunggu_penerimaan','direalisasi','selesai','disetujui','ditolak') NOT NULL DEFAULT 'draft',
  `status_validasi` varchar(50) DEFAULT NULL,
  `approval_stage` enum('none','direktur_bidang','direktur_utama','direktur_umum','selesai') NOT NULL DEFAULT 'none',
  `jenis_usulan` enum('RKA','Pesan Cepat') NOT NULL DEFAULT 'RKA',
  `catatan_validasi` text DEFAULT NULL,
  `validated_by` int(11) DEFAULT NULL,
  `validated_at` datetime DEFAULT NULL,
  `catatan_pengusul` text DEFAULT NULL,
  `file_rka_path` varchar(255) DEFAULT NULL,
  `file_rka_excel_path` varchar(255) DEFAULT NULL,
  `file_rka_dokumen_path` varchar(255) DEFAULT NULL,
  `catatan_verifikasi` text DEFAULT NULL,
  `catatan_manajer` text DEFAULT NULL,
  `catatan_banding_gudang` text DEFAULT NULL,
  `banding_by` int(11) DEFAULT NULL,
  `banding_at` datetime DEFAULT NULL,
  `catatan_direksi` text DEFAULT NULL,
  `catatan_pengadaan` text DEFAULT NULL,
  `catatan_penerimaan` text DEFAULT NULL,
  `nomor_disposisi` varchar(80) DEFAULT NULL,
  `tanggal_disposisi` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usulan_pengadaan`
--

INSERT INTO `usulan_pengadaan` (`id`, `nomor_usulan`, `tanggal_usulan`, `unit_pengusul`, `id_user_pengusul`, `status`, `status_validasi`, `approval_stage`, `jenis_usulan`, `catatan_validasi`, `validated_by`, `validated_at`, `catatan_pengusul`, `file_rka_path`, `file_rka_excel_path`, `file_rka_dokumen_path`, `catatan_verifikasi`, `catatan_manajer`, `catatan_banding_gudang`, `banding_by`, `banding_at`, `catatan_direksi`, `catatan_pengadaan`, `catatan_penerimaan`, `nomor_disposisi`, `tanggal_disposisi`, `created_at`, `updated_at`) VALUES
(1, 'UP-2026-001', '2026-04-16', 'Seksi Gudang', 2, 'menunggu_penerimaan', 'menunggu_konfirmasi_subunit', 'selesai', 'RKA', 'bagus', 4, '2026-04-27 20:52:29', 'Usulan prioritas pengadaan peralatan operasional triwulan II', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'bagus', 'proses diserahkan', 'Diterima Gudang', NULL, NULL, '2026-04-16 15:05:21', '2026-06-17 01:59:32'),
(2, 'UP-20260426-001', '2026-04-26', 'Sub Unit Gudang', 5, 'ditolak', 'ditolak', 'none', 'RKA', 'perbaiki', 4, '2026-04-27 20:53:03', 'Getra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'perbaiki', NULL, NULL, NULL, NULL, '2026-04-26 18:00:14', '2026-04-27 20:53:03'),
(3, 'UP-20260507-001', '2026-05-07', 'Sub Unit Gudang', 5, 'draft', 'menunggu', 'none', 'RKA', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-07 14:08:17', '2026-05-07 14:08:17'),
(4, 'UP-20260608-001', '2026-06-08', 'Sub Unit Gudang', 5, 'banding_gudang', 'banding_gudang', 'none', 'RKA', NULL, NULL, NULL, 'fia', NULL, NULL, NULL, 'barang terlalu dikit', NULL, 'Barang stok minimum tidak sesuai, perlu revisi', 2, '2026-06-12 02:52:49', NULL, NULL, NULL, NULL, NULL, '2026-06-08 23:22:58', '2026-06-12 02:52:49'),
(5, 'UP-20260611-001', '2026-06-11', 'Sub Unit Gudang', 5, 'draft', 'dikembalikan', 'none', 'RKA', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-11 21:11:56', '2026-06-12 09:16:24'),
(6, 'UP-20260611-002', '2026-06-11', 'Sub Unit Gudang', 5, 'draft', 'menunggu', 'none', 'RKA', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-11 21:19:16', '2026-06-11 21:19:16'),
(7, 'UP-20260611-003', '2026-06-11', 'Sub Unit Gudang', 5, 'draft', 'menunggu', 'none', 'RKA', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-11 21:19:58', '2026-06-11 21:19:58'),
(8, 'UP-20260611-004', '2026-06-11', 'Sub Unit Gudang', 5, 'draft', 'menunggu', 'none', 'RKA', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-11 21:25:11', '2026-06-11 21:25:11'),
(9, 'UP-20260611-005', '2026-06-11', 'Sub Unit Gudang', 5, 'diajukan', 'menunggu', 'none', 'Pesan Cepat', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-11 22:07:23', '2026-06-13 10:52:27'),
(10, 'UP-20260611-006', '2026-06-11', 'Sub Unit Gudang', 5, 'draft', 'menunggu', 'none', 'RKA', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-11 22:07:48', '2026-06-11 22:07:48'),
(11, 'UP-20260611-007', '2026-06-11', 'Sub Unit Gudang', 5, 'draft', 'menunggu', 'none', 'RKA', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-11 22:35:36', '2026-06-11 22:35:36'),
(12, 'UP-20260611-008', '2026-06-11', 'Sub Unit Gudang', 5, 'moora_selesai', 'moora_selesai', 'none', 'Pesan Cepat', NULL, 2, '2026-06-19 13:55:10', '', NULL, NULL, NULL, 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-11 23:11:50', '2026-06-19 13:55:54'),
(13, 'UP-20260611-009', '2026-06-11', 'Sub Unit Gudang', 5, 'banding_gudang', 'banding_gudang', 'none', 'RKA', NULL, NULL, NULL, '', NULL, NULL, NULL, 'pipa pe sudah ada', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-11 23:16:59', '2026-06-12 03:03:03'),
(14, 'UP-20260612-001', '2026-06-12', 'Sub PKA', 5, 'ditolak', 'ditolak', 'none', 'Pesan Cepat', 'belum disetujui', 4, '2026-06-12 10:26:15', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'belum disetujui', NULL, NULL, NULL, NULL, '2026-06-12 03:24:56', '2026-06-12 10:26:15'),
(15, 'UP-20260612-002', '2026-06-12', 'Sub PKA', 5, 'banding_gudang', 'direkomendasikan', 'none', 'RKA', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-12 08:54:12', '2026-06-12 13:46:08'),
(16, 'UP-20260613-001', '2026-06-13', 'PSDM', 5, 'diverifikasi', 'diverifikasi', 'none', 'RKA', NULL, 2, '2026-06-13 08:20:19', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-13 08:19:09', '2026-06-13 08:20:19'),
(17, 'UP-20260616-001', '2026-06-16', 'PSDM', 5, 'diverifikasi', 'diverifikasi', 'none', 'RKA', NULL, 2, '2026-06-16 19:43:06', '', NULL, NULL, NULL, 'Usulan diverifikasi Gudang dan siap masuk dataset MOORA.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-16 19:41:42', '2026-06-16 19:43:06'),
(18, 'UP-20260616-002', '2026-06-16', 'Sub PKA', 5, 'diverifikasi', 'diverifikasi', 'none', 'Pesan Cepat', NULL, 2, '2026-06-17 02:06:27', '', NULL, NULL, NULL, 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-16 22:47:56', '2026-06-17 02:06:27'),
(19, 'UP-20260617-001', '2026-06-17', 'Sub PKA', 5, 'diverifikasi', 'diverifikasi', 'none', 'RKA', NULL, 2, '2026-06-17 05:37:06', '', NULL, NULL, NULL, 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-17 02:01:01', '2026-06-17 05:37:06'),
(20, 'UP-20260617-002', '2026-06-17', 'PSDM', 5, 'diverifikasi', 'diverifikasi', 'none', 'Pesan Cepat', NULL, 2, '2026-06-17 02:03:56', '', NULL, NULL, NULL, 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-17 02:02:33', '2026-06-17 02:03:56'),
(21, 'UP-20260617-003', '2026-06-17', 'PSDM', 5, 'diverifikasi', 'diverifikasi', 'none', 'RKA', NULL, 2, '2026-06-18 14:55:03', '', 'writable/uploads/rka/rka_20260617_041616_d866c5c5.xlsx', 'writable/uploads/rka/rka_20260617_041616_d866c5c5.xlsx', NULL, 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-17 04:16:16', '2026-06-18 14:55:03'),
(22, 'UP-20260617-004', '2026-06-17', 'Sub PKA', 5, 'diverifikasi', 'diverifikasi', 'none', 'Pesan Cepat', NULL, 2, '2026-06-17 04:17:49', '', NULL, NULL, NULL, 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-17 04:16:49', '2026-06-17 04:17:49'),
(23, 'UP-20260617-005', '2026-06-17', 'Sub PKA', 5, 'menunggu_direktur_bidang', 'direkomendasikan', 'direktur_bidang', 'RKA', NULL, 2, '2026-06-17 08:49:54', '', 'writable/uploads/rka/rka_20260617_084528_510f60d8.xlsx', 'writable/uploads/rka/rka_20260617_084528_510f60d8.xlsx', NULL, 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', 'Direkomendasikan ke Direktur berdasarkan hasil MOORA.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-17 08:45:28', '2026-06-17 11:25:04'),
(24, 'UP-20260619-001', '2026-06-19', 'Sub PKA', 5, 'moora_selesai', 'moora_selesai', 'none', 'Pesan Cepat', NULL, 2, '2026-06-19 14:01:58', 'urgensi untuk lapangan', NULL, NULL, NULL, 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-19 14:01:03', '2026-06-19 14:02:39'),
(25, 'UP-20260623-001', '2026-06-23', 'pka', 5, 'diajukan', 'menunggu', 'none', 'RKA', NULL, NULL, NULL, 'rka 2026 juni', 'writable/uploads/rka/rka_20260623_125735_d1fce588.xlsx', 'writable/uploads/rka/rka_20260623_125735_d1fce588.xlsx', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-23 12:57:35', '2026-06-23 12:58:10'),
(26, 'UP-20260623-002', '2026-06-23', 'pka', 5, 'moora_selesai', 'moora_selesai', 'none', 'RKA', NULL, 2, '2026-06-23 12:59:11', 'rka 2026 juni', 'writable/uploads/rka/rka_20260623_125735_034efa34.xlsx', 'writable/uploads/rka/rka_20260623_125735_034efa34.xlsx', NULL, 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-23 12:57:35', '2026-06-23 12:59:27'),
(27, 'UP-20260623-003', '2026-06-23', 'PSDM', 5, 'moora_selesai', 'moora_selesai', 'none', 'Pesan Cepat', NULL, 2, '2026-06-23 13:09:51', 'uregent', NULL, NULL, NULL, 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-23 13:08:55', '2026-06-23 13:10:01'),
(28, 'UP-20260623-004', '2026-06-23', 'Sub Unit Gudang', 5, 'moora_selesai', 'moora_selesai', 'none', 'Pesan Cepat', NULL, 2, '2026-06-23 13:21:19', 'URGENT UNTUK OPERASIONAL', NULL, NULL, NULL, 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-23 13:20:34', '2026-06-23 13:21:31');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_auto_fix_moora_health`
-- (See below for the actual view)
--
CREATE TABLE `v_auto_fix_moora_health` (
`id_usulan` int(10) unsigned
,`nomor_usulan` varchar(50)
,`jenis_usulan` enum('RKA','Pesan Cepat')
,`status` enum('draft','diajukan','verifikasi_gudang','banding_gudang','direvisi','diverifikasi','menunggu_moora','moora_diproses','moora_selesai','direkomendasikan','dikembalikan','menunggu_direktur_bidang','disetujui_direktur_bidang','menunggu_direktur_utama','disetujui_direktur_utama','menunggu_direktur_umum','disposisi_pengadaan','diproses_pengadaan','selesai_pengadaan','menunggu_penerimaan','direalisasi','selesai','disetujui','ditolak')
,`jumlah_detail` bigint(21)
,`jumlah_penilaian` bigint(21)
,`jumlah_hasil` bigint(21)
,`health_status` varchar(16)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_latest_moora`
-- (See below for the actual view)
--
CREATE TABLE `v_latest_moora` (
`id` int(10) unsigned
,`id_usulan` int(10) unsigned
,`id_detail_usulan` int(10) unsigned
,`nilai_yi` decimal(16,8)
,`ranking` int(11)
,`tanggal_hitung` datetime
,`versi_hitung` bigint(20)
,`mode_hitung` varchar(30)
,`jenis_keputusan` varchar(30)
,`nilai_benefit` decimal(16,8)
,`nilai_cost` decimal(16,8)
,`rincian_json` longtext
,`catatan_hitung` varchar(255)
,`checksum_hash` varchar(128)
,`created_at` datetime
,`updated_at` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_latest_moora_context`
-- (See below for the actual view)
--
CREATE TABLE `v_latest_moora_context` (
`id` int(10) unsigned
,`id_usulan` int(10) unsigned
,`id_detail_usulan` int(10) unsigned
,`nilai_yi` decimal(16,8)
,`ranking` int(11)
,`tanggal_hitung` datetime
,`versi_hitung` bigint(20)
,`mode_hitung` varchar(30)
,`jenis_keputusan` varchar(30)
,`nilai_benefit` decimal(16,8)
,`nilai_cost` decimal(16,8)
,`rincian_json` longtext
,`catatan_hitung` varchar(255)
,`checksum_hash` varchar(128)
,`created_at` datetime
,`updated_at` datetime
,`nomor_usulan` varchar(50)
,`tanggal_usulan` date
,`unit_pengusul` varchar(100)
,`id_user_pengusul` int(10) unsigned
,`status` enum('draft','diajukan','verifikasi_gudang','banding_gudang','direvisi','diverifikasi','menunggu_moora','moora_diproses','moora_selesai','direkomendasikan','dikembalikan','menunggu_direktur_bidang','disetujui_direktur_bidang','menunggu_direktur_utama','disetujui_direktur_utama','menunggu_direktur_umum','disposisi_pengadaan','diproses_pengadaan','selesai_pengadaan','menunggu_penerimaan','direalisasi','selesai','disetujui','ditolak')
,`status_validasi` varchar(50)
,`approval_stage` enum('none','direktur_bidang','direktur_utama','direktur_umum','selesai')
,`jenis_usulan` enum('RKA','Pesan Cepat')
,`file_rka_path` varchar(255)
,`file_rka_excel_path` varchar(255)
,`file_rka_dokumen_path` varchar(255)
,`usulan_updated_at` datetime
,`nama_pengusul` varchar(100)
,`id_alternatif` int(10) unsigned
,`jumlah` int(11)
,`estimasi_harga_satuan` decimal(15,2)
,`total_estimasi` decimal(15,2)
,`alasan_kebutuhan` text
,`kode_alternatif` varchar(20)
,`nama_alternatif` varchar(150)
,`kategori_barang` varchar(100)
,`jenis_barang` enum('alat','material','aset')
,`satuan` varchar(30)
,`spesifikasi` text
,`is_global_rankable` int(1)
,`is_workflow_locked` int(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_moora_global`
-- (See below for the actual view)
--
CREATE TABLE `v_moora_global` (
`id` int(10) unsigned
,`id_usulan` int(10) unsigned
,`id_detail_usulan` int(10) unsigned
,`nilai_yi` decimal(16,8)
,`ranking` int(11)
,`tanggal_hitung` datetime
,`versi_hitung` bigint(20)
,`mode_hitung` varchar(30)
,`jenis_keputusan` varchar(30)
,`nilai_benefit` decimal(16,8)
,`nilai_cost` decimal(16,8)
,`rincian_json` longtext
,`catatan_hitung` varchar(255)
,`checksum_hash` varchar(128)
,`created_at` datetime
,`updated_at` datetime
,`nilai_y` double
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_moora_global_final`
-- (See below for the actual view)
--
CREATE TABLE `v_moora_global_final` (
`id` int(10) unsigned
,`id_usulan` int(10) unsigned
,`id_detail_usulan` int(10) unsigned
,`nilai_yi` decimal(16,8)
,`ranking` int(11)
,`tanggal_hitung` datetime
,`versi_hitung` bigint(20)
,`mode_hitung` varchar(30)
,`jenis_keputusan` varchar(30)
,`nilai_benefit` decimal(16,8)
,`nilai_cost` decimal(16,8)
,`rincian_json` longtext
,`catatan_hitung` varchar(255)
,`checksum_hash` varchar(128)
,`created_at` datetime
,`updated_at` datetime
,`nomor_usulan` varchar(50)
,`tanggal_usulan` date
,`unit_pengusul` varchar(100)
,`id_user_pengusul` int(10) unsigned
,`status` enum('draft','diajukan','verifikasi_gudang','banding_gudang','direvisi','diverifikasi','menunggu_moora','moora_diproses','moora_selesai','direkomendasikan','dikembalikan','menunggu_direktur_bidang','disetujui_direktur_bidang','menunggu_direktur_utama','disetujui_direktur_utama','menunggu_direktur_umum','disposisi_pengadaan','diproses_pengadaan','selesai_pengadaan','menunggu_penerimaan','direalisasi','selesai','disetujui','ditolak')
,`status_validasi` varchar(50)
,`approval_stage` enum('none','direktur_bidang','direktur_utama','direktur_umum','selesai')
,`jenis_usulan` enum('RKA','Pesan Cepat')
,`file_rka_path` varchar(255)
,`file_rka_excel_path` varchar(255)
,`file_rka_dokumen_path` varchar(255)
,`usulan_updated_at` datetime
,`nama_pengusul` varchar(100)
,`id_alternatif` int(10) unsigned
,`jumlah` int(11)
,`estimasi_harga_satuan` decimal(15,2)
,`total_estimasi` decimal(15,2)
,`alasan_kebutuhan` text
,`kode_alternatif` varchar(20)
,`nama_alternatif` varchar(150)
,`kategori_barang` varchar(100)
,`jenis_barang` enum('alat','material','aset')
,`satuan` varchar(30)
,`spesifikasi` text
,`is_global_rankable` int(1)
,`is_workflow_locked` int(1)
,`global_ranking` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_moora_workflow_locked`
-- (See below for the actual view)
--
CREATE TABLE `v_moora_workflow_locked` (
`id` int(10) unsigned
,`id_usulan` int(10) unsigned
,`id_detail_usulan` int(10) unsigned
,`nilai_yi` decimal(16,8)
,`ranking` int(11)
,`tanggal_hitung` datetime
,`versi_hitung` bigint(20)
,`mode_hitung` varchar(30)
,`jenis_keputusan` varchar(30)
,`nilai_benefit` decimal(16,8)
,`nilai_cost` decimal(16,8)
,`rincian_json` longtext
,`catatan_hitung` varchar(255)
,`checksum_hash` varchar(128)
,`created_at` datetime
,`updated_at` datetime
,`nomor_usulan` varchar(50)
,`tanggal_usulan` date
,`unit_pengusul` varchar(100)
,`id_user_pengusul` int(10) unsigned
,`status` enum('draft','diajukan','verifikasi_gudang','banding_gudang','direvisi','diverifikasi','menunggu_moora','moora_diproses','moora_selesai','direkomendasikan','dikembalikan','menunggu_direktur_bidang','disetujui_direktur_bidang','menunggu_direktur_utama','disetujui_direktur_utama','menunggu_direktur_umum','disposisi_pengadaan','diproses_pengadaan','selesai_pengadaan','menunggu_penerimaan','direalisasi','selesai','disetujui','ditolak')
,`status_validasi` varchar(50)
,`approval_stage` enum('none','direktur_bidang','direktur_utama','direktur_umum','selesai')
,`jenis_usulan` enum('RKA','Pesan Cepat')
,`file_rka_path` varchar(255)
,`file_rka_excel_path` varchar(255)
,`file_rka_dokumen_path` varchar(255)
,`usulan_updated_at` datetime
,`nama_pengusul` varchar(100)
,`id_alternatif` int(10) unsigned
,`jumlah` int(11)
,`estimasi_harga_satuan` decimal(15,2)
,`total_estimasi` decimal(15,2)
,`alasan_kebutuhan` text
,`kode_alternatif` varchar(20)
,`nama_alternatif` varchar(150)
,`kategori_barang` varchar(100)
,`jenis_barang` enum('alat','material','aset')
,`satuan` varchar(30)
,`spesifikasi` text
,`is_global_rankable` int(1)
,`is_workflow_locked` int(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_pengadaan_document_checklist`
-- (See below for the actual view)
--
CREATE TABLE `v_pengadaan_document_checklist` (
`id_pengadaan` int(10) unsigned
,`id_usulan` int(10) unsigned
,`nomor_pengadaan` varchar(80)
,`status_pengadaan` enum('menunggu','diproses','po_terbit','barang_datang','diserahkan_gudang','selesai','dibatalkan')
,`jumlah_dokumen` bigint(21)
,`dok_po` decimal(22,0)
,`dok_invoice` decimal(22,0)
,`dok_bast` decimal(22,0)
,`dok_surat_jalan` decimal(22,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_penilaian_join`
-- (See below for the actual view)
--
CREATE TABLE `v_penilaian_join` (
`id` int(10) unsigned
,`id_detail_usulan` int(10) unsigned
,`id_usulan` int(10) unsigned
,`id_kriteria` int(10) unsigned
,`nilai` decimal(10,4)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_stok_barang_single_source`
-- (See below for the actual view)
--
CREATE TABLE `v_stok_barang_single_source` (
`id_alternatif` int(10) unsigned
,`kode_alternatif` varchar(20)
,`nama_alternatif` varchar(150)
,`kategori_barang` varchar(100)
,`jenis_barang` enum('alat','material','aset')
,`satuan` varchar(30)
,`kondisi_barang` enum('baik','rusak','diperbaiki','tidak_layak')
,`movement_type` enum('first_moving','slow_moving','non_moving')
,`estimasi_harga` decimal(15,2)
,`stok_alternatif` int(11)
,`stok_minimum_alternatif` int(11)
,`stok_barang_table` int(11)
,`stok_minimum_table` int(11)
,`stok_final` int(11)
,`stok_minimum_final` int(11)
,`lokasi_gudang` varchar(100)
,`sumber_updated_at` varchar(19)
);

-- --------------------------------------------------------

--
-- Structure for view `v_auto_fix_moora_health`
--
DROP TABLE IF EXISTS `v_auto_fix_moora_health`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_auto_fix_moora_health`  AS SELECT `up`.`id` AS `id_usulan`, `up`.`nomor_usulan` AS `nomor_usulan`, `up`.`jenis_usulan` AS `jenis_usulan`, `up`.`status` AS `status`, count(distinct `du`.`id`) AS `jumlah_detail`, count(distinct `p`.`id`) AS `jumlah_penilaian`, count(distinct `hm`.`id`) AS `jumlah_hasil`, CASE WHEN count(distinct `du`.`id`) = 0 THEN 'BROKEN_DETAIL' WHEN count(distinct `p`.`id`) = 0 THEN 'BROKEN_PENILAIAN' WHEN count(distinct `hm`.`id`) = 0 THEN 'BELUM_DIHITUNG' ELSE 'OK' END AS `health_status` FROM (((`usulan_pengadaan` `up` left join `detail_usulan` `du` on(`du`.`id_usulan` = `up`.`id`)) left join `penilaian` `p` on(`p`.`id_detail_usulan` = `du`.`id`)) left join `hasil_moora` `hm` on(`hm`.`id_usulan` = `up`.`id`)) GROUP BY `up`.`id`, `up`.`nomor_usulan`, `up`.`jenis_usulan`, `up`.`status` ;

-- --------------------------------------------------------

--
-- Structure for view `v_latest_moora`
--
DROP TABLE IF EXISTS `v_latest_moora`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_latest_moora`  AS SELECT `hm`.`id` AS `id`, `hm`.`id_usulan` AS `id_usulan`, `hm`.`id_detail_usulan` AS `id_detail_usulan`, `hm`.`nilai_yi` AS `nilai_yi`, `hm`.`ranking` AS `ranking`, `hm`.`tanggal_hitung` AS `tanggal_hitung`, `hm`.`versi_hitung` AS `versi_hitung`, `hm`.`mode_hitung` AS `mode_hitung`, `hm`.`jenis_keputusan` AS `jenis_keputusan`, `hm`.`nilai_benefit` AS `nilai_benefit`, `hm`.`nilai_cost` AS `nilai_cost`, `hm`.`rincian_json` AS `rincian_json`, `hm`.`catatan_hitung` AS `catatan_hitung`, `hm`.`checksum_hash` AS `checksum_hash`, `hm`.`created_at` AS `created_at`, `hm`.`updated_at` AS `updated_at` FROM (`hasil_moora` `hm` join (select `hasil_moora`.`id_usulan` AS `id_usulan`,`hasil_moora`.`mode_hitung` AS `mode_hitung`,max(`hasil_moora`.`versi_hitung`) AS `max_versi` from `hasil_moora` where `hasil_moora`.`mode_hitung` = 'item_based' group by `hasil_moora`.`id_usulan`,`hasil_moora`.`mode_hitung`) `latest` on(`latest`.`id_usulan` = `hm`.`id_usulan` and `latest`.`mode_hitung` = `hm`.`mode_hitung` and `latest`.`max_versi` = `hm`.`versi_hitung`)) WHERE `hm`.`mode_hitung` = 'item_based'union all select `hm`.`id` AS `id`,`hm`.`id_usulan` AS `id_usulan`,`hm`.`id_detail_usulan` AS `id_detail_usulan`,`hm`.`nilai_yi` AS `nilai_yi`,`hm`.`ranking` AS `ranking`,`hm`.`tanggal_hitung` AS `tanggal_hitung`,`hm`.`versi_hitung` AS `versi_hitung`,`hm`.`mode_hitung` AS `mode_hitung`,`hm`.`jenis_keputusan` AS `jenis_keputusan`,`hm`.`nilai_benefit` AS `nilai_benefit`,`hm`.`nilai_cost` AS `nilai_cost`,`hm`.`rincian_json` AS `rincian_json`,`hm`.`catatan_hitung` AS `catatan_hitung`,`hm`.`checksum_hash` AS `checksum_hash`,`hm`.`created_at` AS `created_at`,`hm`.`updated_at` AS `updated_at` from (`hasil_moora` `hm` join (select `hasil_moora`.`id_usulan` AS `id_usulan`,`hasil_moora`.`mode_hitung` AS `mode_hitung`,max(`hasil_moora`.`versi_hitung`) AS `max_versi` from `hasil_moora` where `hasil_moora`.`mode_hitung` = 'rka_aggregate' group by `hasil_moora`.`id_usulan`,`hasil_moora`.`mode_hitung`) `latest` on(`latest`.`id_usulan` = `hm`.`id_usulan` and `latest`.`mode_hitung` = `hm`.`mode_hitung` and `latest`.`max_versi` = `hm`.`versi_hitung`)) where `hm`.`mode_hitung` = 'rka_aggregate' and !exists(select 1 from `hasil_moora` `better` where `better`.`id_usulan` = `hm`.`id_usulan` and `better`.`mode_hitung` = `hm`.`mode_hitung` and `better`.`versi_hitung` = `hm`.`versi_hitung` and (`better`.`ranking` < `hm`.`ranking` or `better`.`ranking` = `hm`.`ranking` and `better`.`nilai_yi` > `hm`.`nilai_yi` or `better`.`ranking` = `hm`.`ranking` and `better`.`nilai_yi` = `hm`.`nilai_yi` and `better`.`id` < `hm`.`id`) limit 1)  ;

-- --------------------------------------------------------

--
-- Structure for view `v_latest_moora_context`
--
DROP TABLE IF EXISTS `v_latest_moora_context`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_latest_moora_context`  AS SELECT `lm`.`id` AS `id`, `lm`.`id_usulan` AS `id_usulan`, `lm`.`id_detail_usulan` AS `id_detail_usulan`, `lm`.`nilai_yi` AS `nilai_yi`, `lm`.`ranking` AS `ranking`, `lm`.`tanggal_hitung` AS `tanggal_hitung`, `lm`.`versi_hitung` AS `versi_hitung`, `lm`.`mode_hitung` AS `mode_hitung`, `lm`.`jenis_keputusan` AS `jenis_keputusan`, `lm`.`nilai_benefit` AS `nilai_benefit`, `lm`.`nilai_cost` AS `nilai_cost`, `lm`.`rincian_json` AS `rincian_json`, `lm`.`catatan_hitung` AS `catatan_hitung`, `lm`.`checksum_hash` AS `checksum_hash`, `lm`.`created_at` AS `created_at`, `lm`.`updated_at` AS `updated_at`, `up`.`nomor_usulan` AS `nomor_usulan`, `up`.`tanggal_usulan` AS `tanggal_usulan`, `up`.`unit_pengusul` AS `unit_pengusul`, `up`.`id_user_pengusul` AS `id_user_pengusul`, `up`.`status` AS `status`, `up`.`status_validasi` AS `status_validasi`, `up`.`approval_stage` AS `approval_stage`, `up`.`jenis_usulan` AS `jenis_usulan`, `up`.`file_rka_path` AS `file_rka_path`, `up`.`file_rka_excel_path` AS `file_rka_excel_path`, `up`.`file_rka_dokumen_path` AS `file_rka_dokumen_path`, `up`.`updated_at` AS `usulan_updated_at`, `u`.`nama_lengkap` AS `nama_pengusul`, `du`.`id_alternatif` AS `id_alternatif`, `du`.`jumlah` AS `jumlah`, `du`.`estimasi_harga_satuan` AS `estimasi_harga_satuan`, `du`.`total_estimasi` AS `total_estimasi`, `du`.`alasan_kebutuhan` AS `alasan_kebutuhan`, `a`.`kode_alternatif` AS `kode_alternatif`, CASE WHEN `lm`.`mode_hitung` = 'rka_aggregate' THEN concat('Agregasi Dokumen RKA - ',coalesce(`up`.`unit_pengusul`,'-')) ELSE coalesce(`a`.`nama_alternatif`,'-') END AS `nama_alternatif`, `a`.`kategori_barang` AS `kategori_barang`, `a`.`jenis_barang` AS `jenis_barang`, `a`.`satuan` AS `satuan`, `a`.`spesifikasi` AS `spesifikasi`, CASE WHEN `up`.`status` = 'moora_selesai' THEN 1 ELSE 0 END AS `is_global_rankable`, CASE WHEN `up`.`status` in ('moora_selesai','menunggu_direktur_bidang','menunggu_direktur_utama','menunggu_direktur_umum','disposisi_pengadaan','diproses_pengadaan','menunggu_penerimaan','selesai') THEN 1 ELSE 0 END AS `is_workflow_locked` FROM ((((`v_latest_moora` `lm` join `usulan_pengadaan` `up` on(`up`.`id` = `lm`.`id_usulan`)) left join `users` `u` on(`u`.`id` = `up`.`id_user_pengusul`)) left join `detail_usulan` `du` on(`du`.`id` = `lm`.`id_detail_usulan`)) left join `alternatif` `a` on(`a`.`id` = `du`.`id_alternatif`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_moora_global`
--
DROP TABLE IF EXISTS `v_moora_global`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_moora_global`  AS SELECT `hasil_moora`.`id` AS `id`, `hasil_moora`.`id_usulan` AS `id_usulan`, `hasil_moora`.`id_detail_usulan` AS `id_detail_usulan`, `hasil_moora`.`nilai_yi` AS `nilai_yi`, `hasil_moora`.`ranking` AS `ranking`, `hasil_moora`.`tanggal_hitung` AS `tanggal_hitung`, `hasil_moora`.`versi_hitung` AS `versi_hitung`, `hasil_moora`.`mode_hitung` AS `mode_hitung`, `hasil_moora`.`jenis_keputusan` AS `jenis_keputusan`, `hasil_moora`.`nilai_benefit` AS `nilai_benefit`, `hasil_moora`.`nilai_cost` AS `nilai_cost`, `hasil_moora`.`rincian_json` AS `rincian_json`, `hasil_moora`.`catatan_hitung` AS `catatan_hitung`, `hasil_moora`.`checksum_hash` AS `checksum_hash`, `hasil_moora`.`created_at` AS `created_at`, `hasil_moora`.`updated_at` AS `updated_at`, `hasil_moora`.`nilai_y` AS `nilai_y` FROM `hasil_moora` ORDER BY coalesce(`hasil_moora`.`nilai_y`,0) DESC ;

-- --------------------------------------------------------

--
-- Structure for view `v_moora_global_final`
--
DROP TABLE IF EXISTS `v_moora_global_final`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_moora_global_final`  AS SELECT `ranked`.`id` AS `id`, `ranked`.`id_usulan` AS `id_usulan`, `ranked`.`id_detail_usulan` AS `id_detail_usulan`, `ranked`.`nilai_yi` AS `nilai_yi`, `ranked`.`ranking` AS `ranking`, `ranked`.`tanggal_hitung` AS `tanggal_hitung`, `ranked`.`versi_hitung` AS `versi_hitung`, `ranked`.`mode_hitung` AS `mode_hitung`, `ranked`.`jenis_keputusan` AS `jenis_keputusan`, `ranked`.`nilai_benefit` AS `nilai_benefit`, `ranked`.`nilai_cost` AS `nilai_cost`, `ranked`.`rincian_json` AS `rincian_json`, `ranked`.`catatan_hitung` AS `catatan_hitung`, `ranked`.`checksum_hash` AS `checksum_hash`, `ranked`.`created_at` AS `created_at`, `ranked`.`updated_at` AS `updated_at`, `ranked`.`nomor_usulan` AS `nomor_usulan`, `ranked`.`tanggal_usulan` AS `tanggal_usulan`, `ranked`.`unit_pengusul` AS `unit_pengusul`, `ranked`.`id_user_pengusul` AS `id_user_pengusul`, `ranked`.`status` AS `status`, `ranked`.`status_validasi` AS `status_validasi`, `ranked`.`approval_stage` AS `approval_stage`, `ranked`.`jenis_usulan` AS `jenis_usulan`, `ranked`.`file_rka_path` AS `file_rka_path`, `ranked`.`file_rka_excel_path` AS `file_rka_excel_path`, `ranked`.`file_rka_dokumen_path` AS `file_rka_dokumen_path`, `ranked`.`usulan_updated_at` AS `usulan_updated_at`, `ranked`.`nama_pengusul` AS `nama_pengusul`, `ranked`.`id_alternatif` AS `id_alternatif`, `ranked`.`jumlah` AS `jumlah`, `ranked`.`estimasi_harga_satuan` AS `estimasi_harga_satuan`, `ranked`.`total_estimasi` AS `total_estimasi`, `ranked`.`alasan_kebutuhan` AS `alasan_kebutuhan`, `ranked`.`kode_alternatif` AS `kode_alternatif`, `ranked`.`nama_alternatif` AS `nama_alternatif`, `ranked`.`kategori_barang` AS `kategori_barang`, `ranked`.`jenis_barang` AS `jenis_barang`, `ranked`.`satuan` AS `satuan`, `ranked`.`spesifikasi` AS `spesifikasi`, `ranked`.`is_global_rankable` AS `is_global_rankable`, `ranked`.`is_workflow_locked` AS `is_workflow_locked`, `ranked`.`global_ranking` AS `global_ranking` FROM (select `ctx`.`id` AS `id`,`ctx`.`id_usulan` AS `id_usulan`,`ctx`.`id_detail_usulan` AS `id_detail_usulan`,`ctx`.`nilai_yi` AS `nilai_yi`,`ctx`.`ranking` AS `ranking`,`ctx`.`tanggal_hitung` AS `tanggal_hitung`,`ctx`.`versi_hitung` AS `versi_hitung`,`ctx`.`mode_hitung` AS `mode_hitung`,`ctx`.`jenis_keputusan` AS `jenis_keputusan`,`ctx`.`nilai_benefit` AS `nilai_benefit`,`ctx`.`nilai_cost` AS `nilai_cost`,`ctx`.`rincian_json` AS `rincian_json`,`ctx`.`catatan_hitung` AS `catatan_hitung`,`ctx`.`checksum_hash` AS `checksum_hash`,`ctx`.`created_at` AS `created_at`,`ctx`.`updated_at` AS `updated_at`,`ctx`.`nomor_usulan` AS `nomor_usulan`,`ctx`.`tanggal_usulan` AS `tanggal_usulan`,`ctx`.`unit_pengusul` AS `unit_pengusul`,`ctx`.`id_user_pengusul` AS `id_user_pengusul`,`ctx`.`status` AS `status`,`ctx`.`status_validasi` AS `status_validasi`,`ctx`.`approval_stage` AS `approval_stage`,`ctx`.`jenis_usulan` AS `jenis_usulan`,`ctx`.`file_rka_path` AS `file_rka_path`,`ctx`.`file_rka_excel_path` AS `file_rka_excel_path`,`ctx`.`file_rka_dokumen_path` AS `file_rka_dokumen_path`,`ctx`.`usulan_updated_at` AS `usulan_updated_at`,`ctx`.`nama_pengusul` AS `nama_pengusul`,`ctx`.`id_alternatif` AS `id_alternatif`,`ctx`.`jumlah` AS `jumlah`,`ctx`.`estimasi_harga_satuan` AS `estimasi_harga_satuan`,`ctx`.`total_estimasi` AS `total_estimasi`,`ctx`.`alasan_kebutuhan` AS `alasan_kebutuhan`,`ctx`.`kode_alternatif` AS `kode_alternatif`,`ctx`.`nama_alternatif` AS `nama_alternatif`,`ctx`.`kategori_barang` AS `kategori_barang`,`ctx`.`jenis_barang` AS `jenis_barang`,`ctx`.`satuan` AS `satuan`,`ctx`.`spesifikasi` AS `spesifikasi`,`ctx`.`is_global_rankable` AS `is_global_rankable`,`ctx`.`is_workflow_locked` AS `is_workflow_locked`,row_number() over ( partition by `ctx`.`mode_hitung` order by `ctx`.`nilai_yi` desc,`ctx`.`tanggal_hitung`,`ctx`.`id`) AS `global_ranking` from `v_latest_moora_context` `ctx` where `ctx`.`status` = 'moora_selesai') AS `ranked` ;

-- --------------------------------------------------------

--
-- Structure for view `v_moora_workflow_locked`
--
DROP TABLE IF EXISTS `v_moora_workflow_locked`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_moora_workflow_locked`  AS SELECT `v_latest_moora_context`.`id` AS `id`, `v_latest_moora_context`.`id_usulan` AS `id_usulan`, `v_latest_moora_context`.`id_detail_usulan` AS `id_detail_usulan`, `v_latest_moora_context`.`nilai_yi` AS `nilai_yi`, `v_latest_moora_context`.`ranking` AS `ranking`, `v_latest_moora_context`.`tanggal_hitung` AS `tanggal_hitung`, `v_latest_moora_context`.`versi_hitung` AS `versi_hitung`, `v_latest_moora_context`.`mode_hitung` AS `mode_hitung`, `v_latest_moora_context`.`jenis_keputusan` AS `jenis_keputusan`, `v_latest_moora_context`.`nilai_benefit` AS `nilai_benefit`, `v_latest_moora_context`.`nilai_cost` AS `nilai_cost`, `v_latest_moora_context`.`rincian_json` AS `rincian_json`, `v_latest_moora_context`.`catatan_hitung` AS `catatan_hitung`, `v_latest_moora_context`.`checksum_hash` AS `checksum_hash`, `v_latest_moora_context`.`created_at` AS `created_at`, `v_latest_moora_context`.`updated_at` AS `updated_at`, `v_latest_moora_context`.`nomor_usulan` AS `nomor_usulan`, `v_latest_moora_context`.`tanggal_usulan` AS `tanggal_usulan`, `v_latest_moora_context`.`unit_pengusul` AS `unit_pengusul`, `v_latest_moora_context`.`id_user_pengusul` AS `id_user_pengusul`, `v_latest_moora_context`.`status` AS `status`, `v_latest_moora_context`.`status_validasi` AS `status_validasi`, `v_latest_moora_context`.`approval_stage` AS `approval_stage`, `v_latest_moora_context`.`jenis_usulan` AS `jenis_usulan`, `v_latest_moora_context`.`file_rka_path` AS `file_rka_path`, `v_latest_moora_context`.`file_rka_excel_path` AS `file_rka_excel_path`, `v_latest_moora_context`.`file_rka_dokumen_path` AS `file_rka_dokumen_path`, `v_latest_moora_context`.`usulan_updated_at` AS `usulan_updated_at`, `v_latest_moora_context`.`nama_pengusul` AS `nama_pengusul`, `v_latest_moora_context`.`id_alternatif` AS `id_alternatif`, `v_latest_moora_context`.`jumlah` AS `jumlah`, `v_latest_moora_context`.`estimasi_harga_satuan` AS `estimasi_harga_satuan`, `v_latest_moora_context`.`total_estimasi` AS `total_estimasi`, `v_latest_moora_context`.`alasan_kebutuhan` AS `alasan_kebutuhan`, `v_latest_moora_context`.`kode_alternatif` AS `kode_alternatif`, `v_latest_moora_context`.`nama_alternatif` AS `nama_alternatif`, `v_latest_moora_context`.`kategori_barang` AS `kategori_barang`, `v_latest_moora_context`.`jenis_barang` AS `jenis_barang`, `v_latest_moora_context`.`satuan` AS `satuan`, `v_latest_moora_context`.`spesifikasi` AS `spesifikasi`, `v_latest_moora_context`.`is_global_rankable` AS `is_global_rankable`, `v_latest_moora_context`.`is_workflow_locked` AS `is_workflow_locked` FROM `v_latest_moora_context` WHERE `v_latest_moora_context`.`status` in ('moora_selesai','menunggu_direktur_bidang','menunggu_direktur_utama','menunggu_direktur_umum','disposisi_pengadaan','diproses_pengadaan','menunggu_penerimaan','selesai') ;

-- --------------------------------------------------------

--
-- Structure for view `v_pengadaan_document_checklist`
--
DROP TABLE IF EXISTS `v_pengadaan_document_checklist`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_pengadaan_document_checklist`  AS SELECT `pp`.`id` AS `id_pengadaan`, `pp`.`id_usulan` AS `id_usulan`, `pp`.`nomor_pengadaan` AS `nomor_pengadaan`, `pp`.`status_pengadaan` AS `status_pengadaan`, count(`pd`.`id`) AS `jumlah_dokumen`, sum(case when `pd`.`jenis_dokumen` = 'po' then 1 else 0 end) AS `dok_po`, sum(case when `pd`.`jenis_dokumen` = 'invoice' then 1 else 0 end) AS `dok_invoice`, sum(case when `pd`.`jenis_dokumen` = 'bast' then 1 else 0 end) AS `dok_bast`, sum(case when `pd`.`jenis_dokumen` = 'surat_jalan' then 1 else 0 end) AS `dok_surat_jalan` FROM (`pengadaan_pembelian` `pp` left join `pengadaan_dokumen` `pd` on(`pd`.`id_pengadaan` = `pp`.`id`)) GROUP BY `pp`.`id`, `pp`.`id_usulan`, `pp`.`nomor_pengadaan`, `pp`.`status_pengadaan` ;

-- --------------------------------------------------------

--
-- Structure for view `v_penilaian_join`
--
DROP TABLE IF EXISTS `v_penilaian_join`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_penilaian_join`  AS SELECT `p`.`id` AS `id`, `p`.`id_detail_usulan` AS `id_detail_usulan`, `du`.`id_usulan` AS `id_usulan`, `p`.`id_kriteria` AS `id_kriteria`, `p`.`nilai` AS `nilai` FROM (`penilaian` `p` join `detail_usulan` `du` on(`du`.`id` = `p`.`id_detail_usulan`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_stok_barang_single_source`
--
DROP TABLE IF EXISTS `v_stok_barang_single_source`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stok_barang_single_source`  AS SELECT `a`.`id` AS `id_alternatif`, `a`.`kode_alternatif` AS `kode_alternatif`, `a`.`nama_alternatif` AS `nama_alternatif`, `a`.`kategori_barang` AS `kategori_barang`, `a`.`jenis_barang` AS `jenis_barang`, `a`.`satuan` AS `satuan`, `a`.`kondisi_barang` AS `kondisi_barang`, `a`.`movement_type` AS `movement_type`, `a`.`estimasi_harga` AS `estimasi_harga`, `a`.`stok` AS `stok_alternatif`, `a`.`stok_minimum` AS `stok_minimum_alternatif`, `sb`.`stok_saat_ini` AS `stok_barang_table`, `sb`.`stok_minimum` AS `stok_minimum_table`, CASE WHEN `sb`.`id` is not null AND coalesce(`sb`.`updated_at`,'1970-01-01 00:00:00') > coalesce(`a`.`updated_at`,'1970-01-01 00:00:00') THEN `sb`.`stok_saat_ini` ELSE `a`.`stok` END AS `stok_final`, CASE WHEN `sb`.`id` is not null AND coalesce(`sb`.`updated_at`,'1970-01-01 00:00:00') > coalesce(`a`.`updated_at`,'1970-01-01 00:00:00') THEN `sb`.`stok_minimum` ELSE `a`.`stok_minimum` END AS `stok_minimum_final`, `sb`.`lokasi_gudang` AS `lokasi_gudang`, greatest(coalesce(`a`.`updated_at`,'1970-01-01 00:00:00'),coalesce(`sb`.`updated_at`,'1970-01-01 00:00:00')) AS `sumber_updated_at` FROM (`alternatif` `a` left join `stok_barang` `sb` on(`sb`.`id_alternatif` = `a`.`id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_user_approval_log`
--
ALTER TABLE `admin_user_approval_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_registration` (`registration_id`),
  ADD KEY `idx_admin` (`admin_id`),
  ADD KEY `idx_action` (`action`);

--
-- Indexes for table `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_alternatif` (`kode_alternatif`);

--
-- Indexes for table `approval_direktur`
--
ALTER TABLE `approval_direktur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_approval_usulan_tahap` (`id_usulan`,`tahap_approval`),
  ADD KEY `idx_approval_status` (`aksi`),
  ADD KEY `idx_approval_user` (`approved_by`);

--
-- Indexes for table `detail_realisasi_pengadaan`
--
ALTER TABLE `detail_realisasi_pengadaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_detail_realisasi` (`id_realisasi`),
  ADD KEY `idx_detail_realisasi_alt` (`id_alternatif`);

--
-- Indexes for table `detail_usulan`
--
ALTER TABLE `detail_usulan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_detail_usulan` (`id_usulan`),
  ADD KEY `idx_detail_alternatif` (`id_alternatif`);

--
-- Indexes for table `distribusi_barang`
--
ALTER TABLE `distribusi_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dokumen_disposisi`
--
ALTER TABLE `dokumen_disposisi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_dokumen_disposisi_nomor` (`nomor_dokumen`),
  ADD KEY `idx_dokumen_disposisi_usulan` (`id_usulan`),
  ADD KEY `idx_dokumen_disposisi_status` (`status_dokumen`),
  ADD KEY `fk_dokumen_disposisi_created_by` (`created_by`),
  ADD KEY `fk_dokumen_disposisi_approved_by` (`approved_by`);

--
-- Indexes for table `hasil_moora`
--
ALTER TABLE `hasil_moora`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_hasil_versi` (`id_detail_usulan`,`versi_hitung`),
  ADD KEY `idx_hasil_usulan` (`id_usulan`),
  ADD KEY `idx_hasil_detail` (`id_detail_usulan`),
  ADD KEY `idx_hasil_ranking` (`ranking`),
  ADD KEY `idx_hasil_mode_hitung` (`mode_hitung`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_kriteria` (`kode_kriteria`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_log_user` (`id_user`),
  ADD KEY `idx_log_modul` (`modul`),
  ADD KEY `idx_log_created` (`created_at`);

--
-- Indexes for table `moora_engine_log`
--
ALTER TABLE `moora_engine_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_moora_engine_usulan` (`id_usulan`),
  ADD KEY `idx_moora_engine_versi` (`versi_hitung`),
  ADD KEY `idx_moora_engine_mode` (`mode_hitung`),
  ADD KEY `fk_moora_engine_user` (`processed_by`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notif_user` (`id_user_penerima`),
  ADD KEY `idx_notif_role` (`role_penerima`),
  ADD KEY `idx_notif_usulan` (`id_usulan`),
  ADD KEY `idx_notif_read` (`is_read`),
  ADD KEY `idx_notif_created` (`created_at`),
  ADD KEY `fk_notif_created_by` (`created_by`);

--
-- Indexes for table `penerimaan_barang`
--
ALTER TABLE `penerimaan_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengadaan_dokumen`
--
ALTER TABLE `pengadaan_dokumen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pengadaan_dokumen_pengadaan` (`id_pengadaan`),
  ADD KEY `idx_pengadaan_dokumen_usulan` (`id_usulan`),
  ADD KEY `fk_pengadaan_dokumen_user` (`uploaded_by`);

--
-- Indexes for table `pengadaan_pembelian`
--
ALTER TABLE `pengadaan_pembelian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_pengadaan_nomor` (`nomor_pengadaan`),
  ADD KEY `idx_pengadaan_usulan` (`id_usulan`),
  ADD KEY `idx_pengadaan_status` (`status_pengadaan`),
  ADD KEY `fk_pengadaan_created_by` (`created_by`);

--
-- Indexes for table `pengadaan_serah_barang`
--
ALTER TABLE `pengadaan_serah_barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_serah_pengadaan` (`id_pengadaan`),
  ADD KEY `idx_serah_usulan` (`id_usulan`),
  ADD KEY `idx_serah_detail` (`id_detail_usulan`),
  ADD KEY `idx_serah_alternatif` (`id_alternatif`),
  ADD KEY `idx_serah_status` (`status_serah`),
  ADD KEY `fk_serah_created_by` (`created_by`),
  ADD KEY `fk_serah_received_by` (`received_by`);

--
-- Indexes for table `pengambilan_barang`
--
ALTER TABLE `pengambilan_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_penilaian` (`id_detail_usulan`,`id_kriteria`),
  ADD KEY `idx_penilaian_detail` (`id_detail_usulan`),
  ADD KEY `idx_penilaian_kriteria` (`id_kriteria`);

--
-- Indexes for table `perbaikan_alat`
--
ALTER TABLE `perbaikan_alat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qr_disposisi`
--
ALTER TABLE `qr_disposisi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_qr_hash` (`qr_hash`),
  ADD KEY `idx_qr_dokumen` (`id_dokumen`),
  ADD KEY `idx_qr_usulan` (`id_usulan`);

--
-- Indexes for table `realisasi_pengadaan`
--
ALTER TABLE `realisasi_pengadaan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_dokumen` (`nomor_dokumen`),
  ADD KEY `idx_realisasi_usulan` (`id_usulan`),
  ADD KEY `idx_realisasi_tanggal` (`tanggal_realisasi`);

--
-- Indexes for table `riwayat_validasi`
--
ALTER TABLE `riwayat_validasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_riwayat_usulan` (`id_usulan`),
  ADD KEY `idx_riwayat_user` (`id_user`);

--
-- Indexes for table `setting_sistem`
--
ALTER TABLE `setting_sistem`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_setting_key` (`setting_key`),
  ADD KEY `idx_setting_group` (`setting_group`);

--
-- Indexes for table `stok_barang`
--
ALTER TABLE `stok_barang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_stok_alternatif` (`id_alternatif`),
  ADD KEY `idx_stok_alternatif` (`id_alternatif`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_registration`
--
ALTER TABLE `user_registration`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_username` (`username`),
  ADD KEY `idx_reg_status` (`status`),
  ADD KEY `idx_reg_username` (`username`);

--
-- Indexes for table `user_registration_approval_log`
--
ALTER TABLE `user_registration_approval_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registration_id` (`registration_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `user_registration_history`
--
ALTER TABLE `user_registration_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registration_id` (`registration_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `usulan_pengadaan`
--
ALTER TABLE `usulan_pengadaan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_usulan` (`nomor_usulan`),
  ADD KEY `idx_usulan_user` (`id_user_pengusul`),
  ADD KEY `idx_usulan_tanggal` (`tanggal_usulan`),
  ADD KEY `idx_usulan_status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_user_approval_log`
--
ALTER TABLE `admin_user_approval_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `approval_direktur`
--
ALTER TABLE `approval_direktur`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `detail_realisasi_pengadaan`
--
ALTER TABLE `detail_realisasi_pengadaan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_usulan`
--
ALTER TABLE `detail_usulan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `distribusi_barang`
--
ALTER TABLE `distribusi_barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dokumen_disposisi`
--
ALTER TABLE `dokumen_disposisi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hasil_moora`
--
ALTER TABLE `hasil_moora`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=407;

--
-- AUTO_INCREMENT for table `moora_engine_log`
--
ALTER TABLE `moora_engine_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `penerimaan_barang`
--
ALTER TABLE `penerimaan_barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pengadaan_dokumen`
--
ALTER TABLE `pengadaan_dokumen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pengadaan_pembelian`
--
ALTER TABLE `pengadaan_pembelian`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengadaan_serah_barang`
--
ALTER TABLE `pengadaan_serah_barang`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengambilan_barang`
--
ALTER TABLE `pengambilan_barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `perbaikan_alat`
--
ALTER TABLE `perbaikan_alat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `qr_disposisi`
--
ALTER TABLE `qr_disposisi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `realisasi_pengadaan`
--
ALTER TABLE `realisasi_pengadaan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riwayat_validasi`
--
ALTER TABLE `riwayat_validasi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `setting_sistem`
--
ALTER TABLE `setting_sistem`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `stok_barang`
--
ALTER TABLE `stok_barang`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_registration`
--
ALTER TABLE `user_registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_registration_approval_log`
--
ALTER TABLE `user_registration_approval_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_registration_history`
--
ALTER TABLE `user_registration_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `usulan_pengadaan`
--
ALTER TABLE `usulan_pengadaan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approval_direktur`
--
ALTER TABLE `approval_direktur`
  ADD CONSTRAINT `fk_approval_user` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_approval_usulan` FOREIGN KEY (`id_usulan`) REFERENCES `usulan_pengadaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_realisasi_pengadaan`
--
ALTER TABLE `detail_realisasi_pengadaan`
  ADD CONSTRAINT `fk_detail_realisasi_alternatif` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_realisasi_realisasi` FOREIGN KEY (`id_realisasi`) REFERENCES `realisasi_pengadaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_usulan`
--
ALTER TABLE `detail_usulan`
  ADD CONSTRAINT `fk_detail_usulan_alternatif` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_usulan_usulan` FOREIGN KEY (`id_usulan`) REFERENCES `usulan_pengadaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dokumen_disposisi`
--
ALTER TABLE `dokumen_disposisi`
  ADD CONSTRAINT `fk_dokumen_disposisi_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dokumen_disposisi_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_dokumen_disposisi_usulan` FOREIGN KEY (`id_usulan`) REFERENCES `usulan_pengadaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hasil_moora`
--
ALTER TABLE `hasil_moora`
  ADD CONSTRAINT `fk_hasil_detail` FOREIGN KEY (`id_detail_usulan`) REFERENCES `detail_usulan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hasil_usulan` FOREIGN KEY (`id_usulan`) REFERENCES `usulan_pengadaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `moora_engine_log`
--
ALTER TABLE `moora_engine_log`
  ADD CONSTRAINT `fk_moora_engine_user` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_moora_engine_usulan` FOREIGN KEY (`id_usulan`) REFERENCES `usulan_pengadaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `fk_notif_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notif_user_penerima` FOREIGN KEY (`id_user_penerima`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notif_usulan` FOREIGN KEY (`id_usulan`) REFERENCES `usulan_pengadaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengadaan_dokumen`
--
ALTER TABLE `pengadaan_dokumen`
  ADD CONSTRAINT `fk_pengadaan_dokumen_pengadaan` FOREIGN KEY (`id_pengadaan`) REFERENCES `pengadaan_pembelian` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pengadaan_dokumen_user` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pengadaan_dokumen_usulan` FOREIGN KEY (`id_usulan`) REFERENCES `usulan_pengadaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengadaan_pembelian`
--
ALTER TABLE `pengadaan_pembelian`
  ADD CONSTRAINT `fk_pengadaan_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pengadaan_usulan` FOREIGN KEY (`id_usulan`) REFERENCES `usulan_pengadaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengadaan_serah_barang`
--
ALTER TABLE `pengadaan_serah_barang`
  ADD CONSTRAINT `fk_serah_alternatif` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_serah_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_serah_detail` FOREIGN KEY (`id_detail_usulan`) REFERENCES `detail_usulan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_serah_pengadaan` FOREIGN KEY (`id_pengadaan`) REFERENCES `pengadaan_pembelian` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_serah_received_by` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_serah_usulan` FOREIGN KEY (`id_usulan`) REFERENCES `usulan_pengadaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD CONSTRAINT `fk_penilaian_detail` FOREIGN KEY (`id_detail_usulan`) REFERENCES `detail_usulan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penilaian_kriteria` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `qr_disposisi`
--
ALTER TABLE `qr_disposisi`
  ADD CONSTRAINT `fk_qr_dokumen` FOREIGN KEY (`id_dokumen`) REFERENCES `dokumen_disposisi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qr_usulan` FOREIGN KEY (`id_usulan`) REFERENCES `usulan_pengadaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `realisasi_pengadaan`
--
ALTER TABLE `realisasi_pengadaan`
  ADD CONSTRAINT `fk_realisasi_usulan` FOREIGN KEY (`id_usulan`) REFERENCES `usulan_pengadaan` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `riwayat_validasi`
--
ALTER TABLE `riwayat_validasi`
  ADD CONSTRAINT `fk_riwayat_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_riwayat_usulan` FOREIGN KEY (`id_usulan`) REFERENCES `usulan_pengadaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stok_barang`
--
ALTER TABLE `stok_barang`
  ADD CONSTRAINT `fk_stok_alternatif` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `usulan_pengadaan`
--
ALTER TABLE `usulan_pengadaan`
  ADD CONSTRAINT `fk_usulan_user` FOREIGN KEY (`id_user_pengusul`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
