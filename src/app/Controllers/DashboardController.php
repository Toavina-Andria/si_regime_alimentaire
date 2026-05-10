<?php

namespace App\Controllers;

use App\Services\DashboardService;

class DashboardController extends BaseController
{
    private DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    private function requireAdmin()
    {
        if (!session()->get('logged_in') || !session()->get('est_admin')) {
            return redirect()->to('/connexion')->with('error', 'Accès réservé aux administrateurs.');
        }
        return null;
    }

    public function index()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

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
        if ($redirect = $this->requireAdmin()) return $redirect;

        $data['regimes'] = $this->dashboardService->getAllRegimes();
        $data['active'] = 'regimes';

        return view('dashboard/regimes', $data);
    }

    public function codes()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $data['codes'] = $this->dashboardService->getAllCodes();

        return view('dashboard/codes', $data);
    }

    public function activites()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $data['activites'] = $this->dashboardService->getAllActivites();

        return view('dashboard/activites', $data);
    }

    public function utilisateurs()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $data['utilisateurs'] = $this->dashboardService->getAllUtilisateurs();

        return view('dashboard/utilisateurs', $data);
    }

    public function stats()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $statsData = $this->dashboardService->getStatsData();

        $data = [
            'active'             => 'admin-stats',
            'chart_inscriptions' => $this->dashboardService->getMonthlyInscriptions(),
            'chart_imc'          => $this->dashboardService->getIMCDistribution(),
            'chart_objectifs'    => $statsData['chart_objectifs'],
            'chart_top_regimes'  => $statsData['chart_top_regimes'],
            'global_stats'       => $statsData['global_stats'],
            'inscriptions_trend' => $statsData['inscriptions_trend'],
        ];

        return view('dashboard/stats', $data);
    }

    public function abonnements()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $data['abonnements'] = $this->dashboardService->getAllAbonnements();

        return view('dashboard/abonnements', $data);
    }

    public function storeAbonnement()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $this->dashboardService->createAbonnement([
            'nom'            => $this->request->getPost('nom'),
            'statut'         => $this->request->getPost('statut'),
            'taux_reduction' => $this->request->getPost('taux_reduction'),
            'prix'           => $this->request->getPost('prix'),
            'description'    => $this->request->getPost('description'),
        ]);

        return redirect()->to('/admin/abonnements')->with('success', 'Abonnement créé avec succès.');
    }

    public function updateAbonnement($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $this->dashboardService->updateAbonnement($id, [
            'nom'            => $this->request->getPost('nom'),
            'statut'         => $this->request->getPost('statut'),
            'taux_reduction' => $this->request->getPost('taux_reduction'),
            'prix'           => $this->request->getPost('prix'),
            'description'    => $this->request->getPost('description'),
        ]);

        return redirect()->to('/admin/abonnements')->with('success', 'Abonnement mis à jour.');
    }

    public function deleteAbonnement($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $this->dashboardService->deleteAbonnement($id);

        return redirect()->to('/admin/abonnements')->with('success', 'Abonnement supprimé.');
    }

    public function parametres()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        return view('dashboard/parametres');
    }
}
