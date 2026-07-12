-- phpMyAdmin SQL Dump
-- Database Schema untuk Sistem Informasi Arsip Digital Pernikahan
-- Bersih dari data pribadi asli untuk kebutuhan repositori umum

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Struktur dari tabel `pengguna`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pengguna` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `role` enum('koor','petugas') NOT NULL DEFAULT 'petugas',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Akun Demo Default:
-- Username Koor: admin_koor (Password: password)
-- Username Petugas: petugas_demo (Password: password)
INSERT INTO `pengguna` (`id`, `username`, `password`, `nama`, `role`) VALUES
(1, 'admin_koor', '$2y$10$SiDPcanC2/8nVGiw2o4ZxuIjyAeFtBDK6e/5xyM1JzYI0lIAiMPWu', 'Koordinator Demo', 'koor'),
(2, 'petugas_demo', '$2y$10$rE2Y9At839uHyqlC5gHbBugBFMjJFpYR3eTVDPKuO6cLpDFluxWV2', 'Petugas Demo', 'petugas');

-- --------------------------------------------------------
-- Struktur dari tabel `arsip`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `arsip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_arsip` varchar(20) NOT NULL,
  `kelurahan` enum('Canden','Patalan','Sumberagung','Trimulyo') NOT NULL,
  `tgl_daftar` date NOT NULL,
  `no_akta` varchar(50) NOT NULL,
  `nama_suami` varchar(100) NOT NULL,
  `nama_istri` varchar(100) NOT NULL,
  `status_pernikahan` varchar(50) NOT NULL,
  `nama_saksi` varchar(100) NOT NULL,
  `nama_petugas` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_pengguna` int(11) NOT NULL,
  `nik_suami` varchar(20) NOT NULL DEFAULT '',
  `nik_istri` varchar(20) NOT NULL DEFAULT '',
  `tgl_nikah` date NOT NULL DEFAULT '2000-01-01',
  `alamat_suami` text NOT NULL,
  `alamat_istri` text NOT NULL,
  `nama_penghulu` varchar(100) NOT NULL DEFAULT '',
  `foto_suami` varchar(255) NOT NULL,
  `foto_istri` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_arsip` (`no_arsip`),
  KEY `fk_arsip_petugas` (`id_pengguna`),
  CONSTRAINT `fk_arsip_petugas` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Struktur dari tabel `arsip_scan`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `arsip_scan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_arsip` varchar(20) NOT NULL,
  `kelurahan` enum('Canden','Patalan','Sumberagung','Trimulyo') NOT NULL,
  `tgl_arsip` date NOT NULL,
  `nama_suami` varchar(100) NOT NULL,
  `nama_istri` varchar(100) NOT NULL,
  `files_pdf` text NOT NULL,
  `nama_petugas` varchar(100) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_arsip` (`no_arsip`),
  KEY `fk_arsip_scan_petugas` (`id_pengguna`),
  CONSTRAINT `fk_arsip_scan_petugas` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Struktur dari tabel `edit_requests`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `edit_requests` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `arsip_id` int(11) NOT NULL,
  `petugas_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_req_arsip` FOREIGN KEY (`arsip_id`) REFERENCES `arsip` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_req_petugas` FOREIGN KEY (`petugas_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
