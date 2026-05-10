<?php

namespace App\Controllers;

use App\Models\Regime;
use App\Services\RegimeService;
use App\Services\RegimePrixService;

class RegimeController extends BaseController
{
    private $regimeModel;
    private $regimeService;

    public function __construct()
    {
        // Vérifier que l'utilisateur est connecté
        if (!session()->get('logged_in')) {
            exit('Accès non autorisé. Veuillez vous connecter.');
        }

        $this->regimeModel = new Regime();
        $this->regimeService = new RegimeService();
    }

    // ------------------------------------------------------------
    // FRONT OFFICE (liste des régimes pour les utilisateurs)
    // ------------------------------------------------------------
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/connexion');
        }

        $regimes = $this->regimeModel->orderBy('created_at', 'DESC')->findAll();

        // Ajout du prix minimum pour chaque régime
        foreach ($regimes as &$r) {
            $minPrix = RegimePrixService::getMinPrix($r['id']);
            $r['prix_depart'] = $minPrix ? $minPrix['prix_base'] : null;
            $r['duree_min']   = $minPrix ? $minPrix['duree_jours'] : null;
        }

        return view('regime/detail', ['regimes' => $regimes]);
    }

    // ------------------------------------------------------------
    // Détail d'un régime (front)
    // ------------------------------------------------------------
    public function show($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/connexion');
        }

        $regime = $this->regimeModel->find($id);
        if (!$regime) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $prixOptions = RegimePrixService::getPrixByRegime($id);

        $db = \Config\Database::connect();
        $activites = $db->table('regime_activite ra')
            ->select('a.nom, a.description, a.intensite, a.calories_heure, ra.frequence_semaine')
            ->join('activite_sportive a', 'a.id = ra.activite_id')
            ->where('ra.regime_id', $id)
            ->orderBy('a.intensite', 'ASC')
            ->get()
            ->getResultArray();

        return view('regime/detail', [
            'regime'      => $regime,
            'prixOptions' => $prixOptions,
            'activites'   => $activites,
        ]);
    }

    // ------------------------------------------------------------
    // ADMINISTRATION - Liste des régimes avec pagination
    // ------------------------------------------------------------
    public function adminIndex()
    {
        $regimes = $this->regimeModel->orderBy('created_at', 'DESC')->paginate(10);
        foreach ($regimes as &$r) {
            $minPrix = RegimePrixService::getMinPrix($r['id']);
            $r['prix_min'] = $minPrix ? $minPrix['prix_base'] : null;
        }

        return view('regime/admin_list', [
            'regimes' => $regimes,
            'pager'   => $this->regimeModel->pager
        ]);
    }

    // ------------------------------------------------------------
    // Formulaire de création (admin)
    // ------------------------------------------------------------
    public function create()
    {
        return view('regime/admin_create');
    }

    // ------------------------------------------------------------
    // Enregistrement d'un nouveau régime (admin)
    // ------------------------------------------------------------
    public function store()
    {
        $data = [
            'nom'               => $this->request->getPost('nom'),
            'description'       => $this->request->getPost('description'),
            'pct_viande'        => $this->request->getPost('pct_viande'),
            'pct_volaille'      => $this->request->getPost('pct_volaille'),
            'pct_poisson'       => $this->request->getPost('pct_poisson'),
            'variation_poids_kg'=> $this->request->getPost('variation_poids_kg'),
            'duree_jours'       => $this->request->getPost('duree_jours'),
        ];

        try {
            RegimeService::createRegime($data);

            $accept = $this->request->getHeaderLine('Accept');
            if ($this->request->isAJAX() || str_contains($accept, 'application/json')) {
                return $this->response->setJSON(['success' => true, 'message' => 'Régime créé avec succès.']);
            }

            return redirect()->to('/regime/admin')->with('message', 'Régime créé avec succès.');
        } catch (\Throwable $th) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Erreur : ' . $th->getMessage()]);
            }
            return redirect()->back()->withInput()->with('error', 'Erreur : ' . $th->getMessage());
        }
    }

    // ------------------------------------------------------------
    // Formulaire d'édition (admin)
    // ------------------------------------------------------------
    public function edit($id)
    {
        $regime = $this->regimeModel->find($id);
        if (!$regime) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return view('regime/admin_edit', ['regime' => $regime]);
    }

    // ------------------------------------------------------------
    // Mise à jour d'un régime (admin)
    // ------------------------------------------------------------
    public function update($id)
    {
        $data = [
            'nom'               => $this->request->getPost('nom'),
            'description'       => $this->request->getPost('description'),
            'pct_viande'        => $this->request->getPost('pct_viande'),
            'pct_volaille'      => $this->request->getPost('pct_volaille'),
            'pct_poisson'       => $this->request->getPost('pct_poisson'),
            'variation_poids_kg'=> $this->request->getPost('variation_poids_kg'),
            'duree_jours'       => $this->request->getPost('duree_jours'),
        ];

        $result = RegimeService::updateRegime($id, $data);

        if ($result['success']) {
            return redirect()->to('/regime/admin')->with('message', $result['message']);
        } else {
            $errors = $result['errors'] ?? [];
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $result['message']);
        }
    }

    // ------------------------------------------------------------
    // Suppression d'un régime (admin)
    // ------------------------------------------------------------
    public function delete($id)
    {
        $result = RegimeService::deleteRegime($id);

        if ($result['success']) {
            return redirect()->to('/regime/admin')->with('message', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }
}