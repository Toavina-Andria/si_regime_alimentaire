<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Compléter mon profil</title>
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <form action="<?= site_url('auth/updateProfil') ?>" method="POST">
        <h3>Bienvenue <?= session()->get('user_nom') ?> !</h3>
        <p>Complétez vos informations pour votre programme de régime.</p>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="error" style="color:red; margin-bottom:15px;">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <label>Date de naissance :</label>
        <input type="date" name="date_naissance" required>

        <label>Genre :</label>
        <select name="genre" required>
            <option value="">Sélectionnez</option>
            <option value="homme">Homme</option>
            <option value="femme">Femme</option>
        </select>

        <label>Adresse (optionnelle) :</label>
        <input type="text" name="adresse" placeholder="Votre adresse complète">

        <label>Taille (cm) :</label>
        <input type="number" name="taille_cm" step="0.01" placeholder="Ex: 175" required>

        <label>Poids (kg) :</label>
        <input type="number" name="poids_kg" step="0.01" placeholder="Ex: 70.5" required>

        <label>Votre Objectif :</label>
        <select name="objectif" required>
            <option value="">Sélectionnez</option>
            <option value="augmenter_poids">Prendre du poids</option>
            <option value="reduire_poids">Perdre du poids</option>
            <option value="imc_ideal">Atteindre mon IMC idéal</option>
        </select>

        <button type="submit">Enregistrer mon profil</button>
    </form>
</body>
</html>