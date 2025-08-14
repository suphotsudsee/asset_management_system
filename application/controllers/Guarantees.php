<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guarantees extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Guarantee_model');
        $this->load->model('Asset_model');
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'form_validation'));

        // ลบบรรทัดนี้ทิ้ง: $data['guarantees'] = $this->Guarantee_model->get_all();
        // เพราะไม่ได้ถูกส่งเข้า view และทำให้ query ทุกครั้งโดยไม่จำเป็น
    }

    /** แสดงรายการค้ำประกันสัญญาทั้งหมด */
    public function index()
    {
        $data = array();

        // --- Filters ---
        $keyword       = $this->input->get('search');
        $status        = $this->input->get('status');
        $vendor        = $this->input->get('vendor');
        $expiry_filter = $this->input->get('expiry_filter');

        // ใช้เมธอดที่ "คืนฟิลด์จาก assets ด้วย" เพื่อให้มี asset_name/asset_code
        if ($keyword || $status || $vendor || $expiry_filter) {
            // **สำคัญ**: ให้ search_guarantees() join กับ assets และ select a.asset_name, a.asset_code
            $data['guarantees'] = $this->Guarantee_model->search_guarantees($keyword, $status, $vendor, $expiry_filter);
        } else {
            // ใช้ get_all() ที่ join assets
            $data['guarantees'] = $this->Guarantee_model->get_all();
        }

        // รายการผู้จำหน่าย
        $data['vendors'] = $this->Guarantee_model->get_distinct_vendors();

        // ค่า filter ที่เลือกไว้
        $data['search_keyword']      = $keyword;
        $data['selected_status']     = $status;
        $data['selected_vendor']     = $vendor;
        $data['selected_expiry_filter'] = $expiry_filter;

        // --- สถิติค้ำประกัน: map key ให้ตรงกับ view ($summary['...']) ---
        $stats = (array) $this->Guarantee_model->get_guarantee_statistics();
        $data['summary'] = [
            'total_guarantees'         => $stats['total']         ?? 0,
            'active_guarantees'        => $stats['active_count']  ?? 0,
            'expired_guarantees'       => $stats['expired_count'] ?? 0,
            'expiring_soon_guarantees' => $stats['expiring_soon'] ?? 0,
        ];
        // ส่งของเดิมเผื่อส่วนอื่นใช้
        $data['guarantee_stats'] = $stats;

        $data['page_title'] = 'ข้อมูลค้ำประกันสัญญา - ระบบจัดการครุภัณฑ์';
        $data['page_name']  = 'guarantees';

        $this->load->view('templates/header', $data);
        $this->load->view('guarantees/index', $data);
        $this->load->view('templates/footer');
    }

    /** แสดงรายละเอียดค้ำประกันสัญญา */
    public function view($guarantee_id)
    {
        $data = array();

        $data['guarantee'] = $this->Guarantee_model->get_guarantee_by_id($guarantee_id);
        if (!$data['guarantee']) show_404();

        $data['renewal_history'] = $this->Guarantee_model->get_renewal_history($guarantee_id);
        $data['claims']          = $this->Guarantee_model->get_guarantee_claims($guarantee_id);

        $data['page_title'] = 'รายละเอียดค้ำประกันสัญญา #'.$guarantee_id;
        $data['page_name']  = 'guarantee_detail';

        $this->load->view('templates/header', $data);
        $this->load->view('guarantees/view', $data);
        $this->load->view('templates/footer');
    }

    /** แสดงฟอร์มเพิ่มค้ำประกันสัญญาใหม่ */
    public function add()
    {
        $data = array();
        $data['assets']            = $this->Asset_model->get_assets_without_active_guarantee();
        $data['selected_asset_id'] = $this->input->get('asset_id');
        $data['page_title']        = 'เพิ่มข้อมูลค้ำประกันสัญญา - ระบบจัดการครุภัณฑ์';
        $data['page_name']         = 'add_guarantee';

        $this->load->view('templates/header', $data);
        $this->load->view('guarantees/add', $data);
        $this->load->view('templates/footer');
    }

    /** บันทึกข้อมูลค้ำประกันสัญญา */
    public function store()
    {
        $this->form_validation->set_rules('asset_id', 'ครุภัณฑ์', 'required|numeric');
        $this->form_validation->set_rules('guarantee_type', 'ประเภทค้ำประกัน', 'required');
        $this->form_validation->set_rules('vendor_name', 'ชื่อผู้จำหน่าย/ผู้ให้บริการ', 'required|trim');
        $this->form_validation->set_rules('vendor_contact', 'ข้อมูลติดต่อผู้จำหน่าย', 'required|trim');
        $this->form_validation->set_rules('start_date', 'วันที่เริ่มต้น', 'required');
        $this->form_validation->set_rules('end_date', 'วันที่สิ้นสุด', 'required');
        $this->form_validation->set_rules('contract_number', 'เลขที่สัญญา', 'trim');

        if ($this->form_validation->run() == FALSE) { $this->add(); return; }

        $asset_id   = $this->input->post('asset_id');
        $start_date = $this->input->post('start_date');
        $end_date   = $this->input->post('end_date');

        $asset = $this->Asset_model->get_asset_by_id($asset_id);
        if (!$asset) { $this->session->set_flashdata('error','ไม่พบครุภัณฑ์ที่ระบุ'); $this->add(); return; }

        if (strtotime($end_date) <= strtotime($start_date)) {
            $this->session->set_flashdata('error','วันที่สิ้นสุดต้องมากกว่าวันที่เริ่มต้น'); $this->add(); return;
        }

        if ($this->Guarantee_model->has_active_guarantee($asset_id, $start_date, $end_date)) {
            $this->session->set_flashdata('error','ครุภัณฑ์นี้มีค้ำประกันที่ยังใช้งานอยู่ในช่วงเวลาที่ระบุ'); $this->add(); return;
        }

        $data = array(
            'asset_id'         => $asset_id,
            'guarantee_type'   => $this->input->post('guarantee_type'),
            'vendor_name'      => $this->input->post('vendor_name'),
            'vendor_contact'   => $this->input->post('vendor_contact'),
            'contract_number'  => $this->input->post('contract_number'),
            'start_date'       => $start_date,
            'end_date'         => $end_date,
            'coverage_details' => $this->input->post('coverage_details'),
            'terms_conditions' => $this->input->post('terms_conditions'),
            'claim_procedure'  => $this->input->post('claim_procedure'),
            'notes'            => $this->input->post('notes'),
            'status'           => 'ใช้งาน',
            'created_date'     => date('Y-m-d H:i:s')
        );

        if ($this->Guarantee_model->insert_guarantee($data)) {
            $this->session->set_flashdata('success','บันทึกข้อมูลค้ำประกันสัญญาเรียบร้อยแล้ว');
            redirect('guarantees');
        } else {
            $this->session->set_flashdata('error','เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            $this->add();
        }
    }

    /** แสดงฟอร์มแก้ไขค้ำประกันสัญญา */
    public function edit($guarantee_id)
    {
        $data = array();
        $data['guarantee'] = $this->Guarantee_model->get_guarantee_by_id($guarantee_id);
        if (!$data['guarantee']) show_404();

        $data['assets']    = $this->Asset_model->get_assets_for_guarantee_edit($data['guarantee']['asset_id']);
        $data['page_title']= 'แก้ไขข้อมูลค้ำประกันสัญญา #'.$guarantee_id;
        $data['page_name'] = 'edit_guarantee';

        $this->load->view('templates/header', $data);
        $this->load->view('guarantees/edit', $data);
        $this->load->view('templates/footer');
    }

    /** อัปเดตข้อมูลค้ำประกันสัญญา */
    public function update($guarantee_id)
    {
        $guarantee = $this->Guarantee_model->get_guarantee_by_id($guarantee_id);
        if (!$guarantee) show_404();

        $this->form_validation->set_rules('guarantee_type', 'ประเภทค้ำประกัน', 'required');
        $this->form_validation->set_rules('vendor_name', 'ชื่อผู้จำหน่าย/ผู้ให้บริการ', 'required|trim');
        $this->form_validation->set_rules('vendor_contact', 'ข้อมูลติดต่อผู้จำหน่าย', 'required|trim');
        $this->form_validation->set_rules('start_date', 'วันที่เริ่มต้น', 'required');
        $this->form_validation->set_rules('end_date', 'วันที่สิ้นสุด', 'required');
        $this->form_validation->set_rules('status', 'สถานะ', 'required');

        if ($this->form_validation->run() == FALSE) { $this->edit($guarantee_id); return; }

        $start_date = $this->input->post('start_date');
        $end_date   = $this->input->post('end_date');
        if (strtotime($end_date) <= strtotime($start_date)) {
            $this->session->set_flashdata('error','วันที่สิ้นสุดต้องมากกว่าวันที่เริ่มต้น'); $this->edit($guarantee_id); return;
        }

        $data = array(
            'guarantee_type'   => $this->input->post('guarantee_type'),
            'vendor_name'      => $this->input->post('vendor_name'),
            'vendor_contact'   => $this->input->post('vendor_contact'),
            'contract_number'  => $this->input->post('contract_number'),
            'start_date'       => $start_date,
            'end_date'         => $end_date,
            'coverage_details' => $this->input->post('coverage_details'),
            'terms_conditions' => $this->input->post('terms_conditions'),
            'claim_procedure'  => $this->input->post('claim_procedure'),
            'notes'            => $this->input->post('notes'),
            'status'           => $this->input->post('status'),
            'updated_date'     => date('Y-m-d H:i:s')
        );

        if ($this->Guarantee_model->update_guarantee($guarantee_id, $data)) {
            $this->session->set_flashdata('success','อัปเดตข้อมูลค้ำประกันสัญญาเรียบร้อยแล้ว');
            redirect('guarantees/view/'.$guarantee_id);
        } else {
            $this->session->set_flashdata('error','เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
            $this->edit($guarantee_id);
        }
    }

    /** ลบค้ำประกันสัญญา */
    public function delete($guarantee_id)
    {
        $guarantee = $this->Guarantee_model->get_guarantee_by_id($guarantee_id);
        if (!$guarantee) show_404();

        if ($this->Guarantee_model->delete_guarantee($guarantee_id)) {
            $this->session->set_flashdata('success','ลบข้อมูลค้ำประกันสัญญาเรียบร้อยแล้ว');
        } else {
            $this->session->set_flashdata('error','เกิดข้อผิดพลาดในการลบข้อมูล');
        }
        redirect('guarantees');
    }

    /** ต่ออายุค้ำประกันสัญญา */
    public function renew($guarantee_id)
    {
        $data = array();
        $data['guarantee'] = $this->Guarantee_model->get_guarantee_by_id($guarantee_id);
        if (!$data['guarantee']) show_404();

        $data['page_title'] = 'ต่ออายุค้ำประกันสัญญา #'.$guarantee_id;
        $data['page_name']  = 'renew_guarantee';

        $this->load->view('templates/header', $data);
        $this->load->view('guarantees/renew', $data);
        $this->load->view('templates/footer');
    }

    /** บันทึกการต่ออายุค้ำประกันสัญญา */
    public function process_renewal($guarantee_id)
    {
        $guarantee = $this->Guarantee_model->get_guarantee_by_id($guarantee_id);
        if (!$guarantee) show_404();

        $this->form_validation->set_rules('new_end_date', 'วันที่สิ้นสุดใหม่', 'required');
        $this->form_validation->set_rules('renewal_cost', 'ค่าใช้จ่ายในการต่ออายุ', 'numeric');

        if ($this->form_validation->run() == FALSE) { $this->renew($guarantee_id); return; }

        $new_end_date      = $this->input->post('new_end_date');
        $current_end_date  = $guarantee['end_date'];
        if (strtotime($new_end_date) <= strtotime($current_end_date)) {
            $this->session->set_flashdata('error','วันที่สิ้นสุดใหม่ต้องมากกว่าวันที่สิ้นสุดเดิม'); $this->renew($guarantee_id); return;
        }

        $renewal_data = array(
            'guarantee_id'  => $guarantee_id,
            'old_end_date'  => $current_end_date,
            'new_end_date'  => $new_end_date,
            'renewal_cost'  => $this->input->post('renewal_cost') ?: 0,
            'renewal_notes' => $this->input->post('renewal_notes'),
            'renewed_by'    => 'ผู้ดูแลระบบ',
            'renewal_date'  => date('Y-m-d H:i:s')
        );

        $guarantee_update = array(
            'end_date'     => $new_end_date,
            'status'       => 'ใช้งาน',
            'updated_date' => date('Y-m-d H:i:s')
        );

        if ($this->Guarantee_model->renew_guarantee($guarantee_id, $guarantee_update, $renewal_data)) {
            $this->session->set_flashdata('success','ต่ออายุค้ำประกันสัญญาเรียบร้อยแล้ว');
            redirect('guarantees/view/'.$guarantee_id);
        } else {
            $this->session->set_flashdata('error','เกิดข้อผิดพลาดในการต่ออายุ');
            $this->renew($guarantee_id);
        }
    }

    /** เคลมประกัน */
    public function claim($guarantee_id)
    {
        $data = array();
        $data['guarantee'] = $this->Guarantee_model->get_guarantee_by_id($guarantee_id);
        if (!$data['guarantee']) show_404();

        if ($data['guarantee']['status'] != 'ใช้งาน' || strtotime($data['guarantee']['end_date']) < time()) {
            $this->session->set_flashdata('error','ค้ำประกันนี้หมดอายุหรือไม่สามารถใช้งานได้แล้ว');
            redirect('guarantees/view/'.$guarantee_id);
            return;
        }

        $data['page_title'] = 'เคลมประกัน #'.$guarantee_id;
        $data['page_name']  = 'claim_guarantee';

        $this->load->view('templates/header', $data);
        $this->load->view('guarantees/claim', $data);
        $this->load->view('templates/footer');
    }

    /** บันทึกการเคลมประกัน */
    public function process_claim($guarantee_id)
    {
        $guarantee = $this->Guarantee_model->get_guarantee_by_id($guarantee_id);
        if (!$guarantee) show_404();

        $this->form_validation->set_rules('claim_reason', 'เหตุผลในการเคลม', 'required|trim');
        $this->form_validation->set_rules('claim_description', 'รายละเอียดการเคลม', 'required|trim');
        $this->form_validation->set_rules('claim_amount', 'จำนวนเงินที่เคลม', 'numeric');

        if ($this->form_validation->run() == FALSE) { $this->claim($guarantee_id); return; }

        $claim_data = array(
            'guarantee_id'     => $guarantee_id,
            'claim_reason'     => $this->input->post('claim_reason'),
            'claim_description'=> $this->input->post('claim_description'),
            'claim_amount'     => $this->input->post('claim_amount') ?: 0,
            'claim_date'       => $this->input->post('claim_date') ?: date('Y-m-d'),
            'claim_status'     => 'รอดำเนินการ',
            'claimed_by'       => 'ผู้ดูแลระบบ',
            'created_date'     => date('Y-m-d H:i:s')
        );

        if ($this->Guarantee_model->insert_claim($claim_data)) {
            $this->session->set_flashdata('success','บันทึกการเคลมประกันเรียบร้อยแล้ว');
            redirect('guarantees/view/'.$guarantee_id);
        } else {
            $this->session->set_flashdata('error','เกิดข้อผิดพลาดในการบันทึกการเคลม');
            $this->claim($guarantee_id);
        }
    }

    /** รายงานค้ำประกันที่ใกล้หมดอายุ */
    public function expiring()
    {
        $data = array();
        $days = $this->input->get('days') ?: 30;

        $data['expiring_guarantees'] = $this->Guarantee_model->get_expiring_guarantees($days);
        $data['selected_days']       = $days;
        $data['page_title']          = 'ค้ำประกันที่ใกล้หมดอายุ ('.$days.' วัน)';
        $data['page_name']           = 'expiring_guarantees';

        $this->load->view('templates/header', $data);
        $this->load->view('guarantees/expiring', $data);
        $this->load->view('templates/footer');
    }

    /** ส่งออกข้อมูลค้ำประกันเป็น CSV */
    public function export()
    {
        // ใช้ get_all() เพื่อให้มี asset_name/asset_code มาครบ
        $guarantees = $this->Guarantee_model->get_all();

        $filename = 'guarantees_'.date('Y-m-d_H-i-s').'.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename='.$filename);

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

        fputcsv($output, array(
            'รหัสค้ำประกัน','ชื่อครุภัณฑ์','รหัสครุภัณฑ์','ประเภทค้ำประกัน',
            'ผู้จำหน่าย/ผู้ให้บริการ','เลขที่สัญญา','วันที่เริ่มต้น','วันที่สิ้นสุด',
            'จำนวนวันคงเหลือ','สถานะ','รายละเอียดความคุ้มครอง'
        ));

        $today = new DateTime('today');
        foreach ($guarantees as $g) {
            $end   = new DateTime($g['end_date']);
            $diff  = (int)$today->diff($end)->format('%r%a'); // อาจติดลบ
            $left  = max(0, $diff);

            fputcsv($output, array(
                $g['guarantee_id'],
                $g['asset_name'] ?? '-',
                $g['asset_code'] ?? '-',
                $g['guarantee_type'],
                $g['vendor_name'],
                $g['contract_number'] ?: '-',
                $g['start_date'],
                $g['end_date'],
                $left,
                $g['status'],
                $g['coverage_details'] ?: '-'
            ));
        }
        fclose($output);
    }

    /** AJAX: ดึงข้อมูลครุภัณฑ์ */
    public function api_get_asset_info()
    {
        $asset_id = $this->input->post('asset_id');
        $asset = $this->Asset_model->get_asset_by_id($asset_id);

        if ($asset) {
            $existing = $this->Guarantee_model->get_active_guarantee_by_asset($asset_id);
            $response = array('success'=>true, 'asset'=>$asset, 'existing_guarantee'=>$existing);
        } else {
            $response = array('success'=>false, 'message'=>'ไม่พบครุภัณฑ์ที่ระบุ');
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    /** AJAX: อัปเดตสถานะค้ำประกัน */
    public function api_update_status()
    {
        $guarantee_id = $this->input->post('guarantee_id');
        $status       = $this->input->post('status');

        $ok = $this->Guarantee_model->update_guarantee_status($guarantee_id, $status);
        $this->output->set_content_type('application/json')
            ->set_output(json_encode($ok ? ['success'=>true,'message'=>'อัปเดตสถานะเรียบร้อยแล้ว']
                                          : ['success'=>false,'message'=>'เกิดข้อผิดพลาดในการอัปเดต']));
    }


    

}
