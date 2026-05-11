<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan — Portefeuille & Codes</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
</head>
<body>
<div class="dashboard-layout">

  <?= $this->include('bar/sidebar') ?>

  <div class="main-content">
    <main class="page-content">
      <button class="mobile-hamburger" aria-label="Menu">☰</button>
      <div class="page-header page-header-row">
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
              <td colspan="6" class="empty-table">
                <div class="empty-table-icon">🎟️</div>
                <div class="empty-table-title">Aucun code bonus</div>
                <div class="empty-table-text">Générez votre premier code bonus</div>
              </td>
            </tr>
            <?php else: ?>
            <?php foreach ($codes as $c): ?>
            <tr>
              <td><code class="code-display"><?= esc($c['code']) ?></code></td>
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
      <?= $this->include('bar/footer') ?>
    </main>
  </div>
</div>

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
        <div class="form-group js-hidden" id="codeValidGroup">
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
