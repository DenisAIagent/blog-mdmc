<?php
/**
 * Template for displaying single posts
 *
 * @package MDMC_Theme
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('single-article'); ?>>
            <div class="container">
                <header class="entry-header">
                    <?php mdmc_category_badge(); ?>
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <div class="entry-meta">
                        <?php mdmc_posted_on(); ?>
                    </div>
                </header>

                <?php mdmc_post_thumbnail(); ?>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <footer class="entry-footer">
                    <?php mdmc_entry_footer(); ?>
                </footer>

                <?php mdmc_simulator_cta(); ?>
                
                <div class="post-navigation-container">
                    <?php
                    the_post_navigation(
                        array(
                            'prev_text' => '<span class="nav-subtitle">' . mdmc_get_translation('previous_post') . '</span> <span class="nav-title">%title</span>',
                            'next_text' => '<span class="nav-subtitle">' . mdmc_get_translation('next_post') . '</span> <span class="nav-title">%title</span>',
                        )
                    );
                    ?>
                </div>
            </div>
            
            <div class="social-sharing-sidebar">
                <?php mdmc_social_sharing(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main><!-- #primary -->

<?php
get_footer();
