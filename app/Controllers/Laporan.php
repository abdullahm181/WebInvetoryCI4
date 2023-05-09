<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelbarangmasuk;
use App\Models\Modelbarangkeluar;
use App\Models\Modeldetailbarangmasuk;
use App\Models\Modeldetailbarangkeluar;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Phpoffice\PhpSpreadsheet\Writer\Xlsx;
use Config\Services;

class Laporan extends BaseController
{
    public function index()
    {
        return view('laporan/index');
    }

    public function cetak_barang_masuk()
    {
        return view('laporan/viewbarangmasuk');
    }
    public function cetak_barang_keluar()
    {
        return view('laporan/viewbarangkeluar');
    }

    public function listDataDetailBarangMasuk()
    {
        $tglawal = $this->request->getPost('tglawal');
        $tglakhir = $this->request->getPost('tglakhir');

        $request = Services::request();
        $datamodel = new Modeldetailbarangmasuk();
        $lists = $datamodel->get_datatables($tglawal, $tglakhir);

            
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                //print_r($list);
                $no++;
                $row = [];

                

                $row[] = $no;
                $row[] = $list->brgnama;
                $row[] = $list->brgkode;
                $row[] = $list->tglfaktur;

                

                $row[] = number_format($list->detjml, 0, ",", ".");
                $row[] = $list->usernamalengkap;
           
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $datamodel->count_all($tglawal, $tglakhir),
                "recordsFiltered" => $datamodel->count_filtered($tglawal, $tglakhir),
                "data" => $data
            ];
            echo json_encode($output);
    }
    public function listDataDetailBarangKeluar()
    {
        $tglawal = $this->request->getPost('tglawal');
        $tglakhir = $this->request->getPost('tglakhir');
        $request = Services::request();
        $datamodel = new Modeldetailbarangkeluar();
        $lists = $datamodel->get_datatables($tglawal, $tglakhir);

            
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                //print_r($list);
                $no++;
                $row = [];

                

                $row[] = $no;
                $row[] = $list->brgnama;
                $row[] = $list->brgkode;
                $row[] = $list->tglfaktur;

                

                $row[] = number_format($list->detjml, 0, ",", ".");
                $row[] = $list->usernamalengkap;
           
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $datamodel->count_all($tglawal, $tglakhir),
                "recordsFiltered" => $datamodel->count_filtered($tglawal, $tglakhir),
                "data" => $data
            ];
            echo json_encode($output);
    }

    public function cetak_barang_masuk_periode()
    {
        $tombolCetak = $this->request->getPost('btnCetak');
        $tombolExport = $this->request->getPost('btnExport');
        $tglawal = $this->request->getPost('tglawal');
        $tglakhir = $this->request->getPost('tglakhir');

        $modelBarangMasuk = new Modelbarangmasuk();

        $dataLaporan = $modelBarangMasuk->laporanPerPeriode($tglawal, $tglakhir);
        $db = \Config\Database::connect();
        $datagroup = $db->query("SELECT barang.brgnama AS brgnama,barang.brgkode as brgkode,sum(detail_barangmasuk.detjml) as QTY FROM barangmasuk 
        LEFT JOIN detail_barangmasuk ON barangmasuk.faktur=detail_barangmasuk.detfaktur  LEFT JOIN barang on detail_barangmasuk.detbrgkode=barang.brgkode WHERE barangmasuk.tglfaktur >= '$tglawal' AND barangmasuk.tglfaktur <='$tglakhir' group by barang.brgnama,barang.brgkode ORDER BY barang.brgkode ASC");
        $datamodel = new Modeldetailbarangmasuk();
         $datadetail = $datamodel->get_datatables($tglawal, $tglakhir);

        if (isset($tombolCetak)) {
            $data = [
                'datalaporan' => $dataLaporan,
                'datadetail'=>$datadetail,
                'datagroup'=>$datagroup,
                'tglawal' => $tglawal,
                'tglakhir' => $tglakhir
            ];

            return view('laporan/cetaklaporanbarangmasuk', $data);
        }

        if (isset($tombolExport)) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $styleColumn = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ];

            $borderArray = [
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'left' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                    'right' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ]
            ];

            $sheet->setCellValue('A1', 'DATA BARANG MASUK');
            $sheet->mergeCells('A1:D1');
            $sheet->getStyle('A1')->getFont()->setBold(true);
            $sheet->getStyle('A1')->applyFromArray($styleColumn);

            $sheet->setCellValue('A3', 'No');
            $sheet->setCellValue('B3', 'Faktur');
            $sheet->setCellValue('C3', 'Tangal Faktur');
            $sheet->setCellValue('D3', 'Total Harga');

            $sheet->getStyle('A3')->applyFromArray($styleColumn);
            $sheet->getStyle('B3')->applyFromArray($styleColumn);
            $sheet->getStyle('C3')->applyFromArray($styleColumn);
            $sheet->getStyle('D3')->applyFromArray($styleColumn);

            $sheet->getStyle('A3')->applyFromArray($borderArray);
            $sheet->getStyle('B3')->applyFromArray($borderArray);
            $sheet->getStyle('C3')->applyFromArray($borderArray);
            $sheet->getStyle('D3')->applyFromArray($borderArray);

            $no = 1;
            $numRow = 4;

            foreach ($dataLaporan->getResultArray() as $row) :

                $sheet->setCellValue('A' . $numRow, $no);
                $sheet->setCellValue('B' . $numRow, $row['faktur']);
                $sheet->setCellValue('C' . $numRow, date('d-m-Y', strtotime($row['tglfaktur'])));
                $sheet->setCellValue('D' . $numRow, $row['totalharga']);

                $sheet->getStyle('A' . $numRow)->applyFromArray($styleColumn);

                $sheet->getStyle('A' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('B' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('C' . $numRow)->applyFromArray($borderArray);
                $sheet->getStyle('D' . $numRow)->applyFromArray($borderArray);

                $no++;
                $numRow++;
            endforeach;

            $sheet->getDefaultRowDimension()->setRowHeight(-1);
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->setTitle('Data Barang Masuk');

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Data Barang Masuk.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }
    }
    public function cetak_barang_keluar_periode()
    {
        $tombolCetak = $this->request->getPost('btnCetak');
        $tombolExport = $this->request->getPost('btnExport');
        $tglawal = $this->request->getPost('tglawal');
        $tglakhir = $this->request->getPost('tglakhir');

        $modelBarangMasuk = new Modelbarangkeluar();

        $dataLaporan = $modelBarangMasuk->laporanPerPeriode($tglawal, $tglakhir);
        $db = \Config\Database::connect();
        $datagroup =  $db->query("SELECT barang.brgnama AS brgnama, barang.brgkode as brgkode,sum(detail_barangkeluar.detjml) as QTY FROM barangkeluar 
        LEFT JOIN detail_barangkeluar ON barangkeluar.faktur=detail_barangkeluar.detfaktur  LEFT JOIN barang on detail_barangkeluar.detbrgkode=barang.brgkode WHERE barangkeluar.tglfaktur >= '$tglawal' AND barangkeluar.tglfaktur <='$tglakhir' group by barang.brgnama,barang.brgkode ORDER BY barang.brgkode ASC");
        $datamodel = new Modeldetailbarangkeluar();
         $datadetail = $datamodel->get_datatables($tglawal, $tglakhir);

        if (isset($tombolCetak)) {
            $data = [
                'datalaporan' => $dataLaporan,
                'datadetail'=>$datadetail,
                'datagroup'=>$datagroup,
                'tglawal' => $tglawal,
                'tglakhir' => $tglakhir
            ];

            return view('laporan/cetaklaporanbarangkeluar', $data);
        }
    }

    public function tampilGrafikBarangMasuk()
    {
        $bulan = $this->request->getPost('bulan');
        //$bulan = date('Y-m-d');
        $db = \Config\Database::connect();
        // $query = $db->query("SELECT tglfaktur AS tgl, totalharga FROM barangmasuk WHERE DATE_FORMAT(tglfaktur, '%Y-%m') = '$bulan' ORDER BY tglfaktur ASC")->getResult();
        $query = $db->query("SELECT barang.brgnama AS brgnama,sum(detail_barangmasuk.detjml) as QTY FROM barangmasuk 
        LEFT JOIN detail_barangmasuk ON barangmasuk.faktur=detail_barangmasuk.detfaktur  LEFT JOIN barang on detail_barangmasuk.detbrgkode=barang.brgkode WHERE DATE_FORMAT(tglfaktur, '%Y-%m') = '$bulan' group by barang.brgnama,barang.brgkode ORDER BY barang.brgkode ASC")->getResult();
        //print_r($query);
        $data = [
            'grafik' => $query
        ];

        $json = [
            'data' => view('laporan/grafikbarangmasuk', $data)
        ];

        echo json_encode($json);
    }

    public function tampilGrafikBarangKeluar()
    {
        $bulan = $this->request->getPost('bulan');
        //$bulan = date('Y-m-d');
        $db = \Config\Database::connect();
        // $query = $db->query("SELECT tglfaktur AS tgl, totalharga FROM barangmasuk WHERE DATE_FORMAT(tglfaktur, '%Y-%m') = '$bulan' ORDER BY tglfaktur ASC")->getResult();
        $query = $db->query("SELECT barang.brgnama AS brgnama,sum(detail_barangkeluar.detjml) as QTY FROM barangkeluar 
        LEFT JOIN detail_barangkeluar ON barangkeluar.faktur=detail_barangkeluar.detfaktur  LEFT JOIN barang on detail_barangkeluar.detbrgkode=barang.brgkode WHERE DATE_FORMAT(tglfaktur, '%Y-%m') = '$bulan' group by barang.brgnama,barang.brgkode ORDER BY barang.brgkode ASC")->getResult();
        //print_r($query);
        $data = [
            'grafik' => $query
        ];

        $json = [
            'data' => view('laporan/grafikbarangmasuk', $data)
        ];

        echo json_encode($json);
    }
}
