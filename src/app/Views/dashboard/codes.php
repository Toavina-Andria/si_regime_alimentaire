<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan — Portefeuille & Codes</title>
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
        <a href="<?= site_url('admin/regimes') ?>" class="sidebar-link">
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
        <a href="<?= site_url('admin/codes') ?>" class="sidebar-link active">
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

  <div class="sidebar-overlay" style="display:none; position:fixed; inset:0; background:rgba(26,26,26,0.3); z-index:99;"></div>

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
          <span class="current">Portefeuille & Codes</span>
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
          <h1 class="page-title">Portefeuille & Codes</h1>
          <p class="page-subtitle">Gérez les codes bonus et consultez les transactions</p>
        </div>
        <button class="btn btn-gold" data-modal="modalCode">+ Générer un code</button>
      </div>

      <div class="table-card">
        <table class="data-table">
          <thead>
            <tr>
              <th>Code</th>
              <th>Montant</th>
              <th>Statut</th>
              <th>Date d'expiration</th>
              <th>Créé le</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($codes)): ?>
            <tr>
              <td colspan="6" style="text-align:center; color:var(--color-text-muted); padding:48px;">
                <div style="font-size:36px; margin-bottom:12px; opacity:0.5;">🎟️</div>
                <div style="font-size:16px; font-weight:600; margin-bottom:4px;">Aucun code bonus</div>
                <div style="font-size:13px;">Générez votre premier code bonus</div>
              </td>
            </tr>
            <?php else: ?>
            <?php foreach ($codes as $c): ?>
            <tr>
              <td><code style="font-family:var(--font-mono); background:var(--color-bg); padding:2px 8px; border-radius:4px; font-size:13px;"><?= esc($c['code']) ?></code></td>
              <td><strong><?= $c['valeur_points'] ?> €</strong></td>
              <td>
                <?php if ($c['est_valide']): ?>
                <span class="pill pill-success">✅ Validé</span>
                <?php else: ?>
                <span class="pill pill-danger">❌ Expiré</span>
                <?php endif; ?>
              </td>
              <td><?= $c['expires_at'] ? date('d/m/Y', strtotime($c['expires_at'])) : '—' ?></td>
              <td><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
              <td>
                <div class="action-btns">
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

<!-- Modal Générer un code -->
<div class="modal-overlay" id="modalCode">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Générer un code bonus</div>
      <button class="modal-close">&times;</button>
    </div>
    <form action="<?= site_url('admin/codes') ?>" method="POST">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Code</label>
          <input type="text" class="form-input" name="code" placeholder="ex: NUTRI2026" value="NUTRI-<?= strtoupper(substr(md5(uniqid()), 0, 6)) ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Montant (€)</label>
          <input type="number" class="form-input" name="valeur_points" min="1" step="0.5" required placeholder="ex: 10">
        </div>
        <div class="form-group">
          <label class="form-label">Date d'expiration</label>
          <input type="date" class="form-input" name="expires_at">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-ghost modal-close">Annuler</button>
        <button type="submit" class="btn btn-gold">Générer le code</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
</body>
</html>
