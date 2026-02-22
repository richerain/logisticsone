-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 22, 2026 at 11:54 AM
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
-- Database: `log1_logs1_plt`
--

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
(1, '2025_11_24_150828_create_plt_projects_table', 1),
(2, '2025_11_24_150833_create_plt_milestones_table', 1),
(3, '2025_11_24_150833_create_plt_resources_table', 1),
(4, '2025_11_24_150834_create_plt_allocations_table', 1),
(5, '2025_11_24_150835_create_plt_dispatches_table', 1),
(6, '2025_11_24_150836_create_plt_tracking_logs_table', 2),
(7, '2026_02_15_160100_add_project_dates_to_plt_movement_project_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `plt_allocations`
--

CREATE TABLE `plt_allocations` (
  `allo_id` bigint(20) UNSIGNED NOT NULL,
  `allo_project_id` bigint(20) UNSIGNED NOT NULL,
  `allo_milestone_id` bigint(20) UNSIGNED NOT NULL,
  `allo_resource_id` bigint(20) UNSIGNED NOT NULL,
  `allo_location_id` int(11) NOT NULL COMMENT 'Reference to sws_locations.id',
  `allo_quantity` int(11) NOT NULL,
  `allo_allocated_date` date NOT NULL,
  `allo_status` enum('allocated','dispatched','delivered') NOT NULL DEFAULT 'allocated',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plt_dispatches`
--

CREATE TABLE `plt_dispatches` (
  `dis_id` bigint(20) UNSIGNED NOT NULL,
  `dis_allocation_id` bigint(20) UNSIGNED NOT NULL,
  `dis_dispatch_number` varchar(50) NOT NULL,
  `dis_carrier` varchar(100) DEFAULT NULL,
  `dis_expected_delivery_date` date NOT NULL,
  `dis_actual_delivery_date` date DEFAULT NULL,
  `dis_tracking_code` varchar(100) DEFAULT NULL,
  `dis_status` enum('prepared','in_transit','delivered','returned') NOT NULL DEFAULT 'prepared',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plt_milestones`
--

CREATE TABLE `plt_milestones` (
  `mile_id` bigint(20) UNSIGNED NOT NULL,
  `mile_project_id` bigint(20) UNSIGNED NOT NULL,
  `mile_milestone_name` varchar(100) NOT NULL,
  `mile_description` text DEFAULT NULL,
  `mile_target_date` date NOT NULL,
  `mile_actual_date` date DEFAULT NULL,
  `mile_status` enum('pending','in_progress','completed','overdue') NOT NULL DEFAULT 'pending',
  `mile_priority` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plt_milestones`
--

INSERT INTO `plt_milestones` (`mile_id`, `mile_project_id`, `mile_milestone_name`, `mile_description`, `mile_target_date`, `mile_actual_date`, `mile_status`, `mile_priority`, `created_at`, `updated_at`) VALUES
(2, 1, 'Execution', 'Main execution phase', '2026-01-21', NULL, 'pending', 'medium', '2026-01-13 11:54:14', '2026-01-13 11:54:14'),
(3, 1, 'Closure', 'Finalize and close project', '2026-01-21', NULL, 'pending', 'medium', '2026-01-13 11:54:14', '2026-01-13 11:54:14'),
(7, 3, 'Initiation', 'Project initiation and kickoff', '2026-01-15', NULL, 'completed', 'medium', '2026-01-15 05:26:56', '2026-01-15 05:28:08'),
(8, 3, 'Execution', 'Main execution phase', '2026-01-22', NULL, 'completed', 'medium', '2026-01-15 05:26:56', '2026-01-15 05:28:13'),
(9, 3, 'Closure', 'Finalize and close project', '2026-01-22', NULL, 'completed', 'medium', '2026-01-15 05:26:56', '2026-01-15 05:28:18'),
(10, 4, 'Initiation', 'Project initiation and kickoff', '2026-01-16', '2026-01-17', 'completed', 'medium', '2026-01-15 05:27:21', '2026-01-15 05:29:22'),
(11, 4, 'Execution', 'Main execution phase', '2026-01-23', NULL, 'in_progress', 'medium', '2026-01-15 05:27:21', '2026-01-15 05:29:34'),
(12, 4, 'Closure', 'Finalize and close project', '2026-01-23', NULL, 'pending', 'medium', '2026-01-15 05:27:21', '2026-01-15 05:27:21'),
(13, 5, 'Initiation', 'Project initiation and kickoff', '2026-01-01', NULL, 'pending', 'medium', '2026-01-15 05:27:43', '2026-01-15 05:27:43'),
(14, 5, 'Execution', 'Main execution phase', '2026-01-08', NULL, 'pending', 'medium', '2026-01-15 05:27:43', '2026-01-15 05:27:43'),
(15, 5, 'Closure', 'Finalize and close project', '2026-01-08', NULL, 'pending', 'medium', '2026-01-15 05:27:43', '2026-01-15 05:27:43');

-- --------------------------------------------------------

--
-- Table structure for table `plt_movement_project`
--

CREATE TABLE `plt_movement_project` (
  `mp_id` bigint(20) UNSIGNED NOT NULL,
  `mp_item_name` varchar(255) NOT NULL,
  `mp_unit_transfer` int(11) NOT NULL DEFAULT 0,
  `mp_stored_from` varchar(255) DEFAULT NULL,
  `mp_stored_to` varchar(255) DEFAULT NULL,
  `mp_item_type` varchar(100) DEFAULT NULL,
  `mp_movement_type` enum('Stock Transfer','Asset Transfer') NOT NULL DEFAULT 'Stock Transfer',
  `mp_status` enum('pending','in-progress','delayed','completed') NOT NULL DEFAULT 'pending',
  `mp_project_start` date DEFAULT NULL,
  `mp_project_end` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plt_movement_project`
--

INSERT INTO `plt_movement_project` (`mp_id`, `mp_item_name`, `mp_unit_transfer`, `mp_stored_from`, `mp_stored_to`, `mp_item_type`, `mp_movement_type`, `mp_status`, `mp_project_start`, `mp_project_end`, `created_at`, `updated_at`) VALUES
(1, 'Bondpaper size-A4 (100-pcs bundle)', 10, 'Main Warehouse', 'Administrative Office', 'Supplies', 'Stock Transfer', 'completed', '2026-02-15', '2026-02-15', '2026-02-14 18:43:35', '2026-02-14 19:07:23'),
(2, 'Toyota Hiace', 1, 'Main Warehouse', 'Garage', 'Automotive', 'Stock Transfer', 'in-progress', '2026-02-15', NULL, '2026-02-14 19:10:19', '2026-02-14 19:17:26');

-- --------------------------------------------------------

--
-- Table structure for table `plt_projects`
--

CREATE TABLE `plt_projects` (
  `pro_id` bigint(20) UNSIGNED NOT NULL,
  `pro_project_name` varchar(255) NOT NULL,
  `pro_description` text DEFAULT NULL,
  `pro_start_date` date NOT NULL,
  `pro_end_date` date DEFAULT NULL,
  `pro_status` enum('planning','active','on_hold','completed','cancelled') NOT NULL DEFAULT 'planning',
  `pro_budget_allocated` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pro_assigned_manager_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plt_projects`
--

INSERT INTO `plt_projects` (`pro_id`, `pro_project_name`, `pro_description`, `pro_start_date`, `pro_end_date`, `pro_status`, `pro_budget_allocated`, `pro_assigned_manager_id`, `created_at`, `updated_at`) VALUES
(1, 'transferring item', 'wdawdasd', '2026-01-14', '2026-01-21', 'cancelled', 10000.00, 123123, '2026-01-13 11:54:14', '2026-01-15 05:25:53'),
(3, 'Distribute item', 'desc', '2026-01-15', '2026-01-22', 'completed', 5000.00, 97814, '2026-01-15 05:26:55', '2026-01-15 05:28:18'),
(4, 'testing project', 'asddasd', '2026-01-16', '2026-01-23', 'active', 10000.00, 12434654, '2026-01-15 05:27:21', '2026-01-15 05:29:35'),
(5, 'transferring item', 'adwdsawd', '2026-01-01', '2026-01-08', 'planning', 9000.00, 1234634, '2026-01-15 05:27:43', '2026-01-15 05:27:43'),
(6, 'Logistics Transfer RF202602159G9U0', 'Transfer 10 units of Bondpaper size-A4 (100-pcs bundle) from unknown to Administrative Office', '2026-02-15', NULL, 'planning', 0.00, 1, '2026-02-14 18:43:35', '2026-02-14 18:43:35'),
(7, 'Logistics Transfer RF202602153X9U6', 'Transfer 1 units of Toyota Hiace from unknown to Garage', '2026-02-15', NULL, 'planning', 0.00, 1, '2026-02-14 19:10:18', '2026-02-14 19:10:18');

-- --------------------------------------------------------

--
-- Table structure for table `plt_resources`
--

CREATE TABLE `plt_resources` (
  `res_id` bigint(20) UNSIGNED NOT NULL,
  `res_project_id` bigint(20) UNSIGNED NOT NULL,
  `res_item_id` int(11) NOT NULL COMMENT 'Reference to sws_items.id',
  `res_quantity_required` int(11) NOT NULL,
  `res_quantity_allocated` int(11) NOT NULL DEFAULT 0,
  `res_estimated_cost` decimal(10,2) DEFAULT NULL,
  `res_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plt_tracking_logs`
--

CREATE TABLE `plt_tracking_logs` (
  `track_id` bigint(20) UNSIGNED NOT NULL,
  `track_project_id` bigint(20) UNSIGNED NOT NULL,
  `track_log_type` enum('milestone_update','allocation_change','dispatch_event','resource_issue') NOT NULL,
  `track_description` text NOT NULL,
  `track_logged_by` varchar(100) NOT NULL,
  `track_reference_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plt_tracking_logs`
--

INSERT INTO `plt_tracking_logs` (`track_id`, `track_project_id`, `track_log_type`, `track_description`, `track_logged_by`, `track_reference_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'milestone_update', 'Project created successfully', 'altamantericjason@gmail.com', NULL, '2026-01-13 11:54:14', '2026-01-13 11:54:14'),
(4, 1, 'milestone_update', 'Project details updated', 'altamantericjason@gmail.com', NULL, '2026-01-15 05:25:54', '2026-01-15 05:25:54'),
(5, 3, 'milestone_update', 'Project created successfully', 'altamantericjason@gmail.com', NULL, '2026-01-15 05:26:56', '2026-01-15 05:26:56'),
(6, 4, 'milestone_update', 'Project created successfully', 'altamantericjason@gmail.com', NULL, '2026-01-15 05:27:21', '2026-01-15 05:27:21'),
(7, 5, 'milestone_update', 'Project created successfully', 'altamantericjason@gmail.com', NULL, '2026-01-15 05:27:43', '2026-01-15 05:27:43'),
(8, 6, 'dispatch_event', 'Created from SWS transfer RF202602159G9U0', 'system', 'RF202602159G9U0', '2026-02-14 18:43:35', '2026-02-14 18:43:35'),
(9, 7, 'dispatch_event', 'Created from SWS transfer RF202602153X9U6', 'system', 'RF202602153X9U6', '2026-02-14 19:10:18', '2026-02-14 19:10:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plt_allocations`
--
ALTER TABLE `plt_allocations`
  ADD PRIMARY KEY (`allo_id`),
  ADD KEY `plt_allocations_allo_milestone_id_foreign` (`allo_milestone_id`),
  ADD KEY `plt_allocations_allo_resource_id_foreign` (`allo_resource_id`),
  ADD KEY `plt_allocations_allo_project_id_allo_milestone_id_index` (`allo_project_id`,`allo_milestone_id`),
  ADD KEY `plt_allocations_allo_status_index` (`allo_status`);

--
-- Indexes for table `plt_dispatches`
--
ALTER TABLE `plt_dispatches`
  ADD PRIMARY KEY (`dis_id`),
  ADD UNIQUE KEY `plt_dispatches_dis_dispatch_number_unique` (`dis_dispatch_number`),
  ADD KEY `plt_dispatches_dis_allocation_id_index` (`dis_allocation_id`),
  ADD KEY `plt_dispatches_dis_status_index` (`dis_status`),
  ADD KEY `plt_dispatches_dis_dispatch_number_index` (`dis_dispatch_number`);

--
-- Indexes for table `plt_milestones`
--
ALTER TABLE `plt_milestones`
  ADD PRIMARY KEY (`mile_id`),
  ADD KEY `plt_milestones_mile_project_id_index` (`mile_project_id`),
  ADD KEY `plt_milestones_mile_status_index` (`mile_status`),
  ADD KEY `plt_milestones_mile_target_date_index` (`mile_target_date`);

--
-- Indexes for table `plt_movement_project`
--
ALTER TABLE `plt_movement_project`
  ADD PRIMARY KEY (`mp_id`),
  ADD KEY `plt_movement_project_mp_status_index` (`mp_status`),
  ADD KEY `plt_movement_project_mp_movement_type_index` (`mp_movement_type`);

--
-- Indexes for table `plt_projects`
--
ALTER TABLE `plt_projects`
  ADD PRIMARY KEY (`pro_id`),
  ADD KEY `plt_projects_pro_status_index` (`pro_status`),
  ADD KEY `plt_projects_pro_start_date_index` (`pro_start_date`),
  ADD KEY `plt_projects_pro_end_date_index` (`pro_end_date`);

--
-- Indexes for table `plt_resources`
--
ALTER TABLE `plt_resources`
  ADD PRIMARY KEY (`res_id`),
  ADD KEY `plt_resources_res_project_id_index` (`res_project_id`);

--
-- Indexes for table `plt_tracking_logs`
--
ALTER TABLE `plt_tracking_logs`
  ADD PRIMARY KEY (`track_id`),
  ADD KEY `plt_tracking_logs_track_project_id_index` (`track_project_id`),
  ADD KEY `plt_tracking_logs_track_log_type_index` (`track_log_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `plt_allocations`
--
ALTER TABLE `plt_allocations`
  MODIFY `allo_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plt_dispatches`
--
ALTER TABLE `plt_dispatches`
  MODIFY `dis_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plt_milestones`
--
ALTER TABLE `plt_milestones`
  MODIFY `mile_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `plt_movement_project`
--
ALTER TABLE `plt_movement_project`
  MODIFY `mp_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `plt_projects`
--
ALTER TABLE `plt_projects`
  MODIFY `pro_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `plt_resources`
--
ALTER TABLE `plt_resources`
  MODIFY `res_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plt_tracking_logs`
--
ALTER TABLE `plt_tracking_logs`
  MODIFY `track_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `plt_allocations`
--
ALTER TABLE `plt_allocations`
  ADD CONSTRAINT `plt_allocations_allo_milestone_id_foreign` FOREIGN KEY (`allo_milestone_id`) REFERENCES `plt_milestones` (`mile_id`),
  ADD CONSTRAINT `plt_allocations_allo_project_id_foreign` FOREIGN KEY (`allo_project_id`) REFERENCES `plt_projects` (`pro_id`),
  ADD CONSTRAINT `plt_allocations_allo_resource_id_foreign` FOREIGN KEY (`allo_resource_id`) REFERENCES `plt_resources` (`res_id`);

--
-- Constraints for table `plt_dispatches`
--
ALTER TABLE `plt_dispatches`
  ADD CONSTRAINT `plt_dispatches_dis_allocation_id_foreign` FOREIGN KEY (`dis_allocation_id`) REFERENCES `plt_allocations` (`allo_id`);

--
-- Constraints for table `plt_milestones`
--
ALTER TABLE `plt_milestones`
  ADD CONSTRAINT `plt_milestones_mile_project_id_foreign` FOREIGN KEY (`mile_project_id`) REFERENCES `plt_projects` (`pro_id`);

--
-- Constraints for table `plt_resources`
--
ALTER TABLE `plt_resources`
  ADD CONSTRAINT `plt_resources_res_project_id_foreign` FOREIGN KEY (`res_project_id`) REFERENCES `plt_projects` (`pro_id`);

--
-- Constraints for table `plt_tracking_logs`
--
ALTER TABLE `plt_tracking_logs`
  ADD CONSTRAINT `plt_tracking_logs_track_project_id_foreign` FOREIGN KEY (`track_project_id`) REFERENCES `plt_projects` (`pro_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
