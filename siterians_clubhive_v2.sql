-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 10, 2025 at 02:06 PM
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
-- Database: `siterians_clubhive_v2`
--

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
('vLSfS7IGNCA38eIMKsYJxByoB9puIDeYxKgLr3kb', 58, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibXFYa3lEOVZlUGxLVnJvUzNlV3MyY2QzeWRzTVdCc1ZUdkZoZVpXSSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC92b3RpbmcvY2hlY2stY2hhbmdlcz9jaGVja3N1bT1mYjUxYTZiOTNmOTM1ZjAzN2EzYTEyYzgwMjNkYmQ5NiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU4O30=', 1754827570);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_candidates`
--

CREATE TABLE `tbl_candidates` (
  `candidate_id` bigint(20) UNSIGNED NOT NULL,
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `position` text NOT NULL,
  `partylist` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_candidates`
--

INSERT INTO `tbl_candidates` (`candidate_id`, `election_id`, `user_id`, `position`, `partylist`, `created_at`, `updated_at`) VALUES
(18, 10, 37, 'President', 'IND', '2025-05-22 21:17:37', '2025-05-22 21:17:37'),
(19, 10, 8, 'President', 'PDP', '2025-05-22 21:17:37', '2025-05-22 21:17:37'),
(20, 10, 34, 'Vice President', 'IND', '2025-05-22 21:21:43', '2025-05-22 21:21:43'),
(21, 10, 43, 'Vice President', 'NPC', '2025-05-22 21:21:43', '2025-05-22 21:21:43'),
(22, 10, 53, 'Treasurer', 'IND', '2025-05-22 21:22:21', '2025-05-22 21:22:21'),
(23, 10, 45, 'Treasurer', 'NPC', '2025-05-22 21:22:21', '2025-05-22 21:22:21'),
(25, 11, 6, 'President', 'NPC', '2025-08-10 11:10:18', '2025-08-10 11:10:18'),
(26, 11, 23, 'President', 'IND', '2025-08-10 11:10:18', '2025-08-10 11:10:18'),
(27, 11, 8, 'Testing Text Input POsition', 'NPC', '2025-08-10 11:10:47', '2025-08-10 11:10:47'),
(28, 11, 34, 'Testing Text Input POsition', 'IND', '2025-08-10 11:10:47', '2025-08-10 11:10:47');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_classes`
--

CREATE TABLE `tbl_classes` (
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `grade_level` tinyint(3) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_classes`
--

INSERT INTO `tbl_classes` (`class_id`, `grade_level`, `created_at`, `updated_at`) VALUES
(1, 7, '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(2, 8, '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(3, 9, '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(4, 10, '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(5, 11, '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(6, 12, '2025-03-29 12:55:49', '2025-03-29 12:55:49');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_clubs`
--

CREATE TABLE `tbl_clubs` (
  `club_id` bigint(20) UNSIGNED NOT NULL,
  `club_name` varchar(255) NOT NULL,
  `club_adviser` bigint(20) UNSIGNED NOT NULL,
  `club_description` text DEFAULT NULL,
  `category` enum('academic','sports','service') NOT NULL DEFAULT 'academic',
  `requires_approval` tinyint(1) NOT NULL DEFAULT 1,
  `club_logo` varchar(255) NOT NULL,
  `club_banner` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_club_hunting_day` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_clubs`
--

INSERT INTO `tbl_clubs` (`club_id`, `club_name`, `club_adviser`, `club_description`, `category`, `requires_approval`, `club_logo`, `club_banner`, `created_at`, `updated_at`, `is_club_hunting_day`) VALUES
(1, 'Supreme Secondary Learner Government-Sitero Francisco MNHS', 4, 'The Supreme Secondary Learner Government (SSLG) - Sitero Francisco MNHS empowers student leaders to inspire positive change, advocate for the student body, and build a vibrant school community through leadership, service, and unity.', 'academic', 1, 'club-logos/lFkL7res3cHWaPblBAg3VMDjmOROb6WAP5p1KTic.jpg', 'club-banners/9aDbycENE7PPHR3Xy4RMq0c3b8salrOCdLpWPDDs.jpg', '2025-03-29 15:02:34', '2025-08-10 11:46:27', 0),
(3, 'Sitero SHS GILAS', 12, '\"To elevate integrity and Social Responsibilities of Society.\"', 'academic', 1, 'club-logos/SqqhdRiTn4aZHZRh2hDUriBIf90mrsBrb7h0MySc.jpg', 'club-banners/mGhxzLkmeHiQS8TZx5z8Wxc8Tkl5ddiPt1B2jPUV.jpg', '2025-03-30 06:29:57', '2025-08-10 11:46:27', 0),
(7, 'Sitero SHS SAMAFIL-Samahan ng mga Mag-aaral sa Filipino', 15, '\"Samahan ng mga Mag-aaral sa Filipino.\"', 'academic', 1, 'club-logos/RSlL7clxjxyRNvoXMo5YHHWrw5JFLuj2vgljrAzn.jpg', 'club-banners/lWgp51Kmmntt3edu50RssCqbf17ObnvGN6t7ckGZ.jpg', '2025-03-30 09:05:03', '2025-08-10 11:46:27', 0),
(8, 'Sitero Francisco Memorial National High School Guidance Office', 16, 'Mabuhay!\r\n\r\nTara sa Sitero Francisco Memorial National High School Virtual Guidance Center!\r\nDito walang iwanan, lahat tayo magtutulungan-- mag-aaral, guro, at magulang... Ano man ang sa inyong isip ay bumabagabag, sabihin lang, at ating pag-usapan...', 'academic', 1, 'club-logos/lLcROt8zOcdVgL6t4joVTHWIORvhuHDLLeobUZHp.jpg', 'club-banners/QtxTIDuxgMwiJWjSxLw0H4SKFdxPfyd9fV7qTdRH.jpg', '2025-03-30 09:07:21', '2025-08-10 11:46:27', 0),
(9, 'Altiora Peto/Ang Antipara', 17, 'Altiora Peto/Ang Antipara is the official social media outlet of the student publications in English and Filipino of Sitero Francisco Memorial National High School in Ugong, Valenzuela City.', 'academic', 1, 'club-logos/cuIenPBVJUBS2wL3ZRN5hrEf6hgp1RBUzw5ZOT5a.jpg', 'club-banners/PQRkWYesL6qpuReLr7xtbTF5IK3tJnf66FXVy7R1.png', '2025-03-30 09:12:37', '2025-08-10 11:46:27', 0),
(10, 'SFMNHS - Boy Scouts of the Philippines', 18, 'We encourage you to join the growing family of Siterian Scouts! Are you interested in fun adventures? Meeting new friends? Exploring the world? Well, you can do all of that when you join the Boy Scouts of the Philippines! \r\nSo what are you waiting for? Come and join our family now!', 'academic', 1, 'club-logos/LODcxMm3p6uUv0PCWENhuA7xswjZfMzyoXEOwwCO.jpg', 'club-banners/vXGOEQ7yol9B3yfVD6DY7MR2voGdkMXcjFKqql2d.jpg', '2025-03-30 09:16:57', '2025-08-10 11:46:27', 0),
(11, 'Sitero Youth Integrity Fighters', 19, 'Sitero Youth Integrity Fighters is a school-based organization since 2015 of CIC recognized by a sec.', 'academic', 1, 'club-logos/5GDTVMUu1WCPVwyzZHvveNfrTReZQU2FhwfUb2I3.jpg', 'club-banners/MGiZKBsrnbFhi0Kl7tIC98jRqGsv11d9ACb4ZyEy.jpg', '2025-03-30 09:23:05', '2025-08-10 11:46:27', 0),
(24, 'CLICK - SFMNHS The English Club', 2, 'CLICK – The English Club of Sitero Francisco MNHS is a vibrant community of learners who are passionate about mastering the English language through creative expression, critical thinking, and cultural appreciation. The club aims to enhance students’ communication skills in reading, writing, speaking, and listening while fostering confidence, creativity, and collaboration.', 'academic', 1, 'club-logos/403fMhJyGlf4siqP7LrUjxh7mGv3i54caadj4TYs.png', 'club-banners/7qfCuHbGOGg8KCRuzfWhE51Mr72goPql8cK51flz.jpg', '2025-05-22 07:44:24', '2025-08-10 11:46:27', 0),
(25, 'Panitikang Siterians', 51, NULL, 'academic', 1, 'club-logos/BStk0AFjV7etyxkTH5zMsFfvjdihaTdPGYqhAkNZ.jpg', 'club-banners/qGNVlNFCsh9LIIu7VvHF9PAE8FXtciJf4HIl7gQL.png', '2025-05-22 08:20:38', '2025-08-10 11:46:27', 0),
(26, 'Values Education Club', 44, NULL, 'academic', 1, 'club-logos/WyKl80DciaFo1ZIoVcYcAbKhxBWjGj68PfjT4xEG.jpg', 'club-banners/qaQt86UEZNrM2wIumSiHOHO7yVPVpXVPbEcrezXb.jpg', '2025-05-22 08:43:29', '2025-08-10 11:46:27', 0),
(27, 'SFMNHS-Mental Health & Psycho-Social Support & Services', 49, NULL, 'academic', 1, 'club-logos/LhMbEBcIzkao8R85bEVeYF9efbQ1aUbydaKu03IA.jpg', 'club-banners/4dclVzsgp4qrixiiQXHzs17JVuHHpOwdsjM6RzVy.jpg', '2025-05-22 09:06:06', '2025-08-10 11:46:27', 0),
(28, 'SFMNHS- GIRL SCOUT UNIT', 52, NULL, 'academic', 1, 'club-logos/9Dy4McUI5jMNqbniNJF4oKlEanmKJduJf9GwtfYy.jpg', 'club-banners/QhFSvRL5jC3UZ8xXYI6IywAskrSyiZO8wVeQCKa6.jpg', '2025-05-22 09:23:27', '2025-08-10 11:46:27', 0),
(29, 'STAGE-SFMNHS Theater Artists Guild of Enhancement', 50, NULL, 'academic', 1, 'club-logos/2xVITle47b2bQqsSjBoWIQQxtcOrbotrsIvKccdU.jpg', 'club-banners/1xkfjC9Fn5cBxokLvR9JxYfboNjWZ3zfXCZ66xly.png', '2025-05-22 09:36:19', '2025-08-10 11:46:27', 0),
(30, 'SFMNHS TLE CLUB', 18, 'No description available', 'academic', 1, 'club-logos/ngvKzAWOBAQYiwpgNeU46Db2f5x1JQTxDppYaYSu.jpg', 'club-banners/0ai4CfvoCNmtqLXsAgx9EOizvUUYA7e147TlyJaU.png', '2025-05-22 09:49:20', '2025-08-10 11:46:27', 0),
(31, 'MAPEH CLUB', 50, 'For Learning and acknowledging the students achievements in MAPEH.', 'academic', 1, 'club-logos/sjaSTCGRFQ9suWJXVqXHhMBldIkvnDEfFw6BJQBz.jpg', 'club-banners/GnnmkMQfO0oLmYqqfHWkfBb9ymtyNKblcMkFcomZ.png', '2025-05-22 10:01:51', '2025-08-10 11:46:27', 0),
(34, 'VITS', 76, 'This is an IT organization.', 'academic', 1, 'club-logos/8Ezh9jmdOhV2PUtUTkz2F9BNurYIiGnlYp3l1uEe.jpg', 'club-banners/h9dBFjNcWqWKuBV84ChzEkZBqAGr6hE6oforMTxs.jpg', '2025-05-23 02:05:55', '2025-08-10 11:46:27', 0),
(36, 'TEST CLUB APPROVAL', 80, 'TEST CLUB APPROVLA FEATURES', 'sports', 1, 'club-logos/1LUNzQbPFDZ0PPH815ZquJzTmMo5HOzYdCYW3Sxc.jpg', 'club-banners/pxXHStgCZxyBpZJXbJ9bUulXkrFNiomhI45JJ0IB.jpg', '2025-08-10 11:26:13', '2025-08-10 11:46:27', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_club_join_requests`
--

CREATE TABLE `tbl_club_join_requests` (
  `request_id` bigint(20) UNSIGNED NOT NULL,
  `club_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_club_join_requests`
--

INSERT INTO `tbl_club_join_requests` (`request_id`, `club_id`, `user_id`, `status`, `message`, `created_at`, `updated_at`) VALUES
(5, 36, 58, 'approved', 'Join request for TEST CLUB APPROVAL', '2025-08-10 11:44:23', '2025-08-10 11:44:48');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_club_membership`
--

CREATE TABLE `tbl_club_membership` (
  `membership_id` bigint(20) UNSIGNED NOT NULL,
  `club_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `club_role` enum('ADVISER','MEMBER') NOT NULL,
  `club_position` text DEFAULT NULL,
  `joined_date` datetime NOT NULL,
  `club_accessibility` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`club_accessibility`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_club_membership`
--

INSERT INTO `tbl_club_membership` (`membership_id`, `club_id`, `user_id`, `club_role`, `club_position`, `joined_date`, `club_accessibility`, `created_at`, `updated_at`) VALUES
(3, 1, 34, 'MEMBER', NULL, '2025-04-05 13:27:48', '{\"manage_posts\":false,\"manage_events\":false}', '2025-04-05 05:27:48', '2025-04-05 05:27:48'),
(4, 1, 35, 'MEMBER', NULL, '2025-04-05 23:15:08', '{\"manage_posts\":false,\"manage_events\":false}', '2025-04-05 15:15:08', '2025-04-09 15:32:46'),
(5, 3, 34, 'MEMBER', NULL, '2025-04-06 02:01:01', '{\"manage_posts\":false,\"manage_events\":false}', '2025-04-05 18:01:01', '2025-04-05 18:01:01'),
(6, 9, 35, 'MEMBER', NULL, '2025-04-06 02:27:32', '{\"manage_posts\":false,\"manage_events\":false}', '2025-04-05 18:27:32', '2025-04-05 18:27:32'),
(7, 3, 35, 'MEMBER', NULL, '2025-04-06 02:27:58', '{\"manage_posts\":false,\"manage_events\":false}', '2025-04-05 18:27:58', '2025-04-05 18:27:58'),
(8, 7, 34, 'MEMBER', NULL, '2025-04-06 14:35:05', '{\"manage_posts\":false,\"manage_events\":false}', '2025-04-06 06:35:05', '2025-04-06 06:35:05'),
(9, 3, 21, 'MEMBER', 'President', '2025-04-06 14:39:56', '{\"manage_posts\":true,\"manage_events\":true}', '2025-04-06 06:39:56', '2025-05-20 16:19:37'),
(10, 1, 23, 'MEMBER', 'Vice President', '2025-04-06 14:40:41', '{\"manage_posts\":true,\"manage_events\":true}', '2025-04-06 06:40:41', '2025-04-07 20:24:08'),
(11, 1, 22, 'MEMBER', 'President', '2025-04-07 14:37:06', '{\"manage_posts\":true,\"manage_events\":true}', '2025-04-07 06:37:06', '2025-05-16 09:12:33'),
(12, 1, 37, 'MEMBER', 'Secretary', '2025-04-07 14:48:02', '{\"manage_posts\":true,\"manage_events\":true}', '2025-04-07 06:48:02', '2025-04-07 20:23:51'),
(13, 3, 37, 'MEMBER', NULL, '2025-04-07 14:48:42', '{\"manage_posts\":false,\"manage_events\":false}', '2025-04-07 06:48:42', '2025-04-07 06:48:42'),
(14, 8, 22, 'MEMBER', NULL, '2025-04-08 05:42:14', '{\"manage_posts\":false,\"manage_events\":false}', '2025-04-07 21:42:14', '2025-04-07 21:42:14'),
(17, 1, 6, 'MEMBER', 'Auditor', '2025-04-08 10:53:23', '{\"manage_posts\":true,\"manage_events\":true}', '2025-04-08 02:53:23', '2025-05-22 18:54:20'),
(22, 3, 22, 'MEMBER', NULL, '2025-04-11 23:48:30', '{\"manage_posts\":true,\"manage_events\":true}', '2025-04-11 15:48:30', '2025-04-11 15:48:30'),
(23, 8, 37, 'MEMBER', NULL, '2025-04-12 15:43:35', NULL, '2025-04-12 07:43:35', '2025-04-12 07:43:35'),
(24, 1, 43, 'MEMBER', NULL, '2025-04-14 02:34:08', NULL, '2025-04-13 18:34:08', '2025-04-13 18:34:08'),
(25, 1, 45, 'MEMBER', NULL, '2025-04-14 03:04:36', NULL, '2025-04-13 19:04:36', '2025-04-13 19:04:36'),
(26, 1, 46, 'MEMBER', NULL, '2025-04-14 03:04:36', NULL, '2025-04-13 19:04:36', '2025-04-13 19:04:36'),
(27, 1, 47, 'MEMBER', NULL, '2025-04-14 03:04:36', NULL, '2025-04-13 19:04:36', '2025-04-13 19:04:36'),
(28, 1, 53, 'MEMBER', NULL, '2025-04-14 03:06:34', NULL, '2025-04-13 19:06:34', '2025-04-13 19:06:34'),
(29, 9, 22, 'MEMBER', NULL, '2025-04-15 02:24:26', '{\"manage_posts\":true,\"manage_events\":true}', '2025-04-14 18:24:26', '2025-04-14 18:24:26'),
(34, 7, 22, 'MEMBER', NULL, '2025-05-19 12:20:26', NULL, '2025-05-19 04:20:26', '2025-05-19 04:20:26'),
(35, 1, 58, 'MEMBER', NULL, '2025-05-22 16:25:21', NULL, '2025-05-22 08:25:21', '2025-05-22 08:25:21'),
(36, 25, 58, 'MEMBER', 'Secretary', '2025-05-22 16:27:08', '{\"manage_posts\":true,\"manage_events\":true}', '2025-05-22 08:27:08', '2025-05-22 08:27:20'),
(37, 1, 59, 'MEMBER', NULL, '2025-05-22 16:28:57', NULL, '2025-05-22 08:28:57', '2025-05-22 08:28:57'),
(38, 25, 59, 'MEMBER', 'Auditor', '2025-05-22 16:29:15', '{\"manage_posts\":true,\"manage_events\":false}', '2025-05-22 08:29:15', '2025-05-22 08:30:03'),
(39, 1, 60, 'MEMBER', NULL, '2025-05-22 16:44:07', NULL, '2025-05-22 08:44:07', '2025-05-22 08:44:07'),
(40, 1, 61, 'MEMBER', NULL, '2025-05-22 16:44:40', '{\"manage_posts\":false,\"manage_events\":false}', '2025-05-22 08:44:40', '2025-05-22 19:35:17'),
(41, 26, 60, 'MEMBER', 'PIO', '2025-05-22 16:45:30', '{\"manage_posts\":true,\"manage_events\":true}', '2025-05-22 08:45:30', '2025-05-22 08:48:01'),
(42, 26, 61, 'MEMBER', NULL, '2025-05-22 16:47:52', NULL, '2025-05-22 08:47:52', '2025-05-22 08:47:52'),
(43, 24, 60, 'MEMBER', NULL, '2025-05-22 17:13:11', NULL, '2025-05-22 09:13:11', '2025-05-22 09:13:11'),
(44, 1, 8, 'MEMBER', NULL, '2025-05-22 18:16:35', NULL, '2025-05-22 10:16:35', '2025-05-22 10:16:35'),
(45, 10, 58, 'MEMBER', NULL, '2025-05-22 20:08:14', NULL, '2025-05-22 12:08:14', '2025-05-22 12:08:14'),
(47, 1, 62, 'MEMBER', 'Treasurer', '2025-05-23 03:21:26', '{\"manage_posts\":true,\"manage_events\":true}', '2025-05-22 19:21:26', '2025-05-22 21:53:59'),
(49, 1, 64, 'MEMBER', NULL, '2025-05-23 10:02:35', NULL, '2025-05-23 02:02:35', '2025-05-23 02:02:35'),
(50, 1, 65, 'MEMBER', NULL, '2025-05-23 10:02:35', NULL, '2025-05-23 02:02:35', '2025-05-23 02:02:35'),
(51, 1, 66, 'MEMBER', NULL, '2025-05-23 10:02:35', NULL, '2025-05-23 02:02:35', '2025-05-23 02:02:35'),
(52, 1, 67, 'MEMBER', NULL, '2025-05-23 10:02:35', NULL, '2025-05-23 02:02:35', '2025-05-23 02:02:35'),
(53, 1, 68, 'MEMBER', NULL, '2025-05-23 10:02:35', NULL, '2025-05-23 02:02:35', '2025-05-23 02:02:35'),
(54, 1, 69, 'MEMBER', NULL, '2025-05-23 10:02:36', NULL, '2025-05-23 02:02:36', '2025-05-23 02:02:36'),
(55, 34, 6, 'MEMBER', 'President', '2025-05-23 10:11:11', '{\"manage_posts\":true,\"manage_events\":true}', '2025-05-23 02:11:11', '2025-05-23 02:14:30'),
(56, 34, 34, 'MEMBER', NULL, '2025-05-23 10:11:11', NULL, '2025-05-23 02:11:11', '2025-05-23 02:11:11'),
(57, 7, 6, 'MEMBER', NULL, '2025-05-23 10:16:09', NULL, '2025-05-23 02:16:09', '2025-05-23 02:16:09'),
(59, 36, 58, 'MEMBER', NULL, '2025-08-10 19:44:48', NULL, '2025-08-10 11:44:48', '2025-08-10 11:44:48');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_elections`
--

CREATE TABLE `tbl_elections` (
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `club_id` bigint(20) UNSIGNED NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_elections`
--

INSERT INTO `tbl_elections` (`election_id`, `club_id`, `is_published`, `title`, `description`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(10, 1, 1, 'SSLG 2025 Election', 'The Supreme Secondary Learner Government (SSLG) 2025 Election is a student-led democratic process that empowers learners to choose their next set of student leaders. Through this election, students have the opportunity to practice their right to vote, promote leadership, and uphold the values of responsibility and participation within the school community.\r\n\r\nLet your voice be heard. Vote wisely. Lead the change!', '2025-05-23 05:16:49', '2025-05-30 00:00:00', '2025-05-22 21:16:49', '2025-05-23 02:19:22'),
(11, 1, 1, 'asdasd', 'asdasd', '2025-08-10 19:09:10', '2025-08-12 00:00:00', '2025-08-10 11:09:10', '2025-08-10 11:11:48');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_events`
--

CREATE TABLE `tbl_events` (
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_description` text DEFAULT NULL,
  `club_id` bigint(20) UNSIGNED NOT NULL,
  `organizer_id` bigint(20) UNSIGNED NOT NULL,
  `event_date` date NOT NULL,
  `event_time` varchar(255) DEFAULT NULL,
  `event_location` varchar(255) DEFAULT NULL,
  `event_visibility` enum('PUBLIC','CLUB_ONLY') NOT NULL DEFAULT 'CLUB_ONLY',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_events`
--

INSERT INTO `tbl_events` (`event_id`, `event_name`, `event_description`, `club_id`, `organizer_id`, `event_date`, `event_time`, `event_location`, `event_visibility`, `created_at`, `updated_at`) VALUES
(6, 'Sitero Student Voice: Leadership and Governance Forum', '“Lead with Purpose, Serve with Integrity\"\r\n\r\nA half-day forum where student leaders and class officers gather to discuss school-wide issues, propose solutions, and present action plans. Sessions include leadership workshops, open forums, and planning for student-led initiatives.', 1, 4, '2025-05-23', '8am to 9pm onwards', 'Sitero Francisco Memorial National High School', 'CLUB_ONLY', '2025-05-21 02:14:29', '2025-05-21 02:14:29'),
(8, 'Storytelling Contest', 'The STORYTELLING event is a celebration of creativity, expression, and the timeless art of telling tales. This event invites students from various grade levels to step into the spotlight and bring stories to life.', 24, 2, '2025-05-23', '1:00 PM', 'SFMNHS AVR', 'CLUB_ONLY', '2025-05-22 07:56:50', '2025-05-22 07:56:50'),
(9, 'Poetry Unplugged: A Spoken Word Experience', 'Poetry Unplugged is an open mic-style spoken word event that gives students a platform to share their voices through original poems, dramatic monologues, and creative expressions.', 24, 2, '2025-05-26', '10 am onwards', 'SFMNHS AVR', 'CLUB_ONLY', '2025-05-22 07:59:17', '2025-05-22 07:59:45'),
(10, 'Impromptu Speech: Unscripted Voices', 'Unscripted Voices is an impromptu speech event that empowers Grade 9 learners to speak their minds on relevant and meaningful topics without a prepared script.', 24, 2, '2025-05-27', '10:00-11:30 am', 'JHS Computer Lab', 'PUBLIC', '2025-05-22 08:01:25', '2025-05-22 08:01:40'),
(11, '2024 English and National Reading Month Celebration', NULL, 24, 2, '2025-05-28', NULL, 'School Activity Center', 'CLUB_ONLY', '2025-05-22 08:16:51', '2025-05-22 08:16:51'),
(12, 'Culminating Activity', NULL, 25, 58, '2025-08-27', '1:00 pm', 'Covered Court', 'CLUB_ONLY', '2025-05-22 08:33:09', '2025-05-22 08:33:19'),
(13, 'Bible Month', NULL, 26, 60, '2025-05-30', NULL, 'Activity Center', 'CLUB_ONLY', '2025-05-22 08:54:25', '2025-05-22 08:54:25'),
(14, 'Filipino Values Month', NULL, 26, 60, '2025-05-29', '10:00 am', 'Activity Center', 'PUBLIC', '2025-05-22 08:55:45', '2025-05-22 08:55:45'),
(15, 'Releasing of Cards', NULL, 1, 4, '2025-06-02', NULL, 'SFMNHS AVR', 'PUBLIC', '2025-05-22 08:58:51', '2025-05-22 08:58:51'),
(16, 'Mental Health Awareness Month', NULL, 27, 49, '2025-10-09', NULL, 'SFMNHS AVR', 'PUBLIC', '2025-05-22 09:10:10', '2025-05-22 09:10:10'),
(17, 'GSP HOLIDAY BADGE FAIR', 'BADGE FAIR', 28, 52, '2025-05-30', '8am onwards', 'WES ARENA', 'CLUB_ONLY', '2025-05-22 09:32:12', '2025-05-22 09:32:12'),
(18, 'GIRL SCOUT WEEK', '\"Women of Today for the Girls of Tomorrow\"\r\nSitero Francisco Memorial National High School', 28, 52, '2025-05-23', '10:00-11:30 am', 'Sitero Francisco Memorial High School', 'CLUB_ONLY', '2025-05-22 09:33:58', '2025-05-22 09:33:58'),
(19, 'Participation of Sitero Francisco MNHS - Senior High Theater Arts Guild of Enhancement (𝗦𝗧𝗔𝗚𝗘) in SK Ugong\'s Linggo ng Kabataan', '\"𝘼𝙣𝙜 𝙠𝙖𝙗𝙖𝙩𝙖𝙖𝙣 𝙖𝙣𝙜 𝙥𝙖𝙜-𝙖𝙨𝙖 𝙣𝙜 𝙗𝙖𝙮𝙖𝙣\"\r\n-Dr. Jose Rizal\r\nThe 𝑳𝒊𝒏𝒈𝒈𝒐 𝒏𝒈 𝒌𝒂𝒃𝒂𝒕𝒂𝒂𝒏 program celebrates the brilliance and passion of the youth, recognizing excellence in various fields and unwavering dedication as scholars of Ugong.🏅\r\nSection 30 of Republic Act No. 10742, the Sanggunian Kabataan Reform Act of 2015 mandates the observance of Linggo ng Kabataan in very barangay, municipality, city and province on the week where the 12th of August falls to coincide with the International Youth Day (IYD).', 29, 50, '2025-05-26', '8am onwards', '3S UGONG', 'CLUB_ONLY', '2025-05-22 09:45:49', '2025-05-22 09:45:49'),
(20, '\"Building Solid Foundations for Future Champions in Skilled Professions!\"', 'The Olympics are designed to inspire and nurture practical skills in our students, setting them up for amazing careers. 🚀 The day began with a burst of fun—a cheerful parade around the school with our talented students and enthusiastic TLE teachers! 🎶🚶‍♀️🚶‍♂️\r\nAnd the best part?  The launch also featured a thrilling cooking competition! 🧑‍🍳🔥 Our incredible Grade 9 and 10 cookery students showcased their amazing culinary skills in the activity center, preparing, presenting, and serving their delicious creations to the judges. 🍽️😋 It\'s been a fantastic success so far, and the Olympics wrap up tomorrow, July 13th.  We can\'t wait to see what happens next! 🤩\r\nSo Siterians, get ready because this is just the beginning of a month full of learnings and wonderful events for the School-Based Skill Olympics 2025!  🥳👏🏆  Let\'s make it a memorable one!', 30, 8, '2025-07-13', '9am onwards', 'Sitero Francisco Memorial High School', 'CLUB_ONLY', '2025-05-22 09:55:13', '2025-05-22 09:56:09'),
(21, 'Groove Siterians: The Ultimate Hip-Hop Dance Battle!', 'As part of Ani ng Sining: Diwa at Damdamin, we are thrilled to present the ultimate showdown for all Grade 10 students—Groove Siterians: The Ultimate Hip-Hop Dance Battle! 🏆🔥\r\nThis exciting event will be the highlight of your Grade 10 culminating activity, where you’ll have the chance to showcase your hip-hop skills, creativity, and passion for dance.\r\nSee you there!', 31, 8, '2025-09-02', '10 am onwards', 'Sitero Francisco Memorial High School', 'CLUB_ONLY', '2025-05-22 10:08:04', '2025-05-22 10:08:04'),
(23, 'Sitero Guidance Meeting', 'Meeting at Guidance Office', 34, 76, '2025-05-24', '8am onwards', 'Sitero Guidance Office', 'PUBLIC', '2025-05-23 02:10:26', '2025-05-23 02:10:26');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_posts`
--

CREATE TABLE `tbl_posts` (
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `post_caption` text NOT NULL,
  `club_id` bigint(20) UNSIGNED NOT NULL,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `post_visibility` enum('PUBLIC','CLUB_ONLY') NOT NULL DEFAULT 'CLUB_ONLY',
  `post_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_posts`
--

INSERT INTO `tbl_posts` (`post_id`, `post_caption`, `club_id`, `author_id`, `post_visibility`, `post_date`, `created_at`, `updated_at`) VALUES
(1, '📢 Greetings SSLG family!\r\nI am proud to witness the growth of our young leaders as they take initiative in planning our upcoming community outreach and student empowerment programs. Your passion and leadership truly shape a better school environment. Keep inspiring! 💪\r\n#SFLG #StudentLeaders #ServeAndLead', 1, 4, 'CLUB_ONLY', '2025-05-19 09:41:28', '2025-05-19 01:41:28', '2025-05-19 01:41:28'),
(2, 'Leading with purpose, serving with heart! 💛\r\nOur team is currently organizing projects that will make a difference in our school and beyond. Being part of the SFLG has taught me the value of service and teamwork. Let’s go, SSLG! 🙌\r\n#YouthLeadership #SSLG2025', 1, 22, 'CLUB_ONLY', '2025-05-19 09:41:49', '2025-05-19 01:41:49', '2025-05-21 04:25:24'),
(4, '🏀 Shoutout to our GILAS athletes for their unwavering discipline and sportsmanship during training. As your adviser, I’m beyond proud of the determination you show on and off the court. Let\'s keep aiming higher—one game, one goal!\r\n#GILASStrong #TeamWorkMakesTheDreamWork', 3, 12, 'CLUB_ONLY', '2025-05-21 00:18:38', '2025-05-20 16:18:38', '2025-05-20 16:19:07'),
(5, 'Just finished an intense training session with the GILAS fam! 💪\r\nIt’s not just about basketball—it’s about grit, discipline, and family. Can’t wait to show what we’ve got this season!\r\n#GILASSHS #HardworkPaysOff #StudentAthletes', 3, 21, 'CLUB_ONLY', '2025-05-21 00:26:04', '2025-05-20 16:26:04', '2025-05-20 16:26:04'),
(6, '📚 Isang pagbati ng pagmamalaki sa ating mga kasapi ng SAMAFIL!\r\nPatuloy ninyong pinagyayaman ang ating kultura at wika sa pamamagitan ng inyong malikhaing proyekto. Nawa’y magpatuloy ang inyong pagyakap sa pagiging makabayan at mapanlikha.\r\n#SAMAFIL #WikangFilipino #SiningAtKultura', 7, 15, 'CLUB_ONLY', '2025-05-21 00:57:56', '2025-05-20 16:57:56', '2025-05-20 16:57:56'),
(7, '✒️ To all members of Altiora Peto and Ang Antipara, your creative minds and sharp voices are what fuel our campus journalism. Continue to seek truth and write with integrity. Your words matter!\r\n#CampusJournalism #AltioraPeto #AngAntipara #VoiceOfTheYouth', 9, 17, 'CLUB_ONLY', '2025-05-21 01:00:09', '2025-05-20 17:00:09', '2025-05-20 17:00:09'),
(8, '🏕️ Salute to our Boy Scouts! Your courage, discipline, and commitment during drills and leadership tasks are commendable. You are living examples of preparedness and service. Keep the Scouting spirit alive!\r\n#ScoutingForLife #BSP #PreparedToServe', 10, 18, 'CLUB_ONLY', '2025-05-21 01:00:40', '2025-05-20 17:00:40', '2025-05-20 17:00:40'),
(9, '💡 Integrity is doing the right thing, even when no one is watching.\r\nTo our Sitero Youth Integrity Fighters, thank you for being role models of honesty and ethical leadership in school. Keep the light of integrity burning.\r\n#IntegrityMatters #YouthWithPurpose #SiteroIntegrityFighters', 11, 19, 'CLUB_ONLY', '2025-05-21 01:01:05', '2025-05-20 17:01:05', '2025-05-20 17:01:05'),
(18, 'Siterians, get ready to witness the Grade 10 students as they perform a powerful rendition of the piece \"1896\" by Aurelio S. Alvero on the elimination round of Conventional Speech Choir Competition this December 2, Monday, 10:30am at the SFMNHS Activity Center. Let us listen to their voices unite in harmony, bringing drama, rhythm and creativity to life. 🗣️🗣️', 24, 2, 'PUBLIC', '2025-05-22 16:02:59', '2025-05-22 08:02:59', '2025-05-22 08:02:59'),
(19, '\"𝙈𝙖𝙠𝙖𝙠𝙖𝙢𝙞𝙩 𝙣𝙖𝙩𝙞𝙣 𝙖𝙣𝙜 𝙠𝙖𝙡𝙖𝙮𝙖𝙖𝙣 𝙨𝙖 𝙥𝙖𝙜𝙞𝙜𝙞𝙣𝙜 𝙠𝙖𝙧𝙖𝙥𝙖𝙩-𝙙𝙖𝙥𝙖𝙩 𝙙𝙞𝙩𝙤, 𝙨𝙖 𝙥𝙖𝙜𝙩𝙖𝙖𝙨 𝙣𝙜 𝙠𝙖𝙩𝙪𝙬𝙞𝙧𝙖𝙣 𝙖𝙩 𝙙𝙖𝙣𝙜𝙖𝙡 𝙣𝙜 𝙩𝙖𝙤, 𝙨𝙖 𝙥𝙖𝙜𝙢𝙖𝙢𝙖𝙝𝙖𝙡 𝙨𝙖 𝙢𝙖𝙠𝙖𝙩𝙖𝙧𝙪𝙣𝙜𝙖𝙣, 𝙨𝙖 𝙢𝙖𝙗𝙪𝙩𝙞, 𝙨𝙖 𝙙𝙖𝙠𝙞𝙡𝙖, 𝙠𝙖𝙝𝙞𝙩 𝙢𝙖𝙢𝙖𝙩𝙖𝙮 𝙖𝙡𝙖𝙣𝙜-𝙖𝙡𝙖𝙣𝙜 𝙙𝙞𝙩𝙤.\"\r\n- Padre Tolentino, mula sa El Filibusterismo ni Dr. Jose Rizal \r\n\r\nNgayong buwan ng Agosto ipinagdiriwang ng buong bansa ang National heroes day hindi upang magsaya kundi para gunitahin ang mga bagay na ginawa ng ating bayani para makamtan  ang tunay na kalayaan ng bansa. \r\n\r\nNgayong ika-26 ng Agosto ay ating ipinagdiriwang ang araw ng mga bayani sa ating bansa. Ito ay ating ginugunita upang alalahanin ang mga sakripisyo ng mga bayaning Pilipino sa pagkamit ng kalayaan, hustisya, at pagkakakilanlan ng bansang pilipinas. \r\nMagkaisa tayo sa paaralan sa pag-alala sa kanilang kadakilaan!\r\nMaligayang Araw ng mga Bayani!\r\n\r\n𝙇𝙖𝙮𝙤𝙪𝙩: Amir Yman Corpur | 𝙆𝙖𝙡𝙞𝙝𝙞𝙢\r\n𝘾𝙖𝙥𝙩𝙞𝙤𝙣: Trisha Ramos | 𝙏𝙖𝙜𝙖𝙨𝙪𝙧𝙞 at Amir Yman Corpuz | 𝙆𝙖𝙡𝙞𝙝𝙞𝙢', 25, 58, 'PUBLIC', '2025-05-22 16:31:34', '2025-05-22 08:31:34', '2025-05-22 08:31:34'),
(20, '𝐏𝐈𝐍𝐀𝐍𝐃𝐀𝐘 𝐒𝐀 𝐀𝐏𝐎𝐘 🔥\r\nNarito ang mga bagong opisyales ng Filipino Club na Pinanday sa apoy para maglingkod. Dumaan man sa maraming pagsubok at paghihirap patuloy kaming magiging instrumento para mapatupad  ang mga programang na sainyo ay dapat ilatag. Kami ay may layunin na ipalaganap na ang Wikang Filipino ay bahagi ng ating pagkakakilanlan bilang mga Pilipino. At ngayong buwan ng Wika na may temang \"Filipino:𝚆𝚒𝚔𝚊𝚗𝚐 𝙼𝚊𝚙𝚊𝚐𝚙𝚊𝚕𝚊𝚢𝚊\"  abangan pa ang mga kapanapanabik na mga programa.\r\nAsahan na patuloy  kaming maglilingkod para sa bayan, sa wika at sa Siterians bilang mga bagong opisiyales ng Filipino Club. \r\nKami ay nag iiwan ng katagang \"𝑊𝑖𝑘𝑎𝑛𝑔 𝐹𝑖𝑙𝑖𝑝𝑖𝑛𝑜, 𝑇𝑖𝑛𝑖𝑔 𝑛𝑔 𝐵𝑎𝑦𝑎𝑛, 𝑇𝑢𝑛𝑔𝑜 𝑠𝑎 𝐾𝑎𝑙𝑎𝑦𝑎𝑎𝑛. \"\r\n\r\n𝙇𝙖𝙮𝙤𝙪𝙩 : Amir Yman Corpuz | 𝙆𝙖𝙡𝙞𝙝𝙞𝙢\r\n𝘾𝙖𝙥𝙩𝙞𝙤𝙣 : Joshua Raymundo | 𝙋𝙧𝙚𝙨𝙞𝙙𝙚𝙣𝙩𝙚', 25, 51, 'PUBLIC', '2025-05-22 16:37:53', '2025-05-22 08:37:53', '2025-05-22 08:38:19'),
(21, '\"The fear of the Lord doesn’t necessarily mean that you should be afraid of God. What it means is that we should live our lives in awe of Him. We do this first by recognizing who He is. He is the creator and source of all things. He is all-powerful and all-knowledgeable. God’s Word says He holds the power of life and death in His hands. These are certainly attributes that should cause us to be in awe of God.\"\r\n- YouVersion Bible App', 26, 44, 'PUBLIC', '2025-05-22 16:48:42', '2025-05-22 08:48:42', '2025-05-22 08:48:57'),
(22, '\"We all have moments when we feel like the wandering sheep. Sometimes, we feel like we’re straying off the path. But remember this: you are not forgotten, and you matter to Jesus. He cares for the one who has strayed just as much as He does the ninety-nine who stayed close (Matthew 18:13). So draw near to Him today. \r\nBecause no matter where you are on your spiritual journey, Jesus is seeking after you, calling you by name to not only follow Him but to be with Him.\"\r\n- YouVersion Bible App', 26, 44, 'PUBLIC', '2025-05-22 16:49:41', '2025-05-22 08:49:41', '2025-05-22 08:49:41'),
(23, '\"Jesus isn\'t just asking us to audibly hear His words and carry on with our personal agenda; He\'s urging us to actively listen and obey, to live by His truth. Listening and obeying are what build our faith in Jesus Christ. Hearing the Word of God should lead to a transformed life marked by fruitfulness.\"\r\n-YouvVersion Bible App', 26, 44, 'PUBLIC', '2025-05-22 16:50:08', '2025-05-22 08:50:08', '2025-05-22 08:50:08'),
(24, 'TO BE ANNOUNCED\r\nEnrollment for incoming GR 7, GR 11, Transferees & Balik aral\r\nFor more updates, visit the official FB page', 1, 4, 'PUBLIC', '2025-05-22 16:59:47', '2025-05-22 08:59:47', '2025-05-22 08:59:47'),
(25, 'Think Like A Farmer 🙂', 27, 49, 'PUBLIC', '2025-05-22 17:11:12', '2025-05-22 09:11:12', '2025-05-22 09:11:12'),
(26, 'Sama sama tayo maglaan ng oras para manalangin.', 27, 49, 'PUBLIC', '2025-05-22 17:12:13', '2025-05-22 09:12:13', '2025-05-22 09:12:13'),
(27, '☘️GSP Sunrise Parade & \r\nGSP Investiture and Rededication Ceremony☘️\r\nNovember 26, 2024', 28, 52, 'CLUB_ONLY', '2025-05-22 17:26:50', '2025-05-22 09:26:50', '2025-05-22 09:26:50'),
(28, '☘️GSP Sunrise Parade & \r\nGSP Investiture and Rededication Ceremony☘️\r\nNovember 26, 2024', 28, 52, 'CLUB_ONLY', '2025-05-22 17:27:37', '2025-05-22 09:27:37', '2025-05-22 09:27:37'),
(29, 'Alay Lakad para sa Kabataan ng Bagong Pilipinas\r\nNovember 24, 2024\r\nQuirino Grandstand, Manila\r\nReflecting the Foundation\'s commitment to empowering the youth and building the brighter future for the nation. \r\nThis Walk-For-A-Cause activity aims to raise fund to help and support the full economic and social well-being of Out-Of- School-Youth nationwide.', 28, 52, 'CLUB_ONLY', '2025-05-22 17:28:26', '2025-05-22 09:28:26', '2025-05-22 09:28:26'),
(30, '\"Caring for the Environment is in the Heart of Every Girl Scout\"\r\nAs Girl Scouts, we honor our planet by respecting all living things and practicing thriftiness. Let us unite to protect nature, make mindful choices, and lead by example for a sustainable life.', 28, 52, 'CLUB_ONLY', '2025-05-22 17:29:17', '2025-05-22 09:29:17', '2025-05-22 09:29:17'),
(31, 'Participation of Sitero Francisco MNHS - Senior High Theater Arts Guild of Enhancement (𝗦𝗧𝗔𝗚𝗘) in SFMNHS\' 22nd Founding Anniversary', 29, 50, 'CLUB_ONLY', '2025-05-22 17:42:08', '2025-05-22 09:42:08', '2025-05-22 09:42:08'),
(32, 'Participation of Sitero Francisco MNHS - Senior High Theater Arts Guild of Enhancement (𝗦𝗧𝗔𝗚𝗘) in SFMNHS\' 22nd Founding Anniversary\r\n𝐒𝐢𝐭𝐞𝐫𝐨 𝐅𝐫𝐚𝐧𝐜𝐢𝐬𝐜𝐨 𝐌𝐞𝐦𝐨𝐫𝐢𝐚𝐥 𝐍𝐚𝐭𝐢𝐨𝐧𝐚𝐥 𝐇𝐢𝐠𝐡 𝐒𝐜𝐡𝐨𝐨𝐥 (𝐒𝐅𝐌𝐍𝐇𝐒) 𝐌𝐚𝐫𝐤𝐬 22 𝐘𝐞𝐚𝐫𝐬 𝐨𝐟 𝐄𝐱𝐜𝐞𝐥𝐥𝐞𝐧𝐜𝐞 𝐚𝐧𝐝 𝐂𝐨𝐦𝐦𝐮𝐧𝐢𝐭𝐲 𝐁𝐮𝐢𝐥𝐝𝐢𝐧𝐠\r\nRecently, Sitero Francisco Memorial National High School celebrated it\'s 22nd Founding Anniversary with the theme \"𝑆𝑖𝑡𝑒𝑟𝑜: 𝐷𝑎𝑙𝑎𝑤𝑎𝑚𝑝𝑢\'𝑡 𝐷𝑎𝑙𝑎𝑤𝑎𝑛𝑔 𝑇𝑎𝑜𝑛 𝑛𝑔 𝑃𝑎𝑔𝑡𝑢𝑡𝑢𝑙𝑢𝑛𝑔𝑎𝑛 𝑎𝑡 𝑇𝑎𝑔𝑢𝑚𝑝𝑎𝑦 𝑇𝑢𝑛𝑔𝑜 𝑠𝑎 𝑃𝑎𝑔-𝑎𝑎𝑙𝑎𝑏 𝑛𝑔 𝑘𝑎ℎ𝑢𝑠𝑎𝑦𝑎𝑛\" highlighting it\'s passion for education and harnessing the talents of the many generations of Siterians.\r\nFor over two decades, the school has stood as a beacon of hope, perseverance, and excellence in education, serving as a nurturing ground for generations of Siterians. Every achievement is a testament to the school\'s resilience, strong foundation, and the collaborative efforts of everyone who has walked its halls.\r\n✍🏻: Mikaela Chloe Valdellon | STAGE president\r\n💻:\r\nMikaela Chloe Valdellon | STAGE president\r\nMheday Chona Jonem | CMAC member\r\nArjune Ray Macaraeg | STAGE member', 29, 50, 'CLUB_ONLY', '2025-05-22 17:42:59', '2025-05-22 09:42:59', '2025-05-22 09:42:59'),
(36, '🎨✨ Ani ng Sining, Diwa at Damdamin! ✨🎶\r\nIpagdiwang natin ang National Arts Month sa pamamagitan ng pagpapakita ng ating talento at pagkamalikhain! 💡🎭 Sumali sa mga patimpalak na naghihintay sa inyo:\r\n🎨 Pintahusay – Ipakita ang galing sa pagpipinta!\r\n🖌 Poster Making – Ilabas ang sining sa makabuluhang obra!\r\n🎤 Siterian Voice – Ipadama ang diwa ng sining sa pamamagitan ng musika!\r\n🎬 Filmmaking – Ibahagi ang iyong kwento sa sining ng pelikula!\r\nHuwag palampasin ang pagkakataong ito upang ipahayag ang iyong damdamin sa pamamagitan ng sining! ✨🎭🎶', 31, 50, 'PUBLIC', '2025-05-22 18:19:39', '2025-05-22 10:19:39', '2025-05-22 10:19:39'),
(37, 'Avoid Being Hacked! 🛡️🚫💻\r\nIn today\'s hyper-connected world, our lives are deeply intertwined with technology and social media. This digital landscape, while offering incredible opportunities ✨, also presents significant risks, with hacking incidents on the rise. Do you feel confident that your online accounts are truly secure? 🤔 Let\'s explore some essential tips to safeguard yourself and your digital life! 💪\r\nBy following these tips and staying vigilant, you can significantly reduce your risk of becoming a victim of hacking. Let\'s work together to make the internet a safer place for everyone! Share this information with your friends and family to help spread awareness! 💖\r\n\r\nLayout: Paul Laquinta | Grade 8 P.I.O\r\nCaption: Rhayven Labaja | Vice President', 30, 18, 'CLUB_ONLY', '2025-05-22 18:21:22', '2025-05-22 10:21:22', '2025-05-22 10:21:22'),
(38, '📢 𝙋𝙍𝙊𝙋𝙀𝙍 𝙐𝙎𝙀 𝙊𝙁 𝘼𝙄 📢\r\nAI is a powerful tool, but it should 𝙚𝙣𝙝𝙖𝙣𝙘𝙚 𝙡𝙚𝙖𝙧𝙣𝙞𝙣𝙜, 𝙣𝙤𝙩 𝙧𝙚𝙥𝙡𝙖𝙘𝙚 𝙚𝙛𝙛𝙤𝙧𝙩! 🚀📖\r\nStudents, let\'s use AI 𝙬𝙞𝙨𝙚𝙡𝙮 𝙖𝙣𝙙 𝙧𝙚𝙨𝙥𝙤𝙣𝙨𝙞𝙗𝙡𝙮, as a guide, not as a shortcut! Keep learning, thinking critically, and putting in the effort to grow. Remember, 𝙩𝙚𝙘𝙝𝙣𝙤𝙡𝙤𝙜𝙮 𝙨𝙝𝙤𝙪𝙡𝙙 𝙨𝙪𝙥𝙥𝙤𝙧𝙩 𝙮𝙤𝙪𝙧 𝙚𝙙𝙪𝙘𝙖𝙩𝙞𝙤𝙣, 𝙣𝙤𝙩 𝙙𝙤 𝙩𝙝𝙚 𝙬𝙤𝙧𝙠 𝙛𝙤𝙧 𝙮𝙤𝙪!\r\nAnd of course, there are 𝙢𝙖𝙣𝙮 𝙤𝙩𝙝𝙚𝙧 𝙥𝙧𝙤𝙥𝙚𝙧 𝙬𝙖𝙮𝙨 𝙩𝙤 𝙪𝙨𝙚 𝘼𝙄! Use it correctly to make learning more effective.\r\n💡 𝘽𝙚 𝙨𝙢𝙖𝙧𝙩, 𝙨𝙩𝙖𝙮 𝙤𝙧𝙞𝙜𝙞𝙣𝙖𝙡, 𝙖𝙣𝙙 𝙢𝙖𝙠𝙚 𝘼𝙄 𝙮𝙤𝙪𝙧 𝙡𝙚𝙖𝙧𝙣𝙞𝙣𝙜 𝙖𝙡𝙡𝙮!\r\n\r\nLAYOUT: Gillianne Mañalac | Secretary\r\nCAPTION: James Acuña | President', 30, 18, 'PUBLIC', '2025-05-22 18:21:49', '2025-05-22 10:21:49', '2025-05-22 10:21:49'),
(40, '📢 ATTENTION, SFLG MEMBERS!\r\nThis is a reminder to all Supreme Secondary Learner Government officers and members that we will have our general assembly and project planning session this Wednesday, May 26, 2025, at 3:00 PM in Room 203.\r\n\r\nLet’s gather as one team to finalize our upcoming student-led initiatives for English and National Reading Month. Your presence, ideas, and leadership are needed!\r\n\r\n💛 Let’s continue to lead with purpose and serve with heart.', 1, 4, 'CLUB_ONLY', '2025-05-23 00:15:30', '2025-05-22 16:15:30', '2025-05-22 16:15:30'),
(41, '📣 Hey, Sitero Learners!\r\nThe Supreme Secondary Learner Government (SFLG) is inviting all student leaders and class representatives to join our General Assembly this Wednesday, November 20, 2024, at 3:00 PM in Room 203.\r\n\r\nWe’ll be discussing exciting plans and upcoming projects for English and National Reading Month—and we need your ideas and support! 💡📚\r\n\r\n✅ Come prepared\r\n✅ Bring your creativity\r\n✅ Let your voice be heard!\r\n\r\nTogether, let’s make a difference. ✨\r\n– Your SFLG Officers', 1, 37, 'CLUB_ONLY', '2025-05-23 00:18:43', '2025-05-22 16:18:43', '2025-05-22 16:18:43'),
(47, 'Hello VITS Family!', 34, 76, 'CLUB_ONLY', '2025-05-23 10:09:02', '2025-05-23 02:09:02', '2025-05-23 02:09:02');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_post_images`
--

CREATE TABLE `tbl_post_images` (
  `image_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_post_images`
--

INSERT INTO `tbl_post_images` (`image_id`, `post_id`, `image_path`, `created_at`, `updated_at`) VALUES
(75, 18, 'post-images/FPSyfw20sKUWHX7lcLDxue6hO5I2enAyQgdZQrob.jpg', '2025-05-22 08:02:59', '2025-05-22 08:02:59'),
(76, 19, 'post-images/b15CmE4N8Q2Ic47KDZWUDzc0npaLgc933Ddxxyjx.jpg', '2025-05-22 08:31:34', '2025-05-22 08:31:34'),
(77, 20, 'post-images/ibCNvDmEv7cmY2eIcDvM7nu7rJo8Ve8hgNRL4ngf.jpg', '2025-05-22 08:37:53', '2025-05-22 08:37:53'),
(78, 20, 'post-images/PCuNRG9vkOe2mQIT1VH3KWI6TSRnv1jPXlLUjl4D.jpg', '2025-05-22 08:37:53', '2025-05-22 08:37:53'),
(79, 20, 'post-images/qyy488vtaS8GnW5nvGYbStTvKc4nZGxEAUeFtWx8.jpg', '2025-05-22 08:37:53', '2025-05-22 08:37:53'),
(80, 20, 'post-images/5wFpMsWNKltvaV9310Li0qg5D1t44eSxF0SA3QWy.jpg', '2025-05-22 08:37:53', '2025-05-22 08:37:53'),
(81, 20, 'post-images/Af56SoSCIUhFwZjNeczSBSfWGfhqrMt04gvPndLB.jpg', '2025-05-22 08:37:53', '2025-05-22 08:37:53'),
(82, 20, 'post-images/VW3y64P9I77i2sAfmE7joKVqma6UXESJBxFsp0kd.jpg', '2025-05-22 08:37:53', '2025-05-22 08:37:53'),
(83, 20, 'post-images/uNV5q03NeNZOlLtLPBrKP0bVb1Qzq378aa4sipOt.jpg', '2025-05-22 08:37:53', '2025-05-22 08:37:53'),
(84, 20, 'post-images/kI3cy4bGuX1cTRgQVfI9a47c5XDdXtNFLmtKOmi0.jpg', '2025-05-22 08:37:53', '2025-05-22 08:37:53'),
(85, 20, 'post-images/I2PikgnWueu7bNra5QAoWBMUQeYPODARH6zZIswA.jpg', '2025-05-22 08:37:53', '2025-05-22 08:37:53'),
(86, 20, 'post-images/CHFX2HJL7yKmErNdahC6ReyFzmsuCzjzYTwawogC.jpg', '2025-05-22 08:37:53', '2025-05-22 08:37:53'),
(87, 20, 'post-images/e4WDpW2SZ4oeghIl8uPIE6ujOiEGzgINBEaOw6gW.jpg', '2025-05-22 08:37:53', '2025-05-22 08:37:53'),
(88, 21, 'post-images/kFQQZ7AVjpMNK4eWrOHtu26ExS8ag9SY4Em6Yakd.jpg', '2025-05-22 08:48:42', '2025-05-22 08:48:42'),
(89, 22, 'post-images/YhmBBbRGVPpwW5yIlKEh3O7q11SXiRmzhtOQfY4t.jpg', '2025-05-22 08:49:41', '2025-05-22 08:49:41'),
(90, 23, 'post-images/QSMXncgESnkJwBdKS5eKJVQQG0tUwxKRdY5h7goF.jpg', '2025-05-22 08:50:08', '2025-05-22 08:50:08'),
(91, 25, 'post-images/sJZDuiAKyLwDamru6xsMRhQQjawYpsDW2Ve6LUi9.jpg', '2025-05-22 09:11:12', '2025-05-22 09:11:12'),
(92, 26, 'post-images/cprzVJBOxDPHMFKomHzcEJltWEYk2dF0dCuDwL40.jpg', '2025-05-22 09:12:13', '2025-05-22 09:12:13'),
(93, 27, 'post-images/lDeT1wN3JbjzANuv9IRgNfWjVg8mYDfFxZUYbOry.jpg', '2025-05-22 09:26:50', '2025-05-22 09:26:50'),
(94, 27, 'post-images/Vp58gz4ViZXSgTLXopawC1sfMslxfBVqsYf7kexc.jpg', '2025-05-22 09:26:50', '2025-05-22 09:26:50'),
(95, 27, 'post-images/DLCBBqF72R4isBO6tmbRLxL4XsLvcO3KwEChab52.jpg', '2025-05-22 09:26:50', '2025-05-22 09:26:50'),
(96, 28, 'post-images/1QWXdqRUlvrwE3TuAo3CKvDW3yqFExTiinBbOGaK.jpg', '2025-05-22 09:27:37', '2025-05-22 09:27:37'),
(97, 29, 'post-images/VELRjMk543iBzvtXCqKNiebhk7XDHbYaniYFYR07.jpg', '2025-05-22 09:28:26', '2025-05-22 09:28:26'),
(98, 29, 'post-images/DQLsAZl2R4oGQdGoszL1PxCOQazKfW1gnbtZUAAk.jpg', '2025-05-22 09:28:26', '2025-05-22 09:28:26'),
(99, 29, 'post-images/YdaCMqs2tbp1wL6QccRbzDT64XnadkwQjNGFeQF0.jpg', '2025-05-22 09:28:26', '2025-05-22 09:28:26'),
(100, 30, 'post-images/IFIFKGaia5msVIFk4zTyNXswyDOWcI65MZQsofej.jpg', '2025-05-22 09:29:17', '2025-05-22 09:29:17'),
(101, 31, 'post-images/B5qNcLej1Qt8ElXGtlGksjhskkUopkA9hiaAX29H.jpg', '2025-05-22 09:42:08', '2025-05-22 09:42:08'),
(102, 32, 'post-images/QPEbBlsc4wb0pZLyTVT4NPbgGE3mYnzdp6fMDKFw.jpg', '2025-05-22 09:42:59', '2025-05-22 09:42:59'),
(116, 37, 'post-images/gZVqjxSu9dLS0upT9ARMRuN7AWfygIeDVgrqrwft.jpg', '2025-05-22 10:21:22', '2025-05-22 10:21:22'),
(117, 37, 'post-images/KlqzwNpJBR4H1iRKHIsMw4SA5Mnjb48o9WjEH4G2.jpg', '2025-05-22 10:21:22', '2025-05-22 10:21:22'),
(118, 37, 'post-images/dDxmMAjmguxpKCEAsJW4d3DCnLMgVh4ppaGJgK8t.jpg', '2025-05-22 10:21:22', '2025-05-22 10:21:22'),
(119, 37, 'post-images/yVKB2GwHhtJxje4uEevCqAmiT6qNqAplZE31cnjN.jpg', '2025-05-22 10:21:22', '2025-05-22 10:21:22'),
(120, 37, 'post-images/aZYnDdgdzCPKQ5sKx2MvpVKmGjL2cHiNs1VMqPFN.jpg', '2025-05-22 10:21:22', '2025-05-22 10:21:22'),
(121, 37, 'post-images/hRzyIRykTrHjkt2F30ITETkX09SsJ6C9rzqjquWu.jpg', '2025-05-22 10:21:22', '2025-05-22 10:21:22'),
(122, 38, 'post-images/zTp983GBtt6vM6UAZc0HriSTV7k9oyAtKB8xbozO.jpg', '2025-05-22 10:21:49', '2025-05-22 10:21:49');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sections`
--

CREATE TABLE `tbl_sections` (
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `section_name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_sections`
--

INSERT INTO `tbl_sections` (`section_id`, `class_id`, `section_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Alpha', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(2, 1, 'Beta', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(3, 1, 'Gamma', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(4, 2, 'Alpha', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(5, 2, 'Beta', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(6, 2, 'Gamma', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(7, 3, 'Alpha', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(8, 3, 'Beta', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(9, 3, 'Gamma', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(10, 4, 'Alpha', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(11, 4, 'Beta', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(12, 4, 'Gamma', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(13, 5, 'Alpha', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(14, 5, 'Beta', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(15, 5, 'Gamma', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(16, 6, 'Alpha', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(17, 6, 'Beta', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(18, 6, 'Gamma', '2025-03-29 12:55:49', '2025-03-29 12:55:49'),
(19, 6, 'ABM-A', '2025-03-29 15:23:25', '2025-03-29 15:23:25'),
(20, 2, 'Thompson', '2025-03-29 17:29:53', '2025-03-29 17:29:53'),
(21, 1, 'Random Class', '2025-03-30 06:34:31', '2025-03-30 06:34:31'),
(22, 3, 'Reovoca', '2025-03-30 13:19:21', '2025-03-30 13:19:21'),
(23, 1, 'Charlie', '2025-03-31 04:48:00', '2025-03-31 04:48:00'),
(24, 1, 'Random', '2025-04-01 04:15:55', '2025-04-01 04:15:55'),
(25, 5, 'ABM-C', '2025-04-01 04:36:07', '2025-04-01 04:36:07'),
(26, 4, 'Goodies', '2025-04-01 04:37:02', '2025-04-01 04:37:02'),
(27, 3, 'Random 9', '2025-04-01 04:47:01', '2025-04-01 04:47:01'),
(28, 3, 'Delta', '2025-04-05 15:14:54', '2025-04-05 15:14:54'),
(29, 4, 'Mango', '2025-04-07 21:46:17', '2025-04-07 21:46:17'),
(30, 3, 'Denden', '2025-04-08 02:40:45', '2025-04-08 02:40:45');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role` enum('ADMIN','TEACHER','STUDENT') NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sex` enum('MALE','FEMALE') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `mother_contact_no` varchar(20) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `father_contact_no` varchar(20) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `role`, `name`, `email`, `sex`, `address`, `contact_no`, `password`, `section_id`, `mother_name`, `mother_contact_no`, `father_name`, `father_contact_no`, `remember_token`, `profile_picture`, `created_at`, `updated_at`) VALUES
(1, 'ADMIN', 'Admin User', 'admin@gmail.com', NULL, NULL, NULL, '$2y$12$1buyJYGqbYQCS5tC7GcYuuYH7LkTNYUT4lMjamgYpND5X9DGnlvRu', NULL, NULL, NULL, NULL, NULL, 'mdAHcoemYNzUAy18o3FTuPTwlw0tUEfB0OAqj0oDgj5QyNGVqQ1bbeq7Z45J', '1747933871_1.png', '2025-03-29 12:55:49', '2025-05-22 17:11:34'),
(2, 'TEACHER', 'Jezelle Dalwampu', 'jezelle@gmail.com', NULL, NULL, NULL, '$2y$12$9k9xjICVBCUhXf1d8T77vOXzdkwxIwio5TsJpZmgk5Gg2v9V/J3RK', NULL, NULL, NULL, NULL, NULL, 'nnZ7CzEqvkCHot99bcTVOCxRps565b2oWWv56RXoWg5ryV5ZiPCDySlnxm5H', NULL, '2025-03-29 12:55:49', '2025-05-22 08:10:35'),
(4, 'TEACHER', 'Melody S. Delos Santos', 'melody@gmail.com', NULL, NULL, NULL, '$2y$12$HwqGpYmNzUEuS4L3Eunng.dkYpLGvZ8kIZd0gilYQQ3xO.4PU5JeC', NULL, NULL, NULL, NULL, NULL, 'v2dW6vz5yNno6sQycrZJYsCHwwlask1qRll19eZwjYI0BfTTQXzC4VdNa3H7', '1743958684_4.jpg', '2025-03-29 15:00:43', '2025-05-22 18:06:14'),
(6, 'STUDENT', 'Jovince Salic', 'jovincepro@gmail.com', NULL, NULL, NULL, '$2y$12$cof1lJsh.xWn5iXkEUSSP.7GsiMTXhQGLT9SP4D8Ar9XBEWzY5xkS', 1, NULL, NULL, NULL, NULL, NULL, '1754824448_6.jpg', '2025-03-29 17:15:01', '2025-08-10 11:14:08'),
(8, 'STUDENT', 'Jean Ann Abay', 'jeannnsss@gmail.com', NULL, NULL, NULL, '$2y$12$CVrnFmtj0TgU.SYNnAARg.enYTwmfsJnTeV7VLAUh3oaI.O1WuEnW', 18, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-29 17:28:32', '2025-05-22 10:16:05'),
(9, 'STUDENT', 'Jasper James Vinluan', 'blazyjasper@gmail.com', NULL, NULL, NULL, '$2y$12$1ZHg5Wz2a0DoKGihVjQmb.GdJUhZeU3TNqvJJZNhihkErhkQoY5ki', 16, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-29 17:28:51', '2025-05-22 10:15:31'),
(10, 'STUDENT', 'Rain Esteban', 'rain@gmail.com', NULL, NULL, NULL, '$2y$12$8sP2XFqyu0n6FqKHcxjHs.O7Y5XeAinehX8ll5hMhOowLbXLszJhm', 18, NULL, NULL, NULL, NULL, 'EIzVyYCXMXecnQnBQYjks7GrJuX34e5uuG8yihXd7ALqjLXcbMU26zvveisg', NULL, '2025-03-29 17:29:20', '2025-05-22 10:15:42'),
(12, 'TEACHER', 'Harold Leobrera', 'haroldleobrera@gmail.com', NULL, NULL, NULL, '$2y$12$SIioCA7kAHJzjB2pOr0Frex5Ygphw03Fyolf2d/pPch3L8YK5wZb2', NULL, NULL, NULL, NULL, NULL, NULL, '1744399825_12.jpg', '2025-03-30 01:13:33', '2025-04-11 19:30:25'),
(14, 'ADMIN', 'Peter Griffin', 'peter@gmail.com', NULL, NULL, NULL, '$2y$12$eKZ09oJHdOa98Lpvmu4g2e0.n2LNHEhm2sOphz45aba.VJNeQWLWW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-30 08:09:58', '2025-03-30 08:09:58'),
(15, 'TEACHER', 'SaMaFil Adviser', 'siterosamafil@gmail.com', NULL, NULL, NULL, '$2y$12$HJab9DnAD1hk5sCilvtf1.GgBjcJqfvyoxu13xHlLVb4VV3MrkDmq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-30 09:03:56', '2025-03-30 09:03:56'),
(16, 'TEACHER', 'Nico Diwa Bundoc Ocampo', 'nicodiwa.ocampo@deped.gov.ph', NULL, NULL, NULL, '$2y$12$hXfwOrC3FGi2gHay/6An..7Ot9n1byd8.clSlolIK2oPe.vKkMa2u', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-30 09:06:13', '2025-03-30 09:06:13'),
(17, 'TEACHER', 'Ang Antipara Adviser', 'angantipara@gmail.com', NULL, NULL, NULL, '$2y$12$EAS5YcQyZbIifG.3OvP6W.nm4F9qr.F6MuWyCqGXUkX0bINH2sUAu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-30 09:09:30', '2025-03-30 09:09:30'),
(18, 'TEACHER', 'Angelica Gamal', 'sitero.boyscout@gmail.com', NULL, NULL, NULL, '$2y$12$yHpXD.7HHSNu5GSrNET5decnvJBFOrPhCee2XocR4CsgyyfPGn0tu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-30 09:15:44', '2025-03-30 09:15:44'),
(19, 'TEACHER', 'John-John B. Galicia', 'siteroyouthintegrityfighters@gmail.com', NULL, NULL, NULL, '$2y$12$Up7p2RIdF6RYwpMyudg04O.f.efweEqfc9iSfxp3aGJIx8G7gt4HG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-30 09:21:19', '2025-03-30 09:21:19'),
(21, 'STUDENT', 'Mariajasmin Porta', 'jasmin@gmail.com', NULL, NULL, NULL, '$2y$12$iKa6BQsPddOavmy5U6Q5fuC6wLqViB10w2hB.tZQvfMHBq9S0e1qC', 7, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-30 18:29:09', '2025-03-30 18:29:09'),
(22, 'STUDENT', 'Krayssanta F. Lapuz', 'sslglapuz@gmail.com', NULL, NULL, NULL, '$2y$12$t3NH0rMDE6qeKJxiTnQXsOeN93X4vT4q4ewTTIdu9gBq7v5Hi6o7S', 10, NULL, NULL, NULL, NULL, NULL, '1743959456_22.jpg', '2025-03-30 18:30:28', '2025-04-06 17:10:56'),
(23, 'STUDENT', 'Darlyn M. Abucion', 'darlyn@gmail.com', NULL, NULL, NULL, '$2y$12$5Uj/PiCsRY6R4VPw6Prd6u3h/BqHVFwAM197ySL3ixeMhgjp1cFkC', 12, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-30 19:04:07', '2025-03-30 19:04:07'),
(24, 'STUDENT', 'Alliyah Jade Bracero', 'braceroalliyahjade@gmail.com', NULL, NULL, NULL, '$2y$12$yB2vJySYUMMH.Fur19SMl.4T8BF9x35dXPZuwhCRNNcypD1fKAu56', 22, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-31 02:28:49', '2025-03-31 02:28:49'),
(34, 'STUDENT', 'First SSLG Member Test', 'sslg1test@gmail.com', NULL, NULL, NULL, '$2y$12$L234EVnR0egoRe9Dc040XO6cb5iIA4lyYwjYGuspxvzt3lURsxuXe', 26, NULL, NULL, NULL, NULL, 'hWsgcM85rjEJKHRHP1nX2GtksAOT8B17CemiajJU96khyumTdAJYLOrXx6dt', NULL, '2025-04-05 05:27:48', '2025-04-05 05:27:48'),
(35, 'STUDENT', 'Second SSLG Member', 'sslg2test@gmail.com', NULL, NULL, NULL, '$2y$12$O/Oan7E87PSGP6SB8JfMm.tUx9Gha1mFGLVN/.g7V66Cxz8VchKme', 28, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-05 15:15:08', '2025-04-05 15:15:08'),
(36, 'ADMIN', 'Daron Mangaoang', 'mangaoang.daron27@gmail.com', NULL, NULL, NULL, '$2y$12$l.aN9vvFx.zBtxMctk7W0u6b9jVWmT6wZjf/r/Vu84nqlujFMuaSu', NULL, NULL, NULL, NULL, NULL, 'EpjxTzSgUudrjgnwruPMtUEZNw7NeqAjZowe5sd3pIZx0BP8PlnskDa2p3y6', NULL, '2025-04-07 00:34:41', '2025-05-22 07:02:24'),
(37, 'STUDENT', 'Kristine Kyle M. Penaso', 'sslgkristine@gmail.com', NULL, NULL, NULL, '$2y$12$UFINVRoYqEEvbJx4bw.Ow.Mdngs1RPGg4prtrvz1eDTXnTMlTN3da', 5, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-07 06:48:02', '2025-04-07 06:48:02'),
(43, 'STUDENT', 'Ryan Trayhan', 'ryantrayhan@gmail.com', NULL, NULL, NULL, '$2y$12$rc3RwWmViUjvYuPw5.PBHuoIYekRdtZCOcNCwt26EBAPpUdLX6zcu', 2, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-13 18:34:08', '2025-04-13 18:34:08'),
(44, 'TEACHER', 'Joshua Martin', 'joshuamartin@example.com', NULL, NULL, NULL, '$2y$12$wyBJ48lkz7dtmuiyP.pDX.pVeaL6z..4ycPe5gxD8E87FTyOtqxn.', NULL, NULL, NULL, NULL, NULL, 'u0PMX6sXoww93026FhF7AoAlnjjeHodyAezScJr77llA5gpr8IcBWHCBuTpR', NULL, '2025-04-13 18:34:08', '2025-05-22 08:53:07'),
(45, 'STUDENT', 'Jasmine Patel', 'jpatel@example.com', NULL, NULL, NULL, '$2y$12$wPDmbpLffAKgpiW5l3B62.qgc3qLG8Zs4/4b8kHJ1nDBUFovv2S86', 22, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-13 19:04:36', '2025-04-13 19:04:36'),
(46, 'STUDENT', 'Tyler Johnson', 'tjohnson@example.com', NULL, NULL, NULL, '$2y$12$cqCgfORy7VtItn3VPoGQPedTGh4ZMtIPpqbW7eQbAAa.J.miyDcB2', 20, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-13 19:04:36', '2025-04-13 19:04:36'),
(47, 'STUDENT', 'Madison Clark', 'mclark@example.com', NULL, NULL, NULL, '$2y$12$hxTa30Zt40WguWwJtahsKuxZry0Ag/6QKJP2aFfQ8wg9p34aY7duS', 12, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-13 19:04:36', '2025-04-13 19:04:36'),
(48, 'TEACHER', 'Rebecca Wilson', 'rwilson@example.com', NULL, NULL, NULL, '$2y$12$NM4CO5mcafcrYptzR5BRXOPpE2BhjEGsYlbmwRLF.ig03NMUurXo.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-13 19:04:36', '2025-04-13 19:04:36'),
(49, 'TEACHER', 'Marcus Davis', 'mdavis@example.com', NULL, NULL, NULL, '$2y$12$aVgziziRFEmJglyF0rm.r.28b48VoHhk5N0gs566U03ahmejgoqfO', NULL, NULL, NULL, NULL, NULL, 'yEi24q0Kn7xpCWXdHFWNqVuXrAtu7u5SQ9SqCSRiQuW63SsNKtysd7rEdsGI', NULL, '2025-04-13 19:04:37', '2025-05-22 09:08:42'),
(50, 'TEACHER', 'Emma Rodriguez', 'erodriguez@example.com', NULL, NULL, NULL, '$2y$12$XXm1u7Zz5ArnY0PhJ59h9.aka3uQsnuZYKH5Kjn2ko7qLnv7qgUrO', NULL, NULL, NULL, NULL, NULL, 'qHO3gDi4DOM7OYrDQhkhfyrs5YCUo6K226JqaqpZ5HLaUyhhKwfPOn3uOsKm', NULL, '2025-04-13 19:04:37', '2025-05-22 09:38:59'),
(51, 'TEACHER', 'Roberto Celeste', 'roberto@example.com', NULL, NULL, NULL, '$2y$12$1Q704YX6Y7W9jLxN0laK1O14iX.71.N3S6IS957dj6R/vbPRE7LSG', NULL, NULL, NULL, NULL, NULL, 'qkCLSa7zxclaC8HHUlGOYJQKAikHQJdmoJqEmDgKFwx9JDFCrL95A7huRmUL', '1747902125_51.jpg', '2025-04-13 19:04:37', '2025-05-22 08:22:05'),
(52, 'TEACHER', 'Olivia Murphy', 'omurphy@example.com', NULL, NULL, NULL, '$2y$12$wbIGdspOUHMz98rM77GAougK0EJtgWXZFwUfrcQsJB9BokXseeyci', NULL, NULL, NULL, NULL, NULL, 'IpPBbAKLlEwgk1lvqPkF81lbjLS5ibq8JiQ5zMVs11XTwN153OOkjlIexj8n', NULL, '2025-04-13 19:04:37', '2025-05-22 09:24:58'),
(53, 'STUDENT', 'Zoe Robinson', 'zrobinson@gmail.com', NULL, NULL, NULL, '$2y$12$SqZGly0tHRXO2Ur/2IO7I.gbY5.eesuJ55wChK7JcR56lz23Ms3Ty', 19, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-13 19:06:34', '2025-04-13 19:06:34'),
(55, 'ADMIN', 'Sitero Admin', 'adminsitero@gmail.com', NULL, NULL, NULL, '$2y$12$X7yxy4wLR8Ipf0pv8wHA5egM0DR4LVHw87OnZbAEfISLoN3/hVlJO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-06 15:03:33', '2025-05-06 15:03:33'),
(57, 'ADMIN', 'Sitero Admin 2', 'siteroadmin2@gmail.com', NULL, NULL, NULL, '$2y$12$q8g8G.q9KaOYr/8FsXnfQ.CIQZRtfFnoV5Jn5ixwzDamOX1P3l2na', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-22 03:14:56', '2025-05-22 03:14:56'),
(58, 'STUDENT', 'Amir Yman Corpur', 'amiryman@gmail.com', NULL, NULL, NULL, '$2y$12$UvOu1BQI.1cjV34eObKxWeOIpzvRL4aftRtKfgETtldBoVIZW5Gqq', 16, NULL, NULL, NULL, NULL, NULL, '1747903147_58.jpg', '2025-05-22 08:25:21', '2025-05-22 08:39:07'),
(59, 'STUDENT', 'Trisha Ramos', 'trisharamos@gmail.com', NULL, NULL, NULL, '$2y$12$1nSE9lgALDUcrPqaFRXAb.OJ6sSKt8gzwyeqIPhjH0D8e6gYIsjEa', 13, NULL, NULL, NULL, NULL, NULL, '1747903211_59.jpg', '2025-05-22 08:28:57', '2025-05-22 08:40:11'),
(60, 'STUDENT', 'Kristine Caasi', 'kristinecaasi@example.com', NULL, NULL, NULL, '$2y$12$ACmqSIzd5YZ1lp4yrir53OJoTcHTbVjiFp8ty2D.PeSMbQ88kjB1q', 29, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-22 08:44:07', '2025-05-22 08:44:07'),
(61, 'STUDENT', 'Cristina Francisco', 'cristinafrancisco@example.com', NULL, NULL, NULL, '$2y$12$PEslHFVh5pvd/TRvhUJTjeB1mlxZ3hP5XvbpeLohORa7RLc4q5ID6', 12, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-22 08:44:40', '2025-05-22 08:44:40'),
(62, 'STUDENT', 'Mira Dela Cruz', 'miradelacruz09@gmail.com', NULL, NULL, NULL, '$2y$12$QNhQSRBg.0pwNn2BnEiKeuZlOnnJhZbrCq4xtdAXC4UbYYIS9URDu', 28, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-22 17:50:37', '2025-05-22 17:50:37'),
(64, 'STUDENT', 'Alex Rivera', 'arivera@example.com', NULL, NULL, NULL, '$2y$12$CMBbei2TmH/wqTj.vOZDAeJRGwNjYjNkdKt/jJqi4ViDDeinpAdc6', 2, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-23 02:02:35', '2025-05-23 02:02:35'),
(65, 'STUDENT', 'Morgan Lee', 'mlee@example.com', NULL, NULL, NULL, '$2y$12$rFXrVnAxQP5CqxmNOY28AubBOwRYJ7RzNaokMul3my1/iWD.0nk9m', 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-23 02:02:35', '2025-05-23 02:02:35'),
(66, 'STUDENT', 'Samantha Taylor', 'staylor@example.com', NULL, NULL, NULL, '$2y$12$IC6wDNv94Y9ET7d5W8zmBedY.K17eWhdKwhAXfQw68oBNbj0mnEsC', 4, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-23 02:02:35', '2025-05-23 02:02:35'),
(67, 'STUDENT', 'Ethan Brown', 'ebrown@example.com', NULL, NULL, NULL, '$2y$12$H8ijKaDSOqq3pDBsoEUqs.Hv/eTsQBVRByR6IEB56w5/VJQsfCsS6', 3, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-23 02:02:35', '2025-05-23 02:02:35'),
(68, 'STUDENT', 'Daniel Park', 'dpark@example.com', NULL, NULL, NULL, '$2y$12$Adzg/91gDpBMLQXvmrKZXeFOGDghruTteWEnrOU3gYXLnMoxfG6fW', 2, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-23 02:02:35', '2025-05-23 02:02:35'),
(69, 'STUDENT', 'Ryan Martinez', 'rmartinez@example.com', NULL, NULL, NULL, '$2y$12$iWRszygXzbxz7/VIEAKBDOsH9MSruxSe6/IIVqa6ig9eMxp9.7MtG', 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-23 02:02:36', '2025-05-23 02:02:36'),
(70, 'TEACHER', 'Hannah Kim', 'hkim@example.com', NULL, NULL, NULL, '$2y$12$JZhPCBAxf8vmTCrs73LCT.ejiTtOlWSK4twotowK90bGnQgoxhbju', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-23 02:02:36', '2025-05-23 02:02:36'),
(71, 'TEACHER', 'David Thompson', 'dthompson@example.com', NULL, NULL, NULL, '$2y$12$AEzeON8QZPhNAKE3uYB/3ePtNhMZL3RHS9nixeXfGarZPrCPvl28S', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-23 02:02:36', '2025-05-23 02:02:36'),
(72, 'TEACHER', 'Olivia Jackson', 'ojackson@example.com', NULL, NULL, NULL, '$2y$12$v88y9WGho/U6vb8hhmBSC.lWT7dSQnY1dQ1WaLDyY8JhCj8m7QShG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-23 02:02:36', '2025-05-23 02:02:36'),
(73, 'TEACHER', 'Nathan Singh', 'nsingh@example.com', NULL, NULL, NULL, '$2y$12$IVPeihvqLrSAVu17PJktcuHtpZW6qktATNEUWYoA/X0xdqWgRQHg.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-23 02:02:36', '2025-05-23 02:02:36'),
(74, 'TEACHER', 'Sophia Williams', 'swilliams@example.com', NULL, NULL, NULL, '$2y$12$vSuJ6WWmMz3kwbz8gUi34OjS/Xjea7ePEHe59ymo0fpckols7n/R2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-23 02:02:37', '2025-05-23 02:02:37'),
(75, 'TEACHER', 'Carlos Mendez', 'cmendez@example.com', NULL, NULL, NULL, 'password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-23 02:02:37', '2025-05-23 02:02:37'),
(76, 'TEACHER', 'VITS Adviser', 'vits@gmail.com', NULL, NULL, NULL, '$2y$12$OLvWfqLdkrSHL3OzKgyZaeY10JL9jo3IDNUtEWP2VL3laBUxIj2qi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-23 02:04:49', '2025-05-23 02:04:49'),
(80, 'TEACHER', 'user1', 'user1@gmail.com', 'MALE', 'asdasdasdasd', '09781487615', '$2y$12$8oqLiuEiKqC4Fpik5EpTr.GDBs9X8u0px128VnqCef33eYYF/9k36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-10 11:25:43', '2025-08-10 11:25:43');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_votes`
--

CREATE TABLE `tbl_votes` (
  `vote_id` bigint(20) UNSIGNED NOT NULL,
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `voter_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_votes`
--

INSERT INTO `tbl_votes` (`vote_id`, `election_id`, `voter_id`, `created_at`, `updated_at`) VALUES
(3, 10, 62, '2025-05-22 21:22:42', '2025-05-22 21:22:42'),
(4, 10, 22, '2025-05-22 21:23:35', '2025-05-22 21:23:35'),
(5, 10, 37, '2025-05-22 21:24:03', '2025-05-22 21:24:03'),
(6, 10, 6, '2025-05-23 02:16:45', '2025-05-23 02:16:45');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vote_details`
--

CREATE TABLE `tbl_vote_details` (
  `vote_detail_id` bigint(20) UNSIGNED NOT NULL,
  `vote_id` bigint(20) UNSIGNED NOT NULL,
  `position` varchar(255) NOT NULL,
  `candidate_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_vote_details`
--

INSERT INTO `tbl_vote_details` (`vote_detail_id`, `vote_id`, `position`, `candidate_id`, `created_at`, `updated_at`) VALUES
(3, 3, 'President', 18, '2025-05-22 21:22:42', '2025-05-22 21:22:42'),
(4, 3, 'Treasurer', 23, '2025-05-22 21:22:42', '2025-05-22 21:22:42'),
(5, 3, 'Vice President', 21, '2025-05-22 21:22:42', '2025-05-22 21:22:42'),
(6, 4, 'President', 18, '2025-05-22 21:23:35', '2025-05-22 21:23:35'),
(7, 4, 'Treasurer', 22, '2025-05-22 21:23:35', '2025-05-22 21:23:35'),
(8, 4, 'Vice President', 20, '2025-05-22 21:23:35', '2025-05-22 21:23:35'),
(9, 5, 'President', 18, '2025-05-22 21:24:03', '2025-05-22 21:24:03'),
(10, 5, 'Treasurer', 22, '2025-05-22 21:24:03', '2025-05-22 21:24:03'),
(11, 5, 'Vice President', 20, '2025-05-22 21:24:03', '2025-05-22 21:24:03'),
(12, 6, 'President', 18, '2025-05-23 02:16:45', '2025-05-23 02:16:45'),
(13, 6, 'Treasurer', 22, '2025-05-23 02:16:45', '2025-05-23 02:16:45'),
(14, 6, 'Vice President', 20, '2025-05-23 02:16:45', '2025-05-23 02:16:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tbl_candidates`
--
ALTER TABLE `tbl_candidates`
  ADD PRIMARY KEY (`candidate_id`),
  ADD UNIQUE KEY `tbl_candidates_election_id_user_id_position_unique` (`election_id`,`user_id`,`position`) USING HASH,
  ADD KEY `tbl_candidates_user_id_foreign` (`user_id`);

--
-- Indexes for table `tbl_classes`
--
ALTER TABLE `tbl_classes`
  ADD PRIMARY KEY (`class_id`),
  ADD UNIQUE KEY `tbl_classes_grade_level_unique` (`grade_level`);

--
-- Indexes for table `tbl_clubs`
--
ALTER TABLE `tbl_clubs`
  ADD PRIMARY KEY (`club_id`),
  ADD KEY `tbl_clubs_club_adviser_foreign` (`club_adviser`);

--
-- Indexes for table `tbl_club_join_requests`
--
ALTER TABLE `tbl_club_join_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD UNIQUE KEY `tbl_club_join_requests_club_id_user_id_unique` (`club_id`,`user_id`),
  ADD KEY `tbl_club_join_requests_user_id_foreign` (`user_id`);

--
-- Indexes for table `tbl_club_membership`
--
ALTER TABLE `tbl_club_membership`
  ADD PRIMARY KEY (`membership_id`),
  ADD UNIQUE KEY `tbl_club_membership_club_id_user_id_unique` (`club_id`,`user_id`),
  ADD KEY `tbl_club_membership_user_id_foreign` (`user_id`);

--
-- Indexes for table `tbl_elections`
--
ALTER TABLE `tbl_elections`
  ADD PRIMARY KEY (`election_id`),
  ADD KEY `tbl_elections_club_id_foreign` (`club_id`);

--
-- Indexes for table `tbl_events`
--
ALTER TABLE `tbl_events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `tbl_events_club_id_foreign` (`club_id`),
  ADD KEY `tbl_events_organizer_id_foreign` (`organizer_id`);

--
-- Indexes for table `tbl_posts`
--
ALTER TABLE `tbl_posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `tbl_posts_club_id_foreign` (`club_id`),
  ADD KEY `tbl_posts_author_id_foreign` (`author_id`);

--
-- Indexes for table `tbl_post_images`
--
ALTER TABLE `tbl_post_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `tbl_post_images_post_id_foreign` (`post_id`);

--
-- Indexes for table `tbl_sections`
--
ALTER TABLE `tbl_sections`
  ADD PRIMARY KEY (`section_id`),
  ADD UNIQUE KEY `tbl_sections_class_id_section_name_unique` (`class_id`,`section_name`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `tbl_users_email_unique` (`email`),
  ADD KEY `tbl_users_section_id_foreign` (`section_id`);

--
-- Indexes for table `tbl_votes`
--
ALTER TABLE `tbl_votes`
  ADD PRIMARY KEY (`vote_id`),
  ADD UNIQUE KEY `tbl_votes_election_id_voter_id_unique` (`election_id`,`voter_id`),
  ADD KEY `tbl_votes_voter_id_foreign` (`voter_id`);

--
-- Indexes for table `tbl_vote_details`
--
ALTER TABLE `tbl_vote_details`
  ADD PRIMARY KEY (`vote_detail_id`),
  ADD KEY `vote_id` (`vote_id`),
  ADD KEY `candidate_id` (`candidate_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_candidates`
--
ALTER TABLE `tbl_candidates`
  MODIFY `candidate_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tbl_classes`
--
ALTER TABLE `tbl_classes`
  MODIFY `class_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_clubs`
--
ALTER TABLE `tbl_clubs`
  MODIFY `club_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tbl_club_join_requests`
--
ALTER TABLE `tbl_club_join_requests`
  MODIFY `request_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_club_membership`
--
ALTER TABLE `tbl_club_membership`
  MODIFY `membership_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `tbl_elections`
--
ALTER TABLE `tbl_elections`
  MODIFY `election_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_events`
--
ALTER TABLE `tbl_events`
  MODIFY `event_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tbl_posts`
--
ALTER TABLE `tbl_posts`
  MODIFY `post_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `tbl_post_images`
--
ALTER TABLE `tbl_post_images`
  MODIFY `image_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `tbl_sections`
--
ALTER TABLE `tbl_sections`
  MODIFY `section_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `tbl_votes`
--
ALTER TABLE `tbl_votes`
  MODIFY `vote_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_vote_details`
--
ALTER TABLE `tbl_vote_details`
  MODIFY `vote_detail_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_clubs`
--
ALTER TABLE `tbl_clubs`
  ADD CONSTRAINT `tbl_clubs_club_adviser_foreign` FOREIGN KEY (`club_adviser`) REFERENCES `tbl_users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_club_join_requests`
--
ALTER TABLE `tbl_club_join_requests`
  ADD CONSTRAINT `tbl_club_join_requests_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `tbl_clubs` (`club_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_club_join_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_club_membership`
--
ALTER TABLE `tbl_club_membership`
  ADD CONSTRAINT `tbl_club_membership_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `tbl_clubs` (`club_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_club_membership_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_elections`
--
ALTER TABLE `tbl_elections`
  ADD CONSTRAINT `tbl_elections_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `tbl_clubs` (`club_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_events`
--
ALTER TABLE `tbl_events`
  ADD CONSTRAINT `tbl_events_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `tbl_clubs` (`club_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_events_organizer_id_foreign` FOREIGN KEY (`organizer_id`) REFERENCES `tbl_users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_posts`
--
ALTER TABLE `tbl_posts`
  ADD CONSTRAINT `tbl_posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `tbl_users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_posts_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `tbl_clubs` (`club_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_post_images`
--
ALTER TABLE `tbl_post_images`
  ADD CONSTRAINT `tbl_post_images_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `tbl_posts` (`post_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_sections`
--
ALTER TABLE `tbl_sections`
  ADD CONSTRAINT `tbl_sections_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `tbl_classes` (`class_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD CONSTRAINT `tbl_users_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `tbl_sections` (`section_id`) ON DELETE SET NULL;

--
-- Constraints for table `tbl_votes`
--
ALTER TABLE `tbl_votes`
  ADD CONSTRAINT `tbl_votes_election_id_foreign` FOREIGN KEY (`election_id`) REFERENCES `tbl_elections` (`election_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_votes_voter_id_foreign` FOREIGN KEY (`voter_id`) REFERENCES `tbl_users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_vote_details`
--
ALTER TABLE `tbl_vote_details`
  ADD CONSTRAINT `tbl_vote_details_ibfk_1` FOREIGN KEY (`vote_id`) REFERENCES `tbl_votes` (`vote_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_vote_details_ibfk_2` FOREIGN KEY (`candidate_id`) REFERENCES `tbl_candidates` (`candidate_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
