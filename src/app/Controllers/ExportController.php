<?php

namespace App\Controllers;

use App\Services\ExportPDF;
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
        $db = \Config\Database::connect();
        $abonnement = $db->table('utilisateur_abonnement ua')
            ->select('a.nom, a.statut')
            ->join('abonnement a', 'a.id = ua.abonnement_id')
            ->where('ua.utilisateur_id', $userId)
            ->where('ua.statut', 'actif')
            ->orderBy('ua.created_at', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

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