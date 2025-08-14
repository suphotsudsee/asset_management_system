<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->helper(['url', 'form']);
        $this->load->library(['session', 'form_validation']);
    }

    public function index() {
        $data['users'] = $this->User_model->get_all_users();
        $data['page_title'] = 'จัดการผู้ใช้ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'users';
        $this->load->view('templates/header', $data);
        $this->load->view('users/index', $data);
        $this->load->view('templates/footer');
    }

    public function add() {
        $data['page_title'] = 'เพิ่มผู้ใช้ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'users_add';
        $this->load->view('templates/header', $data);
        $this->load->view('users/add');
        $this->load->view('templates/footer');
    }

    public function store() {
        $this->form_validation->set_rules('username', 'ชื่อผู้ใช้', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'รหัสผ่าน', 'required|min_length[6]');
        $this->form_validation->set_rules('full_name', 'ชื่อ-นามสกุล', 'required');
        $this->form_validation->set_rules('email', 'อีเมล', 'valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
            return;
        }

        $data = [
            'username' => $this->input->post('username'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'full_name' => $this->input->post('full_name'),
            'email' => $this->input->post('email'),
            'role' => $this->input->post('role')
        ];

        if ($this->User_model->insert_user($data)) {
            $this->session->set_flashdata('success', 'เพิ่มผู้ใช้เรียบร้อยแล้ว');
            redirect('users');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            $this->add();
        }
    }

    public function edit($id) {
        $data['user'] = $this->User_model->get_user_by_id($id);
        if (!$data['user']) {
            show_404();
        }
        $data['page_title'] = 'แก้ไขผู้ใช้ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'users_edit';
        $this->load->view('templates/header', $data);
        $this->load->view('users/edit', $data);
        $this->load->view('templates/footer');
    }

    public function update($id) {
        $user = $this->User_model->get_user_by_id($id);
        if (!$user) {
            show_404();
        }

        $this->form_validation->set_rules('username', 'ชื่อผู้ใช้', 'required');
        $this->form_validation->set_rules('full_name', 'ชื่อ-นามสกุล', 'required');
        $this->form_validation->set_rules('email', 'อีเมล', 'valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
            return;
        }

        $data = [
            'username' => $this->input->post('username'),
            'full_name' => $this->input->post('full_name'),
            'email' => $this->input->post('email'),
            'role' => $this->input->post('role')
        ];

        if ($this->input->post('password')) {
            $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        }

        if ($this->User_model->update_user($id, $data)) {
            $this->session->set_flashdata('success', 'อัปเดตข้อมูลผู้ใช้เรียบร้อยแล้ว');
            redirect('users');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
            $this->edit($id);
        }
    }

    public function delete($id) {
        $user = $this->User_model->get_user_by_id($id);
        if (!$user) {
            show_404();
        }

        if ($this->User_model->delete_user($id)) {
            $this->session->set_flashdata('success', 'ลบผู้ใช้เรียบร้อยแล้ว');
        } else {
            $this->session->set_flashdata('error', 'เกิดข้อผิดพลาดในการลบข้อมูล');
        }
        redirect('users');
    }
}
