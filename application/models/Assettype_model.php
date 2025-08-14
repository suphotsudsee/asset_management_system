<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assettype_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_types()
    {
        $this->db->order_by('type_name', 'ASC');
        $query = $this->db->get('asset_types');
        return $query->result_array();
    }

    public function get_type_by_id($id)
    {
        $this->db->where('type_id', $id);
        $query = $this->db->get('asset_types');
        return $query->row_array();
    }

    public function insert_type($data)
    {
        return $this->db->insert('asset_types', $data);
    }

    public function update_type($id, $data)
    {
        $this->db->where('type_id', $id);
        return $this->db->update('asset_types', $data);
    }

    public function delete_type($id)
    {
        $this->db->where('type_id', $id);
        return $this->db->delete('asset_types');
    }
}
