<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Transactions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'transaction_id' => ['type' => 'VARCHAR', 'constraint' => '50', 'unique' => true],
            'cashier_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'total'          => ['type' => 'INT', 'constraint' => 11],
            'payment_method' => ['type' => 'ENUM', 'constraint' => ['cash', 'qris', 'card']],
            'items'          => ['type' => 'TEXT'], // Menyimpan JSON string dari detail item
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('cashier_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}