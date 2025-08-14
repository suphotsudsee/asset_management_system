<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guarantee_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * ดึงข้อมูลค้ำประกันสัญญาทั้งหมด
     */
    public function get_all_guarantees($limit = null, $offset = null)
    {
        $this->db->order_by('created_at', 'DESC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get('contract_guarantees');
        return $query->result_array();
    }

    /**
     * ดึงข้อมูลค้ำประกันสัญญาตาม ID
     */
    public function get_guarantee_by_id($guarantee_id)
    {
        $this->db->where('guarantee_id', $guarantee_id);
        $query = $this->db->get('contract_guarantees');
        return $query->row_array();
    }

    /**
     * ดึงข้อมูลค้ำประกันสัญญาตามเลขที่สัญญา
     */
    public function get_guarantee_by_contract($contract_number)
    {
        $this->db->where('contract_number', $contract_number);
        $query = $this->db->get('contract_guarantees');
        return $query->row_array();
    }

    /**
     * เพิ่มข้อมูลค้ำประกันสัญญา
     */
    public function insert_guarantee($data)
    {
        return $this->db->insert('contract_guarantees', $data);
    }

    /**
     * อัปเดตข้อมูลค้ำประกันสัญญา
     */
    public function update_guarantee($guarantee_id, $data)
    {
        $this->db->where('guarantee_id', $guarantee_id);
        return $this->db->update('contract_guarantees', $data);
    }

    /**
     * ลบข้อมูลค้ำประกันสัญญา
     */
    public function delete_guarantee($guarantee_id)
    {
        $this->db->where('guarantee_id', $guarantee_id);
        return $this->db->delete('contract_guarantees');
    }

   /**
 * ค้นหาค้ำประกันสัญญา + รองรับตัวกรองทั้งหมดที่ controller ใช้
 * @param string|null $keyword
 * @param string|null $status          เช่น 'ใช้งาน', 'หมดอายุ'
 * @param string|null $vendor          ค้นหาชื่อผู้จำหน่าย/ผู้ให้บริการ
 * @param string|null $expiry_filter   'expiring_30' | 'expired' | 'active' | null
 * @param string|null $type            (ตัวเลือก) ประเภทค้ำประกัน
 * @return array
 */
public function search_guarantees($keyword = null, $status = null, $vendor = null, $expiry_filter = null, $type = null)
{
    $today = date('Y-m-d');

    // เลือกฟิลด์ที่ view ต้องใช้ + ทำ alias ให้ครอบคลุมสคีมาต่าง ๆ
    $this->db->select("
        g.*,
        a.asset_name,
        a.serial_number,
        a.serial_number AS asset_code,
        COALESCE(g.vendor_name, g.guarantee_provider) AS vendor_name
    ", false);
    $this->db->from('contract_guarantees g');
    $this->db->join('assets a', 'a.asset_id = g.asset_id', 'left');

    // คำค้น
    if (!empty($keyword)) {
        $this->db->group_start()
            ->like('g.contract_number', $keyword)
            ->or_like('g.notes', $keyword)
            ->or_like('g.coverage_details', $keyword)
            ->or_like('a.asset_name', $keyword)
            ->or_like('g.vendor_name', $keyword)
            ->or_like('g.guarantee_provider', $keyword)
        ->group_end();
    }

    // สถานะ
    if (!empty($status)) {
        $this->db->where('g.status', $status);
    }

    // ผู้ขาย/ผู้ให้บริการ
    if (!empty($vendor)) {
        $this->db->group_start()
            ->like('g.vendor_name', $vendor)
            ->or_like('g.guarantee_provider', $vendor)
        ->group_end();
    }

    // ประเภทค้ำประกัน (ถ้ามีใช้)
    if (!empty($type)) {
        $this->db->where('g.guarantee_type', $type);
    }

    // ตัวกรองวันหมดอายุ
    if ($expiry_filter === 'expiring_30') {
        $this->db->where('g.status', 'ใช้งาน');
        $this->db->where('g.end_date >=', $today);
        $this->db->where('g.end_date <=', date('Y-m-d', strtotime('+30 days')));
    } elseif ($expiry_filter === 'expired') {
        $this->db->where('g.end_date <', $today);
    } elseif ($expiry_filter === 'active') {
        $this->db->where('g.status', 'ใช้งาน');
        $this->db->where('g.start_date <=', $today);
        $this->db->where('g.end_date >=', $today);
    }

    // จัดเรียง
    $this->db->order_by('g.end_date', 'ASC');
    $this->db->order_by('g.created_date', 'DESC'); // ถ้าสคีมาคุณใช้ created_at ให้เปลี่ยนชื่อนี้

    return $this->db->get()->result_array();
}


    /**
     * ดึงสถิติค้ำประกันสัญญา
     */
    public function get_guarantee_statistics()
    {
        $stats = array();
        
        // จำนวนค้ำประกันทั้งหมด
        $stats['total'] = $this->db->count_all('contract_guarantees');
        
        // ค้ำประกันตามสถานะ
        $this->db->select('status, COUNT(*) as count');
        $this->db->group_by('status');
        $this->db->order_by('count', 'DESC');
        $query = $this->db->get('contract_guarantees');
        $stats['status'] = $query->result_array();
        
        // ค้ำประกันตามประเภท
        $this->db->select('guarantee_type, COUNT(*) as count');
        $this->db->group_by('guarantee_type');
        $this->db->order_by('count', 'DESC');
        $query = $this->db->get('contract_guarantees');
        $stats['types'] = $query->result_array();
        
        // มูลค่าค้ำประกันรวม
        $this->db->select('SUM(guarantee_amount) as total_amount, AVG(guarantee_amount) as average_amount');
        $this->db->where('status', 'ใช้งาน');
        $query = $this->db->get('contract_guarantees');
        $result = $query->row_array();
        $stats['total_amount'] = $result['total_amount'] ?: 0;
        $stats['average_amount'] = $result['average_amount'] ?: 0;
        
        // ค้ำประกันที่ใกล้หมดอายุ (ภายใน 30 วัน)
        $this->db->where('end_date <=', date('Y-m-d', strtotime('+30 days')));
        $this->db->where('end_date >=', date('Y-m-d'));
        $this->db->where('status', 'ใช้งาน');
        $stats['expiring_soon'] = $this->db->count_all_results('contract_guarantees');
        
        return $stats;
    }

    /**
     * ดึงค้ำประกันที่ใกล้หมดอายุ
     */
    public function get_expiring_guarantees($days = 30)
    {
        $this->db->where('end_date <=', date('Y-m-d', strtotime("+{$days} days")));
        $this->db->where('end_date >=', date('Y-m-d'));
        $this->db->where('status', 'ใช้งาน');
        $this->db->order_by('end_date', 'ASC');
        $query = $this->db->get('contract_guarantees');
        return $query->result_array();
    }

    /**
     * ดึงค้ำประกันที่หมดอายุแล้ว
     */
    public function get_expired_guarantees()
    {
        $this->db->where('end_date <', date('Y-m-d'));
        $this->db->where('status', 'ใช้งาน');
        $this->db->order_by('end_date', 'DESC');
        $query = $this->db->get('contract_guarantees');
        return $query->result_array();
    }

    /**
     * อัปเดตสถานะค้ำประกันที่หมดอายุ
     */
    public function update_expired_guarantees()
    {
        $data = array(
            'status' => 'หมดอายุ',
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('end_date <', date('Y-m-d'));
        $this->db->where('status', 'ใช้งาน');
        return $this->db->update('contract_guarantees', $data);
    }

    /**
     * ดึงรายการประเภทค้ำประกัน
     */
    public function get_guarantee_types()
    {
        $this->db->select('DISTINCT guarantee_type');
        $this->db->order_by('guarantee_type', 'ASC');
        $query = $this->db->get('contract_guarantees');
        return array_column($query->result_array(), 'guarantee_type');
    }

    /**
     * ดึงรายการผู้ค้ำประกัน
     */
    public function get_guarantee_providers()
    {
        $this->db->select('DISTINCT guarantee_provider');
        $this->db->order_by('guarantee_provider', 'ASC');
        $query = $this->db->get('contract_guarantees');
        return array_column($query->result_array(), 'guarantee_provider');
    }

    /**
     * ตรวจสอบว่าเลขที่สัญญาซ้ำหรือไม่
     */
    public function check_contract_exists($contract_number, $exclude_id = null)
    {
        $this->db->where('contract_number', $contract_number);
        if ($exclude_id) {
            $this->db->where('guarantee_id !=', $exclude_id);
        }
        $query = $this->db->get('contract_guarantees');
        return $query->num_rows() > 0;
    }

    /**
     * ดึงข้อมูลค้ำประกันสำหรับรายงาน
     */
    public function get_guarantees_for_report($status = null, $type = null, $date_from = null, $date_to = null)
    {
        if ($status) {
            $this->db->where('status', $status);
        }
        
        if ($type) {
            $this->db->where('guarantee_type', $type);
        }
        
        if ($date_from) {
            $this->db->where('start_date >=', $date_from);
        }
        
        if ($date_to) {
            $this->db->where('end_date <=', $date_to);
        }
        
        $this->db->order_by('start_date', 'DESC');
        $query = $this->db->get('contract_guarantees');
        return $query->result_array();
    }

    /**
     * ดึงค้ำประกันที่ใช้งานอยู่
     */
    public function get_active_guarantees()
    {
        $this->db->where('status', 'ใช้งาน');
        $this->db->where('start_date <=', date('Y-m-d'));
        $this->db->where('end_date >=', date('Y-m-d'));
        $this->db->order_by('end_date', 'ASC');
        $query = $this->db->get('contract_guarantees');
        return $query->result_array();
    }

    /**
     * คำนวณจำนวนวันที่เหลือของค้ำประกัน
     */
    public function calculate_remaining_days($guarantee_id)
    {
        $guarantee = $this->get_guarantee_by_id($guarantee_id);
        if (!$guarantee) {
            return null;
        }
        
        $end_date = new DateTime($guarantee['end_date']);
        $current_date = new DateTime();
        
        if ($end_date < $current_date) {
            return 0; // หมดอายุแล้ว
        }
        
        $diff = $current_date->diff($end_date);
        return $diff->days;
    }

    /**
     * ดึงสรุปค้ำประกันตามผู้ค้ำประกัน
     */
    public function get_guarantee_summary_by_provider()
    {
        $this->db->select('guarantee_provider, COUNT(*) as count, SUM(guarantee_amount) as total_amount');
        $this->db->where('status', 'ใช้งาน');
        $this->db->group_by('guarantee_provider');
        $this->db->order_by('total_amount', 'DESC');
        $query = $this->db->get('contract_guarantees');
        return $query->result_array();
    }

    /**
     * ดึงค้ำประกันล่าสุด
     */
    public function get_recent_guarantees($limit = 10)
    {
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get('contract_guarantees');
        return $query->result_array();
    }

    /**
     * ตรวจสอบสถานะค้ำประกันอัตโนมัติ
     */
    public function check_guarantee_status($guarantee_id)
    {
        $guarantee = $this->get_guarantee_by_id($guarantee_id);
        if (!$guarantee) {
            return false;
        }
        
        $current_date = date('Y-m-d');
        $start_date = $guarantee['start_date'];
        $end_date = $guarantee['end_date'];
        
        if ($current_date < $start_date) {
            return 'ยังไม่เริ่ม';
        } elseif ($current_date > $end_date) {
            return 'หมดอายุ';
        } else {
            return 'ใช้งาน';
        }
    }

    /**
     * ดึงค้ำประกันตามช่วงวันที่
     */
    public function get_guarantees_by_date_range($start_date, $end_date)
    {
        $this->db->where('start_date <=', $end_date);
        $this->db->where('end_date >=', $start_date);
        $this->db->order_by('start_date', 'ASC');
        $query = $this->db->get('contract_guarantees');
        return $query->result_array();
    }

    /**
     * คำนวณมูลค่าค้ำประกันรวมที่ใช้งานอยู่
     */
    public function get_total_active_guarantee_amount()
    {
        $this->db->select('SUM(guarantee_amount) as total');
        $this->db->where('status', 'ใช้งาน');
        $query = $this->db->get('contract_guarantees');
        $result = $query->row_array();
        return $result['total'] ?: 0;
    }

    public function get_distinct_vendors()
    {
        $this->db->distinct();
        $this->db->select('vendor');
        $this->db->from('guarantees');
        $query = $this->db->get();
        return $query->result_array();
    }
  
    public function get_all($limit = null, $offset = null)
{
    $this->db->select('g.*, a.asset_name, a.serial_number, a.serial_number AS asset_code'); // ← alias
    $this->db->from('contract_guarantees g');
    $this->db->join('assets a', 'a.asset_id = g.asset_id', 'left');
    $this->db->order_by('g.end_date', 'DESC');
    if ($limit !== null) $this->db->limit($limit, (int)$offset);
    return $this->db->get()->result_array();
}
}

