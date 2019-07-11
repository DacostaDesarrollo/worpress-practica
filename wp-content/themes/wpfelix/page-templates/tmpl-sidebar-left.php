<?php
/**
 * Template name: Sidebar Left
 *
 * Full width Page template
 * @package WPFelix
 */

get_header();

$sidebar_size = get_theme_mod( 'sidebar_size', 'wide' );

$primary_class = ' with-sidebar with-sidebar-left';
$sidebar_class = ' sidebar-left';

if ( 'narrow' === $sidebar_size )
{
    $primary_class .= ' with-sidebar-narrow grid-l9';
    $sidebar_class .= ' sidebar-narrow grid-l3';
}
else
{
    $primary_class .= ' grid-l8 grid-push-l4';
    $sidebar_class .= ' grid-l4 grid-pull-l8';
}
$sidebar_class .= ' grid-m8 grid-offset-m2 grid-offset-l0';

?>
<div class="grid-container">
    <div class="grid">
        <div id="primary" class="content-area<?php echo esc_attr( $primary_class ); ?>">
            <main id="main" class="site-main" role="main">
                <?php
                while ( have_posts() ) : the_post();

                    get_template_part( 'template-parts/content', 'page' );

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;

                endwhile; // End of the loop.
                ?>

            </main><!-- #main -->
        </div><!-- #primary -->
        <aside id="secondary" class="widget-area<?php echo esc_attr( $sidebar_class ); ?>" role="complementary">
            <?php get_sidebar( 'page' ); ?>
        </aside><!-- #secondary -->
    </div>
</div>
<?php
get_footer();
