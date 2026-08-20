-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2026 at 10:56 AM
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
-- Database: `gateway_it_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cctv_inventory`
--

CREATE TABLE `cctv_inventory` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `source_file` varchar(255) DEFAULT NULL,
  `source_sheet` varchar(100) DEFAULT NULL,
  `source_row` int(10) UNSIGNED DEFAULT NULL,
  `source_id` int(10) UNSIGNED DEFAULT NULL,
  `branch` varchar(150) NOT NULL,
  `region` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `business_unit` varchar(120) DEFAULT NULL,
  `assigned_tech` varchar(150) DEFAULT NULL,
  `total_cameras` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `online_cameras` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `offline_cameras` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `recording_issue_cameras` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `nvr_status` varchar(100) DEFAULT NULL,
  `storage_status` varchar(100) DEFAULT NULL,
  `storage_used_gb` decimal(12,2) DEFAULT NULL,
  `recording_days` varchar(100) DEFAULT NULL,
  `vendor` varchar(150) DEFAULT NULL,
  `nvr_brand` varchar(120) DEFAULT NULL,
  `nvr_model` varchar(150) DEFAULT NULL,
  `nvr_rlp` varchar(150) DEFAULT NULL,
  `nvr_hdd_capacity` varchar(100) DEFAULT NULL,
  `nvr_hdd_capacity_gb` decimal(12,2) DEFAULT NULL,
  `distribution_status` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `distribution_summary` text DEFAULT NULL,
  `imported_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cctv_inventory`
--

INSERT INTO `cctv_inventory` (`id`, `source_file`, `source_sheet`, `source_row`, `source_id`, `branch`, `region`, `province`, `business_unit`, `assigned_tech`, `total_cameras`, `online_cameras`, `offline_cameras`, `recording_issue_cameras`, `nvr_status`, `storage_status`, `storage_used_gb`, `recording_days`, `vendor`, `nvr_brand`, `nvr_model`, `nvr_rlp`, `nvr_hdd_capacity`, `nvr_hdd_capacity_gb`, `distribution_status`, `remarks`, `distribution_summary`, `imported_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 2, 1, 'MAKATI', 'NCR', 'Makati', 'HONDA', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(2, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 3, 2, 'MAKATI', 'NCR', 'Makati', 'HYUNDAI', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(3, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 4, 3, 'PASONG TAMO', 'NCR', 'Makati', 'SUZUKI', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(4, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 5, 4, 'OTIS', 'NCR', 'Paco', 'KIA', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(5, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 6, 5, 'FAIRVIEW', 'NCR', 'Quezon City', 'HONDA', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(6, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 7, 6, 'FAIRVIEW', 'NCR', 'Quezon City', 'MITSUBISHI', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(7, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 8, 7, 'QUEZON AVENUE', 'NCR', 'Quezon City', 'MITSUBISHI', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(8, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 9, 8, 'MARCOS HIGHWAY', 'NCR', 'Quezon City', 'HONDA', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(9, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 10, 9, 'CAINTA', 'Region IV-A', 'Rizal', 'HONDA', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(10, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 11, 10, 'CAINTA', 'Region IV-A', 'Rizal', 'GEELY', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(11, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 12, 11, 'PASIG', 'NCR', 'Pasig', 'MITSUBISHI', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(12, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 13, 12, 'SUCAT', 'NCR', 'Muntinlupa', 'MITSUBISHI', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(13, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 14, 13, 'ALABANG', 'NCR', 'Muntinlupa', 'HONDA', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(14, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 15, 14, 'LAS PINAS', 'NCR', 'Las Pinas', 'MG', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(15, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 16, 15, 'BACOOR (old Nissan)', 'Region IV-A', 'Cavite', 'CHANGAN', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(16, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 17, 16, 'DASMARINAS', 'Region IV-A', 'Cavite', 'KIA', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(17, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 18, 17, 'MARILAO', 'Region III', 'Bulacan', 'MG', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(18, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 19, 18, 'ANGELES', 'Region III', 'Pampanga', 'MG/GEELY', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(19, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 20, 19, 'TARLAC', 'Region III', 'Tarlac', 'GEELY', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(20, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 21, 20, 'ISABELA', 'Region II', 'Isabela', 'HONDA', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(21, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 22, 21, 'SANTA ROSA', 'Region IV-A', 'Laguna', 'SUZUKI', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(22, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 23, 22, 'CALAMBA', 'Region IV-A', 'Laguna', 'MITSUBISHI', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(23, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 24, 23, 'LIPA', 'Region IV-A', 'Batangas', 'GEELY', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(24, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 25, 24, 'ALAMINOS', 'Region I', 'Pangasinan', 'SUZUKI', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(25, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 26, 25, 'SAN PABLO', 'Region IV-A', 'Laguna', 'KIA', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(26, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 27, 26, 'SAN PABLO', 'Region IV-A', 'Laguna', 'MG', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(27, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 28, 27, 'PILI', 'Region V', 'Camarines Sur', 'MITSUBISHI', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(28, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 29, 28, 'LEGAZPI', 'Region V', 'Albay', 'MITSUBISHI', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(29, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 30, 29, 'GREENHILLS', 'NCR', 'San Juan', 'MITSUBISHI', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(30, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'NCR', 31, 30, 'MANILA BAY', 'NCR', 'Manila', 'HONDA', 'JHUN / ARNEL / JUNELL', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(31, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 2, 1, 'Mandaue', 'Visayas', 'Cebu', 'HYUNDAI', 'Richard/Rodel', 15, 14, 1, 1, 'Good', 'Full/Overwrite', NULL, '30', 'Fortune Builders Contractor', 'Hikvision', 'DS-7616NI-K2/16P', NULL, '12TB', 12288.00, NULL, 'Sales Doc./LTO camera was offline. Need cable checking.', 'Showroom: 3 • Workshop: 3 • Parking Area: 1 • Cashier: 1 • Parts Warehouse: 2 • Customer Lounge: 1 • Sales Doc./LTO: 1 • Parts Office: 1 • Service Entrance: 1 • Second floor/Veranda: 1', '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(32, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 3, 2, 'Mandaue', 'Visayas', 'Cebu', 'KIA / GEELY', 'Richard/Rodel', 10, 8, 2, 2, 'Good', 'Full/Overwrite', NULL, '15', 'Fortune Builders Contractor', 'Hikvision', 'DS-7616NI-Q2/16P', NULL, '2TB', 2048.00, NULL, 'Two camera in service entrance no LAN cable setup. Need to setup LAN cable.', 'Geely Showroom: 1 • Geely Sales Reception: 1 • Accounting Admin Office: 1 • KIA Showroom: 1 • KIA Sales Reception: 1 • KIA Service Reception: 1 • Cashier: 2 • Service Entrance: 2', '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(33, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 4, 3, 'Mandaue', 'Visayas', 'Cebu', 'MERCEDES-BENZ', 'Richard/Rodel', 16, 16, 0, 0, 'Good', 'Full/Overwrite', NULL, '28', 'Fortune Builders Contractor', 'Hikvision', 'DS-7732NXI-K4 E', NULL, '16TB', 16384.00, NULL, 'All cameras recording normally.', 'Showroom: 4 • Workshop: 2 • Parking Area: 1 • Cashier: 1 • Parts Warehouse: 2 • Hand Over Area: 1 • Parts Counter: 1 • Service Entrance: 1 • Service Driveway: 1 • Service Office: 1 • Conference Room: 1', '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(34, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 5, 4, 'Cebu City', 'Visayas', 'Cebu', 'OMODA & JAECOO', 'Richard/Rodel', 16, 5, 11, 11, 'Good', 'Full/Overwrite', NULL, '30', 'Fortune Builders Contractor', 'Z-BEN', 'ZB-N4000', NULL, '4TB', 4096.00, NULL, '11 Camera not working, damage by rain water on typhone Odette', 'Showroom: 2 • Workshop: 2 • BRP Workshop: 2 • Parking Area: 2 • Cashier: 1 • Parts Warehouse: 1 • Stockyard: 2 • Service Office: 1 • Conference Room: 1 • Customer Lounge: 1 • Service Reception: 1', '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(35, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 6, 5, 'Talisay', 'Visayas', 'Cebu', 'MITSUBISHI', 'Richard/Rodel', 22, 21, 1, 1, 'Good', 'Full/Overwrite', NULL, '25', 'Fortune Builders Contractor', 'Hikvision', 'DS-7732NXI-K4', NULL, '8TB', 8192.00, NULL, 'Service entrance camera was offline. Need cable checking', 'Showroom: 3 • Service Entrance: 1 • Workshop: 1 • Front/Parking Area: 1 • Inside/Parking Area: 2 •Service Receiving Area: 1 • Cashier: 1 • Server Room: 1 • Training Room: 1 • Admin Office: 1 • Conference Room: 1 • Customer Lounge: 1 • Service Office: 1 • Service Reception: 1 • Service Customer Waiting Area: 1 • Sales Manager\'s Office: 1 • Play Room: 1 • Storage Room: 1 • Inside/Hallway: 1', '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(36, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 7, 6, 'Talisay', 'Visayas', 'Cebu', 'HONDA', 'Richard/Rodel', 16, 12, 4, 4, 'Good', 'Full/Overwrite', NULL, '25', 'Fortune Builders Contractor', 'Hikvision', 'DS-7732NXI-K4/16P', NULL, '8TB', 8192.00, NULL, 'Parts warehouse, Parts Delivery area, one in Workshop & one in Showroom camera were offline. Need cable checking.', 'Showroom: 2 • Showroom Customer Lounge: 1 • Workshop: 3• Parking Area: 2 • Cashier: 2 • Parts Warehouse: 1 • Parts Receiving Area: 1 • Service Reception: 1 • Service Reception Lobby: 2 • Service Driveway: 1', '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(37, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 8, 7, 'Talisay', 'Visayas', 'Cebu', 'GEELY', 'Richard/Rodel', 15, 15, 0, 0, 'Good', 'Full/Overwrite', NULL, '15', 'Dynamic IT Solutions', 'Hikvision', 'DS-7616NI-K2/16P', NULL, '4TB', 4096.00, NULL, 'All cameras recording normally.', 'Showroom: 2 • Workshop: 4 • Parking Area: 1 • Cashier: 2 • Parts Counter: 1 • Service Reception: 1 • Guard House: 1 • Conference room: 1 • Conference Entrance: 1 • Sales Office: 1', '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(38, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 9, NULL, 'Talisay', 'Visayas', 'Cebu', NULL, 'Richard/Rodel', 12, 12, 0, 0, 'Good / Replaced HDD', 'Full/Overwrite', NULL, '15', 'Unknown | Old CCTV', 'Hikvision', 'DS-7216HQHI-F2/N', NULL, '2TB', 2048.00, NULL, 'All cameras recording normally.', 'Showroom: 2 • Workshop: 3 • Service Entrance: 1 • Parts warehouse: 2 • Parts Entrance: 1 • Service Reception: 1 • Guard House: 1 • Conference room: 1', '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(39, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 10, 8, 'Gorordo', 'Visayas', 'Cebu', 'MITSUBISHI', 'Richard/Rodel', 12, 8, 4, 4, 'Good', 'Full/Overwrite', NULL, '15', 'Fortune Builders Contractor', 'Hikvision', 'DS-7632NI-K2', NULL, '4TB', 4096.00, NULL, 'Mitsubishi: three cameras in workshop & one in service office were offline. Need cable checking and the camera itself. Camera in service office was wet by rain water.', 'Showroom: 3 • Workshop: 3 • Cashier: 1 • Parts Warehouse: 2 • Service Entrance: 1 • Service Office: 1 • Service Reception: 1', '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(40, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 11, 9, 'Gorordo', 'Visayas', 'Cebu', 'MG', 'Richard/Rodel', 3, 1, 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'MG: two cameras were offline, neeed cable checking.', 'Showroom: 3', '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(41, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 12, 10, 'Gorordo', 'Visayas', 'Cebu', 'KIA', 'Richard/Rodel', 2, 2, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'KIA: All cameras recording normally.', 'Showroom: 2', '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(42, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 13, 11, 'Gorordo', 'Visayas', 'Cebu', 'GEELY', 'Richard/Rodel', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(43, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 14, 12, 'Gorordo', 'Visayas', 'Cebu', 'JETOUR', 'Richard/Rodel', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(44, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 15, 13, 'NRA', 'Visayas', 'Cebu', 'MERCEDES-BENZ', 'Richard/Rodel', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(45, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 16, 14, 'NRA', 'Visayas', 'Cebu', 'GEELY', 'Richard/Rodel', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(46, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 17, 15, 'Cebu', 'Visayas', 'Cebu', 'HONDA', 'Richard/Rodel', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(47, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 18, 16, 'Talisay', 'Visayas', 'Cebu', 'KIA', 'Richard/Rodel', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(48, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 19, 17, 'Bohol', 'Visayas', 'Cebu', 'MG', 'Richard/Rodel', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(49, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 20, 18, 'Bacolod', 'Visayas', 'Cebu', 'MG', 'Richard/Rodel', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(50, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Visayas', 21, 19, 'Mandaue', 'Visayas', 'Cebu', 'MARKANE MANUFACTURING', 'Richard/Rodel', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(51, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 2, 1, 'MATINA', 'Region XI', 'Davao del sur', 'Mitsubishi', 'Mc Rodel/Ian Iverson Suezo', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(52, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 3, 2, 'MATINA', 'Region XI', 'Davao del sur', 'Hyundai', 'Mc Rodel/Ian Iverson Suezo', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(53, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 4, 3, 'MATINA', 'Region XI', 'Davao del sur', 'Jaeco & Omoda', 'Mc Rodel/Ian Iverson Suezo', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(54, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 5, 4, 'BUHANGIN', 'Region XI', 'Davao del sur', 'Mitsubishi', 'Mc Rodel/Ian Iverson Suezo', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(55, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 6, 5, 'LANANG', 'Region XI', 'Davao del sur', 'BRP', 'Mc Rodel/Ian Iverson Suezo', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(56, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 7, 6, 'DIGOS', 'Region XI', 'Davao del sur', 'Mitsubishi', 'Mc Rodel/Ian Iverson Suezo', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(57, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 8, 7, 'KIDAPAWAN', 'Region XII', 'Cotabato', 'Mitsubishi', 'Mc Rodel/Ian Iverson Suezo', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(58, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 9, 8, 'COTABATO CITY', 'Region XI', 'Cotabato', 'Mitsubishi', 'Mc Rodel/Ian Iverson Suezo', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(59, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 10, 9, 'TAGUM', 'Region XI', 'Davao del norte', 'Geely', 'JUNI IT', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(60, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 11, 10, 'PANABO', 'Region XI', 'Davao del norte', 'Mitsubishi', 'JUNI IT', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(61, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 12, 11, 'SAN FRANCISCO', 'Region XIII', 'Agusan del Sur', 'Mitsubishi', 'JUNI IT', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(62, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 13, 12, 'NEGROS', 'NIR', 'Negros Occidental', 'Mitsubishi', 'REYMARK PABALINAS', 10, 7, 3, 3, 'Good', 'Full/Overwrite', NULL, '30 Days', 'Wireless Link', 'HIKVision', 'DS-7616NI-K2/16P', NULL, '4TB', 4096.00, NULL, '3 cameras offline need cable checking', 'Showroom: 2 • Workshop: 2 • Cashier: 1 • Parts Warehouse: 2 • Service Entrance: 1 • Service Office: 1 • Service Reception: 1', '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(63, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 14, 13, 'BACOLOD', 'NIR', 'Negros Occidental', 'Honda', 'REYMARK PABALINAS', 0, 0, 0, 0, 'currently requesting DVR only', NULL, NULL, NULL, 'unknown/old cctv', NULL, NULL, NULL, NULL, NULL, NULL, 'camera is working', NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(64, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 15, 14, 'CAGAYAN DE ORO', 'Region X', 'Misamis Oriental', 'KIA', 'BAM DINGCONG/JUNELL DOSDOS', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(65, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 16, 15, 'CAGAYAN DE ORO', 'Region X', 'Misamis Oriental', 'Jaeco & Omoda', 'BAM DINGCONG/JUNELL DOSDOS', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(66, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 17, 16, 'CAGAYAN DE ORO', 'Region X', 'Misamis Oriental', 'Honda', 'BAM DINGCONG/JUNELL DOSDOS', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(67, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 18, 17, 'BUTUAN', 'Region III', 'Agusan del Sur', 'MG', 'BAM DINGCONG/JUNELL DOSDOS', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(68, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 19, 18, 'VALENCIA', 'Region X', 'Bukidnon', 'Mitsubishi', 'BAM DINGCONG/JUNELL DOSDOS', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(69, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 20, 19, 'ILIGIAN', 'Region X', 'Lanao del Norte', 'KIA', 'BAM DINGCONG/JUNELL DOSDOS', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL),
(70, 'Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx', 'Minadanao', 21, 20, 'DIPOLOG', 'Region IX', 'Zamboanga del Norte', 'Mitsubishi', 'BAM DINGCONG/JUNELL DOSDOS', 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-19 23:27:40', '2026-08-19 23:27:40', '2026-08-19 23:27:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cctv_sites`
--

CREATE TABLE `cctv_sites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch` varchar(150) NOT NULL,
  `region` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `business_unit` varchar(120) DEFAULT NULL,
  `assigned_tech` varchar(150) DEFAULT NULL,
  `total_cameras` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `online_cameras` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `offline_cameras` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `recording_issue_cameras` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `nvr_status` varchar(30) NOT NULL DEFAULT 'Unknown',
  `storage_used_gb` decimal(12,2) DEFAULT NULL,
  `vendor` varchar(120) DEFAULT NULL,
  `nvr_brand` varchar(120) DEFAULT NULL,
  `nvr_model` varchar(120) DEFAULT NULL,
  `nvr_hdd_capacity_gb` decimal(12,2) DEFAULT NULL,
  `distribution_status` varchar(30) NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `distribution_summary` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cctv_sites`
--

INSERT INTO `cctv_sites` (`id`, `branch`, `region`, `province`, `business_unit`, `assigned_tech`, `total_cameras`, `online_cameras`, `offline_cameras`, `recording_issue_cameras`, `nvr_status`, `storage_used_gb`, `vendor`, `nvr_brand`, `nvr_model`, `nvr_hdd_capacity_gb`, `distribution_status`, `remarks`, `distribution_summary`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Gateway Cubao', 'NCR', 'Metro Manila', 'Retail', 'Mark Santos', 24, 23, 1, 0, 'degraded', 3120.00, 'SecureTech Solutions', 'Hikvision', 'DS-7732NI-I4', 4096.00, 'complete', 'Camera 18 is scheduled for cable replacement.', 'Main floor, loading bay, stockroom, and exterior perimeter.', '2026-08-19 23:10:10', '2026-08-19 23:10:10', NULL),
(2, 'Gateway Makati', 'NCR', 'Metro Manila', 'Corporate', 'Anna Reyes', 32, 32, 0, 0, 'operational', 4680.00, 'Vision Systems PH', 'Dahua', 'NVR5432-4KS2', 8192.00, 'complete', 'Preventive maintenance completed this month.', 'Reception, office floors, parking, server room, and entrances.', '2026-08-19 23:10:10', '2026-08-19 23:10:10', NULL),
(3, 'Gateway Calamba', 'Region IV-A', 'Laguna', 'Distribution', 'Paolo Mendoza', 28, 26, 0, 2, 'degraded', 7220.00, 'Prime Surveillance', 'Uniview', 'NVR308-32E-B', 8192.00, 'partial', 'Two warehouse cameras have intermittent recording gaps.', 'Warehouse aisles A-D complete; dispatch annex expansion pending.', '2026-08-19 23:10:10', '2026-08-19 23:10:10', NULL),
(4, 'Gateway Cebu IT Park', 'Region VII', 'Cebu', 'Retail', 'Carlo Lim', 20, 20, 0, 0, 'operational', 1975.00, 'SecureTech Solutions', 'Hikvision', 'DS-7624NI-K2', 4096.00, 'complete', NULL, 'Sales floor, stockroom, cash lanes, entrances, and delivery bay.', '2026-08-19 23:10:10', '2026-08-19 23:10:10', NULL),
(5, 'Gateway Davao Hub', 'Region XI', 'Davao del Sur', 'Logistics', 'Mia Villanueva', 36, 33, 2, 1, 'maintenance', 10450.00, 'Mindanao Security Systems', 'Dahua', 'NVR608-64-4KS2', 12288.00, 'partial', 'NVR firmware update and camera replacements are scheduled.', 'Sorting floor and loading docks complete; yard coverage in progress.', '2026-08-19 23:10:10', '2026-08-19 23:10:10', NULL),
(6, 'Gateway Bulacan DC', 'Region III', 'Bulacan', 'Distribution', 'John Dela Cruz', 40, 40, 0, 0, 'operational', 6340.00, 'Vision Systems PH', 'Hikvision', 'DS-9664NI-I8', 12288.00, 'complete', 'System operating within normal thresholds.', 'Full warehouse, cold storage, dispatch, and perimeter coverage.', '2026-08-19 23:10:10', '2026-08-19 23:10:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_20_000000_create_cctv_sites_table', 1),
(5, '2026_08_20_000001_add_is_admin_to_users_table', 1),
(6, '2026_08_20_000002_create_cctv_inventory_table', 2),
(7, '2026_08_20_000003_ensure_an_admin_user_exists', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('P9cJILbUUr6uvSo0ZsyiEIJCTMdVkm2zmw0pEvQz', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoib1J1NHR1Q2Z1Wk5MMmZCV2ZpVGo1ZWFzcDFsdW1qZm9IWWdqUGR0VCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91c2VycyI7czo1OiJyb3V0ZSI7czoxMToidXNlcnMuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1787215794);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `is_admin`) VALUES
(1, 'Test User', 'test@example.com', NULL, '$2y$12$LamISnv6QOSbENOQKucn/u4x0Qn5OK0avCqvlEZyJhtxxH66Nk4Ly', NULL, '2026-08-19 23:10:10', '2026-08-19 23:10:10', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `cctv_inventory`
--
ALTER TABLE `cctv_inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cctv_inventory_source_sheet_source_row_unique` (`source_sheet`,`source_row`),
  ADD KEY `cctv_inventory_region_province_index` (`region`,`province`),
  ADD KEY `cctv_inventory_branch_index` (`branch`),
  ADD KEY `cctv_inventory_region_index` (`region`),
  ADD KEY `cctv_inventory_province_index` (`province`),
  ADD KEY `cctv_inventory_business_unit_index` (`business_unit`),
  ADD KEY `cctv_inventory_nvr_status_index` (`nvr_status`);

--
-- Indexes for table `cctv_sites`
--
ALTER TABLE `cctv_sites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cctv_sites_region_province_index` (`region`,`province`),
  ADD KEY `cctv_sites_branch_index` (`branch`),
  ADD KEY `cctv_sites_region_index` (`region`),
  ADD KEY `cctv_sites_province_index` (`province`),
  ADD KEY `cctv_sites_nvr_status_index` (`nvr_status`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cctv_inventory`
--
ALTER TABLE `cctv_inventory`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `cctv_sites`
--
ALTER TABLE `cctv_sites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
