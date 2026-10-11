-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2026 at 01:54 AM
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
-- Database: `counseling_platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_praktik`
--

CREATE TABLE `jadwal_praktik` (
  `id_jadwal` int(10) UNSIGNED NOT NULL,
  `id_konsultan` int(10) UNSIGNED NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `kuota` int(11) NOT NULL DEFAULT 1,
  `is_aktif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `konsultasi`
--

CREATE TABLE `konsultasi` (
  `id_konsultasi` int(10) UNSIGNED NOT NULL,
  `id_klien` int(10) UNSIGNED NOT NULL,
  `id_konsultan` int(10) UNSIGNED NOT NULL,
  `id_jadwal` int(10) UNSIGNED NOT NULL,
  `tipe_sesi` enum('Online','Pick-up') NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `durasi_menit` int(11) DEFAULT 60,
  `status` enum('Menunggu Konfirmasi','Dikonfirmasi','Berlangsung','Selesai','Dibatalkan') DEFAULT 'Menunggu Konfirmasi',
  `titik_jemput` varchar(255) DEFAULT NULL,
  `titik_tujuan` varchar(255) DEFAULT NULL,
  `link_meeting` varchar(255) DEFAULT NULL,
  `room_id` varchar(100) DEFAULT NULL,
  `platform` enum('Google Meet','Zoom','Jitsi') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` int(10) UNSIGNED NOT NULL,
  `id_konsultasi` int(10) UNSIGNED NOT NULL,
  `id_konsultan` int(10) UNSIGNED NOT NULL,
  `catatan` text DEFAULT NULL,
  `rekomendasi` text DEFAULT NULL,
  `dibuat_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(10) UNSIGNED NOT NULL,
  `id_konsultasi` int(10) UNSIGNED NOT NULL,
  `tanggal_bayar` datetime DEFAULT NULL,
  `metode` enum('GoPay','OVO','Transfer Bank','Virtual Account') DEFAULT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `status_bayar` enum('Pending','Sukses','Gagal','Refund') DEFAULT 'Pending',
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `kode_transaksi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesan`
--

CREATE TABLE `pesan` (
  `id_pesan` int(10) UNSIGNED NOT NULL,
  `id_konsultasi` int(10) UNSIGNED NOT NULL,
  `id_pengirim` int(10) UNSIGNED NOT NULL,
  `tipe_pengirim` enum('Client','Konsultan') NOT NULL,
  `isi_pesan` text DEFAULT NULL,
  `tipe_konten` enum('Teks','Gambar','File') DEFAULT 'Teks',
  `dikirim_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_dibaca` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profil_konsultan`
--

CREATE TABLE `profil_konsultan` (
  `id_profil` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `no_str` varchar(50) DEFAULT NULL,
  `spesialisasi` varchar(100) DEFAULT NULL,
  `pengalaman_tahun` int(11) DEFAULT NULL,
  `status_verifikasi` enum('pending','verified','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `role` enum('klien','konsultan','administrator') NOT NULL DEFAULT 'klien',
  `status_akun` enum('aktif','nonaktif','ditangguhkan') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jadwal_praktik`
--
ALTER TABLE `jadwal_praktik`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_konsultan` (`id_konsultan`);

--
-- Indexes for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD PRIMARY KEY (`id_konsultasi`),
  ADD KEY `id_klien` (`id_klien`),
  ADD KEY `id_konsultan` (`id_konsultan`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id_laporan`),
  ADD KEY `id_konsultasi` (`id_konsultasi`),
  ADD KEY `id_konsultan` (`id_konsultan`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_konsultasi` (`id_konsultasi`);

--
-- Indexes for table `pesan`
--
ALTER TABLE `pesan`
  ADD PRIMARY KEY (`id_pesan`),
  ADD KEY `id_konsultasi` (`id_konsultasi`),
  ADD KEY `id_pengirim` (`id_pengirim`);

--
-- Indexes for table `profil_konsultan`
--
ALTER TABLE `profil_konsultan`
  ADD PRIMARY KEY (`id_profil`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jadwal_praktik`
--
ALTER TABLE `jadwal_praktik`
  MODIFY `id_jadwal` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `konsultasi`
--
ALTER TABLE `konsultasi`
  MODIFY `id_konsultasi` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id_laporan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id_pesan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profil_konsultan`
--
ALTER TABLE `profil_konsultan`
  MODIFY `id_profil` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal_praktik`
--
ALTER TABLE `jadwal_praktik`
  ADD CONSTRAINT `jadwal_praktik_ibfk_1` FOREIGN KEY (`id_konsultan`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `konsultasi`
--
ALTER TABLE `konsultasi`
  ADD CONSTRAINT `konsultasi_ibfk_1` FOREIGN KEY (`id_klien`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `konsultasi_ibfk_2` FOREIGN KEY (`id_konsultan`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `konsultasi_ibfk_3` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_praktik` (`id_jadwal`) ON DELETE CASCADE;

--
-- Constraints for table `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `laporan_ibfk_1` FOREIGN KEY (`id_konsultasi`) REFERENCES `konsultasi` (`id_konsultasi`) ON DELETE CASCADE,
  ADD CONSTRAINT `laporan_ibfk_2` FOREIGN KEY (`id_konsultan`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_konsultasi`) REFERENCES `konsultasi` (`id_konsultasi`) ON DELETE CASCADE;

--
-- Constraints for table `pesan`
--
ALTER TABLE `pesan`
  ADD CONSTRAINT `pesan_ibfk_1` FOREIGN KEY (`id_konsultasi`) REFERENCES `konsultasi` (`id_konsultasi`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesan_ibfk_2` FOREIGN KEY (`id_pengirim`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `profil_konsultan`
--
ALTER TABLE `profil_konsultan`
  ADD CONSTRAINT `profil_konsultan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
