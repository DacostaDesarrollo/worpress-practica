<?php
/**
 * Template part for displaying a boxed slider with fading slides on both side.
 *
 * @package WPFelix
 */

$fcat = get_theme_mod( 'featured_category', 0 );
$count = get_theme_mod( 'featured_slides_num', 4 );

$count = intval( $count );
$count = ( $count > 1 && $count < 10 ) ? $count : 4;

$fcat = intval( $fcat );

if ( ! $fcat || $fcat < 0 )
{
    return;
}

$squery = new WP_Query( array(
    'post_type'         => 'post',
    'post_status'       => 'publish',
    'cat'               => $fcat,
    'posts_per_page'    => $count
) );

if ( $squery->have_posts() )
{
    ob_start();
    while ( $squery->have_posts() )
    {
        $squery->the_post();

        if ( is_sticky() )
        {
            continue;
        }

        get_template_part( 'template-parts/loop-slide-post', 'full' );
    }
    $output = ob_get_clean();

    printf(
        '<div class="grid-container">
            <div class="carousel full-box-banner owl-carousel" data-theme-carousel="true" data-options="%1$s" data-full-carousel="true">%2$s</div>
        </div>',
        esc_attr( '{"items":1,"dots":false,"nav":true,"autoHeight":false,"loop":true}' ),
        $output
    );
}
