<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transfer_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * ดึงข้อมูลการโอนย้ายทั้งหมด
     */
    public function get_all_transfers($limit = null, $offset = null)
    {
        $this->db->select('t.*, a.asset_name, a.serial_number, a.asset_type');
        $this->db->from('transfers t');
        $this->db->join('assets a', 't.asset_id = a.asset_id');
        $this->db->order_by('t.transfer_date', 'DESC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * ดึงข้อมูลการโอนย้ายตาม ID
     */
    public function get_transfer_by_id($transfer_id)
    {
        $this->db->select('t.*, a.asset_name, a.serial_number, a.asset_type');
        $this->db->from('transfers t');
        $this->db->join('assets a', 't.asset_id = a.asset_id');
        $this->db->where('t.transfer_id', $transfer_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * ดึงประวัติการโอนย้ายของครุภัณฑ์
     */
    public function get_transfers_by_asset($asset_id)
    {
        $this->db->where('asset_id', $asset_id);
        $this->db->order_by('transfer_date', 'DESC');
        $query = $this->db->get('transfers');
        return $query->result_array();
    }

    /**
     * เพิ่มข้อมูลการโอนย้าย
     */
    public function insert_transfer($data)
    {
        $this->db->trans_start();
        
        // บันทึกข้อมูลการโอนย้าย
        $this->db->insert('transfers', $data);
        
        // อัปเดตสถานที่ปัจจุบันในตารางครุภัณฑ์
        $this->db->where('asset_id', $data['asset_id']);
        $this->db->update('assets', array(
            'current_location' => $data['to_location'],
            'updated_at' => date('Y-m-d H:i:s')
        ));
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * อัปเดตข้อมูลการโอนย้าย
     */
    public function update_transfer($transfer_id, $data)
    {
        $this->db->trans_start();
        
        // ดึงข้อมูลการโอนย้ายเดิม
        $old_transfer = $this->get_transfer_by_id($transfer_id);
        
        // อัปเดตข้อมูลการโอนย้าย
        $this->db->where('transfer_id', $transfer_id);
        $this->db->update('transfers', $data);
        
        // อัปเดตสถานที่ปัจจุบันในตารางครุภัณฑ์ (ถ้าเปลี่ยนสถานที่ปลายทาง)
        if (isset($data['to_location']) && $data['to_location'] != $old_transfer['to_location']) {
            $this->db->where('asset_id', $old_transfer['asset_id']);
            $this->db->update('assets', array(
                'current_location' => $data['to_location'],
                'updated_at' => date('Y-m-d H:i:s')
            ));
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * ลบข้อมูลการโอนย้าย
     */
    public function delete_transfer($transfer_id)
    {
        $this->db->where('transfer_id', $transfer_id);
        return $this->db->delete('transfers');
    }

    /**
     * ค้นหาการโอนย้าย
     */
    public function search_transfers($keyword, $date_from = null, $date_to = null)
    {
        $this->db->select('t.*, a.asset_name, a.serial_number, a.asset_type');
        $this->db->from('transfers t');
        $this->db->join('assets a', 't.asset_id = a.asset_id');
        
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('a.asset_name', $keyword);
            $this->db->or_like('a.serial_number', $keyword);
            $this->db->or_like('t.from_location', $keyword);
            $this->db->or_like('t.to_location', $keyword);
            $this->db->or_like('t.transfer_by', $keyword);
            $this->db->group_end();
        }
        
        if ($date_from) {
            $this->db->where('t.transfer_date >=', $date_from);
        }
        
        if ($date_to) {
            $this->db->where('t.transfer_date <=', $date_to);
        }
        
        $this->db->order_by('t.transfer_date', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * ดึงสถิติการโอนย้าย
     */
    public function get_transfer_statistics($year = null)
    {
        $stats = array();
        
        if ($year) {
            $this->db->where('YEAR(transfer_date)', $year);
        }
        
        // จำนวนการโอนย้ายทั้งหมด
        $stats['total'] = $this->db->count_all_results('transfers');
        
        // การโอนย้ายตามเดือน (ถ้าระบุปี)
        if ($year) {
            $this->db->select('MONTH(transfer_date) as month, COUNT(*) as count');
            $this->db->where('YEAR(transfer_date)', $year);
            $this->db->group_by('MONTH(transfer_date)');
            $this->db->order_by('month', 'ASC');
            $query = $this->db->get('transfers');
            $stats['monthly'] = $query->result_array();
        }
        
        // สถานที่ที่มีการโอนย้ายมากที่สุด
        $this->db->select('to_location, COUNT(*) as count');
        $this->db->group_by('to_location');
        $this->db->order_by('count', 'DESC');
        $this->db->limit(5);
        if ($year) {
            $this->db->where('YEAR(transfer_date)', $year);
        }
        $query = $this->db->get('transfers');
        $stats['top_destinations'] = $query->result_array();
        
        return $stats;
    }

    /**
     * ดึงรายการสถานที่จากการโอนย้าย
     */
    public function get_transfer_locations()
    {
        $locations = array();
        
        // สถานที่ต้นทาง
        $this->db->select('DISTINCT from_location as location');
        $query = $this->db->get('transfers');
        foreach ($query->result_array() as $row) {
            $locations[] = $row['location'];
        }
        
        // สถานที่ปลายทาง
        $this->db->select('DISTINCT to_location as location');
        $query = $this->db->get('transfers');
        foreach ($query->result_array() as $row) {
            $locations[] = $row['location'];
        }
        
        // ลบข้อมูลซ้ำและเรียงลำดับ
        $locations = array_unique($locations);
        sort($locations);
        
        return $locations;
    }

    /**
     * ดึงข้อมูลการโอนย้ายล่าสุด
     */
    public function get_recent_transfers($limit = 10)
    {
        $this->db->select('t.*, a.asset_name, a.serial_number');
        $this->db->from('transfers t');
        $this->db->join('assets a', 't.asset_id = a.asset_id');
        $this->db->order_by('t.created_at', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * ดึงข้อมูลการโอนย้ายสำหรับรายงาน
     */
    public function get_transfers_for_report($date_from = null, $date_to = null, $location = null)
    {
        $this->db->select('t.*, a.asset_name, a.serial_number, a.asset_type, a.purchase_price');
        $this->db->from('transfers t');
        $this->db->join('assets a', 't.asset_id = a.asset_id');
        
        if ($date_from) {
            $this->db->where('t.transfer_date >=', $date_from);
        }
        
        if ($date_to) {
            $this->db->where('t.transfer_date <=', $date_to);
        }
        
        if ($location) {
            $this->db->group_start();
            $this->db->like('t.from_location', $location);
            $this->db->or_like('t.to_location', $location);
            $this->db->group_end();
        }
        
        $this->db->order_by('t.transfer_date', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * ตรวจสอบว่าครุภัณฑ์สามารถโอนย้ายได้หรือไม่
     */
    public function can_transfer_asset($asset_id)
    {
        $this->db->select('status');
        $this->db->where('asset_id', $asset_id);
        $query = $this->db->get('assets');
        $asset = $query->row_array();
        
        if (!$asset) {
            return false;
        }
        
        // ครุภัณฑ์ที่จำหน่ายแล้วไม่สามารถโอนย้ายได้
        return $asset['status'] !== 'จำหน่ายแล้ว';
    }

    /**
     * ดึงสถานที่ปัจจุบันของครุภัณฑ์
     */
    public function get_current_location($asset_id)
    {
        $this->db->select('current_location');
        $this->db->where('asset_id', $asset_id);
        $query = $this->db->get('assets');
        $result = $query->row_array();
        return $result ? $result['current_location'] : null;
    }
}

