<?php

namespace App\Models;

use CodeIgniter\Model;

class Modeltempbarangmasuk extends Model
{
    protected $table            = 'temp_barangmasuk';
    protected $primaryKey       = 'iddetail';
    protected $allowedFields    = [
        'iddetail', 'detfaktur', 'detbrgkode', 'dethargamasuk', 'dethargajual', 'detjml', 'detsubtotal'
    ];
    public function __construct() {
        parent::__construct();
        //$this->load->database();
        $db = \Config\Database::connect();
        $builder = $db->table('temp_barangmasuk');
    }

    public function tampilDataTemp($faktur)
    {
        //$data=$this->table('temp_barangmasuk')->where('detfaktur',$faktur)->get()->getResult();
        $data=$this->table('temp_barangmasuk')->like('detfaktur', $faktur, 'both')->get()->getResult();
        foreach($data as $dt){
            $modelBrg= new Modelbarang();
            $dataBarang=$modelBrg->get_by_kode($dt->detbrgkode);
            $dt->brgkode=$dataBarang['brgkode'];
            $dt->brgnama=$dataBarang['brgnama'];
        }
        
        //print_r($data);
        return $data;
    }
}
