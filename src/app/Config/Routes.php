<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// La page d'accueil affiche le login $routes->get('/', 'Auth::index');




$routes->get('/', 'Auth::index');
$routes->post('auth/register', 'Auth::register');
$routes->get('auth/profil', 'Auth::profil');
$routes->post('auth/updateProfil', 'Auth::updateProfil');
$routes->get('dashboard', 'Auth::dashboard');
$routes->get('auth/logout', 'Auth::logout');

// Optionnel : page de connexion pour les déjà inscrits (à créer si besoin)
// $routes->get('connexion', 'Auth::loginForm');
// $routes->post('auth/doLogin', 'Auth::doLogin');