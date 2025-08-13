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
     * ค้นหาค้ำประกันสัญญา
     */
    public function search_guarantees($keyword, $status = null, $type = null)
    {
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('contract_number', $keyword);
            $this->db->or_like('guarantee_provider', $keyword);
            $this->db->or_like('notes', $keyword);
            $this->db->group_end();
        }
        
        if ($status) {
            $this->db->where('status', $status);
        }
        
        if ($type) {
            $this->db->where('guarantee_type', $type);
        }
        
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('contract_guarantees');
        return $query->result_array();
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
}

