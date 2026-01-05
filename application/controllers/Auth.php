<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->helper(array('url', 'form'));
		$this->load->library(array('session', 'form_validation'));
	}

	public function login()
	{
		// Redirect if already logged in
		if ($this->session->userdata('user_id'))
		{
			redirect('dashboard');
		}

		if ($this->input->method() === 'post')
		{
			$this->form_validation->set_rules('username', 'ชื่อผู้ใช้', 'required');
			$this->form_validation->set_rules('password', 'รหัสผ่าน', 'required');

			if ($this->form_validation->run())
			{
				$username = $this->input->post('username');
				$password = $this->input->post('password');

				$user = $this->User_model->get_user_by_username($username);
				if ($user && password_verify($password, $user['password']))
				{
					$this->session->set_userdata(array(
						'user_id'   => $user['user_id'],
						'username'  => $user['username'],
						'full_name' => $user['full_name'],
						'role'      => $user['role']
					));

					$this->session->set_flashdata('success', 'เข้าสู่ระบบสำเร็จ');
					redirect('dashboard');
				}
				else
				{
					$this->session->set_flashdata('error', 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
					redirect('auth/login');
				}
			}
		}

		$this->load->view('auth/login');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('auth/login');
	}
}
