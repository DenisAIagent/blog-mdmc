<footer class="footer">
        <div class="container footer-container">
            <div class="footer-logo">
                <?php
                if ( has_custom_logo() ) {
                    echo '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
                    the_custom_logo();
                    echo '</a>';
                } else {
                    echo '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home"><img src="' . get_template_directory_uri() . '/assets/images/logo-mdmc.png" alt="' . get_bloginfo('name') . ' Logo Footer"></a>';
                }
                ?>
                <p><?php bloginfo('description'); ?></p>
                <div class="google-partner">
                    <a href="https://www.google.com/partners/agency?id=3215385696" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Voir le profil Google Partner de MDMC', 'mdmc-theme'); ?>">
                         <img src="https://www.gstatic.com/partners/badge/images/2024/PartnerBadgeClickable.svg" alt="<?php esc_attr_e( 'Badge Google Partner', 'mdmc-theme'); ?>" loading="lazy" onerror="this.src='https://www.gstatic.com/partners/badge/images/2025/PartnerBadgeClickable.svg';">
                     </a>
                </div>
            </div>
            <div class="footer-links">
                <div class="footer-column">
                    <h4><?php _e('Navigation', 'mdmc-theme'); ?></h4>
                     <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer_nav',
                            'container'      => false,
                            'menu_class'     => '',
                            'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                            'depth'          => 1
                        ) );
                    ?>
                </div>
                 <div class="footer-column">
                    <h4><?php _e('Ressources', 'mdmc-theme'); ?></h4>
                     <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer_resources',
                            'container'      => false,
                            'menu_class'     => '',
                            'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                             'depth'          => 1
                        ) );
                    ?>
                </div>
                 <div class="footer-column">
                    <h4><?php _e('Légal', 'mdmc-theme'); ?></h4>
                     <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer_legal',
                            'container'      => false,
                            'menu_class'     => '',
                            'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                             'depth'          => 1
                        ) );
                    ?>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
             <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('Tous droits réservés.', 'mdmc-theme'); ?></p>
        </div>
    </footer>

<?php wp_footer(); ?>
</body>
</html>