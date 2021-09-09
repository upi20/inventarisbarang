<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends Render_Controller
{
    public function index()
    {
        $this->content = 'home/konten';
        $this->data['home'] = $this->db->get("konten_home")->row_array();
        $this->navigation = ['home'];
        $this->title = 'IDETO.co.id';
        $this->render();
    }

    function __construct()
    {
        parent::__construct();
        $this->default_template = 'templates/landing_page';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}
