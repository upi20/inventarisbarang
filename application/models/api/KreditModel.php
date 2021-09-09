<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KreditModel extends Render_Model
{
  public function api_warung($id_sales, $id = null): ?array
  {
    $this->db->select("a.id,
      CONCAT(a.nama_pemilik, ' ', '[', a.kode, ']') as nama,
      a.alamat, (
        (b.jumlah_karton * 12) + b.jumlah_renceng
      ) as renceng");
    $this->db->from('warung a');
    $this->db->join('warung_sales_penjualan b', 'a.id = b.id_warung');
    $this->db->where('a.id_sales', $id_sales);
    $this->db->where('b.status', 1);
    if ($id == null) {
      $db = $this->db->get();
      $length = $db->num_rows();
      $return = $db->result_array();
    } else {
      $this->db->where('id', $id);
      $db = $this->db->get();
      $length = $db->num_rows();
      $return = $db->result_array();
    }
    $return = ['data' => $return, 'length' => $length];
    return $return;
  }
}
