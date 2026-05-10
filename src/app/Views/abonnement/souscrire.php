<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriPlan — Souscrire</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Bebas+Neue&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
    <style>
        .sub-page { padding: 2rem; max-width: 600px; margin: 0 auto; }
        .sub-page h1 { font-family: var(--font-heading); font-size: 1.75rem; margin-bottom: 1.5rem; color: var(--color-text-primary); }
        .sub-card { background: var(--color-surface); border-radius: 16px; border: 1px solid var(--color-border); overflow: hidden; margin-bottom: 1.5rem; }
        .sub-card-header { background: var(--color-primary); color: white; padding: 1rem 1.25rem; }
        .sub-card-header h3 { font-family: var(--font-heading); font-size: 1.15rem; margin: 0; }
        .sub-card-body { padding: 1.25rem; }
        .sub-card-body h6 { font-size: 0.85rem; color: var(--color-text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.35rem; }
        .sub-card-body p { color: var(--color-text-primary); font-size: 0.95rem; }
        .sub-detail { display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid var(--color-border); font-size: 0.93rem; }
        .sub-detail:last-child { border-bottom: none; }
        .sub-detail span:first-child { color: var(--color-text-secondary); }
        .sub-detail span:last-child { font-weight: 600; color: var(--color-text-primary); }
        .sub-price { font-family: var(--font-heading); font-size: 1.5rem; color: var(--color-primary); }
        .sub-alert { padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.9rem; margin-bottom: 1.5rem; }
        .sub-alert.success { background: #e9f4ef; color: var(--color-primary); border: 1px solid #b7d9c9; }
        .sub-alert.error { background: #fef2f2; color: var(--color-danger); border: 1px solid #fecaca; }
        .sub-alert.warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
        .sub-alert.info { background: #f0f9ff; color: #1e40af; border: 1px solid #bae6fd; }
        .sub-alert.info h6 { margin-bottom: 0.25rem; }
        .sub-alert.info small { opacity: 0.85; }
        .sub-form-actions { display: flex; flex-direction: column; gap: 0.75rem; }
        .sub-already { text-align: center; padding: 2rem 1.25rem; }
        .sub-already p { color: var(--color-text-secondary); margin-bottom: 1.25rem; font-size: 0.95rem; }
        .sub-already .btn { display: inline-block; }
        .sub-back { margin-top: 1.5rem; }
    </style>
</head>
<body>
    <?php
    $csrf_token = csrf_hash();
    $csrf_name = csrf_token();
    ?>

    <?= $this->include('bar/sidebar') ?>
    <div class="main-content">
        <main class="page-content">
            <div class="sub-page">

                <h1>Souscrire à un abonnement</h1>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="sub-alert success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="sub-alert error"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <div class="sub-card">
                    <div class="sub-card-header">
                        <h3><?= esc($abonnement['nom'] ?? 'Abonnement') ?></h3>
                    </div>
                    <div class="sub-card-body">
                        <?php if (!empty($abonnement['description'])): ?>
                            <h6>Description</h6>
                            <p><?= esc($abonnement['description']) ?></p>
                        <?php endif; ?>

                        <div class="sub-detail">
                            <span>Statut</span>
                            <span><span class="sub-badge" style="background:#dbeafe;color:#1e40af;padding:0.2rem 0.6rem;border-radius:40px;font-size:0.8rem;"><?= esc($abonnement['statut'] ?? 'Actif') ?></span></span>
                        </div>
                        <div class="sub-detail">
                            <span>Taux de réduction</span>
                            <span><?= $abonnement['taux_reduction'] ?? 0 ?>%</span>
                        </div>
                        <div class="sub-detail">
                            <span>Prix</span>
                            <span class="sub-price"><?= number_format($abonnement['prix'] ?? 0, 2) ?> points</span>
                        </div>

                        <?php if ($activeSubscription): ?>
                            <div class="sub-alert warning" style="margin-top:1rem;">
                                ✓ Vous avez déjà un abonnement actif jusqu'au <strong><?= date('d/m/Y', strtotime($activeSubscription['date_fin'])) ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!$activeSubscription): ?>
                    <form method="POST" action="<?= base_url('abonnement/souscrire') ?>" class="sub-card">
                        <div class="sub-card-body">
                            <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_token ?>">
                            <input type="hidden" name="abonnement_id" value="<?= $abonnement['id'] ?>">
                            <div class="sub-form-actions">
                                <button type="submit" class="btn btn-primary">✓ Souscrire maintenant</button>
                                <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">Annuler</a>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="sub-card">
                        <div class="sub-card-body sub-already">
                            <p>Vous avez déjà un abonnement actif. Vous ne pouvez souscrire à un autre abonnement que lorsque le vôtre aura expiré.</p>
                            <a href="<?= base_url('dashboard') ?>" class="btn btn-primary">Retour au tableau de bord</a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="sub-alert info">
                    <h6>ℹ️ Informations</h6>
                    <small>Cet abonnement vous permettra de bénéficier d'une réduction de <?= $abonnement['taux_reduction'] ?? 0 ?>% sur vos achats de régimes alimentaires. Valable 30 jours à partir de la souscription.</small>
                </div>

                <div class="sub-back">
                    <a href="<?= base_url('abonnements') ?>" class="btn btn-secondary">← Retour aux abonnements</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>