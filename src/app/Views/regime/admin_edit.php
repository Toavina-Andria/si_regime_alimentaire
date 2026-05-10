<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le régime</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
</head>
<body>
<div class="dashboard-layout">
    <?= $this->include('bar/sidebar') ?>
<div class="main-content" style="max-width: 600px; margin: 40px auto;">
    <style>
        .field-error { display: block; color: #e53e3e; font-size: 0.78rem; margin-top: 4px; font-weight: 500; }
        .alert { padding: 0.75rem 1rem; border-radius: 12px; margin-bottom: 1rem; font-size: 0.9rem; }
        .alert-danger { background: #fff5f5; border-left: 4px solid #e53e3e; color: #c53030; }
        label { display: block; font-weight: 600; font-size: 0.85rem; margin: 1rem 0 0.3rem; color: #2D6A4F; }
        input, textarea { width: 100%; padding: 0.6rem 0.9rem; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.95rem; }
        input:focus, textarea:focus { outline: none; border-color: #2D6A4F; }
    </style>
    <h1>✏️ Modifier le régime</h1>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger"><?= implode('<br>', session()->getFlashdata('errors')) ?></div>
    <?php endif; ?>
    <?php $validation = \Config\Services::validation(); ?>
    <form action="<?= base_url('regime/admin/update/' . $regime['id']) ?>" method="POST">
        <?= csrf_field() ?>
        <label>Nom</label>
        <input type="text" name="nom" value="<?= old('nom', esc($regime['nom'])) ?>" required>
        <?= $validation ? $validation->showError('nom', '') : '' ?>

        <label>Description</label>
        <textarea name="description"><?= old('description', esc($regime['description'])) ?></textarea>

        <label>% Viande</label>
        <input type="number" name="pct_viande" step="0.01" value="<?= old('pct_viande', $regime['pct_viande']) ?>" required>
        <?= $validation ? $validation->showError('pct_viande', '') : '' ?>

        <label>% Volaille</label>
        <input type="number" name="pct_volaille" step="0.01" value="<?= old('pct_volaille', $regime['pct_volaille']) ?>" required>
        <?= $validation ? $validation->showError('pct_volaille', '') : '' ?>

        <label>% Poisson</label>
        <input type="number" name="pct_poisson" step="0.01" value="<?= old('pct_poisson', $regime['pct_poisson']) ?>" required>
        <?= $validation ? $validation->showError('pct_poisson', '') : '' ?>

        <label>Variation de poids (kg)</label>
        <input type="number" name="variation_poids_kg" step="0.1" value="<?= old('variation_poids_kg', $regime['variation_poids_kg']) ?>" required>

        <label>Durée recommandée (jours)</label>
        <input type="number" name="duree_jours" min="1" value="<?= old('duree_jours', $regime['duree_jours']) ?>" required>

        <button type="submit" class="btn-primary">Mettre à jour</button>
        <a href="<?= base_url('regime/admin') ?>" class="btn-outline">Annuler</a>
    </form>
</div>
</div>
</body>
</html>