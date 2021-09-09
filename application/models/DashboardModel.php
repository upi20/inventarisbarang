<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashboardModel extends Render_Model
{
    public function getJumlahSales(): int
    {
        $return = $this->db
            ->query('SELECT count(*) as jumlah FROM role_users WHERE role_lev_id = 6')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahWarung(): int
    {
        $return = $this->db
            ->query('SELECT count(*) as jumlah FROM warung')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahKecamatan(): int
    {
        $return = $this->db
            ->query('SELECT count(*) as jumlah FROM kecamatan')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahWilayah(): int
    {
        $return = $this->db
            ->query('SELECT count(*) as jumlah FROM wilayah')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahStokDiluar(): int
    {
        $return = $this->db
            ->query('SELECT sum(jumlah) as jumlah FROM stok_keluar_detail WHERE status = 1')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahKredit(): int
    {
        $return = $this->db
            ->query('SELECT sum(dibayar) as jumlah FROM warung_sales_penjualan WHERE status = 1')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahPemasukan(): int
    {
        $return = $this->db
            ->query('SELECT sum(dibayar) as jumlah FROM warung_sales_penjualan WHERE status = 2')
            ->row_array();
        return $return['jumlah'] ?? 0;
    }

    public function getJumlahPengadaan(): int
    {
        $return = $this->db
            ->query('SELECT sum(dibayar) as jumlah FROM stok_masuk WHERE status = 1')
            ->row_array();
        return $return['jumlah'] ?? 0;
    }

    public function api_getStokDibawa($id_sales): array
    {
        $return = $this->db
            ->select_sum('a.jumlah')
            ->from('stok_keluar_detail a')
            ->join('stok_keluar b', 'b.id = a.id_stok_keluar')
            ->where('b.status', 1)
            ->where('b.id_sales', $id_sales)
            ->get()->row_array();
        $return = [
            'karton' => (int)($return['jumlah'] ?? 0),
            'renceng' => 0
        ];
        return $return;
    }

    public function api_getTerjual($id_sales): array
    {
        $return = $this->db
            ->select_sum('a.jumlah_karton')
            ->select_sum('a.jumlah_renceng')
            ->from('warung_sales_penjualan a')
            ->join('stok_keluar b', 'b.id = a.id_stok_keluar')
            ->where('b.status', 1)
            ->where('b.id_sales', $id_sales)
            ->get()->row_array();
        $return = [
            'karton' => (int)($return['jumlah_karton'] ?? 0),
            'renceng' => (int)($return['jumlah_renceng'] ?? 0),
        ];
        return $return;
    }

    public function api_sisaPenjualan($stok, $terjual): array
    {
        $total_stok_renceng = $stok['karton'] * 12;
        $total_terjual_renceng = ($terjual['karton'] * 12) + $terjual['renceng'];
        $sisa_renceng = $total_stok_renceng - $total_terjual_renceng;
        $karton = floor($sisa_renceng / 12);
        $renceng = $sisa_renceng % 12;
        $return = [
            'karton' => $karton,
            'renceng' => $renceng
        ];
        return $return;
    }

    public function api_warung($id_sales, $id = null): ?array
    {
        $this->db->select("id, CONCAT(nama_pemilik, ' ', '[', kode, ']') as nama, alamat");
        $this->db->from('warung');
        $this->db->where('id_sales', $id_sales);
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
