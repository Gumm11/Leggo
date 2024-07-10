-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2024 at 02:23 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_leggo`
--

-- --------------------------------------------------------

--
-- Table structure for table `bandara`
--

CREATE TABLE `bandara` (
  `Id_bandara` int(11) NOT NULL,
  `Nama_bandara` varchar(255) NOT NULL,
  `Kode_bandara` varchar(20) NOT NULL,
  `Id_kota` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bandara`
--

INSERT INTO `bandara` (`Id_bandara`, `Nama_bandara`, `Kode_bandara`, `Id_kota`) VALUES
(1, 'KL International Airport (KUL)', 'KUL', 2),
(2, 'Ngurah Rai International Airport (DPS)', 'DPS', 3),
(3, 'Changi International Airport (SIN)', 'SIN', 4),
(4, 'Suvarnabhumi International Airport (BKK)', 'BKK', 5),
(5, 'Soekarno–Hatta International Airport (CGK)', 'CGK', 1),
(6, 'Kualanamu International Airport', 'KNO', 6);

-- --------------------------------------------------------

--
-- Table structure for table `kota`
--

CREATE TABLE `kota` (
  `Id_kota` int(11) NOT NULL,
  `Nama_kota` varchar(255) NOT NULL,
  `Negara` varchar(255) NOT NULL,
  `Tempat_wisata` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kota`
--

INSERT INTO `kota` (`Id_kota`, `Nama_kota`, `Negara`, `Tempat_wisata`) VALUES
(1, 'Jakarta', 'Indonesia', 'National Monument'),
(2, 'Kuala Lumpur', 'Malaysia', 'Twin Tower'),
(3, 'Bali', 'Indonesia', 'Kuta Beach'),
(4, 'Singapura', 'Singapura', 'Merlion Park'),
(5, 'Bangkok', 'Thailand', 'Grand Palace'),
(6, 'Medan', 'Indonesia', 'Toba Lake');

-- --------------------------------------------------------

--
-- Table structure for table `passengers`
--

CREATE TABLE `passengers` (
  `id` int(11) NOT NULL,
  `id_pemesanan` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `age_group` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `passengers`
--

INSERT INTO `passengers` (`id`, `id_pemesanan`, `nama_lengkap`, `age_group`) VALUES
(1, 1, 'Vovella', 'dewasa'),
(2, 1, 'Angelin', 'dewasa'),
(3, 2, 'Vovella', 'dewasa'),
(4, 2, 'Angelin', 'anak-anak'),
(5, 3, 'Vovella', 'dewasa'),
(6, 3, 'Angelin', 'anak-anak');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` int(11) NOT NULL,
  `id_penerbangan_berangkat` int(11) DEFAULT NULL,
  `id_penerbangan_pulang` int(11) DEFAULT NULL,
  `id_users` int(11) DEFAULT NULL,
  `jumlah_penumpang` int(11) DEFAULT NULL,
  `total_bayar` decimal(10,2) DEFAULT NULL,
  `status_pembayaran` varchar(50) DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `selected_bagasi` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id_pemesanan`, `id_penerbangan_berangkat`, `id_penerbangan_pulang`, `id_users`, `jumlah_penumpang`, `total_bayar`, `status_pembayaran`, `nama_lengkap`, `nomor_telepon`, `email`, `selected_bagasi`) VALUES
(1, 1, 7, 1, 2, 10467000.00, 'Pending', 'Vovella', '082360988114', '03082220006@student.uph.edu', 10),
(2, 7, 1, 1, 2, 10467000.00, 'Success', 'Vovella', '082360988114', '03082220006@student.uph.edu', 10),
(3, 7, 1, 1, 2, 10467000.00, 'Pending', 'Vovella', '082360988114', '03082220006@student.uph.edu', 10);

-- --------------------------------------------------------

--
-- Table structure for table `penerbangan`
--

CREATE TABLE `penerbangan` (
  `Id_penerbangan` int(11) NOT NULL,
  `Id_pesawat` int(11) NOT NULL,
  `Id_bandara_keberangkatan` int(11) NOT NULL,
  `Id_bandara_kedatangan` int(11) NOT NULL,
  `Tanggal_keberangkatan` date NOT NULL,
  `Jam_keberangkatan` time NOT NULL,
  `Jam_kedatangan` time NOT NULL,
  `Harga_tiket` decimal(10,2) NOT NULL,
  `Jumlah_kursi` int(5) NOT NULL,
  `Kelas` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penerbangan`
--

INSERT INTO `penerbangan` (`Id_penerbangan`, `Id_pesawat`, `Id_bandara_keberangkatan`, `Id_bandara_kedatangan`, `Tanggal_keberangkatan`, `Jam_keberangkatan`, `Jam_kedatangan`, `Harga_tiket`, `Jumlah_kursi`, `Kelas`) VALUES
(1, 1, 6, 2, '2024-10-08', '05:25:00', '14:35:00', 2296750.00, 6, 'Ekonomi'),
(2, 2, 6, 2, '2024-10-08', '05:50:00', '11:40:00', 2396750.00, 8, 'Ekonomi'),
(3, 3, 6, 2, '2024-04-10', '13:50:00', '20:10:00', 2796750.00, 9, 'Ekonomi'),
(4, 3, 6, 2, '2024-04-10', '04:55:00', '18:05:00', 3296750.00, 10, 'Ekonomi'),
(7, 2, 2, 6, '2024-07-10', '10:50:00', '11:40:00', 2436750.00, 10, 'Ekonomi');

-- --------------------------------------------------------

--
-- Table structure for table `pesawat`
--

CREATE TABLE `pesawat` (
  `Id_pesawat` int(11) NOT NULL,
  `Nama_maskapai` varchar(255) NOT NULL,
  `Kode_pesawat` varchar(20) NOT NULL,
  `Tipe_pesawat` varchar(50) NOT NULL,
  `Jumlah_kursi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesawat`
--

INSERT INTO `pesawat` (`Id_pesawat`, `Nama_maskapai`, `Kode_pesawat`, `Tipe_pesawat`, `Jumlah_kursi`) VALUES
(1, 'Lion Air', 'JT', 'Boeing 737-800', 189),
(2, 'Super Air Jet', 'SJ', 'Airbus A320-200', 186),
(3, 'Citilink', 'QG', 'Airbus A320-200', 186);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id_users` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id_users`, `email`, `username`, `phone_number`, `password`) VALUES
(1, 'a@gmail.com', 'asdadaa', '082360988116', '$2y$10$H6DY7zbqXIJL4itzB6YjNuYj97pulJG/eyQjiIoQ5TvZ4/N.R.i.u'),
(2, 'skyfin312@gmail.com', 'setan', '082360988110', '$2y$10$B0ztAEfulo3ew7iXnzUR8OXVARlgw.L6XMj8QP0f.OyNgUKDLhCia');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bandara`
--
ALTER TABLE `bandara`
  ADD PRIMARY KEY (`Id_bandara`),
  ADD KEY `Id_kota` (`Id_kota`);

--
-- Indexes for table `kota`
--
ALTER TABLE `kota`
  ADD PRIMARY KEY (`Id_kota`);

--
-- Indexes for table `passengers`
--
ALTER TABLE `passengers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pemesanan` (`id_pemesanan`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD KEY `id_penerbangan_berangkat` (`id_penerbangan_berangkat`),
  ADD KEY `id_penerbangan_pulang` (`id_penerbangan_pulang`),
  ADD KEY `id_users` (`id_users`);

--
-- Indexes for table `penerbangan`
--
ALTER TABLE `penerbangan`
  ADD PRIMARY KEY (`Id_penerbangan`),
  ADD KEY `Id_pesawat` (`Id_pesawat`),
  ADD KEY `Id_bandara_keberangkatan` (`Id_bandara_keberangkatan`),
  ADD KEY `Id_bandara_kedatangan` (`Id_bandara_kedatangan`);

--
-- Indexes for table `pesawat`
--
ALTER TABLE `pesawat`
  ADD PRIMARY KEY (`Id_pesawat`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id_users`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bandara`
--
ALTER TABLE `bandara`
  MODIFY `Id_bandara` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kota`
--
ALTER TABLE `kota`
  MODIFY `Id_kota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `passengers`
--
ALTER TABLE `passengers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pemesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `penerbangan`
--
ALTER TABLE `penerbangan`
  MODIFY `Id_penerbangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pesawat`
--
ALTER TABLE `pesawat`
  MODIFY `Id_pesawat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bandara`
--
ALTER TABLE `bandara`
  ADD CONSTRAINT `bandara_ibfk_1` FOREIGN KEY (`Id_kota`) REFERENCES `kota` (`Id_kota`);

--
-- Constraints for table `passengers`
--
ALTER TABLE `passengers`
  ADD CONSTRAINT `passengers_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`);

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_penerbangan_berangkat`) REFERENCES `penerbangan` (`Id_penerbangan`),
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`id_penerbangan_pulang`) REFERENCES `penerbangan` (`Id_penerbangan`),
  ADD CONSTRAINT `pemesanan_ibfk_3` FOREIGN KEY (`id_users`) REFERENCES `users` (`Id_users`);

--
-- Constraints for table `penerbangan`
--
ALTER TABLE `penerbangan`
  ADD CONSTRAINT `penerbangan_ibfk_1` FOREIGN KEY (`Id_pesawat`) REFERENCES `pesawat` (`Id_pesawat`),
  ADD CONSTRAINT `penerbangan_ibfk_2` FOREIGN KEY (`Id_bandara_keberangkatan`) REFERENCES `bandara` (`Id_bandara`),
  ADD CONSTRAINT `penerbangan_ibfk_3` FOREIGN KEY (`Id_bandara_kedatangan`) REFERENCES `bandara` (`Id_bandara`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
