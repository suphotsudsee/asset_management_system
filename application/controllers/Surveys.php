<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surveys extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Asset_model');
        $this->load->model('Survey_model');
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));
    }

    public function index()
    {
        $year = $this->input->get('year') ?: date('Y');
        $location = $this->input->get('location');
        $category = $this->input->get('category');
        $status = $this->input->get('status');

        $data['assets'] = $this->Asset_model->get_assets_for_survey($year, $location, $category, $status);
        $data['survey_stats'] = $this->Survey_model->get_survey_statistics($year);
        $data['locations'] = $this->Asset_model->get_distinct_locations();
        $data['categories'] = $this->Asset_model->get_distinct_categories();

        $data['selected_year'] = $year;
        $data['selected_location'] = $location;
        $data['selected_category'] = $category;
        $data['selected_status'] = $status;

        $data['page_title'] = 'สำรวจครุภัณฑ์ประจำปี ' . ($year + 543) . ' - ระบบจัดการครุภัณฑ์';
        $data['page_name'] = 'surveys';

        $this->load->view('templates/header', $data);
        $this->load->view('surveys/index', $data);
        $this->load->view('templates/footer');
    }

    public function save()
    {
        $asset_id = $this->input->post('asset_id');
        $year = $this->input->post('year');
        $condition = $this->input->post('condition');
        $notes = $this->input->post('notes');
        $surveyed_by = $this->session->userdata('username') ?: 'ผู้ดูแลระบบ';
        $survey_date = date('Y-m-d');

        if ($asset_id && $year && $condition) {
            $data = array(
                'asset_id' => $asset_id,
                'survey_year' => $year,
                'condition' => $condition,
                'notes' => $notes,
                'surveyed_by' => $surveyed_by,
                'survey_date' => $survey_date
            );
            $this->Survey_model->insert_survey($data);
            $this->session->set_flashdata('success', 'บันทึกการสำรวจเรียบร้อยแล้ว');
        }

        redirect('surveys?year=' . $year);
    }
}
