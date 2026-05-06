<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
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
