<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Pages publiques / authentification
$routes->get('/', 'Auth::index');                      // inscription
$routes->post('auth/register', 'Auth::register');
$routes->get('auth/profil', 'Auth::profil');
$routes->post('auth/updateProfil', 'Auth::updateProfil');

// Connexion – double accès pour compatibilité
$routes->get('connexion', 'Auth::login');              // URL simple
$routes->get('auth/connexion', 'Auth::login');         // alias avec auth/
$routes->post('auth/doLogin', 'Auth::doLogin');

$routes->get('logout', 'Auth::logout');

// Dashboard utilisateur (front office)
$routes->get('dashboard', 'UserDashboard::index');

// Consultation publique des régimes
$routes->get('regimes', 'RegimeController::index');
$routes->get('regime/(:num)', 'RegimeController::show/$1');

// Back Office (admin)
$routes->group('admin', static function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('regimes', 'DashboardController::regimes');
    $routes->get('codes', 'DashboardController::codes');
    $routes->get('activites', 'DashboardController::activites');
    $routes->get('utilisateurs', 'DashboardController::utilisateurs');
});