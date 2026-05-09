<?php

namespace App\Controllers;

use App\Models\ActiviteSportive;
use App\Models\CodeBonus;
use App\Models\Regime;
use App\Models\Utilisateur;
use App\Services\DashboardService;

class DashboardController extends BaseController
{
    private DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index()
    {
        $data = [
            'imc'                => null,
            'categorie_imc'      => null,
            'kpi_users'          => $this->dashboardService->getTotalUsers(),
            'kpi_users_trend'    => $this->dashboardService->getUserTrend(),
            'kpi_regimes'        => $this->dashboardService->getActiveRegimes(),
            'kpi_codes'          => $this->dashboardService->getCodesThisMonth(),
            'kpi_gold'           => $this->dashboardService->getGoldRevenue(),
            'chart_inscriptions' => $this->dashboardService->getMonthlyInscriptions(),
            'chart_imc'          => $this->dashboardService->getIMCDistribution(),
            'recent_regimes'     => $this->dashboardService->getRecentRegimes(),
            'recent_activity'    => $this->dashboardService->getRecentActivity(),
        ];

        return view('dashboard/index', $data);
    }

    public function regimes()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $regimeModel = new Regime();
        $data['regimes'] = $regimeModel->orderBy('created_at', 'DESC')->findAll();

        return view('dashboard/regimes', $data);
    }

    public function codes()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $codeBonusModel = new CodeBonus();
        $data['codes'] = $codeBonusModel->orderBy('created_at', 'DESC')->findAll();

        return view('dashboard/codes', $data);
    }

    public function activites()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $activiteModel = new ActiviteSportive();
        $data['activites'] = $activiteModel->orderBy('created_at', 'DESC')->findAll();

        return view('dashboard/activites', $data);
    }

    public function utilisateurs()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $utilisateurModel = new Utilisateur();
        $data['utilisateurs'] = $utilisateurModel->orderBy('created_at', 'DESC')->findAll();

        return view('dashboard/utilisateurs', $data);
    }
}
