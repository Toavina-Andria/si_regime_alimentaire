<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Désactiver les contraintes de clés étrangères
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        // Vider les tables (ordre inverse des dépendances)
        $this->db->table('historique_poids')->truncate();
        $this->db->table('souscription_regime')->truncate();
        $this->db->table('utilisateur_abonnement')->truncate();
        $this->db->table('portefeuille')->truncate();
        $this->db->table('code_bonus')->truncate();
        $this->db->table('transaction_portefeuille')->truncate();
        $this->db->table('regime_activite')->truncate();
        $this->db->table('regime_prix')->truncate();
        $this->db->table('regime')->truncate();
        $this->db->table('activite_sportive')->truncate();
        $this->db->table('utilisateur')->truncate();
        $this->db->table('abonnement')->truncate();

        // 1. Abonnements
        $abonnements = [
            ['nom' => 'Gratuit', 'statut' => 'free', 'taux_reduction' => 0, 'prix' => 0, 'description' => 'Accès de base'],
            ['nom' => 'Gold', 'statut' => 'gold', 'taux_reduction' => 15, 'prix' => 29.99, 'description' => 'Remise 15%'],
        ];
        $this->db->table('abonnement')->insertBatch($abonnements);

        // 2. Activités sportives
        $activites = [
            ['nom' => 'Marche rapide', 'intensite' => 1, 'calories_heure' => 300],
            ['nom' => 'Natation', 'intensite' => 2, 'calories_heure' => 500],
            ['nom' => 'Course à pied', 'intensite' => 2, 'calories_heure' => 600],
            ['nom' => 'Musculation', 'intensite' => 2, 'calories_heure' => 450],
            ['nom' => 'HIIT', 'intensite' => 3, 'calories_heure' => 750],
            ['nom' => 'Vélo', 'intensite' => 2, 'calories_heure' => 480],
            ['nom' => 'Yoga', 'intensite' => 1, 'calories_heure' => 200],
        ];
        $this->db->table('activite_sportive')->insertBatch($activites);
        $activiteIds = $this->db->table('activite_sportive')->select('id')->get()->getResultArray();
        $activiteIds = array_column($activiteIds, 'id');

        // 3. Régimes
        $regimes = [
            ['nom' => 'Régime Méditerranéen', 'description' => 'Omnivore équilibré', 'pct_viande' => 20, 'pct_volaille' => 20, 'pct_poisson' => 25, 'variation_poids_kg' => -0.2, 'duree_jours' => 30],
            ['nom' => 'Régime Hyperprotéiné', 'description' => 'Prise de masse muscle', 'pct_viande' => 40, 'pct_volaille' => 30, 'pct_poisson' => 10, 'variation_poids_kg' => 1.5, 'duree_jours' => 21],
            ['nom' => 'Régime Vegan', 'description' => 'Sans produit animal', 'pct_viande' => 0, 'pct_volaille' => 0, 'pct_poisson' => 0, 'variation_poids_kg' => 0.0, 'duree_jours' => 28],
            ['nom' => 'Régime Cétogène', 'description' => 'Low-carb, high fat', 'pct_viande' => 35, 'pct_volaille' => 25, 'pct_poisson' => 15, 'variation_poids_kg' => -1.2, 'duree_jours' => 14],
        ];
        $this->db->table('regime')->insertBatch($regimes);
        $regimeIds = $this->db->table('regime')->select('id')->get()->getResultArray();
        $regimeIds = array_column($regimeIds, 'id');

        // 4. RégimePrix (plusieurs durées par régime)
        $regimePrix = [];
        foreach ($regimeIds as $rid) {
            $base = rand(30, 100);
            $regimePrix[] = ['regime_id' => $rid, 'duree_jours' => 7, 'prix_base' => $base];
            $regimePrix[] = ['regime_id' => $rid, 'duree_jours' => 14, 'prix_base' => $base * 1.5];
            $regimePrix[] = ['regime_id' => $rid, 'duree_jours' => 30, 'prix_base' => $base * 2];
        }
        $this->db->table('regime_prix')->insertBatch($regimePrix);
        $regimePrixIds = $this->db->table('regime_prix')->select('id')->get()->getResultArray();
        $regimePrixIds = array_column($regimePrixIds, 'id');

        // 5. RégimeActivite (associe des activités aléatoires)
        foreach ($regimeIds as $rid) {
            $randActivites = array_rand($activiteIds, 2);
            foreach ((array)$randActivites as $aid) {
                $this->db->table('regime_activite')->insert([
                    'regime_id' => $rid,
                    'activite_id' => $activiteIds[$aid],
                    'frequence_semaine' => rand(2,5)
                ]);
            }
        }

        // 6. Utilisateurs (10 profils)
        $users = [];
        $objectifs = ['augmenter_poids', 'reduire_poids', 'imc_ideal'];
        $genres = ['homme', 'femme'];
        for ($i = 1; $i <= 10; $i++) {
            $taille = rand(155, 190);
            $poids = rand(55, 95);
            $users[] = [
                'nom' => "Nom$i",
                'prenom' => "Prenom$i",
                'email' => "user$i@test.com",
                'mot_de_passe' => password_hash('password', PASSWORD_DEFAULT),
                'date_naissance' => date('Y-m-d', strtotime("-".rand(18,60)." years")),
                'genre' => $genres[array_rand($genres)],
                'adresse' => "Adresse $i, Ville",
                'taille_cm' => $taille,
                'poids_kg' => $poids,
                'objectif' => $objectifs[array_rand($objectifs)],
                'created_at' => date('Y-m-d H:i:s', strtotime("-".rand(1,365)." days"))
            ];
        }
        $this->db->table('utilisateur')->insertBatch($users);
        $userIds = $this->db->table('utilisateur')->select('id')->get()->getResultArray();
        $userIds = array_column($userIds, 'id');

        // 7. Portefeuille (chaque utilisateur)
        foreach ($userIds as $uid) {
            $this->db->table('portefeuille')->insert([
                'utilisateur_id' => $uid,
                'solde_points' => rand(0, 500)
            ]);
        }

        // 8. Souscriptions (chaque utilisateur a 1 ou 2 régimes)
        $statuts = ['actif', 'termine', 'annule'];
        foreach ($userIds as $uid) {
            $nbSous = rand(1, 3);
            for ($j = 0; $j < $nbSous; $j++) {
                $rpId = $regimePrixIds[array_rand($regimePrixIds)];
                $debut = date('Y-m-d', strtotime("-".rand(1,180)." days"));
                $fin = date('Y-m-d', strtotime("+".rand(30,120)." days"));
                $statut = $statuts[array_rand($statuts)];
                $this->db->table('souscription_regime')->insert([
                    'utilisateur_id' => $uid,
                    'regime_prix_id' => $rpId,
                    'date_debut' => $debut,
                    'date_fin' => $fin,
                    'prix_paye' => rand(30, 150),
                    'remise_appliquee' => rand(0, 15),
                    'statut' => $statut,
                    'created_at' => date('Y-m-d H:i:s', strtotime("-".rand(1,60)." days"))
                ]);
            }
        }

        // 9. Historique poids (pour chaque utilisateur, 5 à 10 mesures)
        foreach ($userIds as $uid) {
            $poidsActuel = $this->db->table('utilisateur')->where('id', $uid)->get()->getRowArray()['poids_kg'];
            for ($k = 1; $k <= 8; $k++) {
                $joursAvant = rand(1, 120);
                $poidsAncien = $poidsActuel + rand(-5, 5);
                $this->db->table('historique_poids')->insert([
                    'utilisateur_id' => $uid,
                    'poids_kg' => max(40, $poidsAncien),
                    'mesure_le' => date('Y-m-d', strtotime("-$joursAvant days")),
                    'note' => "Mesure $k"
                ]);
            }
        }

        // 10. Codes bonus et transactions
        $codes = [
            ['code' => 'WELCOME10', 'valeur_points' => 100, 'est_valide' => 1],
            ['code' => 'GOLD2025', 'valeur_points' => 250, 'est_valide' => 1],
            ['code' => 'SPECIAL', 'valeur_points' => 500, 'est_valide' => 0],
        ];
        $this->db->table('code_bonus')->insertBatch($codes);
        $codeIds = $this->db->table('code_bonus')->select('id')->get()->getResultArray();
        $codeIds = array_column($codeIds, 'id');

        // Transactions pour chaque portefeuille
        $porteFeuilles = $this->db->table('portefeuille')->get()->getResultArray();
        foreach ($porteFeuilles as $pf) {
            $nbTransactions = rand(2, 5);
            for ($t = 0; $t < $nbTransactions; $t++) {
                $type = rand(0,1) ? 'credit' : 'debit';
                $montant = $type == 'credit' ? rand(50, 300) : rand(20, 150);
                $this->db->table('transaction_portefeuille')->insert([
                    'portefeuille_id' => $pf['id'],
                    'code_bonus_id' => $codeIds[array_rand($codeIds)],
                    'montant' => $montant,
                    'type' => $type,
                    'description' => "Transaction test $t",
                    'created_at' => date('Y-m-d H:i:s', strtotime("-".rand(1,90)." days"))
                ]);
            }
        }

        // Réactiver les contraintes
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        echo "✅ Données de test générées avec succès !\n";
    }
}