<?php
/**
 * The main template file
 *
 * @package MDMC_Theme
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="hero-blog">
        <div class="container">
            <h1 class="hero-title"><?php echo esc_html(mdmc_get_translation('blog_title')); ?></h1>
            <p class="hero-subtitle"><?php echo esc_html(mdmc_get_translation('blog_subtitle')); ?></p>
            
            <div class="search-container">
                <?php get_search_form(); ?>
            </div>
            
            <div class="category-filters">
                <?php
                $categories = get_categories(array(
                    'orderby' => 'name',
                    'order'   => 'ASC',
                    'hide_empty' => true,
                ));
                
                if (!empty($categories)) :
                    echo '<ul class="category-pills">';
                    echo '<li><a href="' . esc_url(home_url('/')) . '" class="category-pill active">' . esc_html(mdmc_get_translation('all_categories')) . '</a></li>';
                    
                    foreach ($categories as $category) :
                        echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-pill">' . esc_html($category->name) . '</a></li>';
                    endforeach;
                    
                    echo '</ul>';
                endif;
                ?>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (have_posts()) : ?>
            <div class="blog-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content/content', 'card');
                endwhile;
                ?>
            </div>

            <?php
            the_posts_pagination(array(
                'prev_text' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg><span class="screen-reader-text">' . mdmc_get_translation('previous') . '</span>',
                'next_text' => '<span class="screen-reader-text">' . mdmc_get_translation('next') . '</span><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 5L19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            ));
            ?>

        <?php else : ?>
            <div class="no-results">
                <h2><?php echo esc_html(mdmc_get_translation('no_posts_found')); ?></h2>
                <p><?php echo esc_html(mdmc_get_translation('no_posts_message')); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main><!-- #primary -->

<?php
get_footer();
