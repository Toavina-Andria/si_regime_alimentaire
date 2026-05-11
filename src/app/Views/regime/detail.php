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
        <main class="page-content">

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php elseif (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="page-header page-header-row">
                <div>
                    <h1 class="page-title"><?= esc($regime['nom']) ?></h1>
                    <p class="page-subtitle">Détails du régime alimentaire</p>
                </div>
                <div class="regime-header-actions">
                    <?php if ($wallet): ?>
                        <span class="points-badge">⭐ <?= number_format($wallet['solde_points'], 2) ?> points</span>
                    <?php else: ?>
                        <span class="points-badge-empty">⭐ 0 point</span>
                    <?php endif; ?>
                    <a href="<?= base_url('regimes') ?>" class="btn-outline">← Retour</a>
                </div>
            </div>

            <div class="regime-hero suggestion-card">
                <p class="suggestion-desc"><?= nl2br(esc($regime['description'] ?? 'Aucune description')) ?></p>

                <div class="regime-stats">
                    <span class="regime-stat">📅 <strong><?= $regime['duree_jours'] ?></strong> jours</span>
                    <span class="regime-stat">
                        ⚖️ <strong><?php $v = $regime['variation_poids_kg']; ?><?= $v > 0 ? '+' : '' ?><?= $v ?></strong> kg
                    </span>
                    <span class="badge <?= $regime['variation_poids_kg'] > 0 ? 'gain' : ($regime['variation_poids_kg'] < 0 ? 'loss' : 'stable') ?>">
                        <?= $regime['variation_poids_kg'] > 0 ? 'Prise de poids' : ($regime['variation_poids_kg'] < 0 ? 'Perte de poids' : 'Stable') ?>
                    </span>
                </div>

                <div class="diet-composition">
                    <div class="diet-row">
                        <span class="diet-label">🥩 Viande</span>
                        <div class="diet-bar"><div class="diet-bar-fill viande" style="width:<?= $regime['pct_viande'] ?>%"></div></div>
                        <span class="diet-pct"><?= $regime['pct_viande'] ?>%</span>
                    </div>
                    <div class="diet-row">
                        <span class="diet-label">🐔 Volaille</span>
                        <div class="diet-bar"><div class="diet-bar-fill volaille" style="width:<?= $regime['pct_volaille'] ?>%"></div></div>
                        <span class="diet-pct"><?= $regime['pct_volaille'] ?>%</span>
                    </div>
                    <div class="diet-row">
                        <span class="diet-label">🐟 Poisson</span>
                        <div class="diet-bar"><div class="diet-bar-fill poisson" style="width:<?= $regime['pct_poisson'] ?>%"></div></div>
                        <span class="diet-pct"><?= $regime['pct_poisson'] ?>%</span>
                    </div>
                </div>
            </div>

            <?php if (!empty($prix)): ?>
            <div class="chart-card regime-section">
                <div class="chart-card-header">
                    <div class="chart-card-title">💰 Tarifs</div>
                    <div class="chart-card-subtitle">Choisissez la durée de votre abonnement</div>
                </div>
                <div class="price-grid">
                    <?php $best = null; $bestVal = PHP_INT_MAX;
                    foreach ($prix as $p) { $ppj = $p['prix_base'] / $p['duree_jours']; if ($ppj < $bestVal) { $bestVal = $ppj; $best = $p['id']; } } ?>
                    <?php foreach ($prix as $p):
                        $ppj = $p['prix_base'] / $p['duree_jours'];
                        $isBest = $p['id'] === $best;
                    ?>
                    <div class="price-card <?= $isBest ? 'featured' : '' ?>">
                        <?php if ($isBest): ?><div class="badge-reco">Meilleur rapport</div><?php endif; ?>
                        <div class="duration"><?= $p['duree_jours'] ?> jours</div>
                        <div class="amount"><?= number_format($p['prix_base'], 2) ?> <small>€</small></div>
                        <div class="regime-price-perday">
                            soit <?= number_format($ppj, 2) ?> €/jour
                        </div>
                        <form method="POST" action="<?= base_url('regime/souscrire') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="regime_prix_id" value="<?= $p['id'] ?>">
                            <button type="submit" class="btn btn-primary btn-sm">Souscrire</button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($activites)): ?>
            <div class="chart-card" style="max-width:860px;margin:0 auto 2rem;">
                <div class="chart-card-header">
                    <div class="chart-card-title">🏋️ Activités sportives associées</div>
                    <div class="chart-card-subtitle">Activités recommandées pour ce régime</div>
                </div>
                <div class="suggestions-grid" style="padding:1.5rem;">
                    <?php foreach ($activites as $act): ?>
                    <div class="suggestion-card">
                        <div class="suggestion-header">
                            <h3><?= esc($act['nom']) ?></h3>
                            <span class="badge <?= $act['intensite'] == 3 ? 'loss' : ($act['intensite'] == 2 ? 'gain' : 'stable') ?>">
                                <?= $act['intensite'] == 1 ? 'Faible' : ($act['intensite'] == 2 ? 'Modérée' : 'Intense') ?>
                            </span>
                        </div>
                        <p style="font-size:0.85rem;color:var(--color-text-secondary);margin:0.5rem 0;">
                            <?= esc($act['description'] ?? '') ?>
                        </p>
                        <div style="display:flex;gap:1rem;font-size:0.85rem;color:var(--color-text-muted);margin-top:0.75rem;border-top:1px solid var(--color-border);padding-top:0.75rem;">
                            <span>🔥 <?= $act['calories_heure'] ?> kcal/h</span>
                            <span>📅 <?= $act['frequence_semaine'] ?>x/semaine</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?= $this->include('bar/footer') ?>
        </main>
    </div>
</div>
</body>
</html>
