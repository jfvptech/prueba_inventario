<?php

if (! function_exists('estado_vencimiento')) {
    /**
     * Retorna: 'VENCIDO' | 'POR_VENCER' | 'VIGENTE'
     * Ventana de "por vencer": 3 días.
     */
    function estado_vencimiento(string $fechaVence, ?string $hoy = null): string {
        $hoy = $hoy ?: date('Y-m-d');

        $exp = new DateTime($fechaVence);
        $now = new DateTime($hoy);

        if ($exp < $now) return 'VENCIDO';

        $limite = (clone $now)->modify('+3 days');
        return ($exp <= $limite) ? 'POR_VENCER' : 'VIGENTE';
    }
}
