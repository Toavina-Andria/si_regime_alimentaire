<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriPlan — Codes bonus</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Bebas+Neue&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
    <style>
        .code-page { padding: 2rem; max-width: 500px; margin: 0 auto; }
        .code-page h1 { font-family: var(--font-heading); font-size: 1.75rem; margin-bottom: 0.5rem; color: var(--color-text-primary); }
        .code-page .subtitle { color: var(--color-text-secondary); margin-bottom: 2rem; font-size: 0.95rem; }
        .code-card { background: var(--color-surface); border-radius: 16px; border: 1px solid var(--color-border); padding: 2rem; }
        .code-card label { display: block; font-size: 0.9rem; font-weight: 600; color: var(--color-text-primary); margin-bottom: 0.5rem; }
        .code-card input[type="text"] { width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--color-border); border-radius: 10px; font-family: var(--font-body); font-size: 1rem; outline: none; transition: border-color 0.2s; }
        .code-card input[type="text"]:focus { border-color: var(--color-primary); }
        .code-card .btn { margin-top: 1rem; width: 100%; }
        .code-msg { margin-top: 1rem; padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.9rem; text-align: center; }
        .code-msg.success { background: #e9f4ef; color: var(--color-primary); border: 1px solid #b7d9c9; }
        .code-msg.error { background: #fef2f2; color: var(--color-danger); border: 1px solid #fecaca; }
    </style>
</head>
<body>
    <?= $this->include('bar/sidebar') ?>
    <div class="main-content">
        <main class="page-content">
            <div class="code-page">
                <h1>🎟️ Codes bonus</h1>
                <p class="subtitle">Entrez un code pour créditer des points sur votre portefeuille</p>

                <div class="code-card">
                    <form action="<?= base_url('code/verify') ?>" method="post">
                        <label for="code">Code bonus</label>
                        <input type="text" id="code" name="code" placeholder="Ex: WELCOME10" required>
                        <button type="submit" class="btn btn-primary">Valider le code</button>
                    </form>

                    <?php if (isset($status)): ?>
                        <div class="code-msg <?= $status === 0 ? 'error' : 'success' ?>">
                            <?= $message ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>