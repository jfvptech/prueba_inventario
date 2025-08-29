<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CrearProductos extends Migration
{
     public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT','unsigned' => true,'auto_increment' => true],
            'nombre'      => ['type' => 'VARCHAR','constraint' => 200],
            'codigo'      => ['type' => 'VARCHAR','constraint' => 60,'unique' => true],
            'unidad'      => ['type' => 'VARCHAR','constraint' => 20,'default' => 'unidad'],
            'creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('productos');
    }

    public function down()
    {
        $this->forge->dropTable('productos');
    }
}
