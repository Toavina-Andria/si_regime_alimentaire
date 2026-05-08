<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos services – NutriPlan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/services.css') ?>">
</head>

<body>
    <div class="dashboard-layout services-page">
        <!-- Topbar simplifié -->
        <div class="main-content" style="padding: 0;">
            <div class="services-hero">
                <h1>🍽️ Choisissez votre service</h1>
                <p>NutriPlan met à votre disposition tous les outils pour réussir votre transformation.</p>
                <div style="margin-top: 1rem;">
                    <a href="<?= site_url('dashboard') ?>" class="btn-outline" style="background: white;">← Tableau de bord</a>
                    <a href="<?= site_url('logout') ?>" class="btn-outline" style="margin-left: 0.5rem;">Déconnexion</a>
                </div>
            </div>

            <div class="services-grid">
                <!-- 1. Tableau de bord -->
                <a href="<?= site_url('dashboard') ?>" class="service-card">
                    <div class="service-icon">📊</div>
                    <div class="service-title">Tableau de bord</div>
                    <div class="service-desc">Votre IMC, objectifs, activités récentes en un coup d’œil.</div>
                    <div class="service-action">Accéder <span>→</span></div>
                </a>

                <!-- 2. Régimes -->
                <a href="<?= site_url('regimes') ?>" class="service-card">
                    <div class="service-icon">🥗</div>
                    <div class="service-title">Régimes</div>
                    <div class="service-desc">Parcourez tous les régimes, composition, prix, activités associées.</div>
                    <div class="service-action">Explorer <span>→</span></div>
                </a>

                <!-- 3. Statistiques -->
                <a href="<?= site_url('stats') ?>" class="service-card">
                    <div class="service-icon">📈</div>
                    <div class="service-title">Statistiques</div>
                    <div class="service-desc">Évolution du poids, IMC, progression vers votre but.</div>
                    <div class="service-action">Visualiser <span>→</span></div>
                </a>

                <!-- 4. acExport PDF -->
                <a href="<?= site_url('export/bilan') ?>" class="service-card">
                    <div class="service-icon">📄</div>
                    <div class="service-title">Export PDF</div>
                    <div class="service-desc">Téléchargez votre bilan personnel complet au format PDF.</div>
                    <div class="service-action">Télécharger <span>→</span></div>
                </a>

                <!-- 5. Mon profil -->
                <a href="<?= site_url('auth/profil') ?>" class="service-card">
                    <div class="service-icon">👤</div>
                    <div class="service-title">Mon profil</div>
                    <div class="service-desc">Modifiez vos informations personnelles et mesures (taille, poids).</div>
                    <div class="service-action">Modifier <span>→</span></div>
                </a>

                <!-- 6. Porte‑monnaie (code argent) -->
                <a href="<?= site_url('wallet') ?>" class="service-card">
                    <div class="service-icon">💰</div>
                    <div class="service-title">Porte‑monnaie</div>
                    <div class="service-desc">Ajoutez des crédits avec un code bonus, consultez vos points.</div>
                    <div class="service-action">Gérer <span>→</span></div>
                </a>
                <!-- 7. Analyse des données -->
                <a href="<?= site_url('analysis') ?>" class="service-card">
                    <div class="service-icon">📊</div>
                    <div class="service-title">Analyse des données</div>
                    <div class="service-desc">Statistiques avancées, tendances, top régimes et répartition des utilisateurs.</div>
                    <div class="service-action">Explorer <span>→</span></div>
                </a>
                <!-- Vous pouvez ajouter d’autres cartes selon vos besoins -->
            </div>
        </div>
    </div>
</body>

</html>