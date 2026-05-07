<table>
    <tr>
        <th>Regime</th>
        <th>Description</th>
        <th>% Viande</th>
        <th>% Volaille</th>
        <th>% Poisson</th>
        <th>variation poids (kg)</th>
        <th>Durée (jours)</th>
        <th>Actions</th>
    </tr>
<?php foreach ($regimes as $regime): ?>
    <tr>
        <td><?= $regime['nom'] ?></td>
        <td><?= $regime['description'] ?></td>
        <td><?= $regime['pct_viande'] ?>%</td>
        <td><?= $regime['pct_volaille'] ?>%</td>
        <td><?= $regime['pct_poisson'] ?>%</td>
        <td><?= $regime['variation_poids_kg'] ?></td>
        <td><?= $regime['duree_jours'] ?></td>
        <td><a href="<?= base_url('regime/' . $regime['id']) ?>">Voir détails</a></td>
    </tr>
<?php endforeach; ?>
</table>
