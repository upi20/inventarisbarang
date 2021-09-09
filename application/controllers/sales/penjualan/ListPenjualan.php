<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ListPenjualan extends Render_Controller
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