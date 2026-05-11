<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan — Gestion des abonnements</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
</head>
<body>
<div class="dashboard-layout">

  <?= $this->include('bar/sidebar') ?>

  <div class="main-content">
    <main class="page-content">
      <button class="mobile-hamburger" aria-label="Menu">☰</button>
      <div class="page-header" style="display:flex; align-items:center; justify-content:space-between;">
        <div>
          <h1 class="page-title">Abonnements</h1>
          <p class="page-subtitle">Gérez les types d'abonnement disponibles</p>
        </div>
        <button class="btn btn-primary" data-modal="modalAbonnement">+ Nouvel abonnement</button>
      </div>

      <?php if (session()->getFlashdata('success')): ?>
        <div style="background:#D1FAE5;color:#065F46;padding:14px 20px;border-radius:12px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
          <span>✅</span><span><?= session()->getFlashdata('success') ?></span>
        </div>
      <?php endif; ?>

      <div class="table-card">
        <table class="data-table">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Statut</th>
              <th>Réduction</th>
              <th>Prix (points)</th>
              <th>Description</th>
              <th>Créé le</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($abonnements)): ?>
            <tr>
              <td colspan="7" style="text-align:center; color:var(--color-text-muted); padding:48px;">
                <div style="font-size:36px; margin-bottom:12px; opacity:0.5;">🎯</div>
                <div style="font-size:16px; font-weight:600; margin-bottom:4px;">Aucun abonnement</div>
                <div style="font-size:13px;">Créez votre premier type d'abonnement</div>
              </td>
            </tr>
            <?php else: ?>
            <?php foreach ($abonnements as $a): ?>
            <tr>
              <td><strong><?= esc($a['nom']) ?></strong></td>
              <td>
                <?php if ($a['statut'] == 'gold'): ?>
                  <span class="pill pill-gold">⭐ Gold</span>
                <?php else: ?>
                  <span class="pill pill-success">🔹 <?= esc(ucfirst($a['statut'])) ?></span>
                <?php endif; ?>
              </td>
              <td><?= $a['taux_reduction'] ?>%</td>
              <td><strong><?= number_format($a['prix'], 2) ?></strong></td>
              <td style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; color:var(--color-text-secondary);">
                <?= esc($a['description'] ?? '—') ?>
              </td>
              <td><?= date('d/m/Y', strtotime($a['created_at'])) ?></td>
              <td>
                <div class="action-btns">
                  <button class="action-btn js-edit-abonnement" data-id="<?= $a['id'] ?>" data-nom="<?= esc($a['nom']) ?>" data-statut="<?= esc($a['statut']) ?>" data-taux="<?= $a['taux_reduction'] ?>" data-prix="<?= $a['prix'] ?>" data-desc="<?= esc($a['description'] ?? '') ?>" title="Modifier">✏️</button>
                  <a href="<?= base_url('admin/abonnement/delete/' . $a['id']) ?>" class="action-btn delete" title="Supprimer" onclick="return confirm('Supprimer cet abonnement ?')">🗑️</a>
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

<div class="modal-overlay" id="modalAbonnement">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title" id="modalTitle">Nouvel abonnement</div>
      <button class="modal-close">&times;</button>
    </div>
    <form id="abonnementForm" method="POST">
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Nom</label>
          <input type="text" class="form-input" name="nom" required placeholder="ex: Gold">
        </div>
        <div class="form-group">
          <label class="form-label">Statut</label>
          <select class="form-input form-select" name="statut" required>
            <option value="gold">Gold</option>
            <option value="free">Gratuit</option>
          </select>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
          <div class="form-group">
            <label class="form-label">Taux réduction (%)</label>
            <input type="number" class="form-input" name="taux_reduction" step="0.01" min="0" max="100" required>
          </div>
          <div class="form-group">
            <label class="form-label">Prix (points)</label>
            <input type="number" class="form-input" name="prix" step="0.01" min="0" required>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea class="form-input" name="description" rows="3" style="resize:vertical;" placeholder="Avantages de l'abonnement..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-ghost modal-close">Annuler</button>
        <button type="submit" class="btn btn-primary" id="submitBtn">Créer l'abonnement</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var modal = document.getElementById('modalAbonnement');
  var form = document.getElementById('abonnementForm');
  var modalTitle = document.getElementById('modalTitle');
  var submitBtn = document.getElementById('submitBtn');

  document.querySelector('[data-modal="modalAbonnement"]').addEventListener('click', function () {
    form.action = '<?= base_url('admin/abonnement/store') ?>';
    form.reset();
    modalTitle.textContent = 'Nouvel abonnement';
    submitBtn.textContent = 'Créer l\'abonnement';
    openModal(modal);
  });

  document.querySelectorAll('.js-edit-abonnement').forEach(function (btn) {
    btn.addEventListener('click', function () {
      form.action = '<?= base_url('admin/abonnement/update') ?>/' + btn.dataset.id;
      form.elements.nom.value = btn.dataset.nom;
      form.elements.statut.value = btn.dataset.statut;
      form.elements.taux_reduction.value = btn.dataset.taux;
      form.elements.prix.value = btn.dataset.prix;
      form.elements.description.value = btn.dataset.desc;
      modalTitle.textContent = 'Modifier l\'abonnement';
      submitBtn.textContent = 'Mettre à jour';
      openModal(modal);
    });
  });
});
</script>
</body>
</html>
