<?php

namespace App\Services;

use App\Models\Utilisateur;
use App\Models\Regime;
use App\Models\SouscriptionRegime;
use App\Models\CodeBonus;
use App\Models\Portefeuille;
use App\Models\RegimePrix;
use App\Models\Abonnement;
use App\Models\ActiviteSportive;
use App\Services\DataAnalysisService;

class DashboardService
{
    private $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // ============================================================
    // Méthodes pour l'utilisateur connecté (Mon espace / UserDashboard)
    // ============================================================
    public function getUserById(int $userId): ?array
    {
        $userModel = new Utilisateur();
        $user = $userModel->find($userId);
        if (!$user) return null;

        $user['streak_days'] = $this->getStreakDays($userId);
        $user['total_days']  = $this->getTotalDays($userId);
        return $user;
    }

    public function getUserObjective(int $userId): ?string
    {
        $user = $this->getUserById($userId);
        return $user['objectif'] ?? null;
    }

    public function getUserImcData(int $userId): array
    {
        $user = $this->getUserById($userId);
        $imc = null;
        $categorieImc = null;
        if ($user && !empty($user['taille_cm']) && !empty($user['poids_kg'])) {
            $userModel = new Utilisateur();
            $imc = $userModel->calculerIMC($user['taille_cm'], $user['poids_kg']);
            $categorieImc = $userModel->categorieIMC($imc);
        }
        return ['imc' => $imc, 'categorie_imc' => $categorieImc];
    }

    public function getUserGoldSubscription(int $userId): ?array
    {
        $builder = $this->db->table('utilisateur_abonnement ua');
        $builder->select('a.nom, a.statut, a.taux_reduction, a.prix, ua.date_debut, ua.date_fin');
        $builder->join('abonnement a', 'a.id = ua.abonnement_id');
        $builder->where('ua.utilisateur_id', $userId);
        $builder->where('ua.statut', 'actif');
        $builder->orderBy('ua.date_debut', 'DESC');
        $builder->limit(1);
        return $builder->get()->getRowArray();
    }

    public function getWallet(int $userId): ?array
    {
        return (new Portefeuille())->where('utilisateur_id', $userId)->first();
    }

    public function getUserSuggestions(int $userId): array
    {
        $user = $this->getUserById($userId);
        if (!$user || empty($user['objectif'])) return [];

        $objectif = $user['objectif'];
        switch ($objectif) {
            case 'augmenter_poids':
                $regimes = (new Regime())->where('variation_poids_kg >', 0)->orderBy('variation_poids_kg', 'DESC')->findAll();
                break;
            case 'reduire_poids':
                $regimes = (new Regime())->where('variation_poids_kg <', 0)->orderBy('variation_poids_kg', 'ASC')->findAll();
                break;
            default:
                $regimes = (new Regime())->where('variation_poids_kg >=', -0.5)->where('variation_poids_kg <=', 0.5)->findAll();
                break;
        }

        $suggestions = [];
        foreach ($regimes as $regime) {
            $prixOptions = (new RegimePrix())->where('regime_id', $regime['id'])->orderBy('duree_jours', 'ASC')->findAll();
            $prixFormatted = [];
            foreach ($prixOptions as $p) {
                $prixFormatted[] = [
                    'duree_jours' => $p['duree_jours'],
                    'prix_base'   => (float) $p['prix_base']
                ];
            }

            $activites = $this->db->table('regime_activite ra')
                ->select('a.nom, a.intensite, a.calories_heure, ra.frequence_semaine')
                ->join('activite_sportive a', 'a.id = ra.activite_id')
                ->where('ra.regime_id', $regime['id'])
                ->get()
                ->getResultArray();

            $suggestions[] = [
                'regime'      => $regime,
                'prixOptions' => $prixFormatted,
                'activites'   => $activites
            ];
            if (count($suggestions) >= 3) break;
        }
        return $suggestions;
    }

    public function getUserRecentActivity(int $userId): array
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

    // ============================================================
    // Méthodes pour les statistiques globales (DashboardController admin)
    // ============================================================
    public function getTotalUsers(): int
    {
        return (new Utilisateur())->countAllResults();
    }

    public function getActiveRegimes(): int
    {
        return (new Regime())->countAllResults();
    }

    public function getValidCodesCount(): int
    {
        return (new CodeBonus())->where('est_valide', 1)->countAllResults();
    }

    public function getUserGoldRevenue(): float
    {
        $goldRevenue = $this->db->table('souscription_regime')
            ->select('SUM(prix_paye) as total')
            ->where('remise_appliquee', 15.00)
            ->get()
            ->getRowArray();
        return round($goldRevenue['total'] ?? 0, 2);
    }

    public function getUserTrend(): int
    {
        $lastMonth = $this->db->table('utilisateur')
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->countAllResults();

        $previousMonth = $this->db->table('utilisateur')
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-60 days')))
            ->where('created_at <', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->countAllResults();

        if ($previousMonth > 0) {
            return round((($lastMonth - $previousMonth) / $previousMonth) * 100);
        }
        return $lastMonth > 0 ? 100 : 0;
    }

    public function getInscriptionsParMois(): array
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

    public function getRepartitionIMC(): array
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

    public function getRecentRegimes(bool $withPrix = true, bool $withDurationDisplay = true): array
    {
        $regimes = (new Regime())->orderBy('created_at', 'DESC')->limit(5)->findAll();
        foreach ($regimes as &$r) {
            if ($withDurationDisplay) {
                $r['duree_display'] = $r['duree_jours'] . ' jours';
            }
            if ($withPrix) {
                $prixRow = (new RegimePrix())->where('regime_id', $r['id'])->orderBy('prix_base', 'ASC')->first();
                $r['prix'] = $prixRow ? number_format($prixRow['prix_base'], 2) : '—';
            }
        }
        return $regimes;
    }

    // ============================================================
    // Méthodes spécifiques pour DashboardController (admin)
    // ============================================================
    public function getCodesThisMonth(): int
    {
        $codeModel = new CodeBonus();
        $startOfMonth = date('Y-m-01 00:00:00');
        $endOfMonth = date('Y-m-t 23:59:59');
        return $codeModel->where('est_valide', 1)
                         ->where('created_at >=', $startOfMonth)
                         ->where('created_at <=', $endOfMonth)
                         ->countAllResults();
    }

    public function getGoldRevenue(): float
    {
        return $this->getUserGoldRevenue(); // alias
    }

    public function getMonthlyInscriptions(): array
    {
        return $this->getInscriptionsParMois(); // alias
    }

    public function getIMCDistribution(): array
    {
        return $this->getRepartitionIMC(); // alias
    }

    public function getRecentActivity(): array
    {
        $activity = [];

        // Dernières transactions de codes
        $codes = $this->db->table('transaction_portefeuille tp')
            ->select("CONCAT('Code #', cb.code, ' utilisé') as text, tp.created_at")
            ->join('code_bonus cb', 'cb.id = tp.code_bonus_id')
            ->orderBy('tp.created_at', 'DESC')
            ->limit(2)
            ->get()
            ->getResultArray();
        foreach ($codes as $c) {
            $activity[] = [
                'type' => 'gold',
                'text' => $c['text'],
                'time' => $this->timeAgo($c['created_at'])
            ];
        }

        // Derniers régimes créés
        $regimes = $this->db->table('regime')
            ->select("CONCAT('Nouveau régime \"', nom, '\" créé') as text, created_at")
            ->orderBy('created_at', 'DESC')
            ->limit(2)
            ->get()
            ->getResultArray();
        foreach ($regimes as $r) {
            $activity[] = [
                'type' => 'green',
                'text' => $r['text'],
                'time' => $this->timeAgo($r['created_at'])
            ];
        }

        // Derniers utilisateurs inscrits
        $users = $this->db->table('utilisateur')
            ->select("CONCAT('Nouvel inscrit : ', prenom, ' ', nom) as text, created_at")
            ->orderBy('created_at', 'DESC')
            ->limit(2)
            ->get()
            ->getResultArray();
        foreach ($users as $u) {
            $activity[] = [
                'type' => 'green',
                'text' => $u['text'],
                'time' => $this->timeAgo($u['created_at'])
            ];
        }

        // Trier par date décroissante
        usort($activity, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return array_slice($activity, 0, 5);
    }

    private function timeAgo($datetime)
    {
        $now = new \DateTime();
        $then = new \DateTime($datetime);
        $diff = $now->getTimestamp() - $then->getTimestamp();

        if ($diff < 60) return 'il y a ' . $diff . ' s';
        if ($diff < 3600) return 'il y a ' . floor($diff / 60) . ' min';
        if ($diff < 86400) return 'il y a ' . floor($diff / 3600) . ' h';
        return 'il y a ' . floor($diff / 86400) . ' j';
    }

    // ============================================================
    // Méthodes CRUD pour l'admin (DashboardController)
    // ============================================================
    public function getAllRegimes(): array
    {
        return (new Regime())->orderBy('created_at', 'DESC')->findAll();
    }

    public function getAllCodes(): array
    {
        return (new CodeBonus())->orderBy('created_at', 'DESC')->findAll();
    }

    public function getAllActivites(): array
    {
        return (new ActiviteSportive())->orderBy('created_at', 'DESC')->findAll();
    }

    public function createActivite(array $data)
    {
        return (new ActiviteSportive())->insert($data);
    }

    public function getAllUtilisateurs(): array
    {
        return (new Utilisateur())->orderBy('created_at', 'DESC')->findAll();
    }

    public function getAllAbonnements(): array
    {
        return (new Abonnement())->orderBy('created_at', 'DESC')->findAll();
    }

    public function createAbonnement(array $data)
    {
        return (new Abonnement())->insert($data);
    }

    public function updateAbonnement(int $id, array $data)
    {
        return (new Abonnement())->update($id, $data);
    }

    public function deleteAbonnement(int $id)
    {
        return (new Abonnement())->delete($id);
    }

    public function getStatsData(): array
    {
        $analysisService = new DataAnalysisService();

        return [
            'total_users'       => $this->getTotalUsers(),
            'total_regimes'     => $this->getActiveRegimes(),
            'total_codes'       => $this->getValidCodesCount(),
            'total_gold_revenue' => $this->getGoldRevenue(),
            'inscriptions'      => $this->getInscriptionsParMois(),
            'imc_distribution'  => $this->getRepartitionIMC(),
            'recent_regimes'    => $this->getRecentRegimes(),
            'user_trend'        => $this->getUserTrend(),
            'analysis_data'     => $analysisService->getGlobalStats(),
        ];
    }

    // ============================================================
    // Utilitaires privés
    // ============================================================
    private function getStreakDays(int $userId): int
    {
        $actif = (new SouscriptionRegime())
            ->where('utilisateur_id', $userId)
            ->where('statut', 'actif')
            ->orderBy('date_debut', 'DESC')
            ->first();
        if (!$actif) return 0;
        return (new \DateTime($actif['date_debut']))->diff(new \DateTime())->days;
    }

    private function getTotalDays(int $userId): int
    {
        $souscriptions = (new SouscriptionRegime())->where('utilisateur_id', $userId)->findAll();
        $total = 0;
        foreach ($souscriptions as $s) {
            $debut = new \DateTime($s['date_debut']);
            $fin = $s['date_fin'] ? new \DateTime($s['date_fin']) : new \DateTime();
            $total += $debut->diff($fin)->days;
        }
        return $total;
    }
}