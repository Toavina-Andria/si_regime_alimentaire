<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::index');
$routes->post('auth/register', 'Auth::register');
$routes->get('auth/profil', 'Auth::profil');
$routes->post('auth/updateProfil', 'Auth::updateProfil');
$routes->get('dashboard', 'Auth::dashboard');
$routes->get('logout', 'Auth::logout');


// Routes pour les régimes alimentaires
$routes->get('regimes', 'RegimeController::index');
$routes->get('regime/(:num)', 'RegimeController::show/$1');
