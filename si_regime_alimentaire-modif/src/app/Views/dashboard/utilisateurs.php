<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan — Utilisateurs</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Bebas+Neue&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
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
            <line x1="3" y1="6" x2="21" y2="6"/>
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
          </svg>
        </button>
        <div class="breadcrumb">
          <a href="<?= base_url('admin/dashboard') ?>">Accueil</a>
          <span>/</span>
          <span class="current">Utilisateurs</span>
        </div>
      </div>
      <div class="topbar-right">
        <div class="topbar-search">
          <span class="search-icon">🔍</span>
          <input type="text" placeholder="Rechercher..." aria-label="Rechercher">
        </div>
        <button class="notification-btn" aria-label="Notifications">
          🔔
          <span class="notification-dot"></span>
        </button>
      </div>
    </header>

    <main class="page-content">
      <div class="page-header" style="display:flex; align-items:center; justify-content:space-between;">
        <div>
          <h1 class="page-title">Utilisateurs</h1>
          <p class="page-subtitle">Gérez les comptes utilisateurs de la plateforme</p>
        </div>
      </div>

      <div class="table-card">
        <table class="data-table">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Email</th>
              <th>Genre</th>
              <th>Taille</th>
              <th>Poids</th>
              <th>Objectif</th>
              <th>Inscrit le</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($utilisateurs)): ?>
            <tr>
              <td colspan="8" style="text-align:center; color:var(--color-text-muted); padding:48px;">
                <div style="font-size:36px; margin-bottom:12px; opacity:0.5;">👥</div>
                <div style="font-size:16px; font-weight:600; margin-bottom:4px;">Aucun utilisateur</div>
                <div style="font-size:13px;">Les utilisateurs inscrits apparaîtront ici</div>
              </td>
            </tr>
            <?php else: ?>
            <?php foreach ($utilisateurs as $u): ?>
            <tr>
              <td><strong><?= esc($u['nom']) ?></strong></td>
              <td><?= esc($u['prenom']) ?></td>
              <td><?= esc($u['email']) ?></td>
              <td><?= $u['genre'] ?? '—' ?></td>
              <td><?= $u['taille_cm'] ? $u['taille_cm'] . ' cm' : '—' ?></td>
              <td><?= $u['poids_kg'] ? $u['poids_kg'] . ' kg' : '—' ?></td>
              <td>
                <?php if ($u['objectif'] == 'augmenter_poids'): ?>
                <span class="pill pill-warning">↑ Prendre</span>
                <?php elseif ($u['objectif'] == 'reduire_poids'): ?>
                <span class="pill pill-danger">↓ Perdre</span>
                <?php elseif ($u['objectif'] == 'imc_ideal'): ?>
                <span class="pill pill-success">✓ IMC idéal</span>
                <?php else: ?>
                <span class="pill pill-info">—</span>
                <?php endif; ?>
              </td>
              <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
</body>
</html>
