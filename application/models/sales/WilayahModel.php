<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WilayahModel extends Render_Model
{
    // dipakai Administrator |
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        // select tabel
        $this->db->select("a.id, b.user_nama as sales, c.nama as kecamatan, a.created_at, a.updated_at, IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("sales_wilayah a");
        $this->db->join("users b", "a.id_sales = b.user_id", "left");
        $this->db->join("kecamatan c", "a.id_kecamatan = c.id", "left");

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
            $this->db->where("(nama LIKE '%$cari%' or kota LIKE '%$cari%' or keterangan LIKE '%$cari%' or IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%')");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    // dipakai Administrator |
    public function getWilayah($id)
    {
        $result = $this->db->get_where("sales_wilayah", ['id' => $id])->row_array();
        return $result;
    }

    // dipakai Administrator |
    public function insert($sales, $kecamatan, $status)
    {
        $result = $this->db->insert("sales_wilayah", [
            'id_sales' => $sales,
            'id_kecamatan' => $kecamatan,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s"),
        ]);

        return $result;
    }

    // dipakai Administrator |
    public function update($id, $sales, $kecamatan, $status)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('sales_wilayah', [
            'id_sales' => $sales,
            'id_kecamatan' => $kecamatan,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    // dipakai Administrator |
    public function delete($id)
    {
        $result = $this->db->delete('sales_wilayah', ['id' => $id]);
        return $result;
    }

    // dipakai Registrasi
    public function cari($key)
    {
        $this->db->select('a.id as id, a.keterangan as text');
        $this->db->from('sales_wilayah a');
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

    public function listSales()
    {
        $return = $this->db->select('a.user_id as id, a.user_nama as text')
            ->from('users a')
            ->join('role_users b', 'a.user_id = b.role_user_id')
            ->join('level c', 'b.role_lev_id = c.lev_id')
            ->where('c.lev_id', '6')
            ->get()
            ->result_array();
        return $return;
    }
}
