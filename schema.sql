-- phpMyAdmin SQL Dump
-- Database: `db_peduli_sampah_krapyak`

CREATE DATABASE IF NOT EXISTS `db_peduli_sampah_krapyak` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_peduli_sampah_krapyak`;

-- Hapus tabel lama jika ada
DROP TABLE IF EXISTS `riwayat_kegiatan`;
DROP TABLE IF EXISTS `sampah`;
DROP TABLE IF EXISTS `santri_profile`;
DROP TABLE IF EXISTS `users`;

-- Table structure for table `users`
CREATE TABLE `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','santri') NOT NULL DEFAULT 'santri',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `santri_profile`
CREATE TABLE `santri_profile` (
  `id_profile` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `nama_santri` varchar(100) NOT NULL,
  `asrama_komplek` varchar(100) NOT NULL,
  `yayasan` varchar(100) NOT NULL,
  PRIMARY KEY (`id_profile`),
  FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `sampah`
CREATE TABLE `sampah` (
  `id_sampah` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `jenis_sampah` varchar(100) NOT NULL,
  `berat` float NOT NULL,
  `tanggal` date NOT NULL,
  `asrama_komplek` varchar(100) NOT NULL,
  `yayasan` varchar(100) NOT NULL,
  PRIMARY KEY (`id_sampah`),
  FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `riwayat_kegiatan`
CREATE TABLE `riwayat_kegiatan` (
  `id_riwayat` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `aktivitas` enum('input','edit','hapus') NOT NULL,
  `detail_aktivitas` varchar(255) NOT NULL,
  `jenis_sampah` varchar(100) DEFAULT NULL,
  `berat` float DEFAULT NULL,
  `waktu` datetime NOT NULL,
  PRIMARY KEY (`id_riwayat`),
  FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Default Admin
INSERT INTO `users` (`nama`, `username`, `password`, `role`) VALUES
('Administrator', 'admin', '$2a$12$T9fVQiSx2P9kayN.tP/Ty.Il7Ecv7s/838HbFulhqT6fl5PrM9oiC', 'admin');

-- Seed dummy santri
INSERT INTO `users` (`nama`, `username`, `password`, `role`) VALUES
('Budi Santoso', 'budi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'santri'),
('Ahmad Dahlan', 'ahmad', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'santri'),
('Siti Aisyah', 'siti', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'santri');

INSERT INTO `santri_profile` (`id_user`, `nama_santri`, `asrama_komplek`, `yayasan`) VALUES
(2, 'Budi Santoso', 'K', 'Ali Maksum'),
(3, 'Ahmad Dahlan', 'A', 'Krapyak'),
(4, 'Siti Aisyah', 'B', 'Ali Maksum');

-- Seed dummy sampah
INSERT INTO `sampah` (`id_user`, `jenis_sampah`, `berat`, `tanggal`, `asrama_komplek`, `yayasan`) VALUES
(2, 'Plastik', 2.5, CURDATE(), 'K', 'Ali Maksum'),
(2, 'Kertas', 1.0, CURDATE(), 'K', 'Ali Maksum'),
(3, 'Organik', 3.2, CURDATE(), 'A', 'Krapyak'),
(4, 'Plastik', 1.5, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'B', 'Ali Maksum'),
(3, 'Logam', 0.5, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'A', 'Krapyak');

-- Seed dummy riwayat
INSERT INTO `riwayat_kegiatan` (`id_user`, `aktivitas`, `detail_aktivitas`, `jenis_sampah`, `berat`, `waktu`) VALUES
(2, 'input', 'Budi Santoso menyetorkan Plastik 2.5 Kg', 'Plastik', 2.5, NOW()),
(2, 'input', 'Budi Santoso menyetorkan Kertas 1.0 Kg', 'Kertas', 1.0, NOW()),
(3, 'input', 'Ahmad Dahlan menyetorkan Organik 3.2 Kg', 'Organik', 3.2, NOW()),
(4, 'input', 'Siti Aisyah menyetorkan Plastik 1.5 Kg', 'Plastik', 1.5, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(3, 'input', 'Ahmad Dahlan menyetorkan Logam 0.5 Kg', 'Logam', 0.5, DATE_SUB(NOW(), INTERVAL 2 DAY));
