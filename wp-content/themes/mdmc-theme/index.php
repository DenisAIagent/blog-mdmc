<?php get_header(); ?>

<main id="main-content">

    <?php if ( is_home() && ! is_front_page() ) : ?>
        <header class="page-header">
            <h1 class="page-title"><?php single_post_title(); ?></h1>
        </header>
    <?php endif; ?>

    <?php if ( have_posts() ) : ?>
        <section id="articles" class="articles" style="padding-top: 4rem;">
            <div class="container">

                <div class="articles-grid">
                    <?php while ( have_posts() ) : the_post(); ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class('article-card'); ?>>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                    <?php the_post_thumbnail('medium_large', ['class' => 'article-image', 'loading' => 'lazy']); ?>
                                </a>
                            <?php else : ?>
                                <div class="article-image-placeholder"></div>
                            <?php endif; ?>

                            <div class="article-content">
                                <?php
                                $categories = get_the_category();
                                if ( ! empty( $categories ) ) {
                                    echo '<span class="article-category">' . esc_html( $categories[0]->name ) . '</span>';
                                }
                                ?>
                                <h3 class="article-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <span class="article-date"><?php echo get_the_date(); ?></span>
                                <div class="article-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-small"><?php _e('Lire la suite', 'mdmc-theme'); ?></a>
                            </div>
                        </article>

                    <?php endwhile; ?>
                </div>

                <?php the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => __( 'Précédent', 'mdmc-theme' ),
                    'next_text' => __( 'Suivant', 'mdmc-theme' ),
                ) ); ?>

            </div>
        </section>

    <?php else : ?>
        <section class="no-results">
            <div class="container">
                <h2><?php _e('Rien à afficher', 'mdmc-theme'); ?></h2>
                <p><?php _e('Il semble que nous ne trouvions pas ce que vous cherchez. Peut-être essayer une recherche ?', 'mdmc-theme'); ?></p>
                <?php get_search_form(); ?>
            </div>
        </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>