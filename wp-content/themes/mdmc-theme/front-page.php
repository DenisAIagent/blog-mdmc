<?php
/**
 * The template for displaying the front page
 *
 * @package MDMC_Theme
 */

get_header();
?>

<main id="primary" class="site-main">

    <section class="hero">
        <div class="container">
            <h1><?php echo mdmc_get_translation('hero_title'); ?></h1>
            <p><?php echo mdmc_get_translation('hero_description'); ?></p>
            
            <?php get_search_form(); ?>
            
            <div class="categories">
                <div class="category-pill active"><?php echo mdmc_get_translation('category_all'); ?></div>
                <?php
                $categories = get_categories( array(
                    'orderby' => 'name',
                    'order'   => 'ASC',
                    'number'  => 5,
                ) );
                
                foreach ( $categories as $category ) {
                    printf(
                        '<div class="category-pill" data-category="%1$s">%2$s</div>',
                        esc_attr( $category->slug ),
                        esc_html( $category->name )
                    );
                }
                ?>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="blog-grid">
            <?php
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => 6,
            );
            
            $query = new WP_Query( $args );
            
            if ( $query->have_posts() ) :
                while ( $query->have_posts() ) :
                    $query->the_post();
                    get_template_part( 'template-parts/content/content', 'card' );
                endwhile;
                wp_reset_postdata();
            else :
                get_template_part( 'template-parts/content/content', 'none' );
            endif;
            ?>
        </div>
    </div>

    <section class="cta-section">
        <div class="container">
            <h2><?php echo mdmc_get_translation('cta_title'); ?></h2>
            <p><?php echo mdmc_get_translation('cta_description'); ?></p>
            <a href="#simulator" class="cta-button"><?php echo mdmc_get_translation('simulator_button'); ?></a>
        </div>
    </section>

    <section id="simulator" class="simulator-section">
        <div class="container">
            <h2><?php echo mdmc_get_translation('simulator_title'); ?></h2>
            <p><?php echo mdmc_get_translation('simulator_description'); ?></p>
            
            <div class="simulator-form">
                <form id="cost-simulator" action="#" method="post">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="platform"><?php echo mdmc_get_translation('simulator_platform_label'); ?></label>
                            <select id="platform" name="platform" class="form-control">
                                <option value="spotify"><?php echo mdmc_get_translation('simulator_platform_spotify'); ?></option>
                                <option value="youtube"><?php echo mdmc_get_translation('simulator_platform_youtube'); ?></option>
                                <option value="facebook"><?php echo mdmc_get_translation('simulator_platform_facebook'); ?></option>
                                <option value="instagram"><?php echo mdmc_get_translation('simulator_platform_instagram'); ?></option>
                                <option value="tiktok"><?php echo mdmc_get_translation('simulator_platform_tiktok'); ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="budget"><?php echo mdmc_get_translation('simulator_budget_label'); ?></label>
                            <input type="number" id="budget" name="budget" class="form-control" min="100" step="100" value="1000">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="duration"><?php echo mdmc_get_translation('simulator_duration_label'); ?></label>
                            <select id="duration" name="duration" class="form-control">
                                <option value="7"><?php echo mdmc_get_translation('simulator_duration_7'); ?></option>
                                <option value="14"><?php echo mdmc_get_translation('simulator_duration_14'); ?></option>
                                <option value="30" selected><?php echo mdmc_get_translation('simulator_duration_30'); ?></option>
                                <option value="60"><?php echo mdmc_get_translation('simulator_duration_60'); ?></option>
                                <option value="90"><?php echo mdmc_get_translation('simulator_duration_90'); ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="target"><?php echo mdmc_get_translation('simulator_target_label'); ?></label>
                            <select id="target" name="target" class="form-control">
                                <option value="general"><?php echo mdmc_get_translation('simulator_target_general'); ?></option>
                                <option value="specific"><?php echo mdmc_get_translation('simulator_target_specific'); ?></option>
                                <option value="niche"><?php echo mdmc_get_translation('simulator_target_niche'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="objective"><?php echo mdmc_get_translation('simulator_objective_label'); ?></label>
                        <select id="objective" name="objective" class="form-control">
                            <option value="awareness"><?php echo mdmc_get_translation('simulator_objective_awareness'); ?></option>
                            <option value="streams"><?php echo mdmc_get_translation('simulator_objective_streams'); ?></option>
                            <option value="followers"><?php echo mdmc_get_translation('simulator_objective_followers'); ?></option>
                            <option value="engagement"><?php echo mdmc_get_translation('simulator_objective_engagement'); ?></option>
                            <option value="conversion"><?php echo mdmc_get_translation('simulator_objective_conversion'); ?></option>
                        </select>
                    </div>
                    
                    <div class="form-group text-center mt-4">
                        <button type="submit" class="cta-button"><?php echo mdmc_get_translation('simulator_calculate_button'); ?></button>
                    </div>
                </form>
                
                <div class="simulator-results" style="display: none;">
                    <div class="form-row">
                        <div class="form-group">
                            <div class="result-card">
                                <h4><?php echo mdmc_get_translation('simulator_result_reach'); ?></h4>
                                <div class="result-value" id="result-reach">0</div>
                                <p><?php echo mdmc_get_translation('simulator_result_reach_description'); ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="result-card">
                                <h4><?php echo mdmc_get_translation('simulator_result_engagement'); ?></h4>
                                <div class="result-value" id="result-engagement">0</div>
                                <p><?php echo mdmc_get_translation('simulator_result_engagement_description'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <div class="result-card">
                                <h4><?php echo mdmc_get_translation('simulator_result_cost'); ?></h4>
                                <div class="result-value" id="result-cost">0€</div>
                                <p><?php echo mdmc_get_translation('simulator_result_cost_description'); ?></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="result-card">
                                <h4><?php echo mdmc_get_translation('simulator_result_roi'); ?></h4>
                                <div class="result-value" id="result-roi">0%</div>
                                <p><?php echo mdmc_get_translation('simulator_result_roi_description'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main><!-- #primary -->

<?php
get_footer();
