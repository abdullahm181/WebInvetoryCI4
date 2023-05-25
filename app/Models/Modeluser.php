<?php

namespace App\Models;

use CodeIgniter\Model;

class Modeluser extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'userid';
    protected $allowedFields    = ['userid', 'usernama', 'usernamalengkap','userpassword', 'userlevelid', 'useraktif','isdeleted'];
    protected $dt;

    private function _get_datatables_query()
    {
        $this->dt = $this->db->table($this->table);
  
    }
    function get_datatables()
    {
        $this->_get_datatables_query();
        $query = $this->dt->get();
        return $query->getResult();
    }
    function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->dt->countAllResults();
    }
    public function count_all()
      {
            $tbl_storage = $this->db->table($this->table);
  
          return $tbl_storage->countAllResults();
      }
}
