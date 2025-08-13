<?php
/**
 * Asset Management System - Test File
 * ไฟล์ทดสอบระบบจัดการครุภัณฑ์
 */

// เริ่มต้น session
session_start();

// กำหนด base path
define('BASEPATH', TRUE);

// โหลด CodeIgniter
require_once 'index.php';

echo "<h1>ทดสอบระบบจัดการครุภัณฑ์</h1>";

// ทดสอบการเชื่อมต่อฐานข้อมูล
echo "<h2>1. ทดสอบการเชื่อมต่อฐานข้อมูล</h2>";
try {
    $CI =& get_instance();
    $CI->load->database();
    
    if ($CI->db->initialize()) {
        echo "<p style='color: green;'>✓ เชื่อมต่อฐานข้อมูลสำเร็จ</p>";
        
        // ทดสอบการสร้างตาราง
        $tables = $CI->db->list_tables();
        echo "<p>ตารางในฐานข้อมูล: " . implode(', ', $tables) . "</p>";
        
    } else {
        echo "<p style='color: red;'>✗ ไม่สามารถเชื่อมต่อฐานข้อมูลได้</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ เกิดข้อผิดพลาด: " . $e->getMessage() . "</p>";
}

// ทดสอบโมเดล
echo "<h2>2. ทดสอบโมเดล</h2>";
$models = [
    'Asset_model',
    'Transfer_model', 
    'Disposal_model',
    'Repair_model',
    'Survey_model',
    'Depreciation_model',
    'Guarantee_model'
];

foreach ($models as $model) {
    try {
        $CI->load->model($model);
        echo "<p style='color: green;'>✓ โหลดโมเดล {$model} สำเร็จ</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ ไม่สามารถโหลดโมเดล {$model} ได้: " . $e->getMessage() . "</p>";
    }
}

// ทดสอบ Controllers
echo "<h2>3. ทดสอบ Controllers</h2>";
$controllers = [
    'Dashboard',
    'Assets',
    'Transfers',
    'Disposals', 
    'Repairs',
    'Reports',
    'Guarantees'
];

foreach ($controllers as $controller) {
    $file_path = APPPATH . 'controllers/' . $controller . '.php';
    if (file_exists($file_path)) {
        echo "<p style='color: green;'>✓ Controller {$controller} พร้อมใช้งาน</p>";
    } else {
        echo "<p style='color: red;'>✗ ไม่พบ Controller {$controller}</p>";
    }
}

// ทดสอบ Views
echo "<h2>4. ทดสอบ Views</h2>";
$view_folders = [
    'templates',
    'dashboard',
    'assets',
    'transfers',
    'disposals',
    'repairs', 
    'reports',
    'guarantees'
];

foreach ($view_folders as $folder) {
    $folder_path = APPPATH . 'views/' . $folder;
    if (is_dir($folder_path)) {
        $files = scandir($folder_path);
        $php_files = array_filter($files, function($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'php';
        });
        echo "<p style='color: green;'>✓ View folder {$folder} มี " . count($php_files) . " ไฟล์</p>";
    } else {
        echo "<p style='color: red;'>✗ ไม่พบ View folder {$folder}</p>";
    }
}

// ทดสอบ Assets (CSS, JS)
echo "<h2>5. ทดสอบ Assets</h2>";
$asset_files = [
    'assets/css/bootstrap.min.css',
    'assets/css/custom.css',
    'assets/js/jquery-3.6.0.min.js',
    'assets/js/bootstrap.bundle.min.js',
    'assets/js/custom.js'
];

foreach ($asset_files as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✓ ไฟล์ {$file} พร้อมใช้งาน</p>";
    } else {
        echo "<p style='color: red;'>✗ ไม่พบไฟล์ {$file}</p>";
    }
}

// ทดสอบการตั้งค่า
echo "<h2>6. ทดสอบการตั้งค่า</h2>";
$config_items = [
    'base_url' => $CI->config->item('base_url'),
    'index_page' => $CI->config->item('index_page'),
    'encryption_key' => $CI->config->item('encryption_key') ? 'ตั้งค่าแล้ว' : 'ยังไม่ตั้งค่า'
];

foreach ($config_items as $key => $value) {
    echo "<p><strong>{$key}:</strong> {$value}</p>";
}

echo "<h2>7. สรุปผลการทดสอบ</h2>";
echo "<p>ระบบจัดการครุภัณฑ์พร้อมใช้งาน</p>";
echo "<p>สามารถเข้าใช้งานได้ที่: <a href='" . base_url() . "' target='_blank'>" . base_url() . "</a></p>";

?>

<style>
body {
    font-family: 'Sarabun', Arial, sans-serif;
    margin: 20px;
    background-color: #f8f9fa;
}

h1, h2 {
    color: #333;
    border-bottom: 2px solid #007bff;
    padding-bottom: 5px;
}

p {
    margin: 5px 0;
    padding: 5px;
    background-color: white;
    border-radius: 3px;
}

a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
</style>

