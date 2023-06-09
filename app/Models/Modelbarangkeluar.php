<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelbarangkeluar extends Model
{
  protected $table            = 'barangkeluar';
  protected $primaryKey       = 'faktur';
  protected $allowedFields    = [
    'faktur', 'tglfaktur', 'namapelanggan','inputby'
  ];

  
    protected $db;
    protected $dt;
  public function __construct()
  {
    parent::__construct();
    //$this->load->database();
    $db = \Config\Database::connect();
    $builder = $db->table('barangkeluar');
  }

  public function insert_data($data)
  {
    if ($this->db->table($this->table)->insert($data)) {
      return $this->db->insertID();
    } else {
      return false;
    }
  }

  public function noFakturOtomatis($tanggalSekarang)
  {
     $query = $this->table('barangkeluar')
     ->where('tglfaktur', $tanggalSekarang)
     ->get();

        $max = 0;
        foreach ($query->getResultArray() as $r){
            if((int)substr($r['faktur'], -4)>$max) $max=(int)substr($r['faktur'], -4);
        }

        return $max;
  }
  
  private function _get_datatables_query($tglawal, $tglakhir)
  {
      if ($tglawal=='' && $tglakhir==''){
          $this->dt = $this->db->table($this->table);
      }else{
          $this->dt = $this->db->table($this->table)
              ->where('tglfaktur >=', $tglawal)
              ->where('tglfaktur <=', $tglakhir);
      }

  }
  function get_datatables($tglawal, $tglakhir)
  {
      $this->_get_datatables_query($tglawal, $tglakhir);
      $query = $this->dt->get();
      return $query->getResult();
  }
  function count_filtered($tglawal, $tglakhir)
  {
      $this->_get_datatables_query($tglawal, $tglakhir);
      return $this->dt->countAllResults();
  }
  public function count_all($tglawal, $tglakhir)
    {
        if ($tglawal=='' && $tglakhir==''){
            $tbl_storage = $this->db->table($this->table);
        }else{
            $tbl_storage = $this->db->table($this->table)
                ->where('tglfaktur >=', $tglawal)
                ->where('tglfaktur <=', $tglakhir);
        }

        return $tbl_storage->countAllResults();
    }
    public function laporanPerPeriode($tglawal, $tglakhir){
      return $this->table('barangkeluar')->where('tglfaktur >=', $tglawal)->where('tglfaktur <=', $tglakhir)->get();
  }
}
