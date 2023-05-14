<?php

namespace App\Models;

use CodeIgniter\Model;

class Modeltempbarangkeluar extends Model
{
    protected $table            = 'temp_barangkeluar';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['detfaktur', 'detbrgkode',  'detjml'];
    public function __construct() {
      parent::__construct();
      //$this->load->database();
      $db = \Config\Database::connect();
      $builder = $db->table('temp_barangkeluar');
  }
    public function tampilDataTemp($nofaktur){
        return $this->table('temp_barangkeluar')->join('barang', 'detbrgkode=brgkode')->where('detfaktur', $nofaktur)->get();
    }

    public function hapusData($nofaktur){
        $this->table('temp_barangkeluar')->where('detfaktur', $nofaktur);
        return $this->table('temp_barangkeluar')->delete();
    }
    public function cekJumlahByFakturDanKode($faktur,$brgkode)
  {
     $query = $this->table('temp_barangkeluar')
     ->where('detfaktur', $faktur)
     ->where('detbrgkode',$brgkode)
     ->get();

        $total = 0;
        foreach ($query->getResultArray() as $r){
            $total+=$r['detjml'];
        }

        return $total;
  }
}
