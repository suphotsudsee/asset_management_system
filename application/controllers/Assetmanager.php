<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assetmanager extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Asset_model');
        $this->load->model('Transfer_model');
        $this->load->model('Repair_model');
        $this->load->model('Depreciation_model');
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
    }

    /**
     * แสดงรายการครุภัณฑ์ทั้งหมด
     */
    public function index()
    {
        $data = array();
        
        // การค้นหาและกรอง
        $keyword = $this->input->get('search');
        $type = $this->input->get('type');
        $status = $this->input->get('status');
        $location = $this->input->get('location');
        
        if ($keyword || $type || $status || $location) {
            $data['assets'] = $this->Asset_model->search_assets($keyword, $type, $status, $location);
        } else {
            $data['assets'] = $this->Asset_model->get_all_assets();
        }
        
        // ข้อมูลสำหรับ dropdown filters
        $data['asset_types'] = $this->Asset_model->get_asset_types();
        $data['locations'] = $this->Asset_model->get_locations();
        
        // ข้อมูลการค้นหา
        $data['search_keyword'] = $keyword;
        $data['selected_type'] = $type;
        $data['selected_status'] = $status;
        $data['selected_location'] = $location;
        
        $data['page_title'] = 'รายการครุภัณฑ์ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'assets';
        
        $this->load->view('templates/header', $data);
        $this->load->view('assetmanager/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * แสดงรายละเอียดครุภัณฑ์
     */
    public function view($asset_id)
    {
        $data = array();
        
        $data['asset'] = $this->Asset_model->get_asset_by_id($asset_id);
        
        if (!$data['asset']) {
            show_404();
        }
        
        // ดึงประวัติการโอนย้าย
        $data['transfers'] = $this->Transfer_model->get_transfers_by_asset($asset_id);
        
        // ดึงประวัติการซ่อมแซม
        $data['repairs'] = $this->Repair_model->get_repairs_by_asset($asset_id);
        
        // คำนวณค่าเสื่อมราคา
        $data['depreciation'] = $this->Asset_model->calculate_depreciation($asset_id);
        
        $data['page_title'] = 'รายละเอียดครุภัณฑ์: ' . $data['asset']['asset_name'];
        $data['page_name'] = 'asset_detail';
        
        $this->load->view('templates/header', $data);
        $this->load->view('assetmanager/view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * แสดงฟอร์มเพิ่มครุภัณฑ์ใหม่
     */
    public function add()
    {
        $data = array();
        $data['page_title'] = 'เพิ่มครุภัณฑ์ใหม่ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'add_asset';
        
        $this->load->view('templates/header', $data);
        $this->load->view('assetmanager/add', $data);
        $this->load->view('templates/footer');
    }

    /**
     * บันทึกครุภัณฑ์ใหม่
     */
    public function store()
    {
        // ตั้งค่า validation rules
        $this->form_validation->set_rules('asset_name', 'ชื่อครุภัณฑ์', 'required|trim');
        $this->form_validation->set_rules('asset_type', 'ประเภทครุภัณฑ์', 'required|trim');
        $this->form_validation->set_rules('serial_number', 'หมายเลขซีเรียล', 'trim');
        $this->form_validation->set_rules('purchase_date', 'วันที่จัดซื้อ', 'required');
        $this->form_validation->set_rules('purchase_price', 'ราคาจัดซื้อ', 'required|numeric');
        $this->form_validation->set_rules('current_location', 'สถานที่ตั้งปัจจุบัน', 'required|trim');
        $this->form_validation->set_rules('status', 'สถานะ', 'required');
        $this->form_validation->set_rules('depreciation_rate', 'อัตราค่าเสื่อมราคา', 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]');
        $this->form_validation->set_rules('acquisition_method', 'วิธีการได้มา', 'required|trim');
        $this->form_validation->set_rules('responsible_person', 'ผู้รับผิดชอบ', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
            return;
        }

        // ตรวจสอบ Serial Number ซ้ำ
        $serial_number = $this->input->post('serial_number');
        if ($serial_number && $this->Asset_model->check_serial_exists($serial_number)) {
            $this->session->set_flashdata('error', 'หมายเลขซีเรียลนี้มีอยู่ในระบบแล้ว');
            $this->add();
            return;
        }

        // เตรียมข้อมูลสำหรับบันทึก
        $data = array(
            'asset_name' => $this->input->post('asset_name'),
            'asset_type' => $this->input->post('asset_type'),
            'serial_number' => $serial_number ?: null,
            'purchase_date' => $this->input->post('purchase_date'),
            'purchase_price' => $this->input->post('purchase_price'),
            'current_location' => $this->input->post('current_location'),
            'status' => $this->input->post('status'),
            'warranty_info' => $this->input->post('warranty_info'),
            'depreciation_rate' => $this->input->post('depreciation_rate'),
            'acquisition_method' => $this->input->post('acquisition_method'),
            'responsible_person' => $this->input->post('responsible_person')
        );

        if ($this->Asset_model->insert_asset($data)) {
            $this->session->set_flashdata('success', 'เพิ่มครุภัณฑ์เรียบร้อยแล้ว');
            redirect('assetmanager');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            $this->add();
        }
    }

    /**
     * แสดงฟอร์มแก้ไขครุภัณฑ์
     */
    public function edit($asset_id)
    {
        $data = array();
        
        $data['asset'] = $this->Asset_model->get_asset_by_id($asset_id);
        
        if (!$data['asset']) {
            show_404();
        }
        
        $data['page_title'] = 'แก้ไขครุภัณฑ์: ' . $data['asset']['asset_name'];
        $data['page_name'] = 'edit_asset';
        
        $this->load->view('templates/header', $data);
        $this->load->view('assetmanager/edit', $data);
        $this->load->view('templates/footer');
    }

    /**
     * อัปเดตข้อมูลครุภัณฑ์
     */
    public function update($asset_id)
    {
        $asset = $this->Asset_model->get_asset_by_id($asset_id);
        
        if (!$asset) {
            show_404();
        }

        // ตั้งค่า validation rules
        $this->form_validation->set_rules('asset_name', 'ชื่อครุภัณฑ์', 'required|trim');
        $this->form_validation->set_rules('asset_type', 'ประเภทครุภัณฑ์', 'required|trim');
        $this->form_validation->set_rules('serial_number', 'หมายเลขซีเรียล', 'trim');
        $this->form_validation->set_rules('purchase_date', 'วันที่จัดซื้อ', 'required');
        $this->form_validation->set_rules('purchase_price', 'ราคาจัดซื้อ', 'required|numeric');
        $this->form_validation->set_rules('current_location', 'สถานที่ตั้งปัจจุบัน', 'required|trim');
        $this->form_validation->set_rules('status', 'สถานะ', 'required');
        $this->form_validation->set_rules('depreciation_rate', 'อัตราค่าเสื่อมราคา', 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]');
        $this->form_validation->set_rules('acquisition_method', 'วิธีการได้มา', 'required|trim');
        $this->form_validation->set_rules('responsible_person', 'ผู้รับผิดชอบ', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($asset_id);
            return;
        }

        // ตรวจสอบ Serial Number ซ้ำ
        $serial_number = $this->input->post('serial_number');
        if ($serial_number && $this->Asset_model->check_serial_exists($serial_number, $asset_id)) {
            $this->session->set_flashdata('error', 'หมายเลขซีเรียลนี้มีอยู่ในระบบแล้ว');
            $this->edit($asset_id);
            return;
        }

        // เตรียมข้อมูลสำหรับอัปเดต
        $data = array(
            'asset_name' => $this->input->post('asset_name'),
            'asset_type' => $this->input->post('asset_type'),
            'serial_number' => $serial_number ?: null,
            'purchase_date' => $this->input->post('purchase_date'),
            'purchase_price' => $this->input->post('purchase_price'),
            'current_location' => $this->input->post('current_location'),
            'status' => $this->input->post('status'),
            'warranty_info' => $this->input->post('warranty_info'),
            'depreciation_rate' => $this->input->post('depreciation_rate'),
            'acquisition_method' => $this->input->post('acquisition_method'),
            'responsible_person' => $this->input->post('responsible_person')
        );

        if ($this->Asset_model->update_asset($asset_id, $data)) {
            $this->session->set_flashdata('success', 'อัปเดตข้อมูลครุภัณฑ์เรียบร้อยแล้ว');
            redirect('assetmanager/view/' . $asset_id);
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
            $this->edit($asset_id);
        }
    }

    /**
     * ลบครุภัณฑ์
     */
    public function delete($asset_id)
    {
        $asset = $this->Asset_model->get_asset_by_id($asset_id);
        
        if (!$asset) {
            show_404();
        }

        if ($this->Asset_model->delete_asset($asset_id)) {
            $this->session->set_flashdata('success', 'ลบครุภัณฑ์เรียบร้อยแล้ว');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการลบข้อมูล');
        }

        redirect('assets');
    }

    /**
     * ส่งออกข้อมูลครุภัณฑ์เป็น CSV
     */
    public function export()
    {
        $assets = $this->Asset_model->get_all_assets();
        
        $filename = 'assets_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        
        $output = fopen('php://output', 'w');
        
        // เขียน BOM สำหรับ UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // เขียนหัวตาราง
        fputcsv($output, array(
            'รหัสครุภัณฑ์',
            'ชื่อครุภัณฑ์',
            'ประเภท',
            'หมายเลขซีเรียล',
            'วันที่จัดซื้อ',
            'ราคาจัดซื้อ',
            'สถานที่ตั้งปัจจุบัน',
            'สถานะ',
            'อัตราค่าเสื่อมราคา',
            'วิธีการได้มา',
            'ผู้รับผิดชอบ'
        ));
        
        // เขียนข้อมูล
        foreach ($assets as $asset) {
            fputcsv($output, array(
                $asset['asset_id'],
                $asset['asset_name'],
                $asset['asset_type'],
                $asset['serial_number'],
                $asset['purchase_date'],
                $asset['purchase_price'],
                $asset['current_location'],
                $asset['status'],
                $asset['depreciation_rate'] . '%',
                $asset['acquisition_method'],
                $asset['responsible_person']
            ));
        }
        
        fclose($output);
    }

    /**
     * API สำหรับ AJAX - ดึงข้อมูลครุภัณฑ์
     */
    public function api_get_assets()
    {
        $assets = $this->Asset_model->get_active_assets();
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($assets));
    }

    /**
     * API สำหรับ AJAX - อัปเดตสถานะครุภัณฑ์
     */
    public function api_update_status()
    {
        $asset_id = $this->input->post('asset_id');
        $status = $this->input->post('status');
        
        if ($this->Asset_model->update_asset_status($asset_id, $status)) {
            $response = array('success' => true, 'message' => 'อัปเดตสถานะเรียบร้อยแล้ว');
        } else {
            $response = array('success' => false, 'message' => 'เกิดข้อผิดพลาดในการอัปเดต');
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }


    
}

