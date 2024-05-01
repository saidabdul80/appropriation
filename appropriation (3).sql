-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2024 at 08:28 AM
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
-- Database: `appropriation`
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
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'updated', 'App\\Models\\Scheme', 'updated', 1, 'App\\Models\\User', 1, '{\"attributes\":{\"wallet_number\":240430091642,\"owner_id\":null,\"owner_type\":null,\"amount\":null},\"old\":{\"wallet_number\":240429035623,\"owner_id\":null,\"owner_type\":null,\"amount\":null}}', NULL, '2024-04-30 08:16:42', '2024-04-30 08:16:42');

-- --------------------------------------------------------

--
-- Table structure for table `appropriations`
--

CREATE TABLE `appropriations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `scheme_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` longtext NOT NULL,
  `appropriation_type_id` int(11) NOT NULL,
  `percentage_dividend` double(8,2) NOT NULL,
  `budget_location` enum('head','subhead') NOT NULL DEFAULT 'head',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appropriations`
--

INSERT INTO `appropriations` (`id`, `scheme_id`, `department_id`, `appropriation_type_id`, `percentage_dividend`, `budget_location`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '[1]', 1, 57.00, 'subhead', '2023-01-29 14:45:44', '2024-04-30 07:20:24', NULL),
(2, 1, '[2]', 2, 11.25, 'subhead', '2023-01-29 14:46:10', '2024-04-28 15:01:08', NULL),
(3, 1, '[1]', 3, 12.00, 'head', '2023-01-29 14:46:36', '2023-01-29 14:46:36', NULL),
(4, 1, '[1]', 4, 5.00, 'subhead', '2023-01-29 14:46:54', '2024-04-30 13:17:51', NULL),
(5, 1, '[4]', 5, 9.75, 'head', '2023-01-29 14:47:22', '2023-01-29 14:47:22', NULL),
(6, 1, '[2]', 6, 2.75, 'head', '2023-01-29 14:47:59', '2024-04-25 08:24:45', NULL),
(7, 5, '[2]', 1, 57.00, 'head', '2023-01-29 14:49:08', '2023-01-29 14:49:08', NULL),
(8, 5, '[2]', 2, 11.25, 'head', '2023-01-29 14:49:19', '2023-01-29 14:49:19', NULL),
(9, 5, '[1]', 3, 12.00, 'head', '2023-01-29 14:49:34', '2023-01-29 14:49:34', NULL),
(10, 5, '[1]', 4, 5.00, 'head', '2023-01-29 14:49:43', '2023-01-29 14:49:43', NULL),
(11, 5, '[4]', 5, 9.75, 'head', '2023-01-29 14:50:10', '2023-01-29 14:50:10', NULL),
(12, 5, '[2,5]', 6, 5.00, 'head', '2023-01-29 14:50:45', '2023-01-29 14:50:45', NULL),
(13, 2, '[2]', 1, 60.00, 'head', '2023-01-29 14:52:06', '2023-01-29 14:52:06', NULL),
(14, 2, '[2]', 2, 17.00, 'head', '2023-01-29 14:52:17', '2023-01-29 14:52:17', NULL),
(15, 2, '[2]', 7, 9.00, 'head', '2023-01-29 14:52:33', '2023-01-29 14:52:33', NULL),
(16, 2, '[4]', 8, 3.00, 'head', '2023-01-29 14:52:59', '2023-01-29 14:52:59', NULL),
(17, 2, '[2]', 9, 3.00, 'head', '2023-01-29 14:53:25', '2024-04-24 14:52:47', NULL),
(18, 2, '[1]', 10, 8.00, 'head', '2023-01-29 14:53:50', '2024-04-24 14:52:54', NULL),
(19, 3, '[2]', 1, 60.00, 'head', '2023-01-29 14:54:11', '2023-01-29 14:54:11', NULL),
(20, 3, '[2]', 2, 17.00, 'head', '2023-01-29 14:54:57', '2023-01-29 14:54:57', NULL),
(21, 3, '[4]', 8, 6.00, 'head', '2023-01-29 14:58:34', '2023-01-29 14:58:34', NULL),
(22, 3, '[2]', 11, 4.00, 'head', '2023-01-29 14:59:22', '2023-01-29 14:59:22', NULL),
(23, 3, '[1]', 12, 6.00, 'head', '2023-01-29 15:00:27', '2023-01-29 15:00:27', NULL),
(24, 3, '[2]', 13, 1.50, 'head', '2023-01-29 15:00:51', '2023-01-29 15:00:51', NULL),
(25, 3, '[5]', 14, 1.50, 'head', '2023-01-29 15:01:04', '2023-01-29 15:01:04', NULL),
(26, 3, '[3]', 15, 1.00, 'head', '2023-01-29 15:01:35', '2023-01-29 15:01:35', NULL),
(27, 3, '[3]', 16, 1.00, 'head', '2023-01-29 15:02:22', '2023-01-29 15:02:22', NULL),
(28, 3, '[2,3]', 17, 1.00, 'head', '2023-01-29 15:03:17', '2023-01-29 15:03:17', NULL),
(29, 3, '[1]', 18, 1.00, 'head', '2023-01-29 15:05:03', '2023-01-29 15:05:03', NULL),
(30, 4, '[1]', 10, 7.00, 'head', '2023-02-02 08:50:37', '2023-02-02 08:50:37', NULL),
(31, 4, '[2]', 7, 9.00, 'head', '2023-02-02 08:50:57', '2023-02-02 08:50:57', NULL),
(32, 4, '[4]', 19, 3.00, 'head', '2023-02-02 08:51:28', '2023-02-02 08:51:28', NULL),
(33, 4, '[2]', 20, 17.00, 'head', '2023-02-02 08:51:46', '2023-02-02 08:51:46', NULL),
(34, 4, '[2]', 1, 60.00, 'head', '2023-02-02 08:52:06', '2023-02-02 08:52:06', NULL),
(35, 4, '[1]', 21, 4.00, 'head', '2023-02-02 08:52:49', '2023-02-02 08:52:49', NULL),
(36, 1, '[5]', 22, 2.25, 'head', '2024-04-25 08:23:24', '2024-04-27 09:31:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `appropriations_history`
--

CREATE TABLE `appropriations_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `owner_id` bigint(20) UNSIGNED NOT NULL,
  `owner_type` enum('scheme','department') NOT NULL,
  `amount` double(20,2) NOT NULL,
  `appropriation` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`appropriation`)),
  `source_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `fund_category` varchar(255) NOT NULL,
  `fund_month_year` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appropriations_history`
--

INSERT INTO `appropriations_history` (`id`, `owner_id`, `owner_type`, `amount`, `appropriation`, `source_id`, `description`, `fund_category`, `fund_month_year`, `created_at`, `updated_at`) VALUES
(1, 1, 'scheme', 2890000.00, '[{\"id\":1,\"name\":\"Capitation\",\"department\":\"DA&FA\",\"amount\":1647300,\"percentage_dividend\":57},{\"id\":2,\"name\":\"FFS Claims\",\"department\":\"DP&BM\",\"amount\":325125,\"percentage_dividend\":11.25},{\"id\":3,\"name\":\"Reserve\",\"department\":\"DA&FA\",\"amount\":346800,\"percentage_dividend\":12},{\"id\":4,\"name\":\"Admin\",\"department\":\"DA&FA\",\"amount\":144500,\"percentage_dividend\":5},{\"id\":5,\"name\":\"ICT Resources\",\"department\":\"DHICT\",\"amount\":281775,\"percentage_dividend\":9.75},{\"id\":6,\"name\":\"QA\",\"department\":\"DP&BM\",\"amount\":79475,\"percentage_dividend\":2.75},{\"id\":36,\"name\":\"M&E\",\"department\":\"DHPRS\",\"amount\":65025,\"percentage_dividend\":2.25}]', 1, NULL, '2021', '2021-03', '2024-04-30 08:33:48', '2024-04-30 08:33:48'),
(2, 1, 'scheme', 20000000.00, '[{\"id\":1,\"name\":\"Capitation\",\"department\":\"DA&FA\",\"amount\":11400000,\"percentage_dividend\":57},{\"id\":2,\"name\":\"FFS Claims\",\"department\":\"DP&BM\",\"amount\":2250000,\"percentage_dividend\":11.25},{\"id\":3,\"name\":\"Reserve\",\"department\":\"DA&FA\",\"amount\":2400000,\"percentage_dividend\":12},{\"id\":4,\"name\":\"Admin\",\"department\":\"DA&FA\",\"amount\":1000000,\"percentage_dividend\":5},{\"id\":5,\"name\":\"ICT Resources\",\"department\":\"DHICT\",\"amount\":1950000,\"percentage_dividend\":9.75},{\"id\":6,\"name\":\"QA\",\"department\":\"DP&BM\",\"amount\":550000,\"percentage_dividend\":2.75},{\"id\":36,\"name\":\"M&E\",\"department\":\"DHPRS\",\"amount\":450000,\"percentage_dividend\":2.25}]', 1, 'kloe89 we9hiu', '2022', '2022-01', '2024-04-30 13:14:59', '2024-04-30 13:14:59');

-- --------------------------------------------------------

--
-- Table structure for table `appropriation_types`
--

CREATE TABLE `appropriation_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appropriation_types`
--

INSERT INTO `appropriation_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Capitation', NULL, NULL),
(2, 'FFS Claims', NULL, NULL),
(3, 'Reserve', NULL, NULL),
(4, 'Admin', NULL, NULL),
(5, 'ICT Resources', NULL, NULL),
(6, 'QA', NULL, NULL),
(7, 'TPA Admin', NULL, NULL),
(8, 'HICT Admin', NULL, NULL),
(9, 'Medical Savings', NULL, NULL),
(10, 'NiCare Admin', NULL, NULL),
(11, 'Medical Saving', NULL, NULL),
(12, 'NiCare Admin (General)', NULL, NULL),
(13, 'Quality Assurance', NULL, NULL),
(14, 'M&E', NULL, NULL),
(15, 'Mobilization & Sensitization', NULL, NULL),
(16, 'NiCare DOs (MDAs/LGAs)', NULL, NULL),
(17, 'HMU&CCMS Agent Mgt.', NULL, NULL),
(18, 'Acct. & FSC', NULL, NULL),
(19, 'ICT Admin', NULL, NULL),
(20, 'FFS', NULL, NULL),
(21, 'TiSHIP Mgt. Committee', NULL, NULL),
(22, 'M&E', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `configurations`
--

CREATE TABLE `configurations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  `seeds` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `short_name`, `created_at`, `updated_at`) VALUES
(1, 'DEPARTMENT OF ADMINISTRATION', 'DA&FA', NULL, NULL),
(2, 'DEPARTMENT OF POVIDER & BENEFIT MANAGEMENT', 'DP&BM', NULL, NULL),
(3, 'DEPARTMENT OF PROGRAMME & BUSINESS DEVELOPMENT', 'DP&BD', NULL, NULL),
(4, 'DEPARTMENT OF HEALTH INFORMATION COMMUNICATION TECHNOLOGY', 'DHICT', NULL, NULL),
(5, 'DEPARTMENT OF HEALTH PLANNING RESEARCH & STATISTIC', 'DHPRS', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `scheme_id` int(11) NOT NULL,
  `amount` double(8,2) NOT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `paid_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenditure_categories`
--

CREATE TABLE `expenditure_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
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
-- Table structure for table `funds`
--

CREATE TABLE `funds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fund_category` varchar(255) NOT NULL,
  `scheme_id` bigint(20) UNSIGNED NOT NULL,
  `amount` varchar(255) NOT NULL,
  `status` enum('used','unused') NOT NULL DEFAULT 'unused',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fund_categories`
--

CREATE TABLE `fund_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `scheme_id` int(11) NOT NULL,
  `fund_category` varchar(255) NOT NULL,
  `fund_month_year` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fund_categories`
--

INSERT INTO `fund_categories` (`id`, `scheme_id`, `fund_category`, `fund_month_year`, `created_at`, `updated_at`) VALUES
(1, 1, '2021', '2021-01', '2024-04-30 08:16:42', '2024-04-30 08:16:42'),
(2, 1, '2022', '2022-01', '2024-04-30 13:14:12', '2024-04-30 13:14:12');

-- --------------------------------------------------------

--
-- Table structure for table `main_wallets`
--

CREATE TABLE `main_wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `owner_type` enum('appropriation','scheme') NOT NULL,
  `owner_id` bigint(20) UNSIGNED NOT NULL,
  `balance` double(15,2) NOT NULL DEFAULT 0.00,
  `total_collection` double(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `main_wallets`
--

INSERT INTO `main_wallets` (`id`, `owner_type`, `owner_id`, `balance`, `total_collection`, `created_at`, `updated_at`) VALUES
(1, 'appropriation', 1, 12757300.00, 13047300.00, '2024-04-30 08:33:47', '2024-04-30 13:14:59'),
(2, 'appropriation', 2, 2425125.00, 2575125.00, '2024-04-30 08:33:48', '2024-04-30 13:14:59'),
(3, 'appropriation', 3, 2746800.00, 2746800.00, '2024-04-30 08:33:48', '2024-04-30 13:14:59'),
(4, 'appropriation', 4, 1074500.00, 1144500.00, '2024-04-30 08:33:48', '2024-04-30 13:23:28'),
(5, 'appropriation', 5, 2231775.00, 2231775.00, '2024-04-30 08:33:48', '2024-04-30 13:14:59'),
(6, 'appropriation', 6, 629475.00, 629475.00, '2024-04-30 08:33:48', '2024-04-30 13:14:59'),
(7, 'appropriation', 36, 515025.00, 515025.00, '2024-04-30 08:33:48', '2024-04-30 13:14:59');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(11, 'App\\Models\\User', 1);

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `description`, `created_at`, `updated_at`) VALUES
(11, 'fund_scheme', 'web', 'Fund Programme', NULL, NULL),
(12, 'projection', 'web', 'Projection', NULL, NULL),
(13, 'appropriate', 'web', 'Appropriate', NULL, NULL),
(14, 'new_appropriationm', 'web', 'New Appropriationm', NULL, NULL),
(15, 'appr_income', 'web', 'Appropriation Income', NULL, NULL),
(16, 'appr_current_balance', 'web', 'Appropriation current balance', NULL, NULL),
(17, 'income', 'web', 'Income', NULL, NULL),
(18, 'balance', 'web', 'Balance', NULL, NULL),
(19, 'expenditure', 'web', 'Expenditure', NULL, NULL),
(20, 'general._app._history', 'web', 'General App History', NULL, NULL),
(21, 'debit_fund', 'web', 'Debit Fund', NULL, NULL),
(22, 'report', 'web', 'Report', NULL, NULL),
(23, 'appro_history', 'web', 'Appropriation History', NULL, NULL);

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
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `department_ids` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `description`, `department_ids`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', NULL, NULL, NULL, NULL),
(2, 'Director', 'web', NULL, NULL, NULL, NULL),
(3, 'Chirman', 'web', NULL, NULL, NULL, NULL),
(4, 'Member', 'web', NULL, NULL, NULL, NULL),
(5, 'ES', 'web', NULL, NULL, NULL, NULL),
(6, 'DHICT', 'web', NULL, NULL, NULL, NULL),
(7, 'DA&FA', 'web', NULL, NULL, NULL, NULL),
(8, 'DP&BM', 'web', NULL, NULL, NULL, NULL),
(9, 'DP&BD', 'web', NULL, NULL, NULL, NULL),
(10, 'DHPRS', 'web', NULL, NULL, NULL, NULL),
(11, 'Chief Acct', 'web', NULL, NULL, NULL, NULL),
(12, 'CIA', 'web', NULL, NULL, NULL, NULL),
(13, 'Cashier', 'web', NULL, NULL, NULL, NULL),
(14, 'SA/DDHICT', 'web', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(11, 11),
(11, 12),
(11, 13),
(12, 6),
(12, 7),
(12, 8),
(12, 9),
(12, 10),
(12, 11),
(12, 13),
(12, 14),
(13, 11),
(13, 13),
(14, 6),
(14, 7),
(14, 8),
(14, 9),
(14, 10),
(14, 11),
(14, 12),
(14, 13),
(14, 14),
(15, 5),
(15, 6),
(15, 7),
(15, 8),
(15, 9),
(15, 10),
(15, 11),
(15, 12),
(15, 13),
(16, 5),
(16, 6),
(16, 7),
(16, 8),
(16, 9),
(16, 10),
(16, 11),
(16, 12),
(16, 13),
(17, 5),
(17, 6),
(17, 7),
(17, 8),
(17, 9),
(17, 10),
(17, 11),
(17, 12),
(17, 13),
(18, 5),
(18, 6),
(18, 7),
(18, 8),
(18, 9),
(18, 10),
(18, 11),
(18, 12),
(18, 13),
(19, 5),
(19, 6),
(19, 7),
(19, 8),
(19, 9),
(19, 10),
(19, 11),
(19, 12),
(19, 13),
(20, 5),
(20, 6),
(20, 7),
(20, 8),
(20, 9),
(20, 10),
(20, 11),
(20, 12),
(20, 13),
(22, 5),
(22, 6),
(22, 7),
(22, 8),
(22, 9),
(22, 10),
(22, 11),
(22, 12),
(22, 13),
(23, 11),
(23, 13);

-- --------------------------------------------------------

--
-- Table structure for table `schemes`
--

CREATE TABLE `schemes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `premium_amount` double(8,2) NOT NULL,
  `fund_category` enum('month','year') NOT NULL,
  `wallet_number` bigint(20) UNSIGNED DEFAULT NULL,
  `fund_type` enum('api','entry') NOT NULL DEFAULT 'entry',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schemes`
--

INSERT INTO `schemes` (`id`, `name`, `premium_amount`, `fund_category`, `wallet_number`, `fund_type`, `created_at`, `updated_at`) VALUES
(1, 'BHCPF', 12000.00, 'year', 240430091642, 'entry', '2023-01-29 14:29:39', '2024-04-30 08:16:42'),
(2, 'NiCare (Informal Sector)', 7200.00, 'month', 230129033002, 'api', '2023-01-29 14:30:02', '2023-03-05 15:54:43'),
(3, 'NiCare (Formal Sector)', 3600.00, 'year', 230129033017, 'entry', '2023-01-29 14:30:17', '2023-01-29 14:30:17'),
(4, 'NiCare TiSHIP', 3000.00, 'year', 230129033023, 'entry', '2023-01-29 14:30:23', '2023-01-29 14:30:23'),
(5, 'State Counterpart', 2000.00, 'year', 230129034848, 'entry', '2023-01-29 14:48:48', '2023-01-29 14:48:48'),
(6, 'Sample', 2000.00, 'month', 230130042524, 'entry', '2023-01-30 03:25:24', '2023-01-30 03:25:24');

-- --------------------------------------------------------

--
-- Table structure for table `sources`
--

CREATE TABLE `sources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `scheme_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sources`
--

INSERT INTO `sources` (`id`, `name`, `short_name`, `scheme_id`, `created_at`, `updated_at`) VALUES
(1, 'BHCPF Funding (FG)', '', 1, NULL, NULL),
(2, 'State Counterpart Funding', '', 5, NULL, NULL),
(3, 'Premium Sales', '', 2, NULL, NULL),
(4, 'Formal Sector Premium', '', 3, NULL, NULL),
(5, 'Other Source', '', 1, NULL, NULL),
(6, 'Other Source', '', 2, NULL, NULL),
(7, 'Other Source', '', 3, NULL, NULL),
(8, 'Other Source', '', 4, NULL, NULL),
(9, 'Other Source', '', 4, NULL, NULL),
(10, 'Ibrahim Badamasi Babangida University Lapai', 'IBBUL', 4, NULL, NULL),
(11, 'Federal Poly Bida', '', 4, NULL, NULL),
(12, 'Federal University of Technology Minna', 'FUT Minna', 4, NULL, NULL),
(13, 'Other Source', '', 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sub_heads`
--

CREATE TABLE `sub_heads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_heads`
--

INSERT INTO `sub_heads` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Transportation', '2024-04-30 08:36:54', '2024-04-30 08:36:54', NULL),
(2, 'Training', '2024-04-30 08:37:02', '2024-04-30 08:37:02', NULL),
(3, 'Casual', '2024-04-30 13:15:52', '2024-04-30 13:15:52', NULL),
(4, 'Office', '2024-04-30 13:16:04', '2024-04-30 13:16:04', NULL),
(5, 'Vehicle Main.', '2024-04-30 13:16:11', '2024-04-30 13:16:11', NULL),
(6, 'Fuelling', '2024-04-30 13:16:18', '2024-04-30 13:16:18', NULL),
(7, 'Local Meeting', '2024-04-30 13:16:26', '2024-04-30 13:16:26', NULL),
(8, 'Recharge of Tvs', '2024-04-30 13:16:39', '2024-04-30 13:16:39', NULL),
(9, 'Stationeries', '2024-04-30 13:16:53', '2024-04-30 13:16:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sub_head_budgets`
--

CREATE TABLE `sub_head_budgets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subhead_id` int(11) NOT NULL,
  `appropriation_id` int(11) NOT NULL COMMENT 'represent the head',
  `amount` double(8,2) NOT NULL DEFAULT 0.00,
  `fund_category` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_head_budgets`
--

INSERT INTO `sub_head_budgets` (`id`, `subhead_id`, `appropriation_id`, `amount`, `fund_category`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 300000.00, '2021', '2024-04-30 09:23:18', '2024-04-30 09:23:18', NULL),
(2, 2, 1, 500000.00, '2021', '2024-04-30 10:21:07', '2024-04-30 10:21:07', NULL),
(3, 1, 2, 250000.00, '2021', '2024-04-30 13:02:21', '2024-04-30 13:02:21', NULL),
(4, 2, 1, 0.00, '2022', '2024-04-30 13:14:59', '2024-04-30 13:14:59', NULL),
(5, 1, 2, 0.00, '2022', '2024-04-30 13:14:59', '2024-04-30 13:14:59', NULL),
(6, 1, 4, 200000.00, '2022', '2024-04-30 13:17:51', '2024-04-30 13:17:51', NULL),
(7, 5, 4, 250000.00, '2022', '2024-04-30 13:18:16', '2024-04-30 13:18:16', NULL),
(8, 6, 4, 100000.00, '2022', '2024-04-30 13:18:31', '2024-04-30 13:18:31', NULL),
(9, 7, 4, 100000.00, '2022', '2024-04-30 13:18:44', '2024-04-30 13:18:44', NULL),
(10, 4, 4, 100000.00, '2022', '2024-04-30 13:19:08', '2024-04-30 13:19:08', NULL),
(11, 3, 4, 200000.00, '2022', '2024-04-30 13:19:22', '2024-04-30 13:19:22', NULL),
(12, 8, 4, 50000.00, '2022', '2024-04-30 13:19:35', '2024-04-30 13:19:35', NULL),
(13, 9, 4, 44500.00, '2022', '2024-04-30 13:20:33', '2024-04-30 13:20:33', NULL),
(14, 2, 4, 100000.00, '2022', '2024-04-30 13:20:57', '2024-04-30 13:20:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `owner_id` bigint(20) UNSIGNED NOT NULL,
  `owner_type` enum('scheme','appropriation') NOT NULL,
  `action` enum('credit','debit','undo credit') NOT NULL,
  `amount` double(15,2) NOT NULL,
  `subhead_id` int(11) DEFAULT NULL,
  `appropriation_history_id` bigint(20) UNSIGNED DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `account_type` enum('balance','transits','reserve') NOT NULL DEFAULT 'balance',
  `source_id` bigint(20) UNSIGNED DEFAULT NULL,
  `state` enum('used','unused','none') DEFAULT 'none',
  `description` varchar(5000) DEFAULT NULL,
  `fund_category` varchar(255) DEFAULT NULL,
  `performed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `owner_id`, `owner_type`, `action`, `amount`, `subhead_id`, `appropriation_history_id`, `data`, `account_type`, `source_id`, `state`, `description`, `fund_category`, `performed_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'scheme', 'credit', 2000000.00, NULL, NULL, NULL, 'balance', 1, 'used', NULL, '2021-01', 1, '2024-04-30 08:16:42', '2024-04-30 13:14:59'),
(2, 1, 'scheme', 'credit', 390000.00, NULL, NULL, NULL, 'balance', 1, 'used', NULL, '2021-02', 1, '2024-04-30 08:32:50', '2024-04-30 13:14:59'),
(3, 1, 'scheme', 'credit', 500000.00, NULL, NULL, NULL, 'balance', 1, 'used', NULL, '2021-03', 1, '2024-04-30 08:33:27', '2024-04-30 13:14:59'),
(4, 1, 'scheme', 'debit', 2890000.00, NULL, 1, NULL, 'balance', 1, 'used', NULL, NULL, 1, '2024-04-30 08:33:48', '2024-04-30 13:14:59'),
(5, 1, 'appropriation', 'debit', 250000.00, 1, NULL, '{\"Subject\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"Computers\",\"type\":\"text\",\"for\":null},\"Approval_Date\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"2024-04-29\",\"type\":\"date\",\"for\":null},\"Section_of_Work_Plan\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"File_Name\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"File_Number\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"Page_Number\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"Beneficiary\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"Beneficiary Name\",\"type\":\"text\",\"for\":null},\"Account_Number\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":3948394860,\"type\":\"number\",\"for\":null},\"Amount\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":250000,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Payment_Date\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"2024-04-30\",\"type\":\"date\",\"for\":null},\"Trx_Charges\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"VAT_%\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"VAT_\\u20a6\":0,\"Withholding_Tax_%\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Withholding_Tax_\\u20a6\":0,\"Stamp_Duty_%\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Stamp_Duty_\\u20a6\":0,\"Gross_Amount\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Total_Taxes\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0}}', 'balance', NULL, 'none', '', '2021', 1, '2024-04-30 10:31:07', '2024-04-30 10:31:07'),
(6, 1, 'appropriation', 'debit', 40000.00, 1, NULL, '{\"Subject\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"Keyboard\",\"type\":\"text\",\"for\":null},\"Approval_Date\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"2024-04-29\",\"type\":\"date\",\"for\":null},\"Section_of_Work_Plan\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"File_Name\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"File_Number\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"Page_Number\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"Beneficiary\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"Beneficiary Name2\",\"type\":\"text\",\"for\":null},\"Account_Number\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":3248394860,\"type\":\"number\",\"for\":null},\"Amount\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":40000,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Payment_Date\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"2024-04-30\",\"type\":\"date\",\"for\":null},\"Trx_Charges\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"VAT_%\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"VAT_\\u20a6\":0,\"Withholding_Tax_%\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Withholding_Tax_\\u20a6\":0,\"Stamp_Duty_%\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Stamp_Duty_\\u20a6\":0,\"Gross_Amount\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Total_Taxes\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0}}', 'balance', NULL, 'none', '', '2021', 1, '2024-04-30 10:33:59', '2024-04-30 10:33:59'),
(7, 2, 'appropriation', 'debit', 150000.00, 3, NULL, '{\"Subject\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"Things\",\"type\":\"text\",\"for\":null},\"Approval_Date\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"2024-04-28\",\"type\":\"date\",\"for\":null},\"Section_of_Work_Plan\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"File_Name\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"File_Number\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"Page_Number\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"Beneficiary\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"ekuiwo\",\"type\":\"text\",\"for\":null},\"Account_Number\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":47823532,\"type\":\"number\",\"for\":null},\"Amount\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":150000,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Payment_Date\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"2024-04-30\",\"type\":\"date\",\"for\":null},\"Trx_Charges\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"VAT_%\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"VAT_\\u20a6\":0,\"Withholding_Tax_%\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Withholding_Tax_\\u20a6\":0,\"Stamp_Duty_%\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Stamp_Duty_\\u20a6\":0,\"Gross_Amount\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Total_Taxes\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0}}', 'balance', NULL, 'none', '', '2021', 1, '2024-04-30 13:03:36', '2024-04-30 13:03:36'),
(8, 1, 'scheme', 'credit', 20000000.00, NULL, NULL, NULL, 'balance', 1, 'used', 'kloe89 we9hiu', '2022-01', 1, '2024-04-30 13:14:12', '2024-04-30 13:14:59'),
(9, 1, 'scheme', 'debit', 20000000.00, NULL, 2, NULL, 'balance', 1, 'used', 'kloe89 we9hiu', NULL, 1, '2024-04-30 13:14:59', '2024-04-30 13:14:59'),
(10, 4, 'appropriation', 'debit', 50000.00, 6, NULL, '{\"Subject\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"Fueling to Abj\",\"type\":\"text\",\"for\":null},\"Approval_Date\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"2022-01-03\",\"type\":\"date\",\"for\":null},\"Section_of_Work_Plan\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"File_Name\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"File_Number\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"Page_Number\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"Beneficiary\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"lkwioejw oi\",\"type\":\"text\",\"for\":null},\"Account_Number\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":389238430,\"type\":\"number\",\"for\":null},\"Amount\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":50000,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Payment_Date\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"2022-01-21\",\"type\":\"date\",\"for\":null},\"Trx_Charges\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"VAT_%\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"VAT_\\u20a6\":0,\"Withholding_Tax_%\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Withholding_Tax_\\u20a6\":0,\"Stamp_Duty_%\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Stamp_Duty_\\u20a6\":0,\"Gross_Amount\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Total_Taxes\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0}}', 'balance', NULL, 'none', '', '2022', 1, '2024-04-30 13:22:42', '2024-04-30 13:22:42'),
(11, 4, 'appropriation', 'debit', 20000.00, 7, NULL, '{\"Subject\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"Repairs\",\"type\":\"text\",\"for\":null},\"Approval_Date\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"2022-01-03\",\"type\":\"date\",\"for\":null},\"Section_of_Work_Plan\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"File_Name\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"File_Number\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"Page_Number\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":null,\"type\":\"text\",\"for\":null},\"Beneficiary\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"lkwioejw oi\",\"type\":\"text\",\"for\":null},\"Account_Number\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":389238430,\"type\":\"number\",\"for\":null},\"Amount\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":20000,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Payment_Date\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":\"2022-01-21\",\"type\":\"date\",\"for\":null},\"Trx_Charges\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"VAT_%\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"VAT_\\u20a6\":0,\"Withholding_Tax_%\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Withholding_Tax_\\u20a6\":0,\"Stamp_Duty_%\":{\"required\":0,\"show\":1,\"activate\":0,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Stamp_Duty_\\u20a6\":0,\"Gross_Amount\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0},\"Total_Taxes\":{\"required\":1,\"show\":1,\"activate\":1,\"value\":0,\"type\":\"number\",\"for\":\"tax\",\"amount\":0}}', 'balance', NULL, 'none', '', '2022', 1, '2024-04-30 13:23:28', '2024-04-30 13:23:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wallet_number` bigint(20) UNSIGNED DEFAULT NULL,
  `owner_type` enum('App\\Models\\Scheme','App\\Models\\Appropriation') NOT NULL,
  `owner_id` bigint(20) UNSIGNED NOT NULL,
  `balance` double(15,2) NOT NULL DEFAULT 0.00,
  `fund_month_year` varchar(255) DEFAULT NULL,
  `fund_category` varchar(255) NOT NULL DEFAULT '',
  `safe_balance` double(15,2) NOT NULL DEFAULT 0.00,
  `transits` double(15,2) NOT NULL DEFAULT 0.00,
  `total_collection` double(15,2) NOT NULL DEFAULT 0.00,
  `source_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `wallet_number`, `owner_type`, `owner_id`, `balance`, `fund_month_year`, `fund_category`, `safe_balance`, `transits`, `total_collection`, `source_id`, `description`, `token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 240430091642, 'App\\Models\\Scheme', 1, 0.00, '2022-01', '2022', 0.00, 0.00, 22890000.00, 1, 'kloe89 we9hiu', 'appscheme1', '2024-04-30 08:16:42', '2024-04-30 13:14:59', NULL),
(2, NULL, 'App\\Models\\Appropriation', 1, 1357300.00, NULL, '2021', 0.00, 0.00, 1647300.00, NULL, NULL, 'app20211', '2024-04-30 08:33:47', '2024-04-30 10:33:59', NULL),
(3, NULL, 'App\\Models\\Appropriation', 2, 175125.00, NULL, '2021', 0.00, 0.00, 325125.00, NULL, NULL, 'app20212', '2024-04-30 08:33:48', '2024-04-30 13:03:36', NULL),
(4, NULL, 'App\\Models\\Appropriation', 3, 346800.00, NULL, '2021', 0.00, 0.00, 346800.00, NULL, NULL, 'app20213', '2024-04-30 08:33:48', '2024-04-30 08:33:48', NULL),
(5, NULL, 'App\\Models\\Appropriation', 4, 144500.00, NULL, '2021', 0.00, 0.00, 144500.00, NULL, NULL, 'app20214', '2024-04-30 08:33:48', '2024-04-30 08:33:48', NULL),
(6, NULL, 'App\\Models\\Appropriation', 5, 281775.00, NULL, '2021', 0.00, 0.00, 281775.00, NULL, NULL, 'app20215', '2024-04-30 08:33:48', '2024-04-30 08:33:48', NULL),
(7, NULL, 'App\\Models\\Appropriation', 6, 79475.00, NULL, '2021', 0.00, 0.00, 79475.00, NULL, NULL, 'app20216', '2024-04-30 08:33:48', '2024-04-30 08:33:48', NULL),
(8, NULL, 'App\\Models\\Appropriation', 36, 65025.00, NULL, '2021', 0.00, 0.00, 65025.00, NULL, NULL, 'app202136', '2024-04-30 08:33:48', '2024-04-30 08:33:48', NULL),
(9, NULL, 'App\\Models\\Appropriation', 1, 11400000.00, NULL, '2022', 0.00, 0.00, 11400000.00, NULL, NULL, 'app20221', '2024-04-30 13:14:59', '2024-04-30 13:14:59', NULL),
(10, NULL, 'App\\Models\\Appropriation', 2, 2250000.00, NULL, '2022', 0.00, 0.00, 2250000.00, NULL, NULL, 'app20222', '2024-04-30 13:14:59', '2024-04-30 13:14:59', NULL),
(11, NULL, 'App\\Models\\Appropriation', 3, 2400000.00, NULL, '2022', 0.00, 0.00, 2400000.00, NULL, NULL, 'app20223', '2024-04-30 13:14:59', '2024-04-30 13:14:59', NULL),
(12, NULL, 'App\\Models\\Appropriation', 4, 930000.00, NULL, '2022', 0.00, 0.00, 1000000.00, NULL, NULL, 'app20224', '2024-04-30 13:14:59', '2024-04-30 13:23:28', NULL),
(13, NULL, 'App\\Models\\Appropriation', 5, 1950000.00, NULL, '2022', 0.00, 0.00, 1950000.00, NULL, NULL, 'app20225', '2024-04-30 13:14:59', '2024-04-30 13:14:59', NULL),
(14, NULL, 'App\\Models\\Appropriation', 6, 550000.00, NULL, '2022', 0.00, 0.00, 550000.00, NULL, NULL, 'app20226', '2024-04-30 13:14:59', '2024-04-30 13:14:59', NULL),
(15, NULL, 'App\\Models\\Appropriation', 36, 450000.00, NULL, '2022', 0.00, 0.00, 450000.00, NULL, NULL, 'app202236', '2024-04-30 13:14:59', '2024-04-30 13:14:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shareholder_id` int(11) NOT NULL,
  `amount` double(8,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
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
-- Indexes for table `appropriations`
--
ALTER TABLE `appropriations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appropriations_history`
--
ALTER TABLE `appropriations_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appropriation_types`
--
ALTER TABLE `appropriation_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `configurations`
--
ALTER TABLE `configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_short_name_unique` (`short_name`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenditure_categories`
--
ALTER TABLE `expenditure_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `funds`
--
ALTER TABLE `funds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fund_categories`
--
ALTER TABLE `fund_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `main_wallets`
--
ALTER TABLE `main_wallets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `schemes`
--
ALTER TABLE `schemes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schemes_wallet_number_index` (`wallet_number`);

--
-- Indexes for table `sources`
--
ALTER TABLE `sources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_heads`
--
ALTER TABLE `sub_heads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_head_budgets`
--
ALTER TABLE `sub_head_budgets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wallets_token_unique` (`token`),
  ADD UNIQUE KEY `wallets_wallet_number_unique` (`wallet_number`),
  ADD KEY `owner_type` (`owner_type`),
  ADD KEY `fund_category` (`fund_category`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appropriations`
--
ALTER TABLE `appropriations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `appropriations_history`
--
ALTER TABLE `appropriations_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `appropriation_types`
--
ALTER TABLE `appropriation_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `configurations`
--
ALTER TABLE `configurations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenditure_categories`
--
ALTER TABLE `expenditure_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `funds`
--
ALTER TABLE `funds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fund_categories`
--
ALTER TABLE `fund_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `main_wallets`
--
ALTER TABLE `main_wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `schemes`
--
ALTER TABLE `schemes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sources`
--
ALTER TABLE `sources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `sub_heads`
--
ALTER TABLE `sub_heads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sub_head_budgets`
--
ALTER TABLE `sub_head_budgets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
