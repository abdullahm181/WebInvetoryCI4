<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelkategori;

class Kategori extends BaseController
{
    
    public function index()
    {
        $model = new Modelkategori();
        $data = [
            'tampildata' => $model->where('isdeleted !=','1')->orderBy('katid', 'DESC')->findAll()
        ];
        return view('kategori/viewkategori', $data);
    }
    public function store(){
        helper(['form', 'url']);
           
        $model = new Modelkategori();

        $storeTyp='Add';
        $result=false;
          
        $data = [
            'katid' => $this->request->getVar('katid'),
            'katnama'  => $this->request->getVar('katnama'),
            'isdeleted'=>0
            ];
        if($data['katid']==0 || $data['katid']==null ){
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

        $model = new Modelkategori();
        $save = $model->insert_data($data);
        if ($save != false) {
            $data = $model->where('katid', $save)->first();
            return $data;
        } else {
            return null;
        }
    }

    public function get_data($id)
    {

        $model = new Modelkategori();

        $data = $model->where('katid', $id)->first();

        if ($data) {
            echo json_encode(array("status" => true, 'data' => $data));
        } else {
            echo json_encode(array("status" => false));
        }
    }
    public function get_all()
    {

        $model = new Modelkategori();

        $data = $model->where('isdeleted !=','1')->orderBy('katid', 'DESC')->findAll();

        echo json_encode($data);
    }

    public function data_update($data)
    {

        $model = new Modelkategori();

        $id = $data['katid'];

        $update = $model->update($id, $data);
        if ($update != false) {
            $data = $model->where('katid', $id)->first();
            return $data;
        } else {
            return null;
        }
    }

    public function delete($id = null)
    {
        $model = new Modelkategori();
        //$delete = $model->where('katid', $id)->delete();
        $data = $model->where('katid', $id)->first();
        $data['isdeleted']=1;

        $update = $model->update($id, $data);
        if ($update) {
            echo json_encode(array("status" => true));
        } else {
            echo json_encode(array("status" => false));
        }
    }
}
