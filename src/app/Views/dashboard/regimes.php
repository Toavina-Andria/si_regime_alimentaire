<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan — Régimes alimentaires</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Bebas+Neue&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
</head>
<body>
<div class="dashboard-layout">

  <aside class="sidebar">
    <div class="sidebar-logo">
      <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
        <path d="M16 2C8.268 2 2 8.268 2 16s6.268 14 14 14 14-6.268 14-14S23.732 2 16 2z" fill="#2D6A4F"/>
        <path d="M16 6c-1.5 3-4.5 5-7 7 2 2.5 4 5.5 5 9 2.5-1.5 5-4 7-7-2.5-2-4.5-5-5-9z" fill="#D4A853" opacity="0.8"/>
        <path d="M11 20c3 1 6 2 8 4 2-2 5-3 8-4" stroke="#52B788" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
      <div class="sidebar-logo-text">
        NutriPlan
        <small>Admin Panel</small>
      </div>
    </div>

    <nav class="sidebar-nav">
      <div class="sidebar-section">
        <div class="sidebar-section-title">Vue d'ensemble</div>
        <a href="<?= site_url('admin/dashboard') ?>" class="sidebar-link">
          <span class="icon">📊</span>
          Tableau de bord
        </a>
      </div>
      <div class="sidebar-section">
        <div class="sidebar-section-title">Gestion</div>
        <a href="<?= site_url('admin/regimes') ?>" class="sidebar-link active">
          <span class="icon">🥗</span>
          Régimes alimentaires
        </a>
        <a href="<?= site_url('admin/activites') ?>" class="sidebar-link">
          <span class="icon">🏃</span>
          Activités sportives
        </a>
        <a href="<?= site_url('admin/utilisateurs') ?>" class="sidebar-link">
          <span class="icon">👥</span>
          Utilisateurs
        </a>
        <a href="<?= site_url('admin/codes') ?>" class="sidebar-link">
          <span class="icon">💰</span>
          Portefeuille & Codes
        </a>
      </div>
      <div class="sidebar-section">
        <div class="sidebar-section-title">Configuration</div>
        <a href="#" class="sidebar-link">
          <span class="icon">⚙️</span>
          Paramètres
        </a>
        <a href="#" class="sidebar-link">
          <span class="icon">📄</span>
          Logs & Historique
        </a>
      </div>
    </nav>

    <div class="sidebar-footer">
      <div class="sidebar-profile">
        <div class="sidebar-avatar"><?= strtoupper(substr(session()->get('user_nom') ?? 'A', 0, 1)) ?></div>
        <div class="sidebar-profile-info">
          <div class="sidebar-profile-name"><?= session()->get('user_nom') ?? 'Admin' ?></div>
          <div class="sidebar-profile-email"><?= session()->get('user_email') ?? '' ?></div>
        </div>
      </div>
      <a href="<?= site_url('logout') ?>" class="sidebar-logout">
        <span>🚪</span>
        Déconnexion
      </a>
    </div>
  </aside>

  <div class="main-content">
    <header class="topbar">
      <div class="topbar-left">
        <button class="hamburger" aria-label="Menu">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="3" y1="6" x2="21" y2="6"/>
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
          </svg>
        </button>
        <div class="breadcrumb">
          <a href="<?= site_url('admin/dashboard') ?>">Accueil</a>
          <span>/</span>
          <span class="current">Régimes alimentaires</span>
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
          <h1 class="page-title">Régimes alimentaires</h1>
          <p class="page-subtitle">Gérez les régimes proposés aux utilisateurs</p>
        </div>
        <button class="btn btn-primary" data-modal="modalRegime">+ Nouveau régime</button>
      </div>

      <div class="table-card">
        <table class="data-table">
          <thead>
            <tr>
              <th>Nom</th>
              <th>% Viande</th>
              <th>% Poisson</th>
              <th>% Volaille</th>
              <th>Variation poids</th>
              <th>Durée</th>
              <th>Date</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($regimes)): ?>
            <tr>
              <td colspan="8" style="text-align:center; color:var(--color-text-muted); padding:48px;">
                <div style="font-size:36px; margin-bottom:12px; opacity:0.5;">🥗</div>
                <div style="font-size:16px; font-weight:600; margin-bottom:4px;">Aucun régime</div>
                <div style="font-size:13px;">Créez votre premier régime alimentaire</div>
              </td>
            </tr>
            <?php else: ?>
            <?php foreach ($regimes as $r): ?>
            <tr>
              <td><strong><?= esc($r['nom']) ?></strong></td>
              <td><span class="badge badge-viande"><?= $r['pct_viande'] ?>%</span></td>
              <td><span class="badge badge-poisson"><?= $r['pct_poisson'] ?>%</span></td>
              <td><span class="badge badge-volaille"><?= $r['pct_volaille'] ?>%</span></td>
              <td><?= $r['variation_poids_kg'] ?> kg</td>
              <td><?= $r['duree_jours'] ?> j</td>
              <td><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
              <td>
                <div class="action-btns">
                  <button class="action-btn" title="Modifier">✏️</button>
                  <button class="action-btn delete" title="Supprimer">🗑️</button>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<!-- Modal Nouveau Régime -->
<div class="modal-overlay" id="modalRegime">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Nouveau régime</div>
      <button class="modal-close">&times;</button>
    </div>
    <form action="<?= site_url('regimes') ?>" method="POST">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Nom du régime</label>
          <input type="text" class="form-input" name="nom" required placeholder="ex: Méditerranéen">
        </div>
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea class="form-input" name="description" rows="3" placeholder="Description du régime..." style="resize:vertical;"></textarea>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px;">
          <div class="form-group">
            <label class="form-label">% Viande</label>
            <input type="number" class="form-input" name="pct_viande" min="0" max="100" required>
          </div>
          <div class="form-group">
            <label class="form-label">% Poisson</label>
            <input type="number" class="form-input" name="pct_poisson" min="0" max="100" required>
          </div>
          <div class="form-group">
            <label class="form-label">% Volaille</label>
            <input type="number" class="form-input" name="pct_volaille" min="0" max="100" required>
          </div>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
          <div class="form-group">
            <label class="form-label">Durée (jours)</label>
            <input type="number" class="form-input" name="duree_jours" min="1" required>
          </div>
          <div class="form-group">
            <label class="form-label">Variation poids (kg)</label>
            <input type="number" class="form-input" name="variation_poids_kg" step="0.1" placeholder="ex: -2.5">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-ghost modal-close">Annuler</button>
        <button type="submit" class="btn btn-primary">Créer le régime</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
</body>
</html>
