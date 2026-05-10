<?php

namespace App\Controllers;

use App\Models\Utilisateur;
use App\Models\HistoriquePoids;
use App\Models\SouscriptionRegime;

class StatsController extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/connexion');
        }

        $userId = session()->get('user_id');
        $userModel = new Utilisateur();
        $user = $userModel->find($userId);

        // 1. Calcul IMC actuel
        $imc = null;
        $categorieImc = null;
        if ($user && !empty($user['taille_cm']) && !empty($user['poids_kg'])) {
            $imc = $userModel->calculerIMC($user['taille_cm'], $user['poids_kg']);
            $categorieImc = $userModel->categorieIMC($imc);
        }

        // 2. Historique des poids (pour le graphique)
        $poidsModel = new HistoriquePoids();
        $weightHistory = $poidsModel
            ->where('utilisateur_id', $userId)
            ->orderBy('mesure_le', 'ASC')
            ->findAll();

        $poidsLabels = [];
        $poidsValues = [];
        foreach ($weightHistory as $w) {
            $poidsLabels[] = date('d/m/Y', strtotime($w['mesure_le']));
            $poidsValues[] = (float) $w['poids_kg'];
        }

        // Si peu de données, on ajoute le poids actuel si différent
        if (count($weightHistory) == 0 && !empty($user['poids_kg'])) {
            $poidsLabels[] = 'Actuel';
            $poidsValues[] = (float) $user['poids_kg'];
        }

        // 3. Nombre de régimes souscrits
        $souscriptionModel = new SouscriptionRegime();
        $nbRegimes = $souscriptionModel->where('utilisateur_id', $userId)->countAllResults();
        $regimeActif = $souscriptionModel
            ->where('utilisateur_id', $userId)
            ->where('statut', 'actif')
            ->orderBy('created_at', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        // 4. Données pour le graphique d'objectif (ex: progression estimée)
        // (fictif mais peut être amélioré)
        $objectifData = $this->getObjectifProgression($user);

        return view('dashboard/stats', [
            'user'            => $user,
            'imc'             => $imc,
            'categorie_imc'   => $categorieImc,
            'poids_labels'    => json_encode($poidsLabels),
            'poids_values'    => json_encode($poidsValues),
            'nb_regimes'      => $nbRegimes,
            'regime_actif'    => $regimeActif,
            'objectif_data'   => $objectifData,
        ]);
    }

    private function getObjectifProgression($user)
    {
        // Exemple : si l'objectif est de perdre du poids, on calcule la progression
        $objectif = $user['objectif'] ?? null;
        $taille = $user['taille_cm'] ?? null;
        $poidsActuel = $user['poids_kg'] ?? null;

        if (!$objectif || !$taille || !$poidsActuel) {
            return ['labels' => [], 'values' => []];
        }

        $tailleM = $taille / 100;
        $imcActuel = $poidsActuel / ($tailleM * $tailleM);

        if ($objectif === 'reduire_poids') {
            $imcCible = 22; // IMC idéal pour la perte (exemple)
            $label = 'Perte de poids';
            $actuel = $imcActuel;
            $cible = $imcCible;
        } elseif ($objectif === 'augmenter_poids') {
            $imcCible = 24;
            $label = 'Gain de poids';
            $actuel = $imcActuel;
            $cible = $imcCible;
        } else { // imc_ideal
            $imcCible = 22;
            $label = 'IMC idéal';
            $actuel = $imcActuel;
            $cible = $imcCible;
        }

        return [
            'label'  => $label,
            'actuel' => round($actuel, 1),
            'cible'  => $cible,
            'pourcentage' => $actuel >= $cible ? 100 : round(($actuel / $cible) * 100, 1)
        ];
    }
}