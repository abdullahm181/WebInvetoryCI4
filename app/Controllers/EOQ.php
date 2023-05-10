<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelbarang;
use App\Models\Modellokasi;

class EOQ extends BaseController
{
    public function index()
    {
        $model = new Modelbarang();
        $data = [
            'tampildata' => $model->orderBy('brgkode', 'DESC')->findAll()
        ];
        return view('EOQ/index', $data);
    }
    

    public function get_data($id)
    {

        $model = new Modelbarang();

        $data = $model->where('brgid', $id)->first();

        if ($data) {
            echo json_encode(array("status" => true, 'data' => $data));
        } else {
            echo json_encode(array("status" => false));
        }
    }
    public function get_by_kode($kode)
    {

        $model = new Modelbarang();

        $data = $model->where('brgkode', $kode)->first();

        if ($data) {
            echo json_encode(array("status" => true, 'data' => $data));
        } else {
            echo json_encode(array("status" => false));
        }
    }
    public function get_all()
    {

        $model = new Modelbarang();

        $data = $model->orderBy('brgid', 'DESC')->findAll();

        echo json_encode($data);
    }

    public function data_update()
    {
      
      $data = [
        'brgid' => $this->request->getVar('brgid'),
        'brgnama'  => $this->request->getVar('brgnama'),
        
        'brgharga' => $this->request->getVar('brgharga'),
        
        'jumlahkebutuhantahun' => $this->request->getVar('jumlahkebutuhantahun'),
        'biayapesan' => $this->request->getVar('biayapesan'),
        'biayapenyimpanan'=> $this->request->getVar('biayapenyimpanan'),
        'penjualantertinggiharian'=> $this->request->getVar('penjualantertinggiharian'),
        'leadtimeterlama'=> $this->request->getVar('leadtimeterlama'),
        'ratapenjualanharian'=> $this->request->getVar('ratapenjualanharian'),
        'rataleadtime'=> $this->request->getVar('rataleadtime')
    ];

        $model = new Modelbarang();
        $dataOld = $model->where('brgid', $data['brgid'])->first();

        $id = $data['brgid'];
        $dataOld['brgharga']=$data['brgharga'];
        $dataOld['jumlahkebutuhantahun']=$data['jumlahkebutuhantahun'];
        $dataOld['biayapesan']=$data['biayapesan'];
        $dataOld['biayapenyimpanan']=$data['biayapenyimpanan'];
        $dataOld['penjualantertinggiharian']=$data['penjualantertinggiharian'];
        $dataOld['leadtimeterlama']=$data['leadtimeterlama'];
        $dataOld['ratapenjualanharian']=$data['ratapenjualanharian'];
        $dataOld['rataleadtime']=$data['rataleadtime'];

        $update = $model->update($id, $dataOld);
        if ($update != false) {
            echo json_encode(array("status" => TRUE, 'data' => $dataOld));
        } else {
            echo json_encode(array("status" => FALSE));
        }
    }

}
