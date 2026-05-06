<?php

namespace App\Controllers;

class Auth extends BaseController
{
    // Affiche la page de création de compte (login.php)
    public function index()
    {
        return view('login');
    }

    // Affiche le formulaire de profil après login
    public function profil()
    {
        return view('formulaire');
    }

    // Traite l'inscription (redirige vers le formulaire de profil)
    public function register()
    {
        // Tu pourras plus tard récupérer les données avec $this->request->getPost()
        // et les enregistrer en base.
        
        // Pour l'instant, simple redirection
        return redirect()->to('/auth/profil');
    }
}