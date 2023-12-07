-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 12, 2022 at 02:44 AM
-- Server version: 8.0.27
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pacakplus`
--

-- --------------------------------------------------------

--
-- Table structure for table `ckp`
--

DROP TABLE IF EXISTS `ckp`;
CREATE TABLE IF NOT EXISTS `ckp` (
  `id_butirckp` bigint NOT NULL AUTO_INCREMENT,
  `pemilik` varchar(30) NOT NULL,
  `rujukanfungsional` bigint DEFAULT NULL,
  `tahun_ckp` year NOT NULL,
  `bulan_ckp` int NOT NULL,
  `satuankegiatan` int NOT NULL,
  `jumlah_target` int NOT NULL,
  `jumlah_realisasi` int DEFAULT NULL,
  `persentase` int DEFAULT '100',
  `kualitas` int DEFAULT '100',
  `jenis_keg` tinyint NOT NULL DEFAULT '1',
  `rincian_keg` varchar(255) NOT NULL,
  `is_target` tinyint DEFAULT '1',
  `is_revisi` tinyint NOT NULL DEFAULT '0',
  `is_realisasi` tinyint DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_lastupdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_butirckp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ckpbulan`
--

DROP TABLE IF EXISTS `ckpbulan`;
CREATE TABLE IF NOT EXISTS `ckpbulan` (
  `kode_bulan` int NOT NULL,
  `nama_bulan` varchar(255) NOT NULL,
  PRIMARY KEY (`kode_bulan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ckpnilai`
--

DROP TABLE IF EXISTS `ckpnilai`;
CREATE TABLE IF NOT EXISTS `ckpnilai` (
  `id_ckpnilai` bigint NOT NULL AUTO_INCREMENT,
  `pegawai` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tahun` year NOT NULL,
  `bulan` int NOT NULL,
  `delagasi_penilai` varchar(30) NOT NULL,
  `skor` float NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ckpnilai`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ckpsatuankegiatan`
--

DROP TABLE IF EXISTS `ckpsatuankegiatan`;
CREATE TABLE IF NOT EXISTS `ckpsatuankegiatan` (
  `id_satuan` int NOT NULL AUTO_INCREMENT,
  `nama_satuan` varchar(255) NOT NULL,
  PRIMARY KEY (`id_satuan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dailypresencestatus`
--

DROP TABLE IF EXISTS `dailypresencestatus`;
CREATE TABLE IF NOT EXISTS `dailypresencestatus` (
  `id_dailypresencestatus` int NOT NULL AUTO_INCREMENT,
  `keterangan_presensi` varchar(255) NOT NULL,
  PRIMARY KEY (`id_dailypresencestatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dailyreport`
--

DROP TABLE IF EXISTS `dailyreport`;
CREATE TABLE IF NOT EXISTS `dailyreport` (
  `id_keg` bigint NOT NULL AUTO_INCREMENT,
  `id_ckp` bigint DEFAULT NULL,
  `owner` varchar(30) NOT NULL,
  `lintas_tim` tinyint NOT NULL DEFAULT '0',
  `is_izinlintastim` tinyint DEFAULT NULL,
  `assigned_to` varchar(30) DEFAULT NULL,
  `timkerjaproject` bigint DEFAULT NULL,
  `is_setujuketuatim` tinyint DEFAULT NULL,
  `rincian_report` text NOT NULL,
  `status_selesai` tinyint NOT NULL DEFAULT '1',
  `tanggal_kerja` date NOT NULL,
  `eomusulan` int DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_lastupdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `priority` tinyint NOT NULL DEFAULT '0',
  `ket` text,
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_keg`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `eombulanan`
--

DROP TABLE IF EXISTS `eombulanan`;
CREATE TABLE IF NOT EXISTS `eombulanan` (
  `id_eombulanan` bigint NOT NULL AUTO_INCREMENT,
  `tahun` int NOT NULL,
  `bulan` int NOT NULL,
  `satker` int NOT NULL,
  `pegawai` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ranking_sistem` int NOT NULL,
  `ranking_voting` int DEFAULT NULL,
  `satu_persen` float DEFAULT NULL,
  `dua_persen` float DEFAULT NULL,
  `tiga_persen` float DEFAULT NULL,
  `empat_persen` float DEFAULT NULL,
  `lima_persen` float DEFAULT NULL,
  `enam_persen` float DEFAULT NULL,
  `pilihan_pimpinan` int DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_lastupdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_eombulanan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eombulananvoting`
--

DROP TABLE IF EXISTS `eombulananvoting`;
CREATE TABLE IF NOT EXISTS `eombulananvoting` (
  `id_eombulananvoting` bigint NOT NULL AUTO_INCREMENT,
  `eombulanan` bigint NOT NULL,
  `voter` varchar(30) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timestamp_lastupdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_eombulananvoting`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eommaster`
--

DROP TABLE IF EXISTS `eommaster`;
CREATE TABLE IF NOT EXISTS `eommaster` (
  `id_eommaster` int NOT NULL,
  `nama_eommaster` varchar(255) NOT NULL,
  `definisi_eommaster` text NOT NULL,
  PRIMARY KEY (`id_eommaster`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kamusboolean`
--

DROP TABLE IF EXISTS `kamusboolean`;
CREATE TABLE IF NOT EXISTS `kamusboolean` (
  `id_status` int NOT NULL,
  `artinya` varchar(255) NOT NULL,
  PRIMARY KEY (`id_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

DROP TABLE IF EXISTS `level`;
CREATE TABLE IF NOT EXISTS `level` (
  `id_level` int NOT NULL AUTO_INCREMENT,
  `nama_level` varchar(255) NOT NULL,
  PRIMARY KEY (`id_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penggunafungsi`
--

DROP TABLE IF EXISTS `penggunafungsi`;
CREATE TABLE IF NOT EXISTS `penggunafungsi` (
  `id_fungsi` int NOT NULL AUTO_INCREMENT,
  `nama_fungsi` varchar(255) NOT NULL,
  PRIMARY KEY (`id_fungsi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `penggunajabatan`
--

DROP TABLE IF EXISTS `penggunajabatan`;
CREATE TABLE IF NOT EXISTS `penggunajabatan` (
  `id_jabatan` bigint NOT NULL AUTO_INCREMENT,
  `nama_jabatan` text NOT NULL,
  PRIMARY KEY (`id_jabatan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `penggunapangkatgol`
--

DROP TABLE IF EXISTS `penggunapangkatgol`;
CREATE TABLE IF NOT EXISTS `penggunapangkatgol` (
  `id_pangkatgol` bigint NOT NULL AUTO_INCREMENT,
  `nama_pangkatgol` varchar(255) NOT NULL,
  PRIMARY KEY (`id_pangkatgol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penggunasatker`
--

DROP TABLE IF EXISTS `penggunasatker`;
CREATE TABLE IF NOT EXISTS `penggunasatker` (
  `id_satker` int NOT NULL AUTO_INCREMENT,
  `nama_satker` varchar(255) NOT NULL,
  PRIMARY KEY (`id_satker`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rujukanfungsional`
--

DROP TABLE IF EXISTS `rujukanfungsional`;
CREATE TABLE IF NOT EXISTS `rujukanfungsional` (
  `id_rujukanfungsional` bigint NOT NULL AUTO_INCREMENT,
  `jenis_fungsional` int NOT NULL,
  `tingkatan` int NOT NULL,
  `jenjang` int NOT NULL,
  `unsur_id` varchar(30) NOT NULL,
  `unsur_nama` text NOT NULL,
  `subunsur_id` varchar(30) NOT NULL,
  `subunsur_nama` text NOT NULL,
  `butir_id` varchar(30) NOT NULL,
  `butir_nama` text NOT NULL,
  `deskripsi` text,
  `subbutir_id` varchar(30) DEFAULT NULL,
  `subbutir_nama` text,
  `satuan_hasil` text NOT NULL,
  `ak` double NOT NULL,
  `batas_penilaian` text NOT NULL,
  `bukti_fisik` text NOT NULL,
  PRIMARY KEY (`id_rujukanfungsional`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rujukanfungsionaljenis`
--

DROP TABLE IF EXISTS `rujukanfungsionaljenis`;
CREATE TABLE IF NOT EXISTS `rujukanfungsionaljenis` (
  `id_jenisfungsional` int NOT NULL,
  `nama_jenisfungsional` varchar(255) NOT NULL,
  PRIMARY KEY (`id_jenisfungsional`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rujukanfungsionaljenjang`
--

DROP TABLE IF EXISTS `rujukanfungsionaljenjang`;
CREATE TABLE IF NOT EXISTS `rujukanfungsionaljenjang` (
  `kode_jenjang` int NOT NULL,
  `nama_jenjang` varchar(255) NOT NULL,
  PRIMARY KEY (`kode_jenjang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rujukanfungsionaltingkatan`
--

DROP TABLE IF EXISTS `rujukanfungsionaltingkatan`;
CREATE TABLE IF NOT EXISTS `rujukanfungsionaltingkatan` (
  `kode_tingkatan` int NOT NULL,
  `nama_tingkatan` varchar(255) NOT NULL,
  PRIMARY KEY (`kode_tingkatan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
