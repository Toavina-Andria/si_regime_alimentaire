<?php

namespace App\Controllers;

class ServicesController extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/connexion');
        }

        $userId = session()->get('user_id');
        $user = model('App\Models\Utilisateur')->find($userId);
        if ($user && !empty($user['est_admin'])) {
            session()->set('est_admin', true);
            return redirect()->to('/admin/dashboard');
        }

        return view('services/choix');
    }
}