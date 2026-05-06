-- ============================================================
--  Création de la base de données - Application régime alimentaire
-- ============================================================

CREATE DATABASE IF NOT EXISTS `regime_alimentaire`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `regime_alimentaire`;

SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- Table : abonnement
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `abonnement` (
  `id`              INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `nom`             VARCHAR(100)     NOT NULL,
  `statut`          VARCHAR(50)      NOT NULL COMMENT 'ex: gold, silver, free',
  `taux_reduction`  DECIMAL(5,2)     NOT NULL DEFAULT 0.00 COMMENT 'en pourcentage, ex: 15.00',
  `prix`            DECIMAL(10,2)    NOT NULL DEFAULT 0.00,
  `description`     TEXT,
  `created_at`      TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_abonnement_statut` (`statut`)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table : utilisateur
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id`              INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `nom`             VARCHAR(100)     NOT NULL,
  `prenom`          VARCHAR(100)     NOT NULL,
  `email`           VARCHAR(255)     NOT NULL,
  `mot_de_passe`    VARCHAR(255)     NOT NULL COMMENT 'hash bcrypt',
  `date_naissance`  DATE,
  `genre`           ENUM('homme','femme','autre') NOT NULL,
  `adresse`         VARCHAR(255),
  `taille_cm`       DECIMAL(5,2)     COMMENT 'en centimètres',
  `poids_kg`        DECIMAL(5,2)     COMMENT 'en kilogrammes',
  `objectif`        ENUM('augmenter_poids','reduire_poids','imc_ideal') NOT NULL,
  `created_at`      TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_utilisateur_email` (`email`)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table : utilisateur_abonnement
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `utilisateur_abonnement` (
  `id`              INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `utilisateur_id`  INT UNSIGNED     NOT NULL,
  `abonnement_id`   INT UNSIGNED     NOT NULL,
  `date_debut`      DATE             NOT NULL,
  `date_fin`        DATE,
  `statut`          ENUM('actif','expire','annule') NOT NULL DEFAULT 'actif',
  `created_at`      TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_ua_utilisateur` (`utilisateur_id`),
  KEY `idx_ua_abonnement`  (`abonnement_id`),
  CONSTRAINT `fk_ua_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ua_abonnement`  FOREIGN KEY (`abonnement_id`)  REFERENCES `abonnement`   (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table : portefeuille
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `portefeuille` (
  `id`              INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `utilisateur_id`  INT UNSIGNED     NOT NULL,
  `solde_points`    DECIMAL(10,2)    NOT NULL DEFAULT 0.00,
  `updated_at`      TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_portefeuille_utilisateur` (`utilisateur_id`),
  CONSTRAINT `fk_portefeuille_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table : code_bonus
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `code_bonus` (
  `id`              INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `code`            VARCHAR(50)      NOT NULL,
  `valeur_points`   DECIMAL(10,2)    NOT NULL,
  `est_valide`      TINYINT(1)       NOT NULL DEFAULT 1,
  `expires_at`      TIMESTAMP        NULL,
  `created_at`      TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_code_bonus` (`code`),
  KEY `idx_cb_created_by` (`created_by`),
  CONSTRAINT `fk_cb_created_by` FOREIGN KEY (`created_by`) REFERENCES `utilisateur` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table : transaction_portefeuille
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `transaction_portefeuille` (
  `id`              INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `portefeuille_id` INT UNSIGNED     NOT NULL,
  `code_bonus_id`   INT UNSIGNED     NULL COMMENT 'NULL si transaction manuelle',
  `montant`         DECIMAL(10,2)    NOT NULL COMMENT 'positif = crédit, négatif = débit',
  `type`            ENUM('credit','debit') NOT NULL,
  `description`     VARCHAR(255),
  `created_at`      TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tp_portefeuille` (`portefeuille_id`),
  KEY `idx_tp_code_bonus`   (`code_bonus_id`),
  CONSTRAINT `fk_tp_portefeuille` FOREIGN KEY (`portefeuille_id`) REFERENCES `portefeuille` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tp_code_bonus`   FOREIGN KEY (`code_bonus_id`)   REFERENCES `code_bonus`   (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table : regime
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `regime` (
  `id`                  INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `nom`                 VARCHAR(150)  NOT NULL,
  `description`         TEXT,
  `pct_viande`          DECIMAL(5,2)  NOT NULL DEFAULT 0.00 COMMENT 'pourcentage viande',
  `pct_volaille`        DECIMAL(5,2)  NOT NULL DEFAULT 0.00,
  `pct_poisson`         DECIMAL(5,2)  NOT NULL DEFAULT 0.00,
  `variation_poids_kg`  DECIMAL(5,2)  NOT NULL COMMENT 'positif = prise, négatif = perte',
  `duree_jours`         INT UNSIGNED  NOT NULL COMMENT 'durée de référence',
  `created_at`          TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `chk_regime_pct` CHECK (`pct_viande` + `pct_volaille` + `pct_poisson` <= 100)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table : regime_prix
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `regime_prix` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `regime_id`   INT UNSIGNED  NOT NULL,
  `duree_jours` INT UNSIGNED  NOT NULL COMMENT 'ex: 7, 14, 30, 90',
  `prix_base`   DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_regime_duree` (`regime_id`, `duree_jours`),
  KEY `idx_rp_regime` (`regime_id`),
  CONSTRAINT `fk_rp_regime` FOREIGN KEY (`regime_id`) REFERENCES `regime` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table : activite_sportive
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `activite_sportive` (
  `id`              INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `nom`             VARCHAR(150)  NOT NULL,
  `description`     TEXT,
  `intensite`       TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '1=faible, 2=modéré, 3=intense',
  `calories_heure`  DECIMAL(7,2),
  `created_at`      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table : regime_activite
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `regime_activite` (
  `id`                INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `regime_id`         INT UNSIGNED     NOT NULL,
  `activite_id`       INT UNSIGNED     NOT NULL,
  `frequence_semaine` TINYINT UNSIGNED NOT NULL DEFAULT 2,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_regime_activite` (`regime_id`, `activite_id`),
  KEY `idx_ra_regime`   (`regime_id`),
  KEY `idx_ra_activite` (`activite_id`),
  CONSTRAINT `fk_ra_regime`   FOREIGN KEY (`regime_id`)   REFERENCES `regime`           (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ra_activite` FOREIGN KEY (`activite_id`) REFERENCES `activite_sportive` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table : souscription_regime
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `souscription_regime` (
  `id`                INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `utilisateur_id`    INT UNSIGNED  NOT NULL,
  `regime_prix_id`    INT UNSIGNED  NOT NULL,
  `date_debut`        DATE          NOT NULL,
  `date_fin`          DATE          NOT NULL,
  `prix_paye`         DECIMAL(10,2) NOT NULL,
  `remise_appliquee`  DECIMAL(5,2)  NOT NULL DEFAULT 0.00,
  `statut`            ENUM('actif','termine','annule') NOT NULL DEFAULT 'actif',
  `created_at`        TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sr_utilisateur` (`utilisateur_id`),
  KEY `idx_sr_regime_prix` (`regime_prix_id`),
  CONSTRAINT `fk_sr_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_sr_regime_prix` FOREIGN KEY (`regime_prix_id`) REFERENCES `regime_prix` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Table : historique_poids
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `historique_poids` (
  `id`              INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `utilisateur_id`  INT UNSIGNED  NOT NULL,
  `poids_kg`        DECIMAL(5,2)  NOT NULL,
  `note`            VARCHAR(255),
  `mesure_le`       DATE          NOT NULL,
  `created_at`      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_hp_utilisateur` (`utilisateur_id`),
  CONSTRAINT `fk_hp_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
--  Données initiales
-- ============================================================

INSERT INTO `abonnement` (`nom`, `statut`, `taux_reduction`, `prix`, `description`) VALUES
  ('Gratuit', 'free',  0.00,  0.00, 'Accès standard sans remise'),
  ('Gold',    'gold', 15.00, 29.99, 'Accès Gold - 15% de remise sur tous les régimes, paiement unique');

INSERT INTO `activite_sportive` (`nom`, `description`, `intensite`, `calories_heure`) VALUES
  ('Marche rapide', 'Marche à 6 km/h',             1, 300.00),
  ('Natation',      'Nage libre modérée',           2, 500.00),
  ('Course à pied', 'Jogging à 10 km/h',            2, 600.00),
  ('Musculation',   'Exercices avec charges',       2, 450.00),
  ('HIIT',          'Entraînement haute intensité', 3, 750.00),
  ('Vélo',          'Cyclisme modéré',              2, 480.00),
  ('Yoga',          'Yoga et étirements',           1, 200.00);