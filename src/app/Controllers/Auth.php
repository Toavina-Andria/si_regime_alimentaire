<?php

namespace App\Controllers;

class Auth extends BaseController
{
    // Affiche le formulaire d'inscription (login.php)
    public function index()
    {
        return view('login');
    }

    // Traite l'inscription (1ère étape : nom, prénom, email, mot de passe)
    public function register()
    {
        $rules = [
            'nom'          => 'required',
            'prenom'       => 'required',
            'email'        => 'required|valid_email|is_unique[utilisateur.email]',
            'mot_de_passe' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $builder = $db->table('utilisateur');

        $data = [
            'nom'          => $this->request->getPost('nom'),
            'prenom'       => $this->request->getPost('prenom'),
            'email'        => $this->request->getPost('email'),
            'mot_de_passe' => password_hash($this->request->getPost('mot_de_passe'), PASSWORD_DEFAULT),
            'created_at'   => date('Y-m-d H:i:s')
        ];

        if (!$builder->insert($data)) {
            $error = $db->error();
            return redirect()->back()->withInput()->with('error', 'Erreur insertion : ' . $error['message']);
        }

        $userId = $db->insertID();

        session()->set([
            'user_id'    => $userId,
            'user_email' => $data['email'],
            'user_nom'   => $data['nom'],
            'logged_in'  => true
        ]);

        return redirect()->to('/auth/profil');
    }

    // Affiche le formulaire de complétion du profil (formulaire.php)
    public function profil()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        return view('formulaire');
    }

    // Traite la mise à jour du profil (2ème étape : santé + objectif)
    public function updateProfil()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $userId = session()->get('user_id');

        // Validation des champs
        $rules = [
            'date_naissance' => 'required|valid_date',
            'genre'          => 'required|in_list[homme,femme]',
            'taille_cm'      => 'required|numeric|greater_than[50]|less_than[300]',
            'poids_kg'       => 'required|numeric|greater_than[10]|less_than[500]',
            'objectif'       => 'required|in_list[augmenter_poids,reduire_poids,imc_ideal]',
            'adresse'        => 'permit_empty|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Données à mettre à jour
        $data = [
            'date_naissance' => $this->request->getPost('date_naissance'),
            'genre'          => $this->request->getPost('genre'),
            'taille_cm'      => $this->request->getPost('taille_cm'),
            'poids_kg'       => $this->request->getPost('poids_kg'),
            'objectif'       => $this->request->getPost('objectif'),
            'adresse'        => $this->request->getPost('adresse') ?: null
        ];

        $db = \Config\Database::connect();
        $builder = $db->table('utilisateur');
        $builder->where('id', $userId);

        if (!$builder->update($data)) {
            $error = $db->error();
            return redirect()->back()->withInput()->with('error', 'Erreur mise à jour : ' . $error['message']);
        }

        // Rediriger vers le tableau de bord
        return redirect()->to('/dashboard');
    }

    // Tableau de bord (après profil complet)
    public function dashboard()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        return view('dashboard/index');
    }

    // Déconnexion
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}