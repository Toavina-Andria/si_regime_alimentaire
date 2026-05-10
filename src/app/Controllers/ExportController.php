<?php

namespace App\Controllers;

use App\Services\ExportPDF;
use App\Services\AbonnementServices;
use App\Models\Utilisateur;

class ExportController extends BaseController
{
    public function bilan()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/connexion');
        }

        $userId = session()->get('user_id');
        $userModel = new Utilisateur();
        $user = $userModel->find($userId);

        // Calcul IMC
        $imc = null;
        $categorie = null;
        if ($user && !empty($user['taille_cm']) && !empty($user['poids_kg'])) {
            $imc = $userModel->calculerIMC($user['taille_cm'], $user['poids_kg']);
            $categorie = $userModel->categorieIMC($imc);
        }

        // Abonnement actuel
        $abonnement = AbonnementServices::getUserActiveAbonnementWithDetails($userId);

        $stats = [
            'imc'        => $imc,
            'categorie'  => $categorie,
            'objectif'   => $user['objectif'] ?? null,
            'abonnement' => $abonnement,
        ];

        $exportService = new ExportPDF();
        $exportService->exportBilanPersonnel($user, $stats, 'bilan_' . $user['id'] . '.pdf');
    }
}