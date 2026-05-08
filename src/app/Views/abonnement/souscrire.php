<?php
// Get the CSRF token for form submission
$csrf_token = csrf_hash();
$csrf_name = csrf_token();
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Header -->
            <h1 class="mb-4">Souscrire à un abonnement</h1>

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

            <!-- Abonnement Details Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><?= esc($abonnement['nom'] ?? 'Abonnement') ?></h5>
                </div>
                <div class="card-body">
                    <!-- Description -->
                    <?php if (!empty($abonnement['description'])): ?>
                        <div class="mb-3">
                            <h6>Description:</h6>
                            <p><?= esc($abonnement['description']) ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Status -->
                    <div class="mb-3">
                        <strong>Statut:</strong>
                        <span class="badge bg-info"><?= esc($abonnement['statut'] ?? 'Actif') ?></span>
                    </div>

                    <!-- Discount Rate -->
                    <div class="mb-3">
                        <strong>Taux de réduction:</strong>
                        <span><?= $abonnement['taux_reduction'] ?? 0 ?>%</span>
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <strong>Prix:</strong>
                        <span class="h5 text-success"><?= number_format($abonnement['prix'] ?? 0, 2) ?> points</span>
                    </div>

                    <!-- Active Subscription Notice -->
                    <?php if ($activeSubscription): ?>
                        <div class="alert alert-warning" role="alert">
                            ✓ Vous avez déjà un abonnement actif jusqu'au 
                            <strong><?= date('d/m/Y', strtotime($activeSubscription['date_fin'])) ?></strong>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Subscription Form -->
            <?php if (!$activeSubscription): ?>
                <form method="POST" action="<?= base_url('abonnement/souscrire') ?>" class="card">
                    <div class="card-body">
                        <!-- CSRF Token (hidden) -->
                        <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_token ?>">
                        
                        <!-- Abonnement ID (hidden) -->
                        <input type="hidden" name="abonnement_id" value="<?= $abonnement['id'] ?>">

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-check-circle"></i> Souscrire maintenant
                        </button>

                        <!-- Cancel Button -->
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary btn-lg w-100 mt-2">
                            Annuler
                        </a>
                    </div>
                </form>
            <?php else: ?>
                <!-- Message if already subscribed -->
                <div class="card">
                    <div class="card-body text-center">
                        <p class="mb-3">Vous avez déjà un abonnement actif. Vous ne pouvez souscrire à un autre abonnement que lorsque le vôtre aura expiré.</p>
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-primary">
                            Retour au tableau de bord
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Info Section -->
            <div class="alert alert-info mt-4" role="alert">
                <h6 class="alert-heading">ℹ️ Informations</h6>
                <small>
                    Cet abonnement vous permettra de bénéficier d'une réduction de <?= $abonnement['taux_reduction'] ?? 0 ?>% 
                    sur vos achats de régimes alimentaires. Valable 30 jours à partir de la souscription.
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Optional: Include Bootstrap CSS for styling (if not already included in layout) -->
<style>
    .container {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }
    
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: none;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }
</style>
