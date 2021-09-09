<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RiwayatPembayaran extends Render_Controller
{
    public function index()
    {
        // Page Settings
        $this->title = 'Sales - Riwayat Pembayaran';
        $this->navigation = ['Sales', 'Riwayat Pembayaran'];
        $this->plugins = ['datatables'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url();
        $this->breadcrumb_2 = 'Sales';
        $this->breadcrumb_2_url = '#';
        $this->breadcrumb_3 = 'Riwayat Pembayaran';
        $this->breadcrumb_3_url = base_url() . 'sales/riwayatPembayaran';

        // content
        $this->content      = 'sales/riwayatPembayaran';

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

        // cek filter
        $filter = $this->input->post("filter");

        $data = $this->model->getAllData($draw, $length, $start, $_cari, $order, $filter)->result_array();
        $count = $this->model->getAllData(null, null, null, $_cari, $order, $filter)->num_rows();

        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw, 'search' => $_cari, 'data' => $data]);
    }

    // dipakai Administrator |
    public function getRiwayatPembayaran()
    {
        $id = $this->input->get("id");
        $result = $this->model->getRiwayatPembayaran($id);
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

    function __construct()
    {
        parent::__construct();
        // Cek session
        $this->sesion->cek_session();
        if ($this->session->userdata('data')['level'] != 'Administrator') {
            redirect('my404', 'refresh');
        }

        $this->load->model("sales/riwayatPembayaranModel", 'model');
        $this->default_template = 'templates/dashboard';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}
