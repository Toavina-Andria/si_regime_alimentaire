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
    public static function logout()
    {
        session()->destroy();

        return redirect()->to('/');
    }
}
