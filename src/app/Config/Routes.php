<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('register', 'Auth::index');

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
$routes->get('quick-login/(:num)', 'Auth::quickLogin/$1');

// Routes pour les régimes alimentaires
$routes->get('regimes', 'Home::index');
// Dashboard utilisateur (front office)
$routes->get('dashboard', 'UserDashboard::index');

// Consultation publique des régimes
$routes->get('regimes', 'RegimeController::index');
$routes->get('regime/(:num)', 'RegimeController::show/$1');

// Route pour traiter l'envoi du login et rediriger
$routes->get('auth/profil', 'Auth::profil');
$routes->post('auth/updateProfil', 'Auth::updateProfil');
$routes->get('connexion', 'Auth::loginForm');
$routes->post('auth/doLogin', 'Auth::doLogin');
$routes->get('logout', 'Auth::logout');

// Routes Admin / Dashboard
// Back Office (admin)
$routes->group('admin', static function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('stats', 'DashboardController::stats');
    $routes->get('regimes', 'DashboardController::regimes');
    $routes->get('abonnements', 'DashboardController::abonnements');
    $routes->post('abonnement/store', 'DashboardController::storeAbonnement');
    $routes->post('abonnement/update/(:num)', 'DashboardController::updateAbonnement/$1');
    $routes->get('abonnement/delete/(:num)', 'DashboardController::deleteAbonnement/$1');
    $routes->get('codes', 'DashboardController::codes');
    $routes->get('activites', 'DashboardController::activites');
    $routes->get('utilisateurs', 'DashboardController::utilisateurs');
});

// Route pour l'export PDF du bilan personnel
$routes->get('export/bilan', 'ExportController::bilan');

// Routes pour les statistiques utilisateur
$routes->get('stats', 'StatsController::index');