<?php
/**
 * The template for displaying search results pages.
 *
 * @package WPFelix
 */
get_header(); ?>
<div class="grid-container">
    <div class="grid">
        <section id="primary" class="content-area<?php echo esc_attr( wpfelix_content_sidebar_class( 'primary' ) ); ?>">
            <main id="main" class="site-main" role="main">
            <?php
            
            if ( have_posts() )
            {
                /* Start the Loop */
                while ( have_posts() )
                {
                    the_post();
                    get_template_part( 'template-parts/loop', 'search' );

                }

                the_posts_navigation();

            }
            else
            {
                get_template_part( 'template-parts/content', 'none' );

            }

            ?>
            </main><!-- #main -->
        </section><!-- #primary -->
        <?php if ( ! get_theme_mod( 'sidebar_search_disabled' ) ) : ?>
        <aside id="secondary" class="widget-area<?php echo esc_attr( wpfelix_content_sidebar_class( 'secondary' ) ); ?>" role="complementary">
            <?php get_sidebar(); ?>
        </aside><!-- #secondary -->
        <?php endif; ?>
    </div>
</div>
<?php
get_footer();
