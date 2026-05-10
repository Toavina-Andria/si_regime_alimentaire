<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau régime – NutriPlan</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
    <style>

        .form-card {
            background: white;
            border-radius: 32px;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            max-width: 720px;
            margin: 2rem auto;
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: #1e3a2f;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-left: 5px solid #2D6A4F;
            padding-left: 1.2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #2D6A4F;
            margin-bottom: 0.5rem;
        }

        input, textarea {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background-color: #fefcf5;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #2D6A4F;
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.2);
            background: white;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .alert {
            padding: 1rem;
            border-radius: 20px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        .alert-danger {
            background: #fff5f5;
            border-left: 5px solid #e53e3e;
            color: #c53030;
        }
        .field-error { display: block; color: #e53e3e; font-size: 0.78rem; margin-top: 4px; font-weight: 500; }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .btn-primary, .btn-outline {
            padding: 0.7rem 1.6rem;
            border-radius: 40px;
            font-weight: 600;
            transition: 0.2s;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .btn-primary {
            background: #2D6A4F;
            border: none;
            color: white;
        }
        .btn-primary:hover {
            background: #1e4a3a;
            transform: translateY(-2px);
        }
        .btn-outline {
            background: transparent;
            border: 1px solid #2D6A4F;
            color: #2D6A4F;
        }
        .btn-outline:hover {
            background: #e9f4f1;
        }

        @media (max-width: 640px) {
            .form-card {
                margin: 1rem;
                padding: 1.5rem;
            }
            .form-actions {
                flex-direction: column;
            }
            .btn-primary, .btn-outline {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
<div class="dashboard-layout">
    <?= $this->include('bar/sidebar') ?>
    <div class="main-content" style="background: #f5f7f6; min-height: 100vh;">
        <div class="form-card">
            <div class="form-title">
                <span>➕</span> Nouveau régime alimentaire
            </div>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <?= implode('<br>', session()->getFlashdata('errors')) ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('regime/admin/store') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label>Nom du régime</label>
                    <input type="text" name="nom" value="<?= old('nom') ?>" placeholder="ex: Régime Méditerranéen" required>
                    <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['nom'])): ?><small class="field-error"><?= session()->getFlashdata('errors')['nom'] ?></small><?php endif; ?>
                    <?php if ($validation = \Config\Services::validation()) : ?><?= $validation->showError('nom', '') ?><?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" placeholder="Décrivez les principes, bénéfices..."><?= old('description') ?></textarea>
                    <?php if ($validation = \Config\Services::validation()) : ?><?= $validation->showError('description', '') ?><?php endif; ?>
                </div>

                <div class="form-group">
                    <label>🥩 % Viande</label>
                    <input type="number" name="pct_viande" step="0.01" value="<?= old('pct_viande') ?>" required>
                    <?php if ($validation = \Config\Services::validation()) : ?><?= $validation->showError('pct_viande', '') ?><?php endif; ?>
                </div>

                <div class="form-group">
                    <label>🐔 % Volaille</label>
                    <input type="number" name="pct_volaille" step="0.01" value="<?= old('pct_volaille') ?>" required>
                    <?php if ($validation = \Config\Services::validation()) : ?><?= $validation->showError('pct_volaille', '') ?><?php endif; ?>
                </div>

                <div class="form-group">
                    <label>🐟 % Poisson</label>
                    <input type="number" name="pct_poisson" step="0.01" value="<?= old('pct_poisson') ?>" required>
                    <?php if ($validation = \Config\Services::validation()) : ?><?= $validation->showError('pct_poisson', '') ?><?php endif; ?>
                </div>

                <div class="form-group">
                    <label>⚖️ Variation de poids (kg)</label>
                    <input type="number" name="variation_poids_kg" step="0.1" placeholder="positif = prise / négatif = perte" value="<?= old('variation_poids_kg') ?>" required>
                    <?php if ($validation = \Config\Services::validation()) : ?><?= $validation->showError('variation_poids_kg', '') ?><?php endif; ?>
                </div>

                <div class="form-group">
                    <label>📅 Durée recommandée (jours)</label>
                    <input type="number" name="duree_jours" min="1" value="<?= old('duree_jours') ?>" required>
                    <?php if ($validation = \Config\Services::validation()) : ?><?= $validation->showError('duree_jours', '') ?><?php endif; ?>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">💾 Créer le régime</button>
                    <a href="<?= base_url('regime/admin') ?>" class="btn-outline">↩️ Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>