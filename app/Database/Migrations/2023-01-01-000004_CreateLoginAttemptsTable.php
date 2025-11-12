<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLoginAttemptsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => '45',
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'attempt_time' => [
                'type' => 'DATETIME',
            ],
            'success' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        //$this->forge->addKey(['username', 'attempt_time']);
        $this->forge->createTable('login_attempts');
    }

    public function down()
    {
        $this->forge->dropTable('login_attempts');
    }
}