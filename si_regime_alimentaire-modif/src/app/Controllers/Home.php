<?php

namespace App\Controllers;

class Home extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'regimes'       => $this->getRegimes(),
            'activites'     => $this->getActivites(),
            'stats'         => $this->getStats(),
            'testimonials'  => $this->getTestimonials(),
        ];

        return view('home/index', $data);
    }

    private function getRegimes()
    {
        $builder = $this->db->table('regime r');
        $builder->select('r.*, rp.duree_jours, rp.prix_base');
        $builder->join('regime_prix rp', 'rp.regime_id = r.id', 'left');
        $builder->orderBy('r.created_at', 'DESC');
        $results = $builder->get()->getResultArray();

        $grouped = [];
        foreach ($results as $row) {
            $id = $row['id'];
            if (!isset($grouped[$id])) {
                $grouped[$id] = [
                    'id'          => $row['id'],
                    'nom'         => $row['nom'],
                    'description' => $row['description'],
                    'pct_viande'  => $row['pct_viande'],
                    'pct_volaille'=> $row['pct_volaille'],
                    'pct_poisson' => $row['pct_poisson'],
                    'variation'   => $row['variation_poids_kg'],
                    'duree'       => $row['duree_jours'] ?? $row['regime_duree'] ?? null,
                    'prix'        => $row['prix_base'],
                    'prix_options'=> [],
                ];
            }
            if ($row['prix_base']) {
                $grouped[$id]['prix_options'][] = [
                    'duree' => $row['duree_jours'],
                    'prix'  => $row['prix_base'],
                ];
            }
        }

        return array_values($grouped);
    }

    private function getActivites()
    {
        $builder = $this->db->table('activite_sportive');
        $builder->orderBy('nom', 'ASC');
        return $builder->get()->getResultArray();
    }

    private function getStats()
    {
        $users   = $this->db->table('utilisateur')->countAllResults();
        $regimes = $this->db->table('regime')->countAllResults();
        $activites = $this->db->table('activite_sportive')->countAllResults();

        return [
            'utilisateurs' => $users,
            'regimes'      => $regimes,
            'activites'    => $activites,
        ];
    }

    private function getTestimonials()
    {
        return [
            [
                'name'    => 'Marie D.',
                'avatar'  => '👩',
                'text'    => 'Grâce à NutriPlan, j\'ai perdu 8 kg en 3 mois tout en mangeant équilibré. Le suivi est incroyable !',
                'goal'    => 'Perte de poids',
            ],
            [
                'name'    => 'Thomas L.',
                'avatar'  => '👨',
                'text'    => 'Les régimes sont variés et adaptés à mes besoins. L\'abonnement Gold m\'a fait économiser sur le long terme.',
                'goal'    => 'IMC idéal',
            ],
            [
                'name'    => 'Sophie M.',
                'avatar'  => '👩‍🦰',
                'text'    => 'Le suivi du poids et les activités recommandées m\'aident à rester motivée chaque jour.',
                'goal'    => 'Prise de masse',
            ],
        ];
    }
}
