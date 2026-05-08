<?php

namespace App\Controllers;

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
            return redirect()->to('/');
        }

        $userId = session()->get('user_id');
        $user   = $this->getUser($userId);

        $data = [
            'user'              => $user,
            'imc'               => $this->calculerIMC($user),
            'subscription'      => $this->getSubscription($userId),
            'current_regime'    => $this->getCurrentRegime($userId),
            'weight_history'    => $this->getWeightHistory($userId),
            'wallet'            => $this->getWallet($userId),
            'streak_days'       => $this->getStreakDays($userId),
            'total_days'        => $this->getTotalDays($userId),
            'regime_history'    => $this->getRegimeHistory($userId),
            'transactions'      => $this->getRecentTransactions($userId),
        ];

        return view('dashboard/user/index', $data);
    }

    private function getUser(int $userId)
    {
        $builder = $this->db->table('utilisateur');
        $builder->where('id', $userId);
        return $builder->get()->getRowArray();
    }

    private function calculerIMC($user)
    {
        if (!$user || !$user['taille_cm'] || !$user['poids_kg']) {
            return null;
        }
        $taille_m = $user['taille_cm'] / 100;
        $imc = round($user['poids_kg'] / ($taille_m * $taille_m), 1);

        if ($imc < 18.5) {
            $label = 'Sous-poids';
            $color = '#74C69D';
        } elseif ($imc < 25) {
            $label = 'Poids normal';
            $color = '#2D6A4F';
        } elseif ($imc < 30) {
            $label = 'Surpoids';
            $color = '#D4A853';
        } else {
            $label = 'Obésité';
            $color = '#C1392B';
        }

        return [
            'value' => $imc,
            'label' => $label,
            'color' => $color,
        ];
    }

    private function getSubscription(int $userId)
    {
        $builder = $this->db->table('utilisateur_abonnement ua');
        $builder->select('a.nom as abonnement_nom, a.statut, a.taux_reduction, a.prix as abonnement_prix, a.description, ua.date_debut, ua.date_fin, ua.statut as souscription_statut');
        $builder->join('abonnement a', 'a.id = ua.abonnement_id');
        $builder->where('ua.utilisateur_id', $userId);
        $builder->where('ua.statut', 'actif');
        $builder->orderBy('ua.created_at', 'DESC');
        $builder->limit(1);
        return $builder->get()->getRowArray();
    }

    private function getCurrentRegime(int $userId)
    {
        $builder = $this->db->table('souscription_regime sr');
        $builder->select('r.nom, r.description, r.pct_viande, r.pct_volaille, r.pct_poisson, r.variation_poids_kg, r.duree_jours as regime_duree, rp.duree_jours, rp.prix_base, sr.date_debut, sr.date_fin, sr.statut, sr.prix_paye, sr.remise_appliquee');
        $builder->join('regime_prix rp', 'rp.id = sr.regime_prix_id');
        $builder->join('regime r', 'r.id = rp.regime_id');
        $builder->where('sr.utilisateur_id', $userId);
        $builder->where('sr.statut', 'actif');
        $builder->orderBy('sr.created_at', 'DESC');
        $builder->limit(1);
        return $builder->get()->getRowArray();
    }

    private function getWeightHistory(int $userId)
    {
        $builder = $this->db->table('historique_poids');
        $builder->select('poids_kg, mesure_le');
        $builder->where('utilisateur_id', $userId);
        $builder->orderBy('mesure_le', 'ASC');
        $builder->limit(30);
        $results = $builder->get()->getResultArray();

        $labels = [];
        $values = [];
        foreach ($results as $row) {
            $labels[] = date('d/m', strtotime($row['mesure_le']));
            $values[] = (float) $row['poids_kg'];
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    private function getWallet(int $userId)
    {
        $builder = $this->db->table('portefeuille');
        $builder->where('utilisateur_id', $userId);
        return $builder->get()->getRowArray();
    }

    private function getStreakDays(int $userId)
    {
        $builder = $this->db->table('souscription_regime');
        $builder->select('date_debut, date_fin, statut');
        $builder->where('utilisateur_id', $userId);
        $builder->where('statut', 'actif');
        $builder->orderBy('date_debut', 'DESC');
        $builder->limit(1);
        $regime = $builder->get()->getRowArray();

        if (!$regime) return 0;

        $start = new \DateTime($regime['date_debut']);
        $now = new \DateTime();
        return $start->diff($now)->days;
    }

    private function getTotalDays(int $userId)
    {
        $builder = $this->db->table('souscription_regime');
        $builder->select('date_debut, date_fin, statut');
        $builder->where('utilisateur_id', $userId);
        $builder->orderBy('date_debut', 'ASC');
        $regimes = $builder->get()->getResultArray();

        $total = 0;
        foreach ($regimes as $r) {
            $start = new \DateTime($r['date_debut']);
            $end = $r['date_fin'] ? new \DateTime($r['date_fin']) : new \DateTime();
            $total += $start->diff($end)->days;
        }
        return $total;
    }

    private function getRegimeHistory(int $userId)
    {
        $builder = $this->db->table('souscription_regime sr');
        $builder->select('r.nom, sr.date_debut, sr.date_fin, sr.statut, sr.prix_paye');
        $builder->join('regime_prix rp', 'rp.id = sr.regime_prix_id');
        $builder->join('regime r', 'r.id = rp.regime_id');
        $builder->where('sr.utilisateur_id', $userId);
        $builder->where('sr.statut !=', 'actif');
        $builder->orderBy('sr.date_debut', 'DESC');
        $builder->limit(10);
        return $builder->get()->getResultArray();
    }

    private function getRecentTransactions(int $userId)
    {
        $builder = $this->db->table('transaction_portefeuille tp');
        $builder->select('tp.montant, tp.type, tp.description, tp.created_at');
        $builder->join('portefeuille p', 'p.id = tp.portefeuille_id');
        $builder->where('p.utilisateur_id', $userId);
        $builder->orderBy('tp.created_at', 'DESC');
        $builder->limit(5);
        return $builder->get()->getResultArray();
    }
}
