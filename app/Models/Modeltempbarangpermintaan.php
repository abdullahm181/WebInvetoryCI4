<?php

namespace App\Models;

use CodeIgniter\Model;

class Modeltempbarangpermintaan extends Model
{
    protected $table            = 'temp_barangpermintaan';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['detnosuratjalan', 'detbrgkode', 'detjml'];
    public function __construct() {
      parent::__construct();
      //$this->load->database();
      $db = \Config\Database::connect();
      $builder = $db->table('temp_barangpermintaan');
  }
    public function tampilDataTemp($nofaktur){
        return $this->table('temp_barangpermintaan')->join('barang', 'detbrgkode=brgkode')->where('detnosuratjalan', $nofaktur)->get()->getResult();
    }

    public function hapusData($nofaktur){
        $this->table('temp_barangpermintaan')->where('detnosuratjalan', $nofaktur);
        return $this->table('temp_barangpermintaan')->delete();
    }
    
}
