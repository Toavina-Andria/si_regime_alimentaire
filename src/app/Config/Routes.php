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

$routes->get('connexion', 'Auth::login');
$routes->get('auth/connexion', 'Auth::login');
$routes->post('auth/doLogin', 'Auth::doLogin');

$routes->get('logout', 'Auth::logout');
$routes->get('quick-login/(:num)', 'Auth::quickLogin/$1');

$routes->get('dashboard', 'UserDashboard::index');

$routes->get('regimes', 'RegimeController::index');
$routes->get('regime/(:num)', 'RegimeController::show/$1');

$routes->group('admin', static function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('stats', 'DashboardController::stats');
    $routes->get('regimes', 'DashboardController::regimes');
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
});

$routes->get('export/bilan', 'ExportController::bilan');

$routes->get('stats', 'StatsController::index');
$routes->post('stats/poids', 'StatsController::storeWeight');

$routes->get('regime/admin', 'RegimeController::index');
$routes->get('regime/admin/create', 'RegimeController::create');
$routes->post('regime/admin/store', 'RegimeController::store');
$routes->get('regime/admin/edit/(:num)', 'RegimeController::edit/$1');
$routes->post('regime/admin/update/(:num)', 'RegimeController::update/$1');
$routes->get('regime/admin/delete/(:num)', 'RegimeController::delete/$1');

$routes->get('code', 'CodeController::index');
$routes->get('wallet/code', 'CodeController::index');
$routes->post('code/verify', 'CodeController::verifier');

$routes->get('services', 'ServicesController::index');
$routes->get('analysis', 'DataAnalysisController::index');

$routes->get('abonnement/(:num)', 'AbonnementController::index/$1');
$routes->post('abonnement/souscrire', 'AbonnementController::souscrireRegime');
$routes->get('abonnements', 'AbonnementController::liste');

$routes->post('regime/souscrire', 'RegimeController::souscrire');
