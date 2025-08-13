<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Asset_model');
        $this->load->model('Survey_model');
        $this->load->model('Depreciation_model');
        $this->load->model('Transfer_model');
        $this->load->model('Disposal_model');
        $this->load->model('Repair_model');
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
    }

    /**
     * หน้าหลักรายงาน
     */
    public function index()
    {
        $data = array();
        
        // สถิติรวม
        $data['total_assets'] = $this->Asset_model->count_assets();
        $data['active_assets'] = $this->Asset_model->count_assets_by_status('ใช้งาน');
        $data['repair_assets'] = $this->Asset_model->count_assets_by_status('ซ่อมแซม');
        $data['disposed_assets'] = $this->Asset_model->count_assets_by_status('จำหน่ายแล้ว');
        
        // สถิติการซ่อมแซม
        $data['pending_repairs'] = $this->Repair_model->count_repairs_by_status('รอพิจารณา');
        $data['approved_repairs'] = $this->Repair_model->count_repairs_by_status('อนุมัติ');
        $data['completed_repairs'] = $this->Repair_model->count_repairs_by_status('เสร็จสิ้น');
        
        // มูลค่าครุภัณฑ์
        $data['total_value'] = $this->Asset_model->get_total_asset_value();
        $data['depreciation_value'] = $this->Depreciation_model->get_total_depreciation_value();
        $data['book_value'] = $data['total_value'] - $data['depreciation_value'];
        
        $data['page_title'] = 'รายงานและสถิติ - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'reports';
        
        $this->load->view('templates/header', $data);
        $this->load->view('reports/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * รายงานสำรวจครุภัณฑ์ประจำปี
     */
    public function annual_survey()
    {
        $data = array();
        
        $year = $this->input->get('year') ?: date('Y');
        $location = $this->input->get('location');
        $category = $this->input->get('category');
        $status = $this->input->get('status');
        
        // ดึงข้อมูลครุภัณฑ์สำหรับสำรวจ
        $data['assets'] = $this->Asset_model->get_assets_for_survey($year, $location, $category, $status);
        
        // สถิติการสำรวจ
        $data['survey_stats'] = $this->Survey_model->get_survey_statistics($year);
        
        // รายการสถานที่และประเภท
        $data['locations'] = $this->Asset_model->get_distinct_locations();
        $data['categories'] = $this->Asset_model->get_distinct_categories();
        
        $data['selected_year'] = $year;
        $data['selected_location'] = $location;
        $data['selected_category'] = $category;
        $data['selected_status'] = $status;
        
        $data['page_title'] = 'รายงานสำรวจครุภัณฑ์ประจำปี ' . ($year + 543);
        $data['page_name'] = 'annual_survey';
        
        $this->load->view('templates/header', $data);
        $this->load->view('reports/annual_survey', $data);
        $this->load->view('templates/footer');
    }

    /**
     * รายงานค่าเสื่อมราคา
     */
    public function depreciation()
    {
        $data = array();
        
        $year = $this->input->get('year') ?: date('Y');
        $month = $this->input->get('month');
        $category = $this->input->get('category');
        
        // ดึงข้อมูลค่าเสื่อมราคา
        $data['depreciation_data'] = $this->Depreciation_model->get_depreciation_report($year, $month, $category);
        
        // สรุปค่าเสื่อมราคา
        $data['depreciation_summary'] = $this->Depreciation_model->get_depreciation_summary($year);
        
        // กราฟค่าเสื่อมราคารายเดือน
        $data['monthly_depreciation'] = $this->Depreciation_model->get_monthly_depreciation($year);
        
        // รายการประเภท
        $data['categories'] = $this->Asset_model->get_distinct_categories();
        
        $data['selected_year'] = $year;
        $data['selected_month'] = $month;
        $data['selected_category'] = $category;
        
        $data['page_title'] = 'รายงานค่าเสื่อมราคาครุภัณฑ์ ปี ' . ($year + 543);
        $data['page_name'] = 'depreciation_report';
        
        $this->load->view('templates/header', $data);
        $this->load->view('reports/depreciation', $data);
        $this->load->view('templates/footer');
    }

    /**
     * รายงานสถานะครุภัณฑ์
     */
    public function asset_status()
    {
        $data = array();
        
        $location = $this->input->get('location');
        $category = $this->input->get('category');
        $status = $this->input->get('status');
        
        // ดึงข้อมูลครุภัณฑ์ตามสถานะ
        $data['assets'] = $this->Asset_model->get_assets_by_filters($location, $category, $status);
        
        // สถิติตามสถานะ
        $data['status_stats'] = $this->Asset_model->get_asset_status_statistics();
        
        // สถิติตามสถานที่
        $data['location_stats'] = $this->Asset_model->get_asset_location_statistics();
        
        // สถิติตามประเภท
        $data['category_stats'] = $this->Asset_model->get_asset_category_statistics();
        
        // รายการสถานที่และประเภท
        $data['locations'] = $this->Asset_model->get_distinct_locations();
        $data['categories'] = $this->Asset_model->get_distinct_categories();
        
        $data['selected_location'] = $location;
        $data['selected_category'] = $category;
        $data['selected_status'] = $status;
        
        $data['page_title'] = 'รายงานสถานะครุภัณฑ์';
        $data['page_name'] = 'asset_status_report';
        
        $this->load->view('templates/header', $data);
        $this->load->view('reports/asset_status', $data);
        $this->load->view('templates/footer');
    }

    /**
     * รายงานการโอนย้ายครุภัณฑ์
     */
    public function transfers()
    {
        $data = array();
        
        $year = $this->input->get('year') ?: date('Y');
        $month = $this->input->get('month');
        $from_location = $this->input->get('from_location');
        $to_location = $this->input->get('to_location');
        
        // ดึงข้อมูลการโอนย้าย
        $data['transfers'] = $this->Transfer_model->get_transfers_for_report($year, $month, $from_location, $to_location);
        
        // สถิติการโอนย้าย
        $data['transfer_stats'] = $this->Transfer_model->get_transfer_statistics($year);
        
        // รายการสถานที่
        $data['locations'] = $this->Asset_model->get_distinct_locations();
        
        $data['selected_year'] = $year;
        $data['selected_month'] = $month;
        $data['selected_from_location'] = $from_location;
        $data['selected_to_location'] = $to_location;
        
        $data['page_title'] = 'รายงานการโอนย้ายครุภัณฑ์ ปี ' . ($year + 543);
        $data['page_name'] = 'transfer_report';
        
        $this->load->view('templates/header', $data);
        $this->load->view('reports/transfers', $data);
        $this->load->view('templates/footer');
    }

    /**
     * รายงานการจำหน่ายครุภัณฑ์
     */
    public function disposals()
    {
        $data = array();
        
        $year = $this->input->get('year') ?: date('Y');
        $month = $this->input->get('month');
        $disposal_method = $this->input->get('disposal_method');
        
        // ดึงข้อมูลการจำหน่าย
        $data['disposals'] = $this->Disposal_model->get_disposals_for_report($year, $month, $disposal_method);
        
        // สถิติการจำหน่าย
        $data['disposal_stats'] = $this->Disposal_model->get_disposal_statistics($year);
        
        // สรุปมูลค่าการจำหน่าย
        $data['disposal_value_summary'] = $this->Disposal_model->get_disposal_value_summary($year);
        
        $data['selected_year'] = $year;
        $data['selected_month'] = $month;
        $data['selected_disposal_method'] = $disposal_method;
        
        $data['page_title'] = 'รายงานการจำหน่ายครุภัณฑ์ ปี ' . ($year + 543);
        $data['page_name'] = 'disposal_report';
        
        $this->load->view('templates/header', $data);
        $this->load->view('reports/disposals', $data);
        $this->load->view('templates/footer');
    }

    /**
     * รายงานการซ่อมแซม
     */
    public function repairs()
    {
        $data = array();
        
        $year = $this->input->get('year') ?: date('Y');
        $month = $this->input->get('month');
        $status = $this->input->get('status');
        $priority = $this->input->get('priority');
        
        // ดึงข้อมูลการซ่อมแซม
        $data['repairs'] = $this->Repair_model->get_repairs_for_report($year, $month, $status);
        
        // สถิติการซ่อมแซม
        $data['repair_stats'] = $this->Repair_model->get_repair_statistics($year);
        
        // สรุปค่าใช้จ่ายการซ่อมแซม
        $data['repair_cost_summary'] = $this->Repair_model->get_repair_cost_summary($year);
        
        $data['selected_year'] = $year;
        $data['selected_month'] = $month;
        $data['selected_status'] = $status;
        $data['selected_priority'] = $priority;
        
        $data['page_title'] = 'รายงานการซ่อมแซมครุภัณฑ์ ปี ' . ($year + 543);
        $data['page_name'] = 'repair_report';
        
        $this->load->view('templates/header', $data);
        $this->load->view('reports/repairs', $data);
        $this->load->view('templates/footer');
    }

    /**
     * รายงานมูลค่าครุภัณฑ์
     */
    public function asset_value()
    {
        $data = array();
        
        $year = $this->input->get('year') ?: date('Y');
        $category = $this->input->get('category');
        $location = $this->input->get('location');
        
        // ดึงข้อมูลมูลค่าครุภัณฑ์
        $data['asset_values'] = $this->Asset_model->get_asset_value_report($year, $category, $location);
        
        // สรุปมูลค่าตามประเภท
        $data['value_by_category'] = $this->Asset_model->get_value_by_category();
        
        // สรุปมูลค่าตามสถานที่
        $data['value_by_location'] = $this->Asset_model->get_value_by_location();
        
        // แนวโน้มมูลค่าครุภัณฑ์
        $data['value_trend'] = $this->Asset_model->get_asset_value_trend($year);
        
        // รายการประเภทและสถานที่
        $data['categories'] = $this->Asset_model->get_distinct_categories();
        $data['locations'] = $this->Asset_model->get_distinct_locations();
        
        $data['selected_year'] = $year;
        $data['selected_category'] = $category;
        $data['selected_location'] = $location;
        
        $data['page_title'] = 'รายงานมูลค่าครุภัณฑ์ ปี ' . ($year + 543);
        $data['page_name'] = 'asset_value_report';
        
        $this->load->view('templates/header', $data);
        $this->load->view('reports/asset_value', $data);
        $this->load->view('templates/footer');
    }

    /**
     * ส่งออกรายงานสำรวจครุภัณฑ์เป็น CSV
     */
    public function export_survey()
    {
        $year = $this->input->get('year') ?: date('Y');
        $location = $this->input->get('location');
        $category = $this->input->get('category');
        $status = $this->input->get('status');
        
        $assets = $this->Asset_model->get_assets_for_survey($year, $location, $category, $status);
        
        $filename = 'asset_survey_' . $year . '_' . date('Y-m-d_H-i-s') . '.csv';
        
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
            'สถานที่ตั้ง',
            'ผู้รับผิดชอบ',
            'วันที่จัดซื้อ',
            'ราคาทุน',
            'ค่าเสื่อมสะสม',
            'มูลค่าตามบัญชี',
            'สถานะ',
            'หมายเหตุ'
        ));
        
        // เขียนข้อมูล
        foreach ($assets as $asset) {
            $book_value = $asset['purchase_price'] - $asset['accumulated_depreciation'];
            
            fputcsv($output, array(
                $asset['asset_code'],
                $asset['asset_name'],
                $asset['category'],
                $asset['serial_number'] ?: '-',
                $asset['current_location'],
                $asset['responsible_person'],
                $asset['purchase_date'],
                number_format($asset['purchase_price'], 2),
                number_format($asset['accumulated_depreciation'], 2),
                number_format($book_value, 2),
                $asset['status'],
                $asset['notes'] ?: '-'
            ));
        }
        
        fclose($output);
    }

    /**
     * ส่งออกรายงานค่าเสื่อมราคาเป็น CSV
     */
    public function export_depreciation()
    {
        $year = $this->input->get('year') ?: date('Y');
        $month = $this->input->get('month');
        $category = $this->input->get('category');
        
        $depreciation_data = $this->Depreciation_model->get_depreciation_report($year, $month, $category);
        
        $filename = 'depreciation_report_' . $year . '_' . date('Y-m-d_H-i-s') . '.csv';
        
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
            'วันที่จัดซื้อ',
            'ราคาทุน',
            'อายุการใช้งาน (ปี)',
            'วิธีคิดค่าเสื่อม',
            'ค่าเสื่อมรายปี',
            'ค่าเสื่อมสะสม',
            'มูลค่าตามบัญชี',
            'สถานะ'
        ));
        
        // เขียนข้อมูล
        foreach ($depreciation_data as $item) {
            fputcsv($output, array(
                $item['asset_code'],
                $item['asset_name'],
                $item['category'],
                $item['purchase_date'],
                number_format($item['purchase_price'], 2),
                $item['useful_life'],
                $item['depreciation_method'],
                number_format($item['annual_depreciation'], 2),
                number_format($item['accumulated_depreciation'], 2),
                number_format($item['book_value'], 2),
                $item['status']
            ));
        }
        
        fclose($output);
    }

    /**
     * พิมพ์รายงานสำรวจครุภัณฑ์
     */
    public function print_survey()
    {
        $data = array();
        
        $year = $this->input->get('year') ?: date('Y');
        $location = $this->input->get('location');
        $category = $this->input->get('category');
        $status = $this->input->get('status');
        
        $data['assets'] = $this->Asset_model->get_assets_for_survey($year, $location, $category, $status);
        $data['survey_stats'] = $this->Survey_model->get_survey_statistics($year);
        
        $data['selected_year'] = $year;
        $data['selected_location'] = $location;
        $data['selected_category'] = $category;
        $data['selected_status'] = $status;
        
        $data['page_title'] = 'รายงานสำรวจครุภัณฑ์ประจำปี ' . ($year + 543);
        
        $this->load->view('reports/print_survey', $data);
    }

    /**
     * พิมพ์รายงานค่าเสื่อมราคา
     */
    public function print_depreciation()
    {
        $data = array();
        
        $year = $this->input->get('year') ?: date('Y');
        $month = $this->input->get('month');
        $category = $this->input->get('category');
        
        $data['depreciation_data'] = $this->Depreciation_model->get_depreciation_report($year, $month, $category);
        $data['depreciation_summary'] = $this->Depreciation_model->get_depreciation_summary($year);
        
        $data['selected_year'] = $year;
        $data['selected_month'] = $month;
        $data['selected_category'] = $category;
        
        $data['page_title'] = 'รายงานค่าเสื่อมราคาครุภัณฑ์ ปี ' . ($year + 543);
        
        $this->load->view('reports/print_depreciation', $data);
    }

    /**
     * API สำหรับกราฟ - ข้อมูลสถิติครุภัณฑ์
     */
    public function api_asset_statistics()
    {
        $year = $this->input->get('year') ?: date('Y');
        
        $data = array(
            'status_stats' => $this->Asset_model->get_asset_status_statistics(),
            'category_stats' => $this->Asset_model->get_asset_category_statistics(),
            'location_stats' => $this->Asset_model->get_asset_location_statistics(),
            'monthly_acquisitions' => $this->Asset_model->get_monthly_acquisitions($year),
            'value_trend' => $this->Asset_model->get_asset_value_trend($year)
        );
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    /**
     * API สำหรับกราฟ - ข้อมูลค่าเสื่อมราคา
     */
    public function api_depreciation_chart()
    {
        $year = $this->input->get('year') ?: date('Y');
        
        $data = array(
            'monthly_depreciation' => $this->Depreciation_model->get_monthly_depreciation($year),
            'category_depreciation' => $this->Depreciation_model->get_depreciation_by_category($year),
            'depreciation_trend' => $this->Depreciation_model->get_depreciation_trend($year)
        );
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}

