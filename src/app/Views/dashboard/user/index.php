<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord – NutriPlan</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
    <style>
        .user-greeting { background: white; border-radius: 32px; padding: 1.5rem 2rem; margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .user-stats { display: flex; gap: 1rem; margin-top: 1rem; flex-wrap: wrap; }
        .stat-badge { background: #e9f4ef; padding: 0.5rem 1rem; border-radius: 40px; font-size: 0.85rem; }
        .suggestions-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.25rem; max-height: 480px; overflow-y: auto; padding-right: 0.5rem; }
        .suggestions-grid::-webkit-scrollbar { width: 6px; }
        .suggestions-grid::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.12); border-radius: 3px; }
        .suggestion-card { background: white; border-radius: 16px; padding: 1.25rem 1.5rem; border: 1px solid var(--color-border); }
        .suggestion-header h3 { font-size: 1.1rem; font-weight: 600; }
        .suggestion-card .badge { font-size: 0.7rem; padding: 0.2rem 0.6rem; border-radius: 40px; }
        .badge.gain { background: #2D6A4F; color: white; }
        .badge.loss { background: #C1392B; color: white; }
        .badge.stable { background: #D4A853; color: white; }
        .suggestion-desc { font-size: 0.9rem; color: var(--color-text-secondary); }
        .suggestion-diet { font-size: 0.85rem; color: var(--color-text-muted); }
        .price-tag { background: #f0f0f0; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; margin-right: 0.5rem; }
        .bottom-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem; }
        @media (max-width: 768px) { .bottom-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="dashboard-layout">
    <?= view('bar/sidebar') ?>
    <div class="main-content">
        <header class="topbar">
            <div class="topbar-left"><h1 class="page-title">Mon espace</h1></div>
            <div class="topbar-right">
                <a href="<?= site_url('services') ?>" class="btn-outline">✨ Services</a>
                <a href="<?= site_url('logout') ?>" class="btn-outline">Déconnexion</a>
            </div>
        </header>
        <main class="page-content">
            <!-- Bienvenue personnalisée -->
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

            <!-- Suggestions régimes -->
            <div class="suggestions-section">
                <div class="section-header"><h2 class="section-title">🍽️ Régimes suggérés pour vous</h2></div>
                <?php if (empty($suggestions)): ?>
                    <div class="alert alert-info">Aucun régime pour le moment.</div>
                <?php else: ?>
                    <div class="suggestions-grid">
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
                                <button class="btn-primary btn-subscribe" data-id="<?= $r['id'] ?>">Souscrire</button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Profil et portefeuille -->
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
                    <div class="stat-number" style="font-size: 1.5rem;"><?= number_format($wallet['solde_points'] ?? 0, 2) ?> points</div>
                    <hr>
                    <div class="activity-card-title">⭐ Mon abonnement</div>
                    <?php if ($subscription): ?>
                        <p><?= esc($subscription['nom']) ?> (<?= ucfirst($subscription['statut']) ?>)</p>
                        <p>Depuis le <?= date('d/m/Y', strtotime($subscription['date_debut'])) ?></p>
                    <?php else: ?>
                        <p>Aucun abonnement actif</p>
                    <?php endif; ?>
                    <a href="<?= site_url('wallet/code') ?>" class="btn-outline" style="margin-top: 1rem;">➕ Ajouter un code</a>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</body>
</html>