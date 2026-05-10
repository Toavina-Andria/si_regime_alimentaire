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
    <h1>✏️ Modifier le régime</h1>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger"><?= implode('<br>', session()->getFlashdata('errors')) ?></div>
    <?php endif; ?>
    <form action="<?= base_url('regime/admin/update/' . $regime['id']) ?>" method="POST">
        <?= csrf_field() ?>
        <label>Nom</label>
        <input type="text" name="nom" value="<?= esc($regime['nom']) ?>" required>

        <label>Description</label>
        <textarea name="description"><?= esc($regime['description']) ?></textarea>

        <label>% Viande</label>
        <input type="number" name="pct_viande" step="0.01" value="<?= $regime['pct_viande'] ?>" required>

        <label>% Volaille</label>
        <input type="number" name="pct_volaille" step="0.01" value="<?= $regime['pct_volaille'] ?>" required>

        <label>% Poisson</label>
        <input type="number" name="pct_poisson" step="0.01" value="<?= $regime['pct_poisson'] ?>" required>

        <label>Variation de poids (kg)</label>
        <input type="number" name="variation_poids_kg" step="0.1" value="<?= $regime['variation_poids_kg'] ?>" required>

        <label>Durée recommandée (jours)</label>
        <input type="number" name="duree_jours" min="1" value="<?= $regime['duree_jours'] ?>" required>

        <button type="submit" class="btn-primary">Mettre à jour</button>
        <a href="<?= base_url('regime/admin') ?>" class="btn-outline">Annuler</a>
    </form>
</div>
</div>
</body>
</html>