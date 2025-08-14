<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Depreciation_model extends CI_Model
{
    private $table = 'depreciation_records';
    private $pk    = 'depreciation_record_id';

    public function get_all()
    {
        $this->db->select('d.*, a.asset_name, a.serial_number')
                 ->from($this->table.' d')
                 ->join('assets a', 'a.asset_id = d.asset_id', 'left')
                 ->order_by('d.record_date', 'DESC');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, [$this->pk => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->update($this->table, $data, [$this->pk => $id]);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, [$this->pk => $id]);
    }

/* ===== Aggregate helpers for Reports ===== */

public function get_total_depreciation_value($start_date = null, $end_date = null, $asset_id = null)
{
    $this->_normalize_date_bounds($start_date, $end_date);   // 👈 เพิ่มบรรทัดนี้

    $table    = 'depreciation_records';
    $dateCol  = 'record_date';
    $amountCol= 'depreciation_amount';

    $this->db->select("COALESCE(SUM($amountCol),0) AS total", false)
             ->from($table);

    if ($asset_id)   $this->db->where('asset_id', (int)$asset_id);
    if ($start_date) $this->db->where("$dateCol >=", $start_date);
    if ($end_date)   $this->db->where("$dateCol <=", $end_date);

    $row = $this->db->get()->row_array();
    return (float)($row['total'] ?? 0);
}

/**
 * สรุปค่าเสื่อมรายเดือนของปีที่ระบุ (สำหรับกราฟรายงาน)
 * @param int|null $year  ค่าเริ่มต้น = ปีปัจจุบัน
 * @return array [1=>totalJan, 2=>totalFeb, ..., 12=>totalDec]
 */
public function get_monthly_depreciation_summary($year = null)
{
    $year = $year ?: (int)date('Y');
    $dateCol   = 'record_date';
    $amountCol = 'depreciation_amount';

    $this->db->select('MONTH('.$dateCol.') AS m, COALESCE(SUM('.$amountCol.'),0) AS total', false)
             ->from($this->table)
             ->where('YEAR('.$dateCol.')', (int)$year)
             ->group_by('MONTH('.$dateCol.')')
             ->order_by('m', 'ASC');

    $rows = $this->db->get()->result_array();

    $out = array_fill(1, 12, 0.0);
    foreach ($rows as $r) {
        $out[(int)$r['m']] = (float)$r['total'];
    }
    return $out;
}

/** alias เผื่อ controller อื่นเรียกชื่อแตกต่าง */
public function get_total_depreciation($start_date = null, $end_date = null, $asset_id = null)
{
    return $this->get_total_depreciation_value($start_date, $end_date, $asset_id);
}


/* ================= Depreciation reports ================= */

public function get_depreciation_report($start_date = null, $end_date = null, $asset_id = null, $group_by = null)
{
    $this->_normalize_date_bounds($start_date, $end_date);   // 👈 เพิ่มบรรทัดนี้

    $table    = 'depreciation_records';
    $dateCol  = 'record_date';
    $amountCol= 'depreciation_amount';
    $accumCol = 'accumulated_depreciation';
    $bookCol  = 'book_value';

    $this->db->from($table.' d');
    $this->db->join('assets a', 'a.asset_id = d.asset_id', 'left');

    if ($start_date) $this->db->where("d.$dateCol >=", $start_date);
    if ($end_date)   $this->db->where("d.$dateCol <=", $end_date);
    if ($asset_id)   $this->db->where('d.asset_id', (int)$asset_id);

    $select = "d.*, a.asset_name, a.serial_number,
               d.$dateCol AS record_date, d.$amountCol AS depreciation_amount";
    $select .= ", d.$accumCol AS accumulated_depreciation, d.$bookCol AS book_value";
    $this->db->select($select, false)->order_by("d.$dateCol", 'DESC');

    return $this->db->get()->result_array();
}

/* เผื่อ controller ตัวอื่นเรียกชื่อแตกต่าง */
public function get_report_depreciation($start_date = null, $end_date = null, $asset_id = null, $group_by = null)
{
    $this->_normalize_date_bounds($start_date, $end_date);  // ← เพิ่มบรรทัดนี้
    return $this->get_depreciation_report($start_date, $end_date, $asset_id, $group_by);
}

// วางไว้ใน class Depreciation_model (เป็น private helper)
private function _normalize_date_bounds(&$start_date, &$end_date)
{
    $norm = function($s, $is_end = false) {
        $s = trim((string)$s);
        if ($s === '') return null;

        // ปีล้วน: 2025
        if (preg_match('/^\d{4}$/', $s)) {
            return $is_end ? ($s.'-12-31') : ($s.'-01-01');
        }

        // ปี-เดือน: 2025-08
        if (preg_match('/^\d{4}-\d{2}$/', $s)) {
            $first = $s.'-01';
            return $is_end ? date('Y-m-t', strtotime($first)) : $first;
        }

        // d/m/Y (เผื่อค่าจาก datepicker/ผู้ใช้) + พ.ศ.
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $s)) {
            list($d,$m,$y) = array_map('intval', explode('/', $s));
            if ($y > 2400) $y -= 543; // แปลง พ.ศ. -> ค.ศ.
            return sprintf('%04d-%02d-%02d', $y, $m, $d);
        }

        // Y-m-d ปล่อยผ่าน
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $s)) {
            return $s;
        }

        // อื่น ๆ: คืนค่าเดิม (ให้ validation ชั้นนอกจัดการ)
        return $s;
    };

    // เติมอีกฝั่งให้ครบถ้าส่งมาฝั่งเดียว
    if ($start_date && !$end_date) {
        // ถ้า start เป็นปี/ปี-เดือน จะได้ end ครบโดยอัตโนมัติ
        $tmpStart = $norm($start_date, false);
        $tmpEnd   = $norm($start_date, true);
        $start_date = $tmpStart;
        $end_date   = $tmpEnd;
    } elseif ($end_date && !$start_date) {
        $tmpStart = $norm($end_date, false);
        $tmpEnd   = $norm($end_date, true);
        $start_date = $tmpStart;
        $end_date   = $tmpEnd;
    } else {
        $start_date = $norm($start_date, false);
        $end_date   = $norm($end_date, true);
    }

    // ถ้ากลับลำ (start > end) ให้สลับ
    if ($start_date && $end_date && $start_date > $end_date) {
        $t = $start_date; $start_date = $end_date; $end_date = $t;
    }
}

}
