<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Repairs extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Repair_model');
        $this->load->model('Asset_model');
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
    }

    /**
     * แสดงรายการการซ่อมแซมทั้งหมด
     */
    public function index()
    {
        $data = array();
        
        // การค้นหาและกรอง
        $keyword = $this->input->get('search');
        $status = $this->input->get('status');
        $priority = $this->input->get('priority');
        $date_from = $this->input->get('date_from');
        $date_to = $this->input->get('date_to');
        
        if ($keyword || $status || $priority || $date_from || $date_to) {
            $data['repairs'] = $this->Repair_model->search_repairs($keyword, $status, $priority, $date_from, $date_to);
        } else {
            $data['repairs'] = $this->Repair_model->get_all_repairs();
        }
        
        // ข้อมูลการค้นหา
        $data['search_keyword'] = $keyword;
        $data['selected_status'] = $status;
        $data['selected_priority'] = $priority;
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        
        $data['page_title'] = 'รายการซ่อมแซมครุภัณฑ์ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'repairs';
        
        $this->load->view('templates/header', $data);
        $this->load->view('repairs/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * แสดงรายละเอียดการซ่อมแซม
     */
    public function view($repair_id)
    {
        $data = array();
        
        $data['repair'] = $this->Repair_model->get_repair_by_id($repair_id);
        
        if (!$data['repair']) {
            show_404();
        }
        
        $data['page_title'] = 'รายละเอียดการซ่อมแซม #' . $repair_id;
        $data['page_name'] = 'repair_detail';
        
        $this->load->view('templates/header', $data);
        $this->load->view('repairs/view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * แสดงฟอร์มขออนุญาตซ่อมแซม
     */
    public function add($asset_id = null)
    {
        $data = array();

        // ดึงรายการครุภัณฑ์ที่สามารถซ่อมแซมได้
        $data['assets'] = $this->Asset_model->get_repairable_assets();

        // รองรับการระบุ asset_id ทั้งจากพารามิเตอร์และ query string
        $data['selected_asset_id'] = $asset_id !== null ? $asset_id : $this->input->get('asset_id');

        $data['page_title'] = 'ขออนุญาตซ่อมแซมครุภัณฑ์ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'add_repair';

        $this->load->view('templates/header', $data);
        $this->load->view('repairs/add', $data);
        $this->load->view('templates/footer');
    }

    /**
     * บันทึกคำขอซ่อมแซม
     */
    public function store()
    {
        // ตั้งค่า validation rules
        $this->form_validation->set_rules('asset_id', 'ครุภัณฑ์', 'required|numeric');
        $this->form_validation->set_rules('problem_description', 'รายละเอียดปัญหา', 'required|trim');
        $this->form_validation->set_rules('priority', 'ระดับความสำคัญ', 'required');
        $this->form_validation->set_rules('requested_by', 'ผู้แจ้ง', 'required|trim');
        $this->form_validation->set_rules('contact_info', 'ข้อมูลติดต่อ', 'required|trim');
        $this->form_validation->set_rules('repair_type', 'ประเภทการซ่อม', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
            return;
        }

        $asset_id = $this->input->post('asset_id');

        // ตรวจสอบว่าครุภัณฑ์มีอยู่และสามารถซ่อมแซมได้
        $asset = $this->Asset_model->get_asset_by_id($asset_id);
        if (!$asset) {
            $this->session->set_flashdata('error', 'ไม่พบครุภัณฑ์ที่ระบุ');
            $this->add();
            return;
        }

        if ($asset['status'] == 'จำหน่ายแล้ว') {
            $this->session->set_flashdata('error', 'ไม่สามารถซ่อมแซมครุภัณฑ์ที่จำหน่ายแล้ว');
            $this->add();
            return;
        }

        // ตรวจสอบว่ามีการซ่อมแซมที่ยังไม่เสร็จสิ้นอยู่หรือไม่
        if ($this->Repair_model->has_pending_repair($asset_id)) {
            $this->session->set_flashdata('error', 'ครุภัณฑ์นี้มีการซ่อมแซมที่ยังไม่เสร็จสิ้นอยู่');
            $this->add();
            return;
        }

        // เตรียมข้อมูลสำหรับบันทึก
        $data = array(
            'asset_id' => $asset_id,
            'problem_description' => $this->input->post('problem_description'),
            'priority' => $this->input->post('priority'),
            'requested_by' => $this->input->post('requested_by'),
            'contact_info' => $this->input->post('contact_info'),
            'repair_type' => $this->input->post('repair_type'),
            'estimated_cost' => $this->input->post('estimated_cost') ?: 0,
            'expected_completion' => $this->input->post('expected_completion'),
            'notes' => $this->input->post('notes'),
            'status' => 'รอพิจารณา',
            'request_date' => date('Y-m-d H:i:s')
        );

        if ($this->Repair_model->insert_repair($data)) {
            // อัปเดตสถานะครุภัณฑ์
            $this->Asset_model->update_asset_status($asset_id, 'รอซ่อมแซม');
            
            $this->session->set_flashdata('success', 'ส่งคำขอซ่อมแซมเรียบร้อยแล้ว');
            redirect('repairs');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            $this->add();
        }
    }

    /**
     * แสดงฟอร์มแก้ไขการซ่อมแซม
     */
    public function edit($repair_id)
    {
        $data = array();
        
        $data['repair'] = $this->Repair_model->get_repair_by_id($repair_id);
        
        if (!$data['repair']) {
            show_404();
        }
        
        // ดึงรายการครุภัณฑ์ที่สามารถซ่อมแซมได้
        $data['assets'] = $this->Asset_model->get_repairable_assets();
        
        $data['page_title'] = 'แก้ไขการซ่อมแซม #' . $repair_id;
        $data['page_name'] = 'edit_repair';
        
        $this->load->view('templates/header', $data);
        $this->load->view('repairs/edit', $data);
        $this->load->view('templates/footer');
    }

    /**
     * อัปเดตข้อมูลการซ่อมแซม
     */
    public function update($repair_id)
    {
        $repair = $this->Repair_model->get_repair_by_id($repair_id);
        
        if (!$repair) {
            show_404();
        }

        // ตั้งค่า validation rules
        $this->form_validation->set_rules('problem_description', 'รายละเอียดปัญหา', 'required|trim');
        $this->form_validation->set_rules('priority', 'ระดับความสำคัญ', 'required');
        $this->form_validation->set_rules('requested_by', 'ผู้แจ้ง', 'required|trim');
        $this->form_validation->set_rules('contact_info', 'ข้อมูลติดต่อ', 'required|trim');
        $this->form_validation->set_rules('repair_type', 'ประเภทการซ่อม', 'required');
        $this->form_validation->set_rules('status', 'สถานะ', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($repair_id);
            return;
        }

        // เตรียมข้อมูลสำหรับอัปเดต
        $data = array(
            'problem_description' => $this->input->post('problem_description'),
            'priority' => $this->input->post('priority'),
            'requested_by' => $this->input->post('requested_by'),
            'contact_info' => $this->input->post('contact_info'),
            'repair_type' => $this->input->post('repair_type'),
            'estimated_cost' => $this->input->post('estimated_cost') ?: 0,
            'actual_cost' => $this->input->post('actual_cost') ?: 0,
            'expected_completion' => $this->input->post('expected_completion'),
            'actual_completion' => $this->input->post('actual_completion'),
            'vendor_info' => $this->input->post('vendor_info'),
            'repair_details' => $this->input->post('repair_details'),
            'notes' => $this->input->post('notes'),
            'status' => $this->input->post('status')
        );

        // อัปเดตวันที่อนุมัติหากสถานะเปลี่ยนเป็นอนุมัติ
        if ($this->input->post('status') == 'อนุมัติ' && $repair['status'] != 'อนุมัติ') {
            $data['approved_date'] = date('Y-m-d H:i:s');
            $data['approved_by'] = 'ผู้อนุมัติ'; // ในระบบจริงควรใช้ข้อมูลผู้ใช้ที่ล็อกอิน
        }

        // อัปเดตวันที่เสร็จสิ้นหากสถานะเปลี่ยนเป็นเสร็จสิ้น
        if ($this->input->post('status') == 'เสร็จสิ้น' && $repair['status'] != 'เสร็จสิ้น') {
            $data['completion_date'] = date('Y-m-d H:i:s');
            
            // อัปเดตสถานะครุภัณฑ์กลับเป็นใช้งาน
            $this->Asset_model->update_asset_status($repair['asset_id'], 'ใช้งาน');
        }

        if ($this->Repair_model->update_repair($repair_id, $data)) {
            $this->session->set_flashdata('success', 'อัปเดตข้อมูลการซ่อมแซมเรียบร้อยแล้ว');
            redirect('repairs/view/' . $repair_id);
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
            $this->edit($repair_id);
        }
    }

    /**
     * ลบการซ่อมแซม
     */
    public function delete($repair_id)
    {
        $repair = $this->Repair_model->get_repair_by_id($repair_id);
        
        if (!$repair) {
            show_404();
        }

        if ($this->Repair_model->delete_repair($repair_id)) {
            // อัปเดตสถานะครุภัณฑ์กลับเป็นสถานะเดิม
            if ($repair['status'] != 'เสร็จสิ้น') {
                $this->Asset_model->update_asset_status($repair['asset_id'], 'ใช้งาน');
            }
            
            $this->session->set_flashdata('success', 'ลบการซ่อมแซมเรียบร้อยแล้ว');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการลบข้อมูล');
        }

        redirect('repairs');
    }

    /**
     * อนุมัติการซ่อมแซม
     */
    public function approve($repair_id)
    {
        $repair = $this->Repair_model->get_repair_by_id($repair_id);
        
        if (!$repair) {
            show_404();
        }

        $data = array(
            'status' => 'อนุมัติ',
            'approved_by' => 'ผู้อนุมัติ', // ในระบบจริงควรใช้ข้อมูลผู้ใช้ที่ล็อกอิน
            'approved_date' => date('Y-m-d H:i:s')
        );

        if ($this->Repair_model->update_repair($repair_id, $data)) {
            // อัปเดตสถานะครุภัณฑ์
            $this->Asset_model->update_asset_status($repair['asset_id'], 'ซ่อมแซม');
            
            $this->session->set_flashdata('success', 'อนุมัติการซ่อมแซมเรียบร้อยแล้ว');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการอนุมัติ');
        }

        redirect('repairs/view/' . $repair_id);
    }

    /**
     * ไม่อนุมัติการซ่อมแซม
     */
    public function reject($repair_id)
    {
        $repair = $this->Repair_model->get_repair_by_id($repair_id);
        
        if (!$repair) {
            show_404();
        }

        $data = array(
            'status' => 'ไม่อนุมัติ',
            'approved_by' => 'ผู้อนุมัติ', // ในระบบจริงควรใช้ข้อมูลผู้ใช้ที่ล็อกอิน
            'approved_date' => date('Y-m-d H:i:s')
        );

        if ($this->Repair_model->update_repair($repair_id, $data)) {
            // อัปเดตสถานะครุภัณฑ์กลับเป็นสถานะเดิม
            $this->Asset_model->update_asset_status($repair['asset_id'], 'ใช้งาน');
            
            $this->session->set_flashdata('success', 'ไม่อนุมัติการซ่อมแซมเรียบร้อยแล้ว');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการไม่อนุมัติ');
        }

        redirect('repairs/view/' . $repair_id);
    }

    /**
     * พิมพ์หนังสือขออนุญาตซ่อมแซม
     */
    public function print_request($repair_id)
    {
        $data = array();
        
        $data['repair'] = $this->Repair_model->get_repair_by_id($repair_id);
        
        if (!$data['repair']) {
            show_404();
        }
        
        $data['page_title'] = 'หนังสือขออนุญาตซ่อมแซม #' . $repair_id;
        
        $this->load->view('repairs/print_request', $data);
    }

    /**
     * ส่งออกข้อมูลการซ่อมแซมเป็น CSV
     */
    public function export()
    {
        $repairs = $this->Repair_model->get_all_repairs();
        
        $filename = 'repairs_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        
        $output = fopen('php://output', 'w');
        
        // เขียน BOM สำหรับ UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // เขียนหัวตาราง
        fputcsv($output, array(
            'รหัสการซ่อม',
            'ชื่อครุภัณฑ์',
            'หมายเลขซีเรียล',
            'รายละเอียดปัญหา',
            'ประเภทการซ่อม',
            'ระดับความสำคัญ',
            'ผู้แจ้ง',
            'วันที่แจ้ง',
            'สถานะ',
            'ค่าใช้จ่ายประมาณ',
            'ค่าใช้จ่ายจริง',
            'ผู้อนุมัติ',
            'วันที่อนุมัติ',
            'วันที่เสร็จสิ้น'
        ));
        
        // เขียนข้อมูล
        foreach ($repairs as $repair) {
            fputcsv($output, array(
                $repair['repair_id'],
                $repair['asset_name'],
                $repair['serial_number'] ?: '-',
                $repair['problem_description'],
                $repair['repair_type'],
                $repair['priority'],
                $repair['requested_by'],
                $repair['request_date'],
                $repair['status'],
                number_format($repair['estimated_cost'], 2),
                number_format($repair['actual_cost'], 2),
                $repair['approved_by'] ?: '-',
                $repair['approved_date'] ?: '-',
                $repair['completion_date'] ?: '-'
            ));
        }
        
        fclose($output);
    }

    /**
     * รายงานการซ่อมแซม
     */
    public function report()
    {
        $data = array();
        
        $year = $this->input->get('year') ?: date('Y');
        $month = $this->input->get('month');
        $status = $this->input->get('status');
        
        $data['repairs'] = $this->Repair_model->get_repairs_for_report($year, $month, $status);
        $data['statistics'] = $this->Repair_model->get_repair_statistics($year);
        $data['cost_summary'] = $this->Repair_model->get_repair_cost_summary($year);
        $data['selected_year'] = $year;
        $data['selected_month'] = $month;
        $data['selected_status'] = $status;
        
        $data['page_title'] = 'รายงานการซ่อมแซมครุภัณฑ์ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'repair_report';
        
        $this->load->view('templates/header', $data);
        $this->load->view('repairs/report', $data);
        $this->load->view('templates/footer');
    }

    /**
     * API สำหรับ AJAX - ดึงข้อมูลครุภัณฑ์
     */
    public function api_get_asset_info()
    {
        $asset_id = $this->input->post('asset_id');
        $asset = $this->Asset_model->get_asset_by_id($asset_id);
        
        if ($asset) {
            $response = array(
                'success' => true,
                'asset' => $asset
            );
        } else {
            $response = array(
                'success' => false,
                'message' => 'ไม่พบครุภัณฑ์ที่ระบุ'
            );
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * API สำหรับ AJAX - อัปเดตสถานะการซ่อมแซม
     */
    public function api_update_status()
    {
        $repair_id = $this->input->post('repair_id');
        $status = $this->input->post('status');
        
        if ($this->Repair_model->update_repair_status($repair_id, $status)) {
            $response = array('success' => true, 'message' => 'อัปเดตสถานะเรียบร้อยแล้ว');
        } else {
            $response = array('success' => false, 'message' => 'เกิดข้อผิดพลาดในการอัปเดต');
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}

