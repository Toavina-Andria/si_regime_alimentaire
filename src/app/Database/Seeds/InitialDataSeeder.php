<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        $date_format = 'Y-m-d H:i:s';
        // Insert 15 codebonus
        $data = [
            [
                'code' => 'STARTFIT15',
                'valeur_points' => 15,
                'est_valide' => true,
                'expires_at' => date($date_format, strtotime('+15 days')),
            ],
            [
                'code' => 'GYMPOWER25',
                'valeur_points' => 25,
                'est_valide' => true,
                'expires_at' => date($date_format, strtotime('+45 days')),
            ],
            [
                'code' => 'CARDIO50',
                'valeur_points' => 50,
                'est_valide' => true,
                'expires_at' => date($date_format, strtotime('+90 days')),
            ],
            [
                'code' => 'MUSCLEBOOST35',
                'valeur_points' => 35,
                'est_valide' => true,
                'expires_at' => date($date_format, strtotime('+60 days')),
            ],
            [
                'code' => 'SUMMERBODY75',
                'valeur_points' => 75,
                'est_valide' => true,
                'expires_at' => date($date_format, strtotime('+120 days')),
            ],
            [
                'code' => 'FITLIFE100',
                'valeur_points' => 100,
                'est_valide' => true,
                'expires_at' => date($date_format, strtotime('+180 days')),
            ],
            [
                'code' => 'ENERGYUP45',
                'valeur_points' => 45,
                'est_valide' => true,
                'expires_at' => date($date_format, strtotime('+75 days')),
            ],
            [
                'code' => 'STRONG2026',
                'valeur_points' => 60,
                'est_valide' => true,
                'expires_at' => date($date_format, strtotime('+150 days')),
            ],
            [
                'code' => 'WELLNESS80',
                'valeur_points' => 80,
                'est_valide' => false,
                'expires_at' => date($date_format, strtotime('-10 days')),
            ],
            [
                'code' => 'NEWYEAR150',
                'valeur_points' => 150,
                'est_valide' => true,
                'expires_at' => date($date_format, strtotime('+365 days')),
            ],
        ];
        $this->db->table('code_bonus')->insertBatch($data);
        // Insert regime
        $data = [
            [
                'nom' => 'Good Chiken',
                'description' => 'Régime riche en protéines de poulet pour la prise de masse musculaire.',
                'pct_viande' => 50.00,
                'pct_volaille' => 50.00,
                'pct_poisson' => 0.00,
                'variation_poids_kg' => 2.00,
                'duree_jours' => 30,
            ],
            [
                'nom' => 'Fish & Fit',
                'description' => 'Régime équilibré avec une forte proportion de poisson pour la santé cardiovasculaire.',
                'pct_viande' => 20.00,
                'pct_volaille' => 30.00,
                'pct_poisson' => 50.00,
                'variation_poids_kg' => -1.50,
                'duree_jours' => 30,
            ],
            [
                'nom' => 'Vegan Power',
                'description' => 'Régime végétalien riche en protéines végétales pour la prise de masse.',
                'pct_viande' => 0.00,
                'pct_volaille' => 0.00,
                'pct_poisson' => 0.00,
                'variation_poids_kg' => 1.00,
                'duree_jours' => 30,
            ],
            [
                'nom' => 'Balanced Diet',
                'description' => 'Régime équilibré avec une proportion égale de viande, volaille et poisson pour une santé optimale.',
                'pct_viande' => 33.33,
                'pct_volaille' => 33.33,
                'pct_poisson' => 33.34,
                'variation_poids_kg' => 0.00,
                'duree_jours' => 30,
            ],
        ];
        $this->db->table('regime')->insertBatch($data);
        // insert regime_prix
        $data = [
            [
                'regime_id' => 1,
                'duree_jours' => 30,
                'prix_base' => 49.99,
            ],
            [
                'regime_id' => 1,
                'duree_jours' => 60,
                'prix_base' => 89.99,
            ],
            [
                'regime_id' => 1,
                'duree_jours' => 90,
                'prix_base' => 129.99,
            ],

            [
                'regime_id' => 2,
                'duree_jours' => 30,
                'prix_base' => 59.99,
            ],
            [
                'regime_id' => 2,
                'duree_jours' => 60,
                'prix_base' => 109.99,
            ],
            [
                'regime_id' => 2,
                'duree_jours' => 90,
                'prix_base' => 149.99,
            ],

            [
                'regime_id' => 3,
                'duree_jours' => 30,
                'prix_base' => 44.99,
            ],
            [
                'regime_id' => 3,
                'duree_jours' => 60,
                'prix_base' => 84.99,
            ],
            [
                'regime_id' => 3,
                'duree_jours' => 90,
                'prix_base' => 119.99,
            ],

            [
                'regime_id' => 4,
                'duree_jours' => 30,
                'prix_base' => 54.99,
            ],
            [
                'regime_id' => 4,
                'duree_jours' => 60,
                'prix_base' => 99.99,
            ],
            [
                'regime_id' => 4,
                'duree_jours' => 90,
                'prix_base' => 139.99,
            ],
        ];

        $this->db->table('regime_prix')->insertBatch($data);
        // Insert abonnements
        $data = [
            [
                'nom' => 'Gratuit',
                'statut' => 'free',
                'taux_reduction' => 0.00,
                'prix' => 0.00,
                'description' => 'Accès standard sans remise',
            ],
            [
                'nom' => 'Gold',
                'statut' => 'gold',
                'taux_reduction' => 15.00,
                'prix' => 29.99,
                'description' => 'Accès Gold - 15% de remise sur tous les régimes, paiement unique',
            ],
        ];
        $this->db->table('abonnement')->insertBatch($data);

        // Insert activites sportives
        $activites = [
            [
                'nom' => 'Marche rapide',
                'description' => 'Marche à 6 km/h',
                'intensite' => 1,
                'calories_heure' => 300.00,
            ],
            [
                'nom' => 'Natation',
                'description' => 'Nage libre modérée',
                'intensite' => 2,
                'calories_heure' => 500.00,
            ],
            [
                'nom' => 'Course à pied',
                'description' => 'Jogging à 10 km/h',
                'intensite' => 2,
                'calories_heure' => 600.00,
            ],
            [
                'nom' => 'Musculation',
                'description' => 'Exercices avec charges',
                'intensite' => 2,
                'calories_heure' => 450.00,
            ],
            [
                'nom' => 'HIIT',
                'description' => 'Entraînement haute intensité',
                'intensite' => 3,
                'calories_heure' => 750.00,
            ],
            [
                'nom' => 'Vélo',
                'description' => 'Cyclisme modéré',
                'intensite' => 2,
                'calories_heure' => 480.00,
            ],
            [
                'nom' => 'Yoga',
                'description' => 'Yoga et étirements',
                'intensite' => 1,
                'calories_heure' => 200.00,
            ],
        ];
        $this->db->table('activite_sportive')->insertBatch($activites);
    }
}
