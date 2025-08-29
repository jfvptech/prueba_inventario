<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LoteModelo;
use App\Models\MovimientoModelo;
use App\Models\ProductosModelo;
use CodeIgniter\HTTP\ResponseInterface;

helper('estado');

class InventarioControlador extends BaseController
{
    /**
     * POST /api/inventario/entrada
     * Body: { producto_id, cantidad, vence_en (Y-m-d) }
     */
    public function entrada()
    {
        $payload = $this->request->getJSON(true) ?? $this->request->getPost();

        $reglas = [
            'producto_id' => 'required|is_natural_no_zero',
            'cantidad'    => 'required|is_natural_no_zero',
            'vence_en'    => 'required|valid_date[Y-m-d]',
        ];
        if (! $this->validate($reglas)) {
            return $this->response->setStatusCode(422)
                ->setJSON(['errores' => $this->validator->getErrors()]);
        }

        $db = db_connect();
        $db->transStart();

        $loteModelo = new LoteModelo();
        $movModelo  = new MovimientoModelo();

        $loteId = $loteModelo->insert([
            'producto_id' => (int)$payload['producto_id'],
            'vence_en'    => $payload['vence_en'],
            'cantidad'    => (int)$payload['cantidad'],
            'disponible'  => (int)$payload['cantidad'],
        ], true);

        if (! $loteId) {
            $db->transRollback();
            return $this->response->setStatusCode(422)
                ->setJSON(['errores' => $loteModelo->errors()]);
        }

        $movModelo->insert([
            'producto_id' => (int)$payload['producto_id'],
            'lote_id'     => $loteId,
            'tipo'        => 'ENTRADA',
            'cantidad'    => (int)$payload['cantidad'],
        ]);

        $db->transComplete();

        return $this->response->setJSON([
            'lote_id' => $loteId,
            'mensaje' => 'Entrada registrada'
        ]);
    }

    /**
     * POST /api/inventario/salida
     * Body: { producto_id, cantidad }
     * Lógica FEFO (sale primero lo que vence antes)
     */
    public function salida()
    {
        $payload = $this->request->getJSON(true) ?? $this->request->getPost();

        $reglas = [
            'producto_id' => 'required|is_natural_no_zero',
            'cantidad'    => 'required|is_natural_no_zero',
        ];
        if (! $this->validate($reglas)) {
            return $this->response->setStatusCode(422)
                ->setJSON(['errores' => $this->validator->getErrors()]);
        }

        $necesita   = (int)$payload['cantidad'];
        $productoId = (int)$payload['producto_id'];

        $db = db_connect();
        $db->transStart();

        $loteModelo = new LoteModelo();
        $movModelo  = new MovimientoModelo();

        $lotes = $loteModelo
            ->where('producto_id', $productoId)
            ->where('disponible >', 0)
            ->orderBy('vence_en', 'ASC') // FEFO
            ->findAll();

        foreach ($lotes as $lote) {
            if ($necesita <= 0) break;

            $toma = min($necesita, (int)$lote['disponible']);
            if ($toma <= 0) continue;

            // actualizar disponible
            $loteModelo->update($lote['id'], [
                'disponible' => (int)$lote['disponible'] - $toma
            ]);

            // registrar movimiento
            $movModelo->insert([
                'producto_id' => $productoId,
                'lote_id'     => $lote['id'],
                'tipo'        => 'SALIDA',
                'cantidad'    => $toma,
            ]);

            $necesita -= $toma;
        }

        if ($necesita > 0) {
            $db->transRollback();
            return $this->response->setStatusCode(409)
                ->setJSON(['error' => 'Stock insuficiente para la salida solicitada.']);
        }

        $db->transComplete();

        return $this->response->setJSON(['ok' => true, 'mensaje' => 'Salida aplicada']);
    }

    /**
     * GET /api/inventario/listar[?producto_id=##]
     * Resumen por producto con estado global y lotes.
     */
    public function listar()
    {
        $productoId = (int)($this->request->getGet('producto_id') ?? 0);

        $prodModelo = new ProductosModelo();
        $loteModelo = new LoteModelo();

        $productos = $productoId
            ? $prodModelo->where('id', $productoId)->findAll()
            : $prodModelo->findAll();

        $hoy = date('Y-m-d');
        $salida = [];

        foreach ($productos as $p) {
            $lotes = $loteModelo
                ->where('producto_id', $p['id'])
                ->orderBy('vence_en', 'ASC')
                ->findAll();

            $total = 0;
            $detalleLotes = [];
            $peor = 'VIGENTE'; 

            foreach ($lotes as $l) {
                $estado = estado_vencimiento($l['vence_en'], $hoy);

                $total += (int)$l['disponible'];
                $detalleLotes[] = [
                    'lote_id'    => (int)$l['id'],
                    'vence_en'   => $l['vence_en'],
                    'cantidad'   => (int)$l['cantidad'],
                    'disponible' => (int)$l['disponible'],
                    'estado'     => $estado,
                ];

                $prioridad = ['VIGENTE' => 1, 'POR_VENCER' => 2, 'VENCIDO' => 3];
                if ($prioridad[$estado] > $prioridad[$peor]) $peor = $estado;
            }

            $salida[] = [
                'producto' => [
                    'id'     => (int)$p['id'],
                    'nombre' => $p['nombre'],
                    'codigo' => $p['codigo'],
                    'unidad' => $p['unidad'],
                ],
                'existencia_total' => $total,
                'estado'           => $peor,
                'lotes'            => $detalleLotes,
            ];
        }

        return $this->response->setJSON($salida);
    }
}
