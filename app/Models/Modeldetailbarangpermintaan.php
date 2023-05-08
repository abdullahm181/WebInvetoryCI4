<?php

namespace App\Models;

use CodeIgniter\Model;

class Modeldetailbarangpermintaan extends Model
{
    protected $table            = 'detail_barangpermintaan';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'detnosuratjalan', 'detbrgkode', 'detjml'
    ];

    public function dataDetail($faktur){
        $data=$this->table('detail_barangpermintaan')->join('barang','detbrgkode=brgkode')->where('detnosuratjalan', $faktur)->get()->getResult();
        return $data;
    }

    public function ambilDetailBerdasarkanID($iddetail){
        return $this->table('detail_barangmasuk')
        ->join('barang', 'brgkode=detbrgkode')
        ->where('id', $iddetail)
        ->get();
    }

}