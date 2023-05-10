<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Services;

class Main extends BaseController
{
    public function index()
    {
        return view('main/home');
    }
    public function RequestBarang()
    {
        return view('main/forminput');
    }
    public function tampilGrafikMasuk()
    {
        $bulan = $this->request->getPost('bulan');
        //$bulan = date('Y-m-d');
        $db = \Config\Database::connect();
        // $query = $db->query("SELECT tglfaktur AS tgl, totalharga FROM barangmasuk WHERE DATE_FORMAT(tglfaktur, '%Y-%m') = '$bulan' ORDER BY tglfaktur ASC")->getResult();
        $query = $db->query("SELECT barang.brgnama AS brgnama,sum(detail_barangmasuk.detjml) as QTY FROM barangmasuk 
        LEFT JOIN detail_barangmasuk ON barangmasuk.faktur=detail_barangmasuk.detfaktur  LEFT JOIN barang on detail_barangmasuk.detbrgkode=barang.brgkode WHERE DATE_FORMAT(tglfaktur, '%Y-%m') = '$bulan' group by barang.brgnama,barang.brgkode ORDER BY barang.brgkode ASC")->getResult();
        $label=[];
        $dataset=[];
        foreach($query as $dt){
            $label[]=$dt->brgnama;
            $dataset[]=$dt->QTY;
        }
        //print_r($query);
        $data = [
            'grafiklabel' => $label,
            'grafikdata'=>$dataset
        ];


        echo json_encode($data);
    }

    public function tampilGrafikKeluar()
    {
        $bulan = $this->request->getPost('bulan');
        //$bulan = date('Y-m-d');
        $db = \Config\Database::connect();
        // $query = $db->query("SELECT tglfaktur AS tgl, totalharga FROM barangmasuk WHERE DATE_FORMAT(tglfaktur, '%Y-%m') = '$bulan' ORDER BY tglfaktur ASC")->getResult();
        $query = $db->query("SELECT barang.brgnama AS brgnama,sum(detail_barangkeluar.detjml) as QTY FROM barangkeluar 
        LEFT JOIN detail_barangkeluar ON barangkeluar.faktur=detail_barangkeluar.detfaktur  LEFT JOIN barang on detail_barangkeluar.detbrgkode=barang.brgkode WHERE DATE_FORMAT(tglfaktur, '%Y-%m') = '$bulan' group by barang.brgnama,barang.brgkode ORDER BY barang.brgkode ASC")->getResult();
        $label=[];
        $dataset=[];
        foreach($query as $dt){
            $label[]=$dt->brgnama;
            $dataset[]=$dt->QTY;
        }
        //print_r($query);
        $data = [
            'grafiklabel' => $label,
            'grafikdata'=>$dataset
        ];


        echo json_encode($data);
    }
    public function listdatabarangrestock()
    {
        
        $request = Services::request();
        
        $db = \Config\Database::connect();
        $query = $db->query("SELECT brgnama,brgkode ,brgstok,(CASE
        WHEN biayapenyimpanan = 0 THEN 0
        ELSE SQRT((2*biayapesan*jumlahkebutuhantahun)/(brgharga*biayapenyimpanan))
    END) AS EOQ,((penjualantertinggiharian*leadtimeterlama)-(ratapenjualanharian*rataleadtime)) AS SafetyStock,((ratapenjualanharian*rataleadtime)+((penjualantertinggiharian*leadtimeterlama)-(ratapenjualanharian*rataleadtime))) AS ReorderPoint FROM barang WHERE (brgstok)<((ratapenjualanharian*rataleadtime)+((penjualantertinggiharian*leadtimeterlama)-(ratapenjualanharian*rataleadtime))) ORDER BY brgkode ASC")->getResult();
            
            $data = [];
            $no = $request->getPost("start");
            foreach ($query as $list) {
                //print_r($list);
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->brgnama;
                $row[] = $list->brgkode;
                $row[] = number_format($list->brgstok, 0, ",", ".");
                $row[] = number_format($list->EOQ, 0, ",", ".");
                $row[] = number_format($list->SafetyStock, 0, ",", ".");
                $row[] = number_format($list->ReorderPoint, 0, ",", ".");
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => count($query),
                "recordsFiltered" => count($query),
                "data" => $data
            ];
            echo json_encode($output);
    }
    public function listdatabarangrestockFormInput()
    {
        
        $request = Services::request();
        
        $db = \Config\Database::connect();
        $query = $db->query("SELECT brgnama,brgkode ,brgstok,(CASE
        WHEN biayapenyimpanan = 0 THEN 0
        ELSE SQRT((2*biayapesan*jumlahkebutuhantahun)/(brgharga*biayapenyimpanan))
    END) AS EOQ,((penjualantertinggiharian*leadtimeterlama)-(ratapenjualanharian*rataleadtime)) AS SafetyStock,((ratapenjualanharian*rataleadtime)+((penjualantertinggiharian*leadtimeterlama)-(ratapenjualanharian*rataleadtime))) AS ReorderPoint FROM barang WHERE (brgstok)<((ratapenjualanharian*rataleadtime)+((penjualantertinggiharian*leadtimeterlama)-(ratapenjualanharian*rataleadtime))) ORDER BY brgkode ASC")->getResult();
            
            $data = [];
            $no = $request->getPost("start");
            foreach ($query as $list) {
                //print_r($list);
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->brgnama;
                $row[] = $list->brgkode;
                $row[] = number_format($list->brgstok, 0, ",", ".");
                $row[] = number_format($list->EOQ, 0, ",", ".");
                $row[] = number_format($list->SafetyStock, 0, ",", ".");
                $row[] = number_format($list->ReorderPoint, 0, ",", ".");
                $row[]="<button type=\"button\" class=\"btn btn-sm btn-primary\" onclick=\"pilih('" . $list->brgkode . "')\">Pilih</button>";
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => count($query),
                "recordsFiltered" => count($query),
                "data" => $data
            ];
            echo json_encode($output);
    }
}
