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
                'objectif' => 'imc_ideal'
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
    }
}
