<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos services – NutriPlan</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
    <style>
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            padding: 2rem;
        }
        .service-card {
            background: white;
            border-radius: 32px;
            padding: 2rem 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 30px rgba(0,0,0,0.1);
        }
        .service-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .service-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #2D6A4F;
        }
        .service-desc {
            color: #6b7280;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        .page-header {
            text-align: center;
            margin: 2rem 0 1rem;
        }
        .page-title {
            font-size: 2.2rem;
            font-family: 'Playfair Display', serif;
        }
        @media (max-width: 640px) {
            .services-grid { padding: 1rem; gap: 1rem; }
            .service-card { padding: 1.5rem; }
        }
    </style>
</head>
<body>
<div class="dashboard-layout">
    <div class="main-content" style="background: #f5f7f6; min-height: 100vh;">
        <header class="topbar" style="background: white; padding: 1rem 2rem;">
            <div class="topbar-left">
                <h1 class="page-title">🍽️ Nos services</h1>
            </div>
            <div class="topbar-right">
                <a href="<?= site_url('dashboard') ?>" class="btn-outline">← Mon tableau de bord</a>
                <a href="<?= site_url('logout') ?>" class="btn-outline" style="margin-left: 1rem;">Déconnexion</a>
            </div>
        </header>

        <div class="page-header">
            <p class="page-subtitle">Choisissez un service pour atteindre vos objectifs</p>
        </div>

        <div class="services-grid">
            <!-- Carte : Tableau de bord -->
            <a href="<?= site_url('dashboard') ?>" class="service-card">
                <div class="service-icon">📊</div>
                <div class="service-title">Tableau de bord</div>
                <div class="service-desc">Vue d’ensemble de votre progression, IMC, objectifs.</div>
            </a>

            <!-- Carte : Régimes -->
            <a href="<?= site_url('regimes') ?>" class="service-card">
                <div class="service-icon">🥗</div>
                <div class="service-title">Régimes</div>
                <div class="service-desc">Consultez tous les régimes, leurs compositions et prix.</div>
            </a>

            <!-- Carte : Statistiques -->
            <a href="<?= site_url('stats') ?>" class="service-card">
                <div class="service-icon">📈</div>
                <div class="service-title">Statistiques</div>
                <div class="service-desc">Évolution du poids, IMC, progression vers l’objectif.</div>
            </a>

            <!-- Carte : Exporter mon bilan -->
            <a href="<?= site_url('export/bilan') ?>" class="service-card">
                <div class="service-icon">📄</div>
                <div class="service-title">Export PDF</div>
                <div class="service-desc">Téléchargez votre bilan personnel au format PDF.</div>
            </a>

            <!-- Carte : Mon profil (optionnel) -->
            <a href="<?= site_url('auth/profil') ?>" class="service-card">
                <div class="service-icon">👤</div>
                <div class="service-title">Mon profil</div>
                <div class="service-desc">Modifiez vos informations personnelles et santé.</div>
            </a>

            <!-- Carte : Code Argent (si implémenté) -->
            <a href="<?= site_url('wallet/code') ?>" class="service-card">
                <div class="service-icon">💰</div>
                <div class="service-title">Porte-monnaie</div>
                <div class="service-desc">Ajoutez des crédits avec un code bonus (bientôt).</div>
            </a>
        </div>
    </div>
</div>
</body>
</html>