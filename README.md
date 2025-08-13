# ระบบจัดการครุภัณฑ์ (Asset Management System)

ระบบจัดการครุภัณฑ์ที่พัฒนาด้วย CodeIgniter 3 + jQuery + Bootstrap สำหรับการจัดการครุภัณฑ์ขององค์กรอย่างครบครัน

## ฟีเจอร์หลัก

### 1. ระบบลงทะเบียนสินทรัพย์
- เพิ่ม แก้ไข ลบข้อมูลครุภัณฑ์
- ระบบค้นหาและกรองข้อมูล
- คำนวณค่าเสื่อมราคาอัตโนมัติ
- ส่งออกข้อมูลเป็น CSV

### 2. ระบบโอนย้ายครุภัณฑ์
- บันทึกการโอนย้ายครุภัณฑ์ระหว่างสถานที่
- ติดตามประวัติการย้าย
- อัปเดตสถานที่ครุภัณฑ์อัตโนมัติ

### 3. ระบบแทงจำหน่ายครุภัณฑ์
- บันทึกการแทงจำหน่ายครุภัณฑ์
- คำนวณมูลค่าตามบัญชี
- ระบบอนุมัติ/ไม่อนุมัติ
- พิมพ์ใบแทงจำหน่าย

### 4. ระบบหนังสือขออนุญาตซ่อมแซม
- สร้างหนังสือขออนุญาตซ่อมแซม
- ติดตามสถานะการซ่อมแซม
- บันทึกค่าใช้จ่ายการซ่อมแซม
- พิมพ์เอกสารทางการ

### 5. ระบบรายงานสำรวจครุภัณฑ์ประจำปี
- รายงานสำรวจครุภัณฑ์ประจำปี
- สถิติและกราฟแสดงผล
- ส่งออกรายงานเป็น CSV
- พิมพ์รายงานแบบเอกสารทางการ

### 6. ระบบรายงานค่าเสื่อมครุภัณฑ์
- คำนวณค่าเสื่อมราคาตามวิธีเส้นตรง
- รายงานค่าเสื่อมรายเดือน/รายปี
- กราฟแสดงแนวโน้มค่าเสื่อม
- ส่งออกและพิมพ์รายงาน

### 7. ระบบข้อมูลค้ำประกันสัญญา
- บันทึกข้อมูลค้ำประกันสัญญา
- ต่ออายุค้ำประกัน
- เคลมประกัน
- แจ้งเตือนค้ำประกันใกล้หมดอายุ

## เทคโนโลยีที่ใช้

- **Backend**: CodeIgniter 3.1.13
- **Frontend**: Bootstrap 4.6.0 + jQuery 3.6.0
- **Database**: MySQL/MariaDB
- **Charts**: Chart.js
- **Tables**: DataTables
- **Icons**: Font Awesome 5

## การติดตั้ง

### ความต้องการของระบบ
- PHP 7.4 หรือสูงกว่า
- MySQL 5.7 หรือ MariaDB 10.2 หรือสูงกว่า
- Web Server (Apache/Nginx)
- mod_rewrite สำหรับ Apache

### ขั้นตอนการติดตั้ง

1. **อัปโหลดไฟล์**
   ```bash
   # อัปโหลดไฟล์ทั้งหมดไปยัง web server
   # ตั้งค่า permission สำหรับโฟลเดอร์ที่จำเป็น
   chmod 755 application/logs
   chmod 755 application/cache
   ```

2. **สร้างฐานข้อมูล**
   ```sql
   # สร้างฐานข้อมูลใหม่
   CREATE DATABASE asset_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   
   # นำเข้าโครงสร้างฐานข้อมูล
   mysql -u username -p asset_management < database.sql
   ```

3. **ตั้งค่าการเชื่อมต่อฐานข้อมูล**
   ```php
   // แก้ไขไฟล์ application/config/database.php
   $db['default'] = array(
       'dsn'      => '',
       'hostname' => 'localhost',
       'username' => 'your_username',
       'password' => 'your_password',
       'database' => 'asset_management',
       'dbdriver' => 'mysqli',
       'dbprefix' => '',
       'pconnect' => FALSE,
       'db_debug' => (ENVIRONMENT !== 'production'),
       'cache_on' => FALSE,
       'cachedir' => '',
       'char_set' => 'utf8mb4',
       'dbcollat' => 'utf8mb4_unicode_ci',
       'swap_pre' => '',
       'encrypt'  => FALSE,
       'compress' => FALSE,
       'stricton' => FALSE,
       'failover' => array(),
       'save_queries' => TRUE
   );
   ```

4. **ตั้งค่า Base URL**
   ```php
   // แก้ไขไฟล์ application/config/config.php
   $config['base_url'] = 'http://yourdomain.com/asset_management/';
   ```

5. **ทดสอบระบบ**
   - เข้าใช้งานที่ `http://yourdomain.com/asset_management/test_system.php`
   - ตรวจสอบการทำงานของระบบ

## การใช้งาน

### หน้าหลัก (Dashboard)
- แสดงสถิติครุภัณฑ์ทั้งหมด
- กราฟแสดงข้อมูลต่างๆ
- กิจกรรมล่าสุด
- การแจ้งเตือนต่างๆ

### การจัดการครุภัณฑ์
1. **เพิ่มครุภัณฑ์ใหม่**: เมนู Assets > เพิ่มครุภัณฑ์
2. **ค้นหาครุภัณฑ์**: ใช้ช่องค้นหาและตัวกรอง
3. **แก้ไขข้อมูล**: คลิกปุ่มแก้ไขในรายการ
4. **ลบครุภัณฑ์**: คลิกปุ่มลบ (ต้องยืนยัน)

### การโอนย้าย
1. เลือกครุภัณฑ์ที่ต้องการโอนย้าย
2. ระบุสถานที่ปลายทาง
3. ใส่เหตุผลการโอนย้าย
4. บันทึกข้อมูล

### การแทงจำหน่าย
1. เลือกครุภัณฑ์ที่ต้องการจำหน่าย
2. ระบุเหตุผลการจำหน่าย
3. ระบุมูลค่าที่คาดว่าจะได้รับ
4. รอการอนุมัติ

### การซ่อมแซม
1. สร้างหนังสือขออนุญาตซ่อมแซม
2. ระบุรายละเอียดปัญหา
3. ประมาณการค่าใช้จ่าย
4. พิมพ์หนังสือเพื่อขออนุมัติ

### การดูรายงาน
1. เข้าเมนู Reports
2. เลือกประเภทรายงานที่ต้องการ
3. กำหนดช่วงวันที่และเงื่อนไข
4. ดูรายงานหรือส่งออกเป็น CSV

### การจัดการค้ำประกัน
1. **เพิ่มค้ำประกัน**: เลือกครุภัณฑ์และใส่ข้อมูลค้ำประกัน
2. **ต่ออายุ**: คลิกปุ่มต่ออายุในรายการค้ำประกัน
3. **เคลม**: คลิกปุ่มเคลมและใส่รายละเอียด
4. **ดูการแจ้งเตือน**: เช็คค้ำประกันที่ใกล้หมดอายุ

## โครงสร้างไฟล์

```
asset_management_system/
├── application/
│   ├── controllers/
│   │   ├── Dashboard.php
│   │   ├── Assets.php
│   │   ├── Transfers.php
│   │   ├── Disposals.php
│   │   ├── Repairs.php
│   │   ├── Reports.php
│   │   └── Guarantees.php
│   ├── models/
│   │   ├── Asset_model.php
│   │   ├── Transfer_model.php
│   │   ├── Disposal_model.php
│   │   ├── Repair_model.php
│   │   ├── Survey_model.php
│   │   ├── Depreciation_model.php
│   │   └── Guarantee_model.php
│   ├── views/
│   │   ├── templates/
│   │   ├── dashboard/
│   │   ├── assets/
│   │   ├── transfers/
│   │   ├── disposals/
│   │   ├── repairs/
│   │   ├── reports/
│   │   └── guarantees/
│   └── config/
├── assets/
│   ├── css/
│   ├── js/
│   └── fonts/
├── database.sql
├── test_system.php
└── README.md
```

## การสำรองข้อมูล

### สำรองฐานข้อมูล
```bash
mysqldump -u username -p asset_management > backup_$(date +%Y%m%d_%H%M%S).sql
```

### สำรองไฟล์
```bash
tar -czf asset_management_backup_$(date +%Y%m%d_%H%M%S).tar.gz asset_management_system/
```

## การแก้ไขปัญหา

### ปัญหาที่พบบ่อย

1. **ไม่สามารถเชื่อมต่อฐานข้อมูลได้**
   - ตรวจสอบการตั้งค่าใน `application/config/database.php`
   - ตรวจสอบว่าฐานข้อมูลถูกสร้างแล้ว
   - ตรวจสอบ username และ password

2. **หน้าเว็บแสดง 404 Error**
   - ตรวจสอบการตั้งค่า `base_url` ใน `application/config/config.php`
   - ตรวจสอบไฟล์ `.htaccess`
   - ตรวจสอบว่า mod_rewrite เปิดใช้งาน

3. **ไม่สามารถอัปโหลดไฟล์ได้**
   - ตรวจสอบ permission ของโฟลเดอร์
   - ตรวจสอบการตั้งค่า PHP (upload_max_filesize, post_max_size)

4. **กราฟไม่แสดงผล**
   - ตรวจสอบว่าไฟล์ Chart.js โหลดได้
   - ตรวจสอบ JavaScript Console สำหรับ error

## การพัฒนาเพิ่มเติม

### การเพิ่มฟีเจอร์ใหม่
1. สร้าง Controller ใหม่ใน `application/controllers/`
2. สร้าง Model ใหม่ใน `application/models/`
3. สร้าง View ใหม่ใน `application/views/`
4. เพิ่มเมนูใน template header
5. อัปเดตฐานข้อมูลตามต้องการ

### การปรับแต่ง UI
- แก้ไขไฟล์ `assets/css/custom.css`
- แก้ไขไฟล์ `assets/js/custom.js`
- แก้ไข template ใน `application/views/templates/`

## การรักษาความปลอดภัย

### ข้อแนะนำ
1. เปลี่ยน encryption_key ใน config.php
2. ตั้งค่า database ให้ใช้ user ที่มีสิทธิ์จำกัด
3. ใช้ HTTPS สำหรับ production
4. สำรองข้อมูลอย่างสม่ำเสมอ
5. อัปเดต CodeIgniter และ dependencies

## การสนับสนุน

หากพบปัญหาหรือต้องการความช่วยเหลือ:
1. ตรวจสอบไฟล์ log ใน `application/logs/`
2. ใช้ไฟล์ `test_system.php` เพื่อตรวจสอบระบบ
3. ตรวจสอบเอกสารนี้สำหรับคำแนะนำ

## เวอร์ชัน

- **เวอร์ชัน 1.0.0** - ระบบพื้นฐานครบครัน
- รองรับการจัดการครุภัณฑ์แบบครบวงจร
- รองรับการรายงานและสถิติ
- รองรับการจัดการค้ำประกันสัญญา

---

**หมายเหตุ**: ระบบนี้พัฒนาขึ้นเพื่อใช้งานในองค์กรขนาดกลางถึงใหญ่ สามารถปรับแต่งและขยายฟังก์ชันได้ตามความต้องการ

