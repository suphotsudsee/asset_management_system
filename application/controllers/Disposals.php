<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Disposals extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Disposal_model');
        $this->load->model('Asset_model');
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
    }

    /**
     * แสดงรายการการจำหน่ายทั้งหมด
     */
    public function index()
    {
        $data = array();
        
        // การค้นหาและกรอง
        $keyword = $this->input->get('search');
        $method = $this->input->get('method');
        $date_from = $this->input->get('date_from');
        $date_to = $this->input->get('date_to');
        
        if ($keyword || $method || $date_from || $date_to) {
            $data['disposals'] = $this->Disposal_model->search_disposals($keyword, $method, $date_from, $date_to);
        } else {
            $data['disposals'] = $this->Disposal_model->get_all_disposals();
        }
        
        // ข้อมูลการค้นหา
        $data['search_keyword'] = $keyword;
        $data['selected_method'] = $method;
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        
        $data['page_title'] = 'รายการแทงจำหน่ายครุภัณฑ์ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'disposals';
        
        $this->load->view('templates/header', $data);
        $this->load->view('disposals/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * แสดงรายละเอียดการจำหน่าย
     */
    public function view($disposal_id)
    {
        $data = array();
        
        $data['disposal'] = $this->Disposal_model->get_disposal_by_id($disposal_id);
        
        if (!$data['disposal']) {
            show_404();
        }
        
        $data['page_title'] = 'รายละเอียดการจำหน่าย #' . $disposal_id;
        $data['page_name'] = 'disposal_detail';
        
        $this->load->view('templates/header', $data);
        $this->load->view('disposals/view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * แสดงฟอร์มแทงจำหน่ายครุภัณฑ์
     */
    public function add()
    {
        $data = array();
        
        // ดึงรายการครุภัณฑ์ที่สามารถจำหน่ายได้
        $data['assets'] = $this->Asset_model->get_disposable_assets();
        
        // ถ้ามี asset_id ใน URL ให้เลือกไว้
        $data['selected_asset_id'] = $this->input->get('asset_id');
        
        $data['page_title'] = 'แทงจำหน่ายครุภัณฑ์ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'add_disposal';
        
        $this->load->view('templates/header', $data);
        $this->load->view('disposals/add', $data);
        $this->load->view('templates/footer');
    }

    /**
     * บันทึกการจำหน่าย
     */
    public function store()
    {
        // ตั้งค่า validation rules
        $this->form_validation->set_rules('asset_id', 'ครุภัณฑ์', 'required|numeric');
        $this->form_validation->set_rules('disposal_method', 'วิธีการจำหน่าย', 'required|trim');
        $this->form_validation->set_rules('disposal_date', 'วันที่จำหน่าย', 'required');
        $this->form_validation->set_rules('disposed_by', 'ผู้ดำเนินการ', 'required|trim');
        $this->form_validation->set_rules('reason', 'เหตุผลการจำหน่าย', 'required|trim');
        $this->form_validation->set_rules('book_value', 'มูลค่าตามบัญชี', 'required|numeric|greater_than_equal_to[0]');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
            return;
        }

        $asset_id = $this->input->post('asset_id');

        // ตรวจสอบว่าครุภัณฑ์มีอยู่และสามารถจำหน่ายได้
        $asset = $this->Asset_model->get_asset_by_id($asset_id);
        if (!$asset) {
            $this->session->set_flashdata('error', 'ไม่พบครุภัณฑ์ที่ระบุ');
            $this->add();
            return;
        }

        if ($asset['status'] == 'จำหน่ายแล้ว') {
            $this->session->set_flashdata('error', 'ครุภัณฑ์นี้ถูกจำหน่ายไปแล้ว');
            $this->add();
            return;
        }

        // ตรวจสอบว่ามีการจำหน่ายครุภัณฑ์นี้อยู่แล้วหรือไม่
        if ($this->Disposal_model->check_asset_disposed($asset_id)) {
            $this->session->set_flashdata('error', 'ครุภัณฑ์นี้มีการจำหน่ายอยู่แล้ว');
            $this->add();
            return;
        }

        // เตรียมข้อมูลสำหรับบันทึก
        $data = array(
            'asset_id' => $asset_id,
            'disposal_method' => $this->input->post('disposal_method'),
            'disposal_date' => $this->input->post('disposal_date'),
            'disposed_by' => $this->input->post('disposed_by'),
            'reason' => $this->input->post('reason'),
            'book_value' => $this->input->post('book_value'),
            'disposal_value' => $this->input->post('disposal_value') ?: 0,
            'buyer_info' => $this->input->post('buyer_info'),
            'notes' => $this->input->post('notes')
        );

        if ($this->Disposal_model->insert_disposal($data)) {
            $this->session->set_flashdata('success', 'บันทึกการจำหน่ายเรียบร้อยแล้ว');
            redirect('disposals');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            $this->add();
        }
    }

    /**
     * แสดงฟอร์มแก้ไขการจำหน่าย
     */
    public function edit($disposal_id)
    {
        $data = array();
        
        $data['disposal'] = $this->Disposal_model->get_disposal_by_id($disposal_id);
        
        if (!$data['disposal']) {
            show_404();
        }
        
        // ดึงรายการครุภัณฑ์ที่สามารถจำหน่ายได้
        $data['assets'] = $this->Asset_model->get_disposable_assets();
        
        $data['page_title'] = 'แก้ไขการจำหน่าย #' . $disposal_id;
        $data['page_name'] = 'edit_disposal';
        
        $this->load->view('templates/header', $data);
        $this->load->view('disposals/edit', $data);
        $this->load->view('templates/footer');
    }

    /**
     * อัปเดตข้อมูลการจำหน่าย
     */
    public function update($disposal_id)
    {
        $disposal = $this->Disposal_model->get_disposal_by_id($disposal_id);
        
        if (!$disposal) {
            show_404();
        }

        // ตั้งค่า validation rules
        $this->form_validation->set_rules('disposal_method', 'วิธีการจำหน่าย', 'required|trim');
        $this->form_validation->set_rules('disposal_date', 'วันที่จำหน่าย', 'required');
        $this->form_validation->set_rules('disposed_by', 'ผู้ดำเนินการ', 'required|trim');
        $this->form_validation->set_rules('reason', 'เหตุผลการจำหน่าย', 'required|trim');
        $this->form_validation->set_rules('book_value', 'มูลค่าตามบัญชี', 'required|numeric|greater_than_equal_to[0]');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($disposal_id);
            return;
        }

        // เตรียมข้อมูลสำหรับอัปเดต
        $data = array(
            'disposal_method' => $this->input->post('disposal_method'),
            'disposal_date' => $this->input->post('disposal_date'),
            'disposed_by' => $this->input->post('disposed_by'),
            'reason' => $this->input->post('reason'),
            'book_value' => $this->input->post('book_value'),
            'disposal_value' => $this->input->post('disposal_value') ?: 0,
            'buyer_info' => $this->input->post('buyer_info'),
            'notes' => $this->input->post('notes')
        );

        if ($this->Disposal_model->update_disposal($disposal_id, $data)) {
            $this->session->set_flashdata('success', 'อัปเดตข้อมูลการจำหน่ายเรียบร้อยแล้ว');
            redirect('disposals/view/' . $disposal_id);
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
            $this->edit($disposal_id);
        }
    }

    /**
     * ลบการจำหน่าย
     */
    public function delete($disposal_id)
    {
        $disposal = $this->Disposal_model->get_disposal_by_id($disposal_id);
        
        if (!$disposal) {
            show_404();
        }

        if ($this->Disposal_model->delete_disposal($disposal_id)) {
            $this->session->set_flashdata('success', 'ลบการจำหน่ายเรียบร้อยแล้ว');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการลบข้อมูล');
        }

        redirect('disposals');
    }

    /**
     * ส่งออกข้อมูลการจำหน่ายเป็น CSV
     */
    public function export()
    {
        $disposals = $this->Disposal_model->get_all_disposals();
        
        $filename = 'disposals_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        
        $output = fopen('php://output', 'w');
        
        // เขียน BOM สำหรับ UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // เขียนหัวตาราง
        fputcsv($output, array(
            'รหัสการจำหน่าย',
            'ชื่อครุภัณฑ์',
            'หมายเลขซีเรียล',
            'วิธีการจำหน่าย',
            'วันที่จำหน่าย',
            'ผู้ดำเนินการ',
            'เหตุผล',
            'มูลค่าตามบัญชี',
            'มูลค่าจำหน่าย',
            'ข้อมูลผู้ซื้อ',
            'หมายเหตุ'
        ));
        
        // เขียนข้อมูล
        foreach ($disposals as $disposal) {
            fputcsv($output, array(
                $disposal['disposal_id'],
                $disposal['asset_name'],
                $disposal['serial_number'] ?: '-',
                $disposal['disposal_method'],
                $disposal['disposal_date'],
                $disposal['disposed_by'],
                $disposal['reason'],
                number_format($disposal['book_value'], 2),
                number_format($disposal['disposal_value'], 2),
                $disposal['buyer_info'],
                $disposal['notes']
            ));
        }
        
        fclose($output);
    }

    /**
     * API สำหรับ AJAX - ดึงข้อมูลครุภัณฑ์และมูลค่าตามบัญชี
     */
    public function api_get_asset_book_value()
    {
        $asset_id = $this->input->post('asset_id');
        $asset = $this->Asset_model->get_asset_by_id($asset_id);
        
        if ($asset) {
            // คำนวณมูลค่าตามบัญชี
            $this->load->model('Depreciation_model');
            $depreciation = $this->Depreciation_model->calculate_depreciation($asset_id);
            
            $response = array(
                'success' => true,
                'asset' => $asset,
                'book_value' => $depreciation ? $depreciation['book_value'] : $asset['purchase_price']
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
     * รายงานการจำหน่าย
     */
    public function report()
    {
        $data = array();
        
        $year = $this->input->get('year') ?: date('Y');
        $method = $this->input->get('method');
        
        $data['disposals'] = $this->Disposal_model->get_disposals_for_report($year, $method);
        $data['statistics'] = $this->Disposal_model->get_disposal_statistics($year);
        $data['selected_year'] = $year;
        $data['selected_method'] = $method;
        
        $data['page_title'] = 'รายงานการจำหน่ายครุภัณฑ์ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'disposal_report';
        
        $this->load->view('templates/header', $data);
        $this->load->view('disposals/report', $data);
        $this->load->view('templates/footer');
    }

    /**
     * พิมพ์ใบแทงจำหน่าย
     */
    public function print_disposal($disposal_id)
    {
        $data = array();
        
        $data['disposal'] = $this->Disposal_model->get_disposal_by_id($disposal_id);
        
        if (!$data['disposal']) {
            show_404();
        }
        
        $data['page_title'] = 'ใบแทงจำหน่ายครุภัณฑ์ #' . $disposal_id;
        
        $this->load->view('disposals/print', $data);
    }

    /**
     * อนุมัติการจำหน่าย
     */
    public function approve($disposal_id)
    {
        $disposal = $this->Disposal_model->get_disposal_by_id($disposal_id);
        
        if (!$disposal) {
            show_404();
        }

        $data = array(
            'approval_status' => 'อนุมัติ',
            'approved_by' => 'ผู้อนุมัติ', // ในระบบจริงควรใช้ข้อมูลผู้ใช้ที่ล็อกอิน
            'approved_date' => date('Y-m-d H:i:s')
        );

        if ($this->Disposal_model->update_disposal($disposal_id, $data)) {
            $this->session->set_flashdata('success', 'อนุมัติการจำหน่ายเรียบร้อยแล้ว');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการอนุมัติ');
        }

        redirect('disposals/view/' . $disposal_id);
    }

    /**
     * ไม่อนุมัติการจำหน่าย
     */
    public function reject($disposal_id)
    {
        $disposal = $this->Disposal_model->get_disposal_by_id($disposal_id);
        
        if (!$disposal) {
            show_404();
        }

        $data = array(
            'approval_status' => 'ไม่อนุมัติ',
            'approved_by' => 'ผู้อนุมัติ', // ในระบบจริงควรใช้ข้อมูลผู้ใช้ที่ล็อกอิน
            'approved_date' => date('Y-m-d H:i:s')
        );

        if ($this->Disposal_model->update_disposal($disposal_id, $data)) {
            $this->session->set_flashdata('success', 'ไม่อนุมัติการจำหน่ายเรียบร้อยแล้ว');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการไม่อนุมัติ');
        }

        redirect('disposals/view/' . $disposal_id);
    }
}

