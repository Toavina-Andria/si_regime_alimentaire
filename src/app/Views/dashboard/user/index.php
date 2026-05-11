<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord – NutriPlan</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
</head>
<body>
<div class="dashboard-layout">
    <?= view('bar/sidebar') ?>
    <div class="main-content">
        <main class="page-content">
            <button class="mobile-hamburger" aria-label="Menu">☰</button>

            <div class="user-greeting">
                <h2>Bonjour, <?= esc($user['prenom']) ?> 🎉</h2>
                <p><?= esc($user['email']) ?> · Membre depuis <?= date('F Y', strtotime($user['created_at'])) ?></p>
                <div class="user-stats">
                    <div class="stat-badge">🎯 Objectif :
                        <?php if ($objective === 'reduire_poids'): ?>Perdre du poids
                        <?php elseif ($objective === 'augmenter_poids'): ?>Prendre du poids
                        <?php elseif ($objective === 'imc_ideal'): ?>IMC idéal<?php else: ?>Non défini<?php endif; ?>
                    </div>
                    <div class="stat-badge">⚖️ IMC : <?= $imc ?? '—' ?> (<?= $categorie_imc ?? 'Non calculé' ?>)</div>
                    <div class="stat-badge">🔥 Streak : <?= $streak_days ?> jours</div>
                    <div class="stat-badge">📅 Total : <?= $total_days ?> jours</div>
                </div>
            </div>

            <div class="suggestions-section">
                <div class="section-header"><h2 class="section-title">🍽️ Régimes suggérés pour vous</h2></div>
                <?php if (empty($suggestions)): ?>
                    <div class="alert alert-info">Aucun régime pour le moment.</div>
                <?php else: ?>
                    <div class="suggestions-grid suggestions-grid-scroll">
                        <?php foreach ($suggestions as $s): $r = $s['regime']; ?>
                        <div class="suggestion-card">
                            <div class="suggestion-header">
                                <h3><?= esc($r['nom']) ?></h3>
                                <span class="badge <?= $r['variation_poids_kg'] > 0 ? 'gain' : ($r['variation_poids_kg'] < 0 ? 'loss' : 'stable') ?>">
                                    <?= ($r['variation_poids_kg'] > 0 ? '+' : '') . $r['variation_poids_kg'] ?> kg
                                </span>
                            </div>
                            <p class="suggestion-desc"><?= esc($r['description'] ?? 'Aucune description') ?></p>
                            <div class="suggestion-diet">
                                <span>🥩 Viande <?= $r['pct_viande'] ?>%</span>
                                <span>🐟 Poisson <?= $r['pct_poisson'] ?>%</span>
                                <span>🍗 Volaille <?= $r['pct_volaille'] ?>%</span>
                            </div>
                            <?php if (!empty($s['prixOptions'])): ?>
                                <div class="suggestion-prices">
                                    <?php foreach ($s['prixOptions'] as $p): ?>
                                        <span class="price-tag"><?= $p['duree_jours'] ?>j : <?= number_format($p['prix_base'],2) ?>€</span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($s['activites'])): ?>
                                <div class="suggestion-activities">
                                    <strong>🏋️ Activités :</strong>
                                    <ul><?php foreach ($s['activites'] as $act): ?><li><?= esc($act['nom']) ?> – <?= $act['frequence_semaine'] ?>x/semaine</li><?php endforeach; ?></ul>
                                </div>
                            <?php endif; ?>
                            <div class="suggestion-actions">
                                <a href="<?= site_url('regime/' . $r['id']) ?>" class="btn-outline">Voir détail</a>
                                <a href="<?= site_url('regime/' . $r['id']) ?>" class="btn-primary btn-sm">Souscrire</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bottom-grid">
                <div class="table-card">
                    <div class="table-card-header"><div class="table-card-title">👤 Mon profil</div></div>
                    <table class="data-table">
                        <tr><td><strong>Nom complet</strong></td><td><?= esc($user['nom']) ?> <?= esc($user['prenom']) ?></td></tr>
                        <tr><td><strong>Email</strong></td><td><?= esc($user['email']) ?></td></tr>
                        <tr><td><strong>Taille</strong></td><td><?= $user['taille_cm'] ?? '—' ?> cm</td></tr>
                        <tr><td><strong>Poids actuel</strong></td><td><?= $user['poids_kg'] ?? '—' ?> kg</td></tr>
                        <tr><td><strong>Adresse</strong></td><td><?= esc($user['adresse'] ?? '—') ?></td></tr>
                    </table>
                </div>
                <div class="activity-card">
                    <div class="activity-card-title">💰 Mon portefeuille</div>
                    <div class="kpi-value mb-3"><?= number_format($wallet['solde_points'] ?? 0, 2) ?> points</div>
                    <hr>
                    <div class="activity-card-title">⭐ Mon abonnement</div>
                    <?php if ($subscription): ?>
                        <p><?= esc($subscription['nom']) ?> (<?= ucfirst($subscription['statut']) ?>)</p>
                        <p>Depuis le <?= date('d/m/Y', strtotime($subscription['date_debut'])) ?></p>
                    <?php else: ?>
                        <p>Aucun abonnement actif</p>
                    <?php endif; ?>
                    <div class="mt-4">
                        <a href="<?= site_url('wallet/code') ?>" class="btn-outline">➕ Ajouter un code</a>
                    </div>
                </div>
            </div>
            <?= $this->include('bar/footer') ?>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</body>
</html>