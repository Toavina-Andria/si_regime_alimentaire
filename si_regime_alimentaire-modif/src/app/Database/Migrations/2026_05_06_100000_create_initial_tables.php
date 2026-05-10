<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInitialTables extends Migration
{
    public function up()
    {
        // Table : abonnement
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nom' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'statut' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'comment' => 'ex: gold, silver, free',
            ],
            'taux_reduction' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0.00,
                'comment' => 'en pourcentage, ex: 15.00',
            ],
            'prix' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('statut', 'uq_abonnement_statut');
        $this->forge->createTable('abonnement');

        // Table : utilisateur
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nom' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'prenom' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'mot_de_passe' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'comment' => 'hash bcrypt',
            ],
            'date_naissance' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'genre' => [
                'type' => 'ENUM',
                'constraint' => ['homme', 'femme', 'autre'],
            ],
            'adresse' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'taille_cm' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'comment' => 'en centimètres',
            ],
            'poids_kg' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
                'comment' => 'en kilogrammes',
            ],
            'objectif' => [
                'type' => 'ENUM',
                'constraint' => ['augmenter_poids', 'reduire_poids', 'imc_ideal'],
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email', 'uq_utilisateur_email');
        $this->forge->createTable('utilisateur');

        // Table : utilisateur_abonnement
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'utilisateur_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'abonnement_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'date_debut' => [
                'type' => 'DATE',
            ],
            'date_fin' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'statut' => [
                'type' => 'ENUM',
                'constraint' => ['actif', 'expire', 'annule'],
                'default' => 'actif',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('utilisateur_id', false, false, 'idx_ua_utilisateur');
        $this->forge->addKey('abonnement_id', false, false, 'idx_ua_abonnement');
        $this->forge->addForeignKey('utilisateur_id', 'utilisateur', 'id', 'CASCADE', 'CASCADE', 'fk_ua_utilisateur');
        $this->forge->addForeignKey('abonnement_id', 'abonnement', 'id', 'RESTRICT', 'CASCADE', 'fk_ua_abonnement');
        $this->forge->createTable('utilisateur_abonnement');

        // Table : portefeuille
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'utilisateur_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'solde_points' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('utilisateur_id', 'uq_portefeuille_utilisateur');
        $this->forge->addForeignKey('utilisateur_id', 'utilisateur', 'id', 'CASCADE', 'CASCADE', 'fk_portefeuille_utilisateur');
        $this->forge->createTable('portefeuille');

        // Table : code_bonus
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'valeur_points' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'est_valide' => [
                'type' => 'TINYINT',
                'default' => 1,
            ],
            'expires_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code', 'uq_code_bonus');
        $this->forge->createTable('code_bonus');

        // Table : transaction_portefeuille
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'portefeuille_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'code_bonus_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'NULL si transaction manuelle',
            ],
            'montant' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'comment' => 'positif = crédit, négatif = débit',
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['credit', 'debit'],
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('portefeuille_id', false, false, 'idx_tp_portefeuille');
        $this->forge->addKey('code_bonus_id', false, false, 'idx_tp_code_bonus');
        $this->forge->addForeignKey('portefeuille_id', 'portefeuille', 'id', 'CASCADE', 'CASCADE', 'fk_tp_portefeuille');
        $this->forge->addForeignKey('code_bonus_id', 'code_bonus', 'id', 'SET NULL', 'CASCADE', 'fk_tp_code_bonus');
        $this->forge->createTable('transaction_portefeuille');

        // Table : regime
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nom' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'pct_viande' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0.00,
                'comment' => 'pourcentage viande',
            ],
            'pct_volaille' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0.00,
            ],
            'pct_poisson' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0.00,
            ],
            'variation_poids_kg' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'comment' => 'positif = prise, négatif = perte',
            ],
            'duree_jours' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'durée de référence',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('regime');

        // Table : regime_prix
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'regime_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'duree_jours' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment' => 'ex: 7, 14, 30, 90',
            ],
            'prix_base' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['regime_id', 'duree_jours'], 'uq_regime_duree');
        $this->forge->addKey('regime_id', false, false, 'idx_rp_regime');
        $this->forge->addForeignKey('regime_id', 'regime', 'id', 'CASCADE', 'CASCADE', 'fk_rp_regime');
        $this->forge->createTable('regime_prix');

        // Table : activite_sportive
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nom' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'intensite' => [
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => 1,
                'comment' => '1=faible, 2=modéré, 3=intense',
            ],
            'calories_heure' => [
                'type' => 'DECIMAL',
                'constraint' => '7,2',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('activite_sportive');

        // Table : regime_activite
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'regime_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'activite_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'frequence_semaine' => [
                'type' => 'TINYINT',
                'constraint' => 4,
                'unsigned' => true,
                'default' => 2,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['regime_id', 'activite_id'], 'uq_regime_activite');
        $this->forge->addKey('regime_id', false, false, 'idx_ra_regime');
        $this->forge->addKey('activite_id', false, false, 'idx_ra_activite');
        $this->forge->addForeignKey('regime_id', 'regime', 'id', 'CASCADE', 'CASCADE', 'fk_ra_regime');
        $this->forge->addForeignKey('activite_id', 'activite_sportive', 'id', 'CASCADE', 'CASCADE', 'fk_ra_activite');
        $this->forge->createTable('regime_activite');

        // Table : souscription_regime
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'utilisateur_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'regime_prix_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'date_debut' => [
                'type' => 'DATE',
            ],
            'date_fin' => [
                'type' => 'DATE',
            ],
            'prix_paye' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'remise_appliquee' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0.00,
            ],
            'statut' => [
                'type' => 'ENUM',
                'constraint' => ['actif', 'termine', 'annule'],
                'default' => 'actif',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('utilisateur_id', false, false, 'idx_sr_utilisateur');
        $this->forge->addKey('regime_prix_id', false, false, 'idx_sr_regime_prix');
        $this->forge->addForeignKey('utilisateur_id', 'utilisateur', 'id', 'CASCADE', 'CASCADE', 'fk_sr_utilisateur');
        $this->forge->addForeignKey('regime_prix_id', 'regime_prix', 'id', 'RESTRICT', 'CASCADE', 'fk_sr_regime_prix');
        $this->forge->createTable('souscription_regime');

        // Table : historique_poids
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'utilisateur_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'poids_kg' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
            ],
            'note' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'mesure_le' => [
                'type' => 'DATE',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('utilisateur_id', false, false, 'idx_hp_utilisateur');
        $this->forge->addForeignKey('utilisateur_id', 'utilisateur', 'id', 'CASCADE', 'CASCADE', 'fk_hp_utilisateur');
        $this->forge->createTable('historique_poids');
    }

    public function down()
    {
        $this->forge->dropTable('historique_poids', true);
        $this->forge->dropTable('souscription_regime', true);
        $this->forge->dropTable('regime_activite', true);
        $this->forge->dropTable('activite_sportive', true);
        $this->forge->dropTable('regime_prix', true);
        $this->forge->dropTable('regime', true);
        $this->forge->dropTable('transaction_portefeuille', true);
        $this->forge->dropTable('code_bonus', true);
        $this->forge->dropTable('portefeuille', true);
        $this->forge->dropTable('utilisateur_abonnement', true);
        $this->forge->dropTable('utilisateur', true);
        $this->forge->dropTable('abonnement', true);
    }
}
