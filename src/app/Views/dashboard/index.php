<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>NutriPlan — Tableau de bord</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
</head>
<body>
<div class="dashboard-layout">
    <?= view('bar/sidebar') ?>
    <div class="main-content">
        <main class="page-content">
            <button class="mobile-hamburger" aria-label="Menu">☰</button>

            <?php if (isset($user) && $user): ?>
            <div class="user-greeting">
                <h2>Bonjour, <?= esc($user['prenom']) ?> 🎉</h2>
                <p><?= esc($user['email']) ?> · Membre depuis <?= date('F Y', strtotime($user['created_at'])) ?></p>
                <div class="user-stats">
                    <div class="stat-badge">🎯 Objectif :
                        <?php if ($objective === 'reduire_poids'): ?>Perdre du poids
                        <?php elseif ($objective === 'augmenter_poids'): ?>Prendre du poids
                        <?php elseif ($objective === 'imc_ideal'): ?>Atteindre IMC idéal
                        <?php else: ?>Non défini
                        <?php endif; ?>
                    </div>
                    <div class="stat-badge">⚖️ IMC : <?= $imc ?? '—' ?> (<?= $categorie_imc ?? 'Non calculé' ?>)</div>
                    <div class="stat-badge">🔥 Streak : <?= $streak_days ?? 0 ?> jours</div>
                    <div class="stat-badge">📅 Total : <?= $total_days ?? 0 ?> jours</div>
                </div>
            </div>

            <div class="suggestions-section">
                <h2>🍽️ Régimes suggérés pour vous</h2>
                <?php if (empty($suggestions)): ?>
                    <p>Aucun régime pour le moment.</p>
                <?php else: ?>
                    <div class="suggestions-grid">
                        <?php foreach ($suggestions as $s): $r = $s['regime']; ?>
                        <div class="suggestion-card">
                            <h3><?= esc($r['nom']) ?></h3>
                            <p><?= esc($r['description'] ?? '') ?></p>
                            <div class="suggestion-diet">
                                <span>🥩 Viande <?= $r['pct_viande'] ?>%</span>
                                <span>🐟 Poisson <?= $r['pct_poisson'] ?>%</span>
                                <span>🍗 Volaille <?= $r['pct_volaille'] ?>%</span>
                            </div>
                            <?php if (!empty($s['prixOptions'])): ?>
                                <div><?php foreach ($s['prixOptions'] as $p): ?><span class="price-tag"><?= $p['duree_jours'] ?>j : <?= number_format($p['prix_base'],2) ?>€</span><?php endforeach; ?></div>
                            <?php endif; ?>
                            <a href="<?= site_url('regime/' . $r['id']) ?>" class="btn-outline">Voir détail</a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bottom-grid">
                <div class="table-card"><h3>👤 Mon profil</h3>
                    <table class="data-table">
                        <tr><td>Nom</td><td><?= esc($user['nom']) ?> <?= esc($user['prenom']) ?></td></tr>
                        <tr><td>Email</td><td><?= esc($user['email']) ?></td></tr>
                        <tr><td>Taille</td><td><?= $user['taille_cm'] ?? '—' ?> cm</td></tr>
                        <tr><td>Poids</td><td><?= $user['poids_kg'] ?? '—' ?> kg</td></tr>
                    </table>
                </div>
                <div class="activity-card">
                    <h3>💰 Portefeuille</h3>
                    <div class="stat-number"><?= number_format($wallet['solde_points'] ?? 0, 2) ?> points</div>
                    <hr>
                    <h3>⭐ Abonnement</h3>
                    <?php if ($subscription): ?>
                        <p><?= esc($subscription['nom']) ?> (<?= ucfirst($subscription['statut']) ?>) depuis <?= date('d/m/Y', strtotime($subscription['date_debut'])) ?></p>
                    <?php else: ?>
                        <p>Aucun abonnement actif</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!isset($user) && isset($kpi_users)): ?>
            <div class="kpi-grid">
                <div class="kpi-card"><div class="kpi-value"><?= $kpi_users ?></div><div class="kpi-label">Utilisateurs</div></div>
                <div class="kpi-card"><div class="kpi-value"><?= $kpi_regimes ?></div><div class="kpi-label">Régimes</div></div>
                <div class="kpi-card"><div class="kpi-value"><?= $kpi_codes ?></div><div class="kpi-label">Codes (mois)</div></div>
                <div class="kpi-card">
                    <div class="kpi-value"><?= $kpi_users_trend > 0 ? '+' : '' ?><?= $kpi_users_trend ?>%</div>
                    <div class="kpi-label">Croissance (30j)</div>
                    <div class="kpi-trend <?= $kpi_users_trend >= 0 ? 'up' : 'down' ?>"><?= $kpi_users_trend >= 0 ? '↑' : '↓' ?> <?= abs($kpi_users_trend) ?>%</div>
                </div>
            </div>

            <div class="revenue-card">
                <div class="revenue-total">
                    <div class="revenue-total-label">Revenus Total</div>
                    <div class="revenue-total-amount"><?= number_format($total_revenue, 2) ?>€</div>
                    <div class="revenue-total-period">Depuis le lancement</div>
                </div>
                <div class="revenue-divider"></div>
                <div class="revenue-detail">
                    <div class="revenue-detail-label">Répartition</div>
                    <?php
                    $gold_pct = $total_revenue > 0 ? round(($gold_revenue / $total_revenue) * 100, 1) : 0;
                    $std_pct = $total_revenue > 0 ? round(($standard_revenue / $total_revenue) * 100, 1) : 0;
                    ?>
                    <div class="revenue-bar-wrapper">
                        <div class="revenue-bar">
                            <div class="revenue-bar-fill gold" style="width: <?= $gold_pct ?>%;"></div>
                            <div class="revenue-bar-fill standard" style="width: <?= $std_pct ?>%;"></div>
                        </div>
                    </div>
                    <div class="revenue-stats">
                        <div class="revenue-stat">
                            <span class="revenue-dot gold"></span>
                            <span class="revenue-stat-label">Gold</span>
                            <span class="revenue-stat-amount"><?= number_format($gold_revenue, 2) ?>€</span>
                            <span class="revenue-stat-pct">(<?= $gold_pct ?>%)</span>
                        </div>
                        <div class="revenue-stat">
                            <span class="revenue-dot standard"></span>
                            <span class="revenue-stat-label">Standard</span>
                            <span class="revenue-stat-amount"><?= number_format($standard_revenue, 2) ?>€</span>
                            <span class="revenue-stat-pct">(<?= $std_pct ?>%)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="charts-grid">
                <div class="chart-card">
                    <canvas id="chartInscriptions" data-labels='<?= json_encode($chart_inscriptions['labels']) ?>' data-values='<?= json_encode($chart_inscriptions['values']) ?>'></canvas>
                </div>
                <div class="chart-card">
                    <canvas id="chartIMC" data-labels='<?= json_encode($chart_imc['labels']) ?>' data-values='<?= json_encode($chart_imc['values']) ?>' data-colors='<?= json_encode($chart_imc['colors']) ?>'></canvas>
                </div>
            </div>

            <div class="table-card">
                <h3>Derniers régimes créés</h3>
                <table class="data-table">
                    <thead><tr><th>Nom</th><th>% Viande</th><th>% Poisson</th><th>% Volaille</th><th>Durée</th><th>Prix</th><th>Date</th></tr></thead>
                    <tbody>
                        <?php foreach ($recent_regimes as $r): ?>
                        <tr>
                            <td><?= esc($r['nom']) ?></td>
                            <td><?= $r['pct_viande'] ?>%</td>
                            <td><?= $r['pct_poisson'] ?>%</td>
                            <td><?= $r['pct_volaille'] ?>%</td>
                            <td><?= $r['duree_display'] ?></td>
                            <td><?= $r['prix'] ?>€</td>
                            <td><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
            <?= $this->include('bar/footer') ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<?php if (!isset($user)): ?>
<script>
    new Chart(document.getElementById('chartInscriptions'), {
        type: 'line',
        data: { labels: JSON.parse(document.getElementById('chartInscriptions').dataset.labels), datasets: [{ label: 'Inscriptions', data: JSON.parse(document.getElementById('chartInscriptions').dataset.values), borderColor: '#2D6A4F' }] }
    });
    new Chart(document.getElementById('chartIMC'), {
        type: 'doughnut',
        data: { labels: JSON.parse(document.getElementById('chartIMC').dataset.labels), datasets: [{ data: JSON.parse(document.getElementById('chartIMC').dataset.values), backgroundColor: JSON.parse(document.getElementById('chartIMC').dataset.colors) }] }
    });
</script>
<?php endif; ?>
</body>
</html>