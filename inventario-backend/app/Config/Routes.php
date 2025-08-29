<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('pruebas', ['namespace' => 'App\Controllers'], static function($routes) {
    // Productos
    $routes->get('productos/listar', 'ProductoControlador::listar');
    $routes->post('productos/crear', 'ProductoControlador::crear');

    // Inventario
    $routes->post('inventario/entrada', 'InventarioControlador::entrada');
    $routes->post('inventario/salida',  'InventarioControlador::salida');
    $routes->get('inventario/listar',   'InventarioControlador::listar');
});
