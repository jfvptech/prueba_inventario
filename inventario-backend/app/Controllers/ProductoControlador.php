<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductosModelo;
use CodeIgniter\HTTP\ResponseInterface;

class ProductoControlador extends BaseController
{
   public function listar()
    {
        $m = new ProductosModelo();
        return $this->response->setJSON($m->findAll());
    }

    public function crear()
    {
        $datos = $this->request->getJSON(true) ?? $this->request->getPost();

        $m = new ProductosModelo();
        if (! $m->insert($datos)) {
            return $this->response->setStatusCode(422)
                ->setJSON(['errores' => $m->errors()]);
        }

        return $this->response->setJSON([
            'id' => $m->getInsertID(),
            'mensaje' => 'Producto creado'
        ]);
    }
}
