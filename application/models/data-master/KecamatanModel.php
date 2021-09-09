<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KecamatanModel extends Render_Model
{
    // dipakai Administrator |
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        // select tabel
        $this->db->select("a.id, b.nama as kota, a.kode, a.nama, a.created_at, a.updated_at, IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("kecamatan a");
        $this->db->join("kota b", "a.id_kota = b.id");

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
            $this->db->where("(nama LIKE '%$cari%' or kota LIKE '%$cari%' or IF(kecamatan.status = '0' , 'Tidak Aktif', IF(kecamatan.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%')");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    // dipakai Administrator |
    public function getKecamatan($id)
    {
        $result = $this->db->get_where("kecamatan", ['id' => $id])->row_array();
        return $result;
    }

    // dipakai Administrator |
    public function insert($kota, $kode, $nama)
    {
        $result = $this->db->insert("kecamatan", [
            'id_kota' => $kota,
            'kode' => $kode,
            'nama' => $nama,
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);

        return $result;
    }

    // dipakai Administrator |
    public function update($id, $kota, $kode, $nama)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('kecamatan', [
            'id_kota' => $kota,
            'kode' => $kode,
            'nama' => $nama,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    // dipakai Administrator |
    public function delete($id)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('kecamatan', [
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
        $this->db->from('kecamatan a');
        $this->db->where("keterangan LIKE '%$key%' or keterangan LIKE '%$key%' or jumlah_klik LIKE '%$key%'");
        return $this->db->get()->result_array();
    }

    public function listkota()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('kota a')
            ->where('status', '1')
            ->get()
            ->result_array();
        return $return;
    }
}
