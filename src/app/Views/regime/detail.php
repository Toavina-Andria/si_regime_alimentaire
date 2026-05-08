<h1><?= $regime['nom'] ?></h1>

<p><?= $regime['description'] ?></p>
<p><?= $regime['variation_poids_kg'] ?></p>
<p><?= $regime['duree_jours'] ?></p>

<table>
    <tr>
        <th>% Viande</th>
        <th>% Volaille</th>
        <th>% Poisson</th>
    </tr>
    <tr>
        <td><?= $regime['pct_viande'] ?>%</td>
        <td><?= $regime['pct_volaille'] ?>%</td>
        <td><?= $regime['pct_poisson'] ?>%</td>
    </tr>
</table>

<!-- prix diponible pour le regime -->
<ul>
    <?php foreach ($prix as $p): ?>
    <li> duree de <?= $p['duree_jours'] ?> ,coute <?= $p['prix_base'] ?>€</li>
    <?php endforeach; ?>
</ul>
