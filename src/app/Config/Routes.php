<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// La page d'accueil affiche le login
$routes->get('/', 'Auth::index');

// Route pour afficher le formulaire de profil
$routes->get('auth/profil', 'Auth::profil');

// Route pour traiter l'envoi du login et rediriger
$routes->post('auth/register', 'Auth::register');
$routes->get('/', 'Auth::index');
$routes->post('auth/register', 'Auth::register');
$routes->get('auth/profil', 'Auth::profil');
$routes->post('auth/updateProfil', 'Auth::updateProfil');
$routes->get('dashboard', 'Auth::dashboard');
$routes->get('connexion', 'Auth::loginForm');
$routes->post('auth/doLogin', 'Auth::doLogin');
$routes->get('logout', 'Auth::logout');
