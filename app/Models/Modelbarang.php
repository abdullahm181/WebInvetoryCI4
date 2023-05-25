<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelbarang extends Model
{
    protected $table            = 'barang';
    protected $primaryKey       = 'brgid';

    protected $allowedFields    = ['brgid','brgkatid', 'brgsatid', 'brgnama', 'brgkode', 'brgharga', 'brggambar','brgstok','brglokid','jumlahkebutuhantahun','biayapesan','biayapenyimpanan','penjualantertinggiharian','leadtimeterlama','ratapenjualanharian','rataleadtime','isdeleted'];

    protected $column_order = array(null, 'brgkode', 'brgnama', null,null,null);
    protected $column_search = array('brgkode', 'brgnama');
    protected $order = array('brgnama' => 'ASC');
    protected $request;
    protected $db;
    protected $dt;

    public function __construct() {
        parent::__construct();
        //$this->load->database();
        $db = \Config\Database::connect();
        $builder = $db->table('barang');
        $this->dt = $this->db->table($this->table);
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
    public function getAllKode($tempKode){
        return $this->table('barang')->like('brgkode', $tempKode, 'both')->get()->getResult();
    }

    public function tampil_data(){
        return $this->table('barang')->join('kategori','brgkatid=katid')->join('satuan','brgsatid=satid')->join('lokasi','brglokid=lokid')->where('barang.isdeleted !=','1')->get();
    }
    public function tampildata_cari($cari)
    {
        $db = \Config\Database::connect();
        // $query = $db->query("SELECT tglfaktur AS tgl, totalharga FROM barangmasuk WHERE DATE_FORMAT(tglfaktur, '%Y-%m') = '$bulan' ORDER BY tglfaktur ASC")->getResult();
        $query = $db->query("SELECT * FROM barang 
        JOIN kategori ON brgkatid=katid
        JOIN satuan ON brgsatid=satid
        JOIN lokasi ON brglokid=lokid
        WHERE (brgkode like '%$cari%' OR brgnama like '%$cari%' OR katnama like '%$cari%') AND barang.isdeleted != 1")->getResult();
        return $query ;
    }

    public function get_by_kode($kode){
        return $this->table('barang')->join('lokasi','brglokid=lokid')->Where('brgkode', $kode)->first();

    }
    public function get_by_id($id){
        return $this->table('barang')->join('lokasi','brglokid=lokid')->Where('brgid', $id)->first();

    }

    private function _get_datatables_query()
    {
        $i = 0;
        foreach ($this->column_search as $item) {
            
            $i++;
        }
 
        
    }
    function get_datatables()
    {
        $this->_get_datatables_query();
        
        $query = $this->dt->where('isdeleted !=','1')->get();
        return $query->getResult();
    }
    function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->dt->where('isdeleted !=','1')->countAllResults();
    }
    public function count_all()
    {
        $tbl_storage = $this->db->table($this->table)->where('isdeleted !=','1');
        return $tbl_storage->countAllResults();
    }
}
