<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelsatuan;

class Satuan extends BaseController
{
    public function index()
    {
        $model = new Modelsatuan();
        $data = [
            'tampildata' => $model->where('isdeleted !=','1')->orderBy('satid', 'DESC')->findAll()
        ];
        return view('satuan/viewsatuan', $data);
    }
    public function store(){
        helper(['form', 'url']);
           
        $model = new Modelsatuan();

        $storeTyp='Add';
        $result=false;
          
        $data = [
            'satid' => $this->request->getVar('satid'),
            'satnama'  => $this->request->getVar('satnama'),
            'isdeleted'=>0
            ];
        if($data['satid']==0 || $data['satid']==null ){
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

        $model = new Modelsatuan();
        $save = $model->insert_data($data);
        if ($save != false) {
            $data = $model->where('satid', $save)->first();
            return $data;
        } else {
            return null;
        }
    }

    public function get_data($id)
    {

        $model = new Modelsatuan();

        $data = $model->where('satid', $id)->first();

        if ($data) {
            echo json_encode(array("status" => true, 'data' => $data));
        } else {
            echo json_encode(array("status" => false));
        }
    }
    public function get_all()
    {

        $model = new Modelsatuan();

        $data = $model->where('isdeleted !=','1')->orderBy('satid', 'DESC')->findAll();

        echo json_encode($data);
    }

    public function data_update($data)
    {

        $model = new Modelsatuan();

        $id = $data['satid'];

        $update = $model->update($id, $data);
        if ($update != false) {
            $data = $model->where('satid', $id)->first();
            return $data;
        } else {
            return null;
        }
    }

    public function delete($id = null)
    {
        $model = new Modelsatuan();
        $data = $model->where('satid', $id)->first();
        $data['isdeleted']=1;
        //$delete = $model->where('satid', $id)->delete();
        $delete=$model->update($id, $data);
        if ($delete) {
            echo json_encode(array("status" => true));
        } else {
            echo json_encode(array("status" => false));
        }
    }
}
