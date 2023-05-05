<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modellokasi;

class Lokasi extends BaseController
{
    public function index()
    {
        $model = new Modellokasi();
        $data = [
            'tampildata' => $model->orderBy('lokid', 'DESC')->findAll()
        ];
        return view('lokasi/viewlokasi', $data);
    }
    public function store(){
        helper(['form', 'url']);
           
        $model = new Modellokasi();

        $storeTyp='Add';
        $result=false;
          
        $data = [
            'lokid' => $this->request->getVar('lokid'),
            'loklorong'  => $this->request->getVar('loklorong'),
            'lokrak'  => $this->request->getVar('lokrak'),
            'lokkode'  => $this->request->getVar('lokkode')
            ];
        if($data['lokid']==0 || $data['lokid']==null ){
            //add
            $data=$this->data_add($data);
            if($data!=null) $result=true;

        }else{
            //update
            $storeTyp='Update';
            $data=$this->data_update($data);
            if($data!=null) $result=true;
        }
        echo json_encode(array("status" => $result, 'data' => $data,'pesan'=>$storeTyp));
    }
    public function data_add($data)
    {

        $model = new Modellokasi();
        $save = $model->insert_data($data);
        if ($save != false) {
            $data = $model->where('lokid', $save)->first();
            return $data;
        } else {
            return null;
        }
    }

    public function get_data($id)
    {

        $model = new Modellokasi();

        $data = $model->where('lokid', $id)->first();

        if ($data) {
            echo json_encode(array("status" => true, 'data' => $data));
        } else {
            echo json_encode(array("status" => false));
        }
    }
    public function get_all()
    {

        $model = new Modellokasi();

        $data = $model->orderBy('lokid', 'DESC')->findAll();

        echo json_encode($data);
    }

    public function data_update($data)
    {

        $model = new Modellokasi();

        $id = $data['lokid'];

        $update = $model->update($id, $data);
        if ($update != false) {
            $data = $model->where('lokid', $id)->first();
            return $data;
        } else {
            return null;
        }
    }

    public function delete($id = null)
    {
        $model = new Modellokasi();
        $delete = $model->where('lokid', $id)->delete();
        if ($delete) {
            echo json_encode(array("status" => true));
        } else {
            echo json_encode(array("status" => false));
        }
    }
}
