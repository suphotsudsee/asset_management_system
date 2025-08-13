<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transfers extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Transfer_model');
        $this->load->model('Asset_model');
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
    }

    /**
     * แสดงรายการการโอนย้ายทั้งหมด
     */
    public function index()
    {
        $data = array();
        
        // การค้นหาและกรอง
        $keyword = $this->input->get('search');
        $status = $this->input->get('status');
        $date_from = $this->input->get('date_from');
        $date_to = $this->input->get('date_to');
        
        if ($keyword || $status || $date_from || $date_to) {
            $data['transfers'] = $this->Transfer_model->search_transfers($keyword, $status, $date_from, $date_to);
        } else {
            $data['transfers'] = $this->Transfer_model->get_all_transfers();
        }
        
        // ข้อมูลการค้นหา
        $data['search_keyword'] = $keyword;
        $data['selected_status'] = $status;
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        
        $data['page_title'] = 'รายการโอนย้ายครุภัณฑ์ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'transfers';
        
        $this->load->view('templates/header', $data);
        $this->load->view('transfers/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * แสดงรายละเอียดการโอนย้าย
     */
    public function view($transfer_id)
    {
        $data = array();
        
        $data['transfer'] = $this->Transfer_model->get_transfer_by_id($transfer_id);
        
        if (!$data['transfer']) {
            show_404();
        }
        
        $data['page_title'] = 'รายละเอียดการโอนย้าย #' . $transfer_id;
        $data['page_name'] = 'transfer_detail';
        
        $this->load->view('templates/header', $data);
        $this->load->view('transfers/view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * แสดงฟอร์มโอนย้ายครุภัณฑ์
     */
    public function add()
    {
        $data = array();
        
        // ดึงรายการครุภัณฑ์ที่สามารถโอนย้ายได้
        $data['assets'] = $this->Asset_model->get_transferable_assets();
        
        // ถ้ามี asset_id ใน URL ให้เลือกไว้
        $data['selected_asset_id'] = $this->input->get('asset_id');
        
        $data['page_title'] = 'โอนย้ายครุภัณฑ์ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'add_transfer';
        
        $this->load->view('templates/header', $data);
        $this->load->view('transfers/add', $data);
        $this->load->view('templates/footer');
    }

    /**
     * บันทึกการโอนย้าย
     */
    public function store()
    {
        // ตั้งค่า validation rules
        $this->form_validation->set_rules('asset_id', 'ครุภัณฑ์', 'required|numeric');
        $this->form_validation->set_rules('from_location', 'สถานที่เดิม', 'required|trim');
        $this->form_validation->set_rules('to_location', 'สถานที่ใหม่', 'required|trim');
        $this->form_validation->set_rules('transfer_date', 'วันที่โอนย้าย', 'required');
        $this->form_validation->set_rules('transferred_by', 'ผู้ดำเนินการ', 'required|trim');
        $this->form_validation->set_rules('reason', 'เหตุผลการโอนย้าย', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
            return;
        }

        $asset_id = $this->input->post('asset_id');
        $from_location = $this->input->post('from_location');
        $to_location = $this->input->post('to_location');

        // ตรวจสอบว่าครุภัณฑ์มีอยู่และสามารถโอนย้ายได้
        $asset = $this->Asset_model->get_asset_by_id($asset_id);
        if (!$asset) {
            $this->session->set_flashdata('error', 'ไม่พบครุภัณฑ์ที่ระบุ');
            $this->add();
            return;
        }

        if ($asset['status'] == 'จำหน่ายแล้ว') {
            $this->session->set_flashdata('error', 'ไม่สามารถโอนย้ายครุภัณฑ์ที่จำหน่ายแล้ว');
            $this->add();
            return;
        }

        // ตรวจสอบว่าสถานที่เดิมตรงกับข้อมูลในระบบ
        if ($asset['current_location'] !== $from_location) {
            $this->session->set_flashdata('error', 'สถานที่เดิมไม่ตรงกับข้อมูลในระบบ');
            $this->add();
            return;
        }

        // ตรวจสอบว่าสถานที่ใหม่ไม่เหมือนสถานที่เดิม
        if ($from_location === $to_location) {
            $this->session->set_flashdata('error', 'สถานที่ใหม่ต้องไม่เหมือนกับสถานที่เดิม');
            $this->add();
            return;
        }

        // เตรียมข้อมูลสำหรับบันทึก
        $data = array(
            'asset_id' => $asset_id,
            'from_location' => $from_location,
            'to_location' => $to_location,
            'transfer_date' => $this->input->post('transfer_date'),
            'transferred_by' => $this->input->post('transferred_by'),
            'reason' => $this->input->post('reason'),
            'notes' => $this->input->post('notes'),
            'status' => 'เสร็จสิ้น'
        );

        if ($this->Transfer_model->insert_transfer($data)) {
            $this->session->set_flashdata('success', 'บันทึกการโอนย้ายเรียบร้อยแล้ว');
            redirect('transfers');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            $this->add();
        }
    }

    /**
     * แสดงฟอร์มแก้ไขการโอนย้าย
     */
    public function edit($transfer_id)
    {
        $data = array();
        
        $data['transfer'] = $this->Transfer_model->get_transfer_by_id($transfer_id);
        
        if (!$data['transfer']) {
            show_404();
        }
        
        // ดึงรายการครุภัณฑ์ที่สามารถโอนย้ายได้
        $data['assets'] = $this->Asset_model->get_transferable_assets();
        
        $data['page_title'] = 'แก้ไขการโอนย้าย #' . $transfer_id;
        $data['page_name'] = 'edit_transfer';
        
        $this->load->view('templates/header', $data);
        $this->load->view('transfers/edit', $data);
        $this->load->view('templates/footer');
    }

    /**
     * อัปเดตข้อมูลการโอนย้าย
     */
    public function update($transfer_id)
    {
        $transfer = $this->Transfer_model->get_transfer_by_id($transfer_id);
        
        if (!$transfer) {
            show_404();
        }

        // ตั้งค่า validation rules
        $this->form_validation->set_rules('transferred_by', 'ผู้ดำเนินการ', 'required|trim');
        $this->form_validation->set_rules('reason', 'เหตุผลการโอนย้าย', 'required|trim');
        $this->form_validation->set_rules('status', 'สถานะ', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($transfer_id);
            return;
        }

        // เตรียมข้อมูลสำหรับอัปเดต
        $data = array(
            'transferred_by' => $this->input->post('transferred_by'),
            'reason' => $this->input->post('reason'),
            'notes' => $this->input->post('notes'),
            'status' => $this->input->post('status')
        );

        if ($this->Transfer_model->update_transfer($transfer_id, $data)) {
            $this->session->set_flashdata('success', 'อัปเดตข้อมูลการโอนย้ายเรียบร้อยแล้ว');
            redirect('transfers/view/' . $transfer_id);
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
            $this->edit($transfer_id);
        }
    }

    /**
     * ลบการโอนย้าย
     */
    public function delete($transfer_id)
    {
        $transfer = $this->Transfer_model->get_transfer_by_id($transfer_id);
        
        if (!$transfer) {
            show_404();
        }

        if ($this->Transfer_model->delete_transfer($transfer_id)) {
            $this->session->set_flashdata('success', 'ลบการโอนย้ายเรียบร้อยแล้ว');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการลบข้อมูล');
        }

        redirect('transfers');
    }

    /**
     * ส่งออกข้อมูลการโอนย้ายเป็น CSV
     */
    public function export()
    {
        $transfers = $this->Transfer_model->get_all_transfers();
        
        $filename = 'transfers_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        
        $output = fopen('php://output', 'w');
        
        // เขียน BOM สำหรับ UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // เขียนหัวตาราง
        fputcsv($output, array(
            'รหัสการโอนย้าย',
            'ชื่อครุภัณฑ์',
            'หมายเลขซีเรียล',
            'จากสถานที่',
            'ไปสถานที่',
            'วันที่โอนย้าย',
            'ผู้ดำเนินการ',
            'เหตุผล',
            'สถานะ',
            'หมายเหตุ'
        ));
        
        // เขียนข้อมูล
        foreach ($transfers as $transfer) {
            fputcsv($output, array(
                $transfer['transfer_id'],
                $transfer['asset_name'],
                $transfer['serial_number'] ?: '-',
                $transfer['from_location'],
                $transfer['to_location'],
                $transfer['transfer_date'],
                $transfer['transferred_by'],
                $transfer['reason'],
                $transfer['status'],
                $transfer['notes']
            ));
        }
        
        fclose($output);
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
     * API สำหรับ AJAX - อัปเดตสถานะการโอนย้าย
     */
    public function api_update_status()
    {
        $transfer_id = $this->input->post('transfer_id');
        $status = $this->input->post('status');
        
        if ($this->Transfer_model->update_transfer_status($transfer_id, $status)) {
            $response = array('success' => true, 'message' => 'อัปเดตสถานะเรียบร้อยแล้ว');
        } else {
            $response = array('success' => false, 'message' => 'เกิดข้อผิดพลาดในการอัปเดต');
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * รายงานการโอนย้าย
     */
    public function report()
    {
        $data = array();
        
        $year = $this->input->get('year') ?: date('Y');
        $month = $this->input->get('month');
        
        $data['transfers'] = $this->Transfer_model->get_transfers_for_report($year, $month);
        $data['statistics'] = $this->Transfer_model->get_transfer_statistics($year);
        $data['selected_year'] = $year;
        $data['selected_month'] = $month;
        
        $data['page_title'] = 'รายงานการโอนย้ายครุภัณฑ์ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'transfer_report';
        
        $this->load->view('templates/header', $data);
        $this->load->view('transfers/report', $data);
        $this->load->view('templates/footer');
    }
}

