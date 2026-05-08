<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan — Tableau de bord</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Bebas+Neue&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
</head>
<body>
<div class="dashboard-layout">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-logo">
      <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
        <path d="M16 2C8.268 2 2 8.268 2 16s6.268 14 14 14 14-6.268 14-14S23.732 2 16 2z" fill="#2D6A4F"/>
        <path d="M16 6c-1.5 3-4.5 5-7 7 2 2.5 4 5.5 5 9 2.5-1.5 5-4 7-7-2.5-2-4.5-5-5-9z" fill="#D4A853" opacity="0.8"/>
        <path d="M11 20c3 1 6 2 8 4 2-2 5-3 8-4" stroke="#52B788" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
      <div class="sidebar-logo-text">
        NutriPlan
        <small>Admin Panel</small>
      </div>
    </div>

    <nav class="sidebar-nav">
      <div class="sidebar-section">
        <div class="sidebar-section-title">Vue d'ensemble</div>
        <a href="<?= site_url('admin/dashboard') ?>" class="sidebar-link active">
          <span class="icon">📊</span>
          Tableau de bord
        </a>
      </div>

      <div class="sidebar-section">
        <div class="sidebar-section-title">Gestion</div>
        <a href="<?= site_url('admin/regimes') ?>" class="sidebar-link">
          <span class="icon">🥗</span>
          Régimes alimentaires
        </a>
        <a href="<?= site_url('admin/activites') ?>" class="sidebar-link">
          <span class="icon">🏃</span>
          Activités sportives
        </a>
        <a href="<?= site_url('admin/utilisateurs') ?>" class="sidebar-link">
          <span class="icon">👥</span>
          Utilisateurs
        </a>
        <a href="<?= site_url('admin/codes') ?>" class="sidebar-link">
          <span class="icon">💰</span>
          Portefeuille & Codes
        </a>
      </div>

      <div class="sidebar-section">
        <div class="sidebar-section-title">Configuration</div>
        <a href="#" class="sidebar-link">
          <span class="icon">⚙️</span>
          Paramètres
        </a>
        <a href="#" class="sidebar-link">
          <span class="icon">📄</span>
          Logs & Historique
        </a>
      </div>
    </nav>

    <div class="sidebar-footer">
      <div class="sidebar-profile">
        <div class="sidebar-avatar"><?= strtoupper(substr(session()->get('user_nom') ?? 'A', 0, 1)) ?></div>
        <div class="sidebar-profile-info">
          <div class="sidebar-profile-name"><?= session()->get('user_nom') ?? 'Admin' ?></div>
          <div class="sidebar-profile-email"><?= session()->get('user_email') ?? '' ?></div>
        </div>
      </div>
      <a href="<?= site_url('logout') ?>" class="sidebar-logout">
        <span>🚪</span>
        Déconnexion
      </a>
    </div>
  </aside>

  <!-- Main Content -->
  <div class="main-content">

    <!-- Topbar -->
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
          <a href="<?= site_url('admin/dashboard') ?>">Accueil</a>
          <span>/</span>
          <span class="current">Tableau de bord</span>
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

    <!-- Page Content -->
    <main class="page-content">
      <div class="page-header">
        <h1 class="page-title">Tableau de bord</h1>
        <p class="page-subtitle">Vue d'ensemble de votre application NutriPlan</p>
      </div>

      <!-- KPI Cards -->
      <div class="kpi-grid">
        <div class="kpi-card">
          <div class="kpi-card-header">
            <div class="kpi-icon green">👥</div>
            <span class="kpi-trend up"><?= $kpi_users_trend ?>% ↑</span>
          </div>
          <div class="kpi-value" data-target="<?= $kpi_users ?>" data-prefix="">0</div>
          <div class="kpi-label">Utilisateurs inscrits</div>
        </div>

        <div class="kpi-card">
          <div class="kpi-card-header">
            <div class="kpi-icon dark-green">🥗</div>
          </div>
          <div class="kpi-value" data-target="<?= $kpi_regimes ?>">0</div>
          <div class="kpi-label">Régimes actifs</div>
        </div>

        <div class="kpi-card">
          <div class="kpi-card-header">
            <div class="kpi-icon gold">🎟️</div>
            <span class="kpi-trend up">+5% ↑</span>
          </div>
          <div class="kpi-value" data-target="<?= $kpi_codes ?>">0</div>
          <div class="kpi-label">Codes validés (mois)</div>
        </div>

        <div class="kpi-card">
          <div class="kpi-card-header">
            <div class="kpi-icon gold">⭐</div>
          </div>
          <div class="kpi-value" data-target="<?= $kpi_gold ?>" data-prefix="">0</div>
          <div class="kpi-label">Revenus option Gold (€)</div>
        </div>
      </div>

      <!-- Charts -->
      <div class="charts-grid">
        <div class="chart-card">
          <div class="chart-card-header">
            <div>
              <div class="chart-card-title">Évolution des inscriptions</div>
              <div class="chart-card-subtitle">12 derniers mois</div>
            </div>
          </div>
          <div class="chart-container bar">
            <canvas id="chartInscriptions"
              data-labels='<?= json_encode($chart_inscriptions['labels']) ?>'
              data-values='<?= json_encode($chart_inscriptions['values']) ?>'>
            </canvas>
          </div>
        </div>

        <div class="chart-card">
          <div class="chart-card-header">
            <div>
              <div class="chart-card-title">Répartition IMC</div>
              <div class="chart-card-subtitle">Profils utilisateurs</div>
            </div>
          </div>
          <div class="chart-container donut">
            <canvas id="chartIMC"
              data-labels='<?= json_encode($chart_imc['labels']) ?>'
              data-values='<?= json_encode($chart_imc['values']) ?>'
              data-colors='<?= json_encode($chart_imc['colors']) ?>'>
            </canvas>
          </div>
          <div class="chart-legend">
            <?php foreach ($chart_imc['labels'] as $i => $label): ?>
            <?php $v = $chart_imc['values'][$i]; ?>
            <?php $total = array_sum($chart_imc['values']); ?>
            <?php $pct = $total > 0 ? round(($v / $total) * 100, 1) : 0; ?>
            <div class="chart-legend-item">
              <span class="chart-legend-dot" style="background:<?= $chart_imc['colors'][$i] ?>"></span>
              <?= $label ?>
              <span class="chart-legend-value"><?= $pct ?>%</span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Bottom Grid -->
      <div class="bottom-grid">
        <div class="table-card">
          <div class="table-card-header">
            <div class="table-card-title">Derniers régimes créés</div>
            <a href="<?= site_url('admin/regimes') ?>" class="table-card-link">Voir tout →</a>
          </div>
          <table class="data-table">
            <thead>
              <tr>
                <th>Nom du régime</th>
                <th>% Viande</th>
                <th>% Poisson</th>
                <th>% Volaille</th>
                <th>Durée</th>
                <th>Prix</th>
                <th>Date</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($recent_regimes)): ?>
              <tr>
                <td colspan="8" style="text-align:center; color:var(--color-text-muted); padding:32px;">
                  Aucun régime pour le moment
                </td>
              </tr>
              <?php else: ?>
              <?php foreach ($recent_regimes as $r): ?>
              <tr>
                <td><strong><?= esc($r['nom']) ?></strong></td>
                <td><span class="badge badge-viande"><?= $r['pct_viande'] ?>%</span></td>
                <td><span class="badge badge-poisson"><?= $r['pct_poisson'] ?>%</span></td>
                <td><span class="badge badge-volaille"><?= $r['pct_volaille'] ?>%</span></td>
                <td><?= $r['duree_display'] ?></td>
                <td><?= $r['prix'] ?>€</td>
                <td><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
                <td>
                  <div class="action-btns">
                    <button class="action-btn" title="Modifier">✏️</button>
                    <button class="action-btn delete" title="Supprimer">🗑️</button>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <div class="activity-card">
          <div class="activity-card-title">Activité récente</div>
          <div class="activity-timeline">
            <?php if (empty($recent_activity)): ?>
            <div class="empty-state" style="padding:24px 0;">
              <div class="empty-state-text">Aucune activité récente</div>
            </div>
            <?php else: ?>
            <?php foreach ($recent_activity as $a): ?>
            <div class="activity-item">
              <span class="activity-dot <?= $a['type'] ?>"></span>
              <div class="activity-content">
                <div class="activity-text"><?= esc($a['text']) ?></div>
                <div class="activity-time"><?= $a['time'] ?></div>
              </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
</body>
</html>
