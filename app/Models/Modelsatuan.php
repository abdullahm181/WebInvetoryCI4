<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelsatuan extends Model
{
    
    protected $table            = 'satuan';
    protected $primaryKey       = 'satid';
    
    protected $allowedFields    = ['satid','satnama','isdeleted'];

    public function __construct() {
        parent::__construct();
        //$this->load->database();
        $db = \Config\Database::connect();
        $builder = $db->table('satuan');
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
