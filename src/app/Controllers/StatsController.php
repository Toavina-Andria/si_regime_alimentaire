<?php

namespace App\Controllers;

use App\Models\Utilisateur;
use App\Models\HistoriquePoids;
use App\Models\SouscriptionRegime;

class StatsController extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/connexion');
        }

        $userId = session()->get('user_id');
        $userModel = new Utilisateur();
        $user = $userModel->find($userId);

        $imc = null;
        $categorieImc = null;
        if ($user && !empty($user['taille_cm']) && !empty($user['poids_kg'])) {
            $imc = $userModel->calculerIMC($user['taille_cm'], $user['poids_kg']);
            $categorieImc = $userModel->categorieIMC($imc);
        }

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

        if (count($weightHistory) == 0 && !empty($user['poids_kg'])) {
            $poidsLabels[] = 'Actuel';
            $poidsValues[] = (float) $user['poids_kg'];
        }

        $souscriptionModel = new SouscriptionRegime();
        $nbRegimes = $souscriptionModel->where('utilisateur_id', $userId)->countAllResults();
        $regimeActif = $souscriptionModel
            ->where('utilisateur_id', $userId)
            ->where('statut', 'actif')
            ->orderBy('created_at', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

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

    public function storeWeight()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/connexion');
        }

        $userId = session()->get('user_id');
        $poids = $this->request->getPost('poids_kg');

        if (!$poids || !is_numeric($poids) || $poids <= 0) {
            return redirect()->back()->with('error', 'Veuillez entrer un poids valide.');
        }

        $poids = round((float) $poids, 2);

        $historique = new HistoriquePoids();
        $historique->insert([
            'utilisateur_id' => $userId,
            'poids_kg'       => $poids,
            'mesure_le'      => date('Y-m-d'),
        ]);

        $userModel = new Utilisateur();
        $userModel->skipValidation(true)->update($userId, ['poids_kg' => $poids]);

        return redirect()->back()->with('success', 'Poids du jour enregistré.');
    }

    private function getObjectifProgression($user)
    {

        $objectif = $user['objectif'] ?? null;
        $taille = $user['taille_cm'] ?? null;
        $poidsActuel = $user['poids_kg'] ?? null;

        if (!$objectif || !$taille || !$poidsActuel) {
            return ['labels' => [], 'values' => []];
        }

        $tailleM = $taille / 100;
        $imcActuel = $poidsActuel / ($tailleM * $tailleM);

        if ($objectif === 'reduire_poids') {
            $imcCible = 22;
            $label = 'Perte de poids';
            $actuel = $imcActuel;
            $cible = $imcCible;
        } elseif ($objectif === 'augmenter_poids') {
            $imcCible = 24;
            $label = 'Gain de poids';
            $actuel = $imcActuel;
            $cible = $imcCible;
        } else {
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