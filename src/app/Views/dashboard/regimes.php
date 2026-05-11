<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan — Régimes alimentaires</title>
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
              <td colspan="8" class="empty-table">
                <div class="empty-table-icon">🥗</div>
                <div class="empty-table-title">Aucun régime</div>
                <div class="empty-table-text">Créez votre premier régime alimentaire</div>
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
                  <button type="button" class="action-btn js-edit-regime" data-id="<?= $r['id'] ?>" title="Modifier">✏️</button>
                  <a href="<?= base_url('regime/admin/delete/' . $r['id']) ?>" class="action-btn delete" title="Supprimer" onclick="return confirm('Supprimer ce régime ?')">🗑️</a>
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

<div class="modal-overlay" id="modalRegime">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Nouveau régime</div>
      <button class="modal-close">&times;</button>
    </div>
    <form id="regimeForm" action="<?= base_url('regime/admin/store') ?>" method="POST">
      <?= csrf_field() ?>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label" for="regime_nom">Nom du régime</label>
          <input id="regime_nom" type="text" class="form-input" name="nom" required placeholder="ex: Méditerranéen">
        </div>
        <div class="form-group">
          <label class="form-label" for="regime_description">Description</label>
          <textarea id="regime_description" class="form-input" name="description" rows="3" placeholder="Description du régime..." style="resize:vertical;"></textarea>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px;">
          <div class="form-group">
            <label class="form-label" for="regime_pct_viande">% Viande</label>
            <input id="regime_pct_viande" type="number" class="form-input" name="pct_viande" min="0" max="100" step="0.01" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="regime_pct_poisson">% Poisson</label>
            <input id="regime_pct_poisson" type="number" class="form-input" name="pct_poisson" min="0" max="100" step="0.01" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="regime_pct_volaille">% Volaille</label>
            <input id="regime_pct_volaille" type="number" class="form-input" name="pct_volaille" min="0" max="100" step="0.01" required>
          </div>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
          <div class="form-group">
            <label class="form-label" for="regime_duree_jours">Durée (jours)</label>
            <input id="regime_duree_jours" type="number" class="form-input" name="duree_jours" min="1" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="regime_variation_poids_kg">Variation poids (kg)</label>
            <input id="regime_variation_poids_kg" type="number" class="form-input" name="variation_poids_kg" step="0.1" required placeholder="ex: -2.5">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="regimeSubmitBtn" type="submit" class="btn btn-primary">Créer le régime</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('modalRegime');
    if (!modal) return;

    var form = document.getElementById('regimeForm');
    var modalTitle = modal.querySelector('.modal-title');
    var submitBtn = document.getElementById('regimeSubmitBtn');
    var createBtn = document.querySelector('[data-modal="modalRegime"]');
    var storeUrl = "<?= base_url('regime/admin/store') ?>";
    var updateBaseUrl = "<?= base_url('regime/admin/update') ?>";
    var detailBaseUrl = "<?= base_url('regime') ?>";

    function setCreateMode() {
      form.action = storeUrl;
      form.reset();
      modalTitle.textContent = 'Nouveau régime';
      submitBtn.textContent = 'Créer le régime';
    }

    if (createBtn) {
      createBtn.addEventListener('click', function () {
        setCreateMode();
      });
    }

    document.querySelectorAll('.js-edit-regime').forEach(function (btn) {
      btn.addEventListener('click', async function () {
        var regimeId = btn.getAttribute('data-id');
        try {
          var response = await fetch(detailBaseUrl + '/' + regimeId, {
            headers: {
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            }
          });

          if (!response.ok) {
            throw new Error('Erreur de chargement du régime.');
          }

          var regime = await response.json();

          form.elements.nom.value = regime.nom ?? '';
          form.elements.description.value = regime.description ?? '';
          form.elements.pct_viande.value = regime.pct_viande ?? '';
          form.elements.pct_poisson.value = regime.pct_poisson ?? '';
          form.elements.pct_volaille.value = regime.pct_volaille ?? '';
          form.elements.duree_jours.value = regime.duree_jours ?? '';
          form.elements.variation_poids_kg.value = regime.variation_poids_kg ?? '';

          form.action = updateBaseUrl + '/' + regimeId;
          modalTitle.textContent = 'Modifier le régime';
          submitBtn.textContent = 'Mettre à jour';

          openModal(modal);
        } catch (error) {
          alert('Impossible de charger le régime pour modification.');
        }
      });
    });

    if (form) {
      var originalSubmitText = submitBtn ? submitBtn.textContent : 'Envoyer';
      form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (!form.action) return;
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.textContent = 'Envoi...';
        }

        var fd = new FormData(form);
        try {
          var res = await fetch(form.action, {
            method: (form.method || 'POST'),
            body: fd,
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            },
            credentials: 'same-origin'
          });

          var contentType = (res.headers.get('content-type') || '').toLowerCase();

          if (contentType.indexOf('application/json') !== -1) {
            var data = await res.json();
            if (data && data.success) {
              if (typeof closeModal === 'function') {
                closeModal(modal);
              }
              window.location.reload();
            } else {
              var msg = (data && (data.message || data.error)) || 'Erreur lors de l\u2019enregistrement.';
              alert(msg);
            }
          } else if (res.redirected) {

            window.location = res.url;
          } else {

            window.location.reload();
          }
        } catch (err) {
          alert('Erreur réseau — impossible d\u2019envoyer le formulaire.');
        } finally {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = originalSubmitText;
          }
        }
      });
    }
  });
</script>
</body>
</html>
