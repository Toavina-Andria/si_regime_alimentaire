<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan — Nouveau régime</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Bebas+Neue&family=JetBrains+Mono:wght@400;500&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
  <style>
    .form-card {
      background: var(--color-surface);
      border-radius: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      padding: 32px;
      max-width: 720px;
      margin: 0 auto;
    }
    .form-card-title {
      font-family: var(--font-heading);
      font-size: 24px;
      font-weight: 600;
      color: var(--color-text-primary);
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 16px;
    }
    .form-row-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
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
          <a href="<?= base_url('admin/dashboard') ?>">Accueil</a>
          <span>/</span>
          <a href="<?= base_url('regime/admin') ?>">Régimes</a>
          <span>/</span>
          <span class="current">Nouveau</span>
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
      <div class="page-header">
        <h1 class="page-title">Nouveau régime alimentaire</h1>
        <p class="page-subtitle">Créez un nouveau régime pour vos utilisateurs</p>
      </div>

      <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert-dashboard" style="background:#FEE2E2;color:#991B1B;padding:16px 20px;border-radius:12px;margin-bottom:24px;display:flex;align-items:center;gap:10px;">
          <span>❌</span>
          <span><?= implode('<br>', session()->getFlashdata('errors')) ?></span>
        </div>
      <?php endif; ?>

      <div class="form-card">
        <form action="<?= base_url('regime/admin/store') ?>" method="POST">
          <?= csrf_field() ?>

          <div class="form-group">
            <label class="form-label">Nom du régime</label>
            <input type="text" class="form-input" name="nom" value="<?= old('nom') ?>" placeholder="ex: Régime Méditerranéen" required>
          </div>

          <div class="form-group">
            <label class="form-label">Description</label>
            <textarea class="form-input" name="description" rows="4" style="resize:vertical;" placeholder="Décrivez les principes, bénéfices..."><?= old('description') ?></textarea>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">% Viande</label>
              <input type="number" class="form-input" name="pct_viande" step="0.01" value="<?= old('pct_viande') ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">% Volaille</label>
              <input type="number" class="form-input" name="pct_volaille" step="0.01" value="<?= old('pct_volaille') ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">% Poisson</label>
              <input type="number" class="form-input" name="pct_poisson" step="0.01" value="<?= old('pct_poisson') ?>" required>
            </div>
          </div>

          <div class="form-row-2">
            <div class="form-group">
              <label class="form-label">Variation poids (kg)</label>
              <input type="number" class="form-input" name="variation_poids_kg" step="0.1" placeholder="positif = prise / négatif = perte" value="<?= old('variation_poids_kg') ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">Durée (jours)</label>
              <input type="number" class="form-input" name="duree_jours" min="1" value="<?= old('duree_jours') ?>" required>
            </div>
          </div>

          <div style="display:flex; gap:12px; margin-top:8px;">
            <button type="submit" class="btn btn-primary">💾 Créer le régime</button>
            <a href="<?= base_url('regime/admin') ?>" class="btn btn-ghost">↩️ Annuler</a>
          </div>
        </form>
      </div>
    </main>
  </div>
</div>

<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
</body>
</html>
