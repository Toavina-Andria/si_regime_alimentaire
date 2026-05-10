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

  <?= $this->include('bar/sidebar') ?>

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
          <a href="<?= base_url('admin/dashboard') ?>">Accueil</a>
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
                  <button class="action-btn js-edit-code" data-id="<?= $c['id'] ?>" data-code="<?= esc($c['code']) ?>" data-points="<?= $c['valeur_points'] ?>" data-expires="<?= $c['expires_at'] ?>" data-valide="<?= $c['est_valide'] ?>" title="Modifier">✏️</button>
                  <a href="<?= base_url('admin/codes/delete/' . $c['id']) ?>" class="action-btn delete" title="Supprimer" onclick="return confirm('Supprimer ce code ?')">🗑️</a>
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
    <form id="codeForm" method="POST">
      <?= csrf_field() ?>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Code</label>
          <input type="text" class="form-input" name="code" placeholder="ex: NUTRI2026" value="NUTRI-<?= strtoupper(substr(md5(uniqid()), 0, 6)) ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Montant (€)</label>
          <input type="number" class="form-input" name="valeur_points" min="1" step="0.5" required placeholder="ex: 10">
        </div>
        <div class="form-group" id="codeExpiryGroup">
          <label class="form-label">Date d'expiration</label>
          <input type="date" class="form-input" name="expires_at">
        </div>
        <div class="form-group" id="codeValidGroup" style="display:none;">
          <label class="form-label">Statut</label>
          <select class="form-input form-select" name="est_valide">
            <option value="1">✅ Validé</option>
            <option value="0">❌ Expiré</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-ghost modal-close">Annuler</button>
        <button type="submit" class="btn btn-gold" id="codeSubmitBtn">Générer le code</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var modal = document.getElementById('modalCode');
  var form = document.getElementById('codeForm');
  var modalTitle = modal.querySelector('.modal-title');
  var submitBtn = document.getElementById('codeSubmitBtn');
  var expiryGroup = document.getElementById('codeExpiryGroup');
  var validGroup = document.getElementById('codeValidGroup');

  document.querySelector('[data-modal="modalCode"]').addEventListener('click', function () {
    form.action = '<?= base_url('admin/codes') ?>';
    form.reset();
    modalTitle.textContent = 'Générer un code bonus';
    submitBtn.textContent = 'Générer le code';
    expiryGroup.style.display = '';
    validGroup.style.display = 'none';
  });

  document.querySelectorAll('.js-edit-code').forEach(function (btn) {
    btn.addEventListener('click', function () {
      form.action = '<?= base_url('admin/codes/update') ?>/' + btn.dataset.id;
      form.elements.code.value = btn.dataset.code;
      form.elements.valeur_points.value = btn.dataset.points;
      form.elements.expires_at.value = btn.dataset.expires ? btn.dataset.expires.substring(0, 10) : '';
      if (form.elements.est_valide) form.elements.est_valide.value = btn.dataset.valide;
      modalTitle.textContent = 'Modifier le code bonus';
      submitBtn.textContent = 'Mettre à jour';
      expiryGroup.style.display = 'none';
      validGroup.style.display = '';
      openModal(modal);
    });
  });
});
</script>
</body>
</html>
