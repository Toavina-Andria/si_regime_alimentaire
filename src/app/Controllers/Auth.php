<?php

namespace App\Controllers;

use App\Models\Utilisateur;
use App\Services\AuthService;
use App\Services\UtilisateurService;

class Auth extends BaseController
{
    public function index()
    {
        return view('authentification/login');
    }

    public function register()
    {
        if (!$this->validate(Utilisateur::$validationRulesInscription)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'email' => $this->request->getPost('email'),
            'mot_de_passe' => $this->request->getPost('mot_de_passe'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            $userId = AuthService::register($data);
            $data['id'] = $userId;
            $this->setSession($data);
            return redirect()->to('/auth/profil');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de l\'inscription : ' . $th->getMessage());
        }
    }

    public function setSession($data)
    {
        session()->set([
            'user_id' => $data['id'],
            'user_email' => $data['email'],
            'user_nom' => $data['nom'],
            'logged_in' => true
        ]);
    }

    public function login()
    {
        $userModel = new Utilisateur();
        $users = $userModel->orderBy('est_admin', 'DESC')->orderBy('nom', 'ASC')->findAll();

        return view('authentification/connexion', ['users' => $users]);
    }

    public function doLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('mot_de_passe');
        $user = AuthService::attemptLogin($email, $password);
        $this->setSession($user);
        return empty($user['date_naissance']) || empty($user['genre']) || empty($user['objectif'])
                    ? redirect()->to('/auth/profil')
                    : redirect()->to('/dashboard');
    }

    public function quickLogin($id)
    {
        $userModel = new Utilisateur();
        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->to('/connexion')->with('error', 'Utilisateur introuvable.');
        }

        $this->setSession($user);

        if (empty($user['date_naissance']) || empty($user['genre']) || empty($user['objectif'])) {
            return redirect()->to('/auth/profil');
        }

        if (!empty($user['est_admin'])) {
            session()->set('est_admin', true);
            return redirect()->to('/admin/dashboard');
        }

        return redirect()->to('/dashboard');
    }

    public function profil()
    {
        if ($redirect = AuthService::requireLogin('/')) {
            return $redirect;
        }

        return view('authentification/formulaire');
    }

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

    public function logout()
    {
        return AuthService::logout();
    }
}
