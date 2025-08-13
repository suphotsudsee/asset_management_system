-- Asset Management System Database
-- Created: 2025-08-05

CREATE DATABASE IF NOT EXISTS `asset_management` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `asset_management`;

-- ตาราง: assets (ครุภัณฑ์)
CREATE TABLE `assets` (
  `asset_id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_name` varchar(255) NOT NULL COMMENT 'ชื่อครุภัณฑ์',
  `asset_type` varchar(100) NOT NULL COMMENT 'ประเภทครุภัณฑ์',
  `serial_number` varchar(100) UNIQUE DEFAULT NULL COMMENT 'หมายเลขซีเรียล',
  `purchase_date` date NOT NULL COMMENT 'วันที่จัดซื้อ',
  `purchase_price` decimal(15,2) NOT NULL COMMENT 'ราคาจัดซื้อ',
  `current_location` varchar(255) NOT NULL COMMENT 'สถานที่ตั้งปัจจุบัน',
  `status` enum('ใช้งาน','ชำรุด','จำหน่ายแล้ว','ซ่อมแซม') NOT NULL DEFAULT 'ใช้งาน' COMMENT 'สถานะ',
  `warranty_info` text DEFAULT NULL COMMENT 'ข้อมูลการรับประกัน',
  `depreciation_rate` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'อัตราค่าเสื่อมราคา (%)',
  `acquisition_method` varchar(100) NOT NULL COMMENT 'วิธีการได้มา',
  `responsible_person` varchar(255) NOT NULL COMMENT 'ผู้รับผิดชอบ',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`asset_id`),
  KEY `idx_asset_type` (`asset_type`),
  KEY `idx_status` (`status`),
  KEY `idx_location` (`current_location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางครุภัณฑ์';

-- ตาราง: transfers (การโอนย้าย)
CREATE TABLE `transfers` (
  `transfer_id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL COMMENT 'รหัสครุภัณฑ์',
  `from_location` varchar(255) NOT NULL COMMENT 'จากสถานที่',
  `to_location` varchar(255) NOT NULL COMMENT 'ไปยังสถานที่',
  `transfer_date` date NOT NULL COMMENT 'วันที่โอนย้าย',
  `transfer_by` varchar(255) NOT NULL COMMENT 'ผู้โอนย้าย',
  `reason` text DEFAULT NULL COMMENT 'เหตุผลในการโอนย้าย',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`transfer_id`),
  KEY `fk_transfers_asset` (`asset_id`),
  CONSTRAINT `fk_transfers_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางการโอนย้าย';

-- ตาราง: disposals (การจำหน่าย)
CREATE TABLE `disposals` (
  `disposal_id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL COMMENT 'รหัสครุภัณฑ์',
  `disposal_date` date NOT NULL COMMENT 'วันที่จำหน่าย',
  `disposal_method` varchar(100) NOT NULL COMMENT 'วิธีการจำหน่าย',
  `disposal_price` decimal(15,2) DEFAULT NULL COMMENT 'ราคาที่จำหน่ายได้',
  `disposal_by` varchar(255) NOT NULL COMMENT 'ผู้ดำเนินการจำหน่าย',
  `reason` text DEFAULT NULL COMMENT 'เหตุผลในการจำหน่าย',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`disposal_id`),
  KEY `fk_disposals_asset` (`asset_id`),
  CONSTRAINT `fk_disposals_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางการจำหน่าย';

-- ตาราง: repairs (การซ่อมแซม)
CREATE TABLE `repairs` (
  `repair_id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL COMMENT 'รหัสครุภัณฑ์',
  `request_date` date NOT NULL COMMENT 'วันที่แจ้งซ่อม',
  `repair_date` date DEFAULT NULL COMMENT 'วันที่ซ่อมเสร็จ',
  `description` text NOT NULL COMMENT 'รายละเอียดการซ่อม',
  `cost` decimal(15,2) DEFAULT NULL COMMENT 'ค่าใช้จ่ายในการซ่อม',
  `repaired_by` varchar(255) DEFAULT NULL COMMENT 'ผู้ซ่อมแซม',
  `status` enum('รอดำเนินการ','กำลังซ่อม','ซ่อมเสร็จแล้ว','ไม่สามารถซ่อมได้') NOT NULL DEFAULT 'รอดำเนินการ' COMMENT 'สถานะการซ่อม',
  `requested_by` varchar(255) NOT NULL COMMENT 'ผู้แจ้งซ่อม',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`repair_id`),
  KEY `fk_repairs_asset` (`asset_id`),
  KEY `idx_repair_status` (`status`),
  CONSTRAINT `fk_repairs_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางการซ่อมแซม';

-- ตาราง: annual_surveys (การสำรวจประจำปี)
CREATE TABLE `annual_surveys` (
  `survey_id` int(11) NOT NULL AUTO_INCREMENT,
  `survey_year` int(4) NOT NULL COMMENT 'ปีที่สำรวจ',
  `asset_id` int(11) NOT NULL COMMENT 'รหัสครุภัณฑ์',
  `condition` enum('ดี','พอใช้','ชำรุด','ไม่สามารถใช้งานได้') NOT NULL COMMENT 'สภาพครุภัณฑ์',
  `notes` text DEFAULT NULL COMMENT 'บันทึกเพิ่มเติม',
  `surveyed_by` varchar(255) NOT NULL COMMENT 'ผู้สำรวจ',
  `survey_date` date NOT NULL COMMENT 'วันที่สำรวจ',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`survey_id`),
  UNIQUE KEY `unique_asset_year` (`asset_id`, `survey_year`),
  KEY `fk_surveys_asset` (`asset_id`),
  KEY `idx_survey_year` (`survey_year`),
  CONSTRAINT `fk_surveys_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางการสำรวจประจำปี';

-- ตาราง: depreciation_records (บันทึกค่าเสื่อมราคา)
CREATE TABLE `depreciation_records` (
  `depreciation_record_id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL COMMENT 'รหัสครุภัณฑ์',
  `record_date` date NOT NULL COMMENT 'วันที่บันทึก',
  `depreciation_amount` decimal(15,2) NOT NULL COMMENT 'จำนวนค่าเสื่อมราคา',
  `accumulated_depreciation` decimal(15,2) NOT NULL COMMENT 'ค่าเสื่อมราคาสะสม',
  `book_value` decimal(15,2) NOT NULL COMMENT 'มูลค่าตามบัญชี',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`depreciation_record_id`),
  KEY `fk_depreciation_asset` (`asset_id`),
  KEY `idx_record_date` (`record_date`),
  CONSTRAINT `fk_depreciation_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางบันทึกค่าเสื่อมราคา';

-- ตาราง: contract_guarantees (ค้ำประกันสัญญา)
CREATE TABLE `contract_guarantees` (
  `guarantee_id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL COMMENT 'รหัสครุภัณฑ์',
  `contract_number` varchar(100) DEFAULT NULL COMMENT 'เลขที่สัญญา',
  `guarantee_type` varchar(100) NOT NULL COMMENT 'ประเภทการค้ำประกัน',
  `guarantee_amount` decimal(15,2) DEFAULT NULL COMMENT 'จำนวนเงินค้ำประกัน',
  `start_date` date NOT NULL COMMENT 'วันที่เริ่มต้น',
  `end_date` date NOT NULL COMMENT 'วันที่สิ้นสุด',
  `vendor_name` varchar(255) NOT NULL COMMENT 'ชื่อผู้จำหน่าย/ผู้ให้บริการ',
  `vendor_contact` varchar(255) NOT NULL COMMENT 'ข้อมูลติดต่อผู้จำหน่าย',
  `coverage_details` text DEFAULT NULL COMMENT 'รายละเอียดความคุ้มครอง',
  `terms_conditions` text DEFAULT NULL COMMENT 'เงื่อนไขและข้อกำหนด',
  `claim_procedure` text DEFAULT NULL COMMENT 'ขั้นตอนการเคลม',
  `notes` text DEFAULT NULL COMMENT 'บันทึกเพิ่มเติม',
  `status` enum('ใช้งาน','หมดอายุ','ยกเลิก') NOT NULL DEFAULT 'ใช้งาน' COMMENT 'สถานะ',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`guarantee_id`),
  UNIQUE KEY `unique_contract_number` (`contract_number`),
  KEY `fk_contract_guarantees_asset` (`asset_id`),
  KEY `idx_guarantee_dates` (`start_date`, `end_date`),
  KEY `idx_guarantee_status` (`status`),
  CONSTRAINT `fk_contract_guarantees_asset` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`asset_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางค้ำประกันสัญญา';

-- ตาราง: users (ผู้ใช้งานระบบ) - เพิ่มเติมสำหรับการจัดการผู้ใช้
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE COMMENT 'ชื่อผู้ใช้',
  `password` varchar(255) NOT NULL COMMENT 'รหัสผ่าน',
  `full_name` varchar(255) NOT NULL COMMENT 'ชื่อ-นามสกุล',
  `email` varchar(255) DEFAULT NULL COMMENT 'อีเมล',
  `role` enum('admin','user','viewer') NOT NULL DEFAULT 'user' COMMENT 'บทบาท',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active' COMMENT 'สถานะ',
  `last_login` timestamp NULL DEFAULT NULL COMMENT 'เข้าสู่ระบบล่าสุด',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `unique_username` (`username`),
  KEY `idx_user_role` (`role`),
  KEY `idx_user_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางผู้ใช้งานระบบ';

-- Insert default admin user
INSERT INTO `users` (`username`, `password`, `full_name`, `email`, `role`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ผู้ดูแลระบบ', 'admin@example.com', 'admin');

-- Insert sample data for testing
INSERT INTO `assets` (`asset_name`, `asset_type`, `serial_number`, `purchase_date`, `purchase_price`, `current_location`, `status`, `depreciation_rate`, `acquisition_method`, `responsible_person`) VALUES
('คอมพิวเตอร์ Dell OptiPlex 3070', 'คอมพิวเตอร์', 'DELL001', '2024-01-15', 25000.00, 'ห้องคอมพิวเตอร์ 1', 'ใช้งาน', 20.00, 'ซื้อ', 'นายสมชาย ใจดี'),
('เครื่องพิมพ์ HP LaserJet Pro', 'เครื่องพิมพ์', 'HP001', '2024-02-10', 8500.00, 'ห้องสำนักงาน', 'ใช้งาน', 25.00, 'ซื้อ', 'นางสาวมาลี สวยงาม'),
('โต๊ะทำงานไม้สัก', 'เฟอร์นิเจอร์', 'DESK001', '2023-12-01', 3500.00, 'ห้องผู้อำนวยการ', 'ใช้งาน', 10.00, 'ซื้อ', 'นายประชา รักงาน'),
('เก้าอี้สำนักงาน', 'เฟอร์นิเจอร์', 'CHAIR001', '2024-01-20', 2500.00, 'ห้องประชุม', 'ชำรุด', 15.00, 'ซื้อ', 'นางสุดา ขยันทำงาน'),
('โปรเจคเตอร์ Epson', 'อุปกรณ์นำเสนอ', 'EPSON001', '2023-11-15', 15000.00, 'ห้องประชุมใหญ่', 'ใช้งาน', 30.00, 'ซื้อ', 'นายวิชัย เก่งมาก');

-- Insert sample transfer data
INSERT INTO `transfers` (`asset_id`, `from_location`, `to_location`, `transfer_date`, `transfer_by`, `reason`) VALUES
(1, 'ห้องคอมพิวเตอร์ 2', 'ห้องคอมพิวเตอร์ 1', '2024-03-01', 'นายสมชาย ใจดี', 'ย้ายเพื่อใช้งานในห้องใหม่'),
(3, 'ห้องรองผู้อำนวยการ', 'ห้องผู้อำนวยการ', '2024-01-05', 'นายประชา รักงาน', 'เปลี่ยนตำแหน่งงาน');

-- Insert sample repair data
INSERT INTO `repairs` (`asset_id`, `request_date`, `description`, `status`, `requested_by`) VALUES
(4, '2024-07-15', 'เก้าอี้หักขา ต้องการซ่อมแซม', 'รอดำเนินการ', 'นางสุดา ขยันทำงาน'),
(2, '2024-06-20', 'เครื่องพิมพ์ติดกระดาษบ่อย', 'ซ่อมเสร็จแล้ว', 'นางสาวมาลี สวยงาม');

-- Insert sample survey data
INSERT INTO `annual_surveys` (`survey_year`, `asset_id`, `condition`, `surveyed_by`, `survey_date`) VALUES
(2024, 1, 'ดี', 'คณะกรรมการสำรวจ', '2024-12-01'),
(2024, 2, 'ดี', 'คณะกรรมการสำรวจ', '2024-12-01'),
(2024, 3, 'ดี', 'คณะกรรมการสำรวจ', '2024-12-01'),
(2024, 4, 'ชำรุด', 'คณะกรรมการสำรวจ', '2024-12-01'),
(2024, 5, 'ดี', 'คณะกรรมการสำรวจ', '2024-12-01');

-- Insert sample contract guarantee data
INSERT INTO `contract_guarantees`
(`asset_id`, `contract_number`, `guarantee_type`, `guarantee_amount`, `start_date`, `end_date`, `vendor_name`, `vendor_contact`, `status`)
VALUES
(1, 'CON2024001', 'หนังสือค้ำประกัน', 50000.00, '2024-01-01', '2024-12-31', 'ธนาคารกรุงเทพ', '02-000-0000', 'ใช้งาน'),
(2, 'CON2024002', 'เงินสดค้ำประกัน', 25000.00, '2024-02-01', '2025-01-31', 'บริษัท ABC จำกัด', '02-111-1111', 'ใช้งาน');

