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
-- Database: `log1_logs1_main`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `announcement_image` varchar(255) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `desc`, `announcement_image`, `created_date`, `updated_date`) VALUES
(1, 'Test Announcement', 'This is a test announcement to verify integration.', NULL, '2026-02-06 22:47:43', '2026-02-06 22:47:43'),
(2, 'title test', 'desc test', 'images/announcement/1770448718_anmt.jpg', '2026-02-06 23:18:38', '2026-02-06 23:18:38'),
(3, '123', 'wdadaw', 'images/announcement/1770448743_anmt.jpg', '2026-02-06 23:19:03', '2026-02-06 23:19:03'),
(4, 'testing', 'awnodddddddddddddddddddddddddddddddddddddddddwaiubbsvnldsjvlaboeuixiiiiip,aweawnodddddddddddddddddddddddddddddddddddddddddwaiubbsvnldsjvlaboeuixiiiiip,aweawnodddddddddddddddddddddddddddddddddddddddddwaiubbsvnldsjvlaboeuixiiiiip,aweawnodddddddddddddddddddddddddddddddddddddddddwaiubbsvnldsjvlaboeuixiiiiip,aweawnodddddddddddddddddddddddddddddddddddddddddwaiubbsvnldsjvlaboeuixiiiiip,aweawnodddddddddddddddddddddddddddddddddddddddddwaiubbsvnldsjvlaboeuixiiiiip,aweawnodddddddddddddddddddddddddddddddddddddddddwaiubbsvnldsjvlaboeuixiiiiip,awe', 'images/announcement/1770448953_anmt.jpg', '2026-02-06 23:22:33', '2026-02-06 23:22:33');

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `api_keys`
--

INSERT INTO `api_keys` (`id`, `key`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, '63cfb7730dcc34299fa38cb1a620f701', 'System Generated Key', 'active', '2026-02-07 22:35:05', '2026-02-07 22:35:05');

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
-- Table structure for table `employee_account`
--

CREATE TABLE `employee_account` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employeeid` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `sex` enum('male','female') DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `contactnum` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `roles` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `otp` varchar(255) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_account`
--

INSERT INTO `employee_account` (`id`, `user_id`, `employeeid`, `lastname`, `firstname`, `middlename`, `sex`, `age`, `birthdate`, `contactnum`, `email`, `address`, `password`, `picture`, `roles`, `status`, `otp`, `otp_expires_at`, `email_verified_at`, `last_login`, `created_at`, `updated_at`, `remember_token`) VALUES
(1, 1, 'EMP20260111RMWJV', 'Altamante', 'Ric Jason', 'E.', 'male', 22, '2003-10-11', '09123456780', 'altamantericjason@gmail.com', 'BLK123 LOT123, Streets Barangay Subdivision City 0123', '$2y$12$SnMt31VHXXfbQ3pel1Mhv.Lpuk.uGHMhEkgIh6hzSi4cRofxzEO52', 'images/profile-picture/jFjyCETHpVDDKDXgZma237r9NH7PKjDfCLEUc0nb.jpg', 'superadmin', 'active', NULL, NULL, '2026-02-15 00:39:57', '2026-02-15 00:39:57', '2026-01-11 04:31:00', '2026-02-15 00:39:57', '2L9kSdVv6GAKxsGTZ0d4JmzZfWzhJr1B0P2G53QPS3u6iHp7Hvfsa51d83QG'),
(2, 2, 'EMP20260111H38Q5', 'Quilenderino', 'Robert', 'B.', 'male', 24, '2001-12-21', '09777765712', 'robertbarredoquilenderino@gmail.com', '24 Santan st. Palanas C. Barangay Vasra Quezon City', '$2y$12$NRfjHKQ3XmAyvw6W058ScehqoPUKy1edW7zc5WU.9gSZwWAi45RS2', 'images/profile-picture/R7U3KmVqhTGtD3dKww7XGcd6Brjgdgj0YI2gBnn3.jpg', 'Admin', 'active', NULL, NULL, '2026-02-14 20:55:02', '2026-02-14 20:55:02', '2026-01-11 04:31:01', '2026-02-14 20:55:02', 'gRjAgVpg9ZnUkNfq9HMyXYfj15ZAigWVwQqTeyoeEHkNPU6iw7GivmZ5sRnY'),
(3, 3, 'EMP20260111K6BGU', 'Ibuos', 'Carl', 'B.', 'male', 25, '1998-01-03', '09123456791', 'carleyibuos@gmail.com', 'BLK00 LOT00, Streets Barangay Subdivision City 0123', '$2y$12$5ewIaPVXUluRrs9XzbGGBOs5Q97EQU.NsR2mnadrZXXZ9ufT7B43S', NULL, 'manager', 'active', NULL, NULL, '2026-02-21 22:11:36', '2026-02-21 22:11:36', '2026-01-11 04:31:01', '2026-02-21 22:11:36', 'GMTWMQXieGljoap8kB7fUojlSqrXZjE3nh9sVPvTs88g0GdYjr5Uje9liQ9O'),
(4, 4, 'EMP20260111CWJOG', 'Sobejana', 'Rhenelynn Jhuy', 'B.', 'female', 25, '1998-01-04', '09123456792', 'sobejanajoy@gmail.com', 'BLK00 LOT00, Streets Barangay Subdivision City 0123', '$2y$12$U4N1v1.bJZcz.q2cd7Wi/eJJ3v6CrgeM2oamjfRdVvyxHUqMBACOu', NULL, 'staff', 'active', NULL, NULL, '2026-01-11 04:31:01', NULL, '2026-01-11 04:31:01', '2026-01-11 04:31:01', NULL),
(9, NULL, 'EMP20260211H4AKE', 'Tolentino', 'Kriztian Icy', NULL, 'male', 0, '2026-02-11', '0000000000', 'aicy3987@gmail.com', NULL, '$2y$12$N8HOLLH22TB0.nIofUE4lO4vkevYppXrgfnBkYI/6NhHAzhAhdpsi', NULL, 'Staff', 'active', NULL, NULL, NULL, NULL, '2026-02-10 18:56:29', '2026-02-10 18:56:29', NULL);

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
(5, '0001_01_01_000000_create_users_table', 2),
(6, '0001_01_01_000001_create_cache_table', 2),
(7, '0001_01_01_000002_create_jobs_table', 2),
(8, '2025_11_18_000001_create_main_users_table', 2),
(9, '2025_11_18_000002_ensure_main_users_exists_and_copy', 2),
(10, '2025_11_18_000003_update_main_users_schema_to_sws', 2),
(11, '2025_12_01_000100_create_main_vendors_table', 2),
(12, '2025_12_01_000101_migrate_vendor_accounts_and_update_users_roles', 2),
(13, '2026_01_02_035148_create_user_management_accounts_tables', 2),
(19, '2025_11_19_000100_create_sws_warehouse_table', 4),
(20, '2025_11_19_010000_change_ware_id_to_string_code', 4),
(23, '2025_11_19_082537_create_sws_transactions_table', 5),
(24, '2025_11_19_083344_create_sws_transaction_logs_table', 5),
(25, '2025_11_19_083412_create_sws_inventory_snapshots_table', 5),
(26, '2025_11_19_083614_create_sws_inventory_audits_table', 5),
(27, '2025_11_19_083620_create_sws_items_table', 5),
(28, '2025_11_19_083626_create_sws_categories_table', 5),
(29, '2025_11_19_083632_create_sws_locations_table', 5),
(30, '2025_11_20_112045_add_item_code_to_sws_items_table', 5),
(31, '2025_11_20_115236_add_stock_columns_to_sws_items_table', 5),
(32, '2025_11_20_180451_add_item_stored_from_to_sws_items_table', 5),
(33, '2025_11_20_183820_update_sws_locations_loc_type_enum', 5),
(34, '2025_11_20_184142_change_sws_locations_loc_id_to_string', 5),
(35, '2025_11_24_000001_overhaul_sws_schema', 5),
(36, '2025_11_24_000002_rename_sws_warehouses_to_sws_warehouse', 5),
(37, '2026_01_11_130641_add_foreign_keys_to_sws_tables', 6),
(38, '2025_11_09_181436_create_psm_vendor_table', 7),
(39, '2025_11_09_181437_create_psm_product_table', 7),
(40, '2025_11_13_175538_create_psm_purchase_table', 7),
(41, '2025_11_14_221141_create_psm_budget_table', 7),
(42, '2025_11_14_221202_update_psm_purchase_table', 7),
(43, '2025_11_15_194554_add_approval_columns_to_psm_purchase_table', 7),
(44, '2025_11_17_120000_add_delivery_and_cancel_by_to_psm_purchase_table', 7),
(45, '2025_11_17_130000_create_psm_quote_table', 7),
(46, '2025_11_17_130500_update_delivery_columns_in_psm_purchase_table', 7),
(47, '2026_01_14_000001_fix_warehouse_id_to_string', 8),
(48, '2026_01_14_000002_fix_sws_foreign_keys_type', 9),
(49, '2026_01_14_000003_readd_sws_foreign_keys', 10),
(50, '2026_01_14_000001_update_employee_and_vendor_accounts_add_auth_columns', 11),
(51, '2026_01_15_000003_add_company_name_to_vendor_account_table', 12),
(52, '2026_01_22_000000_truncate_psm_vendor_table', 13),
(53, '2026_01_23_085544_add_prod_picture_to_psm_products_table', 14),
(54, '2026_01_23_090551_add_prod_picture_to_psm_products_table', 15),
(55, '2026_01_24_074530_create_psm_budget_logs_table', 16),
(56, '2026_01_24_080545_add_spent_to_to_psm_budget_logs_table', 17),
(57, '2026_01_25_043502_update_sws_locations_loc_id_length', 18),
(58, '2026_01_31_000001_change_sws_items_dates_to_string', 19),
(59, '2026_01_31_000002_change_sws_categories_id_to_string', 20),
(60, '2026_01_31_000003_add_warranty_expiration_to_psm_purchase', 21),
(61, '2026_01_31_131352_create_psm_purchase_product_table', 22),
(62, '2026_01_18_000300_refresh_psm_purchase_ids_to_new_format', 23),
(63, '2026_01_31_140000_modify_purcprod_id_in_psm_purchase_product_table', 23),
(64, '2026_01_31_173928_modify_psm_purchase_and_quote_delivery_columns', 24),
(65, '2025_02_07_000000_create_announcements_table', 25),
(66, '2026_02_08_050633_add_company_desc_to_vendor_account_table', 26),
(67, '2026_02_08_062745_create_api_keys_table', 27);

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
('jdxqWFBeV1JeT4VDGgbwbHlfVnOO0YtK4ihekyX2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWVAxWFlJRE1kOWp1TUg1RWg0YTBPYU5jdEl1RG5uV0J1dXVmd052NyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1769928273),
('kht9ryKS1euI1m8dHwIvJBeYJ7y5aXFvix8oqthq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibGJIM2UwQVpJNE9vNEdYbVBHMTIzVldiQkRoeXFjbXAydmFrOXFnSiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbi92ZW5kb3ItcG9ydGFsIjtzOjU6InJvdXRlIjtzOjEyOiJsb2dpbi52ZW5kb3IiO319', 1769922635),
('rOmrKHiVtMuqzTZDDrZDLUKgqY9197pMLdbkIwaB', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaDZSVzlva3A1UDE4YTVsdUdTZkgxaXl0NjNVakRTRkdGQ3RsZFJLSiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NzQ6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9vdHAtdmVyaWZpY2F0aW9uP2VtYWlsPWFsdGFtYW50ZXJpY2phc29uJTQwZ21haWwuY29tIjtzOjU6InJvdXRlIjtzOjE2OiJvdHAudmVyaWZpY2F0aW9uIjt9czo1MDoibG9naW5fc3dzXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1769922695);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employeeid` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `sex` enum('male','female') NOT NULL,
  `age` int(11) NOT NULL,
  `birthdate` date NOT NULL,
  `contactnum` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `roles` enum('superadmin','admin','manager','staff') NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `otp` varchar(255) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employeeid`, `lastname`, `firstname`, `middlename`, `sex`, `age`, `birthdate`, `contactnum`, `email`, `address`, `password`, `picture`, `roles`, `status`, `otp`, `otp_expires_at`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'EMP20260111RMWJV', 'Altamante', 'Ric Jason', 'E.', 'male', 25, '1998-01-01', '09123456789', 'altamantericjason@gmail.com', 'BLK00 LOT00, Streets Barangay Subdivision City 0123', '$2y$12$SnMt31VHXXfbQ3pel1Mhv.Lpuk.uGHMhEkgIh6hzSi4cRofxzEO52', NULL, 'superadmin', 'active', NULL, NULL, '2026-01-14 06:36:18', 'dFANFCDAHYJZr6mghCYbS0wjQksWvluW9hrVFrhuvlq0OAT7pI3GyGvO2Xqs', '2026-01-11 04:31:00', '2026-01-14 06:36:18'),
(2, 'EMP20260111H38Q5', 'Quilenderino', 'Robert', 'B.', 'male', 25, '1998-01-02', '09123456790', 'robertbarredoquilenderino@gmail.com', 'BLK00 LOT00, Streets Barangay Subdivision City 0123', '$2y$12$NRfjHKQ3XmAyvw6W058ScehqoPUKy1edW7zc5WU.9gSZwWAi45RS2', NULL, 'superadmin', 'active', NULL, NULL, '2026-01-11 04:31:01', NULL, '2026-01-11 04:31:01', '2026-01-11 05:38:45'),
(3, 'EMP20260111K6BGU', 'Ibuos', 'Carl', 'B.', 'male', 25, '1998-01-03', '09123456791', 'carleyibuos@gmail.com', 'BLK00 LOT00, Streets Barangay Subdivision City 0123', '$2y$12$5ewIaPVXUluRrs9XzbGGBOs5Q97EQU.NsR2mnadrZXXZ9ufT7B43S', NULL, 'manager', 'active', NULL, NULL, '2026-01-11 04:31:01', NULL, '2026-01-11 04:31:01', '2026-01-11 04:31:01'),
(4, 'EMP20260111CWJOG', 'Sobejana', 'Rhenelynn Jhuy', 'B.', 'female', 25, '1998-01-04', '09123456792', 'sobejanajoy@gmail.com', 'BLK00 LOT00, Streets Barangay Subdivision City 0123', '$2y$12$U4N1v1.bJZcz.q2cd7Wi/eJJ3v6CrgeM2oamjfRdVvyxHUqMBACOu', NULL, 'staff', 'active', NULL, NULL, '2026-01-11 04:31:01', NULL, '2026-01-11 04:31:01', '2026-01-11 04:31:01');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendorid` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `sex` enum('male','female') NOT NULL,
  `age` int(11) NOT NULL,
  `birthdate` date NOT NULL,
  `contactnum` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `roles` enum('vendor') NOT NULL DEFAULT 'vendor',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `otp` varchar(255) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `vendorid`, `lastname`, `firstname`, `middlename`, `sex`, `age`, `birthdate`, `contactnum`, `email`, `address`, `password`, `picture`, `roles`, `status`, `otp`, `otp_expires_at`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'VEN20260111NE5VZ', 'Tolentino', 'Kristian Icy', 'B.', 'male', 25, '1998-01-05', '09123456793', 'aicy3987@gmail.com', 'BLK00 LOT00, Streets Barangay Subdivision City 0123', '$2y$12$MElBJWiNp3tyXVxl10BrouInUjChqLEXoMTI2P0rNigKlXh2NCPzW', NULL, 'vendor', 'active', NULL, NULL, '2026-01-11 04:31:01', NULL, '2026-01-11 04:31:01', '2026-01-11 04:31:01'),
(2, 'VEN202601117KPD5', 'Matho', 'Potato', NULL, 'male', 0, '2026-01-11', '0000000000', 'potatomatho@gmail.com', NULL, '$2y$12$duvdZGFqrbMh8OtusXsWMu347lugPzUen.j.vG2IQC4nt.z/R5qiK', NULL, 'vendor', 'active', NULL, NULL, '2026-01-11 05:46:21', 'auTFg3Dcr1WOvJtXgGNOjBhKHXT4X7M1SirZOL0adLNK4ytDPU7vRpKwzFkH', '2026-01-11 05:45:44', '2026-01-11 05:46:21');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_account`
--

CREATE TABLE `vendor_account` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vendorid` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `middlename` varchar(255) DEFAULT NULL,
  `sex` enum('male','female') DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `contactnum` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `roles` varchar(255) NOT NULL DEFAULT 'vendor',
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `otp` varchar(255) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `company_type` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_desc` text DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_account`
--

INSERT INTO `vendor_account` (`id`, `vendor_id`, `vendorid`, `lastname`, `firstname`, `middlename`, `sex`, `age`, `birthdate`, `contactnum`, `email`, `address`, `password`, `picture`, `roles`, `status`, `otp`, `otp_expires_at`, `email_verified_at`, `last_login`, `company_type`, `company_name`, `company_desc`, `rating`, `created_at`, `updated_at`, `remember_token`) VALUES
(1, 1, 'VEN20260111NE5VZ', 'Tolentino', 'Kristian Icy', 'B.', 'male', 25, '1998-01-05', '09123456793', 'tolentinokriztian9@gmail.com', 'BLK00 LOT00, Streets Barangay Subdivision City 0123', '$2y$12$MElBJWiNp3tyXVxl10BrouInUjChqLEXoMTI2P0rNigKlXh2NCPzW', 'images/profile-picture/cIl5os7attHmOhdKjXCatqmhaKe9vhvBxBk8RmXJ.png', 'vendor', 'active', NULL, NULL, '2026-02-18 00:32:01', '2026-02-18 00:32:01', 'Equipment', 'IcyTools', 'IcyTools is an equipment-based company supplying reliable tools and machinery for industrial and commercial use. We aim to provide efficient, high-quality equipment that helps customers work smarter and faster.', 0, '2026-01-11 04:31:01', '2026-02-18 00:32:01', 'kdVBrgzT3ayau4oNAjFlXN8sfj9NnHQpVKtBPE13aLyj8jNYxqF6fKLXsEN3'),
(3, NULL, 'VEN20260114Z1JB0', 'Matho', 'Potato', NULL, 'male', 22, '2000-10-11', '0912312312', 'potatomatho@gmail.com', '1234 Street, Barangay Building Floor Manuel City', '$2y$12$.D7OPld9HNCV5hAa0lZqD.MfSVE36cBCR3Oelhvb1VhmFOMjBoyfq', 'images/profile-picture/WamGMgiUQatb28SQUIJ05SzYaiif9uoUSAc32XrW.png', 'Vendor', 'active', NULL, NULL, '2026-02-14 13:55:45', '2026-02-14 13:55:45', 'Supplies', 'OfficeSupplies Depot', 'OfficeSupplies Depot is a supplies-based company offering a wide range of office essentials for businesses and institutions. We ensure consistent quality, affordability, and timely delivery to support daily operations.', 0, '2026-01-14 07:43:34', '2026-02-14 13:55:45', 'XF2rgb2L5F7dbvEIs9BJ8xYGtuVBnOhZqYZWTFKnEqhNgX2R3jSS2E9OkTAq'),
(4, NULL, 'VEN202601235JMKX', 'Quilenderino', 'Butchoy', NULL, 'male', 27, '2000-01-25', '09123345678', 'butchoyquilenderino@gmail.com', 'BLK 01 LOT 01 Lemon Street. Barangay 123 Manila Quezon City', '$2y$12$dLqJ5f9OXkqVV8gMVahB..X2RSLSL1JYc1Vd89vI9cHOrnnMg/cBS', 'images/profile-picture/muhZlyuuAZUhu7MNb0I4KtHGos9R4RfGx82W06Km.png', 'Vendor', 'active', NULL, NULL, '2026-02-14 21:06:20', '2026-02-14 21:06:20', 'Automotive', 'AutoButchoy', 'AutoButchoy is an automotive-based company providing vehicle maintenance, parts, and related services. We are committed to delivering dependable solutions that keep vehicles safe, efficient, and road-ready.', 0, '2026-01-22 22:12:45', '2026-02-14 21:06:20', 'ni3UdQANOOCD6IibpJ1VzgNGMYNyfb9Yd4Jr2n5gnmqdPxIyrEYAe19Bio4T'),
(7, NULL, 'VEN20260124W0JBV', 'city', 'lemoun', NULL, 'male', 0, '2026-01-24', '0000000000', 'lemouncity@gmail.com', NULL, '$2y$12$QjOwii48u2.0DewVWNI.x.m//8d4lQNwb7amzIIXLEZJyWUDg66XK', NULL, 'Vendor', 'inactive', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-01-23 21:27:13', '2026-01-23 21:27:13', NULL),
(8, NULL, 'VEN20260208OZRGI', 'Antares', 'Lhil Carl', NULL, 'male', 22, '2002-07-10', '09748352816', 'lhilcarlibuos123@gmail.com', 'Building 205, J.P. Rizal Avenue Rockwell Center Metro Manila Makati City 1200', '$2y$12$I3nLvJSZwb2yrVOQL2PjX.IO7f.Op10/8IlSpX/DxLRZo9OEwbz8a', 'images/profile-picture/RfgiPqKnntbpDA09mxf86KR5HmK6lwFBapCREOKn.png', 'Vendor', 'active', NULL, NULL, '2026-02-21 22:08:08', '2026-02-21 22:08:08', 'Furniture', 'LhilFarniture', 'LhilFarniture is a furniture-based company specializing in durable and stylish pieces for homes and offices. We focus on quality craftsmanship, functional design, and reliable service to meet everyday living and workspace needs.', 0, '2026-02-07 19:58:14', '2026-02-21 22:08:08', 'RWkWj7F4vO2jEmg6pi4Dul5Wx1KxzSlOMZCwB9tLgL0YWTvBxbBDZT6a6PWv');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `api_keys_key_unique` (`key`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `employee_account`
--
ALTER TABLE `employee_account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_account_email_unique` (`email`),
  ADD KEY `employee_account_user_id_foreign` (`user_id`);

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
  ADD UNIQUE KEY `users_employeeid_unique` (`employeeid`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendors_vendorid_unique` (`vendorid`),
  ADD UNIQUE KEY `vendors_email_unique` (`email`);

--
-- Indexes for table `vendor_account`
--
ALTER TABLE `vendor_account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendor_account_email_unique` (`email`),
  ADD KEY `vendor_account_vendor_id_foreign` (`vendor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_account`
--
ALTER TABLE `employee_account`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vendor_account`
--
ALTER TABLE `vendor_account`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee_account`
--
ALTER TABLE `employee_account`
  ADD CONSTRAINT `employee_account_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_account`
--
ALTER TABLE `vendor_account`
  ADD CONSTRAINT `vendor_account_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
