<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guarantee_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * ดึงข้อมูลค้ำประกันสัญญาทั้งหมด
     */
    public function get_all_guarantees($limit = null, $offset = null)
    {
        $this->db->select('g.*');
        $this->db->from('contract_guarantees g');

        $hasAsset = $this->apply_asset_join();

        $this->db->order_by('g.created_at', 'DESC');

        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();
        $results = $query->result_array();

        if (!$hasAsset) {
            foreach ($results as &$row) {
                $row['asset_code'] = null;
                $row['asset_name'] = null;
            }
            unset($row);
        }

        return $results;
    }

    /**
     * ดึงข้อมูลค้ำประกันสัญญาตาม ID
     */
    public function get_guarantee_by_id($guarantee_id)
    {
        $this->db->select('g.*');
        $this->db->from('contract_guarantees g');

        $hasAsset = $this->apply_asset_join();

        $this->db->where('g.guarantee_id', $guarantee_id);
        $query = $this->db->get();
        $result = $query->row_array();

        if (!$hasAsset && $result) {
            $result['asset_code'] = null;
            $result['asset_name'] = null;
        }

        return $result;
    }

    /**
     * ดึงข้อมูลค้ำประกันสัญญาตามเลขที่สัญญา
     */
    public function get_guarantee_by_contract($contract_number)
    {
        $this->db->where('contract_number', $contract_number);
        $query = $this->db->get('contract_guarantees');
        return $query->row_array();
    }

    /**
     * เพิ่มข้อมูลค้ำประกันสัญญา
     */
    public function insert_guarantee($data)
    {
        return $this->db->insert('contract_guarantees', $data);
    }

    /**
     * อัปเดตข้อมูลค้ำประกันสัญญา
     */
    public function update_guarantee($guarantee_id, $data)
    {
        $this->db->where('guarantee_id', $guarantee_id);
        return $this->db->update('contract_guarantees', $data);
    }

    /**
     * ลบข้อมูลค้ำประกันสัญญา
     */
    public function delete_guarantee($guarantee_id)
    {
        $this->db->where('guarantee_id', $guarantee_id);
        return $this->db->delete('contract_guarantees');
    }

    /**
     * ค้นหาค้ำประกันสัญญา
     */
    public function search_guarantees($keyword, $status = null, $vendor = null, $expiry_filter = null)
    {
        $this->db->select('g.*');
        $this->db->from('contract_guarantees g');

        $hasAsset = $this->apply_asset_join();

        if ($keyword) {
            $this->db->group_start();
            $this->db->like('g.contract_number', $keyword);
            $this->db->or_like('g.vendor_name', $keyword);
            if ($hasAsset) {
                $this->db->or_like('a.asset_name', $keyword);
            }
            $this->db->group_end();
        }

        if ($status) {
            $this->db->where('g.status', $status);
        }

        if ($vendor) {
            $this->db->where('g.vendor_name', $vendor);
        }

        if ($expiry_filter) {
            $today = date('Y-m-d');
            switch ($expiry_filter) {
                case 'expired':
                    $this->db->where('g.end_date <', $today);
                    break;
                case 'expiring_30':
                    $this->db->where('g.end_date >=', $today);
                    $this->db->where('g.end_date <=', date('Y-m-d', strtotime('+30 days')));
                    break;
                case 'expiring_60':
                    $this->db->where('g.end_date >=', $today);
                    $this->db->where('g.end_date <=', date('Y-m-d', strtotime('+60 days')));
                    break;
                case 'expiring_90':
                    $this->db->where('g.end_date >=', $today);
                    $this->db->where('g.end_date <=', date('Y-m-d', strtotime('+90 days')));
                    break;
            }
        }

        $this->db->order_by('g.created_at', 'DESC');
        $query = $this->db->get();
        $results = $query->result_array();

        if (!$hasAsset) {
            foreach ($results as &$row) {
                $row['asset_code'] = null;
                $row['asset_name'] = null;
            }
            unset($row);
        }

        return $results;
    }

    /**
     * ตรวจสอบและเชื่อมกับตาราง assets หากมีการอ้างอิง asset_id
     */
    private function apply_asset_join()
    {
        $hasAsset = $this->db->field_exists('asset_id', 'contract_guarantees');
        if ($hasAsset) {
            $this->db->select('a.asset_id as asset_code, a.asset_name');
            $this->db->join('assets a', 'g.asset_id = a.asset_id', 'left');
        } else {
            $this->db->select('NULL as asset_code, NULL as asset_name', false);
        }
        return $hasAsset;
    }

    /**
     * ดึงสถิติค้ำประกันสัญญา
     */
    public function get_guarantee_statistics()
    {
        $stats = [];

        $stats['total_guarantees'] = $this->db->count_all('contract_guarantees');

        $this->db->where('status', 'ใช้งาน');
        $stats['active_guarantees'] = $this->db->count_all_results('contract_guarantees');
        $this->db->reset_query();

        $this->db->where('end_date >=', date('Y-m-d'));
        $this->db->where('end_date <=', date('Y-m-d', strtotime('+30 days')));
        $this->db->where('status', 'ใช้งาน');
        $stats['expiring_soon'] = $this->db->count_all_results('contract_guarantees');
        $this->db->reset_query();

        $this->db->where('end_date <', date('Y-m-d'));
        $this->db->where('status', 'ใช้งาน');
        $stats['expired_guarantees'] = $this->db->count_all_results('contract_guarantees');
        $this->db->reset_query();

        return $stats;
    }

    /**
     * ดึงรายชื่อผู้จำหน่ายแบบไม่ซ้ำ
     */
    public function get_distinct_vendors()
    {
        $this->db->distinct();
        $this->db->select('vendor_name');
        $this->db->order_by('vendor_name', 'ASC');
        return $this->db->get('contract_guarantees')->result_array();
    }

    /**
     * ดึงค้ำประกันที่ใกล้หมดอายุ
     */
    public function get_expiring_guarantees($days = 30)
    {
        $this->db->where('end_date <=', date('Y-m-d', strtotime("+{$days} days")));
        $this->db->where('end_date >=', date('Y-m-d'));
        $this->db->where('status', 'ใช้งาน');
        $this->db->order_by('end_date', 'ASC');
        $query = $this->db->get('contract_guarantees');
        return $query->result_array();
    }

    /**
     * ดึงค้ำประกันที่หมดอายุแล้ว
     */
    public function get_expired_guarantees()
    {
        $this->db->where('end_date <', date('Y-m-d'));
        $this->db->where('status', 'ใช้งาน');
        $this->db->order_by('end_date', 'DESC');
        $query = $this->db->get('contract_guarantees');
        return $query->result_array();
    }

    /**
     * อัปเดตสถานะค้ำประกันที่หมดอายุ
     */
    public function update_expired_guarantees()
    {
        $data = [
            'status' => 'หมดอายุ',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('end_date <', date('Y-m-d'));
        $this->db->where('status', 'ใช้งาน');
        return $this->db->update('contract_guarantees', $data);
    }

    /**
     * ดึงรายการประเภทค้ำประกัน
     */
    public function get_guarantee_types()
    {
        $this->db->select('DISTINCT guarantee_type');
        $this->db->order_by('guarantee_type', 'ASC');
        $query = $this->db->get('contract_guarantees');
        return array_column($query->result_array(), 'guarantee_type');
    }

    /**
     * ดึงรายการผู้ค้ำประกัน
     */
    public function get_guarantee_providers()
    {
        $this->db->select('DISTINCT vendor_name');
        $this->db->order_by('vendor_name', 'ASC');
        $query = $this->db->get('contract_guarantees');
        return array_column($query->result_array(), 'vendor_name');
    }

    /**
     * ตรวจสอบว่าเลขที่สัญญาซ้ำหรือไม่
     */
    public function check_contract_exists($contract_number, $exclude_id = null)
    {
        $this->db->where('contract_number', $contract_number);
        if ($exclude_id) {
            $this->db->where('guarantee_id !=', $exclude_id);
        }
        $query = $this->db->get('contract_guarantees');
        return $query->num_rows() > 0;
    }

    /**
     * ดึงข้อมูลค้ำประกันสำหรับรายงาน
     */
    public function get_guarantees_for_report($status = null, $type = null, $date_from = null, $date_to = null)
    {
        if ($status) {
            $this->db->where('status', $status);
        }

        if ($type) {
            $this->db->where('guarantee_type', $type);
        }

        if ($date_from) {
            $this->db->where('start_date >=', $date_from);
        }

        if ($date_to) {
            $this->db->where('end_date <=', $date_to);
        }

        $this->db->order_by('start_date', 'DESC');
        $query = $this->db->get('contract_guarantees');
        return $query->result_array();
    }

    /**
     * ดึงค้ำประกันที่ใช้งานอยู่ทั้งหมด
     */
    public function get_active_guarantees()
    {
        $this->db->where('status', 'ใช้งาน');
        $this->db->where('start_date <=', date('Y-m-d'));
        $this->db->where('end_date >=', date('Y-m-d'));
        $this->db->order_by('end_date', 'ASC');
        $query = $this->db->get('contract_guarantees');
        return $query->result_array();
    }

    /**
     * คำนวณจำนวนวันที่เหลือของค้ำประกัน
     */
    public function calculate_remaining_days($guarantee_id)
    {
        $guarantee = $this->get_guarantee_by_id($guarantee_id);
        if (!$guarantee) {
            return null;
        }

        $end_date = new DateTime($guarantee['end_date']);
        $current_date = new DateTime();

        if ($end_date < $current_date) {
            return 0; // หมดอายุแล้ว
        }

        $diff = $current_date->diff($end_date);
        return $diff->days;
    }

    /**
     * ดึงสรุปค้ำประกันตามผู้ค้ำประกัน
     */
    public function get_guarantee_summary_by_provider()
    {
        $this->db->select('vendor_name, COUNT(*) as count, SUM(guarantee_amount) as total_amount');
        $this->db->where('status', 'ใช้งาน');
        $this->db->group_by('vendor_name');
        $this->db->order_by('total_amount', 'DESC');
        $query = $this->db->get('contract_guarantees');
        return $query->result_array();
    }

    /**
     * ดึงค้ำประกันล่าสุด
     */
    public function get_recent_guarantees($limit = 10)
    {
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get('contract_guarantees');
        return $query->result_array();
    }

    /**
     * ตรวจสอบสถานะค้ำประกันอัตโนมัติ
     */
    public function check_guarantee_status($guarantee_id)
    {
        $guarantee = $this->get_guarantee_by_id($guarantee_id);
        if (!$guarantee) {
            return false;
        }

        $current_date = date('Y-m-d');
        $start_date = $guarantee['start_date'];
        $end_date = $guarantee['end_date'];

        if ($current_date < $start_date) {
            return 'ยังไม่เริ่ม';
        } elseif ($current_date > $end_date) {
            return 'หมดอายุ';
        } else {
            return 'ใช้งาน';
        }
    }

    /**
     * ดึงค้ำประกันตามช่วงวันที่
     */
    public function get_guarantees_by_date_range($start_date, $end_date)
    {
        $this->db->where('start_date <=', $end_date);
        $this->db->where('end_date >=', $start_date);
        $this->db->order_by('start_date', 'ASC');
        $query = $this->db->get('contract_guarantees');
        return $query->result_array();
    }

    /**
     * คำนวณมูลค่าค้ำประกันรวมที่ใช้งานอยู่
     */
    public function get_total_active_guarantee_amount()
    {
        $this->db->select('SUM(guarantee_amount) as total');
        $this->db->where('status', 'ใช้งาน');
        $query = $this->db->get('contract_guarantees');
        $result = $query->row_array();
        return $result['total'] ?: 0;
    }

    /**
     * ตรวจสอบว่าครุภัณฑ์มีค้ำประกันที่ยังใช้งานอยู่หรือไม่
     */
    public function has_active_guarantee($asset_id, $start_date, $end_date)
    {
        $this->db->where('asset_id', $asset_id);
        $this->db->where('status', 'ใช้งาน');
        $this->db->where('start_date <=', $end_date);
        $this->db->where('end_date >=', $start_date);
        return $this->db->count_all_results('contract_guarantees') > 0;
    }

    /**
     * ดึงค้ำประกันที่ใช้งานอยู่ของครุภัณฑ์
     */
    public function get_active_guarantee_by_asset($asset_id)
    {
        $this->db->where('asset_id', $asset_id);
        $this->db->where('status', 'ใช้งาน');
        $this->db->where('start_date <=', date('Y-m-d'));
        $this->db->where('end_date >=', date('Y-m-d'));
        $query = $this->db->get('contract_guarantees');
        return $query->row_array();
    }

    /**
     * อัปเดตสถานะค้ำประกัน
     */
    public function update_guarantee_status($guarantee_id, $status)
    {
        $this->db->where('guarantee_id', $guarantee_id);
        return $this->db->update('contract_guarantees', [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * บันทึกการต่ออายุค้ำประกัน
     */
    public function renew_guarantee($guarantee_id, $guarantee_update, $renewal_data = null)
    {
        $this->db->where('guarantee_id', $guarantee_id);
        $updated = $this->db->update('contract_guarantees', $guarantee_update);

        if ($updated && $renewal_data && $this->db->table_exists('guarantee_renewals')) {
            $this->db->insert('guarantee_renewals', $renewal_data);
        }

        return $updated;
    }

    /**
     * บันทึกการเคลมค้ำประกัน
     */
    public function insert_claim($claim_data)
    {
        if ($this->db->table_exists('guarantee_claims')) {
            return $this->db->insert('guarantee_claims', $claim_data);
        }
        return false;
    }

    /**
     * ดึงประวัติการต่ออายุค้ำประกัน
     */
    public function get_renewal_history($guarantee_id)
    {
        if ($this->db->table_exists('guarantee_renewals')) {
            $this->db->where('guarantee_id', $guarantee_id);
            $this->db->order_by('renewal_date', 'DESC');
            return $this->db->get('guarantee_renewals')->result_array();
        }
        return [];
    }

    /**
     * ดึงรายการเคลมค้ำประกัน
     */
    public function get_guarantee_claims($guarantee_id)
    {
        if ($this->db->table_exists('guarantee_claims')) {
            $this->db->where('guarantee_id', $guarantee_id);
            $this->db->order_by('claim_date', 'DESC');
            return $this->db->get('guarantee_claims')->result_array();
        }
        return [];
    }
}

