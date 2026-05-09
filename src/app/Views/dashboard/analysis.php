<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Analyse des données – NutriPlan</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        .analysis-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin: 2rem;
        }
        .stats-cards {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            border-radius: 24px;
            padding: 1rem 1.5rem;
            text-align: center;
            min-width: 150px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #2D6A4F;
        }
        @media (max-width: 768px) {
            .analysis-grid { grid-template-columns: 1fr; margin: 1rem; }
        }
    </style>
</head>
<body>
<div class="dashboard-layout">
    <?= $this->include('bar/sidebar') ?>
    <div class="main-content">
        <header class="topbar">
            <h1 class="page-title">📊 Analyse des données</h1>
            <a href="<?= base_url('dashboard') ?>" class="btn-outline">← Retour</a>
        </header>

        <!-- KPI globaux -->
        <div class="stats-cards">
            <div class="stat-card"><div class="stat-number"><?= $global_stats['total_users'] ?></div><div>Utilisateurs</div></div>
            <div class="stat-card"><div class="stat-number"><?= $global_stats['total_regimes'] ?></div><div>Régimes</div></div>
            <div class="stat-card"><div class="stat-number"><?= $global_stats['total_subscriptions'] ?></div><div>Souscriptions</div></div>
            <div class="stat-card"><div class="stat-number"><?= number_format($global_stats['gold_revenue'], 2) ?> €</div><div>Revenus Gold</div></div>
        </div>

        <div class="analysis-grid">
            <!-- Objectifs -->
            <div class="chart-card">
                <div class="chart-card-title">🎯 Objectifs des utilisateurs</div>
                <canvas id="objectifChart" 
                    data-labels='<?= json_encode($objectif_dist['labels']) ?>'
                    data-values='<?= json_encode($objectif_dist['values']) ?>'>
                </canvas>
            </div>

            <!-- Top régimes -->
            <div class="chart-card">
                <div class="chart-card-title">🏆 Régimes les plus souscrits</div>
                <canvas id="topRegimesChart"
                    data-labels='<?= json_encode($top_regimes['labels']) ?>'
                    data-values='<?= json_encode($top_regimes['values']) ?>'>
                </canvas>
            </div>

            <!-- Répartition IMC -->
            <div class="chart-card">
                <div class="chart-card-title">🩺 Répartition des IMC</div>
                <canvas id="imcChart"
                    data-labels='<?= json_encode($imc_dist['labels']) ?>'
                    data-values='<?= json_encode($imc_dist['values']) ?>'
                    data-colors='<?= json_encode($imc_dist['colors']) ?>'>
                </canvas>
            </div>

            <!-- Évolution des inscriptions -->
            <div class="chart-card">
                <div class="chart-card-title">📈 Inscriptions (12 mois)</div>
                <canvas id="inscriptionsChart"
                    data-labels='<?= json_encode($inscriptions['labels']) ?>'
                    data-values='<?= json_encode($inscriptions['values']) ?>'>
                </canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // Graphique objectifs (donut)
    const objCtx = document.getElementById('objectifChart').getContext('2d');
    new Chart(objCtx, {
        type: 'doughnut',
        data: {
            labels: JSON.parse(objCtx.canvas.dataset.labels),
            datasets: [{ data: JSON.parse(objCtx.canvas.dataset.values), backgroundColor: ['#2D6A4F', '#52B788', '#D4A853', '#B4432B'] }]
        }
    });

    // Graphique top régimes (barres)
    const regCtx = document.getElementById('topRegimesChart').getContext('2d');
    new Chart(regCtx, {
        type: 'bar',
        data: {
            labels: JSON.parse(regCtx.canvas.dataset.labels),
            datasets: [{ label: 'Souscriptions', data: JSON.parse(regCtx.canvas.dataset.values), backgroundColor: '#2D6A4F' }]
        }
    });

    // Graphique IMC (donut)
    const imcCtx = document.getElementById('imcChart').getContext('2d');
    new Chart(imcCtx, {
        type: 'doughnut',
        data: {
            labels: JSON.parse(imcCtx.canvas.dataset.labels),
            datasets: [{ data: JSON.parse(imcCtx.canvas.dataset.values), backgroundColor: JSON.parse(imcCtx.canvas.dataset.colors) }]
        }
    });

    // Graphique inscriptions (line)
    const insCtx = document.getElementById('inscriptionsChart').getContext('2d');
    new Chart(insCtx, {
        type: 'line',
        data: {
            labels: JSON.parse(insCtx.canvas.dataset.labels),
            datasets: [{ label: 'Nouveaux utilisateurs', data: JSON.parse(insCtx.canvas.dataset.values), borderColor: '#2D6A4F', fill: true, backgroundColor: 'rgba(45,106,79,0.1)' }]
        }
    });
</script>
</body>
</html>