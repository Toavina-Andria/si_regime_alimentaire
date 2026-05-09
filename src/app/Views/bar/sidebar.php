<aside class="sidebar">
    <div class="sidebar-logo">
        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
            <path d="M16 2C8.268 2 2 8.268 2 16s6.268 14 14 14 14-6.268 14-14S23.732 2 16 2z" fill="#2D6A4F" />
            <path d="M11 20c3 1 6 2 8 4 2-2 5-3 8-4" stroke="#52B788" stroke-width="1.5" stroke-linecap="round" />
        </svg>
        <div class="sidebar-logo-text">
        </div>
    </div>
    <nav class="sidebar-nav">
            <div class="sidebar-section">
                <div class="sidebar-section-title">Tableau de bord</div>
                </a>
                </a>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title">Gestion</div>
                </a>
                </a>
                </a>
                </a>
            </a>
        </div>

        <div class="sidebar-section">
            </a>
        </div>
        <div class="sidebar-section">
            </a>
            </a>
            </a>
        </div>
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
        </a>
    </div>
</aside>
