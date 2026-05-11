<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run()
    {

       $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        // ── Truncate all tables (order matters: children first) ──────────────
        $this->db->table('historique_poids')->truncate();
        $this->db->table('souscription_regime')->truncate();
        $this->db->table('utilisateur_abonnement')->truncate();
        $this->db->table('transaction_portefeuille')->truncate();
        $this->db->table('portefeuille')->truncate();
        $this->db->table('code_bonus')->truncate();
        $this->db->table('regime_activite')->truncate();
        $this->db->table('regime_prix')->truncate();
        $this->db->table('regime')->truncate();
        $this->db->table('activite_sportive')->truncate();
        $this->db->table('utilisateur')->truncate();
        $this->db->table('abonnement')->truncate();

        // ── 1. Abonnements ───────────────────────────────────────────────────
        $this->db->table('abonnement')->insertBatch([
            [
                'nom'            => 'Gratuit',
                'statut'         => 'free',
                'taux_reduction' => 0.00,
                'prix'           => 0.00,
                'description'    => 'Accès standard sans remise',
            ],
            [
                'nom'            => 'Gold',
                'statut'         => 'gold',
                'taux_reduction' => 15.00,
                'prix'           => 29.99,
                'description'    => 'Accès Gold – 15 % de remise sur tous les régimes, paiement unique',
            ],
        ]);

        // ── 2. Activités sportives (5) ───────────────────────────────────────
        $this->db->table('activite_sportive')->insertBatch([
            [
                'nom'           => 'Marche rapide',
                'description'   => 'Marche soutenue à 6 km/h, idéale pour débuter.',
                'intensite'     => 1,
                'calories_heure' => 300.00,
            ],
            [
                'nom'           => 'Natation',
                'description'   => 'Nage libre en piscine, travail cardio complet.',
                'intensite'     => 2,
                'calories_heure' => 500.00,
            ],
            [
                'nom'           => 'Course à pied',
                'description'   => 'Jogging à 10 km/h sur terrain plat.',
                'intensite'     => 2,
                'calories_heure' => 600.00,
            ],
            [
                'nom'           => 'Musculation',
                'description'   => 'Exercices avec charges libres et machines.',
                'intensite'     => 2,
                'calories_heure' => 450.00,
            ],
            [
                'nom'           => 'HIIT',
                'description'   => 'Entraînement fractionné haute intensité, 30 s effort / 15 s repos.',
                'intensite'     => 3,
                'calories_heure' => 750.00,
            ],
        ]);

        $activiteIds = array_column(
            $this->db->table('activite_sportive')->select('id')->get()->getResultArray(),
            'id'
        );

        // ── 3. Régimes (5) ───────────────────────────────────────────────────
        $this->db->table('regime')->insertBatch([
            [
                'nom'                => 'Good Chicken',
                'description'        => 'Régime riche en protéines de volaille pour la prise de masse musculaire.',
                'pct_viande'         => 20.00,
                'pct_volaille'       => 60.00,
                'pct_poisson'        => 0.00,
                'variation_poids_kg' => 2.00,
                'duree_jours'        => 30,
            ],
            [
                'nom'                => 'Fish & Fit',
                'description'        => 'Régime équilibré à forte proportion de poisson pour la santé cardiovasculaire.',
                'pct_viande'         => 10.00,
                'pct_volaille'       => 20.00,
                'pct_poisson'        => 60.00,
                'variation_poids_kg' => -1.50,
                'duree_jours'        => 30,
            ],
            [
                'nom'                => 'Vegan Power',
                'description'        => 'Régime végétalien riche en protéines végétales, zéro produit animal.',
                'pct_viande'         => 0.00,
                'pct_volaille'       => 0.00,
                'pct_poisson'        => 0.00,
                'variation_poids_kg' => 1.00,
                'duree_jours'        => 28,
            ],
            [
                'nom'                => 'Balanced Diet',
                'description'        => 'Régime équilibré viande / volaille / poisson pour une santé optimale.',
                'pct_viande'         => 33.33,
                'pct_volaille'       => 33.33,
                'pct_poisson'        => 33.34,
                'variation_poids_kg' => 0.00,
                'duree_jours'        => 30,
            ],
            [
                'nom'                => 'Régime Cétogène',
                'description'        => 'Low-carb, high-fat. Favorise la cétose pour une perte de poids rapide.',
                'pct_viande'         => 40.00,
                'pct_volaille'       => 25.00,
                'pct_poisson'        => 15.00,
                'variation_poids_kg' => -2.00,
                'duree_jours'        => 14,
            ],
        ]);

        $regimeIds = array_column(
            $this->db->table('regime')->select('id')->get()->getResultArray(),
            'id'
        );

        // ── 4. Prix des régimes (3 durées × 5 régimes) ──────────────────────
        $regimePrix = [];
        $prixBase = [49.99, 59.99, 44.99, 54.99, 39.99]; // un par régime
        foreach ($regimeIds as $i => $rid) {
            $base = $prixBase[$i];
            $regimePrix[] = ['regime_id' => $rid, 'duree_jours' => 30, 'prix_base' => $base];
            $regimePrix[] = ['regime_id' => $rid, 'duree_jours' => 60, 'prix_base' => round($base * 1.8, 2)];
            $regimePrix[] = ['regime_id' => $rid, 'duree_jours' => 90, 'prix_base' => round($base * 2.5, 2)];
        }
        $this->db->table('regime_prix')->insertBatch($regimePrix);

        $regimePrixIds = array_column(
            $this->db->table('regime_prix')->select('id')->get()->getResultArray(),
            'id'
        );

        // ── 5. Liaison régime ↔ activité (2 activités par régime) ────────────
        // Pairs fixes pour éviter les doublons
        $activitePairs = [
            [$activiteIds[0], $activiteIds[2]], // Good Chicken  : marche + course
            [$activiteIds[1], $activiteIds[3]], // Fish & Fit    : natation + muscu
            [$activiteIds[0], $activiteIds[4]], // Vegan Power   : marche + HIIT
            [$activiteIds[2], $activiteIds[3]], // Balanced Diet : course + muscu
            [$activiteIds[1], $activiteIds[4]], // Cétogène      : natation + HIIT
        ];
        $regimeActivite = [];
        foreach ($regimeIds as $i => $rid) {
            foreach ($activitePairs[$i] as $freq => $aid) {
                $regimeActivite[] = [
                    'regime_id'         => $rid,
                    'activite_id'       => $aid,
                    'frequence_semaine' => $freq === 0 ? 3 : 2,
                ];
            }
        }
        $this->db->table('regime_activite')->insertBatch($regimeActivite);

        // ── 6. Codes bonus (15) ──────────────────────────────────────────────
        $fmt = 'Y-m-d H:i:s';
        $this->db->table('code_bonus')->insertBatch([
            ['code' => 'STARTFIT15',   'valeur_points' => 15,  'est_valide' => 1, 'expires_at' => date($fmt, strtotime('+15 days'))],
            ['code' => 'GYMPOWER25',   'valeur_points' => 25,  'est_valide' => 1, 'expires_at' => date($fmt, strtotime('+45 days'))],
            ['code' => 'CARDIO50',     'valeur_points' => 50,  'est_valide' => 1, 'expires_at' => date($fmt, strtotime('+90 days'))],
            ['code' => 'MUSCLEBOOST35','valeur_points' => 35,  'est_valide' => 1, 'expires_at' => date($fmt, strtotime('+60 days'))],
            ['code' => 'SUMMERBODY75', 'valeur_points' => 75,  'est_valide' => 1, 'expires_at' => date($fmt, strtotime('+120 days'))],
            ['code' => 'FITLIFE100',   'valeur_points' => 100, 'est_valide' => 1, 'expires_at' => date($fmt, strtotime('+180 days'))],
            ['code' => 'ENERGYUP45',   'valeur_points' => 45,  'est_valide' => 1, 'expires_at' => date($fmt, strtotime('+75 days'))],
            ['code' => 'STRONG2026',   'valeur_points' => 60,  'est_valide' => 1, 'expires_at' => date($fmt, strtotime('+150 days'))],
            ['code' => 'WELLNESS80',   'valeur_points' => 80,  'est_valide' => 0, 'expires_at' => date($fmt, strtotime('-10 days'))],
            ['code' => 'NEWYEAR150',   'valeur_points' => 150, 'est_valide' => 1, 'expires_at' => date($fmt, strtotime('+365 days'))],
            ['code' => 'WELCOME10',    'valeur_points' => 10,  'est_valide' => 1, 'expires_at' => date($fmt, strtotime('+30 days'))],
            ['code' => 'BOOST200',     'valeur_points' => 200, 'est_valide' => 1, 'expires_at' => date($fmt, strtotime('+200 days'))],
            ['code' => 'SLIM30',       'valeur_points' => 30,  'est_valide' => 1, 'expires_at' => date($fmt, strtotime('+50 days'))],
            ['code' => 'KETO2026',     'valeur_points' => 70,  'est_valide' => 1, 'expires_at' => date($fmt, strtotime('+100 days'))],
            ['code' => 'EXPIRED99',    'valeur_points' => 99,  'est_valide' => 0, 'expires_at' => date($fmt, strtotime('-30 days'))],
        ]);

        $codeIds = array_column(
            $this->db->table('code_bonus')->select('id')->get()->getResultArray(),
            'id'
        );

        // ── 7. Utilisateurs : 1 admin + 5 utilisateurs normaux ───────────────
        $this->db->table('utilisateur')->insertBatch([
            // ---- Admin ----
            [
                'nom'            => 'Admin',
                'prenom'         => 'Super',
                'email'          => 'admin@regime-app.com',
                'mot_de_passe'   => password_hash('Admin@1234', PASSWORD_DEFAULT),
                'date_naissance' => '1990-01-15',
                'genre'          => 'homme',
                'adresse'        => 'Antananarivo, Ampefiloha',
                'taille_cm'      => 175.00,
                'poids_kg'       => 72.00,
                'objectif'       => 'imc_ideal',
                'est_admin'      => 1,
                'created_at'     => date('Y-m-d H:i:s', strtotime('-200 days')),
            ],
            // ---- Utilisateur 1 ----
            [
                'nom'            => 'Rakoto',
                'prenom'         => 'Jean',
                'email'          => 'jean.rakoto@example.com',
                'mot_de_passe'   => password_hash('Password1!', PASSWORD_DEFAULT),
                'date_naissance' => '1995-03-22',
                'genre'          => 'homme',
                'adresse'        => 'Antananarivo, Analakely',
                'taille_cm'      => 178.00,
                'poids_kg'       => 85.00,
                'objectif'       => 'reduire_poids',
                'est_admin'      => 0,
                'created_at'     => date('Y-m-d H:i:s', strtotime('-120 days')),
            ],
            // ---- Utilisateur 2 ----
            [
                'nom'            => 'Rabe',
                'prenom'         => 'Marie',
                'email'          => 'marie.rabe@example.com',
                'mot_de_passe'   => password_hash('Password2!', PASSWORD_DEFAULT),
                'date_naissance' => '1998-07-10',
                'genre'          => 'femme',
                'adresse'        => 'Fianarantsoa, Centre',
                'taille_cm'      => 162.00,
                'poids_kg'       => 58.00,
                'objectif'       => 'imc_ideal',
                'est_admin'      => 0,
                'created_at'     => date('Y-m-d H:i:s', strtotime('-90 days')),
            ],
            // ---- Utilisateur 3 ----
            [
                'nom'            => 'Randria',
                'prenom'         => 'Paul',
                'email'          => 'paul.randria@example.com',
                'mot_de_passe'   => password_hash('Password3!', PASSWORD_DEFAULT),
                'date_naissance' => '1988-11-05',
                'genre'          => 'homme',
                'adresse'        => 'Toamasina, Bazar Be',
                'taille_cm'      => 182.00,
                'poids_kg'       => 70.00,
                'objectif'       => 'augmenter_poids',
                'est_admin'      => 0,
                'created_at'     => date('Y-m-d H:i:s', strtotime('-60 days')),
            ],
            // ---- Utilisateur 4 ----
            [
                'nom'            => 'Rasoa',
                'prenom'         => 'Hanta',
                'email'          => 'hanta.rasoa@example.com',
                'mot_de_passe'   => password_hash('Password4!', PASSWORD_DEFAULT),
                'date_naissance' => '2000-05-18',
                'genre'          => 'femme',
                'adresse'        => 'Mahajanga, Amborovy',
                'taille_cm'      => 158.00,
                'poids_kg'       => 65.00,
                'objectif'       => 'reduire_poids',
                'est_admin'      => 0,
                'created_at'     => date('Y-m-d H:i:s', strtotime('-45 days')),
            ],
            // ---- Utilisateur 5 ----
            [
                'nom'            => 'Ramiandrisoa',
                'prenom'         => 'Luc',
                'email'          => 'luc.ramiandrisoa@example.com',
                'mot_de_passe'   => password_hash('Password5!', PASSWORD_DEFAULT),
                'date_naissance' => '1993-09-30',
                'genre'          => 'homme',
                'adresse'        => 'Antsiranana, Tanambao',
                'taille_cm'      => 170.00,
                'poids_kg'       => 75.00,
                'objectif'       => 'imc_ideal',
                'est_admin'      => 0,
                'created_at'     => date('Y-m-d H:i:s', strtotime('-30 days')),
            ],
        ]);

        $userIds = array_column(
            $this->db->table('utilisateur')->select('id')->get()->getResultArray(),
            'id'
        );

        // ── 8. Abonnements utilisateurs ──────────────────────────────────────
        // Admin → gold | users 1-2 → gold | users 3-5 → free
        $abonnements = $this->db->table('abonnement')->select('id, statut')->get()->getResultArray();
        $abonnementMap = array_column($abonnements, 'id', 'statut'); // ['free'=>1,'gold'=>2]

        $userAbonnements = [];
        foreach ($userIds as $i => $uid) {
            $isGold     = $i <= 2; // admin (0), user1 (1), user2 (2)
            $abId       = $isGold ? $abonnementMap['gold'] : $abonnementMap['free'];
            $userAbonnements[] = [
                'utilisateur_id' => $uid,
                'abonnement_id'  => $abId,
                'date_debut'     => date('Y-m-d', strtotime('-60 days')),
                'date_fin'       => $isGold ? date('Y-m-d', strtotime('+305 days')) : null,
                'statut'         => 'actif',
            ];
        }
        $this->db->table('utilisateur_abonnement')->insertBatch($userAbonnements);

        // ── 9. Portefeuilles ─────────────────────────────────────────────────
        $soldes = [500.00, 120.00, 75.00, 0.00, 200.00, 50.00];
        foreach ($userIds as $i => $uid) {
            $this->db->table('portefeuille')->insert([
                'utilisateur_id' => $uid,
                'solde_points'   => $soldes[$i],
            ]);
        }

        $portefeuilles = $this->db->table('portefeuille')->select('id, utilisateur_id')->get()->getResultArray();
        $pfMap = array_column($portefeuilles, 'id', 'utilisateur_id'); // uid → portefeuille_id

        // ── 10. Transactions portefeuille ────────────────────────────────────
        $transactions = [];
        foreach ($userIds as $uid) {
            $pfId = $pfMap[$uid];
            // 2 transactions par utilisateur
            $transactions[] = [
                'portefeuille_id' => $pfId,
                'code_bonus_id'   => $codeIds[0],
                'montant'         => 100.00,
                'type'            => 'credit',
                'description'     => 'Utilisation du code STARTFIT15',
                'created_at'      => date('Y-m-d H:i:s', strtotime('-40 days')),
            ];
            $transactions[] = [
                'portefeuille_id' => $pfId,
                'code_bonus_id'   => null,
                'montant'         => 50.00,
                'type'            => 'debit',
                'description'     => 'Achat de régime',
                'created_at'      => date('Y-m-d H:i:s', strtotime('-10 days')),
            ];
        }
        $this->db->table('transaction_portefeuille')->insertBatch($transactions);

        // ── 11. Souscriptions régimes ────────────────────────────────────────
        // Chaque user (hors admin) souscrit à 1 ou 2 régimes
        $souscriptions = [
            // jean (userId[1]) → Good Chicken 30j actif + Fish&Fit 60j terminé
            ['uid' => $userIds[1], 'rpid' => $regimePrixIds[0],  'debut' => '-30 days', 'fin' => '+0 days',  'prix' => 49.99, 'remise' => 15.00, 'statut' => 'actif'],
            ['uid' => $userIds[1], 'rpid' => $regimePrixIds[4],  'debut' => '-90 days', 'fin' => '-30 days', 'prix' => 59.99, 'remise' => 0.00,  'statut' => 'termine'],
            // marie (userId[2]) → Vegan Power 30j actif
            ['uid' => $userIds[2], 'rpid' => $regimePrixIds[6],  'debut' => '-15 days', 'fin' => '+15 days', 'prix' => 44.99, 'remise' => 15.00, 'statut' => 'actif'],
            // paul (userId[3]) → Good Chicken 60j actif
            ['uid' => $userIds[3], 'rpid' => $regimePrixIds[1],  'debut' => '-20 days', 'fin' => '+40 days', 'prix' => 89.98, 'remise' => 0.00,  'statut' => 'actif'],
            // hanta (userId[4]) → Cétogène 30j annulé
            ['uid' => $userIds[4], 'rpid' => $regimePrixIds[12], 'debut' => '-60 days', 'fin' => '-30 days', 'prix' => 39.99, 'remise' => 0.00,  'statut' => 'annule'],
            // luc (userId[5]) → Balanced Diet 30j actif
            ['uid' => $userIds[5], 'rpid' => $regimePrixIds[9],  'debut' => '-10 days', 'fin' => '+20 days', 'prix' => 54.99, 'remise' => 0.00,  'statut' => 'actif'],
        ];

        foreach ($souscriptions as $s) {
            $this->db->table('souscription_regime')->insert([
                'utilisateur_id'   => $s['uid'],
                'regime_prix_id'   => $s['rpid'],
                'date_debut'       => date('Y-m-d', strtotime($s['debut'])),
                'date_fin'         => date('Y-m-d', strtotime($s['fin'])),
                'prix_paye'        => $s['prix'],
                'remise_appliquee' => $s['remise'],
                'statut'           => $s['statut'],
                'created_at'       => date('Y-m-d H:i:s', strtotime($s['debut'])),
            ]);
        }

        // ── 12. Historique poids (5 mesures par utilisateur) ─────────────────
        $poidsInitiaux = [72.00, 85.00, 58.00, 70.00, 65.00, 75.00];
        foreach ($userIds as $i => $uid) {
            $base = $poidsInitiaux[$i];
            for ($k = 5; $k >= 1; $k--) {
                $variation = round(($k - 3) * 0.3, 2); // légère tendance
                $this->db->table('historique_poids')->insert([
                    'utilisateur_id' => $uid,
                    'poids_kg'       => max(40, $base + $variation),
                    'mesure_le'      => date('Y-m-d', strtotime("-{$k}0 days")),
                    'note'           => "Mesure semaine " . (6 - $k),
                ]);
            }
        }

        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        echo " DemoDataSeeder : données générées avec succès !\n";
        echo "   → 1 admin  + 5 utilisateurs\n";
        echo "   → 5 activités sportives\n";
        echo "   → 5 régimes  (3 durées de prix chacun)\n";
        echo "   → 15 codes bonus\n";
        echo "   → portefeuilles, transactions, souscriptions, historique poids\n";
    }
}