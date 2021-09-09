<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SatuanHargaModel extends Render_Model
{
    // dipakai Administrator |
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        // select tabel
        $this->db->select("*, IF(satuan_harga.status = '0' , 'Tidak Aktif', IF(satuan_harga.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("satuan_harga");

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
            $this->db->where("(qty LIKE '%$cari%' or nama LIKE '%$cari%' or harga LIKE '%$cari%' or IF(satuan_harga.status = '0' , 'Tidak Aktif', IF(satuan_harga.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%')");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    // dipakai Administrator |
    public function getSatuanHarga($id)
    {
        $result = $this->db->get_where("satuan_harga", ['id' => $id])->row_array();
        return $result;
    }

    // dipakai Administrator |
    public function insert($qty, $nama, $harga, $status)
    {
        $result = $this->db->insert("satuan_harga", [
            'qty' => $qty,
            'nama' => $nama,
            'harga' => $harga,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s"),
        ]);

        return $result;
    }

    // dipakai Administrator |
    public function update($id, $qty, $nama, $harga, $status)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('satuan_harga', [
            'qty' => $qty,
            'nama' => $nama,
            'harga' => $harga,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    // dipakai Administrator |
    public function delete($id)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('satuan_harga', [

            'status' => '0',
            'deleted_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    // dipakai Registrasi
    public function cari($key)
    {
        $this->db->select('a.id as id, a.qty, a.nama, a.harga');
        $this->db->from('satuan_harga a');
        $this->db->where("qty LIKE '%$key%' or nama LIKE '%$key%' or harga LIKE '%$key%'");
        return $this->db->get()->result_array();
    }
}
