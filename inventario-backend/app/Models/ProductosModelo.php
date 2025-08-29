<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductosModelo extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre','codigo','unidad'];
    protected $validationRules = [
        'nombre' => 'required|min_length[2]',
        'codigo' => 'required|is_unique[productos.codigo]',
        'unidad' => 'required'
    ];
}
