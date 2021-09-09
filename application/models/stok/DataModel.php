<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataModel extends Render_Model
{
     // dipakai Administrator |
     public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null)
     {
         // select tabel
         $this->db->select("a.id, b.nama as namakategori, a.nama, a.keterangan, a.qty_karton, a.qty_renceng");
         $this->db->from("produk a");
         $this->db->join("kategori b", "a.id_kategori = b.id");
         $this->db->where("a.status", 1);
 
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
             $this->db->where("(nama LIKE '%$cari%' or keterangan LIKE '%$cari%' )");
         }
 
         // pagination
         if ($show != null && $start != null) {
             $this->db->limit($show, $start);
         }
 
         $result = $this->db->get();
         return $result;
     }
 
}
