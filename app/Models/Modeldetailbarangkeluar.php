<?php

namespace App\Models;

use CodeIgniter\Model;

class Modeldetailbarangkeluar extends Model
{
    protected $table            = 'detail_barangkeluar';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['detfaktur', 'detbrgkode', 'dethargajual', 'detjml', 'detsubtotal'];
    public function __construct() {
      parent::__construct();
      //$this->load->database();
      $db = \Config\Database::connect();
      $builder = $db->table('detail_barangkeluar');
  }

    public function tampilDataTemp($nofaktur){
        return $this->table('detail_barangkeluar')
                    ->join('barang', 'detbrgkode=brgkode')
                    ->join('satuan', 'brgsatid = satid')
                    ->where('detfaktur', $nofaktur)
                    ->get();
    }

    public function ambilTotalHarga($nofaktur){
        $query = $this->table('detail_barangkeluar')->getWhere([
            'detfaktur' => $nofaktur
        ]);

        $totalHarga =  0;
        foreach ($query->getResultArray() as $r) :
            $totalHarga += $r['detsubtotal'];
        endforeach;

        return $totalHarga;
    }
}
