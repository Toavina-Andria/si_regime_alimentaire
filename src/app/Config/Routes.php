<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'DashboardController::index');
$routes->get('/code', 'CodeController::index');
$routes->post('/code/verify', 'CodeController::verifier');
// La page d'accueil affiche le login
$routes->get('/', 'Auth::index');

// Route pour afficher le formulaire de profil
$routes->get('auth/profil', 'Auth::profil');

// Route pour traiter l'envoi du login et rediriger
$routes->post('auth/register', 'Auth::register');
