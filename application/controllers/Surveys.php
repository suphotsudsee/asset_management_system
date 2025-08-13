<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surveys extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index()
    {
        $data['page_title'] = 'สำรวจประจำปี - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'surveys';
        $data['breadcrumbs'] = array(
            array('title' => 'สำรวจประจำปี')
        );

        $this->load->view('templates/header', $data);
        $this->load->view('surveys/index', $data);
        $this->load->view('templates/footer');
    }
}
