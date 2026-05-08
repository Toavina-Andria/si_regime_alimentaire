<?php

namespace App\Controllers;

use App\Services\DataAnalysisService;

class DataAnalysisController extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/connexion');
        }

        $service = new DataAnalysisService();
        $data = [
            'objectif_dist' => $service->getObjectifDistribution(),
            'top_regimes'   => $service->getTopRegimes(),
            'imc_dist'      => $service->getIMCDistribution(),
            'inscriptions'  => $service->getInscriptionsTrend(),
            'global_stats'  => $service->getGlobalStats(),
        ];

        return view('dashboard/analysis', $data);
    }
}