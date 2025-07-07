<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->group('', ['filter' => 'AuthFilter'], function ($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('dashboard', 'Home::index');
    $routes->get('bpadres', 'BarrasPerfilController::ObtenerBarrasPerfilPadres');
});

$routes->group('login', function ($routes) {
    $routes->get('/', 'LoginController::index');
    $routes->post('login', 'LoginController::logueo_ingreso');
    $routes->get('logout', 'LoginController::salir');
    $routes->get('unauthorized', 'LoginController::unauthorized');
});

$routes->group('dashboard', ['filter' => 'AuthFilter'], function ($routes) {
    $routes->get('mant_viajes', 'MantenimientoViajesController::index');
    $routes->get('reg_servicio', 'RegistrarServicioController::index');
    $routes->get('mant_carga', 'MantenimientoCargaController::index');
    $routes->get('mant_destino', 'MantenimientoDestinosController::index');
});

$routes->group('mant_carga', ['filter' => 'CambioFilter'], function ($routes) {
    $routes->get('datatables', 'MantenimientoCargaController::traerCarga');
    $routes->post('agregar_carga', 'MantenimientoCargaController::agregarCarga');
    $routes->post('editar_carga', 'MantenimientoCargaController::editarCarga');
});

$routes->group('mant_viajes', ['filter' => 'CambioFilter'], function ($routes) {
    $routes->get('cmbprovincia', 'DestinosController::getProvinciaXDep');
    $routes->get('cmbdistrito', 'DestinosController::getDistritoXProvDep');
    $routes->get('cmbprovincia2', 'DestinosController::getProvinciaXDep');
    $routes->get('cmbdistrito2', 'DestinosController::getDistritoXProvDep');
    $routes->get('datos_conductores', 'ConductorController::getDatosXcod');
    $routes->get('datos_vehiculos', 'VehiculosController::getDatosXcod');
    $routes->post('registrar_viaje', 'MantenimientoViajesController::registrarViaje');
    $routes->get('datatables', 'MantenimientoViajesController::traerViajes');
    $routes->post('editar_viaje', 'MantenimientoViajesController::editarViaje');
});

$routes->group('mant_destino', ['filter' => 'CambioFilter'], function ($routes) {
    $routes->get('datatables', 'MantenimientoDestinosController::getDestinos');
    $routes->post('agregar_destino', 'MantenimientoDestinosController::agregarDestino');
    $routes->post('editar_destino', 'MantenimientoDestinosController::editarDestino');
});

$routes->group('reg_servicio', ['filter' => 'CambioFilter'], function ($routes) {
    $routes->get('cmbprovincia', 'DestinosController::getProvinciaXDep');
    $routes->get('cmbdistrito', 'DestinosController::getDistritoXProvDep');
    $routes->get('cmbprovincia2', 'DestinosController::getProvinciaXDep');
    $routes->get('cmbdistrito2', 'DestinosController::getDistritoXProvDep');
});