<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearMovimientos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT','unsigned' => true,'auto_increment' => true],
            'producto_id' => ['type' => 'INT','unsigned' => true],
            'lote_id'     => ['type' => 'INT','unsigned' => true,'null' => true],
            'tipo'        => ['type' => 'ENUM','constraint' => ['ENTRADA','SALIDA']],
            'cantidad'    => ['type' => 'INT','unsigned' => true],
            'creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('producto_id','productos','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('lote_id','lotes','id','SET NULL','CASCADE');
        $this->forge->createTable('movimientos');
    }

    public function down()
    {
        $this->forge->dropTable('movimientos');
    }
}
