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
        
        // Si l'utilisateur n'existe pas, détruire session
        if (!$user) {
            session()->destroy();
            return redirect()->to('/connexion');
        }

        $objective = $this->dashboardService->getUserObjective($userId);
        $imcData = $this->dashboardService->getUserImcData($userId);
        $suggestions = $this->dashboardService->getUserSuggestions($userId);
        $wallet = $this->dashboardService->getWallet($userId);
        $subscription = $this->dashboardService->getUserGoldSubscription($userId);

        // Données pour KPI (garder si nécessaire)
        $kpi_users = $this->dashboardService->getTotalUsers();
        $kpi_regimes = $this->dashboardService->getActiveRegimes();
        $kpi_codes = $this->dashboardService->getValidCodesCount();
        $kpi_gold = $this->dashboardService->getUserGoldRevenue();
        $kpi_users_trend = $this->dashboardService->getUserTrend();
        $chart_inscriptions = $this->dashboardService->getInscriptionsParMois();
        $chart_imc = $this->dashboardService->getRepartitionIMC();
        $recent_regimes = $this->dashboardService->getRecentRegimes(true, true);
        $recent_activity = $this->dashboardService->getUserRecentActivity($userId);

        return view('dashboard/user/index', [
            'user'               => $user,
            'imc'                => $imcData['imc'],
            'objective'          => $objective,
            'categorie_imc'      => $imcData['categorie_imc'],
            'suggestions'        => $suggestions,
            'streak_days'        => $user['streak_days'] ?? 0,
            'total_days'         => $user['total_days'] ?? 0,
            'subscription'       => $subscription,
            'wallet'             => $wallet ?? null,
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
}