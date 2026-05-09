<?php

namespace App\Services;

use App\Models\Utilisateur;
use Exception;

class AuthService
{
    public static function requireLogin(string $redirectTo = '/connexion', string $message = 'Veuillez vous connecter pour accéder à cette page.')
    {
        if (!session()->get('logged_in')) {
            return redirect()->to($redirectTo)->with('error', $message);
        }

        return null;
    }
    public static function register($data)
    {
        try {
             // create user - validation should be done in controller
            $data = [
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'mot_de_passe' => $data['mot_de_passe'] // model va hacher
            ];
            $util = new Utilisateur();
            $util->insert($data);
            return $util->getInsertID();
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de l\'inscription : ' . $th->getMessage());
        }
    }
    public static function logout()
    {
        session()->destroy();

        return redirect()->to('/');
    }
}
