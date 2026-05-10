<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — NutriPlan</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
  <style>
    .login-cards-container {
      max-width: 600px;
      margin: 0 auto;
      padding: 40px 24px;
    }
    .login-cards-title {
      font-family: 'Playfair Display', serif;
      font-size: 28px;
      color: #1e3a2f;
      text-align: center;
      margin-bottom: 8px;
    }
    .login-cards-sub {
      text-align: center;
      color: #5a6e62;
      font-size: 14px;
      margin-bottom: 32px;
    }
    .login-cards-grid {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    .login-card {
      display: flex;
      align-items: center;
      gap: 16px;
      background: white;
      border: 1.5px solid #E2E4DC;
      border-radius: 16px;
      padding: 16px 20px;
      text-decoration: none;
      color: inherit;
      transition: all 0.2s ease;
      cursor: pointer;
    }
    .login-card:hover {
      border-color: #2D6A4F;
      box-shadow: 0 4px 16px rgba(45,106,79,0.12);
      transform: translateY(-2px);
    }
    .login-card.admin {
      border-color: #D4A853;
      background: linear-gradient(135deg, #FFFDF5, #fff);
    }
    .login-card.admin:hover {
      border-color: #B8860B;
      box-shadow: 0 4px 16px rgba(212,168,83,0.25);
    }
    .login-card-avatar {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 18px;
      color: white;
      flex-shrink: 0;
      background: #52B788;
    }
    .login-card.admin .login-card-avatar {
      background: linear-gradient(135deg, #D4A853, #F59E0B);
    }
    .login-card-info {
      flex: 1;
      min-width: 0;
    }
    .login-card-name {
      font-weight: 600;
      font-size: 16px;
      color: #1A1A1A;
    }
    .login-card-email {
      font-size: 13px;
      color: #9CA3AF;
    }
    .login-card-badge {
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }
    .login-card-badge.admin {
      background: #FEF3C7;
      color: #92400E;
    }
    .login-card-badge.user {
      background: #E2E4DC;
      color: #4B5563;
    }
    .login-card-arrow {
      font-size: 18px;
      color: #9CA3AF;
      transition: transform 0.2s;
    }
    .login-card:hover .login-card-arrow {
      transform: translateX(4px);
      color: #2D6A4F;
    }
    .login-separator {
      display: flex;
      align-items: center;
      gap: 16px;
      margin: 32px 0 24px;
      color: #9CA3AF;
      font-size: 13px;
    }
    .login-separator::before,
    .login-separator::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #E2E4DC;
    }
    .login-form-toggle {
      text-align: center;
    }
    .login-form-toggle a {
      color: #2D6A4F;
      font-weight: 500;
      text-decoration: none;
      font-size: 14px;
    }
    .login-form-toggle a:hover {
      text-decoration: underline;
    }
    .login-empty {
      text-align: center;
      padding: 48px 24px;
      color: #9CA3AF;
    }
    .login-empty-icon {
      font-size: 48px;
      margin-bottom: 12px;
    }
  </style>
</head>
<body>
<div class="auth-container">
  <div class="auth-brand">
    <div class="auth-brand-content">
      <svg width="56" height="56" viewBox="0 0 56 56" fill="none">
        <path d="M28 4C14.745 4 4 14.745 4 28s10.745 24 24 24 24-10.745 24-24S41.255 4 28 4z" fill="rgba(255,255,255,0.2)"/>
        <path d="M28 10c-3 6-9 10-14 14 4 5 8 11 10 18 5-3 10-8 14-14-5-4-9-10-10-18z" fill="rgba(255,255,255,0.35)"/>
        <path d="M20 38c6 2 12 4 16 8 4-4 10-6 16-8" stroke="rgba(255,255,255,0.5)" stroke-width="2" stroke-linecap="round"/>
      </svg>
      <h1>NutriPlan</h1>
      <p>Votre programme alimentaire<br>personnalisé</p>
    </div>
    <div class="auth-brand-features">
      <div class="auth-brand-feature">
        <span>🥗</span> Régimes adaptés à vos objectifs
      </div>
      <div class="auth-brand-feature">
        <span>📊</span> Suivi personnalisé
      </div>
      <div class="auth-brand-feature">
        <span>🏃</span> Exercices sur mesure
      </div>
    </div>
  </div>

  <div class="auth-form">
    <div class="login-cards-container">
      <div class="login-cards-title">👋 Bonjour</div>
      <div class="login-cards-sub">Choisissez un compte pour vous connecter</div>

      <?php if (session()->getFlashdata('error')): ?>
        <div style="background:#FEE2E2;color:#991B1B;padding:12px 16px;border-radius:12px;font-size:14px;margin-bottom:20px;text-align:center;">
          <?= session()->getFlashdata('error') ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($users)): ?>
        <div class="login-cards-grid">
          <?php foreach ($users as $u): ?>
            <?php $isAdmin = !empty($u['est_admin']); ?>
            <a href="<?= base_url('quick-login/' . $u['id']) ?>" class="login-card <?= $isAdmin ? 'admin' : '' ?>">
              <div class="login-card-avatar">
                <?= strtoupper(substr($u['prenom'], 0, 1)) ?>
              </div>
              <div class="login-card-info">
                <div class="login-card-name">
                  <?= esc($u['prenom']) ?> <?= esc($u['nom']) ?>
                  <?php if ($isAdmin): ?><span style="font-size:14px;">⭐</span><?php endif; ?>
                </div>
                <div class="login-card-email"><?= esc($u['email']) ?></div>
              </div>
              <span class="login-card-badge <?= $isAdmin ? 'admin' : 'user' ?>">
                <?= $isAdmin ? 'Admin' : 'User' ?>
              </span>
              <span class="login-card-arrow">→</span>
            </a>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="login-empty">
          <div class="login-empty-icon">👤</div>
          <div>Aucun compte trouvé.<br><a href="<?= base_url('/') ?>" style="color:#2D6A4F;">Créer un compte</a></div>
        </div>
      <?php endif; ?>

      <div class="login-separator">ou</div>

      <div class="login-form-toggle">
        <a href="<?= base_url('/') ?>">Créer un nouveau compte →</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>
