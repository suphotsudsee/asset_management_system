<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Repair_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * ดึงข้อมูลการซ่อมแซมทั้งหมด
     */
    public function get_all_repairs($limit = null, $offset = null)
    {
        $this->db->select('r.*, a.asset_name, a.serial_number, a.asset_type, r.description');
        $this->db->from('repairs r');
        $this->db->join('assets a', 'r.asset_id = a.asset_id');
        $this->db->order_by('r.request_date', 'DESC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * ดึงข้อมูลการซ่อมแซมตาม ID
     */
    public function get_repair_by_id($repair_id)
    {
        $this->db->select("r.*, a.asset_name, a.serial_number, a.asset_type, a.current_location, r.description");
        $this->db->from('repairs r');
        $this->db->join('assets a', 'r.asset_id = a.asset_id');
        $this->db->where('r.repair_id', $repair_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * ดึงประวัติการซ่อมแซมของครุภัณฑ์
     */
    public function get_repairs_by_asset($asset_id)
    {
        $this->db->where('asset_id', $asset_id);
        $this->db->order_by('request_date', 'DESC');
        $query = $this->db->get('repairs');
        return $query->result_array();
    }

    /**
     * เพิ่มข้อมูลการซ่อมแซม
     */
    public function insert_repair($data)
    {
        $this->db->trans_start();
        
        // บันทึกข้อมูลการซ่อมแซม
        $this->db->insert('repairs', $data);
        
        // อัปเดตสถานะครุภัณฑ์เป็น "ซ่อมแซม" (ถ้าต้องการ)
        if (isset($data['update_asset_status']) && $data['update_asset_status']) {
            $this->db->where('asset_id', $data['asset_id']);
            $this->db->update('assets', array(
                'status' => 'ซ่อมแซม',
                'updated_at' => date('Y-m-d H:i:s')
            ));
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * อัปเดตข้อมูลการซ่อมแซม
     */
    public function update_repair($repair_id, $data)
    {
        $this->db->trans_start();
        
        // ดึงข้อมูลการซ่อมแซมเดิม
        $old_repair = $this->get_repair_by_id($repair_id);
        
        // อัปเดตข้อมูลการซ่อมแซม
        $this->db->where('repair_id', $repair_id);
        $this->db->update('repairs', $data);
        
        // อัปเดตสถานะครุภัณฑ์ตามสถานะการซ่อม
        if (isset($data['status']) && $data['status'] != $old_repair['status']) {
            $asset_status = 'ใช้งาน'; // ค่าเริ่มต้น
            
            switch ($data['status']) {
                case 'รอดำเนินการ':
                case 'กำลังซ่อม':
                    $asset_status = 'ซ่อมแซม';
                    break;
                case 'ซ่อมเสร็จแล้ว':
                    $asset_status = 'ใช้งาน';
                    break;
                case 'ไม่สามารถซ่อมได้':
                    $asset_status = 'ชำรุด';
                    break;
            }
            
            $this->db->where('asset_id', $old_repair['asset_id']);
            $this->db->update('assets', array(
                'status' => $asset_status,
                'updated_at' => date('Y-m-d H:i:s')
            ));
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * ลบข้อมูลการซ่อมแซม
     */
    public function delete_repair($repair_id)
    {
        $this->db->where('repair_id', $repair_id);
        return $this->db->delete('repairs');
    }

    /**
     * ค้นหาการซ่อมแซม
     */
    public function search_repairs($keyword, $status = null, $date_from = null, $date_to = null)
    {
        $this->db->select('r.*, a.asset_name, a.serial_number, a.asset_type, r.description');
        $this->db->from('repairs r');
        $this->db->join('assets a', 'r.asset_id = a.asset_id');
        
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('a.asset_name', $keyword);
            $this->db->or_like('a.serial_number', $keyword);
            $this->db->or_like("r.description", $keyword);
            $this->db->or_like('r.requested_by', $keyword);
            $this->db->or_like('r.repaired_by', $keyword);
            $this->db->group_end();
        }
        
        if ($status) {
            $this->db->where('r.status', $status);
        }
        
        if ($date_from) {
            $this->db->where('r.request_date >=', $date_from);
        }
        
        if ($date_to) {
            $this->db->where('r.request_date <=', $date_to);
        }
        
        $this->db->order_by('r.request_date', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * ดึงสถิติการซ่อมแซม
     */
    public function get_repair_statistics($year = null)
    {
        $stats = array();
        
        if ($year) {
            $this->db->where('YEAR(request_date)', $year);
        }
        
        // จำนวนการซ่อมแซมทั้งหมด
        $stats['total'] = $this->db->count_all_results('repairs');
        
        // การซ่อมแซมตามสถานะ
        $this->db->select('status, COUNT(*) as count');
        $this->db->group_by('status');
        $this->db->order_by('count', 'DESC');
        if ($year) {
            $this->db->where('YEAR(request_date)', $year);
        }
        $query = $this->db->get('repairs');
        $stats['status'] = $query->result_array();
        
        // ค่าใช้จ่ายในการซ่อมแซม
        $this->db->select('SUM(cost) as total_cost, AVG(cost) as average_cost');
        $this->db->where('cost IS NOT NULL');
        if ($year) {
            $this->db->where('YEAR(request_date)', $year);
        }
        $query = $this->db->get('repairs');
        $result = $query->row_array();
        $stats['total_cost'] = $result['total_cost'] ?: 0;
        $stats['average_cost'] = $result['average_cost'] ?: 0;
        
        // การซ่อมแซมตามเดือน (ถ้าระบุปี)
        if ($year) {
            $this->db->select('MONTH(request_date) as month, COUNT(*) as count, SUM(cost) as cost');
            $this->db->where('YEAR(request_date)', $year);
            $this->db->group_by('MONTH(request_date)');
            $this->db->order_by('month', 'ASC');
            $query = $this->db->get('repairs');
            $stats['monthly'] = $query->result_array();
        }
        
        // ครุภัณฑ์ที่ซ่อมบ่อยที่สุด
        $this->db->select('a.asset_name, a.serial_number, COUNT(*) as repair_count');
        $this->db->from('repairs r');
        $this->db->join('assets a', 'r.asset_id = a.asset_id');
        $this->db->group_by('r.asset_id');
        $this->db->order_by('repair_count', 'DESC');
        $this->db->limit(5);
        if ($year) {
            $this->db->where('YEAR(r.request_date)', $year);
        }
        $query = $this->db->get();
        $stats['frequent_repairs'] = $query->result_array();
        
        return $stats;
    }

    /**
     * ดึงข้อมูลการซ่อมแซมที่รอดำเนินการ
     */
    public function get_pending_repairs()
    {
        $this->db->select('r.*, a.asset_name, a.serial_number, a.asset_type, r.description');
        $this->db->from('repairs r');
        $this->db->join('assets a', 'r.asset_id = a.asset_id');
        $this->db->where('r.status', 'รอดำเนินการ');
        $this->db->order_by('r.request_date', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * ดึงข้อมูลการซ่อมแซมที่กำลังดำเนินการ
     */
    public function get_ongoing_repairs()
    {
        $this->db->select('r.*, a.asset_name, a.serial_number, a.asset_type, r.description');
        $this->db->from('repairs r');
        $this->db->join('assets a', 'r.asset_id = a.asset_id');
        $this->db->where('r.status', 'กำลังซ่อม');
        $this->db->order_by('r.request_date', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * นับจำนวนการซ่อมแซมตามสถานะ
     */
    public function count_repairs_by_status($status)
    {
        $this->db->where('status', $status);
        return $this->db->count_all_results('repairs');
    }

    /**
     * ดึงข้อมูลการซ่อมแซมล่าสุด
     */
    public function get_recent_repairs($limit = 10)
    {
        $this->db->select('r.*, a.asset_name, a.serial_number');
        $this->db->from('repairs r');
        $this->db->join('assets a', 'r.asset_id = a.asset_id');
        $this->db->order_by('r.created_at', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * ดึงข้อมูลการซ่อมแซมสำหรับรายงาน
     */
    public function get_repairs_for_report($date_from = null, $date_to = null, $status = null)
    {
        $this->db->select('r.*, a.asset_name, a.serial_number, a.asset_type, a.purchase_price');
        $this->db->from('repairs r');
        $this->db->join('assets a', 'r.asset_id = a.asset_id');
        
        if ($date_from) {
            $this->db->where('r.request_date >=', $date_from);
        }
        
        if ($date_to) {
            $this->db->where('r.request_date <=', $date_to);
        }
        
        if ($status) {
            $this->db->where('r.status', $status);
        }
        
        $this->db->order_by('r.request_date', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * อัปเดตสถานะการซ่อมแซม
     */
    public function update_repair_status($repair_id, $status, $repaired_by = null, $repair_date = null, $cost = null)
    {
        $data = array(
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        if ($repaired_by) {
            $data['repaired_by'] = $repaired_by;
        }
        
        if ($repair_date) {
            $data['repair_date'] = $repair_date;
        }
        
        if ($cost !== null) {
            $data['cost'] = $cost;
        }
        
        return $this->update_repair($repair_id, $data);
    }

    /**
     * คำนวณระยะเวลาการซ่อมแซม
     */
    public function calculate_repair_duration($repair_id)
    {
        $repair = $this->get_repair_by_id($repair_id);
        if (!$repair || !$repair['repair_date']) {
            return null;
        }
        
        $request_date = new DateTime($repair['request_date']);
        $repair_date = new DateTime($repair['repair_date']);
        $duration = $request_date->diff($repair_date);
        
        return $duration->days;
    }

    /**
     * ดึงรายการผู้ซ่อมแซม
     */
    public function get_repair_technicians()
    {
        $this->db->select('DISTINCT repaired_by');
        $this->db->where('repaired_by IS NOT NULL');
        $this->db->where('repaired_by !=', '');
        $this->db->order_by('repaired_by', 'ASC');
        $query = $this->db->get('repairs');
        return array_column($query->result_array(), 'repaired_by');
    }

    /**
     * ตรวจสอบว่าครุภัณฑ์มีการซ่อมแซมที่ยังไม่เสร็จหรือไม่
     */
    public function has_pending_repair($asset_id)
    {
        $this->db->where('asset_id', $asset_id);
        $this->db->where_in('status', array('รอดำเนินการ', 'กำลังซ่อม'));
        $query = $this->db->get('repairs');
        return $query->num_rows() > 0;
    }
}
