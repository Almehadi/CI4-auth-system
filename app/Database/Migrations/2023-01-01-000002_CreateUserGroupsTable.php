<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserGroupsTable extends Migration
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
            'group_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                
            ],
            'descriptions' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'permissions' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('group_name');
        $this->forge->createTable('user_groups');

        // Insert default groups
        $groups = [
            [
                'group_name' => 'Administrator', 
                'descriptions' => 'Full system access with all permissions', 
                'permissions' => '{"users":["create","read","update","delete"],"employees":["create","read","update","delete"],"reports":["read","export"]}',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'group_name' => 'Manager', 
                'descriptions' => 'Department manager access', 
                'permissions' => '{"users":["read"],"employees":["create","read","update"],"reports":["read"]}',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'group_name' => 'Employee', 
                'descriptions' => 'Regular employee access', 
                'permissions' => '{"employees":["read"],"reports":["read"]}',
                'created_at' => date('Y-m-d H:i:s')
            ],
        ];

        $this->db->table('user_groups')->insertBatch($groups);
    }

    public function down()
    {
        $this->forge->dropTable('user_groups');
    }
}