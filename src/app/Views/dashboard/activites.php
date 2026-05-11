<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan — Activités sportives</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Bebas+Neue&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
</head>
<body>
<div class="dashboard-layout">

  <?= $this->include('bar/sidebar') ?>

  <div class="main-content">
    <main class="page-content">
      <button class="mobile-hamburger" aria-label="Menu">☰</button>
      <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>
      <?php if ($errors = session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger"><?= implode('<br>', $errors) ?></div>
      <?php endif; ?>

      <div class="page-header" style="display:flex; align-items:center; justify-content:space-between;">
        <div>
          <h1 class="page-title">Activités sportives</h1>
          <p class="page-subtitle">Gérez les activités physiques associées aux régimes</p>
        </div>
        <button class="btn btn-primary" data-modal="modalActivite">+ Nouvelle activité</button>
      </div>

      <div class="table-card">
        <table class="data-table">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Description</th>
              <th>Intensité</th>
              <th>Calories/heure</th>
              <th>Date</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($activites)): ?>
            <tr>
              <td colspan="6" style="text-align:center; color:var(--color-text-muted); padding:48px;">
                <div style="font-size:36px; margin-bottom:12px; opacity:0.5;">🏃</div>
                <div style="font-size:16px; font-weight:600; margin-bottom:4px;">Aucune activité</div>
                <div style="font-size:13px;">Ajoutez votre première activité sportive</div>
              </td>
            </tr>
            <?php else: ?>
            <?php foreach ($activites as $a): ?>
            <tr>
              <td><strong><?= esc($a['nom']) ?></strong></td>
              <td style="color:var(--color-text-secondary); max-width:250px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?= esc($a['description']) ?></td>
              <td>
                <?php if ($a['intensite'] == 1): ?>
                <span class="pill pill-success">Faible</span>
                <?php elseif ($a['intensite'] == 2): ?>
                <span class="pill pill-warning">Modéré</span>
                <?php else: ?>
                <span class="pill pill-danger">Intense</span>
                <?php endif; ?>
              </td>
              <td><?= $a['calories_heure'] ?> kcal</td>
              <td><?= date('d/m/Y', strtotime($a['created_at'])) ?></td>
              <td>
                <div class="action-btns">
                  <button class="action-btn js-edit-activite" data-id="<?= $a['id'] ?>" data-nom="<?= esc($a['nom']) ?>" data-description="<?= esc($a['description'] ?? '') ?>" data-intensite="<?= $a['intensite'] ?>" data-calories="<?= $a['calories_heure'] ?>" title="Modifier">✏️</button>
                  <a href="<?= base_url('admin/activites/delete/' . $a['id']) ?>" class="action-btn delete" title="Supprimer" onclick="return confirm('Supprimer cette activité ?')">🗑️</a>
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

<div class="modal-overlay" id="modalActivite">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Nouvelle activité sportive</div>
      <button class="modal-close">&times;</button>
    </div>
    <form id="activiteForm" method="POST">
      <?= csrf_field() ?>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Nom de l'activité</label>
          <input type="text" class="form-input" name="nom" required placeholder="ex: Marche rapide">
        </div>
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea class="form-input" name="description" rows="3" placeholder="Description..." style="resize:vertical;"></textarea>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
          <div class="form-group">
            <label class="form-label">Intensité</label>
            <select class="form-input form-select" name="intensite">
              <option value="1">Faible</option>
              <option value="2" selected>Modéré</option>
              <option value="3">Intense</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Calories / heure</label>
            <input type="number" class="form-input" name="calories_heure" min="0" step="10" placeholder="ex: 300">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-ghost modal-close">Annuler</button>
        <button type="submit" class="btn btn-primary" id="activiteSubmitBtn">Ajouter l'activité</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var modal = document.getElementById('modalActivite');
  var form = document.getElementById('activiteForm');
  var modalTitle = modal.querySelector('.modal-title');
  var submitBtn = document.getElementById('activiteSubmitBtn');

  document.querySelector('[data-modal="modalActivite"]').addEventListener('click', function () {
    form.action = '<?= base_url('admin/activites') ?>';
    form.reset();
    modalTitle.textContent = 'Nouvelle activité sportive';
    submitBtn.textContent = 'Ajouter l\'activité';
  });

  document.querySelectorAll('.js-edit-activite').forEach(function (btn) {
    btn.addEventListener('click', function () {
      form.action = '<?= base_url('admin/activites/update') ?>/' + btn.dataset.id;
      form.elements.nom.value = btn.dataset.nom;
      form.elements.description.value = btn.dataset.description;
      form.elements.intensite.value = btn.dataset.intensite;
      form.elements.calories_heure.value = btn.dataset.calories;
      modalTitle.textContent = 'Modifier l\'activité';
      submitBtn.textContent = 'Mettre à jour';
      openModal(modal);
    });
  });
});
</script>
</body>
</html>
