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