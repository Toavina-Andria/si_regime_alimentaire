<?php

namespace App\Services;

use App\Models\CodeBonus;
use App\Models\HistoriquePoids;
use App\Models\Regime;
use App\Models\SouscriptionRegime;
use App\Models\Portefeuille;
use App\Models\RegimePrix;
use App\Models\Utilisateur;
use App\Models\UtilisateurAbonnement;
use App\Models\TransactionPortefeuille;

class DashboardService
{
    private Utilisateur $utilisateurModel;
    private Regime $regimeModel;
    private RegimePrix $regimePrixModel;
    private SouscriptionRegime $souscriptionRegimeModel;
    private CodeBonus $codeBonusModel;
    private TransactionPortefeuille $transactionPortefeuilleModel;
    private UtilisateurAbonnement $utilisateurAbonnementModel;
    private HistoriquePoids $historiquePoidsModel;
    private string $dateFormat = 'Y-m-d H:i:s';
    private $db;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->utilisateurModel = new Utilisateur();
        $this->regimeModel = new Regime();
        $this->regimePrixModel = new RegimePrix();
        $this->souscriptionRegimeModel = new SouscriptionRegime();
        $this->codeBonusModel = new CodeBonus();
        $this->transactionPortefeuilleModel = new TransactionPortefeuille();
        $this->utilisateurAbonnementModel = new UtilisateurAbonnement();
        $this->historiquePoidsModel = new HistoriquePoids();
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

        return $activity;
    }

    public function getCurrentRegime(int $userId): ?array
    {
        $sub = $this->souscriptionRegimeModel
            ->select('souscription_regime.*, regime.nom, regime.description, regime.pct_viande, regime.pct_volaille, regime.pct_poisson, regime.variation_poids_kg, regime.duree_jours, regime_prix.prix_base')
            ->join('regime_prix', 'regime_prix.id = souscription_regime.regime_prix_id')
            ->join('regime', 'regime.id = regime_prix.regime_id')
            ->where('souscription_regime.utilisateur_id', $userId)
            ->where('souscription_regime.statut', 'actif')
            ->where('souscription_regime.date_debut <=', date('Y-m-d'))
            ->where('souscription_regime.date_fin >=', date('Y-m-d'))
            ->first();

        return $sub ?: null;
    }

    public function getStreakDays(int $userId): int
    {
        $current = $this->getCurrentRegime($userId);
        if (!$current) {
            return 0;
        }
        $start = new \DateTime($current['date_debut']);
        $now = new \DateTime();
        return (int) $start->diff($now)->days;
    }

    public function getTotalDays(int $userId): int
    {
        $subs = $this->souscriptionRegimeModel
            ->select('date_debut, date_fin')
            ->where('utilisateur_id', $userId)
            ->findAll();

        $total = 0;
        foreach ($subs as $sub) {
            $start = new \DateTime($sub['date_debut']);
            $end = new \DateTime($sub['date_fin']);
            $total += $start->diff($end)->days;
        }
        return $total;
    }

    public function getWeightHistory(int $userId): array
    {
        $records = $this->historiquePoidsModel
            ->where('utilisateur_id', $userId)
            ->orderBy('mesure_le', 'ASC')
            ->findAll();

        $labels = [];
        $values = [];
        foreach ($records as $r) {
            $labels[] = date('d/m/Y', strtotime($r['mesure_le']));
            $values[] = (float) $r['poids_kg'];
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    public function getWalletTransactions(int $userId): array
    {
        $wallet = $this->getWallet($userId);
        if (!$wallet) {
            return [];
        }

        return $this->transactionPortefeuilleModel
            ->where('portefeuille_id', $wallet['id'])
            ->orderBy('created_at', 'DESC')
            ->findAll(10);
    }

    public function getRegimeHistory(int $userId): array
    {
        return $this->souscriptionRegimeModel
            ->select('souscription_regime.*, regime.nom')
            ->join('regime_prix', 'regime_prix.id = souscription_regime.regime_prix_id')
            ->join('regime', 'regime.id = regime_prix.regime_id')
            ->where('souscription_regime.utilisateur_id', $userId)
            ->orderBy('souscription_regime.date_debut', 'DESC')
            ->findAll();
    }

    private function getImcCategoryCounts(): array
    {
        $users = $this->utilisateurModel
            ->select('taille_cm, poids_kg')
            ->where('taille_cm IS NOT NULL', null, false)
            ->where('poids_kg IS NOT NULL', null, false)
            ->findAll();

        $categories = [
            'Poids insuffisant' => 0,
            'Poids normal' => 0,
            'Surpoids' => 0,
            'Obésité' => 0,
        ];

        foreach ($users as $user) {
            $imc = $this->utilisateurModel->calculerIMC($user['taille_cm'], $user['poids_kg']);
            $category = $this->utilisateurModel->categorieIMC($imc);
            if ($category !== null && isset($categories[$category])) {
                $categories[$category]++;
            }
        }

        return $categories;
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


}