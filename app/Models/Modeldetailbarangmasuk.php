<?php

namespace App\Models;

use CodeIgniter\Model;

class Modeldetailbarangmasuk extends Model
{
    protected $table            = 'detail_barangmasuk';
    protected $primaryKey       = 'iddetail';
    protected $allowedFields    = [
        'detfaktur', 'detbrgkode', 'dethargamasuk', 'dethargajual', 'detjml', 'detsubtotal'
    ];

    public function dataDetail($faktur){
        $data=$this->table('detail_barangmasuk')->where('detfaktur', $faktur)->get();
        foreach($data->getResult() as $dt){
            $modelBrg= new Modelbarang();
            $dataBarang=$modelBrg->get_by_kode($dt->detbrgkode);
            $dt->brgkode=$dataBarang['brgkode'];
            $dt->brgnama=$dataBarang['brgnama'];
        }
        
        return $data;
    }

    public function ambilTotalHarga($faktur){
        $query = $this->table('detail_barangmasuk')->getWhere([
            'detfaktur' => $faktur
        ]);

        $totalharga = 0;
        foreach ($query->getResultArray() as $r){
            $totalharga += $r['detsubtotal'];
        }

        return $totalharga;
    }

    public function ambilDetailBerdasarkanID($iddetail){
        return $this->table('detail_barangmasuk')
        ->join('barang', 'brgkode=detbrgkode')
        ->where('iddetail', $iddetail)
        ->get();
    }

}