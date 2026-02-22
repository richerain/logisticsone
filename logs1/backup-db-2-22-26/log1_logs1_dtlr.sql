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
-- Database: `log1_logs1_dtlr`
--

-- --------------------------------------------------------

--
-- Table structure for table `dtlr_documents`
--

CREATE TABLE `dtlr_documents` (
  `doc_id` varchar(20) NOT NULL,
  `doc_type` enum('Contract','Purchase Order','Invoice','Quotation','Good Received Note') NOT NULL,
  `doc_title` varchar(255) NOT NULL,
  `doc_status` enum('pending_review','indexed','archived') NOT NULL DEFAULT 'pending_review',
  `doc_file_available` tinyint(1) NOT NULL DEFAULT 1,
  `doc_file_path` varchar(500) DEFAULT NULL,
  `doc_file_original_name` varchar(255) DEFAULT NULL,
  `doc_file_mime` varchar(100) DEFAULT NULL,
  `doc_file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dtlr_documents`
--

INSERT INTO `dtlr_documents` (`doc_id`, `doc_type`, `doc_title`, `doc_status`, `doc_file_available`, `doc_file_path`, `doc_file_original_name`, `doc_file_mime`, `doc_file_size`, `created_at`, `updated_at`) VALUES
('DOC20260215OF2EA', 'Good Received Note', 'Digital Inventory Report - 2026-02-15 03:50', 'pending_review', 1, 'dtlr/documents/DOC20260215OF2EA.pdf', 'digital-inventory-report.pdf', 'application/pdf', 10758, '2026-02-14 19:50:08', '2026-02-14 19:50:08');

-- --------------------------------------------------------

--
-- Table structure for table `dtlr_logistics_records`
--

CREATE TABLE `dtlr_logistics_records` (
  `log_id` varchar(20) NOT NULL,
  `doc_id` varchar(20) DEFAULT NULL,
  `doc_type` varchar(60) DEFAULT NULL,
  `doc_title` varchar(255) DEFAULT NULL,
  `doc_status` varchar(30) DEFAULT NULL,
  `module` varchar(120) NOT NULL,
  `submodule` varchar(120) NOT NULL,
  `performed_action` varchar(120) NOT NULL,
  `performed_by` varchar(160) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, '2025_12_28_000100_create_dtlr_documents_table', 1),
(2, '2025_12_28_000200_create_dtlr_logistics_records_table', 1),
(3, '2025_12_28_000300_alter_dtlr_logistics_records_add_document_fields', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dtlr_documents`
--
ALTER TABLE `dtlr_documents`
  ADD PRIMARY KEY (`doc_id`),
  ADD KEY `dtlr_documents_doc_type_index` (`doc_type`),
  ADD KEY `dtlr_documents_doc_status_index` (`doc_status`),
  ADD KEY `dtlr_documents_created_at_index` (`created_at`);

--
-- Indexes for table `dtlr_logistics_records`
--
ALTER TABLE `dtlr_logistics_records`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `dtlr_logistics_records_module_index` (`module`),
  ADD KEY `dtlr_logistics_records_submodule_index` (`submodule`),
  ADD KEY `dtlr_logistics_records_performed_action_index` (`performed_action`),
  ADD KEY `dtlr_logistics_records_created_at_index` (`created_at`),
  ADD KEY `dtlr_logistics_records_doc_id_index` (`doc_id`),
  ADD KEY `dtlr_logistics_records_doc_status_index` (`doc_status`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
