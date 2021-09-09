<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan extends Render_Controller
{
    public function index()
    {
        // Page Settings
        $this->title = 'Sales - Penjualan';
        $this->navigation = ['Sales', 'Penjualan'];
        $this->plugins = ['datatables'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url();
        $this->breadcrumb_2 = 'Sales';
        $this->breadcrumb_2_url = '#';
        $this->breadcrumb_3 = 'Penjualan';
        $this->breadcrumb_3_url = base_url() . 'sales/penjualan';

        // content
        $this->content      = 'sales/penjualan';

        // Send data to view
        $this->render();
    }

    public function detailPenjualan()
    {
        // Page Settings
        $this->title = 'Sales - Detail Penjualan';
        $this->title_show = false;
        $this->navigation = ['Sales', 'Penjualan'];
        $this->plugins = ['datatables'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url();
        $this->breadcrumb_2 = 'Sales';
        $this->breadcrumb_2_url = '#';
        $this->breadcrumb_3 = 'DetailPenjualan';
        $this->breadcrumb_3_url = base_url() . 'sales/detailPenjualan';
        $this->breadcrumb_show = false;

        // content
        $this->content      = 'sales/detailPenjualan';

        // Send data to view
        $id = $this->uri->segment('4');
        $a = $this->model->getTagihan($id)->result_array();
        $this->data['kod'] = $a;
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

        // cek filter
        $filter = $this->input->post("filter");

        $data = $this->model->getAllData($draw, $length, $start, $_cari, $order, $filter)->result_array();
        $count = $this->model->getAllData(null, null, null, $_cari, $order, $filter)->num_rows();

        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw, 'search' => $_cari, 'data' => $data]);
    }

    // dipakai Administrator |
    public function getDetail()
    {
        $id = $this->uri->segment('4');
        // cek filter
        $filter = $this->input->post("filter");

        $data = $this->model->getDetail($id, $filter)->result_array();
        $count = $this->model->getDetail($id, $filter)->num_rows();
        // $code = $result ? 200 : 500;
        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'data' => $data]);
    }
    public function getPenjualan()
    {
        $id = $this->input->get("id");
        $result = $this->model->getPenjualan($id);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    public function setor()
    {
        $id_stok_keluar = $this->input->post("id_stok_keluar");
        $total_harga = $this->input->post("total_harga");
        $setoran = $this->input->post("setoran");
        $sisa = $this->input->post("sisa");
        $result = $this->model->setorPembayaran($id_stok_keluar, $total_harga, $setoran, $sisa);
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

    public function ajax_select_list_kecamatan()
    {
        $return = $this->model->listKecamatan();
        $this->output_json($return);
    }

    public function ajax_select_list_sales()
    {
        $return = $this->model->listSales();
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

        $this->load->model("sales/penjualanModel", 'model');
        $this->default_template = 'templates/dashboard';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}
