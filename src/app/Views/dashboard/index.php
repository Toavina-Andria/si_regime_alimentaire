<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
    <div style="text-align:center; margin-top:50px;">
        <h1>Bienvenue <?= session()->get('user_nom') ?> !</h1>
        <p>Votre email : <?= session()->get('user_email') ?></p>
        <a href="<?= site_url('auth/logout') ?>">Se déconnecter</a>
    </div>
</body>
</html>