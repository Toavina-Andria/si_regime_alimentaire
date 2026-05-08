<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon profil — NutriPlan</title>
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
        <h2>Bienvenue, <?= session()->get('user_nom') ?> !</h2>
        <p>Complétez vos informations pour commencer votre programme</p>
      </div>

      <?php if (session()->getFlashdata('errors')): ?>
        <div class="auth-error">
          <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <p>✗ <?= $error ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form action="<?= site_url('auth/updateProfil') ?>" method="POST">
        <div class="form-row">
          <div class="form-group">
            <label>Date de naissance</label>
            <input type="date" name="date_naissance" required>
          </div>
          <div class="form-group">
            <label>Genre</label>
            <select name="genre" required>
              <option value="">Sélectionnez</option>
              <option value="homme">Homme</option>
              <option value="femme">Femme</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Adresse (optionnelle)</label>
          <input type="text" name="adresse" placeholder="Votre adresse complète">
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Taille (cm)</label>
            <input type="number" name="taille_cm" step="0.01" placeholder="Ex: 175" required>
          </div>
          <div class="form-group">
            <label>Poids (kg)</label>
            <input type="number" name="poids_kg" step="0.01" placeholder="Ex: 70.5" required>
          </div>
        </div>
        <div class="form-group">
          <label>Objectif</label>
          <select name="objectif" required>
            <option value="">Sélectionnez</option>
            <option value="augmenter_poids">💪 Prendre du poids</option>
            <option value="reduire_poids">🎯 Perdre du poids</option>
            <option value="imc_ideal">⚖️ Atteindre mon IMC idéal</option>
          </select>
        </div>
        <button type="submit" class="auth-submit">Enregistrer mon profil</button>
      </form>
    </div>
  </div>
</body>
</html>
