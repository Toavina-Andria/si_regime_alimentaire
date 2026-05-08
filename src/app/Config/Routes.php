<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::index');
$routes->post('auth/register', 'Auth::register');
$routes->get('auth/profil', 'Auth::profil');
$routes->post('auth/updateProfil', 'Auth::updateProfil');
$routes->get('dashboard', 'UserDashboard::index');
$routes->get('logout', 'Auth::logout');

// Routes pour les régimes alimentaires
$routes->get('regimes', 'RegimeController::index');
$routes->get('regime/(:num)', 'RegimeController::show/$1');

// Route pour traiter l'envoi du login et rediriger
$routes->post('auth/register', 'Auth::register');
$routes->get('/', 'Auth::index');
$routes->post('auth/register', 'Auth::register');
$routes->get('auth/profil', 'Auth::profil');
$routes->post('auth/updateProfil', 'Auth::updateProfil');
$routes->get('dashboard', 'UserDashboard::index');
$routes->get('connexion', 'Auth::loginForm');
$routes->post('auth/doLogin', 'Auth::doLogin');
$routes->get('logout', 'Auth::logout');

// Routes Admin / Dashboard
$routes->group('admin', static function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('regimes', 'DashboardController::regimes');
    $routes->get('codes', 'DashboardController::codes');
    $routes->get('activites', 'DashboardController::activites');
    $routes->get('utilisateurs', 'DashboardController::utilisateurs');
});
