<?php

namespace App\Services;

class DataAnalysisService
{
    private $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getGlobalStats(): array
    {
        $totalUsers = $this->db->table('utilisateur')->countAllResults();
        $totalRegimes = $this->db->table('regime')->countAllResults();
        $totalSubscriptions = $this->db->table('souscription_regime')->countAllResults();
        $goldRevenue = $this->db->table('souscription_regime')
            ->select('SUM(prix_paye) as total')
            ->where('remise_appliquee', 15.00)
            ->get()
            ->getRowArray();
        $goldRevenue = $goldRevenue['total'] ?? 0;

        return [
            'total_users'        => $totalUsers,
            'total_regimes'      => $totalRegimes,
            'total_subscriptions'=> $totalSubscriptions,
            'gold_revenue'       => round($goldRevenue, 2)
        ];
    }

    public function getObjectifDistribution(): array
    {
        $builder = $this->db->table('utilisateur');
        $builder->select('objectif, COUNT(*) as total');
        $builder->groupBy('objectif');
        $result = $builder->get()->getResultArray();

        $labels = [];
        $values = [];
        foreach ($result as $row) {
            $label = match($row['objectif']) {
                'augmenter_poids' => 'Prendre du poids',
                'reduire_poids'   => 'Perdre du poids',
                'imc_ideal'       => 'Atteindre IMC idéal',
                default           => 'Non défini'
            };
            $labels[] = $label;
            $values[] = (int) $row['total'];
        }
        return ['labels' => $labels, 'values' => $values];
    }

    public function getTopRegimes(): array
    {
        $builder = $this->db->table('souscription_regime sr');
        $builder->select('r.nom, COUNT(*) as total');
        $builder->join('regime_prix rp', 'rp.id = sr.regime_prix_id');
        $builder->join('regime r', 'r.id = rp.regime_id');
        $builder->groupBy('r.id');
        $builder->orderBy('total', 'DESC');
        $builder->limit(5);
        $result = $builder->get()->getResultArray();

        $labels = [];
        $values = [];
        foreach ($result as $row) {
            $labels[] = $row['nom'];
            $values[] = (int) $row['total'];
        }
        return ['labels' => $labels, 'values' => $values];
    }

    public function getIMCDistribution(): array
    {
        $userModel = new \App\Models\Utilisateur();
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

    public function getInscriptionsTrend(): array
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
}