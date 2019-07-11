<?php
/**
 * The template for displaying archive pages.
 *
 * @package WPFelix
 */

get_header(); ?>
<div class="grid-container">
    <div class="grid">
        <div id="primary" class="content-area<?php echo esc_attr( wpfelix_content_sidebar_class( 'primary' ) ); ?>">
            <main id="main" class="site-main" role="main">
            <?php
            if ( have_posts() )
            {
                if ( get_theme_mod( 'posts_first_archive', true ) )
                {
                    while ( have_posts() )
                    {
                        the_post();
                        get_template_part( 'template-parts/loop-post-full', get_post_format() );
                        break;
                    }
                }

                switch ( get_theme_mod( 'posts_layout_archive' ) )
                {
                    case 'full':
                        echo '<div class="posts-container posts-full-container">';
                        while ( have_posts() )
                        {
                            the_post();
                            get_template_part( 'template-parts/loop-post-full', get_post_format() );
                        }
                        echo '</div>';
                        break;

                    case 'grid':
                        echo '<div class="posts-container posts-grid-container">';
                        while ( have_posts() )
                        {
                            the_post();
                            get_template_part( 'template-parts/loop-post-grid', get_post_format() );
                        }
                        echo '</div>';
                        break;

                    case 'grid_full':
                        echo '<div class="posts-container posts-grid-container">';
                        while ( have_posts() )
                        {
                            the_post();
                            get_template_part( 'template-parts/loop-post-gridf', get_post_format() );
                        }
                        echo '</div>';
                        break;

                    case 'list':
                        echo '<div class="posts-container posts-list-container">';
                        while ( have_posts() )
                        {
                            the_post();
                            get_template_part( 'template-parts/loop-post-list', get_post_format() );
                        }
                        echo '</div>';
                        break;

                    case 'list_odd_even':
                        echo '<div class="posts-container posts-list-oe-container">';
                        while ( have_posts() )
                        {
                            the_post();
                            get_template_part( 'template-parts/loop-post-list-oe', get_post_format() );
                        }
                        echo '</div>';
                        break;

                    default:
                        echo '<div class="posts-container posts-list-container">';
                        while ( have_posts() )
                        {
                            the_post();
                            get_template_part( 'template-parts/loop-post-list', get_post_format() );
                        }
                        echo '</div>';
                        break;
                }

                switch ( get_theme_mod( 'posts_nav_type', 'default' ) )
                {
                    case 'pages':
                        wpfelix_posts_pagination();
                        break;
                    
                    default:
                        the_posts_navigation();
                        break;
                }
            }
            else
            {
                get_template_part( 'template-parts/content', 'none' );
            }

            ?>

            </main><!-- #main -->
        </div><!-- #primary -->
        <?php if ( ! get_theme_mod( 'sidebar_archive_disabled' ) ) : ?>
        <aside id="secondary" class="widget-area<?php echo esc_attr( wpfelix_content_sidebar_class( 'secondary' ) ); ?>" role="complementary">
            <?php get_sidebar(); ?>
        </aside><!-- #secondary -->
        <?php endif; ?>
    </div>
</div>
<?php
get_footer();
