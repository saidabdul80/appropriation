-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2024 at 08:46 AM
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
-- Database: `nicare_neets`
--

-- --------------------------------------------------------

--
-- Table structure for table `activated_users`
--

CREATE TABLE `activated_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `activation_code` varchar(8) NOT NULL,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `lga` bigint(20) UNSIGNED NOT NULL,
  `ward` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activated_users`
--

INSERT INTO `activated_users` (`id`, `user_id`, `activation_code`, `facility_id`, `lga`, `ward`, `created_at`, `updated_at`) VALUES
(1, 1, '123456', 125, 13, 135, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `enrolee_visits`
--

CREATE TABLE `enrolee_visits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activated_user_id` bigint(20) UNSIGNED NOT NULL,
  `nicare_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sex` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lga` bigint(20) UNSIGNED NOT NULL,
  `ward` bigint(20) UNSIGNED NOT NULL,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `reason_for_visit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `service_accessed` int(11) DEFAULT NULL,
  `date_of_visit` date NOT NULL,
  `reporting_month` varchar(255) NOT NULL,
  `referred` enum('yes','no') DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enrolee_visits`
--

INSERT INTO `enrolee_visits` (`id`, `activated_user_id`, `nicare_id`, `sex`, `phone`, `lga`, `ward`, `facility_id`, `reason_for_visit`, `service_accessed`, `date_of_visit`, `reporting_month`, `referred`, `created_at`, `updated_at`) VALUES
(1, 46087, 'NGSCHA013432', 'Female', '08143798706', 25, 272, 117, 'Delivery', 129, '2023-01-18', 'December, 2022', 'no', '2023-12-14 19:40:15', NULL),
(2, 46087, 'NGSCHA013435', 'Male', '08100215691', 25, 272, 117, 'Malaria Fever', 73, '2022-01-29', 'December, 2021', 'no', '2023-12-14 19:40:15', NULL),
(3, 46087, 'NGSCHA013432', 'Female', '08143798706', 25, 272, 117, 'Delivery', 129, '2022-01-18', 'December, 2021', 'no', '2023-12-14 19:40:15', NULL),
(4, 46087, 'NGSCHA013436', 'Female', '09136011838', 25, 272, 117, 'Malaria Fever', 73, '2022-01-29', 'December, 2021', 'no', '2023-12-14 19:40:15', NULL),
(5, 46087, 'NGSCHA013443', 'Female', '09036353798', 25, 272, 117, 'ANC', 124, '2022-01-18', 'December, 2021', 'no', '2023-12-14 19:40:15', NULL),
(6, 46087, 'NGSCHA013445', 'Female', '08161228899', 25, 272, 117, 'ANC', 124, '2022-01-18', 'December, 2022', 'no', '2023-12-14 19:40:15', NULL),
(7, 46087, 'NGSCHA013451', 'Male', '08168143343', 25, 272, 117, 'Diarrhea', 48, '2022-01-09', 'December, 2021', 'no', '2023-12-14 19:40:15', NULL),
(8, 46087, 'NGSCHA013454', 'Female', '08131394811', 25, 272, 117, 'Delivery', 129, '2022-01-19', 'December, 2021', 'no', '2023-12-14 19:40:15', NULL),
(9, 46087, 'NGSCHA013457', 'Female', '08168593090', 25, 272, 117, 'ANC', 124, '2022-01-29', 'December, 2021', 'no', '2023-12-14 19:40:15', NULL),
(10, 46087, 'NGSCHA013467', 'Female', '07037185207', 25, 272, 117, 'ANC', 124, '2022-01-19', 'December, 2021', 'no', '2023-12-14 19:40:15', NULL),
(11, 46086, 'NGSCHA008292', 'Female', '07051089118', 25, 269, 221, 'ANC', 124, '2022-11-30', 'November, 2022', 'no', '2023-12-15 13:50:57', NULL),
(12, 46086, 'NGSCHA008294', 'Female', '07051089118', 25, 269, 221, 'Back Pain', 97, '2022-11-30', 'November, 2022', 'no', '2023-12-15 13:50:57', NULL),
(13, 46086, 'NGSCHA008300', 'Female', '09036647767', 25, 269, 221, 'ANC', 124, '2022-11-30', 'November, 2022', 'no', '2023-12-15 13:50:57', NULL),
(14, 46086, 'NGSCHA008748', 'Female', '08059443329', 25, 265, 221, 'Maleria Fever', 73, '2022-01-14', 'December, 2022', 'no', '2023-12-15 13:50:57', NULL),
(15, 46086, 'NGSCHA008292', 'Female', '07051089118', 25, 269, 221, 'ANC', 124, '2022-01-11', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(16, 46086, 'NGSCHA008294', 'Female', '07051089118', 25, 269, 221, 'Back Pain', 97, '2022-01-11', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(17, 46086, 'NGSCHA008300', 'Female', '09036647767', 25, 269, 221, 'ANC', 124, '2022-01-11', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(18, 46086, 'NGSCHA008915', 'Male', '08096792848', 25, 269, 221, 'ANC', 124, '2022-01-16', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(19, 46086, 'NGSCHA010171', 'Female', '08133230203', 25, 265, 221, 'Family Planning', 123, '2022-01-14', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(20, 46086, 'NGSCHA010174', 'Male', '08054394044', 25, 265, 221, 'Fever/Cough', 73, '2022-01-15', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(21, 46086, 'NGSCHA011395', 'Male', '08028807242', 25, 267, 221, 'BP', 96, '2022-01-16', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(22, 46086, 'NGSCHA011438', 'Female', '08154121399', 25, 267, 221, 'ANC', 124, '2022-01-14', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(23, 46086, 'NGSCHA012262', 'Female', '07081702392', 25, 269, 221, 'Fever/Headache', 73, '2022-01-16', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(24, 46086, 'NGSCHA012265', 'Male', '08060208417', 25, 269, 221, 'Maleria Fever', 73, '2022-01-19', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(25, 46086, 'NGSCHA012266', 'Female', '09053176338', 25, 269, 221, 'BP', 96, '2022-01-19', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(26, 46086, 'NGSCHA012267', 'Female', '09063718355', 25, 269, 221, 'Diarrhea', 80, '2022-01-19', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(27, 46086, 'NGSCHA012269', 'Female', '08114346525', 25, 265, 221, 'Maleria Fever', 73, '2022-01-23', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(28, 46086, 'NGSCHA012270', 'Female', '07035014209', 25, 269, 221, 'Labour', 129, '2022-01-23', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(29, 46086, 'NGSCHA012272', 'Female', '09035367450', 25, 269, 221, 'Maleria Fever', 73, '2022-01-14', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(30, 46086, 'NGSCHA012276', 'Male', '07035014209', 25, 269, 221, 'Back Pain/Leg Pain', 97, '2022-01-14', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(31, 46086, 'NGSCHA012277', 'Female', '08129092849', 25, 269, 221, 'Labour', 129, '2022-01-23', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(32, 46086, 'NGSCHA012283', 'Male', '08145707295', 25, 269, 221, 'Diarrhea/Fever', 80, '2022-01-14', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(33, 46086, 'NGSCHA012283', 'Male', '08145707295', 25, 269, 221, 'Diarrhea/Fever', 73, '2022-01-14', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(34, 46086, 'NGSCHA012284', 'Female', '08145707295', 25, 269, 221, 'Fever/Vomiting', 81, '2022-01-14', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(35, 46086, 'NGSCHA012285', 'Male', '08145707295', 25, 269, 221, 'Brueces On The Right Leg', 97, '2022-01-14', 'December, 2021', 'no', '2023-12-15 13:50:57', NULL),
(36, 46086, 'NGSCHA008288', 'Male', '09064237665', 25, 269, 221, 'Fever/Vomiting', 81, '2022-02-27', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(37, 46086, 'NGSCHA008291', 'Female', '08124522385', 25, 269, 221, 'Back Pain/Lower Abdominal Pain', 81, '2022-02-23', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(38, 46086, 'NGSCHA008292', 'Female', '07051089118', 25, 269, 221, 'Vomiting/Abdominal Pain', 81, '2022-02-17', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(39, 46086, 'NGSCHA008293', 'Female', '08074519218', 25, 269, 221, 'Headache/Diziness', 73, '2022-02-17', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(40, 46086, 'NGSCHA008297', 'Female', '08141344036', 25, 269, 221, 'Labour', 129, '2022-02-17', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(41, 46086, 'NGSCHA008299', 'Female', '09028816101', 25, 269, 221, 'Fever/Diarrhea', 73, '2024-02-27', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(42, 46086, 'NGSCHA008299', 'Female', '09028816101', 25, 269, 221, 'Fever/Diarrhea', 80, '2024-02-27', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(43, 46086, 'NGSCHA008300', 'Female', '09036647767', 25, 269, 221, 'Abdominal', 81, '2022-02-23', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(44, 46086, 'NGSCHA008301', 'Female', '08058150254', 25, 269, 221, 'Maleria Fever', 73, '2022-02-17', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(45, 46086, 'NGSCHA008791', 'Female', '08054228555', 25, 269, 221, 'Back Pain/Body Pain', 97, '2022-02-20', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(46, 46086, 'NGSCHA008799', 'Female', '08054228555', 25, 269, 221, 'Lack Of Appetite/Body Weakness', 73, '2022-02-21', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(47, 46086, 'NGSCHA008810', 'Female', '08100419128', 25, 269, 221, 'Back Pain/Body Pain/Body Pain', 97, '2022-02-23', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(48, 46086, 'NGSCHA008842', 'Female', '08110971999', 25, 269, 221, 'Chest Pain/Vomiting/PUD', 127, '2022-02-23', 'January, 2022', 'yes', '2023-12-15 13:50:57', NULL),
(49, 46086, 'NGSCHA008844', 'Female', '08058227220', 25, 269, 221, 'Fever/Head Vomiting', 81, '2022-02-21', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(50, 46086, 'NGSCHA008861', 'Female', '08101515070', 25, 265, 221, 'Back Pain/Leg Pain', 97, '2022-02-24', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(51, 46086, 'NGSCHA008882', 'Female', '09137857120', 25, 269, 221, 'Lower Abdominal Pain', 81, '2022-02-25', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(52, 46086, 'NGSCHA008906', 'Male', '08148181625', 25, 269, 221, 'Maleria Fever', 73, '2022-02-24', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(53, 46086, 'NGSCHA011406', 'Male', '09020411086', 25, 267, 221, 'Maleria Fever', 73, '2022-02-11', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(54, 46086, 'NGSCHA011443', 'Male', '07062402625', 25, 267, 221, 'Maleria Fever', 73, '2022-02-27', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(55, 46086, 'NGSCHA012285', 'Male', '08145707295', 25, 269, 221, 'Fever/Headache', 73, '2022-02-24', 'January, 2022', 'no', '2023-12-15 13:50:57', NULL),
(56, 46086, 'NGSCHA010165', 'Male', '08058779315', 25, 265, 221, 'Back Pain/Body Pain', 97, '2022-03-02', 'February, 2022', 'no', '2023-12-15 13:50:57', NULL),
(57, 46086, 'NGSCHA010166', 'Female', '08144956005', 25, 265, 221, 'Cough/Catterh', 74, '2022-03-01', 'February, 2022', 'no', '2023-12-15 13:50:57', NULL),
(58, 46086, 'NGSCHA010167', 'Male', '08144956005', 25, 265, 221, 'Diarrhea/Fever', 73, '2022-03-01', 'February, 2022', 'no', '2023-12-15 13:50:57', NULL),
(59, 46086, 'NGSCHA010167', 'Male', '08144956005', 25, 265, 221, 'Diarrhea/Fever', 80, '2022-03-01', 'February, 2022', 'no', '2023-12-15 13:50:57', NULL),
(60, 46086, 'NGSCHA010168', 'Male', '08144956005', 25, 265, 221, 'Cough/Catterh', 74, '2022-03-01', 'February, 2022', 'no', '2023-12-15 13:50:57', NULL),
(61, 46086, 'NGSCHA010170', 'Male', '08073680134', 25, 265, 221, 'Maleria Fever', 73, '2022-02-28', 'February, 2022', 'no', '2023-12-15 13:50:57', NULL),
(62, 46086, 'NGSCHA029787', 'Male', '07067879616', 25, 265, 221, 'Diarrhea', 48, '2022-03-01', 'February, 2022', 'no', '2023-12-15 13:50:57', NULL),
(63, 46086, 'NGSCHA029789', 'Male', '09071702516', 25, 265, 221, 'Maleria', 73, '2022-02-28', 'February, 2022', 'no', '2023-12-15 13:50:57', NULL),
(64, 46086, 'NGSCHA029801', 'Male', '08139767397', 25, 265, 221, 'Maleria Fever/Fever', 73, '2022-03-02', 'February, 2022', 'no', '2023-12-15 13:50:57', NULL),
(65, 46086, 'NGSCHA039212', 'Male', '08071978730', 25, 265, 221, 'Maleria Fever', 73, '2022-03-01', 'February, 2022', 'no', '2023-12-15 13:50:57', NULL),
(66, 46086, 'NGSCHA039503', 'Male', '07058878530', 25, 265, 221, 'Epigastric Pain', 76, '2022-03-01', 'February, 2022', 'no', '2023-12-15 13:50:57', NULL),
(67, 46086, 'NGSCHA008245', 'Female', '09064327665', 25, 269, 221, 'Back Pain/Stomach Pain', 81, '2022-04-21', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(68, 46086, 'NGSCHA008277', 'Male', '08154156141', 25, 269, 221, 'Fever/Vomiting', 81, '2022-04-21', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(69, 46086, 'NGSCHA008279', 'Male', '09136124135', 25, 269, 221, 'Stomach Pain/Diarrhea', 76, '2022-04-21', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(70, 46086, 'NGSCHA008289', 'Female', '09022143129', 25, 269, 221, 'Maleria Fever', 73, '2022-04-21', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(71, 46086, 'NGSCHA008290', 'Female', '09036527096', 25, 269, 221, 'ANC', 124, '2022-04-21', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(72, 46086, 'NGSCHA008295', 'Female', '08157210482', 25, 269, 221, 'Body Pain/Fever', 73, '2022-04-21', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(73, 46086, 'NGSCHA008787', 'Female', '08054228555', 25, 269, 221, 'Chest Pain/Back Pain', 74, '2022-04-03', 'March, 2023', 'no', '2023-12-15 13:50:57', NULL),
(74, 46086, 'NGSCHA008789', 'Female', '09061292244', 25, 269, 221, 'Back Pain/Body Pain/Malaria Fever', 73, '2022-04-10', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(75, 46086, 'NGSCHA008807', 'Female', '08144570495', 25, 269, 221, 'Maleria Fever', 73, '2022-04-07', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(76, 46086, 'NGSCHA008814', 'Female', '07042594358', 25, 269, 221, 'Fever/Cough', 73, '2022-04-04', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(77, 46086, 'NGSCHA008824', 'Female', '08056352150', 25, 269, 221, 'Diarrhea', 80, '2022-04-07', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(78, 46086, 'NGSCHA008858', 'Male', '08145984238', 25, 269, 221, 'Fever/Diarrhea', 73, '2022-04-04', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(79, 46086, 'NGSCHA008858', 'Male', '08145984238', 25, 269, 221, 'Fever/Diarrhea', 80, '2022-04-04', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(80, 46086, 'NGSCHA011397', 'Female', '09018206438', 25, 267, 221, 'Maleria Fever', 73, '2022-04-10', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(81, 46086, 'NGSCHA011399', 'Male', '08103008771', 25, 267, 221, 'Difficult cough', 50, '2022-04-10', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(82, 46086, 'NGSCHA011415', 'Female', '08063317831', 25, 267, 221, 'Fever/Back Pain', 73, '2022-04-10', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(83, 46086, 'NGSCHA012260', 'Female', '08138434957', 25, 269, 221, 'Dizziness/BP', 96, '2022-04-07', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(84, 46086, 'NGSCHA012267', 'Female', '09063718355', 25, 269, 221, 'Back Pain/Lower Abdominal Pain', 81, '2022-04-03', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(85, 46086, 'NGSCHA012275', 'Female', '09068329346', 25, 269, 221, 'Fever/Headache', 73, '2022-04-10', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(86, 46086, 'NGSCHA027937', 'Female', '08030642619', 25, 269, 221, 'Maleria Fever', 73, '2022-04-10', 'March, 2022', 'no', '2023-12-15 13:50:57', NULL),
(87, 46086, 'NGSCHA008808', 'Female', '08112695082', 25, 269, 221, 'Fever/Cough', 73, '2022-05-15', 'April, 2022', 'no', '2023-12-15 13:50:57', NULL),
(88, 46086, 'NGSCHA011394', 'Female', '07088401290', 25, 267, 221, 'Headache Chapin/BP', 73, '2022-05-16', 'April, 2022', 'no', '2023-12-15 13:50:57', NULL),
(89, 46086, 'NGSCHA011394', 'Female', '07088401290', 25, 267, 221, 'Headache Chapin/BP', 96, '2022-05-16', 'April, 2022', 'no', '2023-12-15 13:50:57', NULL),
(90, 46086, 'NGSCHA008299', 'Female', '09028816101', 25, 269, 221, 'Fever/Diarrhea', 73, '2022-06-23', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(91, 46086, 'NGSCHA008299', 'Female', '09028816101', 25, 269, 221, 'Fever/Diarrhea', 80, '2022-06-23', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(92, 46086, 'NGSCHA008866', 'Female', '08101515070', 25, 265, 221, 'Fever/Dizziness', 73, '2022-06-22', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(93, 46086, 'NGSCHA008868', 'Female', '08101515070', 25, 265, 221, 'Fever', 73, '2022-06-20', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(94, 46086, 'NGSCHA008875', 'Female', '08101515070', 25, 265, 221, 'Fever', 73, '2022-06-21', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(95, 46086, 'NGSCHA008876', 'Male', '08101515070', 25, 265, 221, 'Fever/Abdominal Pain', 96, '2022-06-21', 'May, 2022', 'yes', '2023-12-15 13:50:57', NULL),
(96, 46086, 'NGSCHA008877', 'Male', '08101515070', 25, 265, 221, 'Fever', 73, '2024-06-21', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(97, 46086, 'NGSCHA008880', 'Male', '08101515070', 25, 265, 221, 'Fever/Diarrhea', 73, '2022-06-20', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(98, 46086, 'NGSCHA008880', 'Male', '08101515070', 25, 265, 221, 'Fever/Diarrhea', 80, '2022-06-20', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(99, 46086, 'NGSCHA008887', 'Male', '08101515070', 25, 265, 221, 'Cough/Catterh', 74, '2022-06-20', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(100, 46086, 'NGSCHA008888', 'Male', '08101515070', 25, 265, 221, 'Fever/Abdominal pain', 81, '2022-06-20', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(101, 46086, 'NGSCHA008891', 'Female', '08101515070', 25, 265, 221, 'Chest Pain', 74, '2022-06-04', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(102, 46086, 'NGSCHA008892', 'Female', '08101515070', 25, 265, 221, 'Fever/Cough', 73, '2022-06-20', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(103, 46086, 'NGSCHA008893', 'Female', '08101515070', 25, 265, 221, 'Fever/Cough', 73, '2022-06-20', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(104, 46086, 'NGSCHA008895', 'Male', '08101515070', 25, 265, 221, 'Fever/Headache', 73, '2022-06-21', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(105, 46086, 'NGSCHA010173', 'Female', '07069520335', 25, 265, 221, 'Fever/Diarrhea', 73, '2022-06-20', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(106, 46086, 'NGSCHA010173', 'Female', '07069520335', 25, 265, 221, 'Fever/Diarrhea', 80, '2022-06-20', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(107, 46086, 'NGSCHA011393', 'Male', '08101253367', 25, 265, 221, 'Fever/Cough', 73, '2022-06-04', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(108, 46086, 'NGSCHA012294', 'Male', '09056614809', 25, 265, 221, 'Abdominal pain', 81, '2022-06-26', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(109, 46086, 'NGSCHA012295', 'Male', '07035139832', 25, 265, 221, 'Diarrhea', 48, '2022-06-20', 'May, 2022', 'no', '2023-12-15 13:50:57', NULL),
(110, 46087, 'NGSCHA013470', 'Female', '08164623135', 25, 272, 117, 'ANC', 124, '2022-01-20', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(111, 46087, 'NGSCHA013473', 'Female', '09135200390', 25, 272, 117, 'Malaria Fever', 47, '2022-01-29', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(112, 46087, 'NGSCHA013494', 'Male', '07039460594', 25, 272, 117, 'Malaria Fever', 47, '2022-01-29', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(113, 46087, 'NGSCHA014359', 'Male', '07050718372', 25, 272, 117, 'Malaria Fever', 47, '2022-01-22', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(114, 46087, 'NGSCHA014362', 'Female', '09067651354', 25, 272, 117, 'Malaria Fever', 47, '2022-01-27', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(115, 46087, 'NGSCHA014367', 'Female', '08140565610', 25, 272, 117, 'ANC', 124, '2022-01-22', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(116, 46087, 'NGSCHA014373', 'Male', '08168593090', 25, 272, 117, 'Malaria Fever', 47, '2022-01-29', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(117, 46087, 'NGSCHA014379', 'Male', '08168593090', 25, 272, 117, 'Malaria Fever', 47, '2022-01-29', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(118, 46087, 'NGSCHA014388', 'Female', '08165751442', 25, 272, 117, 'Malaria Fever', 47, '2022-01-20', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(119, 46087, 'NGSCHA014390', 'Female', '09036561301', 25, 272, 117, 'Malaria Fever', 47, '2022-01-20', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(120, 46087, 'NGSCHA014404', 'Female', '08165108393', 25, 272, 117, 'Delivery', 129, '2022-01-22', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(121, 46087, 'NGSCHA014405', 'Female', '08132032880', 25, 272, 117, 'Malaria Fever', 47, '2022-01-17', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(122, 46087, 'NGSCHA014406', 'Male', '09066104742', 25, 272, 117, 'Diarrhea', 48, '2022-01-17', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(123, 46087, 'NGSCHA014410', 'Female', '08101334087', 25, 272, 117, 'Malaria Fever', 47, '2022-01-17', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(124, 46087, 'NGSCHA014411', 'Female', '08101334087', 25, 272, 117, 'Malaria Fever', 47, '2022-01-17', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(125, 46087, 'NGSCHA014411', 'Female', '08101334087', 25, 272, 117, 'Malaria Fever', 47, '2022-01-17', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(126, 46087, 'NGSCHA014412', 'Female', '08101334087', 25, 272, 117, 'Malaria Fever', 47, '2022-01-17', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(127, 46087, 'NGSCHA014412', 'Female', '08101334087', 25, 272, 117, 'Malaria Fever', 47, '2022-01-17', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(128, 46087, 'NGSCHA014414', 'Male', '09035200392', 25, 272, 117, 'Malaria Fever', 47, '2022-01-11', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(129, 46087, 'NGSCHA014424', 'Male', '09135200392', 25, 272, 117, 'Diarrhea', 48, '2022-01-11', 'December, 2021', 'no', '2023-12-18 16:04:58', NULL),
(130, 46087, 'NGSCHA013427', 'Female', '09068862308', 25, 272, 117, 'Malaria Fever', 47, '2022-02-02', 'January, 2022', 'no', '2023-12-18 16:04:58', NULL),
(131, 46087, 'NGSCHA013428', 'Female', '08101331788', 25, 272, 117, 'Malaria Fever', 47, '2022-02-27', 'January, 2022', 'no', '2023-12-18 16:04:58', NULL),
(132, 46087, 'NGSCHA013430', 'Male', '07032922666', 25, 272, 117, 'Malaria Fever', 47, '2022-01-31', 'January, 2022', 'no', '2023-12-18 16:04:58', NULL),
(133, 46087, 'NGSCHA013442', 'Female', '09066724061', 25, 272, 117, 'Malaria Fever', 47, '2022-02-18', 'January, 2022', 'no', '2023-12-18 16:04:58', NULL),
(134, 46087, 'NGSCHA013444', 'Male', '08101331788', 25, 272, 117, 'Malaria Fever', 47, '2022-02-02', 'January, 2022', 'no', '2023-12-18 16:04:58', NULL),
(135, 46087, 'NGSCHA013444', 'Male', '08101331788', 25, 272, 117, 'Malaria Fever', 47, '2022-02-02', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(136, 46087, 'NGSCHA013461', 'Female', '09065010193', 25, 272, 117, 'Malaria Fever', 47, '2022-02-09', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(137, 46087, 'NGSCHA013463', 'Female', '08162341886', 25, 272, 117, 'Malaria Fever', 47, '2022-02-03', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(138, 46087, 'NGSCHA013464', 'Female', '08069555469', 25, 272, 117, 'Malaria Fever', 47, '2022-02-15', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(139, 46087, 'NGSCHA013486', 'Female', '07039460594', 25, 272, 117, 'ANC', 124, '2022-02-27', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(140, 46087, 'NGSCHA013488', 'Female', '09034139684', 25, 272, 117, 'ANC', 124, '2022-02-19', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(141, 46087, 'NGSCHA013491', 'Female', '07039460594', 25, 272, 117, 'Malaria Fever', 47, '2022-02-19', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(142, 46087, 'NGSCHA013492', 'Female', '07039460594', 25, 272, 117, 'Malaria Fever', 47, '2022-02-19', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(143, 46087, 'NGSCHA013493', 'Male', '07039460595', 25, 272, 117, 'Malaria Fever', 47, '2022-02-19', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(144, 46087, 'NGSCHA013495', 'Male', '08038383249', 25, 272, 117, 'Malaria Fever', 47, '2022-02-22', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(145, 46087, 'NGSCHA013495', 'Male', '08038383249', 25, 272, 117, 'Malaria Fever', 47, '2022-02-22', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(146, 46087, 'NGSCHA014345', 'Male', '09034139684', 25, 272, 117, 'Malaria Fever', 47, '2022-02-07', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(147, 46087, 'NGSCHA014346', 'Male', '09034131684', 25, 272, 117, 'Malaria Fever', 47, '2022-02-13', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(148, 46087, 'NGSCHA014347', 'Male', '09034139684', 25, 272, 117, 'Malaria Fever', 47, '2022-02-13', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(149, 46087, 'NGSCHA014348', 'Female', '09138750649', 25, 272, 117, 'ANC', 124, '2022-02-13', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(150, 46087, 'NGSCHA014358', 'Female', '08152205375', 25, 272, 117, 'Delivery', 129, '2022-02-14', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(151, 46087, 'NGSCHA014366', 'Female', '09135200392', 25, 272, 117, 'Malaria Fever', 47, '2022-02-27', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(152, 46087, 'NGSCHA014381', 'Female', '08062277550', 25, 272, 117, 'Malaria Fever', 47, '2022-02-27', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(153, 46087, 'NGSCHA014386', 'Male', '07038675088', 25, 272, 117, 'Malaria Fever', 47, '2022-02-15', 'January, 2022', 'no', '2024-01-23 17:56:41', NULL),
(154, 46087, 'NGSCHA013429', 'Female', '07062461593', 25, 272, 117, 'Malaria Fever', 47, '2022-02-28', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(155, 46087, 'NGSCHA013468', 'Female', '09063194235', 25, 272, 117, 'Malaria Fever', 47, '2022-03-27', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(156, 46087, 'NGSCHA013471', 'Female', '09030985160', 25, 272, 117, 'Malaria Fever', 47, '2022-03-28', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(157, 46087, 'NGSCHA013472', 'Female', '08132916546', 25, 272, 117, 'Malaria Fever', 47, '2022-02-28', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(158, 46087, 'NGSCHA013486', 'Female', '07039460594', 25, 272, 117, 'ANC', 49, '2022-03-01', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(159, 46087, 'NGSCHA013486', 'Female', '07039460594', 25, 272, 117, 'ANC', 124, '2022-03-01', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(160, 46087, 'NGSCHA013486', 'Female', '07039460594', 25, 272, 117, 'ANC', 124, '2022-03-01', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(161, 46087, 'NGSCHA013490', 'Female', '08066242009', 25, 272, 117, 'Malaria Fever', 47, '2022-03-07', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(162, 46087, 'NGSCHA014344', 'Female', '09138750649', 25, 272, 117, 'Malaria Fever', 47, '2022-02-28', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(163, 46087, 'NGSCHA014369', 'Female', '09135200392', 25, 272, 117, 'ANC', 124, '2022-03-30', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(164, 46087, 'NGSCHA014383', 'Female', '07033892442', 25, 272, 117, 'ANC', 124, '2022-03-28', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(165, 46087, 'NGSCHA014388', 'Female', '08165751442', 25, 272, 117, 'Malaria Fever', 47, '2022-02-28', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(166, 46087, 'NGSCHA014391', 'Female', '08165751442', 25, 272, 117, 'Diarrhea', 48, '2022-03-28', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(167, 46087, 'NGSCHA014393', 'Female', '09153714361', 25, 272, 117, 'Malaria Fever', 47, '2022-03-30', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(168, 46087, 'NGSCHA014394', 'Male', '08104741232', 25, 272, 117, 'Malaria Fever', 47, '2022-03-28', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(169, 46087, 'NGSCHA014395', 'Female', '09037221035', 25, 272, 117, 'Malaria Fever', 47, '2022-03-30', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(170, 46087, 'NGSCHA014399', 'Male', '08106156919', 25, 272, 117, 'Malaria Fever', 47, '2022-03-29', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(171, 46087, 'NGSCHA014410', 'Female', '08101334087', 25, 272, 117, 'Delivery', 129, '2022-03-30', 'February, 2022', 'no', '2024-01-23 17:56:41', NULL),
(172, 1347, 'NGSCHA000001', 'Male', '08024347376', 15, 158, 98, 'owjiowejiowj', 4, '2024-02-01', '2024-02-01', 'no', '2024-03-13 16:22:55', NULL);

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
-- Table structure for table `medical_bills`
--

CREATE TABLE `medical_bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `month` varchar(255) NOT NULL,
  `amount` double(11,2) NOT NULL,
  `facility_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `capitation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medical_bills`
--

INSERT INTO `medical_bills` (`id`, `month`, `amount`, `facility_id`, `created_at`, `updated_at`, `capitation_id`) VALUES
(1, '2023-01-31T23:00:00.000Z', 1000.00, 221, '2023-12-18 14:33:21', '2023-12-18 14:33:21', 10),
(2, '2021-12-31T23:00:00.000Z', 20000.00, 117, '2023-12-18 16:10:45', '2023-12-18 16:10:45', 123);

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
(1, '2014_10_12_100000_create_password_resets_table', 1),
(2, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(3, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(4, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(5, '2016_06_01_000004_create_oauth_clients_table', 1),
(6, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(7, '2019_08_19_000000_create_failed_jobs_table', 1),
(8, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(9, '2023_09_01_053720_create_job_batches_table', 1),
(10, '2023_09_01_053725_create_jobs_table', 1),
(11, '2023_09_21_195918_create_activated_users_table', 1),
(12, '2023_09_21_200130_create_enrolee_visits_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('03d791973d3a040d61fb595992d745eec71c34a50f78722d09c3db6aad10c2d2958dafa002e179ee', 46087, 3, 'AuthToken', '[]', 0, '2023-12-14 17:36:18', '2023-12-14 17:36:18', '2024-12-14 12:36:18'),
('05fad8b27129e5e6c41c9050aec24b663df15e73b4b909ddcd3aa50e5c410eb1708fc0be2bac8959', 46087, 3, 'AuthToken', '[]', 0, '2023-12-14 16:22:28', '2023-12-14 16:22:28', '2024-12-14 11:22:28'),
('1ce96a1ce16369ddf6819d639409a37ee43dd2aec00878ec404211b3022f43bbc80320a13ea19f8f', 46087, 3, 'AuthToken', '[]', 0, '2023-12-14 15:14:33', '2023-12-14 15:14:33', '2024-12-14 10:14:33'),
('39e086f197abb5ea30d8e996dc6ae5ad8043639201f4c8cac30b8a3f50a0aac830312d3acc7b2aa7', 46086, 3, 'AuthToken', '[]', 0, '2023-12-14 17:48:04', '2023-12-14 17:48:04', '2024-12-14 12:48:04'),
('482cc6eb87cc89a141f9cb87988aff49fe7568dbd3ea7b868ee80333e85104ade5b1ff231c35951b', 46086, 3, 'AuthToken', '[]', 0, '2023-12-14 18:07:48', '2023-12-14 18:07:48', '2024-12-14 13:07:48'),
('5c47400b069e74e350a5dc0f4a7d542d3b60aeb3065f54b8437648682a63da083def8e3991884d5f', 1452, 3, 'AuthToken', '[]', 0, '2023-11-08 18:39:05', '2023-11-08 18:39:05', '2024-11-08 13:39:05'),
('5f59565c72fdeedc4fbb08079b0225feb500c5e2f96fefefc2131da388b557ef400acb8528759060', 1347, 3, 'AuthToken', '[]', 0, '2024-03-13 15:36:50', '2024-03-13 15:36:50', '2025-03-13 16:36:50'),
('86973b0b83d9bba42f4029435cfdbb17be1882ddc33ae18335c769807987c30ff7fcb399cb508ecd', 46087, 3, 'AuthToken', '[]', 0, '2023-12-14 15:14:59', '2023-12-14 15:14:59', '2024-12-14 10:14:59'),
('87b8a1e0fa292f1e8dc5e8864c9d9accd79858a13d656ca84995954e86db28b3076f53245110048a', 46086, 3, 'AuthToken', '[]', 0, '2023-12-14 18:08:41', '2023-12-14 18:08:41', '2024-12-14 13:08:41'),
('8b0683e35b2ed3079882c1da190456a1da4b17c0bd4ce2637ce5a4c857b8979c4f4493730b95185e', 46086, 3, 'AuthToken', '[]', 0, '2023-12-14 17:41:40', '2023-12-14 17:41:40', '2024-12-14 12:41:40'),
('8ec7d361b38d41d618050fe198c9364f62c2dbd7f182d4786c1d76fae77a97d75c3f14f2e0f7faba', 46085, 3, 'AuthToken', '[]', 0, '2023-12-14 18:10:26', '2023-12-14 18:10:26', '2024-12-14 13:10:26'),
('b97fe14a4d33b7b16a213a36db7c62e23ba3d6b9d6bbee58ecc18d75976d63a0cdd482f60314b7a5', 1347, 3, 'AuthToken', '[]', 0, '2024-03-13 15:38:47', '2024-03-13 15:38:47', '2025-03-13 16:38:47'),
('c359a592eeb2ac7486f68c36387649e7af2f5b257633f0cef5a83f18c1512a2994aa9eceec900a70', 1347, 3, 'AuthToken', '[]', 0, '2024-03-13 15:51:34', '2024-03-13 15:51:34', '2025-03-13 16:51:34'),
('e071901b6e4276dcd3cc10a5678a3fcb88676da56f67e1c7dafd459ecb987a386a8a213186b59f4f', 46086, 3, 'AuthToken', '[]', 0, '2023-12-15 10:33:01', '2023-12-15 10:33:01', '2024-12-15 05:33:01'),
('e4b7fa428d9e32c074b958f8302860e8ee2070251d0bdca03ad848799f1d1ca11ba409317a68cf77', 46084, 3, 'AuthToken', '[]', 0, '2023-12-14 18:10:57', '2023-12-14 18:10:57', '2024-12-14 13:10:57'),
('e4c580b0badf1010964a2f9d224d3a17271312c861a547b4dd033dffb01641132e9b15a5a1d9eca2', 1347, 3, 'AuthToken', '[]', 0, '2024-03-13 15:59:08', '2024-03-13 15:59:08', '2025-03-13 16:59:08'),
('ed2c92a79928c7149c1deae966555613bdd90472ce235cb4335ca0a34e8700ce0ed8756b0e307f2f', 46083, 3, 'AuthToken', '[]', 0, '2023-11-05 18:44:41', '2023-11-05 18:44:41', '2024-11-05 13:44:41'),
('f5d105b324c1200e576296d20893d1a89efaed4d08c0d9941781216fea4f894a61a49aac83062129', 1347, 3, 'AuthToken', '[]', 0, '2024-03-13 16:02:29', '2024-03-13 16:02:29', '2025-03-13 17:02:29'),
('ffc72b563e17512c292f5562ea96fbf8a83ef62f168eac707bf74540992cdafb8510156478d7c269', 46087, 3, 'AuthToken', '[]', 0, '2023-12-14 15:31:35', '2023-12-14 15:31:35', '2024-12-14 10:31:35');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Paramount Community Personal Access Client', 'K1hXnHXsL3zuuQLovBtlt2qxqXMoTXuwDZz2kuxJ', NULL, 'http://localhost', 1, 0, 0, '2023-09-26 20:11:22', '2023-09-26 20:11:22'),
(2, NULL, 'Paramount Community Password Grant Client', 'W77nX1QolRPqGSg23g1C9NHq5fl0nIxBXOaf5VbG', 'users', 'http://localhost', 0, 1, 0, '2023-09-26 20:11:22', '2023-09-26 20:11:22'),
(3, NULL, 'Laravel Personal Access Client', 'wihCXnumWVeSvEiaU0I4Ne3GboDxz3PxaflEG1Of', NULL, 'http://localhost', 1, 0, 0, '2023-11-05 18:30:12', '2023-11-05 18:30:12'),
(4, NULL, 'Laravel Password Grant Client', 'XFPD6sCANODTq5eGr5eyvi4HnBJlVhSBeyq5cWCn', 'users', 'http://localhost', 0, 1, 0, '2023-11-05 18:30:12', '2023-11-05 18:30:12');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2023-09-26 20:11:22', '2023-09-26 20:11:22'),
(2, 3, '2023-11-05 18:30:12', '2023-11-05 18:30:12');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
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
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activated_users`
--
ALTER TABLE `activated_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `activated_users_user_id_unique` (`user_id`);

--
-- Indexes for table `enrolee_visits`
--
ALTER TABLE `enrolee_visits`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `medical_bills`
--
ALTER TABLE `medical_bills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activated_users`
--
ALTER TABLE `activated_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `enrolee_visits`
--
ALTER TABLE `enrolee_visits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

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
-- AUTO_INCREMENT for table `medical_bills`
--
ALTER TABLE `medical_bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
