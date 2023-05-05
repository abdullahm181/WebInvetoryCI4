<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelbarang;

class Barang extends BaseController
{
    public function index()
    {
        $model = new Modelbarang();
        $data = [
            'tampildata' => $model->orderBy('brgid', 'DESC')->findAll()
        ];
        return view('barang/viewbarang', $data);
    }
    
    public function store()
    {
        helper(['form', 'url']);

        $model = new Modelbarang();

        $storeTyp = 'Add';
        $result = false;

        $data = [
            'brgid' => $this->request->getVar('brgid'),
            'brgnama'  => $this->request->getVar('brgnama'),
            'brgkatid' => $this->request->getVar('brgkatid'),
            'brgsatid' => $this->request->getVar('brgsatid'),
            'brgharga' => $this->request->getVar('brgharga'),
            'brggambar' => null,
            'brgkode' => $this->request->getVar('brgkode'),
            'brgstok' => $this->request->getVar('brgstok')
        ];
        if ($data['brgid'] == 0 || $data['brgid'] == null) {
            if ($file = $this->request->getFile('brggambar')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    // Get file name and extension
                    $name = $file->getName();
                    $ext = $file->getClientExtension();

                    // Get random file name
                    $newName = $file->getRandomName();

                    // Store file in public/uploads/ folder
                    $file->move('../public/uploads', $newName);
                    $data['brggambar']=$newName;
                }
            }

            $data = $this->data_add($data);
            if ($data != null) $result = true;
        } else {
            //update
            $storeTyp = 'Update';
            $data = $this->data_update($data);
            if ($data != null) $result = true;
        }
        echo json_encode(array("status" => $result, 'data' => $data, 'pesan' => $storeTyp));
    }
    public function data_add($data)
    {

        $model = new Modelbarang();
        $save = $model->insert_data($data);
        if ($save != false) {
            $data = $model->where('brgid', $save)->first();
            return $data;
        } else {
            return null;
        }
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
    public function get_all()
    {

        $model = new Modelbarang();

        $data = $model->orderBy('brgid', 'DESC')->findAll();

        echo json_encode($data);
    }

    public function data_update($data)
    {

        $model = new Modelbarang();

        $id = $data['brgid'];

        $update = $model->update($id, $data);
        if ($update != false) {
            $data = $model->where('brgid', $id)->first();
            return $data;
        } else {
            return null;
        }
    }

    public function delete($id = null)
    {
        $model = new Modelbarang();
        $delete = $model->where('brgid', $id)->delete();
        if ($delete) {
            echo json_encode(array("status" => true));
        } else {
            echo json_encode(array("status" => false));
        }
    }
}
