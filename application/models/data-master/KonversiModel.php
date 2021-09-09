<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KonversiModel extends Render_Model
{
    public function getData()
    {
        $result = $this->db->select('*')
            ->limit('1')
            ->order_by('id', 'DESC')
            ->get('konversi')
            ->row_array();
        return $result;
    }

    // dipakai Administrator |
    public function insert($karton, $renceng, $status)
    {
        $result = $this->db->insert("konversi", [
            'karton' => $karton,
            'renceng' => $renceng,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s"),
        ]);

        return $result;
    }

    // dipakai Administrator |
    public function update($id, $karton, $renceng, $status)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('konversi', [
            'karton' => $karton,
            'renceng' => $renceng,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }
}
