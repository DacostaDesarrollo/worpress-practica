<?php
/**
 * The template for displaying all single posts.
 *
 * @package WPFelix
 */

get_header(); ?>
<div class="grid-container">
    <div class="grid">
        <div id="primary" class="content-area<?php echo esc_attr( wpfelix_content_sidebar_class( 'primary' ) ); ?>">
            <main id="main" class="site-main" role="main">

            <?php
            while ( have_posts() ) : the_post();

                get_template_part( 'template-parts/content-post', get_post_format() );

                the_post_navigation();

                if ( 'post' == get_post_type() )
                {
                    get_template_part( 'template-parts/content-post', 'author' );
                    get_template_part( 'template-parts/content-post', 'related' );
                }

                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;

            endwhile; // End of the loop.
            ?>

            </main><!-- #main -->
        </div><!-- #primary -->
        <?php if ( 'default' == get_theme_mod( 'layout_post', 'default' ) && ! get_theme_mod( 'sidebar_post_disabled' ) ) : ?>
        <aside id="secondary" class="widget-area<?php echo esc_attr( wpfelix_content_sidebar_class( 'secondary' ) ); ?>" role="complementary">
            <?php get_sidebar(); ?>
        </aside><!-- #secondary -->
        <?php endif; ?>
    </div>
</div>
<?php
get_footer();
