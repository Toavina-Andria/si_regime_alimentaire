<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan — Tableau de bord</title>
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

    <!-- Main Content -->
    <div class="main-content">

      <!-- Topbar -->
      <header class="topbar">
        <div class="topbar-left">
          <button class="hamburger" aria-label="Menu">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round">
              <line x1="3" y1="6" x2="21" y2="6" />
              <line x1="3" y1="12" x2="21" y2="12" />
              <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
          </button>
          <div class="breadcrumb">
            <a href="<?= base_url('admin/dashboard') ?>">Accueil</a>
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
        <!-- Carte IMC personnelle -->
        <div class="kpi-card">
          <div class="kpi-card-header">
            <div class="kpi-icon green">⚖️</div>
          </div>
          <div class="kpi-value"><?= $imc ?? '—' ?></div>
          <div class="kpi-label">Votre IMC</div>
          <?php if ($categorie_imc): ?>
            <div style="font-size:0.8rem; margin-top:5px;">Catégorie : <?= $categorie_imc ?></div>
          <?php endif; ?>
          <a href="<?= site_url('export/bilan') ?>" class="btn-outline" style="display: inline-block; margin-top: 10px;">📄 Exporter mon bilan PDF</a>
            <a href="<?= site_url('regimes') ?>" class="btn-outline" style="display: inline-block; margin-top: 10px; margin-left: 10px;">🥗 Voir tous les régimes</a>
        </div>

        <!-- Section Suggestions personnalisées (version corrigée) -->
        <div class="suggestions-section">
          <div class="section-header">
            <h2 class="section-title">🍽️ Régimes suggérés pour vous</h2>
            <p class="section-subtitle">
              Basés sur votre objectif :
              <strong>
                <?php
                $obj = $user['objectif'] ?? '';
                if ($obj === 'augmenter_poids') echo 'Prendre du poids';
                elseif ($obj === 'reduire_poids') echo 'Perdre du poids';
                elseif ($obj === 'imc_ideal') echo 'Atteindre votre IMC idéal';
                else echo 'Non défini';
                ?>
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
                    <a href="<?= site_url('regime/' . $regime['id']) ?>" class="btn-outline">Voir détail</a>
                    <button class="btn-primary btn-subscribe" data-id="<?= $regime['id'] ?>">Souscrire</button>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
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
              <canvas id="chartInscriptions" data-labels='<?= json_encode($chart_inscriptions['labels']) ?>'
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
              <canvas id="chartIMC" data-labels='<?= json_encode($chart_imc['labels']) ?>'
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
              <a href="<?= base_url('admin/regimes') ?>" class="table-card-link">Voir tout →</a>
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
