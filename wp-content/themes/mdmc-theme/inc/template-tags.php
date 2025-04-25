<?php
/**
 * Custom template tags for this theme
 *
 * @package MDMC_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function mdmc_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    
    $time_string = sprintf(
        $time_string,
        esc_attr(get_the_date('c')),
        esc_html(get_the_date())
    );

    $posted_on = sprintf(
        '%s',
        '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
    );

    $byline = sprintf(
        '%s',
        '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
    );

    echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function mdmc_entry_footer() {
    // Hide category and tag text for pages.
    if ('post' === get_post_type()) {
        /* translators: used between list items, there is a space after the comma */
        $categories_list = get_the_category_list(', ');
        if ($categories_list) {
            /* translators: 1: list of categories. */
            printf('<span class="cat-links">%1$s</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list('', ', ');
        if ($tags_list) {
            /* translators: 1: list of tags. */
            printf('<span class="tags-links">%1$s</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
            <?php the_post_thumbnail('mdmc-featured', array('class' => 'featured-image')); ?>
        </div><!-- .post-thumbnail -->
    <?php else : ?>
        <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
            <?php
            the_post_thumbnail(
                'mdmc-card',
                array(
                    'alt' => the_title_attribute(
                        array(
                            'echo' => false,
                        )
                    ),
                    'class' => 'card-image',
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
function mdmc_get_translation($key, $lang = '') {
    if (empty($lang)) {
        $lang = mdmc_get_current_language();
    }
    
    // Get translations from JSON file
    $translations_file = MDMC_THEME_DIR . '/assets/js/languages.json';
    $translations = array();
    
    if (file_exists($translations_file)) {
        $json_content = file_get_contents($translations_file);
        $translations = json_decode($json_content, true);
    }
    
    if (isset($translations[$key][$lang])) {
        return $translations[$key][$lang];
    }
    
    // Fallback to French
    if (isset($translations[$key]['fr'])) {
        return $translations[$key]['fr'];
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
        echo '<span class="category-badge">' . esc_html($categories[0]->name) . '</span>';
    }
}

/**
 * Display social sharing buttons
 */
function mdmc_social_sharing() {
    $share_url = urlencode(get_permalink());
    $share_title = urlencode(get_the_title());
    
    echo '<div class="social-sharing">';
    echo '<h4 data-i18n="share_this">' . esc_html(mdmc_get_translation('share_this')) . '</h4>';
    echo '<ul>';
    echo '<li><a href="https://www.facebook.com/sharer/sharer.php?u=' . $share_url . '" target="_blank" rel="noopener noreferrer" class="facebook-share"><span class="screen-reader-text">Facebook</span><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 2H15C13.6739 2 12.4021 2.52678 11.4645 3.46447C10.5268 4.40215 10 5.67392 10 7V10H7V14H10V22H14V14H17L18 10H14V7C14 6.73478 14.1054 6.48043 14.2929 6.29289C14.4804 6.10536 14.7348 6 15 6H18V2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a></li>';
    echo '<li><a href="https://twitter.com/intent/tweet?text=' . $share_title . '&url=' . $share_url . '" target="_blank" rel="noopener noreferrer" class="twitter-share"><span class="screen-reader-text">Twitter</span><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M23 3C22.0424 3.67548 20.9821 4.19211 19.86 4.53C19.2577 3.83751 18.4573 3.34669 17.567 3.12393C16.6767 2.90116 15.7395 2.9572 14.8821 3.28445C14.0247 3.61171 13.2884 4.1944 12.773 4.95372C12.2575 5.71303 11.9877 6.61234 12 7.53V8.53C10.2426 8.57557 8.50127 8.18581 6.93101 7.39545C5.36074 6.60508 4.01032 5.43864 3 4C3 4 -1 13 8 17C5.94053 18.398 3.48716 19.0989 1 19C10 24 21 19 21 7.5C20.9991 7.22145 20.9723 6.94359 20.92 6.67C21.9406 5.66349 22.6608 4.39271 23 3V3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a></li>';
    echo '<li><a href="https://www.linkedin.com/shareArticle?mini=true&url=' . $share_url . '&title=' . $share_title . '" target="_blank" rel="noopener noreferrer" class="linkedin-share"><span class="screen-reader-text">LinkedIn</span><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 8C17.5913 8 19.1174 8.63214 20.2426 9.75736C21.3679 10.8826 22 12.4087 22 14V21H18V14C18 13.4696 17.7893 12.9609 17.4142 12.5858C17.0391 12.2107 16.5304 12 16 12C15.4696 12 14.9609 12.2107 14.5858 12.5858C14.2107 12.9609 14 13.4696 14 14V21H10V14C10 12.4087 10.6321 10.8826 11.7574 9.75736C12.8826 8.63214 14.4087 8 16 8V8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 9H2V21H6V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 6C5.10457 6 6 5.10457 6 4C6 2.89543 5.10457 2 4 2C2.89543 2 2 2.89543 2 4C2 5.10457 2.89543 6 4 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a></li>';
    echo '</ul>';
    echo '</div>';
}

/**
 * Display CTA block for simulator
 */
function mdmc_simulator_cta() {
    echo '<div class="simulator-cta">';
    echo '<h3 data-i18n="simulator_title">' . esc_html(mdmc_get_translation('simulator_title')) . '</h3>';
    echo '<p data-i18n="simulator_text">' . esc_html(mdmc_get_translation('simulator_text')) . '</p>';
    echo '<a href="' . esc_url(home_url('/simulateur/')) . '" class="btn btn-primary" data-i18n="simulator_button">' . esc_html(mdmc_get_translation('simulator_button')) . '</a>';
    echo '</div>';
}
