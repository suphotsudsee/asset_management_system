<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assettypes extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Assettype_model');
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);
    }

    public function index()
    {
        $data['types'] = $this->Assettype_model->get_all_types();
        $data['page_title'] = 'จัดการประเภทครุภัณฑ์ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'assettypes';

        $this->load->view('templates/header', $data);
        $this->load->view('assettypes/index', $data);
        $this->load->view('templates/footer');
    }

    public function add()
    {
        $data['page_title'] = 'เพิ่มประเภทครุภัณฑ์ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'assettypes_add';

        $this->load->view('templates/header', $data);
        $this->load->view('assettypes/add', $data);
        $this->load->view('templates/footer');
    }

    public function store()
    {
        $this->form_validation->set_rules('type_name', 'ชื่อประเภท', 'required|is_unique[asset_types.type_name]');
        $this->form_validation->set_rules('description', 'คำอธิบาย', 'trim');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
            return;
        }

        $data = [
            'type_name' => $this->input->post('type_name'),
            'description' => $this->input->post('description')
        ];

        if ($this->Assettype_model->insert_type($data)) {
            $this->session->set_flashdata('success', 'เพิ่มประเภทครุภัณฑ์เรียบร้อยแล้ว');
            redirect('assettypes');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            $this->add();
        }
    }

    public function edit($id)
    {
        $data['type'] = $this->Assettype_model->get_type_by_id($id);
        if (!$data['type']) {
            show_404();
        }

        $data['page_title'] = 'แก้ไขประเภทครุภัณฑ์ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'assettypes_edit';

        $this->load->view('templates/header', $data);
        $this->load->view('assettypes/edit', $data);
        $this->load->view('templates/footer');
    }

    public function update($id)
    {
        $type = $this->Assettype_model->get_type_by_id($id);
        if (!$type) {
            show_404();
        }

        $this->form_validation->set_rules('type_name', 'ชื่อประเภท', 'required|callback_unique_type['.$id.']');
        $this->form_validation->set_rules('description', 'คำอธิบาย', 'trim');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
            return;
        }

        $data = [
            'type_name' => $this->input->post('type_name'),
            'description' => $this->input->post('description')
        ];

        if ($this->Assettype_model->update_type($id, $data)) {
            $this->session->set_flashdata('success', 'อัปเดตประเภทครุภัณฑ์เรียบร้อยแล้ว');
            redirect('assettypes');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
            $this->edit($id);
        }
    }

    public function delete($id)
    {
        $type = $this->Assettype_model->get_type_by_id($id);
        if (!$type) {
            show_404();
        }

        if ($this->Assettype_model->delete_type($id)) {
            $this->session->set_flashdata('success', 'ลบประเภทครุภัณฑ์เรียบร้อยแล้ว');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการลบข้อมูล');
        }
        redirect('assettypes');
    }

    public function unique_type($str, $id)
    {
        $this->db->where('type_name', $str);
        $this->db->where('type_id !=', $id);
        $query = $this->db->get('asset_types');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('unique_type', 'ชื่อประเภทนี้ถูกใช้แล้ว');
            return FALSE;
        }
        return TRUE;
    }
}
