<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan — Utilisateurs</title>
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

      <div class="page-header page-header-row">
        <div>
          <h1 class="page-title">Utilisateurs</h1>
          <p class="page-subtitle">Gérez les comptes utilisateurs de la plateforme</p>
        </div>
      </div>

      <div class="table-card">
        <table class="data-table">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Email</th>
              <th>Genre</th>
              <th>Taille</th>
              <th>Poids</th>
              <th>Objectif</th>
              <th>Admin</th>
              <th>Inscrit le</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($utilisateurs)): ?>
            <tr>
              <td colspan="10" class="empty-table">
                <div class="empty-table-icon">👥</div>
                <div class="empty-table-title">Aucun utilisateur</div>
                <div class="empty-table-text">Les utilisateurs inscrits apparaîtront ici</div>
              </td>
            </tr>
            <?php else: ?>
            <?php foreach ($utilisateurs as $u): ?>
            <tr>
              <td><strong><?= esc($u['nom']) ?></strong></td>
              <td><?= esc($u['prenom']) ?></td>
              <td><?= esc($u['email']) ?></td>
              <td><?= $u['genre'] ?? '—' ?></td>
              <td><?= $u['taille_cm'] ? $u['taille_cm'] . ' cm' : '—' ?></td>
              <td><?= $u['poids_kg'] ? $u['poids_kg'] . ' kg' : '—' ?></td>
              <td>
                <?php if ($u['objectif'] == 'augmenter_poids'): ?>
                <span class="pill pill-warning">↑ Prendre</span>
                <?php elseif ($u['objectif'] == 'reduire_poids'): ?>
                <span class="pill pill-danger">↓ Perdre</span>
                <?php elseif ($u['objectif'] == 'imc_ideal'): ?>
                <span class="pill pill-success">✓ IMC idéal</span>
                <?php else: ?>
                <span class="pill pill-info">—</span>
                <?php endif; ?>
              </td>
              <td><?= !empty($u['est_admin']) ? '👨‍💼' : '' ?></td>
              <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
              <td>
                <div class="action-btns">
                  <button class="action-btn js-edit-user" data-id="<?= $u['id'] ?>" data-nom="<?= esc($u['nom']) ?>" data-prenom="<?= esc($u['prenom']) ?>" data-email="<?= esc($u['email']) ?>" data-admin="<?= !empty($u['est_admin']) ? 1 : 0 ?>" title="Modifier">✏️</button>
                  <a href="<?= base_url('admin/utilisateurs/delete/' . $u['id']) ?>" class="action-btn delete" title="Supprimer" onclick="return confirm('Supprimer cet utilisateur ?')">🗑️</a>
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

<div class="modal-overlay" id="modalUser">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Modifier l'utilisateur</div>
      <button class="modal-close">&times;</button>
    </div>
    <form id="userForm" method="POST">
      <?= csrf_field() ?>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Nom</label>
          <input type="text" class="form-input" name="nom_display" readonly>
        </div>
        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" class="form-input" name="email_display" readonly>
        </div>
        <div class="form-group">
          <label class="form-label">Rôle administrateur</label>
          <select class="form-input form-select" name="est_admin">
            <option value="0">👤 Utilisateur</option>
            <option value="1">👨‍💼 Administrateur</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-ghost modal-close">Annuler</button>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var modal = document.getElementById('modalUser');
  var form = document.getElementById('userForm');

  document.querySelectorAll('.js-edit-user').forEach(function (btn) {
    btn.addEventListener('click', function () {
      form.action = '<?= base_url('admin/utilisateurs/update') ?>/' + btn.dataset.id;
      form.elements.nom_display.value = btn.dataset.prenom + ' ' + btn.dataset.nom;
      form.elements.email_display.value = btn.dataset.email;
      form.elements.est_admin.value = btn.dataset.admin;
      openModal(modal);
    });
  });
});
</script>
</body>
</html>
