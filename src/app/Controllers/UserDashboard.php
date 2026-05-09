<?php

namespace App\Controllers;

use App\Services\DashboardService;

class UserDashboard extends BaseController
{
    private DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/connexion');
        }

        $userId = session()->get('user_id');
        $user = $this->dashboardService->getUserById($userId);
        $imcData = $this->dashboardService->getUserImcData($userId);
        $suggestions = $this->dashboardService->getUserSuggestions($userId);
        $kpi_users = $this->dashboardService->getTotalUsers();
        $kpi_regimes = $this->dashboardService->getActiveRegimes();
        $kpi_codes = $this->dashboardService->getValidCodesCount();
        $kpi_gold = $this->dashboardService->getUserGoldRevenue();
        $kpi_users_trend = $this->dashboardService->getUserTrend();
        $chart_inscriptions = $this->dashboardService->getInscriptionsParMois();
        $chart_imc = $this->dashboardService->getRepartitionIMC();
        $recent_regimes = $this->dashboardService->getRecentRegimes(true, true);
        $recent_activity = $this->getRecentActivity($userId);

        return view('dashboard/index', [
            'user'               => $user,
            'imc'                => $imcData['imc'],
            'categorie_imc'      => $imcData['categorie_imc'],
            'suggestions'        => $suggestions,
            'kpi_users'          => $kpi_users,
            'kpi_users_trend'    => $kpi_users_trend,
            'kpi_regimes'        => $kpi_regimes,
            'kpi_codes'          => $kpi_codes,
            'kpi_gold'           => $kpi_gold,
            'chart_inscriptions' => $chart_inscriptions,
            'chart_imc'          => $chart_imc,
            'recent_regimes'     => $recent_regimes,
            'recent_activity'    => $recent_activity,
        ]);
    }

    private function getRecentActivity($userId)
    {
        return $this->dashboardService->getUserRecentActivity($userId);
    }
}