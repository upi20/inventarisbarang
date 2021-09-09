<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProdukModel extends Render_Model
{
    // dipakai Administrator |
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        // select tabel
        $this->db->select("a.id, b.nama as namakategori, a.nama, a.keterangan, a.qty_karton, a.qty_renceng, IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str, a.created_at, a.updated_at");
        $this->db->from("produk a");
        $this->db->join("kategori b", "a.id_kategori = b.id");

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
            $this->db->where("(nama LIKE '%$cari%' or keterangan LIKE '%$cari%' or IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%')");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    // dipakai Administrator |
    public function getData($id)
    {
        $result = $this->db->get_where("produk", ['id' => $id])->row_array();
        return $result;
    }

    // dipakai Administrator |
    public function insert($kategori, $nama, $keterangan)
    {
        $result = $this->db->insert("produk", [
            'id_kategori' => $kategori,
            'nama' => $nama,
            'keterangan' => $keterangan,
            'qty_karton' => 0,
            'qty_renceng' => 0,
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);

        return $result;
    }

    // dipakai Administrator |
    public function update($id, $kategori, $nama, $keterangan)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('produk', [
            'id_kategori' => $kategori,
            'nama' => $nama,
            'keterangan' => $keterangan,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    // dipakai Administrator |
    public function delete($id)
    {
        // $result = $this->db->delete('produk', ['id' => $id]);
        $this->db->where('id', $id);
        $result = $this->db->update('produk', [
            'status' => 0,
            'deleted_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    // dipakai Registrasi
    public function cari($key)
    {
        $this->db->select('a.id as id, a.keterangan as text');
        $this->db->from('produk a');
        $this->db->where("keterangan LIKE '%$key%' or keterangan LIKE '%$key%' or jumlah_klik LIKE '%$key%'");
        return $this->db->get()->result_array();
    }

    public function listKategori()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('kategori a')
            ->get()
            ->result_array();
        return $return;
    }
}
