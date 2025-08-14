<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surveys extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Survey_model');
        $this->load->model('Asset_model');
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
    }

    /**
     * แสดงรายการสำรวจครุภัณฑ์ทั้งหมด
     */
    public function index()
    {
        $data = array();
        
        // การค้นหาและกรอง
        $keyword = $this->input->get('search');
        $year = $this->input->get('year');
        $condition = $this->input->get('condition');
        
        if ($keyword || $year || $condition) {
            $data['surveys'] = $this->Survey_model->search_surveys($keyword, $year, $condition);
        } else {
            $data['surveys'] = $this->Survey_model->get_all_surveys();
        }
        
        // ข้อมูลสำหรับ dropdown filters
        $data['survey_years'] = $this->Survey_model->get_survey_years();
        
        // ข้อมูลการค้นหา
        $data['search_keyword'] = $keyword;
        $data['selected_year'] = $year;
        $data['selected_condition'] = $condition;
        
        $data['title'] = 'รายงานสำรวจครุภัณฑ์ประจำปี';
        
        $this->load->view('templates/header', $data);
        $this->load->view('surveys/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * แสดงรายละเอียดการสำรวจ
     */
    public function view($survey_id)
    {
        $data = array();
        
        $data['survey'] = $this->Survey_model->get_survey_by_id($survey_id);
        
        if (!$data['survey']) {
            show_404();
        }
        
        $data['title'] = 'รายละเอียดการสำรวจครุภัณฑ์';
        
        $this->load->view('templates/header', $data);
        $this->load->view('surveys/view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * แสดงฟอร์มเพิ่มการสำรวจใหม่
     */
    public function add()
    {
        $data = array();
        
        // ดึงรายการครุภัณฑ์ที่ยังไม่ได้สำรวจในปีปัจจุบัน
        $current_year = date('Y');
        $data['assets'] = $this->Asset_model->get_all_assets();
        
        $data['title'] = 'เพิ่มข้อมูลสำรวจครุภัณฑ์';
        $data['survey_year'] = $current_year;
        
        $this->form_validation->set_rules('survey_year', 'ปีที่สำรวจ', 'required|numeric');
        $this->form_validation->set_rules('asset_id', 'ครุภัณฑ์', 'required|numeric');
        $this->form_validation->set_rules('condition', 'สภาพครุภัณฑ์', 'required');
        $this->form_validation->set_rules('surveyed_by', 'ผู้สำรวจ', 'required');
        $this->form_validation->set_rules('survey_date', 'วันที่สำรวจ', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('surveys/add', $data);
            $this->load->view('templates/footer');
        } else {
            // ตรวจสอบว่าครุภัณฑ์นี้ได้รับการสำรวจในปีนี้แล้วหรือไม่
            $asset_id = $this->input->post('asset_id');
            $survey_year = $this->input->post('survey_year');
            
            if ($this->Survey_model->is_asset_surveyed($asset_id, $survey_year)) {
                $this->session->set_flashdata('error', 'ครุภัณฑ์นี้ได้รับการสำรวจในปี ' . $survey_year . ' แล้ว');
                redirect('surveys/add');
                return;
            }

            // เตรียมข้อมูลสำหรับบันทึก
            $data = array(
                'asset_id' => $asset_id,
                'survey_year' => $survey_year,
                'survey_date' => $this->input->post('survey_date'),
                'condition' => $this->input->post('condition'),
                'surveyed_by' => $this->input->post('surveyed_by'),
                'notes' => $this->input->post('notes')
            );

            if ($this->Survey_model->insert_survey($data)) {
                // อัปเดตสถานะครุภัณฑ์ตามสภาพที่สำรวจ
                $condition = $this->input->post('condition');
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
                
                $this->Asset_model->update_asset_status($asset_id, $asset_status);
                
                $this->session->set_flashdata('success', 'บันทึกการสำรวจเรียบร้อยแล้ว');
                redirect('surveys');
            } else {
                $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                redirect('surveys/add');
            }
        }
    }

    /**
     * แสดงฟอร์มแก้ไขการสำรวจ
     */
    public function edit($survey_id)
    {
        $data = array();
        
        $data['survey'] = $this->Survey_model->get_survey_by_id($survey_id);
        
        if (!$data['survey']) {
            show_404();
        }
        
        $data['assets'] = $this->Asset_model->get_all_assets();
        
        $data['title'] = 'แก้ไขข้อมูลสำรวจครุภัณฑ์';
        
        $this->form_validation->set_rules('survey_year', 'ปีที่สำรวจ', 'required|numeric');
        $this->form_validation->set_rules('asset_id', 'ครุภัณฑ์', 'required|numeric');
        $this->form_validation->set_rules('condition', 'สภาพครุภัณฑ์', 'required');
        $this->form_validation->set_rules('surveyed_by', 'ผู้สำรวจ', 'required');
        $this->form_validation->set_rules('survey_date', 'วันที่สำรวจ', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('surveys/edit', $data);
            $this->load->view('templates/footer');
        } else {
            // เตรียมข้อมูลสำหรับอัปเดต
            $data = array(
                'survey_year' => $this->input->post('survey_year'),
                'asset_id' => $this->input->post('asset_id'),
                'condition' => $this->input->post('condition'),
                'notes' => $this->input->post('notes'),
                'surveyed_by' => $this->input->post('surveyed_by'),
                'survey_date' => $this->input->post('survey_date')
            );

            if ($this->Survey_model->update_survey($survey_id, $data)) {
                // อัปเดตสถานะครุภัณฑ์ตามสภาพที่สำรวจ
                $condition = $this->input->post('condition');
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
                
                $this->Asset_model->update_asset_status($this->input->post('asset_id'), $asset_status);
                
                $this->session->set_flashdata('success', 'อัปเดตข้อมูลการสำรวจเรียบร้อยแล้ว');
                redirect('surveys');
            } else {
                $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
                redirect('surveys/edit/' . $survey_id);
            }
        }
    }

    /**
     * ลบการสำรวจ
     */
    public function delete($survey_id)
    {
        $survey = $this->Survey_model->get_survey_by_id($survey_id);
        
        if (!$survey) {
            show_404();
        }

        if ($this->Survey_model->delete_survey($survey_id)) {
            $this->session->set_flashdata('success', 'ลบข้อมูลการสำรวจเรียบร้อยแล้ว');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการลบข้อมูล');
        }

        redirect('surveys');
    }

    /**
     * สร้างการสำรวจประจำปี
     */
    public function create_annual_survey()
    {
        $data = array();
        
        $data['title'] = 'สร้างการสำรวจประจำปี';
        $data['current_year'] = date('Y');
        
        $this->load->view('templates/header', $data);
        $this->load->view('surveys/create_annual', $data);
        $this->load->view('templates/footer');
    }

    /**
     * บันทึกการสร้างการสำรวจประจำปี
     */
    public function store_annual_survey()
    {
        // ตั้งค่า validation rules
        $this->form_validation->set_rules('survey_year', 'ปีที่สำรวจ', 'required|numeric');
        $this->form_validation->set_rules('survey_date', 'วันที่สำรวจ', 'required');
        $this->form_validation->set_rules('surveyed_by', 'ผู้สำรวจ', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->create_annual_survey();
            return;
        }

        $year = $this->input->post('survey_year');
        $surveyed_by = $this->input->post('surveyed_by');
        $survey_date = $this->input->post('survey_date');
        
        // ดึงรายการครุภัณฑ์ที่ยังไม่ได้สำรวจในปีนี้
        $unsurveyed_assets = $this->Survey_model->get_unsurveyed_assets($year);
        
        if (empty($unsurveyed_assets)) {
            $this->session->set_flashdata('info', 'ไม่มีครุภัณฑ์ที่ต้องสำรวจในปี ' . $year);
            redirect('surveys');
            return;
        }
        
        $success_count = 0;
        
        foreach ($unsurveyed_assets as $asset) {
            $data = array(
                'asset_id' => $asset['asset_id'],
                'survey_year' => $year,
                'survey_date' => $survey_date,
                'condition' => 'ดี', // ค่าเริ่มต้น
                'surveyed_by' => $surveyed_by,
                'notes' => 'สร้างอัตโนมัติจากการสำรวจประจำปี'
            );
            
            if ($this->Survey_model->insert_survey($data)) {
                $success_count++;
            }
        }
        
        if ($success_count > 0) {
            $this->session->set_flashdata('success', 'สร้างการสำรวจประจำปีเรียบร้อยแล้ว ' . $success_count . ' รายการ');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการสร้างการสำรวจประจำปี');
        }
        
        redirect('surveys');
    }

    /**
     * ส่งออกข้อมูลการสำรวจเป็น CSV
     */
    public function export($year = null)
    {
        if (!$year) {
            $year = date('Y');
        }
        
        $surveys = $this->Survey_model->get_surveys_for_report($year);
        
        $filename = 'surveys_' . $year . '_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        
        $output = fopen('php://output', 'w');
        
        // เขียน BOM สำหรับ UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // เขียนหัวตาราง
        fputcsv($output, array(
            'รหัสการสำรวจ',
            'ปีที่สำรวจ',
            'วันที่สำรวจ',
            'รหัสครุภัณฑ์',
            'ชื่อครุภัณฑ์',
            'ประเภท',
            'หมายเลขซีเรียล',
            'สถานที่ตั้ง',
            'สภาพ',
            'ผู้สำรวจ',
            'หมายเหตุ'
        ));
        
        // เขียนข้อมูล
        foreach ($surveys as $survey) {
            fputcsv($output, array(
                $survey['survey_id'],
                $survey['survey_year'],
                $survey['survey_date'],
                $survey['asset_id'],
                $survey['asset_name'],
                $survey['asset_type'],
                $survey['serial_number'],
                $survey['current_location'],
                $survey['condition'],
                $survey['surveyed_by'],
                $survey['notes']
            ));
        }
        
        fclose($output);
    }

    /**
     * API สำหรับ AJAX - ดึงประวัติการสำรวจของครุภัณฑ์
     */
    public function api_get_survey_history($asset_id)
    {
        $surveys = $this->Survey_model->get_surveys_by_asset($asset_id);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($surveys));
    }
}

