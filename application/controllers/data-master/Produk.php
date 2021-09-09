<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk extends Render_Controller
{
    public function index()
    {
        // Page Settings
        $this->title = 'Data Master - Produk';
        $this->navigation = ['Data Master', 'Produk'];
        $this->plugins = ['datatables'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url();
        $this->breadcrumb_2 = 'Data Master';
        $this->breadcrumb_2_url = '#';
        $this->breadcrumb_3 = 'Produk';
        $this->breadcrumb_3_url = base_url() . 'data-master/produk';

        // content
        $this->content      = 'data-master/produk';

        // Send data to view
        $this->render();
    }

    // dipakai Administrator |
    public function ajax_data()
    {
        $order = ['order' => $this->input->post('order'), 'columns' => $this->input->post('columns')];
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $draw = $draw == null ? 1 : $draw;
        $length = $this->input->post('length');
        $cari = $this->input->post('search');

        if (isset($cari['value'])) {
            $_cari = $cari['value'];
        } else {
            $_cari = null;
        }

        $data = $this->model->getAllData($draw, $length, $start, $_cari, $order)->result_array();
        $count = $this->model->getAllData(null, null, null, $_cari, $order, null)->num_rows();

        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw, 'search' => $_cari, 'data' => $data]);
    }

    // dipakai Administrator |
    public function getData()
    {
        $id = $this->input->get("id");
        $result = $this->model->getData($id);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    // dipakai Administrator |
    public function insert()
    {
        $kategori = $this->input->post("kategori");
        $nama = $this->input->post("nama");
        $keterangan = $this->input->post("keterangan");
        $harga_beli = $this->input->post("harga_beli");
        $harga_jual = $this->input->post("harga_jual");
        $berat = $this->input->post("berat");
        $result = $this->model->insert($kategori, $nama, $keterangan, $harga_beli, $harga_jual, $berat);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    // dipakai Administrator |
    public function update()
    {
        $id = $this->input->post("id");
        $kategori = $this->input->post("kategori");
        $nama = $this->input->post("nama");
        $keterangan = $this->input->post("keterangan");
        $harga_beli = $this->input->post("harga_beli");
        $harga_jual = $this->input->post("harga_jual");
        $berat = $this->input->post("berat");
        $result = $this->model->update($id, $kategori, $nama, $keterangan, $harga_beli, $harga_jual, $berat);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    // dipakai Administrator |
    public function delete()
    {
        $id = $this->input->post("id");
        $result = $this->model->delete($id);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    // dipakai Registrasi |
    public function cari()
    {
        $key = $this->input->post('q');
        // jika inputan ada
        if ($key) {
            $this->output_json([
                "results" => $this->model->cari($key)
            ]);
        } else {
            $this->output_json([
                "results" => []
            ]);
        }
    }

    public function ajax_select_list_kategori()
    {
        $return = $this->model->listKategori();
        $this->output_json($return);
    }

    function __construct()
    {
        parent::__construct();
        // Cek session
        $this->sesion->cek_session();
        if ($this->session->userdata('data')['level'] != 'Administrator') {
            redirect('my404', 'refresh');
        }

        $this->load->model("data-master/produkModel", 'model');
        $this->default_template = 'templates/dashboard';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}
