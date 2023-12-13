-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 13, 2023 at 06:44 AM
-- Server version: 8.0.31
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sk_ejm`
--

-- --------------------------------------------------------

--
-- Table structure for table `dailypresence`
--

DROP TABLE IF EXISTS `dailypresence`;
CREATE TABLE IF NOT EXISTS `dailypresence` (
  `id_dailypresence` bigint NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `pegawai` varchar(30) NOT NULL,
  `jam_datang` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `status_presensi` int NOT NULL DEFAULT '1',
  `is_setujuadmin` tinyint DEFAULT NULL,
  `deleted` tinyint NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_lastupdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dailypresence`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dailypresence`
--

INSERT INTO `dailypresence` (`id_dailypresence`, `tanggal`, `pegawai`, `jam_datang`, `jam_pulang`, `status_presensi`, `is_setujuadmin`, `deleted`, `timestamp`, `timestamp_lastupdated`) VALUES
(1, '2022-08-19', 'admin', '07:27:00', '16:35:00', 1, 1, 0, '2022-11-10 06:53:57', '2022-11-10 06:53:57'),
(2, '2022-11-09', 'admin', '07:25:00', '16:35:00', 1, 0, 0, '2022-11-10 06:53:57', '2022-11-10 06:53:57'),
(3, '2022-11-10', 'admin', '07:15:00', '16:05:00', 1, 1, 0, '2022-11-10 10:53:57', '2022-11-10 06:53:57'),
(4, '2022-11-11', 'admin', '07:29:00', '16:05:00', 1, 1, 0, '2022-11-10 10:53:57', '2022-11-10 06:53:57'),
(5, '2022-11-08', 'admin', NULL, NULL, 2, NULL, 0, '2022-11-10 10:53:57', '2022-11-10 06:53:57'),
(6, '2022-11-18', 'admin', '07:00:00', NULL, 1, NULL, 0, '2022-11-18 07:06:04', '2022-11-18 07:06:04');

-- --------------------------------------------------------

--
-- Table structure for table `dailypresencestatus`
--

DROP TABLE IF EXISTS `dailypresencestatus`;
CREATE TABLE IF NOT EXISTS `dailypresencestatus` (
  `id_dailypresencestatus` int NOT NULL AUTO_INCREMENT,
  `keterangan_presensi` varchar(255) NOT NULL,
  PRIMARY KEY (`id_dailypresencestatus`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dailypresencestatus`
--

INSERT INTO `dailypresencestatus` (`id_dailypresencestatus`, `keterangan_presensi`) VALUES
(1, 'Hadir'),
(2, 'Cuti'),
(3, 'Dinas Luar'),
(4, 'Lembur di Hari Libur');

-- --------------------------------------------------------

--
-- Table structure for table `dailyreport`
--

DROP TABLE IF EXISTS `dailyreport`;
CREATE TABLE IF NOT EXISTS `dailyreport` (
  `id_keg` bigint NOT NULL AUTO_INCREMENT,
  `owner` varchar(30) NOT NULL,
  `lintas_tim` tinyint NOT NULL DEFAULT '0',
  `is_izinlintastim` tinyint DEFAULT NULL,
  `assigned_to` varchar(30) DEFAULT NULL,
  `timkerjaproject` bigint DEFAULT NULL,
  `is_setujuketuatim` tinyint DEFAULT NULL,
  `rincian_report` text NOT NULL,
  `status_selesai` tinyint NOT NULL DEFAULT '1',
  `tanggal_kerja` date NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_lastupdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `priority` tinyint NOT NULL DEFAULT '0',
  `ket` text,
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_keg`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dailyreport`
--

INSERT INTO `dailyreport` (`id_keg`, `owner`, `lintas_tim`, `is_izinlintastim`, `assigned_to`, `timkerjaproject`, `is_setujuketuatim`, `rincian_report`, `status_selesai`, `tanggal_kerja`, `timestamp`, `timestamp_lastupdated`, `priority`, `ket`, `deleted`) VALUES
(1, 'mrx', 0, NULL, 'admin', 1, 0, 'Code this application and share it to the Internet', 0, '2023-12-28', '2022-08-19 07:12:47', '2022-08-19 07:12:47', 1, NULL, 0),
(2, 'admin', 0, NULL, NULL, NULL, NULL, 'Write a research paper', 0, '2023-12-16', '2022-08-19 07:20:58', '2022-08-19 07:20:58', 0, NULL, 0),
(8, 'admin', 0, NULL, NULL, NULL, NULL, 'Binge watch Avatar The Last Airbender', 1, '2023-11-01', '2022-08-19 07:20:58', '2022-08-19 07:20:58', 0, NULL, 0),
(9, 'admin', 0, NULL, 'mrx', 1, NULL, 'Teeees', 1, '2023-12-07', '2023-12-07 08:35:06', '2023-12-07 08:35:06', 0, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

DROP TABLE IF EXISTS `level`;
CREATE TABLE IF NOT EXISTS `level` (
  `id_level` int NOT NULL AUTO_INCREMENT,
  `nama_level` varchar(255) NOT NULL,
  PRIMARY KEY (`id_level`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`id_level`, `nama_level`) VALUES
(0, 'Super Admin'),
(1, 'Admin Daerah'),
(2, 'Pimpinan'),
(3, 'Ketua Tim'),
(4, 'Admin Umum'),
(5, 'Pegawai');

-- --------------------------------------------------------

--
-- Table structure for table `levelpengguna`
--

DROP TABLE IF EXISTS `levelpengguna`;
CREATE TABLE IF NOT EXISTS `levelpengguna` (
  `id_levelpengguna` bigint NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `level` int NOT NULL,
  `autentikasi` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_levelpengguna`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `levelpengguna`
--

INSERT INTO `levelpengguna` (`id_levelpengguna`, `username`, `level`, `autentikasi`) VALUES
(1, 'admin', 0, 1),
(2, 'admin', 1, 1),
(3, 'admin', 2, 1),
(4, 'admin', 3, 1),
(5, 'admin', 4, 1),
(6, 'admin', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

DROP TABLE IF EXISTS `pengguna`;
CREATE TABLE IF NOT EXISTS `pengguna` (
  `username` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL,
  `nip` bigint NOT NULL,
  `gelar_depan` varchar(20) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `gelar_belakang` varchar(20) DEFAULT NULL,
  `satker` int NOT NULL,
  `fungsi_pengguna` int NOT NULL,
  `subfungsi_pengguna` int DEFAULT NULL,
  `approved_ckp_by` int NOT NULL,
  `jabatan` int NOT NULL,
  `pangkatgol` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `foto` text NOT NULL,
  `tgl_daftar` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_pengguna` tinyint NOT NULL DEFAULT '1',
  `theme` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`username`, `password`, `nip`, `gelar_depan`, `nama`, `gelar_belakang`, `satker`, `fungsi_pengguna`, `subfungsi_pengguna`, `approved_ckp_by`, `jabatan`, `pangkatgol`, `email`, `foto`, `tgl_daftar`, `status_pengguna`, `theme`) VALUES
('admin', '2aefc34200a294a3cc7db81b43a81873', 190000000000000001, NULL, 'Admin', 'M.Sc.', 1000, 1, 17, 2, 2, 7, 'nofriani@bps.go.id', '190000000000000001-admin.jpg', '2021-03-03 05:16:09', 1, 0),
('mrsx', 'd19f7d284f8f2ffce2d9eff53cffda82', 190000000000000000, 'Dr.', 'X', '', 1000, 1, NULL, 1, 1, 9, 'edwine@bps.go.id', '190000000000000000-mrx.jpg', '2021-03-23 20:49:45', 1, 0),
('mrx', '9b47c953143b82bc24fef9ad3b238750', 190000000000000002, NULL, 'X', 'PhD', 1000, 1, 17, 2, 1, 9, 'novrian@bps.go.id', '190000000000000002-mrsx.jpg', '2021-03-12 15:57:23', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `penggunaapprover`
--

DROP TABLE IF EXISTS `penggunaapprover`;
CREATE TABLE IF NOT EXISTS `penggunaapprover` (
  `id_approver` int NOT NULL AUTO_INCREMENT,
  `satker` int NOT NULL,
  `approver` varchar(30) NOT NULL,
  `autentikasi` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_approver`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penggunaapprover`
--

INSERT INTO `penggunaapprover` (`id_approver`, `satker`, `approver`, `autentikasi`) VALUES
(1, 1700, 'mrx', 1),
(2, 1700, 'mrsx', 1);

-- --------------------------------------------------------

--
-- Table structure for table `penggunafungsi`
--

DROP TABLE IF EXISTS `penggunafungsi`;
CREATE TABLE IF NOT EXISTS `penggunafungsi` (
  `id_fungsi` int NOT NULL AUTO_INCREMENT,
  `nama_fungsi` varchar(255) NOT NULL,
  PRIMARY KEY (`id_fungsi`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penggunafungsi`
--

INSERT INTO `penggunafungsi` (`id_fungsi`, `nama_fungsi`) VALUES
(1, 'Development'),
(2, 'Marketing');

-- --------------------------------------------------------

--
-- Table structure for table `penggunajabatan`
--

DROP TABLE IF EXISTS `penggunajabatan`;
CREATE TABLE IF NOT EXISTS `penggunajabatan` (
  `id_jabatan` bigint NOT NULL AUTO_INCREMENT,
  `nama_jabatan` text NOT NULL,
  PRIMARY KEY (`id_jabatan`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penggunajabatan`
--

INSERT INTO `penggunajabatan` (`id_jabatan`, `nama_jabatan`) VALUES
(1, 'Senior Engineer'),
(2, 'Junior Engineer');

-- --------------------------------------------------------

--
-- Table structure for table `penggunapangkatgol`
--

DROP TABLE IF EXISTS `penggunapangkatgol`;
CREATE TABLE IF NOT EXISTS `penggunapangkatgol` (
  `id_pangkatgol` bigint NOT NULL AUTO_INCREMENT,
  `nama_pangkatgol` varchar(255) NOT NULL,
  PRIMARY KEY (`id_pangkatgol`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penggunapangkatgol`
--

INSERT INTO `penggunapangkatgol` (`id_pangkatgol`, `nama_pangkatgol`) VALUES
(1, 'Pengatur Muda/ II-a'),
(2, 'Pengatur Muda Tingkat I/ II-b'),
(3, 'Pengatur/ II-c'),
(4, 'Pengatur Tingkat I/ II-d'),
(5, 'Penata Muda/ III-a'),
(6, 'Penata Muda Tingkat I/ III-b'),
(7, 'Penata/ III-c'),
(8, 'Penata Tingkat I/ III-d'),
(9, 'Pembina/ IV-a'),
(10, 'Pembina Tingkat I/ IV-b'),
(11, 'Pembina Utama Muda/ IV-c'),
(12, 'Pembina Utama Madya/ IV-d'),
(13, 'Pembina Utama/ IV-e');

-- --------------------------------------------------------

--
-- Table structure for table `penggunasatker`
--

DROP TABLE IF EXISTS `penggunasatker`;
CREATE TABLE IF NOT EXISTS `penggunasatker` (
  `id_satker` int NOT NULL,
  `nama_satker` varchar(100) NOT NULL,
  PRIMARY KEY (`id_satker`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `penggunasatker`
--

INSERT INTO `penggunasatker` (`id_satker`, `nama_satker`) VALUES
(1000, 'Indonesia'),
(1100, 'Provinsi Aceh'),
(1200, 'Provinsi Sumatra Utara'),
(1300, 'Provinsi Sumatra Barat'),
(1400, 'Provinsi Riau'),
(1500, 'Provinsi Jambi'),
(1600, 'Provinsi Sumatra Selatan'),
(1700, 'Provinsi Bengkulu'),
(1800, 'Provinsi Lampung'),
(1900, 'Provinsi Bangka Belitung'),
(3100, 'Provinsi DKI Jakarta');

-- --------------------------------------------------------

--
-- Table structure for table `penggunasubfungsi`
--

DROP TABLE IF EXISTS `penggunasubfungsi`;
CREATE TABLE IF NOT EXISTS `penggunasubfungsi` (
  `id_subfungsi` int NOT NULL AUTO_INCREMENT,
  `id_fungsi` int NOT NULL,
  `nama_subfungsi` text NOT NULL,
  PRIMARY KEY (`id_subfungsi`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penggunasubfungsi`
--

INSERT INTO `penggunasubfungsi` (`id_subfungsi`, `id_fungsi`, `nama_subfungsi`) VALUES
(1, 1, 'Programmer'),
(2, 1, 'Designer'),
(3, 1, 'Analyst'),
(4, 2, 'Sales'),
(5, 2, 'Presenter');

-- --------------------------------------------------------

--
-- Table structure for table `tanggallibur`
--

DROP TABLE IF EXISTS `tanggallibur`;
CREATE TABLE IF NOT EXISTS `tanggallibur` (
  `tanggal` date NOT NULL,
  `status` int NOT NULL,
  `ket` text,
  PRIMARY KEY (`tanggal`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `tanggallibur`
--

INSERT INTO `tanggallibur` (`tanggal`, `status`, `ket`) VALUES
('2022-01-01', 1, 'Tahun Baru Masehi 2018'),
('2022-02-16', 1, 'Tahun Baru Imlek 2569'),
('2022-03-17', 1, 'Hari Raya Nyepi'),
('2022-03-30', 1, 'Wafat Yesus Kristus'),
('2022-04-14', 1, 'Isra Miraj'),
('2022-05-01', 1, 'Hari Buruh Internasional'),
('2022-05-10', 1, 'Kenaikan Yesus Kristus'),
('2022-05-29', 1, 'Hari Waisak'),
('2022-06-01', 1, 'Hari Lahir Pancasila'),
('2022-06-13', 1, 'Cuti Hari Raya'),
('2022-06-14', 1, 'Cuti Hari Raya'),
('2022-06-15', 1, 'Cuti Hari Raya'),
('2022-06-16', 1, 'Cuti Hari Raya'),
('2022-06-17', 1, 'Cuti Hari Raya'),
('2022-06-18', 1, 'Cuti Hari Raya'),
('2022-06-19', 1, 'Cuti Hari Raya'),
('2022-08-17', 1, 'Hari Kemerdekaan'),
('2022-08-22', 1, 'Idul Adha'),
('2022-09-11', 1, 'Tahun Baru Hijriyah'),
('2022-11-20', 1, 'Maulid Nabi'),
('2022-12-24', 1, 'Cuti Natal'),
('2022-12-25', 1, 'Cuti Natal');

-- --------------------------------------------------------

--
-- Table structure for table `timkerja`
--

DROP TABLE IF EXISTS `timkerja`;
CREATE TABLE IF NOT EXISTS `timkerja` (
  `id_timkerja` int NOT NULL AUTO_INCREMENT,
  `tahun` int NOT NULL,
  `satker` int NOT NULL,
  `nama_timkerja` varchar(255) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_timkerja`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `timkerja`
--

INSERT INTO `timkerja` (`id_timkerja`, `tahun`, `satker`, `nama_timkerja`, `status`) VALUES
(1, 2023, 1000, 'Project Management Office (PMO)', 1),
(2, 2023, 1000, 'Programmer', 1);

-- --------------------------------------------------------

--
-- Table structure for table `timkerjamember`
--

DROP TABLE IF EXISTS `timkerjamember`;
CREATE TABLE IF NOT EXISTS `timkerjamember` (
  `id_timkerjamember` int NOT NULL AUTO_INCREMENT,
  `timkerja` int NOT NULL,
  `anggota` varchar(30) NOT NULL,
  `is_ketua` tinyint NOT NULL DEFAULT '0',
  `is_member` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_timkerjamember`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `timkerjamember`
--

INSERT INTO `timkerjamember` (`id_timkerjamember`, `timkerja`, `anggota`, `is_ketua`, `is_member`) VALUES
(1, 2, 'admin', 0, 1),
(2, 2, 'mrx', 1, 1),
(3, 2, 'mrsx', 0, 1),
(4, 1, 'admin', 1, 1),
(5, 1, 'mrx', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `timkerjaproject`
--

DROP TABLE IF EXISTS `timkerjaproject`;
CREATE TABLE IF NOT EXISTS `timkerjaproject` (
  `id_project` bigint NOT NULL AUTO_INCREMENT,
  `timkerja` int NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_description` text NOT NULL,
  `start_date` date NOT NULL,
  `finish_date` date NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_lastupdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_project`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `timkerjaproject`
--

INSERT INTO `timkerjaproject` (`id_project`, `timkerja`, `project_name`, `project_description`, `start_date`, `finish_date`, `timestamp`, `timestamp_lastupdated`) VALUES
(1, 2, 'SK-EJM', 'Code the App', '2023-07-01', '2023-12-31', '2022-09-28 08:13:12', '2022-09-28 08:13:12'),
(2, 1, 'Something', 'Doing something', '2023-11-01', '2023-12-31', '2022-09-28 08:13:12', '2022-09-28 08:13:12');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
