<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ListProdukModel extends Render_Model
{
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null, $filter = null)
    {
        // select tabel
        $this->db->select("
        a.id,
        a.id_penanggung_jawab,
        a.created_at,
        a.updated_at,
        b.id as id_detail,
        b.id_stok_keluar,
        b.jumlah,
        c.user_nama as penanggung_jawab,
        e.user_nama as sales,
        IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Keluar', 'Tidak Diketahui')) as status_str");
        $this->db->from("stok_keluar a");
        $this->db->join("stok_keluar_detail b", "a.id = b.id_stok_keluar", "left");
        $this->db->join("users c", "a.id_penanggung_jawab = c.user_id", "left");
        $this->db->join("users e", "a.id_sales = e.user_id", "left");
        $this->db->where("(a.status <> 0 and a.status <> 99)");
        $this->db->group_by('a.id');

        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            switch ($order_colum) {
                case 'id':
                    $order_colum = 'a.' . $order_colum;
                    break;

                case 'id_penanggung_jawab':
                    $order_colum = 'a.' . $order_colum;
                    break;

                case 'total_harga':
                    $order_colum = 'a.' . $order_colum;
                    break;

                case 'dibayar':
                    $order_colum = 'a.' . $order_colum;
                    break;

                case 'sisa':
                    $order_colum = 'a.' . $order_colum;
                    break;

                case 'created_at':
                    $order_colum = 'a.' . $order_colum;
                    break;

                case 'id_detail':
                    $order_colum = 'b.id';
                    break;

                case 'id_stok_keluar':
                    $order_colum = 'b.' . $order_colum;
                    break;

                case 'id_produk':
                    $order_colum = 'b.' . $order_colum;
                    break;

                case 'harga':
                    $order_colum = 'b.' . $order_colum;
                    break;

                case 'jumlah':
                    $order_colum = 'b.' . $order_colum;
                    break;

                case 'ttl_harga':
                    $order_colum = 'b.total_harga';
                    break;

                case 'satuan':
                    $order_colum = 'b.' . $order_colum;
                    break;

                case 'updated_at':
                    $order_colum = 'b.' . $order_colum;
                    break;

                case 'penanggung_jawab':
                    $order_colum = 'c.user_nama';
                    break;

                case 'status_str':
                    $order_colum = "a.status";
                    break;
            }
            $this->db->order_by($order_colum, $dir);
        }

        // initial data table
        if ($draw == 1) {
            $this->db->limit(10, 0);
        }

        // filter
        if ($filter != null) {
            // filter date
            if ($filter['date']['start'] != null && $filter['date']['end'] != null) {
                $this->db->where("(a.created_at >= '{$filter['date']['start']} 00:00:00' and a.created_at <= '{$filter['date']['end']} 23:59:59')");
            }

            // filter admin
            if ($filter['admin'] != '') {
                $this->db->where("a.id_penanggung_jawab", $filter['admin']);
            }

            // filter sales
            if ($filter['sales'] != '') {
                $this->db->where("a.id_sales", $filter['sales']);
            }

            // filter sales
            if ($filter['status'] != '') {
                $this->db->where("a.status", $filter['status']);
            }
        }

        // pencarian
        if ($cari != null) {
            $this->db->where("(
                    a.id LIKE '%$cari%' or
                    a.id_penanggung_jawab LIKE '%$cari%' or
                    c.user_nama LIKE '%$cari%' or
                    a.total_harga LIKE '%$cari%' or
                    a.dibayar LIKE '%$cari%' or
                    a.sisa LIKE '%$cari%' or
                    a.created_at LIKE '%$cari%' or
                    a.updated_at LIKE '%$cari%' or
                    b.id LIKE '%$cari%' or
                    b.id_stok_keluar LIKE '%$cari%' or
                    b.id_produk LIKE '%$cari%' or
                    b.harga LIKE '%$cari%' or
                    b.jumlah LIKE '%$cari%' or
                    b.total_harga LIKE '%$cari%' or
                    b.satuan LIKE '%$cari%' or
                    IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Keluar', 'Tidak Diketahui')) LIKE '%$cari%'
                )");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    public function getProduk($id)
    {
        $result = $this->db->get_where("stok_keluar", ['id' => $id])->row_array();
        return $result;
    }

    public function insert($sales, $jumlah, $status)
    {
        $result = $this->db->insert("stok_keluar", [
            'id_sales' => $sales,
            'jumlah' => $jumlah,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s"),
        ]);

        return $result;
    }

    public function update($id, $sales, $jumlah, $status)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('stok_keluar', [
            'id_sales' => $sales,
            'jumlah' => $jumlah,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    public function delete($id)
    {
        $this->db->trans_start();
        // kembalikan stok produk
        $list_details = $this->db
            ->select('id_produk, jumlah')
            ->where('id_stok_keluar', $id)
            ->get('stok_keluar_detail')->result_array();
        $this->db->reset_query();

        foreach ($list_details as $detail) {
            // update stok
            $this->db->query("UPDATE produk as a
                SET qty_karton =
                    ((
                        SELECT qty_karton
                        FROM produk as b
                        WHERE b.id = a.id
                        ) + {$detail['jumlah']})
                WHERE a.id = {$detail['id_produk']}");
            $this->db->reset_query();
        }

        // set deleted_at
        $this->db->where('id', $id);
        $result = $this->db->update('stok_keluar', [
            'status' => 99,
            'deleted_at' => date("Y-m-d H:i:s")
        ]);
        $this->db->reset_query();

        $this->db->where('id_stok_keluar', $id);
        $result = $this->db->update('stok_keluar_detail', [
            'status' => 99,
            'deleted_at' => date("Y-m-d H:i:s")
        ]);
        $this->db->trans_complete();
        return $result;
    }

    public function cari($key)
    {
        $this->db->select('a.id as id, a.keterangan as text');
        $this->db->from('stok_keluar a');
        $this->db->where("keterangan LIKE '%$key%' or keterangan LIKE '%$key%' or jumlah_klik LIKE '%$key%'");
        return $this->db->get()->result_array();
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

    public function getDetailStokKeluar($id)
    {
        $stok_keluar = $this->db
            ->select("
                b.user_nama as pj,
                c.user_nama as sales,
                a.id as kode,
                a.created_at,
                a.updated_at
            ")
            ->from('stok_keluar a')
            ->join('users b', 'a.id_penanggung_jawab = b.user_id')
            ->join('users c', 'a.id_sales = c.user_id')
            ->where('id', $id)
            ->get()->row_array();

        $detail_stok_keluar = $this->db
            ->select('
                b.nama as produk_nama,
                a.jumlah,
            ')
            ->from('stok_keluar_detail a')
            ->join('produk b', 'a.id_produk = b.id')
            ->where('a.id_stok_keluar', $id)
            ->where('(a.status <> 0 and a.status <> 99)')
            ->get()->result_array();
        if ($stok_keluar != null) {
            $return = array_merge(
                $stok_keluar,
                [
                    'details' => $detail_stok_keluar
                ]
            );
            return $return;
        } else {
            return null;
        }
    }
}
