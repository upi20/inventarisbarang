<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PenjualanModel extends Render_Model
{
    // dipakai Administrator |
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        // select tabel
        $this->db->select("a.id, b.nama_pemilik as warung, c.user_nama as sales, d.nama as produk, d.harga_jual as harga, a.jumlah, a.total_harga, a.dibayar, a.sisa, a.setoran, e.user_nama as penerima, a.hutang, a.created_at, a.updated_at, IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("warung_sales_penjualan a");
        $this->db->join("warung b", "a.id_warung = b.id", "left");
        $this->db->join("users c", "a.id_sales = c.user_id", "left");
        $this->db->join("produk d", "a.id_produk = d.id", "left");
        $this->db->join("users e", "a.id_penerima = e.user_id", "left");

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
            $this->db->where("(nama LIKE '%$cari%' or kota LIKE '%$cari%' or keterangan LIKE '%$cari%' or IF(warung_sales_penjualan.status = '0' , 'Tidak Aktif', IF(warung_sales_penjualan.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%')");
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

    // dipakai Administrator |
    public function insert($warung, $sales, $produk, $harga, $jumlah, $total_harga, $dibayar, $sisa, $setoran, $penerima, $hutang, $status)
    {
        $result = $this->db->insert("warung_sales_penjualan", [
            'id_warung' => $warung,
            'id_sales' => $sales,
            'id_produk' => $produk,
            'harga' => $harga,
            'jumlah' => $jumlah,
            'total_harga' => $total_harga,
            'dibayar' => $dibayar,
            'sisa' => $sisa,
            'setoran' => $setoran,
            'id_penerima' => $penerima,
            'hutang' => $hutang,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s"),
        ]);

        return $result;
    }

    // dipakai Administrator |
    public function update($id, $warung, $sales, $produk, $harga, $jumlah, $total_harga, $dibayar, $sisa, $setoran, $penerima, $hutang, $status)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('warung_sales_penjualan', [
            'id_warung' => $warung,
            'id_sales' => $sales,
            'id_produk' => $produk,
            'harga' => $harga,
            'jumlah' => $jumlah,
            'total_harga' => $total_harga,
            'dibayar' => $dibayar,
            'sisa' => $sisa,
            'setoran' => $setoran,
            'id_penerima' => $penerima,
            'hutang' => $hutang,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    // dipakai Administrator |
    public function delete($id)
    {
        $result = $this->db->delete('warung_sales_penjualan', ['id' => $id]);
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

    public function listWarung()
    {
        $return = $this->db->select('a.id, a.nama_pemilik as text')
            ->from('warung a')
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

    public function listProduk()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('produk a')
            ->get()
            ->result_array();
        return $return;
    }

    public function listPenerima()
    {
        $return = $this->db->select('a.user_id as id, a.user_nama as text')
            ->from('users a')
            ->get()
            ->result_array();
        return $return;
    }

    public function produkById($id)
    {
        $cari = $this->db->select('*')
            ->from('produk a')
            ->where('id', $id)
            ->get()
            ->result_array();
        return $cari;
    }
}
