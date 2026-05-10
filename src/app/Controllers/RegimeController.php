<?php

namespace App\Controllers;

use App\Models\Regime;
use App\Services\RegimeService;

class RegimeController extends BaseController
{
    private $regimeModel;
    private $regimeService;
    private $regimePrixModel;

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
        $this->regimeService = new RegimeService();
    }

    // Liste des régimes
    public function index()
    {
        $regimes = $this->regimeModel->orderBy('created_at', 'DESC')->paginate(10);
        return view('dashboard/regimes', [
            'regimes' => $regimes,
            'pager' => $this->regimeModel->pager,
        ]);
    }

    // Formulaire de création
    public function create()
    {
        return view('regime/admin_create');
    }


    // Détail régime (JSON pour AJAX)
    public function show($id)
    {
        $regime = RegimeService::getRegimeById($id);
        $regimeprix = RegimeService::getRegimePrixByRegimeId($id);
        $activites = RegimeService::getActiviteByRegimeId($id);
        $data = [
            'regime' => $regime,
            'prix' => $regimeprix,
            'activites' => $activites,
            'message' => 'initial'
        ];
        try {
            if (!$regime) {
                return $this->response->setStatusCode(404)->setJSON([
                    'message' => 'Régime introuvable.'
                ]);
            }

            $accept = $this->request->getHeaderLine('Accept');
            if ($this->request->isAJAX() || str_contains($accept, 'application/json')) {
                return $this->response->setJSON($regime);
            }
            $data = [
                'regime' => $regime,
                'prix' => $regimeprix,
                'activites' => $activites,
                'message' => 'ok'
            ];
            return view('regime/detail', $data);
        } catch (\Throwable $th) {
            if ($th->getMessage() !== 'Trying to access array offset on null' )
            {
                $data['message'] = $th->getMessage();
            }

            return view('regime/detail',$data);
        }
    }

    // Enregistrement
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


        // Create and handle result (returns array with success/errors)
        try {

            RegimeService::createRegime($data);

            $accept = $this->request->getHeaderLine('Accept');
            if ($this->request->isAJAX() || str_contains($accept, 'application/json')) {
                return $this->response->setJSON(['success' => true, 'message' => 'Régime créé avec succès.']);
            }


            return redirect()->to('/regime/admin')->with('message', 'Régime créé avec succès.');
        } catch (\Throwable $th) {
            // send to json if ajax
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Erreur lors de la création du régime: ' . $th->getMessage()]);
            }
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
        $data = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'pct_viande' => $this->request->getPost('pct_viande'),
            'pct_volaille' => $this->request->getPost('pct_volaille'),
            'pct_poisson' => $this->request->getPost('pct_poisson'),
            'variation_poids_kg' => $this->request->getPost('variation_poids_kg'),
            'duree_jours' => $this->request->getPost('duree_jours'),
        ];

        $result = RegimeService::updateRegime($id, $data);

        if ($result['success']) {
            return redirect()->to('/regime/admin')->with('message', $result['message']);
        } else {
            $errors = $result['errors'] ?? [];
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', $result['message']);
        }
    }

    // Suppression
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
