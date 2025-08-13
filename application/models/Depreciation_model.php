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

    /**
     * Sum of latest accumulated depreciation of all assets
     *
     * Reports controller expects the total depreciation value in order to
     * calculate the book value of the assets.  Each asset can have multiple
     * depreciation records, therefore we need to take the latest accumulated
     * depreciation for each asset and then sum those amounts together.
     *
     * @return float
     */
    public function get_total_depreciation_value()
    {
        // Sub-query to get the latest accumulated depreciation per asset
        $subquery = $this->db->select('MAX(accumulated_depreciation) as accumulated_depreciation')
                             ->from($this->table)
                             ->group_by('asset_id')
                             ->get_compiled_select();

        $query = $this->db->select_sum('accumulated_depreciation')
                          ->from("({$subquery}) AS t")
                          ->get();

        $row = $query->row_array();
        return isset($row['accumulated_depreciation']) ? (float)$row['accumulated_depreciation'] : 0.0;
    }

    /**
     * Detailed depreciation report for assets
     *
     * @param int      $year     Fiscal year
     * @param int|null $month    Optional month (1-12)
     * @param string   $category Optional asset category
     * @return array
     */
    public function get_depreciation_report($year, $month = null, $category = null)
    {
        // Determine the last date to include in the report
        $end_date = $year . '-12-31';
        if (!empty($month)) {
            $last_day = date('t', strtotime($year . '-' . $month . '-01'));
            $end_date = sprintf('%04d-%02d-%02d', $year, $month, $last_day);
        }

        $this->db->select(
            'a.asset_id,
             a.asset_id as asset_code,
             a.asset_name,
             a.asset_type as category,
             a.purchase_date,
             a.purchase_price,
             a.depreciation_rate,
             a.status,
             MAX(d.accumulated_depreciation) as accumulated_depreciation'
        );
        $this->db->from('assets a');
        $this->db->join(
            'depreciation_records d',
            "d.asset_id = a.asset_id AND d.record_date <= '{$end_date}'",
            'left'
        );

        if (!empty($category)) {
            $this->db->where('a.asset_type', $category);
        }

        $this->db->group_by('a.asset_id');
        $this->db->order_by('a.asset_id', 'ASC');

        $records = $this->db->get()->result_array();

        foreach ($records as &$item) {
            $annual = $item['purchase_price'] * ($item['depreciation_rate'] / 100);
            $acc   = $item['accumulated_depreciation'] ? (float)$item['accumulated_depreciation'] : 0.0;

            $item['annual_depreciation']    = $annual;
            $item['accumulated_depreciation'] = $acc;
            $item['book_value']            = $item['purchase_price'] - $acc;
            $item['useful_life']           = $item['depreciation_rate'] > 0 ? round(100 / $item['depreciation_rate']) : 0;
            // Currently the system supports only straight-line method
            $item['depreciation_method']   = 'เส้นตรง';
        }

        return $records;
    }

    /**
     * Summary information for depreciation report
     *
     * @param int $year
     * @return array
     */
    public function get_depreciation_summary($year)
    {
        $data = $this->get_depreciation_report($year);

        $summary = [
            'total_cost'            => 0,
            'annual_depreciation'   => 0,
            'accumulated_depreciation' => 0,
            'book_value'            => 0,
        ];

        foreach ($data as $item) {
            $summary['total_cost'] += (float)$item['purchase_price'];
            $summary['annual_depreciation'] += (float)$item['annual_depreciation'];
            $summary['accumulated_depreciation'] += (float)$item['accumulated_depreciation'];
            $summary['book_value'] += (float)$item['book_value'];
        }

        return $summary;
    }

    /**
     * Monthly depreciation amounts for a given year
     *
     * @param int $year
     * @return array
     */
    public function get_monthly_depreciation($year)
    {
        return $this->db->select('MONTH(record_date) as month, SUM(depreciation_amount) as depreciation_amount')
                        ->from($this->table)
                        ->where('YEAR(record_date)', $year)
                        ->group_by('MONTH(record_date)')
                        ->order_by('MONTH(record_date)', 'ASC')
                        ->get()
                        ->result_array();
    }

    /**
     * Depreciation amount grouped by asset category for a given year
     *
     * @param int $year
     * @return array
     */
    public function get_depreciation_by_category($year)
    {
        return $this->db->select('a.asset_type as category, SUM(d.depreciation_amount) as depreciation_amount')
                        ->from($this->table . ' d')
                        ->join('assets a', 'a.asset_id = d.asset_id', 'left')
                        ->where('YEAR(d.record_date)', $year)
                        ->group_by('a.asset_type')
                        ->order_by('depreciation_amount', 'DESC')
                        ->get()
                        ->result_array();
    }

    /**
     * Depreciation trend for the past few years
     *
     * @param int $year Reference year
     * @return array
     */
    public function get_depreciation_trend($year)
    {
        $start_year = $year - 4; // last 5 years including current

        return $this->db->select('YEAR(record_date) as year, SUM(depreciation_amount) as depreciation_amount')
                        ->from($this->table)
                        ->where('YEAR(record_date) >=', $start_year)
                        ->where('YEAR(record_date) <=', $year)
                        ->group_by('YEAR(record_date)')
                        ->order_by('year', 'ASC')
                        ->get()
                        ->result_array();
    }
}
