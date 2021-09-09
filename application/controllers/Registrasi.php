<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Registrasi extends Render_Controller
{

    // Digunakan Registrasi
    public function index()
    {
        $registrasi = $this->registrasi->getData('siswa');
        if ($registrasi['nilai'] == 1) {
            // page attribut
            $this->title = 'Registrasi Siswa';
            $this->plugins = ['icheck', 'select2'];

            // content
            $this->content      = 'registrasi/siswa';

            // Send data to view
            $this->render();
        } else {
            $this->load->view('global_404');
            $this->output->set_status_header('404');
        }
    }

    // Digunakan Registrasi
    public function guru()
    {
        $registrasi = $this->registrasi->getData('guru');
        if ($registrasi['nilai'] == 1) {
            // page attribut
            $this->title = 'Registrasi Guru';
            $this->plugins = ['icheck', 'select2'];

            // content
            $this->content      = 'registrasi/guru';

            // Send data to view
            $this->render();
        } else {
            $this->load->view('global_404');
            $this->output->set_status_header('404');
        }
    }

    // Digunakan Registrasi
    public function insert_siswa()
    {
        $registrasi = $this->registrasi->getData('siswa');
        if ($registrasi['nilai'] == 1) {
            // load model pengguna untuk insert
            $this->load->model('pengaturan/penggunaModel', 'pengguna');
            $this->load->model("sekolah/siswaModel", 'model');

            // Mulai transaksi
            $this->db->trans_start();
            // insert user
            $status = 2;
            $level = 5;
            $nama = $this->input->post("nama");
            $no_telpon = $this->input->post("no_telpon");
            $username = $this->input->post("nisn");
            $password = $this->input->post("password");
            $user = $this->pengguna->insert($level, $nama, $no_telpon, $username, $password, $status);

            // insert siswa
            $id_user = $user['id'];
            $tanggal_lahir = $this->input->post("tanggal_lahir");
            $nisn = $this->input->post("nisn");
            $jenis_kelamin = $this->input->post("jenis_kelamin");
            $alamat = $this->input->post("alamat");
            $id_kelas = $this->input->post("id_kelas");
            $siswa = $this->model->insertSiswa($nisn, $id_user,$id_kelas, $nama, $tanggal_lahir, $jenis_kelamin, $alamat, $status);

            // simpan transaksi
            $this->db->trans_complete();
            $result = $user && $siswa;

            // kirim output
            $code = $result ? 200 : 500;
            $this->output_json(["data" => $result], $code);
        } else {
            $this->load->view('global_404');
            $this->output->set_status_header('404');
        }
    }

    // Digunakan Registrasi
    public function insert_guru()
    {
        $registrasi = $this->registrasi->getData('guru');
        if ($registrasi['nilai'] == 1) {
            // load model pengguna untuk insert
            $this->load->model('pengaturan/penggunaModel', 'pengguna');
            $this->load->model("sekolah/guruModel", 'model');

            // Mulai transaksi
            $this->db->trans_start();
            // insert user
            $status = 2;
            $level = 4;
            $nama = $this->input->post("nama");
            $no_telpon = $this->input->post("no_telpon");
            $username = $this->input->post("nip");
            $password = $this->input->post("password");
            $user = $this->pengguna->insert($level, $nama, $no_telpon, $username, $password, $status);

            // insert guru
            $id_user = $user['id'];
            $tanggal_lahir = $this->input->post("tanggal_lahir");
            $nip = $this->input->post("nip");
            $jenis_kelamin = $this->input->post("jenis_kelamin");
            $alamat = $this->input->post("alamat");
            $id_sekolah = $this->input->post("id_sekolah");
            $guru = $this->model->insertGuru($nip, $id_user, $id_sekolah, $nama, $tanggal_lahir, $jenis_kelamin, $alamat, $no_telpon, $status);

            // insert guru_kelas
            $id_kelas = $this->input->post("id_kelas");
            $guru_kelas = $this->model->insertGuruKelas($nip, $id_kelas, $status);

            // simpan transaksi
            $this->db->trans_complete();
            $result = $user && $guru && $guru_kelas;

            // kirim output
            $code = $result ? 200 : 500;
            $this->output_json(["data" => $result], $code);
        } else {
            $this->load->view('global_404');
            $this->output->set_status_header('404');
        }
    }

    function __construct()
    {
        parent::__construct();
        $this->default_template = 'templates/registrasi';
        $this->load->model('pengaturan/RegistrasiModel', 'registrasi');
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}
