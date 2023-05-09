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
    protected $dt;

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

    private function _get_datatables_query($tglawal, $tglakhir)
  {
      if ($tglawal=='' && $tglakhir==''){
          $this->dt = $this->table('detail_barangmasuk')
          ->join('barang', 'brgkode=detbrgkode')->join('barangmasuk','faktur=detfaktur')->join('users','userid=inputby');
      }else{
          $this->dt = $this->table('detail_barangmasuk')
          ->join('barang', 'brgkode=detbrgkode')->join('barangmasuk','faktur=detfaktur')->join('users','userid=inputby')
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
            $tbl_storage = $this->table('detail_barangmasuk')
            ->join('barang', 'brgkode=detbrgkode')->join('barangmasuk','faktur=detfaktur')->join('users','userid=inputby')
                ->where('tglfaktur >=', $tglawal)
                ->where('tglfaktur <=', $tglakhir);
        }

        return $tbl_storage->countAllResults();
    }

}