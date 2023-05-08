<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelbarangpermintaan extends Model
{
    protected $table            = 'barangpermintaan';
    protected $primaryKey       = 'nosuratjalan';
    protected $allowedFields    = [
        'nosuratjalan', 'tglpermintaan', 'status','inputby'
    ];

    public function __construct() {
        parent::__construct();
        //$this->load->database();
        $db = \Config\Database::connect();
        $builder = $db->table('barangpermintaan');
    }
    public function noFakturOtomatis($tanggalSekarang)
    {
       $query = $this->table('barangpermintaan')
       ->where('tglpermintaan', $tanggalSekarang)
       ->get();
  
          $max = 0;
          foreach ($query->getResultArray() as $r){
              if((int)substr($r['nosuratjalan'], -4)>$max) $max=(int)substr($r['nosuratjalan'], -4);
          }
  
          return $max;
    }
    public function insert_data($data) {
        if($this->db->table($this->table)->insert($data))
        {
            return $this->db->insertID();
        }
        else
        {
            return false;
        }
    }

    public function cekFaktur($faktur){
        return $this->table('barangmasuk')->Where('sha1(nosuratjalan)', $faktur)->get()->getResult();
    }

}

