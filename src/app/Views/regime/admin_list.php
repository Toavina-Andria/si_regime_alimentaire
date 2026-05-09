<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des régimes</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
</head>
<body>
<div class="dashboard-layout">
    <?= $this->include('bar/sidebar') ?>
    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">Gestion des régimes</h1>
            <a href="<?= base_url('regime/admin/create') ?>" class="btn-primary">➕ Nouveau régime</a>
        </div>

        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th><th>Nom</th><th>Viande</th><th>Volaille</th><th>Poisson</th><th>Variation (kg)</th><th>Durée (j)</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($regimes as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= esc($r['nom']) ?></td>
                    <td><?= $r['pct_viande'] ?>%</td>
                    <td><?= $r['pct_volaille'] ?>%</td>
                    <td><?= $r['pct_poisson'] ?>%</td>
                    <td><?= $r['variation_poids_kg'] > 0 ? '+' : '' ?><?= $r['variation_poids_kg'] ?> kg</td>
                    <td><?= $r['duree_jours'] ?> j</td>
                    <td>
                        <a href="<?= base_url('regime/admin/edit/' . $r['id']) ?>" class="btn-outline">✏️</a>
                        <a href="<?= base_url('regime/admin/delete/' . $r['id']) ?>" class="btn-outline" onclick="return confirm('Supprimer ce régime ?')">🗑️</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= $pager->links() ?>
        <p><a href="<?= base_url('dashboard') ?>" class="btn-outline">← Retour au tableau de bord</a></p>
    </div>
</div>
</body>
</html>