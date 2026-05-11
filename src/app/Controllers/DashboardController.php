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

    private function requireAdmin()
    {
        if (!session()->get('logged_in') || !session()->get('est_admin')) {
            return redirect()->to('/dashboard');
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
            'total_revenue'      => $this->dashboardService->getTotalRevenue(),
            'gold_revenue'       => $this->dashboardService->getGoldRevenue(),
            'standard_revenue'   => $this->dashboardService->getStandardRevenue(),
            'chart_inscriptions' => $this->dashboardService->getMonthlyInscriptions(),
            'chart_imc'          => $this->dashboardService->getIMCDistribution(),
            'recent_regimes'     => $this->dashboardService->getRecentRegimes(),
            'recent_activity'    => $this->dashboardService->getRecentActivity(),
        ];

        return view('dashboard/index', $data);
    }

    public function stats()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $data = $this->dashboardService->getStatsData();
        $data['active'] = 'stats';

        return view('dashboard/stats', $data);
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

    public function storeActivite()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $rules = [
            'nom'            => 'required|min_length[2]|max_length[255]',
            'description'    => 'permit_empty',
            'intensite'      => 'required|in_list[1,2,3]',
            'calories_heure' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nom'            => $this->request->getPost('nom'),
            'description'    => $this->request->getPost('description'),
            'intensite'      => $this->request->getPost('intensite'),
            'calories_heure' => $this->request->getPost('calories_heure'),
        ];

        if ($this->dashboardService->createActivite($data)) {
            return redirect()->to('/admin/activites')->with('message', 'Activité ajoutée avec succès.');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de l\'ajout de l\'activité.');
    }

    public function deleteActivite($id)
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $this->dashboardService->deleteActivite($id);
        return redirect()->to('/admin/activites')->with('message', 'Activité supprimée avec succès.');
    }

    public function updateActivite($id)
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $data = [
            'nom'            => $this->request->getPost('nom'),
            'description'    => $this->request->getPost('description'),
            'intensite'      => $this->request->getPost('intensite'),
            'calories_heure' => $this->request->getPost('calories_heure'),
        ];

        $this->dashboardService->updateActivite($id, $data);
        return redirect()->to('/admin/activites')->with('message', 'Activité mise à jour avec succès.');
    }

    public function storeCode()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $data = [
            'code'          => $this->request->getPost('code'),
            'valeur_points' => $this->request->getPost('valeur_points'),
            'est_valide'    => 1,
            'expires_at'    => $this->request->getPost('expires_at') ?: null,
        ];

        $this->dashboardService->createCode($data);
        return redirect()->to('/admin/codes')->with('message', 'Code bonus créé avec succès.');
    }

    public function updateCode($id)
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $data = [
            'code'          => $this->request->getPost('code'),
            'valeur_points' => $this->request->getPost('valeur_points'),
            'est_valide'    => $this->request->getPost('est_valide') ? 1 : 0,
            'expires_at'    => $this->request->getPost('expires_at') ?: null,
        ];

        $this->dashboardService->updateCode($id, $data);
        return redirect()->to('/admin/codes')->with('message', 'Code bonus mis à jour.');
    }

    public function deleteCode($id)
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $this->dashboardService->deleteCode($id);
        return redirect()->to('/admin/codes')->with('message', 'Code bonus supprimé.');
    }

    public function utilisateurs()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $data['utilisateurs'] = $this->dashboardService->getAllUtilisateurs();

        return view('dashboard/utilisateurs', $data);
    }

    public function updateUtilisateur($id)
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $data = [
            'est_admin' => $this->request->getPost('est_admin') ? 1 : 0,
        ];

        $this->dashboardService->updateUtilisateur($id, $data);
        return redirect()->to('/admin/utilisateurs')->with('message', 'Utilisateur mis à jour.');
    }

    public function deleteUtilisateur($id)
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $this->dashboardService->deleteUtilisateur($id);
        return redirect()->to('/admin/utilisateurs')->with('message', 'Utilisateur supprimé.');
    }

    public function abonnements()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $data['abonnements'] = $this->dashboardService->getAllAbonnements();

        return view('dashboard/abonnements', $data);
    }

    public function storeAbonnement()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $data = [
            'nom'             => $this->request->getPost('nom'),
            'statut'          => $this->request->getPost('statut'),
            'taux_reduction'  => $this->request->getPost('taux_reduction'),
            'prix'            => $this->request->getPost('prix'),
            'description'     => $this->request->getPost('description'),
        ];

        $this->dashboardService->createAbonnement($data);

        return redirect()->to('/admin/abonnements')->with('success', 'Abonnement créé avec succès.');
    }

    public function updateAbonnement($id)
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $data = [
            'nom'             => $this->request->getPost('nom'),
            'statut'          => $this->request->getPost('statut'),
            'taux_reduction'  => $this->request->getPost('taux_reduction'),
            'prix'            => $this->request->getPost('prix'),
            'description'     => $this->request->getPost('description'),
        ];

        $this->dashboardService->updateAbonnement($id, $data);

        return redirect()->to('/admin/abonnements')->with('success', 'Abonnement mis à jour.');
    }

    public function deleteAbonnement($id)
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $this->dashboardService->deleteAbonnement($id);

        return redirect()->to('/admin/abonnements')->with('success', 'Abonnement supprimé.');
    }

}
