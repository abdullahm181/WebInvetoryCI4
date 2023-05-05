<?php

namespace App\Models;

use CodeIgniter\Model;

class Modelauth extends Model
{
    
    protected $table            = 'users';
    protected $primaryKey       = 'userid';
   
    protected $allowedFields    = ['userid','usernama','userpassword','userlevelid'];
    public function __construct() {
        parent::__construct();
        //$this->load->database();
        $db = \Config\Database::connect();
        $builder = $db->table('users');
    }

    public function get_by_usernama ($usernama){
        return $this->table('users')->where('usernama', $usernama)->get()->getResult() ?: NULL;
    }
      
}
