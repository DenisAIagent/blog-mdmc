<?php
/**
 * Automatic translation functionality for MDMC Music Ads Blog Theme
 *
 * @package MDMC_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Class for handling automatic translation of content and metadata
 */
class MDMC_Auto_Translator {
    /**
     * Supported languages
     */
    private $languages = array('fr', 'en', 'es', 'pt');
    
    /**
     * Default language
     */
    private $default_language = 'fr';
    
    /**
     * Constructor
     */
    public function __construct() {
        // Hook into post save
        add_action('save_post', array($this, 'auto_translate_post'), 10, 3);
        
        // Add admin notice for translation status
        add_action('admin_notices', array($this, 'translation_admin_notice'));
        
        // Add AJAX handler for manual translation trigger
        add_action('wp_ajax_mdmc_translate_post', array($this, 'ajax_translate_post'));
        
        // Add meta box for translation options
        add_action('add_meta_boxes', array($this, 'add_translation_meta_box'));
    }
    
    /**
     * Add meta box for translation options
     */
    public function add_translation_meta_box() {
        add_meta_box(
            'mdmc_translation_meta_box',
            __('MDMC Auto Translation', 'mdmc-theme'),
            array($this, 'render_translation_meta_box'),
            array('post', 'page'),
            'side',
            'high'
        );
    }
    
    /**
     * Render translation meta box
     */
    public function render_translation_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('mdmc_translation_meta_box', 'mdmc_translation_meta_box_nonce');
        
        // Get current translation status
        $translation_status = get_post_meta($post->ID, '_mdmc_translation_status', true);
        $source_language = get_post_meta($post->ID, '_mdmc_source_language', true);
        
        if (empty($source_language)) {
            $source_language = $this->default_language;
        }
        
        // Output fields
        ?>
        <p>
            <label for="mdmc_source_language"><?php _e('Source Language:', 'mdmc-theme'); ?></label>
            <select id="mdmc_source_language" name="mdmc_source_language">
                <?php foreach ($this->languages as $lang) : ?>
                    <option value="<?php echo esc_attr($lang); ?>" <?php selected($source_language, $lang); ?>>
                        <?php echo strtoupper(esc_html($lang)); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        
        <p>
            <label for="mdmc_auto_translate">
                <input type="checkbox" id="mdmc_auto_translate" name="mdmc_auto_translate" value="1" checked>
                <?php _e('Auto-translate on save', 'mdmc-theme'); ?>
            </label>
        </p>
        
        <div class="translation-status">
            <?php if (!empty($translation_status)) : ?>
                <p><?php echo esc_html($translation_status); ?></p>
            <?php endif; ?>
        </div>
        
        <p>
            <button type="button" id="mdmc_translate_now" class="button button-secondary" data-post-id="<?php echo esc_attr($post->ID); ?>">
                <?php _e('Translate Now', 'mdmc-theme'); ?>
            </button>
            <span class="spinner" style="float: none; margin-top: 0;"></span>
        </p>
        
        <script>
        jQuery(document).ready(function($) {
            $('#mdmc_translate_now').on('click', function() {
                var button = $(this);
                var spinner = button.next('.spinner');
                var postId = button.data('post-id');
                var sourceLang = $('#mdmc_source_language').val();
                
                // Disable button and show spinner
                button.prop('disabled', true);
                spinner.css('visibility', 'visible');
                
                // Send AJAX request
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'mdmc_translate_post',
                        post_id: postId,
                        source_language: sourceLang,
                        nonce: $('#mdmc_translation_meta_box_nonce').val()
                    },
                    success: function(response) {
                        if (response.success) {
                            $('.translation-status').html('<p>' + response.data.message + '</p>');
                        } else {
                            $('.translation-status').html('<p class="error">' + response.data.message + '</p>');
                        }
                    },
                    error: function() {
                        $('.translation-status').html('<p class="error">Translation failed. Please try again.</p>');
                    },
                    complete: function() {
                        // Re-enable button and hide spinner
                        button.prop('disabled', false);
                        spinner.css('visibility', 'hidden');
                    }
                });
            });
        });
        </script>
        <?php
    }
    
    /**
     * AJAX handler for manual translation
     */
    public function ajax_translate_post() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mdmc_translation_meta_box')) {
            wp_send_json_error(array('message' => 'Security check failed.'));
        }
        
        // Check post ID
        if (!isset($_POST['post_id']) || !intval($_POST['post_id'])) {
            wp_send_json_error(array('message' => 'Invalid post ID.'));
        }
        
        $post_id = intval($_POST['post_id']);
        $source_language = isset($_POST['source_language']) ? sanitize_text_field($_POST['source_language']) : $this->default_language;
        
        // Perform translation
        $result = $this->translate_post_content($post_id, $source_language);
        
        if ($result) {
            wp_send_json_success(array('message' => 'Translation completed successfully.'));
        } else {
            wp_send_json_error(array('message' => 'Translation failed. Please try again.'));
        }
    }
    
    /**
     * Display admin notice for translation status
     */
    public function translation_admin_notice() {
        global $pagenow, $post;
        
        // Only show on post edit screen
        if (!($pagenow == 'post.php' && isset($_GET['post']) && isset($_GET['message']) && $_GET['message'] == 1)) {
            return;
        }
        
        $post_id = intval($_GET['post']);
        $translation_status = get_post_meta($post_id, '_mdmc_translation_status', true);
        
        if (!empty($translation_status)) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($translation_status) . '</p></div>';
            
            // Clear the status after displaying
            delete_post_meta($post_id, '_mdmc_translation_status');
        }
    }
    
    /**
     * Auto translate post content and metadata on save
     */
    public function auto_translate_post($post_id, $post, $update) {
        // Skip auto-save, revision, and auto-draft
        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id) || $post->post_status == 'auto-draft') {
            return;
        }
        
        // Check if auto-translate is enabled
        if (isset($_POST['mdmc_auto_translate']) && $_POST['mdmc_auto_translate'] == '1') {
            // Get source language
            $source_language = isset($_POST['mdmc_source_language']) ? sanitize_text_field($_POST['mdmc_source_language']) : $this->default_language;
            
            // Save source language
            update_post_meta($post_id, '_mdmc_source_language', $source_language);
            
            // Translate post
            $this->translate_post_content($post_id, $source_language);
        }
    }
    
    /**
     * Translate post content and metadata
     */
    public function translate_post_content($post_id, $source_language = 'fr') {
        // Get post data
        $post = get_post($post_id);
        
        if (!$post) {
            return false;
        }
        
        // Get post title and content
        $title = $post->post_title;
        $content = $post->post_content;
        $excerpt = $post->post_excerpt;
        
        // Get SEO metadata
        $meta_description = get_post_meta($post_id, '_mdmc_meta_description', true);
        if (!is_array($meta_description)) {
            $meta_description = array();
        }
        
        $meta_keywords = get_post_meta($post_id, '_mdmc_meta_keywords', true);
        if (!is_array($meta_keywords)) {
            $meta_keywords = array();
        }
        
        // Store translations
        $translations = array(
            'title' => array(),
            'content' => array(),
            'excerpt' => array(),
            'meta_description' => array(),
            'meta_keywords' => array()
        );
        
        // Set source language content
        $translations['title'][$source_language] = $title;
        $translations['content'][$source_language] = $content;
        $translations['excerpt'][$source_language] = $excerpt;
        
        if (isset($meta_description[$source_language])) {
            $translations['meta_description'][$source_language] = $meta_description[$source_language];
        }
        
        if (isset($meta_keywords[$source_language])) {
            $translations['meta_keywords'][$source_language] = $meta_keywords[$source_language];
        }
        
        // Translate to other languages
        foreach ($this->languages as $target_lang) {
            // Skip source language
            if ($target_lang === $source_language) {
                continue;
            }
            
            // Translate title
            $translations['title'][$target_lang] = $this->translate_text($title, $source_language, $target_lang);
            
            // Translate content
            $translations['content'][$target_lang] = $this->translate_text($content, $source_language, $target_lang);
            
            // Translate excerpt
            $translations['excerpt'][$target_lang] = $this->translate_text($excerpt, $source_language, $target_lang);
            
            // Translate meta description
            if (isset($meta_description[$source_language]) && !empty($meta_description[$source_language])) {
                $translations['meta_description'][$target_lang] = $this->translate_text(
                    $meta_description[$source_language],
                    $source_language,
                    $target_lang
                );
            }
            
            // Translate meta keywords
            if (isset($meta_keywords[$source_language]) && !empty($meta_keywords[$source_language])) {
                $translations['meta_keywords'][$target_lang] = $this->translate_text(
                    $meta_keywords[$source_language],
                    $source_language,
                    $target_lang
                );
            }
        }
        
        // Store translations in post meta
        update_post_meta($post_id, '_mdmc_translations', $translations);
        
        // Update SEO metadata with translations
        update_post_meta($post_id, '_mdmc_meta_description', $translations['meta_description']);
        update_post_meta($post_id, '_mdmc_meta_keywords', $translations['meta_keywords']);
        
        // Set translation status
        update_post_meta(
            $post_id,
            '_mdmc_translation_status',
            sprintf(
                __('Content automatically translated from %s to %s.', 'mdmc-theme'),
                strtoupper($source_language),
                implode(', ', array_map('strtoupper', array_diff($this->languages, array($source_language))))
            )
        );
        
        return true;
    }
    
    /**
     * Translate text using LibreTranslate API
     * 
     * This is a simple implementation. For production, consider:
     * 1. Using a more robust API with better translation quality
     * 2. Implementing rate limiting and error handling
     * 3. Adding caching to avoid repeated translations
     */
    private function translate_text($text, $source_lang, $target_lang) {
        // If text is empty, return empty
        if (empty($text)) {
            return '';
        }
        
        // For demonstration purposes, we'll use a simple translation approach
        // In a real implementation, you would use an API like Google Translate, DeepL, or LibreTranslate
        
        // Simulate translation with a basic word replacement
        // This is just for demonstration - in a real scenario, use a proper translation API
        $translated = $this->simulate_translation($text, $source_lang, $target_lang);
        
        return $translated;
    }
    
    /**
     * Simulate translation for demonstration purposes
     * 
     * In a real implementation, replace this with an actual API call
     */
    private function simulate_translation($text, $source_lang, $target_lang) {
        // Common words in different languages for simulation
        $common_words = array(
            'fr' => array(
                'bonjour' => array('en' => 'hello', 'es' => 'hola', 'pt' => 'olá'),
                'merci' => array('en' => 'thank you', 'es' => 'gracias', 'pt' => 'obrigado'),
                'musique' => array('en' => 'music', 'es' => 'música', 'pt' => 'música'),
                'marketing' => array('en' => 'marketing', 'es' => 'marketing', 'pt' => 'marketing'),
                'digital' => array('en' => 'digital', 'es' => 'digital', 'pt' => 'digital'),
                'blog' => array('en' => 'blog', 'es' => 'blog', 'pt' => 'blog'),
                'article' => array('en' => 'article', 'es' => 'artículo', 'pt' => 'artigo'),
                'campagne' => array('en' => 'campaign', 'es' => 'campaña', 'pt' => 'campanha'),
                'publicitaire' => array('en' => 'advertising', 'es' => 'publicitario', 'pt' => 'publicitário'),
                'simulateur' => array('en' => 'simulator', 'es' => 'simulador', 'pt' => 'simulador')
            ),
            'en' => array(
                'hello' => array('fr' => 'bonjour', 'es' => 'hola', 'pt' => 'olá'),
                'thank you' => array('fr' => 'merci', 'es' => 'gracias', 'pt' => 'obrigado'),
                'music' => array('fr' => 'musique', 'es' => 'música', 'pt' => 'música'),
                'marketing' => array('fr' => 'marketing', 'es' => 'marketing', 'pt' => 'marketing'),
                'digital' => array('fr' => 'digital', 'es' => 'digital', 'pt' => 'digital'),
                'blog' => array('fr' => 'blog', 'es' => 'blog', 'pt' => 'blog'),
                'article' => array('fr' => 'article', 'es' => 'artículo', 'pt' => 'artigo'),
                'campaign' => array('fr' => 'campagne', 'es' => 'campaña', 'pt' => 'campanha'),
                'advertising' => array('fr' => 'publicitaire', 'es' => 'publicitario', 'pt' => 'publicitário'),
                'simulator' => array('fr' => 'simulateur', 'es' => 'simulador', 'pt' => 'simulador')
            ),
            'es' => array(
                'hola' => array('fr' => 'bonjour', 'en' => 'hello', 'pt' => 'olá'),
                'gracias' => array('fr' => 'merci', 'en' => 'thank you', 'pt' => 'obrigado'),
                'música' => array('fr' => 'musique', 'en' => 'music', 'pt' => 'música'),
                'marketing' => array('fr' => 'marketing', 'en' => 'marketing', 'pt' => 'marketing'),
                'digital' => array('fr' => 'digital', 'en' => 'digital', 'pt' => 'digital'),
                'blog' => array('fr' => 'blog', 'en' => 'blog', 'pt' => 'blog'),
                'artículo' => array('fr' => 'article', 'en' => 'article', 'pt' => 'artigo'),
                'campaña' => array('fr' => 'campagne', 'en' => 'campaign', 'pt' => 'campanha'),
                'publicitario' => array('fr' => 'publicitaire', 'en' => 'advertising', 'pt' => 'publicitário'),
                'simulador' => array('fr' => 'simulateur', 'en' => 'simulator', 'pt' => 'simulador')
            ),
            'pt' => array(
                'olá' => array('fr' => 'bonjour', 'en' => 'hello', 'es' => 'hola'),
                'obrigado' => array('fr' => 'merci', 'en' => 'thank you', 'es' => 'gracias'),
                'música' => array('fr' => 'musique', 'en' => 'music', 'es' => 'música'),
                'marketing' => array('fr' => 'marketing', 'en' => 'marketing', 'es' => 'marketing'),
                'digital' => array('fr' => 'digital', 'en' => 'digital', 'es' => 'digital'),
                'blog' => array('fr' => 'blog', 'en' => 'blog', 'es' => 'blog'),
                'artigo' => array('fr' => 'article', 'en' => 'article', 'es' => 'artículo'),
                'campanha' => array('fr' => 'campagne', 'en' => 'campaign', 'es' => 'campaña'),
                'publicitário' => array('fr' => 'publicitaire', 'en' => 'advertising', 'es' => 'publicitario'),
                'simulador' => array('fr' => 'simulateur', 'en' => 'simulator', 'es' => 'simulador')
            )
        );
        
        // Add language-specific prefixes to simulate translation
        $prefixes = array(
            'fr' => 'FR: ',
            'en' => 'EN: ',
            'es' => 'ES: ',
            'pt' => 'PT: '
        );
        
        // For demonstration, we'll just add a prefix to show it's translated
        // In a real implementation, use a proper translation API
        $translated = $prefixes[$target_lang] . $text;
        
        // Replace some common words to simulate translation
        if (isset($common_words[$source_lang])) {
            foreach ($common_words[$source_lang] as $word => $translations) {
                if (isset($translations[$target_lang])) {
                    $translated = str_ireplace($word, $translations[$target_lang], $translated);
                }
            }
        }
        
        return $translated;
    }
}

// Initialize the auto translator
$mdmc_auto_translator = new MDMC_Auto_Translator();

/**
 * Function to get translated content based on current language
 */
function mdmc_get_translated_content($post_id, $field = 'content') {
    $current_lang = mdmc_get_current_language();
    $translations = get_post_meta($post_id, '_mdmc_translations', true);
    
    if (is_array($translations) && isset($translations[$field][$current_lang])) {
        return $translations[$field][$current_lang];
    }
    
    // Fallback to original content
    $post = get_post($post_id);
    
    switch ($field) {
        case 'title':
            return $post->post_title;
        case 'content':
            return $post->post_content;
        case 'excerpt':
            return $post->post_excerpt;
        default:
            return '';
    }
}

/**
 * Filter post title to show translated version
 */
function mdmc_filter_the_title($title, $post_id = 0) {
    if (!$post_id) {
        return $title;
    }
    
    $translated_title = mdmc_get_translated_content($post_id, 'title');
    
    if (!empty($translated_title)) {
        return $translated_title;
    }
    
    return $title;
}
add_filter('the_title', 'mdmc_filter_the_title', 10, 2);

/**
 * Filter post content to show translated version
 */
function mdmc_filter_the_content($content) {
    global $post;
    
    if (!isset($post->ID)) {
        return $content;
    }
    
    $translated_content = mdmc_get_translated_content($post->ID, 'content');
    
    if (!empty($translated_content)) {
        return $translated_content;
    }
    
    return $content;
}
add_filter('the_content', 'mdmc_filter_the_content');

/**
 * Filter post excerpt to show translated version
 */
function mdmc_filter_the_excerpt($excerpt) {
    global $post;
    
    if (!isset($post->ID)) {
        return $excerpt;
    }
    
    $translated_excerpt = mdmc_get_translated_content($post->ID, 'excerpt');
    
    if (!empty($translated_excerpt)) {
        return $translated_excerpt;
    }
    
    return $excerpt;
}
add_filter('the_excerpt', 'mdmc_filter_the_excerpt');
