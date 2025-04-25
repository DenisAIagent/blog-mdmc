<?php
/**
 * MDMC Music Ads Blog Theme Functions
 *
 * @package MDMC_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Define Constants
 */
define('MDMC_THEME_VERSION', '1.1.0');
define('MDMC_THEME_DIR', get_template_directory());
define('MDMC_THEME_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
function mdmc_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ));
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Menu Principal', 'mdmc-theme'),
        'footer-1' => esc_html__('Footer Navigation', 'mdmc-theme'),
        'footer-2' => esc_html__('Footer Ressources', 'mdmc-theme'),
        'footer-3' => esc_html__('Footer Mentions Légales', 'mdmc-theme'),
    ));
    
    // Register image sizes
    add_image_size('mdmc-featured', 1200, 675, true); // 16:9 aspect ratio
    add_image_size('mdmc-card', 600, 338, true); // 16:9 aspect ratio
    
    // Load theme textdomain
    load_theme_textdomain('mdmc-theme', MDMC_THEME_DIR . '/languages');
}
add_action('after_setup_theme', 'mdmc_theme_setup');

/**
 * Enqueue scripts and styles
 */
function mdmc_theme_scripts() {
    // Enqueue Google Fonts
    wp_enqueue_style('mdmc-google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap', array(), null);
    
    // Enqueue main stylesheet
    wp_enqueue_style('mdmc-style', get_stylesheet_uri(), array(), MDMC_THEME_VERSION);
    
    // Enqueue custom CSS
    wp_enqueue_style('mdmc-main-style', MDMC_THEME_URI . '/assets/css/main.css', array(), MDMC_THEME_VERSION);
    
    // Enqueue custom JS
    wp_enqueue_script('mdmc-main-js', MDMC_THEME_URI . '/assets/js/main.js', array('jquery'), MDMC_THEME_VERSION, true);
    
    // Enqueue i18n script
    wp_enqueue_script('mdmc-i18n-js', MDMC_THEME_URI . '/assets/js/i18n.js', array('jquery'), MDMC_THEME_VERSION, true);
    
    // Get current post metadata for translations
    $page_data = array();
    if (is_singular()) {
        global $post;
        $page_data = array(
            'meta_description' => get_post_meta($post->ID, '_mdmc_meta_description', true),
            'meta_keywords' => get_post_meta($post->ID, '_mdmc_meta_keywords', true)
        );
    }
    
    // Localize script for translations
    wp_localize_script('mdmc-i18n-js', 'mdmcI18n', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'languages' => array('fr', 'en', 'es', 'pt'),
        'current_lang' => mdmc_get_current_language(),
        'pageData' => $page_data
    ));
    
    // Add theme URI for loading assets
    wp_localize_script('mdmc-i18n-js', 'mdmcThemeUri', MDMC_THEME_URI);
}
add_action('wp_enqueue_scripts', 'mdmc_theme_scripts');

/**
 * Register widget areas
 */
function mdmc_theme_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'mdmc-theme'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Ajouter des widgets ici.', 'mdmc-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'mdmc_theme_widgets_init');

/**
 * Custom template tags
 */
require MDMC_THEME_DIR . '/inc/template-tags.php';

/**
 * Multilingual support functions
 */

/**
 * Get current language
 * 
 * @return string Current language code (fr, en, es, pt)
 */
function mdmc_get_current_language() {
    // Check if language is set in cookie
    if (isset($_COOKIE['mdmc_language'])) {
        $lang = sanitize_text_field($_COOKIE['mdmc_language']);
        if (in_array($lang, array('fr', 'en', 'es', 'pt'))) {
            return $lang;
        }
    }
    
    // Check browser language
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        if (in_array($browser_lang, array('fr', 'en', 'es', 'pt'))) {
            return $browser_lang;
        }
    }
    
    // Default to French
    return 'fr';
}

/**
 * Set language cookie via AJAX
 */
function mdmc_set_language() {
    if (isset($_POST['lang']) && in_array($_POST['lang'], array('fr', 'en', 'es', 'pt'))) {
        $lang = sanitize_text_field($_POST['lang']);
        setcookie('mdmc_language', $lang, time() + (86400 * 30), '/'); // 30 days
        wp_send_json_success();
    }
    wp_send_json_error();
}
add_action('wp_ajax_mdmc_set_language', 'mdmc_set_language');
add_action('wp_ajax_nopriv_mdmc_set_language', 'mdmc_set_language');

/**
 * Add language meta tags for SEO
 */
function mdmc_language_meta_tags() {
    $current_lang = mdmc_get_current_language();
    $languages = array('fr', 'en', 'es', 'pt');
    
    // Current language
    echo '<meta property="og:locale" content="' . esc_attr(mdmc_get_locale_from_code($current_lang)) . '" />' . "\n";
    
    // Alternate languages
    foreach ($languages as $lang) {
        if ($lang !== $current_lang) {
            echo '<link rel="alternate" hreflang="' . esc_attr($lang) . '" href="' . esc_url(add_query_arg('lang', $lang, get_permalink())) . '" />' . "\n";
            echo '<meta property="og:locale:alternate" content="' . esc_attr(mdmc_get_locale_from_code($lang)) . '" />' . "\n";
        }
    }
}
add_action('wp_head', 'mdmc_language_meta_tags');

/**
 * Get locale from language code
 */
function mdmc_get_locale_from_code($code) {
    $locales = array(
        'fr' => 'fr_FR',
        'en' => 'en_US',
        'es' => 'es_ES',
        'pt' => 'pt_PT',
    );
    
    return isset($locales[$code]) ? $locales[$code] : 'fr_FR';
}

/**
 * Add custom meta boxes for SEO
 */
function mdmc_add_meta_boxes() {
    $post_types = array('post', 'page');
    
    foreach ($post_types as $post_type) {
        add_meta_box(
            'mdmc_seo_meta_box',
            __('MDMC SEO Settings', 'mdmc-theme'),
            'mdmc_seo_meta_box_callback',
            $post_type,
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'mdmc_add_meta_boxes');

/**
 * SEO Meta Box Callback
 */
function mdmc_seo_meta_box_callback($post) {
    wp_nonce_field('mdmc_seo_meta_box', 'mdmc_seo_meta_box_nonce');
    
    $languages = array('fr', 'en', 'es', 'pt');
    $meta_description = get_post_meta($post->ID, '_mdmc_meta_description', true);
    $meta_keywords = get_post_meta($post->ID, '_mdmc_meta_keywords', true);
    
    echo '<p><strong>' . __('Meta Description', 'mdmc-theme') . '</strong></p>';
    
    foreach ($languages as $lang) {
        $lang_meta_desc = isset($meta_description[$lang]) ? $meta_description[$lang] : '';
        echo '<p>';
        echo '<label for="mdmc_meta_description_' . esc_attr($lang) . '">' . strtoupper(esc_html($lang)) . ':</label><br>';
        echo '<textarea id="mdmc_meta_description_' . esc_attr($lang) . '" name="mdmc_meta_description[' . esc_attr($lang) . ']" rows="2" style="width:100%;">' . esc_textarea($lang_meta_desc) . '</textarea>';
        echo '</p>';
    }
    
    echo '<p><strong>' . __('Meta Keywords', 'mdmc-theme') . '</strong></p>';
    
    foreach ($languages as $lang) {
        $lang_meta_keywords = isset($meta_keywords[$lang]) ? $meta_keywords[$lang] : '';
        echo '<p>';
        echo '<label for="mdmc_meta_keywords_' . esc_attr($lang) . '">' . strtoupper(esc_html($lang)) . ':</label><br>';
        echo '<input type="text" id="mdmc_meta_keywords_' . esc_attr($lang) . '" name="mdmc_meta_keywords[' . esc_attr($lang) . ']" value="' . esc_attr($lang_meta_keywords) . '" style="width:100%;" placeholder="' . esc_attr__('Séparez les mots-clés par des virgules', 'mdmc-theme') . '">';
        echo '</p>';
    }
    
    echo '<p class="description">' . __('Note: Si vous remplissez uniquement la version française, les autres langues seront automatiquement traduites par le système i18n.js.', 'mdmc-theme') . '</p>';
}

/**
 * Save SEO meta box data
 */
function mdmc_save_meta_box_data($post_id) {
    if (!isset($_POST['mdmc_seo_meta_box_nonce'])) {
        return;
    }
    
    if (!wp_verify_nonce($_POST['mdmc_seo_meta_box_nonce'], 'mdmc_seo_meta_box')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (isset($_POST['post_type']) && ('page' === $_POST['post_type'] || 'post' === $_POST['post_type'])) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }
    
    if (isset($_POST['mdmc_meta_description'])) {
        $meta_description = array_map('sanitize_textarea_field', $_POST['mdmc_meta_description']);
        update_post_meta($post_id, '_mdmc_meta_description', $meta_description);
    }
    
    if (isset($_POST['mdmc_meta_keywords'])) {
        $meta_keywords = array_map('sanitize_text_field', $_POST['mdmc_meta_keywords']);
        update_post_meta($post_id, '_mdmc_meta_keywords', $meta_keywords);
    }
}
add_action('save_post', 'mdmc_save_meta_box_data');

/**
 * Output meta tags in head
 */
function mdmc_output_meta_tags() {
    global $post;
    
    if (!is_singular()) {
        return;
    }
    
    $current_lang = mdmc_get_current_language();
    $meta_description = get_post_meta($post->ID, '_mdmc_meta_description', true);
    $meta_keywords = get_post_meta($post->ID, '_mdmc_meta_keywords', true);
    
    if (!empty($meta_description) && isset($meta_description[$current_lang])) {
        echo '<meta name="description" content="' . esc_attr($meta_description[$current_lang]) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($meta_description[$current_lang]) . '" />' . "\n";
    }
    
    if (!empty($meta_keywords) && isset($meta_keywords[$current_lang])) {
        echo '<meta name="keywords" content="' . esc_attr($meta_keywords[$current_lang]) . '" />' . "\n";
    }
}
add_action('wp_head', 'mdmc_output_meta_tags');

/**
 * Add structured data for articles
 */
function mdmc_add_structured_data() {
    if (!is_singular('post')) {
        return;
    }
    
    global $post;
    $current_lang = mdmc_get_current_language();
    $meta_description = get_post_meta($post->ID, '_mdmc_meta_description', true);
    
    $description = isset($meta_description[$current_lang]) ? $meta_description[$current_lang] : get_the_excerpt($post->ID);
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => get_the_title(),
        'description' => $description,
        'author' => array(
            '@type' => 'Person',
            'name' => get_the_author_meta('display_name', $post->post_author)
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'logo' => array(
                '@type' => 'ImageObject',
                'url' => get_custom_logo_url()
            )
        ),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'mainEntityOfPage' => get_permalink(),
    );
    
    if (has_post_thumbnail()) {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
        if ($image) {
            $schema['image'] = array(
                '@type' => 'ImageObject',
                'url' => $image[0],
                'width' => $image[1],
                'height' => $image[2]
            );
        }
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
}
add_action('wp_head', 'mdmc_add_structured_data');

/**
 * Get custom logo URL
 */
function get_custom_logo_url() {
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
    
    return $logo[0];
}

/**
 * Create template-tags.php file
 */
function mdmc_create_template_tags_file() {
    $template_tags_dir = MDMC_THEME_DIR . '/inc';
    
    if (!file_exists($template_tags_dir)) {
        wp_mkdir_p($template_tags_dir);
    }
    
    $template_tags_file = $template_tags_dir . '/template-tags.php';
    
    if (!file_exists($template_tags_file)) {
        $template_tags_content = '<?php
/**
 * Custom template tags for this theme
 *
 * @package MDMC_Theme
 */

if (!defined(\'ABSPATH\')) {
    exit; // Exit if accessed directly
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function mdmc_posted_on() {
    $time_string = \'<time class="entry-date published updated" datetime="%1$s">%2$s</time>\';
    
    $time_string = sprintf(
        $time_string,
        esc_attr(get_the_date(\'c\')),
        esc_html(get_the_date())
    );

    $posted_on = sprintf(
        \'%s\',
        \'<a href="\' . esc_url(get_permalink()) . \'" rel="bookmark">\' . $time_string . \'</a>\'
    );

    $byline = sprintf(
        \'%s\',
        \'<span class="author vcard"><a class="url fn n" href="\' . esc_url(get_author_posts_url(get_the_author_meta(\'ID\'))) . \'">\' . esc_html(get_the_author()) . \'</a></span>\'
    );

    echo \'<span class="posted-on">\' . $posted_on . \'</span><span class="byline"> \' . $byline . \'</span>\'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function mdmc_entry_footer() {
    // Hide category and tag text for pages.
    if (\'post\' === get_post_type()) {
        /* translators: used between list items, there is a space after the comma */
        $categories_list = get_the_category_list(\', \');
        if ($categories_list) {
            /* translators: 1: list of categories. */
            printf(\'<span class="cat-links">%1$s</span>\', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list(\'\', \', \');
        if ($tags_list) {
            /* translators: 1: list of tags. */
            printf(\'<span class="tags-links">%1$s</span>\', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }
}

/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function mdmc_post_thumbnail() {
    if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
        return;
    }

    if (is_singular()) :
        ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail(\'mdmc-featured\', array(\'class\' => \'featured-image\')); ?>
        </div><!-- .post-thumbnail -->
    <?php else : ?>
        <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
            <?php
            the_post_thumbnail(
                \'mdmc-card\',
                array(
                    \'alt\' => the_title_attribute(
                        array(
                            \'echo\' => false,
                        )
                    ),
                    \'class\' => \'card-image\',
                )
            );
            ?>
        </a>
    <?php
    endif; // End is_singular().
}

/**
 * Get translated text
 * 
 * @param string $key Text key
 * @param string $lang Language code (optional)
 * @return string Translated text
 */
function mdmc_get_translation($key, $lang = \'\') {
    if (empty($lang)) {
        $lang = mdmc_get_current_language();
    }
    
    // Get translations from JSON file
    $translations_file = MDMC_THEME_DIR . \'/assets/js/languages.json\';
    $translations = array();
    
    if (file_exists($translations_file)) {
        $json_content = file_get_contents($translations_file);
        $translations = json_decode($json_content, true);
    }
    
    if (isset($translations[$key][$lang])) {
        return $translations[$key][$lang];
    }
    
    // Fallback to French
    if (isset($translations[$key][\'fr\'])) {
        return $translations[$key][\'fr\'];
    }
    
    // Return key if no translation found
    return $key;
}

/**
 * Display category badge
 */
function mdmc_category_badge() {
    $categories = get_the_category();
    if (!empty($categories)) {
        echo \'<span class="category-badge">\' . esc_html($categories[0]->name) . \'</span>\';
    }
}

/**
 * Display social sharing buttons
 */
function mdmc_social_sharing() {
    $share_url = urlencode(get_permalink());
    $share_title = urlencode(get_the_title());
    
    echo \'<div class="social-sharing">\';
    echo \'<h4 data-i18n="share_this">\' . esc_html(mdmc_get_translation(\'share_this\')) . \'</h4>\';
    echo \'<ul>\';
    echo \'<li><a href="https://www.facebook.com/sharer/sharer.php?u=\' . $share_url . \'" target="_blank" rel="noopener noreferrer" class="facebook-share"><span class="screen-reader-text">Facebook</span><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 2H15C13.6739 2 12.4021 2.52678 11.4645 3.46447C10.5268 4.40215 10 5.67392 10 7V10H7V14H10V22H14V14H17L18 10H14V7C14 6.73478 14.1054 6.48043 14.2929 6.29289C14.4804 6.10536 14.7348 6 15 6H18V2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a></li>\';
    echo \'<li><a href="https://twitter.com/intent/tweet?text=\' . $share_title . \'&url=\' . $share_url . \'" target="_blank" rel="noopener noreferrer" class="twitter-share"><span class="screen-reader-text">Twitter</span><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M23 3C22.0424 3.67548 20.9821 4.19211 19.86 4.53C19.2577 3.83751 18.4573 3.34669 17.567 3.12393C16.6767 2.90116 15.7395 2.9572 14.8821 3.28445C14.0247 3.61171 13.2884 4.1944 12.773 4.95372C12.2575 5.71303 11.9877 6.61234 12 7.53V8.53C10.2426 8.57557 8.50127 8.18581 6.93101 7.39545C5.36074 6.60508 4.01032 5.43864 3 4C3 4 -1 13 8 17C5.94053 18.398 3.48716 19.0989 1 19C10 24 21 19 21 7.5C20.9991 7.22145 20.9723 6.94359 20.92 6.67C21.9406 5.66349 22.6608 4.39271 23 3V3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a></li>\';
    echo \'<li><a href="https://www.linkedin.com/shareArticle?mini=true&url=\' . $share_url . \'&title=\' . $share_title . \'" target="_blank" rel="noopener noreferrer" class="linkedin-share"><span class="screen-reader-text">LinkedIn</span><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 8C17.5913 8 19.1174 8.63214 20.2426 9.75736C21.3679 10.8826 22 12.4087 22 14V21H18V14C18 13.4696 17.7893 12.9609 17.4142 12.5858C17.0391 12.2107 16.5304 12 16 12C15.4696 12 14.9609 12.2107 14.5858 12.5858C14.2107 12.9609 14 13.4696 14 14V21H10V14C10 12.4087 10.6321 10.8826 11.7574 9.75736C12.8826 8.63214 14.4087 8 16 8V8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 9H2V21H6V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 6C5.10457 6 6 5.10457 6 4C6 2.89543 5.10457 2 4 2C2.89543 2 2 2.89543 2 4C2 5.10457 2.89543 6 4 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a></li>\';
    echo \'</ul>\';
    echo \'</div>\';
}

/**
 * Display CTA block for simulator
 */
function mdmc_simulator_cta() {
    echo \'<div class="simulator-cta">\';
    echo \'<h3 data-i18n="simulator_title">\' . esc_html(mdmc_get_translation(\'simulator_title\')) . \'</h3>\';
    echo \'<p data-i18n="simulator_text">\' . esc_html(mdmc_get_translation(\'simulator_text\')) . \'</p>\';
    echo \'<a href="\' . esc_url(home_url(\'/simulateur/\')) . \'" class="btn btn-primary" data-i18n="simulator_button">\' . esc_html(mdmc_get_translation(\'simulator_button\')) . \'</a>\';
    echo \'</div>\';
}
';
        
        file_put_contents($template_tags_file, $template_tags_content);
    }
}
add_action('after_setup_theme', 'mdmc_create_template_tags_file');
