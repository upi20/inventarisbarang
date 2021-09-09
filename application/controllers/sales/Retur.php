<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Retur extends Render_Controller
{
    public function index()
    {
        // Page Settings
        $this->title = 'Sales - Retur';
        $this->navigation = ['Sales', 'Retur'];
        $this->plugins = ['datatables'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url();
        $this->breadcrumb_2 = 'Sales';
        $this->breadcrumb_2_url = '#';
        $this->breadcrumb_3 = 'Retur';
        $this->breadcrumb_3_url = base_url() . 'sales/retur';

        // content
        $this->content      = 'sales/retur';

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