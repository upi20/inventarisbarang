<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Login extends RestController
{
  public function index_post()
  {
    $username   = $this->input->post('username');
    $password   = $this->input->post('password');

    // Cek login ke model

    if ($this->session->userdata('status')) {
      $this->response([
        'status' => true,
        'message' => 'Anda sudah login'
      ], 200);
    }

    $login     = $this->login->cekLogin($username, $password);
    if ($login['status'] == 0) {
      // Set session value
      // akun aktif
      if ($login['data'][0]['user_status'] == 1) {
        $data = [
          'id' => $login['data'][0]['user_id'],
          'nama' => $login['data'][0]['user_nama'],
          'email' => $login['data'][0]['user_email'],
          'level' => $login['data'][0]['lev_nama'],
          'level_id' => $login['data'][0]['lev_id'],
        ];

        $this->session->set_userdata([
          'data' => $data,
          'status' => true
        ]);

        $this->response([
          'status' => true,
          'message' => 'Login berhasil',
          'data' => $data
        ], 200);
      }
      // akun di nonaktifkan
      else if ($login['data'][0]['user_status'] == 0) {
        $this->response([
          'status' => false,
          'message' => 'Akun di nonaktifkan'
        ], 400);
      }
      // menunggu dikonfirmasi
      else if ($login['data'][0]['user_status'] == 2) {
        $this->response([
          'status' => false,
          'message' => 'Akun di nonaktifkan'
        ], 400);
      }
      // erorr
      else {
        $this->response([
          'status' => false,
          'message' => 'Server error'
        ], 500);
      }
    } else if ($login['status'] == 1) {
      $this->response([
        'status' => false,
        'message' => 'Password salah'
      ], 400);
    } else {
      $this->response([
        'status' => false,
        'message' => 'Akun tidak ditemukan'
      ], 400);
    }
  }

  // development
  public function index_get()
  {
    $username   = $this->input->get('username');
    $password   = $this->input->get('password');

    // Cek login ke model

    if ($this->session->userdata('status')) {
      $this->response([
        'status' => true,
        'message' => 'Anda sudah login'
      ], 200);
    }

    $login     = $this->login->cekLogin($username, $password);
    if ($login['status'] == 0) {
      // Set session value
      // akun aktif
      if ($login['data'][0]['user_status'] == 1) {
        $data = [
          'id' => $login['data'][0]['user_id'],
          'nama' => $login['data'][0]['user_nama'],
          'email' => $login['data'][0]['user_email'],
          'level' => $login['data'][0]['lev_nama'],
          'level_id' => $login['data'][0]['lev_id'],
        ];

        $this->session->set_userdata([
          'data' => $data,
          'status' => true
        ]);

        $this->response([
          'status' => true,
          'message' => 'Login berhasil',
          'data' => $data
        ], 200);
      }
      // akun di nonaktifkan
      else if ($login['data'][0]['user_status'] == 0) {
        $this->response([
          'status' => false,
          'message' => 'Akun di nonaktifkan'
        ], 400);
      }
      // menunggu dikonfirmasi
      else if ($login['data'][0]['user_status'] == 2) {
        $this->response([
          'status' => false,
          'message' => 'Akun di nonaktifkan'
        ], 400);
      }
      // erorr
      else {
        $this->response([
          'status' => false,
          'message' => 'Server error'
        ], 500);
      }
    } else if ($login['status'] == 1) {
      $this->response([
        'status' => false,
        'message' => 'Password salah'
      ], 400);
    } else {
      $this->response([
        'status' => false,
        'message' => 'Akun tidak ditemukan'
      ], 400);
    }
  }

  public function logout_get()
  {
    $session = array('status', 'data');
    $this->session->unset_userdata($session);
    $this->response([
      'status' => true,
      'message' => 'Logout berhasil..'
    ], 200);
  }

  function __construct()
  {
    parent::__construct();
    $this->load->model('loginModel', 'login');
  }
}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */