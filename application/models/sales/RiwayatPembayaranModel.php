<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RiwayatPembayaranModel extends Render_Model
{
  // dipakai Administrator |
  public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null, $filter = null)
  {
    // select tabel
    $this->db->select("
      h.*,
      b.nama_warung as warung,
      c.user_nama as sales,
      d.nama as produk,
      e.user_nama as penerima,
      g.nama as satuan,
      g.harga as sharga")
      ->from('warung_pembayaran h')
      ->join('warung_sales_penjualan a', 'a.id = h.id_warung_sales_penjualan', 'left')
      ->join('warung b', 'a.id_warung = b.id', 'left')
      ->join('users c', 'a.id_sales = c.user_id', 'left')
      ->join('produk d', 'a.id_produk = d.id', 'left')
      ->join('users e', 'a.id_penerima = e.user_id', 'left')
      ->join('stok_keluar f', 'a.id_stok_keluar = f.id', 'left')
      ->join('satuan_harga g', 'a.id_satuan_harga = g.id', 'left');

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

  // dipakai Administrator |
  public function getRiwayatPembayaran($id)
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
}
