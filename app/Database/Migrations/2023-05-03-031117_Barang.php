<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Barang extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'brgid' => [
                'type' => 'int',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'brgkode' => [
                'type' => 'char',
                'constraint' => '10',
                'null' => TRUE,
            ],
            'brgnama' => [
                'type' => 'varchar',
                'constraint' => '100'
            ],
            'brgkatid' => [
                'type' => 'int',
                'unsigned' => true
            ],
            'brgsatid' => [
                'type' => 'int',
                'unsigned' => true
            ],
            'brgharga' => [
                'type' => 'double',
            ],
            'brgstok' => [
                'type' => 'double',
            ],
            'brggambar' => [
                'type' => 'varchar',
                'constraint' => 200,
                'null' => TRUE,
            ],
        ]);
 
        $this->forge->addPrimaryKey('brgid');
        // $this->forge->addForeignKey('brgkatid', 'kategori', 'katid');
        // $this->forge->addForeignKey('brgsatid', 'satuan', 'satid');
 
        $this->forge->createTable('barang');
    }
 
    public function down()
    {
        $this->forge->dropTable('barang');
    }
}
