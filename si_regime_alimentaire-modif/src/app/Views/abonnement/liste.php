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
            <?= $this->include('bar/sidebar') ?>


            <div class="container mt-4">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Header -->
                        <h1 class="mb-4">
                            <i class="fas fa-gift"></i> Nos Abonnements
                        </h1>

                        <!-- Alert Messages -->
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Abonnements Grid -->
                        <div class="row">
                            <?php if (!empty($abonnements)): ?>
                                <?php foreach ($abonnements as $abonnement): ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100 shadow-sm">
                                            <!-- Card Header -->
                                            <div class="card-header bg-primary text-white">
                                                <h5 class="mb-0"><?= esc($abonnement['nom']) ?></h5>
                                            </div>

                                            <!-- Card Body -->
                                            <div class="card-body">
                                                <!-- Description -->
                                                <?php if (!empty($abonnement['description'])): ?>
                                                    <p class="card-text text-muted">
                                                        <?= esc(substr($abonnement['description'], 0, 100)) ?>
                                                        <?= strlen($abonnement['description']) > 100 ? '...' : '' ?>
                                                    </p>
                                                <?php endif; ?>

                                                <!-- Features -->
                                                <div class="mb-3">
                                                    <h6>Avantages:</h6>
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            <i class="fas fa-percent text-success"></i>
                                                            Réduction:
                                                            <strong><?= $abonnement['taux_reduction'] ?? 0 ?>%</strong>
                                                        </li>
                                                        <li>
                                                            <i class="fas fa-calendar-alt text-info"></i>
                                                            Durée: <strong>30 jours</strong>
                                                        </li>
                                                        <li>
                                                            <i class="fas fa-coins text-warning"></i>
                                                            Coût: <strong><?= number_format($abonnement['prix'] ?? 0, 2) ?>
                                                                points</strong>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <!-- Status Badge -->
                                                <div class="mb-3">
                                                    <span class="badge bg-success">Disponible</span>
                                                    <?php if ($activeSubscription && $activeSubscription['abonnement_id'] == $abonnement['id']): ?>
                                                        <span class="badge bg-info">Actif</span>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Active Subscription Notice -->
                                                <?php if ($activeSubscription && $activeSubscription['abonnement_id'] == $abonnement['id']): ?>
                                                    <div class="alert alert-info alert-sm" role="alert">
                                                        ✓ Actif jusqu'au
                                                        <strong><?= date('d/m/Y', strtotime($activeSubscription['date_fin'])) ?></strong>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Card Footer -->
                                            <div class="card-footer bg-light">
                                                <?php if (!$activeSubscription): ?>
                                                    <a href="<?= base_url('abonnement/' . $abonnement['id']) ?>"
                                                        class="btn btn-primary w-100">
                                                        <i class="fas fa-shopping-cart"></i> Souscrire
                                                    </a>
                                                <?php elseif ($activeSubscription['abonnement_id'] != $abonnement['id']): ?>
                                                    <button class="btn btn-secondary w-100" disabled>
                                                        <i class="fas fa-lock"></i> Abonnement actif
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-success w-100" disabled>
                                                        <i class="fas fa-check"></i> Abonnement actif
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- No Abonnements Message -->
                                <div class="col-12">
                                    <div class="alert alert-info text-center" role="alert">
                                        <p class="mb-0">Aucun abonnement disponible pour le moment.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Back Button -->
                        <div class="mt-4">
                            <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour au tableau de bord
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
<!-- Styles -->
<style>
    .card {
        border: none;
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card-header {
        border-bottom: 2px solid #0d6efd;
    }

    .list-unstyled li {
        margin-bottom: 0.5rem;
    }

    .alert-sm {
        padding: 0.5rem 0.75rem;
        margin-bottom: 0;
        font-size: 0.9rem;
    }
</style>