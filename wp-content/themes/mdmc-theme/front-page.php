<?php get_header(); ?>

<main id="main-content">

    <section class="hero" id="hero">
        <div class="container hero-container">
            <div class="hero-content">
                <h1>Titre modifiable via Customizer ou ACF</h1>
                <p class="hero-slogan fade-in delay-2"><?php echo esc_html( get_theme_mod( 'mdmc_hero_slogan', 'Push. Play. Blow up.' ) ); ?></p>
                <p class="fade-in delay-3">Sous-titre modifiable</p>
                <div class="cta-container fade-in delay-4">
                    <button id="simulateur-btn" class="btn btn-primary">Simulez votre campagne</button>
                    <a href="#contact" class="btn btn-secondary">Contactez-nous</a>
                </div>
            </div>
             <div class="hero-stats fade-in delay-4">
                  <div class="stat-item">
                       <span class="stat-number" data-target="500" data-suffix="+">0</span>
                       <span class="stat-label">Artistes accompagnés</span>
                  </div>
                  <div class="stat-item">
                       <span class="stat-number" data-target="34000000">0</span>
                       <span class="stat-label">Vues générées</span>
                  </div>
                  <div class="stat-item">
                       <span class="stat-number" data-target="5.8" data-suffix="%">0,0%</span>
                       <span class="stat-label">Taux d'engagement</span>
                  </div>
             </div>
        </div>
    </section>

    <section id="services" class="services">
        <div class="container">
            <h2 class="section-title">Nos Services</h2>
            <div class="services-grid">
                 <div class="service-card fade-in">
                      <div class="service-icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/youtube-ads-icon.png" alt="Icône Campagnes Publicitaires" loading="lazy"></div>
                      <h3>Campagnes Publicitaires</h3>
                      <p>Stratégies publicitaires optimisées...</p>
                 </div>
                 <div class="service-card fade-in delay-1">
                      <div class="service-icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/strategy-icon.png" alt="Icône Stratégie de contenu" loading="lazy"></div>
                      <h3>Stratégie de Contenu</h3>
                      <p>Accompagnement personnalisé...</p>
                 </div>
                 <div class="service-card fade-in delay-2">
                      <div class="service-icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/analytics-icon.png" alt="Icône Analytics & Optimisation" loading="lazy"></div>
                      <h3>Analytics & Optimisation</h3>
                      <p>Suivi détaillé des performances...</p>
                 </div>
            </div>
        </div>
    </section>

    <?php // Intégrer les autres sections (About, Articles récents, Contact) de manière similaire ?>
    <?php // Pour "Articles récents", vous pouvez utiliser une WP_Query personnalisée ici ?>

</main>

<?php get_footer(); ?>