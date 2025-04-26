<?php
/**
 * The template for displaying archive pages
 *
 * @package MDMC_Theme
 */

get_header();
?>

<main id="primary" class="site-main">

    <section class="hero">
        <div class="container">
            <h1 class="page-title">
                <?php
                if ( is_category() ) {
                    single_cat_title();
                } elseif ( is_tag() ) {
                    single_tag_title();
                } elseif ( is_author() ) {
                    the_post();
                    printf( esc_html__( 'Author: %s', 'mdmc-theme' ), '<span class="vcard">' . get_the_author() . '</span>' );
                    rewind_posts();
                } elseif ( is_year() ) {
                    printf( esc_html__( 'Year: %s', 'mdmc-theme' ), get_the_date( _x( 'Y', 'yearly archives date format', 'mdmc-theme' ) ) );
                } elseif ( is_month() ) {
                    printf( esc_html__( 'Month: %s', 'mdmc-theme' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'mdmc-theme' ) ) );
                } elseif ( is_day() ) {
                    printf( esc_html__( 'Day: %s', 'mdmc-theme' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'mdmc-theme' ) ) );
                } elseif ( is_post_type_archive() ) {
                    post_type_archive_title();
                } elseif ( is_tax() ) {
                    single_term_title();
                } else {
                    esc_html_e( 'Archives', 'mdmc-theme' );
                }
                ?>
            </h1>
            
            <?php
            the_archive_description( '<p class="archive-description">', '</p>' );
            ?>
            
            <?php get_search_form(); ?>
        </div>
    </section>

    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="blog-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/content/content', 'card' );
                endwhile;
                ?>
            </div>
            
            <?php
            the_posts_pagination( array(
                'prev_text' => '&larr; ' . mdmc_get_translation('pagination_prev'),
                'next_text' => mdmc_get_translation('pagination_next') . ' &rarr;',
            ) );
            
        else :
            get_template_part( 'template-parts/content/content', 'none' );
        endif;
        ?>
    </div>

</main><!-- #primary -->

<?php
get_footer();
