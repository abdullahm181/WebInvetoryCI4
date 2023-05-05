<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelbarang extends Model
{
    protected $table            = 'barang';
    protected $primaryKey       = 'brgid';

    protected $allowedFields    = ['brgid','brgkatid', 'brgsatid', 'brgnama', 'brgkode', 'brgharga', 'brggambar','brgstok'];

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

    public function tampil_data(){
        return $this->table('barang')->join('kategori','brgkatid=katid')->join('satuan','brgsatid=satid')->get();
    }
}
