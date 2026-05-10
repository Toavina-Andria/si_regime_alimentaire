<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan — Statistiques</title>
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
          <span class="current">Statistiques</span>
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
        <h1 class="page-title">Statistiques</h1>
        <p class="page-subtitle">Indicateurs clés et analyse des données</p>
      </div>

      <div class="kpi-grid">
        <div class="kpi-card">
          <div class="kpi-card-header">
            <div class="kpi-icon dark-green">👥</div>
          </div>
          <div class="kpi-value"><?= $global_stats['total_users'] ?? 0 ?></div>
          <div class="kpi-label">Utilisateurs</div>
        </div>
        <div class="kpi-card">
          <div class="kpi-card-header">
            <div class="kpi-icon green">🥗</div>
          </div>
          <div class="kpi-value"><?= $global_stats['total_regimes'] ?? 0 ?></div>
          <div class="kpi-label">Régimes</div>
        </div>
        <div class="kpi-card">
          <div class="kpi-card-header">
            <div class="kpi-icon gold">📋</div>
          </div>
          <div class="kpi-value"><?= $global_stats['total_subscriptions'] ?? 0 ?></div>
          <div class="kpi-label">Souscriptions</div>
        </div>
        <div class="kpi-card">
          <div class="kpi-card-header">
            <div class="kpi-icon" style="background:rgba(212,168,83,0.2);">💰</div>
          </div>
          <div class="kpi-value"><?= number_format($global_stats['gold_revenue'] ?? 0, 0) ?>€</div>
          <div class="kpi-label">Revenus Gold</div>
        </div>
      </div>

      <div class="charts-grid">
        <div class="chart-card">
          <div class="chart-card-header">
            <div class="chart-card-title">📈 Inscriptions</div>
            <div class="chart-card-subtitle">12 derniers mois</div>
          </div>
          <div class="chart-container bar">
            <canvas id="inscriptionsChart"
              data-labels='<?= json_encode($inscriptions_trend['labels'] ?? []) ?>'
              data-values='<?= json_encode($inscriptions_trend['values'] ?? []) ?>'>
            </canvas>
          </div>
        </div>
        <div class="chart-card">
          <div class="chart-card-header">
            <div class="chart-card-title">🎯 Objectifs</div>
            <div class="chart-card-subtitle">Répartition</div>
          </div>
          <div class="chart-container donut">
            <canvas id="objectifsChart"
              data-labels='<?= json_encode($chart_objectifs['labels'] ?? []) ?>'
              data-values='<?= json_encode($chart_objectifs['values'] ?? []) ?>'>
            </canvas>
          </div>
        </div>
      </div>

      <div class="charts-grid">
        <div class="chart-card">
          <div class="chart-card-header">
            <div class="chart-card-title">🥇 Top régimes</div>
            <div class="chart-card-subtitle">Les plus souscrits</div>
          </div>
          <div class="chart-container bar">
            <canvas id="topRegimesChart"
              data-labels='<?= json_encode($chart_top_regimes['labels'] ?? []) ?>'
              data-values='<?= json_encode($chart_top_regimes['values'] ?? []) ?>'>
            </canvas>
          </div>
        </div>
        <div class="chart-card">
          <div class="chart-card-header">
            <div class="chart-card-title">⚖️ IMC</div>
            <div class="chart-card-subtitle">Distribution</div>
          </div>
          <div class="chart-container donut">
            <canvas id="imcChart"
              data-labels='<?= json_encode($chart_imc['labels'] ?? []) ?>'
              data-values='<?= json_encode($chart_imc['values'] ?? []) ?>'
              data-colors='<?= json_encode($chart_imc['colors'] ?? []) ?>'>
            </canvas>
          </div>
        </div>
      </div>

      <div class="table-card">
        <div class="table-card-header">
          <div class="table-card-title">Statistiques globales</div>
        </div>
        <table class="data-table">
          <thead>
            <tr><th>Indicateur</th><th>Valeur</th></tr>
          </thead>
          <tbody>
            <tr><td>Total utilisateurs</td><td><strong><?= $global_stats['total_users'] ?? 0 ?></strong></td></tr>
            <tr><td>Total régimes</td><td><strong><?= $global_stats['total_regimes'] ?? 0 ?></strong></td></tr>
            <tr><td>Total souscriptions</td><td><strong><?= $global_stats['total_subscriptions'] ?? 0 ?></strong></td></tr>
            <tr><td>Revenus Gold</td><td><strong><?= number_format($global_stats['gold_revenue'] ?? 0, 2) ?> €</strong></td></tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  function initBar(id, label) {
    var el = document.getElementById(id);
    if (!el) return;
    var labels = JSON.parse(el.dataset.labels || '[]');
    var values = JSON.parse(el.dataset.values || '[]');
    if (!labels.length) return;
    new Chart(el, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: label,
          data: values,
          backgroundColor: 'rgba(45,106,79,0.7)',
          borderColor: '#2D6A4F',
          borderWidth: 1,
          borderRadius: 6
        }]
      },
      options: { responsive: true, plugins: { legend: { display: false } } }
    });
  }
  function initDoughnut(id) {
    var el = document.getElementById(id);
    if (!el) return;
    var labels = JSON.parse(el.dataset.labels || '[]');
    var values = JSON.parse(el.dataset.values || '[]');
    var colors = JSON.parse(el.dataset.colors || '["#2D6A4F","#52B788","#D4A853","#B4432B"]');
    if (!labels.length) return;
    new Chart(el, {
      type: 'doughnut',
      data: {
        labels: labels,
        datasets: [{ data: values, backgroundColor: colors, borderWidth: 0 }]
      },
      options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
  }
  initBar('inscriptionsChart', 'Inscriptions');
  initBar('topRegimesChart', 'Souscriptions');
  initDoughnut('objectifsChart');
  initDoughnut('imcChart');
});
</script>
</body>
</html>
