<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Asset_model');
        $this->load->model('Survey_model');
        $this->load->model('Transfer_model');
        $this->load->model('Repair_model');
        $this->load->model('Disposal_model');
        $this->load->model('Guarantee_model');
        $this->output->set_content_type('application/json');
    }

    /**
     * API สำหรับดึงข้อมูลครุภัณฑ์
     */
    public function assets()
    {
        $assets = $this->Asset_model->get_all_assets();
        $this->output->set_output(json_encode($assets));
    }

    /**
     * API สำหรับดึงข้อมูลครุภัณฑ์ตาม ID
     */
    public function asset($asset_id)
    {
        $asset = $this->Asset_model->get_asset_by_id($asset_id);
        if ($asset) {
            $this->output->set_output(json_encode($asset));
        } else {
            $this->output->set_status_header(404);
            $this->output->set_output(json_encode(['error' => 'ไม่พบข้อมูลครุภัณฑ์']));
        }
    }

    /**
     * API สำหรับดึงประวัติการสำรวจของครุภัณฑ์
     */
    public function surveys_history($asset_id)
    {
        $surveys = $this->Survey_model->get_surveys_by_asset($asset_id);
        $this->output->set_output(json_encode($surveys));
    }

    /**
     * API สำหรับดึงประวัติการโอนย้ายของครุภัณฑ์
     */
    public function transfers_history($asset_id)
    {
        $transfers = $this->Transfer_model->get_transfers_by_asset($asset_id);
        $this->output->set_output(json_encode($transfers));
    }

    /**
     * API สำหรับดึงประวัติการซ่อมแซมของครุภัณฑ์
     */
    public function repairs_history($asset_id)
    {
        $repairs = $this->Repair_model->get_repairs_by_asset($asset_id);
        $this->output->set_output(json_encode($repairs));
    }

    /**
     * API สำหรับดึงสถิติครุภัณฑ์
     */
    public function asset_statistics()
    {
        $stats = $this->Asset_model->get_asset_statistics();
        $this->output->set_output(json_encode($stats));
    }

    /**
     * API สำหรับดึงสถิติการสำรวจ
     */
    public function survey_statistics($year = null)
    {
        $stats = $this->Survey_model->get_survey_statistics($year);
        $this->output->set_output(json_encode($stats));
    }

    /**
     * API สำหรับดึงสถิติการซ่อมแซม
     */
    public function repair_statistics($year = null)
    {
        $stats = $this->Repair_model->get_repair_statistics($year);
        $this->output->set_output(json_encode($stats));
    }

    /**
     * API สำหรับดึงสถิติการโอนย้าย
     */
    public function transfer_statistics($year = null)
    {
        $stats = $this->Transfer_model->get_transfer_statistics($year);
        $this->output->set_output(json_encode($stats));
    }

    /**
     * API สำหรับดึงสถิติการจำหน่าย
     */
    public function disposal_statistics($year = null)
    {
        $stats = $this->Disposal_model->get_disposal_statistics($year);
        $this->output->set_output(json_encode($stats));
    }

    /**
     * API สำหรับดึงข้อมูลการค้ำประกันที่ใกล้หมดอายุ
     */
    public function expiring_guarantees($days = 30)
    {
        $guarantees = $this->Guarantee_model->get_expiring_guarantees($days);
        $this->output->set_output(json_encode($guarantees));
    }

    /**
     * API สำหรับอัปเดตสถานะครุภัณฑ์
     */
    public function update_asset_status()
    {
        $asset_id = $this->input->post('asset_id');
        $status = $this->input->post('status');
        
        if (!$asset_id || !$status) {
            $this->output->set_status_header(400);
            $this->output->set_output(json_encode(['error' => 'ข้อมูลไม่ครบถ้วน']));
            return;
        }
        
        if ($this->Asset_model->update_asset_status($asset_id, $status)) {
            $this->output->set_output(json_encode(['success' => true, 'message' => 'อัปเดตสถานะเรียบร้อยแล้ว']));
        } else {
            $this->output->set_status_header(500);
            $this->output->set_output(json_encode(['error' => 'เกิดข้อผิดพลาดในการอัปเดต']));
        }
    }

    /**
     * API สำหรับดึงข้อมูลสำหรับ Dashboard
     */
    public function dashboard_data()
    {
        $data = [
            'asset_stats' => $this->Asset_model->get_asset_statistics(),
            'recent_transfers' => $this->Transfer_model->get_recent_transfers(5),
            'recent_repairs' => $this->Repair_model->get_recent_repairs(5),
            'expiring_guarantees' => $this->Guarantee_model->get_expiring_guarantees(30),
            'assets_need_repair' => $this->Asset_model->get_assets_need_repair()
        ];
        
        $this->output->set_output(json_encode($data));
    }

    /**
     * API สำหรับค้นหาครุภัณฑ์
     */
    public function search_assets()
    {
        $keyword = $this->input->get('keyword');
        $type = $this->input->get('type');
        $status = $this->input->get('status');
        $location = $this->input->get('location');
        
        $assets = $this->Asset_model->search_assets($keyword, $type, $status, $location);
        $this->output->set_output(json_encode($assets));
    }

    /**
     * API สำหรับดึงรายการประเภทครุภัณฑ์
     */
    public function asset_types()
    {
        $types = $this->Asset_model->get_asset_types();
        $this->output->set_output(json_encode($types));
    }

    /**
     * API สำหรับดึงรายการสถานที่
     */
    public function locations()
    {
        $locations = $this->Asset_model->get_locations();
        $this->output->set_output(json_encode($locations));
    }
}

