<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Depreciation extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Depreciation_model', 'Asset_model']);
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url','form']);
    }

    public function index()
    {
        $data['records'] = $this->Depreciation_model->get_all();
        $this->load->view('templates/header');
        $this->load->view('depreciation/index', $data);   // ← ชี้ไปโฟลเดอร์เอกพจน์
        $this->load->view('templates/footer');
    }

    public function create()
    {
        $this->_set_validation_rules();
        if ($this->form_validation->run() === FALSE) {
            $this->_render_form();
        } else {
            $this->Depreciation_model->insert($this->_collect_post_data());
            $this->session->set_flashdata('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
            redirect('depreciation');
        }
    }

    public function edit($id = NULL)
    {
        $record = $this->Depreciation_model->get_by_id($id);
        if (!$record) show_404();

        $this->_set_validation_rules();
        if ($this->form_validation->run() === FALSE) {
            $this->_render_form($record);
        } else {
            $this->Depreciation_model->update($id, $this->_collect_post_data());
            $this->session->set_flashdata('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
            redirect('depreciation');
        }
    }

    public function delete($id = NULL)
    {
        if ($this->Depreciation_model->delete($id)) {
            $this->session->set_flashdata('success', 'ลบข้อมูลเรียบร้อยแล้ว');
        } else {
            $this->session->set_flashdata('error', 'ไม่สามารถลบข้อมูลได้');
        }
        redirect('depreciation');
    }

    /* ---------- helpers ---------- */
    private function _set_validation_rules()
    {
        $this->form_validation->set_rules('asset_id','ครุภัณฑ์','required|integer');
        $this->form_validation->set_rules('record_date','วันที่บันทึก','required|callback_valid_date');
        $this->form_validation->set_rules('depreciation_amount','ค่าเสื่อมราคา','required|numeric');
        $this->form_validation->set_rules('accumulated_depreciation','ค่าเสื่อมสะสม','required|numeric');
        $this->form_validation->set_rules('book_value','มูลค่าตามบัญชี','required|numeric');
    }

    public function valid_date($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        $ok = $d && $d->format('Y-m-d') === $date;
        if(!$ok) $this->form_validation->set_message('valid_date', 'รูปแบบวันที่ไม่ถูกต้อง (YYYY-MM-DD)');
        return $ok;
    }

    private function _collect_post_data()
    {
        return [
            'asset_id'                 => $this->input->post('asset_id', TRUE),
            'record_date'              => $this->input->post('record_date', TRUE),
            'depreciation_amount'      => $this->input->post('depreciation_amount', TRUE),
            'accumulated_depreciation' => $this->input->post('accumulated_depreciation', TRUE),
            'book_value'               => $this->input->post('book_value', TRUE),
        ];
    }

    private function _render_form($record = NULL)
    {
        $data['record'] = $record;
        $data['assets'] = $this->Asset_model->get_all(); // ต้องมีใน Asset_model
        $this->load->view('templates/header');
        $this->load->view('depreciation/form', $data);   // ← เอกพจน์
        $this->load->view('templates/footer');
    }
}
