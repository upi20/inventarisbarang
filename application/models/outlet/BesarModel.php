<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BesarModel extends Render_Model
{
    // dipakai Administrator |
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null, $filter = null)
    {
        // select tabel
        $this->db->select("c.kode as kode_kecamatan,
            c.nama as kecamatan,
            d.user_nama as sales,
            a.nama_warung as warung,
            h.nama as produk")
            ->select_sum("f.jumlah_karton", "karton")
            ->select_sum("f.jumlah_renceng", "renceng")
            ->select_sum("f.sisa", "kredit")
            ->from("warung a")
            ->join("warung_kategori b", "a.id_kategori = b.id", "left")
            ->join("kecamatan c", "a.id_kecamatan = c.id", "left")
            ->join("users d", "a.id_sales = d.user_id", "left")
            ->join("warung_sales_penjualan f", "a.id = f.id_warung", "left")
            ->join("users g", "f.id_penerima = g.user_id", "left")
            ->join("produk h", "f.id_produk = h.id", "left")
            ->group_by("a.id")
            ->group_by("f.id_produk")
            ->where("a.id_kategori", 1);
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

        // filter
        if ($filter != null) {

            // by sales
            if ($filter['sales'] != '') {
                $this->db->where('d.user_id', $filter['sales']);
            }

            // by kecamatan
            if ($filter['kecamatan'] != '') {
                $this->db->where('c.id', $filter['kecamatan']);
            }

            // by penjualan
            if ($filter['penjualan'] != '') {
                $fit = $filter['penjualan'];
                $this->db->where("a.jumlah LIKE '%$fit%'");
            }
        }

        // pencarian
        if ($cari != null) {
            $this->db->where("(
                    c.kode LIKE '%$cari%' or
                    c.nama LIKE '%$cari%' or
                    d.user_nama LIKE '%$cari%' or
                    a.nama_warung LIKE '%$cari%' or
                    h.nama LIKE '%$cari%'
                )");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    // dipakai Administrator |
    public function getPenjualan($id)
    {
        $result = $this->db->get_where("warung_sales_penjualan", ['id' => $id])->row_array();
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
        $return = $this->db->select('a.id, a.kode, a.nama as text')
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
            ->where('c.lev_nama', 'Sales')
            ->get()
            ->result_array();
        return $return;
    }
}
