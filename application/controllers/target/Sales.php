<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales extends Render_Controller
{

    // dipakai Administrator |
    public function index()
    {
        // Page Settings
        $this->title = 'Sales';
        $this->navigation = ['Target', 'Pencapaian Sales'];
        $this->plugins = ['datatables'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url();
        $this->breadcrumb_2 = 'Target';
        $this->breadcrumb_2_url = '#';
        $this->breadcrumb_3 = 'Sales';
        $this->breadcrumb_3_url = base_url() . 'target/sales';

        // content
        $this->content      = 'target/sales';

        // Send data to view
        $this->render();
    }

    public function uploadImage()
    {
        $nama = $this->session->userdata('data')['nama'];
        $id = $this->session->userdata('data')['id'];

        $config['upload_path']          = './gambar/target/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg|JPG|PNG|JPEG';
        $config['file_name']            = md5(uniqid("bobotohtoken", true));
        $config['overwrite']            = true;
        $config['max_size']             = 8024;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('file')) {
            return $this->upload->data("file_name");
        }
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
    public function getSales()
    {
        $id = $this->input->get("id");
        $result = $this->model->getSales($id);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    // dipakai Administrator |
    public function insert()
    {
        $target = $this->input->post("target");
        $sales = $this->input->post("sales");
        $jumlah = $this->input->post("jumlah");
        $status = $this->input->post("status");
        $result = $this->model->insert($target, $sales, $jumlah, $status);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    // dipakai Administrator |
    public function update()
    {
        $id = $this->input->post("id");
        $target = $this->input->post("target");
        $sales = $this->input->post("sales");
        $jumlah = $this->input->post("jumlah");
        $status = $this->input->post("status");
        $result = $this->model->update($id, $target, $sales, $jumlah, $status);
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

    public function ajax_select_list_target()
    {
        $return = $this->model->listTarget();
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

        $this->load->model("target/salesModel", 'model');
        $this->default_template = 'templates/dashboard';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}

/* End of file Pengguna.php */
/* Location: ./application/controllers/pengaturan/Pengguna.php */