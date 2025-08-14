-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 14, 2025 at 07:21 AM
-- Server version: 8.0.17
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `asset_management`
--
CREATE DATABASE IF NOT EXISTS `asset_management` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `asset_management`;

-- --------------------------------------------------------

--
-- Table structure for table `annual_surveys`
--

CREATE TABLE `annual_surveys` (
  `survey_id` int(11) NOT NULL,
  `survey_year` int(4) NOT NULL COMMENT 'ปีที่สำรวจ',
  `asset_id` int(11) NOT NULL COMMENT 'รหัสครุภัณฑ์',
  `condition` enum('ดี','พอใช้','ชำรุด','ไม่สามารถใช้งานได้') NOT NULL COMMENT 'สภาพครุภัณฑ์',
  `notes` text COMMENT 'บันทึกเพิ่มเติม',
  `surveyed_by` varchar(255) NOT NULL COMMENT 'ผู้สำรวจ',
  `survey_date` date NOT NULL COMMENT 'วันที่สำรวจ',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='ตารางการสำรวจประจำปี';

--
-- Dumping data for table `annual_surveys`
--

INSERT INTO `annual_surveys` (`survey_id`, `survey_year`, `asset_id`, `condition`, `notes`, `surveyed_by`, `survey_date`, `created_at`) VALUES
(1, 2024, 1, 'ดี', '', 'คณะกรรมการสำรวจ', '2024-12-01', '2025-08-06 03:33:18'),
(2, 2024, 2, 'ดี', NULL, 'คณะกรรมการสำรวจ', '2024-12-01', '2025-08-06 03:33:18'),
(3, 2024, 3, 'ดี', NULL, 'คณะกรรมการสำรวจ', '2024-12-01', '2025-08-06 03:33:18'),
(4, 2024, 4, 'ชำรุด', NULL, 'คณะกรรมการสำรวจ', '2024-12-01', '2025-08-06 03:33:18'),
(5, 2024, 5, 'ดี', NULL, 'คณะกรรมการสำรวจ', '2024-12-01', '2025-08-06 03:33:18');

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `asset_id` int(11) NOT NULL,
  `asset_name` varchar(255) NOT NULL COMMENT 'ชื่อครุภัณฑ์',
  `asset_type` varchar(100) NOT NULL COMMENT 'ประเภทครุภัณฑ์',
  `serial_number` varchar(100) DEFAULT NULL COMMENT 'หมายเลขซีเรียล',
  `purchase_date` date NOT NULL COMMENT 'วันที่จัดซื้อ',
  `purchase_price` decimal(15,2) NOT NULL COMMENT 'ราคาจัดซื้อ',
  `current_location` varchar(255) NOT NULL COMMENT 'สถานที่ตั้งปัจจุบัน',
  `status` enum('ใช้งาน','ชำรุด','จำหน่ายแล้ว','ซ่อมแซม') NOT NULL DEFAULT 'ใช้งาน' COMMENT 'สถานะ',
  `warranty_info` text COMMENT 'ข้อมูลการรับประกัน',
  `depreciation_rate` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'อัตราค่าเสื่อมราคา (%)',
  `acquisition_method` varchar(100) NOT NULL COMMENT 'วิธีการได้มา',
  `responsible_person` varchar(255) NOT NULL COMMENT 'ผู้รับผิดชอบ',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='ตารางครุภัณฑ์';

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`asset_id`, `asset_name`, `asset_type`, `serial_number`, `purchase_date`, `purchase_price`, `current_location`, `status`, `warranty_info`, `depreciation_rate`, `acquisition_method`, `responsible_person`, `created_at`) VALUES
(1, 'คอมพิวเตอร์ Dell OptiPlex 3070', 'คอมพิวเตอร์', 'DELL001', '2024-01-15', '25000.00', 'ห้องคอมพิวเตอร์ 1', 'ใช้งาน', NULL, '20.00', 'ซื้อ', 'นายสมชาย ใจดี', '2025-08-06 03:33:18'),
(2, 'เครื่องพิมพ์ HP LaserJet Pro', 'เครื่องพิมพ์', 'HP001', '2024-02-10', '8500.00', 'ห้องสำนักงาน', 'ใช้งาน', NULL, '25.00', 'ซื้อ', 'นางสาวมาลี สวยงาม', '2025-08-06 03:33:18'),
(3, 'โต๊ะทำงานไม้สัก', 'เฟอร์นิเจอร์', 'DESK001', '2023-12-01', '3500.00', 'ห้องผู้อำนวยการ', 'ใช้งาน', NULL, '10.00', 'ซื้อ', 'นายประชา รักงาน', '2025-08-06 03:33:18'),
(4, 'เก้าอี้สำนักงาน', 'เฟอร์นิเจอร์', 'CHAIR001', '2024-01-20', '2500.00', 'ห้องประชุม', 'ชำรุด', NULL, '15.00', 'ซื้อ', 'นางสุดา ขยันทำงาน', '2025-08-06 03:33:18'),
(5, 'โปรเจคเตอร์ Epson', 'โปรเจคเตอร์', 'EPSON001', '2023-11-15', '15000.00', 'ห้องประชุมใหญ่', 'ใช้งาน', '', '30.00', 'จัดซื้อ', 'นายวิชัย เก่งมาก', '2025-08-06 03:33:18');

-- --------------------------------------------------------

--
-- Table structure for table `asset_types`
--

CREATE TABLE `asset_types` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(100) NOT NULL COMMENT 'ชื่อประเภทครุภัณฑ์',
  `description` text COMMENT 'คำอธิบาย'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='ตารางประเภทครุภัณฑ์';

--
-- Dumping data for table `asset_types`
--

INSERT INTO `asset_types` (`type_id`, `type_name`, `description`) VALUES
(1, 'คอมพิวเตอร์', NULL),
(2, 'เครื่องพิมพ์', NULL),
(3, 'เครื่องถ่ายเอกสาร', NULL),
(4, 'โปรเจคเตอร์', NULL),
(5, 'เครื่องปรับอากาศ', NULL),
(6, 'รถยนต์', NULL),
(7, 'เฟอร์นิเจอร์', NULL),
(8, 'อุปกรณ์เครือข่าย', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contract_guarantees`
--

CREATE TABLE `contract_guarantees` (
  `guarantee_id` int(11) NOT NULL,
  `asset_id` int(11) DEFAULT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `vendor_contact` varchar(255) DEFAULT NULL,
  `contract_number` varchar(100) NOT NULL COMMENT 'เลขที่สัญญา',
  `guarantee_type` varchar(100) NOT NULL COMMENT 'ประเภทการค้ำประกัน',
  `guarantee_amount` decimal(15,2) NOT NULL COMMENT 'จำนวนเงินค้ำประกัน',
  `start_date` date NOT NULL COMMENT 'วันที่เริ่มต้น',
  `end_date` date NOT NULL COMMENT 'วันที่สิ้นสุด',
  `guarantee_provider` varchar(255) DEFAULT NULL COMMENT 'ผู้ค้ำประกัน',
  `coverage_details` text COMMENT 'รายละเอียดความคุ้มครอง',
  `terms_conditions` text COMMENT 'เงื่อนไขและข้อกำหนด',
  `claim_procedure` text COMMENT 'ขั้นตอนการเคลม',
  `notes` text COMMENT 'บันทึกเพิ่มเติม',
  `status` enum('ใช้งาน','หมดอายุ','ยกเลิก') NOT NULL DEFAULT 'ใช้งาน' COMMENT 'สถานะ',
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='ตารางค้ำประกันสัญญา';

--
-- Dumping data for table `contract_guarantees`
--

INSERT INTO `contract_guarantees` (`guarantee_id`, `asset_id`, `vendor_name`, `vendor_contact`, `contract_number`, `guarantee_type`, `guarantee_amount`, `start_date`, `end_date`, `guarantee_provider`, `coverage_details`, `terms_conditions`, `claim_procedure`, `notes`, `status`, `created_date`) VALUES
(1, NULL, '', '', 'CON2024001', 'หนังสือค้ำประกัน', '50000.00', '2024-01-01', '2024-12-31', 'ธนาคารกรุงเทพ', NULL, NULL, NULL, NULL, 'ใช้งาน', '2025-08-06 03:33:18'),
(2, NULL, '', '', 'CON2024002', 'เงินสดค้ำประกัน', '25000.00', '2024-02-01', '2025-01-31', 'บริษัท ABC จำกัด', NULL, NULL, NULL, NULL, 'ใช้งาน', '2025-08-06 03:33:18');

-- --------------------------------------------------------

--
-- Table structure for table `depreciation_records`
--

CREATE TABLE `depreciation_records` (
  `depreciation_record_id` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL COMMENT 'รหัสครุภัณฑ์',
  `record_date` date NOT NULL COMMENT 'วันที่บันทึก',
  `depreciation_amount` decimal(15,2) NOT NULL COMMENT 'จำนวนค่าเสื่อมราคา',
  `accumulated_depreciation` decimal(15,2) NOT NULL COMMENT 'ค่าเสื่อมราคาสะสม',
  `book_value` decimal(15,2) NOT NULL COMMENT 'มูลค่าตามบัญชี',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='ตารางบันทึกค่าเสื่อมราคา';

--
-- Dumping data for table `depreciation_records`
--

INSERT INTO `depreciation_records` (`depreciation_record_id`, `asset_id`, `record_date`, `depreciation_amount`, `accumulated_depreciation`, `book_value`, `created_at`) VALUES
(1, 4, '2025-08-08', '10.00', '10.00', '10.00', '2025-08-08 00:52:53');

-- --------------------------------------------------------

--
-- Table structure for table `disposals`
--

CREATE TABLE `disposals` (
  `disposal_id` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL COMMENT 'รหัสครุภัณฑ์',
  `disposal_date` date NOT NULL COMMENT 'วันที่จำหน่าย',
  `disposal_method` varchar(100) NOT NULL COMMENT 'วิธีการจำหน่าย',
  `disposal_price` decimal(15,2) DEFAULT NULL COMMENT 'ราคาที่จำหน่ายได้',
  `disposal_by` varchar(255) NOT NULL COMMENT 'ผู้ดำเนินการจำหน่าย',
  `reason` text COMMENT 'เหตุผลในการจำหน่าย',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='ตารางการจำหน่าย';

-- --------------------------------------------------------

--
-- Table structure for table `guarantees`
--

CREATE TABLE `guarantees` (
  `id` int(11) NOT NULL,
  `vendor` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guarantee_claims`
--

CREATE TABLE `guarantee_claims` (
  `id` int(11) NOT NULL,
  `guarantee_id` int(11) NOT NULL,
  `claim_reason` varchar(255) NOT NULL,
  `claim_description` text NOT NULL,
  `claim_amount` decimal(12,2) DEFAULT '0.00',
  `claim_date` date NOT NULL,
  `claim_status` varchar(50) NOT NULL DEFAULT 'รอดำเนินการ',
  `claimed_by` varchar(100) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guarantee_renewals`
--

CREATE TABLE `guarantee_renewals` (
  `id` int(11) NOT NULL,
  `guarantee_id` int(11) NOT NULL,
  `old_end_date` date NOT NULL,
  `new_end_date` date NOT NULL,
  `renewal_cost` decimal(12,2) DEFAULT '0.00',
  `renewal_notes` text,
  `renewed_by` varchar(100) DEFAULT NULL,
  `renewal_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `repairs`
--

CREATE TABLE `repairs` (
  `repair_id` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL COMMENT 'รหัสครุภัณฑ์',
  `request_date` date NOT NULL COMMENT 'วันที่แจ้งซ่อม',
  `repair_date` date DEFAULT NULL COMMENT 'วันที่ซ่อมเสร็จ',
  `description` text NOT NULL COMMENT 'รายละเอียดการซ่อม',
  `cost` decimal(15,2) DEFAULT NULL COMMENT 'ค่าใช้จ่ายในการซ่อม',
  `repaired_by` varchar(255) DEFAULT NULL COMMENT 'ผู้ซ่อมแซม',
  `status` enum('รอดำเนินการ','กำลังซ่อม','ซ่อมเสร็จแล้ว','ไม่สามารถซ่อมได้') NOT NULL DEFAULT 'รอดำเนินการ' COMMENT 'สถานะการซ่อม',
  `requested_by` varchar(255) NOT NULL COMMENT 'ผู้แจ้งซ่อม',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='ตารางการซ่อมแซม';

--
-- Dumping data for table `repairs`
--

INSERT INTO `repairs` (`repair_id`, `asset_id`, `request_date`, `repair_date`, `description`, `cost`, `repaired_by`, `status`, `requested_by`, `created_at`) VALUES
(1, 4, '2024-07-15', NULL, 'เก้าอี้หักขา ต้องการซ่อมแซม', NULL, NULL, 'รอดำเนินการ', 'นางสุดา ขยันทำงาน', '2025-08-06 03:33:18'),
(2, 2, '2024-06-20', NULL, 'เครื่องพิมพ์ติดกระดาษบ่อย', NULL, NULL, 'ซ่อมเสร็จแล้ว', 'นางสาวมาลี สวยงาม', '2025-08-06 03:33:18');

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `transfer_id` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL COMMENT 'รหัสครุภัณฑ์',
  `from_location` varchar(255) NOT NULL COMMENT 'จากสถานที่',
  `to_location` varchar(255) NOT NULL COMMENT 'ไปยังสถานที่',
  `transfer_date` date NOT NULL COMMENT 'วันที่โอนย้าย',
  `transfer_by` varchar(255) NOT NULL COMMENT 'ผู้โอนย้าย',
  `reason` text COMMENT 'เหตุผลในการโอนย้าย',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='ตารางการโอนย้าย';

--
-- Dumping data for table `transfers`
--

INSERT INTO `transfers` (`transfer_id`, `asset_id`, `from_location`, `to_location`, `transfer_date`, `transfer_by`, `reason`, `created_at`) VALUES
(1, 1, 'ห้องคอมพิวเตอร์ 2', 'ห้องคอมพิวเตอร์ 1', '2024-03-01', 'นายสมชาย ใจดี', 'ย้ายเพื่อใช้งานในห้องใหม่', '2025-08-06 03:33:18'),
(2, 3, 'ห้องรองผู้อำนวยการ', 'ห้องผู้อำนวยการ', '2024-01-05', 'นายประชา รักงาน', 'เปลี่ยนตำแหน่งงาน', '2025-08-06 03:33:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL COMMENT 'ชื่อผู้ใช้',
  `password` varchar(255) NOT NULL COMMENT 'รหัสผ่าน',
  `full_name` varchar(255) NOT NULL COMMENT 'ชื่อ-นามสกุล',
  `email` varchar(255) DEFAULT NULL COMMENT 'อีเมล',
  `role` enum('admin','user','viewer') NOT NULL DEFAULT 'user' COMMENT 'บทบาท',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active' COMMENT 'สถานะ',
  `last_login` timestamp NULL DEFAULT NULL COMMENT 'เข้าสู่ระบบล่าสุด',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='ตารางผู้ใช้งานระบบ';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `full_name`, `email`, `role`, `status`, `last_login`, `created_at`) VALUES
(1, 'admin', '$2y$10$z6NDUCJKb2vJyjgWAlkb4uG9KOfomBpS9oyV1aZdXLTcm1ED6bdFO', 'ผู้ดูแลระบบ', 'admin@example.com', 'admin', 'active', NULL, '2025-08-06 03:33:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `annual_surveys`
--
ALTER TABLE `annual_surveys`
  ADD PRIMARY KEY (`survey_id`),
  ADD UNIQUE KEY `unique_asset_year` (`asset_id`,`survey_year`),
  ADD KEY `fk_surveys_asset` (`asset_id`),
  ADD KEY `idx_survey_year` (`survey_year`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`asset_id`),
  ADD UNIQUE KEY `serial_number` (`serial_number`),
  ADD KEY `idx_asset_type` (`asset_type`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_location` (`current_location`);

--
-- Indexes for table `asset_types`
--
ALTER TABLE `asset_types`
  ADD PRIMARY KEY (`type_id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

--
-- Indexes for table `contract_guarantees`
--
ALTER TABLE `contract_guarantees`
  ADD PRIMARY KEY (`guarantee_id`),
  ADD UNIQUE KEY `unique_contract_number` (`contract_number`),
  ADD KEY `idx_guarantee_dates` (`start_date`,`end_date`),
  ADD KEY `idx_guarantee_status` (`status`),
  ADD KEY `fk_contract_guarantees_asset` (`asset_id`);

--
-- Indexes for table `depreciation_records`
--
ALTER TABLE `depreciation_records`
  ADD PRIMARY KEY (`depreciation_record_id`),
  ADD KEY `fk_depreciation_asset` (`asset_id`),
  ADD KEY `idx_record_date` (`record_date`);

--
-- Indexes for table `disposals`
--
ALTER TABLE `disposals`
  ADD PRIMARY KEY (`disposal_id`),
  ADD KEY `fk_disposals_asset` (`asset_id`);

--
-- Indexes for table `guarantees`
--
ALTER TABLE `guarantees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guarantee_claims`
--
ALTER TABLE `guarantee_claims`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_g` (`guarantee_id`);

--
-- Indexes for table `guarantee_renewals`
--
ALTER TABLE `guarantee_renewals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_g` (`guarantee_id`);

--
-- Indexes for table `repairs`
--
ALTER TABLE `repairs`
  ADD PRIMARY KEY (`repair_id`),
  ADD KEY `fk_repairs_asset` (`asset_id`),
  ADD KEY `idx_repair_status` (`status`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`transfer_id`),
  ADD KEY `fk_transfers_asset` (`asset_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `unique_username` (`username`),
  ADD KEY `idx_user_role` (`role`),
  ADD KEY `idx_user_status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `annual_surveys`
--
ALTER TABLE `annual_surveys`
  MODIFY `survey_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `asset_types`
--
ALTER TABLE `asset_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contract_guarantees`
--
ALTER TABLE `contract_guarantees`
  MODIFY `guarantee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `depreciation_records`
--
ALTER TABLE `depreciation_records`
  MODIFY `depreciation_record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `disposals`
--
ALTER TABLE `disposals`
  MODIFY `disposal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guarantees`
--
ALTER TABLE `guarantees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guarantee_claims`
--
ALTER TABLE `guarantee_claims`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guarantee_renewals`
--
ALTER TABLE `guarantee_renewals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `repairs`
--
ALTER TABLE `repairs`
  MODIFY `repair_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `transfer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `annual_surveys`
--
ALTER TABLE `annual_surveys`
  ADD CONSTRAINT `fk_surveys_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`) ON DELETE CASCADE;

--
-- Constraints for table `contract_guarantees`
--
ALTER TABLE `contract_guarantees`
  ADD CONSTRAINT `fk_contract_guarantees_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `depreciation_records`
--
ALTER TABLE `depreciation_records`
  ADD CONSTRAINT `fk_depreciation_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`) ON DELETE CASCADE;

--
-- Constraints for table `disposals`
--
ALTER TABLE `disposals`
  ADD CONSTRAINT `fk_disposals_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`) ON DELETE CASCADE;

--
-- Constraints for table `guarantee_claims`
--
ALTER TABLE `guarantee_claims`
  ADD CONSTRAINT `fk_claims_guarantee` FOREIGN KEY (`guarantee_id`) REFERENCES `contract_guarantees` (`guarantee_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `guarantee_renewals`
--
ALTER TABLE `guarantee_renewals`
  ADD CONSTRAINT `fk_renewals_guarantee` FOREIGN KEY (`guarantee_id`) REFERENCES `contract_guarantees` (`guarantee_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `repairs`
--
ALTER TABLE `repairs`
  ADD CONSTRAINT `fk_repairs_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`) ON DELETE CASCADE;

--
-- Constraints for table `transfers`
--
ALTER TABLE `transfers`
  ADD CONSTRAINT `fk_transfers_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
