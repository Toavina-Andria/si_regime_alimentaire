<aside class="sidebar">
    <div class="sidebar-logo">
        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
            <path d="M16 2C8.268 2 2 8.268 2 16s6.268 14 14 14 14-6.268 14-14S23.732 2 16 2z" fill="#2D6A4F" />
            <path d="M16 6c-1.5 3-4.5 5-7 7 2 2.5 4 5.5 5 9 2.5-1.5 5-4 7-7-2.5-2-4.5-5-5-9z" fill="#D4A853"
                opacity="0.8" />
            <path d="M11 20c3 1 6 2 8 4 2-2 5-3 8-4" stroke="#52B788" stroke-width="1.5" stroke-linecap="round" />
        </svg>
        <div class="sidebar-logo-text">
            <a href="<?= session()->get('est_admin') ? base_url('admin/dashboard') : base_url('regimes') ?>">NutriPlan
                <small><?= session()->get('est_admin') ? 'Admin' : 'User' ?> Panel</small>
            </a>
        </div>
    </div>
<?php
helper('navigation');

?>
    <nav class="sidebar-nav">

        <?php if (session()->get('est_admin')): ?>
            <div class="sidebar-section-label">👨‍💼 Administration</div>

            <div class="sidebar-section">
                <div class="sidebar-section-title">Tableau de bord</div>
                <a href="<?= base_url('admin/dashboard') ?>"
                    class="sidebar-link <?= navActive('admin/dashboard') ?>">
                    <span class="icon">📊</span>
                    Tableau de bord
                </a>

            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title">Gestion</div>
                <a href="<?= base_url('admin/regimes') ?>"
                    class="sidebar-link <?= navActive(['admin/regimes', 'regime/admin']) ?>">
                    <span class="icon">🥗</span>
                    Régimes alimentaires
                </a>
                <a href="<?= base_url('admin/activites') ?>"
                    class="sidebar-link <?= navActive('admin/activites') ?>">
                    <span class="icon">🏃</span>
                    Activités sportives
                </a>
                <a href="<?= base_url('admin/codes') ?>"
                    class="sidebar-link <?= navActive('admin/codes') ?>">
                    <span class="icon">💰</span>
                    Codes bonus
                </a>
                <a href="<?= base_url('admin/utilisateurs') ?>"
                    class="sidebar-link <?= navActive('admin/utilisateurs') ?>">
                    <span class="icon">👥</span>
                    Utilisateurs
                </a>
            </div>

        <?php endif; ?>

        <?php if (!session()->get('est_admin')): ?>
        <div class="sidebar-section-label">👤 Mon Espace</div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Vue d'ensemble</div>
            <a href="<?= base_url('dashboard') ?>"
                class="sidebar-link <?= navActive('dashboard') ?>">
                <span class="icon">🏠</span>
                Mon espace
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Mes Outils</div>
            <a href="<?= base_url('stats') ?>"
                class="sidebar-link <?= navActive('stats') ?>">
                <span class="icon">📉</span>
                Mes statistiques
            </a>
            <a href="<?= base_url('export/bilan') ?>"
                class="sidebar-link <?= navActive('export/bilan') ?>">
                <span class="icon">📄</span>
                Exporter mon bilan
            </a>
            <a href="<?= base_url('services') ?>"
                class="sidebar-link <?= navActive('services') ?>">
                <span class="icon">✨</span>
                Services
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Abonnement</div>
            <a href="<?= base_url('abonnements') ?>"
                class="sidebar-link <?= navActive(['abonnements', 'abonnement']) ?>">
                <span class="icon">🎯</span>
                Mes abonnements
            </a>
            <a href="<?= base_url('code') ?>"
                class="sidebar-link <?= navActive('code') ?>">
                <span class="icon">🎟️</span>
                Codes bonus
            </a>
        </div>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-profile">
            <div class="sidebar-avatar"><?= strtoupper(substr(session()->get('user_nom') ?? 'A', 0, 1)) ?></div>
            <div class="sidebar-profile-info">
                <div class="sidebar-profile-name"><?= session()->get('user_nom') ?? 'Admin' ?></div>
                <div class="sidebar-profile-email"><?= session()->get('user_email') ?? '' ?></div>
            </div>
        </div>
        <a href="<?= base_url('logout') ?>" class="sidebar-logout">
            <span>🚪</span>
            Déconnexion
        </a>
    </div>
</aside>
