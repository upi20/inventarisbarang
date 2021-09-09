<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends Render_Controller
{

	public function index()
	{
		$this->sesion->cek_login();
		$this->content = 'login';
		$this->render();
	}


	public function doLogin()
	{
		$username 	= $this->input->post('email');
		$password 	= $this->input->post('password');

		// Cek login ke model
		$login 		= $this->login->cekLogin($username, $password);


		if ($login['status'] == 0) {
			// Set session value
			// akun aktif
			if ($login['data'][0]['user_status'] == 1) {
				$session = array(
					'status' => true,
					'data'	 => array(
						'id' => $login['data'][0]['user_id'],
						'nama' => $login['data'][0]['user_nama'],
						'email' => $login['data'][0]['user_email'],
						'level' => $login['data'][0]['lev_nama'],
						'level_id' => $login['data'][0]['lev_id'],
					)
				);

				$this->session->set_userdata($session);

				$this->output_json(['status' => 0]);
			}
			// akun di nonaktifkan
			else if ($login['data'][0]['user_status'] == 0) {
				$this->output_json(['status' => 3]);
			}
			// menunggu dikonfirmasi
			else if ($login['data'][0]['user_status'] == 2) {
				$this->output_json(['status' => 4]);
			}
			// erorr
			else {
				$this->output_json(['status' => 5]);
			}
		} else if ($login['status'] == 1) {
			$this->output_json(['status' => 1]);
		} else {
			$this->output_json(['status' => 2]);
		}
	}


	public function logout()
	{
		$session = array('status', 'data');

		$this->session->unset_userdata($session);

		redirect('login', 'refresh');
	}


	function __construct()
	{
		parent::__construct();
		$this->load->model('loginModel', 'login');
		$this->default_template = 'templates/login';
		$this->load->library('plugin');
		$this->load->helper('url');
	}
}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */