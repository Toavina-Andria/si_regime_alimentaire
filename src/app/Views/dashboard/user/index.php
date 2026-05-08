<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon tableau de bord — NutriPlan</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Bebas+Neue&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
  <style>
    .user-dashboard {
      min-height: 100vh;
      background: var(--color-bg);
      padding: 32px;
    }

    .ud-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 32px;
    }

    .ud-header-left {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .ud-avatar-large {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      background: var(--color-primary);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      font-weight: 600;
      flex-shrink: 0;
    }

    .ud-greeting h1 {
      font-family: var(--font-heading);
      font-size: 24px;
      font-weight: 600;
      color: var(--color-text-primary);
    }

    .ud-greeting p {
      font-size: 14px;
      color: var(--color-text-secondary);
    }

    .ud-logout {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 20px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 500;
      color: var(--color-text-secondary);
      transition: all 150ms ease;
      background: var(--color-surface);
      border: 1px solid var(--color-border);
    }

    .ud-logout:hover {
      background: rgba(193,57,43,0.08);
      color: var(--color-danger);
      border-color: transparent;
    }

    .ud-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
      margin-bottom: 24px;
    }

    .ud-grid-3 {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 24px;
      margin-bottom: 24px;
    }

    .ud-card {
      background: var(--color-surface);
      border-radius: 16px;
      padding: 24px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .ud-card-title {
      font-size: 14px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--color-text-muted);
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .ud-stat-value {
      font-family: var(--font-kpi);
      font-size: 36px;
      line-height: 1;
      color: var(--color-text-primary);
      margin-bottom: 4px;
    }

    .ud-stat-label {
      font-size: 13px;
      color: var(--color-text-secondary);
    }

    .ud-profile-info {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .ud-profile-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 0;
      border-bottom: 1px solid var(--color-border);
      font-size: 14px;
    }

    .ud-profile-row:last-child { border-bottom: none; }

    .ud-profile-label {
      color: var(--color-text-muted);
      font-weight: 500;
    }

    .ud-profile-value {
      color: var(--color-text-primary);
      font-weight: 600;
    }

    .ud-badge-gold {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 20px;
      background: linear-gradient(135deg, #D4A853, #F0C040);
      color: #1A1A1A;
      font-weight: 600;
      font-size: 13px;
      font-family: var(--font-heading);
      font-style: italic;
    }

    .ud-badge-free {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 20px;
      background: var(--color-bg);
      color: var(--color-text-secondary);
      font-weight: 600;
      font-size: 13px;
    }

    .ud-regime-card {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 16px;
      background: var(--color-bg);
      border-radius: 12px;
    }

    .ud-regime-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      background: rgba(45,106,79,0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      flex-shrink: 0;
    }

    .ud-regime-info h3 {
      font-size: 16px;
      font-weight: 600;
      color: var(--color-text-primary);
      margin-bottom: 2px;
    }

    .ud-regime-info p {
      font-size: 13px;
      color: var(--color-text-secondary);
    }

    .ud-regime-progress {
      margin-top: 16px;
    }

    .ud-progress-bar {
      height: 8px;
      background: var(--color-border);
      border-radius: 4px;
      overflow: hidden;
      margin-top: 8px;
    }

    .ud-progress-fill {
      height: 100%;
      background: var(--color-primary-light);
      border-radius: 4px;
      transition: width 300ms ease;
    }

    .ud-progress-label {
      display: flex;
      justify-content: space-between;
      font-size: 12px;
      color: var(--color-text-muted);
    }

    .ud-composition {
      display: flex;
      gap: 8px;
      margin-top: 12px;
    }

    .ud-comp-item {
      flex: 1;
      text-align: center;
      padding: 8px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
    }

    .ud-comp-viande { background: #FEF3C7; color: #92400E; }
    .ud-comp-poisson { background: #DBEAFE; color: #1E40AF; }
    .ud-comp-volaille { background: #D1FAE5; color: #065F46; }

    .ud-chart-container {
      height: 250px;
      position: relative;
    }

    .ud-wallet-balance {
      text-align: center;
      padding: 16px;
    }

    .ud-wallet-amount {
      font-family: var(--font-kpi);
      font-size: 48px;
      color: var(--color-primary);
      line-height: 1;
    }

    .ud-wallet-label {
      font-size: 14px;
      color: var(--color-text-muted);
      margin-top: 4px;
    }

    .ud-transaction {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid var(--color-border);
      font-size: 13px;
    }

    .ud-transaction:last-child { border-bottom: none; }

    .ud-transaction-amount.credit { color: var(--color-primary-light); font-weight: 600; }
    .ud-transaction-amount.debit { color: var(--color-danger); font-weight: 600; }

    .ud-transaction-date {
      color: var(--color-text-muted);
      font-size: 12px;
    }

    .ud-empty {
      text-align: center;
      padding: 32px 16px;
      color: var(--color-text-muted);
    }

    .ud-empty-icon {
      font-size: 36px;
      margin-bottom: 8px;
      opacity: 0.5;
    }

    .ud-goal-banner {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 16px 20px;
      border-radius: 12px;
      background: linear-gradient(135deg, rgba(45,106,79,0.08), rgba(82,183,136,0.08));
      border: 1px solid rgba(45,106,79,0.15);
    }

    .ud-goal-icon {
      font-size: 28px;
    }

    .ud-goal-text {
      font-size: 14px;
      color: var(--color-text-secondary);
    }

    .ud-goal-text strong {
      color: var(--color-primary);
    }

    .ud-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    .ud-table th {
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--color-text-muted);
      padding: 8px 12px;
      text-align: left;
      border-bottom: 1px solid var(--color-border);
    }

    .ud-table td {
      padding: 10px 12px;
      border-bottom: 1px solid var(--color-border);
      color: var(--color-text-primary);
    }

    .full-width {
      grid-column: 1 / -1;
    }

    @media (max-width: 900px) {
      .ud-grid, .ud-grid-3 { grid-template-columns: 1fr; }
      .user-dashboard { padding: 16px; }
    }
  </style>
</head>
<body>
<div class="user-dashboard">

  <!-- Header -->
  <div class="ud-header">
    <div class="ud-header-left">
      <div class="ud-avatar-large">
        <?= strtoupper(substr($user['prenom'] ?? 'U', 0, 1)) ?><?= strtoupper(substr($user['nom'] ?? '', 0, 1)) ?>
      </div>
      <div class="ud-greeting">
        <h1>Bonjour, <?= esc($user['prenom'] ?? 'Utilisateur') ?> 👋</h1>
        <p><?= esc($user['email']) ?> · Membre depuis <?= date('F Y', strtotime($user['created_at'] ?? 'now')) ?></p>
      </div>
    </div>
    <a href="<?= site_url('logout') ?>" class="ud-logout">
      <span>🚪</span>
      Déconnexion
    </a>
  </div>

  <!-- Goal Banner -->
  <?php if ($user['objectif']): ?>
  <div class="ud-goal-banner" style="margin-bottom:24px;">
    <span class="ud-goal-icon">
      <?php if ($user['objectif'] == 'reduire_poids'): ?>🎯
      <?php elseif ($user['objectif'] == 'augmenter_poids'): ?>💪
      <?php else: ?>⚖️
      <?php endif; ?>
    </span>
    <div class="ud-goal-text">
      Votre objectif actuel : <strong>
        <?php if ($user['objectif'] == 'reduire_poids'): ?>Perdre du poids
        <?php elseif ($user['objectif'] == 'augmenter_poids'): ?>Prendre du poids
        <?php else: ?>Atteindre votre IMC idéal
        <?php endif; ?>
      </strong>
      <?php if ($imc): ?> · IMC actuel : <strong><?= $imc['value'] ?></strong> (<?= $imc['label'] ?>)<?php endif; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- Stats row -->
  <div class="ud-grid-3">
    <div class="ud-card" style="text-align:center;">
      <div class="ud-stat-value"><?= $streak_days ?></div>
      <div class="ud-stat-label">🔥 Jours de streak</div>
    </div>
    <div class="ud-card" style="text-align:center;">
      <div class="ud-stat-value"><?= $total_days ?></div>
      <div class="ud-stat-label">📅 Total jours en régime</div>
    </div>
    <div class="ud-card" style="text-align:center;">
      <div class="ud-stat-value"><?php if ($imc): ?><?= $imc['value'] ?><?php else: ?>—<?php endif; ?></div>
      <div class="ud-stat-label">⚖️ IMC actuel</div>
    </div>
  </div>

  <!-- Main Grid -->
  <div class="ud-grid">

    <!-- Profile Card -->
    <div class="ud-card">
      <div class="ud-card-title">👤 Mon profil</div>
      <div class="ud-profile-info">
        <div class="ud-profile-row">
          <span class="ud-profile-label">Nom complet</span>
          <span class="ud-profile-value"><?= esc($user['prenom'] ?? '') ?> <?= esc($user['nom'] ?? '') ?></span>
        </div>
        <div class="ud-profile-row">
          <span class="ud-profile-label">Email</span>
          <span class="ud-profile-value"><?= esc($user['email'] ?? '') ?></span>
        </div>
        <div class="ud-profile-row">
          <span class="ud-profile-label">Taille</span>
          <span class="ud-profile-value"><?= $user['taille_cm'] ? $user['taille_cm'] . ' cm' : '—' ?></span>
        </div>
        <div class="ud-profile-row">
          <span class="ud-profile-label">Poids actuel</span>
          <span class="ud-profile-value"><?= $user['poids_kg'] ? $user['poids_kg'] . ' kg' : '—' ?></span>
        </div>
        <div class="ud-profile-row">
          <span class="ud-profile-label">Genre</span>
          <span class="ud-profile-value"><?= $user['genre'] ? ucfirst($user['genre']) : '—' ?></span>
        </div>
        <div class="ud-profile-row">
          <span class="ud-profile-label">Date naissance</span>
          <span class="ud-profile-value"><?= $user['date_naissance'] ? date('d/m/Y', strtotime($user['date_naissance'])) : '—' ?></span>
        </div>
      </div>
    </div>

    <!-- Subscription Card -->
    <div class="ud-card">
      <div class="ud-card-title">💳 Mon abonnement</div>
      <?php if ($subscription): ?>
      <div style="display:flex; align-items:center; gap:16px; margin-bottom:16px;">
        <?php if ($subscription['statut'] == 'gold'): ?>
        <span class="ud-badge-gold">⭐ Gold</span>
        <?php else: ?>
        <span class="ud-badge-free">🔹 Gratuit</span>
        <?php endif; ?>
      </div>
      <div class="ud-profile-info">
        <div class="ud-profile-row">
          <span class="ud-profile-label">Plan</span>
          <span class="ud-profile-value"><?= esc($subscription['abonnement_nom']) ?></span>
        </div>
        <?php if ($subscription['taux_reduction'] > 0): ?>
        <div class="ud-profile-row">
          <span class="ud-profile-label">Réduction</span>
          <span class="ud-profile-value" style="color:var(--color-accent);">-<?= $subscription['taux_reduction'] ?>%</span>
        </div>
        <?php endif; ?>
        <div class="ud-profile-row">
          <span class="ud-profile-label">Depuis le</span>
          <span class="ud-profile-value"><?= date('d/m/Y', strtotime($subscription['date_debut'])) ?></span>
        </div>
        <?php if ($subscription['date_fin']): ?>
        <div class="ud-profile-row">
          <span class="ud-profile-label">Expire le</span>
          <span class="ud-profile-value"><?= date('d/m/Y', strtotime($subscription['date_fin'])) ?></span>
        </div>
        <?php endif; ?>
      </div>
      <?php else: ?>
      <div class="ud-empty">
        <div class="ud-empty-icon">📋</div>
        <p>Aucun abonnement actif</p>
        <p style="font-size:13px;margin-top:4px;">Souscrivez à l'offre Gold pour profiter de réductions</p>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Current Regime -->
  <div class="ud-grid" style="margin-bottom:24px;">
    <div class="ud-card full-width">
      <div class="ud-card-title">🥗 Mon régime actuel</div>
      <?php if ($current_regime): ?>
      <?php
        $start = new \DateTime($current_regime['date_debut']);
        $end = new \DateTime($current_regime['date_fin']);
        $now = new \DateTime();
        $total_days_regime = $start->diff($end)->days ?: 1;
        $elapsed = $start->diff($now)->days;
        $progress_pct = min(round(($elapsed / $total_days_regime) * 100), 100);
      ?>
      <div class="ud-regime-card">
        <div class="ud-regime-icon">🥗</div>
        <div class="ud-regime-info" style="flex:1;">
          <h3><?= esc($current_regime['nom']) ?></h3>
          <p><?= esc($current_regime['description']) ?></p>
        </div>
        <div style="text-align:right;">
          <div style="font-size:20px;font-weight:600;color:var(--color-primary);"><?= $current_regime['prix_paye'] ?>€</div>
          <div style="font-size:12px;color:var(--color-text-muted);">payé</div>
        </div>
      </div>
      <div class="ud-regime-progress">
        <div class="ud-progress-label">
          <span>Jour <?= $elapsed ?> / <?= $total_days_regime ?></span>
          <span><?= $progress_pct ?>%</span>
        </div>
        <div class="ud-progress-bar">
          <div class="ud-progress-fill" style="width:<?= $progress_pct ?>%;"></div>
        </div>
      </div>
      <div class="ud-composition">
        <div class="ud-comp-item ud-comp-viande">🥩 Viande <?= $current_regime['pct_viande'] ?>%</div>
        <div class="ud-comp-item ud-comp-poisson">🐟 Poisson <?= $current_regime['pct_poisson'] ?>%</div>
        <div class="ud-comp-item ud-comp-volaille">🍗 Volaille <?= $current_regime['pct_volaille'] ?>%</div>
      </div>
      <?php else: ?>
      <div class="ud-empty">
        <div class="ud-empty-icon">🥗</div>
        <p>Aucun régime actif</p>
        <p style="font-size:13px;margin-top:4px;">Consultez la liste des régimes disponibles pour commencer</p>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Weight Chart + Wallet -->
  <div class="ud-grid">
    <div class="ud-card">
      <div class="ud-card-title">📈 Évolution du poids</div>
      <?php if (!empty($weight_history['values'])): ?>
      <div class="ud-chart-container">
        <canvas id="chartPoids"
          data-labels='<?= json_encode($weight_history['labels']) ?>'
          data-values='<?= json_encode($weight_history['values']) ?>'>
        </canvas>
      </div>
      <?php else: ?>
      <div class="ud-empty">
        <div class="ud-empty-icon">📊</div>
        <p>Aucun historique de poids</p>
        <p style="font-size:13px;margin-top:4px;">Les mesures apparaîtront ici au fur et à mesure</p>
      </div>
      <?php endif; ?>
    </div>

    <div class="ud-card">
      <div class="ud-card-title">💰 Mon portefeuille</div>
      <?php if ($wallet): ?>
      <div class="ud-wallet-balance">
        <div class="ud-wallet-amount"><?= number_format($wallet['solde_points'], 2) ?></div>
        <div class="ud-wallet-label">points disponibles</div>
      </div>
      <?php else: ?>
      <div class="ud-empty">
        <div class="ud-empty-icon">💰</div>
        <p>Portefeuille vide</p>
      </div>
      <?php endif; ?>

      <?php if (!empty($transactions)): ?>
      <div style="margin-top:16px;border-top:1px solid var(--color-border);padding-top:12px;">
        <div style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--color-text-muted);margin-bottom:8px;">Dernières transactions</div>
        <?php foreach ($transactions as $t): ?>
        <div class="ud-transaction">
          <div>
            <div><?= esc($t['description'] ?? ($t['type'] == 'credit' ? 'Crédit' : 'Débit')) ?></div>
            <div class="ud-transaction-date"><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></div>
          </div>
          <div class="ud-transaction-amount <?= $t['type'] ?>">
            <?= $t['type'] == 'credit' ? '+' : '-' ?><?= number_format(abs($t['montant']), 2) ?> pts
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Regime History -->
  <?php if (!empty($regime_history)): ?>
  <div style="margin-top:24px;">
    <div class="ud-card">
      <div class="ud-card-title">📋 Historique des régimes</div>
      <table class="ud-table">
        <thead>
          <tr>
            <th>Régime</th>
            <th>Début</th>
            <th>Fin</th>
            <th>Prix</th>
            <th>Statut</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($regime_history as $h): ?>
          <tr>
            <td><strong><?= esc($h['nom']) ?></strong></td>
            <td><?= date('d/m/Y', strtotime($h['date_debut'])) ?></td>
            <td><?= $h['date_fin'] ? date('d/m/Y', strtotime($h['date_fin'])) : '—' ?></td>
            <td><?= $h['prix_paye'] ?>€</td>
            <td>
              <?php if ($h['statut'] == 'termine'): ?>
              <span class="pill pill-success">Terminé</span>
              <?php elseif ($h['statut'] == 'annule'): ?>
              <span class="pill pill-danger">Annulé</span>
              <?php else: ?>
              <span class="pill pill-info"><?= ucfirst($h['statut']) ?></span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var canvas = document.getElementById('chartPoids');
  if (!canvas) return;

  var labels = JSON.parse(canvas.getAttribute('data-labels') || '[]');
  var values = JSON.parse(canvas.getAttribute('data-values') || '[]');
  if (labels.length === 0) return;

  new Chart(canvas, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Poids (kg)',
        data: values,
        borderColor: '#2D6A4F',
        backgroundColor: 'rgba(45,106,79,0.08)',
        borderWidth: 3,
        pointBackgroundColor: '#52B788',
        pointBorderColor: '#FFFFFF',
        pointBorderWidth: 2,
        pointRadius: 5,
        pointHoverRadius: 7,
        fill: true,
        tension: 0.4,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#1A1A1A',
          titleFont: { family: 'DM Sans', size: 12 },
          bodyFont: { family: 'DM Sans', size: 13, weight: '600' },
          padding: 10,
          cornerRadius: 8,
          callbacks: {
            label: function (ctx) { return ctx.parsed.y + ' kg'; }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: false,
          grid: { color: '#E2E4DC', borderDash: [4,4] },
          ticks: {
            font: { family: 'DM Sans', size: 12 },
            color: '#9CA3AF',
            callback: function (v) { return v + ' kg'; }
          }
        },
        x: {
          grid: { display: false },
          ticks: {
            font: { family: 'DM Sans', size: 11 },
            color: '#9CA3AF',
            maxTicksLimit: 10,
          }
        }
      }
    }
  });
});
</script>
</body>
</html>
