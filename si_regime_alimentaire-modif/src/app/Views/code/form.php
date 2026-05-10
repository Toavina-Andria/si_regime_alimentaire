<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriPlan — Tableau de bord</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Bebas+Neue&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
</head>

<body>

    <?= $this->include('bar/sidebar') ?>
    <div class="main-content">

        <main class="page-content">
            <form action="/code/verify" method="post">
                <label for="code">Enter your code:</label>
                <input type="text" id="code" name="code" required>
                <button type="submit">Redeem</button>
            </form>
            <?php if (isset($status)) {
                if ($status === 0) { ?>
                    <p style="color: red;"><?= $message ?></p>
                <?php } else { ?>
                    <p style="color: green;"><?= $message ?></p>
                <?php }
            } ?>
        </main>
    </div>
</body>

</html>