<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <header class="header">
        <div class="container header-container">
            <div class="logo">
                <?php
                if ( has_custom_logo() ) {
                    the_custom_logo();
                } else {
                    echo '<h1><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . get_bloginfo( 'name' ) . '</a></h1>';
                }
                ?>
            </div>

            <nav class="nav-desktop" aria-label="<?php esc_attr_e( 'Navigation principale', 'mdmc-theme' ); ?>">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary_desktop',
                    'container'      => false,
                    'menu_class'     => '',
                    'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                ) );
                ?>
            </nav>

            <button class="hamburger-menu" type="button" aria-label="<?php esc_attr_e( 'Ouvrir le menu', 'mdmc-theme' ); ?>" aria-expanded="false" aria-controls="nav-mobile-list">
                 <span class="sr-only"><?php _e( 'Ouvrir le menu de navigation', 'mdmc-theme' ); ?></span>
                 <div class="bar"></div>
                 <div class="bar"></div>
                 <div class="bar"></div>
            </button>

        </div>

        <nav class="nav-mobile" id="nav-mobile-list" aria-hidden="true" aria-label="<?php esc_attr_e( 'Navigation mobile', 'mdmc-theme' ); ?>">
             <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary_mobile',
                    'container'      => false,
                    'menu_class'     => '',
                    'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                ) );
            ?>
        </nav>
    </header>