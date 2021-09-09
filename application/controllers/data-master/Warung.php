<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Warung extends Render_Controller
{
    public function index()
    {
        // Page Settings
        $this->title = 'Data Master - Warung';
        $this->navigation = ['Data Master', 'Warung'];
        $this->plugins = ['datatables'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url();
        $this->breadcrumb_2 = 'Data Master';
        $this->breadcrumb_2_url = '#';
        $this->breadcrumb_3 = 'Warung';
        $this->breadcrumb_3_url = base_url() . 'data-master/warung';

        // content
        $this->content      = 'data-master/warung';

        // Send data to view
        $this->render();
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
    public function getListWarung()
    {
        $id = $this->input->get("id");
        $result = $this->model->getListWarung($id);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    // dipakai Administrator |
    public function insert()
    {
        $sales = $this->input->post("sales");
        $kecamatan = $this->input->post("kecamatan");
        $kode = $this->getKode($kecamatan);
        $kategori = $this->input->post("kategori");
        $warung = $this->input->post("warung");
        $nama = $this->input->post("nama");
        $alamat = $this->input->post("alamat");
        $no_hp = $this->input->post("no_hp");
        $patokan = $this->input->post("patokan");
        $kordinat = $this->input->post("kordinat");
        $jenis = $this->input->post("jenis");
        $result = $this->model->insert($kode, $sales, $kecamatan, $kategori, $warung, $nama, $alamat, $no_hp, $patokan, $kordinat, $jenis);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    // dipakai Administrator |
    public function update()
    {
        $id = $this->input->post("id");
        $sales = $this->input->post("sales");
        $kecamatan = $this->input->post("kecamatan");
        $kategori = $this->input->post("kategori");
        $warung = $this->input->post("warung");
        $nama = $this->input->post("nama");
        $alamat = $this->input->post("alamat");
        $no_hp = $this->input->post("no_hp");
        $patokan = $this->input->post("patokan");
        $kordinat = $this->input->post("kordinat");
        $jenis = $this->input->post("jenis");
        $result = $this->model->update($id, $sales, $kecamatan, $kategori, $warung, $nama, $alamat, $no_hp, $patokan, $kordinat, $jenis);
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

    public function getKode($id_kecamatan = 0)
    {
        $get_kode_kecamatan = $this->db->select('kode')->get_where('kecamatan', ['id' => $id_kecamatan])->row_array();
        $kode_kecamatan = $get_kode_kecamatan['kode'];

        $this->db->select('RIGHT(warung.kode,5) as kode', FALSE);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('warung');  //cek dulu apakah ada sudah ada kode di tabel.    
        if ($query->num_rows() <> 0) {

            $data = $query->row();
            $kode = intval($data->kode) + 1;
        } else {
            $kode = 1;
        }
        $batas = str_pad($kode, 5, "0", STR_PAD_LEFT);
        $kodetampil = "BD-" . $kode_kecamatan . "-" . $batas;
        return $kodetampil;
    }

    public function ajax_select_list_sales()
    {
        $return = $this->model->listSales();
        $this->output_json($return);
    }

    public function ajax_select_list_kecamatan()
    {
        $return = $this->model->listKecamatan();
        $this->output_json($return);
    }

    public function ajax_select_list_kategori()
    {
        $return = $this->model->listKategori();
        $this->output_json($return);
    }

    private function getkategoribyId($id)
    {
        $return = $this->db->select("*")
            ->from('warung_kategori')
            ->where('id', $id)
            ->get()->row_array();
        // var_dump($this->db->last_query());
        // die;
        return $return;
    }

    private function getkecamatanbyId($kode)
    {
        $return = $this->db->select("*")
            ->from('kecamatan')
            ->where('kode', $kode)
            ->get()->row_array();
        return $return;
    }

    public function export_excel()
    {
        // data body
        $detail = $this->model->getAllIsiLaporExport();

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
        $col_end = "N";
        $title_excel = "Master_Warung";
        // Header excel ================================================================================================
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Dokumen Properti
        $spreadsheet->getProperties()
            ->setCreator("Distribusi Kopi Gadjah")
            ->setLastModifiedBy("Administrator")
            ->setTitle($title_excel)
            ->setSubject("Administrator")
            ->setDescription("Daftar Warung $date")
            ->setKeywords("Laporan, Report")
            ->setCategory("Laporan, Report");
        // set default font
        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(11);


        // header 2 ====================================================================================================
        $row += 1;
        $sheet->mergeCells($col_start . $row . ":" . $col_end . $row)
            ->setCellValue("A$row", "Daftar Warung");
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
            'Kode',
            'ID Sales',
            'Sales',
            'ID Kategori',
            'Nama Kategori',
            'Kode Kecamatan',
            'Kecamatan',
            'Nama Warung',
            'Pemilik',
            'Alamat',
            'No HP',
            'Patokan',
            'Koordinat',
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
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['kode']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['id_sales']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['sales']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['id_kategori']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['kategori']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['kode_kecamatan']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['kecamatan']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['warung']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['nama']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['alamat']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['no_hp']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['patokan']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['kordinat']);
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
        $sheet->getStyle("E" . $start_tabel . ":" . "E" . $row)->getAlignment()->setHorizontal('center');
        $sheet->getStyle("E" . $start_tabel . ":" . "G" . $row)->getAlignment()->setHorizontal('center');
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
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(w(20));
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(w(20));
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(w(20));
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(w(20));
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(w(20));
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(w(30));
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(w(15));
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(w(30));
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(w(30));

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

    public function downloadSample()
    {
        // data body
        $detail = $this->model->getAllIsiLaporExport();

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
        $col_end = "N";
        $title_excel = "Master_Warung";
        // Header excel ================================================================================================
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Dokumen Properti
        $spreadsheet->getProperties()
            ->setCreator("Distribusi Kopi Gadjah")
            ->setLastModifiedBy("Administrator")
            ->setTitle($title_excel)
            ->setSubject("Administrator")
            ->setDescription("Daftar Warung $date")
            ->setKeywords("Laporan, Report")
            ->setCategory("Laporan, Report");
        // set default font
        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(11);


        // header 2 ====================================================================================================
        $row += 1;
        $sheet->mergeCells($col_start . $row . ":" . $col_end . $row)
            ->setCellValue("A$row", "Daftar Warung");
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
            'Kode',
            'ID Sales',
            'Sales',
            'ID Kategori',
            'Nama Kategori',
            'Kode Kecamatan',
            'Kecamatan',
            'Nama Warung',
            'Pemilik',
            'Alamat',
            'No HP',
            'Patokan',
            'Koordinat',
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
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['kode']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['id_sales']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['sales']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['id_kategori']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['kategori']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['kode_kecamatan']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['kecamatan']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['warung']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['nama']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['alamat']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['no_hp']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['patokan']);
            $sheet->setCellValue(chr(65 + ++$c) . "$row", $q['kordinat']);
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
        $sheet->getStyle("E" . $start_tabel . ":" . "E" . $row)->getAlignment()->setHorizontal('center');
        $sheet->getStyle("E" . $start_tabel . ":" . "G" . $row)->getAlignment()->setHorizontal('center');
        // $sheet->getStyle("C" . $start_tabel . ":D" . $row)
        //     ->getAlignment()
        //     ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        // footer
        // $row += 3;
        // $sheet->setCellValue("Q" . $row, "Kasui, $date");

        // $row += 3;
        // $sheet->setCellValue("Q" . $row, "(.....................................)");

        // function for width column
        function wh($width)
        {
            return 0.71 + $width;
        }


        // set width column
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(wh(20));
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(wh(20));
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(wh(20));
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(wh(20));
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(wh(20));
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(wh(30));
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(wh(15));
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(wh(30));
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(wh(30));

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
                $kategori = $this->getkategoribyId($val[4]);
                $kecamatan = $this->getkecamatanbyId($val[6]);
                if ($kategori == null) {
                    $url = base_url('data-master/warung');
                    echo "<script>
					alert('Import data gagal ID Kategori tidak ditemukan dalam database.');
					window.location.href='$url';
					</script>";
                    die;
                }
                if ($kecamatan == null) {
                    $url = base_url('data-master/warung');
                    echo "<script>
					alert('Import data gagal ID Kecamatan tidak ditemukan dalam database.');
					window.location.href='$url';
					</script>";
                    die;
                }

                $this->db->insert('warung', [
                    "kode"          => $this->getKode($kecamatan['id']),
                    "id_sales"   => $val[2] == "" ? NULL : $val[2],
                    "id_kategori"   => $val[4] == "" ? NULL : $val[4],
                    "id_kecamatan"  => $kecamatan['id'] == "" ? NULL : $kecamatan['id'],
                    "nama_warung"  => $val[8] == "" ? NULL : $val[8],
                    "nama_pemilik"  => $val[9] == "" ? NULL : $val[9],
                    "alamat"        => $val[10] == "" ? NULL : $val[10],
                    "no_hp"         => $val[11] == "" ? NULL : $val[11],
                    "patokan"      => $val[12] == "" ? NULL : $val[12],
                    "kordinat"      => $val[13] == "" ? NULL : $val[13],
                    "status"        => 1,
                    "created_at"    => date("Y-m-d H:i:s")
                ]);
            }
            $num++;
        }


        // database transaction complete
        $this->db->trans_complete();

        $url = base_url('data-master/warung');
        echo "<script>
		alert('Import data berhasil..!');
		window.location.href='$url';
		</script>";
    }

    function __construct()
    {
        parent::__construct();
        // Cek session
        $this->sesion->cek_session();
        if ($this->session->userdata('data')['level'] != 'Administrator') {
            redirect('my404', 'refresh');
        }

        $this->load->model("data-master/warungModel", 'model');
        $this->default_template = 'templates/dashboard';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}
