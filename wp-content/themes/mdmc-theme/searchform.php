<?php
/**
 * Template part for displaying search form
 *
 * @package MDMC_Theme
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text"><?php echo esc_html(mdmc_get_translation('search_for')); ?></label>
    <div class="search-input-container">
        <input type="search" class="search-field" placeholder="<?php echo esc_attr(mdmc_get_translation('search_placeholder')); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        <button type="submit" class="search-submit">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="screen-reader-text"><?php echo esc_html(mdmc_get_translation('search')); ?></span>
        </button>
    </div>
</form>
