<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Disposal_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * ดึงข้อมูลการจำหน่ายทั้งหมด
     */
    public function get_all_disposals($limit = null, $offset = null)
    {
        $this->db->select('d.*, a.asset_name, a.serial_number, a.asset_type, a.purchase_price');
        $this->db->from('disposals d');
        $this->db->join('assets a', 'd.asset_id = a.asset_id');
        $this->db->order_by('d.disposal_date', 'DESC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * ดึงข้อมูลการจำหน่ายตาม ID
     */
    public function get_disposal_by_id($disposal_id)
    {
        $this->db->select('d.*, a.asset_name, a.serial_number, a.asset_type, a.purchase_price, a.purchase_date');
        $this->db->from('disposals d');
        $this->db->join('assets a', 'd.asset_id = a.asset_id');
        $this->db->where('d.disposal_id', $disposal_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * ดึงข้อมูลการจำหน่ายของครุภัณฑ์
     */
    public function get_disposal_by_asset($asset_id)
    {
        $this->db->where('asset_id', $asset_id);
        $query = $this->db->get('disposals');
        return $query->row_array();
    }

    /**
     * เพิ่มข้อมูลการจำหน่าย
     */
    public function insert_disposal($data)
    {
        $this->db->trans_start();
        
        // บันทึกข้อมูลการจำหน่าย
        $this->db->insert('disposals', $data);
        
        // อัปเดตสถานะครุภัณฑ์เป็น "จำหน่ายแล้ว"
        $this->db->where('asset_id', $data['asset_id']);
        $this->db->update('assets', array(
            'status' => 'จำหน่ายแล้ว',
            'updated_at' => date('Y-m-d H:i:s')
        ));
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * อัปเดตข้อมูลการจำหน่าย
     */
    public function update_disposal($disposal_id, $data)
    {
        $this->db->where('disposal_id', $disposal_id);
        return $this->db->update('disposals', $data);
    }

    /**
     * ลบข้อมูลการจำหน่าย
     */
    public function delete_disposal($disposal_id)
    {
        $this->db->trans_start();
        
        // ดึงข้อมูลการจำหน่าย
        $disposal = $this->get_disposal_by_id($disposal_id);
        
        // ลบข้อมูลการจำหน่าย
        $this->db->where('disposal_id', $disposal_id);
        $this->db->delete('disposals');
        
        // เปลี่ยนสถานะครุภัณฑ์กลับเป็น "ใช้งาน" หรือ "ชำรุด"
        if ($disposal) {
            $this->db->where('asset_id', $disposal['asset_id']);
            $this->db->update('assets', array(
                'status' => 'ใช้งาน', // หรือสามารถกำหนดสถานะอื่นตามต้องการ
                'updated_at' => date('Y-m-d H:i:s')
            ));
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * ค้นหาการจำหน่าย
     */
    public function search_disposals($keyword, $date_from = null, $date_to = null, $method = null)
    {
        $this->db->select('d.*, a.asset_name, a.serial_number, a.asset_type, a.purchase_price');
        $this->db->from('disposals d');
        $this->db->join('assets a', 'd.asset_id = a.asset_id');
        
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('a.asset_name', $keyword);
            $this->db->or_like('a.serial_number', $keyword);
            $this->db->or_like('d.disposal_by', $keyword);
            $this->db->or_like('d.reason', $keyword);
            $this->db->group_end();
        }
        
        if ($date_from) {
            $this->db->where('d.disposal_date >=', $date_from);
        }
        
        if ($date_to) {
            $this->db->where('d.disposal_date <=', $date_to);
        }
        
        if ($method) {
            $this->db->where('d.disposal_method', $method);
        }
        
        $this->db->order_by('d.disposal_date', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * ดึงสถิติการจำหน่าย
     */
    public function get_disposal_statistics($year = null)
    {
        $stats = array();
        
        if ($year) {
            $this->db->where('YEAR(disposal_date)', $year);
        }
        
        // จำนวนการจำหน่ายทั้งหมด
        $stats['total'] = $this->db->count_all_results('disposals');
        
        // การจำหน่ายตามวิธีการ
        $this->db->select('disposal_method, COUNT(*) as count');
        $this->db->group_by('disposal_method');
        $this->db->order_by('count', 'DESC');
        if ($year) {
            $this->db->where('YEAR(disposal_date)', $year);
        }
        $query = $this->db->get('disposals');
        $stats['methods'] = $query->result_array();
        
        // มูลค่าการจำหน่าย
        $this->db->select('SUM(disposal_price) as total_disposal_value, SUM(a.purchase_price) as total_purchase_value');
        $this->db->from('disposals d');
        $this->db->join('assets a', 'd.asset_id = a.asset_id');
        if ($year) {
            $this->db->where('YEAR(d.disposal_date)', $year);
        }
        $query = $this->db->get();
        $result = $query->row_array();
        $stats['total_disposal_value'] = $result['total_disposal_value'] ?: 0;
        $stats['total_purchase_value'] = $result['total_purchase_value'] ?: 0;
        
        // การจำหน่ายตามเดือน (ถ้าระบุปี)
        if ($year) {
            $this->db->select('MONTH(disposal_date) as month, COUNT(*) as count, SUM(disposal_price) as value');
            $this->db->where('YEAR(disposal_date)', $year);
            $this->db->group_by('MONTH(disposal_date)');
            $this->db->order_by('month', 'ASC');
            $query = $this->db->get('disposals');
            $stats['monthly'] = $query->result_array();
        }
        
        return $stats;
    }

    /**
     * ดึงรายการวิธีการจำหน่าย
     */
    public function get_disposal_methods()
    {
        $this->db->select('disposal_method');
        $this->db->distinct();
        $this->db->order_by('disposal_method', 'ASC');
        $query = $this->db->get('disposals');
        return array_column($query->result_array(), 'disposal_method');
    }

    /**
     * ดึงข้อมูลการจำหน่ายล่าสุด
     */
    public function get_recent_disposals($limit = 10)
    {
        $this->db->select('d.*, a.asset_name, a.serial_number');
        $this->db->from('disposals d');
        $this->db->join('assets a', 'd.asset_id = a.asset_id');
        $this->db->order_by('d.created_at', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * ดึงข้อมูลการจำหน่ายสำหรับรายงาน
     * รองรับพารามิเตอร์แบบ (year, month, method) หรือ (start_date, end_date, method)
     * รวมถึงกรณี (year, method) จากบาง controller เดิม
     */
    public function get_disposals_for_report($arg1 = null, $arg2 = null, $arg3 = null)
    {
        $this->db->select('d.*, a.asset_name, a.serial_number, a.asset_type, a.purchase_price, a.purchase_date');
        $this->db->from('disposals d');
        $this->db->join('assets a', 'd.asset_id = a.asset_id');

        $start_date = null;
        $end_date   = null;
        $method     = null;

        // โหมดปี/เดือน/วิธี
        if ($arg1 && ctype_digit((string)$arg1) && strlen((string)$arg1) === 4) {
            $year = (int)$arg1;
            $isMonth = ($arg2 !== null && ctype_digit((string)$arg2) && (int)$arg2 >= 1 && (int)$arg2 <= 12);
            if ($isMonth) {
                $month = (int)$arg2;
                $start_date = sprintf('%04d-%02d-01', $year, $month);
                $end_date   = date('Y-m-t', strtotime($start_date));
                $method     = $arg3 ?: null;
            } else {
                // กรณี (year, method)
                $start_date = sprintf('%04d-01-01', $year);
                $end_date   = sprintf('%04d-12-31', $year);
                $method     = $arg2 ?: null;
            }
        } else {
            // โหมดเข้ากันได้ย้อนหลัง: (start_date, end_date, method)
            $start_date = $arg1 ?: null;
            $end_date   = $arg2 ?: null;
            $method     = $arg3 ?: null;
        }

        if (!empty($start_date)) {
            $this->db->where('d.disposal_date >=', $start_date);
        }
        if (!empty($end_date)) {
            $this->db->where('d.disposal_date <=', $end_date);
        }
        if (!empty($method)) {
            $this->db->where('d.disposal_method', $method);
        }

        $this->db->order_by('d.disposal_date', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * สรุปมูลค่าการจำหน่ายตามปี (รวม)
     */
    public function get_disposal_value_summary($year = null)
    {
        $this->db->select('SUM(d.disposal_price) AS total_disposal_value, SUM(a.purchase_price) AS total_purchase_value', false);
        $this->db->from('disposals d');
        $this->db->join('assets a', 'd.asset_id = a.asset_id');
        if ($year) {
            $this->db->where('YEAR(d.disposal_date)', (int)$year);
        }
        $row = $this->db->get()->row_array();
        return array(
            'total_disposal_value' => isset($row['total_disposal_value']) ? (float)$row['total_disposal_value'] : 0.0,
            'total_purchase_value' => isset($row['total_purchase_value']) ? (float)$row['total_purchase_value'] : 0.0,
        );
    }

    /**
     * ตรวจสอบว่าครุภัณฑ์สามารถจำหน่ายได้หรือไม่
     */
    public function can_dispose_asset($asset_id)
    {
        $this->db->select('status');
        $this->db->where('asset_id', $asset_id);
        $query = $this->db->get('assets');
        $asset = $query->row_array();
        
        if (!$asset) {
            return false;
        }
        
        // ครุภัณฑ์ที่จำหน่ายแล้วไม่สามารถจำหน่ายซ้ำได้
        return $asset['status'] !== 'จำหน่ายแล้ว';
    }

    /**
     * ตรวจสอบว่าครุภัณฑ์ถูกจำหน่ายแล้วหรือไม่
     */
    public function is_asset_disposed($asset_id)
    {
        $this->db->where('asset_id', $asset_id);
        $query = $this->db->get('disposals');
        return $query->num_rows() > 0;
    }

    /**
     * คำนวณกำไร/ขาดทุนจากการจำหน่าย
     */
    public function calculate_disposal_gain_loss($disposal_id)
    {
        $disposal = $this->get_disposal_by_id($disposal_id);
        if (!$disposal) {
            return false;
        }
        
        $purchase_price = $disposal['purchase_price'];
        $disposal_price = $disposal['disposal_price'] ?: 0;
        
        // คำนวณค่าเสื่อมราคาสะสม (ใช้วิธีเส้นตรง)
        $purchase_date = new DateTime($disposal['purchase_date']);
        $disposal_date = new DateTime($disposal['disposal_date']);
        $years = $purchase_date->diff($disposal_date)->y;
        
        // สมมติอัตราค่าเสื่อมราคา 20% ต่อปี (หรือดึงจากฐานข้อมูล)
        $depreciation_rate = 0.20;
        $accumulated_depreciation = $purchase_price * $depreciation_rate * $years;
        
        // ป้องกันค่าเสื่อมราคาเกินราคาซื้อ
        if ($accumulated_depreciation > $purchase_price) {
            $accumulated_depreciation = $purchase_price;
        }
        
        $book_value = $purchase_price - $accumulated_depreciation;
        $gain_loss = $disposal_price - $book_value;
        
        return array(
            'purchase_price' => $purchase_price,
            'disposal_price' => $disposal_price,
            'accumulated_depreciation' => $accumulated_depreciation,
            'book_value' => $book_value,
            'gain_loss' => $gain_loss,
            'years_used' => $years
        );
    }



}

