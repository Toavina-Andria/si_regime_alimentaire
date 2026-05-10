<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriPlan — Mes abonnements</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Bebas+Neue&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
    <style>
        .sub-page { padding: 2rem; max-width: 1100px; margin: 0 auto; }
        .sub-page h1 { font-family: var(--font-heading); font-size: 1.75rem; margin-bottom: 1.5rem; color: var(--color-text-primary); }
        .sub-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }
        .sub-card { background: var(--color-surface); border-radius: 16px; border: 1px solid var(--color-border); overflow: hidden; transition: all 0.2s; display: flex; flex-direction: column; }
        .sub-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .sub-card-header { background: var(--color-primary); color: white; padding: 1rem 1.25rem; }
        .sub-card-header h3 { font-family: var(--font-heading); font-size: 1.15rem; }
        .sub-card-body { padding: 1.25rem; flex: 1; }
        .sub-card-body p { color: var(--color-text-secondary); font-size: 0.9rem; line-height: 1.5; margin-bottom: 1rem; }
        .sub-features { list-style: none; display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1rem; }
        .sub-features li { font-size: 0.88rem; color: var(--color-text-primary); }
        .sub-features li strong { color: var(--color-primary); }
        .sub-badges { display: flex; gap: 0.5rem; margin-bottom: 0.75rem; }
        .sub-badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 40px; font-size: 0.78rem; font-weight: 500; }
        .sub-badge.available { background: #e9f4ef; color: var(--color-primary); }
        .sub-badge.active { background: #dbeafe; color: #1e40af; }
        .sub-notice { background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 10px; padding: 0.6rem 0.9rem; font-size: 0.85rem; color: #1e40af; }
        .sub-card-footer { padding: 1rem 1.25rem; border-top: 1px solid var(--color-border); background: var(--color-surface-2); }
        .sub-card-footer .btn { width: 100%; text-align: center; }
        .sub-card-footer .btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .sub-empty { text-align: center; padding: 3rem; color: var(--color-text-secondary); background: var(--color-surface); border-radius: 16px; border: 1px dashed var(--color-border); }
        .sub-back { margin-top: 2rem; }
        .sub-alert { padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.9rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; }
        .sub-alert.success { background: #e9f4ef; color: var(--color-primary); border: 1px solid #b7d9c9; }
        .sub-alert.error { background: #fef2f2; color: var(--color-danger); border: 1px solid #fecaca; }
    </style>
</head>
<body>
    <?= $this->include('bar/sidebar') ?>
    <div class="main-content">
        <main class="page-content">
            <div class="sub-page">

                <h1>Nos Abonnements</h1>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="sub-alert success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="sub-alert error"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <?php if (!empty($abonnements)): ?>
                    <div class="sub-grid">
                        <?php foreach ($abonnements as $abonnement): ?>
                            <div class="sub-card">
                                <div class="sub-card-header">
                                    <h3><?= esc($abonnement['nom']) ?></h3>
                                </div>
                                <div class="sub-card-body">
                                    <?php if (!empty($abonnement['description'])): ?>
                                        <p><?= esc(substr($abonnement['description'], 0, 100)) ?><?= strlen($abonnement['description']) > 100 ? '...' : '' ?></p>
                                    <?php endif; ?>
                                    <ul class="sub-features">
                                        <li>📉 Réduction : <strong><?= $abonnement['taux_reduction'] ?? 0 ?>%</strong></li>
                                        <li>📅 Durée : <strong>30 jours</strong></li>
                                        <li>🪙 Coût : <strong><?= number_format($abonnement['prix'] ?? 0, 2) ?> points</strong></li>
                                    </ul>
                                    <div class="sub-badges">
                                        <span class="sub-badge available">Disponible</span>
                                        <?php if ($activeSubscription && $activeSubscription['abonnement_id'] == $abonnement['id']): ?>
                                            <span class="sub-badge active">Actif</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($activeSubscription && $activeSubscription['abonnement_id'] == $abonnement['id']): ?>
                                        <div class="sub-notice">✓ Actif jusqu'au <strong><?= date('d/m/Y', strtotime($activeSubscription['date_fin'])) ?></strong></div>
                                    <?php endif; ?>
                                </div>
                                <div class="sub-card-footer">
                                    <?php if (!$activeSubscription): ?>
                                        <a href="<?= base_url('abonnement/' . $abonnement['id']) ?>" class="btn btn-primary">🛒 Souscrire</a>
                                    <?php elseif ($activeSubscription['abonnement_id'] != $abonnement['id']): ?>
                                        <button class="btn btn-secondary" disabled>🔒 Abonnement actif</button>
                                    <?php else: ?>
                                        <button class="btn btn-primary" disabled>✓ Abonnement actif</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="sub-empty">Aucun abonnement disponible pour le moment.</div>
                <?php endif; ?>

                <div class="sub-back">
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">← Retour au tableau de bord</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>