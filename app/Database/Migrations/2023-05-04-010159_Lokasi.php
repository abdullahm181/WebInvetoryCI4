<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Lokasi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'lokid' => [
                'type' => 'int',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'loklorong' => [
                'type' => 'varchar',
                'constraint' => '255'
            ],
            'lokrak' => [
                'type' => 'varchar',
                'constraint' => '255'
            ],
            'lokkode' => [
                'type' => 'varchar',
                'constraint' => '255'
            ],
        ]);
        $this->forge->addKey('lokid');
        $this->forge->createTable('lokasi');
    }
 
    public function down()
    {
        $this->forge->dropTable('lokasi');
    }
}
