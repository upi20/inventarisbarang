<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KotaModel extends Render_Model
{
    // dipakai Administrator |
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        // select tabel
        $this->db->select("*, IF(kota.status = '0' , 'Tidak Aktif', IF(kota.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("kota");

        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            $this->db->order_by($order_colum, $dir);
        }

        // initial data table
        if ($draw == 1) {
            $this->db->limit(10, 0);
        }

        // pencarian
        if ($cari != null) {
            $this->db->where("(nama LIKE '%$cari%' or IF(kota.status = '0' , 'Tidak Aktif', IF(kota.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%')");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    // dipakai Administrator |
    public function getKota($id)
    {
        $result = $this->db->get_where("kota", ['id' => $id])->row_array();
        return $result;
    }

    // dipakai Administrator |
    public function insert($nama)
    {
        $result = $this->db->insert("kota", [
            'nama' => $nama,
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);

        return $result;
    }

    // dipakai Administrator |
    public function update($id, $nama)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('kota', [
            'nama' => $nama,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    // dipakai Administrator |
    public function delete($id)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('kota', [
            'status' => 0,
            'updated_at' => date("Y-m-d H:i:s"),
            'deleted_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    // dipakai Registrasi
    public function cari($key)
    {
        $this->db->select('a.id as id, a.keterangan as text');
        $this->db->from('kota a');
        $this->db->where("keterangan LIKE '%$key%' or keterangan LIKE '%$key%' or jumlah_klik LIKE '%$key%'");
        return $this->db->get()->result_array();
    }
}
