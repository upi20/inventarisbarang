<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PenjualanModel extends Render_Model
{
    // dipakai Administrator |
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null, $filter = null)
    {
        // select tabel
        $this->db->select("a.id, c.user_nama as sales, d.nama as produk, a.status, a.created_at, a.updated_at, a.deleted_at, IF(a.status = '1' , 'Dibawa Sales', IF(a.status = '2' , 'Dikembalikan', 'Tidak Diketahui')) as status_str")
            ->select_sum("b.jumlah", "jumlah")
            ->select_sum("e.jumlah_karton", "karton")
            ->select_sum("e.jumlah_renceng", "renceng")
            ->select_sum("e.total_harga", "total_harga")
            ->select_sum("e.dibayar", "dibayar")
            ->select_sum("e.sisa", "sisa")
            ->from("stok_keluar a")
            ->join("stok_keluar_detail b", "a.id = b.id_stok_keluar", 'left')
            ->join("users c", "a.id_sales = c.user_id", 'left')
            ->join("produk d", "b.id_produk = d.id", 'left')
            ->join("warung_sales_penjualan e", "e.id_stok_keluar = a.id", 'left')
            ->where("a.status != ", 0)
            ->where("a.status != ", 99)
            ->group_by("a.id");

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
                $this->db->where('c.user_id', $filter['sales']);
            }

            // by kecamatan
            if ($filter['kecamatan'] != '') {
                $this->db->where('f.id', $filter['kecamatan']);
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
            b.nama_pemilik LIKE '%$cari%' or
            c.user_nama LIKE '%$cari%' or
            d.nama LIKE '%$cari%' or
            IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%')");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    public function getDetail($id, $filter = null)
    {
        $this->db->select("a.*, b.nama_warung as warung, c.user_nama as sales, d.nama as produk, e.user_nama as penerima, g.nama as satuan, g.harga as sharga, (IF(a.sisa > '0' , 'Hutang', IF(a.sisa = '0' , 'Lunas', 'Tidak Diketahui'))) as status_str")
            ->from('warung_sales_penjualan a')
            ->join('warung b', 'a.id_warung = b.id', 'left')
            ->join('users c', 'a.id_sales = c.user_id', 'left')
            ->join('produk d', 'a.id_produk = d.id', 'left')
            ->join('users e', 'a.id_penerima = e.user_id', 'left')
            ->join('stok_keluar f', 'a.id_stok_keluar = f.id', 'left')
            ->join('satuan_harga g', 'a.id_satuan_harga = g.id', 'left')
            ->where('a.id_stok_keluar', $id);

        // filter
        if ($filter != null) {

            // by status
            if ($filter['status'] != '') {
                if ($filter['status'] == 'Hutang') {
                    $this->db->where('a.sisa >', 0);
                }
                if ($filter['status'] == 'Lunas') {
                    $this->db->where('a.sisa =', 0);
                }
            }
        }

        $result = $this->db->get();
        // var_dump($this->db->last_query());
        // die;
        return $result;
    }

    public function getTagihan($id)
    {
        $result = $this->db->select("id, id_stok_keluar")
            ->from("warung_sales_penjualan")
            ->select_sum("sisa", "sisa")
            ->where('id_stok_keluar', $id)
            ->get();
        return $result;
    }

    // dipakai Administrator |
    public function getPenjualan($id)
    {
        $result = $this->db->get_where("warung_sales_penjualan", ['id_stok_keluar' => $id])->row_array();
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

    public function setorPembayaran($id_stok_keluar, $total_harga, $setoran, $sisa)
    {
        $this->db->trans_start();
        $this->db->get('warung_pembayaran');
        $q = $this->db->insert("warung_pembayaran", [
            'id_stok_keluar' => $id_stok_keluar,
            'total_harga' => $total_harga,
            'dibayar' => $setoran,
            'sisa' => $sisa,
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        if ($q) {
            $get_penjualan = $this->db->get_where('warung_sales_penjualan', ['status' => '1', 'id_stok_keluar' => $id_stok_keluar])->result_array();
            foreach ($get_penjualan as $get) {
                $a = $this->db->select('total_harga, dibayar, sisa')
                    ->from('warung_sales_penjualan')
                    ->where('id', $get['id'])
                    ->get()->result_array();
                $sisaPem = $a[0]['sisa'];
                $dibayar = $a[0]['dibayar'];
                $tersisa = $sisaPem - $setoran;
                if ($tersisa > 0) {
                    $upd['sisa'] = $tersisa;
                    $upd['dibayar'] = $dibayar + $setoran;
                    $upd['status'] = 1;
                    $exe = $this->db->where('id', $get['id']);
                    $exe = $this->db->update('warung_sales_penjualan', $upd);
                    break;
                } elseif ($tersisa < 0) {
                    $setoran = abs($tersisa);
                    $upd['sisa'] = 0;
                    $upd['dibayar'] = $sisaPem;
                    $upd['status'] = 2;
                    $exe = $this->db->where('id', $get['id']);
                    $exe = $this->db->update('warung_sales_penjualan', $upd);
                } elseif ($tersisa == 0) {
                    $upd['sisa'] = 0;
                    $upd['dibayar'] = $dibayar + $setoran;
                    $upd['status'] = 2;
                    $exe = $this->db->where('id', $get['id']);
                    $exe = $this->db->update('warung_sales_penjualan', $upd);
                    break;
                }
            }
        }
        $this->db->trans_complete();
        $return['id'] = $this->db->insert_id();
        $return['id_stok_keluar'] = $id_stok_keluar;
        return $return;
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
