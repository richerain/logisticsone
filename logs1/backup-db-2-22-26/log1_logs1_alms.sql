-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 22, 2026 at 11:53 AM
-- Server version: 10.11.14-MariaDB-ubu2204
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `log1_logs1_alms`
--

-- --------------------------------------------------------

--
-- Table structure for table `almns_repair_personnel`
--

CREATE TABLE `almns_repair_personnel` (
  `id` int(10) UNSIGNED NOT NULL,
  `rep_id` varchar(20) DEFAULT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `almns_repair_personnel`
--

INSERT INTO `almns_repair_personnel` (`id`, `rep_id`, `firstname`, `middlename`, `lastname`, `position`, `status`) VALUES
(1, 'EMP-006', 'Patricia', NULL, 'Lim', 'Technician', 'inactive'),
(2, 'EMP-020', 'Eleanor', NULL, 'Gomez', 'Cleaning Staff', 'active'),
(3, 'EMP-022', 'Ricardo  Tavara', NULL, 'Jr', 'Mechanic', 'active'),
(4, 'EMP-023', 'Adrian', NULL, 'Navarro', 'Mechanic', 'active'),
(5, 'EMP-024', 'Brent', NULL, 'Castillo', 'Mechanic', 'active'),
(6, 'EMP-025', 'Cedric', NULL, 'Morales', 'Mechanic', 'active'),
(7, 'EMP-026', 'Darren', NULL, 'Salonga', 'Mechanic', 'active'),
(8, 'EMP-027', 'Elmer', NULL, 'Robles', 'Mechanic', 'active'),
(9, 'EMP-028', 'Francis', NULL, 'Evangelista', 'Technician', 'active'),
(10, 'EMP-029', 'Gino', NULL, 'Alvarado', 'Technician', 'active'),
(11, 'EMP-030', 'Harold', NULL, 'Simeon', 'Technician', 'active'),
(12, 'EMP-031', 'Ivan', NULL, 'Dominguez', 'Technician', 'active'),
(13, 'EMP-032', 'Jasper', NULL, 'Villareal', 'Technician', 'active'),
(14, 'EMP-033', 'Karen', NULL, 'Abad', 'Cleaning Staff', 'active'),
(15, 'EMP-034', 'Loraine', NULL, 'Matias', 'Cleaning Staff', 'active'),
(16, 'EMP-035', 'Megan', NULL, 'Soriano', 'Cleaning Staff', 'active'),
(17, 'EMP-036', 'Nina', NULL, 'Baldonado', 'Cleaning Staff', 'active'),
(18, 'EMP-037', 'Olivia', NULL, 'Serrano', 'Cleaning Staff', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `alms_assets`
--

CREATE TABLE `alms_assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `asset_code` varchar(20) NOT NULL,
  `asset_name` varchar(255) NOT NULL,
  `asset_category` varchar(100) DEFAULT NULL,
  `asset_location` varchar(100) DEFAULT NULL,
  `asset_status` enum('operational','under_maintenance','out_of_service','in_storage') NOT NULL DEFAULT 'operational',
  `last_maintenance` date DEFAULT NULL,
  `next_maintenance` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alms_assets`
--

INSERT INTO `alms_assets` (`id`, `asset_code`, `asset_name`, `asset_category`, `asset_location`, `asset_status`, `last_maintenance`, `next_maintenance`, `created_at`, `updated_at`) VALUES
(1, 'AST20260208FZITC', 'HBW Office Vinyl Coated Paper Clip 50mm', 'Supplies', 'Main Warehouse', 'operational', '2026-02-08', '2026-02-08', '2026-02-08 04:05:48', '2026-02-08 04:05:48'),
(2, 'AST20260208QF4SI', 'Toyota Hiace - ABC-1234', 'Vehicle', 'Garage', 'operational', '2024-08-15', NULL, '2026-02-08 04:05:48', '2026-02-08 04:05:48'),
(3, 'AST20260208OSPYF', 'Honda CB500 - HON-500', 'Vehicle', 'Garage', 'out_of_service', '2024-07-20', NULL, '2026-02-08 04:05:48', '2026-02-08 04:05:48'),
(4, 'AST20260208ZTJML', 'Isuzu NPR - ISZ-2021', 'Vehicle', 'Garage', 'operational', '2024-09-01', NULL, '2026-02-08 04:05:48', '2026-02-08 04:05:48'),
(5, 'AST20260208IYGVE', 'Mitsubishi Fuso - MIF-2020', 'Vehicle', 'Garage', 'under_maintenance', '2024-06-10', NULL, '2026-02-08 04:05:48', '2026-02-08 04:05:48'),
(6, 'AST20260208SP1VR', 'Suzuki Carry - SUZ-2022', 'Vehicle', 'Garage', 'operational', '2024-08-25', NULL, '2026-02-08 04:05:48', '2026-02-08 04:05:48'),
(7, 'AST20260208L3GOV', 'Toyota Fortuner - TOY-2019', 'Vehicle', 'Garage', 'out_of_service', '2024-05-15', NULL, '2026-02-08 04:05:48', '2026-02-08 04:05:48'),
(8, 'AST202602098XQ2U', 'Ballpoint Pen (12pcs per pack)', 'Supplies', 'Main Warehouse', 'operational', '2026-02-09', '2026-02-09', '2026-02-08 19:18:32', '2026-02-08 19:18:32'),
(9, 'AST20260209DTDZZ', 'Bondpaper size-A4 (100-pcs bundle)', 'Supplies', 'Main Warehouse', 'operational', '2026-02-09', '2026-02-09', '2026-02-08 19:18:32', '2026-02-08 19:18:32'),
(10, 'AST20260209NXAPA', 'Joy Stapler Promo Bundle 2s', 'Supplies', 'Main Warehouse', 'operational', '2026-02-09', '2026-02-09', '2026-02-08 19:18:32', '2026-02-08 19:18:32'),
(13, 'AST20260214SSE5C', 'Unknown - TEMP-001', 'Vehicle', 'Garage', 'operational', '2026-02-14', NULL, '2026-02-14 13:44:35', '2026-02-14 13:44:35'),
(14, 'AST20260214VIRB1', 'Filing Cabinet', 'Furniture', 'Main Warehouse', 'operational', '2026-02-14', '2026-02-14', '2026-02-14 15:16:33', '2026-02-14 15:16:33'),
(15, 'AST20260214O1F6V', 'Mitsubishi Fuso Canter Cruise (Minibus)', 'Automotive', 'Main Warehouse', 'operational', '2026-02-14', '2026-02-14', '2026-02-14 15:16:33', '2026-02-14 15:16:33'),
(16, 'AST20260214UAEIP', 'Canon Pixma G3010 All-in-One Ink Tank Printer (Wi-Fi Connectivity, 2315C018AB, Black)', 'Equipment', 'Main Warehouse', 'operational', '2026-02-14', '2026-02-14', '2026-02-14 15:42:58', '2026-02-14 15:42:58'),
(17, 'AST20260214NOYIR', 'ThinkPad E16 AMD G2', 'Equipment', 'Main Warehouse', 'operational', '2026-02-14', '2026-02-14', '2026-02-14 15:42:58', '2026-02-14 15:42:58'),
(18, 'AST20260214KWWSJ', 'Honda CB500', 'Automotive', 'Main Warehouse', 'operational', '2026-02-14', '2026-02-14', '2026-02-14 15:42:58', '2026-02-14 15:42:58'),
(19, 'AST20260214VB8LH', 'Toyota Hiace', 'Automotive', 'Main Warehouse', 'operational', '2026-02-14', '2026-02-14', '2026-02-14 15:42:58', '2026-02-14 15:42:58'),
(20, 'AST20260214IYNDT', 'BATTERY-LN3 (DIN Size / H6 Equivalent)', 'Automotive', 'Main Warehouse', 'operational', '2026-02-14', '2026-02-14', '2026-02-14 15:51:42', '2026-02-14 15:51:42');

-- --------------------------------------------------------

--
-- Table structure for table `alms_maintenance`
--

CREATE TABLE `alms_maintenance` (
  `mnt_id` int(10) UNSIGNED NOT NULL,
  `mnt_code` varchar(50) DEFAULT NULL,
  `mnt_asset_name` varchar(255) NOT NULL,
  `mnt_type` varchar(50) NOT NULL,
  `mnt_scheduled_date` date NOT NULL,
  `mnt_repair_personnel_id` int(10) UNSIGNED DEFAULT NULL,
  `mnt_status` enum('scheduled','in_progress','completed','cancelled','on_hold') NOT NULL DEFAULT 'scheduled',
  `mnt_priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `mnt_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `mnt_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alms_maintenance`
--

INSERT INTO `alms_maintenance` (`mnt_id`, `mnt_code`, `mnt_asset_name`, `mnt_type`, `mnt_scheduled_date`, `mnt_repair_personnel_id`, `mnt_status`, `mnt_priority`, `mnt_created_at`, `mnt_updated_at`) VALUES
(1, 'MTN202602084O8G8', 'Toyota Fortuner - TOY-2019', 'Engine Inspection', '2026-02-08', 3, 'completed', 'low', '2026-02-08 03:22:40', '2026-02-08 03:23:51'),
(2, 'MTN202602083V9K7', 'Honda CB500 - HON-500', 'Tire Replacement', '2026-02-08', 3, 'completed', 'high', '2026-02-08 03:23:08', '2026-02-08 03:23:56'),
(3, 'MTN202602087H5Q5', 'Honda CB500 - HON-500', 'Oil Change', '2026-02-08', 3, 'completed', 'low', '2026-02-08 03:23:12', '2026-02-08 07:58:59'),
(4, 'MTN202602088J5F1', 'Toyota Hiace - ABC-1234', 'Brake Service', '2026-02-08', 3, 'in_progress', 'low', '2026-02-08 03:23:14', '2026-02-08 07:58:49'),
(5, 'MTN202602083H1J9', 'Isuzu NPR - ISZ-2021', 'Tire Replacement', '2026-02-08', 3, 'in_progress', 'low', '2026-02-08 07:58:15', '2026-02-08 07:58:55'),
(6, 'MTN202602089I5I1', 'Honda CB500 - HON-500', 'Oil Change', '2026-02-08', 3, 'scheduled', 'low', '2026-02-08 07:58:21', '2026-02-08 07:58:21'),
(7, 'MTN202602089B4I2', 'Honda CB500 - HON-500', 'Oil Change', '2026-02-08', 3, 'scheduled', 'low', '2026-02-08 07:58:23', '2026-02-08 07:58:23'),
(8, 'MTN202602091Y6H2', 'Toyota Fortuner - TOY-2019', 'Brake Service', '2026-02-10', 3, 'scheduled', 'high', '2026-02-08 20:35:51', '2026-02-08 20:35:51');

-- --------------------------------------------------------

--
-- Table structure for table `alms_processed_external_requests`
--

CREATE TABLE `alms_processed_external_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `external_id` varchar(255) NOT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alms_processed_external_requests`
--

INSERT INTO `alms_processed_external_requests` (`id`, `external_id`, `processed_at`, `created_at`, `updated_at`) VALUES
(1, '6', '2026-02-08 03:22:40', '2026-02-08 03:22:40', '2026-02-08 03:22:40'),
(2, '7', '2026-02-08 03:23:08', '2026-02-08 03:23:08', '2026-02-08 03:23:08'),
(3, '5', '2026-02-08 03:23:12', '2026-02-08 03:23:12', '2026-02-08 03:23:12'),
(4, '3', '2026-02-08 03:23:14', '2026-02-08 03:23:14', '2026-02-08 03:23:14'),
(5, '2', '2026-02-08 07:58:15', '2026-02-08 07:58:15', '2026-02-08 07:58:15'),
(6, '1', '2026-02-08 07:58:21', '2026-02-08 07:58:21', '2026-02-08 07:58:21'),
(7, '4', '2026-02-08 07:58:23', '2026-02-08 07:58:23', '2026-02-08 07:58:23'),
(8, '64', '2026-02-08 20:35:51', '2026-02-08 20:35:51', '2026-02-08 20:35:51');

-- --------------------------------------------------------

--
-- Table structure for table `alms_request_maintenance`
--

CREATE TABLE `alms_request_maintenance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `req_id` varchar(50) NOT NULL,
  `req_asset_name` varchar(255) NOT NULL,
  `req_date` date NOT NULL,
  `req_priority` varchar(20) NOT NULL,
  `req_type` varchar(100) DEFAULT NULL,
  `req_status` varchar(50) NOT NULL DEFAULT 'pending',
  `req_processed` tinyint(1) NOT NULL DEFAULT 0
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
(1, '2025_12_22_000001_create_alms_assets_table', 1),
(2, '2025_12_22_000101_create_almns_repair_personnel_table', 1),
(3, '2025_12_22_000201_create_alms_maintenance_table', 1),
(4, '2025_12_22_000301_add_rep_id_to_almns_repair_personnel', 1),
(5, '2025_12_22_000400_create_alms_request_maintenance', 2),
(6, '2025_12_22_000500_add_req_type_to_alms_request_maintenance', 2),
(7, '2025_12_22_000600_add_req_processed_to_alms_request_maintenance', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `almns_repair_personnel`
--
ALTER TABLE `almns_repair_personnel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `almns_repair_personnel_rep_id_unique` (`rep_id`);

--
-- Indexes for table `alms_assets`
--
ALTER TABLE `alms_assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alms_assets_asset_code_unique` (`asset_code`);

--
-- Indexes for table `alms_maintenance`
--
ALTER TABLE `alms_maintenance`
  ADD PRIMARY KEY (`mnt_id`),
  ADD UNIQUE KEY `alms_maintenance_mnt_code_unique` (`mnt_code`),
  ADD KEY `alms_maintenance_mnt_scheduled_date_index` (`mnt_scheduled_date`),
  ADD KEY `alms_maintenance_mnt_status_index` (`mnt_status`),
  ADD KEY `alms_maintenance_mnt_priority_index` (`mnt_priority`),
  ADD KEY `alms_maintenance_mnt_repair_personnel_id_foreign` (`mnt_repair_personnel_id`);

--
-- Indexes for table `alms_processed_external_requests`
--
ALTER TABLE `alms_processed_external_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alms_processed_external_requests_external_id_unique` (`external_id`);

--
-- Indexes for table `alms_request_maintenance`
--
ALTER TABLE `alms_request_maintenance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alms_request_maintenance_req_id_unique` (`req_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `almns_repair_personnel`
--
ALTER TABLE `almns_repair_personnel`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `alms_assets`
--
ALTER TABLE `alms_assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `alms_maintenance`
--
ALTER TABLE `alms_maintenance`
  MODIFY `mnt_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `alms_processed_external_requests`
--
ALTER TABLE `alms_processed_external_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `alms_request_maintenance`
--
ALTER TABLE `alms_request_maintenance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alms_maintenance`
--
ALTER TABLE `alms_maintenance`
  ADD CONSTRAINT `alms_maintenance_mnt_repair_personnel_id_foreign` FOREIGN KEY (`mnt_repair_personnel_id`) REFERENCES `almns_repair_personnel` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
