<?php
/**
 * Template part for displaying post cards in grid
 *
 * @package MDMC_Theme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('blog-card'); ?>>
    <?php mdmc_post_thumbnail(); ?>
    
    <div class="card-content">
        <?php mdmc_category_badge(); ?>
        
        <header class="entry-header">
            <h2 class="entry-title">
                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
            </h2>
        </header>
        
        <div class="entry-summary">
            <?php
            $excerpt = get_the_excerpt();
            $excerpt = substr($excerpt, 0, 150);
            $excerpt = substr($excerpt, 0, strrpos($excerpt, ' '));
            echo $excerpt . '...';
            ?>
        </div>
        
        <footer class="entry-footer">
            <a href="<?php the_permalink(); ?>" class="read-more">
                <?php echo esc_html(mdmc_get_translation('read_more')); ?> →
            </a>
        </footer>
    </div>
</article>
