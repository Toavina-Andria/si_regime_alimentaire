<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques – NutriPlan</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
<div class="dashboard-layout">
    <?= $this->include('bar/sidebar') ?>
    <div class="main-content">
        <main class="page-content">
            <button class="mobile-hamburger" aria-label="Menu">☰</button>

            <?php if (isset($total_users)): ?>
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-value"><?= $total_users ?></div>
                    <div class="kpi-label">Utilisateurs</div>
                    <?php if (isset($user_trend)): ?>
                        <div class="kpi-trend"><?= $user_trend > 0 ? '+' : '' ?><?= $user_trend ?>% (30j)</div>
                    <?php endif; ?>
                </div>
                <div class="kpi-card">
                    <div class="kpi-value"><?= $total_regimes ?></div>
                    <div class="kpi-label">Régimes</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-value"><?= $total_codes ?></div>
                    <div class="kpi-label">Codes valides</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-value"><?= number_format($total_gold_revenue, 2) ?>€</div>
                    <div class="kpi-label">Revenus Gold</div>
                </div>
            </div>

            <div class="charts-grid">
                <div class="chart-card">
                    <div class="chart-card-header">
                        <div class="chart-card-title">📈 Inscriptions</div>
                    </div>
                    <div class="chart-container">
                        <canvas id="chartInscriptions"
                            data-labels='<?= json_encode($inscriptions['labels'] ?? []) ?>'
                            data-values='<?= json_encode($inscriptions['values'] ?? []) ?>'>
                        </canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-card-header">
                        <div class="chart-card-title">🍽️ Répartition IMC</div>
                    </div>
                    <div class="chart-container">
                        <canvas id="chartIMC"
                            data-labels='<?= json_encode($imc_distribution['labels'] ?? []) ?>'
                            data-values='<?= json_encode($imc_distribution['values'] ?? []) ?>'
                            data-colors='<?= json_encode($imc_distribution['colors'] ?? []) ?>'>
                        </canvas>
                    </div>
                </div>
            </div>

            <?php if (!empty($recent_regimes)): ?>
            <div class="table-card">
                <h3>Derniers régimes</h3>
                <table class="data-table">
                    <thead><tr><th>Nom</th><th>% Viande</th><th>% Poisson</th><th>% Volaille</th><th>Durée</th><th>Prix</th></tr></thead>
                    <tbody>
                        <?php foreach ($recent_regimes as $r): ?>
                        <tr>
                            <td><?= esc($r['nom']) ?></td>
                            <td><?= $r['pct_viande'] ?>%</td>
                            <td><?= $r['pct_poisson'] ?>%</td>
                            <td><?= $r['pct_volaille'] ?>%</td>
                            <td><?= $r['duree_display'] ?? $r['duree_jours'] . ' jours' ?></td>
                            <td><?= $r['prix'] ?? '—' ?>€</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
            <?php endif; ?>

            <?php if (isset($imc)): ?>
            <div class="page-header">
                <p class="page-subtitle">Suivez votre progression</p>
            </div>
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-value"><?= $imc ?? '—' ?></div>
                    <div class="kpi-label">IMC actuel</div>
                    <?php if ($categorie_imc): ?>
                        <div class="kpi-trend"><?= $categorie_imc ?></div>
                    <?php endif; ?>
                </div>
                <div class="kpi-card">
                    <div class="kpi-value"><?= $nb_regimes ?? 0 ?></div>
                    <div class="kpi-label">Régimes souscrits</div>
                </div>
                <?php if (isset($regime_actif) && $regime_actif): ?>
                <div class="kpi-card">
                    <div class="kpi-value">Actif</div>
                    <div class="kpi-label">Régime en cours</div>
                    <div class="kpi-trend"><?= esc($regime_actif['statut']) ?></div>
                </div>
                <?php endif; ?>
            </div>

            <div class="chart-card chart-card-mb">
                <div class="chart-card-header">
                    <div class="chart-card-title">📈 Évolution de votre poids</div>
                    <div class="chart-card-subtitle">kg</div>
                </div>
                <div class="chart-container bar">
                    <canvas id="weightChart"
                        data-labels='<?= $poids_labels ?? '[]' ?>'
                        data-values='<?= $poids_values ?? '[]' ?>'>
                    </canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-card-header">
                    <div class="chart-card-title">🎯 Progression vers votre objectif</div>
                    <div class="chart-card-subtitle"><?= $objectif_data['label'] ?? 'Non défini' ?></div>
                </div>
                <?php if (!empty($objectif_data)): ?>
                    <div class="objective-padding">
                        <div class="objective-mb">
                            <strong>Actuel :</strong> <?= $objectif_data['actuel'] ?>
                            &nbsp;|&nbsp;
                            <strong>Cible :</strong> <?= $objectif_data['cible'] ?>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $objectif_data['pourcentage'] ?>%; background: #2D6A4F;">
                                <?= $objectif_data['pourcentage'] ?>%
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <p>Complétez votre profil pour voir votre progression.</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script>
<?php if (isset($total_users)): ?>
    new Chart(document.getElementById('chartInscriptions'), {
        type: 'line',
        data: {
            labels: JSON.parse(document.getElementById('chartInscriptions').dataset.labels),
            datasets: [{ label: 'Inscriptions', data: JSON.parse(document.getElementById('chartInscriptions').dataset.values), borderColor: '#2D6A4F', fill: true, backgroundColor: 'rgba(45,106,79,0.1)', tension: 0.3 }]
        }
    });
    new Chart(document.getElementById('chartIMC'), {
        type: 'doughnut',
        data: {
            labels: JSON.parse(document.getElementById('chartIMC').dataset.labels),
            datasets: [{ data: JSON.parse(document.getElementById('chartIMC').dataset.values), backgroundColor: JSON.parse(document.getElementById('chartIMC').dataset.colors) }]
        }
    });
<?php endif; ?>
<?php if (isset($imc)): ?>
    const ctx = document.getElementById('weightChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: JSON.parse(document.getElementById('weightChart').dataset.labels),
            datasets: [{
                label: 'Poids (kg)',
                data: JSON.parse(document.getElementById('weightChart').dataset.values),
                borderColor: '#2D6A4F',
                backgroundColor: 'rgba(45, 106, 79, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: { callbacks: { label: (ctx) => `${ctx.raw} kg` } }
            }
        }
    });
<?php endif; ?>
</script>


</body>
</html>
