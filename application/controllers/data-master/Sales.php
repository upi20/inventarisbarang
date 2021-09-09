<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales extends Render_Controller
{


    public function index()
    {
        // Page Settings
        $this->title                     = 'Sales';
        $this->content                     = 'data-master/Sales';
        $this->navigation                 = ['Data Master', 'Sales '];
        $this->plugins                     = ['datatables', 'datatables-btn'];

        // Breadcrumb setting
        $this->breadcrumb_1             = 'Dashboard';
        $this->breadcrumb_1_url         = base_url() . 'dashboard';
        $this->breadcrumb_2             = 'Data Master';
        $this->breadcrumb_2_url         = '#';
        $this->breadcrumb_3             = 'Data Master';
        $this->breadcrumb_3_url         = '#';

        // Send data to view
        $this->data['records']             = $this->Sales->getAllData();
        $this->data['level']             = $this->Sales->getDataLevel();

        $this->render();
    }


    // Get data detail
    public function getDataDetail()
    {
        $id                         = $this->input->post('id');

        $exe                         = $this->Sales->getDataDetail($id);

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
        $status                     = '1';
        $password                     = $this->input->post('password');

        $exe                         = $this->Sales->insert($level, $nama, $telepon, $username, $password, $status);

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
        $password                     = $this->input->post('password');

        $exe                         = $this->Sales->update($id, $level, $nama, $telepon, $username, $password);

        $this->output_json(
            [
                'id'             => $id,
                'level'         => $exe['level'],
                'username'         => $username,
                'nama'             => $nama,
                'telepon'         => $telepon,
            ]
        );
    }


    // Delete data
    public function delete()
    {
        $id                             = $this->input->post('id');

        $exe                             = $this->Sales->delete($id);

        $this->output_json(
            [
                'id'             => $id
            ]
        );
    }

    private function getLevelbyId($id)
    {
        $return = $this->db->select("*")
            ->from('level')
            ->where('lev_id', $id)
            ->get()->row_array();
        return $return;
    }

    public function export_excel()
    {
        // data body
        $detail = $this->Sales->getAllIsiLaporExport();

        $bulan_array = [
            1 => 'Januari',
            2 => 'February',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $today_m = (int)Date("m");
        $today_d = (int)Date("d");
        $today_y = (int)Date("Y");

        $last_date_of_this_month =  date('t', strtotime(date("Y-m-d")));

        $date = $today_d . " " . $bulan_array[$today_m] . " " . $today_y;

        // laporan baru
        $row = 1;
        $col_start = "A";
        $col_end = "K";
        $title_excel = "Master_Sales";
        // Header excel ================================================================================================
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Dokumen Properti
        $spreadsheet->getProperties()
            ->setCreator("Sales Kopi Gadjah")
            ->setLastModifiedBy("Administrator")
            ->setTitle($title_excel)
            ->setSubject("Administrator")
            ->setDescription("Daftar Sales $date")
            ->setKeywords("Laporan, Report")
            ->setCategory("Laporan, Report");
        // set default font
        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(11);


        // header 2 ====================================================================================================
        $row += 1;
        $sheet->mergeCells($col_start . $row . ":" . $col_end . $row)
            ->setCellValue("A$row", "Daftar Sales");
        $sheet->getStyle($col_start . $row . ":" . $col_end . $row)->applyFromArray([
            "font" => [
                "bold" => true,
                "size" => 13
            ],
            "alignment" => [
                "horizontal" => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Tabel =======================================================================================================
        // Tabel Header
        $row += 2;
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '93C5FD',
                ]
            ],
        ];
        $sheet->getStyle($col_start . $row . ":" . $col_end . $row)->applyFromArray($styleArray);
        $row++;
        $styleArray['fill']['startColor']['rgb'] = 'E5E7EB';
        $sheet->getStyle($col_start . $row . ":" . $col_end . $row)->applyFromArray($styleArray);

        // poin-poin header disini
        $headers = [
            'No',
            'ID',
            'ID Level',
            'Nama',
            'Password',
            'Email',
            'Phone',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
        ];

        // apply header
        for ($i = 0; $i < count($headers); $i++) {
            $sheet->setCellValue(chr(65 + $i) . ($row - 1), $headers[$i]);
            $sheet->setCellValue(chr(65 + $i) . $row, ($i + 1));
        }

        // tabel body
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            "alignment" => [
                'wrapText' => TRUE,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
            ]
        ];
        $start_tabel = $row + 1;
        foreach ($detail as $q) {
            $c = 0;
            $row++;
            $sheet->setCellValue(chr(65 + $c) . "$row", ($row - 5));
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['user_id']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['role_lev_id']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['user_nama']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['user_password']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['user_email']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['user_phone']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['user_status']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['created_at']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['updated_at']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['created_at']);
        }
        // format
        // nomor center
        $sheet->getStyle($col_start . $start_tabel . ":" . $col_start . $row)
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // border all data
        $sheet->getStyle($col_start . $start_tabel . ":" . $col_end . $row)
            ->applyFromArray($styleArray);

        // $code_rm = '_-[$RM-ms-MY]* #.##0,00_-;-[$RM-ms-MY]* #.##0,00_-;_-[$RM-ms-MY]* "-"??_-;_-@_-';
        // $sheet->getStyle("F" . $start_tabel . ":" . $col_end . $row)->getNumberFormat()->setFormatCode($code_rm);
        // $sheet->getStyle("G" . $start_tabel . ":" . "G" . $row)
        //     ->getNumberFormat()
        //     ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        // $sheet->getStyle("I" . $start_tabel . ":" . "I" . $row)
        //     ->getNumberFormat()
        //     ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        // set alignment
        $sheet->getStyle("A" . $start_tabel . ":" . "A" . $row)->getAlignment()->setHorizontal('center');
        $sheet->getStyle("B" . $start_tabel . ":" . "B" . $row)->getAlignment()->setHorizontal('center');
        $sheet->getStyle("C" . $start_tabel . ":" . "C" . $row)->getAlignment()->setHorizontal('center');
        // $sheet->getStyle("C" . $start_tabel . ":D" . $row)
        //     ->getAlignment()
        //     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        // footer
        // $row += 3;
        // $sheet->setCellValue("Q" . $row, "Kasui, $date");

        // $row += 3;
        // $sheet->setCellValue("Q" . $row, "(.....................................)");

        // function for width column
        function w($width)
        {
            return 0.71 + $width;
        }


        // set width column
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(w(20));
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(w(30));
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(w(20));
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(w(20));
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(w(17));
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(w(17));
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(w(17));

        // set  printing area
        $spreadsheet->getActiveSheet()->getPageSetup()->setPrintArea($col_start . '1:' . $col_end . $row);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // margin
        $spreadsheet->getActiveSheet()->getPageMargins()->setTop(1);
        $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0);
        $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(0);

        // page center on
        $spreadsheet->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $spreadsheet->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

        // $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        // $writer->save($title_excel);
        // header("Location: " . base_url($title_excel));
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);


        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $title_excel . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function import()
    {

        // Fungsi upload file
        $fileName = $_FILES['file']['name'];
        $config['upload_path'] = './assets/'; //path upload
        $config['file_name'] = $fileName;  // nama file
        $config['allowed_types'] = 'xls|xlsx'; //tipe file yang diperbolehkan
        $config['max_size'] = 100000; // maksimal sizze

        $this->load->library('upload'); //meload librari upload
        $this->upload->initialize($config);

        $file_location = "";

        if (!$this->upload->do_upload('file')) {
            echo json_encode(['code' => 1, 'message' => $this->upload->display_errors()]);

            exit();
        } else {
            $file_location = array('upload_data' => $this->upload->data());
            $file_location = $file_location['upload_data']['full_path'];
        }

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_location);
        // hapus file setelah digunakan
        unlink($file_location);
        $array_from_excel = $spreadsheet->getActiveSheet()->toArray();
        $num = 1;
        $no_resi1 = '';
        $error_message = '';
        $kode_penjualan = '';
        // var_dump($array_from_excel);
        // die;
        // get kode admin by session id_user

        $no_resi = [];
        $this->db->trans_start();
        foreach ($array_from_excel as $val) {
            if ($num > 5 && $val[5]) {
                $level = $this->getLevelbyId($val[3]);
                // var_dump($val);
                // die;
                if ($level > 0) {
                    $url = base_url('data-master/sales');
                    echo "<script>
					alert('Import data gagal ID Level tidak ditemukan dalam database.');
					window.location.href='$url';
					</script>";
                    die;
                }

                $this->db->insert('users', [
                    "user_nama" => $val[3],
                    "user_password" => $val[4],
                    "user_email" => $val[5],
                    "user_phone" => $val[6],
                    "user_status" => $val[7],
                    "created_at" => $val[8],
                    "updated_at" => $val[9],
                    "deleted_at" => $val[10]
                ]);
                $id_user = $this->db->insert_id();


                $this->db->insert('role_users', [
                    "role_user_id" => $id_user,
                    "role_lev_id" => $val[2]
                ]);
            }
            $num++;
        }


        // database transaction complete
        $this->db->trans_complete();

        $url = base_url('data-master/sales');
        echo "<script>
		alert('Import data berhasil..!');
		window.location.href='$url';
		</script>";
    }


    function __construct()
    {
        parent::__construct();
        $this->sesion->cek_session();
        if ($this->session->userdata('data')['level'] != 'Administrator') {
            redirect('my404', 'refresh');
        }
        $this->load->model('data-master/SalesModel', 'Sales');
        $this->default_template = 'templates/dashboard';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}

/* End of file Sales.php */
/* Location: ./application/controllers/data-master/Sales.php */