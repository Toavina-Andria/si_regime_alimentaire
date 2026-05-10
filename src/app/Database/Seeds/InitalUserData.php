<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitalUserData extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nom' => 'admin',
                'prenom' => 'Admin',
                'email' => 'admin@service.com',
                'mot_de_passe' => password_hash('admin123', PASSWORD_DEFAULT),
                'date_naissance' => '25-01-1999',
                'genre' => 'homme',
                'adresse' => 'Ampasamadinika',
                'taille_cm' => '170',
                'poids_kg' => '65',
                'objectif' => 'imc_ideal',
                'est_admin' => 1
            ],
            [
                'nom' => 'Elen',
                'prenom' => 'Dupont',
                'email' => 'elen@example.com',
                'mot_de_passe' => password_hash('12345678', PASSWORD_DEFAULT),
                'date_naissance' => '02-07-1986',
                'genre' => 'femme',
                'adresse' => 'France Rue le courbe',
                'taille_cm' => '155',
                'poids_kg' => '75',
                'objectif' => 'reduire_poids'
            ],

        ];
        $this->db->table('utilisateur')->insertBatch($data);

        $this->db->table('parametres')->insertBatch([
            ['clef' => 'nom_plateforme', 'valeur' => 'NutriPlan', 'description' => 'Nom de la plateforme'],
            ['clef' => 'email_contact', 'valeur' => 'contact@nutriplan.fr', 'description' => 'Email de contact'],
            ['clef' => 'devise', 'valeur' => '€', 'description' => 'Unité monétaire'],
            ['clef' => 'remise_gold', 'valeur' => '15', 'description' => 'Remise abonnement Gold en %'],
            ['clef' => 'maintenance', 'valeur' => '0', 'description' => 'Mode maintenance (0/1)'],
            ['clef' => 'email_expediteur', 'valeur' => 'noreply@nutriplan.fr', 'description' => "Email d'expédition"],
            ['clef' => 'notification_admin', 'valeur' => '1', 'description' => 'Notifications admin'],
        ]);
    }
}
