<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $data = [
            'kpi_users'         => $this->getTotalUsers(),
            'kpi_users_trend'   => $this->getUserTrend(),
            'kpi_regimes'       => $this->getActiveRegimes(),
            'kpi_codes'         => $this->getCodesThisMonth(),
            'kpi_gold'          => $this->getGoldRevenue(),
            'chart_inscriptions' => $this->getMonthlyInscriptions(),
            'chart_imc'         => $this->getIMCDistribution(),
            'recent_regimes'    => $this->getRecentRegimes(),
            'recent_activity'   => $this->getRecentActivity(),
        ];

        return view('dashboard/index', $data);
    }

    public function regimes()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $builder = $this->db->table('regime');
        $builder->orderBy('created_at', 'DESC');
        $data['regimes'] = $builder->get()->getResultArray();

        return view('dashboard/regimes', $data);
    }

    public function codes()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $builder = $this->db->table('code_bonus');
        $builder->orderBy('created_at', 'DESC');
        $data['codes'] = $builder->get()->getResultArray();

        return view('dashboard/codes', $data);
    }

    public function activites()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $builder = $this->db->table('activite_sportive');
        $builder->orderBy('created_at', 'DESC');
        $data['activites'] = $builder->get()->getResultArray();

        return view('dashboard/activites', $data);
    }

    private function getTotalUsers()
    {
        return $this->db->table('utilisateur')->countAllResults();
    }

    private function getUserTrend()
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

    private function getActiveRegimes()
    {
        return $this->db->table('regime')->countAllResults();
    }

    private function getCodesThisMonth()
    {
        return $this->db->table('transaction_portefeuille')
            ->where('created_at >=', date('Y-m-01 00:00:00'))
            ->where('type', 'credit')
            ->countAllResults();
    }

    private function getGoldRevenue()
    {
        $builder = $this->db->table('utilisateur_abonnement ua');
        $builder->select('SUM(a.prix) as total');
        $builder->join('abonnement a', 'a.id = ua.abonnement_id');
        $builder->where('a.statut', 'gold');
        $result = $builder->get()->getRowArray();
        return $result['total'] ?? 0;
    }

    private function getMonthlyInscriptions()
    {
        $months = [];
        $counts = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[] = date('M', strtotime("-$i months"));

            $count = $this->db->table('utilisateur')
                ->where('created_at >=', $month . '-01 00:00:00')
                ->where('created_at <', date('Y-m-01 00:00:00', strtotime("-$i months +1 month")))
                ->countAllResults();
            $counts[] = $count;
        }

        return [
            'labels' => $months,
            'values' => $counts,
        ];
    }

    private function getIMCDistribution()
    {
        $users = $this->db->table('utilisateur')
            ->select('taille_cm, poids_kg')
            ->where('taille_cm IS NOT NULL')
            ->where('poids_kg IS NOT NULL')
            ->get()
            ->getResultArray();

        $categories = [
            'sous_poids'  => 0,
            'normal'      => 0,
            'surpoids'    => 0,
            'obesite'     => 0,
        ];

        foreach ($users as $user) {
            $taille_m = $user['taille_cm'] / 100;
            $imc = $user['poids_kg'] / ($taille_m * $taille_m);

            if ($imc < 18.5) {
                $categories['sous_poids']++;
            } elseif ($imc < 25) {
                $categories['normal']++;
            } elseif ($imc < 30) {
                $categories['surpoids']++;
            } else {
                $categories['obesite']++;
            }
        }

        return [
            'labels' => ['Sous-poids', 'Poids normal', 'Surpoids', 'Obésité'],
            'values' => array_values($categories),
            'colors' => ['#74C69D', '#2D6A4F', '#D4A853', '#C1392B'],
        ];
    }

    private function getRecentRegimes()
    {
        $builder = $this->db->table('regime');
        $builder->orderBy('created_at', 'DESC');
        $builder->limit(5);
        $regimes = $builder->get()->getResultArray();

        foreach ($regimes as &$regime) {
            $prixBuilder = $this->db->table('regime_prix');
            $prixBuilder->where('regime_id', $regime['id']);
            $prixBuilder->limit(1);
            $prix = $prixBuilder->get()->getRowArray();
            $regime['prix'] = $prix ? $prix['prix_base'] : '—';

            $regime['duree_display'] = $regime['duree_jours'] . ' jours';
            if ($regime['duree_jours'] >= 30) {
                $mois = floor($regime['duree_jours'] / 30);
                $regime['duree_display'] = $mois . ' mois';
            }
        }

        return $regimes;
    }

    private function getRecentActivity()
    {
        $activity = [];

        $codes = $this->db->table('transaction_portefeuille tp')
            ->select("CONCAT('Code #', cb.code, ' validé') as text, tp.created_at")
            ->join('code_bonus cb', 'cb.id = tp.code_bonus_id')
            ->orderBy('tp.created_at', 'DESC')
            ->limit(2)
            ->get()
            ->getResultArray();

        foreach ($codes as $c) {
            $activity[] = [
                'text' => $c['text'],
                'time' => $this->timeAgo($c['created_at']),
                'type' => 'gold',
            ];
        }

        $regimes = $this->db->table('regime')
            ->select("CONCAT('Nouveau régime \"', nom, '\" créé') as text, created_at")
            ->orderBy('created_at', 'DESC')
            ->limit(2)
            ->get()
            ->getResultArray();

        foreach ($regimes as $r) {
            $activity[] = [
                'text' => $r['text'],
                'time' => $this->timeAgo($r['created_at']),
                'type' => 'green',
            ];
        }

        $users = $this->db->table('utilisateur')
            ->select("CONCAT('Nouvel inscrit : ', prenom, ' ', nom) as text, created_at")
            ->orderBy('created_at', 'DESC')
            ->limit(2)
            ->get()
            ->getResultArray();

        foreach ($users as $u) {
            $activity[] = [
                'text' => $u['text'],
                'time' => $this->timeAgo($u['created_at']),
                'type' => 'green',
            ];
        }

        usort($activity, function ($a, $b) {
            return $a['time'] <=> $b['time'];
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
}
