<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte</title>
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <form action="<?= site_url('auth/register') ?>" method="POST">
        <h3>Create Account</h3>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="error">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" required placeholder="Votre nom">

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" required placeholder="Votre prénom">

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required placeholder="email@exemple.com">

        <label for="pwd">Mot de passe :</label>
        <input type="password" id="pwd" name="mot_de_passe" required placeholder="Mot de passe (6 caractères min)">

        <button type="submit">Sign up</button>
        <p>Déjà inscrit ? <a href="<?= site_url('connexion') ?>">Se connecter</a></p>
    </form>
</body>
</html>