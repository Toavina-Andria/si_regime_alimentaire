<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Se connecter</title>
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <form action="<?= site_url('auth/doLogin') ?>" method="POST">
        <h3>Connexion</h3>

        <?php if(session()->getFlashdata('error')): ?>
            <div style="color:red; margin-bottom:15px;"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required placeholder="email@exemple.com">

        <label for="pwd">Mot de passe :</label>
        <input type="password" name="mot_de_passe" id="pwd" required>

        <button type="submit">Se connecter</button>
        <p>Pas encore de compte ? <a href="<?= site_url('/') ?>">Créer un compte</a></p>
    </form>
</body>
</html>