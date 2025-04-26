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
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'mdmc-theme' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="container header-container">
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) :
					the_custom_logo();
				else :
				?>
					<svg width="40" height="40" viewBox="0 0 40 40">
						<rect width="40" height="40" fill="#0E0E0E"/>
						<path d="M10 10L20 30L30 10" stroke="#C43D28" stroke-width="3" fill="none"/>
						<circle cx="20" cy="20" r="8" fill="#0E0E0E" stroke="#C43D28" stroke-width="2"/>
						<circle cx="20" cy="20" r="3" fill="#C43D28"/>
					</svg>
				<?php endif; ?>
				
				<div class="site-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						MDMC <span class="site-title-highlight">Music Ads</span>
					</a>
				</div>
			</div><!-- .site-branding -->

			<nav id="site-navigation" class="main-navigation">
				<button class="mobile-menu-toggle" aria-controls="primary-menu" aria-expanded="false">☰</button>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'menu_class'     => 'main-menu',
						'container'      => false,
					)
				);
				?>
				<a href="#simulator" class="cta-button"><?php echo mdmc_get_translation('simulator_button'); ?></a>
			</nav><!-- #site-navigation -->

			<div class="language-selector">
				<button class="language-toggle" aria-expanded="false">
					<?php echo esc_html( strtoupper( mdmc_get_current_language() ) ); ?>
					<svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>
				<ul class="language-dropdown">
					<li><a href="#" data-lang="fr" <?php echo mdmc_get_current_language() === 'fr' ? 'class="active"' : ''; ?>>Français</a></li>
					<li><a href="#" data-lang="en" <?php echo mdmc_get_current_language() === 'en' ? 'class="active"' : ''; ?>>English</a></li>
					<li><a href="#" data-lang="es" <?php echo mdmc_get_current_language() === 'es' ? 'class="active"' : ''; ?>>Español</a></li>
					<li><a href="#" data-lang="pt" <?php echo mdmc_get_current_language() === 'pt' ? 'class="active"' : ''; ?>>Português</a></li>
				</ul>
			</div>
		</div>
	</header><!-- #masthead -->
