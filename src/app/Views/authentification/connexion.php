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
        <h2>Connexion</h2>
        <p>Connectez-vous à votre espace NutriPlan</p>
      </div>

      <?php if(session()->getFlashdata('error')): ?>
        <div class="auth-error">✗ <?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <form action="<?= base_url('auth/doLogin') ?>" method="POST">
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

      <div class="auth-footer">
        Pas encore de compte ? <a href="<?= base_url('register') ?>">Créer un compte</a>
      </div>
    </div>
  </div>
</body>
</html>
