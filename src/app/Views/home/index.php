<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NutriPlan — Votre programme alimentaire personnalisé</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:ital,wght@0,400;0,500;0,600;1,400&family=Bebas+Neue&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
  <style>
    .hp-hero {
      background: linear-gradient(135deg, #2D6A4F 0%, #40916C 50%, #52B788 100%);
      padding: 80px 32px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    .hp-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .hp-hero-content { position: relative; z-index: 1; max-width: 800px; margin: 0 auto; }
    .hp-hero h1 {
      font-family: var(--font-heading);
      font-size: 48px;
      font-weight: 600;
      color: white;
      margin-bottom: 16px;
      line-height: 1.2;
    }
    .hp-hero p {
      font-size: 18px;
      color: rgba(255,255,255,0.85);
      margin-bottom: 32px;
      line-height: 1.6;
    }
    .hp-hero-btns { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
    .hp-hero-btns .btn { padding: 14px 32px; font-size: 16px; }

    .hp-section {
      padding: 64px 32px;
      max-width: 1200px;
      margin: 0 auto;
    }
    .hp-section-title {
      font-family: var(--font-heading);
      font-size: 32px;
      font-weight: 600;
      color: var(--color-text-primary);
      text-align: center;
      margin-bottom: 8px;
    }
    .hp-section-sub {
      font-size: 16px;
      color: var(--color-text-secondary);
      text-align: center;
      margin-bottom: 48px;
    }

    .hp-stats {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 24px;
      margin-bottom: 0;
    }
    .hp-stat-card {
      background: var(--color-surface);
      border-radius: 16px;
      padding: 32px 24px;
      text-align: center;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .hp-stat-value {
      font-family: 'Bebas Neue', Impact, sans-serif;
      font-size: 52px;
      color: var(--color-primary);
      line-height: 1;
      margin-bottom: 4px;
    }
    .hp-stat-label {
      font-size: 15px;
      color: var(--color-text-secondary);
      font-weight: 500;
    }

    .hp-regimes-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
      gap: 24px;
    }
    .hp-regime-card {
      background: var(--color-surface);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      transition: all 250ms ease;
    }
    .hp-regime-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 32px rgba(0,0,0,0.1);
    }
    .hp-regime-head {
      padding: 24px 24px 16px;
      border-bottom: 1px solid var(--color-border);
    }
    .hp-regime-head h3 {
      font-size: 20px;
      font-weight: 600;
      color: var(--color-text-primary);
      margin-bottom: 4px;
    }
    .hp-regime-head p {
      font-size: 14px;
      color: var(--color-text-secondary);
      line-height: 1.5;
    }
    .hp-regime-body { padding: 16px 24px 24px; }
    .hp-regime-compo {
      display: flex;
      gap: 6px;
      margin-bottom: 16px;
    }
    .hp-regime-compo span {
      flex: 1;
      text-align: center;
      padding: 6px 8px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
    }
    .hp-regime-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 12px;
      border-top: 1px solid var(--color-border);
    }
    .hp-regime-price {
      font-size: 24px;
      font-weight: 700;
      color: var(--color-primary);
    }
    .hp-regime-price small {
      font-size: 14px;
      font-weight: 400;
      color: var(--color-text-muted);
    }

    .hp-activites {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap: 16px;
    }
    .hp-activite-item {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 20px;
      background: var(--color-surface);
      border-radius: 12px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.04);
      transition: all 200ms ease;
    }
    .hp-activite-item:hover {
      background: #EFF8F3;
      transform: translateX(4px);
    }
    .hp-activite-icon {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      flex-shrink: 0;
    }
    .hp-activite-icon.faible { background: rgba(82,183,136,0.15); }
    .hp-activite-icon.moyen { background: rgba(212,168,83,0.15); }
    .hp-activite-icon.intense { background: rgba(193,57,43,0.12); }
    .hp-activite-info h4 { font-size: 15px; font-weight: 600; color: var(--color-text-primary); }
    .hp-activite-info p { font-size: 13px; color: var(--color-text-muted); }

    .hp-testimonials {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 24px;
    }
    .hp-testimonial {
      background: var(--color-surface);
      border-radius: 16px;
      padding: 32px 24px;
      position: relative;
    }
    .hp-testimonial::before {
      content: '"';
      font-family: var(--font-heading);
      font-size: 64px;
      color: var(--color-primary-light);
      opacity: 0.3;
      position: absolute;
      top: 12px;
      left: 20px;
      line-height: 1;
    }
    .hp-testimonial-text {
      font-size: 15px;
      color: var(--color-text-secondary);
      line-height: 1.6;
      margin-bottom: 16px;
      font-style: italic;
    }
    .hp-testimonial-author {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .hp-testimonial-avatar { font-size: 32px; }
    .hp-testimonial-name { font-size: 14px; font-weight: 600; color: var(--color-text-primary); }
    .hp-testimonial-goal { font-size: 12px; color: var(--color-text-muted); }

    .hp-cta {
      background: var(--color-bg);
      text-align: center;
      padding: 80px 32px;
    }
    .hp-cta h2 {
      font-family: var(--font-heading);
      font-size: 36px;
      color: var(--color-text-primary);
      margin-bottom: 12px;
    }
    .hp-cta p {
      font-size: 16px;
      color: var(--color-text-secondary);
      margin-bottom: 32px;
    }

    .hp-nav {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 100;
      background: rgba(255,255,255,0.95);
      backdrop-filter: blur(8px);
      border-bottom: 1px solid var(--color-border);
      padding: 0 32px;
      height: 64px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .hp-nav-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-family: var(--font-heading);
      font-size: 20px;
      font-weight: 600;
      color: var(--color-primary);
    }
    .hp-nav-links { display: flex; align-items: center; gap: 12px; }
    .hp-body { padding-top: 64px; }

    .hp-btn-outline {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 10px 24px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 500;
      font-family: var(--font-body);
      border: 1.5px solid var(--color-primary);
      color: var(--color-primary);
      background: transparent;
      cursor: pointer;
      transition: all 200ms ease;
      text-decoration: none;
    }
    .hp-btn-outline:hover {
      background: rgba(45,106,79,0.08);
    }
    .hp-btn-white {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 14px 32px;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 600;
      font-family: var(--font-body);
      background: white;
      color: var(--color-primary);
      border: none;
      cursor: pointer;
      transition: all 200ms ease;
      text-decoration: none;
    }
    .hp-btn-white:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }

    footer {
      background: var(--color-text-primary);
      color: rgba(255,255,255,0.6);
      text-align: center;
      padding: 32px;
      font-size: 14px;
    }

    @media (max-width: 768px) {
      .hp-hero h1 { font-size: 32px; }
      .hp-hero { padding: 48px 20px; }
      .hp-section { padding: 40px 20px; }
      .hp-stats { grid-template-columns: 1fr; }
      .hp-testimonials { grid-template-columns: 1fr; }
      .hp-nav { padding: 0 16px; }
      .hp-regimes-grid { grid-template-columns: 1fr; }
      .hp-activites { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

<nav class="hp-nav">
  <a href="<?= base_url('/') ?>" class="hp-nav-logo">
    <svg width="28" height="28" viewBox="0 0 32 32" fill="none">
      <path d="M16 2C8.268 2 2 8.268 2 16s6.268 14 14 14 14-6.268 14-14S23.732 2 16 2z" fill="#2D6A4F"/>
      <path d="M16 6c-1.5 3-4.5 5-7 7 2 2.5 4 5.5 5 9 2.5-1.5 5-4 7-7-2.5-2-4.5-5-5-9z" fill="#D4A853" opacity="0.8"/>
    </svg>
    NutriPlan
  </a>
  <div class="hp-nav-links">
    <a href="<?= base_url('/abonnements') ?>" class="hp-btn-outline" style="border:none;color:var(--color-text-secondary);">Abonnements</a>
    <a href="<?= base_url('/code') ?>" class="hp-btn-outline" style="border:none;color:var(--color-text-secondary);">Code Cadeau</a>

    <a href="<?= base_url('/') ?>#regimes" class="hp-btn-outline" style="border:none;color:var(--color-text-secondary);">Régimes</a>
    <a href="<?= base_url('/') ?>#activites" class="hp-btn-outline" style="border:none;color:var(--color-text-secondary);">Activités</a>
    <?php if (session()->get('logged_in')): ?>
    <a href="<?= base_url('dashboard') ?>" class="btn btn-primary" style="padding:8px 20px;font-size:14px;">Mon espace</a>
    <?php else: ?>
    <a href="<?= base_url('connexion') ?>" class="hp-btn-outline">Connexion</a>
    <a href="<?= base_url('register') ?>" class="btn btn-primary" style="padding:8px 20px;font-size:14px;">S'inscrire</a>
    <?php endif; ?>
  </div>
</nav>

<div class="hp-body">

  <section class="hp-hero">
    <div class="hp-hero-content">
      <h1>Transformez votre alimentation,<br>transformez votre vie</h1>
      <p>Des régimes personnalisés adaptés à vos objectifs, un suivi de progression en temps réel, et des activités recommandées pour atteindre votre poids de forme.</p>
      <div class="hp-hero-btns">
        <?php if (session()->get('logged_in')): ?>
        <a href="<?= base_url('dashboard') ?>" class="hp-btn-white">Mon tableau de bord →</a>
        <?php else: ?>
        <a href="<?= base_url('register') ?>" class="hp-btn-white">Commencer maintenant →</a>
        <a href="<?= base_url('connexion') ?>" class="hp-btn-outline" style="border-color:rgba(255,255,255,0.4);color:white;">J'ai déjà un compte</a>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="hp-section">
    <div class="hp-stats">
      <div class="hp-stat-card">
        <div class="hp-stat-value"><?= $stats['utilisateurs'] ?></div>
        <div class="hp-stat-label">Utilisateurs inscrits</div>
      </div>
      <div class="hp-stat-card">
        <div class="hp-stat-value"><?= $stats['regimes'] ?></div>
        <div class="hp-stat-label">Régimes disponibles</div>
      </div>
      <div class="hp-stat-card">
        <div class="hp-stat-value"><?= $stats['activites'] ?></div>
        <div class="hp-stat-label">Activités sportives</div>
      </div>
    </div>
  </section>

  <section class="hp-section" id="regimes">
    <h2 class="hp-section-title">Nos régimes alimentaires</h2>
    <p class="hp-section-sub">Des programmes adaptés à chaque objectif, avec une composition équilibrée</p>
    <div class="hp-regimes-grid">
      <?php if (empty($regimes)): ?>
      <div style="grid-column:1/-1;text-align:center;padding:48px;color:var(--color-text-muted);">
        <div style="font-size:48px;margin-bottom:12px;">🥗</div>
        <p>Nos régimes arrivent bientôt !</p>
      </div>
      <?php else: ?>
      <?php foreach ($regimes as $r): ?>
      <div class="hp-regime-card">
        <div class="hp-regime-head">
          <h3><?= esc($r['nom']) ?></h3>
          <p><?= esc($r['description'] ?: '') ?></p>
        </div>
        <div class="hp-regime-body">
          <div class="hp-regime-compo">
            <span style="background:#FEF3C7;color:#92400E;">🥩 <?= $r['pct_viande'] ?>%</span>
            <span style="background:#DBEAFE;color:#1E40AF;">🐟 <?= $r['pct_poisson'] ?>%</span>
            <span style="background:#D1FAE5;color:#065F46;">🍗 <?= $r['pct_volaille'] ?>%</span>
          </div>
          <div style="display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap;">
            <span class="pill pill-info">Variation: <?= $r['variation'] ?> kg</span>
            <?php if ($r['duree']): ?>
            <span class="pill pill-success"><?= $r['duree'] ?> jours</span>
            <?php endif; ?>
          </div>
          <?php if (!empty($r['prix_options'])): ?>
          <div class="hp-regime-meta">
            <div class="hp-regime-price">
              <?= $r['prix_options'][0]['prix'] ?>€
              <small>/ <?= $r['prix_options'][0]['duree'] ?>j</small>
            </div>
            <a href="<?= base_url('regime/' . $r['id']) ?>" class="btn btn-primary btn-sm">Voir détails</a>
          </div>
          <?php else: ?>
          <div class="hp-regime-meta">
            <div class="hp-regime-price">Nous contacter</div>
            <a href="<?= base_url('regime/' . $r['id']) ?>" class="btn btn-primary btn-sm">Voir détails</a>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="hp-section" id="activites" style="background:var(--color-bg);border-radius:24px;margin:0 32px 64px;">
    <h2 class="hp-section-title">Activités sportives</h2>
    <p class="hp-section-sub">Des exercices adaptés à votre régime pour des résultats optimaux</p>
    <div class="hp-activites">
      <?php if (empty($activites)): ?>
      <div style="grid-column:1/-1;text-align:center;padding:48px;color:var(--color-text-muted);">
        <p>Les activités arrivent bientôt !</p>
      </div>
      <?php else: ?>
      <?php foreach ($activites as $a): ?>
      <?php
        $icone = '🏃';
        if ($a['intensite'] == 1) $icone = '🚶';
        elseif ($a['intensite'] == 3) $icone = '🔥';
        $intensite_label = $a['intensite'] == 1 ? 'Faible' : ($a['intensite'] == 2 ? 'Modéré' : 'Intense');
        $intensite_class = $a['intensite'] == 1 ? 'faible' : ($a['intensite'] == 2 ? 'moyen' : 'intense');
      ?>
      <div class="hp-activite-item">
        <div class="hp-activite-icon <?= $intensite_class ?>"><?= $icone ?></div>
        <div class="hp-activite-info">
          <h4><?= esc($a['nom']) ?></h4>
          <p><?= $a['calories_heure'] ?> kcal/h · <?= $intensite_label ?></p>
        </div>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="hp-section">
    <h2 class="hp-section-title">Ils nous font confiance</h2>
    <p class="hp-section-sub">Découvrez les résultats de nos utilisateurs</p>
    <div class="hp-testimonials">
      <?php foreach ($testimonials as $t): ?>
      <div class="hp-testimonial">
        <p class="hp-testimonial-text"><?= $t['text'] ?></p>
        <div class="hp-testimonial-author">
          <span class="hp-testimonial-avatar"><?= $t['avatar'] ?></span>
          <div>
            <div class="hp-testimonial-name"><?= $t['name'] ?></div>
            <div class="hp-testimonial-goal"><?= $t['goal'] ?></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="hp-cta">
    <h2>Prêt à transformer votre alimentation ?</h2>
    <p>Rejoignez NutriPlan et commencez votre programme personnalisé dès aujourd'hui.</p>
    <?php if (session()->get('logged_in')): ?>
    <a href="<?= base_url('dashboard') ?>" class="btn btn-primary" style="padding:16px 40px;font-size:18px;">Accéder à mon espace →</a>
    <?php else: ?>
    <a href="<?= base_url('register') ?>" class="btn btn-primary" style="padding:16px 40px;font-size:18px;">Créer mon compte gratuit →</a>
    <?php endif; ?>
  </section>
</div>

<footer class="app-footer" style="margin-top:0;">
  <div class="footer-students">
    <span>ETU004235 Toavina</span>
    <span>ETU003936 Aiky</span>
    <span>ETU004372 Célina</span>
  </div>
</footer>
</body>
</html>
