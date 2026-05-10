<?php

namespace App\Controllers;

use App\Services\DataAnalysisService;

class DataAnalysisController extends BaseController
{
    private DataAnalysisService $analysisService;

    public function __construct()
    {
        $this->analysisService = new DataAnalysisService();
    }

    public function index()
    {
        // Vérifier que l'utilisateur est connecté
        if (!session()->get('logged_in')) {
            return redirect()->to('/connexion');
        }

        $data = [
            'global_stats'      => $this->analysisService->getGlobalStats(),
            'objectif_dist'     => $this->analysisService->getObjectifDistribution(),
            'top_regimes'       => $this->analysisService->getTopRegimes(),
            'imc_dist'          => $this->analysisService->getIMCDistribution(),
            'inscriptions'      => $this->analysisService->getInscriptionsTrend(),
        ];

        return view('dashboard/analysis', $data);
    }
}