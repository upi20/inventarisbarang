<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KategoriWarungModel extends Render_Model
{
    // dipakai Administrator |
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        // PIlih tabel
        $this->db->select("*, IF(warung_kategori.status = '0' , 'Tidak Aktif', IF(warung_kategori.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("warung_kategori");

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
            $this->db->where("(nama LIKE '%$cari%' or keterangan LIKE '%$cari%' or IF(warung_kategori.status = '0' , 'Tidak Aktif', IF(warung_kategori.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%')");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    // dipakai Administrator |
    public function getKategori($id)
    {
        $result = $this->db->get_where("warung_kategori", ['id' => $id])->row_array();
        return $result;
    }

    // dipakai Administrator | masuk data
    public function insert($nama, $keterangan, $status)
    {
        $result = $this->db->insert("warung_kategori", [
            'nama' => $nama,
            'keterangan' => $keterangan,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s"),
        ]);

        return $result;
    }

    // dipakai Administrator |
    public function update($id, $nama, $keterangan, $status)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('warung_kategori', [
            'nama' => $nama,
            'keterangan' => $keterangan,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    // dipakai Administrator |
    public function delete($id)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('warung_kategori', [

            'status' => '0',
            'updated_at' => date("Y-m-d H:i:s"),
            'deleted_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    // dipakai Registrasi
    public function cari($key)
    {
        $this->db->select('a.id as id, a.keterangan as text');
        $this->db->from('warung_kategori a');
        $this->db->where("keterangan LIKE '%$key%' or keterangan LIKE '%$key%' or jumlah_klik LIKE '%$key%'");
        return $this->db->get()->result_array();
    }
}
