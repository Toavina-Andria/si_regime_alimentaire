<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan — Abonnements</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Bebas+Neue&family=JetBrains+Mono:wght@400;500&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
  <style>
    .abo-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 24px;
      margin-top: 28px;
    }
    .abo-card {
      background: var(--color-surface);
      border-radius: 20px;
      border: 1px solid var(--color-border);
      padding: 28px;
      display: flex;
      flex-direction: column;
      transition: box-shadow 0.25s, transform 0.25s;
      position: relative;
      overflow: hidden;
    }
    .abo-card:hover {
      box-shadow: 0 12px 32px rgba(0,0,0,0.08);
      transform: translateY(-4px);
    }
    .abo-card.gold {
      border-color: #D4A853;
      background: linear-gradient(180deg, #FFFDF5 0%, var(--color-surface) 100%);
    }
    .abo-card.gold::before {
      content: '⭐ Populaire';
      position: absolute;
      top: 16px;
      right: -28px;
      background: linear-gradient(135deg, #D4A853, #F59E0B);
      color: #1A1A1A;
      font-size: 11px;
      font-weight: 700;
      padding: 4px 36px;
      transform: rotate(45deg);
      letter-spacing: 0.3px;
    }
    .abo-card-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 16px;
    }
    .abo-icon {
      width: 48px;
      height: 48px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      flex-shrink: 0;
    }
    .abo-icon.gold {
      background: rgba(212,168,83,0.2);
    }
    .abo-icon.free {
      background: rgba(82,183,136,0.2);
    }
    .abo-name {
      font-family: var(--font-heading);
      font-size: 20px;
      font-weight: 600;
      color: var(--color-text-primary);
    }
    .abo-price {
      font-family: var(--font-kpi);
      font-size: 36px;
      line-height: 1;
      color: var(--color-primary);
      margin: 8px 0 4px;
      letter-spacing: 1px;
    }
    .abo-price span {
      font-family: var(--font-body);
      font-size: 14px;
      font-weight: 400;
      color: var(--color-text-muted);
      letter-spacing: 0;
    }
    .abo-price.gold {
      color: #B8860B;
    }
    .abo-desc {
      font-size: 14px;
      color: var(--color-text-secondary);
      line-height: 1.6;
      margin: 8px 0 16px;
      flex: 1;
    }
    .abo-features {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-bottom: 20px;
    }
    .abo-feature {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      color: var(--color-text-secondary);
    }
    .abo-feature-icon {
      width: 20px;
      text-align: center;
      font-size: 16px;
      flex-shrink: 0;
    }
    .abo-btn {
      width: 100%;
      padding: 12px 24px;
      border-radius: 12px;
      font-size: 15px;
      font-weight: 600;
      font-family: var(--font-body);
      border: none;
      cursor: pointer;
      transition: all 200ms ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      text-decoration: none;
    }
    .abo-btn.primary {
      background: var(--color-primary);
      color: white;
    }
    .abo-btn.primary:hover {
      background: #23563f;
      box-shadow: 0 4px 14px rgba(45,106,79,0.35);
    }
    .abo-btn.gold {
      background: linear-gradient(135deg, #D4A853, #F0C040);
      color: #1A1A1A;
    }
    .abo-btn.gold:hover {
      box-shadow: 0 4px 14px rgba(212,168,83,0.45);
    }
    .abo-btn.success {
      background: #D1FAE5;
      color: #065F46;
      cursor: default;
    }
    .abo-btn.secondary {
      background: var(--color-surface-2);
      color: var(--color-text-muted);
      cursor: not-allowed;
    }
    .abo-active-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #D1FAE5;
      color: #065F46;
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
      margin-top: 12px;
    }
    .abo-active-until {
      font-size: 12px;
      color: var(--color-text-muted);
      margin-top: 4px;
    }
    .alert-dashboard {
      padding: 16px 20px;
      border-radius: 12px;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 16px;
    }
    .alert-dashboard.success {
      background: #D1FAE5;
      color: #065F46;
    }
    .alert-dashboard.error {
      background: #FEE2E2;
      color: #991B1B;
    }
  </style>
</head>
<body>
<div class="dashboard-layout">

  <?= $this->include('bar/sidebar') ?>

  <div class="main-content">
    <header class="topbar">
      <div class="topbar-left">
        <button class="hamburger" aria-label="Menu">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="3" y1="6" x2="21" y2="6" />
            <line x1="3" y1="12" x2="21" y2="12" />
            <line x1="3" y1="18" x2="21" y2="18" />
          </svg>
        </button>
        <div class="breadcrumb">
          <a href="<?= base_url('dashboard') ?>">Accueil</a>
          <span>/</span>
          <span class="current">Abonnements</span>
        </div>
      </div>
      <div class="topbar-right">
        <div class="topbar-search">
          <span class="search-icon">🔍</span>
          <input type="text" placeholder="Rechercher..." aria-label="Rechercher">
        </div>
        <button class="notification-btn" aria-label="Notifications">
          🔔
          <span class="notification-dot"></span>
        </button>
      </div>
    </header>

    <main class="page-content">
      <div class="page-header" style="display:flex; align-items:center; justify-content:space-between;">
        <div>
          <h1 class="page-title">Abonnements</h1>
          <p class="page-subtitle">Choisissez la formule qui correspond à vos besoins</p>
        </div>
      </div>

      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert-dashboard success">
          <span>✅</span>
          <span><?= session()->getFlashdata('success') ?></span>
        </div>
      <?php endif; ?>

      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert-dashboard error">
          <span>❌</span>
          <span><?= session()->getFlashdata('error') ?></span>
        </div>
      <?php endif; ?>

      <?php if (!empty($abonnements)): ?>
        <div class="abo-grid">
          <?php foreach ($abonnements as $abonnement): ?>
            <?php $isGold = $abonnement['statut'] == 'gold'; ?>
            <?php $isActive = $activeSubscription && $activeSubscription['abonnement_id'] == $abonnement['id']; ?>

            <div class="abo-card <?= $isGold ? 'gold' : '' ?>">
              <div class="abo-card-header">
                <div class="abo-icon <?= $isGold ? 'gold' : 'free' ?>">
                  <?= $isGold ? '⭐' : '🔹' ?>
                </div>
                <div class="abo-name"><?= esc($abonnement['nom']) ?></div>
              </div>

              <div class="abo-price <?= $isGold ? 'gold' : '' ?>">
                <?= number_format($abonnement['prix'] ?? 0, 0) ?>
                <span>points</span>
              </div>

              <div class="abo-desc">
                <?php if (!empty($abonnement['description'])): ?>
                  <?= esc($abonnement['description']) ?>
                <?php else: ?>
                  Accédez aux fonctionnalités de base de NutriPlan.
                <?php endif; ?>
              </div>

              <div class="abo-features">
                <div class="abo-feature">
                  <span class="abo-feature-icon">💰</span>
                  <span><strong><?= $abonnement['taux_reduction'] ?? 0 ?>%</strong> de réduction sur tous les régimes</span>
                </div>
                <div class="abo-feature">
                  <span class="abo-feature-icon">📅</span>
                  <span>Durée de <strong>30 jours</strong></span>
                </div>
                <div class="abo-feature">
                  <span class="abo-feature-icon"><?= $isGold ? '✨' : '✅' ?></span>
                  <span><?= $isGold ? 'Avantages premium inclus' : 'Accès aux fonctionnalités de base' ?></span>
                </div>
              </div>

              <?php if ($isActive): ?>
                <div class="abo-active-badge">✅ Abonnement actif</div>
                <div class="abo-active-until">
                  Valable jusqu'au <?= date('d/m/Y', strtotime($activeSubscription['date_fin'])) ?>
                </div>
              <?php elseif ($activeSubscription): ?>
                <button class="abo-btn secondary" disabled>🔒 Abonnement actif</button>
              <?php else: ?>
                <a href="<?= base_url('abonnement/' . $abonnement['id']) ?>" class="abo-btn <?= $isGold ? 'gold' : 'primary' ?>">
                  Souscrire
                </a>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state" style="margin-top:48px;">
          <div class="empty-state-icon">🎯</div>
          <div class="empty-state-title">Aucun abonnement disponible</div>
          <div class="empty-state-text">Revenez plus tard, de nouvelles formules arrivent bientôt.</div>
        </div>
      <?php endif; ?>

      <div style="margin-top: 32px;">
        <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">
          ← Retour au tableau de bord
        </a>
      </div>
    </main>
  </div>
</div>

<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
</body>
</html>
