<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes statistiques – NutriPlan</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
<div class="dashboard-layout">
    <div class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <h1 class="page-title">📊 Mes statistiques</h1>
            </div>
            <div class="topbar-right">
                <a href="<?= site_url('dashboard') ?>" class="btn-outline">← Retour au tableau de bord</a>
            </div>
        </header>

        <main class="page-content">
            <div class="page-header">
                <p class="page-subtitle">Suivez votre progression</p>
            </div>

            <!-- Cartes KPI stats -->
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-value"><?= $imc ?? '—' ?></div>
                    <div class="kpi-label">IMC actuel</div>
                    <?php if ($categorie_imc): ?>
                        <div class="kpi-trend"><?= $categorie_imc ?></div>
                    <?php endif; ?>
                </div>
                <div class="kpi-card">
                    <div class="kpi-value"><?= $nb_regimes ?></div>
                    <div class="kpi-label">Régimes souscrits</div>
                </div>
                <?php if ($regime_actif): ?>
                <div class="kpi-card">
                    <div class="kpi-value">Actif</div>
                    <div class="kpi-label">Régime en cours</div>
                    <div class="kpi-trend"><?= esc($regime_actif['statut']) ?></div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Graphique évolution du poids -->
            <div class="chart-card" style="margin-bottom: 30px;">
                <div class="chart-card-header">
                    <div class="chart-card-title">📈 Évolution de votre poids</div>
                    <div class="chart-card-subtitle">kg</div>
                </div>
                <div class="chart-container bar">
                    <canvas id="weightChart" 
                        data-labels='<?= $poids_labels ?>' 
                        data-values='<?= $poids_values ?>'>
                    </canvas>
                </div>
            </div>

            <!-- Progression vers l'objectif -->
            <div class="chart-card">
                <div class="chart-card-header">
                    <div class="chart-card-title">🎯 Progression vers votre objectif</div>
                    <div class="chart-card-subtitle"><?= $objectif_data['label'] ?? 'Non défini' ?></div>
                </div>
                <?php if (!empty($objectif_data)): ?>
                    <div style="padding: 20px;">
                        <div style="margin-bottom: 15px;">
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
        </main>
    </div>
</div>

<script>
    // Graphique du poids
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
</script>

<style>
    .progress-bar {
        background-color: #e9ecef;
        border-radius: 20px;
        overflow: hidden;
        height: 30px;
    }
    .progress-fill {
        height: 100%;
        color: white;
        text-align: center;
        line-height: 30px;
        border-radius: 20px;
        font-size: 14px;
    }
</style>
</body>
</html>