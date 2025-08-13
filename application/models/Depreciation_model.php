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
}
