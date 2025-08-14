<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Asset_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * ดึงข้อมูลครุภัณฑ์ทั้งหมด
     */
    public function get_all_assets($limit = null, $offset = null)
    {
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by("created_at", "DESC");
        $query = $this->db->get("assets");
        return $query->result_array();
    }

    /**
     * ดึงข้อมูลครุภัณฑ์ตาม ID
     */
    public function get_asset_by_id($asset_id)
    {
        $this->db->where("asset_id", $asset_id);
        $query = $this->db->get("assets");
        return $query->row_array();
    }

    /**
     * ค้นหาครุภัณฑ์
     */
    public function search_assets($keyword, $type = null, $status = null, $location = null)
    {
        $this->db->like("asset_name", $keyword);
        $this->db->or_like("serial_number", $keyword);
        $this->db->or_like("responsible_person", $keyword);
        
        if ($type) {
            $this->db->where("asset_type", $type);
        }
        
        if ($status) {
            $this->db->where("status", $status);
        }
        
        if ($location) {
            $this->db->like("current_location", $location);
        }
        
        $this->db->order_by("created_at", "DESC");
        $query = $this->db->get("assets");
        return $query->result_array();
    }

    /**
     * เพิ่มครุภัณฑ์ใหม่
     */
    public function insert_asset($data)
    {
        return $this->db->insert("assets", $data);
    }

    /**
     * อัปเดตข้อมูลครุภัณฑ์
     */
    public function update_asset($asset_id, $data)
    {
        $this->db->where("asset_id", $asset_id);
        return $this->db->update("assets", $data);
    }

    /**
     * ลบครุภัณฑ์
     */
    public function delete_asset($asset_id)
    {
        $this->db->where("asset_id", $asset_id);
        return $this->db->delete("assets");
    }

    /**
     * ดึงข้อมูลสถิติครุภัณฑ์
     */
    public function get_asset_statistics()
    {
        $stats = array();
        
        // จำนวนครุภัณฑ์ทั้งหมด
        $stats["total"] = $this->db->count_all("assets");
        
        // จำนวนครุภัณฑ์ตามสถานะ
        $this->db->select("status, COUNT(*) as count");
        $this->db->group_by("status");
        $query = $this->db->get("assets");
        $status_counts = $query->result_array();
        
        foreach ($status_counts as $row) {
            $stats["status"][$row["status"]] = $row["count"];
        }
        
        // จำนวนครุภัณฑ์ตามประเภท
        $this->db->select("asset_type, COUNT(*) as count");
        $this->db->group_by("asset_type");
        $this->db->order_by("count", "DESC");
        $this->db->limit(5);
        $query = $this->db->get("assets");
        $stats["types"] = $query->result_array();
        
        // มูลค่ารวมครุภัณฑ์
        $this->db->select("SUM(purchase_price) as total_value");
        $query = $this->db->get("assets");
        $result = $query->row_array();
        $stats["total_value"] = $result["total_value"] ?: 0;
        
        return $stats;
    }

    /**
     * ดึงรายการประเภทครุภัณฑ์
     */
    public function get_asset_types()
    {
        $this->db->order_by("type_name", "ASC");
        $query = $this->db->get("asset_types");
        return array_column($query->result_array(), "type_name");
    }

    /**
     * ดึงรายการสถานที่
     */
    public function get_locations()
    {
        $this->db->distinct();
        $this->db->select("current_location");
        $this->db->order_by("current_location", "ASC");
        $query = $this->db->get("assets");
        return array_column($query->result_array(), "current_location");
    }

    /**
     * ดึงข้อมูลครุภัณฑ์ที่ใช้งานได้
     */
    public function get_active_assets()
    {
        $this->db->where("status", "ใช้งาน");
        $this->db->order_by("asset_name", "ASC");
        $query = $this->db->get("assets");
        return $query->result_array();
    }

    /**
     * ดึงข้อมูลครุภัณฑ์ที่ต้องซ่อมแซม
     */
    public function get_assets_need_repair()
    {
        $this->db->where("status", "ชำรุด");
        $this->db->order_by("updated_at", "DESC");
        $query = $this->db->get("assets");
        return $query->result_array();
    }

    /**
     * อัปเดตสถานะครุภัณฑ์
     */
    public function update_asset_status($asset_id, $status)
    {
        $data = array(
            "status" => $status,
            "updated_at" => date("Y-m-d H:i:s")
        );
        $this->db->where("asset_id", $asset_id);
        return $this->db->update("assets", $data);
    }

    /**
     * อัปเดตสถานที่ครุภัณฑ์
     */
    public function update_asset_location($asset_id, $location)
    {
        $data = array(
            "current_location" => $location,
            "updated_at" => date("Y-m-d H:i:s")
        );
        $this->db->where("asset_id", $asset_id);
        return $this->db->update("assets", $data);
    }

    /**
     * ตรวจสอบว่า Serial Number ซ้ำหรือไม่
     */
    public function check_serial_exists($serial_number, $exclude_id = null)
    {
        $this->db->where("serial_number", $serial_number);
        if ($exclude_id) {
            $this->db->where("asset_id !=", $exclude_id);
        }
        $query = $this->db->get("assets");
        return $query->num_rows() > 0;
    }

    /**
     * ดึงข้อมูลครุภัณฑ์สำหรับรายงาน
     */
    public function get_assets_for_report($year = null, $type = null, $status = null)
    {
        if ($year) {
            $this->db->where("YEAR(purchase_date)", $year);
        }
        
        if ($type) {
            $this->db->where("asset_type", $type);
        }
        
        if ($status) {
            $this->db->where("status", $status);
        }
        
        $this->db->order_by("purchase_date", "DESC");
        $query = $this->db->get("assets");
        return $query->result_array();
    }

    /**
     * คำนวณค่าเสื่อมราคา
     */
    public function calculate_depreciation($asset_id, $as_of_date = null)
    {
        if (!$as_of_date) {
            $as_of_date = date("Y-m-d");
        }
        
        $asset = $this->get_asset_by_id($asset_id);
        if (!$asset) {
            return false;
        }
        
        $purchase_date = new DateTime($asset["purchase_date"]);
        $current_date = new DateTime($as_of_date);
        $years = $purchase_date->diff($current_date)->y;
        
        $annual_depreciation = $asset["purchase_price"] * ($asset["depreciation_rate"] / 100);
        $accumulated_depreciation = $annual_depreciation * $years;
        $book_value = $asset["purchase_price"] - $accumulated_depreciation;
        
        // ป้องกันค่าติดลบ
        if ($book_value < 0) {
            $book_value = 0;
            $accumulated_depreciation = $asset["purchase_price"];
        }
        
        return array(
            "purchase_price" => $asset["purchase_price"],
            "annual_depreciation" => $annual_depreciation,
            "accumulated_depreciation" => $accumulated_depreciation,
            "book_value" => $book_value,
            "years" => $years
        );
    }
    /**
     * ดึงข้อมูลครุภัณฑ์ที่สามารถซ่อมแซมได้
     */
    public function get_repairable_assets()
    {
        $this->db->where_in("status", array("ชำรุด", "รอซ่อม")); // เพิ่มสถานะที่ถือว่าซ่อมแซมได้
        $this->db->order_by("asset_name", "ASC");
        $query = $this->db->get("assets");
        return $query->result_array();
    }

    /**
     * ดึงข้อมูลครุภัณฑ์ที่สามารถจำหน่ายได้
     */
    public function get_disposable_assets()
    {
        $this->db->where_in("status", array("ชำรุด", "ไม่ใช้งาน", "เสื่อมสภาพ")); // เพิ่มสถานะที่ถือว่าสามารถจำหน่ายได้
        $this->db->order_by("asset_name", "ASC");
        $query = $this->db->get("assets");
        return $query->result_array();
    }

    /**
     * ดึงข้อมูลครุภัณฑ์ที่สามารถโอนย้ายได้
     */
    public function get_transferable_assets()
    {
        $this->db->where_in("status", array("ใช้งาน", "ว่าง")); // เพิ่มสถานะที่ถือว่าสามารถโอนย้ายได้
        $this->db->order_by("asset_name", "ASC");
        $query = $this->db->get("assets");
        return $query->result_array();
    }

    // application/models/Asset_model.php
    public function count_assets()
    {
        return $this->db->count_all('assets'); // สมมติชื่อตารางว่า assets
    }

    /**
     * นับจำนวนครุภัณฑ์ตามสถานะ
     * @param string $status สถานะ (เช่น "ใช้งาน", "ชำรุด" ฯลฯ)
     * @return int
     */
    public function count_assets_by_status($status)
    {
        $this->db->where('status', $status); // สมมติชื่อคอลัมน์คือ status
        return $this->db->count_all_results('assets'); // สมมติชื่อตารางคือ assets
    }

    /**
     * นับจำนวนรายการซ่อมแซมตามสถานะ
     * @param string $status สถานะ (เช่น "รอดำเนินการ", "เสร็จสิ้น", "ยกเลิก")
     * @return int
     */
    public function count_repairs_by_status($status)
    {
        $this->db->where('status', $status); // สมมติชื่อคอลัมน์คือ status
        return $this->db->count_all_results('repairs'); // สมมติชื่อตารางคือ repairs
    }

    /**
     * นับจำนวนรายการจำหน่ายตามสถานะ
     * @param string $status สถานะ (เช่น "รอดำเนินการ", "เสร็จสิ้น", "ยกเลิก")
     * @return int
     */
    public function count_disposals_by_status($status)
    {
        $this->db->where('status', $status); // สมมติชื่อคอลัมน์คือ status
        return $this->db->count_all_results('disposals'); // สมมติชื่อตารางคือ disposals
    }

    public function get_total_asset_value()
    {
        $this->db->select_sum('purchase_price');
        $query = $this->db->get('assets');
        $result = $query->row_array();
        return $result['purchase_price'] ?: 0;
    }

    /**
     * ดึงรายการครุภัณฑ์สำหรับปีสำรวจที่กำหนด
     * @param string|int $survey_year
     * @return array
     */
    public function get_assets_for_survey($year)
{
    $this->db->where('YEAR(purchase_date)', $year);
    $query = $this->db->get('assets');
    return $query->result_array();
}



    

    public function get_all()
{
    return $this->db->order_by('asset_name','ASC')->get('assets')->result();
}

public function get_assets_without_active_guarantee()
{
    $today = date('Y-m-d');

    $this->db->select('a.asset_id, a.asset_name, a.serial_number, a.serial_number AS asset_code'); // ← เพิ่ม alias
    $this->db->from('assets a');

    if ($this->db->table_exists('contract_guarantees') && $this->db->field_exists('asset_id', 'contract_guarantees')) {
        $join = "g.asset_id = a.asset_id
                 AND g.status = 'ใช้งาน'
                 AND g.start_date <= ".$this->db->escape($today)."
                 AND g.end_date   >= ".$this->db->escape($today);
        $this->db->join('contract_guarantees g', $join, 'left', false);
        $this->db->where('g.guarantee_id IS NULL', null, false);
    }

    $this->db->order_by('a.asset_name', 'ASC');
    return $this->db->get()->result_array();
}

public function get_assets_for_guarantee_edit($current_asset_id)
{
    $list = $this->get_assets_without_active_guarantee();

    if ($current_asset_id) {
        $hasCurrent = false;
        foreach ($list as $row) {
            if ((int)$row['asset_id'] === (int)$current_asset_id) { $hasCurrent = true; break; }
        }
        if (!$hasCurrent) {
            $current = $this->db->select('asset_id, asset_name, serial_number, serial_number AS asset_code') // ← alias
                                ->from('assets')
                                ->where('asset_id', $current_asset_id)
                                ->get()->row_array();
            if ($current) array_unshift($list, $current);
        }
    }
    return $list;
}
/* ================= Helpers (เลือกคอลัมน์ให้เข้ากับสคีมาจริง) ================= */
private function _col(array $candidates, $table = 'assets')
{
    foreach ($candidates as $c) {
        if ($this->db->field_exists($c, $table)) return $c;
    }
    return null;
}

/* ================= Queries for Reports::asset_status() ================= */

/**
 * ดึงครุภัณฑ์ตามตัวกรอง (สถานที่, ประเภท, สถานะ, ปี)
 * คืนฟิลด์มาตรฐานพร้อม alias: category, current_location, asset_code
 */
public function get_assets_by_filters($location = null, $category = null, $status = null, $year = null)
{
    $table     = 'assets';
    $locCol    = $this->_col(['current_location','location','room','department'], $table);
    $catCol    = $this->_col(['category','asset_type','type'], $table);
    $statusCol = $this->_col(['status','asset_status'], $table);
    $dateCol   = $this->_col(['purchase_date','acquisition_date','created_at','created_date'], $table);

    // select เบื้องต้น + alias ฟิลด์ที่ view ใช้
    $this->db->select('a.*');
    if ($catCol)    $this->db->select("a.$catCol   AS category", false);
    if ($locCol)    $this->db->select("a.$locCol   AS current_location", false);
    // alias asset_code จาก serial_number (กัน view เก่าเรียก asset_code)
    if ($this->db->field_exists('serial_number', $table)) {
        $this->db->select('a.serial_number AS asset_code', false);
    }

    $this->db->from($table.' a');

    if ($location !== null && $locCol)   $this->db->where("a.$locCol", $location);
    if ($category !== null && $catCol)   $this->db->where("a.$catCol", $category);
    if ($status   !== null && $statusCol)$this->db->where("a.$statusCol", $status);

    if (!empty($year) && $dateCol) {
        $this->db->where("YEAR(a.$dateCol)", (int)$year);
    }

    $this->db->order_by('a.asset_name', 'ASC');
    return $this->db->get()->result_array();
}

/** นับจำนวนต่อสถานะ (สำหรับกราฟ/การ์ด) */
public function get_asset_status_statistics($year = null)
{
    $table     = 'assets';
    $statusCol = $this->_col(['status','asset_status'], $table);
    $dateCol   = $this->_col(['purchase_date','acquisition_date','created_at','created_date'], $table);

    if (!$statusCol) return []; // ไม่มีคอลัมน์สถานะก็จบ

    $this->db->select("a.$statusCol AS status, COUNT(*) AS count", false)
             ->from($table.' a')
             ->group_by("a.$statusCol")
             ->order_by('count', 'DESC');

    if (!empty($year) && $dateCol) $this->db->where("YEAR(a.$dateCol)", (int)$year);

    return $this->db->get()->result_array();
}

/** นับจำนวนต่อสถานที่ */
public function get_asset_location_statistics($year = null)
{
    $table   = 'assets';
    $locCol  = $this->_col(['current_location','location','room','department'], $table);
    $dateCol = $this->_col(['purchase_date','acquisition_date','created_at','created_date'], $table);

    if (!$locCol) return [];

    $this->db->select("a.$locCol AS location, COUNT(*) AS count", false)
             ->from($table.' a')
             ->group_by("a.$locCol")
             ->order_by('count','DESC');

    if (!empty($year) && $dateCol) $this->db->where("YEAR(a.$dateCol)", (int)$year);

    return $this->db->get()->result_array();
}

/** นับจำนวนต่อประเภท */
public function get_asset_category_statistics($year = null)
{
    $table   = 'assets';
    $catCol  = $this->_col(['category','asset_type','type'], $table);
    $dateCol = $this->_col(['purchase_date','acquisition_date','created_at','created_date'], $table);

    if (!$catCol) return [];

    $this->db->select("a.$catCol AS category, COUNT(*) AS count", false)
             ->from($table.' a')
             ->group_by("a.$catCol")
             ->order_by('count','DESC');

    if (!empty($year) && $dateCol) $this->db->where("YEAR(a.$dateCol)", (int)$year);

    return $this->db->get()->result_array();
}

/** รายชื่อสถานที่ (distinct) */
public function get_distinct_locations($year = null)
{
    $table   = 'assets';
    $locCol  = $this->_col(['current_location','location','room','department'], $table);
    $dateCol = $this->_col(['purchase_date','acquisition_date','created_at','created_date'], $table);

    if (!$locCol) return [];

    $this->db->distinct()->select("a.$locCol AS location", false)->from($table.' a');
    if (!empty($year) && $dateCol) $this->db->where("YEAR(a.$dateCol)", (int)$year);
    $this->db->order_by('location','ASC');
    $rows = $this->db->get()->result_array();

    // คืนเป็น array simple ถ้าอยากใช้กับ dropdown ได้สะดวก
    return array_map(function($r){ return $r['location']; }, $rows);
}

/** รายชื่อประเภท (distinct) */
public function get_distinct_categories($year = null)
{
    $table   = 'assets';
    $catCol  = $this->_col(['category','asset_type','type'], $table);
    $dateCol = $this->_col(['purchase_date','acquisition_date','created_at','created_date'], $table);

    if (!$catCol) return [];

    $this->db->distinct()->select("a.$catCol AS category", false)->from($table.' a');
    if (!empty($year) && $dateCol) $this->db->where("YEAR(a.$dateCol)", (int)$year);
    $this->db->order_by('category','ASC');
    $rows = $this->db->get()->result_array();

    return array_map(function($r){ return $r['category']; }, $rows);
}


}