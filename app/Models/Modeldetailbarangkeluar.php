<?php

namespace App\Models;

use CodeIgniter\Model;


class Modeldetailbarangkeluar extends Model
{
    protected $table            = 'detail_barangkeluar';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['detfaktur', 'detbrgkode', 'detjml'];

    protected $dt;
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

    private function _get_datatables_query($tglawal, $tglakhir)
  {
      if ($tglawal=='' && $tglakhir==''){
          $this->dt = $this->table('detail_barangkeluar')
          ->join('barang', 'brgkode=detbrgkode')->join('barangkeluar','faktur=detfaktur')->join('users','userid=inputby');
      }else{
          $this->dt = $this->table('detail_barangkeluar')
          ->join('barang', 'brgkode=detbrgkode')->join('barangkeluar','faktur=detfaktur')->join('users','userid=inputby')
              ->where('barangkeluar.tglfaktur >=', $tglawal)
              ->where('barangkeluar.tglfaktur <=', $tglakhir);
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
            $tbl_storage = $this->table('detail_barangkeluar')
            ->join('barang', 'brgkode=detbrgkode')->join('barangkeluar','faktur=detfaktur')->join('users','userid=inputby')
                ->where('barangkeluar.tglfaktur >=', $tglawal)
                ->where('barangkeluar.tglfaktur <=', $tglakhir);
        }

        return $tbl_storage->countAllResults();
    }
    public function dataDetail($faktur){
        $data=$this->table('detail_barangkeluar')->join('barang','brgkode=detbrgkode')->where('detfaktur', $faktur)->get();
        
        return $data;
    }
}
