<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ListMasuk extends Render_Controller
{

    public function index()
    {
        // Page Settings
        $this->title = 'Stok Masuk';
        $this->title_show = false;
        $this->navigation = ['Masuk', 'Stok'];
        $this->plugins = ['datatables', 'daterangepicker'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url();
        $this->breadcrumb_2 = 'Stok';
        $this->breadcrumb_2_url = '#';
        $this->breadcrumb_3 = 'Masuk';
        $this->breadcrumb_3_url = base_url() . 'stok/masuk';
        $this->breadcrumb_show = false;

        $this->data['level'] = $this->level;
        // list admin
        $this->data['list_admin'] = [];
        if ($this->data['level'] == 'Admin') {
            $this->data['list_admin'] = [[
                'id' => $this->id_user,
                'text' => $this->user_nama
            ]];
        } else {
            $this->load->model("stok/masuk/tambahModel", 'tambah');
            $this->data['list_admin'] = $this->tambah->listAdmin();
            $this->data['list_admin'] = array_merge($this->data['list_admin'], [[
                'id' => $this->id_user,
                'text' => $this->user_nama
            ]]);
        }

        // content
        $this->content      = 'stok/masuk/list';

        // Send data to view
        $this->render();
    }

    public function uploadImage()
    {
        $nama = $this->session->userdata('data')['nama'];
        $id = $this->session->userdata('data')['id'];

        $config['upload_path']          = './gambar/pengadaan/';
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

    public function ajax_data()
    {
        $order = ['order' => $this->input->post('order'), 'columns' => $this->input->post('columns')];
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $draw = $draw == null ? 1 : $draw;
        $length = $this->input->post('length');
        $cari = $this->input->post('search');

        $date_start = $this->input->post('date_start');
        $date_end = $this->input->post('date_end');
        $admin = $this->input->post('admin');

        $filter = [
            'date' => [
                'start' => $date_start,
                'end' => $date_end,
            ],
            'admin' => $admin
        ];

        if (isset($cari['value'])) {
            $_cari = $cari['value'];
        } else {
            $_cari = null;
        }

        $data = $this->model->getAllData($draw, $length, $start, $_cari, $order, $filter)->result_array();
        $count = $this->model->getAllData(null, null,    null,   $_cari, $order, $filter)->num_rows();

        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw, 'search' => $_cari, 'data' => $data]);
    }

    public function getProduk()
    {
        $id = $this->input->get("id");
        $result = $this->model->getProduk($id);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    public function insert()
    {
        $sales = $this->input->post("sales");
        $jumlah = $this->input->post("jumlah");
        $status = $this->input->post("status");
        $result = $this->model->insert($sales, $jumlah, $status);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    public function update()
    {
        $id = $this->input->post("id");
        $sales = $this->input->post("sales");
        $jumlah = $this->input->post("jumlah");
        $status = $this->input->post("status");
        $result = $this->model->update($id, $sales, $jumlah, $status);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    public function delete()
    {
        $id = $this->input->post("id");
        $result = $this->model->delete($id);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

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

    public function ajax_select_list_sales()
    {
        $return = $this->model->listSales();
        $this->output_json($return);
    }

    public function getDetailStokMasuk()
    {
        $id = $this->input->get('id');
        $result = $this->model->getDetailStokMasuk($id);
        $this->output_json($result, $result == null ? 404 : 200);
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
        $this->load->model("stok/masuk/ListProdukModel", 'model');
        $this->default_template = 'templates/dashboard';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}

/* End of file Pengguna.php */
/* Location: ./application/controllers/pengaturan/Pengguna.php */