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
    .auth-container { max-width: 900px; }
    .auth-form { max-width: 100%; }
    .user-card-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; margin: 20px 0; max-height: 420px; overflow-y: auto; padding-right: 8px; }
    .user-card-grid::-webkit-scrollbar { width: 6px; }
    .user-card-grid::-webkit-scrollbar-track { background: transparent; }
    .user-card-grid::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.15); border-radius: 3px; }
    .user-card { background: #fff; border: 1px solid var(--color-border, #E2E4DC); border-radius: 16px; padding: 20px; cursor: pointer; transition: all 0.2s; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
    .user-card:hover { border-color: var(--color-primary-light, #52B788); transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,0.1); }
    .user-card-admin { background: #FFFDF5; border-color: #D4A853; }
    .user-card-admin:hover { border-color: #B8860B; }
    .user-card-avatar { width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #2D6A4F, #52B788); display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 600; color: white; margin: 0 auto 10px; }
    .user-card-admin .user-card-avatar { background: linear-gradient(135deg, #B8860B, #D4A853); }
    .user-card-name { font-size: 15px; font-weight: 600; color: var(--color-text-primary, #1A1A1A); }
    .user-card-email { font-size: 12px; color: var(--color-text-secondary, #6B7280); margin-top: 2px; }
    .user-card-badge { display: inline-block; margin-top: 8px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; background: rgba(212,168,83,0.2); color: #B8860B; }
    .auth-tabs { display: flex; gap: 4px; background: rgba(255,255,255,0.06); border-radius: 12px; padding: 4px; margin-bottom: 8px; }
    .auth-tab { flex: 1; padding: 10px 16px; border: none; border-radius: 10px; background: transparent; color: rgba(255,255,255,0.5); font-family: inherit; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
    .auth-tab.active { background: rgba(255,255,255,0.1); color: #fff; }
    .auth-tab:hover { color: #fff; }
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
          <span>🥗</span>
          Régimes adaptés à vos objectifs
        </div>
        <div class="auth-brand-feature">
          <span>📊</span>
          Suivi de progression en temps réel
        </div>
        <div class="auth-brand-feature">
          <span>🏃</span>
          Activités physiques recommandées
        </div>
      </div>
    </div>

    <div class="auth-form">
      <div class="auth-form-header">
        <h2>Connexion rapide</h2>
        <p>Choisissez un compte pour vous connecter</p>
      </div>

      <?php if(session()->getFlashdata('error')): ?>
        <div class="auth-error">✗ <?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <div class="auth-tabs">
        <button class="auth-tab active" data-tab="quick">Connexion rapide</button>
        <button class="auth-tab" data-tab="form">Connexion par email</button>
      </div>

      <div id="tab-quick">
        <div class="user-card-grid">
          <?php
            $userModel = new \App\Models\Utilisateur();
            $users = $userModel->orderBy('est_admin', 'DESC')->orderBy('nom', 'ASC')->findAll();
          ?>
          <?php foreach ($users as $u): ?>
          <a href="<?= base_url('quick-login/' . $u['id']) ?>" class="user-card <?= !empty($u['est_admin']) ? 'user-card-admin' : '' ?>" style="text-decoration: none;">
            <div class="user-card-avatar"><?= strtoupper(substr($u['prenom'] ?? $u['nom'], 0, 1)) ?></div>
            <div class="user-card-name"><?= esc($u['prenom'] ?? '') ?> <?= esc($u['nom']) ?></div>
            <div class="user-card-email"><?= esc($u['email']) ?></div>
            <?php if (!empty($u['est_admin'])): ?>
              <div class="user-card-badge">👨‍💼 Admin</div>
            <?php endif; ?>
          </a>
          <?php endforeach; ?>
        </div>
      </div>

      <div id="tab-form" style="display: none;">
        <form action="<?= base_url('auth/doLogin') ?>" method="POST">
          <?= csrf_field() ?>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required placeholder="email@exemple.com">
          </div>
          <div class="form-group">
            <label for="pwd">Mot de passe</label>
            <input type="password" name="mot_de_passe" id="pwd" required placeholder="Votre mot de passe">
          </div>
          <button type="submit" class="auth-submit">Se connecter</button>
        </form>
      </div>

      <div class="auth-footer">
        Pas encore de compte ? <a href="<?= base_url('register') ?>">Créer un compte</a>
      </div>
    </div>
  </div>

  <script>
    document.querySelectorAll('.auth-tab').forEach(function(tab) {
      tab.addEventListener('click', function() {
        document.querySelectorAll('.auth-tab').forEach(function(t) { t.classList.remove('active'); });
        this.classList.add('active');
        document.getElementById('tab-quick').style.display = this.dataset.tab === 'quick' ? 'block' : 'none';
        document.getElementById('tab-form').style.display = this.dataset.tab === 'form' ? 'block' : 'none';
      });
    });
  </script>
  <?= view('bar/footer') ?>
</body>
</html>
