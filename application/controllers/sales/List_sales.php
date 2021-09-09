<?php
defined('BASEPATH') or exit('No direct script access allowed');

class List_sales extends Render_Controller
{


    public function index()
    {
        // Page Settings
        $this->title                     = 'List Sales';
        $this->content                   = 'sales/list_sales';
        $this->navigation                = ['Sales', 'List Sales'];
        $this->plugins                   = ['datatables', 'datatables-btn'];

        // Breadcrumb setting
        $this->breadcrumb_1             = 'Dashboard';
        $this->breadcrumb_1_url         = base_url() . 'dashboard';
        $this->breadcrumb_2             = 'Sales';
        $this->breadcrumb_2_url         = '#';
        $this->breadcrumb_3             = 'List_sales';
        $this->breadcrumb_3_url         = '#';

        // Send data to view
        $this->data['records']             = $this->list_sales->getAllData();
        $this->data['level']             = $this->list_sales->getDataLevel();

        $this->render();
    }


    // Get data detail
    public function getDataDetail()
    {
        $id                         = $this->input->post('id');

        $exe                         = $this->list_sales->getDataDetail($id);

        $this->output_json(
            [
                'id'             => $exe['role_user_id'],
                'level'         => $exe['role_lev_id'],
                'nama'             => $exe['user_nama'],
                'phone'         => $exe['user_phone'],
                'username'         => $exe['user_email'],
                'status'         => $exe['user_status'],
            ]
        );
    }


    // Insert data
    public function insert()
    {
        $level                         = $this->input->post('level');
        $nama                         = $this->input->post('nama');
        $telepon                     = $this->input->post('telepon');
        $username                     = $this->input->post('username');
        $status                     = $this->input->post('status');
        $password                     = $this->input->post('password');

        $exe                         = $this->list_sales->insert($level, $nama, $telepon, $username, $password, $status);

        $this->output_json(
            [
                'id'             => $exe['id'],
                'level'         => $exe['level'],
                'username'         => $username,
                'nama'             => $nama,
                'telepon'         => $telepon,
                'status'         => $status,
            ]
        );
    }


    // Update data
    public function update()
    {
        $id                         = $this->input->post('id');
        $level                         = $this->input->post('level');
        $nama                         = $this->input->post('nama');
        $telepon                     = $this->input->post('telepon');
        $username                     = $this->input->post('username');
        $status                     = $this->input->post('status');
        $password                     = $this->input->post('password');

        $exe                         = $this->list_sales->update($id, $level, $nama, $telepon, $username, $password, $status);

        $this->output_json(
            [
                'id'             => $id,
                'level'         => $exe['level'],
                'username'         => $username,
                'nama'             => $nama,
                'telepon'         => $telepon,
                'status'         => $status,
            ]
        );
    }


    // Delete data
    public function delete()
    {
        $id                             = $this->input->post('id');

        $exe                             = $this->list_sales->delete($id);

        $this->output_json(
            [
                'id'             => $id
            ]
        );
    }


    function __construct()
    {
        parent::__construct();
        $this->sesion->cek_session();
        if ($this->session->userdata('data')['level'] != 'Administrator') {
            redirect('my404', 'refresh');
        }
        $this->load->model('sales/list_salesModel', 'list_sales');
        $this->default_template = 'templates/dashboard';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}

/* End of file List_sales.php */
/* Location: ./application/controllers/sales/List_sales.php */