<?php

namespace App\Controllers;

use App\Models\Abonnement;
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
        $data['active'] = 'regimes';

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

    public function stats()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $data['active'] = 'admin-stats';
        $data['chart_inscriptions'] = $this->dashboardService->getMonthlyInscriptions();
        $data['chart_imc'] = $this->dashboardService->getIMCDistribution();
        $data['chart_objectifs'] = (new \App\Services\DataAnalysisService())->getObjectifDistribution();
        $data['chart_top_regimes'] = (new \App\Services\DataAnalysisService())->getTopRegimes();
        $data['global_stats'] = (new \App\Services\DataAnalysisService())->getGlobalStats();
        $data['inscriptions_trend'] = (new \App\Services\DataAnalysisService())->getInscriptionsTrend();

        return view('dashboard/stats', $data);
    }

    public function abonnements()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $abonnementModel = new Abonnement();
        $data['abonnements'] = $abonnementModel->orderBy('created_at', 'DESC')->findAll();

        return view('dashboard/abonnements', $data);
    }

    public function storeAbonnement()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $abonnementModel = new Abonnement();
        $abonnementModel->insert([
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

        $abonnementModel = new Abonnement();
        $abonnementModel->update($id, [
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

        $abonnementModel = new Abonnement();
        $abonnementModel->delete($id);

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
