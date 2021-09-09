<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ListWarungModel extends Render_Model
{
    // dipakai Administrator |
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        // select tabel
        $this->db->select("a.id, b.nama as kecamatan, b.nama as kategori, a.nama_pemilik as nama, a.alamat, a.no_hp, a.kordinat, a.created_at, a.updated_at, IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("warung a");
        $this->db->join("kecamatan b", "a.id_kecamatan = b.id", "LEFT");
        $this->db->join("warung_kategori c", "a.id_kategori = c.id", "LEFT");

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
            $this->db->where("(nama LIKE '%$cari%' or kota LIKE '%$cari%' or keterangan LIKE '%$cari%' or IF(warung.status = '0' , 'Tidak Aktif', IF(warung.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%')");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    // dipakai Administrator |
    public function getListWarung($id)
    {
        $result = $this->db->get_where("warung", ['id' => $id])->row_array();
        return $result;
    }

    // dipakai Administrator |
    public function insert($kecamatan, $kategori, $nama, $alamat, $no_hp, $kordinat, $status)
    {
        $result = $this->db->insert("warung", [
            'id_kecamatan' => $kecamatan,
            'id_kategori' => $kategori,
            'nama_pemilik' => $nama,
            'alamat' => $alamat,
            'no_hp' => $no_hp,
            'kordinat' => $kordinat,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s"),
        ]);

        return $result;
    }

    // dipakai Administrator |
    public function update($id, $kecamatan, $kategori, $nama, $alamat, $no_hp, $kordinat, $status)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('warung', [
            'id_kecamatan' => $kecamatan,
            'id_kategori' => $kategori,
            'nama_pemilik' => $nama,
            'alamat' => $alamat,
            'no_hp' => $no_hp,
            'kordinat' => $kordinat,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    // dipakai Administrator |
    public function delete($id)
    {
        $result = $this->db->delete('warung', ['id' => $id]);
        return $result;
    }

    // dipakai Registrasi
    public function cari($key)
    {
        $this->db->select('a.id as id, a.keterangan as text');
        $this->db->from('warung a');
        $this->db->where("keterangan LIKE '%$key%' or keterangan LIKE '%$key%' or jumlah_klik LIKE '%$key%'");
        return $this->db->get()->result_array();
    }

    public function listKecamatan()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('kecamatan a')
            ->get()
            ->result_array();
        return $return;
    }

    public function listKategori()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('warung_kategori a')
            ->get()
            ->result_array();
        return $return;
    }
}
