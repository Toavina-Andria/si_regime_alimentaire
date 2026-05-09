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

<!-- Modal Nouveau Régime -->
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
        <button type="button" class="btn btn-ghost modal-close">Annuler</button>
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
  });
</script>
</body>
</html>
