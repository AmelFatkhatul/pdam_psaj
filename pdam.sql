-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Mar 2025 pada 06.16
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
-- Database: `pdam`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` int(11) NOT NULL,
  `nama_anggota` varchar(100) NOT NULL,
  `no_pelanggan` varchar(50) NOT NULL,
  `telepon` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `anggota`
--

INSERT INTO `anggota` (`id_anggota`, `nama_anggota`, `no_pelanggan`, `telepon`) VALUES
(25, 'Ahmad Fadli', '13243243', '081234567891'),
(26, 'Citra Lestari', '13254374', '081234567896'),
(27, ' ismail', '12345678', '082345678');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keluhan`
--

CREATE TABLE `keluhan` (
  `id_keluhan` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `keluhan` text NOT NULL,
  `tanggal_keluhan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `keluhan`
--

INSERT INTO `keluhan` (`id_keluhan`, `id_anggota`, `keluhan`, `tanggal_keluhan`) VALUES
(10, 25, 'kran rusak', '2025-03-08 06:34:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `login_admin`
--

CREATE TABLE `login_admin` (
  `id_admin` int(11) NOT NULL,
  `username_admin` varchar(50) NOT NULL,
  `password_admin` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `login_admin`
--

INSERT INTO `login_admin` (`id_admin`, `username_admin`, `password_admin`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `login_users`
--

CREATE TABLE `login_users` (
  `id_user` int(11) NOT NULL,
  `email_user` varchar(255) NOT NULL,
  `username_user` varchar(50) NOT NULL,
  `password_user` varchar(255) NOT NULL,
  `saldo` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `login_users`
--

INSERT INTO `login_users` (`id_user`, `email_user`, `username_user`, `password_user`, `saldo`) VALUES
(4, 'meta@gmail.com', 'meta', '$2y$10$EGf1u.sNqVZfcBu1Y/PD8ueZNBqv9dgNAGWI8ddO/F8S2AYcdfvvO', 125000),
(5, 'kika@gmail.com', 'kika', '$2y$10$F.OaeTpnw/GRA1XNk4WdIeVd57Ie3FhcC/f0iS6SZDQLTIL.uO6qO', 0),
(6, 'amel@gmail.com', 'amel', '$2y$10$7O8QsXAMH6iQeOhhJXlST.HEU2OJLyK6I3xdcdam6yZC3hzj8Pmbe', 2147413647);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tambah_tagihan`
--

CREATE TABLE `tambah_tagihan` (
  `id_tagihan` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `no_pelanggan` varchar(50) NOT NULL,
  `bulan_pem` date NOT NULL,
  `total_pem` decimal(10,2) NOT NULL,
  `status` enum('belum bayar','sudah bayar') DEFAULT 'belum bayar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tambah_tagihan`
--

INSERT INTO `tambah_tagihan` (`id_tagihan`, `id_anggota`, `no_pelanggan`, `bulan_pem`, `total_pem`, `status`) VALUES
(43, 25, '13243243', '2025-03-08', 70000.00, 'sudah bayar'),
(44, 26, '13254374', '2025-03-08', 70000.00, 'sudah bayar'),
(46, 27, '12345678', '2025-03-13', 75000.00, 'sudah bayar');

-- --------------------------------------------------------

--
-- Struktur dari tabel `topup`
--

CREATE TABLE `topup` (
  `id_topup` int(11) NOT NULL,
  `username_user` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('Dana','BRI','Gopay','OVO','ShopeePay') NOT NULL,
  `referral_code` char(8) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `topup`
--

INSERT INTO `topup` (`id_topup`, `username_user`, `amount`, `payment_method`, `referral_code`, `created_at`) VALUES
(26, 'meta', 70000.00, 'Dana', '82E2D867', '2025-03-04 11:16:01'),
(27, 'meta', 30000.00, 'Dana', '255BA840', '2025-03-04 11:18:43'),
(28, 'meta', 70000.00, 'Dana', 'E08BD498', '2025-03-05 03:52:38'),
(29, 'meta', 70000.00, 'Dana', 'ED09FC96', '2025-03-06 02:59:27'),
(30, 'meta', 80000.00, 'Dana', 'D4675D3E', '2025-03-07 01:26:20'),
(31, 'meta', 7000.00, 'Dana', 'D88881B5', '2025-03-07 02:14:12'),
(32, 'amel', 100000.00, 'Dana', 'D37A0EE2', '2025-03-08 05:10:36'),
(33, 'amel', 7000.00, 'Dana', '338BDFB6', '2025-03-08 05:33:22'),
(34, 'amel', 99999999.99, 'Dana', '6CBECAAD', '2025-03-08 06:32:44'),
(35, 'meta', 100000.00, 'Dana', 'EFAD3578', '2025-03-13 02:01:14'),
(36, 'meta', 70000.00, 'Gopay', 'DA67B8B5', '2025-03-18 01:05:11'),
(37, 'meta', 100000.00, 'Dana', '50AA0F29', '2025-03-18 01:54:06');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`),
  ADD UNIQUE KEY `no_pelanggan` (`no_pelanggan`);

--
-- Indeks untuk tabel `keluhan`
--
ALTER TABLE `keluhan`
  ADD PRIMARY KEY (`id_keluhan`),
  ADD KEY `id_anggota` (`id_anggota`);

--
-- Indeks untuk tabel `login_admin`
--
ALTER TABLE `login_admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username_admin` (`username_admin`);

--
-- Indeks untuk tabel `login_users`
--
ALTER TABLE `login_users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email_user` (`email_user`),
  ADD UNIQUE KEY `username_user` (`username_user`);

--
-- Indeks untuk tabel `tambah_tagihan`
--
ALTER TABLE `tambah_tagihan`
  ADD PRIMARY KEY (`id_tagihan`),
  ADD KEY `id_anggota` (`id_anggota`);

--
-- Indeks untuk tabel `topup`
--
ALTER TABLE `topup`
  ADD PRIMARY KEY (`id_topup`),
  ADD KEY `username_user` (`username_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `keluhan`
--
ALTER TABLE `keluhan`
  MODIFY `id_keluhan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `login_admin`
--
ALTER TABLE `login_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `login_users`
--
ALTER TABLE `login_users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tambah_tagihan`
--
ALTER TABLE `tambah_tagihan`
  MODIFY `id_tagihan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT untuk tabel `topup`
--
ALTER TABLE `topup`
  MODIFY `id_topup` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `keluhan`
--
ALTER TABLE `keluhan`
  ADD CONSTRAINT `keluhan_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tambah_tagihan`
--
ALTER TABLE `tambah_tagihan`
  ADD CONSTRAINT `tambah_tagihan_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `topup`
--
ALTER TABLE `topup`
  ADD CONSTRAINT `topup_ibfk_1` FOREIGN KEY (`username_user`) REFERENCES `login_users` (`username_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
