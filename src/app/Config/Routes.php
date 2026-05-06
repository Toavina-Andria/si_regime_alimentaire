<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'DashboardController::index');
$routes->get('/code', 'CodeController::index');
$routes->post('/code/verify', 'CodeController::verifier');