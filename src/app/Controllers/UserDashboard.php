<?php

namespace App\Controllers;

use App\Models\Utilisateur;
use App\Models\Regime;
use App\Models\SouscriptionRegime;
use App\Models\CodeBonus;

class UserDashboard extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/connexion');
        }

        $userId = session()->get('user_id');
        $userModel = new Utilisateur();
        $user = $userModel->find($userId);

        // Calcul IMC et catégorie
        $imc = null;
        $categorieImc = null;
        if ($user && !empty($user['taille_cm']) && !empty($user['poids_kg'])) {
            $imc = $userModel->calculerIMC($user['taille_cm'], $user['poids_kg']);
            $categorieImc = $userModel->categorieIMC($imc);
        }

        // KPI
        $kpi_users = $userModel->countAll();
        $kpi_regimes = (new Regime())->countAll();
        $kpi_codes = (new CodeBonus())->where('est_valide', 1)->countAllResults();
        
        // Revenus Gold (remise 15%)
        $goldRevenue = $this->db->table('souscription_regime')
            ->select('SUM(prix_paye) as total')
            ->where('remise_appliquee', 15.00)
            ->get()
            ->getRowArray();
        $kpi_gold = $goldRevenue['total'] ?? 0;

        // Graphiques
        $chart_inscriptions = $this->getInscriptionsParMois();
        $chart_imc = $this->getRepartitionIMC();

        // Derniers régimes
        $recent_regimes = (new Regime())
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();
        foreach ($recent_regimes as &$r) {
            $r['duree_display'] = $r['duree_jours'] . ' jours';
            // Prix fictif (à adapter)
            $r['prix'] = number_format(rand(29, 99), 2);
        }

        // Activité récente
        $recent_activity = $this->getRecentActivity($userId);
        $kpi_users_trend = 12; // tendance fictive

        return view('dashboard/index', [
            'user'               => $user,
            'imc'                => $imc,
            'categorie_imc'      => $categorieImc,
            'kpi_users'          => $kpi_users,
            'kpi_regimes'        => $kpi_regimes,
            'kpi_codes'          => $kpi_codes,
            'kpi_gold'           => $kpi_gold,
            'kpi_users_trend'    => $kpi_users_trend,
            'chart_inscriptions' => $chart_inscriptions,
            'chart_imc'          => $chart_imc,
            'recent_regimes'     => $recent_regimes,
            'recent_activity'    => $recent_activity,
        ]);
    }

    private function getInscriptionsParMois()
    {
        $builder = $this->db->table('utilisateur')
            ->select("DATE_FORMAT(created_at, '%Y-%m') as mois, COUNT(*) as total")
            ->groupBy('mois')
            ->orderBy('mois', 'DESC')
            ->limit(12);
        $result = $builder->get()->getResultArray();
        $result = array_reverse($result);

        $labels = [];
        $values = [];
        foreach ($result as $row) {
            $labels[] = date('M Y', strtotime($row['mois'] . '-01'));
            $values[] = (int) $row['total'];
        }
        return ['labels' => $labels, 'values' => $values];
    }

    private function getRepartitionIMC()
    {
        $userModel = new Utilisateur();
        $users = $userModel->findAll();
        $categories = [
            'Poids insuffisant' => 0,
            'Poids normal'      => 0,
            'Surpoids'          => 0,
            'Obésité'           => 0
        ];
        foreach ($users as $u) {
            if (!empty($u['taille_cm']) && !empty($u['poids_kg'])) {
                $imc = $userModel->calculerIMC($u['taille_cm'], $u['poids_kg']);
                $cat = $userModel->categorieIMC($imc);
                if (isset($categories[$cat])) $categories[$cat]++;
            }
        }
        return [
            'labels' => array_keys($categories),
            'values' => array_values($categories),
            'colors' => ['#2D6A4F', '#52B788', '#D4A853', '#B4432B']
        ];
    }

    private function getRecentActivity($userId)
    {
        $souscriptionModel = new SouscriptionRegime();
        $activities = $souscriptionModel
            ->select('souscription_regime.created_at, regime.nom as regime_nom')
            ->join('regime_prix', 'regime_prix.id = souscription_regime.regime_prix_id')
            ->join('regime', 'regime.id = regime_prix.regime_id')
            ->where('utilisateur_id', $userId)
            ->orderBy('souscription_regime.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        $result = [];
        foreach ($activities as $act) {
            $result[] = [
                'type' => 'subscription',
                'text' => "Souscription au régime « {$act['regime_nom']} »",
                'time' => date('d/m/Y H:i', strtotime($act['created_at']))
            ];
        }
        if (empty($result)) {
            $result[] = [
                'type' => 'info',
                'text' => "Aucune activité récente. Commencez un régime !",
                'time' => date('d/m/Y H:i')
            ];
        }
        return $result;
    }
}