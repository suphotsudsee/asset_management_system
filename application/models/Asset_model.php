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
        $this->db->distinct();
        $this->db->select("asset_type");
        $this->db->order_by("asset_type", "ASC");
        $query = $this->db->get("assets");
        return array_column($query->result_array(), "asset_type");
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
     * นับจำนวนครุภัณฑ์ทั้งหมด
     */
    public function count_assets()
    {
        return $this->db->count_all('assets');
    }

    /**
     * นับจำนวนครุภัณฑ์ตามสถานะ
     */
    public function count_assets_by_status($status)
    {
        $this->db->where('status', $status);
        return $this->db->count_all_results('assets');
    }

    /**
     * มูลค่ารวมครุภัณฑ์
     */
    public function get_total_asset_value()
    {
        $this->db->select('SUM(purchase_price) as total_value');
        $query = $this->db->get('assets');
        $row = $query->row_array();
        return $row['total_value'] ? (float)$row['total_value'] : 0;
    }

    /**
     * ดึงรายการสถานที่แบบไม่ซ้ำ
     */
    public function get_distinct_locations()
    {
        $this->db->distinct();
        $this->db->select('current_location');
        $this->db->order_by('current_location', 'ASC');
        return $this->db->get('assets')->result_array();
    }

    /**
     * ดึงรายการประเภทครุภัณฑ์แบบไม่ซ้ำ
     */
    public function get_distinct_categories()
    {
        $this->db->distinct();
        $this->db->select('asset_type as category');
        $this->db->order_by('asset_type', 'ASC');
        return $this->db->get('assets')->result_array();
    }

    /**
     * ดึงข้อมูลครุภัณฑ์สำหรับรายงานสำรวจประจำปี
     */
    public function get_assets_for_survey($year, $location = null, $category = null, $status = null)
    {
        $this->db->select('a.asset_id, a.asset_id as asset_code, a.asset_name, a.asset_type as category, a.serial_number, a.current_location, a.responsible_person, a.purchase_price, a.status, s.survey_id');
        $this->db->from('assets a');
        $this->db->join('annual_surveys s', 'a.asset_id = s.asset_id AND s.survey_year = ' . (int)$year, 'left');
        $this->db->where('a.status !=', 'จำหน่ายแล้ว');
        if ($location) {
            $this->db->where('a.current_location', $location);
        }
        if ($category) {
            $this->db->where('a.asset_type', $category);
        }
        if ($status) {
            $this->db->where('a.status', $status);
        }
        $this->db->order_by('a.asset_name', 'ASC');
        $query = $this->db->get();
        $assets = $query->result_array();

        foreach ($assets as &$asset) {
            $depr = $this->calculate_depreciation($asset['asset_code']);
            $asset['accumulated_depreciation'] = $depr ? $depr['accumulated_depreciation'] : 0;
            $asset['book_value'] = $depr ? $depr['book_value'] : $asset['purchase_price'];
            $asset['survey_status'] = $asset['survey_id'] ? 'สำรวจแล้ว' : 'ยังไม่สำรวจ';
        }
        unset($asset);

        return $assets;
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
}
