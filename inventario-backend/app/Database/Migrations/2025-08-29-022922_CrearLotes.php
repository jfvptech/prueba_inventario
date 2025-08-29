<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearLotes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT','unsigned' => true,'auto_increment' => true],
            'producto_id' => ['type' => 'INT','unsigned' => true],
            'vence_en'    => ['type' => 'DATE'],
            'cantidad'    => ['type' => 'INT','unsigned' => true],
            'disponible'  => ['type' => 'INT','unsigned' => true],
            'creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('producto_id','productos','id','CASCADE','CASCADE');
        $this->forge->createTable('lotes');
    }

    public function down()
    {
        $this->forge->dropTable('lotes');
    }
}
