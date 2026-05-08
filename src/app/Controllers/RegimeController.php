<?php

namespace App\Controllers;

use App\Models\Regime;

class RegimeController extends BaseController
{
    private $regimeModel;

    public function __construct()
    {
        // Vérifier que l'utilisateur est connecté
        if (!session()->get('logged_in')) {
            exit('Accès non autorisé. Veuillez vous connecter.');
        }

        // Optionnel : restreindre aux admins (exemple : email spécifique)
        // if (session()->get('user_email') !== 'admin@exemple.com') {
        //     exit('Accès réservé aux administrateurs.');
        // }

        $this->regimeModel = new Regime();
    }

    // Liste des régimes
    public function index()
    {
        $regimes = $this->regimeModel->orderBy('created_at', 'DESC')->paginate(10);
        return view('regime/admin_list', [
            'regimes' => $regimes,
            'pager'   => $this->regimeModel->pager
        ]);
    }

    // Formulaire de création
    public function create()
    {
        return view('regime/admin_create');
    }

    // Enregistrement
    public function store()
    {
        $rules = [
            'nom'        => 'required|min_length[3]',
            'pct_viande' => 'required|numeric|between[0,100]',
            'pct_volaille' => 'required|numeric|between[0,100]',
            'pct_poisson' => 'required|numeric|between[0,100]',
            'variation_poids_kg' => 'required|numeric',
            'duree_jours' => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nom'        => $this->request->getPost('nom'),
            'description'=> $this->request->getPost('description'),
            'pct_viande' => $this->request->getPost('pct_viande'),
            'pct_volaille' => $this->request->getPost('pct_volaille'),
            'pct_poisson' => $this->request->getPost('pct_poisson'),
            'variation_poids_kg' => $this->request->getPost('variation_poids_kg'),
            'duree_jours' => $this->request->getPost('duree_jours'),
        ];

        if ($this->regimeModel->insert($data)) {
            return redirect()->to('/regime/admin')->with('message', 'Régime créé avec succès.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création.');
        }
    }

    // Formulaire d'édition
    public function edit($id)
    {
        $regime = $this->regimeModel->find($id);
        if (!$regime) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return view('regime/admin_edit', ['regime' => $regime]);
    }

    // Mise à jour
    public function update($id)
    {
        $rules = [
            'nom'        => 'required|min_length[3]',
            'pct_viande' => 'required|numeric|between[0,100]',
            'pct_volaille' => 'required|numeric|between[0,100]',
            'pct_poisson' => 'required|numeric|between[0,100]',
            'variation_poids_kg' => 'required|numeric',
            'duree_jours' => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nom'        => $this->request->getPost('nom'),
            'description'=> $this->request->getPost('description'),
            'pct_viande' => $this->request->getPost('pct_viande'),
            'pct_volaille' => $this->request->getPost('pct_volaille'),
            'pct_poisson' => $this->request->getPost('pct_poisson'),
            'variation_poids_kg' => $this->request->getPost('variation_poids_kg'),
            'duree_jours' => $this->request->getPost('duree_jours'),
        ];

        if ($this->regimeModel->update($id, $data)) {
            return redirect()->to('/regime/admin')->with('message', 'Régime mis à jour.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour.');
        }
    }

    // Suppression
    public function delete($id)
    {
        if ($this->regimeModel->delete($id)) {
            return redirect()->to('/regime/admin')->with('message', 'Régime supprimé.');
        } else {
            return redirect()->back()->with('error', 'Impossible de supprimer ce régime (peut-être lié à des souscriptions).');
        }
    }
}