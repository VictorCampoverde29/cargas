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

$routes->group('index', ['filter' => 'CambioFilter'], function ($routes) {
   $routes->get('dt_ultimos_viajes', 'Home::dt_ultimos_viajes');
});

$routes->group('dashboard', ['filter' => 'AuthFilter'], function ($routes) {
    $routes->get('mant_viajes', 'ViajesController::index');
    $routes->get('reg_servicio', 'RegistrarServicioController::index');
    $routes->get('mant_carga', 'CargaController::index');
    $routes->get('mant_destino', 'DestinosController::index');
});

$routes->group('mant_carga', ['filter' => 'CambioFilter'], function ($routes) {
    $routes->get('datatables', 'CargaController::traerCarga');
    $routes->post('agregar_carga', 'CargaController::agregarCarga');
    $routes->get('carga_xcod', 'CargaController::getCargaXcod');
    $routes->post('editar_carga', 'CargaController::editarCarga');
});

$routes->group('mant_viajes', ['filter' => 'CambioFilter'], function ($routes) {
    $routes->get('cmbprovincia', 'DestinosController::getProvinciaXDep');
    $routes->get('cmbdistrito', 'DestinosController::getDistritoXProvDep');
    $routes->get('cmbprovincia2', 'DestinosController::getProvinciaXDep');
    $routes->get('cmbdistrito2', 'DestinosController::getDistritoXProvDep');
    $routes->get('datos_conductores', 'ConductorController::getDatosXcod');
    $routes->get('datos_vehiculos', 'VehiculosController::getDatosXcod');
    $routes->post('registrar_viaje', 'ViajesController::registrarViaje');
    $routes->get('datatables', 'ViajesController::traerViajes');
    $routes->post('editar_viaje', 'ViajesController::editarViaje');
    $routes->get('ultimos_viajes_dash', 'ViajesController::ultimosViajesDash');
    $routes->get('estadisticas_viajes_dash', 'ViajesController::estadisticasViajesDash');
    $routes->post('validar_estado_servicios', 'ServicioController::validarServiciosViaje');
    $routes->get('select_cargas', 'CargaController::selectCargas');
    $routes->get('buscar_destinos', 'DestinosController::buscadorDestinos');
});

$routes->group('mant_destino', ['filter' => 'CambioFilter'], function ($routes) {
    $routes->get('datatables', 'DestinosController::getDestinos');
    $routes->get('destinos_xcod', 'DestinosController::getDestinosXcod');
    $routes->post('agregar_destino', 'DestinosController::agregarDestino');
    $routes->post('editar_destino', 'DestinosController::editarDestino');
});

$routes->group('guias', ['filter' => 'CambioFilter'], function ($routes) {
    $routes->get('rango', 'GuiaTransController::traerGuias');
});

$routes->group('servicios', ['filter' => 'CambioFilter'], function ($routes) {
    $routes->post('reg_servicio', 'ServicioController::registrarServicio');
    $routes->get('datatables', 'ServicioController::traerServiciosXCod');
    $routes->get('verificar_venta', 'VentasController::verificarVenta');
    $routes->post('editar', 'ServicioController::update');
    $routes->get('obtener_id_guia', 'ServicioController::obtenerIdGuia');
});

$routes->group('ventas', ['filter' => 'CambioFilter'], function ($routes) {
    $routes->get('generarPDF/(:num)', 'VentasController::generarPDF/$1');
});