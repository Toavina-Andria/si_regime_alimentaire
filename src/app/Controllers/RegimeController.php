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

        $regimeModel = new Regime();
        $regimes = $regimeModel->orderBy('created_at', 'DESC')->findAll();

        $db = \Config\Database::connect();
        foreach ($regimes as &$r) {
            $prixMin = $db->table('regime_prix')
                ->select('prix_base, duree_jours')
                ->where('regime_id', $r['id'])
                ->orderBy('prix_base', 'ASC')
                ->limit(1)
                ->get()
                ->getRowArray();
            $r['prix_depart'] = $prixMin ? $prixMin['prix_base'] : null;
            $r['duree_min'] = $prixMin ? $prixMin['duree_jours'] : null;
        }

        return view('regime/list', ['regimes' => $regimes]);
    }

    public function show($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/connexion');
        }

        $regimeModel = new Regime();
        $regime = $regimeModel->find($id);

        if (!$regime) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $regimePrix = RegimeService::getRegimePrixByRegimeId($id);

        $db = \Config\Database::connect();
        $activites = $db->table('regime_activite ra')
            ->select('a.nom, a.description, a.intensite, a.calories_heure, ra.frequence_semaine')
            ->join('activite_sportive a', 'a.id = ra.activite_id')
            ->where('ra.regime_id', $id)
            ->orderBy('a.intensite', 'ASC')
            ->get()
            ->getResultArray();

        return view('regime/detail', [
            'regime'    => $regime,
            'prix'      => $regimePrix,
            'activites' => $activites,
        ]);
    }
}