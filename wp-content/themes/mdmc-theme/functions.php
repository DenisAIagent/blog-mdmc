<?php
/**
 * MDMC Theme functions and definitions
 *
 * @package MDMC_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Define theme constants
define( 'MDMC_THEME_VERSION', '1.0.0' );
define( 'MDMC_THEME_DIR', get_template_directory() );
define( 'MDMC_THEME_URI', get_template_directory_uri() );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function mdmc_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'mdmc-featured', 1200, 675, true );
    add_image_size( 'mdmc-card', 600, 338, true );

    // Register navigation menus
    register_nav_menus(
        array(
            'primary' => esc_html__( 'Primary Menu', 'mdmc-theme' ),
            'footer-1'  => esc_html__( 'Footer Menu 1', 'mdmc-theme' ),
            'footer-2'  => esc_html__( 'Footer Menu 2', 'mdmc-theme' ),
        )
    );

    // Switch default core markup to output valid HTML5.
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );

    // Set up the WordPress core custom background feature.
    add_theme_support(
        'custom-background',
        apply_filters(
            'mdmc_custom_background_args',
            array(
                'default-color' => '0E0E0E',
                'default-image' => '',
            )
        )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add support for custom logo
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 60,
            'width'       => 200,
            'flex-width'  => true,
            'flex-height' => true,
        )
    );

    // Add support for editor styles
    add_theme_support( 'editor-styles' );

    // Add support for Block Editor features
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
}
add_action( 'after_setup_theme', 'mdmc_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
function mdmc_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'mdmc_content_width', 1200 );
}
add_action( 'after_setup_theme', 'mdmc_content_width', 0 );

/**
 * Register widget area.
 */
function mdmc_widgets_init() {
    register_sidebar(
        array(
            'name'          => esc_html__( 'Sidebar', 'mdmc-theme' ),
            'id'            => 'sidebar-1',
            'description'   => esc_html__( 'Add widgets here.', 'mdmc-theme' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    // Footer widget areas
    register_sidebar(
        array(
            'name'          => esc_html__( 'Footer About', 'mdmc-theme' ),
            'id'            => 'footer-about',
            'description'   => esc_html__( 'Add widgets to the footer about section.', 'mdmc-theme' ),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="footer-widget-title">',
            'after_title'   => '</h3>',
        )
    );
    
    register_sidebar(
        array(
            'name'          => esc_html__( 'Footer Navigation 1', 'mdmc-theme' ),
            'id'            => 'footer-nav-1',
            'description'   => esc_html__( 'Add widgets to the first footer navigation column.', 'mdmc-theme' ),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="footer-widget-title">',
            'after_title'   => '</h4>',
        )
    );
    
    register_sidebar(
        array(
            'name'          => esc_html__( 'Footer Navigation 2', 'mdmc-theme' ),
            'id'            => 'footer-nav-2',
            'description'   => esc_html__( 'Add widgets to the second footer navigation column.', 'mdmc-theme' ),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="footer-widget-title">',
            'after_title'   => '</h4>',
        )
    );
}
add_action( 'widgets_init', 'mdmc_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function mdmc_scripts() {
    // Enqueue Google Fonts
    wp_enqueue_style(
        'mdmc-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap',
        array(),
        MDMC_THEME_VERSION
    );

    // Enqueue main stylesheet
    wp_enqueue_style(
        'mdmc-style',
        get_stylesheet_uri(),
        array(),
        MDMC_THEME_VERSION
    );

    // Enqueue custom styles
    wp_enqueue_style(
        'mdmc-main-style',
        MDMC_THEME_URI . '/assets/css/main.css',
        array(),
        MDMC_THEME_VERSION
    );

    // Enqueue jQuery
    wp_enqueue_script( 'jquery' );

    // Load translations for JavaScript
    wp_enqueue_script(
        'mdmc-i18n',
        MDMC_THEME_URI . '/assets/js/i18n.js',
        array(),
        MDMC_THEME_VERSION,
        true
    );

    // Enqueue custom scripts
    wp_enqueue_script(
        'mdmc-main-script',
        MDMC_THEME_URI . '/assets/js/main.js',
        array( 'jquery', 'mdmc-i18n' ),
        MDMC_THEME_VERSION,
        true
    );

    // Localize script with translation data
    $translations_file = MDMC_THEME_DIR . '/assets/js/languages.json';
    $translations = array();
    
    if ( file_exists( $translations_file ) ) {
        $json_content = file_get_contents( $translations_file );
        $translations = json_decode( $json_content, true );
    }
    
    wp_localize_script(
        'mdmc-i18n',
        'i18n',
        $translations
    );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'mdmc_scripts' );

/**
 * Custom template tags for this theme.
 */
require MDMC_THEME_DIR . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require MDMC_THEME_DIR . '/inc/template-functions.php';

/**
 * Multilingual support functions.
 */
require MDMC_THEME_DIR . '/inc/multilingual.php';

/**
 * SEO optimization functions.
 */
require MDMC_THEME_DIR . '/inc/seo.php';

/**
 * Simulator functionality.
 */
require MDMC_THEME_DIR . '/inc/simulator.php';

/**
 * Customize excerpt length
 */
function mdmc_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'mdmc_excerpt_length' );

/**
 * Customize excerpt more
 */
function mdmc_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'mdmc_excerpt_more' );

/**
 * Add custom body classes
 */
function mdmc_body_classes( $classes ) {
    // Add a class for the current language
    $classes[] = 'lang-' . mdmc_get_current_language();
    
    return $classes;
}
add_filter( 'body_class', 'mdmc_body_classes' );

/**
 * Get current language
 */
function mdmc_get_current_language() {
    $default_lang = 'fr';
    
    // Check for cookie
    if ( isset( $_COOKIE['mdmc_language'] ) ) {
        return sanitize_text_field( $_COOKIE['mdmc_language'] );
    }
    
    // Check browser language
    if ( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
        $browser_lang = substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 );
        $supported_langs = array( 'fr', 'en', 'es', 'pt' );
        
        if ( in_array( $browser_lang, $supported_langs ) ) {
            return $browser_lang;
        }
    }
    
    return $default_lang;
}

/**
 * Get translation
 */
function mdmc_get_translation( $key, $lang = '' ) {
    if ( empty( $lang ) ) {
        $lang = mdmc_get_current_language();
    }
    
    // Get translations from JSON file
    $translations_file = MDMC_THEME_DIR . '/assets/js/languages.json';
    $translations = array();
    
    if ( file_exists( $translations_file ) ) {
        $json_content = file_get_contents( $translations_file );
        $translations = json_decode( $json_content, true );
    }
    
    if ( isset( $translations[$key][$lang] ) ) {
        return $translations[$key][$lang];
    }
    
    // Fallback to French
    if ( isset( $translations[$key]['fr'] ) ) {
        return $translations[$key]['fr'];
    }
    
    // Return key if no translation found
    return $key;
}

/**
 * Get custom logo URL
 */
function mdmc_get_custom_logo_url() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $logo_url = '';
    
    if ( $custom_logo_id ) {
        $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
    }
    
    return $logo_url ? $logo_url : MDMC_THEME_URI . '/assets/images/logo.svg';
}

/**
 * Display social sharing buttons
 */
function mdmc_social_sharing() {
    $share_url = urlencode(get_permalink());
    $share_title = urlencode(get_the_title());
    
    echo '<div class="social-sharing">';
    echo '<h4>' . esc_html__( 'Partager cet article', 'mdmc-theme' ) . '</h4>';
    echo '<div class="social-links">';
    echo '<a href="https://twitter.com/intent/tweet?text=' . $share_title . '&url=' . $share_url . '" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="' . esc_attr__( 'Partager sur Twitter', 'mdmc-theme' ) . '">';
    echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>';
    echo '</a>';
    echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $share_url . '" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="' . esc_attr__( 'Partager sur Facebook', 'mdmc-theme' ) . '">';
    echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>';
    echo '</a>';
    echo '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . $share_url . '&title=' . $share_title . '" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="' . esc_attr__( 'Partager sur LinkedIn', 'mdmc-theme' ) . '">';
    echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>';
    echo '</a>';
    echo '</div>';
    echo '</div>';
}

/**
 * Display related posts
 */
function mdmc_related_posts() {
    $current_post_id = get_the_ID();
    $categories = get_the_category();
    
    if ( $categories ) {
        $category_ids = array();
        foreach ( $categories as $category ) {
            $category_ids[] = $category->term_id;
        }
        
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 3,
            'post__not_in' => array( $current_post_id ),
            'category__in' => $category_ids,
            'orderby' => 'rand'
        );
        
        $related_query = new WP_Query( $args );
        
        if ( $related_query->have_posts() ) {
            echo '<div class="related-posts">';
            echo '<h3>' . esc_html__( 'Articles similaires', 'mdmc-theme' ) . '</h3>';
            echo '<div class="related-posts-grid">';
            
            while ( $related_query->have_posts() ) {
                $related_query->the_post();
                get_template_part( 'template-parts/content/content', 'card' );
            }
            
            echo '</div>';
            echo '</div>';
        }
        
        wp_reset_postdata();
    }
}
