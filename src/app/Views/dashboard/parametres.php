<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan — Paramètres</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Bebas+Neue&family=JetBrains+Mono:wght@400;500&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
</head>
<body>
<div class="dashboard-layout">

  <?= $this->include('bar/sidebar') ?>

  <div class="main-content">
    <header class="topbar">
      <div class="topbar-left">
        <button class="hamburger" aria-label="Menu">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="3" y1="6" x2="21" y2="6" /><line x1="3" y1="12" x2="21" y2="12" /><line x1="3" y1="18" x2="21" y2="18" />
          </svg>
        </button>
        <div class="breadcrumb">
          <a href="<?= base_url('admin/dashboard') ?>">Accueil</a>
          <span>/</span>
          <span class="current">Paramètres</span>
        </div>
      </div>
      <div class="topbar-right">
        <div class="topbar-search">
          <span class="search-icon">🔍</span>
          <input type="text" placeholder="Rechercher..." aria-label="Rechercher">
        </div>
        <button class="notification-btn" aria-label="Notifications">🔔<span class="notification-dot"></span></button>
      </div>
    </header>

    <main class="page-content">
      <div class="page-header">
        <h1 class="page-title">Paramètres</h1>
        <p class="page-subtitle">Configuration de la plateforme</p>
      </div>

      <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:20px;">
        <div class="kpi-card" style="cursor:pointer;">
          <div class="kpi-card-header">
            <div class="kpi-icon dark-green">⚙️</div>
          </div>
          <div class="kpi-label" style="font-size:16px; font-weight:600; margin-bottom:8px;">Configuration générale</div>
          <p style="font-size:13px; color:var(--color-text-muted);">Nom de la plateforme, email, maintenance...</p>
        </div>
        <div class="kpi-card" style="cursor:pointer;">
          <div class="kpi-card-header">
            <div class="kpi-icon gold">🔔</div>
          </div>
          <div class="kpi-label" style="font-size:16px; font-weight:600; margin-bottom:8px;">Notifications</div>
          <p style="font-size:13px; color:var(--color-text-muted);">Configurer les emails et alertes</p>
        </div>
        <div class="kpi-card" style="cursor:pointer;">
          <div class="kpi-card-header">
            <div class="kpi-icon green">🔒</div>
          </div>
          <div class="kpi-label" style="font-size:16px; font-weight:600; margin-bottom:8px;">Sécurité</div>
          <p style="font-size:13px; color:var(--color-text-muted);">Gestion des accès et permissions</p>
        </div>
        <div class="kpi-card" style="cursor:pointer;">
          <div class="kpi-card-header">
            <div class="kpi-icon" style="background:rgba(212,168,83,0.2);">📊</div>
          </div>
          <div class="kpi-label" style="font-size:16px; font-weight:600; margin-bottom:8px;">Préférences d'affichage</div>
          <p style="font-size:13px; color:var(--color-text-muted);">Personnaliser l'interface du back-office</p>
        </div>
      </div>
    </main>
  </div>
</div>

<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
</body>
</html>
