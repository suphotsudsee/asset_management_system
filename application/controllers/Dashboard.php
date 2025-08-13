<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Asset_model');
        $this->load->model('Transfer_model');
        $this->load->model('Repair_model');
        $this->load->model('Disposal_model');
        $this->load->helper('url');
        $this->load->library('session');
    }

    /**
     * หน้าหลัก Dashboard
     */
    public function index()
    {
        $data = array();
        
        // ดึงสถิติครุภัณฑ์
        $data['asset_stats'] = $this->Asset_model->get_asset_statistics();
        
        // ดึงสถิติการซ่อมแซม
        $data['repair_stats'] = $this->Repair_model->get_repair_statistics();
        
        // ดึงข้อมูลครุภัณฑ์ล่าสุด
        $data['recent_assets'] = $this->Asset_model->get_all_assets(5);
        
        // ดึงการซ่อมแซมที่รอดำเนินการ
        $data['pending_repairs'] = $this->Repair_model->get_pending_repairs();
        
        // ดึงการโอนย้ายล่าสุด
        $data['recent_transfers'] = $this->Transfer_model->get_recent_transfers(5);
        
        // ดึงการจำหน่ายล่าสุด
        $data['recent_disposals'] = $this->Disposal_model->get_recent_disposals(5);
        
        $data['page_title'] = 'หน้าหลัก - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'dashboard';
        
        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * ข้อมูลสถิติสำหรับ AJAX
     */
    public function get_statistics()
    {
        $stats = array(
            'assets' => $this->Asset_model->get_asset_statistics(),
            'repairs' => $this->Repair_model->get_repair_statistics(),
            'transfers' => $this->Transfer_model->get_transfer_statistics(),
            'disposals' => $this->Disposal_model->get_disposal_statistics()
        );
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($stats));
    }

    /**
     * ข้อมูลกราฟสำหรับ Dashboard
     */
    public function get_chart_data()
    {
        $year = $this->input->get('year') ?: date('Y');
        
        $chart_data = array(
            'asset_types' => $this->Asset_model->get_asset_statistics()['types'],
            'monthly_repairs' => $this->Repair_model->get_repair_statistics($year)['monthly'] ?? array(),
            'monthly_transfers' => $this->Transfer_model->get_transfer_statistics($year)['monthly'] ?? array()
        );
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($chart_data));
    }
}

