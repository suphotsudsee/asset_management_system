<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * ดึงข้อมูลการสำรวจทั้งหมด
     */
    public function get_all_surveys($year = null, $limit = null, $offset = null)
    {
        $this->db->select('s.*, a.asset_name, a.serial_number, a.asset_type, a.current_location');
        $this->db->from('annual_surveys s');
        $this->db->join('assets a', 's.asset_id = a.asset_id');
        
        if ($year) {
            $this->db->where('s.survey_year', $year);
        }
        
        $this->db->order_by('s.survey_date', 'DESC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * ดึงข้อมูลการสำรวจตาม ID
     */
    public function get_survey_by_id($survey_id)
    {
        $this->db->select('s.*, a.asset_name, a.serial_number, a.asset_type, a.current_location, a.purchase_price');
        $this->db->from('annual_surveys s');
        $this->db->join('assets a', 's.asset_id = a.asset_id');
        $this->db->where('s.survey_id', $survey_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * ดึงข้อมูลการสำรวจของครุภัณฑ์
     */
    public function get_surveys_by_asset($asset_id)
    {
        $this->db->where('asset_id', $asset_id);
        $this->db->order_by('survey_year', 'DESC');
        $query = $this->db->get('annual_surveys');
        return $query->result_array();
    }

    /**
     * ดึงข้อมูลการสำรวจตามปี
     */
    public function get_surveys_by_year($year)
    {
        $this->db->select('s.*, a.asset_name, a.serial_number, a.asset_type, a.current_location, a.purchase_price');
        $this->db->from('annual_surveys s');
        $this->db->join('assets a', 's.asset_id = a.asset_id');
        $this->db->where('s.survey_year', $year);
        $this->db->order_by('a.asset_name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * เพิ่มข้อมูลการสำรวจ
     */
    public function insert_survey($data)
    {
        return $this->db->insert('annual_surveys', $data);
    }

    /**
     * อัปเดตข้อมูลการสำรวจ
     */
    public function update_survey($survey_id, $data)
    {
        $this->db->where('survey_id', $survey_id);
        return $this->db->update('annual_surveys', $data);
    }

    /**
     * ลบข้อมูลการสำรวจ
     */
    public function delete_survey($survey_id)
    {
        $this->db->where('survey_id', $survey_id);
        return $this->db->delete('annual_surveys');
    }

    /**
     * ค้นหาการสำรวจ
     */
    public function search_surveys($keyword, $year = null, $condition = null)
    {
        $this->db->select('s.*, a.asset_name, a.serial_number, a.asset_type, a.current_location');
        $this->db->from('annual_surveys s');
        $this->db->join('assets a', 's.asset_id = a.asset_id');
        
        if ($keyword) {
            $this->db->group_start();
            $this->db->like('a.asset_name', $keyword);
            $this->db->or_like('a.serial_number', $keyword);
            $this->db->or_like('s.surveyed_by', $keyword);
            $this->db->or_like('s.notes', $keyword);
            $this->db->group_end();
        }
        
        if ($year) {
            $this->db->where('s.survey_year', $year);
        }
        
        if ($condition) {
            $this->db->where('s.condition', $condition);
        }
        
        $this->db->order_by('s.survey_date', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * ดึงสถิติการสำรวจครุภัณฑ์
     */
    public function get_survey_statistics($year)
    {
        // คำนวณจำนวนครุภัณฑ์ทั้งหมด (ไม่นับที่จำหน่ายแล้ว)
        $this->db->where('status !=', 'จำหน่ายแล้ว');
        $total_assets = $this->db->count_all_results('assets');

        // จำนวนครุภัณฑ์ที่มีการสำรวจในปีที่ระบุ
        $this->db->where('survey_year', $year);
        $surveyed = $this->db->count_all_results('annual_surveys');

        $not_surveyed = $total_assets - $surveyed;

        return array(
            'total_assets' => $total_assets,
            'surveyed' => $surveyed,
            'not_surveyed' => $not_surveyed
        );
    }

    /**
     * ดึงรายการปีที่มีการสำรวจ
     */
    public function get_survey_years()
    {
        $this->db->select('DISTINCT survey_year');
        $this->db->order_by('survey_year', 'DESC');
        $query = $this->db->get('annual_surveys');
        return array_column($query->result_array(), 'survey_year');
    }

    /**
     * ดึงครุภัณฑ์ที่ยังไม่ได้สำรวจในปีที่กำหนด
     */
    public function get_unsurveyed_assets($year)
    {
        $this->db->select('a.*');
        $this->db->from('assets a');
        $this->db->where('a.status !=', 'จำหน่ายแล้ว');
        $this->db->where('a.asset_id NOT IN (SELECT asset_id FROM annual_surveys WHERE survey_year = ' . $year . ')', null, false);
        $this->db->order_by('a.asset_name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * ตรวจสอบว่าครุภัณฑ์ถูกสำรวจในปีที่กำหนดแล้วหรือไม่
     */
    public function is_asset_surveyed($asset_id, $year)
    {
        $this->db->where('asset_id', $asset_id);
        $this->db->where('survey_year', $year);
        $query = $this->db->get('annual_surveys');
        return $query->num_rows() > 0;
    }

    /**
     * สร้างการสำรวจสำหรับครุภัณฑ์ทั้งหมดในปีที่กำหนด
     */
    public function create_annual_survey($year, $surveyed_by, $survey_date = null)
    {
        if (!$survey_date) {
            $survey_date = date('Y-m-d');
        }
        
        // ดึงครุภัณฑ์ที่ยังไม่ได้สำรวจ
        $unsurveyed_assets = $this->get_unsurveyed_assets($year);
        
        $this->db->trans_start();
        
        foreach ($unsurveyed_assets as $asset) {
            $survey_data = array(
                'survey_year' => $year,
                'asset_id' => $asset['asset_id'],
                'condition' => 'ดี', // ค่าเริ่มต้น
                'surveyed_by' => $surveyed_by,
                'survey_date' => $survey_date
            );
            
            $this->db->insert('annual_surveys', $survey_data);
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * อัปเดตสภาพครุภัณฑ์จากการสำรวจ
     */
    public function update_asset_condition_from_survey($survey_id, $condition)
    {
        $survey = $this->get_survey_by_id($survey_id);
        if (!$survey) {
            return false;
        }
        
        $this->db->trans_start();
        
        // อัปเดตข้อมูลการสำรวจ
        $this->db->where('survey_id', $survey_id);
        $this->db->update('annual_surveys', array('condition' => $condition));
        
        // อัปเดตสถานะครุภัณฑ์ตามสภาพที่สำรวจ
        $asset_status = 'ใช้งาน'; // ค่าเริ่มต้น
        
        switch ($condition) {
            case 'ชำรุด':
            case 'ไม่สามารถใช้งานได้':
                $asset_status = 'ชำรุด';
                break;
            case 'ดี':
            case 'พอใช้':
                $asset_status = 'ใช้งาน';
                break;
        }
        
        $this->db->where('asset_id', $survey['asset_id']);
        $this->db->update('assets', array(
            'status' => $asset_status,
            'updated_at' => date('Y-m-d H:i:s')
        ));
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * ดึงข้อมูลการสำรวจสำหรับรายงาน
     */
    public function get_surveys_for_report($year)
    {
        $this->db->select('s.*, a.asset_name, a.serial_number, a.asset_type, a.current_location, a.purchase_price, a.purchase_date');
        $this->db->from('annual_surveys s');
        $this->db->join('assets a', 's.asset_id = a.asset_id');
        $this->db->where('s.survey_year', $year);
        $this->db->order_by('a.asset_type', 'ASC');
        $this->db->order_by('a.asset_name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * ดึงสรุปการสำรวจตามประเภทครุภัณฑ์
     */
    public function get_survey_summary_by_type($year)
    {
        $this->db->select('a.asset_type, s.condition, COUNT(*) as count');
        $this->db->from('annual_surveys s');
        $this->db->join('assets a', 's.asset_id = a.asset_id');
        $this->db->where('s.survey_year', $year);
        $this->db->group_by(array('a.asset_type', 's.condition'));
        $this->db->order_by('a.asset_type', 'ASC');
        $this->db->order_by('s.condition', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * ดึงครุภัณฑ์ที่มีสภาพเสื่อมโทรมจากการสำรวจ
     */
    public function get_deteriorated_assets($year)
    {
        $this->db->select('s.*, a.asset_name, a.serial_number, a.asset_type, a.current_location');
        $this->db->from('annual_surveys s');
        $this->db->join('assets a', 's.asset_id = a.asset_id');
        $this->db->where('s.survey_year', $year);
        $this->db->where_in('s.condition', array('ชำรุด', 'ไม่สามารถใช้งานได้'));
        $this->db->order_by('a.asset_name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
}

