<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon tableau de bord — NutriPlan</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Bebas+Neue&family=JetBrains+Mono:wght@400;500&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/userdashboard.css') ?>">

</head>

<body>
  <div class="dashboard-layout">

    <?= $this->include('bar/sidebar') ?>
    <!-- Header -->
    <div class="main-content">

      <main class="page-content">
        <div class="ud-header">
          <div class="ud-header-left">
            <div class="ud-avatar-large">
              <?= strtoupper(substr($user['prenom'] ?? 'U', 0, 1)) ?><?= strtoupper(substr($user['nom'] ?? '', 0, 1)) ?>
            </div>
            <div class="ud-greeting">
              <h1>Bonjour, <?= esc($user['prenom'] ?? 'Utilisateur') ?> 👋</h1>
              <p><?= esc($user['email']) ?> · Membre depuis <?= date('F Y', strtotime($user['created_at'] ?? 'now')) ?>
              </p>
            </div>
          </div>
          <a href="<?= base_url('logout') ?>" class="ud-logout">
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
                <?= $objective ?>
              </strong>
              <?php if ($imc): ?> · IMC actuel : <strong><?= $imc ?></strong> (<?= $categorie_imc ?>)<?php endif; ?>
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
            <div class="ud-stat-value"><?php if ($imc): ?><?= $imc ?><?php else: ?>—<?php endif; ?></div>
            <div class="ud-stat-label">⚖️ IMC actuel</div>
          </div>
        </div>
        <div class="suggestions-section">
          <div class="section-header">
            <h2 class="section-title">🍽️ Régimes suggérés pour vous</h2>
            <p class="section-subtitle">
              Basés sur votre objectif :
              <strong>
                <?= $objective ?>
              </strong>
            </p>
          </div>

          <?php if (empty($suggestions)): ?>
            <div class="alert alert-info">
              Aucun régime ne correspond actuellement à votre objectif. Revenez plus tard ou modifiez votre profil.
            </div>
          <?php else: ?>
            <div class="suggestions-grid">
              <?php foreach ($suggestions as $s): ?>
                <?php $regime = $s['regime']; ?>
                <div class="suggestion-card">
                  <div class="suggestion-header">
                    <h3><?= esc($regime['nom']) ?></h3>
                    <?php $var = $regime['variation_poids_kg']; ?>
                    <?php if ($var > 0): ?>
                      <span class="badge gain">+<?= $var ?> kg</span>
                    <?php elseif ($var < 0): ?>
                      <span class="badge loss"><?= $var ?> kg</span>
                    <?php else: ?>
                      <span class="badge stable">Stable</span>
                    <?php endif; ?>
                  </div>
                  <p class="suggestion-desc"><?= esc($regime['description'] ?? 'Aucune description') ?></p>

                  <div class="suggestion-diet">
                    <span>🍖 Viande <?= $regime['pct_viande'] ?>%</span>
                    <span>🐟 Poisson <?= $regime['pct_poisson'] ?>%</span>
                    <span>🐔 Volaille <?= $regime['pct_volaille'] ?>%</span>
                  </div>

                  <?php if (!empty($s['prixOptions'])): ?>
                    <div class="suggestion-prices">
                      <strong>Tarifs :</strong>
                      <?php foreach ($s['prixOptions'] as $p): ?>
                        <span class="price-tag"><?= $p['duree_jours'] ?>j : <?= number_format($p['prix_base'], 2) ?>€</span>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>

                  <?php if (!empty($s['activites'])): ?>
                    <div class="suggestion-activities">
                      <strong>🏋️ Activités associées :</strong>
                      <ul>
                        <?php foreach ($s['activites'] as $act): ?>
                          <li><?= esc($act['nom']) ?> – <?= $act['frequence_semaine'] ?>x/semaine</li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                  <?php endif; ?>

                  <div class="suggestion-actions">
                    <a href="<?= base_url('regime/' . $regime['id']) ?>" class="btn-outline">Voir détail</a>
                    <button class="btn-primary btn-subscribe" data-id="<?= $regime['id'] ?>">Souscrire</button>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>


        <!-- Main Grid -->
        <div class="ud-grid">
          <!-- imc -->


          <!-- Section Suggestions personnalisées (version corrigée) -->

          <!-- KPI Cards -->


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
                <span
                  class="ud-profile-value"><?= $user['date_naissance'] ? date('d/m/Y', strtotime($user['date_naissance'])) : '—' ?></span>
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
                    <span class="ud-profile-value"
                      style="color:var(--color-accent);">-<?= $subscription['taux_reduction'] ?>%</span>
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
                  <div style="font-size:20px;font-weight:600;color:var(--color-primary);">
                    <?= $current_regime['prix_paye'] ?>€
                  </div>
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
                <canvas id="chartPoids" data-labels='<?= json_encode($weight_history['labels']) ?>'
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
                <div
                  style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--color-text-muted);margin-bottom:8px;">
                  Dernières transactions</div>
                <?php foreach ($transactions as $t): ?>
                  <div class="ud-transaction">
                    <div>
                      <div><?= esc($t['description'] ?? ($t['type'] == 'credit' ? 'Crédit' : 'Débit')) ?></div>
                      <div class="ud-transaction-date"><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></div>
                    </div>
                    <div class="ud-transaction-amount <?= $t['type'] ?>">
                      <?= $t['type'] == 'credit' ? '+' : '-' ?>     <?= number_format(abs($t['montant']), 2) ?> pts
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
    </main>
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
              grid: { color: '#E2E4DC', borderDash: [4, 4] },
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