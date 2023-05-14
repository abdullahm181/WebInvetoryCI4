<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelbarangmasuk extends Model
{
    protected $table            = 'barangmasuk';
    protected $primaryKey       = 'faktur';
    protected $allowedFields    = [
        'faktur', 'tglfaktur', 'nosuratjalan','inputby'
    ];

    public function __construct() {
        parent::__construct();
        //$this->load->database();
        $db = \Config\Database::connect();
        $builder = $db->table('barangmasuk');
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
        return $this->table('barangmasuk')->Where('sha1(faktur)', $faktur)->get()->getResult();
    }

    public function laporanPerPeriode($tglawal, $tglakhir){
        return $this->table('barangmasuk')->where('tglfaktur >=', $tglawal)->where('tglfaktur <=', $tglakhir)->get();
    }

    public function noFakturOtomatis($tanggalSekarang)
    {
       $query = $this->table('barangmasuk')
       ->where('tglfaktur', $tanggalSekarang)
       ->get();
  
          $max = 0;
          foreach ($query->getResultArray() as $r){
              if((int)substr($r['faktur'], -4)>$max) $max=(int)substr($r['faktur'], -4);
          }
  
          return $max;
    }
}

