<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Konversi extends Render_Controller
{

    // dipakai Administrator |
    public function index()
    {
        // Page Settings
        $this->title = 'Konversi';
        $this->navigation = ['Data Master', 'Konversi'];
        $this->plugins = ['datatables'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url();
        $this->breadcrumb_2 = 'Data Master';
        $this->breadcrumb_2_url = '#';
        $this->breadcrumb_3 = 'Konversi';
        $this->breadcrumb_3_url = base_url() . 'data-master/konversi';

        // content
        $this->content      = 'data-master/konversi';

        // Send data to view
        $this->render();
    }

    public function getData()
    {
        $result = $this->model->getData();
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    // dipakai Administrator |
    public function insert()
    {
        $karton = $this->input->post("karton");
        $renceng = $this->input->post("renceng");
        $status = $this->input->post("status");
        $result = $this->model->insert($karton, $renceng, $status);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    // dipakai Administrator |
    public function update()
    {
        $id = $this->input->post("id");
        $karton = $this->input->post("karton");
        $renceng = $this->input->post("renceng");
        $status = $this->input->post("status");
        $result = $this->model->update($id, $karton, $renceng, $status);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    function __construct()
    {
        parent::__construct();
        // Cek session
        $this->sesion->cek_session();
        if ($this->session->userdata('data')['level'] != 'Administrator') {
            redirect('my404', 'refresh');
        }

        $this->load->model("data-master/konversiModel", 'model');
        $this->default_template = 'templates/dashboard';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}

/* End of file Pengguna.php */
/* Location: ./application/controllers/data-master/Pengguna.php */