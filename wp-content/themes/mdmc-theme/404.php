<?php
/**
 * Template for displaying 404 pages (not found)
 *
 * @package MDMC_Theme
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <section class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title"><?php echo esc_html(mdmc_get_translation('404_title')); ?></h1>
            </header><!-- .page-header -->

            <div class="page-content">
                <p><?php echo esc_html(mdmc_get_translation('404_text')); ?></p>

                <div class="error-actions">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                        <?php echo esc_html(mdmc_get_translation('back_to_home')); ?>
                    </a>
                </div>

                <div class="search-container">
                    <h2><?php echo esc_html(mdmc_get_translation('search_title')); ?></h2>
                    <?php get_search_form(); ?>
                </div>
            </div><!-- .page-content -->
        </section><!-- .error-404 -->
    </div>
</main><!-- #primary -->

<?php
get_footer();
