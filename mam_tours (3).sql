-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 17, 2026 at 09:44 PM
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
-- Database: `mam_tours`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `subject_id`, `causer_type`, `causer_id`, `properties`, `created_at`, `updated_at`) VALUES
(1, 'default', 'created', 'App\\Models\\User', 1, NULL, NULL, '[]', '2026-02-17 09:37:15', '2026-02-17 09:37:15'),
(2, 'default', 'created', 'App\\Models\\User', 2, NULL, NULL, '[]', '2026-02-17 09:37:15', '2026-02-17 09:37:15');

-- --------------------------------------------------------

--
-- Table structure for table `analytics_events`
--

CREATE TABLE `analytics_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `session_id` varchar(100) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `referrer` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(100) NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `car_id` bigint(20) UNSIGNED NOT NULL,
  `kyc_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customerName` varchar(200) NOT NULL,
  `customerEmail` varchar(255) DEFAULT NULL,
  `customerPhone` varchar(255) DEFAULT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `phone_number` varchar(255) DEFAULT NULL,
  `mobile_money_number` varchar(20) DEFAULT NULL,
  `idType` varchar(255) DEFAULT NULL,
  `idNumber` varchar(255) DEFAULT NULL,
  `idDocument` varchar(255) DEFAULT NULL,
  `pricing` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`pricing`)),
  `totalPrice` decimal(12,2) DEFAULT NULL,
  `addOns` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`addOns`)),
  `payment` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment`)),
  `conditionReports` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`conditionReports`)),
  `expiresAt` datetime DEFAULT NULL,
  `confirmedAt` datetime DEFAULT NULL,
  `checkedOutAt` datetime DEFAULT NULL,
  `returnedAt` datetime DEFAULT NULL,
  `canceledAt` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `carPicture` varchar(100) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `numberPlate` varchar(50) NOT NULL,
  `dailyRate` int(11) NOT NULL,
  `seats` int(11) NOT NULL,
  `transmission` varchar(20) NOT NULL DEFAULT 'Automatic',
  `fuel_type` varchar(20) NOT NULL DEFAULT 'Petrol',
  `year` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `booking_count` int(11) NOT NULL DEFAULT 0,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `luggage_capacity` varchar(20) DEFAULT NULL,
  `doors` varchar(10) NOT NULL DEFAULT '4',
  `isAvailable` tinyint(1) NOT NULL DEFAULT 1,
  `category` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `carPicture`, `brand`, `model`, `numberPlate`, `dailyRate`, `seats`, `transmission`, `fuel_type`, `year`, `description`, `features`, `is_featured`, `view_count`, `booking_count`, `rating`, `luggage_capacity`, `doors`, `isAvailable`, `category`, `created_at`, `updated_at`) VALUES
(1, 'Noah.jpeg', 'Toyota', 'Noah', 'UBB 123A', 100000, 8, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'Van', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(2, 'Prado.jpg', 'Toyota', 'Prado', 'UBB 124A', 150000, 7, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'SUV', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(3, 'Hilux.jpg', 'Toyota', 'Hilux', 'UBB 125A', 120000, 5, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'Pickup', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(4, 'Toyota Hiace.jpg', 'Toyota', 'Hiace', 'UBB 126A', 130000, 14, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'Van', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(5, 'Toyota Fortuner.jpg', 'Toyota', 'Fortuner', 'UBB 127A', 140000, 7, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'SUV', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(6, 'Harrier.jpg', 'Toyota', 'Harrier', 'UBB 128A', 135000, 5, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'SUV', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(7, 'Rav 4.jpeg', 'Toyota', 'Rav 4', 'UBB 129A', 125000, 5, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'SUV', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(8, 'Auris.jpg', 'Toyota', 'Auris', 'UBB 130A', 80000, 5, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'Sedan', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(9, 'Toyota Avensis.jpg', 'Toyota', 'Avensis', 'UBB 131A', 85000, 5, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'Sedan', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(10, 'Toyota Fielder.jpg', 'Toyota', 'Fielder', 'UBB 132A', 90000, 5, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'Station Wagon', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(11, 'Toyota Isis.jpg', 'Toyota', 'Isis', 'UBB 133A', 110000, 7, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'Van', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(12, 'Spacio.jpg', 'Toyota', 'Spacio', 'UBB 134A', 105000, 7, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'Van', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(13, 'Rumion.jpg', 'Toyota', 'Rumion', 'UBB 135A', 108000, 7, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'Van', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(14, 'Toyota Runx.jpg', 'Toyota', 'Runx', 'UBB 136A', 75000, 5, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'Hatchback', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(15, 'Toyota Allex.jpg', 'Toyota', 'Allex', 'UBB 137A', 70000, 5, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'Hatchback', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(16, 'Passo.jpg', 'Toyota', 'Passo', 'UBB 138A', 65000, 5, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'Hatchback', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(17, 'Premio.jpg', 'Toyota', 'Premio', 'UBB 139A', 82000, 5, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'Sedan', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(18, 's class.jpeg', 'Mercedes-Benz', 'S Class', 'UBB 140A', 200000, 5, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'Luxury Sedan', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(19, 'Gle.jpeg', 'Mercedes-Benz', 'GLE', 'UBB 141A', 220000, 7, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'Luxury SUV', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(20, 'Jeep Grand Cherokee.jpg', 'Jeep', 'Grand Cherokee', 'UBB 142A', 160000, 5, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'SUV', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(21, 'jeep wrangler.jpg', 'Jeep', 'Wrangler', 'UBB 143A', 155000, 5, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'SUV', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(22, 'Land cruiser.jpg', 'Land Rover', 'Land Cruiser', 'UBB 144A', 180000, 7, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'SUV', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(23, 'Jaguar xf 2015.jpg', 'Jaguar', 'XF 2015', 'UBB 145A', 210000, 5, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'Luxury Sedan', '2026-02-17 09:37:00', '2026-02-17 09:37:00'),
(24, 'Alphard.jpeg', 'Nissan', 'Alphard', 'UBB 146A', 115000, 8, 'Automatic', 'Petrol', NULL, NULL, NULL, 0, 0, 0, 0.00, NULL, '4', 1, 'Van', '2026-02-17 09:37:00', '2026-02-17 09:37:00');

-- --------------------------------------------------------

--
-- Table structure for table `condition_photos`
--

CREATE TABLE `condition_photos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `condition_reports`
--

CREATE TABLE `condition_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(20) NOT NULL,
  `checklist` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`checklist`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_analytics`
--

CREATE TABLE `daily_analytics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `total_visitors` int(11) NOT NULL,
  `unique_visitors` int(11) NOT NULL,
  `total_page_views` int(11) NOT NULL,
  `new_users` int(11) NOT NULL,
  `total_bookings` int(11) NOT NULL,
  `total_booking_value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `completed_bookings` int(11) NOT NULL,
  `pending_bookings` int(11) NOT NULL,
  `avg_session_duration` decimal(8,2) NOT NULL DEFAULT 0.00,
  `bounce_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `top_pages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`top_pages`)),
  `traffic_sources` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`traffic_sources`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `kyc_audit_logs`
--

CREATE TABLE `kyc_audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kyc_verification_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(50) NOT NULL,
  `performed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kyc_document_versions`
--

CREATE TABLE `kyc_document_versions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kyc_verification_id` bigint(20) UNSIGNED NOT NULL,
  `version` int(11) NOT NULL,
  `document_type` varchar(50) NOT NULL,
  `document_path` varchar(255) NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kyc_verifications`
--

CREATE TABLE `kyc_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `id_type` varchar(255) DEFAULT NULL,
  `id_number` varchar(255) DEFAULT NULL,
  `permit_number` varchar(255) DEFAULT NULL,
  `id_document_path` varchar(255) DEFAULT NULL,
  `id_original_document_path` varchar(255) DEFAULT NULL,
  `permit_document_path` varchar(255) DEFAULT NULL,
  `permit_original_document_path` varchar(255) DEFAULT NULL,
  `document_metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`document_metadata`)),
  `risk_score` int(11) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `additional_info_message` text DEFAULT NULL,
  `automated_checks` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`automated_checks`)),
  `automated_checks_at` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `verification_notes` text DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint(20) UNSIGNED DEFAULT NULL,
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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_05_03_000001_create_customer_columns', 1),
(4, '2019_05_03_000002_create_subscriptions_table', 1),
(5, '2019_05_03_000003_create_subscription_items_table', 1),
(6, '2019_08_19_000000_create_failed_jobs_table', 1),
(7, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(8, '2026_01_29_210136_create_cars_table', 1),
(9, '2026_01_29_210158_create_bookings_table', 1),
(10, '2026_01_29_210214_create_audit_logs_table', 1),
(11, '2026_01_29_210230_create_condition_reports_table', 1),
(12, '2026_01_29_210251_create_condition_photos_table', 1),
(13, '2026_01_29_221822_add_phone_to_users', 1),
(14, '2026_01_29_221857_create_notifications_table', 1),
(15, '2026_01_29_221915_create_sms_logs_table', 1),
(16, '2026_01_29_223651_add_fields_to_users_table', 1),
(17, '2026_02_01_000000_add_profile_picture_to_users', 1),
(18, '2026_02_02_000000_add_payment_fields_to_bookings', 1),
(19, '2026_02_02_000001_create_kyc_table', 1),
(20, '2026_02_02_000002_add_kyc_to_bookings', 1),
(21, '2026_02_07_000000_add_user_and_kyc_to_bookings', 1),
(22, '2026_02_07_000001_add_original_documents_to_kyc', 1),
(23, '2026_02_13_231746_add_mobile_money_number_to_bookings_table', 1),
(24, '2026_02_13_232605_create_reviews_table', 1),
(25, '2026_02_14_003057_create_activity_log_table', 1),
(26, '2026_02_14_003100_add_two_factor_to_users', 1),
(27, '2026_02_15_195700_add_performance_indexes_to_tables', 1),
(28, '2026_02_15_200000_create_analytics_events_table', 1),
(29, '2026_02_15_201000_create_webhooks_table', 1),
(30, '2026_02_15_202000_enhance_kyc_verifications_table', 1),
(31, '2026_02_16_000000_enhance_cars_table', 1),
(32, '2026_02_16_110000_add_booking_fields', 1),
(33, '2026_02_16_120000_create_visitor_tracking', 1),
(34, '2026_02_16_add_id_fields_to_users', 1),
(35, '2026_02_17_110228_update_car_prices_for_budget_vehicles', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `channel` varchar(255) NOT NULL,
  `response` text DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page_visits`
--

CREATE TABLE `page_visits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `page_url` varchar(255) NOT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `referrer` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) NOT NULL,
  `country` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `duration_seconds` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `rating` int(10) UNSIGNED NOT NULL DEFAULT 5,
  `review_text` text NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_logs`
--

CREATE TABLE `sms_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `phone_number` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(255) NOT NULL,
  `provider` varchar(255) NOT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `response` text DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `stripe_id` varchar(255) NOT NULL,
  `stripe_status` varchar(255) NOT NULL,
  `stripe_price` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_items`
--

CREATE TABLE `subscription_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subscription_id` bigint(20) UNSIGNED NOT NULL,
  `stripe_id` varchar(255) NOT NULL,
  `stripe_product` varchar(255) NOT NULL,
  `stripe_price` varchar(255) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) DEFAULT NULL,
  `id_number` varchar(255) DEFAULT NULL,
  `id_document` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `sms_notifications` tinyint(1) NOT NULL DEFAULT 1,
  `email_notifications` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stripe_id` varchar(255) DEFAULT NULL,
  `pm_type` varchar(255) DEFAULT NULL,
  `pm_last_four` varchar(4) DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(255) DEFAULT NULL,
  `failed_login_attempts` int(11) NOT NULL DEFAULT 0,
  `locked_until` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `profile_picture`, `id_type`, `id_number`, `id_document`, `email_verified_at`, `password`, `role`, `sms_notifications`, `email_notifications`, `remember_token`, `created_at`, `updated_at`, `stripe_id`, `pm_type`, `pm_last_four`, `trial_ends_at`, `two_factor_confirmed_at`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_enabled`, `last_login_at`, `last_login_ip`, `failed_login_attempts`, `locked_until`) VALUES
(1, 'MAM Tours Admin', 'wilberofficial2001@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$2hMAU/7hhIB7emCsPFn1D.fqo8vMiBmwf1WYxFy.ygV7cHBGsLD5q', 'admin', 1, 1, NULL, '2026-02-17 09:37:14', '2026-02-17 09:37:14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL),
(2, 'Test User', 'user@mamtours.com', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$VvKQYfVdAh4yVoLyjsorT.9b430Vu5MSQzvXeApaFp7sOpX417Nk2', 'user', 1, 1, NULL, '2026-02-17 09:37:15', '2026-02-17 09:37:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_actions`
--

CREATE TABLE `user_actions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action_type` varchar(255) NOT NULL,
  `action_name` varchar(255) NOT NULL,
  `resource_type` varchar(255) DEFAULT NULL,
  `resource_id` bigint(20) UNSIGNED DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `ip_address` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `device_type` varchar(255) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `os` varchar(255) DEFAULT NULL,
  `last_activity_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `webhook_deliveries`
--

CREATE TABLE `webhook_deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `webhook_endpoint_id` bigint(20) UNSIGNED NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `attempts` int(11) NOT NULL DEFAULT 0,
  `response_code` int(11) DEFAULT NULL,
  `response_body` text DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `webhook_endpoints`
--

CREATE TABLE `webhook_endpoints` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(255) NOT NULL,
  `secret` varchar(64) NOT NULL,
  `events` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`events`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Indexes for table `analytics_events`
--
ALTER TABLE `analytics_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `analytics_events_event_type_created_at_index` (`event_type`,`created_at`),
  ADD KEY `analytics_events_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `analytics_events_event_type_index` (`event_type`),
  ADD KEY `analytics_events_session_id_index` (`session_id`),
  ADD KEY `analytics_events_created_at_index` (`created_at`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_bookings_user` (`user_id`),
  ADD KEY `idx_bookings_car` (`car_id`),
  ADD KEY `idx_bookings_status` (`status`),
  ADD KEY `idx_bookings_dates` (`startDate`,`endDate`),
  ADD KEY `idx_bookings_status_date` (`status`,`startDate`),
  ADD KEY `idx_bookings_payment_status` (`payment_status`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cars_numberplate_unique` (`numberPlate`),
  ADD KEY `idx_cars_available` (`isAvailable`),
  ADD KEY `idx_cars_category` (`category`),
  ADD KEY `idx_cars_available_category` (`isAvailable`,`category`);

--
-- Indexes for table `condition_photos`
--
ALTER TABLE `condition_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `condition_photos_report_id_foreign` (`report_id`);

--
-- Indexes for table `condition_reports`
--
ALTER TABLE `condition_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `condition_reports_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `daily_analytics`
--
ALTER TABLE `daily_analytics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `daily_analytics_date_unique` (`date`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `kyc_audit_logs`
--
ALTER TABLE `kyc_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kyc_audit_logs_performed_by_foreign` (`performed_by`),
  ADD KEY `kyc_audit_logs_kyc_verification_id_created_at_index` (`kyc_verification_id`,`created_at`),
  ADD KEY `kyc_audit_logs_action_index` (`action`);

--
-- Indexes for table `kyc_document_versions`
--
ALTER TABLE `kyc_document_versions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kyc_document_versions_kyc_verification_id_version_index` (`kyc_verification_id`,`version`);

--
-- Indexes for table `kyc_verifications`
--
ALTER TABLE `kyc_verifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kyc_verifications_user_id_unique` (`user_id`),
  ADD KEY `idx_kyc_user` (`user_id`),
  ADD KEY `idx_kyc_status` (`status`),
  ADD KEY `kyc_verifications_verified_by_foreign` (`verified_by`),
  ADD KEY `kyc_verifications_rejected_by_foreign` (`rejected_by`),
  ADD KEY `kyc_verifications_status_index` (`status`),
  ADD KEY `kyc_verifications_submitted_at_index` (`submitted_at`),
  ADD KEY `kyc_verifications_verified_at_index` (`verified_at`),
  ADD KEY `kyc_verifications_user_id_status_index` (`user_id`,`status`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`),
  ADD KEY `notifications_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `page_visits`
--
ALTER TABLE `page_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_visits_created_at_index` (`created_at`),
  ADD KEY `page_visits_user_id_index` (`user_id`),
  ADD KEY `page_visits_page_url_index` (`page_url`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reviews_user` (`user_id`),
  ADD KEY `idx_reviews_approved` (`is_approved`),
  ADD KEY `idx_reviews_approved_date` (`is_approved`,`created_at`);

--
-- Indexes for table `sms_logs`
--
ALTER TABLE `sms_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sms_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscriptions_stripe_id_unique` (`stripe_id`),
  ADD KEY `subscriptions_user_id_stripe_status_index` (`user_id`,`stripe_status`);

--
-- Indexes for table `subscription_items`
--
ALTER TABLE `subscription_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_items_subscription_id_stripe_price_unique` (`subscription_id`,`stripe_price`),
  ADD UNIQUE KEY `subscription_items_stripe_id_unique` (`stripe_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_stripe_id_index` (`stripe_id`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_created` (`created_at`);

--
-- Indexes for table `user_actions`
--
ALTER TABLE `user_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_actions_user_id_index` (`user_id`),
  ADD KEY `user_actions_action_type_index` (`action_type`),
  ADD KEY `user_actions_created_at_index` (`created_at`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_sessions_session_id_unique` (`session_id`),
  ADD KEY `user_sessions_user_id_index` (`user_id`),
  ADD KEY `user_sessions_created_at_index` (`created_at`);

--
-- Indexes for table `webhook_deliveries`
--
ALTER TABLE `webhook_deliveries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `webhook_deliveries_webhook_endpoint_id_status_index` (`webhook_endpoint_id`,`status`),
  ADD KEY `webhook_deliveries_created_at_index` (`created_at`);

--
-- Indexes for table `webhook_endpoints`
--
ALTER TABLE `webhook_endpoints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `webhook_endpoints_is_active_index` (`is_active`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `analytics_events`
--
ALTER TABLE `analytics_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `condition_photos`
--
ALTER TABLE `condition_photos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `condition_reports`
--
ALTER TABLE `condition_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_analytics`
--
ALTER TABLE `daily_analytics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kyc_audit_logs`
--
ALTER TABLE `kyc_audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kyc_document_versions`
--
ALTER TABLE `kyc_document_versions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kyc_verifications`
--
ALTER TABLE `kyc_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `page_visits`
--
ALTER TABLE `page_visits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_logs`
--
ALTER TABLE `sms_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_items`
--
ALTER TABLE `subscription_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_actions`
--
ALTER TABLE `user_actions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `webhook_deliveries`
--
ALTER TABLE `webhook_deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `webhook_endpoints`
--
ALTER TABLE `webhook_endpoints`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `analytics_events`
--
ALTER TABLE `analytics_events`
  ADD CONSTRAINT `analytics_events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`),
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `condition_photos`
--
ALTER TABLE `condition_photos`
  ADD CONSTRAINT `condition_photos_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `condition_reports` (`id`);

--
-- Constraints for table `condition_reports`
--
ALTER TABLE `condition_reports`
  ADD CONSTRAINT `condition_reports_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `kyc_audit_logs`
--
ALTER TABLE `kyc_audit_logs`
  ADD CONSTRAINT `kyc_audit_logs_kyc_verification_id_foreign` FOREIGN KEY (`kyc_verification_id`) REFERENCES `kyc_verifications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kyc_audit_logs_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `kyc_document_versions`
--
ALTER TABLE `kyc_document_versions`
  ADD CONSTRAINT `kyc_document_versions_kyc_verification_id_foreign` FOREIGN KEY (`kyc_verification_id`) REFERENCES `kyc_verifications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kyc_verifications`
--
ALTER TABLE `kyc_verifications`
  ADD CONSTRAINT `kyc_verifications_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kyc_verifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kyc_verifications_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `page_visits`
--
ALTER TABLE `page_visits`
  ADD CONSTRAINT `page_visits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sms_logs`
--
ALTER TABLE `sms_logs`
  ADD CONSTRAINT `sms_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_actions`
--
ALTER TABLE `user_actions`
  ADD CONSTRAINT `user_actions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `user_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `webhook_deliveries`
--
ALTER TABLE `webhook_deliveries`
  ADD CONSTRAINT `webhook_deliveries_webhook_endpoint_id_foreign` FOREIGN KEY (`webhook_endpoint_id`) REFERENCES `webhook_endpoints` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
