<?php // --- Section des Articles Récents --- ?>
    <section id="articles-recents" class="articles" style="padding-top: 4rem; background-color: #0F0F0F;"> <?php // Adaptez le style/ID si besoin ?>
        <div class="container">
            <h2 class="section-title" style="color: var(--color-text);"><?php _e('Nos Derniers Articles', 'mdmc-theme'); // Titre de la section ?></h2>

            <?php
            // Configuration de la requête pour chercher les articles
            $args = array(
                'post_type'      => 'post',       // On veut des articles de blog
                'posts_per_page' => 3,            // Combien d'articles afficher ? Mettez -1 pour tous ou un autre chiffre.
                'post_status'    => 'publish',    // Uniquement les articles publiés
                'orderby'        => 'date',       // Trier par date
                'order'          => 'DESC',       // Du plus récent au plus ancien
            );

            // Exécution de la requête personnalisée
            $recent_posts_query = new WP_Query( $args );
            ?>

            <?php if ( $recent_posts_query->have_posts() ) : // Vérifie s'il y a des articles ?>
                <div class="articles-grid">

                    <?php while ( $recent_posts_query->have_posts() ) : $recent_posts_query->the_post(); // La boucle ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class('article-card'); // Utilise le style de carte d'article de votre CSS ?>>

                            <?php if ( has_post_thumbnail() ) : // Vérifie s'il y a une image à la une ?>
                                <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                    <?php the_post_thumbnail('medium_large', ['class' => 'article-image', 'loading' => 'lazy']); // Affiche l'image ?>
                                </a>
                            <?php else : ?>
                                <div class="article-image-placeholder"></div> <?php // Affiche un placeholder si pas d'image ?>
                            <?php endif; ?>

                            <div class="article-content">
                                <?php
                                // Affiche la première catégorie de l'article
                                $categories = get_the_category();
                                if ( ! empty( $categories ) ) : ?>
                                    <span class="article-category"><?php echo esc_html( $categories[0]->name ); ?></span>
                                <?php endif; ?>

                                <h3 class="article-title"><a href="<?php the_permalink(); ?>"><?php the_title(); // Affiche le titre de l'article ?></a></h3>
                                <span class="article-date"><?php echo get_the_date(); // Affiche la date ?></span>
                                <div class="article-excerpt">
                                    <?php the_excerpt(); // Affiche l'extrait ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-small" style="font-size: 0.875rem; padding: 0.6rem 1.2rem;"><?php _e('Lire la suite', 'mdmc-theme'); // Bouton Lire la suite ?></a>
                            </div>
                        </article>

                    <?php endwhile; // Fin de la boucle ?>

                </div><?php
                 // Optionnel : Ajouter un bouton "Voir tous les articles" qui mène à la page de blog
                 $blog_page_url = get_permalink( get_option( 'page_for_posts' ) ); // Récupère l'URL de la page de blog définie dans Réglages > Lecture
                 if ($blog_page_url) :
                ?>
                <div class="articles-cta" style="text-align: center; margin-top: 3rem;">
                    <a href="<?php echo esc_url($blog_page_url); ?>" class="btn btn-secondary"><?php _e('Voir tous les articles', 'mdmc-theme'); ?></a>
                </div>
                <?php endif; ?>

            <?php else : // S'il n'y a aucun article ?>
                <p><?php _e('Aucun article à afficher pour le moment.', 'mdmc-theme'); ?></p>
            <?php endif; ?>

            <?php wp_reset_postdata(); // Important après une boucle WP_Query personnalisée ?>

        </div></section><?php // --- Fin Section des Articles Récents --- ?>
