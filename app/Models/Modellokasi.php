<?php

namespace App\Models;

use CodeIgniter\Model;

class Modellokasi extends Model
{
    protected $table            = 'lokasi';
    protected $primaryKey       = 'lokid';
    
    protected $allowedFields    = ['lokid','loklorong','lokrak','lokkode','isdeleted'];

    public function __construct() {
        parent::__construct();
        //$this->load->database();
        $db = \Config\Database::connect();
        $builder = $db->table('lokasi');
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
}
