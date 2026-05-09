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
    public static function attemptLogin(string $email, string $password)
    {
        $userModel = new Utilisateur();
        $user = $userModel->where('email', $email)->first();
        $redirect = redirect()->back()->with('error', 'Email ou mot de passe incorrect');

        if ($user) {
            $storedPassword = (string) ($user['mot_de_passe'] ?? '');
            $passwordIsValid = $storedPassword === $password || password_verify($password, $storedPassword);

            if ($passwordIsValid) {
                session()->set([
                    'user_id' => $user['id'],
                    'user_email' => $user['email'],
                    'user_nom' => $user['nom'],
                    'logged_in' => true,
                ]);

                $redirect = empty($user['date_naissance']) || empty($user['genre']) || empty($user['objectif'])
                    ? redirect()->to('/auth/profil')
                    : redirect()->to('/dashboard');
            }
        }

        return $redirect;
    }
    public static function logout()
    {
        session()->destroy();

        return redirect()->to('/');
    }
}
