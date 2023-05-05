<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelbarang extends Model
{
    protected $table            = 'barang';
    protected $primaryKey       = 'brgid';

    protected $allowedFields    = ['brgid','brgkatid', 'brgsatid', 'brgnama', 'brgkode', 'brgharga', 'brggambar','brgstok','brglokid'];

    public function __construct() {
        parent::__construct();
        //$this->load->database();
        $db = \Config\Database::connect();
        $builder = $db->table('barang');
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
        return $this->table('barang')->join('kategori','brgkatid=katid')->join('satuan','brgsatid=satid')->join('lokasi','brglokid=lokid')->get();
    }
    public function tampildata_cari($cari)
    {
        return $this->table('barang')->join('kategori','brgkatid=katid')->join('satuan','brgsatid=satid')->join('lokasi','brglokid=lokid')->orlike('brgkode', $cari)->orlike('brgnama', $cari)->orlike('katnama', $cari);
    }

    public function get_by_kode($kode){
        return $this->table('barang')->Where('brgkode', $kode)->first();

    }
}