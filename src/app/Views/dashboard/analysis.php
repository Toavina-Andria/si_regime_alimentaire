<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Analyse des données – NutriPlan</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
<div class="dashboard-layout">
    <?= view('bar/sidebar') ?>
    <div class="main-content">
        <div class="page-content">
            <button class="mobile-hamburger" aria-label="Menu">☰</button>

        <div class="stats-cards">
            <div class="stat-card"><div class="stat-number"><?= $global_stats['total_users'] ?></div><div>Utilisateurs</div></div>
            <div class="stat-card"><div class="stat-number"><?= $global_stats['total_regimes'] ?></div><div>Régimes</div></div>
            <div class="stat-card"><div class="stat-number"><?= $global_stats['total_subscriptions'] ?></div><div>Souscriptions</div></div>
            <div class="stat-card"><div class="stat-number"><?= number_format($global_stats['gold_revenue'], 2) ?> €</div><div>Revenus Gold</div></div>
        </div>

        <div class="analysis-grid">

            <div class="chart-card">
                <div class="chart-card-title">🎯 Objectifs des utilisateurs</div>
                <canvas id="objectifChart" data-labels='<?= json_encode($objectif_dist['labels']) ?>' data-values='<?= json_encode($objectif_dist['values']) ?>'></canvas>
            </div>

            <div class="chart-card">
                <div class="chart-card-title">🏆 Régimes les plus souscrits</div>
                <canvas id="topRegimesChart" data-labels='<?= json_encode($top_regimes['labels']) ?>' data-values='<?= json_encode($top_regimes['values']) ?>'></canvas>
            </div>

            <div class="chart-card">
                <div class="chart-card-title">🩺 Répartition des IMC</div>
                <canvas id="imcChart" data-labels='<?= json_encode($imc_dist['labels']) ?>' data-values='<?= json_encode($imc_dist['values']) ?>' data-colors='<?= json_encode($imc_dist['colors']) ?>'></canvas>
            </div>

            <div class="chart-card">
                <div class="chart-card-title">📈 Inscriptions (12 mois)</div>
                <canvas id="inscriptionsChart" data-labels='<?= json_encode($inscriptions['labels']) ?>' data-values='<?= json_encode($inscriptions['values']) ?>'></canvas>
            </div>
            <?= $this->include('bar/footer') ?>
        </div>
    </div>
</div>
</div>

<script>
    new Chart(document.getElementById('objectifChart'), { type: 'doughnut', data: { labels: JSON.parse(document.getElementById('objectifChart').dataset.labels), datasets: [{ data: JSON.parse(document.getElementById('objectifChart').dataset.values), backgroundColor: ['#2D6A4F','#52B788','#D4A853','#B4432B'] }] } });
    new Chart(document.getElementById('topRegimesChart'), { type: 'bar', data: { labels: JSON.parse(document.getElementById('topRegimesChart').dataset.labels), datasets: [{ label: 'Souscriptions', data: JSON.parse(document.getElementById('topRegimesChart').dataset.values), backgroundColor: '#2D6A4F' }] } });
    new Chart(document.getElementById('imcChart'), { type: 'doughnut', data: { labels: JSON.parse(document.getElementById('imcChart').dataset.labels), datasets: [{ data: JSON.parse(document.getElementById('imcChart').dataset.values), backgroundColor: JSON.parse(document.getElementById('imcChart').dataset.colors) }] } });
    new Chart(document.getElementById('inscriptionsChart'), { type: 'line', data: { labels: JSON.parse(document.getElementById('inscriptionsChart').dataset.labels), datasets: [{ label: 'Nouveaux utilisateurs', data: JSON.parse(document.getElementById('inscriptionsChart').dataset.values), borderColor: '#2D6A4F', fill: true, backgroundColor: 'rgba(45,106,79,0.1)' }] } });
</script>
</body>
</html>