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
-- Database: `log1_logs1_psm`
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
(1, '2026_02_11_090940_create_psm_requisition_table', 1),
(2, '2026_02_14_120001_create_psm_purcahse_request_table', 2),
(3, '2026_02_14_120010_update_psm_quote_drop_unused_columns', 3),
(4, '2026_02_14_120020_update_psm_purchase_drop_unused_columns', 4);

-- --------------------------------------------------------

--
-- Table structure for table `psm_budget`
--

CREATE TABLE `psm_budget` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bud_id` varchar(255) NOT NULL,
  `bud_allocated_amount` decimal(15,2) NOT NULL,
  `bud_spent_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bud_remaining_amount` decimal(15,2) NOT NULL,
  `bud_assigned_date` date NOT NULL,
  `bud_validity_type` enum('Week','Month','Year') NOT NULL,
  `bud_valid_from` date NOT NULL,
  `bud_valid_to` date NOT NULL,
  `bud_amount_status_health` enum('Healthy','Stable','Alert','Exceeded') NOT NULL DEFAULT 'Healthy',
  `bud_for_department` varchar(255) NOT NULL DEFAULT 'Logistics 1',
  `bud_for_module` varchar(255) NOT NULL DEFAULT 'Procurement & Sourcing Management',
  `bud_for_submodule` varchar(255) NOT NULL DEFAULT 'Purchase Management',
  `bud_desc` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `psm_budget_allocated`
--

CREATE TABLE `psm_budget_allocated` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `all_id` varchar(255) NOT NULL,
  `all_req_id` varchar(255) NOT NULL,
  `all_req_by` varchar(255) NOT NULL,
  `all_amount` decimal(15,2) NOT NULL,
  `all_budget_allocated` decimal(15,2) NOT NULL DEFAULT 0.00,
  `all_department` varchar(255) NOT NULL,
  `all_date` date NOT NULL,
  `all_purpose` text NOT NULL,
  `all_status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `psm_budget_logs`
--

CREATE TABLE `psm_budget_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_code` varchar(255) DEFAULT NULL,
  `bud_id` varchar(255) DEFAULT NULL,
  `bud_spent` decimal(15,2) NOT NULL DEFAULT 0.00,
  `spent_to` varchar(255) DEFAULT NULL,
  `bud_type` varchar(255) NOT NULL DEFAULT 'Purchase Payment',
  `bud_spent_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `psm_consolidated`
--

CREATE TABLE `psm_consolidated` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `con_req_id` varchar(255) NOT NULL,
  `con_items` text DEFAULT NULL,
  `con_chosen_vendor` varchar(255) DEFAULT NULL,
  `con_total_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `con_requester` varchar(255) DEFAULT NULL,
  `con_dept` varchar(255) DEFAULT NULL,
  `con_date` timestamp NULL DEFAULT NULL,
  `con_note` text DEFAULT NULL,
  `con_status` varchar(255) DEFAULT NULL,
  `con_budget_approval` varchar(255) NOT NULL DEFAULT 'pending',
  `con_purchase_order` varchar(255) DEFAULT NULL,
  `parent_budget_req_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `psm_consolidated`
--

INSERT INTO `psm_consolidated` (`id`, `req_id`, `con_req_id`, `con_items`, `con_chosen_vendor`, `con_total_price`, `con_requester`, `con_dept`, `con_date`, `con_note`, `con_status`, `con_budget_approval`, `con_purchase_order`, `parent_budget_req_id`, `created_at`, `updated_at`) VALUES
(1, 'REQN20260213K3DWC', 'CONS20260213WHZPV', '[\"Bondpaper size-A4 (100-pcs bundle)\",\"Ballpoint Pen (12pcs per pack)\",\"Joy Stapler Promo Bundle 2s\",\"HBW Office Vinyl Coated Paper Clip 50mm\"]', 'VEN20260114Z1JB0', 516.00, 'Tomas Finland', NULL, '2026-02-11 16:00:00', 'Notes / Remarks', 'Approved', 'Approved', 'Completed', 'REQB202602130CMVIS5F', '2026-02-12 16:27:41', '2026-02-13 10:53:00'),
(8, 'REQN20260214HTZWG', 'CONS20260214LRNPZ', '[\"Toyota Hiace\"]', 'VEN202601235JMKX', 1379000.00, 'Robert Quilenderino', 'Logistics Office', '2026-02-13 16:00:00', 'Notes / Remarks', 'Approved', 'Approved', 'Completed', 'REQB202602146YYFXXOG', '2026-02-13 18:55:32', '2026-02-14 00:14:56'),
(9, 'REQN202602143FUHL', 'CONS20260214BRRVB', '[\"HBW Office Vinyl Coated Paper Clip 50mm\",\"Joy Stapler Promo Bundle 2s\",\"Ballpoint Pen (12pcs per pack)\",\"Bondpaper size-A4 (100-pcs bundle)\"]', 'VEN20260114Z1JB0', 516.00, 'Robert Quilenderino', 'Administrative Office', '2026-02-13 16:00:00', 'Notes / Remarks', 'Approved', 'Approved', 'Completed', 'REQB20260214DE2KDZOT', '2026-02-13 18:56:03', '2026-02-14 00:14:15'),
(10, 'REQN2026021498LMB', 'CONS20260214V4ZKF', '[\"\\ufffcHBW Office Heavy Duty Puncher\",\"Canon Pixma G3010 All-in-One Ink Tank Printer (Wi-Fi Connectivity, 2315C018AB, Black)\",\"ThinkPad E16 AMD G2\",\"COMIX S3508D (8 sheets, 22L Micro Cut Heavy Duty Paper Shredder)\"]', 'VEN20260111NE5VZ', 559159.00, 'Tomas Finland', 'Core Transaction Office', '2026-02-13 16:00:00', 'Notes / Remarks', 'Approved', 'Approved', 'Completed', 'REQB20260214DE2KDZOT', '2026-02-13 18:56:03', '2026-02-14 00:14:37'),
(11, 'REQN20260214GZHCW', 'CONS202602142TDUK', '[\"Honda CB500\"]', 'VEN202601235JMKX', 379000.00, 'Robert Quilenderino', 'Logistics Office', '2026-02-13 16:00:00', 'Notes / Remarks', 'Approved', 'Approved', 'Completed', 'REQB20260214VVFCPWIA', '2026-02-13 22:59:19', '2026-02-14 00:13:54'),
(12, 'REQN20260214EJ1EN', 'CONS20260214EVZSD', '[\"2012 Isuzu NPR\"]', 'VEN202601235JMKX', 980000.00, 'Robert Quilenderino', 'Logistics Office', '2026-02-13 16:00:00', 'Notes / Remarks', 'Approved', 'Approved', 'Completed', 'REQB20260214FNASPZSH', '2026-02-13 23:05:59', '2026-02-14 00:13:35'),
(13, 'REQN20260214MB5X3', 'CONS202602147DAYD', '[\"Mitsubishi Fuso Canter FE73 (6-Wheeler)\"]', 'VEN202601235JMKX', 2400000.00, 'Robert Quilenderino', 'Logistics Office', '2026-02-13 16:00:00', 'Notes / Remarks', 'Approved', 'Approved', 'Completed', 'REQB20260214XEIUHGXW', '2026-02-13 23:15:58', '2026-02-14 00:12:37'),
(14, 'REQN2026021403AYF', 'CONS20260214W37QI', '[\"Mitsubishi Fuso Canter Cruise (Minibus)\"]', 'VEN202601235JMKX', 2600000.00, 'Robert Quilenderino', 'Logistics Office', '2026-02-13 16:00:00', 'Notes / Remarks', 'Approved', 'Approved', 'Completed', 'REQB20260214XOE0DYEF', '2026-02-13 23:21:28', '2026-02-14 00:12:00'),
(15, 'REQN202602144Z7A3', 'CONS20260214D4DAT', '[\"Cabinet\"]', 'VEN20260208OZRGI', 8999.00, 'James Carter', 'Core Transaction Office', '2026-02-12 16:00:00', 'Notes / Remarks', 'Approved', 'Approved', 'Completed', 'REQB20260214ZPPPZHNQ', '2026-02-14 00:16:13', '2026-02-14 05:43:47'),
(16, 'REQN20260214GKRSB', 'CONS20260214T7BEK', '[\"Toyota Fortuner\"]', 'VEN202601235JMKX', 2200000.00, 'Robert Quilenderino', 'Logistics Office', '2026-02-13 16:00:00', 'Notes / Remarks', 'Approved', 'Approved', NULL, 'REQB20260214ZPPPZHNQ', '2026-02-14 00:16:13', '2026-02-14 05:09:06'),
(17, 'REQN20260214HPG9Y', 'CONS20260214KI9J4', '[\"Suzuki Carry Utility Van 1.5L\"]', 'VEN202601235JMKX', 820000.00, 'Robert Quilenderino', 'Logistics Office', '2026-02-13 16:00:00', 'Notes / Remarks', 'Approved', 'Approved', NULL, 'REQB20260214ZPPPZHNQ', '2026-02-14 00:16:13', '2026-02-14 05:09:06'),
(18, 'REQN20260214JD9L4', 'CONS20260214N2J1V', '[\"BATTERY-LN3 (DIN Size \\/ H6 Equivalent)\"]', 'VEN202601235JMKX', 11000.00, 'Pauline Estrada', 'Administrative Office', '2026-02-13 16:00:00', 'Need Asap', 'Approved', 'pending', NULL, 'REQB20260214MOVQDZMV', '2026-02-14 05:41:09', '2026-02-14 05:41:09'),
(19, 'EXTPR-2026-0001', 'CONS20260214PUDSB', '[\"BATTERY-LN3 (DIN Size \\/ H6 Equivalent) (PROD20260214ETQIC) x1 @ \\u20b111000.00\"]', 'VEN202601235JMKX', 11000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'Approved', 'Completed', 'REQB20260214QD35C6J2', '2026-02-14 12:28:02', '2026-02-14 12:31:37'),
(20, 'REQN20260215UEYK1', 'CONS20260214HPZ9L', '[\"4X 16-Inch Wheel Covers (Snap-On, R16, Steel Rim)\"]', 'VEN202601235JMKX', 2000.00, 'Robert Quilenderino', 'Logistics Office', '2026-02-13 16:00:00', 'v', 'Approved', 'Approved', NULL, 'REQB20260214QD35C6J2', '2026-02-14 12:28:02', '2026-02-14 12:30:15'),
(21, 'EXTPR-2026-0001', 'CONS20260214Y2NOD', '[\"BATTERY-LN3 (DIN Size \\/ H6 Equivalent) (PROD20260214ETQIC) x1 @ \\u20b111000.00\"]', 'VEN202601235JMKX', 11000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214YPWI5B6U', '2026-02-14 12:50:28', '2026-02-14 12:50:28'),
(22, 'EXTPR-2026-0002', 'CONS20260214MAH5X', '[\"Toyota Glanza Alloy Wheel (PROD20260214OOI4K) x1 @ \\u20b19000.00\"]', 'VEN202601235JMKX', 9000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214YPWI5B6U', '2026-02-14 12:50:28', '2026-02-14 12:50:28'),
(23, 'EXTPR-2026-0003', 'CONS20260214KHVCL', '[\"Hyundai Creta \\u2013 16-Inch Alloy Wheels (PROD20260214JOY7C) x1 @ \\u20b155000.00\"]', 'VEN202601235JMKX', 55000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214YPWI5B6U', '2026-02-14 12:50:28', '2026-02-14 12:50:28'),
(24, 'EXTPR-2026-0004', 'CONS2026021476NEG', '[\"2018-2019 Toyota Camry NON-AFS Headlight \\u2013 OEM (PROD20260214JROKA) x1 @ \\u20b130000.00\"]', 'VEN202601235JMKX', 30000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214YPWI5B6U', '2026-02-14 12:50:28', '2026-02-14 12:50:28'),
(25, 'EXTPR-2026-0005', 'CONS20260214NSBQY', '[\"Toyota Camry SE 2018-2020 Headlight \\u2013 Left (Driver Side, Aftermarket) (PROD202602141JHN0) x1 @ \\u20b114000.00\"]', 'VEN202601235JMKX', 14000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214YPWI5B6U', '2026-02-14 12:50:28', '2026-02-14 12:50:28'),
(26, 'EXTPR-2026-0007', 'CONS20260214SEXG6', '[\"SPARCO-CORSA Steering Wheel Cover (Black, Japan Style) (PROD20260214FGH9V) x1 @ \\u20b11200.00\"]', 'VEN202601235JMKX', 1200.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214YPWI5B6U', '2026-02-14 12:50:28', '2026-02-14 12:50:28'),
(27, 'EXTPR-2026-0008', 'CONS202602142NUIM', '[\"Toyota Red Suede Steering Booster \\/ Wheel Cover (Non-Slip) (PROD20260214GC7O0) x1 @ \\u20b1400.00\"]', 'VEN202601235JMKX', 400.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214YPWI5B6U', '2026-02-14 12:50:28', '2026-02-14 12:50:28'),
(28, 'REQN20260214XNJ24', 'CONS20260214RL57T', '[\"Pouf Classic (x2)\",\"Cabinet (x2)\",\"Mirror (x2)\",\"Lamp (x2)\",\"Bar Stool (x2)\",\"Wardrobe (x2)\",\"Sofa (x2)\",\"Round Table (x2)\",\"Filing Cabinet (x2)\",\"Gaming Chair (x2)\"]', 'VEN20260208OZRGI', 76994.00, 'John Paul Natividad', 'Human Resource Department', '2026-02-13 16:00:00', 'Notes / Remarks', 'Approved', 'pending', NULL, 'REQB20260214UWZLQXRB', '2026-02-14 13:24:22', '2026-02-14 13:24:22'),
(29, 'EXTPR-2026-0001', 'CONS20260214TQ1XA', '[\"BATTERY-LN3 (DIN Size \\/ H6 Equivalent) (PROD20260214ETQIC) x1 @ \\u20b111000.00\"]', 'VEN202601235JMKX', 11000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214UWZLQXRB', '2026-02-14 13:24:22', '2026-02-14 13:24:22'),
(30, 'EXTPR-2026-0002', 'CONS20260214KGEMC', '[\"Toyota Glanza Alloy Wheel (PROD20260214OOI4K) x1 @ \\u20b19000.00\"]', 'VEN202601235JMKX', 9000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214UWZLQXRB', '2026-02-14 13:24:22', '2026-02-14 13:24:22'),
(31, 'EXTPR-2026-0003', 'CONS20260214H1WF6', '[\"Hyundai Creta \\u2013 16-Inch Alloy Wheels (PROD20260214JOY7C) x1 @ \\u20b155000.00\"]', 'VEN202601235JMKX', 55000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214UWZLQXRB', '2026-02-14 13:24:22', '2026-02-14 13:24:22'),
(32, 'EXTPR-2026-0004', 'CONS20260214CVTZ6', '[\"2018-2019 Toyota Camry NON-AFS Headlight \\u2013 OEM (PROD20260214JROKA) x1 @ \\u20b130000.00\"]', 'VEN202601235JMKX', 30000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214UWZLQXRB', '2026-02-14 13:24:22', '2026-02-14 13:24:22'),
(33, 'EXTPR-2026-0005', 'CONS20260214SZ5B9', '[\"Toyota Camry SE 2018-2020 Headlight \\u2013 Left (Driver Side, Aftermarket) (PROD202602141JHN0) x1 @ \\u20b114000.00\"]', 'VEN202601235JMKX', 14000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214UWZLQXRB', '2026-02-14 13:24:22', '2026-02-14 13:24:22'),
(34, 'EXTPR-2026-0007', 'CONS20260214WDOOF', '[\"SPARCO-CORSA Steering Wheel Cover (Black, Japan Style) (PROD20260214FGH9V) x1 @ \\u20b11200.00\"]', 'VEN202601235JMKX', 1200.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214UWZLQXRB', '2026-02-14 13:24:22', '2026-02-14 13:24:22'),
(35, 'EXTPR-2026-0008', 'CONS202602141SBZZ', '[\"Toyota Red Suede Steering Booster \\/ Wheel Cover (Non-Slip) (PROD20260214GC7O0) x1 @ \\u20b1400.00\"]', 'VEN202601235JMKX', 400.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214UWZLQXRB', '2026-02-14 13:24:22', '2026-02-14 13:24:22'),
(36, 'EXTPR-2026-0013', 'CONS20260214KQCSH', '[\"Mitsubishi Fuso Canter FE73 (6-Wheeler) (PROD20260209WNKAJ) x1 @ \\u20b12400000.00\"]', 'VEN202601235JMKX', 2400000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214UWZLQXRB', '2026-02-14 13:24:22', '2026-02-14 13:24:22'),
(37, 'EXTPR-2026-0014', 'CONS20260214D7NJ6', '[\"2012 Isuzu NPR (PROD202602095J9D5) x1 @ \\u20b1980000.00\"]', 'VEN202601235JMKX', 980000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214UWZLQXRB', '2026-02-14 13:24:22', '2026-02-14 13:24:22'),
(38, 'EXTPR-2026-0015', 'CONS20260214SQHAK', '[\"Honda CB500 (PROD20260209G0UJU) x1 @ \\u20b1379000.00\"]', 'VEN202601235JMKX', 379000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214UWZLQXRB', '2026-02-14 13:24:22', '2026-02-14 13:24:22'),
(39, 'EXTPR-2026-0016', 'CONS20260214GECOJ', '[\"Toyota Hiace (PROD202602098QOMK) x1 @ \\u20b11379000.00\"]', 'VEN202601235JMKX', 1379000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB20260214UWZLQXRB', '2026-02-14 13:24:22', '2026-02-14 13:24:22'),
(40, 'EXTPR-2026-0001', 'CONS202602141VFSE', '[\"BATTERY-LN3 (DIN Size \\/ H6 Equivalent) (PROD20260214ETQIC) x1 @ \\u20b111000.00\"]', 'VEN202601235JMKX', 11000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB202602141DVK4YV4', '2026-02-14 14:15:36', '2026-02-14 14:15:36'),
(41, 'EXTPR-2026-0002', 'CONS20260214EETD9', '[\"Toyota Glanza Alloy Wheel (PROD20260214OOI4K) x1 @ \\u20b19000.00\"]', 'VEN202601235JMKX', 9000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB202602141DVK4YV4', '2026-02-14 14:15:36', '2026-02-14 14:15:36'),
(42, 'EXTPR-2026-0003', 'CONS20260214LPBOP', '[\"Hyundai Creta \\u2013 16-Inch Alloy Wheels (PROD20260214JOY7C) x1 @ \\u20b155000.00\"]', 'VEN202601235JMKX', 55000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB202602141DVK4YV4', '2026-02-14 14:15:36', '2026-02-14 14:15:36'),
(43, 'EXTPR-2026-0004', 'CONS20260214WHIBK', '[\"2018-2019 Toyota Camry NON-AFS Headlight \\u2013 OEM (PROD20260214JROKA) x1 @ \\u20b130000.00\"]', 'VEN202601235JMKX', 30000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB202602141DVK4YV4', '2026-02-14 14:15:36', '2026-02-14 14:15:36'),
(44, 'EXTPR-2026-0005', 'CONS20260214WCQFK', '[\"Toyota Camry SE 2018-2020 Headlight \\u2013 Left (Driver Side, Aftermarket) (PROD202602141JHN0) x1 @ \\u20b114000.00\"]', 'VEN202601235JMKX', 14000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB202602141DVK4YV4', '2026-02-14 14:15:36', '2026-02-14 14:15:36'),
(45, 'EXTPR-2026-0007', 'CONS20260214SZLWG', '[\"SPARCO-CORSA Steering Wheel Cover (Black, Japan Style) (PROD20260214FGH9V) x1 @ \\u20b11200.00\"]', 'VEN202601235JMKX', 1200.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB202602141DVK4YV4', '2026-02-14 14:15:36', '2026-02-14 14:15:36'),
(46, 'EXTPR-2026-0008', 'CONS20260214SX2BT', '[\"Toyota Red Suede Steering Booster \\/ Wheel Cover (Non-Slip) (PROD20260214GC7O0) x1 @ \\u20b1400.00\"]', 'VEN202601235JMKX', 400.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB202602141DVK4YV4', '2026-02-14 14:15:36', '2026-02-14 14:15:36'),
(47, 'EXTPR-2026-0012', 'CONS202602149N8KO', '[\"Mitsubishi Fuso Canter Cruise (Minibus) (PROD20260209HKZNM) x1 @ \\u20b12600000.00\"]', 'VEN202601235JMKX', 2600000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB202602141DVK4YV4', '2026-02-14 14:15:36', '2026-02-14 14:15:36'),
(48, 'EXTPR-2026-0013', 'CONS20260214DIPSB', '[\"Mitsubishi Fuso Canter FE73 (6-Wheeler) (PROD20260209WNKAJ) x1 @ \\u20b12400000.00\"]', 'VEN202601235JMKX', 2400000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB202602141DVK4YV4', '2026-02-14 14:15:36', '2026-02-14 14:15:36'),
(49, 'EXTPR-2026-0014', 'CONS202602140RP0P', '[\"2012 Isuzu NPR (PROD202602095J9D5) x1 @ \\u20b1980000.00\"]', 'VEN202601235JMKX', 980000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB202602141DVK4YV4', '2026-02-14 14:15:36', '2026-02-14 14:15:36'),
(50, 'EXTPR-2026-0015', 'CONS20260214GOTIR', '[\"Honda CB500 (PROD20260209G0UJU) x1 @ \\u20b1379000.00\"]', 'VEN202601235JMKX', 379000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB202602141DVK4YV4', '2026-02-14 14:15:36', '2026-02-14 14:15:36'),
(51, 'EXTPR-2026-0016', 'CONS20260214GGCRY', '[\"Toyota Hiace (PROD202602098QOMK) x1 @ \\u20b11379000.00\"]', 'VEN202601235JMKX', 1379000.00, 'Via Jeves', 'Log2 Dept', '2026-02-13 16:00:00', NULL, 'Approved', 'pending', NULL, 'REQB202602141DVK4YV4', '2026-02-14 14:15:36', '2026-02-14 14:15:36');

-- --------------------------------------------------------

--
-- Table structure for table `psm_product`
--

CREATE TABLE `psm_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prod_id` varchar(255) NOT NULL,
  `prod_vendor` varchar(255) NOT NULL,
  `prod_name` varchar(255) NOT NULL,
  `prod_price` decimal(10,2) NOT NULL,
  `prod_stock` int(11) NOT NULL,
  `prod_type` enum('equipment','supplies','furniture','automotive') NOT NULL,
  `prod_warranty` varchar(255) NOT NULL DEFAULT 'no warranty',
  `prod_expiration` date DEFAULT NULL,
  `prod_desc` text DEFAULT NULL,
  `prod_picture` varchar(255) DEFAULT NULL,
  `prod_module_from` varchar(255) NOT NULL DEFAULT 'psm',
  `prod_submodule_from` varchar(255) NOT NULL DEFAULT 'vendor-management',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `psm_product`
--

INSERT INTO `psm_product` (`id`, `prod_id`, `prod_vendor`, `prod_name`, `prod_price`, `prod_stock`, `prod_type`, `prod_warranty`, `prod_expiration`, `prod_desc`, `prod_picture`, `prod_module_from`, `prod_submodule_from`, `created_at`, `updated_at`) VALUES
(3, 'PROD2026012396838', 'VEN20260114Z1JB0', 'Bondpaper size-A4 (100-pcs bundle)', 200.00, 200, 'supplies', '6 Month', NULL, 'A4-Size Hard Copy Bond paper 100 pcs in a pack.', 'images/product-picture/prod_1769858008_697de3d8b04d5.jpg', 'psm', 'vendor-product-management', '2026-01-23 01:22:17', '2026-01-31 03:13:28'),
(4, 'PROD202601232EW2T', 'VEN20260114Z1JB0', 'Ballpoint Pen (12pcs per pack)', 108.00, 100, 'supplies', '1 Month', NULL, '12pcs GP-2068 sign pen gel pen large capacity', 'images/product-picture/prod_1769858024_697de3e87b34f.jpg', 'psm', 'vendor-product-management', '2026-01-23 01:33:41', '2026-01-31 03:13:44'),
(5, 'PROD20260123O57W5', 'VEN20260114Z1JB0', 'Joy Stapler Promo Bundle 2s', 155.00, 100, 'supplies', '1 Month', NULL, '1 pc - Joy Stapler 605A and 1 box - Well\'s Staple Wire #35, 5,000 staples per box', 'images/product-picture/prod_1769858036_697de3f4e9765.jpg', 'psm', 'vendor-product-management', '2026-01-23 01:37:05', '2026-01-31 03:13:56'),
(6, 'PROD20260123WWVO5', 'VEN20260114Z1JB0', 'HBW Office Vinyl Coated Paper Clip 50mm', 53.00, 100, 'supplies', '1 Month', NULL, 'Plastic coated wire used to hold sheet together ideal for organizing papers and documents. Size: 50mm Quantity: 100 pcs / Small box', 'images/product-picture/prod_1769858047_697de3ffdd22a.jpg', 'psm', 'vendor-product-management', '2026-01-23 01:45:03', '2026-01-31 03:14:07'),
(8, 'PROD202602098QOMK', 'VEN202601235JMKX', 'Toyota Hiace', 1379000.00, 5, 'automotive', '1 Year', '2027-01-01', 'The 2019 Toyota HiAce is engineered for reliable fleet operations, offering a powerful 2.8L turbo-diesel engine, excellent load capacity, and rear-wheel drive for consistent performance. Enhanced safety systems, improved driver comfort, and low maintenance costs make it an ideal solution for logistics, shuttle services, and corporate fleets where uptime and efficiency matter most.', 'images/product-picture/prod_1770653118_698a05beae4d5.jpg', 'psm', 'vendor-product-management', '2026-02-09 08:05:18', '2026-02-09 08:05:54'),
(9, 'PROD20260209G0UJU', 'VEN202601235JMKX', 'Honda CB500', 379000.00, 10, 'automotive', '1 Year', '2027-01-01', 'The Honda CB500 is a versatile middleweight motorcycle designed for everyday riding and long-term reliability. Powered by a smooth and fuel-efficient 471 cc parallel-twin engine, it delivers balanced performance that’s easy to control yet confident on highways. With a comfortable riding position, modern styling, and standard ABS safety, the CB500 is ideal for new and experienced riders looking for a dependable, practical, and enjoyable all-round motorcycle.', 'images/product-picture/prod_1770653531_698a075b947be.jpeg', 'psm', 'vendor-product-management', '2026-02-09 08:12:11', '2026-02-09 08:12:11'),
(10, 'PROD202602095J9D5', 'VEN202601235JMKX', '2012 Isuzu NPR', 980000.00, 2, 'automotive', '1 Year', '2026-09-23', 'The 2012 Isuzu NPR is a dependable workhorse built to keep businesses moving. Known for its durability, fuel-efficient diesel performance, and low operating costs, it’s ideal for delivery, logistics, and service applications. Its cab-over design offers excellent visibility and tight turning radius, making urban driving and loading effortless. With a strong chassis and proven reliability, the Isuzu NPR remains a trusted choice for fleets that demand performance, uptime, and value.', 'images/product-picture/prod_1770657415_698a168765c7f.jpg', 'psm', 'vendor-product-management', '2026-02-09 09:16:55', '2026-02-09 09:16:55'),
(11, 'PROD20260209WNKAJ', 'VEN202601235JMKX', 'Mitsubishi Fuso Canter FE73 (6-Wheeler)', 2400000.00, 5, 'automotive', '5 Year', '2030-01-10', 'Built for heavier loads and tougher daily operations, the Fuso Canter FE73 delivers strong diesel performance, durability, and excellent stability in a compact 6-wheeler platform. Its reliable engine, reinforced chassis, and cab-over design make it ideal for logistics, construction support, utilities, and fleet hauling. Designed for efficiency and long service life, the FE73 is a trusted choice for businesses that need power, control, and dependable uptime.', 'images/product-picture/prod_1770657498_698a16da5cf1c.png', 'psm', 'vendor-product-management', '2026-02-09 09:18:18', '2026-02-09 09:18:18'),
(12, 'PROD20260209HKZNM', 'VEN202601235JMKX', 'Mitsubishi Fuso Canter Cruise (Minibus)', 2600000.00, 5, 'automotive', '5 Year', '2030-01-10', 'The Fuso Canter Cruise is a practical and dependable minibus designed for employee transport, school service, and shuttle operations. Built on the proven Canter platform, it offers a fuel-efficient diesel engine, stable ride quality, and a spacious, air-conditioned cabin for passenger comfort. With its durable construction, easy maintenance, and strong after-sales support in the Philippines, the Canter Cruise is a reliable solution for organizations that need safe, efficient, and long-lasting people movers.', 'images/product-picture/prod_1770657572_698a1724b00b9.png', 'psm', 'vendor-product-management', '2026-02-09 09:19:32', '2026-02-09 09:19:32'),
(14, 'PROD20260209ACB9K', 'VEN202601235JMKX', 'Suzuki Carry Utility Van 1.5L', 820000.00, 8, 'automotive', '2 Year', '2032-12-26', 'Compact, efficient, and built for everyday business, the Suzuki Carry Utility Van 1.5L is a practical solution for urban deliveries, service operations, and small fleets. Its fuel-efficient 1.5-liter engine, compact footprint, and spacious cargo area make it easy to maneuver through tight city streets while keeping operating costs low. Reliable and easy to maintain, the Carry Utility Van is ideal for businesses that need a dependable light commercial vehicle.', 'images/product-picture/prod_1770657673_698a17891ed66.png', 'psm', 'vendor-product-management', '2026-02-09 09:21:13', '2026-02-09 09:21:13'),
(15, 'PROD20260209SHP58', 'VEN202601235JMKX', 'Toyota Fortuner', 2200000.00, 2, 'automotive', '7 Year', '2031-11-10', 'Toyota Fortuner is a midsize SUV engineered for performance, comfort, and prestige. With its strong diesel engine options, rugged ladder-frame construction, and advanced safety features, the Fortuner is equally confident on city roads and rough terrain. A refined interior, commanding road presence, and Toyota’s proven reliability make it a top choice for executives, families, and corporate fleets that demand both capability and style.', 'images/product-picture/prod_1770657758_698a17decdd6c.png', 'psm', 'vendor-product-management', '2026-02-09 09:22:38', '2026-02-09 09:22:38'),
(16, 'PROD2026021021ARV', 'VEN20260208OZRGI', 'Gaming Chair', 500.00, 100, 'furniture', '1 Year', NULL, NULL, 'images/product-picture/prod_1770692452_698a9f64c9a42.jfif', 'psm', 'vendor-product-management', '2026-02-09 19:00:52', '2026-02-09 19:28:33'),
(17, 'PROD2026021062MDC', 'VEN20260208OZRGI', 'Filing Cabinet', 1600.00, 50, 'furniture', '1 Year', NULL, NULL, 'images/product-picture/prod_1770692927_698aa13f0ed4c.jfif', 'psm', 'vendor-product-management', '2026-02-09 19:08:47', '2026-02-09 19:27:22'),
(18, 'PROD20260210VRFV7', 'VEN20260208OZRGI', 'Round Table', 1200.00, 20, 'furniture', '1 Year', NULL, NULL, 'images/product-picture/prod_1770693307_698aa2bbd7d2f.jfif', 'psm', 'vendor-product-management', '2026-02-09 19:11:33', '2026-02-09 19:27:12'),
(19, 'PROD20260210PBZT5', 'VEN20260208OZRGI', 'Sofa', 6000.00, 100, 'furniture', '1 Year', NULL, NULL, 'images/product-picture/prod_1770693278_698aa29ee2f44.jfif', 'psm', 'vendor-product-management', '2026-02-09 19:14:38', '2026-02-09 19:26:45'),
(20, 'PROD20260210WZ1A8', 'VEN20260208OZRGI', 'Wardrobe', 7999.00, 50, 'furniture', '1 Year', NULL, NULL, 'images/product-picture/prod_1770693881_698aa4f982c5e.jfif', 'psm', 'vendor-product-management', '2026-02-09 19:24:41', '2026-02-09 19:24:41'),
(21, 'PROD202602102Q3Y5', 'VEN20260208OZRGI', 'Bar Stool', 700.00, 30, 'furniture', '1 Year', NULL, NULL, 'images/product-picture/prod_1770693939_698aa5332028a.jfif', 'psm', 'vendor-product-management', '2026-02-09 19:25:39', '2026-02-09 19:26:33'),
(22, 'PROD20260210B8DTQ', 'VEN20260208OZRGI', 'Lamp', 1500.00, 50, 'furniture', '1 Year', NULL, NULL, 'images/product-picture/prod_1770693978_698aa55a51b6c.jfif', 'psm', 'vendor-product-management', '2026-02-09 19:26:18', '2026-02-09 19:26:56'),
(23, 'PROD20260210MIJ9E', 'VEN20260208OZRGI', 'Mirror', 4000.00, 10, 'furniture', '7 Day', NULL, NULL, 'images/product-picture/prod_1770694168_698aa618a5333.jfif', 'psm', 'vendor-product-management', '2026-02-09 19:29:28', '2026-02-09 19:29:28'),
(24, 'PROD20260210BNZG7', 'VEN20260208OZRGI', 'Cabinet', 8999.00, 50, 'furniture', '1 Year', NULL, NULL, 'images/product-picture/prod_1770694266_698aa67a118bb.jfif', 'psm', 'vendor-product-management', '2026-02-09 19:31:06', '2026-02-09 19:31:06'),
(25, 'PROD202602108K5LC', 'VEN20260208OZRGI', 'Pouf Classic', 5999.00, 100, 'furniture', '6 Month', NULL, NULL, 'images/product-picture/prod_1770694330_698aa6ba48477.jfif', 'psm', 'vendor-product-management', '2026-02-09 19:32:10', '2026-02-09 19:32:10'),
(26, 'PROD202602104UJJE', 'VEN20260111NE5VZ', 'COMIX S3508D (8 sheets, 22L Micro Cut Heavy Duty Paper Shredder)', 13560.00, 5, 'equipment', '1 Year', '2027-02-11', 'A paper shredder is an office equipment designed for the secure disposal of confidential and sensitive documents. It efficiently destroys paper materials by cutting them into small strips or particles, ensuring information confidentiality and compliance with data protection requirements.', 'images/product-picture/prod_1770765237_698bbbb569f4c.jpeg', 'psm', 'vendor-product-management', '2026-02-10 15:13:57', '2026-02-10 15:14:32'),
(27, 'PROD20260211L2K2E', 'VEN20260111NE5VZ', 'ThinkPad E16 AMD G2', 536700.00, 23, 'equipment', '1 Year', '2027-02-11', 'AMD Ryzen™ 5 7535U Processor (2.90 GHz up to 4.55 GHz)\r\nWindows 11 Pro 64\r\nIntegrated AMD Radeon™ 660M\r\n16 GB DDR5-4800MT/s (SODIMM)\r\n512 GB SSD M.2 2242 PCIe Gen4 TLC Opal', 'images/product-picture/prod_1770775805_698be4fd38806.webp', 'psm', 'vendor-product-management', '2026-02-10 18:10:05', '2026-02-10 18:10:05'),
(28, 'PROD20260211FUJXJ', 'VEN20260111NE5VZ', 'Canon Pixma G3010 All-in-One Ink Tank Printer (Wi-Fi Connectivity, 2315C018AB, Black)', 8732.00, 10, 'equipment', '1 Year', '2027-02-11', 'From the manufacturer Small and standard ink bottles are available to meet the different requirement in print volume. Ink refill is now made easy. Each ink bottle nozzle is designed to fit seamlessly only into the designated ink tank, eliminating potential mixing accidents. The easy-to-replace maintenance cartridge makes maintenance fuss-free and extends printer lifespan even when printing in high volumes. Print from smartphones, tablets, laptops and cloud storage with the Canon Print Inkjet/SELPHY, and Canon Easy-PhotoPrint Editor apps. The printer’s compact footprint allows it to fit into tight spaces with ease.', 'images/product-picture/prod_1770810099_698c6af3b755c.webp', 'psm', 'vendor-product-management', '2026-02-11 03:41:39', '2026-02-11 03:41:39'),
(29, 'PROD20260211EC7RA', 'VEN20260111NE5VZ', '￼HBW Office Heavy Duty Puncher', 167.00, 17, 'equipment', '1 Year', '2027-02-11', 'Punch hole An office tool used to make small, round holes in paper so the sheets can be filed neatly in a binder or folder.', 'images/product-picture/prod_1770811900_698c71fc9c8f3.webp', 'psm', 'vendor-product-management', '2026-02-11 04:11:40', '2026-02-11 04:11:40'),
(30, 'PROD202602149UIC7', 'VEN202601235JMKX', '4X 16-Inch Wheel Covers (Snap-On, R16, Steel Rim)', 2000.00, 80, 'automotive', '5 Month', '2027-02-02', 'Plastic snap-on hub caps designed for R16 tires with steel rims. Used mainly for aesthetics to mimic alloy wheels and protect rims from dirt and minor scratches. Universal fit but confirm clip design compatibility.', 'images/product-picture/prod_1771074662_699074662b40b.png', 'psm', 'vendor-product-management', '2026-02-14 05:11:02', '2026-02-14 05:11:02'),
(31, 'PROD20260214GC7O0', 'VEN202601235JMKX', 'Toyota Red Suede Steering Booster / Wheel Cover (Non-Slip)', 400.00, 20, 'automotive', '5 Month', '2027-02-01', 'Typically refers to a steering wheel booster knob or suede-style grip accessory. Improves steering control during parking or tight maneuvers. Red suede finish for styling; clamps onto steering wheel.', 'images/product-picture/prod_1771074723_699074a3caf3b.png', 'psm', 'vendor-product-management', '2026-02-14 05:12:03', '2026-02-14 05:12:03'),
(32, 'PROD20260214FGH9V', 'VEN202601235JMKX', 'SPARCO-CORSA Steering Wheel Cover (Black, Japan Style)', 1200.00, 20, 'automotive', '5 Month', '2027-02-02', 'Aftermarket steering wheel cover inspired by Sparco Corsa design. Provides better grip, protects OEM wheel from wear, and enhances interior look. Usually rubber/leatherette material.', 'images/product-picture/prod_1771074791_699074e7f0ea4.png', 'psm', 'vendor-product-management', '2026-02-14 05:13:11', '2026-02-14 05:13:11'),
(33, 'PROD20260214GS6LR', 'VEN202601235JMKX', '2018-2023 Toyota Camry Headlight Assembly – OEM Right (Passenger Side)', 35000.00, 10, 'automotive', '5 Month', '2027-02-14', 'Original Equipment Manufacturer (OEM) headlamp assembly for Toyota Camry XV70. Exact fit, factory quality, includes housing and lens. Higher price due to genuine parts and import costs.', 'images/product-picture/prod_1771074852_69907524de3f7.png', 'psm', 'vendor-product-management', '2026-02-14 05:14:12', '2026-02-14 05:14:12'),
(34, 'PROD202602141JHN0', 'VEN202601235JMKX', 'Toyota Camry SE 2018-2020 Headlight – Left (Driver Side, Aftermarket)', 14000.00, 20, 'automotive', '5 Month', '2027-02-14', 'Replacement headlamp for Camry SE models. Usually aftermarket, lower cost than OEM. Fitment generally good but material quality and light output may differ from genuine units.', 'images/product-picture/prod_1771074961_69907591ef367.png', 'psm', 'vendor-product-management', '2026-02-14 05:16:01', '2026-02-14 05:16:01'),
(35, 'PROD20260214JROKA', 'VEN202601235JMKX', '2018-2019 Toyota Camry NON-AFS Headlight – OEM', 30000.00, 15, 'automotive', '5 Month', '2027-02-14', 'OEM headlamp for Camry models without AFS (Adaptive Front-lighting System). Must match vehicle variant; NON-AFS units differ from AFS versions internally.', 'images/product-picture/prod_1771075072_699076008f919.png', 'psm', 'vendor-product-management', '2026-02-14 05:17:52', '2026-02-14 05:17:52'),
(36, 'PROD20260214JOY7C', 'VEN202601235JMKX', 'Hyundai Creta – 16-Inch Alloy Wheels', 55000.00, 40, 'automotive', '7 Month', '2027-02-14', 'Aluminum alloy rims for Hyundai Creta. Lighter than steel wheels, better heat dissipation, improves appearance. Price depends on design, brand, and whether OEM or replica.', NULL, 'psm', 'vendor-product-management', '2026-02-14 05:19:50', '2026-02-14 05:19:50'),
(37, 'PROD20260214OOI4K', 'VEN202601235JMKX', 'Toyota Glanza Alloy Wheel', 9000.00, 10, 'automotive', '7 Month', '2027-02-14', 'Alloy rim compatible with Toyota Glanza (similar platform to Suzuki Baleno). Ensure correct PCD, offset, and hub bore for proper fitment.', 'images/product-picture/prod_1771075361_6990772138388.jpg', 'psm', 'vendor-product-management', '2026-02-14 05:22:16', '2026-02-14 05:22:41'),
(38, 'PROD20260214ETQIC', 'VEN202601235JMKX', 'BATTERY-LN3 (DIN Size / H6 Equivalent)', 11000.00, 20, 'automotive', '6 Month', '2027-02-14', 'European DIN-type automotive battery commonly used in modern sedans and SUVs. Higher cold-cranking amps (CCA) and larger capacity than small batteries. Price varies by brand (Motolite, Amaron, Bosch, etc.).', 'images/product-picture/prod_1771075407_6990774f5aeef.jpg', 'psm', 'vendor-product-management', '2026-02-14 05:23:27', '2026-02-14 05:23:27');

-- --------------------------------------------------------

--
-- Table structure for table `psm_purcahse_request`
--

CREATE TABLE `psm_purcahse_request` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `preq_id` varchar(255) NOT NULL,
  `preq_name_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preq_name_items`)),
  `preq_unit` int(11) NOT NULL DEFAULT 0,
  `preq_total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `preq_ven_id` varchar(255) DEFAULT NULL,
  `preq_ven_company_name` varchar(255) DEFAULT NULL,
  `preq_ven_type` varchar(255) DEFAULT NULL,
  `preq_status` varchar(255) NOT NULL DEFAULT 'Pending',
  `preq_process` varchar(255) DEFAULT NULL,
  `preq_order_by` varchar(255) DEFAULT NULL,
  `preq_desc` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `psm_purcahse_request`
--

INSERT INTO `psm_purcahse_request` (`id`, `preq_id`, `preq_name_items`, `preq_unit`, `preq_total_amount`, `preq_ven_id`, `preq_ven_company_name`, `preq_ven_type`, `preq_status`, `preq_process`, `preq_order_by`, `preq_desc`, `created_at`, `updated_at`) VALUES
(2, 'PURC20260213AT7B9', '[{\"name\":\"Bondpaper size-A4 (100-pcs bundle)\",\"price\":200,\"warranty\":\"6 Month\",\"expiration\":null},{\"name\":\"Bondpaper size-A4 (100-pcs bundle)\",\"price\":200,\"warranty\":\"6 Month\",\"expiration\":null},{\"name\":\"Bondpaper size-A4 (100-pcs bundle)\",\"price\":200,\"warranty\":\"6 Month\",\"expiration\":null},{\"name\":\"Bondpaper size-A4 (100-pcs bundle)\",\"price\":200,\"warranty\":\"6 Month\",\"expiration\":null},{\"name\":\"Bondpaper size-A4 (100-pcs bundle)\",\"price\":200,\"warranty\":\"6 Month\",\"expiration\":null},{\"name\":\"Bondpaper size-A4 (100-pcs bundle)\",\"price\":200,\"warranty\":\"6 Month\",\"expiration\":null},{\"name\":\"Bondpaper size-A4 (100-pcs bundle)\",\"price\":200,\"warranty\":\"6 Month\",\"expiration\":null},{\"name\":\"Bondpaper size-A4 (100-pcs bundle)\",\"price\":200,\"warranty\":\"6 Month\",\"expiration\":null},{\"name\":\"Bondpaper size-A4 (100-pcs bundle)\",\"price\":200,\"warranty\":\"6 Month\",\"expiration\":null},{\"name\":\"Bondpaper size-A4 (100-pcs bundle)\",\"price\":200,\"warranty\":\"6 Month\",\"expiration\":null}]', 10, 2000.00, NULL, 'OfficeSupplies Depot', 'supplies', 'Pending', NULL, 'Ric Jason Altamante - superadmin', 'Converted from Consolidated Request: CONS20260213U3WVU. Original Department: Human Resource Department', '2026-02-13 17:23:20', '2026-02-13 17:23:20');

-- --------------------------------------------------------

--
-- Table structure for table `psm_purchase`
--

CREATE TABLE `psm_purchase` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pur_id` varchar(255) NOT NULL,
  `pur_name_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`pur_name_items`)),
  `pur_unit` int(11) NOT NULL,
  `pur_total_amount` decimal(12,2) NOT NULL,
  `pur_company_name` varchar(255) NOT NULL,
  `pur_ven_type` enum('equipment','supplies','furniture','automotive') NOT NULL,
  `pur_status` enum('Pending','Approved','Rejected','Cancel','PO Received','Processing Order','Dispatched','Delivered','Vendor-Review','In-Progress','Completed') NOT NULL DEFAULT 'Pending',
  `pur_order_by` varchar(255) DEFAULT NULL,
  `pur_desc` text DEFAULT NULL,
  `pur_warranty` text DEFAULT NULL,
  `pur_expiration` text DEFAULT NULL,
  `pur_delivery_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pur_department_from` varchar(255) NOT NULL DEFAULT 'Logistics 1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `psm_purchase`
--

INSERT INTO `psm_purchase` (`id`, `pur_id`, `pur_name_items`, `pur_unit`, `pur_total_amount`, `pur_company_name`, `pur_ven_type`, `pur_status`, `pur_order_by`, `pur_desc`, `pur_warranty`, `pur_expiration`, `pur_delivery_date`, `created_at`, `updated_at`, `pur_department_from`) VALUES
(53, 'PURC20260213575QA', '[{\"name\":\"Bondpaper size-A4 (100-pcs bundle)\",\"price\":200,\"warranty\":\"6 Month\",\"expiration\":null},{\"name\":\"Ballpoint Pen (12pcs per pack)\",\"price\":108,\"warranty\":\"1 Month\",\"expiration\":null},{\"name\":\"Joy Stapler Promo Bundle 2s\",\"price\":155,\"warranty\":\"1 Month\",\"expiration\":null},{\"name\":\"HBW Office Vinyl Coated Paper Clip 50mm\",\"price\":53,\"warranty\":\"1 Month\",\"expiration\":null}]', 4, 516.00, 'OfficeSupplies Depot', 'supplies', 'Delivered', 'Tomas Finland', 'Converted from Consolidated Request: CONS20260213WHZPV. Original Department: N/A', '[{\"item\":\"Bondpaper size-A4 (100-pcs bundle)\",\"warranty_end\":\"2026-08-19\",\"original_warranty\":\"6 Month\"},{\"item\":\"Ballpoint Pen (12pcs per pack)\",\"warranty_end\":\"2026-03-17\",\"original_warranty\":\"1 Month\"},{\"item\":\"Joy Stapler Promo Bundle 2s\",\"warranty_end\":\"2026-03-17\",\"original_warranty\":\"1 Month\"},{\"item\":\"HBW Office Vinyl Coated Paper Clip 50mm\",\"warranty_end\":\"2026-03-17\",\"original_warranty\":\"1 Month\"}]', NULL, '2026-02-16', '2026-02-13 10:53:00', '2026-02-13 17:24:33', 'Logistics 1'),
(54, 'PURC20260214GQNUS', '[{\"name\":\"Filing Cabinet\",\"price\":1600,\"warranty\":\"1 Year\",\"expiration\":null}]', 1, 1600.00, 'LhilFarniture', 'furniture', 'Delivered', 'Lara Craft / Core Transaction Office', 'Converted from Consolidated Request: CONS20260213GSFCT. Original Department: Core Transaction Office', '[{\"item\":\"Filing Cabinet\",\"warranty_end\":\"2027-02-14\",\"original_warranty\":\"1 Year\"}]', NULL, '2026-02-17', '2026-02-13 17:29:15', '2026-02-14 14:46:29', 'Logistics 1'),
(55, 'PURC20260214O3IGY', '[{\"name\":\"Honda CB500\",\"price\":379000,\"warranty\":\"1 Year\",\"expiration\":\"2027-01-01T00:00:00.000000Z\"}]', 1, 379000.00, 'AutoButchoy', 'automotive', 'Delivered', 'Robert Quilenderino / Logistics Office', 'Converted from Consolidated Request: CONS20260213Y5PDH. Original Department: Logistics Office', '[{\"item\":\"Honda CB500\",\"warranty_end\":\"2027-02-14\",\"original_warranty\":\"1 Year\"}]', '[{\"item\":\"Honda CB500\",\"expiration_date\":\"2027-01-01\"}]', NULL, '2026-02-13 17:29:37', '2026-02-14 15:54:49', 'Logistics 1'),
(56, 'PURC202602144KH62', '[{\"name\":\"Mitsubishi Fuso Canter Cruise (Minibus)\",\"price\":2600000,\"warranty\":\"5 Year\",\"expiration\":\"2030-01-10T00:00:00.000000Z\"}]', 1, 2600000.00, 'AutoButchoy', 'automotive', 'Delivered', 'Robert Quilenderino / Logistics Office', 'Converted from Consolidated Request: CONS20260214W37QI. Original Department: Logistics Office', '[{\"item\":\"Mitsubishi Fuso Canter Cruise (Minibus)\",\"warranty_end\":\"2031-02-13\",\"original_warranty\":\"5 Year\"}]', '[{\"item\":\"Mitsubishi Fuso Canter Cruise (Minibus)\",\"expiration_date\":\"2030-01-10\"}]', '2026-02-18', '2026-02-14 00:12:00', '2026-02-14 14:51:04', 'Logistics 1'),
(57, 'PURC20260214BXFBP', '[{\"name\":\"Mitsubishi Fuso Canter FE73 (6-Wheeler)\",\"price\":2400000,\"warranty\":\"5 Year\",\"expiration\":\"2030-01-10T00:00:00.000000Z\"}]', 1, 2400000.00, 'AutoButchoy', 'automotive', 'Delivered', 'Robert Quilenderino / Logistics Office', 'Converted from Consolidated Request: CONS202602147DAYD. Original Department: Logistics Office', '[{\"item\":\"Mitsubishi Fuso Canter FE73 (6-Wheeler)\",\"warranty_end\":\"2031-02-13\",\"original_warranty\":\"5 Year\"}]', '[{\"item\":\"Mitsubishi Fuso Canter FE73 (6-Wheeler)\",\"expiration_date\":\"2030-01-10\"}]', '2026-02-17', '2026-02-14 00:12:37', '2026-02-14 15:55:34', 'Logistics 1'),
(58, 'PURC202602149DT5E', '[{\"name\":\"2012 Isuzu NPR\",\"price\":980000,\"warranty\":\"1 Year\",\"expiration\":\"2026-09-23T00:00:00.000000Z\"}]', 1, 980000.00, 'AutoButchoy', 'automotive', 'Delivered', 'Robert Quilenderino / Logistics Office', 'Converted from Consolidated Request: CONS20260214EVZSD. Original Department: Logistics Office', '[{\"item\":\"2012 Isuzu NPR\",\"warranty_end\":\"2027-02-15\",\"original_warranty\":\"1 Year\"}]', '[{\"item\":\"2012 Isuzu NPR\",\"expiration_date\":\"2026-09-23\"}]', '2026-02-20', '2026-02-14 00:13:35', '2026-02-14 16:02:18', 'Logistics 1'),
(59, 'PURC20260214BWO5M', '[{\"name\":\"Honda CB500\",\"price\":379000,\"warranty\":\"1 Year\",\"expiration\":\"2027-01-01T00:00:00.000000Z\"}]', 1, 379000.00, 'AutoButchoy', 'automotive', 'Delivered', 'Robert Quilenderino / Logistics Office', 'Converted from Consolidated Request: CONS202602142TDUK. Original Department: Logistics Office', '[{\"item\":\"Honda CB500\",\"warranty_end\":\"2027-02-14\",\"original_warranty\":\"1 Year\"}]', '[{\"item\":\"Honda CB500\",\"expiration_date\":\"2027-01-01\"}]', '2026-02-19', '2026-02-14 00:13:54', '2026-02-14 14:51:10', 'Logistics 1'),
(60, 'PURC20260214HO5HA', '[{\"name\":\"Bondpaper size-A4 (100-pcs bundle)\",\"price\":200,\"warranty\":\"6 Month\",\"expiration\":null},{\"name\":\"Ballpoint Pen (12pcs per pack)\",\"price\":108,\"warranty\":\"1 Month\",\"expiration\":null},{\"name\":\"Joy Stapler Promo Bundle 2s\",\"price\":155,\"warranty\":\"1 Month\",\"expiration\":null},{\"name\":\"HBW Office Vinyl Coated Paper Clip 50mm\",\"price\":53,\"warranty\":\"1 Month\",\"expiration\":null}]', 4, 516.00, 'OfficeSupplies Depot', 'supplies', 'Delivered', 'Robert Quilenderino / Administrative Office', 'Converted from Consolidated Request: CONS20260214BRRVB. Original Department: Administrative Office', '[{\"item\":\"Bondpaper size-A4 (100-pcs bundle)\",\"warranty_end\":\"2026-08-19\",\"original_warranty\":\"6 Month\"},{\"item\":\"Ballpoint Pen (12pcs per pack)\",\"warranty_end\":\"2026-03-17\",\"original_warranty\":\"1 Month\"},{\"item\":\"Joy Stapler Promo Bundle 2s\",\"warranty_end\":\"2026-03-17\",\"original_warranty\":\"1 Month\"},{\"item\":\"HBW Office Vinyl Coated Paper Clip 50mm\",\"warranty_end\":\"2026-03-17\",\"original_warranty\":\"1 Month\"}]', NULL, '2026-02-15', '2026-02-14 00:14:15', '2026-02-14 04:36:11', 'Logistics 1'),
(61, 'PURC202602141CWCA', '[{\"name\":\"COMIX S3508D (8 sheets, 22L Micro Cut Heavy Duty Paper Shredder)\",\"price\":13560,\"warranty\":\"1 Year\",\"expiration\":\"2027-02-11T00:00:00.000000Z\"},{\"name\":\"ThinkPad E16 AMD G2\",\"price\":536700,\"warranty\":\"1 Year\",\"expiration\":\"2027-02-11T00:00:00.000000Z\"},{\"name\":\"Canon Pixma G3010 All-in-One Ink Tank Printer (Wi-Fi Connectivity, 2315C018AB, Black)\",\"price\":8732,\"warranty\":\"1 Year\",\"expiration\":\"2027-02-11T00:00:00.000000Z\"},{\"name\":\"\\ufffcHBW Office Heavy Duty Puncher\",\"price\":167,\"warranty\":\"1 Year\",\"expiration\":\"2027-02-11T00:00:00.000000Z\"}]', 4, 559159.00, 'IcyTools', 'equipment', 'Delivered', 'Tomas Finland / Core Transaction Office', 'Converted from Consolidated Request: CONS20260214V4ZKF. Original Department: Core Transaction Office', '[{\"item\":\"COMIX S3508D (8 sheets, 22L Micro Cut Heavy Duty Paper Shredder)\",\"warranty_end\":\"2027-02-14\",\"original_warranty\":\"1 Year\"},{\"item\":\"ThinkPad E16 AMD G2\",\"warranty_end\":\"2027-02-14\",\"original_warranty\":\"1 Year\"},{\"item\":\"Canon Pixma G3010 All-in-One Ink Tank Printer (Wi-Fi Connectivity, 2315C018AB, Black)\",\"warranty_end\":\"2027-02-14\",\"original_warranty\":\"1 Year\"},{\"item\":\"\\ufffcHBW Office Heavy Duty Puncher\",\"warranty_end\":\"2027-02-14\",\"original_warranty\":\"1 Year\"}]', '[{\"item\":\"COMIX S3508D (8 sheets, 22L Micro Cut Heavy Duty Paper Shredder)\",\"expiration_date\":\"2027-02-11\"},{\"item\":\"ThinkPad E16 AMD G2\",\"expiration_date\":\"2027-02-11\"},{\"item\":\"Canon Pixma G3010 All-in-One Ink Tank Printer (Wi-Fi Connectivity, 2315C018AB, Black)\",\"expiration_date\":\"2027-02-11\"},{\"item\":\"\\ufffcHBW Office Heavy Duty Puncher\",\"expiration_date\":\"2027-02-11\"}]', '2026-02-17', '2026-02-14 00:14:37', '2026-02-14 14:48:27', 'Logistics 1'),
(62, 'PURC20260214D0AZ9', '[{\"name\":\"Toyota Hiace\",\"price\":1379000,\"warranty\":\"1 Year\",\"expiration\":\"2027-01-01T00:00:00.000000Z\"}]', 1, 1379000.00, 'AutoButchoy', 'automotive', 'Delivered', 'Robert Quilenderino / Logistics Office', 'Converted from Consolidated Request: CONS20260214LRNPZ. Original Department: Logistics Office', '[{\"item\":\"Toyota Hiace\",\"warranty_end\":\"2027-02-14\",\"original_warranty\":\"1 Year\"}]', '[{\"item\":\"Toyota Hiace\",\"expiration_date\":\"2027-01-01\"}]', '2026-02-16', '2026-02-14 00:14:56', '2026-02-14 14:51:17', 'Logistics 1'),
(63, 'PURC202602141UOMC', '[{\"name\":\"Filing Cabinet\",\"price\":1600,\"warranty\":\"1 Year\",\"expiration\":null}]', 1, 1600.00, 'LhilFarniture', 'furniture', 'Delivered', 'James Carter / Core Transaction Office', 'Converted from Consolidated Request: CONS20260214D4DAT. Original Department: Core Transaction Office', '[{\"item\":\"Filing Cabinet\",\"warranty_end\":\"2027-02-14\",\"original_warranty\":\"1 Year\"}]', NULL, '2026-02-19', '2026-02-14 05:43:47', '2026-02-14 14:46:32', 'Logistics 1'),
(64, 'PURC20260214R23WD', '[{\"name\":\"BATTERY-LN3 (DIN Size \\/ H6 Equivalent)\",\"price\":11000,\"warranty\":\"6 Month\",\"expiration\":\"2027-02-14T00:00:00.000000Z\"}]', 1, 11000.00, 'AutoButchoy', 'automotive', 'Delivered', 'Via Jeves / Log2 Dept', 'Converted from Consolidated Request: CONS20260214PUDSB. Original Department: Log2 Dept', '[{\"item\":\"BATTERY-LN3 (DIN Size \\/ H6 Equivalent)\",\"warranty_end\":\"2026-08-19\",\"original_warranty\":\"6 Month\"}]', '[{\"item\":\"BATTERY-LN3 (DIN Size \\/ H6 Equivalent)\",\"expiration_date\":\"2027-02-14\"}]', '2026-02-18', '2026-02-14 12:31:37', '2026-02-14 14:51:21', 'Logistics 1');

-- --------------------------------------------------------

--
-- Table structure for table `psm_purchase_product`
--

CREATE TABLE `psm_purchase_product` (
  `purcprod_id` varchar(100) NOT NULL,
  `purcprod_prod_id` varchar(255) DEFAULT NULL,
  `purcprod_prod_name` varchar(255) DEFAULT NULL,
  `purcprod_prod_price` decimal(15,2) DEFAULT NULL,
  `purcprod_prod_unit` varchar(255) DEFAULT NULL,
  `purcprod_prod_type` varchar(255) DEFAULT NULL,
  `purcprod_status` varchar(255) DEFAULT NULL,
  `purcprod_date` date DEFAULT NULL,
  `purcprod_warranty` varchar(255) DEFAULT NULL,
  `purcprod_expiration` varchar(255) DEFAULT NULL,
  `purcprod_desc` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `psm_purchase_product`
--

INSERT INTO `psm_purchase_product` (`purcprod_id`, `purcprod_prod_id`, `purcprod_prod_name`, `purcprod_prod_price`, `purcprod_prod_unit`, `purcprod_prod_type`, `purcprod_status`, `purcprod_date`, `purcprod_warranty`, `purcprod_expiration`, `purcprod_desc`, `created_at`, `updated_at`) VALUES
('PCPD2026021426TYG', 'PROD2026021062MDC', 'Filing Cabinet', 1600.00, '1', 'furniture', 'Delivered', '2026-02-14', '2027-02-14', NULL, 'Converted from Consolidated Request: CONS20260214D4DAT. Original Department: Core Transaction Office', '2026-02-14 14:46:32', '2026-02-14 14:46:32'),
('PCPD202602148GIAP', 'PROD2026021062MDC', 'Filing Cabinet', 1600.00, '1', 'furniture', 'Delivered', '2026-02-14', '2027-02-14', NULL, 'Converted from Consolidated Request: CONS20260213GSFCT. Original Department: Core Transaction Office', '2026-02-14 14:46:29', '2026-02-14 14:46:29'),
('PCPD20260214BZLST', 'PROD20260123WWVO5', 'HBW Office Vinyl Coated Paper Clip 50mm', 53.00, '1', 'supplies', 'Delivered', '2026-02-14', '2026-03-17', NULL, 'Converted from Consolidated Request: CONS20260214BRRVB. Original Department: Administrative Office', '2026-02-14 04:36:11', '2026-02-14 04:36:11'),
('PCPD20260214CVJOI', 'PROD20260209WNKAJ', 'Mitsubishi Fuso Canter FE73 (6-Wheeler)', 2400000.00, '1', 'automotive', 'Delivered', '2026-02-14', '2031-02-13', '2030-01-10', 'Converted from Consolidated Request: CONS202602147DAYD. Original Department: Logistics Office', '2026-02-14 15:55:34', '2026-02-14 15:55:34'),
('PCPD20260214ENJPH', 'PROD2026012396838', 'Bondpaper size-A4 (100-pcs bundle)', 200.00, '1', 'supplies', 'Delivered', '2026-02-14', '2026-08-19', NULL, 'Converted from Consolidated Request: CONS20260214BRRVB. Original Department: Administrative Office', '2026-02-14 04:36:11', '2026-02-14 04:36:11'),
('PCPD20260214GRAOY', 'PROD20260209HKZNM', 'Mitsubishi Fuso Canter Cruise (Minibus)', 2600000.00, '1', 'automotive', 'Delivered', '2026-02-14', '2031-02-13', '2030-01-10', 'Converted from Consolidated Request: CONS20260214W37QI. Original Department: Logistics Office', '2026-02-14 14:51:04', '2026-02-14 14:51:04'),
('PCPD20260214HVGKH', 'PROD202602098QOMK', 'Toyota Hiace', 1379000.00, '1', 'automotive', 'Delivered', '2026-02-14', '2027-02-14', '2027-01-01', 'Converted from Consolidated Request: CONS20260214LRNPZ. Original Department: Logistics Office', '2026-02-14 14:51:17', '2026-02-14 14:51:17'),
('PCPD20260214IDTZ4', 'PROD20260209G0UJU', 'Honda CB500', 379000.00, '1', 'automotive', 'Delivered', '2026-02-14', '2027-02-14', '2027-01-01', 'Converted from Consolidated Request: CONS202602142TDUK. Original Department: Logistics Office', '2026-02-14 14:51:10', '2026-02-14 14:51:10'),
('PCPD20260214JBYQB', 'PROD20260211L2K2E', 'ThinkPad E16 AMD G2', 536700.00, '1', 'equipment', 'Delivered', '2026-02-14', '2027-02-14', '2027-02-11', 'Converted from Consolidated Request: CONS20260214V4ZKF. Original Department: Core Transaction Office', '2026-02-14 14:48:27', '2026-02-14 14:48:27'),
('PCPD20260214KMBZB', 'PROD20260211FUJXJ', 'Canon Pixma G3010 All-in-One Ink Tank Printer (Wi-Fi Connectivity, 2315C018AB, Black)', 8732.00, '1', 'equipment', 'Delivered', '2026-02-14', '2027-02-14', '2027-02-11', 'Converted from Consolidated Request: CONS20260214V4ZKF. Original Department: Core Transaction Office', '2026-02-14 14:48:27', '2026-02-14 14:48:27'),
('PCPD20260214L4VAL', 'PROD20260214ETQIC', 'BATTERY-LN3 (DIN Size / H6 Equivalent)', 11000.00, '1', 'automotive', 'Delivered', '2026-02-14', '2026-08-19', '2027-02-14', 'Converted from Consolidated Request: CONS20260214PUDSB. Original Department: Log2 Dept', '2026-02-14 14:51:21', '2026-02-14 14:51:21'),
('PCPD20260214LBQVW', 'PROD202601232EW2T', 'Ballpoint Pen (12pcs per pack)', 108.00, '1', 'supplies', 'Delivered', '2026-02-14', '2026-03-17', NULL, 'Converted from Consolidated Request: CONS20260214BRRVB. Original Department: Administrative Office', '2026-02-14 04:36:11', '2026-02-14 04:36:11'),
('PCPD20260214NDISK', 'PROD20260123WWVO5', 'HBW Office Vinyl Coated Paper Clip 50mm', 53.00, '1', 'supplies', 'Delivered', '2026-02-14', '2026-03-17', NULL, 'Converted from Consolidated Request: CONS20260213WHZPV. Original Department: N/A', '2026-02-13 17:24:33', '2026-02-13 17:24:33'),
('PCPD20260214NEGFF', 'PROD2026012396838', 'Bondpaper size-A4 (100-pcs bundle)', 200.00, '1', 'supplies', 'Delivered', '2026-02-14', '2026-08-19', NULL, 'Converted from Consolidated Request: CONS20260213WHZPV. Original Department: N/A', '2026-02-13 17:24:33', '2026-02-13 17:24:33'),
('PCPD20260214OSE8L', 'PROD20260211EC7RA', '￼HBW Office Heavy Duty Puncher', 167.00, '1', 'equipment', 'Delivered', '2026-02-14', '2027-02-14', '2027-02-11', 'Converted from Consolidated Request: CONS20260214V4ZKF. Original Department: Core Transaction Office', '2026-02-14 14:48:27', '2026-02-14 14:48:27'),
('PCPD20260214T48BC', 'PROD20260123O57W5', 'Joy Stapler Promo Bundle 2s', 155.00, '1', 'supplies', 'Delivered', '2026-02-14', '2026-03-17', NULL, 'Converted from Consolidated Request: CONS20260214BRRVB. Original Department: Administrative Office', '2026-02-14 04:36:11', '2026-02-14 04:36:11'),
('PCPD20260214UQPQH', 'PROD20260209G0UJU', 'Honda CB500', 379000.00, '1', 'automotive', 'Delivered', '2026-02-14', '2027-02-14', '2027-01-01', 'Converted from Consolidated Request: CONS20260213Y5PDH. Original Department: Logistics Office', '2026-02-14 15:54:49', '2026-02-14 15:54:49'),
('PCPD20260214X38TG', 'PROD202601232EW2T', 'Ballpoint Pen (12pcs per pack)', 108.00, '1', 'supplies', 'Delivered', '2026-02-14', '2026-03-17', NULL, 'Converted from Consolidated Request: CONS20260213WHZPV. Original Department: N/A', '2026-02-13 17:24:33', '2026-02-13 17:24:33'),
('PCPD20260214Y07KX', 'PROD20260123O57W5', 'Joy Stapler Promo Bundle 2s', 155.00, '1', 'supplies', 'Delivered', '2026-02-14', '2026-03-17', NULL, 'Converted from Consolidated Request: CONS20260213WHZPV. Original Department: N/A', '2026-02-13 17:24:33', '2026-02-13 17:24:33'),
('PCPD20260214YPEGH', 'PROD202602104UJJE', 'COMIX S3508D (8 sheets, 22L Micro Cut Heavy Duty Paper Shredder)', 13560.00, '1', 'equipment', 'Delivered', '2026-02-14', '2027-02-14', '2027-02-11', 'Converted from Consolidated Request: CONS20260214V4ZKF. Original Department: Core Transaction Office', '2026-02-14 14:48:27', '2026-02-14 14:48:27'),
('PCPD20260215UNRWW', 'PROD202602095J9D5', '2012 Isuzu NPR', 980000.00, '1', 'automotive', 'Delivered', '2026-02-15', '2027-02-15', '2026-09-23', 'Converted from Consolidated Request: CONS20260214EVZSD. Original Department: Logistics Office', '2026-02-14 16:02:18', '2026-02-14 16:02:18');

-- --------------------------------------------------------

--
-- Table structure for table `psm_quote`
--

CREATE TABLE `psm_quote` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quo_id` varchar(255) NOT NULL,
  `quo_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`quo_items`)),
  `quo_units` int(11) NOT NULL,
  `quo_total_amount` decimal(12,2) NOT NULL,
  `quo_delivery_date` date DEFAULT NULL,
  `quo_status` enum('Reject','Cancel','PO Received','Processing Order','Dispatched','Delivered','Vendor-Review','In-Progress','Completed') NOT NULL DEFAULT 'PO Received',
  `quo_stored_from` varchar(255) NOT NULL DEFAULT 'Main Warehouse A',
  `quo_department_from` varchar(255) NOT NULL DEFAULT 'Logistics 1',
  `quo_module_from` varchar(255) NOT NULL DEFAULT 'Procurement & Sourcing Management',
  `quo_submodule_from` varchar(255) NOT NULL DEFAULT 'Vendor Quote',
  `quo_purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `psm_quote`
--

INSERT INTO `psm_quote` (`id`, `quo_id`, `quo_items`, `quo_units`, `quo_total_amount`, `quo_delivery_date`, `quo_status`, `quo_stored_from`, `quo_department_from`, `quo_module_from`, `quo_submodule_from`, `quo_purchase_id`, `created_at`, `updated_at`) VALUES
(19, 'QUOT00002', '[{\"name\":\"Joy Stapler Promo Bundle 2s\",\"price\":155,\"warranty\":\"1 Month\",\"expiration\":null}]', 1, 155.00, '2026-02-01', 'Completed', 'Main Warehouse A', 'Logistics 1', 'Procurement & Sourcing Management', 'Vendor Quote', 45, '2026-01-31 10:00:05', '2026-01-31 10:07:59'),
(20, 'QUOT00003', '[{\"name\":\"Ballpoint Pen (12pcs per pack)\",\"price\":108,\"warranty\":\"1 Month\",\"expiration\":null},{\"name\":\"Joy Stapler Promo Bundle 2s\",\"price\":155,\"warranty\":\"1 Month\",\"expiration\":null}]', 2, 263.00, '2026-02-01', 'Completed', 'Main Warehouse A', 'Logistics 1', 'Procurement & Sourcing Management', 'Vendor Quote', 46, '2026-01-31 10:00:10', '2026-01-31 10:09:53'),
(21, 'QUOT00004', '[{\"name\":\"Bondpaper size-A4 (100-pcs bundle)\",\"price\":200,\"warranty\":\"6 Month\",\"expiration\":null},{\"name\":\"Ballpoint Pen (12pcs per pack)\",\"price\":108,\"warranty\":\"1 Month\",\"expiration\":null},{\"name\":\"Joy Stapler Promo Bundle 2s\",\"price\":155,\"warranty\":\"1 Month\",\"expiration\":null},{\"name\":\"HBW Office Vinyl Coated Paper Clip 50mm\",\"price\":53,\"warranty\":\"1 Month\",\"expiration\":null}]', 4, 516.00, '2026-02-01', 'Completed', 'Main Warehouse A', 'Logistics 1', 'Procurement & Sourcing Management', 'Vendor Quote', 47, '2026-01-31 10:12:13', '2026-01-31 10:12:40'),
(22, 'QUOT00005', '[{\"name\":\"Bondpaper size-A4 (100-pcs bundle)\",\"price\":200,\"warranty\":\"6 Month\",\"expiration\":null},{\"name\":\"Ballpoint Pen (12pcs per pack)\",\"price\":108,\"warranty\":\"1 Month\",\"expiration\":null},{\"name\":\"Joy Stapler Promo Bundle 2s\",\"price\":155,\"warranty\":\"1 Month\",\"expiration\":null},{\"name\":\"HBW Office Vinyl Coated Paper Clip 50mm\",\"price\":53,\"warranty\":\"1 Month\",\"expiration\":null}]', 4, 516.00, '2026-02-01', 'Completed', 'Main Warehouse A', 'Logistics 1', 'Procurement & Sourcing Management', 'Vendor Quote', 48, '2026-01-31 10:13:57', '2026-01-31 10:14:22'),
(23, 'QUOT00006', '[{\"name\":\"HBW Office Vinyl Coated Paper Clip 50mm\",\"price\":53,\"warranty\":\"1 Month\",\"expiration\":null}]', 1, 53.00, '2026-01-31', 'Completed', 'Main Warehouse A', 'Logistics 1', 'Procurement & Sourcing Management', 'Vendor Quote', 49, '2026-01-31 10:16:23', '2026-01-31 10:17:02'),
(24, 'QUOT00007', '[{\"name\":\"Bondpaper size-A4 (100-pcs bundle)\",\"price\":200,\"warranty\":\"6 Month\",\"expiration\":null},{\"name\":\"Ballpoint Pen (12pcs per pack)\",\"price\":108,\"warranty\":\"1 Month\",\"expiration\":null},{\"name\":\"Joy Stapler Promo Bundle 2s\",\"price\":155,\"warranty\":\"1 Month\",\"expiration\":null},{\"name\":\"HBW Office Vinyl Coated Paper Clip 50mm\",\"price\":53,\"warranty\":\"1 Month\",\"expiration\":null}]', 4, 516.00, '2026-02-15', 'Delivered', 'Main Warehouse A', 'Logistics 1', 'Procurement & Sourcing Management', 'Vendor Quote', 53, '2026-02-13 17:23:25', '2026-02-13 17:24:33'),
(25, 'QUOT00008', '[{\"name\":\"Bondpaper size-A4 (100-pcs bundle)\",\"price\":200,\"warranty\":\"6 Month\",\"expiration\":null},{\"name\":\"Ballpoint Pen (12pcs per pack)\",\"price\":108,\"warranty\":\"1 Month\",\"expiration\":null},{\"name\":\"Joy Stapler Promo Bundle 2s\",\"price\":155,\"warranty\":\"1 Month\",\"expiration\":null},{\"name\":\"HBW Office Vinyl Coated Paper Clip 50mm\",\"price\":53,\"warranty\":\"1 Month\",\"expiration\":null}]', 4, 516.00, '2026-02-15', 'Delivered', 'Main Warehouse A', 'Logistics 1', 'Procurement & Sourcing Management', 'Vendor Quote', 60, '2026-02-14 04:24:11', '2026-02-14 04:36:11'),
(26, 'QUOT00009', '[{\"name\":\"Filing Cabinet\",\"price\":1600,\"warranty\":\"1 Year\",\"expiration\":null}]', 1, 1600.00, '2026-02-15', 'Delivered', 'Main Warehouse A', 'Logistics 1', 'Procurement & Sourcing Management', 'Vendor Quote', 63, '2026-02-14 14:45:50', '2026-02-14 14:46:32'),
(27, 'QUOT00010', '[{\"name\":\"Filing Cabinet\",\"price\":1600,\"warranty\":\"1 Year\",\"expiration\":null}]', 1, 1600.00, '2026-02-15', 'Delivered', 'Main Warehouse A', 'Logistics 1', 'Procurement & Sourcing Management', 'Vendor Quote', 54, '2026-02-14 14:45:56', '2026-02-14 14:46:29'),
(28, 'QUOT00011', '[{\"name\":\"COMIX S3508D (8 sheets, 22L Micro Cut Heavy Duty Paper Shredder)\",\"price\":13560,\"warranty\":\"1 Year\",\"expiration\":\"2027-02-11T00:00:00.000000Z\"},{\"name\":\"ThinkPad E16 AMD G2\",\"price\":536700,\"warranty\":\"1 Year\",\"expiration\":\"2027-02-11T00:00:00.000000Z\"},{\"name\":\"Canon Pixma G3010 All-in-One Ink Tank Printer (Wi-Fi Connectivity, 2315C018AB, Black)\",\"price\":8732,\"warranty\":\"1 Year\",\"expiration\":\"2027-02-11T00:00:00.000000Z\"},{\"name\":\"\\ufffcHBW Office Heavy Duty Puncher\",\"price\":167,\"warranty\":\"1 Year\",\"expiration\":\"2027-02-11T00:00:00.000000Z\"}]', 4, 559159.00, '2026-02-15', 'Delivered', 'Main Warehouse A', 'Logistics 1', 'Procurement & Sourcing Management', 'Vendor Quote', 61, '2026-02-14 14:48:04', '2026-02-14 14:48:27'),
(29, 'QUOT00012', '[{\"name\":\"BATTERY-LN3 (DIN Size \\/ H6 Equivalent)\",\"price\":11000,\"warranty\":\"6 Month\",\"expiration\":\"2027-02-14T00:00:00.000000Z\"}]', 1, 11000.00, '2026-02-15', 'Delivered', 'Main Warehouse A', 'Logistics 1', 'Procurement & Sourcing Management', 'Vendor Quote', 64, '2026-02-14 14:49:21', '2026-02-14 14:51:21'),
(30, 'QUOT00013', '[{\"name\":\"Toyota Hiace\",\"price\":1379000,\"warranty\":\"1 Year\",\"expiration\":\"2027-01-01T00:00:00.000000Z\"}]', 1, 1379000.00, '2026-02-15', 'Delivered', 'Main Warehouse A', 'Logistics 1', 'Procurement & Sourcing Management', 'Vendor Quote', 62, '2026-02-14 14:49:24', '2026-02-14 14:51:17'),
(31, 'QUOT00014', '[{\"name\":\"Honda CB500\",\"price\":379000,\"warranty\":\"1 Year\",\"expiration\":\"2027-01-01T00:00:00.000000Z\"}]', 1, 379000.00, '2026-02-15', 'Delivered', 'Main Warehouse A', 'Logistics 1', 'Procurement & Sourcing Management', 'Vendor Quote', 59, '2026-02-14 14:49:28', '2026-02-14 14:51:10'),
(32, 'QUOT00015', '[{\"name\":\"2012 Isuzu NPR\",\"price\":980000,\"warranty\":\"1 Year\",\"expiration\":\"2026-09-23T00:00:00.000000Z\"}]', 1, 980000.00, '2026-02-15', 'Delivered', 'Main Warehouse A', 'Logistics 1', 'Procurement & Sourcing Management', 'Vendor Quote', 58, '2026-02-14 14:49:32', '2026-02-14 16:02:18'),
(33, 'QUOT00016', '[{\"name\":\"Mitsubishi Fuso Canter Cruise (Minibus)\",\"price\":2600000,\"warranty\":\"5 Year\",\"expiration\":\"2030-01-10T00:00:00.000000Z\"}]', 1, 2600000.00, '2026-02-14', 'Delivered', 'Main Warehouse A', 'Logistics 1', 'Procurement & Sourcing Management', 'Vendor Quote', 56, '2026-02-14 14:49:35', '2026-02-14 14:51:04'),
(34, 'QUOT00017', '[{\"name\":\"Mitsubishi Fuso Canter FE73 (6-Wheeler)\",\"price\":2400000,\"warranty\":\"5 Year\",\"expiration\":\"2030-01-10T00:00:00.000000Z\"}]', 1, 2400000.00, '2026-02-15', 'Delivered', 'Main Warehouse A', 'Logistics 1', 'Procurement & Sourcing Management', 'Vendor Quote', 57, '2026-02-14 15:53:33', '2026-02-14 15:55:34'),
(35, 'QUOT00018', '[{\"name\":\"Honda CB500\",\"price\":379000,\"warranty\":\"1 Year\",\"expiration\":\"2027-01-01T00:00:00.000000Z\"}]', 1, 379000.00, '2026-02-15', 'Delivered', 'Main Warehouse A', 'Logistics 1', 'Procurement & Sourcing Management', 'Vendor Quote', 55, '2026-02-14 15:53:42', '2026-02-14 15:54:49');

-- --------------------------------------------------------

--
-- Table structure for table `psm_request_budget`
--

CREATE TABLE `psm_request_budget` (
  `req_id` varchar(50) NOT NULL,
  `req_by` varchar(255) NOT NULL,
  `req_date` date NOT NULL,
  `req_dept` varchar(255) NOT NULL,
  `req_amount` decimal(15,2) NOT NULL,
  `req_purpose` text NOT NULL,
  `req_contact` varchar(255) NOT NULL,
  `req_status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `psm_request_budget`
--

INSERT INTO `psm_request_budget` (`req_id`, `req_by`, `req_date`, `req_dept`, `req_amount`, `req_purpose`, `req_contact`, `req_status`, `created_at`, `updated_at`) VALUES
('REQB20260212FX1DW', 'Ric Jason Altamante - Superadmin', '2026-02-12', 'Logistics 1', 2223.00, 'Consolidated budget request for approved Purchase Requisitions', 'altamantericjason@gmail.com / 09123456780', 'Approved', '2026-02-11 22:49:52', '2026-02-11 22:49:52'),
('REQB202602141DVK4YV4', 'Ric Jason E. Altamante - Superadmin', '2026-02-14', 'Logistics 1', 7858600.00, 'Consolidated budget for: EXTPR-2026-0001, EXTPR-2026-0002, EXTPR-2026-0003, EXTPR-2026-0004, EXTPR-2026-0005, EXTPR-2026-0007, EXTPR-2026-0008, EXTPR-2026-0012, EXTPR-2026-0013, EXTPR-2026-0014, EXTPR-2026-0015, EXTPR-2026-0016', 'N/A', 'Pending', '2026-02-14 14:15:36', '2026-02-14 14:15:36'),
('REQB202602146YYFXXOG', 'Ric Jason Altamante - Superadmin', '2026-02-14', 'Logistics 1', 1379000.00, 'Consolidated budget for: REQN20260214HTZWG', 'N/A', 'Approved', '2026-02-13 18:55:32', '2026-02-13 23:59:03'),
('REQB20260214DE2KDZOT', 'Ric Jason Altamante - Superadmin', '2026-02-14', 'Logistics 1', 559675.00, 'Consolidated budget for: REQN202602143FUHL, REQN2026021498LMB', 'N/A', 'Approved', '2026-02-13 18:56:03', '2026-02-13 23:59:09'),
('REQB20260214FNASPZSH', 'Ric Jason Altamante - Superadmin', '2026-02-14', 'Logistics 1', 980000.00, 'Consolidated budget for: REQN20260214EJ1EN', 'N/A', 'Approved', '2026-02-13 23:05:59', '2026-02-13 23:59:16'),
('REQB20260214MOVQDZMV', 'Robert B. Quilenderino - Admin', '2026-02-14', 'Logistics 1', 11000.00, 'Consolidated budget for: REQN20260214JD9L4', 'N/A', 'Pending', '2026-02-14 05:41:09', '2026-02-14 05:41:09'),
('REQB20260214QD35C6J2', 'Ric Jason E. Altamante - Superadmin', '2026-02-14', 'Logistics 1', 13000.00, 'Consolidated budget for: REQN20260215UEYK1, EXTPR-2026-0001', 'N/A', 'Approved', '2026-02-14 12:28:02', '2026-02-14 12:30:15'),
('REQB20260214UWZLQXRB', 'Ric Jason E. Altamante - Superadmin', '2026-02-14', 'Logistics 1', 5335594.00, 'Consolidated budget for: EXTPR-2026-0001, EXTPR-2026-0002, EXTPR-2026-0003, EXTPR-2026-0004, EXTPR-2026-0005, EXTPR-2026-0007, EXTPR-2026-0008, EXTPR-2026-0013, EXTPR-2026-0014, EXTPR-2026-0015, EXTPR-2026-0016, REQN20260214XNJ24', 'N/A', 'Pending', '2026-02-14 13:24:22', '2026-02-14 13:24:22'),
('REQB20260214VVFCPWIA', 'Ric Jason Altamante - Superadmin', '2026-02-14', 'Logistics 1', 379000.00, 'Consolidated budget for: REQN20260214GZHCW', 'N/A', 'Approved', '2026-02-13 22:59:19', '2026-02-13 23:59:13'),
('REQB20260214XEIUHGXW', 'Ric Jason Altamante - Superadmin', '2026-02-14', 'Logistics 1', 2400000.00, 'Consolidated budget for: REQN20260214MB5X3', 'N/A', 'Approved', '2026-02-13 23:15:58', '2026-02-13 23:58:32'),
('REQB20260214XOE0DYEF', 'Ric Jason E. Altamante - Superadmin', '2026-02-14', 'Logistics 1', 2600000.00, 'Consolidated budget for: REQN2026021403AYF', 'N/A', 'Approved', '2026-02-13 23:21:28', '2026-02-13 23:58:19'),
('REQB20260214YPWI5B6U', 'Ric Jason E. Altamante - Superadmin', '2026-02-14', 'Logistics 1', 120600.00, 'Consolidated budget for: EXTPR-2026-0001, EXTPR-2026-0002, EXTPR-2026-0003, EXTPR-2026-0004, EXTPR-2026-0005, EXTPR-2026-0007, EXTPR-2026-0008', 'N/A', 'Pending', '2026-02-14 12:50:28', '2026-02-14 12:50:28'),
('REQB20260214ZPPPZHNQ', 'Ric Jason E. Altamante - Superadmin', '2026-02-14', 'Logistics 1', 3028999.00, 'Consolidated budget for: REQN20260214GKRSB, REQN20260214HPG9Y, REQN202602144Z7A3', 'N/A', 'Approved', '2026-02-14 00:16:13', '2026-02-14 05:09:06');

-- --------------------------------------------------------

--
-- Table structure for table `psm_requisition`
--

CREATE TABLE `psm_requisition` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `req_id` varchar(255) NOT NULL,
  `req_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`req_items`)),
  `req_chosen_vendor` varchar(255) DEFAULT NULL,
  `req_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `req_requester` varchar(255) NOT NULL,
  `req_dept` varchar(255) NOT NULL,
  `req_date` date NOT NULL,
  `req_note` text DEFAULT NULL,
  `req_status` varchar(255) NOT NULL DEFAULT 'pending',
  `is_consolidated` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `psm_requisition`
--

INSERT INTO `psm_requisition` (`id`, `req_id`, `req_items`, `req_chosen_vendor`, `req_price`, `req_requester`, `req_dept`, `req_date`, `req_note`, `req_status`, `is_consolidated`, `created_at`, `updated_at`) VALUES
(17, 'REQN202602139GZMF', '[\"HBW Office Vinyl Coated Paper Clip 50mm\",\"Joy Stapler Promo Bundle 2s\",\"Ballpoint Pen (12pcs per pack)\",\"Bondpaper size-A4 (100-pcs bundle)\"]', 'VEN20260114Z1JB0', 516.00, 'James Carter', 'Administrative Office', '2026-02-13', 'Notes / Remarks', 'Approved', 1, '2026-02-12 18:14:07', '2026-02-12 18:16:41'),
(18, 'REQN20260213WSQ4N', '[\"Bondpaper size-A4 (100-pcs bundle) (x10)\"]', 'VEN20260114Z1JB0', 2000.00, 'Steven Hawkins', 'Human Resource Department', '2026-02-13', 'Notes / Remarks', 'Approved', 1, '2026-02-12 18:20:47', '2026-02-12 23:41:26'),
(19, 'REQN202602136A7XD', '[\"Filing Cabinet\"]', 'VEN20260208OZRGI', 1600.00, 'Lara Craft', 'Core Transaction Office', '2026-02-13', 'Notes / Remarks', 'Approved', 1, '2026-02-12 19:20:50', '2026-02-12 23:41:26'),
(20, 'REQN20260213FL2MG', '[\"Honda CB500\"]', 'VEN202601235JMKX', 379000.00, 'Robert Quilenderino', 'Logistics Office', '2026-02-13', 'Notes / Remarks', 'Approved', 1, '2026-02-12 19:27:20', '2026-02-12 23:41:26'),
(21, 'REQN202602138OJEC', '[\"Canon Pixma G3010 All-in-One Ink Tank Printer (Wi-Fi Connectivity, 2315C018AB, Black)\",\"ThinkPad E16 AMD G2\"]', 'VEN20260111NE5VZ', 545432.00, 'James Carter', 'Human Resource Department', '2026-02-13', 'Notes / Remarks', 'Approved', 1, '2026-02-12 23:16:14', '2026-02-12 23:41:26'),
(22, 'REQN202602144Z7A3', '[\"Cabinet\"]', 'VEN20260208OZRGI', 8999.00, 'James Carter', 'Core Transaction Office', '2026-02-13', 'Notes / Remarks', 'Approved', 1, '2026-02-13 11:29:28', '2026-02-14 00:16:13'),
(23, 'REQN2026021498LMB', '[\"\\ufffcHBW Office Heavy Duty Puncher\",\"Canon Pixma G3010 All-in-One Ink Tank Printer (Wi-Fi Connectivity, 2315C018AB, Black)\",\"ThinkPad E16 AMD G2\",\"COMIX S3508D (8 sheets, 22L Micro Cut Heavy Duty Paper Shredder)\"]', 'VEN20260111NE5VZ', 559159.00, 'Tomas Finland', 'Core Transaction Office', '2026-02-14', 'Notes / Remarks', 'Approved', 1, '2026-02-13 18:45:00', '2026-02-13 18:56:03'),
(24, 'REQN202602143FUHL', '[\"HBW Office Vinyl Coated Paper Clip 50mm\",\"Joy Stapler Promo Bundle 2s\",\"Ballpoint Pen (12pcs per pack)\",\"Bondpaper size-A4 (100-pcs bundle)\"]', 'VEN20260114Z1JB0', 516.00, 'Robert Quilenderino', 'Administrative Office', '2026-02-14', 'Notes / Remarks', 'Approved', 1, '2026-02-13 18:45:32', '2026-02-13 18:56:03'),
(29, 'REQN20260214HTZWG', '[\"Toyota Hiace\"]', 'VEN202601235JMKX', 1379000.00, 'Robert Quilenderino', 'Logistics Office', '2026-02-14', 'Notes / Remarks', 'Approved', 1, '2026-02-13 18:51:10', '2026-02-13 18:55:32'),
(30, 'REQN20260214GZHCW', '[\"Honda CB500\"]', 'VEN202601235JMKX', 379000.00, 'Robert Quilenderino', 'Logistics Office', '2026-02-14', 'Notes / Remarks', 'Approved', 1, '2026-02-13 18:51:36', '2026-02-13 22:59:19'),
(31, 'REQN20260214EJ1EN', '[\"2012 Isuzu NPR\"]', 'VEN202601235JMKX', 980000.00, 'Robert Quilenderino', 'Logistics Office', '2026-02-14', 'Notes / Remarks', 'Approved', 1, '2026-02-13 18:51:56', '2026-02-13 23:05:59'),
(32, 'REQN20260214MB5X3', '[\"Mitsubishi Fuso Canter FE73 (6-Wheeler)\"]', 'VEN202601235JMKX', 2400000.00, 'Robert Quilenderino', 'Logistics Office', '2026-02-14', 'Notes / Remarks', 'Approved', 1, '2026-02-13 18:52:18', '2026-02-13 23:15:58'),
(33, 'REQN2026021403AYF', '[\"Mitsubishi Fuso Canter Cruise (Minibus)\"]', 'VEN202601235JMKX', 2600000.00, 'Robert Quilenderino', 'Logistics Office', '2026-02-14', 'Notes / Remarks', 'Approved', 1, '2026-02-13 18:52:43', '2026-02-13 23:21:28'),
(34, 'REQN20260214HPG9Y', '[\"Suzuki Carry Utility Van 1.5L\"]', 'VEN202601235JMKX', 820000.00, 'Robert Quilenderino', 'Logistics Office', '2026-02-14', 'Notes / Remarks', 'Approved', 1, '2026-02-13 18:53:13', '2026-02-14 00:16:13'),
(35, 'REQN20260214GKRSB', '[\"Toyota Fortuner\"]', 'VEN202601235JMKX', 2200000.00, 'Robert Quilenderino', 'Logistics Office', '2026-02-14', 'Notes / Remarks', 'Approved', 1, '2026-02-13 18:53:26', '2026-02-14 00:16:13'),
(36, 'REQN20260214XNJ24', '[\"Pouf Classic (x2)\",\"Cabinet (x2)\",\"Mirror (x2)\",\"Lamp (x2)\",\"Bar Stool (x2)\",\"Wardrobe (x2)\",\"Sofa (x2)\",\"Round Table (x2)\",\"Filing Cabinet (x2)\",\"Gaming Chair (x2)\"]', 'VEN20260208OZRGI', 76994.00, 'John Paul Natividad', 'Human Resource Department', '2026-02-14', 'Notes / Remarks', 'Approved', 1, '2026-02-14 03:19:41', '2026-02-14 13:24:22'),
(37, 'REQN20260214JD9L4', '[\"BATTERY-LN3 (DIN Size \\/ H6 Equivalent)\"]', 'VEN202601235JMKX', 11000.00, 'Pauline Estrada', 'Administrative Office', '2026-02-14', 'Need Asap', 'Approved', 1, '2026-02-14 05:39:31', '2026-02-14 05:41:09'),
(38, 'EXTPR-2026-0001', '[\"BATTERY-LN3 (DIN Size \\/ H6 Equivalent) (PROD20260214ETQIC) x1 @ \\u20b111000.00\"]', 'VEN202601235JMKX', 11000.00, 'Via Jeves', 'Log2 Dept', '2026-02-14', NULL, 'Approved', 0, '2026-02-14 12:04:13', '2026-02-21 22:12:07'),
(39, 'EXTPR-2026-0002', '[\"Toyota Glanza Alloy Wheel (PROD20260214OOI4K) x1 @ \\u20b19000.00\"]', 'VEN202601235JMKX', 9000.00, 'Via Jeves', 'Log2 Dept', '2026-02-14', NULL, 'Approved', 0, '2026-02-14 12:04:13', '2026-02-21 22:12:07'),
(40, 'EXTPR-2026-0003', '[\"Hyundai Creta \\u2013 16-Inch Alloy Wheels (PROD20260214JOY7C) x1 @ \\u20b155000.00\"]', 'VEN202601235JMKX', 55000.00, 'Via Jeves', 'Log2 Dept', '2026-02-14', NULL, 'Approved', 0, '2026-02-14 12:04:13', '2026-02-21 22:12:07'),
(41, 'EXTPR-2026-0004', '[\"2018-2019 Toyota Camry NON-AFS Headlight \\u2013 OEM (PROD20260214JROKA) x1 @ \\u20b130000.00\"]', 'VEN202601235JMKX', 30000.00, 'Via Jeves', 'Log2 Dept', '2026-02-14', NULL, 'Approved', 0, '2026-02-14 12:04:13', '2026-02-21 22:12:07'),
(42, 'EXTPR-2026-0005', '[\"Toyota Camry SE 2018-2020 Headlight \\u2013 Left (Driver Side, Aftermarket) (PROD202602141JHN0) x1 @ \\u20b114000.00\"]', 'VEN202601235JMKX', 14000.00, 'Via Jeves', 'Log2 Dept', '2026-02-14', NULL, 'Approved', 0, '2026-02-14 12:04:13', '2026-02-21 22:12:07'),
(43, 'EXTPR-2026-0006', '[\"2018-2023 Toyota Camry Headlight Assembly \\u2013 OEM Right (Passenger Side) (PROD20260214GS6LR) x1 @ \\u20b135000.00\"]', 'VEN202601235JMKX', 35000.00, 'Via Jeves', 'Log2 Dept', '2026-02-14', NULL, 'Rejected', 0, '2026-02-14 12:04:13', '2026-02-21 22:12:07'),
(44, 'EXTPR-2026-0007', '[\"SPARCO-CORSA Steering Wheel Cover (Black, Japan Style) (PROD20260214FGH9V) x1 @ \\u20b11200.00\"]', 'VEN202601235JMKX', 1200.00, 'Via Jeves', 'Log2 Dept', '2026-02-14', NULL, 'Approved', 0, '2026-02-14 12:04:13', '2026-02-21 22:12:07'),
(45, 'EXTPR-2026-0008', '[\"Toyota Red Suede Steering Booster \\/ Wheel Cover (Non-Slip) (PROD20260214GC7O0) x1 @ \\u20b1400.00\"]', 'VEN202601235JMKX', 400.00, 'Via Jeves', 'Log2 Dept', '2026-02-14', NULL, 'Approved', 0, '2026-02-14 12:04:13', '2026-02-21 22:12:07'),
(46, 'EXTPR-2026-0009', '[\"4X 16-Inch Wheel Covers (Snap-On, R16, Steel Rim) (PROD202602149UIC7) x1 @ \\u20b12000.00\"]', 'VEN202601235JMKX', 2000.00, 'Via Jeves', 'Log2 Dept', '2026-02-14', NULL, 'Rejected', 0, '2026-02-14 12:04:13', '2026-02-21 22:12:07'),
(47, 'EXTPR-2026-0010', '[\"Toyota Fortuner (PROD20260209SHP58) x1 @ \\u20b12200000.00\"]', 'VEN202601235JMKX', 2200000.00, 'Via Jeves', 'Log2 Dept', '2026-02-14', NULL, 'Pending', 0, '2026-02-14 12:04:13', '2026-02-21 22:12:07'),
(48, 'EXTPR-2026-0011', '[\"Suzuki Carry Utility Van 1.5L (PROD20260209ACB9K) x1 @ \\u20b1820000.00\"]', 'VEN202601235JMKX', 820000.00, 'Via Jeves', 'Log2 Dept', '2026-02-14', NULL, 'Rejected', 0, '2026-02-14 12:04:13', '2026-02-21 22:12:07'),
(49, 'EXTPR-2026-0012', '[\"Mitsubishi Fuso Canter Cruise (Minibus) (PROD20260209HKZNM) x1 @ \\u20b12600000.00\"]', 'VEN202601235JMKX', 2600000.00, 'Via Jeves', 'Log2 Dept', '2026-02-14', NULL, 'Approved', 0, '2026-02-14 12:04:13', '2026-02-21 22:12:07'),
(50, 'EXTPR-2026-0013', '[\"Mitsubishi Fuso Canter FE73 (6-Wheeler) (PROD20260209WNKAJ) x1 @ \\u20b12400000.00\"]', 'VEN202601235JMKX', 2400000.00, 'Via Jeves', 'Log2 Dept', '2026-02-14', NULL, 'Approved', 0, '2026-02-14 12:04:13', '2026-02-21 22:12:07'),
(51, 'EXTPR-2026-0014', '[\"2012 Isuzu NPR (PROD202602095J9D5) x1 @ \\u20b1980000.00\"]', 'VEN202601235JMKX', 980000.00, 'Via Jeves', 'Log2 Dept', '2026-02-14', NULL, 'Approved', 0, '2026-02-14 12:04:13', '2026-02-21 22:12:07'),
(52, 'EXTPR-2026-0015', '[\"Honda CB500 (PROD20260209G0UJU) x1 @ \\u20b1379000.00\"]', 'VEN202601235JMKX', 379000.00, 'Via Jeves', 'Log2 Dept', '2026-02-14', NULL, 'Approved', 0, '2026-02-14 12:04:13', '2026-02-21 22:12:07'),
(53, 'EXTPR-2026-0016', '[\"Toyota Hiace (PROD202602098QOMK) x1 @ \\u20b11379000.00\"]', 'VEN202601235JMKX', 1379000.00, 'Via Jeves', 'Log2 Dept', '2026-02-14', NULL, 'Approved', 0, '2026-02-14 12:04:13', '2026-02-21 22:12:07'),
(54, 'REQN20260215UEYK1', '[\"4X 16-Inch Wheel Covers (Snap-On, R16, Steel Rim)\"]', 'VEN202601235JMKX', 2000.00, 'Robert Quilenderino', 'Logistics Office', '2026-02-14', 'v', 'Approved', 1, '2026-02-14 12:22:31', '2026-02-14 12:28:02'),
(55, 'REQN20260215N3KZG', '[\"Ballpoint Pen (12pcs per pack)\",\"Ballpoint Pen (12pcs per pack)\"]', 'VEN20260114Z1JB0', 1080.00, 'Ric Jason Altamante', 'Logistic Office', '2026-02-15', 'Low Stock item need for restocking', 'Pending', 0, '2026-02-14 16:22:25', '2026-02-14 16:22:25'),
(56, 'EXTPR-2026-0018', '[\"Toyota Red Suede Steering Booster \\/ Wheel Cover (Non-Slip) (PROD20260214GC7O0) x1 @ \\u20b1400.00\"]', 'VEN202601235JMKX', 400.00, 'Admin', 'Log2 Dept', '2026-02-15', NULL, 'Pending', 0, '2026-02-14 18:45:43', '2026-02-21 22:12:07'),
(57, 'EXTPR-2026-0017', '[\"Toyota Red Suede Steering Booster \\/ Wheel Cover (Non-Slip) (PROD20260214GC7O0) x1 @ \\u20b1400.00\"]', 'VEN202601235JMKX', 400.00, 'Admin', 'Log2 Dept', '2026-02-15', NULL, 'Pending', 0, '2026-02-14 18:45:43', '2026-02-21 22:12:07'),
(58, 'REQN20260215OVJ69', '[\"BATTERY-LN3 (DIN Size \\/ H6 Equivalent)\",\"BATTERY-LN3 (DIN Size \\/ H6 Equivalent)\"]', 'VEN202601235JMKX', 11000.00, 'Robert Quilenderino', 'Logistic Office', '2026-02-15', 'Low Stock item need for restocking', 'Pending', 0, '2026-02-14 21:07:18', '2026-02-14 21:07:18');

-- --------------------------------------------------------

--
-- Table structure for table `psm_vendor`
--

CREATE TABLE `psm_vendor` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ven_id` varchar(255) NOT NULL,
  `ven_company_name` varchar(255) NOT NULL,
  `ven_contact_person` varchar(255) NOT NULL,
  `ven_email` varchar(255) NOT NULL,
  `ven_phone` varchar(255) NOT NULL,
  `ven_address` text NOT NULL,
  `ven_rating` int(11) NOT NULL DEFAULT 1,
  `ven_type` enum('equipment','supplies','furniture','automotive') NOT NULL,
  `ven_product` int(11) NOT NULL DEFAULT 0,
  `ven_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `ven_desc` text DEFAULT NULL,
  `ven_module_from` varchar(255) NOT NULL DEFAULT 'psm',
  `ven_submodule_from` varchar(255) NOT NULL DEFAULT 'vendor-management',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `psm_vendor`
--

INSERT INTO `psm_vendor` (`id`, `ven_id`, `ven_company_name`, `ven_contact_person`, `ven_email`, `ven_phone`, `ven_address`, `ven_rating`, `ven_type`, `ven_product`, `ven_status`, `ven_desc`, `ven_module_from`, `ven_submodule_from`, `created_at`, `updated_at`) VALUES
(1, 'VEN20260111NE5VZ', 'Kristian Icy Tolentino Company', 'Kristian Icy Tolentino', 'aicy3987@gmail.com', '09123456793', 'BLK00 LOT00, Streets Barangay Subdivision City 0123', 0, 'supplies', 0, 'active', 'Auto-synced from vendor account', 'psm', 'vendor-management', '2026-01-22 23:55:18', '2026-01-22 23:55:18'),
(2, 'VEN20260114Z1JB0', 'OfficeSupplies Depot', 'Potato Matho', 'potatomatho@gmail.com', '0912312312', '1234 Street, Barangay Building Floor Manuel City', 0, 'supplies', 0, 'active', 'Auto-synced from vendor account', 'psm', 'vendor-management', '2026-01-22 23:55:18', '2026-01-22 23:55:18'),
(3, 'VEN202601235JMKX', 'Butchoy Quilenderino Company', 'Butchoy Quilenderino', 'butchoyquilenderino@gmail.com', '0000000000', 'N/A', 0, 'supplies', 0, 'active', 'Auto-synced from vendor account', 'psm', 'vendor-management', '2026-01-22 23:55:18', '2026-01-22 23:55:18'),
(4, 'VEN20260123DIHO9', 'lemoun starr Company', 'lemoun starr', 'lemonstar@gmail.com', '0000000000', 'N/A', 0, 'supplies', 0, 'active', 'Auto-synced from vendor account', 'psm', 'vendor-management', '2026-01-22 23:55:18', '2026-01-22 23:55:18'),
(5, 'VEN20260123FYSVP', 'test test Company', 'test test', 'testtest@gmail.com', '0000000000', 'N/A', 0, 'supplies', 0, 'active', 'Auto-synced from vendor account', 'psm', 'vendor-management', '2026-01-22 23:55:18', '2026-01-22 23:55:18'),
(6, 'VEN20260208OZRGI', 'LhilFarniture', 'Lhil Carl Antares', 'lhilcarlibuos123@gmail.com', '09748352816', 'Building 205, J.P. Rizal Avenue Rockwell Center Metro Manila Makati City 1200', 0, 'furniture', 0, 'active', 'LhilFarniture is a furniture-based company specializing in durable and stylish pieces for homes and offices. We focus on quality craftsmanship, functional design, and reliable service to meet everyday living and workspace needs.', 'main', 'sync', '2026-02-09 19:00:52', '2026-02-09 19:00:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `psm_budget`
--
ALTER TABLE `psm_budget`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `psm_budget_bud_id_unique` (`bud_id`),
  ADD KEY `psm_budget_bud_valid_to_index` (`bud_valid_to`),
  ADD KEY `psm_budget_bud_amount_status_health_index` (`bud_amount_status_health`),
  ADD KEY `psm_budget_bud_assigned_date_index` (`bud_assigned_date`);

--
-- Indexes for table `psm_budget_allocated`
--
ALTER TABLE `psm_budget_allocated`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `psm_budget_allocated_all_id_unique` (`all_id`);

--
-- Indexes for table `psm_budget_logs`
--
ALTER TABLE `psm_budget_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `psm_consolidated`
--
ALTER TABLE `psm_consolidated`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `psm_consolidated_con_req_id_unique` (`con_req_id`);

--
-- Indexes for table `psm_product`
--
ALTER TABLE `psm_product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `psm_product_prod_id_unique` (`prod_id`),
  ADD KEY `psm_product_prod_vendor_foreign` (`prod_vendor`);

--
-- Indexes for table `psm_purcahse_request`
--
ALTER TABLE `psm_purcahse_request`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `psm_purcahse_request_preq_id_unique` (`preq_id`);

--
-- Indexes for table `psm_purchase`
--
ALTER TABLE `psm_purchase`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `psm_purchase_pur_id_unique` (`pur_id`),
  ADD KEY `psm_purchase_pur_status_index` (`pur_status`),
  ADD KEY `psm_purchase_pur_company_name_index` (`pur_company_name`),
  ADD KEY `psm_purchase_created_at_index` (`created_at`);

--
-- Indexes for table `psm_purchase_product`
--
ALTER TABLE `psm_purchase_product`
  ADD PRIMARY KEY (`purcprod_id`);

--
-- Indexes for table `psm_quote`
--
ALTER TABLE `psm_quote`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `psm_quote_quo_id_unique` (`quo_id`);

--
-- Indexes for table `psm_request_budget`
--
ALTER TABLE `psm_request_budget`
  ADD PRIMARY KEY (`req_id`);

--
-- Indexes for table `psm_requisition`
--
ALTER TABLE `psm_requisition`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `psm_requisition_req_id_unique` (`req_id`);

--
-- Indexes for table `psm_vendor`
--
ALTER TABLE `psm_vendor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `psm_vendor_ven_id_unique` (`ven_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `psm_budget`
--
ALTER TABLE `psm_budget`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `psm_budget_allocated`
--
ALTER TABLE `psm_budget_allocated`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `psm_budget_logs`
--
ALTER TABLE `psm_budget_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `psm_consolidated`
--
ALTER TABLE `psm_consolidated`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `psm_product`
--
ALTER TABLE `psm_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `psm_purcahse_request`
--
ALTER TABLE `psm_purcahse_request`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `psm_purchase`
--
ALTER TABLE `psm_purchase`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `psm_quote`
--
ALTER TABLE `psm_quote`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `psm_requisition`
--
ALTER TABLE `psm_requisition`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `psm_vendor`
--
ALTER TABLE `psm_vendor`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `psm_product`
--
ALTER TABLE `psm_product`
  ADD CONSTRAINT `psm_product_prod_vendor_foreign` FOREIGN KEY (`prod_vendor`) REFERENCES `psm_vendor` (`ven_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
