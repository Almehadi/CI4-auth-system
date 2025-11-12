<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserAccountsTable extends Migration
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
            'employee_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'user_group_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 3, // Default to Employee group
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'default' => 'active',
                'comment' => 'active, locked, blocked'
            ],
            'last_login' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('username');
        $this->forge->addForeignKey('employee_id', 'employees', 'oracleid', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_group_id', 'user_groups', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_accounts');
    }

    public function down()
    {
        $this->forge->dropTable('user_accounts');
    }
}