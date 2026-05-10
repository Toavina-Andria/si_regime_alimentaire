<?php

namespace App\Services;

use App\Models\CodeBonus;
use App\Models\HistoriquePoids;
use App\Models\Regime;
use App\Models\RegimePrix;
use App\Models\SouscriptionRegime;
use App\Models\TransactionPortefeuille;
use App\Models\Utilisateur;
use App\Models\UtilisateurAbonnement;
use App\Services\SuggestionAugmenterPoids;
use App\Services\SuggestionDiminuerPoids;
use App\Services\SuggestionService;
use App\Services\UtilisateurService;

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

    public function __construct()
    {
        $this->utilisateurModel = new Utilisateur();
        $this->regimeModel = new Regime();
        $this->regimePrixModel = new RegimePrix();
        $this->souscriptionRegimeModel = new SouscriptionRegime();
        $this->codeBonusModel = new CodeBonus();
        $this->transactionPortefeuilleModel = new TransactionPortefeuille();
        $this->utilisateurAbonnementModel = new UtilisateurAbonnement();
        $this->historiquePoidsModel = new HistoriquePoids();
    }

    public function getTotalUsers(): int
    {
        return $this->utilisateurModel->countAll();
    }

    public function getUserTrend(): int
    {
        $lastMonth = $this->utilisateurModel
            ->where('created_at >=', date($this->dateFormat, strtotime('-30 days')))
            ->countAllResults();

        $previousMonth = $this->utilisateurModel
            ->where('created_at >=', date($this->dateFormat, strtotime('-60 days')))
            ->where('created_at <', date($this->dateFormat, strtotime('-30 days')))
            ->countAllResults();

        if ($previousMonth > 0) {
            return (int) round((($lastMonth - $previousMonth) / $previousMonth) * 100);
        }
        return $lastMonth > 0 ? 100 : 0;
    }

    public function getActiveRegimes(): int
    {
        return $this->regimeModel->countAll();
    }

    public function getCodesThisMonth(): int
    {
        return $this->transactionPortefeuilleModel
            ->where('created_at >=', date($this->dateFormat, strtotime('first day of this month')))
            ->where('type', 'credit')
            ->countAllResults();
    }

    public function getGoldRevenue(): float
    {
        $result = $this->utilisateurAbonnementModel
            ->select('SUM(a.prix) as total')
            ->join('abonnement a', 'a.id = utilisateur_abonnement.abonnement_id')
            ->where('a.statut', 'gold')
            ->first();

        return isset($result['total']) ? (float) $result['total'] : 0.0;
    }

    public function getMonthlyInscriptions(): array
    {
        $months = [];
        $counts = [];

        for ($i = 11; $i >= 0; $i--) {
            $monthKey = date('Y-m', strtotime("-$i months"));
            $months[] = date('M', strtotime("-$i months"));

            $count = $this->utilisateurModel
                ->where('created_at >=', $monthKey . '-01 00:00:00')
                ->where('created_at <', date($this->dateFormat, strtotime("-$i months +1 month")))
                ->countAllResults();
            $counts[] = $count;
        }

        return [
            'labels' => $months,
            'values' => $counts,
        ];
    }

    public function getIMCDistribution(): array
    {
        $counts = $this->getImcCategoryCounts();

        return [
            'labels' => ['Sous-poids', 'Poids normal', 'Surpoids', 'Obésité'],
            'values' => [
                $counts['Poids insuffisant'] ?? 0,
                $counts['Poids normal'] ?? 0,
                $counts['Surpoids'] ?? 0,
                $counts['Obésité'] ?? 0,
            ],
            'colors' => ['#74C69D', '#2D6A4F', '#D4A853', '#C1392B'],
        ];
    }

    public function getRecentRegimes(bool $formatPrice = false, bool $orderByDuree = false): array
    {
        $regimes = $this->regimeModel->orderBy('created_at', 'DESC')->findAll(5);

        foreach ($regimes as &$regime) {
            $prixQuery = $this->regimePrixModel->where('regime_id', $regime['id']);
            if ($orderByDuree) {
                $prixQuery->orderBy('duree_jours', 'ASC');
            }
            $prix = $prixQuery->first();
            if ($prix) {
                $regime['prix'] = $formatPrice ? number_format($prix['prix_base'], 2) : $prix['prix_base'];
            } else {
                $regime['prix'] = '—';
            }

            $regime['duree_display'] = $regime['duree_jours'] . ' jours';
            if ($regime['duree_jours'] >= 30) {
                $mois = (int) floor($regime['duree_jours'] / 30);
                $regime['duree_display'] = $mois . ' mois';
            }
        }
        unset($regime);

        return $regimes;
    }

    public function getRecentActivity(): array
    {
        $activity = [];

        $codes = $this->transactionPortefeuilleModel
            ->select("CONCAT('Code #', cb.code, ' validé') as text, transaction_portefeuille.created_at")
            ->join('code_bonus cb', 'cb.id = transaction_portefeuille.code_bonus_id')
            ->orderBy('transaction_portefeuille.created_at', 'DESC')
            ->findAll(2);

        foreach ($codes as $code) {
            $activity[] = [
                'text' => $code['text'],
                'time' => $this->timeAgo($code['created_at']),
                'timestamp' => strtotime($code['created_at']),
                'type' => 'gold',
            ];
        }

        $regimes = $this->regimeModel
            ->select("CONCAT('Nouveau régime \"', nom, '\" créé') as text, created_at")
            ->orderBy('created_at', 'DESC')
            ->findAll(2);

        foreach ($regimes as $regime) {
            $activity[] = [
                'text' => $regime['text'],
                'time' => $this->timeAgo($regime['created_at']),
                'timestamp' => strtotime($regime['created_at']),
                'type' => 'green',
            ];
        }

        $users = $this->utilisateurModel
            ->select("CONCAT('Nouvel inscrit : ', prenom, ' ', nom) as text, created_at")
            ->orderBy('created_at', 'DESC')
            ->findAll(2);

        foreach ($users as $user) {
            $activity[] = [
                'text' => $user['text'],
                'time' => $this->timeAgo($user['created_at']),
                'timestamp' => strtotime($user['created_at']),
                'type' => 'green',
            ];
        }

        usort($activity, function ($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });

        return array_slice($activity, 0, 5);
    }
    // get user abonnement actif
    public static function getUserGoldSubscription($userId)
    {
        $abonnementModel = new UtilisateurAbonnement();
        $abo =$abonnementModel
        ->select('a.* , utilisateur_abonnement.date_debut, utilisateur_abonnement.date_fin')
        ->join('abonnement a', 'a.id = utilisateur_abonnement.abonnement_id')
        ->where('utilisateur_id', $userId)
        ->where('a.statut', 'gold')
        ->where('utilisateur_abonnement.date_fin >=', date('Y-m-d H:i:s'))
        ->first();
        
        return [
            'statut' => $abo['statut'] ?? null,
            'nom' => $abo['nom'] ?? null,
            'taux_reduction' => $abo['taux_reduction'] ?? null,
            'date_debut' => $abo['date_debut'] ?? null,
            'date_fin' => $abo['date_fin'] ?? null,
        ];
        
    }
    public function getUserById(int $userId): ?array
    {
        return $this->utilisateurModel->find($userId);
    }

    public function getUserImcData(int $userId): array
    {
        $user = $this->getUserById($userId);
        $imc = null;
        $categorieImc = null;

        if ($user && !empty($user['taille_cm']) && !empty($user['poids_kg'])) {
            $imc = $this->utilisateurModel->calculerIMC($user['taille_cm'], $user['poids_kg']);
            $categorieImc = $this->utilisateurModel->categorieIMC($imc);
        }

        return [
            'imc' => $imc,
            'categorie_imc' => $categorieImc,
        ];
    }
    public function getUserObjective(int $userId): ?string
    {
        $user = $this->getUserById($userId);
        $obj = $user['objectif'] ?? '';
        switch ($obj) {
            case 'augmenter_poids':
                $obj = 'Prendre du poids';
                break;
            case 'reduire_poids':
                $obj = 'Perdre du poids';
                break;
            case 'imc_ideal':
                $obj = 'Atteindre votre IMC idéal';
                break;
            default:
                $obj = 'Non défini';
        }
        return $obj;
    }

    public function getUserSuggestions(int $userId): array
    {
        $user = $this->getUserById($userId);
        if (!$user || empty($user['objectif'])) {
            return [];
        }

        $imc = null;
        if (!empty($user['taille_cm']) && !empty($user['poids_kg'])) {
            $imc = $this->utilisateurModel->calculerIMC($user['taille_cm'], $user['poids_kg']);
        }
        $result = [];
        switch ($user['objectif']) {
            case 'augmenter_poids':
                $service = new SuggestionAugmenterPoids();
                $result = $service->getSuggestions();
                break;
            case 'reduire_poids':
                $service = new SuggestionDiminuerPoids();
                $result = $service->getSuggestions();
                break;
            case 'imc_ideal':
                $service = new SuggestionService();
                $result = $service->getSuggestions($user['objectif'], $imc);
                break;
            default:
                $result = [];
                break;
        }
        return $result;
    }

    public function getValidCodesCount(): int
    {
        return $this->codeBonusModel->where('est_valide', 1)->countAllResults();
    }

    public function getUserGoldRevenue(): float
    {
        $result = $this->souscriptionRegimeModel
            ->select('SUM(prix_paye) as total')
            ->where('remise_appliquee', 15.00)
            ->first();

        return isset($result['total']) ? (float) $result['total'] : 0.0;
    }

    public function getInscriptionsParMois(): array
    {
        $builder = $this->utilisateurModel->builder();
        $builder
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
        $counts = $this->getImcCategoryCounts();
        $labels = ['Poids insuffisant', 'Poids normal', 'Surpoids', 'Obésité'];
        $values = [];

        foreach ($labels as $label) {
            $values[] = $counts[$label] ?? 0;
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'colors' => ['#2D6A4F', '#52B788', '#D4A853', '#B4432B'],
        ];
    }
    public function getWallet(int $userId): ?array
    {
        UtilisateurService::validateUser($userId);
        return UtilisateurService::getPortefeuilleByUserId($userId);
   
    }
    public function getUserRecentActivity(int $userId): array
    {
        $activities = $this->souscriptionRegimeModel
            ->select('souscription_regime.created_at, regime.nom as regime_nom')
            ->join('regime_prix', 'regime_prix.id = souscription_regime.regime_prix_id')
            ->join('regime', 'regime.id = regime_prix.regime_id')
            ->where('utilisateur_id', $userId)
            ->orderBy('souscription_regime.created_at', 'DESC')
            ->findAll(5);

        $result = [];
        foreach ($activities as $act) {
            $result[] = [
                'type' => 'subscription',
                'text' => "Souscription au régime « {$act['regime_nom']} »",
                'time' => date('d/m/Y H:i', strtotime($act['created_at'])),
            ];
        }

        if (empty($result)) {
            $result[] = [
                'type' => 'info',
                'text' => 'Aucune activité récente. Commencez un régime !',
                'time' => date('d/m/Y H:i'),
            ];
        }

        return $result;
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

    private function timeAgo(string $datetime): string
    {
        $now = new \DateTime();
        $then = new \DateTime($datetime);
        $diff = $now->getTimestamp() - $then->getTimestamp();
        $ilYa = 'il y a ';
        $result = $ilYa . (int) floor($diff / 86400) . ' j';
        if ($diff < 60) {
            $result = $ilYa . $diff . ' s';
        }
        if ($diff < 3600) {
            $result = $ilYa . (int) floor($diff / 60) . ' min';
        }
        if ($diff < 86400) {
            $result = $ilYa . (int) floor($diff / 3600) . ' h';
        }
        return $result;
    }
}
