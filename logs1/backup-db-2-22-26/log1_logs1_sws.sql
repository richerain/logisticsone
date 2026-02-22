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
-- Database: `log1_logs1_sws`
--

-- --------------------------------------------------------

--
-- Table structure for table `sws_categories`
--

CREATE TABLE `sws_categories` (
  `cat_id` varchar(100) NOT NULL,
  `cat_name` varchar(100) NOT NULL,
  `cat_description` text DEFAULT NULL,
  `cat_created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sws_categories`
--

INSERT INTO `sws_categories` (`cat_id`, `cat_name`, `cat_description`, `cat_created_at`) VALUES
('ICAT20260125DPZFQ', 'Equipment', 'An equipment company is engaged in the sale, leasing, or distribution of durable machinery, tools, and operational assets used by businesses and institutions. These may include industrial equipment, IT hardware, construction machinery, or specialized operational tools. The company handles equipment sourcing, maintenance coordination, warranty management, and after-sales support. Equipment companies often provide long-term solutions for clients that require reliable assets for production, service delivery, or infrastructure support.', '2026-01-25 00:43:47'),
('ICAT20260125HUHIU', 'Supplies', 'A supplies company specializes in the procurement and distribution of consumable goods and essential materials used in daily business operations. These supplies may include office materials, cleaning products, medical supplies, or operational consumables. The company focuses on maintaining consistent stock availability, managing supplier relationships, and ensuring timely delivery to clients. Supplies companies typically serve organizations that require regular replenishment of materials to support continuous operations.', '2026-01-25 00:43:32'),
('ICAT20260125JXBCQ', 'Furniture', 'A furniture company specializes in the design, manufacturing, distribution, or retail of furniture for residential, commercial, or institutional use. Its operations include sourcing raw materials, producing or acquiring finished furniture, managing warehouses, and delivering products to customers. The company may also offer customization, installation, and after-sales support. Furniture companies commonly supply offices, schools, households, and organizations requiring functional and aesthetic furnishing solutions.', '2026-01-25 00:43:16'),
('ICAT20260125WEO0B', 'Automotive', 'An automotive company is engaged in the sale, maintenance, and support of motor/car vehicles and related components. It may provide services such as vehicle sales, parts distribution, repair and maintenance, and fleet servicing. The company typically manages inventory of vehicles and spare parts, coordinates with manufacturers or suppliers, and ensures compliance with safety and regulatory standards. Automotive companies often serve individual customers, businesses, or institutional clients that require transportation solutions.', '2026-01-25 00:42:47');

-- --------------------------------------------------------

--
-- Table structure for table `sws_incoming_asset`
--

CREATE TABLE `sws_incoming_asset` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sws_purcprod_id` varchar(255) NOT NULL,
  `sws_purcprod_prod_id` varchar(255) DEFAULT NULL,
  `sws_purcprod_prod_name` varchar(255) DEFAULT NULL,
  `sws_purcprod_prod_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sws_purcprod_prod_unit` int(11) NOT NULL DEFAULT 0,
  `sws_purcprod_prod_type` varchar(50) DEFAULT NULL,
  `sws_purcprod_status` varchar(50) DEFAULT NULL,
  `sws_purcprod_date` date DEFAULT NULL,
  `sws_purcprod_warranty` date DEFAULT NULL,
  `sws_purcprod_expiration` date DEFAULT NULL,
  `sws_purcprod_desc` text DEFAULT NULL,
  `sws_purcprod_inventory` enum('no','yes') NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sws_incoming_asset`
--

INSERT INTO `sws_incoming_asset` (`id`, `sws_purcprod_id`, `sws_purcprod_prod_id`, `sws_purcprod_prod_name`, `sws_purcprod_prod_price`, `sws_purcprod_prod_unit`, `sws_purcprod_prod_type`, `sws_purcprod_status`, `sws_purcprod_date`, `sws_purcprod_warranty`, `sws_purcprod_expiration`, `sws_purcprod_desc`, `sws_purcprod_inventory`, `created_at`, `updated_at`) VALUES
(1, 'PCPD20260214NDISK', 'PROD20260123WWVO5', 'HBW Office Vinyl Coated Paper Clip 50mm', 53.00, 1, 'supplies', 'Delivered', '2026-02-14', '2026-03-17', NULL, 'Converted from Consolidated Request: CONS20260213WHZPV. Original Department: N/A', 'yes', '2026-02-13 19:45:02', '2026-02-13 23:42:48'),
(2, 'PCPD20260214NEGFF', 'PROD2026012396838', 'Bondpaper size-A4 (100-pcs bundle)', 200.00, 1, 'supplies', 'Delivered', '2026-02-14', '2026-08-19', NULL, 'Converted from Consolidated Request: CONS20260213WHZPV. Original Department: N/A', 'yes', '2026-02-13 19:45:02', '2026-02-13 23:42:59'),
(3, 'PCPD20260214X38TG', 'PROD202601232EW2T', 'Ballpoint Pen (12pcs per pack)', 108.00, 1, 'supplies', 'Delivered', '2026-02-14', '2026-03-17', NULL, 'Converted from Consolidated Request: CONS20260213WHZPV. Original Department: N/A', 'yes', '2026-02-13 19:45:02', '2026-02-13 23:43:05'),
(4, 'PCPD20260214Y07KX', 'PROD20260123O57W5', 'Joy Stapler Promo Bundle 2s', 155.00, 1, 'supplies', 'Delivered', '2026-02-14', '2026-03-17', NULL, 'Converted from Consolidated Request: CONS20260213WHZPV. Original Department: N/A', 'yes', '2026-02-13 19:45:02', '2026-02-14 15:37:09'),
(5, 'PCPD20260214BZLST', 'PROD20260123WWVO5', 'HBW Office Vinyl Coated Paper Clip 50mm', 53.00, 1, 'supplies', 'Delivered', '2026-02-14', '2026-03-17', NULL, 'Converted from Consolidated Request: CONS20260214BRRVB. Original Department: Administrative Office', 'yes', '2026-02-14 05:02:50', '2026-02-14 14:40:42'),
(6, 'PCPD20260214ENJPH', 'PROD2026012396838', 'Bondpaper size-A4 (100-pcs bundle)', 200.00, 1, 'supplies', 'Delivered', '2026-02-14', '2026-08-19', NULL, 'Converted from Consolidated Request: CONS20260214BRRVB. Original Department: Administrative Office', 'no', '2026-02-14 05:02:50', '2026-02-14 05:02:50'),
(7, 'PCPD20260214LBQVW', 'PROD202601232EW2T', 'Ballpoint Pen (12pcs per pack)', 108.00, 1, 'supplies', 'Delivered', '2026-02-14', '2026-03-17', NULL, 'Converted from Consolidated Request: CONS20260214BRRVB. Original Department: Administrative Office', 'no', '2026-02-14 05:02:50', '2026-02-14 05:02:50'),
(8, 'PCPD20260214T48BC', 'PROD20260123O57W5', 'Joy Stapler Promo Bundle 2s', 155.00, 1, 'supplies', 'Delivered', '2026-02-14', '2026-03-17', NULL, 'Converted from Consolidated Request: CONS20260214BRRVB. Original Department: Administrative Office', 'yes', '2026-02-14 05:02:50', '2026-02-14 15:37:09'),
(9, 'PCPD2026021426TYG', 'PROD2026021062MDC', 'Filing Cabinet', 1600.00, 1, 'furniture', 'Delivered', '2026-02-14', '2027-02-14', NULL, 'Converted from Consolidated Request: CONS20260214D4DAT. Original Department: Core Transaction Office', 'yes', '2026-02-14 14:52:04', '2026-02-14 15:27:06'),
(10, 'PCPD202602148GIAP', 'PROD2026021062MDC', 'Filing Cabinet', 1600.00, 1, 'furniture', 'Delivered', '2026-02-14', '2027-02-14', NULL, 'Converted from Consolidated Request: CONS20260213GSFCT. Original Department: Core Transaction Office', 'yes', '2026-02-14 14:52:04', '2026-02-14 15:27:06'),
(11, 'PCPD20260214GRAOY', 'PROD20260209HKZNM', 'Mitsubishi Fuso Canter Cruise (Minibus)', 2600000.00, 1, 'automotive', 'Delivered', '2026-02-14', '2031-02-13', '2030-01-10', 'Converted from Consolidated Request: CONS20260214W37QI. Original Department: Logistics Office', 'yes', '2026-02-14 14:52:04', '2026-02-14 15:12:11'),
(12, 'PCPD20260214HVGKH', 'PROD202602098QOMK', 'Toyota Hiace', 1379000.00, 1, 'automotive', 'Delivered', '2026-02-14', '2027-02-14', '2027-01-01', 'Converted from Consolidated Request: CONS20260214LRNPZ. Original Department: Logistics Office', 'yes', '2026-02-14 14:52:04', '2026-02-14 15:33:49'),
(13, 'PCPD20260214IDTZ4', 'PROD20260209G0UJU', 'Honda CB500', 379000.00, 1, 'automotive', 'Delivered', '2026-02-14', '2027-02-14', '2027-01-01', 'Converted from Consolidated Request: CONS202602142TDUK. Original Department: Logistics Office', 'yes', '2026-02-14 14:52:04', '2026-02-14 15:35:53'),
(14, 'PCPD20260214JBYQB', 'PROD20260211L2K2E', 'ThinkPad E16 AMD G2', 536700.00, 1, 'equipment', 'Delivered', '2026-02-14', '2027-02-14', '2027-02-11', 'Converted from Consolidated Request: CONS20260214V4ZKF. Original Department: Core Transaction Office', 'yes', '2026-02-14 14:52:04', '2026-02-14 15:36:28'),
(15, 'PCPD20260214KMBZB', 'PROD20260211FUJXJ', 'Canon Pixma G3010 All-in-One Ink Tank Printer (Wi-Fi Connectivity, 2315C018AB, Black)', 8732.00, 1, 'equipment', 'Delivered', '2026-02-14', '2027-02-14', '2027-02-11', 'Converted from Consolidated Request: CONS20260214V4ZKF. Original Department: Core Transaction Office', 'yes', '2026-02-14 14:52:04', '2026-02-14 15:36:49'),
(16, 'PCPD20260214L4VAL', 'PROD20260214ETQIC', 'BATTERY-LN3 (DIN Size / H6 Equivalent)', 11000.00, 1, 'automotive', 'Delivered', '2026-02-14', '2026-08-19', '2027-02-14', 'Converted from Consolidated Request: CONS20260214PUDSB. Original Department: Log2 Dept', 'yes', '2026-02-14 14:52:04', '2026-02-14 15:43:42'),
(17, 'PCPD20260214OSE8L', 'PROD20260211EC7RA', '￼HBW Office Heavy Duty Puncher', 167.00, 1, 'equipment', 'Delivered', '2026-02-14', '2027-02-14', '2027-02-11', 'Converted from Consolidated Request: CONS20260214V4ZKF. Original Department: Core Transaction Office', 'no', '2026-02-14 14:52:04', '2026-02-14 14:52:04'),
(18, 'PCPD20260214YPEGH', 'PROD202602104UJJE', 'COMIX S3508D (8 sheets, 22L Micro Cut Heavy Duty Paper Shredder)', 13560.00, 1, 'equipment', 'Delivered', '2026-02-14', '2027-02-14', '2027-02-11', 'Converted from Consolidated Request: CONS20260214V4ZKF. Original Department: Core Transaction Office', 'no', '2026-02-14 14:52:04', '2026-02-14 14:52:04'),
(19, 'PCPD20260214CVJOI', 'PROD20260209WNKAJ', 'Mitsubishi Fuso Canter FE73 (6-Wheeler)', 2400000.00, 1, 'automotive', 'Delivered', '2026-02-14', '2031-02-13', '2030-01-10', 'Converted from Consolidated Request: CONS202602147DAYD. Original Department: Logistics Office', 'no', '2026-02-14 15:58:34', '2026-02-14 15:58:34'),
(20, 'PCPD20260214UQPQH', 'PROD20260209G0UJU', 'Honda CB500', 379000.00, 1, 'automotive', 'Delivered', '2026-02-14', '2027-02-14', '2027-01-01', 'Converted from Consolidated Request: CONS20260213Y5PDH. Original Department: Logistics Office', 'no', '2026-02-14 15:58:34', '2026-02-14 15:58:34'),
(21, 'PCPD20260215UNRWW', 'PROD202602095J9D5', '2012 Isuzu NPR', 980000.00, 1, 'automotive', 'Delivered', '2026-02-15', '2027-02-15', '2026-09-23', 'Converted from Consolidated Request: CONS20260214EVZSD. Original Department: Logistics Office', 'no', '2026-02-14 16:04:34', '2026-02-14 16:04:34');

-- --------------------------------------------------------

--
-- Table structure for table `sws_inventory_audits`
--

CREATE TABLE `sws_inventory_audits` (
  `aud_id` bigint(20) UNSIGNED NOT NULL,
  `aud_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `aud_location_id` varchar(25) DEFAULT NULL,
  `aud_warehouse_id` varchar(20) DEFAULT NULL,
  `aud_adjustment_type` enum('count','adjustment','expiration_writeoff','warranty_check') DEFAULT NULL,
  `aud_quantity_change` int(11) DEFAULT NULL,
  `aud_reason` text DEFAULT NULL,
  `aud_audit_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `aud_audited_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sws_inventory_snapshots`
--

CREATE TABLE `sws_inventory_snapshots` (
  `snap_id` bigint(20) UNSIGNED NOT NULL,
  `snap_item_id` bigint(20) UNSIGNED NOT NULL,
  `snap_location_id` varchar(25) NOT NULL,
  `snap_warehouse_id` varchar(20) DEFAULT NULL,
  `snap_current_quantity` int(11) NOT NULL,
  `snap_min_threshold` int(11) NOT NULL DEFAULT 0,
  `snap_alert_level` enum('normal','low','critical') NOT NULL DEFAULT 'normal',
  `snap_snapshot_date` date NOT NULL,
  `snap_recorded_by` varchar(100) DEFAULT NULL,
  `snap_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sws_items`
--

CREATE TABLE `sws_items` (
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `item_code` varchar(20) DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_description` text DEFAULT NULL,
  `item_stock_keeping_unit` varchar(100) DEFAULT NULL,
  `item_category_id` varchar(100) DEFAULT NULL,
  `item_stored_from` varchar(100) DEFAULT NULL,
  `item_item_type` enum('liquid','illiquid','hybrid') NOT NULL DEFAULT 'illiquid',
  `item_is_fixed` tinyint(1) NOT NULL DEFAULT 0,
  `item_expiration_date` varchar(100) DEFAULT NULL,
  `item_warranty_end` varchar(100) DEFAULT NULL,
  `item_unit_price` decimal(10,2) DEFAULT NULL,
  `item_total_quantity` int(11) NOT NULL DEFAULT 0,
  `item_current_stock` int(11) NOT NULL DEFAULT 0,
  `item_max_stock` int(11) NOT NULL DEFAULT 100,
  `item_liquidity_risk_level` enum('high','medium','low') NOT NULL DEFAULT 'medium',
  `item_is_collateral` tinyint(1) NOT NULL DEFAULT 0,
  `item_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `item_updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sws_items`
--

INSERT INTO `sws_items` (`item_id`, `item_code`, `item_name`, `item_description`, `item_stock_keeping_unit`, `item_category_id`, `item_stored_from`, `item_item_type`, `item_is_fixed`, `item_expiration_date`, `item_warranty_end`, `item_unit_price`, `item_total_quantity`, `item_current_stock`, `item_max_stock`, `item_liquidity_risk_level`, `item_is_collateral`, `item_created_at`, `item_updated_at`) VALUES
(2, 'ITM202602012BAB5', 'HBW Office Vinyl Coated Paper Clip 50mm', NULL, 'PROD20260123WWVO5', 'ICAT20260125HUHIU', 'Main Warehouse', 'liquid', 0, NULL, '2026-03-03', 53.00, 0, 20, 50, 'low', 0, '2026-02-01 05:13:02', '2026-02-14 22:40:41'),
(3, 'ITM20260209NENUA', 'Joy Stapler Promo Bundle 2s', 'descript', 'PROD20260123O57W5', 'ICAT20260125HUHIU', 'Main Warehouse', 'liquid', 0, NULL, '2026-03-03', 155.00, 0, 8, 50, 'low', 0, '2026-02-09 01:04:56', '2026-02-14 23:37:09'),
(4, 'ITM20260209DY7E4', 'Bondpaper size-A4 (100-pcs bundle)', NULL, 'PROD2026012396838', 'ICAT20260125HUHIU', 'Main Warehouse', 'liquid', 0, NULL, '2026-08-13', 100.00, 0, 21, 50, 'low', 0, '2026-02-09 01:05:15', '2026-02-14 19:07:23'),
(5, 'ITM20260209M2XAD', 'Ballpoint Pen (12pcs per pack)', NULL, 'PROD202601232EW2T', 'ICAT20260125HUHIU', 'Main Warehouse', 'liquid', 0, NULL, '2026-03-11', 108.00, 0, 5, 50, 'low', 0, '2026-02-09 01:06:08', '2026-02-14 07:43:05'),
(7, 'ITM202602147KO5G', 'Mitsubishi Fuso Canter Cruise (Minibus)', 'Converted from Consolidated Request: CONS20260214W37QI. Original Department: Logistics Office', 'PROD20260209HKZNM', 'ICAT20260125WEO0B', 'Main Warehouse', 'hybrid', 1, '2030-01-10', '2031-02-13', 2600000.00, 0, 1, 1, 'medium', 0, '2026-02-14 23:12:11', '2026-02-14 23:16:05'),
(8, 'ITM20260214L323P', 'Filing Cabinet', 'Converted from Consolidated Request: CONS20260214D4DAT. Original Department: Core Transaction Office', 'PROD2026021062MDC', 'ICAT20260125JXBCQ', 'Main Warehouse', 'illiquid', 1, NULL, '2027-02-14', 3200.00, 0, 8, 10, 'medium', 0, '2026-02-14 23:15:05', '2026-02-14 23:27:05'),
(9, 'ITM202602143YQ54', 'Toyota Hiace', 'Converted from Consolidated Request: CONS20260214LRNPZ. Original Department: Logistics Office', 'PROD202602098QOMK', 'ICAT20260125WEO0B', 'Main Warehouse', 'hybrid', 1, '2027-01-01', '2027-02-14', 1379000.00, 0, 1, 2, 'medium', 0, '2026-02-14 23:33:48', '2026-02-14 23:37:45'),
(10, 'ITM20260214CUHA8', 'Honda CB500', 'Converted from Consolidated Request: CONS202602142TDUK. Original Department: Logistics Office', 'PROD20260209G0UJU', 'ICAT20260125WEO0B', 'Main Warehouse', 'hybrid', 1, '2027-01-01', '2027-02-14', 379000.00, 0, 1, 2, 'medium', 0, '2026-02-14 23:35:52', '2026-02-14 23:37:36'),
(11, 'ITM20260214MMQ92', 'ThinkPad E16 AMD G2', 'Converted from Consolidated Request: CONS20260214V4ZKF. Original Department: Core Transaction Office', 'PROD20260211L2K2E', 'ICAT20260125DPZFQ', 'Main Warehouse', 'illiquid', 1, '2027-02-11', '2027-02-14', 536700.00, 0, 1, 10, 'high', 0, '2026-02-14 23:36:28', '2026-02-14 23:44:54'),
(12, 'ITM20260214NIK5B', 'Canon Pixma G3010 All-in-One Ink Tank Printer (Wi-Fi Connectivity, 2315C018AB, Black)', 'Converted from Consolidated Request: CONS20260214V4ZKF. Original Department: Core Transaction Office', 'PROD20260211FUJXJ', 'ICAT20260125DPZFQ', 'Main Warehouse', 'hybrid', 1, '2027-02-11', '2027-02-14', 8732.00, 0, 1, 10, 'high', 0, '2026-02-14 23:36:48', '2026-02-14 23:44:37'),
(13, 'ITM20260214TCWY9', 'BATTERY-LN3 (DIN Size / H6 Equivalent)', 'Converted from Consolidated Request: CONS20260214PUDSB. Original Department: Log2 Dept', 'PROD20260214ETQIC', 'ICAT20260125WEO0B', 'Main Warehouse', 'hybrid', 0, '2027-02-14', '2026-08-19', 11000.00, 0, 1, 10, 'medium', 0, '2026-02-14 23:43:41', '2026-02-14 23:44:19');

-- --------------------------------------------------------

--
-- Table structure for table `sws_locations`
--

CREATE TABLE `sws_locations` (
  `loc_id` varchar(25) NOT NULL,
  `loc_name` varchar(100) NOT NULL,
  `loc_type` enum('warehouse','storage_room','office','facility','drop_point','bin','department','room') DEFAULT NULL,
  `loc_zone_type` enum('liquid','illiquid','climate_controlled','general') NOT NULL,
  `loc_supports_fixed_items` tinyint(1) NOT NULL DEFAULT 1,
  `loc_capacity` int(11) DEFAULT NULL,
  `loc_parent_id` varchar(25) DEFAULT NULL,
  `loc_is_active` tinyint(1) NOT NULL DEFAULT 1,
  `loc_created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sws_locations`
--

INSERT INTO `sws_locations` (`loc_id`, `loc_name`, `loc_type`, `loc_zone_type`, `loc_supports_fixed_items`, `loc_capacity`, `loc_parent_id`, `loc_is_active`, `loc_created_at`) VALUES
('', 'Core Transaction Office', 'office', 'general', 1, 1000, NULL, 1, '2026-02-14 07:56:36'),
('LCTN202601256KQ0Q', 'Financial Department', 'department', 'general', 1, 1000, NULL, 1, '2026-01-24 23:16:28'),
('LCTN202601258ZNLF', 'Logistics Office', 'office', 'general', 1, 2000, NULL, 1, '2026-01-24 20:59:00'),
('LCTN20260125B8MHL', 'Administrative Office', 'office', 'general', 1, 1000, NULL, 1, '2026-01-24 23:14:13'),
('LCTN20260125JH4CA', 'Human Resource Department', 'department', 'general', 1, 1000, NULL, 1, '2026-01-24 23:14:37'),
('LCTN20260211X6ING', 'Garage', 'facility', 'general', 1, 1000, NULL, 1, '2026-02-10 21:36:24'),
('LCTN20260214WH7IF', 'Core Transaction Office', 'office', 'general', 1, 1000, NULL, 1, '2026-02-14 00:01:21');

-- --------------------------------------------------------

--
-- Table structure for table `sws_room_request`
--

CREATE TABLE `sws_room_request` (
  `rmreq_id` int(10) UNSIGNED NOT NULL,
  `rmreq_requester` varchar(100) DEFAULT NULL,
  `rmreq_room_type` enum('office','department','facility','room','storage') NOT NULL,
  `rmreq_note` text DEFAULT NULL,
  `rmreq_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `rmreq_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sws_room_request`
--

INSERT INTO `sws_room_request` (`rmreq_id`, `rmreq_requester`, `rmreq_room_type`, `rmreq_note`, `rmreq_date`, `rmreq_status`) VALUES
(2, 'Ric Jason Altamante - superadmin', 'storage', 'room pre penge', '2026-02-13 16:00:00', 'pending'),
(3, 'Ric Jason Altamante - superadmin', 'storage', 'storage room request', '2026-02-13 16:00:00', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `sws_transactions`
--

CREATE TABLE `sws_transactions` (
  `tra_id` bigint(20) UNSIGNED NOT NULL,
  `tra_item_id` bigint(20) UNSIGNED NOT NULL,
  `tra_type` enum('inbound','outbound','transfer','pick_up','drop_off','adjustment') NOT NULL,
  `tra_quantity` int(11) NOT NULL,
  `tra_from_location_id` varchar(25) DEFAULT NULL,
  `tra_to_location_id` varchar(25) DEFAULT NULL,
  `tra_warehouse_id` varchar(20) DEFAULT NULL,
  `tra_transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `tra_reference_id` varchar(100) DEFAULT NULL,
  `tra_status` enum('pending','in_transit','completed','cancelled') NOT NULL DEFAULT 'pending',
  `tra_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sws_transactions`
--

INSERT INTO `sws_transactions` (`tra_id`, `tra_item_id`, `tra_type`, `tra_quantity`, `tra_from_location_id`, `tra_to_location_id`, `tra_warehouse_id`, `tra_transaction_date`, `tra_reference_id`, `tra_status`, `tra_notes`) VALUES
(1, 4, 'transfer', 10, NULL, 'LCTN20260125B8MHL', NULL, '2026-02-14 18:19:38', 'RF202602157O4S4', 'pending', 'Managed by PLT project 6'),
(2, 4, 'transfer', 10, NULL, 'LCTN20260125B8MHL', NULL, '2026-02-14 18:43:35', 'RF202602159G9U0', 'pending', 'Managed by PLT project 6'),
(3, 4, 'transfer', 10, NULL, 'LCTN20260125B8MHL', NULL, '2026-02-14 19:07:23', 'PLT-MOVE-1', 'completed', 'Finalized PLT movement to Administrative Office'),
(4, 9, 'transfer', 1, NULL, 'LCTN20260211X6ING', NULL, '2026-02-14 19:10:18', 'RF202602153X9U6', 'pending', 'Managed by PLT project 7');

-- --------------------------------------------------------

--
-- Table structure for table `sws_transaction_logs`
--

CREATE TABLE `sws_transaction_logs` (
  `log_id` bigint(20) UNSIGNED NOT NULL,
  `log_transaction_id` bigint(20) UNSIGNED NOT NULL,
  `log_event` varchar(100) DEFAULT NULL,
  `log_details` text DEFAULT NULL,
  `log_logged_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `log_logged_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sws_warehouse`
--

CREATE TABLE `sws_warehouse` (
  `ware_id` varchar(20) NOT NULL,
  `ware_name` varchar(100) NOT NULL,
  `ware_location` varchar(255) DEFAULT NULL,
  `ware_capacity` int(11) DEFAULT NULL,
  `ware_capacity_used` int(11) DEFAULT NULL,
  `ware_capacity_free` int(11) DEFAULT NULL,
  `ware_utilization` decimal(5,2) NOT NULL DEFAULT 0.00,
  `ware_status` enum('active','inactive','maintenance') NOT NULL DEFAULT 'active',
  `ware_zone_type` enum('liquid','illiquid','climate_controlled','general') NOT NULL DEFAULT 'general',
  `ware_supports_fixed_items` tinyint(1) NOT NULL DEFAULT 1,
  `ware_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ware_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sws_warehouse`
--

INSERT INTO `sws_warehouse` (`ware_id`, `ware_name`, `ware_location`, `ware_capacity`, `ware_capacity_used`, `ware_capacity_free`, `ware_utilization`, `ware_status`, `ware_zone_type`, `ware_supports_fixed_items`, `ware_created_at`, `ware_updated_at`) VALUES
('WRHS202601147H4B4', 'Main Warehouse', 'Main Warehouse Building Behind the Javes Cooperative Main Branch Building', 10000, 0, 10000, 0.00, 'active', 'general', 1, '2026-01-14 08:02:41', '2026-01-14 08:03:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sws_categories`
--
ALTER TABLE `sws_categories`
  ADD PRIMARY KEY (`cat_id`),
  ADD UNIQUE KEY `sws_categories_cat_name_unique` (`cat_name`);

--
-- Indexes for table `sws_incoming_asset`
--
ALTER TABLE `sws_incoming_asset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sws_incoming_asset_sws_purcprod_id_index` (`sws_purcprod_id`),
  ADD KEY `sws_incoming_asset_sws_purcprod_prod_id_index` (`sws_purcprod_prod_id`);

--
-- Indexes for table `sws_inventory_audits`
--
ALTER TABLE `sws_inventory_audits`
  ADD PRIMARY KEY (`aud_id`),
  ADD KEY `sws_inventory_audits_aud_item_id_foreign` (`aud_item_id`),
  ADD KEY `sws_inventory_audits_aud_warehouse_id_foreign` (`aud_warehouse_id`),
  ADD KEY `sws_inventory_audits_aud_location_id_foreign` (`aud_location_id`);

--
-- Indexes for table `sws_inventory_snapshots`
--
ALTER TABLE `sws_inventory_snapshots`
  ADD PRIMARY KEY (`snap_id`),
  ADD KEY `sws_inventory_snapshots_snap_item_id_foreign` (`snap_item_id`),
  ADD KEY `sws_inventory_snapshots_snap_warehouse_id_foreign` (`snap_warehouse_id`),
  ADD KEY `sws_inventory_snapshots_snap_location_id_foreign` (`snap_location_id`);

--
-- Indexes for table `sws_items`
--
ALTER TABLE `sws_items`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `sws_items_item_stock_keeping_unit_unique` (`item_stock_keeping_unit`),
  ADD UNIQUE KEY `sws_items_item_code_unique` (`item_code`),
  ADD KEY `sws_items_item_category_id_foreign` (`item_category_id`);

--
-- Indexes for table `sws_locations`
--
ALTER TABLE `sws_locations`
  ADD PRIMARY KEY (`loc_id`),
  ADD KEY `sws_locations_loc_parent_id_foreign` (`loc_parent_id`);

--
-- Indexes for table `sws_room_request`
--
ALTER TABLE `sws_room_request`
  ADD PRIMARY KEY (`rmreq_id`);

--
-- Indexes for table `sws_transactions`
--
ALTER TABLE `sws_transactions`
  ADD PRIMARY KEY (`tra_id`),
  ADD KEY `sws_transactions_tra_item_id_foreign` (`tra_item_id`),
  ADD KEY `sws_transactions_tra_warehouse_id_foreign` (`tra_warehouse_id`),
  ADD KEY `sws_transactions_tra_from_location_id_foreign` (`tra_from_location_id`),
  ADD KEY `sws_transactions_tra_to_location_id_foreign` (`tra_to_location_id`);

--
-- Indexes for table `sws_transaction_logs`
--
ALTER TABLE `sws_transaction_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `sws_transaction_logs_log_transaction_id_foreign` (`log_transaction_id`);

--
-- Indexes for table `sws_warehouse`
--
ALTER TABLE `sws_warehouse`
  ADD PRIMARY KEY (`ware_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sws_incoming_asset`
--
ALTER TABLE `sws_incoming_asset`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sws_inventory_audits`
--
ALTER TABLE `sws_inventory_audits`
  MODIFY `aud_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sws_inventory_snapshots`
--
ALTER TABLE `sws_inventory_snapshots`
  MODIFY `snap_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sws_items`
--
ALTER TABLE `sws_items`
  MODIFY `item_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `sws_room_request`
--
ALTER TABLE `sws_room_request`
  MODIFY `rmreq_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sws_transactions`
--
ALTER TABLE `sws_transactions`
  MODIFY `tra_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sws_transaction_logs`
--
ALTER TABLE `sws_transaction_logs`
  MODIFY `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sws_inventory_audits`
--
ALTER TABLE `sws_inventory_audits`
  ADD CONSTRAINT `sws_inventory_audits_aud_item_id_foreign` FOREIGN KEY (`aud_item_id`) REFERENCES `sws_items` (`item_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sws_inventory_audits_aud_location_id_foreign` FOREIGN KEY (`aud_location_id`) REFERENCES `sws_locations` (`loc_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sws_inventory_audits_aud_warehouse_id_foreign` FOREIGN KEY (`aud_warehouse_id`) REFERENCES `sws_warehouse` (`ware_id`) ON DELETE CASCADE;

--
-- Constraints for table `sws_inventory_snapshots`
--
ALTER TABLE `sws_inventory_snapshots`
  ADD CONSTRAINT `sws_inventory_snapshots_snap_item_id_foreign` FOREIGN KEY (`snap_item_id`) REFERENCES `sws_items` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sws_inventory_snapshots_snap_location_id_foreign` FOREIGN KEY (`snap_location_id`) REFERENCES `sws_locations` (`loc_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sws_inventory_snapshots_snap_warehouse_id_foreign` FOREIGN KEY (`snap_warehouse_id`) REFERENCES `sws_warehouse` (`ware_id`) ON DELETE CASCADE;

--
-- Constraints for table `sws_items`
--
ALTER TABLE `sws_items`
  ADD CONSTRAINT `sws_items_item_category_id_foreign` FOREIGN KEY (`item_category_id`) REFERENCES `sws_categories` (`cat_id`) ON DELETE SET NULL;

--
-- Constraints for table `sws_locations`
--
ALTER TABLE `sws_locations`
  ADD CONSTRAINT `sws_locations_loc_parent_id_foreign` FOREIGN KEY (`loc_parent_id`) REFERENCES `sws_locations` (`loc_id`) ON DELETE SET NULL;

--
-- Constraints for table `sws_transactions`
--
ALTER TABLE `sws_transactions`
  ADD CONSTRAINT `sws_transactions_tra_from_location_id_foreign` FOREIGN KEY (`tra_from_location_id`) REFERENCES `sws_locations` (`loc_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sws_transactions_tra_item_id_foreign` FOREIGN KEY (`tra_item_id`) REFERENCES `sws_items` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sws_transactions_tra_to_location_id_foreign` FOREIGN KEY (`tra_to_location_id`) REFERENCES `sws_locations` (`loc_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sws_transactions_tra_warehouse_id_foreign` FOREIGN KEY (`tra_warehouse_id`) REFERENCES `sws_warehouse` (`ware_id`) ON DELETE CASCADE;

--
-- Constraints for table `sws_transaction_logs`
--
ALTER TABLE `sws_transaction_logs`
  ADD CONSTRAINT `sws_transaction_logs_log_transaction_id_foreign` FOREIGN KEY (`log_transaction_id`) REFERENCES `sws_transactions` (`tra_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
