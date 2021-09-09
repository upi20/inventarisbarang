<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SatuanHarga extends Render_Controller
{
    public function index()
    {
        // Page Settings
        $this->title = 'Satuan Harga';
        $this->navigation = ['Data Master', 'Satuan Harga'];
        $this->plugins = ['datatables'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url();
        $this->breadcrumb_2 = 'Data Master';
        $this->breadcrumb_2_url = '#';
        $this->breadcrumb_3 = 'Satuan Harga';
        $this->breadcrumb_3_url = base_url() . 'data-master/satuan-harga';

        // content
        $this->content      = 'data-master/satuan-harga';

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
    public function getSatuanHarga()
    {
        $id = $this->input->get("id");
        $result = $this->model->getSatuanHarga($id);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    // dipakai Administrator |
    public function insert()
    {
        $qty = $this->input->post("qty");
        $nama = $this->input->post("nama");
        $harga = $this->input->post("harga");
        $status = $this->input->post("status");
        $result = $this->model->insert($qty, $nama, $harga, $status);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    // dipakai Administrator |
    public function update()
    {
        $id = $this->input->post("id");
        $qty = $this->input->post("qty");
        $nama = $this->input->post("nama");
        $harga = $this->input->post("harga");
        $status = $this->input->post("status");
        $result = $this->model->update($id, $qty, $nama, $harga, $status);
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

    function __construct()
    {
        parent::__construct();
        // Cek session
        $this->sesion->cek_session();
        if ($this->session->userdata('data')['level'] != 'Administrator') {
            redirect('my404', 'refresh');
        }

        $this->load->model("data-master/satuanHargaModel", 'model');
        $this->default_template = 'templates/dashboard';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}
