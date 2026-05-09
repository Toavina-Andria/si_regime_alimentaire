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

        if ($user) {
            $storedPassword = (string) ($user['mot_de_passe'] ?? '');
            $passwordIsValid = $storedPassword === $password || password_verify($password, $storedPassword);
            if ($passwordIsValid) {
                return $user;
            }
        }

        return null;
    }
    public static function updateProfil(int $id_user, array $data)
    {
        try {
            UtilisateurService::validateUser($id_user);

            $utilisateurModel = new Utilisateur();
            if ($utilisateurModel->update($id_user, $data) === false) { // Use model rather than builder
                $errors = $utilisateurModel->errors();
                $errorMsg = !empty($errors) ? implode(', ', $errors) : "Erreur inconnue lors de la mise à jour";
                throw new \Exception($errorMsg);
            }

        } catch (\Throwable $th) {
            return ['success' => false, 'message' => $th->getMessage()];
        }

        return ['success' => true, 'message' => 'Profil mis à jour avec succès'];
    }
    public static function logout()
    {
        session()->destroy();

        return redirect()->to('/');
    }
}
