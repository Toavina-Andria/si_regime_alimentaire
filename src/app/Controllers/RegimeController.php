<?php

namespace App\Controllers;

use App\Models\Portefeuille;
use App\Models\Regime;
use App\Services\RegimeService;

class RegimeController extends BaseController
{
    private $regimeModel;
    private $regimeService;
    private $regimePrixModel;

    public function __construct()
    {
        $this->regimeModel = new Regime();
        $this->regimeService = new RegimeService();
    }

    public function index()
    {
        $regimes = $this->regimeModel->orderBy('created_at', 'DESC')->paginate(10);
        return view('dashboard/regimes', [
            'regimes' => $regimes,
            'pager' => $this->regimeModel->pager,
        ]);
    }

    public function create()
    {
        return view('regime/admin_create');
    }

    public function show($id)
    {
        $regime = RegimeService::getRegimeById($id);
        $regimeprix = RegimeService::getRegimePrixByRegimeId($id);
        $activites = RegimeService::getActiviteByRegimeId($id);
        if (!$regime) {
            if ($this->request->isAJAX() || str_contains($this->request->getHeaderLine('Accept'), 'application/json')) {
                return $this->response->setStatusCode(404)->setJSON(['message' => 'Régime introuvable.']);
            }
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $accept = $this->request->getHeaderLine('Accept');
        if ($this->request->isAJAX() || str_contains($accept, 'application/json')) {
            return $this->response->setJSON($regime);
        }

        $userId = session()->get('user_id');
        $wallet = null;
        if ($userId) {
            $portefeuilleModel = new Portefeuille();
            $wallet = $portefeuilleModel->where('utilisateur_id', $userId)->first();
        }

        return view('regime/detail', [
            'regime' => $regime,
            'prix' => $regimeprix,
            'activites' => $activites,
            'wallet' => $wallet,
            'message' => 'ok'
        ]);
    }

    public function store()
    {
        $data = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'pct_viande' => $this->request->getPost('pct_viande'),
            'pct_volaille' => $this->request->getPost('pct_volaille'),
            'pct_poisson' => $this->request->getPost('pct_poisson'),
            'variation_poids_kg' => $this->request->getPost('variation_poids_kg'),
            'duree_jours' => $this->request->getPost('duree_jours'),
        ];

        try {
            RegimeService::createRegime($data);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => 'Régime créé avec succès.']);
            }
            return redirect()->to('/regime/admin')->with('message', 'Régime créé avec succès.');
        } catch (\Throwable $th) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $th->getMessage()]);
            }
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }

    }

    public function edit($id)
    {
        $regime = $this->regimeModel->find($id);
        if (!$regime) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return view('regime/admin_edit', ['regime' => $regime]);
    }

    public function update($id)
    {
        $data = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'pct_viande' => $this->request->getPost('pct_viande'),
            'pct_volaille' => $this->request->getPost('pct_volaille'),
            'pct_poisson' => $this->request->getPost('pct_poisson'),
            'variation_poids_kg' => $this->request->getPost('variation_poids_kg'),
            'duree_jours' => $this->request->getPost('duree_jours'),
        ];

        try {
            RegimeService::updateRegime($id, $data);
            return redirect()->to('/regime/admin')->with('message', 'Régime mis à jour avec succès.');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function souscrire()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/connexion')->with('error', 'Veuillez vous connecter.');
        }

        $regimePrixId = $this->request->getPost('regime_prix_id');
        if (!$regimePrixId || !is_numeric($regimePrixId)) {
            return redirect()->back()->with('error', 'ID de tarif invalide.');
        }

        try {
            $result = RegimeService::souscrireRegime($userId, $regimePrixId);
            return redirect()->to('/dashboard')->with('success', $result['message']);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            RegimeService::deleteRegime($id);
            return redirect()->to('/regime/admin')->with('message', 'Régime supprimé avec succès.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
