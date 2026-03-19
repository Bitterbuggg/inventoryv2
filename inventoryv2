-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 12, 2026 at 03:45 PM
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
-- Database: `inventoryv2`
--

-- --------------------------------------------------------

--
-- Table structure for table `analytics_daily_metrics`
--

CREATE TABLE `analytics_daily_metrics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `metric_date` date NOT NULL,
  `metric_key` varchar(150) NOT NULL,
  `metric_value` bigint(20) NOT NULL DEFAULT 0,
  `module` varchar(80) NOT NULL,
  `dimension_json` text DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `analytics_events`
--

CREATE TABLE `analytics_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_name` varchar(150) NOT NULL,
  `module` varchar(80) NOT NULL,
  `actor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference_type` varchar(80) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `method` varchar(16) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `metadata_json` text DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `analytics_events`
--

INSERT INTO `analytics_events` (`id`, `event_name`, `module`, `actor_id`, `reference_type`, `reference_id`, `route`, `method`, `ip_address`, `user_agent`, `metadata_json`, `created_at`) VALUES
(1, 'auth.login_success', 'auth', 1, 'user', 1, '/login', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 09:56:11'),
(2, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 09:56:36'),
(3, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 09:56:38'),
(4, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 09:56:39'),
(5, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 09:56:45'),
(6, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 09:56:47'),
(7, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:01:34'),
(8, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:40:15'),
(9, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:40:39'),
(10, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:40:43'),
(11, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:40:47'),
(12, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:41:06'),
(13, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:41:09'),
(14, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:41:11'),
(15, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:41:21'),
(16, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:41:29'),
(17, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:41:34'),
(18, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:41:52'),
(19, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:41:54'),
(20, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 47, '/procurement/po-requests/47/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:42:24'),
(21, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:42:25'),
(22, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:42:30'),
(23, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:42:32'),
(24, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:42:34'),
(25, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:42:41'),
(26, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:42:51'),
(27, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:42:57'),
(28, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:43:06'),
(29, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:43:40'),
(30, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-04 10:43:48'),
(31, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:44:01'),
(32, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-balance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_balance\",\"filters\":{\"has_keyword\":false}}', '2026-03-04 10:44:12'),
(33, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:44:16'),
(34, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 51, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:46:53'),
(35, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:46:53'),
(36, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:46:57'),
(37, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:47:00'),
(38, 'procurement.pr_updated', 'procurement', 1, 'purchase_request', 51, '/procurement/purchase-requests/51/update', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:47:49'),
(39, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:47:49'),
(40, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:48:11'),
(41, 'procurement.pr_cancelled', 'procurement', 1, 'purchase_request', 39, '/procurement/purchase-requests/39/cancel', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:48:19'),
(42, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:48:19'),
(43, 'procurement.pr_cancelled', 'procurement', 1, 'purchase_request', 44, '/procurement/purchase-requests/44/cancel', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:48:36'),
(44, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:48:36'),
(45, 'procurement.pr_cancelled', 'procurement', 1, 'purchase_request', 49, '/procurement/purchase-requests/49/cancel', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:48:45'),
(46, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:48:46'),
(47, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 51, '/procurement/purchase-requests/51/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:48:50'),
(48, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:48:51'),
(49, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:49:04'),
(50, 'procurement.pr_approved', 'procurement', 1, 'approval', 1, '/procurement/approvals/1/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:49:17'),
(51, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:49:17'),
(52, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:49:21'),
(53, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:49:25'),
(54, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:49:31'),
(55, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:49:46'),
(56, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:49:50'),
(57, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:49:50'),
(58, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:50:02'),
(59, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:50:05'),
(60, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:50:20'),
(61, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:50:25'),
(62, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:50:28'),
(63, 'procurement.po_created', 'procurement', 1, 'purchase_order', 51, '/procurement/purchase-orders/from-pr/51', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_request_id\":51}', '2026-03-04 10:50:41'),
(64, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:50:41'),
(65, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 51, '/procurement/purchase-orders/51/issue', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:50:51'),
(66, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:50:52'),
(67, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:50:54'),
(68, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:51:03'),
(69, 'procurement.po_request_created', 'procurement', 1, 'po_request', 51, '/procurement/po-requests/from-po/51', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_order_id\":51}', '2026-03-04 10:51:09'),
(70, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:51:09'),
(71, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 51, '/procurement/po-requests/51/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:51:24'),
(72, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:51:24'),
(73, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:51:32'),
(74, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 51, '/receiving/create/from-po-request/51', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:51:35'),
(75, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:52:26'),
(76, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:52:40'),
(77, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:52:43'),
(78, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:52:48'),
(79, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:52:52'),
(80, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:52:56'),
(81, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:53:08'),
(82, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:53:15'),
(83, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:53:17'),
(84, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:53:25'),
(85, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:53:33'),
(86, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 10:53:45'),
(87, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 51, '/receiving/create/from-po-request/51', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 10:53:51'),
(88, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 11:07:12'),
(89, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:07:15'),
(90, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 11:07:43'),
(91, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:07:48'),
(92, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:07:52'),
(93, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:12:01'),
(94, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:15:05'),
(95, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"draft\"}', '2026-03-04 11:15:30'),
(96, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"issued\"}', '2026-03-04 11:15:36'),
(97, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"partially_received\"}', '2026-03-04 11:15:43'),
(98, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"fully_received\"}', '2026-03-04 11:15:47'),
(99, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"cancelled\"}', '2026-03-04 11:15:52'),
(100, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:16:00'),
(101, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:16:11'),
(102, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:16:26'),
(103, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:16:41'),
(104, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:19:03'),
(105, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:19:44'),
(106, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:19:48'),
(107, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:19:52'),
(108, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:20:11'),
(109, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:31:34'),
(110, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 11:31:42'),
(111, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:31:44'),
(112, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:31:46'),
(113, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:31:48'),
(114, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:31:59'),
(115, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:35:54'),
(116, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:35:58'),
(117, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 11:36:02'),
(118, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:36:05'),
(119, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:36:20'),
(120, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:42:09'),
(121, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:42:09'),
(122, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:44:09'),
(123, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:45:38'),
(124, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:47:21'),
(125, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 11:47:43'),
(126, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:47:45'),
(127, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:47:54'),
(128, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 11:48:00'),
(129, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-04 11:48:04'),
(130, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-movements', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_movements\",\"filters\":{\"date_from\":\"\",\"date_to\":\"\",\"movement_type\":\"\"}}', '2026-03-04 11:48:09'),
(131, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/issuances', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"issuances\",\"filters\":{\"status\":\"\",\"date_from\":\"\",\"date_to\":\"\"}}', '2026-03-04 11:48:28'),
(132, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/low-stock', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"low_stock\",\"filters\":{\"threshold\":10}}', '2026-03-04 11:48:33'),
(133, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/fast-moving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"fast_moving\",\"filters\":{\"date_from\":\"\",\"date_to\":\"\",\"limit\":20}}', '2026-03-04 11:48:35'),
(134, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:02:17'),
(135, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:03:11'),
(136, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:03:22'),
(137, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:03:25'),
(138, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:03:42'),
(139, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:03:49'),
(140, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:05:48'),
(141, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:06:04'),
(142, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:06:14'),
(143, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:06:18'),
(144, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:06:21'),
(145, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:06:26'),
(146, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:06:28'),
(147, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:06:31'),
(148, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:07:07'),
(149, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:08:36'),
(150, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:08:50'),
(151, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"pending\"}', '2026-03-04 13:09:27'),
(152, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 37, '/procurement/po-requests/37/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:09:39'),
(153, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:09:39'),
(154, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"pending\"}', '2026-03-04 13:09:48'),
(155, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 27, '/procurement/po-requests/27/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:10:08'),
(156, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:10:08'),
(157, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 17, '/procurement/po-requests/17/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:10:15');
INSERT INTO `analytics_events` (`id`, `event_name`, `module`, `actor_id`, `reference_type`, `reference_id`, `route`, `method`, `ip_address`, `user_agent`, `metadata_json`, `created_at`) VALUES
(158, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:10:16'),
(159, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"converted_to_receiving\"}', '2026-03-04 13:10:54'),
(160, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"rejected\"}', '2026-03-04 13:10:58'),
(161, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"closed\"}', '2026-03-04 13:11:04'),
(162, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:16:08'),
(163, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:16:15'),
(164, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:19:02'),
(165, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"pending\"}', '2026-03-04 13:19:11'),
(166, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:19:19'),
(167, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 7, '/procurement/po-requests/7/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:19:42'),
(168, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:20:46'),
(169, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 52, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:20:59'),
(170, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:21:00'),
(171, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 53, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:21:17'),
(172, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:21:18'),
(173, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 54, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:21:30'),
(174, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:21:31'),
(175, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 55, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:21:44'),
(176, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:22:28'),
(177, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:22:35'),
(178, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:22:37'),
(179, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:22:42'),
(180, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 55, '/procurement/purchase-requests/55/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:22:46'),
(181, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:22:47'),
(182, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 54, '/procurement/purchase-requests/54/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:22:49'),
(183, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:22:50'),
(184, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 53, '/procurement/purchase-requests/53/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:22:52'),
(185, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:22:52'),
(186, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 52, '/procurement/purchase-requests/52/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:22:55'),
(187, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:22:55'),
(188, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:22:59'),
(189, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:23:03'),
(190, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:23:08'),
(191, 'procurement.pr_approved', 'procurement', 1, 'approval', 2, '/procurement/approvals/2/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:23:10'),
(192, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:23:10'),
(193, 'procurement.pr_approved', 'procurement', 1, 'approval', 3, '/procurement/approvals/3/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:23:17'),
(194, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:23:17'),
(195, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:23:19'),
(196, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:23:21'),
(197, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:23:27'),
(198, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:23:31'),
(199, 'procurement.po_created', 'procurement', 1, 'purchase_order', 52, '/procurement/purchase-orders/from-pr/55', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_request_id\":55}', '2026-03-04 13:23:35'),
(200, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:23:36'),
(201, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 52, '/procurement/purchase-orders/52/issue', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:23:44'),
(202, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:23:44'),
(203, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:23:46'),
(204, 'procurement.po_created', 'procurement', 1, 'purchase_order', 53, '/procurement/purchase-orders/from-pr/54', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_request_id\":54}', '2026-03-04 13:23:54'),
(205, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:23:54'),
(206, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:23:57'),
(207, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:24:03'),
(208, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 53, '/procurement/purchase-orders/53/issue', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:24:09'),
(209, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:24:09'),
(210, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:24:14'),
(211, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:24:17'),
(212, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:24:20'),
(213, 'procurement.po_request_created', 'procurement', 1, 'po_request', 52, '/procurement/po-requests/from-po/52', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_order_id\":52}', '2026-03-04 13:24:25'),
(214, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:24:25'),
(215, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:24:30'),
(216, 'procurement.po_request_created', 'procurement', 1, 'po_request', 53, '/procurement/po-requests/from-po/53', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_order_id\":53}', '2026-03-04 13:24:32'),
(217, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:24:32'),
(218, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:24:36'),
(219, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:24:39'),
(220, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:24:43'),
(221, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:25:19'),
(222, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:25:40'),
(223, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 53, '/procurement/po-requests/53/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:25:48'),
(224, 'procurement.po_request_rejected', 'procurement', 1, 'po_request', 52, '/procurement/po-requests/52/reject', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:26:00'),
(225, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:26:09'),
(226, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:26:33'),
(227, 'procurement.po_request_created', 'procurement', 1, 'po_request', 54, '/procurement/po-requests/from-po/52', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_order_id\":52}', '2026-03-04 13:26:38'),
(228, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:26:38'),
(229, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:26:45'),
(230, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:26:48'),
(231, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:26:51'),
(232, 'procurement.po_request_created', 'procurement', 1, 'po_request', 55, '/procurement/po-requests/from-po/52', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_order_id\":52}', '2026-03-04 13:26:54'),
(233, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:26:54'),
(234, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:26:57'),
(235, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:27:06'),
(236, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 55, '/procurement/po-requests/55/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:27:21'),
(237, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 54, '/procurement/po-requests/54/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:27:22'),
(238, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:27:33'),
(239, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:27:34'),
(240, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:27:43'),
(241, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:27:44'),
(242, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:27:47'),
(243, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:27:49'),
(244, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:27:52'),
(245, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:27:55'),
(246, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:28:02'),
(247, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:28:24'),
(248, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:28:31'),
(249, 'procurement.pr_approved', 'procurement', 1, 'approval', 4, '/procurement/approvals/4/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:28:34'),
(250, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:28:34'),
(251, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:28:42'),
(252, 'procurement.pr_rejected', 'procurement', 1, 'approval', 5, '/procurement/approvals/5/reject', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:28:48'),
(253, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:28:49'),
(254, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:28:52'),
(255, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:28:58'),
(256, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:29:05'),
(257, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:29:11'),
(258, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 55, '/receiving/create/from-po-request/55', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:29:15'),
(259, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 54, '/receiving/create/from-po-request/54', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:29:20'),
(260, 'receiving.details_viewed', 'receiving', 1, 'receiving', 50, '/receiving/50', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:29:33'),
(261, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:29:38'),
(262, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:29:41'),
(263, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:29:43'),
(264, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:29:43'),
(265, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:29:45'),
(266, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:30:05'),
(267, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:30:18'),
(268, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:30:20'),
(269, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:30:26'),
(270, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:39:55'),
(271, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:40:02'),
(272, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 56, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:40:12'),
(273, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:40:12'),
(274, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 57, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:40:37'),
(275, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:40:38'),
(276, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 58, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:40:51'),
(277, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:40:51'),
(278, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 59, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:41:34'),
(279, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:41:34'),
(280, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 60, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:41:51'),
(281, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:41:52'),
(282, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 61, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:42:19'),
(283, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:42:19'),
(284, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 61, '/procurement/purchase-requests/61/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:42:46'),
(285, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:42:46'),
(286, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 60, '/procurement/purchase-requests/60/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:42:49'),
(287, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:42:50'),
(288, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:47:19'),
(289, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 59, '/procurement/purchase-requests/59/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:47:43'),
(290, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:47:56'),
(291, 'procurement.pr_cancelled', 'procurement', 1, 'purchase_request', 58, '/procurement/purchase-requests/58/cancel', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:48:06'),
(292, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 57, '/procurement/purchase-requests/57/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:48:08'),
(293, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 56, '/procurement/purchase-requests/56/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:48:11'),
(294, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:49:15'),
(295, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 62, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:49:36'),
(296, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:49:36'),
(297, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 62, '/procurement/purchase-requests/62/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:49:43'),
(298, 'procurement.pr_cancelled', 'procurement', 1, 'purchase_request', 62, '/procurement/purchase-requests/62/cancel', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:49:46'),
(299, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:49:46'),
(300, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:50:03'),
(301, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 13:50:06'),
(302, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:50:09'),
(303, 'procurement.pr_approved', 'procurement', 1, 'approval', 6, '/procurement/approvals/6/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:50:26'),
(304, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:50:26'),
(305, 'procurement.pr_approved', 'procurement', 1, 'approval', 7, '/procurement/approvals/7/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:51:35'),
(306, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 13:51:35'),
(307, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:04:23'),
(308, 'procurement.pr_approved', 'procurement', 1, 'approval', 8, '/procurement/approvals/8/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:04:33'),
(309, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:07:34'),
(310, 'procurement.pr_rejected', 'procurement', 1, 'approval', 9, '/procurement/approvals/9/reject', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:07:45'),
(311, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:08:12'),
(312, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:08:19'),
(313, 'procurement.po_request_created', 'procurement', 1, 'po_request', 56, '/procurement/po-requests/from-po/52', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_order_id\":52}', '2026-03-04 14:08:22'),
(314, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:08:22'),
(315, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:08:27');
INSERT INTO `analytics_events` (`id`, `event_name`, `module`, `actor_id`, `reference_type`, `reference_id`, `route`, `method`, `ip_address`, `user_agent`, `metadata_json`, `created_at`) VALUES
(316, 'procurement.po_request_created', 'procurement', 1, 'po_request', 57, '/procurement/po-requests/from-po/52', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_order_id\":52}', '2026-03-04 14:08:30'),
(317, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:08:30'),
(318, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:08:38'),
(319, 'procurement.po_request_created', 'procurement', 1, 'po_request', 58, '/procurement/po-requests/from-po/52', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_order_id\":52}', '2026-03-04 14:08:41'),
(320, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:08:42'),
(321, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:08:47'),
(322, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 46, '/procurement/purchase-orders/46/issue', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:08:51'),
(323, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:08:51'),
(324, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 43, '/procurement/purchase-orders/43/issue', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:08:57'),
(325, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:08:57'),
(326, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:09:03'),
(327, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:09:05'),
(328, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:12:35'),
(329, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:12:40'),
(330, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:14:15'),
(331, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 58, '/procurement/po-requests/58/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:14:19'),
(332, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:14:27'),
(333, 'procurement.pr_approved', 'procurement', 1, 'approval', 10, '/procurement/approvals/10/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:14:31'),
(334, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:14:33'),
(335, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 57, '/procurement/po-requests/57/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:14:35'),
(336, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:14:37'),
(337, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:14:41'),
(338, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:14:42'),
(339, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 56, '/procurement/po-requests/56/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:14:44'),
(340, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:14:46'),
(341, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:14:50'),
(342, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:14:53'),
(343, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:14:56'),
(344, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 58, '/receiving/create/from-po-request/58', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:15:25'),
(345, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:17:10'),
(346, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:18:14'),
(347, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 58, '/receiving/create/from-po-request/58', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:18:21'),
(348, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:20:12'),
(349, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:20:54'),
(350, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 58, '/receiving/create/from-po-request/58', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:20:58'),
(351, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:26:10'),
(352, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:26:16'),
(353, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 57, '/receiving/create/from-po-request/57', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:26:19'),
(354, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 57, '/receiving/create/from-po-request/57', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:26:47'),
(355, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 57, '/receiving/create/from-po-request/57', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:27:30'),
(356, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 57, '/receiving/create/from-po-request/57', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:29:30'),
(357, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:31:03'),
(358, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:38:26'),
(359, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:38:28'),
(360, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 58, '/receiving/create/from-po-request/58', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:38:29'),
(361, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-04 14:39:14'),
(362, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:39:16'),
(363, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:39:37'),
(364, 'receiving.details_viewed', 'receiving', 1, 'receiving', 50, '/receiving/50', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:39:47'),
(365, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 58, '/receiving/create/from-po-request/58', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:39:57'),
(366, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 58, '/receiving/create/from-po-request/58', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:42:08'),
(367, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 58, '/receiving/create/from-po-request/58', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:44:33'),
(368, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 58, '/receiving/create/from-po-request/58', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:44:35'),
(369, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 58, '/receiving/create/from-po-request/58', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:44:36'),
(370, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 58, '/receiving/create/from-po-request/58', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:44:36'),
(371, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:44:38'),
(372, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 58, '/receiving/create/from-po-request/58', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:44:40'),
(373, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:45:05'),
(374, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 63, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:45:27'),
(375, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:45:28'),
(376, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 63, '/procurement/purchase-requests/63/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:45:31'),
(377, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:45:36'),
(378, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:45:38'),
(379, 'procurement.pr_approved', 'procurement', 1, 'approval', 12, '/procurement/approvals/12/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:45:41'),
(380, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:45:43'),
(381, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:45:50'),
(382, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:45:53'),
(383, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:46:00'),
(384, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:46:02'),
(385, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:46:06'),
(386, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:46:09'),
(387, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:46:12'),
(388, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:46:14'),
(389, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:46:21'),
(390, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:46:24'),
(391, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:46:38'),
(392, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:46:46'),
(393, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:46:49'),
(394, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:46:57'),
(395, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:47:00'),
(396, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"pending\"}', '2026-03-04 14:47:05'),
(397, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:47:09'),
(398, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:47:15'),
(399, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 64, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:47:26'),
(400, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:47:26'),
(401, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 64, '/procurement/purchase-requests/64/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:47:33'),
(402, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:47:36'),
(403, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:47:38'),
(404, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"draft\"}', '2026-03-04 14:47:44'),
(405, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"partially_received\"}', '2026-03-04 14:47:53'),
(406, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"cancelled\"}', '2026-03-04 14:47:57'),
(407, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:48:00'),
(408, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:48:07'),
(409, 'procurement.pr_approved', 'procurement', 1, 'approval', 13, '/procurement/approvals/13/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:48:13'),
(410, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:48:16'),
(411, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 26, '/procurement/purchase-orders/26/issue', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:48:31'),
(412, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:48:32'),
(413, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:48:38'),
(414, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:48:50'),
(415, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:49:02'),
(416, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:49:09'),
(417, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:49:22'),
(418, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:49:33'),
(419, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:49:41'),
(420, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:49:47'),
(421, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:49:56'),
(422, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 65, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:50:08'),
(423, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:50:09'),
(424, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:50:18'),
(425, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:50:20'),
(426, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:50:22'),
(427, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:50:26'),
(428, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 65, '/procurement/purchase-requests/65/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:50:29'),
(429, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 14:50:40'),
(430, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:50:46'),
(431, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:50:50'),
(432, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 14:50:59'),
(433, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/low-stock', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"low_stock\",\"filters\":{\"threshold\":10}}', '2026-03-04 14:51:58'),
(434, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:00:40'),
(435, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:00:44'),
(436, 'procurement.po_created', 'procurement', 1, 'purchase_order', 54, '/procurement/purchase-orders/from-pr/64', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_request_id\":64}', '2026-03-04 15:00:46'),
(437, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:00:47'),
(438, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 54, '/procurement/purchase-orders/54/issue', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:00:53'),
(439, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:00:53'),
(440, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:01:00'),
(441, 'procurement.po_created', 'procurement', 1, 'purchase_order', 55, '/procurement/purchase-orders/from-pr/63', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_request_id\":63}', '2026-03-04 15:01:04'),
(442, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:01:04'),
(443, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:01:09'),
(444, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:01:10'),
(445, 'procurement.pr_approved', 'procurement', 1, 'approval', 14, '/procurement/approvals/14/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:01:14'),
(446, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:01:15'),
(447, 'procurement.po_created', 'procurement', 1, 'purchase_order', 56, '/procurement/purchase-orders/from-pr/61', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_request_id\":61}', '2026-03-04 15:01:18'),
(448, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:01:19'),
(449, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 56, '/procurement/purchase-orders/56/issue', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:01:23'),
(450, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:01:24'),
(451, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:01:30'),
(452, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:01:36'),
(453, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 58, '/receiving/create/from-po-request/58', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:01:38'),
(454, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:01:46'),
(455, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 58, '/receiving/create/from-po-request/58', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:01:52'),
(456, 'receiving.draft_created', 'receiving', 1, 'receiving', 51, '/receiving', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:01:55'),
(457, 'receiving.details_viewed', 'receiving', 1, 'receiving', 51, '/receiving/51', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:01:55'),
(458, 'receiving.validated', 'receiving', 1, 'receiving', 51, '/receiving/51/validate', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:02:10'),
(459, 'receiving.details_viewed', 'receiving', 1, 'receiving', 51, '/receiving/51', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:02:11'),
(460, 'receiving.posted', 'receiving', 1, 'receiving', 51, '/receiving/51/post', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:02:15'),
(461, 'receiving.details_viewed', 'receiving', 1, 'receiving', 51, '/receiving/51', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:02:15'),
(462, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:02:27'),
(463, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:02:30'),
(464, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:02:33'),
(465, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:02:36'),
(466, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:02:37'),
(467, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 51, '/receiving/create/from-po-request/51', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:02:40'),
(468, 'receiving.draft_created', 'receiving', 1, 'receiving', 52, '/receiving', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:02:44'),
(469, 'receiving.details_viewed', 'receiving', 1, 'receiving', 52, '/receiving/52', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:02:45'),
(470, 'receiving.posted', 'receiving', 1, 'receiving', 52, '/receiving/52/post', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:02:53'),
(471, 'receiving.details_viewed', 'receiving', 1, 'receiving', 52, '/receiving/52', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:02:54'),
(472, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-04 15:02:58'),
(473, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:03:01'),
(474, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:03:06'),
(475, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:03:10');
INSERT INTO `analytics_events` (`id`, `event_name`, `module`, `actor_id`, `reference_type`, `reference_id`, `route`, `method`, `ip_address`, `user_agent`, `metadata_json`, `created_at`) VALUES
(476, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:03:12'),
(477, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:03:25'),
(478, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 55, '/procurement/purchase-orders/55/issue', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:03:29'),
(479, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:03:29'),
(480, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:03:37'),
(481, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:03:38'),
(482, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:03:45'),
(483, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:04:15'),
(484, 'procurement.po_created', 'procurement', 1, 'purchase_order', 57, '/procurement/purchase-orders/from-pr/65', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_request_id\":65}', '2026-03-04 15:04:19'),
(485, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:04:19'),
(486, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 57, '/procurement/purchase-orders/57/issue', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:04:24'),
(487, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:04:24'),
(488, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:04:26'),
(489, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:04:29'),
(490, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:04:31'),
(491, 'procurement.po_created', 'procurement', 1, 'purchase_order', 58, '/procurement/purchase-orders/from-pr/60', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_request_id\":60}', '2026-03-04 15:04:36'),
(492, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:04:36'),
(493, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:04:45'),
(494, 'procurement.po_created', 'procurement', 1, 'purchase_order', 59, '/procurement/purchase-orders/from-pr/59', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_request_id\":59}', '2026-03-04 15:04:48'),
(495, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:04:49'),
(496, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 59, '/procurement/purchase-orders/59/issue', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:04:53'),
(497, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:04:53'),
(498, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:05:25'),
(499, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:08:16'),
(500, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 58, '/procurement/purchase-orders/58/issue', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:08:19'),
(501, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:08:19'),
(502, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:08:50'),
(503, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:08:51'),
(504, 'procurement.po_request_created', 'procurement', 1, 'po_request', 59, '/procurement/po-requests/from-po/59', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_order_id\":59}', '2026-03-04 15:08:56'),
(505, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:08:56'),
(506, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:09:11'),
(507, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 59, '/procurement/po-requests/59/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:09:30'),
(508, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:09:35'),
(509, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 59, '/receiving/create/from-po-request/59', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:09:38'),
(510, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:09:45'),
(511, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:09:49'),
(512, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:14:37'),
(513, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:14:56'),
(514, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:15:01'),
(515, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:15:06'),
(516, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:15:09'),
(517, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:15:12'),
(518, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:15:17'),
(519, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 66, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:15:32'),
(520, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:15:33'),
(521, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 66, '/procurement/purchase-requests/66/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:15:36'),
(522, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:15:36'),
(523, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:15:39'),
(524, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:15:42'),
(525, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:15:45'),
(526, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:15:49'),
(527, 'procurement.pr_approved', 'procurement', 1, 'approval', 15, '/procurement/approvals/15/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:15:56'),
(528, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:15:56'),
(529, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:15:59'),
(530, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:16:08'),
(531, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:16:14'),
(532, 'procurement.po_created', 'procurement', 1, 'purchase_order', 60, '/procurement/purchase-orders/from-pr/66', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_request_id\":66}', '2026-03-04 15:16:17'),
(533, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:16:17'),
(534, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 60, '/procurement/purchase-orders/60/issue', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:16:23'),
(535, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:16:23'),
(536, 'procurement.po_request_created', 'procurement', 1, 'po_request', 60, '/procurement/po-requests/from-po/60', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_order_id\":60}', '2026-03-04 15:16:29'),
(537, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:16:30'),
(538, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 60, '/procurement/po-requests/60/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:16:34'),
(539, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:16:35'),
(540, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:16:42'),
(541, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 60, '/receiving/create/from-po-request/60', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:16:44'),
(542, 'receiving.draft_created', 'receiving', 1, 'receiving', 53, '/receiving', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:16:47'),
(543, 'receiving.details_viewed', 'receiving', 1, 'receiving', 53, '/receiving/53', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:16:47'),
(544, 'receiving.posted', 'receiving', 1, 'receiving', 53, '/receiving/53/post', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:16:50'),
(545, 'receiving.details_viewed', 'receiving', 1, 'receiving', 53, '/receiving/53', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:16:50'),
(546, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-04 15:17:00'),
(547, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:17:23'),
(548, 'issuance.draft_created', 'inventory', 1, 'issuance', 51, '/inventory/issuance', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:17:43'),
(549, 'issuance.details_viewed', 'inventory', 1, 'issuance', 51, '/inventory/issuance/51', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:17:44'),
(550, 'issuance.submitted', 'inventory', 1, 'issuance', 51, '/inventory/issuance/51/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:17:59'),
(551, 'issuance.details_viewed', 'inventory', 1, 'issuance', 51, '/inventory/issuance/51', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:17:59'),
(552, 'issuance.approved', 'inventory', 1, 'issuance', 51, '/inventory/issuance/51/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:18:09'),
(553, 'issuance.details_viewed', 'inventory', 1, 'issuance', 51, '/inventory/issuance/51', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:18:10'),
(554, 'issuance.release_failed', 'inventory', 1, 'issuance', 51, '/inventory/issuance/51/release', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"error\":\"Insufficient stock for item 0.9% Sodium Chloride IV 1L.\"}', '2026-03-04 15:18:13'),
(555, 'issuance.details_viewed', 'inventory', 1, 'issuance', 51, '/inventory/issuance/51', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:18:13'),
(556, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:18:23'),
(557, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:20:45'),
(558, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:34:23'),
(559, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-04 15:34:29'),
(560, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:34:54'),
(561, 'issuance.draft_created', 'inventory', 1, 'issuance', 52, '/inventory/issuance', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:40:48'),
(562, 'issuance.details_viewed', 'inventory', 1, 'issuance', 52, '/inventory/issuance/52', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:40:48'),
(563, 'issuance.submitted', 'inventory', 1, 'issuance', 52, '/inventory/issuance/52/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:41:05'),
(564, 'issuance.details_viewed', 'inventory', 1, 'issuance', 52, '/inventory/issuance/52', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:41:06'),
(565, 'issuance.approved', 'inventory', 1, 'issuance', 52, '/inventory/issuance/52/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:41:17'),
(566, 'issuance.details_viewed', 'inventory', 1, 'issuance', 52, '/inventory/issuance/52', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:41:17'),
(567, 'issuance.release_failed', 'inventory', 1, 'issuance', 52, '/inventory/issuance/52/release', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"error\":\"Insufficient stock for item Amiodarone 150mg\\/3ml Ampule.\"}', '2026-03-04 15:41:23'),
(568, 'issuance.details_viewed', 'inventory', 1, 'issuance', 52, '/inventory/issuance/52', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:41:23'),
(569, 'issuance.release_failed', 'inventory', 1, 'issuance', 52, '/inventory/issuance/52/release', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"error\":\"Insufficient stock for item Amiodarone 150mg\\/3ml Ampule.\"}', '2026-03-04 15:41:28'),
(570, 'issuance.details_viewed', 'inventory', 1, 'issuance', 52, '/inventory/issuance/52', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:41:28'),
(571, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:41:31'),
(572, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-04 15:41:46'),
(573, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:42:16'),
(574, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:42:35'),
(575, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 67, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:42:55'),
(576, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:42:55'),
(577, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 67, '/procurement/purchase-requests/67/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:42:59'),
(578, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:42:59'),
(579, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:43:03'),
(580, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:43:05'),
(581, 'procurement.pr_approved', 'procurement', 1, 'approval', 18, '/procurement/approvals/18/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:43:07'),
(582, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:43:07'),
(583, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:43:09'),
(584, 'procurement.po_created', 'procurement', 1, 'purchase_order', 61, '/procurement/purchase-orders/from-pr/67', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_request_id\":67}', '2026-03-04 15:43:11'),
(585, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:43:12'),
(586, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 61, '/procurement/purchase-orders/61/issue', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:43:15'),
(587, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:43:15'),
(588, 'procurement.po_request_created', 'procurement', 1, 'po_request', 61, '/procurement/po-requests/from-po/61', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_order_id\":61}', '2026-03-04 15:43:19'),
(589, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:43:20'),
(590, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 61, '/procurement/po-requests/61/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:43:23'),
(591, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:43:24'),
(592, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:43:32'),
(593, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 61, '/receiving/create/from-po-request/61', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:43:35'),
(594, 'receiving.draft_created', 'receiving', 1, 'receiving', 54, '/receiving', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:43:38'),
(595, 'receiving.details_viewed', 'receiving', 1, 'receiving', 54, '/receiving/54', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:43:39'),
(596, 'receiving.posted', 'receiving', 1, 'receiving', 54, '/receiving/54/post', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:43:42'),
(597, 'receiving.details_viewed', 'receiving', 1, 'receiving', 54, '/receiving/54', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:43:42'),
(598, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-04 15:43:46'),
(599, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:44:03'),
(600, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-04 15:44:08'),
(601, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:44:21'),
(602, 'issuance.draft_created', 'inventory', 1, 'issuance', 53, '/inventory/issuance', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:44:43'),
(603, 'issuance.details_viewed', 'inventory', 1, 'issuance', 53, '/inventory/issuance/53', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:44:43'),
(604, 'issuance.submitted', 'inventory', 1, 'issuance', 53, '/inventory/issuance/53/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:44:48'),
(605, 'issuance.details_viewed', 'inventory', 1, 'issuance', 53, '/inventory/issuance/53', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:44:48'),
(606, 'issuance.approved', 'inventory', 1, 'issuance', 53, '/inventory/issuance/53/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:44:51'),
(607, 'issuance.details_viewed', 'inventory', 1, 'issuance', 53, '/inventory/issuance/53', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:44:52'),
(608, 'issuance.released', 'inventory', 1, 'issuance', 53, '/inventory/issuance/53/release', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:44:55'),
(609, 'issuance.details_viewed', 'inventory', 1, 'issuance', 53, '/inventory/issuance/53', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:44:55'),
(610, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:45:20'),
(611, 'issuance.details_viewed', 'inventory', 1, 'issuance', 53, '/inventory/issuance/53', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:45:32'),
(612, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:45:42'),
(613, 'issuance.details_viewed', 'inventory', 1, 'issuance', 49, '/inventory/issuance/49', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:45:47'),
(614, 'issuance.details_viewed', 'inventory', 1, 'issuance', 49, '/inventory/issuance/49', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:45:50'),
(615, 'issuance.cancelled', 'inventory', 1, 'issuance', 49, '/inventory/issuance/49/cancel', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:45:59'),
(616, 'issuance.details_viewed', 'inventory', 1, 'issuance', 49, '/inventory/issuance/49', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:46:00'),
(617, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:46:06'),
(618, 'issuance.details_viewed', 'inventory', 1, 'issuance', 44, '/inventory/issuance/44', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:46:14'),
(619, 'issuance.cancelled', 'inventory', 1, 'issuance', 44, '/inventory/issuance/44/cancel', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:46:21'),
(620, 'issuance.details_viewed', 'inventory', 1, 'issuance', 44, '/inventory/issuance/44', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:46:21'),
(621, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:46:27'),
(622, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"draft\"}', '2026-03-04 15:46:42'),
(623, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"submitted\"}', '2026-03-04 15:46:49'),
(624, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"released\"}', '2026-03-04 15:46:54'),
(625, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"rejected\"}', '2026-03-04 15:47:03'),
(626, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"draft\"}', '2026-03-04 15:47:19'),
(627, 'issuance.details_viewed', 'inventory', 1, 'issuance', 39, '/inventory/issuance/39', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:47:22'),
(628, 'issuance.cancelled', 'inventory', 1, 'issuance', 39, '/inventory/issuance/39/cancel', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:47:28'),
(629, 'issuance.details_viewed', 'inventory', 1, 'issuance', 39, '/inventory/issuance/39', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 15:47:29'),
(630, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-04 15:49:45'),
(631, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 15:49:49'),
(632, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-balance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_balance\",\"filters\":{\"has_keyword\":false}}', '2026-03-04 15:57:36'),
(633, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-movements', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_movements\",\"filters\":{\"date_from\":\"\",\"date_to\":\"\",\"movement_type\":\"\"}}', '2026-03-04 15:57:43'),
(634, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-movements', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_movements\",\"filters\":{\"date_from\":\"\",\"date_to\":\"\",\"movement_type\":\"\"}}', '2026-03-04 15:58:00'),
(635, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-movements', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_movements\",\"filters\":{\"date_from\":\"\",\"date_to\":\"\",\"movement_type\":\"issuance\"}}', '2026-03-04 15:58:13');
INSERT INTO `analytics_events` (`id`, `event_name`, `module`, `actor_id`, `reference_type`, `reference_id`, `route`, `method`, `ip_address`, `user_agent`, `metadata_json`, `created_at`) VALUES
(636, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/issuances', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"issuances\",\"filters\":{\"status\":\"\",\"date_from\":\"\",\"date_to\":\"\"}}', '2026-03-04 15:58:25'),
(637, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-movements', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_movements\",\"filters\":{\"date_from\":\"\",\"date_to\":\"\",\"movement_type\":\"\"}}', '2026-03-04 15:58:30'),
(638, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-balance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_balance\",\"filters\":{\"has_keyword\":false}}', '2026-03-04 15:59:10'),
(639, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/issuances', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"issuances\",\"filters\":{\"status\":\"\",\"date_from\":\"\",\"date_to\":\"\"}}', '2026-03-04 15:59:14'),
(640, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/low-stock', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"low_stock\",\"filters\":{\"threshold\":10}}', '2026-03-04 15:59:16'),
(641, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/low-stock', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"low_stock\",\"filters\":{\"threshold\":10}}', '2026-03-04 15:59:30'),
(642, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/low-stock', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"low_stock\",\"filters\":{\"threshold\":100}}', '2026-03-04 15:59:37'),
(643, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/fast-moving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"fast_moving\",\"filters\":{\"date_from\":\"\",\"date_to\":\"\",\"limit\":20}}', '2026-03-04 15:59:44'),
(644, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/low-stock', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"low_stock\",\"filters\":{\"threshold\":10}}', '2026-03-04 15:59:54'),
(645, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:06:42'),
(646, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:06:46'),
(647, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:06:49'),
(648, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:06:53'),
(649, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:08:22'),
(650, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:08:38'),
(651, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:09:31'),
(652, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-04 16:09:34'),
(653, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-balance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_balance\",\"filters\":{\"has_keyword\":false}}', '2026-03-04 16:11:04'),
(654, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-movements', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_movements\",\"filters\":{\"date_from\":\"\",\"date_to\":\"\",\"movement_type\":\"\"}}', '2026-03-04 16:11:06'),
(655, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:11:07'),
(656, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-04 16:11:09'),
(657, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:11:10'),
(658, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:11:12'),
(659, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:11:14'),
(660, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:11:16'),
(661, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:11:27'),
(662, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:12:46'),
(663, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 68, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:28:34'),
(664, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:28:35'),
(665, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:31:04'),
(666, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:31:07'),
(667, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"draft\"}', '2026-03-04 16:31:14'),
(668, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:31:21'),
(669, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:31:24'),
(670, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:31:37'),
(671, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:34:27'),
(672, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:34:31'),
(673, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:34:31'),
(674, 'procurement.pr_created', 'procurement', 1, 'purchase_request', 69, '/procurement/purchase-requests', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:36:46'),
(675, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:36:47'),
(676, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 69, '/procurement/purchase-requests/69/submit', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:37:51'),
(677, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:37:51'),
(678, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:37:59'),
(679, 'procurement.pr_approved', 'procurement', 1, 'approval', 20, '/procurement/approvals/20/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:38:04'),
(680, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:38:05'),
(681, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:38:07'),
(682, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:38:10'),
(683, 'procurement.po_created', 'procurement', 1, 'purchase_order', 62, '/procurement/purchase-orders/from-pr/69', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_request_id\":69}', '2026-03-04 16:38:12'),
(684, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:38:12'),
(685, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 62, '/procurement/purchase-orders/62/issue', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:38:18'),
(686, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:38:19'),
(687, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:38:25'),
(688, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:38:29'),
(689, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:38:32'),
(690, 'procurement.po_request_created', 'procurement', 1, 'po_request', 62, '/procurement/po-requests/from-po/62', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_order_id\":62}', '2026-03-04 16:38:35'),
(691, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:38:35'),
(692, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 62, '/procurement/po-requests/62/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:38:39'),
(693, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:38:40'),
(694, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:38:46'),
(695, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 62, '/receiving/create/from-po-request/62', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:38:49'),
(696, 'receiving.draft_created', 'receiving', 1, 'receiving', 55, '/receiving', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:38:54'),
(697, 'receiving.details_viewed', 'receiving', 1, 'receiving', 55, '/receiving/55', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:38:54'),
(698, 'receiving.posted', 'receiving', 1, 'receiving', 55, '/receiving/55/post', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:38:59'),
(699, 'receiving.details_viewed', 'receiving', 1, 'receiving', 55, '/receiving/55', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:38:59'),
(700, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:39:22'),
(701, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:39:25'),
(702, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:39:28'),
(703, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:39:28'),
(704, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-04 16:39:31'),
(705, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 59, '/receiving/create/from-po-request/59', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:39:34'),
(706, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 59, '/receiving/create/from-po-request/59', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:50:01'),
(707, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 59, '/receiving/create/from-po-request/59', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 16:57:54'),
(708, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 59, '/receiving/create/from-po-request/59', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 17:01:21'),
(709, 'receiving.draft_created', 'receiving', 1, 'receiving', 56, '/receiving', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 17:02:00'),
(710, 'receiving.details_viewed', 'receiving', 1, 'receiving', 56, '/receiving/56', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 17:02:01'),
(711, 'receiving.posted', 'receiving', 1, 'receiving', 56, '/receiving/56/post', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 17:02:10'),
(712, 'receiving.details_viewed', 'receiving', 1, 'receiving', 56, '/receiving/56', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-04 17:02:10'),
(713, 'auth.login_success', 'auth', 1, 'user', 1, '/login', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 08:12:50'),
(714, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-10 08:17:55'),
(715, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-10 08:18:03'),
(716, 'procurement.po_request_created', 'procurement', 1, 'po_request', 63, '/procurement/po-requests/from-po/56', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_order_id\":56}', '2026-03-10 08:19:26'),
(717, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-10 08:19:26'),
(718, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 63, '/procurement/po-requests/63/approve', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 08:19:43'),
(719, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-10 08:19:43'),
(720, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-10 08:20:37'),
(721, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-10 08:20:46'),
(722, 'auth.logout', 'auth', 1, 'user', 1, '/logout', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 08:39:43'),
(723, 'auth.login_success', 'auth', 1, 'user', 1, '/login', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 08:43:28'),
(724, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-10 08:49:29'),
(725, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-10 09:41:15'),
(726, 'auth.logout', 'auth', 1, 'user', 1, '/logout', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 10:52:18'),
(727, 'auth.logout', 'auth', 1, 'user', 1, '/logout', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 10:52:19'),
(728, 'auth.login_success', 'auth', 3, 'user', 3, '/login', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 10:53:00'),
(729, 'receiving.list_viewed', 'receiving', 3, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-10 10:53:01'),
(730, 'receiving.list_viewed', 'receiving', 3, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-10 10:53:12'),
(731, 'receiving.list_viewed', 'receiving', 3, NULL, NULL, '/receiving', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-10 10:53:17'),
(732, 'procurement.po_list_viewed', 'procurement', 3, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-10 10:53:29'),
(733, 'inventory.issuance_list_viewed', 'inventory', 3, NULL, NULL, '/inventory/issuance', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-10 10:53:45'),
(734, 'procurement.pr_list_viewed', 'procurement', 3, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-10 10:57:03'),
(735, 'auth.logout', 'auth', 3, 'user', 3, '/logout', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 11:01:46'),
(736, 'auth.login_success', 'auth', 1, 'user', 1, '/login', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 11:02:01'),
(737, 'auth.logout', 'auth', 1, 'user', 1, '/logout', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 11:02:56'),
(738, 'auth.signup_success', 'auth', 4, 'user', 4, '/signup', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 11:05:22'),
(739, 'procurement.pr_list_viewed', 'procurement', 4, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-10 11:05:23'),
(740, 'auth.logout', 'auth', 4, 'user', 4, '/logout', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 11:05:57'),
(741, 'auth.login_success', 'auth', 1, 'user', 1, '/login', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 11:06:20'),
(742, 'auth.login_success', 'auth', 1, 'user', 1, '/login', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 13:11:42'),
(743, 'auth.logout', 'auth', 1, 'user', 1, '/logout', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 13:16:59'),
(744, 'auth.login_success', 'auth', 1, 'user', 1, '/login', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 13:17:28'),
(745, 'auth.login_success', 'auth', 1, 'user', 1, '/login', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 15:35:25'),
(746, 'admin.create_user', 'admin', 1, 'user', 1, '/admin/users', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 15:36:04'),
(747, 'admin.update_user', 'admin', 1, 'user', 1, '/admin/users/5', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 15:36:39'),
(748, 'auth.logout', 'auth', 1, 'user', 1, '/logout', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 16:04:35'),
(749, 'auth.login_failed', 'auth', NULL, NULL, NULL, '/login', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"identifier_type\":\"email\"}', '2026-03-10 16:05:18'),
(750, 'auth.login_failed', 'auth', NULL, NULL, NULL, '/login', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"identifier_type\":\"email\"}', '2026-03-10 16:05:28'),
(751, 'auth.login_success', 'auth', 1, 'user', 1, '/login', 'POST', 'h:363baea9cba210afac6d7a556fca596e30c46333', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 16:05:50'),
(752, 'auth.login_failed', 'auth', NULL, NULL, NULL, '/login', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"identifier_type\":\"email\"}', '2026-03-10 16:17:40'),
(753, 'auth.login_success', 'auth', 1, 'user', 1, '/login', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 16:17:57'),
(754, 'auth.login_failed', 'auth', NULL, NULL, NULL, '/login', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"identifier_type\":\"email\"}', '2026-03-10 16:18:26'),
(755, 'auth.login_success', 'auth', 3, 'user', 3, '/login', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 16:18:42'),
(756, 'receiving.list_viewed', 'receiving', 3, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-10 16:18:43'),
(757, 'auth.login_success', 'auth', 5, 'user', 5, '/login', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 16:19:06'),
(758, 'auth.logout', 'auth', 5, 'user', 5, '/logout', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 16:19:33'),
(759, 'auth.login_success', 'auth', 2, 'user', 2, '/login', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-10 16:20:07'),
(760, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-10 16:20:08'),
(761, 'auth.login_failed', 'auth', NULL, NULL, NULL, '/login', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"identifier_type\":\"username\"}', '2026-03-12 08:09:05'),
(762, 'auth.login_failed', 'auth', NULL, NULL, NULL, '/login', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"identifier_type\":\"email\"}', '2026-03-12 08:09:17'),
(763, 'auth.login_success', 'auth', 1, 'user', 1, '/login', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:09:36'),
(764, 'auth.login_success', 'auth', 3, 'user', 3, '/login', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:09:51'),
(765, 'receiving.list_viewed', 'receiving', 3, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:09:53'),
(766, 'auth.login_success', 'auth', 2, 'user', 2, '/login', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:10:05'),
(767, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:10:06'),
(768, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:11:34'),
(769, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:11:38'),
(770, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/fast-moving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"fast_moving\",\"filters\":{\"date_from\":\"\",\"date_to\":\"\",\"limit\":20}}', '2026-03-12 08:11:49'),
(771, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:13:03'),
(772, 'procurement.pr_created', 'procurement', 2, 'purchase_request', 70, '/procurement/purchase-requests', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:21:33'),
(773, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:21:33'),
(774, 'procurement.pr_submitted', 'procurement', 2, 'purchase_request', 70, '/procurement/purchase-requests/70/submit', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:21:42'),
(775, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:21:42'),
(776, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:22:13'),
(777, 'procurement.pr_approved', 'procurement', 1, 'approval', 21, '/procurement/approvals/21/approve', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:22:29'),
(778, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:22:30'),
(779, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:22:55'),
(780, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:23:00'),
(781, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:23:40'),
(782, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:23:44'),
(783, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:23:47'),
(784, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:24:06'),
(785, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:24:25'),
(786, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:25:39'),
(787, 'receiving.list_viewed', 'receiving', 3, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:25:46'),
(788, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:26:07'),
(789, 'procurement.po_created', 'procurement', 1, 'purchase_order', 63, '/procurement/purchase-orders/from-pr/70', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_request_id\":70}', '2026-03-12 08:26:15'),
(790, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:26:15'),
(791, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:26:37'),
(792, 'receiving.list_viewed', 'receiving', 3, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Mobile Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:31:03'),
(793, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 63, '/procurement/purchase-orders/63/issue', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:31:14'),
(794, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:31:14'),
(795, 'procurement.po_request_created', 'procurement', 1, 'po_request', 64, '/procurement/po-requests/from-po/63', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_order_id\":63}', '2026-03-12 08:31:22'),
(796, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:31:22'),
(797, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 64, '/procurement/po-requests/64/approve', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:31:50'),
(798, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:31:50');
INSERT INTO `analytics_events` (`id`, `event_name`, `module`, `actor_id`, `reference_type`, `reference_id`, `route`, `method`, `ip_address`, `user_agent`, `metadata_json`, `created_at`) VALUES
(799, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:31:56'),
(800, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:32:01'),
(801, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:32:07'),
(802, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:32:10'),
(803, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:34:30'),
(804, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:35:08'),
(805, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:35:16'),
(806, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:35:29'),
(807, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:36:24'),
(808, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"approved\"}', '2026-03-12 08:36:36'),
(809, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:37:10'),
(810, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:37:32'),
(811, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:37:37'),
(812, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:37:38'),
(813, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:37:39'),
(814, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:39:16'),
(815, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:39:28'),
(816, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 64, '/receiving/create/from-po-request/64', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:39:31'),
(817, 'receiving.draft_created', 'receiving', 1, 'receiving', 57, '/receiving', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:40:58'),
(818, 'receiving.details_viewed', 'receiving', 1, 'receiving', 57, '/receiving/57', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:40:59'),
(819, 'receiving.validated', 'receiving', 1, 'receiving', 57, '/receiving/57/validate', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:41:09'),
(820, 'receiving.details_viewed', 'receiving', 1, 'receiving', 57, '/receiving/57', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:41:09'),
(821, 'receiving.posted', 'receiving', 1, 'receiving', 57, '/receiving/57/post', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:41:21'),
(822, 'receiving.details_viewed', 'receiving', 1, 'receiving', 57, '/receiving/57', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:41:22'),
(823, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-12 08:41:44'),
(824, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:41:47'),
(825, 'receiving.details_viewed', 'receiving', 1, 'receiving', 57, '/receiving/57', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:41:54'),
(826, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-12 08:43:30'),
(827, 'inventory.stock_viewed', 'inventory', 1, 'inventory_stock', 77, '/inventory/quantities/77', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:46:39'),
(828, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:47:17'),
(829, 'issuance.draft_created', 'inventory', 1, 'issuance', 54, '/inventory/issuance', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:48:23'),
(830, 'issuance.details_viewed', 'inventory', 1, 'issuance', 54, '/inventory/issuance/54', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:48:24'),
(831, 'issuance.submitted', 'inventory', 1, 'issuance', 54, '/inventory/issuance/54/submit', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:48:39'),
(832, 'issuance.details_viewed', 'inventory', 1, 'issuance', 54, '/inventory/issuance/54', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:48:40'),
(833, 'issuance.approved', 'inventory', 1, 'issuance', 54, '/inventory/issuance/54/approve', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:48:55'),
(834, 'issuance.details_viewed', 'inventory', 1, 'issuance', 54, '/inventory/issuance/54', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:48:56'),
(835, 'issuance.released', 'inventory', 1, 'issuance', 54, '/inventory/issuance/54/release', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:49:06'),
(836, 'issuance.details_viewed', 'inventory', 1, 'issuance', 54, '/inventory/issuance/54', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:49:06'),
(837, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-balance', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_balance\",\"filters\":{\"has_keyword\":false}}', '2026-03-12 08:49:52'),
(838, 'receiving.conversion_viewed', 'receiving', 3, 'po_request', 63, '/receiving/create/from-po-request/63', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 08:50:19'),
(839, 'receiving.list_viewed', 'receiving', 3, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:50:24'),
(840, 'inventory.issuance_list_viewed', 'inventory', 3, NULL, NULL, '/inventory/issuance', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:55:01'),
(841, 'report.viewed', 'reports', 3, 'report', NULL, '/reports/stock-balance', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_balance\",\"filters\":{\"has_keyword\":false}}', '2026-03-12 08:55:05'),
(842, 'receiving.list_viewed', 'receiving', 3, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:55:17'),
(843, 'inventory.quantities_viewed', 'inventory', 3, NULL, NULL, '/inventory/quantities', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-12 08:56:24'),
(844, 'receiving.list_viewed', 'receiving', 3, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 08:56:27'),
(845, 'receiving.list_viewed', 'receiving', 3, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:03:19'),
(846, 'receiving.conversion_viewed', 'receiving', 3, 'po_request', 63, '/receiving/create/from-po-request/63', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:03:22'),
(847, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:10:20'),
(848, 'procurement.pr_created', 'procurement', 2, 'purchase_request', 71, '/procurement/purchase-requests', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:13:24'),
(849, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:13:24'),
(850, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:14:32'),
(851, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:14:42'),
(852, 'procurement.pr_created', 'procurement', 2, 'purchase_request', 72, '/procurement/purchase-requests', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:16:22'),
(853, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:16:22'),
(854, 'procurement.pr_submitted', 'procurement', 2, 'purchase_request', 71, '/procurement/purchase-requests/71/submit', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:20:56'),
(855, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:20:56'),
(856, 'procurement.pr_submitted', 'procurement', 2, 'purchase_request', 72, '/procurement/purchase-requests/72/submit', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:21:13'),
(857, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:21:13'),
(858, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:21:19'),
(859, 'procurement.pr_approved', 'procurement', 1, 'approval', 23, '/procurement/approvals/23/approve', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:22:26'),
(860, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:22:26'),
(861, 'procurement.pr_approved', 'procurement', 1, 'approval', 24, '/procurement/approvals/24/approve', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:22:32'),
(862, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:22:33'),
(863, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:22:39'),
(864, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:23:00'),
(865, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:23:10'),
(866, 'procurement.po_created', 'procurement', 1, 'purchase_order', 64, '/procurement/purchase-orders/from-pr/71', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_request_id\":71}', '2026-03-12 09:27:11'),
(867, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:27:12'),
(868, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 64, '/procurement/purchase-orders/64/issue', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:27:19'),
(869, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:27:19'),
(870, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:27:25'),
(871, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:27:28'),
(872, 'procurement.po_request_created', 'procurement', 1, 'po_request', 65, '/procurement/po-requests/from-po/64', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_order_id\":64}', '2026-03-12 09:27:32'),
(873, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:27:33'),
(874, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 65, '/procurement/po-requests/65/approve', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:27:56'),
(875, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:27:57'),
(876, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:28:01'),
(877, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:28:06'),
(878, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:30:38'),
(879, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:30:42'),
(880, 'procurement.po_created', 'procurement', 1, 'purchase_order', 65, '/procurement/purchase-orders/from-pr/72', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_request_id\":72}', '2026-03-12 09:30:47'),
(881, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:30:48'),
(882, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 65, '/procurement/purchase-orders/65/issue', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:30:54'),
(883, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:30:54'),
(884, 'procurement.po_request_created', 'procurement', 1, 'po_request', 66, '/procurement/po-requests/from-po/65', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_order_id\":65}', '2026-03-12 09:31:02'),
(885, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:31:03'),
(886, 'procurement.po_request_approved', 'procurement', 1, 'po_request', 66, '/procurement/po-requests/66/approve', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:31:13'),
(887, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:31:13'),
(888, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:31:19'),
(889, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:31:25'),
(890, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:31:30'),
(891, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:32:00'),
(892, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:32:05'),
(893, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:32:15'),
(894, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:32:18'),
(895, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:32:21'),
(896, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:32:24'),
(897, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:32:28'),
(898, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:32:32'),
(899, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 65, '/receiving/create/from-po-request/65', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:32:42'),
(900, 'receiving.draft_created', 'receiving', 1, 'receiving', 58, '/receiving', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:33:57'),
(901, 'receiving.details_viewed', 'receiving', 1, 'receiving', 58, '/receiving/58', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:33:58'),
(902, 'receiving.validated', 'receiving', 1, 'receiving', 58, '/receiving/58/validate', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:34:02'),
(903, 'receiving.details_viewed', 'receiving', 1, 'receiving', 58, '/receiving/58', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:34:02'),
(904, 'receiving.posted', 'receiving', 1, 'receiving', 58, '/receiving/58/post', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:34:49'),
(905, 'receiving.details_viewed', 'receiving', 1, 'receiving', 58, '/receiving/58', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:34:49'),
(906, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:35:09'),
(907, 'receiving.conversion_viewed', 'receiving', 1, 'po_request', 66, '/receiving/create/from-po-request/66', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:35:12'),
(908, 'receiving.draft_created', 'receiving', 1, 'receiving', 59, '/receiving', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:36:24'),
(909, 'receiving.details_viewed', 'receiving', 1, 'receiving', 59, '/receiving/59', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:36:25'),
(910, 'receiving.validated', 'receiving', 1, 'receiving', 59, '/receiving/59/validate', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:36:29'),
(911, 'receiving.details_viewed', 'receiving', 1, 'receiving', 59, '/receiving/59', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:36:30'),
(912, 'receiving.posted', 'receiving', 1, 'receiving', 59, '/receiving/59/post', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:36:36'),
(913, 'receiving.details_viewed', 'receiving', 1, 'receiving', 59, '/receiving/59', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:36:37'),
(914, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:36:42'),
(915, 'receiving.details_viewed', 'receiving', 1, 'receiving', 58, '/receiving/58', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:36:48'),
(916, 'receiving.details_viewed', 'receiving', 1, 'receiving', 59, '/receiving/59', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:36:53'),
(917, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-12 09:36:59'),
(918, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:37:52'),
(919, 'issuance.draft_created', 'inventory', 1, 'issuance', 55, '/inventory/issuance', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:39:15'),
(920, 'issuance.details_viewed', 'inventory', 1, 'issuance', 55, '/inventory/issuance/55', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:39:15'),
(921, 'issuance.submitted', 'inventory', 1, 'issuance', 55, '/inventory/issuance/55/submit', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:39:29'),
(922, 'issuance.details_viewed', 'inventory', 1, 'issuance', 55, '/inventory/issuance/55', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:39:29'),
(923, 'issuance.approved', 'inventory', 1, 'issuance', 55, '/inventory/issuance/55/approve', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:39:44'),
(924, 'issuance.details_viewed', 'inventory', 1, 'issuance', 55, '/inventory/issuance/55', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:39:44'),
(925, 'issuance.released', 'inventory', 1, 'issuance', 55, '/inventory/issuance/55/release', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:39:51'),
(926, 'issuance.details_viewed', 'inventory', 1, 'issuance', 55, '/inventory/issuance/55', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:39:52'),
(927, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:40:49'),
(928, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-12 09:40:54'),
(929, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:41:38'),
(930, 'issuance.details_viewed', 'inventory', 1, 'issuance', 55, '/inventory/issuance/55', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:41:44'),
(931, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-12 09:41:54'),
(932, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:41:58'),
(933, 'issuance.draft_created', 'inventory', 1, 'issuance', 56, '/inventory/issuance', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:43:28'),
(934, 'issuance.details_viewed', 'inventory', 1, 'issuance', 56, '/inventory/issuance/56', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:43:29'),
(935, 'issuance.submitted', 'inventory', 1, 'issuance', 56, '/inventory/issuance/56/submit', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:43:39'),
(936, 'issuance.details_viewed', 'inventory', 1, 'issuance', 56, '/inventory/issuance/56', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:43:39'),
(937, 'issuance.approved', 'inventory', 1, 'issuance', 56, '/inventory/issuance/56/approve', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:43:56'),
(938, 'issuance.details_viewed', 'inventory', 1, 'issuance', 56, '/inventory/issuance/56', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:43:57'),
(939, 'issuance.released', 'inventory', 1, 'issuance', 56, '/inventory/issuance/56/release', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:44:05'),
(940, 'issuance.details_viewed', 'inventory', 1, 'issuance', 56, '/inventory/issuance/56', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:44:05'),
(941, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 09:56:02'),
(942, 'issuance.details_viewed', 'inventory', 1, 'issuance', 55, '/inventory/issuance/55', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 09:56:08'),
(943, 'inventory.quantities_viewed', 'inventory', 1, NULL, NULL, '/inventory/quantities', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"keyword_used\":false}', '2026-03-12 09:58:38'),
(944, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 10:00:01'),
(945, 'receiving.list_viewed', 'receiving', 1, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 10:00:06'),
(946, 'receiving.details_viewed', 'receiving', 1, 'receiving', 58, '/receiving/58', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 10:00:12'),
(947, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 10:18:09'),
(948, 'issuance.details_viewed', 'inventory', 1, 'issuance', 56, '/inventory/issuance/56', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 10:18:25'),
(949, 'inventory.issuance_list_viewed', 'inventory', 1, NULL, NULL, '/inventory/issuance', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 10:18:32'),
(950, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-balance', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_balance\",\"filters\":{\"has_keyword\":false}}', '2026-03-12 10:18:35'),
(951, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 10:37:52'),
(952, 'procurement.pr_list_viewed', 'procurement', 3, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 10:37:57'),
(953, 'procurement.pr_list_viewed', 'procurement', 3, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 10:38:06'),
(954, 'procurement.pr_list_viewed', 'procurement', 3, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 10:39:27'),
(955, 'procurement.pr_created', 'procurement', 3, 'purchase_request', 73, '/procurement/purchase-requests', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 10:39:45'),
(956, 'procurement.pr_list_viewed', 'procurement', 3, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 10:39:45'),
(957, 'procurement.pr_submitted', 'procurement', 3, 'purchase_request', 73, '/procurement/purchase-requests/73/submit', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 10:39:50'),
(958, 'procurement.pr_list_viewed', 'procurement', 3, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 10:39:50'),
(959, 'procurement.approvals_viewed', 'procurement', 3, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 10:39:57'),
(960, 'procurement.pr_approved', 'procurement', 3, 'approval', 27, '/procurement/approvals/27/approve', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 10:40:00'),
(961, 'procurement.approvals_viewed', 'procurement', 3, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 10:40:00');
INSERT INTO `analytics_events` (`id`, `event_name`, `module`, `actor_id`, `reference_type`, `reference_id`, `route`, `method`, `ip_address`, `user_agent`, `metadata_json`, `created_at`) VALUES
(962, 'procurement.pr_list_viewed', 'procurement', 3, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 10:40:03'),
(963, 'auth.login_success', 'auth', 3, 'user', 3, '/login', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 13:25:32'),
(964, 'receiving.list_viewed', 'receiving', 3, NULL, NULL, '/receiving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 13:25:33'),
(965, 'auth.login_success', 'auth', 1, 'user', 1, '/login', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 13:25:49'),
(966, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 13:40:24'),
(967, 'procurement.po_request_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/po-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 13:40:26'),
(968, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-balance', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_balance\",\"filters\":{\"has_keyword\":false}}', '2026-03-12 14:05:21'),
(969, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/fast-moving', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"fast_moving\",\"filters\":{\"date_from\":\"\",\"date_to\":\"\",\"limit\":20}}', '2026-03-12 14:05:27'),
(970, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-movements', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_movements\",\"filters\":{\"date_from\":\"\",\"date_to\":\"\",\"movement_type\":\"\"}}', '2026-03-12 14:23:03'),
(971, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-movements', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_movements\",\"filters\":{\"date_from\":\"\",\"date_to\":\"\",\"movement_type\":\"\"}}', '2026-03-12 14:35:19'),
(972, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-movements', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_movements\",\"filters\":{\"date_from\":\"\",\"date_to\":\"\",\"movement_type\":\"\"}}', '2026-03-12 14:36:29'),
(973, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-movements', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_movements\",\"filters\":{\"date_from\":\"\",\"date_to\":\"\",\"movement_type\":\"\"}}', '2026-03-12 14:37:48'),
(974, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/stock-balance', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"stock_balance\",\"filters\":{\"has_keyword\":false}}', '2026-03-12 14:38:10'),
(975, 'report.viewed', 'reports', 1, 'report', NULL, '/reports/issuances', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"report\":\"issuances\",\"filters\":{\"status\":\"\",\"date_from\":\"\",\"date_to\":\"\"}}', '2026-03-12 14:38:13'),
(976, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 14:38:20'),
(977, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:38:23'),
(978, 'procurement.pr_details_viewed', 'procurement', 1, 'purchase_request', 73, '/procurement/purchase-requests/73', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 14:38:29'),
(979, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 14:38:38'),
(980, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:38:41'),
(981, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 14:38:43'),
(982, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 14:38:45'),
(983, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:38:46'),
(984, 'procurement.po_created', 'procurement', 1, 'purchase_order', 66, '/procurement/purchase-orders/from-pr/73', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"purchase_request_id\":73}', '2026-03-12 14:38:53'),
(985, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:38:53'),
(986, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 14:38:56'),
(987, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:38:59'),
(988, 'procurement.po_issued', 'procurement', 1, 'purchase_order', 66, '/procurement/purchase-orders/66/issue', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 14:39:03'),
(989, 'procurement.po_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-orders', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:39:03'),
(990, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 14:39:07'),
(991, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:39:09'),
(992, 'auth.login_success', 'auth', 2, 'user', 2, '/login', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 14:39:24'),
(993, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:39:25'),
(994, 'procurement.pr_created', 'procurement', 2, 'purchase_request', 74, '/procurement/purchase-requests', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 14:40:10'),
(995, 'procurement.pr_list_viewed', 'procurement', 2, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:40:10'),
(996, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:40:17'),
(997, 'procurement.pr_submitted', 'procurement', 1, 'purchase_request', 74, '/procurement/purchase-requests/74/submit', 'POST', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 14:40:23'),
(998, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:40:24'),
(999, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 14:40:27'),
(1000, 'procurement.pr_details_viewed', 'procurement', 1, 'purchase_request', 74, '/procurement/purchase-requests/74', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 14:40:35'),
(1001, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:40:41'),
(1002, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 14:40:46'),
(1003, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:41:07'),
(1004, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 14:41:39'),
(1005, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 14:43:33'),
(1006, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:43:49'),
(1007, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:50:04'),
(1008, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:54:03'),
(1009, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:56:04'),
(1010, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 14:56:05'),
(1011, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 15:02:10'),
(1012, 'procurement.pr_details_viewed', 'procurement', 1, 'purchase_request', 68, '/procurement/purchase-requests/68', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 15:02:17'),
(1013, 'procurement.pr_details_viewed', 'procurement', 1, 'purchase_request', 68, '/procurement/purchase-requests/68', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 15:02:17'),
(1014, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 15:02:19'),
(1015, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 15:04:26'),
(1016, 'procurement.approvals_viewed', 'procurement', 1, NULL, NULL, '/procurement/approvals/pending', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', NULL, '2026-03-12 15:06:55'),
(1017, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 15:09:14'),
(1018, 'procurement.pr_list_viewed', 'procurement', 1, NULL, NULL, '/procurement/purchase-requests', 'GET', 'h:4b84b15bff6ee5796152495a230e45e3d7e947d9', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '{\"status_filter\":\"all\"}', '2026-03-12 15:19:26');

-- --------------------------------------------------------

--
-- Table structure for table `approvals`
--

CREATE TABLE `approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_type` varchar(50) NOT NULL,
  `reference_id` bigint(20) UNSIGNED NOT NULL,
  `approval_level` int(3) UNSIGNED NOT NULL DEFAULT 1,
  `approver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `decision` varchar(20) NOT NULL DEFAULT 'pending',
  `decision_at` datetime DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approvals`
--

INSERT INTO `approvals` (`id`, `reference_type`, `reference_id`, `approval_level`, `approver_id`, `decision`, `decision_at`, `comments`, `created_at`, `updated_at`) VALUES
(1, 'purchase_request', 51, 1, 1, 'approved', '2026-03-04 10:49:17', 'Good', '2026-03-04 10:48:50', '2026-03-04 10:49:17'),
(2, 'purchase_request', 55, 1, 1, 'approved', '2026-03-04 13:23:10', NULL, '2026-03-04 13:22:46', '2026-03-04 13:23:10'),
(3, 'purchase_request', 54, 1, 1, 'approved', '2026-03-04 13:23:17', NULL, '2026-03-04 13:22:49', '2026-03-04 13:23:17'),
(4, 'purchase_request', 53, 1, 1, 'approved', '2026-03-04 13:28:34', NULL, '2026-03-04 13:22:52', '2026-03-04 13:28:34'),
(5, 'purchase_request', 52, 1, 1, 'rejected', '2026-03-04 13:28:48', 'yuyt', '2026-03-04 13:22:55', '2026-03-04 13:28:48'),
(6, 'purchase_request', 61, 1, 1, 'approved', '2026-03-04 13:50:26', 'Good', '2026-03-04 13:42:46', '2026-03-04 13:50:26'),
(7, 'purchase_request', 60, 1, 1, 'approved', '2026-03-04 13:51:35', NULL, '2026-03-04 13:42:49', '2026-03-04 13:51:35'),
(8, 'purchase_request', 59, 1, 1, 'approved', '2026-03-04 14:04:33', NULL, '2026-03-04 13:47:43', '2026-03-04 14:04:33'),
(9, 'purchase_request', 57, 1, 1, 'rejected', '2026-03-04 14:07:45', 'ffasf', '2026-03-04 13:48:08', '2026-03-04 14:07:45'),
(10, 'purchase_request', 56, 1, 1, 'approved', '2026-03-04 14:14:31', NULL, '2026-03-04 13:48:11', '2026-03-04 14:14:31'),
(11, 'purchase_request', 62, 1, 1, 'rejected', '2026-03-04 13:49:46', 'Cancelled by user.', '2026-03-04 13:49:43', '2026-03-04 13:49:46'),
(12, 'purchase_request', 63, 1, 1, 'approved', '2026-03-04 14:45:41', NULL, '2026-03-04 14:45:31', '2026-03-04 14:45:41'),
(13, 'purchase_request', 64, 1, 1, 'approved', '2026-03-04 14:48:13', NULL, '2026-03-04 14:47:33', '2026-03-04 14:48:13'),
(14, 'purchase_request', 65, 1, 1, 'approved', '2026-03-04 15:01:14', NULL, '2026-03-04 14:50:29', '2026-03-04 15:01:14'),
(15, 'purchase_request', 66, 1, 1, 'approved', '2026-03-04 15:15:56', NULL, '2026-03-04 15:15:36', '2026-03-04 15:15:56'),
(16, 'issuance', 51, 1, 1, 'approved', '2026-03-04 15:18:09', NULL, '2026-03-04 15:17:59', '2026-03-04 15:18:09'),
(17, 'issuance', 52, 1, 1, 'approved', '2026-03-04 15:41:17', NULL, '2026-03-04 15:41:05', '2026-03-04 15:41:17'),
(18, 'purchase_request', 67, 1, 1, 'approved', '2026-03-04 15:43:07', NULL, '2026-03-04 15:42:59', '2026-03-04 15:43:07'),
(19, 'issuance', 53, 1, 1, 'approved', '2026-03-04 15:44:51', NULL, '2026-03-04 15:44:48', '2026-03-04 15:44:51'),
(20, 'purchase_request', 69, 1, 1, 'approved', '2026-03-04 16:38:04', NULL, '2026-03-04 16:37:51', '2026-03-04 16:38:04'),
(21, 'purchase_request', 70, 1, 1, 'approved', '2026-03-12 08:22:29', 'Okay good', '2026-03-12 08:21:42', '2026-03-12 08:22:29'),
(22, 'issuance', 54, 1, 1, 'approved', '2026-03-12 08:48:55', NULL, '2026-03-12 08:48:39', '2026-03-12 08:48:55'),
(23, 'purchase_request', 71, 1, 1, 'approved', '2026-03-12 09:22:26', 'Good', '2026-03-12 09:20:56', '2026-03-12 09:22:26'),
(24, 'purchase_request', 72, 1, 1, 'approved', '2026-03-12 09:22:32', NULL, '2026-03-12 09:21:13', '2026-03-12 09:22:32'),
(25, 'issuance', 55, 1, 1, 'approved', '2026-03-12 09:39:44', 'Flow 1', '2026-03-12 09:39:29', '2026-03-12 09:39:44'),
(26, 'issuance', 56, 1, 1, 'approved', '2026-03-12 09:43:56', 'Flow 2', '2026-03-12 09:43:39', '2026-03-12 09:43:56'),
(27, 'purchase_request', 73, 1, 3, 'approved', '2026-03-12 10:40:00', NULL, '2026-03-12 10:39:50', '2026-03-12 10:40:00'),
(28, 'purchase_request', 74, 1, NULL, 'pending', NULL, NULL, '2026-03-12 14:40:23', '2026-03-12 14:40:23');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `actor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(150) NOT NULL,
  `module` varchar(80) NOT NULL,
  `reference_type` varchar(80) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `actor_id`, `action`, `module`, `reference_type`, `reference_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'receiving.posted', 'receiving', 'receiving', 51, '{\"status\":\"draft\"}', '{\"status\":\"posted\",\"posted_at\":\"2026-03-04 15:02:15\"}', NULL, NULL, '2026-03-04 15:02:15'),
(2, 1, 'receiving.posted', 'receiving', 'receiving', 52, '{\"status\":\"draft\"}', '{\"status\":\"posted\",\"posted_at\":\"2026-03-04 15:02:53\"}', NULL, NULL, '2026-03-04 15:02:53'),
(3, 1, 'receiving.posted', 'receiving', 'receiving', 53, '{\"status\":\"draft\"}', '{\"status\":\"posted\",\"posted_at\":\"2026-03-04 15:16:50\"}', NULL, NULL, '2026-03-04 15:16:50'),
(4, 1, 'issuance.draft_created', 'issuance', 'issuance', 51, NULL, '{\"status\":\"draft\",\"item_count\":1,\"issue_date\":\"2026-03-04\"}', NULL, NULL, '2026-03-04 15:17:43'),
(5, 1, 'issuance.submitted', 'issuance', 'issuance', 51, '{\"status\":\"draft\"}', '{\"status\":\"submitted\",\"submitted_at\":\"2026-03-04 15:17:59\"}', NULL, NULL, '2026-03-04 15:17:59'),
(6, 1, 'issuance.approved', 'issuance', 'issuance', 51, '{\"status\":\"submitted\"}', '{\"status\":\"approved\",\"comments\":null}', NULL, NULL, '2026-03-04 15:18:09'),
(7, 1, 'issuance.release_failed', 'issuance', 'issuance', 51, '{\"status\":\"approved\"}', '{\"error\":\"Insufficient stock for item 0.9% Sodium Chloride IV 1L.\"}', NULL, NULL, '2026-03-04 15:18:12'),
(8, 1, 'issuance.draft_created', 'issuance', 'issuance', 52, NULL, '{\"status\":\"draft\",\"item_count\":1,\"issue_date\":\"2026-03-04\"}', NULL, NULL, '2026-03-04 15:40:48'),
(9, 1, 'issuance.submitted', 'issuance', 'issuance', 52, '{\"status\":\"draft\"}', '{\"status\":\"submitted\",\"submitted_at\":\"2026-03-04 15:41:05\"}', NULL, NULL, '2026-03-04 15:41:05'),
(10, 1, 'issuance.approved', 'issuance', 'issuance', 52, '{\"status\":\"submitted\"}', '{\"status\":\"approved\",\"comments\":null}', NULL, NULL, '2026-03-04 15:41:17'),
(11, 1, 'issuance.release_failed', 'issuance', 'issuance', 52, '{\"status\":\"approved\"}', '{\"error\":\"Insufficient stock for item Amiodarone 150mg\\/3ml Ampule.\"}', NULL, NULL, '2026-03-04 15:41:23'),
(12, 1, 'issuance.release_failed', 'issuance', 'issuance', 52, '{\"status\":\"approved\"}', '{\"error\":\"Insufficient stock for item Amiodarone 150mg\\/3ml Ampule.\"}', NULL, NULL, '2026-03-04 15:41:28'),
(13, 1, 'receiving.posted', 'receiving', 'receiving', 54, '{\"status\":\"draft\"}', '{\"status\":\"posted\",\"posted_at\":\"2026-03-04 15:43:42\"}', NULL, NULL, '2026-03-04 15:43:42'),
(14, 1, 'issuance.draft_created', 'issuance', 'issuance', 53, NULL, '{\"status\":\"draft\",\"item_count\":1,\"issue_date\":\"2026-03-04\"}', NULL, NULL, '2026-03-04 15:44:43'),
(15, 1, 'issuance.submitted', 'issuance', 'issuance', 53, '{\"status\":\"draft\"}', '{\"status\":\"submitted\",\"submitted_at\":\"2026-03-04 15:44:48\"}', NULL, NULL, '2026-03-04 15:44:48'),
(16, 1, 'issuance.approved', 'issuance', 'issuance', 53, '{\"status\":\"submitted\"}', '{\"status\":\"approved\",\"comments\":null}', NULL, NULL, '2026-03-04 15:44:51'),
(17, 1, 'issuance.released', 'issuance', 'issuance', 53, '{\"status\":\"approved\"}', '{\"status\":\"released\",\"items_released\":1,\"total_qty_out\":11,\"total_cost\":935}', NULL, NULL, '2026-03-04 15:44:55'),
(18, 1, 'issuance.cancelled', 'issuance', 'issuance', 49, '{\"status\":\"draft\"}', '{\"status\":\"cancelled\",\"reason\":\"No Items\"}', NULL, NULL, '2026-03-04 15:45:59'),
(19, 1, 'issuance.cancelled', 'issuance', 'issuance', 44, '{\"status\":\"draft\"}', '{\"status\":\"cancelled\",\"reason\":\"No Items\"}', NULL, NULL, '2026-03-04 15:46:21'),
(20, 1, 'issuance.cancelled', 'issuance', 'issuance', 39, '{\"status\":\"draft\"}', '{\"status\":\"cancelled\",\"reason\":\"No Items\"}', NULL, NULL, '2026-03-04 15:47:28'),
(21, 1, 'receiving.posted', 'receiving', 'receiving', 55, '{\"status\":\"draft\"}', '{\"status\":\"posted\",\"posted_at\":\"2026-03-04 16:38:59\"}', NULL, NULL, '2026-03-04 16:38:59'),
(22, 1, 'receiving.posted', 'receiving', 'receiving', 56, '{\"status\":\"draft\"}', '{\"status\":\"posted\",\"posted_at\":\"2026-03-04 17:02:10\"}', NULL, NULL, '2026-03-04 17:02:10'),
(23, 1, 'receiving.posted', 'receiving', 'receiving', 57, '{\"status\":\"draft\"}', '{\"status\":\"posted\",\"posted_at\":\"2026-03-12 08:41:21\"}', NULL, NULL, '2026-03-12 08:41:21'),
(24, 1, 'issuance.draft_created', 'issuance', 'issuance', 54, NULL, '{\"status\":\"draft\",\"item_count\":1,\"issue_date\":\"2026-03-12\"}', NULL, NULL, '2026-03-12 08:48:23'),
(25, 1, 'issuance.submitted', 'issuance', 'issuance', 54, '{\"status\":\"draft\"}', '{\"status\":\"submitted\",\"submitted_at\":\"2026-03-12 08:48:39\"}', NULL, NULL, '2026-03-12 08:48:39'),
(26, 1, 'issuance.approved', 'issuance', 'issuance', 54, '{\"status\":\"submitted\"}', '{\"status\":\"approved\",\"comments\":null}', NULL, NULL, '2026-03-12 08:48:55'),
(27, 1, 'issuance.released', 'issuance', 'issuance', 54, '{\"status\":\"approved\"}', '{\"status\":\"released\",\"items_released\":1,\"total_qty_out\":5,\"total_cost\":1750}', NULL, NULL, '2026-03-12 08:49:06'),
(28, 1, 'receiving.posted', 'receiving', 'receiving', 58, '{\"status\":\"draft\"}', '{\"status\":\"posted\",\"posted_at\":\"2026-03-12 09:34:49\"}', NULL, NULL, '2026-03-12 09:34:49'),
(29, 1, 'receiving.posted', 'receiving', 'receiving', 59, '{\"status\":\"draft\"}', '{\"status\":\"posted\",\"posted_at\":\"2026-03-12 09:36:36\"}', NULL, NULL, '2026-03-12 09:36:36'),
(30, 1, 'issuance.draft_created', 'issuance', 'issuance', 55, NULL, '{\"status\":\"draft\",\"item_count\":2,\"issue_date\":\"2026-03-12\"}', NULL, NULL, '2026-03-12 09:39:15'),
(31, 1, 'issuance.submitted', 'issuance', 'issuance', 55, '{\"status\":\"draft\"}', '{\"status\":\"submitted\",\"submitted_at\":\"2026-03-12 09:39:29\"}', NULL, NULL, '2026-03-12 09:39:29'),
(32, 1, 'issuance.approved', 'issuance', 'issuance', 55, '{\"status\":\"submitted\"}', '{\"status\":\"approved\",\"comments\":\"Flow 1\"}', NULL, NULL, '2026-03-12 09:39:44'),
(33, 1, 'issuance.released', 'issuance', 'issuance', 55, '{\"status\":\"approved\"}', '{\"status\":\"released\",\"items_released\":2,\"total_qty_out\":100,\"total_cost\":15000}', NULL, NULL, '2026-03-12 09:39:51'),
(34, 1, 'issuance.draft_created', 'issuance', 'issuance', 56, NULL, '{\"status\":\"draft\",\"item_count\":2,\"issue_date\":\"2026-03-12\"}', NULL, NULL, '2026-03-12 09:43:28'),
(35, 1, 'issuance.submitted', 'issuance', 'issuance', 56, '{\"status\":\"draft\"}', '{\"status\":\"submitted\",\"submitted_at\":\"2026-03-12 09:43:39\"}', NULL, NULL, '2026-03-12 09:43:39'),
(36, 1, 'issuance.approved', 'issuance', 'issuance', 56, '{\"status\":\"submitted\"}', '{\"status\":\"approved\",\"comments\":\"Flow 2\"}', NULL, NULL, '2026-03-12 09:43:56'),
(37, 1, 'issuance.released', 'issuance', 'issuance', 56, '{\"status\":\"approved\"}', '{\"status\":\"released\",\"items_released\":2,\"total_qty_out\":120,\"total_cost\":13700}', NULL, NULL, '2026-03-12 09:44:05');

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_users`
--

CREATE TABLE `auth_groups_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_groups_users`
--

INSERT INTO `auth_groups_users` (`id`, `user_id`, `group`, `created_at`) VALUES
(1, 1, 'admin', '2026-03-04 09:53:31'),
(2, 2, 'employee', '2026-03-04 09:53:32'),
(3, 3, 'it_staff', '2026-03-04 09:53:33'),
(4, 4, 'employee', '2026-03-10 11:05:22'),
(6, 5, 'admin', '2026-03-10 15:36:04');

-- --------------------------------------------------------

--
-- Table structure for table `auth_identities`
--

CREATE TABLE `auth_identities` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `secret` varchar(255) NOT NULL,
  `secret2` varchar(255) DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `extra` text DEFAULT NULL,
  `force_reset` tinyint(1) NOT NULL DEFAULT 0,
  `last_used_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_identities`
--

INSERT INTO `auth_identities` (`id`, `user_id`, `type`, `name`, `secret`, `secret2`, `expires`, `extra`, `force_reset`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'email_password', NULL, 'admin@local.test', '$2y$12$V7t1U1smPBaNaU40tlSGbONyzf1DZABHpEqoydQTK/xKrUuotXt8m', NULL, NULL, 0, '2026-03-12 13:25:49', '2026-03-04 09:53:31', '2026-03-12 13:25:49'),
(2, 2, 'email_password', NULL, 'employee@local.test', '$2y$12$BaH3FrKLF6XxSFYIh8WnKuZ9iYg2cX.2bCjwA6sB5M.cIHDPsDcW.', NULL, NULL, 0, '2026-03-12 14:39:24', '2026-03-04 09:53:32', '2026-03-12 14:39:24'),
(3, 3, 'email_password', NULL, 'itstaff@local.test', '$2y$12$q2O0VmFMQ6RDhdTVWdIJeuIX0FsB./tJvRMt1t3PDCam9DSP3/DEW', NULL, NULL, 0, '2026-03-12 13:25:32', '2026-03-04 09:53:32', '2026-03-12 13:25:32'),
(4, 4, 'email_password', NULL, 'johndoe@company.local', '$2y$12$SDRIBRyYXW.7T.InwAT3aOqF3xlI0Z1yGMWjUX5ypH2JWuhDscm4O', NULL, NULL, 0, '2026-03-10 11:05:22', '2026-03-10 11:05:21', '2026-03-10 11:05:22'),
(5, 5, 'email_password', NULL, 'sean@company.local', '$2y$12$jtwci5DVqkeaFLqQQ5ValO8WLb0j0dnyL8HGA.V5D6LfcUZYQSA4O', NULL, NULL, 0, '2026-03-10 16:19:06', '2026-03-10 15:36:04', '2026-03-10 16:19:06');

-- --------------------------------------------------------

--
-- Table structure for table `auth_logins`
--

CREATE TABLE `auth_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_logins`
--

INSERT INTO `auth_logins` (`id`, `ip_address`, `user_agent`, `id_type`, `identifier`, `user_id`, `date`, `success`) VALUES
(1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'admin@local.test', 1, '2026-03-04 09:56:11', 1),
(2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'admin@local.test', 1, '2026-03-10 08:12:50', 1),
(3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'admin@local.test', 1, '2026-03-10 08:43:28', 1),
(4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'itstaff@local.test', 3, '2026-03-10 10:53:00', 1),
(5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'admin@local.test', 1, '2026-03-10 11:02:01', 1),
(6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'johndoe@company.local', 4, '2026-03-10 11:05:22', 1),
(7, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'admin@local.test', 1, '2026-03-10 11:06:20', 1),
(8, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'admin@local.test', 1, '2026-03-10 13:11:42', 1),
(9, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'admin@local.test', 1, '2026-03-10 13:17:28', 1),
(10, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'admin@local.test', 1, '2026-03-10 15:35:25', 1),
(11, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'admin@local.test', NULL, '2026-03-10 16:05:18', 0),
(12, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'admin@local.test', NULL, '2026-03-10 16:05:28', 0),
(13, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'admin@local.test', 1, '2026-03-10 16:05:50', 1),
(14, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'itstaff@local.test', NULL, '2026-03-10 16:17:40', 0),
(15, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'admin@local.test', 1, '2026-03-10 16:17:57', 1),
(16, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'itstaff@local.test', NULL, '2026-03-10 16:18:26', 0),
(17, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'itstaff@local.test', 3, '2026-03-10 16:18:42', 1),
(18, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'username', 'sean', 5, '2026-03-10 16:19:06', 1),
(19, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'employee@local.test', 2, '2026-03-10 16:20:07', 1),
(20, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'username', 'sean', NULL, '2026-03-12 08:09:05', 0),
(21, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'sean@local.test', NULL, '2026-03-12 08:09:17', 0),
(22, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'admin@local.test', 1, '2026-03-12 08:09:36', 1),
(23, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'itstaff@local.test', 3, '2026-03-12 08:09:51', 1),
(24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'employee@local.test', 2, '2026-03-12 08:10:05', 1),
(25, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'itstaff@local.test', 3, '2026-03-12 13:25:32', 1),
(26, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'admin@local.test', 1, '2026-03-12 13:25:49', 1),
(27, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'email_password', 'employee@local.test', 2, '2026-03-12 14:39:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions_users`
--

CREATE TABLE `auth_permissions_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `permission` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_permissions_users`
--

INSERT INTO `auth_permissions_users` (`id`, `user_id`, `permission`, `created_at`) VALUES
(1, 1, 'dashboard.view_admin', '2026-03-04 09:53:31'),
(2, 1, 'auth.manage_users', '2026-03-04 09:53:31');

-- --------------------------------------------------------

--
-- Table structure for table `auth_remember_tokens`
--

CREATE TABLE `auth_remember_tokens` (
  `id` int(11) UNSIGNED NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `expires` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_token_logins`
--

CREATE TABLE `auth_token_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_stocks`
--

CREATE TABLE `inventory_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `unit` varchar(50) NOT NULL DEFAULT 'unit',
  `batch_no` varchar(100) DEFAULT NULL,
  `lot_no` varchar(100) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `on_hand_qty` decimal(12,3) NOT NULL DEFAULT 0.000,
  `reserved_qty` decimal(12,3) NOT NULL DEFAULT 0.000,
  `available_qty` decimal(12,3) NOT NULL DEFAULT 0.000,
  `average_unit_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `last_movement_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_stocks`
--

INSERT INTO `inventory_stocks` (`id`, `item_name`, `unit`, `batch_no`, `lot_no`, `expiry_date`, `on_hand_qty`, `reserved_qty`, `available_qty`, `average_unit_cost`, `last_movement_at`, `created_at`, `updated_at`) VALUES
(1, 'Amoxicillin 500mg Capsule', 'box', 'B-2601A', 'L-001', '2028-05-30', 95.000, 10.000, 85.000, 350.00, '2026-03-12 08:49:06', '2026-03-04 10:32:35', '2026-03-12 08:49:06'),
(2, 'Paracetamol 500mg Tablet', 'box', 'B-2602P', 'L-002', '2029-01-15', 250.000, 20.000, 230.000, 120.00, NULL, '2026-03-04 10:32:35', NULL),
(3, 'Ibuprofen 400mg Tablet', 'box', 'B-2603I', 'L-003', '2028-11-20', 150.000, 15.000, 135.000, 180.00, NULL, '2026-03-04 10:32:35', NULL),
(4, 'Cetirizine 10mg Tablet', 'box', 'B-2604C', 'L-004', '2027-08-10', 80.000, 5.000, 75.000, 210.00, NULL, '2026-03-04 10:32:35', NULL),
(5, 'Omeprazole 20mg Capsule', 'box', 'B-2605O', 'L-005', '2028-03-25', 120.000, 10.000, 110.000, 450.00, NULL, '2026-03-04 10:32:35', NULL),
(6, 'Metformin 500mg Tablet', 'box', 'B-2606M', 'L-006', '2029-06-30', 200.000, 30.000, 170.000, 150.00, NULL, '2026-03-04 10:32:35', NULL),
(7, 'Losartan 50mg Tablet', 'box', 'B-2607L', 'L-007', '2028-12-05', 180.000, 20.000, 160.000, 280.00, NULL, '2026-03-04 10:32:35', NULL),
(8, 'Salbutamol 100mcg Inhaler', 'piece', 'B-2608S', 'L-008', '2027-04-15', 50.000, 5.000, 45.000, 320.00, NULL, '2026-03-04 10:32:35', NULL),
(9, 'Sterile Syringe 5cc with Needle', 'box', 'B-2609Y', 'L-009', '2030-01-01', 300.000, 50.000, 250.000, 400.00, NULL, '2026-03-04 10:32:35', NULL),
(10, '0.9% Sodium Chloride IV 1L', 'bottle', 'B-2610N', 'L-010', '2027-10-12', 100.000, 10.000, 90.000, 115.00, NULL, '2026-03-04 10:32:35', NULL),
(11, 'Ceftriaxone 1g Vial', 'vial', 'B-2611C', 'L-011', '2027-11-30', 200.000, 20.000, 180.000, 150.00, NULL, '2026-03-04 10:34:03', NULL),
(12, 'Aspirin 81mg Tablet', 'box', 'B-2612A', 'L-012', '2029-08-15', 139.000, 10.000, 129.000, 85.00, '2026-03-04 15:44:55', '2026-03-04 10:34:03', '2026-03-04 15:44:55'),
(13, 'Simvastatin 20mg Tablet', 'box', 'B-2613S', 'L-013', '2028-02-28', 100.000, 5.000, 95.000, 190.00, NULL, '2026-03-04 10:34:03', NULL),
(14, 'Amlodipine 5mg Tablet', 'box', 'B-2614M', 'L-014', '2028-09-10', 300.000, 30.000, 270.000, 110.00, NULL, '2026-03-04 10:34:03', NULL),
(15, 'Furosemide 40mg Tablet', 'box', 'B-2615F', 'L-015', '2027-05-20', 120.000, 15.000, 105.000, 95.00, NULL, '2026-03-04 10:34:03', NULL),
(16, 'Ciprofloxacin 500mg Tablet', 'box', 'B-2616C', 'L-016', '2029-03-12', 180.000, 20.000, 160.000, 220.00, NULL, '2026-03-04 10:34:03', NULL),
(17, 'Azithromycin 500mg Tablet', 'box', 'B-2617Z', 'L-017', '2028-10-05', 140.000, 10.000, 130.000, 310.00, NULL, '2026-03-04 10:34:03', NULL),
(18, 'Tramadol 50mg Capsule', 'box', 'B-2618T', 'L-018', '2027-12-15', 90.000, 15.000, 75.000, 250.00, NULL, '2026-03-04 10:34:03', NULL),
(19, 'Pantoprazole 40mg Tablet', 'box', 'B-2619P', 'L-019', '2028-07-22', 110.000, 10.000, 100.000, 280.00, NULL, '2026-03-04 10:34:03', NULL),
(20, 'Medical Oxygen Cylinder (50L)', 'tank', 'B-2620O', 'L-020', '2030-05-01', 40.000, 5.000, 35.000, 1500.00, NULL, '2026-03-04 10:34:03', NULL),
(21, 'Diazepam 5mg Tablet', 'box', 'B-2621D', 'L-021', '2028-06-15', 50.000, 5.000, 45.000, 250.00, NULL, '2026-03-04 10:35:47', NULL),
(22, 'Dexamethasone 4mg Tablet', 'box', 'B-2622D', 'L-022', '2027-11-20', 120.000, 10.000, 110.000, 180.00, NULL, '2026-03-04 10:35:47', NULL),
(23, 'Insulin Glargine 100iu/ml Pen', 'piece', 'B-2623I', 'L-023', '2026-12-30', 80.000, 15.000, 65.000, 850.00, NULL, '2026-03-04 10:35:47', NULL),
(24, 'Hydrocortisone 1% Cream 10g', 'tube', 'B-2624H', 'L-024', '2029-01-10', 150.000, 0.000, 150.000, 120.00, NULL, '2026-03-04 10:35:47', NULL),
(25, 'Loperamide 2mg Capsule', 'box', 'B-2625L', 'L-025', '2028-04-05', 200.000, 20.000, 180.000, 150.00, NULL, '2026-03-04 10:35:47', NULL),
(26, 'Ondansetron 4mg Tablet', 'box', 'B-2626O', 'L-026', '2027-09-15', 90.000, 5.000, 85.000, 320.00, NULL, '2026-03-04 10:35:47', NULL),
(27, 'Clopidogrel 75mg Tablet', 'box', 'B-2627C', 'L-027', '2028-08-22', 140.000, 30.000, 110.000, 410.00, NULL, '2026-03-04 10:35:47', NULL),
(28, 'Atorvastatin 40mg Tablet', 'box', 'B-2628A', 'L-028', '2029-05-18', 250.000, 40.000, 210.000, 290.00, NULL, '2026-03-04 10:35:47', NULL),
(29, 'Digoxin 0.25mg Tablet', 'box', 'B-2629D', 'L-029', '2027-03-12', 60.000, 10.000, 50.000, 190.00, NULL, '2026-03-04 10:35:47', NULL),
(30, 'Nitroglycerin 0.4mg Sublingual', 'bottle', 'B-2630N', 'L-030', '2027-10-30', 40.000, 5.000, 35.000, 550.00, NULL, '2026-03-04 10:35:47', NULL),
(31, 'Epinephrine 1mg/ml Ampule', 'ampule', 'B-2631E', 'L-031', '2026-11-30', 100.000, 10.000, 90.000, 150.00, NULL, '2026-03-04 10:38:13', NULL),
(32, 'Naloxone 0.4mg/ml Vial', 'vial', 'B-2632N', 'L-032', '2028-02-15', 50.000, 5.000, 45.000, 850.00, NULL, '2026-03-04 10:38:13', NULL),
(33, 'Atropine Sulfate 1mg/ml Ampule', 'ampule', 'B-2633A', 'L-033', '2027-05-20', 120.000, 15.000, 105.000, 95.00, NULL, '2026-03-04 10:38:13', NULL),
(34, 'Amiodarone 150mg/3ml Ampule', 'ampule', 'B-2634M', 'L-034', '2028-01-10', 80.000, 8.000, 72.000, 450.00, NULL, '2026-03-04 10:38:13', NULL),
(35, 'Magnesium Sulfate 250mg/ml Ampule', 'ampule', 'B-2635G', 'L-035', '2029-07-25', 150.000, 20.000, 130.000, 120.00, NULL, '2026-03-04 10:38:13', NULL),
(36, 'Calcium Gluconate 10% Ampule', 'ampule', 'B-2636C', 'L-036', '2027-09-30', 200.000, 25.000, 175.000, 180.00, NULL, '2026-03-04 10:38:13', NULL),
(37, 'Dobutamine 250mg/20ml Vial', 'vial', 'B-2637D', 'L-037', '2028-12-05', 60.000, 5.000, 55.000, 650.00, NULL, '2026-03-04 10:38:13', NULL),
(38, 'Dopamine 200mg/5ml Ampule', 'ampule', 'B-2638O', 'L-038', '2027-03-18', 90.000, 10.000, 80.000, 420.00, NULL, '2026-03-04 10:38:13', NULL),
(39, 'Norepinephrine 4mg/4ml Ampule', 'ampule', 'B-2639R', 'L-039', '2028-08-22', 110.000, 15.000, 95.000, 580.00, NULL, '2026-03-04 10:38:13', NULL),
(40, 'Lidocaine 2% 50ml Vial', 'vial', 'B-2640L', 'L-040', '2029-10-15', 70.000, 10.000, 60.000, 220.00, NULL, '2026-03-04 10:38:13', NULL),
(41, 'Cefalexin 500mg Capsule', 'box', 'B-2641C', 'L-041', '2028-04-10', 150.000, 15.000, 135.000, 220.00, NULL, '2026-03-04 10:39:53', NULL),
(42, 'Ibuprofen 100mg/5ml Suspension', 'bottle', 'B-2642I', 'L-042', '2027-08-20', 80.000, 5.000, 75.000, 150.00, NULL, '2026-03-04 10:39:53', NULL),
(43, 'Paracetamol 250mg/5ml Syrup', 'bottle', 'B-2643P', 'L-043', '2028-11-30', 120.000, 10.000, 110.000, 95.00, NULL, '2026-03-04 10:39:53', NULL),
(44, 'Lactated Ringer\'s Solution 1L', 'bottle', 'B-2644L', 'L-044', '2027-05-15', 300.000, 50.000, 250.000, 110.00, NULL, '2026-03-04 10:39:53', NULL),
(45, 'D5W IV Fluid 500ml', 'bottle', 'B-2645D', 'L-045', '2027-06-10', 250.000, 40.000, 210.000, 90.00, NULL, '2026-03-04 10:39:53', NULL),
(46, 'Propofol 10mg/ml 50ml Vial', 'vial', 'B-2646P', 'L-046', '2026-12-01', 60.000, 10.000, 50.000, 850.00, NULL, '2026-03-04 10:39:53', NULL),
(47, 'Fentanyl 50mcg/ml Ampule', 'ampule', 'B-2647F', 'L-047', '2028-02-28', 40.000, 5.000, 35.000, 1200.00, NULL, '2026-03-04 10:39:53', NULL),
(48, 'Midazolam 5mg/ml Ampule', 'ampule', 'B-2648M', 'L-048', '2027-09-15', 50.000, 5.000, 45.000, 450.00, NULL, '2026-03-04 10:39:53', NULL),
(49, 'Heparin 5000 IU/ml Vial', 'vial', 'B-2649H', 'L-049', '2028-03-22', 90.000, 10.000, 80.000, 680.00, NULL, '2026-03-04 10:39:53', NULL),
(50, 'Warfarin 5mg Tablet', 'box', 'B-2650W', 'L-050', '2029-01-05', 110.000, 15.000, 95.000, 290.00, NULL, '2026-03-04 10:39:53', NULL),
(51, 'Dexamethasone 4mg Tablet', 'Bottle', NULL, NULL, NULL, 3123.000, 0.000, 3123.000, 23123.00, '2026-03-04 15:02:15', '2026-03-04 15:02:15', '2026-03-04 15:02:15'),
(52, 'Amoxicillin 500mg Capsule', 'Piece', NULL, NULL, NULL, 55.000, 0.000, 55.000, 1000.00, '2026-03-04 15:02:53', '2026-03-04 15:02:53', '2026-03-04 15:02:53'),
(53, 'Cetirizine 10mg Tablet', 'Box', NULL, NULL, NULL, 56.000, 0.000, 56.000, 40000.00, '2026-03-04 15:02:53', '2026-03-04 15:02:53', '2026-03-04 15:02:53'),
(54, 'Clopidogrel 75mg Tablet', 'Box', NULL, NULL, NULL, 56.000, 0.000, 56.000, 70000.00, '2026-03-04 15:02:53', '2026-03-04 15:02:53', '2026-03-04 15:02:53'),
(55, 'Digoxin 0.25mg Tablet', 'Box', NULL, NULL, NULL, 67.000, 0.000, 67.000, 50000.00, '2026-03-04 15:02:53', '2026-03-04 15:02:53', '2026-03-04 15:02:53'),
(56, 'Paracetamol 250mg/5ml Syrup', 'Bottle', NULL, NULL, NULL, 75.000, 0.000, 75.000, 10000.00, '2026-03-04 15:02:53', '2026-03-04 15:02:53', '2026-03-04 15:02:53'),
(57, '0.9% Sodium Chloride IV 1L', 'Bottle', NULL, NULL, NULL, 2.000, 0.000, 2.000, 213.00, '2026-03-04 15:16:50', '2026-03-04 15:16:50', '2026-03-04 15:16:50'),
(58, 'Aspirin 81mg Tablet', 'Box', NULL, NULL, NULL, 100.000, 0.000, 100.000, 100.00, '2026-03-04 15:43:42', '2026-03-04 15:43:42', '2026-03-04 15:43:42'),
(59, 'Paracetamol 500mg', 'Box', NULL, NULL, NULL, 20.000, 0.000, 20.000, 150.00, '2026-03-04 16:38:59', '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(60, 'Amoxicillin 250mg', 'Capsule', NULL, NULL, NULL, 100.000, 0.000, 100.000, 5.50, '2026-03-04 16:38:59', '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(61, 'Surgical Masks', 'Box', NULL, NULL, NULL, 50.000, 0.000, 50.000, 55.00, '2026-03-04 16:38:59', '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(62, 'Latex Gloves (Medium)', 'Pack', NULL, NULL, NULL, 30.000, 0.000, 30.000, 250.00, '2026-03-04 16:38:59', '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(63, 'Syringe 5ml', 'Piece', NULL, NULL, NULL, 200.000, 0.000, 200.000, 12.00, '2026-03-04 16:38:59', '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(64, 'Ibuprofen 400mg', 'Tablet', NULL, NULL, NULL, 500.000, 0.000, 500.000, 8.00, '2026-03-04 16:38:59', '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(65, 'Gauze Pad 4x4', 'Pack', NULL, NULL, NULL, 20.000, 0.000, 20.000, 45.00, '2026-03-04 16:38:59', '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(66, 'Cotton Balls', 'Roll', NULL, NULL, NULL, 15.000, 0.000, 15.000, 80.00, '2026-03-04 16:38:59', '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(67, 'Povidone Iodine 10%', 'Bottle', NULL, NULL, NULL, 10.000, 0.000, 10.000, 120.00, '2026-03-04 16:38:59', '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(68, 'Digital Thermometer', 'Piece', NULL, NULL, NULL, 5.000, 0.000, 5.000, 150.00, '2026-03-04 16:38:59', '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(69, 'Lidocaine Ointment', 'Tube', NULL, NULL, NULL, 12.000, 0.000, 12.000, 350.00, '2026-03-04 16:38:59', '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(70, 'Saline Solution 1L', 'Piece', NULL, NULL, NULL, 50.000, 0.000, 50.000, 90.00, '2026-03-04 16:38:59', '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(71, 'Epinephrine 10mg', 'Vial', NULL, NULL, NULL, 25.000, 0.000, 25.000, 800.00, '2026-03-04 16:38:59', '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(72, 'IV Cannula G20', 'Piece', NULL, NULL, NULL, 100.000, 0.000, 100.000, 0.00, '2026-03-04 16:38:59', '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(73, 'First Aid Kit', 'Set', NULL, NULL, NULL, 3.000, 0.000, 3.000, 2500.00, '2026-03-04 16:38:59', '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(74, 'Azithromycin 500mg Tablet', 'Capsule', 'BATCH-69', 'LOT-109', '2026-03-04', 113.000, 0.000, 113.000, 124.00, '2026-03-04 17:02:10', '2026-03-04 17:02:10', '2026-03-04 17:02:10'),
(75, 'Azithromycin 500mg Tablet', 'Tablet', 'BATCH-69', 'LOT-109', '2026-03-04', 2400.000, 0.000, 2400.000, 214.00, '2026-03-04 17:02:10', '2026-03-04 17:02:10', '2026-03-04 17:02:10'),
(76, 'Clopidogrel 75mg Tablet', 'Tube', 'BATCH-69', 'LOT-109', '2026-03-04', 112.000, 0.000, 112.000, 124.00, '2026-03-04 17:02:10', '2026-03-04 17:02:10', '2026-03-04 17:02:10'),
(77, 'Amoxicillin 500mg Capsule', 'Box', 'BATCH-67', 'LOT-169', '2030-07-11', 9.000, 0.000, 9.000, 500.00, '2026-03-12 08:41:21', '2026-03-12 08:41:21', '2026-03-12 08:41:21'),
(78, 'Kisspirin 100mg', 'Box', 'BATCH-67', 'LOT-109', '2027-01-01', 0.000, 0.000, 0.000, 100.00, '2026-03-12 09:44:05', '2026-03-12 09:34:49', '2026-03-12 09:44:05'),
(79, 'Yakapsul 500mg', 'Box', 'BATCH-67', 'LOT-109', '2027-01-01', 0.000, 0.000, 0.000, 200.00, '2026-03-12 09:44:05', '2026-03-12 09:34:49', '2026-03-12 09:44:05'),
(80, 'Kisspirin 100mg', 'Box', 'BATCH-69', 'LOT-169', '2035-01-01', 9.000, 0.000, 9.000, 100.00, '2026-03-12 09:44:05', '2026-03-12 09:36:36', '2026-03-12 09:44:05'),
(81, 'Yakapsul 500mg', 'Box', 'BATCH-69', 'LOT-169', '2035-01-12', 7.000, 0.000, 7.000, 100.00, '2026-03-12 09:44:05', '2026-03-12 09:36:36', '2026-03-12 09:44:05');

-- --------------------------------------------------------

--
-- Table structure for table `issuances`
--

CREATE TABLE `issuances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `issuance_number` varchar(50) NOT NULL,
  `requestor_id` bigint(20) UNSIGNED NOT NULL,
  `issue_date` date NOT NULL,
  `department` varchar(120) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'draft',
  `submitted_at` datetime DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `rejected_by` bigint(20) UNSIGNED DEFAULT NULL,
  `rejected_at` datetime DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `released_by` bigint(20) UNSIGNED DEFAULT NULL,
  `released_at` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issuances`
--

INSERT INTO `issuances` (`id`, `issuance_number`, `requestor_id`, `issue_date`, `department`, `purpose`, `status`, `submitted_at`, `approved_by`, `approved_at`, `rejected_by`, `rejected_at`, `rejection_reason`, `released_by`, `released_at`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'ISS-PH-2026-001', 3, '2026-03-07', 'Emergency Room', 'ER daily medicine stock', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(2, 'ISS-PH-2026-002', 2, '2026-03-08', 'Outpatient Pharmacy', 'Prescription dispensing', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(3, 'ISS-PH-2026-003', 3, '2026-03-09', 'ICU', 'Critical care supplies', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(4, 'ISS-PH-2026-004', 2, '2026-03-10', 'Pediatrics Ward', 'Ward restock', 'draft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(5, 'ISS-PH-2026-005', 3, '2026-03-11', 'Emergency Room', 'Trauma kits restock', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(6, 'ISS-PH-2026-006', 2, '2026-03-12', 'Maternity Ward', 'Post-partum medications', 'rejected', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(7, 'ISS-PH-2026-007', 3, '2026-03-13', 'Outpatient Pharmacy', 'Chronic illness resupply', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(8, 'ISS-PH-2026-008', 2, '2026-03-14', 'Surgical Ward', 'Post-op antibiotics', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(9, 'ISS-PH-2026-009', 3, '2026-03-15', 'ICU', 'IV fluids restock', 'draft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(10, 'ISS-PH-2026-010', 2, '2026-03-16', 'Emergency Room', 'Asthma/Nebulizer meds', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(11, 'ISS-PH-2026-011', 3, '2026-03-17', 'Isolation Ward', 'Antibiotic therapy', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(12, 'ISS-PH-2026-012', 2, '2026-03-18', 'Cardiology Clinic', 'Hypertension management', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(13, 'ISS-PH-2026-013', 3, '2026-03-19', 'General Ward', 'Pain management', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(14, 'ISS-PH-2026-014', 2, '2026-03-20', 'Operating Room', 'Anesthesia prep', 'draft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(15, 'ISS-PH-2026-015', 3, '2026-03-21', 'Outpatient Pharmacy', 'Daily cholesterol meds', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(16, 'ISS-PH-2026-016', 2, '2026-03-22', 'Orthopedics', 'Post-surgery pain relief', 'rejected', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(17, 'ISS-PH-2026-017', 3, '2026-03-23', 'Emergency Room', 'Oxygen supply rotation', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(18, 'ISS-PH-2026-018', 2, '2026-03-24', 'Gastroenterology', 'Ulcer/GERD treatments', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(19, 'ISS-PH-2026-019', 3, '2026-03-25', 'Intensive Care Unit', 'Broad-spectrum antibiotics', 'draft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(20, 'ISS-PH-2026-020', 2, '2026-03-26', 'Dialysis Center', 'Fluid management', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(21, 'ISS-PH-2026-021', 3, '2026-03-27', 'Psychiatry Ward', 'Anxiety management', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(22, 'ISS-PH-2026-022', 2, '2026-03-28', 'Endocrinology', 'Diabetic patient supplies', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(23, 'ISS-PH-2026-023', 3, '2026-03-29', 'Dermatology', 'Topical steroids restock', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(24, 'ISS-PH-2026-024', 2, '2026-03-30', 'Emergency Room', 'Anti-diarrheal meds', 'draft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(25, 'ISS-PH-2026-025', 3, '2026-03-31', 'Oncology', 'Anti-nausea treatments', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(26, 'ISS-PH-2026-026', 2, '2026-04-01', 'Cardiology', 'Blood thinners resupply', 'rejected', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(27, 'ISS-PH-2026-027', 3, '2026-04-02', 'Outpatient Pharmacy', 'Cholesterol management', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(28, 'ISS-PH-2026-028', 2, '2026-04-03', 'ICU', 'Cardiac support drugs', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(29, 'ISS-PH-2026-029', 3, '2026-04-04', 'Emergency Room', 'Angina treatments', 'draft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(30, 'ISS-PH-2026-030', 2, '2026-04-05', 'General Ward', 'Routine diabetic care', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(31, 'ISS-PH-2026-031', 3, '2026-04-07', 'Emergency Room', 'Code Blue Crash Cart Restock', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(32, 'ISS-PH-2026-032', 2, '2026-04-08', 'ICU', 'Cardiac Arrest Supplies', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(33, 'ISS-PH-2026-033', 3, '2026-04-09', 'Operating Room', 'Anesthesia Emergency Drugs', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(34, 'ISS-PH-2026-034', 2, '2026-04-10', 'Telemetry Unit', 'Arrhythmia Management', 'draft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(35, 'ISS-PH-2026-035', 3, '2026-04-11', 'Neonatal ICU', 'Emergency Resuscitation Meds', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(36, 'ISS-PH-2026-036', 2, '2026-04-12', 'General Ward', 'Floor Crash Cart Check', 'rejected', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(37, 'ISS-PH-2026-037', 3, '2026-04-13', 'Ambulance Service', 'ALS Bag Restock', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(38, 'ISS-PH-2026-038', 2, '2026-04-14', 'Cardiology', 'Heart Block Interventions', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(39, 'ISS-PH-2026-039', 3, '2026-04-15', 'Emergency Room', 'Anaphylaxis Kits', 'cancelled', NULL, NULL, NULL, 1, '2026-03-04 15:47:28', 'No Items', NULL, NULL, NULL, '2026-03-04 10:38:14', '2026-03-04 15:47:28'),
(40, 'ISS-PH-2026-040', 2, '2026-04-16', 'ICU', 'Vasopressor Titration', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(41, 'ISS-PH-2026-041', 3, '2026-04-17', 'Pediatrics Ward', 'Children\'s antibiotics', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(42, 'ISS-PH-2026-042', 2, '2026-04-18', 'Pediatrics Ward', 'Fever management syrups', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(43, 'ISS-PH-2026-043', 3, '2026-04-19', 'Operating Room', 'Anesthesia / Sedation Prep', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(44, 'ISS-PH-2026-044', 2, '2026-04-20', 'ICU', 'Pain management (Fentanyl)', 'cancelled', NULL, NULL, NULL, 1, '2026-03-04 15:46:21', 'No Items', NULL, NULL, NULL, '2026-03-04 10:39:53', '2026-03-04 15:46:21'),
(45, 'ISS-PH-2026-045', 3, '2026-04-21', 'Emergency Room', 'IV Fluids (Lactated Ringers)', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(46, 'ISS-PH-2026-046', 2, '2026-04-22', 'General Ward', 'Routine IV D5W', 'rejected', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(47, 'ISS-PH-2026-047', 3, '2026-04-23', 'Cardiology', 'Anticoagulant therapy', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(48, 'ISS-PH-2026-048', 2, '2026-04-24', 'Operating Room', 'Midazolam for Endoscopy', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(49, 'ISS-PH-2026-049', 3, '2026-04-25', 'ICU', 'Heparin drips', 'cancelled', NULL, NULL, NULL, 1, '2026-03-04 15:45:59', 'No Items', NULL, NULL, NULL, '2026-03-04 10:39:53', '2026-03-04 15:45:59'),
(50, 'ISS-PH-2026-050', 2, '2026-04-26', 'Outpatient Pharmacy', 'Warfarin prescriptions', 'released', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(51, 'ISS-20260304-151743-8282', 1, '2026-03-04', NULL, NULL, 'approved', '2026-03-04 15:17:59', 1, '2026-03-04 15:18:09', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 15:17:43', '2026-03-04 15:18:09'),
(52, 'ISS-20260304-154048-3242', 1, '2026-03-04', NULL, NULL, 'approved', '2026-03-04 15:41:05', 1, '2026-03-04 15:41:17', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 15:40:48', '2026-03-04 15:41:17'),
(53, 'ISS-20260304-154443-2649', 1, '2026-03-04', 'Ward A', NULL, 'released', '2026-03-04 15:44:48', 1, '2026-03-04 15:44:51', NULL, NULL, NULL, 1, '2026-03-04 15:44:55', NULL, '2026-03-04 15:44:43', '2026-03-04 15:44:55'),
(54, 'ISS-20260312-084823-4128', 1, '2026-03-12', 'Pharmacy 1', 'Resupply', 'released', '2026-03-12 08:48:39', 1, '2026-03-12 08:48:55', NULL, NULL, NULL, 1, '2026-03-12 08:49:06', 'Need ASAP', '2026-03-12 08:48:23', '2026-03-12 08:49:06'),
(55, 'ISS-20260312-093915-3403', 1, '2026-03-12', 'Flow 1', 'Flow 1', 'released', '2026-03-12 09:39:29', 1, '2026-03-12 09:39:44', NULL, NULL, NULL, 1, '2026-03-12 09:39:51', 'Flow 1', '2026-03-12 09:39:15', '2026-03-12 09:39:51'),
(56, 'ISS-20260312-094328-4724', 1, '2026-03-12', 'Flow 2', 'Flow 2', 'released', '2026-03-12 09:43:39', 1, '2026-03-12 09:43:56', NULL, NULL, NULL, 1, '2026-03-12 09:44:05', 'Flow 2', '2026-03-12 09:43:28', '2026-03-12 09:44:05');

-- --------------------------------------------------------

--
-- Table structure for table `issuance_items`
--

CREATE TABLE `issuance_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `issuance_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `unit` varchar(50) NOT NULL DEFAULT 'unit',
  `inventory_stock_id` bigint(20) UNSIGNED DEFAULT NULL,
  `requested_qty` decimal(12,3) NOT NULL,
  `issued_qty` decimal(12,3) NOT NULL DEFAULT 0.000,
  `unit_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issuance_items`
--

INSERT INTO `issuance_items` (`id`, `issuance_id`, `item_name`, `unit`, `inventory_stock_id`, `requested_qty`, `issued_qty`, `unit_cost`, `line_total`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 51, '0.9% Sodium Chloride IV 1L', 'unit', NULL, 1.000, 0.000, 0.00, 0.00, NULL, '2026-03-04 15:17:43', '2026-03-04 15:17:43'),
(2, 52, 'Amiodarone 150mg/3ml Ampule', 'Ampoule', NULL, 11.000, 0.000, 0.00, 0.00, NULL, '2026-03-04 15:40:48', '2026-03-04 15:40:48'),
(3, 53, 'Aspirin 81mg Tablet', 'Box', 12, 11.000, 11.000, 85.00, 935.00, NULL, '2026-03-04 15:44:43', '2026-03-04 15:44:55'),
(4, 54, 'Amoxicillin 500mg Capsule', 'Box', 1, 5.000, 5.000, 350.00, 1750.00, 'Needed at Pharmacy 1', '2026-03-12 08:48:23', '2026-03-12 08:49:06'),
(5, 55, 'Yakapsul 500mg', 'Box', 79, 50.000, 50.000, 200.00, 10000.00, 'Flow 1', '2026-03-12 09:39:15', '2026-03-12 09:39:51'),
(6, 55, 'Kisspirin 100mg', 'Box', 78, 50.000, 50.000, 100.00, 5000.00, 'Flow 1', '2026-03-12 09:39:15', '2026-03-12 09:39:51'),
(7, 56, 'Yakapsul 500mg', 'Box', 79, 60.000, 60.000, 128.33, 7700.00, 'Flow 2', '2026-03-12 09:43:28', '2026-03-12 09:44:05'),
(8, 56, 'Kisspirin 100mg', 'Box', 78, 60.000, 60.000, 100.00, 6000.00, 'Flow 2', '2026-03-12 09:43:28', '2026-03-12 09:44:05');

-- --------------------------------------------------------

--
-- Table structure for table `issuance_item_allocations`
--

CREATE TABLE `issuance_item_allocations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `issuance_id` bigint(20) UNSIGNED NOT NULL,
  `issuance_item_id` bigint(20) UNSIGNED NOT NULL,
  `inventory_stock_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `unit` varchar(50) NOT NULL DEFAULT 'unit',
  `batch_no` varchar(100) DEFAULT NULL,
  `lot_no` varchar(100) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `qty_issued` decimal(12,3) NOT NULL DEFAULT 0.000,
  `unit_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issuance_item_allocations`
--

INSERT INTO `issuance_item_allocations` (`id`, `issuance_id`, `issuance_item_id`, `inventory_stock_id`, `item_name`, `unit`, `batch_no`, `lot_no`, `expiry_date`, `qty_issued`, `unit_cost`, `line_total`, `created_at`, `updated_at`) VALUES
(1, 53, 3, 12, 'Aspirin 81mg Tablet', 'Box', 'B-2612A', 'L-012', '2029-08-15', 11.000, 85.00, 935.00, '2026-03-04 15:44:55', '2026-03-04 15:44:55'),
(2, 54, 4, 1, 'Amoxicillin 500mg Capsule', 'Box', 'B-2601A', 'L-001', '2028-05-30', 5.000, 350.00, 1750.00, '2026-03-12 08:49:06', '2026-03-12 08:49:06'),
(3, 55, 5, 79, 'Yakapsul 500mg', 'Box', 'BATCH-67', 'LOT-109', '2027-01-01', 50.000, 200.00, 10000.00, '2026-03-12 09:39:51', '2026-03-12 09:39:51'),
(4, 55, 6, 78, 'Kisspirin 100mg', 'Box', 'BATCH-67', 'LOT-109', '2027-01-01', 50.000, 100.00, 5000.00, '2026-03-12 09:39:51', '2026-03-12 09:39:51'),
(5, 56, 7, 79, 'Yakapsul 500mg', 'Box', 'BATCH-67', 'LOT-109', '2027-01-01', 17.000, 200.00, 3400.00, '2026-03-12 09:44:05', '2026-03-12 09:44:05'),
(6, 56, 7, 81, 'Yakapsul 500mg', 'Box', 'BATCH-69', 'LOT-169', '2035-01-12', 43.000, 100.00, 4300.00, '2026-03-12 09:44:05', '2026-03-12 09:44:05'),
(7, 56, 8, 78, 'Kisspirin 100mg', 'Box', 'BATCH-67', 'LOT-109', '2027-01-01', 19.000, 100.00, 1900.00, '2026-03-12 09:44:05', '2026-03-12 09:44:05'),
(8, 56, 8, 80, 'Kisspirin 100mg', 'Box', 'BATCH-69', 'LOT-169', '2035-01-01', 41.000, 100.00, 4100.00, '2026-03-12 09:44:05', '2026-03-12 09:44:05');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2020-12-28-223112', 'CodeIgniter\\Shield\\Database\\Migrations\\CreateAuthTables', 'default', 'CodeIgniter\\Shield', 1772589202, 1),
(2, '2021-07-04-041948', 'CodeIgniter\\Settings\\Database\\Migrations\\CreateSettingsTable', 'default', 'CodeIgniter\\Settings', 1772589202, 1),
(3, '2021-11-14-143905', 'CodeIgniter\\Settings\\Database\\Migrations\\AddContextColumn', 'default', 'CodeIgniter\\Settings', 1772589202, 1),
(4, '2026-02-20-000001', 'App\\Database\\Migrations\\CreatePurchaseRequestsTable', 'default', 'App', 1772589202, 1),
(5, '2026-02-20-000002', 'App\\Database\\Migrations\\CreatePurchaseRequestItemsTable', 'default', 'App', 1772589202, 1),
(6, '2026-02-20-000003', 'App\\Database\\Migrations\\CreateApprovalsTable', 'default', 'App', 1772589202, 1),
(7, '2026-02-20-000004', 'App\\Database\\Migrations\\CreatePurchaseOrdersTable', 'default', 'App', 1772589202, 1),
(8, '2026-02-20-000005', 'App\\Database\\Migrations\\CreatePurchaseOrderItemsTable', 'default', 'App', 1772589202, 1),
(9, '2026-02-20-000006', 'App\\Database\\Migrations\\CreatePoRequestsTable', 'default', 'App', 1772589202, 1),
(10, '2026-02-20-000007', 'App\\Database\\Migrations\\CreateReceivingsTable', 'default', 'App', 1772589202, 1),
(11, '2026-02-20-000008', 'App\\Database\\Migrations\\CreateReceivingItemsTable', 'default', 'App', 1772589202, 1),
(12, '2026-02-20-000009', 'App\\Database\\Migrations\\CreateInventoryStocksTable', 'default', 'App', 1772589202, 1),
(13, '2026-02-20-000010', 'App\\Database\\Migrations\\CreateStockMovementsTable', 'default', 'App', 1772589203, 1),
(14, '2026-02-20-000011', 'App\\Database\\Migrations\\CreateIssuancesTable', 'default', 'App', 1772589203, 1),
(15, '2026-02-20-000012', 'App\\Database\\Migrations\\CreateIssuanceItemsTable', 'default', 'App', 1772589203, 1),
(16, '2026-02-20-000013', 'App\\Database\\Migrations\\CreateAuditLogsTable', 'default', 'App', 1772589203, 1),
(17, '2026-02-20-000014', 'App\\Database\\Migrations\\AddReportingPerformanceIndexes', 'default', 'App', 1772589203, 1),
(18, '2026-02-20-000015', 'App\\Database\\Migrations\\CreateAnalyticsEventsTable', 'default', 'App', 1772589203, 1),
(19, '2026-02-20-000016', 'App\\Database\\Migrations\\CreateAnalyticsDailyMetricsTable', 'default', 'App', 1772589203, 1),
(20, '2026-02-26-000017', 'App\\Database\\Migrations\\CreateIssuanceItemAllocationsTable', 'default', 'App', 1772589203, 1);

-- --------------------------------------------------------

--
-- Table structure for table `po_requests`
--

CREATE TABLE `po_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `po_request_number` varchar(50) NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `request_date` date NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `rejected_by` bigint(20) UNSIGNED DEFAULT NULL,
  `rejected_at` datetime DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `po_requests`
--

INSERT INTO `po_requests` (`id`, `po_request_number`, `purchase_order_id`, `requested_by`, `request_date`, `status`, `approved_by`, `approved_at`, `rejected_by`, `rejected_at`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 'POR-PH-001', 1, 1, '2026-03-02', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'POR-PH-002', 2, 1, '2026-03-03', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'POR-PH-003', 4, 1, '2026-03-09', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'POR-PH-004', 5, 1, '2026-03-10', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'POR-PH-005', 7, 1, '2026-03-12', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'POR-PH-006', 9, 1, '2026-03-14', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'POR-PH-007', 10, 1, '2026-03-15', 'approved', 1, '2026-03-04 13:19:42', NULL, NULL, NULL, NULL, '2026-03-04 13:19:42'),
(8, 'POR-PH-008', 1, 1, '2026-03-02', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'POR-PH-009', 2, 1, '2026-03-03', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'POR-PH-010', 4, 1, '2026-03-09', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'POR-PH-011', 11, 1, '2026-03-12', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'POR-PH-012', 12, 1, '2026-03-13', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'POR-PH-013', 14, 1, '2026-03-19', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 'POR-PH-014', 15, 1, '2026-03-14', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'POR-PH-015', 17, 1, '2026-03-17', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 'POR-PH-016', 19, 1, '2026-03-15', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 'POR-PH-017', 20, 1, '2026-03-16', 'approved', 1, '2026-03-04 13:10:15', NULL, NULL, NULL, NULL, '2026-03-04 13:10:15'),
(18, 'POR-PH-018', 11, 1, '2026-03-12', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'POR-PH-019', 12, 1, '2026-03-13', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'POR-PH-020', 14, 1, '2026-03-19', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 'POR-PH-021', 21, 1, '2026-03-22', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'POR-PH-022', 22, 1, '2026-03-23', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'POR-PH-023', 24, 1, '2026-03-29', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 'POR-PH-024', 25, 1, '2026-03-24', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 'POR-PH-025', 27, 1, '2026-03-27', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 'POR-PH-026', 29, 1, '2026-03-25', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 'POR-PH-027', 30, 1, '2026-03-26', 'approved', 1, '2026-03-04 13:10:08', NULL, NULL, NULL, NULL, '2026-03-04 13:10:08'),
(28, 'POR-PH-028', 21, 1, '2026-03-22', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 'POR-PH-029', 22, 1, '2026-03-23', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 'POR-PH-030', 24, 1, '2026-03-29', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 'POR-PH-031', 31, 1, '2026-04-01', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 'POR-PH-032', 32, 1, '2026-04-02', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 'POR-PH-033', 34, 1, '2026-04-08', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 'POR-PH-034', 35, 1, '2026-04-03', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 'POR-PH-035', 37, 1, '2026-04-06', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 'POR-PH-036', 39, 1, '2026-04-04', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 'POR-PH-037', 40, 1, '2026-04-05', 'approved', 1, '2026-03-04 13:09:39', NULL, NULL, NULL, NULL, '2026-03-04 13:09:39'),
(38, 'POR-PH-038', 31, 1, '2026-04-01', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 'POR-PH-039', 32, 1, '2026-04-02', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 'POR-PH-040', 34, 1, '2026-04-08', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 'POR-PH-041', 41, 1, '2026-04-12', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 'POR-PH-042', 42, 1, '2026-04-13', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 'POR-PH-043', 44, 1, '2026-04-19', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 'POR-PH-044', 45, 1, '2026-04-14', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 'POR-PH-045', 47, 1, '2026-04-17', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 'POR-PH-046', 49, 1, '2026-04-15', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 'POR-PH-047', 50, 1, '2026-04-16', 'approved', 1, '2026-03-04 10:42:24', NULL, NULL, NULL, NULL, '2026-03-04 10:42:24'),
(48, 'POR-PH-048', 41, 1, '2026-04-12', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 'POR-PH-049', 42, 1, '2026-04-13', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(50, 'POR-PH-050', 44, 1, '2026-04-19', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(51, 'POR-20260304-105109-8100', 51, 1, '2026-03-04', 'converted_to_receiving', 1, '2026-03-04 10:51:24', NULL, NULL, NULL, '2026-03-04 10:51:09', '2026-03-04 15:02:53'),
(52, 'POR-20260304-132425-6921', 52, 1, '2026-03-04', 'rejected', NULL, NULL, 1, '2026-03-04 13:26:00', 'w', '2026-03-04 13:24:25', '2026-03-04 13:26:00'),
(53, 'POR-20260304-132432-3027', 53, 1, '2026-03-04', 'approved', 1, '2026-03-04 13:25:48', NULL, NULL, NULL, '2026-03-04 13:24:32', '2026-03-04 13:25:48'),
(54, 'POR-20260304-132638-8856', 52, 1, '2026-03-04', 'approved', 1, '2026-03-04 13:27:22', NULL, NULL, NULL, '2026-03-04 13:26:38', '2026-03-04 13:27:22'),
(55, 'POR-20260304-132654-4519', 52, 1, '2026-03-04', 'approved', 1, '2026-03-04 13:27:21', NULL, NULL, NULL, '2026-03-04 13:26:54', '2026-03-04 13:27:21'),
(56, 'POR-20260304-140822-9617', 52, 1, '2026-03-04', 'approved', 1, '2026-03-04 14:14:44', NULL, NULL, NULL, '2026-03-04 14:08:22', '2026-03-04 14:14:44'),
(57, 'POR-20260304-140830-5418', 52, 1, '2026-03-04', 'approved', 1, '2026-03-04 14:14:35', NULL, NULL, NULL, '2026-03-04 14:08:30', '2026-03-04 14:14:35'),
(58, 'POR-20260304-140841-6183', 52, 1, '2026-03-04', 'converted_to_receiving', 1, '2026-03-04 14:14:19', NULL, NULL, NULL, '2026-03-04 14:08:41', '2026-03-04 15:02:15'),
(59, 'POR-20260304-150856-3476', 59, 1, '2026-03-04', 'converted_to_receiving', 1, '2026-03-04 15:09:30', NULL, NULL, NULL, '2026-03-04 15:08:56', '2026-03-04 17:02:10'),
(60, 'POR-20260304-151629-8538', 60, 1, '2026-03-04', 'converted_to_receiving', 1, '2026-03-04 15:16:34', NULL, NULL, NULL, '2026-03-04 15:16:29', '2026-03-04 15:16:50'),
(61, 'POR-20260304-154319-5579', 61, 1, '2026-03-04', 'converted_to_receiving', 1, '2026-03-04 15:43:23', NULL, NULL, NULL, '2026-03-04 15:43:19', '2026-03-04 15:43:42'),
(62, 'POR-20260304-163835-2483', 62, 1, '2026-03-04', 'converted_to_receiving', 1, '2026-03-04 16:38:39', NULL, NULL, NULL, '2026-03-04 16:38:35', '2026-03-04 16:38:59'),
(63, 'POR-20260310-081926-5982', 56, 1, '2026-03-10', 'approved', 1, '2026-03-10 08:19:43', NULL, NULL, NULL, '2026-03-10 08:19:26', '2026-03-10 08:19:43'),
(64, 'POR-20260312-083122-3191', 63, 1, '2026-03-12', 'converted_to_receiving', 1, '2026-03-12 08:31:50', NULL, NULL, NULL, '2026-03-12 08:31:22', '2026-03-12 08:41:21'),
(65, 'POR-20260312-092732-4883', 64, 1, '2026-03-12', 'converted_to_receiving', 1, '2026-03-12 09:27:56', NULL, NULL, NULL, '2026-03-12 09:27:32', '2026-03-12 09:34:49'),
(66, 'POR-20260312-093102-4078', 65, 1, '2026-03-12', 'converted_to_receiving', 1, '2026-03-12 09:31:13', NULL, NULL, NULL, '2026-03-12 09:31:02', '2026-03-12 09:36:36');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `po_number` varchar(50) NOT NULL,
  `purchase_request_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `order_date` date NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'draft',
  `subtotal_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `issued_by` bigint(20) UNSIGNED DEFAULT NULL,
  `issued_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `po_number`, `purchase_request_id`, `supplier_name`, `order_date`, `status`, `subtotal_amount`, `total_amount`, `issued_by`, `issued_at`, `created_at`, `updated_at`) VALUES
(1, 'PO-PH-2026-001', 1, 'MediSupply Plus', '2026-03-02', 'issued', 35000.00, 39200.00, 1, '2026-03-02 09:00:00', '2026-03-04 10:32:36', NULL),
(2, 'PO-PH-2026-002', 2, 'PharmaDist Corp', '2026-03-03', 'issued', 24000.00, 26880.00, 1, '2026-03-03 10:30:00', '2026-03-04 10:32:36', NULL),
(3, 'PO-PH-2026-003', 5, 'HealthLink Distributors', '2026-03-06', 'draft', 18000.00, 20160.00, NULL, NULL, '2026-03-04 10:32:36', NULL),
(4, 'PO-PH-2026-004', 8, 'MediSupply Plus', '2026-03-09', 'issued', 45000.00, 50400.00, 1, '2026-03-09 11:15:00', '2026-03-04 10:32:36', NULL),
(5, 'PO-PH-2026-005', 1, 'PharmaDist Corp', '2026-03-10', 'completed', 15000.00, 16800.00, 1, '2026-03-10 13:00:00', '2026-03-04 10:32:36', NULL),
(6, 'PO-PH-2026-006', 2, 'HealthLink Distributors', '2026-03-11', 'draft', 21000.00, 23520.00, NULL, NULL, '2026-03-04 10:32:36', NULL),
(7, 'PO-PH-2026-007', 5, 'MediSupply Plus', '2026-03-12', 'issued', 28000.00, 31360.00, 1, '2026-03-12 14:45:00', '2026-03-04 10:32:36', NULL),
(8, 'PO-PH-2026-008', 8, 'PharmaDist Corp', '2026-03-13', 'cancelled', 0.00, 0.00, 1, '2026-03-13 15:30:00', '2026-03-04 10:32:36', NULL),
(9, 'PO-PH-2026-009', 1, 'HealthLink Distributors', '2026-03-14', 'completed', 32000.00, 35840.00, 1, '2026-03-14 16:00:00', '2026-03-04 10:32:36', NULL),
(10, 'PO-PH-2026-010', 2, 'MediSupply Plus', '2026-03-15', 'issued', 11500.00, 12880.00, 1, '2026-03-15 08:30:00', '2026-03-04 10:32:36', NULL),
(11, 'PO-PH-2026-011', 11, 'MediSupply Plus', '2026-03-12', 'issued', 22000.00, 24640.00, 1, '2026-03-12 09:00:00', '2026-03-04 10:34:03', NULL),
(12, 'PO-PH-2026-012', 12, 'CareFirst Meds', '2026-03-13', 'issued', 15000.00, 16800.00, 1, '2026-03-13 10:30:00', '2026-03-04 10:34:03', NULL),
(13, 'PO-PH-2026-013', 15, 'HealthLink Distributors', '2026-03-16', 'draft', 12000.00, 13440.00, NULL, NULL, '2026-03-04 10:34:03', NULL),
(14, 'PO-PH-2026-014', 18, 'PharmaDist Corp', '2026-03-19', 'issued', 31000.00, 34720.00, 1, '2026-03-19 11:15:00', '2026-03-04 10:34:03', NULL),
(15, 'PO-PH-2026-015', 11, 'CareFirst Meds', '2026-03-14', 'completed', 18000.00, 20160.00, 1, '2026-03-14 13:00:00', '2026-03-04 10:34:03', NULL),
(16, 'PO-PH-2026-016', 12, 'HealthLink Distributors', '2026-03-15', 'draft', 25000.00, 28000.00, NULL, NULL, '2026-03-04 10:34:03', NULL),
(17, 'PO-PH-2026-017', 15, 'MediSupply Plus', '2026-03-17', 'issued', 19000.00, 21280.00, 1, '2026-03-17 14:45:00', '2026-03-04 10:34:03', NULL),
(18, 'PO-PH-2026-018', 18, 'PharmaDist Corp', '2026-03-20', 'cancelled', 0.00, 0.00, 1, '2026-03-20 15:30:00', '2026-03-04 10:34:03', NULL),
(19, 'PO-PH-2026-019', 11, 'CareFirst Meds', '2026-03-15', 'completed', 27000.00, 30240.00, 1, '2026-03-15 16:00:00', '2026-03-04 10:34:03', NULL),
(20, 'PO-PH-2026-020', 12, 'MediSupply Plus', '2026-03-16', 'issued', 14500.00, 16240.00, 1, '2026-03-16 08:30:00', '2026-03-04 10:34:03', NULL),
(21, 'PO-PH-2026-021', 21, 'MediSupply Plus', '2026-03-22', 'issued', 12500.00, 14000.00, 1, '2026-03-22 09:00:00', '2026-03-04 10:35:47', NULL),
(22, 'PO-PH-2026-022', 22, 'CareFirst Meds', '2026-03-23', 'issued', 21600.00, 24192.00, 1, '2026-03-23 10:30:00', '2026-03-04 10:35:47', NULL),
(23, 'PO-PH-2026-023', 25, 'HealthLink Distributors', '2026-03-26', 'draft', 68000.00, 76160.00, NULL, NULL, '2026-03-04 10:35:47', NULL),
(24, 'PO-PH-2026-024', 28, 'PharmaDist Corp', '2026-03-29', 'issued', 18000.00, 20160.00, 1, '2026-03-29 11:15:00', '2026-03-04 10:35:47', NULL),
(25, 'PO-PH-2026-025', 21, 'CareFirst Meds', '2026-03-24', 'completed', 30000.00, 33600.00, 1, '2026-03-24 13:00:00', '2026-03-04 10:35:47', NULL),
(26, 'PO-PH-2026-026', 22, 'HealthLink Distributors', '2026-03-25', 'issued', 28800.00, 32256.00, 1, '2026-03-04 14:48:31', '2026-03-04 10:35:47', '2026-03-04 14:48:31'),
(27, 'PO-PH-2026-027', 25, 'MediSupply Plus', '2026-03-27', 'issued', 57400.00, 64288.00, 1, '2026-03-27 14:45:00', '2026-03-04 10:35:47', NULL),
(28, 'PO-PH-2026-028', 28, 'PharmaDist Corp', '2026-03-30', 'cancelled', 0.00, 0.00, 1, '2026-03-30 15:30:00', '2026-03-04 10:35:47', NULL),
(29, 'PO-PH-2026-029', 21, 'CareFirst Meds', '2026-03-25', 'completed', 11400.00, 12768.00, 1, '2026-03-25 16:00:00', '2026-03-04 10:35:47', NULL),
(30, 'PO-PH-2026-030', 22, 'MediSupply Plus', '2026-03-26', 'issued', 22000.00, 24640.00, 1, '2026-03-26 08:30:00', '2026-03-04 10:35:47', NULL),
(31, 'PO-PH-2026-031', 31, 'MediSupply Plus', '2026-04-01', 'issued', 15000.00, 16800.00, 1, '2026-04-01 09:00:00', '2026-03-04 10:38:14', NULL),
(32, 'PO-PH-2026-032', 32, 'PharmaDist Corp', '2026-04-02', 'issued', 42500.00, 47600.00, 1, '2026-04-02 10:30:00', '2026-03-04 10:38:14', NULL),
(33, 'PO-PH-2026-033', 35, 'HealthLink Distributors', '2026-04-05', 'draft', 18000.00, 20160.00, NULL, NULL, '2026-03-04 10:38:14', NULL),
(34, 'PO-PH-2026-034', 38, 'CareFirst Meds', '2026-04-08', 'issued', 36000.00, 40320.00, 1, '2026-04-08 11:15:00', '2026-03-04 10:38:14', NULL),
(35, 'PO-PH-2026-035', 31, 'PharmaDist Corp', '2026-04-03', 'completed', 11400.00, 12768.00, 1, '2026-04-03 13:00:00', '2026-03-04 10:38:14', NULL),
(36, 'PO-PH-2026-036', 32, 'HealthLink Distributors', '2026-04-04', 'draft', 25000.00, 28000.00, NULL, NULL, '2026-03-04 10:38:14', NULL),
(37, 'PO-PH-2026-037', 35, 'MediSupply Plus', '2026-04-06', 'issued', 39000.00, 43680.00, 1, '2026-04-06 14:45:00', '2026-03-04 10:38:14', NULL),
(38, 'PO-PH-2026-038', 38, 'CareFirst Meds', '2026-04-09', 'cancelled', 0.00, 0.00, 1, '2026-04-09 15:30:00', '2026-03-04 10:38:14', NULL),
(39, 'PO-PH-2026-039', 31, 'HealthLink Distributors', '2026-04-04', 'completed', 63800.00, 71456.00, 1, '2026-04-04 16:00:00', '2026-03-04 10:38:14', NULL),
(40, 'PO-PH-2026-040', 32, 'MediSupply Plus', '2026-04-05', 'issued', 15400.00, 17248.00, 1, '2026-04-05 08:30:00', '2026-03-04 10:38:14', NULL),
(41, 'PO-PH-2026-041', 41, 'MediSupply Plus', '2026-04-12', 'issued', 33000.00, 36960.00, 1, '2026-04-12 09:00:00', '2026-03-04 10:39:53', NULL),
(42, 'PO-PH-2026-042', 42, 'CareFirst Meds', '2026-04-13', 'issued', 12000.00, 13440.00, 1, '2026-04-13 10:30:00', '2026-03-04 10:39:53', NULL),
(43, 'PO-PH-2026-043', 45, 'HealthLink Distributors', '2026-04-16', 'issued', 11400.00, 12768.00, 1, '2026-03-04 14:08:57', '2026-03-04 10:39:53', '2026-03-04 14:08:57'),
(44, 'PO-PH-2026-044', 48, 'PharmaDist Corp', '2026-04-19', 'issued', 33000.00, 36960.00, 1, '2026-04-19 11:15:00', '2026-03-04 10:39:53', NULL),
(45, 'PO-PH-2026-045', 41, 'CareFirst Meds', '2026-04-14', 'completed', 22500.00, 25200.00, 1, '2026-04-14 13:00:00', '2026-03-04 10:39:53', NULL),
(46, 'PO-PH-2026-046', 42, 'HealthLink Distributors', '2026-04-15', 'issued', 51000.00, 57120.00, 1, '2026-03-04 14:08:51', '2026-03-04 10:39:53', '2026-03-04 14:08:51'),
(47, 'PO-PH-2026-047', 45, 'MediSupply Plus', '2026-04-17', 'issued', 48000.00, 53760.00, 1, '2026-04-17 14:45:00', '2026-03-04 10:39:53', NULL),
(48, 'PO-PH-2026-048', 48, 'PharmaDist Corp', '2026-04-20', 'cancelled', 0.00, 0.00, 1, '2026-04-20 15:30:00', '2026-03-04 10:39:53', NULL),
(49, 'PO-PH-2026-049', 41, 'CareFirst Meds', '2026-04-15', 'completed', 61200.00, 68544.00, 1, '2026-04-15 16:00:00', '2026-03-04 10:39:53', NULL),
(50, 'PO-PH-2026-050', 42, 'MediSupply Plus', '2026-04-16', 'issued', 31900.00, 35728.00, 1, '2026-04-16 08:30:00', '2026-03-04 10:39:53', NULL),
(51, 'PO-20260304-105041-2236', 51, 'MediSupply Plus', '2026-03-04', 'fully_received', 10315000.00, 10315000.00, 1, '2026-03-04 10:50:51', '2026-03-04 10:50:41', '2026-03-04 15:02:53'),
(52, 'PO-20260304-132335-4330', 55, NULL, '2026-03-04', 'fully_received', 72213129.00, 72213129.00, 1, '2026-03-04 13:23:44', '2026-03-04 13:23:35', '2026-03-04 15:02:15'),
(53, 'PO-20260304-132354-9032', 54, NULL, '2026-03-04', 'issued', 1729.00, 1729.00, 1, '2026-03-04 13:24:09', '2026-03-04 13:23:54', '2026-03-04 13:24:09'),
(54, 'PO-20260304-150046-6324', 64, NULL, '2026-03-04', 'issued', 242.00, 242.00, 1, '2026-03-04 15:00:53', '2026-03-04 15:00:46', '2026-03-04 15:00:53'),
(55, 'PO-20260304-150104-8725', 63, NULL, '2026-03-04', 'issued', 74336.00, 74336.00, 1, '2026-03-04 15:03:29', '2026-03-04 15:01:04', '2026-03-04 15:03:29'),
(56, 'PO-20260304-150118-9025', 61, NULL, '2026-03-04', 'issued', 9999999999.99, 9999999999.99, 1, '2026-03-04 15:01:23', '2026-03-04 15:01:18', '2026-03-04 15:01:23'),
(57, 'PO-20260304-150419-2711', 65, NULL, '2026-03-04', 'issued', 4.00, 4.00, 1, '2026-03-04 15:04:24', '2026-03-04 15:04:19', '2026-03-04 15:04:24'),
(58, 'PO-20260304-150436-6831', 60, NULL, '2026-03-04', 'issued', 26499981.00, 26499981.00, 1, '2026-03-04 15:08:19', '2026-03-04 15:04:36', '2026-03-04 15:08:19'),
(59, 'PO-20260304-150448-2334', 59, NULL, '2026-03-04', 'partially_received', 546920.00, 546920.00, 1, '2026-03-04 15:04:53', '2026-03-04 15:04:48', '2026-03-04 17:02:10'),
(60, 'PO-20260304-151617-2536', 66, NULL, '2026-03-04', 'fully_received', 426.00, 426.00, 1, '2026-03-04 15:16:23', '2026-03-04 15:16:17', '2026-03-04 15:16:50'),
(61, 'PO-20260304-154311-4509', 67, NULL, '2026-03-04', 'fully_received', 10000.00, 10000.00, 1, '2026-03-04 15:43:15', '2026-03-04 15:43:11', '2026-03-04 15:43:42'),
(62, 'PO-20260304-163812-5210', 69, NULL, '2026-03-04', 'fully_received', 60450.00, 60450.00, 1, '2026-03-04 16:38:18', '2026-03-04 16:38:12', '2026-03-04 16:38:59'),
(63, 'PO-20260312-082615-9212', 70, 'MediSupply Plus', '2026-03-12', 'partially_received', 5000.00, 5000.00, 1, '2026-03-12 08:31:14', '2026-03-12 08:26:15', '2026-03-12 08:41:21'),
(64, 'PO-20260312-092711-5156', 71, 'MediSupply Minus', '2026-03-12', 'fully_received', 20300.00, 20300.00, 1, '2026-03-12 09:27:19', '2026-03-12 09:27:11', '2026-03-12 09:34:49'),
(65, 'PO-20260312-093047-9595', 72, 'MediSupply Plus', '2026-03-12', 'fully_received', 10000.00, 10000.00, 1, '2026-03-12 09:30:54', '2026-03-12 09:30:47', '2026-03-12 09:36:36'),
(66, 'PO-20260312-143853-1419', 73, 'MediSupply Plus', '2026-03-12', 'issued', 1.00, 1.00, 1, '2026-03-12 14:39:03', '2026-03-12 14:38:53', '2026-03-12 14:39:03');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_request_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `unit` varchar(50) NOT NULL DEFAULT 'unit',
  `ordered_qty` decimal(12,3) NOT NULL,
  `received_qty` decimal(12,3) NOT NULL DEFAULT 0.000,
  `unit_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`id`, `purchase_order_id`, `purchase_request_item_id`, `item_name`, `unit`, `ordered_qty`, `received_qty`, `unit_cost`, `line_total`, `created_at`, `updated_at`) VALUES
(1, 51, 6, 'Amoxicillin 500mg Capsule', 'Piece', 55.000, 55.000, 1000.00, 55000.00, '2026-03-04 10:50:41', '2026-03-04 15:02:53'),
(2, 51, 7, 'Cetirizine 10mg Tablet', 'Box', 56.000, 56.000, 40000.00, 2240000.00, '2026-03-04 10:50:41', '2026-03-04 15:02:53'),
(3, 51, 8, 'Clopidogrel 75mg Tablet', 'Box', 56.000, 56.000, 70000.00, 3920000.00, '2026-03-04 10:50:41', '2026-03-04 15:02:53'),
(4, 51, 9, 'Digoxin 0.25mg Tablet', 'Box', 67.000, 67.000, 50000.00, 3350000.00, '2026-03-04 10:50:41', '2026-03-04 15:02:53'),
(5, 51, 10, 'Paracetamol 250mg/5ml Syrup', 'Bottle', 75.000, 75.000, 10000.00, 750000.00, '2026-03-04 10:50:41', '2026-03-04 15:02:53'),
(6, 52, 14, 'Dexamethasone 4mg Tablet', 'Bottle', 3123.000, 3123.000, 23123.00, 72213129.00, '2026-03-04 13:23:35', '2026-03-04 15:02:15'),
(7, 53, 13, 'Amiodarone 150mg/3ml Ampule', 'Capsule', 13.000, 0.000, 133.00, 1729.00, '2026-03-04 13:23:54', '2026-03-04 13:23:54'),
(8, 54, 29, 'Clopidogrel 75mg Tablet', 'Capsule', 11.000, 0.000, 22.00, 242.00, '2026-03-04 15:00:46', '2026-03-04 15:00:46'),
(9, 55, 28, 'Amlodipine 5mg Tablet', 'Box', 32.000, 0.000, 2323.00, 74336.00, '2026-03-04 15:01:04', '2026-03-04 15:01:04'),
(10, 56, 23, 'Digoxin 0.25mg Tablet', 'Piece', 12412.000, 0.000, 14124.00, 175307088.00, '2026-03-04 15:01:18', '2026-03-04 15:01:18'),
(11, 56, 24, 'Ciprofloxacin 500mg Tablet', 'Pack', 124124.000, 0.000, 21412414.00, 9999999999.99, '2026-03-04 15:01:18', '2026-03-04 15:01:18'),
(12, 56, 25, 'Aspirin 81mg Tablet', 'Tablet', 124.000, 0.000, 124.00, 15376.00, '2026-03-04 15:01:18', '2026-03-04 15:01:18'),
(13, 56, 26, 'Ceftriaxone 1g Vial', 'Capsule', 124124.000, 0.000, 1241241.00, 9999999999.99, '2026-03-04 15:01:18', '2026-03-04 15:01:18'),
(14, 57, 30, '0.9% Sodium Chloride IV 1L', 'Pack', 2.000, 0.000, 2.00, 4.00, '2026-03-04 15:04:19', '2026-03-04 15:04:19'),
(15, 58, 21, 'Dexamethasone 4mg Tablet', 'Tablet', 123.000, 0.000, 1323.00, 162729.00, '2026-03-04 15:04:36', '2026-03-04 15:04:36'),
(16, 58, 22, 'Clopidogrel 75mg Tablet', 'Pack', 123.000, 0.000, 214124.00, 26337252.00, '2026-03-04 15:04:36', '2026-03-04 15:04:36'),
(17, 59, 18, 'Azithromycin 500mg Tablet', 'Capsule', 124.000, 113.000, 124.00, 15376.00, '2026-03-04 15:04:48', '2026-03-04 17:02:10'),
(18, 59, 19, 'Azithromycin 500mg Tablet', 'Tablet', 2412.000, 2400.000, 214.00, 516168.00, '2026-03-04 15:04:48', '2026-03-04 17:02:10'),
(19, 59, 20, 'Clopidogrel 75mg Tablet', 'Tube', 124.000, 112.000, 124.00, 15376.00, '2026-03-04 15:04:48', '2026-03-04 17:02:10'),
(20, 60, 31, '0.9% Sodium Chloride IV 1L', 'Bottle', 2.000, 2.000, 213.00, 426.00, '2026-03-04 15:16:17', '2026-03-04 15:16:50'),
(21, 61, 32, 'Aspirin 81mg Tablet', 'Box', 100.000, 100.000, 100.00, 10000.00, '2026-03-04 15:43:11', '2026-03-04 15:43:42'),
(22, 62, 38, 'Paracetamol 500mg', 'Box', 20.000, 20.000, 150.00, 3000.00, '2026-03-04 16:38:12', '2026-03-04 16:38:59'),
(23, 62, 39, 'Amoxicillin 250mg', 'Capsule', 100.000, 100.000, 5.50, 550.00, '2026-03-04 16:38:12', '2026-03-04 16:38:59'),
(24, 62, 40, 'Surgical Masks', 'Box', 50.000, 50.000, 55.00, 2750.00, '2026-03-04 16:38:12', '2026-03-04 16:38:59'),
(25, 62, 41, 'Latex Gloves (Medium)', 'Pack', 30.000, 30.000, 250.00, 7500.00, '2026-03-04 16:38:12', '2026-03-04 16:38:59'),
(26, 62, 42, 'Syringe 5ml', 'Piece', 200.000, 200.000, 12.00, 2400.00, '2026-03-04 16:38:12', '2026-03-04 16:38:59'),
(27, 62, 43, 'Ibuprofen 400mg', 'Tablet', 500.000, 500.000, 8.00, 4000.00, '2026-03-04 16:38:12', '2026-03-04 16:38:59'),
(28, 62, 44, 'Gauze Pad 4x4', 'Pack', 20.000, 20.000, 45.00, 900.00, '2026-03-04 16:38:12', '2026-03-04 16:38:59'),
(29, 62, 45, 'Cotton Balls', 'Roll', 15.000, 15.000, 80.00, 1200.00, '2026-03-04 16:38:12', '2026-03-04 16:38:59'),
(30, 62, 46, 'Povidone Iodine 10%', 'Bottle', 10.000, 10.000, 120.00, 1200.00, '2026-03-04 16:38:12', '2026-03-04 16:38:59'),
(31, 62, 47, 'Digital Thermometer', 'Piece', 5.000, 5.000, 150.00, 750.00, '2026-03-04 16:38:12', '2026-03-04 16:38:59'),
(32, 62, 48, 'Lidocaine Ointment', 'Tube', 12.000, 12.000, 350.00, 4200.00, '2026-03-04 16:38:12', '2026-03-04 16:38:59'),
(33, 62, 49, 'Saline Solution 1L', 'Piece', 50.000, 50.000, 90.00, 4500.00, '2026-03-04 16:38:12', '2026-03-04 16:38:59'),
(34, 62, 50, 'Epinephrine 10mg', 'Vial', 25.000, 25.000, 800.00, 20000.00, '2026-03-04 16:38:12', '2026-03-04 16:38:59'),
(35, 62, 51, 'IV Cannula G20', 'Piece', 100.000, 100.000, 0.00, 0.00, '2026-03-04 16:38:12', '2026-03-04 16:38:59'),
(36, 62, 52, 'First Aid Kit', 'Set', 3.000, 3.000, 2500.00, 7500.00, '2026-03-04 16:38:12', '2026-03-04 16:38:59'),
(37, 63, 53, 'Amoxicillin 500mg Capsule', 'Box', 10.000, 9.000, 500.00, 5000.00, '2026-03-12 08:26:15', '2026-03-12 08:41:21'),
(38, 64, 54, 'Kisspirin 100mg', 'Box', 69.000, 69.000, 100.00, 6900.00, '2026-03-12 09:27:11', '2026-03-12 09:34:49'),
(39, 64, 55, 'Yakapsul 500mg', 'Box', 67.000, 67.000, 200.00, 13400.00, '2026-03-12 09:27:11', '2026-03-12 09:34:49'),
(40, 65, 56, 'Kisspirin 100mg', 'Box', 50.000, 50.000, 100.00, 5000.00, '2026-03-12 09:30:47', '2026-03-12 09:36:36'),
(41, 65, 57, 'Yakapsul 500mg', 'Box', 50.000, 50.000, 100.00, 5000.00, '2026-03-12 09:30:47', '2026-03-12 09:36:36'),
(42, 66, 58, 'Cotton Balls', 'Vial', 1.000, 0.000, 1.00, 1.00, '2026-03-12 14:38:53', '2026-03-12 14:38:53');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requests`
--

CREATE TABLE `purchase_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pr_number` varchar(50) NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `request_date` date NOT NULL,
  `needed_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'draft',
  `submitted_at` datetime DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `rejected_by` bigint(20) UNSIGNED DEFAULT NULL,
  `rejected_at` datetime DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_requests`
--

INSERT INTO `purchase_requests` (`id`, `pr_number`, `requested_by`, `request_date`, `needed_date`, `remarks`, `status`, `submitted_at`, `approved_by`, `approved_at`, `rejected_by`, `rejected_at`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 'PR-PH-2026-001', 2, '2026-03-01', '2026-03-10', NULL, 'approved', '2026-03-01 08:30:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:35', NULL),
(2, 'PR-PH-2026-002', 3, '2026-03-02', '2026-03-12', NULL, 'approved', '2026-03-02 09:15:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:35', NULL),
(3, 'PR-PH-2026-003', 2, '2026-03-03', '2026-03-15', NULL, 'pending', '2026-03-03 10:00:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:35', NULL),
(4, 'PR-PH-2026-004', 3, '2026-03-04', '2026-03-18', NULL, 'draft', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:35', NULL),
(5, 'PR-PH-2026-005', 2, '2026-03-05', '2026-03-20', NULL, 'approved', '2026-03-05 11:30:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:35', NULL),
(6, 'PR-PH-2026-006', 3, '2026-03-06', '2026-03-22', NULL, 'rejected', '2026-03-06 13:45:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:35', NULL),
(7, 'PR-PH-2026-007', 2, '2026-03-07', '2026-03-25', NULL, 'pending', '2026-03-07 14:20:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:35', NULL),
(8, 'PR-PH-2026-008', 3, '2026-03-08', '2026-03-28', NULL, 'approved', '2026-03-08 15:10:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:35', NULL),
(9, 'PR-PH-2026-009', 2, '2026-03-09', '2026-03-30', NULL, 'draft', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:35', NULL),
(10, 'PR-PH-2026-010', 3, '2026-03-10', '2026-04-05', NULL, 'pending', '2026-03-10 16:30:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:35', NULL),
(11, 'PR-PH-2026-011', 2, '2026-03-11', '2026-03-25', NULL, 'approved', '2026-03-11 08:30:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(12, 'PR-PH-2026-012', 3, '2026-03-12', '2026-03-26', NULL, 'approved', '2026-03-12 09:15:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(13, 'PR-PH-2026-013', 2, '2026-03-13', '2026-03-27', NULL, 'pending', '2026-03-13 10:00:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(14, 'PR-PH-2026-014', 3, '2026-03-14', '2026-03-28', NULL, 'draft', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(15, 'PR-PH-2026-015', 2, '2026-03-15', '2026-03-29', NULL, 'approved', '2026-03-15 11:30:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(16, 'PR-PH-2026-016', 3, '2026-03-16', '2026-03-30', NULL, 'rejected', '2026-03-16 13:45:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(17, 'PR-PH-2026-017', 2, '2026-03-17', '2026-03-31', NULL, 'pending', '2026-03-17 14:20:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(18, 'PR-PH-2026-018', 3, '2026-03-18', '2026-04-01', NULL, 'approved', '2026-03-18 15:10:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(19, 'PR-PH-2026-019', 2, '2026-03-19', '2026-04-02', NULL, 'draft', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(20, 'PR-PH-2026-020', 3, '2026-03-20', '2026-04-03', NULL, 'pending', '2026-03-20 16:30:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(21, 'PR-PH-2026-021', 2, '2026-03-21', '2026-04-05', NULL, 'approved', '2026-03-21 08:00:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(22, 'PR-PH-2026-022', 3, '2026-03-22', '2026-04-06', NULL, 'approved', '2026-03-22 09:30:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(23, 'PR-PH-2026-023', 2, '2026-03-23', '2026-04-07', NULL, 'pending', '2026-03-23 10:15:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(24, 'PR-PH-2026-024', 3, '2026-03-24', '2026-04-08', NULL, 'draft', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(25, 'PR-PH-2026-025', 2, '2026-03-25', '2026-04-09', NULL, 'approved', '2026-03-25 11:45:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(26, 'PR-PH-2026-026', 3, '2026-03-26', '2026-04-10', NULL, 'rejected', '2026-03-26 13:00:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(27, 'PR-PH-2026-027', 2, '2026-03-27', '2026-04-11', NULL, 'pending', '2026-03-27 14:20:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(28, 'PR-PH-2026-028', 3, '2026-03-28', '2026-04-12', NULL, 'approved', '2026-03-28 15:10:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(29, 'PR-PH-2026-029', 2, '2026-03-29', '2026-04-13', NULL, 'draft', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(30, 'PR-PH-2026-030', 3, '2026-03-30', '2026-04-14', NULL, 'pending', '2026-03-30 16:45:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(31, 'PR-PH-2026-031', 2, '2026-03-31', '2026-04-15', NULL, 'approved', '2026-03-31 08:30:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(32, 'PR-PH-2026-032', 3, '2026-04-01', '2026-04-16', NULL, 'approved', '2026-04-01 09:15:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(33, 'PR-PH-2026-033', 2, '2026-04-02', '2026-04-17', NULL, 'pending', '2026-04-02 10:00:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(34, 'PR-PH-2026-034', 3, '2026-04-03', '2026-04-18', NULL, 'draft', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(35, 'PR-PH-2026-035', 2, '2026-04-04', '2026-04-19', NULL, 'approved', '2026-04-04 11:30:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(36, 'PR-PH-2026-036', 3, '2026-04-05', '2026-04-20', NULL, 'rejected', '2026-04-05 13:45:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(37, 'PR-PH-2026-037', 2, '2026-04-06', '2026-04-21', NULL, 'pending', '2026-04-06 14:20:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(38, 'PR-PH-2026-038', 3, '2026-04-07', '2026-04-22', NULL, 'approved', '2026-04-07 15:10:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(39, 'PR-PH-2026-039', 2, '2026-04-08', '2026-04-23', NULL, 'cancelled', NULL, NULL, NULL, 1, '2026-03-04 10:48:19', 'Cancelled by user.', '2026-03-04 10:38:14', '2026-03-04 10:48:19'),
(40, 'PR-PH-2026-040', 3, '2026-04-09', '2026-04-24', NULL, 'pending', '2026-04-09 16:30:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(41, 'PR-PH-2026-041', 2, '2026-04-11', '2026-04-25', NULL, 'approved', '2026-04-11 08:00:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(42, 'PR-PH-2026-042', 3, '2026-04-12', '2026-04-26', NULL, 'approved', '2026-04-12 09:30:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(43, 'PR-PH-2026-043', 2, '2026-04-13', '2026-04-27', NULL, 'pending', '2026-04-13 10:15:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(44, 'PR-PH-2026-044', 3, '2026-04-14', '2026-04-28', NULL, 'cancelled', NULL, NULL, NULL, 1, '2026-03-04 10:48:36', 'Cancelled by user.', '2026-03-04 10:39:53', '2026-03-04 10:48:36'),
(45, 'PR-PH-2026-045', 2, '2026-04-15', '2026-04-29', NULL, 'approved', '2026-04-15 11:45:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(46, 'PR-PH-2026-046', 3, '2026-04-16', '2026-04-30', NULL, 'rejected', '2026-04-16 13:00:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(47, 'PR-PH-2026-047', 2, '2026-04-17', '2026-05-01', NULL, 'pending', '2026-04-17 14:20:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(48, 'PR-PH-2026-048', 3, '2026-04-18', '2026-05-02', NULL, 'approved', '2026-04-18 15:10:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(49, 'PR-PH-2026-049', 2, '2026-04-19', '2026-05-03', NULL, 'cancelled', NULL, NULL, NULL, 1, '2026-03-04 10:48:45', 'Cancelled by user.', '2026-03-04 10:39:53', '2026-03-04 10:48:45'),
(50, 'PR-PH-2026-050', 3, '2026-04-20', '2026-05-04', NULL, 'pending', '2026-04-20 16:45:00', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(51, 'PR-20260304-104653-5807', 1, '2026-03-04', '2026-03-09', 'test1', 'converted_to_po', '2026-03-04 10:48:50', 1, '2026-03-04 10:49:17', NULL, NULL, NULL, '2026-03-04 10:46:53', '2026-03-04 10:50:41'),
(52, 'PR-20260304-132059-6670', 1, '2026-03-04', NULL, NULL, 'rejected', '2026-03-04 13:22:55', NULL, NULL, 1, '2026-03-04 13:28:48', 'yuyt', '2026-03-04 13:20:59', '2026-03-04 13:28:48'),
(53, 'PR-20260304-132117-4406', 1, '2026-03-04', NULL, NULL, 'approved', '2026-03-04 13:22:52', 1, '2026-03-04 13:28:34', NULL, NULL, NULL, '2026-03-04 13:21:17', '2026-03-04 13:28:34'),
(54, 'PR-20260304-132130-2707', 1, '2026-03-04', NULL, NULL, 'converted_to_po', '2026-03-04 13:22:49', 1, '2026-03-04 13:23:17', NULL, NULL, NULL, '2026-03-04 13:21:30', '2026-03-04 13:23:54'),
(55, 'PR-20260304-132144-3204', 1, '2026-03-04', NULL, NULL, 'converted_to_po', '2026-03-04 13:22:46', 1, '2026-03-04 13:23:10', NULL, NULL, NULL, '2026-03-04 13:21:44', '2026-03-04 13:23:35'),
(56, 'PR-20260304-134012-1095', 1, '2026-03-04', NULL, NULL, 'approved', '2026-03-04 13:48:11', 1, '2026-03-04 14:14:31', NULL, NULL, NULL, '2026-03-04 13:40:12', '2026-03-04 14:14:31'),
(57, 'PR-20260304-134037-8827', 1, '2026-03-04', NULL, NULL, 'rejected', '2026-03-04 13:48:08', NULL, NULL, 1, '2026-03-04 14:07:45', 'ffasf', '2026-03-04 13:40:37', '2026-03-04 14:07:45'),
(58, 'PR-20260304-134051-2062', 1, '2026-03-04', NULL, NULL, 'cancelled', NULL, NULL, NULL, 1, '2026-03-04 13:48:06', 'Cancelled by user.', '2026-03-04 13:40:51', '2026-03-04 13:48:06'),
(59, 'PR-20260304-134134-8259', 1, '2026-03-04', NULL, NULL, 'converted_to_po', '2026-03-04 13:47:43', 1, '2026-03-04 14:04:33', NULL, NULL, NULL, '2026-03-04 13:41:34', '2026-03-04 15:04:48'),
(60, 'PR-20260304-134151-4666', 1, '2026-03-04', NULL, NULL, 'converted_to_po', '2026-03-04 13:42:49', 1, '2026-03-04 13:51:35', NULL, NULL, NULL, '2026-03-04 13:41:51', '2026-03-04 15:04:36'),
(61, 'PR-20260304-134219-9650', 1, '2026-03-04', NULL, NULL, 'converted_to_po', '2026-03-04 13:42:46', 1, '2026-03-04 13:50:26', NULL, NULL, NULL, '2026-03-04 13:42:19', '2026-03-04 15:01:18'),
(62, 'PR-20260304-134936-8060', 1, '2026-03-04', NULL, NULL, 'cancelled', '2026-03-04 13:49:43', NULL, NULL, 1, '2026-03-04 13:49:46', 'Cancelled by user.', '2026-03-04 13:49:36', '2026-03-04 13:49:46'),
(63, 'PR-20260304-144527-6337', 1, '2026-03-04', NULL, NULL, 'converted_to_po', '2026-03-04 14:45:31', 1, '2026-03-04 14:45:41', NULL, NULL, NULL, '2026-03-04 14:45:27', '2026-03-04 15:01:04'),
(64, 'PR-20260304-144726-8043', 1, '2026-03-04', NULL, NULL, 'converted_to_po', '2026-03-04 14:47:33', 1, '2026-03-04 14:48:13', NULL, NULL, NULL, '2026-03-04 14:47:26', '2026-03-04 15:00:46'),
(65, 'PR-20260304-145008-7354', 1, '2026-03-04', NULL, NULL, 'converted_to_po', '2026-03-04 14:50:29', 1, '2026-03-04 15:01:14', NULL, NULL, NULL, '2026-03-04 14:50:08', '2026-03-04 15:04:19'),
(66, 'PR-20260304-151532-9635', 1, '2026-03-04', NULL, NULL, 'converted_to_po', '2026-03-04 15:15:36', 1, '2026-03-04 15:15:56', NULL, NULL, NULL, '2026-03-04 15:15:32', '2026-03-04 15:16:17'),
(67, 'PR-20260304-154255-1530', 1, '2026-03-04', NULL, NULL, 'converted_to_po', '2026-03-04 15:42:59', 1, '2026-03-04 15:43:07', NULL, NULL, NULL, '2026-03-04 15:42:55', '2026-03-04 15:43:11'),
(68, 'PR-20260304-162834-5928', 1, '2026-03-04', NULL, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-04 16:28:34', '2026-03-04 16:28:34'),
(69, 'PR-20260304-163646-2497', 1, '2026-03-04', NULL, NULL, 'converted_to_po', '2026-03-04 16:37:51', 1, '2026-03-04 16:38:04', NULL, NULL, NULL, '2026-03-04 16:36:46', '2026-03-04 16:38:12'),
(70, 'PR-20260312-082133-2245', 2, '2026-03-12', NULL, NULL, 'converted_to_po', '2026-03-12 08:21:42', 1, '2026-03-12 08:22:29', NULL, NULL, NULL, '2026-03-12 08:21:33', '2026-03-12 08:26:15'),
(71, 'PR-20260312-091324-7462', 2, '2026-03-12', '2026-03-30', 'Data Flow', 'converted_to_po', '2026-03-12 09:20:56', 1, '2026-03-12 09:22:26', NULL, NULL, NULL, '2026-03-12 09:13:24', '2026-03-12 09:27:11'),
(72, 'PR-20260312-091622-3023', 2, '2026-03-12', NULL, 'Data Flow(2)', 'converted_to_po', '2026-03-12 09:21:13', 1, '2026-03-12 09:22:32', NULL, NULL, NULL, '2026-03-12 09:16:22', '2026-03-12 09:30:47'),
(73, 'PR-20260312-103945-6823', 3, '2026-03-12', NULL, NULL, 'converted_to_po', '2026-03-12 10:39:50', 3, '2026-03-12 10:40:00', NULL, NULL, NULL, '2026-03-12 10:39:45', '2026-03-12 14:38:53'),
(74, 'PR-20260312-144010-9049', 2, '2026-03-12', NULL, NULL, 'submitted', '2026-03-12 14:40:23', NULL, NULL, NULL, NULL, NULL, '2026-03-12 14:40:10', '2026-03-12 14:40:23');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_request_items`
--

CREATE TABLE `purchase_request_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_request_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `requested_qty` decimal(12,3) NOT NULL,
  `approved_qty` decimal(12,3) DEFAULT NULL,
  `unit` varchar(50) NOT NULL DEFAULT 'unit',
  `estimated_unit_cost` decimal(12,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_request_items`
--

INSERT INTO `purchase_request_items` (`id`, `purchase_request_id`, `item_name`, `requested_qty`, `approved_qty`, `unit`, `estimated_unit_cost`, `notes`, `created_at`, `updated_at`) VALUES
(6, 51, 'Amoxicillin 500mg Capsule', 55.000, NULL, 'Piece', 1000.00, 're', '2026-03-04 10:47:49', '2026-03-04 10:47:49'),
(7, 51, 'Cetirizine 10mg Tablet', 56.000, NULL, 'Box', 40000.00, 'rer', '2026-03-04 10:47:49', '2026-03-04 10:47:49'),
(8, 51, 'Clopidogrel 75mg Tablet', 56.000, NULL, 'Box', 70000.00, 'rere', '2026-03-04 10:47:49', '2026-03-04 10:47:49'),
(9, 51, 'Digoxin 0.25mg Tablet', 67.000, NULL, 'Box', 50000.00, 'erer', '2026-03-04 10:47:49', '2026-03-04 10:47:49'),
(10, 51, 'Paracetamol 250mg/5ml Syrup', 75.000, NULL, 'Bottle', 10000.00, 'erer', '2026-03-04 10:47:49', '2026-03-04 10:47:49'),
(11, 52, 'Cetirizine 10mg Tablet', 11.000, NULL, 'Capsule', 11.00, '1', '2026-03-04 13:20:59', '2026-03-04 13:20:59'),
(12, 53, 'Aspirin 81mg Tablet', 11.000, NULL, 'Capsule', 22.00, NULL, '2026-03-04 13:21:17', '2026-03-04 13:21:17'),
(13, 54, 'Amiodarone 150mg/3ml Ampule', 13.000, NULL, 'Capsule', 133.00, 're', '2026-03-04 13:21:30', '2026-03-04 13:21:30'),
(14, 55, 'Dexamethasone 4mg Tablet', 3123.000, NULL, 'Bottle', 23123.00, '2', '2026-03-04 13:21:44', '2026-03-04 13:21:44'),
(15, 56, 'Cetirizine 10mg Tablet', 11.000, NULL, 'Capsule', 11.00, '11', '2026-03-04 13:40:12', '2026-03-04 13:40:12'),
(16, 57, 'Dexamethasone 4mg Tablet', 2.000, NULL, 'Roll', 432.00, '21', '2026-03-04 13:40:37', '2026-03-04 13:40:37'),
(17, 58, 'Ceftriaxone 1g Vial', 4124.000, NULL, 'Ampoule', 213.00, '12', '2026-03-04 13:40:51', '2026-03-04 13:40:51'),
(18, 59, 'Azithromycin 500mg Tablet', 124.000, NULL, 'Capsule', 124.00, NULL, '2026-03-04 13:41:34', '2026-03-04 13:41:34'),
(19, 59, 'Azithromycin 500mg Tablet', 2412.000, NULL, 'Tablet', 214.00, NULL, '2026-03-04 13:41:34', '2026-03-04 13:41:34'),
(20, 59, 'Clopidogrel 75mg Tablet', 124.000, NULL, 'Tube', 124.00, NULL, '2026-03-04 13:41:34', '2026-03-04 13:41:34'),
(21, 60, 'Dexamethasone 4mg Tablet', 123.000, NULL, 'Tablet', 1323.00, NULL, '2026-03-04 13:41:51', '2026-03-04 13:41:51'),
(22, 60, 'Clopidogrel 75mg Tablet', 123.000, NULL, 'Pack', 214124.00, NULL, '2026-03-04 13:41:51', '2026-03-04 13:41:51'),
(23, 61, 'Digoxin 0.25mg Tablet', 12412.000, NULL, 'Piece', 14124.00, NULL, '2026-03-04 13:42:19', '2026-03-04 13:42:19'),
(24, 61, 'Ciprofloxacin 500mg Tablet', 124124.000, NULL, 'Pack', 21412414.00, NULL, '2026-03-04 13:42:19', '2026-03-04 13:42:19'),
(25, 61, 'Aspirin 81mg Tablet', 124.000, NULL, 'Tablet', 124.00, NULL, '2026-03-04 13:42:19', '2026-03-04 13:42:19'),
(26, 61, 'Ceftriaxone 1g Vial', 124124.000, NULL, 'Capsule', 1241241.00, NULL, '2026-03-04 13:42:19', '2026-03-04 13:42:19'),
(27, 62, '0.9% Sodium Chloride IV 1L', 2.000, NULL, 'Vial', 22.00, NULL, '2026-03-04 13:49:36', '2026-03-04 13:49:36'),
(28, 63, 'Amlodipine 5mg Tablet', 32.000, NULL, 'Box', 2323.00, NULL, '2026-03-04 14:45:27', '2026-03-04 14:45:27'),
(29, 64, 'Clopidogrel 75mg Tablet', 11.000, NULL, 'Capsule', 22.00, NULL, '2026-03-04 14:47:26', '2026-03-04 14:47:26'),
(30, 65, '0.9% Sodium Chloride IV 1L', 2.000, NULL, 'Pack', 2.00, NULL, '2026-03-04 14:50:08', '2026-03-04 14:50:08'),
(31, 66, '0.9% Sodium Chloride IV 1L', 2.000, NULL, 'Bottle', 213.00, NULL, '2026-03-04 15:15:32', '2026-03-04 15:15:32'),
(32, 67, 'Aspirin 81mg Tablet', 100.000, NULL, 'Box', 100.00, NULL, '2026-03-04 15:42:55', '2026-03-04 15:42:55'),
(33, 68, 'Paracetamol 500mg', 10.000, NULL, 'Box', 150.00, 'Urgent stock refuel', '2026-03-04 16:28:34', '2026-03-04 16:28:34'),
(34, 68, 'Ibuprofen 200mg', 5.000, NULL, 'Pack', 120.50, 'For pediatric ward', '2026-03-04 16:28:34', '2026-03-04 16:28:34'),
(35, 68, 'Surgical Masks', 50.000, NULL, 'Box', 55.00, NULL, '2026-03-04 16:28:34', '2026-03-04 16:28:34'),
(36, 68, 'Latex Gloves (Medium)', 2.000, NULL, 'Piece', 5000.00, 'Imported Unit Mismatch: Pallet | Check quality', '2026-03-04 16:28:34', '2026-03-04 16:28:34'),
(37, 68, 'Syringe 5ml', 100.000, NULL, 'Piece', NULL, NULL, '2026-03-04 16:28:34', '2026-03-04 16:28:34'),
(38, 69, 'Paracetamol 500mg', 20.000, NULL, 'Box', 150.00, 'Monthly restock', '2026-03-04 16:36:46', '2026-03-04 16:36:46'),
(39, 69, 'Amoxicillin 250mg', 100.000, NULL, 'Capsule', 5.50, 'Urgent for Ward A', '2026-03-04 16:36:46', '2026-03-04 16:36:46'),
(40, 69, 'Surgical Masks', 50.000, NULL, 'Box', 55.00, NULL, '2026-03-04 16:36:46', '2026-03-04 16:36:46'),
(41, 69, 'Latex Gloves (Medium)', 30.000, NULL, 'Pack', 250.00, 'For ER', '2026-03-04 16:36:46', '2026-03-04 16:36:46'),
(42, 69, 'Syringe 5ml', 200.000, NULL, 'Piece', 12.00, NULL, '2026-03-04 16:36:46', '2026-03-04 16:36:46'),
(43, 69, 'Ibuprofen 400mg', 500.000, NULL, 'Tablet', 8.00, 'Pediatric use', '2026-03-04 16:36:46', '2026-03-04 16:36:46'),
(44, 69, 'Gauze Pad 4x4', 20.000, NULL, 'Pack', 45.00, 'Wound care', '2026-03-04 16:36:46', '2026-03-04 16:36:46'),
(45, 69, 'Cotton Balls', 15.000, NULL, 'Roll', 80.00, NULL, '2026-03-04 16:36:46', '2026-03-04 16:36:46'),
(46, 69, 'Povidone Iodine 10%', 10.000, NULL, 'Bottle', 120.00, NULL, '2026-03-04 16:36:46', '2026-03-04 16:36:46'),
(47, 69, 'Digital Thermometer', 5.000, NULL, 'Piece', 150.00, 'Imported Unit Mismatch: Unit | Check calibration', '2026-03-04 16:36:46', '2026-03-04 16:36:46'),
(48, 69, 'Lidocaine Ointment', 12.000, NULL, 'Tube', 350.00, NULL, '2026-03-04 16:36:46', '2026-03-04 16:36:46'),
(49, 69, 'Saline Solution 1L', 50.000, NULL, 'Piece', 90.00, 'Imported Unit Mismatch: Bag | IV fluids', '2026-03-04 16:36:46', '2026-03-04 16:36:46'),
(50, 69, 'Epinephrine 10mg', 25.000, NULL, 'Vial', 800.00, 'High priority', '2026-03-04 16:36:46', '2026-03-04 16:36:46'),
(51, 69, 'IV Cannula G20', 100.000, NULL, 'Piece', NULL, NULL, '2026-03-04 16:36:46', '2026-03-04 16:36:46'),
(52, 69, 'First Aid Kit', 3.000, NULL, 'Set', 2500.00, 'For ambulances', '2026-03-04 16:36:46', '2026-03-04 16:36:46'),
(53, 70, 'Amoxicillin 500mg Capsule', 10.000, NULL, 'Box', 500.00, 'Need for Pharmacy 1', '2026-03-12 08:21:33', '2026-03-12 08:21:33'),
(54, 71, 'Kisspirin 100mg', 69.000, NULL, 'Box', 100.00, 'Data Flow 1', '2026-03-12 09:13:24', '2026-03-12 09:13:24'),
(55, 71, 'Yakapsul 500mg', 67.000, NULL, 'Box', 200.00, 'Data Flow 2', '2026-03-12 09:13:24', '2026-03-12 09:13:24'),
(56, 72, 'Kisspirin 100mg', 50.000, NULL, 'Box', 100.00, 'Data Flow 3', '2026-03-12 09:16:22', '2026-03-12 09:16:22'),
(57, 72, 'Yakapsul 500mg', 50.000, NULL, 'Box', 100.00, 'Data Flow 4', '2026-03-12 09:16:22', '2026-03-12 09:16:22'),
(58, 73, 'Cotton Balls', 1.000, NULL, 'Vial', 1.00, '1', '2026-03-12 10:39:45', '2026-03-12 10:39:45'),
(59, 74, 'Amlodipine 5mg Tablet', 10.000, NULL, 'Bottle', 100.00, NULL, '2026-03-12 14:40:10', '2026-03-12 14:40:10');

-- --------------------------------------------------------

--
-- Table structure for table `receivings`
--

CREATE TABLE `receivings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receiving_number` varchar(50) NOT NULL,
  `po_request_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `received_date` date NOT NULL,
  `delivery_reference` varchar(100) DEFAULT NULL,
  `received_by` bigint(20) UNSIGNED NOT NULL,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'draft',
  `remarks` text DEFAULT NULL,
  `posted_at` datetime DEFAULT NULL,
  `voided_at` datetime DEFAULT NULL,
  `voided_by` bigint(20) UNSIGNED DEFAULT NULL,
  `void_reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receivings`
--

INSERT INTO `receivings` (`id`, `receiving_number`, `po_request_id`, `purchase_order_id`, `supplier_name`, `received_date`, `delivery_reference`, `received_by`, `verified_by`, `status`, `remarks`, `posted_at`, `voided_at`, `voided_by`, `void_reason`, `created_at`, `updated_at`) VALUES
(1, 'RCV-PH-2026-001', 1, 1, 'MediSupply Plus', '2026-03-05', 'DR-PH-1001', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(2, 'RCV-PH-2026-002', 2, 2, 'PharmaDist Corp', '2026-03-06', 'DR-PH-1002', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(3, 'RCV-PH-2026-003', 3, 4, 'HealthLink Distributors', '2026-03-12', 'DR-PH-1003', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(4, 'RCV-PH-2026-004', 4, 5, 'MediSupply Plus', '2026-03-13', 'DR-PH-1004', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(5, 'RCV-PH-2026-005', 5, 7, 'PharmaDist Corp', '2026-03-15', 'DR-PH-1005', 2, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(6, 'RCV-PH-2026-006', 6, 9, 'HealthLink Distributors', '2026-03-16', 'DR-PH-1006', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(7, 'RCV-PH-2026-007', 7, 10, 'MediSupply Plus', '2026-03-18', 'DR-PH-1007', 2, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(8, 'RCV-PH-2026-008', 8, 1, 'PharmaDist Corp', '2026-03-19', 'DR-PH-1008', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(9, 'RCV-PH-2026-009', 9, 2, 'HealthLink Distributors', '2026-03-20', 'DR-PH-1009', 2, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(10, 'RCV-PH-2026-010', 10, 4, 'MediSupply Plus', '2026-03-21', 'DR-PH-1010', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:32:36', NULL),
(11, 'RCV-PH-2026-011', 11, 11, 'MediSupply Plus', '2026-03-15', 'DR-PH-1011', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(12, 'RCV-PH-2026-012', 12, 12, 'CareFirst Meds', '2026-03-16', 'DR-PH-1012', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(13, 'RCV-PH-2026-013', 13, 14, 'PharmaDist Corp', '2026-03-22', 'DR-PH-1013', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(14, 'RCV-PH-2026-014', 14, 15, 'CareFirst Meds', '2026-03-17', 'DR-PH-1014', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(15, 'RCV-PH-2026-015', 15, 17, 'MediSupply Plus', '2026-03-20', 'DR-PH-1015', 2, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(16, 'RCV-PH-2026-016', 16, 19, 'CareFirst Meds', '2026-03-18', 'DR-PH-1016', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(17, 'RCV-PH-2026-017', 17, 20, 'MediSupply Plus', '2026-03-19', 'DR-PH-1017', 2, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(18, 'RCV-PH-2026-018', 18, 11, 'MediSupply Plus', '2026-03-20', 'DR-PH-1018', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(19, 'RCV-PH-2026-019', 19, 12, 'CareFirst Meds', '2026-03-21', 'DR-PH-1019', 2, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(20, 'RCV-PH-2026-020', 20, 14, 'PharmaDist Corp', '2026-03-25', 'DR-PH-1020', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:34:03', NULL),
(21, 'RCV-PH-2026-021', 21, 21, 'MediSupply Plus', '2026-03-25', 'DR-PH-1021', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(22, 'RCV-PH-2026-022', 22, 22, 'CareFirst Meds', '2026-03-26', 'DR-PH-1022', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(23, 'RCV-PH-2026-023', 23, 24, 'PharmaDist Corp', '2026-04-01', 'DR-PH-1023', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(24, 'RCV-PH-2026-024', 24, 25, 'CareFirst Meds', '2026-03-27', 'DR-PH-1024', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(25, 'RCV-PH-2026-025', 25, 27, 'MediSupply Plus', '2026-03-30', 'DR-PH-1025', 2, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(26, 'RCV-PH-2026-026', 26, 29, 'CareFirst Meds', '2026-03-28', 'DR-PH-1026', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(27, 'RCV-PH-2026-027', 27, 30, 'MediSupply Plus', '2026-03-29', 'DR-PH-1027', 2, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(28, 'RCV-PH-2026-028', 28, 21, 'MediSupply Plus', '2026-03-30', 'DR-PH-1028', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(29, 'RCV-PH-2026-029', 29, 22, 'CareFirst Meds', '2026-03-31', 'DR-PH-1029', 2, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(30, 'RCV-PH-2026-030', 30, 24, 'PharmaDist Corp', '2026-04-03', 'DR-PH-1030', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:35:47', NULL),
(31, 'RCV-PH-2026-031', 31, 31, 'MediSupply Plus', '2026-04-05', 'DR-PH-1031', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(32, 'RCV-PH-2026-032', 32, 32, 'PharmaDist Corp', '2026-04-06', 'DR-PH-1032', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(33, 'RCV-PH-2026-033', 33, 34, 'CareFirst Meds', '2026-04-12', 'DR-PH-1033', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(34, 'RCV-PH-2026-034', 34, 35, 'PharmaDist Corp', '2026-04-07', 'DR-PH-1034', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(35, 'RCV-PH-2026-035', 35, 37, 'MediSupply Plus', '2026-04-10', 'DR-PH-1035', 2, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(36, 'RCV-PH-2026-036', 36, 39, 'HealthLink Distributors', '2026-04-08', 'DR-PH-1036', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(37, 'RCV-PH-2026-037', 37, 40, 'MediSupply Plus', '2026-04-09', 'DR-PH-1037', 2, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(38, 'RCV-PH-2026-038', 38, 31, 'MediSupply Plus', '2026-04-10', 'DR-PH-1038', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(39, 'RCV-PH-2026-039', 39, 32, 'PharmaDist Corp', '2026-04-11', 'DR-PH-1039', 2, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(40, 'RCV-PH-2026-040', 40, 34, 'CareFirst Meds', '2026-04-15', 'DR-PH-1040', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:38:14', NULL),
(41, 'RCV-PH-2026-041', 41, 41, 'MediSupply Plus', '2026-04-15', 'DR-PH-1041', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(42, 'RCV-PH-2026-042', 42, 42, 'CareFirst Meds', '2026-04-16', 'DR-PH-1042', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(43, 'RCV-PH-2026-043', 43, 44, 'PharmaDist Corp', '2026-04-22', 'DR-PH-1043', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(44, 'RCV-PH-2026-044', 44, 45, 'CareFirst Meds', '2026-04-17', 'DR-PH-1044', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(45, 'RCV-PH-2026-045', 45, 47, 'MediSupply Plus', '2026-04-20', 'DR-PH-1045', 2, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(46, 'RCV-PH-2026-046', 46, 49, 'CareFirst Meds', '2026-04-18', 'DR-PH-1046', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(47, 'RCV-PH-2026-047', 47, 50, 'MediSupply Plus', '2026-04-19', 'DR-PH-1047', 2, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(48, 'RCV-PH-2026-048', 48, 41, 'MediSupply Plus', '2026-04-20', 'DR-PH-1048', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(49, 'RCV-PH-2026-049', 49, 42, 'CareFirst Meds', '2026-04-21', 'DR-PH-1049', 2, NULL, 'draft', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(50, 'RCV-PH-2026-050', 50, 44, 'PharmaDist Corp', '2026-04-25', 'DR-PH-1050', 2, NULL, 'posted', NULL, NULL, NULL, NULL, NULL, '2026-03-04 10:39:53', NULL),
(51, 'RCV-20260304-150155-2572', 58, 52, NULL, '2026-03-04', NULL, 1, 1, 'posted', NULL, '2026-03-04 15:02:15', NULL, NULL, NULL, '2026-03-04 15:01:55', '2026-03-04 15:02:15'),
(52, 'RCV-20260304-150244-7783', 51, 51, 'MediSupply Plus', '2026-03-04', NULL, 1, 1, 'posted', NULL, '2026-03-04 15:02:53', NULL, NULL, NULL, '2026-03-04 15:02:44', '2026-03-04 15:02:53'),
(53, 'RCV-20260304-151647-9287', 60, 60, NULL, '2026-03-04', NULL, 1, 1, 'posted', NULL, '2026-03-04 15:16:50', NULL, NULL, NULL, '2026-03-04 15:16:47', '2026-03-04 15:16:50'),
(54, 'RCV-20260304-154338-6548', 61, 61, NULL, '2026-03-04', NULL, 1, 1, 'posted', NULL, '2026-03-04 15:43:42', NULL, NULL, NULL, '2026-03-04 15:43:38', '2026-03-04 15:43:42'),
(55, 'RCV-20260304-163854-9675', 62, 62, NULL, '2026-03-04', NULL, 1, 1, 'posted', NULL, '2026-03-04 16:38:59', NULL, NULL, NULL, '2026-03-04 16:38:54', '2026-03-04 16:38:59'),
(56, 'RCV-20260304-170200-9744', 59, 59, NULL, '2026-03-04', NULL, 1, 1, 'posted', NULL, '2026-03-04 17:02:10', NULL, NULL, NULL, '2026-03-04 17:02:00', '2026-03-04 17:02:10'),
(57, 'RCV-20260312-084058-6047', 64, 63, 'MediSupply Plus', '2026-03-12', NULL, 1, 1, 'posted', NULL, '2026-03-12 08:41:21', NULL, NULL, NULL, '2026-03-12 08:40:58', '2026-03-12 08:41:21'),
(58, 'RCV-20260312-093357-2786', 65, 64, 'MediSupply Minus', '2026-03-12', 'DR-1234', 1, 1, 'posted', 'Data Flow', '2026-03-12 09:34:49', NULL, NULL, NULL, '2026-03-12 09:33:57', '2026-03-12 09:34:49'),
(59, 'RCV-20260312-093624-2615', 66, 65, 'MediSupply Plus', '2026-03-12', 'DR-1235', 1, 1, 'posted', 'Data Flow(2)', '2026-03-12 09:36:36', NULL, NULL, NULL, '2026-03-12 09:36:24', '2026-03-12 09:36:36');

-- --------------------------------------------------------

--
-- Table structure for table `receiving_items`
--

CREATE TABLE `receiving_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receiving_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_item_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `unit` varchar(50) NOT NULL DEFAULT 'unit',
  `received_qty` decimal(12,3) NOT NULL,
  `accepted_qty` decimal(12,3) NOT NULL,
  `rejected_qty` decimal(12,3) NOT NULL DEFAULT 0.000,
  `batch_no` varchar(100) DEFAULT NULL,
  `lot_no` varchar(100) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `unit_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receiving_items`
--

INSERT INTO `receiving_items` (`id`, `receiving_id`, `purchase_order_item_id`, `item_name`, `unit`, `received_qty`, `accepted_qty`, `rejected_qty`, `batch_no`, `lot_no`, `expiry_date`, `unit_cost`, `line_total`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 51, 6, 'Dexamethasone 4mg Tablet', 'Bottle', 3123.000, 3123.000, 0.000, NULL, NULL, NULL, 23123.00, 72213129.00, NULL, '2026-03-04 15:01:55', '2026-03-04 15:02:15'),
(2, 52, 1, 'Amoxicillin 500mg Capsule', 'Piece', 55.000, 55.000, 0.000, NULL, NULL, NULL, 1000.00, 55000.00, NULL, '2026-03-04 15:02:44', '2026-03-04 15:02:53'),
(3, 52, 2, 'Cetirizine 10mg Tablet', 'Box', 56.000, 56.000, 0.000, NULL, NULL, NULL, 40000.00, 2240000.00, NULL, '2026-03-04 15:02:44', '2026-03-04 15:02:53'),
(4, 52, 3, 'Clopidogrel 75mg Tablet', 'Box', 56.000, 56.000, 0.000, NULL, NULL, NULL, 70000.00, 3920000.00, NULL, '2026-03-04 15:02:44', '2026-03-04 15:02:53'),
(5, 52, 4, 'Digoxin 0.25mg Tablet', 'Box', 67.000, 67.000, 0.000, NULL, NULL, NULL, 50000.00, 3350000.00, NULL, '2026-03-04 15:02:44', '2026-03-04 15:02:53'),
(6, 52, 5, 'Paracetamol 250mg/5ml Syrup', 'Bottle', 75.000, 75.000, 0.000, NULL, NULL, NULL, 10000.00, 750000.00, NULL, '2026-03-04 15:02:44', '2026-03-04 15:02:53'),
(7, 53, 20, '0.9% Sodium Chloride IV 1L', 'Bottle', 2.000, 2.000, 0.000, NULL, NULL, NULL, 213.00, 426.00, NULL, '2026-03-04 15:16:47', '2026-03-04 15:16:50'),
(8, 54, 21, 'Aspirin 81mg Tablet', 'Box', 100.000, 100.000, 0.000, NULL, NULL, NULL, 100.00, 10000.00, NULL, '2026-03-04 15:43:38', '2026-03-04 15:43:42'),
(9, 55, 22, 'Paracetamol 500mg', 'Box', 20.000, 20.000, 0.000, NULL, NULL, NULL, 150.00, 3000.00, NULL, '2026-03-04 16:38:54', '2026-03-04 16:38:59'),
(10, 55, 23, 'Amoxicillin 250mg', 'Capsule', 100.000, 100.000, 0.000, NULL, NULL, NULL, 5.50, 550.00, NULL, '2026-03-04 16:38:54', '2026-03-04 16:38:59'),
(11, 55, 24, 'Surgical Masks', 'Box', 50.000, 50.000, 0.000, NULL, NULL, NULL, 55.00, 2750.00, NULL, '2026-03-04 16:38:54', '2026-03-04 16:38:59'),
(12, 55, 25, 'Latex Gloves (Medium)', 'Pack', 30.000, 30.000, 0.000, NULL, NULL, NULL, 250.00, 7500.00, NULL, '2026-03-04 16:38:54', '2026-03-04 16:38:59'),
(13, 55, 26, 'Syringe 5ml', 'Piece', 200.000, 200.000, 0.000, NULL, NULL, NULL, 12.00, 2400.00, NULL, '2026-03-04 16:38:54', '2026-03-04 16:38:59'),
(14, 55, 27, 'Ibuprofen 400mg', 'Tablet', 500.000, 500.000, 0.000, NULL, NULL, NULL, 8.00, 4000.00, NULL, '2026-03-04 16:38:54', '2026-03-04 16:38:59'),
(15, 55, 28, 'Gauze Pad 4x4', 'Pack', 20.000, 20.000, 0.000, NULL, NULL, NULL, 45.00, 900.00, NULL, '2026-03-04 16:38:54', '2026-03-04 16:38:59'),
(16, 55, 29, 'Cotton Balls', 'Roll', 15.000, 15.000, 0.000, NULL, NULL, NULL, 80.00, 1200.00, NULL, '2026-03-04 16:38:54', '2026-03-04 16:38:59'),
(17, 55, 30, 'Povidone Iodine 10%', 'Bottle', 10.000, 10.000, 0.000, NULL, NULL, NULL, 120.00, 1200.00, NULL, '2026-03-04 16:38:54', '2026-03-04 16:38:59'),
(18, 55, 31, 'Digital Thermometer', 'Piece', 5.000, 5.000, 0.000, NULL, NULL, NULL, 150.00, 750.00, NULL, '2026-03-04 16:38:54', '2026-03-04 16:38:59'),
(19, 55, 32, 'Lidocaine Ointment', 'Tube', 12.000, 12.000, 0.000, NULL, NULL, NULL, 350.00, 4200.00, NULL, '2026-03-04 16:38:54', '2026-03-04 16:38:59'),
(20, 55, 33, 'Saline Solution 1L', 'Piece', 50.000, 50.000, 0.000, NULL, NULL, NULL, 90.00, 4500.00, NULL, '2026-03-04 16:38:54', '2026-03-04 16:38:59'),
(21, 55, 34, 'Epinephrine 10mg', 'Vial', 25.000, 25.000, 0.000, NULL, NULL, NULL, 800.00, 20000.00, NULL, '2026-03-04 16:38:54', '2026-03-04 16:38:59'),
(22, 55, 35, 'IV Cannula G20', 'Piece', 100.000, 100.000, 0.000, NULL, NULL, NULL, 0.00, 0.00, NULL, '2026-03-04 16:38:54', '2026-03-04 16:38:59'),
(23, 55, 36, 'First Aid Kit', 'Set', 3.000, 3.000, 0.000, NULL, NULL, NULL, 2500.00, 7500.00, NULL, '2026-03-04 16:38:54', '2026-03-04 16:38:59'),
(24, 56, 17, 'Azithromycin 500mg Tablet', 'Capsule', 124.000, 113.000, 11.000, 'BATCH-69', 'LOT-109', '2026-03-04', 124.00, 14012.00, NULL, '2026-03-04 17:02:00', '2026-03-04 17:02:10'),
(25, 56, 18, 'Azithromycin 500mg Tablet', 'Tablet', 2412.000, 2400.000, 12.000, 'BATCH-69', 'LOT-109', '2026-03-04', 214.00, 513600.00, NULL, '2026-03-04 17:02:00', '2026-03-04 17:02:10'),
(26, 56, 19, 'Clopidogrel 75mg Tablet', 'Tube', 124.000, 112.000, 12.000, 'BATCH-69', 'LOT-109', '2026-03-04', 124.00, 13888.00, NULL, '2026-03-04 17:02:00', '2026-03-04 17:02:10'),
(27, 57, 37, 'Amoxicillin 500mg Capsule', 'Box', 10.000, 9.000, 1.000, 'BATCH-67', 'LOT-169', '2030-07-11', 500.00, 4500.00, 'Needed at Pharmacy 1', '2026-03-12 08:40:58', '2026-03-12 08:41:21'),
(28, 58, 38, 'Kisspirin 100mg', 'Box', 69.000, 69.000, 0.000, 'BATCH-67', 'LOT-109', '2027-01-01', 100.00, 6900.00, 'Flow 1', '2026-03-12 09:33:57', '2026-03-12 09:34:49'),
(29, 58, 39, 'Yakapsul 500mg', 'Box', 67.000, 67.000, 0.000, 'BATCH-67', 'LOT-109', '2027-01-01', 200.00, 13400.00, 'Flow 2', '2026-03-12 09:33:57', '2026-03-12 09:34:49'),
(30, 59, 40, 'Kisspirin 100mg', 'Box', 50.000, 50.000, 0.000, 'BATCH-69', 'LOT-169', '2035-01-01', 100.00, 5000.00, 'Flow 2', '2026-03-12 09:36:24', '2026-03-12 09:36:36'),
(31, 59, 41, 'Yakapsul 500mg', 'Box', 50.000, 50.000, 0.000, 'BATCH-69', 'LOT-169', '2035-01-12', 100.00, 5000.00, 'Flow 2', '2026-03-12 09:36:24', '2026-03-12 09:36:36');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(9) NOT NULL,
  `class` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(31) NOT NULL DEFAULT 'string',
  `context` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `movement_number` varchar(60) NOT NULL,
  `movement_type` varchar(30) NOT NULL DEFAULT 'receiving',
  `reference_type` varchar(30) NOT NULL,
  `reference_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `inventory_stock_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit` varchar(50) NOT NULL DEFAULT 'unit',
  `qty_in` decimal(12,3) NOT NULL DEFAULT 0.000,
  `qty_out` decimal(12,3) NOT NULL DEFAULT 0.000,
  `balance_after` decimal(12,3) NOT NULL DEFAULT 0.000,
  `unit_cost` decimal(12,2) DEFAULT NULL,
  `performed_by` bigint(20) UNSIGNED NOT NULL,
  `performed_at` datetime NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `movement_number`, `movement_type`, `reference_type`, `reference_id`, `item_name`, `inventory_stock_id`, `unit`, `qty_in`, `qty_out`, `balance_after`, `unit_cost`, `performed_by`, `performed_at`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'MOV-PH-2026-001', 'receiving', 'receiving', 1, 'Amoxicillin 500mg Capsule', 1, 'box', 50.000, 0.000, 50.000, NULL, 2, '2026-03-04 10:32:36', NULL, NULL, NULL),
(2, 'MOV-PH-2026-002', 'issuance', 'issuance', 1, 'Amoxicillin 500mg Capsule', 1, 'box', 0.000, 5.000, 45.000, NULL, 3, '2026-03-04 10:32:36', NULL, NULL, NULL),
(3, 'MOV-PH-2026-003', 'receiving', 'receiving', 2, 'Paracetamol 500mg Tablet', 2, 'box', 100.000, 0.000, 100.000, NULL, 2, '2026-03-04 10:32:36', NULL, NULL, NULL),
(4, 'MOV-PH-2026-004', 'issuance', 'issuance', 2, 'Paracetamol 500mg Tablet', 2, 'box', 0.000, 20.000, 80.000, NULL, 2, '2026-03-04 10:32:36', NULL, NULL, NULL),
(5, 'MOV-PH-2026-005', 'receiving', 'receiving', 3, 'Ibuprofen 400mg Tablet', 3, 'box', 60.000, 0.000, 60.000, NULL, 2, '2026-03-04 10:32:36', NULL, NULL, NULL),
(6, 'MOV-PH-2026-006', 'issuance', 'issuance', 5, 'Ibuprofen 400mg Tablet', 3, 'box', 0.000, 10.000, 50.000, NULL, 3, '2026-03-04 10:32:36', NULL, NULL, NULL),
(7, 'MOV-PH-2026-007', 'receiving', 'receiving', 4, '0.9% Sodium Chloride IV 1L', 10, 'bottle', 40.000, 0.000, 40.000, NULL, 2, '2026-03-04 10:32:36', NULL, NULL, NULL),
(8, 'MOV-PH-2026-008', 'issuance', 'issuance', 7, '0.9% Sodium Chloride IV 1L', 10, 'bottle', 0.000, 15.000, 25.000, NULL, 3, '2026-03-04 10:32:36', NULL, NULL, NULL),
(9, 'MOV-PH-2026-009', 'receiving', 'receiving', 6, 'Salbutamol 100mcg Inhaler', 8, 'piece', 30.000, 0.000, 30.000, NULL, 2, '2026-03-04 10:32:36', NULL, NULL, NULL),
(10, 'MOV-PH-2026-010', 'issuance', 'issuance', 10, 'Salbutamol 100mcg Inhaler', 8, 'piece', 0.000, 5.000, 25.000, NULL, 2, '2026-03-04 10:32:36', NULL, NULL, NULL),
(11, 'MOV-PH-2026-011', 'receiving', 'receiving', 11, 'Ceftriaxone 1g Vial', 11, 'vial', 100.000, 0.000, 100.000, NULL, 2, '2026-03-04 10:34:03', NULL, NULL, NULL),
(12, 'MOV-PH-2026-012', 'issuance', 'issuance', 11, 'Ceftriaxone 1g Vial', 11, 'vial', 0.000, 15.000, 85.000, NULL, 3, '2026-03-04 10:34:03', NULL, NULL, NULL),
(13, 'MOV-PH-2026-013', 'receiving', 'receiving', 12, 'Amlodipine 5mg Tablet', 14, 'box', 150.000, 0.000, 150.000, NULL, 2, '2026-03-04 10:34:03', NULL, NULL, NULL),
(14, 'MOV-PH-2026-014', 'issuance', 'issuance', 12, 'Amlodipine 5mg Tablet', 14, 'box', 0.000, 30.000, 120.000, NULL, 2, '2026-03-04 10:34:03', NULL, NULL, NULL),
(15, 'MOV-PH-2026-015', 'receiving', 'receiving', 13, 'Medical Oxygen Cylinder (50L)', 20, 'tank', 10.000, 0.000, 10.000, NULL, 2, '2026-03-04 10:34:03', NULL, NULL, NULL),
(16, 'MOV-PH-2026-016', 'issuance', 'issuance', 17, 'Medical Oxygen Cylinder (50L)', 20, 'tank', 0.000, 2.000, 8.000, NULL, 3, '2026-03-04 10:34:03', NULL, NULL, NULL),
(17, 'MOV-PH-2026-017', 'receiving', 'receiving', 14, 'Pantoprazole 40mg Tablet', 19, 'box', 50.000, 0.000, 50.000, NULL, 2, '2026-03-04 10:34:03', NULL, NULL, NULL),
(18, 'MOV-PH-2026-018', 'issuance', 'issuance', 18, 'Pantoprazole 40mg Tablet', 19, 'box', 0.000, 5.000, 45.000, NULL, 3, '2026-03-04 10:34:03', NULL, NULL, NULL),
(19, 'MOV-PH-2026-019', 'receiving', 'receiving', 16, 'Tramadol 50mg Capsule', 18, 'box', 30.000, 0.000, 30.000, NULL, 2, '2026-03-04 10:34:03', NULL, NULL, NULL),
(20, 'MOV-PH-2026-020', 'issuance', 'issuance', 20, 'Furosemide 40mg Tablet', 15, 'box', 0.000, 10.000, 20.000, NULL, 2, '2026-03-04 10:34:03', NULL, NULL, NULL),
(21, 'MOV-PH-2026-021', 'receiving', 'receiving', 21, 'Diazepam 5mg Tablet', 21, 'box', 20.000, 0.000, 20.000, NULL, 2, '2026-03-04 10:35:47', NULL, NULL, NULL),
(22, 'MOV-PH-2026-022', 'issuance', 'issuance', 21, 'Diazepam 5mg Tablet', 21, 'box', 0.000, 5.000, 15.000, NULL, 3, '2026-03-04 10:35:47', NULL, NULL, NULL),
(23, 'MOV-PH-2026-023', 'receiving', 'receiving', 22, 'Insulin Glargine 100iu/ml Pen', 23, 'piece', 50.000, 0.000, 50.000, NULL, 2, '2026-03-04 10:35:47', NULL, NULL, NULL),
(24, 'MOV-PH-2026-024', 'issuance', 'issuance', 22, 'Insulin Glargine 100iu/ml Pen', 23, 'piece', 0.000, 10.000, 40.000, NULL, 2, '2026-03-04 10:35:47', NULL, NULL, NULL),
(25, 'MOV-PH-2026-025', 'receiving', 'receiving', 23, 'Hydrocortisone 1% Cream 10g', 24, 'tube', 100.000, 0.000, 100.000, NULL, 2, '2026-03-04 10:35:47', NULL, NULL, NULL),
(26, 'MOV-PH-2026-026', 'issuance', 'issuance', 27, 'Hydrocortisone 1% Cream 10g', 24, 'tube', 0.000, 15.000, 85.000, NULL, 3, '2026-03-04 10:35:47', NULL, NULL, NULL),
(27, 'MOV-PH-2026-027', 'receiving', 'receiving', 24, 'Ondansetron 4mg Tablet', 26, 'box', 40.000, 0.000, 40.000, NULL, 2, '2026-03-04 10:35:47', NULL, NULL, NULL),
(28, 'MOV-PH-2026-028', 'issuance', 'issuance', 28, 'Ondansetron 4mg Tablet', 26, 'box', 0.000, 5.000, 35.000, NULL, 3, '2026-03-04 10:35:47', NULL, NULL, NULL),
(29, 'MOV-PH-2026-029', 'receiving', 'receiving', 26, 'Clopidogrel 75mg Tablet', 27, 'box', 60.000, 0.000, 60.000, NULL, 2, '2026-03-04 10:35:47', NULL, NULL, NULL),
(30, 'MOV-PH-2026-030', 'issuance', 'issuance', 30, 'Atorvastatin 40mg Tablet', 28, 'box', 0.000, 20.000, 40.000, NULL, 2, '2026-03-04 10:35:47', NULL, NULL, NULL),
(31, 'MOV-PH-2026-031', 'receiving', 'receiving', 31, 'Epinephrine 1mg/ml Ampule', 31, 'ampule', 100.000, 0.000, 100.000, NULL, 2, '2026-03-04 10:38:14', NULL, NULL, NULL),
(32, 'MOV-PH-2026-032', 'issuance', 'issuance', 31, 'Epinephrine 1mg/ml Ampule', 31, 'ampule', 0.000, 10.000, 90.000, NULL, 3, '2026-03-04 10:38:14', NULL, NULL, NULL),
(33, 'MOV-PH-2026-033', 'receiving', 'receiving', 32, 'Naloxone 0.4mg/ml Vial', 32, 'vial', 50.000, 0.000, 50.000, NULL, 2, '2026-03-04 10:38:14', NULL, NULL, NULL),
(34, 'MOV-PH-2026-034', 'issuance', 'issuance', 32, 'Naloxone 0.4mg/ml Vial', 32, 'vial', 0.000, 5.000, 45.000, NULL, 2, '2026-03-04 10:38:14', NULL, NULL, NULL),
(35, 'MOV-PH-2026-035', 'receiving', 'receiving', 33, 'Atropine Sulfate 1mg/ml Ampule', 33, 'ampule', 120.000, 0.000, 120.000, NULL, 2, '2026-03-04 10:38:14', NULL, NULL, NULL),
(36, 'MOV-PH-2026-036', 'issuance', 'issuance', 37, 'Atropine Sulfate 1mg/ml Ampule', 33, 'ampule', 0.000, 15.000, 105.000, NULL, 3, '2026-03-04 10:38:14', NULL, NULL, NULL),
(37, 'MOV-PH-2026-037', 'receiving', 'receiving', 34, 'Amiodarone 150mg/3ml Ampule', 34, 'ampule', 80.000, 0.000, 80.000, NULL, 2, '2026-03-04 10:38:14', NULL, NULL, NULL),
(38, 'MOV-PH-2026-038', 'issuance', 'issuance', 38, 'Amiodarone 150mg/3ml Ampule', 34, 'ampule', 0.000, 8.000, 72.000, NULL, 3, '2026-03-04 10:38:14', NULL, NULL, NULL),
(39, 'MOV-PH-2026-039', 'receiving', 'receiving', 36, 'Norepinephrine 4mg/4ml Ampule', 39, 'ampule', 110.000, 0.000, 110.000, NULL, 2, '2026-03-04 10:38:14', NULL, NULL, NULL),
(40, 'MOV-PH-2026-040', 'issuance', 'issuance', 40, 'Norepinephrine 4mg/4ml Ampule', 39, 'ampule', 0.000, 15.000, 95.000, NULL, 2, '2026-03-04 10:38:14', NULL, NULL, NULL),
(41, 'MOV-PH-2026-041', 'receiving', 'receiving', 41, 'Cefalexin 500mg Capsule', 41, 'box', 100.000, 0.000, 100.000, NULL, 2, '2026-03-04 10:39:53', NULL, NULL, NULL),
(42, 'MOV-PH-2026-042', 'issuance', 'issuance', 41, 'Cefalexin 500mg Capsule', 41, 'box', 0.000, 20.000, 80.000, NULL, 3, '2026-03-04 10:39:53', NULL, NULL, NULL),
(43, 'MOV-PH-2026-043', 'receiving', 'receiving', 42, 'Ibuprofen 100mg/5ml Suspension', 42, 'bottle', 50.000, 0.000, 50.000, NULL, 2, '2026-03-04 10:39:53', NULL, NULL, NULL),
(44, 'MOV-PH-2026-044', 'issuance', 'issuance', 42, 'Ibuprofen 100mg/5ml Suspension', 42, 'bottle', 0.000, 10.000, 40.000, NULL, 2, '2026-03-04 10:39:53', NULL, NULL, NULL),
(45, 'MOV-PH-2026-045', 'receiving', 'receiving', 43, 'Propofol 10mg/ml 50ml Vial', 46, 'vial', 40.000, 0.000, 40.000, NULL, 2, '2026-03-04 10:39:53', NULL, NULL, NULL),
(46, 'MOV-PH-2026-046', 'issuance', 'issuance', 43, 'Propofol 10mg/ml 50ml Vial', 46, 'vial', 0.000, 5.000, 35.000, NULL, 3, '2026-03-04 10:39:53', NULL, NULL, NULL),
(47, 'MOV-PH-2026-047', 'receiving', 'receiving', 44, 'Lactated Ringer\'s Solution 1L', 44, 'bottle', 200.000, 0.000, 200.000, NULL, 2, '2026-03-04 10:39:53', NULL, NULL, NULL),
(48, 'MOV-PH-2026-048', 'issuance', 'issuance', 45, 'Lactated Ringer\'s Solution 1L', 44, 'bottle', 0.000, 50.000, 150.000, NULL, 3, '2026-03-04 10:39:53', NULL, NULL, NULL),
(49, 'MOV-PH-2026-049', 'receiving', 'receiving', 46, 'Fentanyl 50mcg/ml Ampule', 47, 'ampule', 30.000, 0.000, 30.000, NULL, 2, '2026-03-04 10:39:53', NULL, NULL, NULL),
(50, 'MOV-PH-2026-050', 'issuance', 'issuance', 48, 'Midazolam 5mg/ml Ampule', 48, 'ampule', 0.000, 10.000, 40.000, NULL, 2, '2026-03-04 10:39:53', NULL, NULL, NULL),
(51, 'MOV-20260304-150215-2030', 'receiving', 'receiving', 51, 'Dexamethasone 4mg Tablet', 51, 'Bottle', 3123.000, 0.000, 3123.000, 23123.00, 1, '2026-03-04 15:02:15', NULL, '2026-03-04 15:02:15', '2026-03-04 15:02:15'),
(52, 'MOV-20260304-150253-6039', 'receiving', 'receiving', 52, 'Amoxicillin 500mg Capsule', 52, 'Piece', 55.000, 0.000, 55.000, 1000.00, 1, '2026-03-04 15:02:53', NULL, '2026-03-04 15:02:53', '2026-03-04 15:02:53'),
(53, 'MOV-20260304-150253-8428', 'receiving', 'receiving', 52, 'Cetirizine 10mg Tablet', 53, 'Box', 56.000, 0.000, 56.000, 40000.00, 1, '2026-03-04 15:02:53', NULL, '2026-03-04 15:02:53', '2026-03-04 15:02:53'),
(54, 'MOV-20260304-150253-2471', 'receiving', 'receiving', 52, 'Clopidogrel 75mg Tablet', 54, 'Box', 56.000, 0.000, 56.000, 70000.00, 1, '2026-03-04 15:02:53', NULL, '2026-03-04 15:02:53', '2026-03-04 15:02:53'),
(55, 'MOV-20260304-150253-4538', 'receiving', 'receiving', 52, 'Digoxin 0.25mg Tablet', 55, 'Box', 67.000, 0.000, 67.000, 50000.00, 1, '2026-03-04 15:02:53', NULL, '2026-03-04 15:02:53', '2026-03-04 15:02:53'),
(56, 'MOV-20260304-150253-2250', 'receiving', 'receiving', 52, 'Paracetamol 250mg/5ml Syrup', 56, 'Bottle', 75.000, 0.000, 75.000, 10000.00, 1, '2026-03-04 15:02:53', NULL, '2026-03-04 15:02:53', '2026-03-04 15:02:53'),
(57, 'MOV-20260304-151650-1187', 'receiving', 'receiving', 53, '0.9% Sodium Chloride IV 1L', 57, 'Bottle', 2.000, 0.000, 2.000, 213.00, 1, '2026-03-04 15:16:50', NULL, '2026-03-04 15:16:50', '2026-03-04 15:16:50'),
(58, 'MOV-20260304-154342-7509', 'receiving', 'receiving', 54, 'Aspirin 81mg Tablet', 58, 'Box', 100.000, 0.000, 100.000, 100.00, 1, '2026-03-04 15:43:42', NULL, '2026-03-04 15:43:42', '2026-03-04 15:43:42'),
(59, 'MOVOUT-20260304-154455-7619', 'issuance', 'issuance', 53, 'Aspirin 81mg Tablet', 12, 'Box', 0.000, 11.000, 139.000, 85.00, 1, '2026-03-04 15:44:55', 'Issuance release', '2026-03-04 15:44:55', '2026-03-04 15:44:55'),
(60, 'MOV-20260304-163859-6924', 'receiving', 'receiving', 55, 'Paracetamol 500mg', 59, 'Box', 20.000, 0.000, 20.000, 150.00, 1, '2026-03-04 16:38:59', NULL, '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(61, 'MOV-20260304-163859-6627', 'receiving', 'receiving', 55, 'Amoxicillin 250mg', 60, 'Capsule', 100.000, 0.000, 100.000, 5.50, 1, '2026-03-04 16:38:59', NULL, '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(62, 'MOV-20260304-163859-3923', 'receiving', 'receiving', 55, 'Surgical Masks', 61, 'Box', 50.000, 0.000, 50.000, 55.00, 1, '2026-03-04 16:38:59', NULL, '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(63, 'MOV-20260304-163859-3975', 'receiving', 'receiving', 55, 'Latex Gloves (Medium)', 62, 'Pack', 30.000, 0.000, 30.000, 250.00, 1, '2026-03-04 16:38:59', NULL, '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(64, 'MOV-20260304-163859-7324', 'receiving', 'receiving', 55, 'Syringe 5ml', 63, 'Piece', 200.000, 0.000, 200.000, 12.00, 1, '2026-03-04 16:38:59', NULL, '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(65, 'MOV-20260304-163859-4025', 'receiving', 'receiving', 55, 'Ibuprofen 400mg', 64, 'Tablet', 500.000, 0.000, 500.000, 8.00, 1, '2026-03-04 16:38:59', NULL, '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(66, 'MOV-20260304-163859-5764', 'receiving', 'receiving', 55, 'Gauze Pad 4x4', 65, 'Pack', 20.000, 0.000, 20.000, 45.00, 1, '2026-03-04 16:38:59', NULL, '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(67, 'MOV-20260304-163859-7604', 'receiving', 'receiving', 55, 'Cotton Balls', 66, 'Roll', 15.000, 0.000, 15.000, 80.00, 1, '2026-03-04 16:38:59', NULL, '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(68, 'MOV-20260304-163859-1942', 'receiving', 'receiving', 55, 'Povidone Iodine 10%', 67, 'Bottle', 10.000, 0.000, 10.000, 120.00, 1, '2026-03-04 16:38:59', NULL, '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(69, 'MOV-20260304-163859-3596', 'receiving', 'receiving', 55, 'Digital Thermometer', 68, 'Piece', 5.000, 0.000, 5.000, 150.00, 1, '2026-03-04 16:38:59', NULL, '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(70, 'MOV-20260304-163859-2724', 'receiving', 'receiving', 55, 'Lidocaine Ointment', 69, 'Tube', 12.000, 0.000, 12.000, 350.00, 1, '2026-03-04 16:38:59', NULL, '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(71, 'MOV-20260304-163859-9948', 'receiving', 'receiving', 55, 'Saline Solution 1L', 70, 'Piece', 50.000, 0.000, 50.000, 90.00, 1, '2026-03-04 16:38:59', NULL, '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(72, 'MOV-20260304-163859-2116', 'receiving', 'receiving', 55, 'Epinephrine 10mg', 71, 'Vial', 25.000, 0.000, 25.000, 800.00, 1, '2026-03-04 16:38:59', NULL, '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(73, 'MOV-20260304-163859-6158', 'receiving', 'receiving', 55, 'IV Cannula G20', 72, 'Piece', 100.000, 0.000, 100.000, 0.00, 1, '2026-03-04 16:38:59', NULL, '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(74, 'MOV-20260304-163859-5442', 'receiving', 'receiving', 55, 'First Aid Kit', 73, 'Set', 3.000, 0.000, 3.000, 2500.00, 1, '2026-03-04 16:38:59', NULL, '2026-03-04 16:38:59', '2026-03-04 16:38:59'),
(75, 'MOV-20260304-170210-3778', 'receiving', 'receiving', 56, 'Azithromycin 500mg Tablet', 74, 'Capsule', 113.000, 0.000, 113.000, 124.00, 1, '2026-03-04 17:02:10', NULL, '2026-03-04 17:02:10', '2026-03-04 17:02:10'),
(76, 'MOV-20260304-170210-6953', 'receiving', 'receiving', 56, 'Azithromycin 500mg Tablet', 75, 'Tablet', 2400.000, 0.000, 2400.000, 214.00, 1, '2026-03-04 17:02:10', NULL, '2026-03-04 17:02:10', '2026-03-04 17:02:10'),
(77, 'MOV-20260304-170210-9105', 'receiving', 'receiving', 56, 'Clopidogrel 75mg Tablet', 76, 'Tube', 112.000, 0.000, 112.000, 124.00, 1, '2026-03-04 17:02:10', NULL, '2026-03-04 17:02:10', '2026-03-04 17:02:10'),
(78, 'MOV-20260312-084121-9758', 'receiving', 'receiving', 57, 'Amoxicillin 500mg Capsule', 77, 'Box', 9.000, 0.000, 9.000, 500.00, 1, '2026-03-12 08:41:21', 'Needed at Pharmacy 1', '2026-03-12 08:41:21', '2026-03-12 08:41:21'),
(79, 'MOVOUT-20260312-084906-7395', 'issuance', 'issuance', 54, 'Amoxicillin 500mg Capsule', 1, 'Box', 0.000, 5.000, 95.000, 350.00, 1, '2026-03-12 08:49:06', 'Issuance release', '2026-03-12 08:49:06', '2026-03-12 08:49:06'),
(80, 'MOV-20260312-093449-1143', 'receiving', 'receiving', 58, 'Kisspirin 100mg', 78, 'Box', 69.000, 0.000, 69.000, 100.00, 1, '2026-03-12 09:34:49', 'Flow 1', '2026-03-12 09:34:49', '2026-03-12 09:34:49'),
(81, 'MOV-20260312-093449-8603', 'receiving', 'receiving', 58, 'Yakapsul 500mg', 79, 'Box', 67.000, 0.000, 67.000, 200.00, 1, '2026-03-12 09:34:49', 'Flow 2', '2026-03-12 09:34:49', '2026-03-12 09:34:49'),
(82, 'MOV-20260312-093636-9402', 'receiving', 'receiving', 59, 'Kisspirin 100mg', 80, 'Box', 50.000, 0.000, 50.000, 100.00, 1, '2026-03-12 09:36:36', 'Flow 2', '2026-03-12 09:36:36', '2026-03-12 09:36:36'),
(83, 'MOV-20260312-093636-3283', 'receiving', 'receiving', 59, 'Yakapsul 500mg', 81, 'Box', 50.000, 0.000, 50.000, 100.00, 1, '2026-03-12 09:36:36', 'Flow 2', '2026-03-12 09:36:36', '2026-03-12 09:36:36'),
(84, 'MOVOUT-20260312-093951-8382', 'issuance', 'issuance', 55, 'Yakapsul 500mg', 79, 'Box', 0.000, 50.000, 17.000, 200.00, 1, '2026-03-12 09:39:51', 'Issuance release', '2026-03-12 09:39:51', '2026-03-12 09:39:51'),
(85, 'MOVOUT-20260312-093951-8370', 'issuance', 'issuance', 55, 'Kisspirin 100mg', 78, 'Box', 0.000, 50.000, 19.000, 100.00, 1, '2026-03-12 09:39:51', 'Issuance release', '2026-03-12 09:39:51', '2026-03-12 09:39:51'),
(86, 'MOVOUT-20260312-094405-8683', 'issuance', 'issuance', 56, 'Yakapsul 500mg', 79, 'Box', 0.000, 17.000, 0.000, 200.00, 1, '2026-03-12 09:44:05', 'Issuance release', '2026-03-12 09:44:05', '2026-03-12 09:44:05'),
(87, 'MOVOUT-20260312-094405-7959', 'issuance', 'issuance', 56, 'Yakapsul 500mg', 81, 'Box', 0.000, 43.000, 7.000, 100.00, 1, '2026-03-12 09:44:05', 'Issuance release', '2026-03-12 09:44:05', '2026-03-12 09:44:05'),
(88, 'MOVOUT-20260312-094405-9457', 'issuance', 'issuance', 56, 'Kisspirin 100mg', 78, 'Box', 0.000, 19.000, 0.000, 100.00, 1, '2026-03-12 09:44:05', 'Issuance release', '2026-03-12 09:44:05', '2026-03-12 09:44:05'),
(89, 'MOVOUT-20260312-094405-2468', 'issuance', 'issuance', 56, 'Kisspirin 100mg', 80, 'Box', 0.000, 41.000, 9.000, 100.00, 1, '2026-03-12 09:44:05', 'Issuance release', '2026-03-12 09:44:05', '2026-03-12 09:44:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `status_message` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `last_active` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `status`, `status_message`, `active`, `last_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', NULL, NULL, 1, '2026-03-12 15:19:26', '2026-03-04 09:53:30', '2026-03-04 09:53:30', NULL),
(2, 'employee', NULL, NULL, 1, '2026-03-12 14:40:10', '2026-03-04 09:53:31', '2026-03-04 09:53:31', NULL),
(3, 'itstaff', NULL, NULL, 1, '2026-03-12 13:25:33', '2026-03-04 09:53:32', '2026-03-04 09:53:32', NULL),
(4, 'john.doe', NULL, NULL, 1, '2026-03-10 11:05:57', '2026-03-10 11:05:21', '2026-03-10 11:05:21', NULL),
(5, 'sean', NULL, NULL, 1, '2026-03-10 16:19:33', '2026-03-10 15:36:03', '2026-03-10 15:36:03', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `analytics_daily_metrics`
--
ALTER TABLE `analytics_daily_metrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `metric_date` (`metric_date`),
  ADD KEY `module` (`module`),
  ADD KEY `metric_key` (`metric_key`),
  ADD KEY `metric_date_metric_key_module` (`metric_date`,`metric_key`,`module`);

--
-- Indexes for table `analytics_events`
--
ALTER TABLE `analytics_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_name` (`event_name`),
  ADD KEY `module` (`module`),
  ADD KEY `actor_id` (`actor_id`),
  ADD KEY `reference_type_reference_id` (`reference_type`,`reference_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reference_type_reference_id` (`reference_type`,`reference_id`),
  ADD KEY `decision` (`decision`),
  ADD KEY `approver_id` (`approver_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `actor_id` (`actor_id`),
  ADD KEY `module` (`module`),
  ADD KEY `reference_type_reference_id` (`reference_type`,`reference_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_groups_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_identities`
--
ALTER TABLE `auth_identities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_secret` (`type`,`secret`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_logins`
--
ALTER TABLE `auth_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_permissions_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `auth_remember_tokens_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `inventory_stocks`
--
ALTER TABLE `inventory_stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_name_unit_batch_no_lot_no_expiry_date` (`item_name`,`unit`,`batch_no`,`lot_no`,`expiry_date`),
  ADD KEY `item_name` (`item_name`),
  ADD KEY `expiry_date` (`expiry_date`),
  ADD KEY `idx_inventory_stocks_available_qty` (`available_qty`);

--
-- Indexes for table `issuances`
--
ALTER TABLE `issuances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `issuance_number` (`issuance_number`),
  ADD KEY `status` (`status`),
  ADD KEY `requestor_id` (`requestor_id`),
  ADD KEY `issue_date` (`issue_date`),
  ADD KEY `idx_issuances_status_date` (`status`,`issue_date`);

--
-- Indexes for table `issuance_items`
--
ALTER TABLE `issuance_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `issuance_id` (`issuance_id`),
  ADD KEY `item_name` (`item_name`),
  ADD KEY `inventory_stock_id` (`inventory_stock_id`);

--
-- Indexes for table `issuance_item_allocations`
--
ALTER TABLE `issuance_item_allocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `issuance_id` (`issuance_id`),
  ADD KEY `issuance_item_id` (`issuance_item_id`),
  ADD KEY `inventory_stock_id` (`inventory_stock_id`),
  ADD KEY `expiry_date` (`expiry_date`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `po_requests`
--
ALTER TABLE `po_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `po_request_number` (`po_request_number`),
  ADD KEY `status` (`status`),
  ADD KEY `purchase_order_id` (`purchase_order_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `po_number` (`po_number`),
  ADD KEY `status` (`status`),
  ADD KEY `purchase_request_id` (`purchase_request_id`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_id` (`purchase_order_id`),
  ADD KEY `purchase_request_item_id` (`purchase_request_item_id`);

--
-- Indexes for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pr_number` (`pr_number`),
  ADD KEY `status` (`status`),
  ADD KEY `requested_by` (`requested_by`);

--
-- Indexes for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_request_id` (`purchase_request_id`);

--
-- Indexes for table `receivings`
--
ALTER TABLE `receivings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receiving_number` (`receiving_number`),
  ADD KEY `status` (`status`),
  ADD KEY `po_request_id` (`po_request_id`),
  ADD KEY `purchase_order_id` (`purchase_order_id`);

--
-- Indexes for table `receiving_items`
--
ALTER TABLE `receiving_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receiving_id` (`receiving_id`),
  ADD KEY `purchase_order_item_id` (`purchase_order_item_id`),
  ADD KEY `expiry_date` (`expiry_date`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `movement_number` (`movement_number`),
  ADD KEY `stock_movements_inventory_stock_id_foreign` (`inventory_stock_id`),
  ADD KEY `reference_type_reference_id` (`reference_type`,`reference_id`),
  ADD KEY `item_name` (`item_name`),
  ADD KEY `performed_at` (`performed_at`),
  ADD KEY `idx_stock_movements_type_date` (`movement_type`,`performed_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `analytics_daily_metrics`
--
ALTER TABLE `analytics_daily_metrics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `analytics_events`
--
ALTER TABLE `analytics_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1019;

--
-- AUTO_INCREMENT for table `approvals`
--
ALTER TABLE `approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `auth_identities`
--
ALTER TABLE `auth_identities`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `auth_logins`
--
ALTER TABLE `auth_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_stocks`
--
ALTER TABLE `inventory_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `issuances`
--
ALTER TABLE `issuances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `issuance_items`
--
ALTER TABLE `issuance_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `issuance_item_allocations`
--
ALTER TABLE `issuance_item_allocations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `po_requests`
--
ALTER TABLE `po_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `receivings`
--
ALTER TABLE `receivings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `receiving_items`
--
ALTER TABLE `receiving_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_identities`
--
ALTER TABLE `auth_identities`
  ADD CONSTRAINT `auth_identities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  ADD CONSTRAINT `auth_permissions_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  ADD CONSTRAINT `auth_remember_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `issuance_items`
--
ALTER TABLE `issuance_items`
  ADD CONSTRAINT `issuance_items_inventory_stock_id_foreign` FOREIGN KEY (`inventory_stock_id`) REFERENCES `inventory_stocks` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `issuance_items_issuance_id_foreign` FOREIGN KEY (`issuance_id`) REFERENCES `issuances` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `issuance_item_allocations`
--
ALTER TABLE `issuance_item_allocations`
  ADD CONSTRAINT `issuance_item_allocations_inventory_stock_id_foreign` FOREIGN KEY (`inventory_stock_id`) REFERENCES `inventory_stocks` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `issuance_item_allocations_issuance_id_foreign` FOREIGN KEY (`issuance_id`) REFERENCES `issuances` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `issuance_item_allocations_issuance_item_id_foreign` FOREIGN KEY (`issuance_item_id`) REFERENCES `issuance_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `po_requests`
--
ALTER TABLE `po_requests`
  ADD CONSTRAINT `po_requests_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_order_items_purchase_request_item_id_foreign` FOREIGN KEY (`purchase_request_item_id`) REFERENCES `purchase_request_items` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  ADD CONSTRAINT `purchase_request_items_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `receivings`
--
ALTER TABLE `receivings`
  ADD CONSTRAINT `receivings_po_request_id_foreign` FOREIGN KEY (`po_request_id`) REFERENCES `po_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `receivings_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `receiving_items`
--
ALTER TABLE `receiving_items`
  ADD CONSTRAINT `receiving_items_purchase_order_item_id_foreign` FOREIGN KEY (`purchase_order_item_id`) REFERENCES `purchase_order_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `receiving_items_receiving_id_foreign` FOREIGN KEY (`receiving_id`) REFERENCES `receivings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_inventory_stock_id_foreign` FOREIGN KEY (`inventory_stock_id`) REFERENCES `inventory_stocks` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
