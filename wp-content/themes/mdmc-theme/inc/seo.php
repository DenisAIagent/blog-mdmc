<?php
/**
 * SEO optimization functions for MDMC Theme
 *
 * @package MDMC_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Add SEO meta boxes to post edit screen
 */
function mdmc_add_seo_meta_boxes() {
    $post_types = array( 'post', 'page' );
    
    foreach ( $post_types as $post_type ) {
        add_meta_box(
            'mdmc_seo_meta_box',
            __( 'MDMC SEO Settings', 'mdmc-theme' ),
            'mdmc_seo_meta_box_callback',
            $post_type,
            'normal',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'mdmc_add_seo_meta_boxes' );

/**
 * SEO meta box callback
 */
function mdmc_seo_meta_box_callback( $post ) {
    wp_nonce_field( 'mdmc_seo_meta_box', 'mdmc_seo_nonce' );
    
    $languages = array(
        'fr' => __( 'Français', 'mdmc-theme' ),
        'en' => __( 'English', 'mdmc-theme' ),
        'es' => __( 'Español', 'mdmc-theme' ),
        'pt' => __( 'Português', 'mdmc-theme' ),
    );
    
    $current_lang = mdmc_get_current_language();
    
    echo '<div class="mdmc-seo-tabs">';
    echo '<div class="mdmc-seo-tab-nav">';
    
    foreach ( $languages as $lang_code => $lang_name ) {
        $active_class = ( $lang_code === $current_lang ) ? ' active' : '';
        echo '<button type="button" class="mdmc-seo-tab-button' . $active_class . '" data-lang="' . esc_attr( $lang_code ) . '">' . esc_html( $lang_name ) . '</button>';
    }
    
    echo '</div>';
    
    foreach ( $languages as $lang_code => $lang_name ) {
        $display_style = ( $lang_code === $current_lang ) ? '' : ' style="display:none;"';
        
        echo '<div class="mdmc-seo-tab-content" data-lang="' . esc_attr( $lang_code ) . '"' . $display_style . '>';
        
        // Meta title field
        $meta_title_value = get_post_meta( $post->ID, '_mdmc_meta_title_' . $lang_code, true );
        echo '<p>';
        echo '<label for="mdmc_meta_title_' . esc_attr( $lang_code ) . '">' . __( 'Meta Title', 'mdmc-theme' ) . '</label><br>';
        echo '<input type="text" id="mdmc_meta_title_' . esc_attr( $lang_code ) . '" name="mdmc_meta_title_' . esc_attr( $lang_code ) . '" value="' . esc_attr( $meta_title_value ) . '" style="width: 100%;">';
        echo '<span class="description">' . __( 'Recommended length: 50-60 characters', 'mdmc-theme' ) . '</span>';
        echo '<div class="mdmc-character-count"><span id="mdmc_meta_title_' . esc_attr( $lang_code ) . '_count">' . strlen( $meta_title_value ) . '</span> ' . __( 'characters', 'mdmc-theme' ) . '</div>';
        echo '</p>';
        
        // Meta description field
        $meta_description_value = get_post_meta( $post->ID, '_mdmc_meta_description_' . $lang_code, true );
        echo '<p>';
        echo '<label for="mdmc_meta_description_' . esc_attr( $lang_code ) . '">' . __( 'Meta Description', 'mdmc-theme' ) . '</label><br>';
        echo '<textarea id="mdmc_meta_description_' . esc_attr( $lang_code ) . '" name="mdmc_meta_description_' . esc_attr( $lang_code ) . '" rows="3" style="width: 100%;">' . esc_textarea( $meta_description_value ) . '</textarea>';
        echo '<span class="description">' . __( 'Recommended length: 150-160 characters', 'mdmc-theme' ) . '</span>';
        echo '<div class="mdmc-character-count"><span id="mdmc_meta_description_' . esc_attr( $lang_code ) . '_count">' . strlen( $meta_description_value ) . '</span> ' . __( 'characters', 'mdmc-theme' ) . '</div>';
        echo '</p>';
        
        // Focus keywords field
        $focus_keywords_value = get_post_meta( $post->ID, '_mdmc_focus_keywords_' . $lang_code, true );
        echo '<p>';
        echo '<label for="mdmc_focus_keywords_' . esc_attr( $lang_code ) . '">' . __( 'Focus Keywords', 'mdmc-theme' ) . '</label><br>';
        echo '<input type="text" id="mdmc_focus_keywords_' . esc_attr( $lang_code ) . '" name="mdmc_focus_keywords_' . esc_attr( $lang_code ) . '" value="' . esc_attr( $focus_keywords_value ) . '" style="width: 100%;">';
        echo '<span class="description">' . __( 'Separate keywords with commas', 'mdmc-theme' ) . '</span>';
        echo '</p>';
        
        // Canonical URL field
        $canonical_url_value = get_post_meta( $post->ID, '_mdmc_canonical_url_' . $lang_code, true );
        echo '<p>';
        echo '<label for="mdmc_canonical_url_' . esc_attr( $lang_code ) . '">' . __( 'Canonical URL', 'mdmc-theme' ) . '</label><br>';
        echo '<input type="url" id="mdmc_canonical_url_' . esc_attr( $lang_code ) . '" name="mdmc_canonical_url_' . esc_attr( $lang_code ) . '" value="' . esc_url( $canonical_url_value ) . '" style="width: 100%;">';
        echo '<span class="description">' . __( 'Leave empty to use the default URL', 'mdmc-theme' ) . '</span>';
        echo '</p>';
        
        // Social media preview
        echo '<div class="mdmc-social-preview">';
        echo '<h4>' . __( 'Social Media Preview', 'mdmc-theme' ) . '</h4>';
        
        echo '<div class="mdmc-social-preview-facebook">';
        echo '<h5>' . __( 'Facebook / LinkedIn', 'mdmc-theme' ) . '</h5>';
        echo '<div class="mdmc-social-preview-box">';
        echo '<div class="mdmc-social-preview-title" id="mdmc_social_preview_title_fb_' . esc_attr( $lang_code ) . '">' . ( ! empty( $meta_title_value ) ? esc_html( $meta_title_value ) : esc_html( get_the_title( $post->ID ) ) ) . '</div>';
        echo '<div class="mdmc-social-preview-url">' . esc_url( get_permalink( $post->ID ) ) . '</div>';
        echo '<div class="mdmc-social-preview-description" id="mdmc_social_preview_desc_fb_' . esc_attr( $lang_code ) . '">' . ( ! empty( $meta_description_value ) ? esc_html( $meta_description_value ) : esc_html( wp_trim_words( strip_shortcodes( $post->post_content ), 25 ) ) ) . '</div>';
        echo '</div>';
        echo '</div>';
        
        echo '<div class="mdmc-social-preview-twitter">';
        echo '<h5>' . __( 'Twitter / X', 'mdmc-theme' ) . '</h5>';
        echo '<div class="mdmc-social-preview-box">';
        echo '<div class="mdmc-social-preview-title" id="mdmc_social_preview_title_tw_' . esc_attr( $lang_code ) . '">' . ( ! empty( $meta_title_value ) ? esc_html( $meta_title_value ) : esc_html( get_the_title( $post->ID ) ) ) . '</div>';
        echo '<div class="mdmc-social-preview-description" id="mdmc_social_preview_desc_tw_' . esc_attr( $lang_code ) . '">' . ( ! empty( $meta_description_value ) ? esc_html( $meta_description_value ) : esc_html( wp_trim_words( strip_shortcodes( $post->post_content ), 25 ) ) ) . '</div>';
        echo '<div class="mdmc-social-preview-url">' . esc_url( get_permalink( $post->ID ) ) . '</div>';
        echo '</div>';
        echo '</div>';
        
        echo '</div>';
        
        echo '</div>';
    }
    
    echo '</div>';
    
    // Add JavaScript to handle tabs and character counting
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Handle tab clicks
        $('.mdmc-seo-tab-button').click(function() {
            var lang = $(this).data('lang');
            
            $('.mdmc-seo-tab-button').removeClass('active');
            $(this).addClass('active');
            
            $('.mdmc-seo-tab-content').hide();
            $('.mdmc-seo-tab-content[data-lang="' + lang + '"]').show();
        });
        
        // Character counting for meta title and description
        $('[id^="mdmc_meta_title_"], [id^="mdmc_meta_description_"]').on('input', function() {
            var id = $(this).attr('id');
            var count = $(this).val().length;
            $('#' + id + '_count').text(count);
            
            // Update social preview
            if (id.indexOf('mdmc_meta_title_') === 0) {
                var lang = id.replace('mdmc_meta_title_', '');
                $('#mdmc_social_preview_title_fb_' + lang).text($(this).val());
                $('#mdmc_social_preview_title_tw_' + lang).text($(this).val());
            } else if (id.indexOf('mdmc_meta_description_') === 0) {
                var lang = id.replace('mdmc_meta_description_', '');
                $('#mdmc_social_preview_desc_fb_' + lang).text($(this).val());
                $('#mdmc_social_preview_desc_tw_' + lang).text($(this).val());
            }
        });
    });
    </script>
    <style>
    .mdmc-seo-tab-nav {
        margin-bottom: 15px;
        border-bottom: 1px solid #ccc;
    }
    .mdmc-seo-tab-button {
        padding: 8px 12px;
        background: #f7f7f7;
        border: 1px solid #ccc;
        border-bottom: none;
        margin-right: 5px;
        cursor: pointer;
    }
    .mdmc-seo-tab-button.active {
        background: #fff;
        border-bottom: 1px solid #fff;
        margin-bottom: -1px;
    }
    .mdmc-character-count {
        text-align: right;
        color: #666;
        font-size: 12px;
        margin-top: 5px;
    }
    .mdmc-social-preview {
        margin-top: 20px;
        border-top: 1px solid #eee;
        padding-top: 15px;
    }
    .mdmc-social-preview h4 {
        margin-bottom: 15px;
    }
    .mdmc-social-preview h5 {
        margin-bottom: 10px;
    }
    .mdmc-social-preview-box {
        border: 1px solid #ddd;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
        background: #f9f9f9;
    }
    .mdmc-social-preview-title {
        font-weight: bold;
        font-size: 16px;
        color: #1a0dab;
        margin-bottom: 5px;
    }
    .mdmc-social-preview-url {
        color: #006621;
        font-size: 13px;
        margin-bottom: 5px;
    }
    .mdmc-social-preview-description {
        color: #545454;
        font-size: 13px;
        line-height: 1.4;
    }
    </style>
    <?php
}

/**
 * Save SEO meta box data
 */
function mdmc_save_seo_meta_box( $post_id ) {
    // Check if nonce is set
    if ( ! isset( $_POST['mdmc_seo_nonce'] ) ) {
        return;
    }

    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['mdmc_seo_nonce'], 'mdmc_seo_meta_box' ) ) {
        return;
    }

    // If this is an autosave, don't do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check user permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $languages = array( 'fr', 'en', 'es', 'pt' );
    
    foreach ( $languages as $lang ) {
        // Save meta title
        if ( isset( $_POST['mdmc_meta_title_' . $lang] ) ) {
            update_post_meta( $post_id, '_mdmc_meta_title_' . $lang, sanitize_text_field( $_POST['mdmc_meta_title_' . $lang] ) );
        }
        
        // Save meta description
        if ( isset( $_POST['mdmc_meta_description_' . $lang] ) ) {
            update_post_meta( $post_id, '_mdmc_meta_description_' . $lang, sanitize_textarea_field( $_POST['mdmc_meta_description_' . $lang] ) );
        }
        
        // Save focus keywords
        if ( isset( $_POST['mdmc_focus_keywords_' . $lang] ) ) {
            update_post_meta( $post_id, '_mdmc_focus_keywords_' . $lang, sanitize_text_field( $_POST['mdmc_focus_keywords_' . $lang] ) );
        }
        
        // Save canonical URL
        if ( isset( $_POST['mdmc_canonical_url_' . $lang] ) ) {
            update_post_meta( $post_id, '_mdmc_canonical_url_' . $lang, esc_url_raw( $_POST['mdmc_canonical_url_' . $lang] ) );
        }
    }
}
add_action( 'save_post', 'mdmc_save_seo_meta_box' );

/**
 * Add meta tags to head
 */
function mdmc_add_meta_tags() {
    global $post;
    
    if ( ! is_singular() || ! isset( $post->ID ) ) {
        return;
    }
    
    $current_lang = mdmc_get_current_language();
    
    // Get meta title
    $meta_title = get_post_meta( $post->ID, '_mdmc_meta_title_' . $current_lang, true );
    if ( empty( $meta_title ) ) {
        $meta_title = get_the_title( $post->ID );
    }
    
    // Get meta description
    $meta_description = get_post_meta( $post->ID, '_mdmc_meta_description_' . $current_lang, true );
    if ( empty( $meta_description ) ) {
        $meta_description = wp_trim_words( strip_shortcodes( $post->post_content ), 25 );
    }
    
    // Get focus keywords
    $focus_keywords = get_post_meta( $post->ID, '_mdmc_focus_keywords_' . $current_lang, true );
    
    // Get canonical URL
    $canonical_url = get_post_meta( $post->ID, '_mdmc_canonical_url_' . $current_lang, true );
    if ( empty( $canonical_url ) ) {
        $canonical_url = get_permalink( $post->ID );
    }
    
    // Output meta tags
    echo '<meta name="description" content="' . esc_attr( $meta_description ) . '" />' . "\n";
    
    if ( ! empty( $focus_keywords ) ) {
        echo '<meta name="keywords" content="' . esc_attr( $focus_keywords ) . '" />' . "\n";
    }
    
    echo '<link rel="canonical" href="' . esc_url( $canonical_url ) . '" />' . "\n";
    
    // Open Graph meta tags
    echo '<meta property="og:locale" content="' . esc_attr( $current_lang . '_' . strtoupper( $current_lang ) ) . '" />' . "\n";
    echo '<meta property="og:type" content="article" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $meta_title ) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $meta_description ) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $canonical_url ) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
    
    if ( has_post_thumbnail( $post->ID ) ) {
        $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
        echo '<meta property="og:image" content="' . esc_url( $thumbnail_src[0] ) . '" />' . "\n";
    }
    
    // Twitter Card meta tags
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $meta_title ) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $meta_description ) . '" />' . "\n";
    
    if ( has_post_thumbnail( $post->ID ) ) {
        $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
        echo '<meta name="twitter:image" content="' . esc_url( $thumbnail_src[0] ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'mdmc_add_meta_tags', 1 );

/**
 * Filter document title
 */
function mdmc_filter_document_title( $title ) {
    global $post;
    
    if ( is_singular() && isset( $post->ID ) ) {
        $current_lang = mdmc_get_current_language();
        $meta_title = get_post_meta( $post->ID, '_mdmc_meta_title_' . $current_lang, true );
        
        if ( ! empty( $meta_title ) ) {
            $title['title'] = $meta_title;
        }
    }
    
    return $title;
}
add_filter( 'document_title_parts', 'mdmc_filter_document_title' );

/**
 * Add structured data
 */
function mdmc_add_structured_data() {
    global $post;
    
    if ( ! is_singular() || ! isset( $post->ID ) ) {
        return;
    }
    
    $current_lang = mdmc_get_current_language();
    
    // Get meta title
    $meta_title = get_post_meta( $post->ID, '_mdmc_meta_title_' . $current_lang, true );
    if ( empty( $meta_title ) ) {
        $meta_title = get_the_title( $post->ID );
    }
    
    // Get meta description
    $meta_description = get_post_meta( $post->ID, '_mdmc_meta_description_' . $current_lang, true );
    if ( empty( $meta_description ) ) {
        $meta_description = wp_trim_words( strip_shortcodes( $post->post_content ), 25 );
    }
    
    // Get canonical URL
    $canonical_url = get_post_meta( $post->ID, '_mdmc_canonical_url_' . $current_lang, true );
    if ( empty( $canonical_url ) ) {
        $canonical_url = get_permalink( $post->ID );
    }
    
    // Prepare structured data
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $meta_title,
        'description' => $meta_description,
        'url' => $canonical_url,
        'datePublished' => get_the_date( 'c', $post->ID ),
        'dateModified' => get_the_modified_date( 'c', $post->ID ),
        'author' => array(
            '@type' => 'Person',
            'name' => get_the_author_meta( 'display_name', $post->post_author )
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo( 'name' ),
            'logo' => array(
                '@type' => 'ImageObject',
                'url' => get_custom_logo_url()
            )
        )
    );
    
    if ( has_post_thumbnail( $post->ID ) ) {
        $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $thumbnail_src[0],
            'width' => $thumbnail_src[1],
            'height' => $thumbnail_src[2]
        );
    }
    
    // Output structured data
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
    
    // Add WebSite structured data for homepage
    if ( is_front_page() ) {
        $website_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_bloginfo( 'name' ),
            'url' => home_url(),
            'potentialAction' => array(
                '@type' => 'SearchAction',
                'target' => home_url( '/?s={search_term_string}' ),
                'query-input' => 'required name=search_term_string'
            )
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode( $website_schema ) . '</script>' . "\n";
    }
    
    // Add BreadcrumbList structured data
    if ( ! is_front_page() ) {
        $breadcrumbs = array();
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => 1,
            'name' => get_bloginfo( 'name' ),
            'item' => home_url()
        );
        
        if ( is_singular( 'post' ) ) {
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => __( 'Blog', 'mdmc-theme' ),
                'item' => get_post_type_archive_link( 'post' )
            );
            
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 3,
                'name' => get_the_title( $post->ID ),
                'item' => get_permalink( $post->ID )
            );
        } elseif ( is_page() ) {
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => get_the_title( $post->ID ),
                'item' => get_permalink( $post->ID )
            );
        }
        
        $breadcrumb_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode( $breadcrumb_schema ) . '</script>' . "\n";
    }
}
add_action( 'wp_head', 'mdmc_add_structured_data' );

/**
 * Add SEO analysis to post edit screen
 */
function mdmc_add_seo_analysis() {
    global $post;
    
    if ( ! isset( $post->ID ) ) {
        return;
    }
    
    $current_lang = mdmc_get_current_language();
    
    // Get meta title
    $meta_title = get_post_meta( $post->ID, '_mdmc_meta_title_' . $current_lang, true );
    if ( empty( $meta_title ) ) {
        $meta_title = get_the_title( $post->ID );
    }
    
    // Get meta description
    $meta_description = get_post_meta( $post->ID, '_mdmc_meta_description_' . $current_lang, true );
    
    // Get focus keywords
    $focus_keywords = get_post_meta( $post->ID, '_mdmc_focus_keywords_' . $current_lang, true );
    $keywords_array = array();
    if ( ! empty( $focus_keywords ) ) {
        $keywords_array = array_map( 'trim', explode( ',', $focus_keywords ) );
    }
    
    // Get content
    $content = $post->post_content;
    
    // Perform SEO analysis
    $analysis = array();
    
    // Check title length
    $title_length = strlen( $meta_title );
    if ( $title_length < 30 ) {
        $analysis[] = array(
            'status' => 'error',
            'message' => __( 'Your title is too short. It should be at least 30 characters.', 'mdmc-theme' )
        );
    } elseif ( $title_length > 60 ) {
        $analysis[] = array(
            'status' => 'warning',
            'message' => __( 'Your title is too long. It should be no more than 60 characters.', 'mdmc-theme' )
        );
    } else {
        $analysis[] = array(
            'status' => 'success',
            'message' => __( 'Your title length is good.', 'mdmc-theme' )
        );
    }
    
    // Check description length
    if ( empty( $meta_description ) ) {
        $analysis[] = array(
            'status' => 'error',
            'message' => __( 'You should add a meta description.', 'mdmc-theme' )
        );
    } else {
        $description_length = strlen( $meta_description );
        if ( $description_length < 120 ) {
            $analysis[] = array(
                'status' => 'warning',
                'message' => __( 'Your description is too short. It should be at least 120 characters.', 'mdmc-theme' )
            );
        } elseif ( $description_length > 160 ) {
            $analysis[] = array(
                'status' => 'warning',
                'message' => __( 'Your description is too long. It should be no more than 160 characters.', 'mdmc-theme' )
            );
        } else {
            $analysis[] = array(
                'status' => 'success',
                'message' => __( 'Your description length is good.', 'mdmc-theme' )
            );
        }
    }
    
    // Check keywords
    if ( empty( $keywords_array ) ) {
        $analysis[] = array(
            'status' => 'warning',
            'message' => __( 'You should add focus keywords.', 'mdmc-theme' )
        );
    } else {
        $analysis[] = array(
            'status' => 'success',
            'message' => __( 'You have added focus keywords.', 'mdmc-theme' )
        );
        
        // Check if keywords are in title
        $keywords_in_title = false;
        foreach ( $keywords_array as $keyword ) {
            if ( stripos( $meta_title, $keyword ) !== false ) {
                $keywords_in_title = true;
                break;
            }
        }
        
        if ( $keywords_in_title ) {
            $analysis[] = array(
                'status' => 'success',
                'message' => __( 'Your title contains at least one of your focus keywords.', 'mdmc-theme' )
            );
        } else {
            $analysis[] = array(
                'status' => 'warning',
                'message' => __( 'Your title does not contain any of your focus keywords.', 'mdmc-theme' )
            );
        }
        
        // Check if keywords are in description
        if ( ! empty( $meta_description ) ) {
            $keywords_in_description = false;
            foreach ( $keywords_array as $keyword ) {
                if ( stripos( $meta_description, $keyword ) !== false ) {
                    $keywords_in_description = true;
                    break;
                }
            }
            
            if ( $keywords_in_description ) {
                $analysis[] = array(
                    'status' => 'success',
                    'message' => __( 'Your description contains at least one of your focus keywords.', 'mdmc-theme' )
                );
            } else {
                $analysis[] = array(
                    'status' => 'warning',
                    'message' => __( 'Your description does not contain any of your focus keywords.', 'mdmc-theme' )
                );
            }
        }
        
        // Check if keywords are in content
        $keywords_in_content = false;
        foreach ( $keywords_array as $keyword ) {
            if ( stripos( $content, $keyword ) !== false ) {
                $keywords_in_content = true;
                break;
            }
        }
        
        if ( $keywords_in_content ) {
            $analysis[] = array(
                'status' => 'success',
                'message' => __( 'Your content contains at least one of your focus keywords.', 'mdmc-theme' )
            );
        } else {
            $analysis[] = array(
                'status' => 'warning',
                'message' => __( 'Your content does not contain any of your focus keywords.', 'mdmc-theme' )
            );
        }
    }
    
    // Check content length
    $content_length = str_word_count( strip_tags( $content ) );
    if ( $content_length < 300 ) {
        $analysis[] = array(
            'status' => 'warning',
            'message' => __( 'Your content is too short. It should be at least 300 words.', 'mdmc-theme' )
        );
    } else {
        $analysis[] = array(
            'status' => 'success',
            'message' => __( 'Your content length is good.', 'mdmc-theme' )
        );
    }
    
    // Check headings
    if ( strpos( $content, '<h2' ) === false && strpos( $content, '<h3' ) === false ) {
        $analysis[] = array(
            'status' => 'warning',
            'message' => __( 'Your content does not contain any subheadings (H2, H3).', 'mdmc-theme' )
        );
    } else {
        $analysis[] = array(
            'status' => 'success',
            'message' => __( 'Your content contains subheadings.', 'mdmc-theme' )
        );
    }
    
    // Check images
    if ( strpos( $content, '<img' ) === false ) {
        $analysis[] = array(
            'status' => 'warning',
            'message' => __( 'Your content does not contain any images.', 'mdmc-theme' )
        );
    } else {
        $analysis[] = array(
            'status' => 'success',
            'message' => __( 'Your content contains images.', 'mdmc-theme' )
        );
    }
    
    // Check links
    if ( strpos( $content, '<a' ) === false ) {
        $analysis[] = array(
            'status' => 'warning',
            'message' => __( 'Your content does not contain any links.', 'mdmc-theme' )
        );
    } else {
        $analysis[] = array(
            'status' => 'success',
            'message' => __( 'Your content contains links.', 'mdmc-theme' )
        );
    }
    
    // Output analysis
    echo '<div class="mdmc-seo-analysis">';
    echo '<h4>' . __( 'SEO Analysis', 'mdmc-theme' ) . '</h4>';
    
    foreach ( $analysis as $item ) {
        echo '<div class="mdmc-seo-analysis-item mdmc-seo-analysis-' . esc_attr( $item['status'] ) . '">';
        echo '<span class="mdmc-seo-analysis-icon"></span>';
        echo '<span class="mdmc-seo-analysis-message">' . esc_html( $item['message'] ) . '</span>';
        echo '</div>';
    }
    
    echo '</div>';
    
    // Add CSS for analysis
    ?>
    <style>
    .mdmc-seo-analysis {
        margin-top: 20px;
        border-top: 1px solid #eee;
        padding-top: 15px;
    }
    .mdmc-seo-analysis h4 {
        margin-bottom: 15px;
    }
    .mdmc-seo-analysis-item {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }
    .mdmc-seo-analysis-icon {
        display: inline-block;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        margin-right: 10px;
    }
    .mdmc-seo-analysis-success .mdmc-seo-analysis-icon {
        background-color: #46b450;
    }
    .mdmc-seo-analysis-warning .mdmc-seo-analysis-icon {
        background-color: #ffb900;
    }
    .mdmc-seo-analysis-error .mdmc-seo-analysis-icon {
        background-color: #dc3232;
    }
    </style>
    <?php
}
add_action( 'mdmc_seo_meta_box_callback', 'mdmc_add_seo_analysis' );
