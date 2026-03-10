<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMultiSessionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => [
                'type'           => 'BIGINT',
                'auto_increment'  => true,
            ],
            'session_name'      => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'comment'    => 'Friendly name for the session (e.g., "Admin Session", "Employee Session")',
            ],
            'user_id'           => [
                'type'   => 'BIGINT',
                'null'   => false,
                'comment' => 'User ID for this session',
            ],
            'session_token'     => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'unique'     => true,
                'comment'    => 'Unique token to identify this session',
            ],
            'ip_address'        => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'user_agent'        => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'is_active'         => [
                'type'       => 'BOOLEAN',
                'default'    => true,
                'comment'    => 'Whether this session is currently active',
            ],
            'last_activity'     => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'created_at'        => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at'        => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('is_active');
        $this->forge->addKey(['user_id', 'is_active']);
        
        try {
            $this->forge->createTable('multi_sessions');
        } catch (\Exception $e) {
            // Table might already exist
            if (strpos($e->getMessage(), 'already exists') === false) {
                throw $e;
            }
        }

        // Add foreign key for MySQL only
        if ($this->db->DBDriver === 'MySQLi') {
            try {
                $this->db->query('
                    ALTER TABLE `' . $this->db->getPrefix() . 'multi_sessions`
                    ADD CONSTRAINT `fk_multi_sessions_user_id`
                    FOREIGN KEY (`user_id`)
                    REFERENCES `users`(`id`)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
                ');
            } catch (\Exception $e) {
                // Foreign key might already exist, continue
            }
        }
    }

    public function down()
    {
        $this->forge->dropTable('multi_sessions');
    }
}
