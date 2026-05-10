<?php

namespace App\Controllers;

use App\Models\Utilisateur;
use App\Services\AuthService;
use App\Services\UtilisateurService;

class Auth extends BaseController
{
    // Affiche le formulaire d'inscription
    public function index()
    {
        return view('authentification/login');
    }

    // Traite l'inscription (1ère étape)
    public function register()
    {
        log_message('info', 'Début du processus d\'inscription.');
        // create user



        if (!$this->validate(Utilisateur::$validationRulesInscription)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }


        $data = [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'email' => $this->request->getPost('email'),
            'mot_de_passe' => $this->request->getPost('mot_de_passe'), // en clair
            'created_at' => date('Y-m-d H:i:s')
        ];
        // register user and get ID
        try {
            $userId = AuthService::register($data);

            // apend user ID to data for session
            $data['id'] = $userId;
            $this->setSession($data);
            return redirect()->to('/auth/profil');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de l\'inscription : ' . $th->getMessage());
        }
    }

    public function setSession($data)
    {
        session()->regenerate();
        session()->set([
            'user_id' => $data['id'],
            'user_email' => $data['email'],
            'user_nom' => $data['nom'],
            'logged_in' => true
        ]);
    }

    // Affiche la page de connexion
    public function login()
    {
        return view('authentification/connexion');
    }

    // Traite la connexion
    public function doLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('mot_de_passe');

        $user = AuthService::attemptLogin($email, $password);
        if ($user) {
            $this->setSession($user);
            if (!empty($user['est_admin'])) {
                session()->set('est_admin', true);
                return redirect()->to('/admin/dashboard');
            }
            return redirect()->to('/dashboard');
        }

        return redirect()->back()->withInput()->with('error', 'Email ou mot de passe incorrect.');
    }

    // Affiche le formulaire de complétion du profil
    public function profil()
    {

        if ($redirect = AuthService::requireLogin('/')) {
            return $redirect;
        }

        return view('authentification/formulaire');
    }

    // Traite la mise à jour du profil (2ème étape)
    public function updateProfil()
    {
        $redirect = null;

        if (AuthService::requireLogin()) {
            return $redirect;
        }

        $userId = session()->get('user_id');



        if (!$this->validate(Utilisateur::$validationRulesProfil)) {
            $redirect = redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        } else {
            $data = [
                'date_naissance' => $this->request->getPost('date_naissance'),
                'genre' => $this->request->getPost('genre'),
                'taille_cm' => $this->request->getPost('taille_cm'),
                'poids_kg' => $this->request->getPost('poids_kg'),
                'objectif' => $this->request->getPost('objectif'),
                'adresse' => $this->request->getPost('adresse') ?: null
            ];

            $result = AuthService::updateProfil($userId, $data);

            if (!$result['success']) {
                $redirect = redirect()->back()->withInput()->with('error', 'Erreur mise à jour : ' . $result['message']);
            } else {
                $redirect = redirect()->to('/dashboard');
            }
        }

        return $redirect;
    }

    // Déconnexion
    public function logout()
    {
        return AuthService::logout();
    }
}
