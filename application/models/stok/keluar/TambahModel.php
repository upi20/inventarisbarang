<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TambahModel extends Render_Model
{
    public function getAllData($kode, $draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        // select tabel
        $this->db->select("a.id,
        a.id_stok_keluar,
        a.id_produk,
        b.nama as produk,
        a.jumlah");
        $this->db->from("stok_keluar_detail a");
        $this->db->join('produk b', 'a.id_produk = b.id', 'LEFT');
        $this->db->where('a.status <>', 99);
        $this->db->where('a.id_stok_keluar', $kode);

        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            switch ($order_colum) {
                case 'produk':
                    $order_colum = 'b.nama';
                    break;
            }
            $this->db->order_by($order_colum, $dir);
        }

        // initial data table
        if ($draw == 1) {
            $this->db->limit(10, 0);
        }

        // pencarian
        if ($cari != null) {
            $this->db->where("(
                a.id_stok_keluar LIKE '%$cari%' or
                a.id_produk LIKE '%$cari%' or
                b.nama LIKE '%$cari%' or
                a.jumlah LIKE '%$cari%'
            )");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    public function getTambah($id)
    {
        $result = $this->db
            ->select('a.id, a.harga, a.jumlah, a.total_harga, a.satuan, a.id_produk')
            ->from('stok_keluar_detail a')
            ->where('a.id', $id)
            ->get()
            ->row_array();
        return $result;
    }


    public function insertDetail($id_stok_keluar, $produk, $jumlah)
    {
        $result = $this->db->insert("stok_keluar_detail", [
            'id_stok_keluar' => $id_stok_keluar,
            'id_produk' => $produk,
            'jumlah' => $jumlah,
            'status' => 0
        ]);
        // update stok
        $this->db->query("UPDATE produk as a
            SET qty_karton =
                ((
                    SELECT qty_karton
                    FROM produk as b
                    WHERE b.id = a.id
                    ) - $jumlah)
            WHERE a.id = $produk");
        return $result;
    }

    public function updateDetail($id, $id_stok_keluar, $produk, $jumlah)
    {
        // transaction start
        $this->db->trans_start();

        // get detail stok masuk
        $current_stok_keluar = $this->db
            ->select('id_produk, jumlah')
            ->where('id', $id)
            ->get('stok_keluar_detail')
            ->row_array();
        $this->db->reset_query();

        // reset produk sebelumnya
        $this->db->query("UPDATE produk as a
            SET qty_karton =
                ((
                    SELECT qty_karton
                    FROM produk as b
                    WHERE b.id = a.id
                    ) + {$current_stok_keluar['jumlah']})
            WHERE a.id = {$current_stok_keluar['id_produk']}");
        $this->db->reset_query();


        // tambah stok baru
        $this->db->query("UPDATE produk as a
            SET qty_karton =
                ((
                    SELECT qty_karton
                    FROM produk as b
                    WHERE b.id = a.id
                    ) - $jumlah)
            WHERE a.id = $produk");
        $this->db->reset_query();

        // update detail stok keluar
        $this->db->where('id', $id);
        $result = $this->db->update('stok_keluar_detail', [
            'id_stok_keluar' => $id_stok_keluar,
            'id_produk' => $produk,
            'jumlah' => $jumlah,
            'status' => 0,
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        $this->db->trans_complete();
        return $result;
    }

    public function delete($id)
    {
        // get detail produk
        $this->db->trans_start();
        $jumlah = $this->db->select('jumlah, id_produk')
            ->where(['id' => $id])
            ->get('stok_keluar_detail')
            ->row_array();
        $this->db->reset_query();

        // update stok
        $this->db->query("UPDATE produk as a
                SET qty_karton =
                    ((
                        SELECT qty_karton
                        FROM produk as b
                        WHERE b.id = a.id
                        ) + {$jumlah['jumlah']})
                WHERE a.id = {$jumlah['id_produk']}");
        $this->db->reset_query();

        // delete detail
        $result = $this->db->delete('stok_keluar_detail', ['id' => $id]);
        $this->db->trans_complete();
        return $result;
    }

    public function listProduk()
    {
        $return = $this->db->select('a.id, a.nama as text, a.qty_renceng as harga')
            ->from('produk a')
            ->get()
            ->result_array();
        return $return ?? [];
    }

    private function getCodestok_keluar()
    {
        $date = date('Y-m') . '-01 00:00:00.0';
        $this->db->select('RIGHT(a.id,5) as id', FALSE);
        $this->db->order_by('id', 'DESC');
        $this->db->where('created_at >=', $date);
        $this->db->limit(1);
        $query = $this->db->get('stok_keluar a');
        if ($query->num_rows() <> 0) {
            $data = $query->row();
            $kode = intval($data->id) + 1;
        } else {
            $kode = 1;
        }
        $return = "SK-" . date('m') . date('y') .  '-' . str_pad($kode, 5, "0", STR_PAD_LEFT);
        return $return;
    }

    public function getTambahData($id = null)
    {
        if ($id != null) {
            $this->db->select('a.id, a.id_penanggung_jawab, a.dibayar, a.status, a.id_sales');
            $this->db->from('stok_keluar a');
            $this->db->where('a.id', $id);
            $this->db->where('a.status <>', 99);
            // jika level admin
            if ($this->level == 'Admin') {
                $this->db->where('a.id_penanggung_jawab', $this->id_user);
            }
            $return = $this->db->get()->row_array();
            return $return;
        } else {
            $this->db->select('a.id, a.id_penanggung_jawab, a.dibayar, a.status');
            $this->db->from('stok_keluar a');
            $this->db->where('a.status', 0);
            // jika level admin
            if ($this->level == 'Admin') {
                $this->db->where('a.id_penanggung_jawab', $this->id_user);
            }
            $tambah = $this->db->get();
            if ($tambah->num_rows() > 0) {
                return $tambah->row_array();
            } else {
                $kode = $this->getCodestok_keluar();
                $data = [
                    'id' => $kode,
                    'dibayar' => 0,
                ];
                // jika level admin
                if ($this->level == 'Admin') {
                    $data = array_merge($data, ['id_penanggung_jawab' => $this->id_user]);
                }
                $this->db->insert('stok_keluar', $data);
                return $data;
            }
        }
    }

    public function getTotalHarga($id)
    {
        $return = $this->db->query("
            SELECT SUM(b.qty_karton * a.jumlah) AS total_harga
            FROM stok_keluar_detail AS a
            JOIN produk AS b ON a.id_produk = b.id
            WHERE id_stok_keluar = '$id'
        ")->row_array();
        $return = $return['total_harga'] ?? 0;
        return $return;
    }

    public function setTotalHarga($id)
    {
        $this->db->where('id', $id);
        $return = $this->db->update('stok_keluar', ['total_harga' => $this->getTotalHarga($id)]);
        return $return;
    }

    function simpanStokkeluar($id, $total_harga, $dibayar, $sisa, $id_user, $sales)
    {
        $this->db->where('id', $id);
        $data = [
            'status' => 1,
            'id_sales' => $sales,
            // 'total_harga' => $total_harga,
            // 'dibayar' => $dibayar,
            // 'sisa' => $sisa,
            'id_penanggung_jawab' => $id_user,
        ];
        if ($this->input->post('edit') == 'true') {
            $data['updated_at'] = date("Y-m-d H:i:s");
        }

        $return = $this->db->update('stok_keluar', $data);
        return $return;
    }

    function simpanStokkeluarDetail($id_stok_keluar)
    {
        $this->db->where('id_stok_keluar', $id_stok_keluar);
        $return = $this->db->update('stok_keluar_detail', ['status' => 1]);
        return $return;
    }

    function listAdmin()
    {
        $return = $this->db
            ->select('a.user_nama as text, a.user_id as id')
            ->from('users a')
            ->join('role_users b', 'b.role_user_id = a.user_id')
            ->where('a.user_status', 1)
            ->where('b.role_lev_id', 3)
            ->get()
            ->result_array();
        return $return ?? [];
    }

    function listSales()
    {
        $return = $this->db
            ->select('a.user_nama as text, a.user_id as id')
            ->from('users a')
            ->join('role_users b', 'b.role_user_id = a.user_id')
            ->where('a.user_status', 1)
            ->where('b.role_lev_id', 6)
            ->get()
            ->result_array();
        return $return ?? [];
    }

    public function listSatuanHarga()
    {
        $return = $this->db->select('id, nama as text, harga')
            ->where('status <> 0')
            ->get('satuan_harga')
            ->result_array();
        return $return ?? [];
    }

    public function listSatuanProduk()
    {
        $return = $this->db->select('id, nama as text')
            ->where('status <> 0')
            ->get('produk')
            ->result_array();
        return $return ?? [];
    }

    public function getHargaSatuanHarga($id)
    {
        $return = $this->db->select('harga')
            ->where('status <> 0')
            ->where('id', $id)
            ->get('satuan_harga')
            ->row_array();
        return $return['harga'] ?? 0;
    }

    function __construct()
    {
        parent::__construct();
        $this->level = $this->session->userdata('data')['level'];
        $this->id_user = $this->session->userdata('data')['id'];
    }
}
