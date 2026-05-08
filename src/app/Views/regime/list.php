<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos régimes – NutriPlan</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
</head>
<body>
    <div class="dashboard-layout">
        <!-- Main Content direct (pas de sidebar partielle) -->
        <div class="main-content">
            <header class="topbar">
                <div class="topbar-left">
                    <h1 class="page-title">🥗 Tous les régimes</h1>
                </div>
                <div class="topbar-right">
                    <a href="<?= base_url('dashboard') ?>" class="btn-outline">← Retour au tableau de bord</a>
                </div>
            </header>

            <main class="page-content">
                <div class="suggestions-grid">
                    <?php foreach ($regimes as $regime): ?>
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
                            <?php if ($regime['prix_depart']): ?>
                                <div class="suggestion-prices">
                                    À partir de <strong><?= number_format($regime['prix_depart'], 2) ?>€</strong> 
                                    (<?= $regime['duree_min'] ?> jours)
                                </div>
                            <?php endif; ?>
                            <div class="suggestion-actions">
                                <a href="<?= base_url('regime/' . $regime['id']) ?>" class="btn-primary">Voir le détail</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>