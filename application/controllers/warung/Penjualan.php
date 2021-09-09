<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan extends Render_Controller
{

    // dipakai Administrator |
    public function index()
    {
        // Page Settings
        $this->title = 'Penjualan';
        $this->navigation = ['Warung', 'Penjualan'];
        $this->plugins = ['datatables'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url();
        $this->breadcrumb_2 = 'Warung';
        $this->breadcrumb_2_url = '#';
        $this->breadcrumb_3 = 'Penjualan';
        $this->breadcrumb_3_url = base_url() . 'warung/penjualan';

        // content
        $this->content      = 'warung/penjualan';

        // Send data to view
        $this->render();
    }

    public function uploadImage()
    {
        $nama = $this->session->userdata('data')['nama'];
        $id = $this->session->userdata('data')['id'];

        $config['upload_path']          = './gambar/warung/';
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
    public function getPenjualan()
    {
        $id = $this->input->get("id");
        $result = $this->model->getPenjualan($id);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    // dipakai Administrator |
    public function insert()
    {
        $warung = $this->input->post("warung");
        $sales = $this->input->post("sales");
        $produk = $this->input->post("produk");
        $harga = $this->input->post("harga");
        $jumlah = $this->input->post("jumlah");
        $total_harga = $this->input->post("total_harga");
        $dibayar = $this->input->post("dibayar");
        $sisa = $this->input->post("sisa");
        $setoran = $this->input->post("setoran");
        $penerima = $this->input->post("penerima");
        $hutang = $this->input->post("hutang");
        $status = $this->input->post("status");
        $result = $this->model->insert($warung, $sales, $produk, $harga, $jumlah, $total_harga, $dibayar, $sisa, $setoran, $penerima, $hutang, $status);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    // dipakai Administrator |
    public function update()
    {
        $id = $this->input->post("id");
        $warung = $this->input->post("warung");
        $sales = $this->input->post("sales");
        $produk = $this->input->post("produk");
        $harga = $this->input->post("harga");
        $jumlah = $this->input->post("jumlah");
        $total_harga = $this->input->post("total_harga");
        $dibayar = $this->input->post("dibayar");
        $sisa = $this->input->post("sisa");
        $setoran = $this->input->post("setoran");
        $penerima = $this->input->post("penerima");
        $hutang = $this->input->post("hutang");
        $status = $this->input->post("status");
        $result = $this->model->update($id, $warung, $sales, $produk, $harga, $jumlah, $total_harga, $dibayar, $sisa, $setoran, $penerima, $hutang, $status);
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

    public function ajax_select_list_warung()
    {
        $return = $this->model->listWarung();
        $this->output_json($return);
    }

    public function ajax_select_list_sales()
    {
        $return = $this->model->listSales();
        $this->output_json($return);
    }

    public function ajax_select_list_produk()
    {
        $return = $this->model->listProduk();
        $this->output_json($return);
    }

    public function ajax_select_list_penerima()
    {
        $return = $this->model->listPenerima();
        $this->output_json($return);
    }

    public function produkById()
    {
        $id = $this->input->post('id');
        $return = $this->model->produkById($id);
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

        $this->load->model("warung/penjualanModel", 'model');
        $this->default_template = 'templates/dashboard';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}

/* End of file Pengguna.php */
/* Location: ./application/controllers/warung/Pengguna.php */