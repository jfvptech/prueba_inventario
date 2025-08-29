<?php

namespace App\Models;

use CodeIgniter\Model;

class LoteModelo extends Model
{
    protected $table = 'lotes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['producto_id','vence_en','cantidad','disponible'];
    protected $validationRules = [
        'producto_id' => 'required|is_natural_no_zero',
        'vence_en'    => 'required|valid_date[Y-m-d]',
        'cantidad'    => 'required|is_natural_no_zero',
        'disponible'  => 'required|is_natural'
    ];
}
