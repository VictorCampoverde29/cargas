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
