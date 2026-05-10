<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($regime['nom']) ?> – NutriPlan</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
</head>
<body>
<div class="dashboard-layout">
    <?= $this->include('bar/sidebar') ?>
    <div class="main-content">
        <header class="topbar">
            <div class="topbar-left">
                <h1 class="page-title"><?= esc($regime['nom']) ?></h1>
            </div>
            <div class="topbar-right">
                <a href="<?= base_url('regimes') ?>" class="btn-outline">← Retour à la liste</a>
            </div>
        </header>

        <main class="page-content">
            <!-- Informations générales -->
            <div class="suggestion-card" style="max-width: 800px; margin-bottom: 20px;">
                <p><strong>Description :</strong> <?= nl2br(esc($regime['description'] ?? 'Aucune description')) ?></p>
                <p><strong>Variation de poids :</strong> 
                    <?php $var = $regime['variation_poids_kg']; ?>
                    <?= $var > 0 ? '+' : '' ?><?= $var ?> kg
                </p>
                <p><strong>Durée recommandée :</strong> <?= $regime['duree_jours'] ?> jours</p>
                <div class="suggestion-diet">
                    <span>🍖 Viande <?= $regime['pct_viande'] ?>%</span>
                    <span>🐟 Poisson <?= $regime['pct_poisson'] ?>%</span>
                    <span>🐔 Volaille <?= $regime['pct_volaille'] ?>%</span>
                </div>
            </div>

            <!-- Prix disponibles selon durée -->
            <?php if (!empty($prix)): ?>
            <div class="chart-card" style="margin-bottom: 20px;">
                <div class="chart-card-header">
                    <div class="chart-card-title">💰 Tarifs selon la durée</div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr><th>Durée</th><th>Prix</th><th></th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prix as $p): ?>
                        <tr>
                            <td><?= $p['duree_jours'] ?> jours</td>
                            <td><?= number_format($p['prix_base'], 2) ?> €</td>
                            <td>
                                <form method="POST" action="<?= base_url('regime/souscrire') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="regime_prix_id" value="<?= $p['id'] ?>">
                                    <button type="submit" class="btn-primary btn-sm">Souscrire</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <!-- Activités sportives associées -->
            <?php if (!empty($activites)): ?>
            <div class="chart-card">
                <div class="chart-card-header">
                    <div class="chart-card-title">🏋️ Activités sportives associées</div>
                </div>
                <div class="suggestions-grid" style="grid-template-columns: 1fr;">
                    <?php foreach ($activites as $act): ?>
                    <div class="suggestion-card">
                        <h3><?= esc($act['nom']) ?></h3>
                        <p><?= esc($act['description'] ?? 'Aucune description') ?></p>
                        <p>Intensité : <?= $act['intensite'] == 1 ? 'Faible' : ($act['intensite'] == 2 ? 'Modérée' : 'Intense') ?></p>
                        <p>Calories/heure : <?= $act['calories_heure'] ?> kcal</p>
                        <p>Fréquence recommandée : <?= $act['frequence_semaine'] ?>x/semaine</p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>
</div>
</body>
</html>