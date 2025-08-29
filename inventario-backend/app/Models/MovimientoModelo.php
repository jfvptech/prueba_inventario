<?php

namespace App\Models;

use CodeIgniter\Model;

class MovimientoModelo extends Model
{
    protected $table = 'movimientos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['producto_id','lote_id','tipo','cantidad'];
    protected $validationRules = [
        'producto_id' => 'required|is_natural_no_zero',
        'tipo'        => 'required|in_list[ENTRADA,SALIDA]',
        'cantidad'    => 'required|is_natural_no_zero'
    ];
}