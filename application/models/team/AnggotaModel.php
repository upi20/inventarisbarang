<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AnggotaModel extends Render_Model
{
    // dipakai Administrator |
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        // select tabel
        $this->db->select("*, IF(team.status = '0' , 'Tidak Aktif', IF(team.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("team");

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
            $this->db->where("(urutan LIKE '%$cari%' or nama LIKE '%$cari%' or sebagai LIKE '%$cari%' or link LIKE '%$cari%' or deskripsi LIKE '%$cari%' or IF(team.status = '0' , 'Tidak Aktif', IF(team.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%')");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    // dipakai Administrator |
    public function getAnggota($id)
    {
        $result = $this->db->get_where("team", ['id' => $id])->row_array();
        return $result;
    }

    // dipakai Administrator |
    public function insert($urutan, $nama, $sebagai, $link, $deskripsi, $foto, $status)
    {
        $result = $this->db->insert("team", [
            'urutan' => $urutan,
            'nama' => $nama,
            'sebagai' => $sebagai,
            'link' => $link,
            'deskripsi' => $deskripsi,
            'foto' => $foto,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s"),
        ]);

        return $result;
    }

    // dipakai Administrator |
    public function update($id, $urutan, $nama, $sebagai, $link, $deskripsi, $foto, $status)
    {
        $this->db->where('id', $id);
        $result = $this->db->update('team', [
            'urutan' => $urutan,
            'nama' => $nama,
            'sebagai' => $sebagai,
            'link' => $link,
            'deskripsi' => $deskripsi,
            'foto' => $foto,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    // dipakai Administrator |
    public function delete($id)
    {
        $result = $this->db->delete('team', ['id' => $id]);
        return $result;
    }

    // dipakai Registrasi
    public function cari($key)
    {
        $this->db->select('a.id as id, a.judul as text');
        $this->db->from('team a');
        $this->db->where("judul LIKE '%$key%' or keterangan LIKE '%$key%' or jumlah_klik LIKE '%$key%'");
        return $this->db->get()->result_array();
    }
}
