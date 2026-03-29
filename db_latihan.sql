-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2024 at 02:49 PM
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
-- Database: `db_latihan`
--

-- --------------------------------------------------------

--
-- Table structure for table `itbl_mahasiswa`
--

CREATE TABLE `itbl_mahasiswa` (
  `id` int(3) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `no_hp` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `itbl_mahasiswa`
--

INSERT INTO `itbl_mahasiswa` (`id`, `nim`, `nama`, `alamat`, `no_hp`) VALUES
(1, 'D1A230068', 'Dimas Aditya', 'Subang', '081234567890'),
(2, 'D1A230062', 'Riska Nuril Anwar', 'Subang', '089876543210');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_matakuliah`
--

CREATE TABLE `tbl_matakuliah` (
  `id_mk` int(3) NOT NULL,
  `kode_mk` varchar(10) NOT NULL,
  `nama_mk` varchar(100) NOT NULL,
  `sks` varchar(2) NOT NULL,
  `ruangan` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_matakuliah`
--

INSERT INTO `tbl_matakuliah` (`id_mk`, `kode_mk`, `nama_mk`, `sks`, `ruangan`) VALUES
(2, 'D1A32006', 'PEMROGRAMAN BERORIENTASI OBJEK', '2', 'Lab 2'),
(3, 'UNI321', 'ILMU KEALAMAN DASAR', '2', '9'),
(4, 'D1A33002', 'SISTEM BASIS DATA', '3', 'Lab 2'),
(5, 'UNI322', 'ILMU SOSIAL DAN BUDAYA DASAR', '2', '11'),
(6, 'UNI323', 'KOMPUTER III', '2', 'Lab 1'),
(7, 'D1A33003', 'PEMROGRAMAN BERBASIS WEB	', '3', 'Lab 2'),
(8, 'D1A32004', 'TATA KELOLA TEKNOLOGI INFORMASI', '2', '8'),
(9, 'D1A33005', '	ANALISIS DAN PERANCANGAN SISTEM INFORMASI', '3', '9'),
(10, 'D1A32001', 'JARINGAN KOMPUTER', '2', 'Lab 2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `itbl_mahasiswa`
--
ALTER TABLE `itbl_mahasiswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_matakuliah`
--
ALTER TABLE `tbl_matakuliah`
  ADD PRIMARY KEY (`id_mk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `itbl_mahasiswa`
--
ALTER TABLE `itbl_mahasiswa`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `tbl_matakuliah`
--
ALTER TABLE `tbl_matakuliah`
  MODIFY `id_mk` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
