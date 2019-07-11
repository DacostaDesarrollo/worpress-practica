<?php
/**
 * Jetpack Compatibility File.
 *
 * @link https://jetpack.com/
 *
 * @package WPFelix
 */


/**
 * Jetpack infinite scroll setup function.
 *
 * See: https://jetpack.com/support/infinite-scroll/
 * See: https://jetpack.com/support/responsive-videos/
 */
function wpfelix_jetpack_setup()
{
    // Add theme support for Infinite Scroll.
    add_theme_support( 'infinite-scroll', apply_filters( 'wpfelix_jetpack_infinite_scroll_args', array(
        'container' => 'main',
        'render'    => 'wpfelix_jetpack_infinite_scroll_render',
        'posts_per_page' => get_option( 'posts_per_page', 10 ),
        'footer'    => false,
    ) ) );
    // Add theme support for Responsive Videos.
    add_theme_support( 'jetpack-responsive-videos' );
}
add_action( 'after_setup_theme', 'wpfelix_jetpack_setup' );


/**
 * Custom render function for Infinite Scroll.
 */
function wpfelix_jetpack_infinite_scroll_render()
{
    $posts_layout = '';

    if ( is_home() )
    {
        $posts_layout = get_theme_mod( 'posts_layout_home', 'full' );
    }
    elseif ( is_archive() )
    {
        $posts_layout = get_theme_mod( 'posts_layout_archive', 'list' );
    }
    else
    {
        $posts_layout = '';
    }

    // Search maybe
    if ( '' == $posts_layout )
    {
        while ( have_posts() )
        {
            the_post();
            get_template_part( 'template-parts/loop', 'search' );
        }
    }
    else
    {
        if ( get_theme_mod( 'posts_first_home', true ) )
        {
            while ( have_posts() )
            {
                the_post();
                get_template_part( 'template-parts/loop-post-full', get_post_format() );
                break;
            }
        }

        switch ( $posts_layout )
        {
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
                echo '<div class="posts-container posts-full-container">';
                while ( have_posts() )
                {
                    the_post();
                    get_template_part( 'template-parts/loop-post-full', get_post_format() );
                }
                echo '</div>';
                break;
        }
    }
}
