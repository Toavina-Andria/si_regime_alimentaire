<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('register', 'Auth::index');
$routes->post('auth/register', 'Auth::register');
$routes->get('auth/profil', 'Auth::profil');
$routes->post('auth/updateProfil', 'Auth::updateProfil');

// Connexion
$routes->get('connexion', 'Auth::login');
$routes->get('auth/connexion', 'Auth::login');
$routes->post('auth/doLogin', 'Auth::doLogin');

$routes->get('logout', 'Auth::logout');
$routes->get('quick-login/(:num)', 'Auth::quickLogin/$1');

// Dashboard utilisateur (front office)
$routes->get('dashboard', 'UserDashboard::index');

// Consultation publique des régimes
$routes->get('regimes', 'RegimeController::index');
$routes->get('regime/(:num)', 'RegimeController::show/$1');

// Routes Admin / Dashboard (Back Office)
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
    $routes->post('activites', 'DashboardController::storeActivite');
    $routes->post('activites/update/(:num)', 'DashboardController::updateActivite/$1');
    $routes->get('activites/delete/(:num)', 'DashboardController::deleteActivite/$1');
    $routes->post('codes', 'DashboardController::storeCode');
    $routes->post('codes/update/(:num)', 'DashboardController::updateCode/$1');
    $routes->get('codes/delete/(:num)', 'DashboardController::deleteCode/$1');
    $routes->get('utilisateurs', 'DashboardController::utilisateurs');
    $routes->post('utilisateurs/update/(:num)', 'DashboardController::updateUtilisateur/$1');
    $routes->get('utilisateurs/delete/(:num)', 'DashboardController::deleteUtilisateur/$1');
    $routes->get('parametres', 'DashboardController::parametres');
    $routes->post('parametres', 'DashboardController::updateParametres');
});

// Export PDF
$routes->get('export/bilan', 'ExportController::bilan');

// Statistiques utilisateur
$routes->get('stats', 'StatsController::index');

// Gestion des régimes (CRUD)
$routes->get('regime/admin', 'RegimeController::index');
$routes->get('regime/admin/create', 'RegimeController::create');
$routes->post('regime/admin/store', 'RegimeController::store');
$routes->get('regime/admin/edit/(:num)', 'RegimeController::edit/$1');
$routes->post('regime/admin/update/(:num)', 'RegimeController::update/$1');
$routes->get('regime/admin/delete/(:num)', 'RegimeController::delete/$1');

// Routes pour les codes bonus
$routes->get('code', 'CodeController::index');
$routes->get('wallet/code', 'CodeController::index');
$routes->post('code/verify', 'CodeController::verifier');

$routes->get('services', 'ServicesController::index');
$routes->get('analysis', 'DataAnalysisController::index');

// Routes pour les abonnements
$routes->get('abonnement/(:num)', 'AbonnementController::index/$1');
$routes->post('abonnement/souscrire', 'AbonnementController::souscrireRegime');
$routes->get('abonnements', 'AbonnementController::liste');

// Souscription à un régime
$routes->post('regime/souscrire', 'RegimeController::souscrire');
