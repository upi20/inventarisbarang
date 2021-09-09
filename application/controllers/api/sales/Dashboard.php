<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Dashboard extends RestController
{
  public function index_get()
  {
    $stok_dibawa = $this->model->api_getStokDibawa($this->id);
    $terjual = $this->model->api_getTerjual($this->id);
    $sisa = $this->model->api_sisaPenjualan($stok_dibawa, $terjual);

    $this->response([
      'status' => true,
      'length' => 3,
      'data' => [
        'stok_dibawa' => $stok_dibawa,
        'terjual' => $terjual,
        'sisa' => $sisa
      ],
    ], RestController::HTTP_OK);
  }

  public function warung_get()
  {
    $id = $this->get('id');
    $data = $this->model->api_warung($this->id, $id);
    $code = $data['data'] == null ?
      RestController::HTTP_NOT_FOUND
      : RestController::HTTP_OK;
    $status = $data['data'] != null;

    // send response
    $this->response([
      'status' => $status,
      'length' => $data['length'],
      'data' => $data['data']
    ], $code);
  }

  function __construct()
  {
    parent::__construct();

    // cek sesi
    if (!$this->sesion->cek_session_api()) {
      $this->response([
        'status' => false,
        'message' => 'Anda belum login'
      ], RestController::HTTP_UNAUTHORIZED);
      exit();
    }

    // cek level
    $this->level = $this->session->userdata('data')['level'];
    $this->id = $this->session->userdata('data')['id'];
    if ($this->level != 'Sales') {
      $this->response([
        'status' => false,
        'message' => 'Akses anda ditolak'
      ], RestController::HTTP_FORBIDDEN);
      exit();
    }

    // import model
    $this->load->model('DashboardModel', 'model');
  }
}
