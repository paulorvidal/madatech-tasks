<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTaskTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
                'unsigned' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'default' => 'pendente'
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],

        ]);
        $this->forge->addKey("id", true);
        $this->forge->createTable('tasks');

        $this->db->query("ALTER TABLE tasks ADD CONSTRAINT check_status CHECK (status IN ('pendente', 'em andamento', 'concluída'))");

    }

    public function down(): void
    {
        $this->forge->dropTable('tasks');
    }
}
