<?php

namespace App\Controllers;

class ServicesController extends BaseController
{
    public function index()
    {
        // Vérifier connexion
        if (!session()->get('logged_in')) {
            return redirect()->to('/connexion');
        }

        return view('services/choix');
    }
}