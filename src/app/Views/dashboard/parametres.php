<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan — Paramètres</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Bebas+Neue&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
  <style>
    .settings-card { background: var(--color-surface); border-radius: 16px; border: 1px solid var(--color-border); padding: 1.5rem; margin-bottom: 1.5rem; }
    .settings-card h2 { font-family: var(--font-heading); font-size: 1.25rem; margin-bottom: 0.25rem; color: var(--color-text-primary); }
    .settings-card .desc { color: var(--color-text-secondary); font-size: 0.85rem; margin-bottom: 1.25rem; }
    .settings-row { display: flex; align-items: center; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid var(--color-border); }
    .settings-row:last-child { border-bottom: none; }
    .settings-row label { flex: 0 0 200px; font-weight: 600; font-size: 0.9rem; color: var(--color-text-primary); }
    .settings-row input, .settings-row select { flex: 1; padding: 0.6rem 0.9rem; border: 1.5px solid var(--color-border); border-radius: 10px; font-size: 0.9rem; outline: none; }
    .settings-row input:focus, .settings-row select:focus { border-color: var(--color-primary); }
    .settings-row .hint { font-size: 0.8rem; color: var(--color-text-muted); flex: 0 0 180px; }
    .btn-save { margin-top: 1rem; }
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
            <line x1="3" y1="6" x2="21" y2="6" /><line x1="3" y1="12" x2="21" y2="12" /><line x1="3" y1="18" x2="21" y2="18" />
          </svg>
        </button>
        <div class="breadcrumb">
          <a href="<?= base_url('admin/dashboard') ?>">Accueil</a>
          <span>/</span>
          <span class="current">Paramètres</span>
        </div>
      </div>
    </header>

    <main class="page-content">
      <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
      <?php endif; ?>

      <div class="page-header">
        <h1 class="page-title">Paramètres</h1>
        <p class="page-subtitle">Configuration de la plateforme</p>
      </div>

      <form method="POST" action="<?= base_url('admin/parametres') ?>">
        <?= csrf_field() ?>

        <div class="settings-card">
          <h2>⚙️ Configuration générale</h2>
          <p class="desc">Paramètres globaux de la plateforme NutriPlan</p>

          <?php
          $settings = [
            'nom_plateforme' => ['label' => 'Nom de la plateforme', 'hint' => 'ex: NutriPlan'],
            'email_contact'  => ['label' => 'Email de contact', 'hint' => 'ex: contact@nutriplan.fr'],
            'devise'         => ['label' => 'Devise (points)', 'hint' => 'ex: €'],
            'remise_gold'    => ['label' => 'Remise Gold (%)', 'hint' => 'en pourcentage, ex: 15'],
          ];
          $paramMap = [];
          foreach ($parametres as $p) {
            $paramMap[$p['clef']] = $p['valeur'];
          }
          ?>

          <?php foreach ($settings as $clef => $cfg): ?>
            <div class="settings-row">
              <label><?= $cfg['label'] ?></label>
              <input type="text" name="parametres[<?= $clef ?>]" value="<?= esc($paramMap[$clef] ?? '') ?>" placeholder="<?= $cfg['hint'] ?>">
              <span class="hint"><?= $cfg['hint'] ?></span>
            </div>
          <?php endforeach; ?>

          <div class="settings-row">
            <label>Mode maintenance</label>
            <select name="parametres[maintenance]">
              <option value="0" <?= ($paramMap['maintenance'] ?? '0') === '0' ? 'selected' : '' ?>>Désactivé</option>
              <option value="1" <?= ($paramMap['maintenance'] ?? '') === '1' ? 'selected' : '' ?>>Activé</option>
            </select>
            <span class="hint">Maintenance du site</span>
          </div>
        </div>

        <div class="settings-card">
          <h2>🔔 Notifications</h2>
          <p class="desc">Configuration des notifications par email</p>

          <div class="settings-row">
            <label>Email d'expédition</label>
            <input type="text" name="parametres[email_expediteur]" value="<?= esc($paramMap['email_expediteur'] ?? '') ?>" placeholder="ex: noreply@nutriplan.fr">
            <span class="hint">Adresse d'envoi des emails</span>
          </div>
          <div class="settings-row">
            <label>Notification admin</label>
            <select name="parametres[notification_admin]">
              <option value="1" <?= ($paramMap['notification_admin'] ?? '1') === '1' ? 'selected' : '' ?>>Activée</option>
              <option value="0" <?= ($paramMap['notification_admin'] ?? '') === '0' ? 'selected' : '' ?>>Désactivée</option>
            </select>
            <span class="hint">Alerte pour les nouvelles inscriptions</span>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-save">💾 Enregistrer les paramètres</button>
      </form>
    </main>
  </div>
</div>
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
</body>
</html>
