<?php
/**
 * The header for our theme
 *
 * @package MDMC_Theme
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php echo esc_html(mdmc_get_translation('skip_to_content')); ?></a>

    <header id="masthead" class="site-header">
        <div class="container">
            <div class="site-header-inner">
                <div class="site-branding">
                    <?php
                    if (has_custom_logo()) :
                        the_custom_logo();
                    else :
                    ?>
                        <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                    <?php endif; ?>
                </div><!-- .site-branding -->

                <nav id="site-navigation" class="main-navigation">
                    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                        <span class="menu-toggle-icon"></span>
                        <span class="screen-reader-text"><?php echo esc_html(mdmc_get_translation('menu')); ?></span>
                    </button>
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                        )
                    );
                    ?>
                </nav><!-- #site-navigation -->

                <div class="header-actions">
                    <div class="language-selector">
                        <button class="language-toggle" aria-expanded="false">
                            <span class="current-language"><?php echo strtoupper(mdmc_get_current_language()); ?></span>
                            <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <ul class="language-dropdown">
                            <li><a href="#" data-lang="fr" <?php echo mdmc_get_current_language() === 'fr' ? 'class="active"' : ''; ?>>FR 🇫🇷</a></li>
                            <li><a href="#" data-lang="en" <?php echo mdmc_get_current_language() === 'en' ? 'class="active"' : ''; ?>>EN 🇬🇧</a></li>
                            <li><a href="#" data-lang="es" <?php echo mdmc_get_current_language() === 'es' ? 'class="active"' : ''; ?>>ES 🇪🇸</a></li>
                            <li><a href="#" data-lang="pt" <?php echo mdmc_get_current_language() === 'pt' ? 'class="active"' : ''; ?>>PT 🇵🇹</a></li>
                        </ul>
                    </div>

                    <a href="<?php echo esc_url(home_url('/simulateur/')); ?>" class="btn btn-primary cta-button">
                        <?php echo esc_html(mdmc_get_translation('simulator_button')); ?>
                    </a>
                </div><!-- .header-actions -->
            </div><!-- .site-header-inner -->
        </div><!-- .container -->
    </header><!-- #masthead -->
