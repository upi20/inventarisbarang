<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tambah extends Render_Controller
{

    public function index()
    {
        // Page Settings
        $this->title = 'Tambah Stok Keluar';
        $this->title_show = false;
        $this->navigation = ['Keluar'];
        $this->plugins = ['datatables', 'select2'];
        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url();
        $this->breadcrumb_2 = 'Stok';
        $this->breadcrumb_2_url = '#';
        $this->breadcrumb_3 = 'keluar';
        $this->breadcrumb_3_url = base_url() . 'stok/keluar/Listkeluar';
        $this->breadcrumb_4 = 'Tambah';
        $this->breadcrumb_4_url = base_url() . 'stok/keluar/Listkeluar/';
        $this->breadcrumb_show = false;

        // content
        $this->content      = 'stok/keluar/tambah';

        $id = $this->input->get('edit');

        // Send data to view
        $this->data['data'] = $this->model->getTambahData($id);
        if ($this->data['data'] == null) {
            redirect('my404', 'refresh');
        }

        $this->data['level'] = $this->level;
        // list admin
        $this->data['list_admin'] = [];
        if ($this->data['level'] == 'Admin') {
            $this->data['list_admin'] = [[
                'id' => $this->id_user,
                'text' => $this->user_nama
            ]];
        } else {
            $this->data['list_admin'] = $this->model->listAdmin();
            $this->data['list_admin'] = array_merge($this->data['list_admin'], [[
                'id' => $this->id_user,
                'text' => $this->user_nama
            ]]);
        }
        $this->data['list_produk'] = $this->model->listProduk();
        $this->data['list_sales'] = $this->model->listSales();
        $this->render();
    }


    public function ajax_data()
    {
        $order = ['order' => $this->input->post('order'), 'columns' => $this->input->post('columns')];
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $draw = $draw == null ? 1 : $draw;
        $length = $this->input->post('length');
        $cari = $this->input->post('search');
        $kode = $this->input->post('id');

        if (isset($cari['value'])) {
            $_cari = $cari['value'];
        } else {
            $_cari = null;
        }

        $data = $this->model->getAllData($kode, $draw, $length, $start, $_cari, $order)->result_array();
        $count = $this->model->getAllData($kode, null, null, null, $_cari, $order, null)->num_rows();

        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw, 'search' => $_cari, 'data' => $data]);
    }

    public function getTambah()
    {
        $id = $this->input->get("id");
        $result = $this->model->getTambah($id);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    public function insert()
    {
        $id_stok_keluar = $this->input->post("id_stok_keluar");
        $produk = $this->input->post("produk");
        $jumlah = $this->input->post("jumlah");

        $result = $this->model->insertDetail($id_stok_keluar, $produk, $jumlah);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    public function update()
    {
        $id = $this->input->post("id");
        $id_stok_keluar = $this->input->post("id_stok_keluar");
        $produk = $this->input->post("produk");
        $jumlah = $this->input->post("jumlah");

        $result = $this->model->updateDetail($id, $id_stok_keluar, $produk, $jumlah);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    public function getTotalHarga()
    {
        $id = $this->input->get('idStokkeluar');
        $result = $this->model->getTotalHarga($id);
        $this->output_json(['total' => $result]);
    }

    public function delete()
    {
        $id = $this->input->post("id");
        $result = $this->model->delete($id);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    function simpan()
    {
        $id = $this->input->post('id');
        $sales = $this->input->post('sales');
        $dibayar = $this->input->post('dibayar');
        $total_harga = $this->model->getTotalHarga($id);
        $sisa = (int)$total_harga - (int)$dibayar;
        $id_user = $this->input->post('id_penanggung_jawab');
        if ($this->level == 'Admin') {
            $id_user = $this->id_user;
        }
        // update stokkeluar
        $this->db->trans_start();
        $keluar = $this->model->simpanStokkeluar($id, $total_harga, $dibayar, $sisa, $id_user, $sales);

        // update detail
        $keluar_detail = $this->model->simpanStokkeluarDetail($id);
        $this->db->trans_complete();
        $this->output_json($keluar && $keluar_detail);
    }

    public function ajax_list_satuan_harga()
    {
        $data = $this->model->listSatuanHarga();
        $this->output_json($data);
    }

    public function ajax_list_satuan_produk()
    {
        $data = $this->model->listSatuanProduk();
        $this->output_json($data);
    }

    public function getHargaSatuanHarga()
    {
        $id = $this->input->get('id');
        $data = $this->model->getHargaSatuanHarga($id);
        $this->output_json($data);
    }

    function __construct()
    {
        parent::__construct();
        // Cek session
        $this->sesion->cek_session();
        $this->level = $this->session->userdata('data')['level'];
        $this->id_user = $this->session->userdata('data')['id'];
        $this->user_nama = $this->session->userdata('data')['nama'];

        // permission
        if ($this->level != 'Administrator' && $this->level != 'Admin') {
            redirect('my404', 'refresh');
        }
        $this->load->model("stok/keluar/tambahModel", 'model');
        $this->default_template = 'templates/dashboard';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}

/* End of file Pengguna.php */
/* Location: ./application/controllers/pengaturan/Pengguna.php */