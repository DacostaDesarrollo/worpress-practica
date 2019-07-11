<?php
/**
 * Template part for displaying a grid styled Featured Slider.
 *
 * @package WPFelix
 */

$fcat = get_theme_mod( 'featured_category', 0 );
$count = get_theme_mod( 'featured_slides_num', 4 );

$count = intval( $count );
$count = ( $count > 1 && $count < 100 ) ? $count : 4;

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

        get_template_part( 'template-parts/loop-slide-post', 'full-grid' );
    }
    $output = ob_get_clean();

    printf(
        '<div class="carousel full-grid-banner owl-carousel" data-theme-carousel="true" data-options="%1$s">%2$s</div>',
        esc_attr(
            '{
                "dots":false,
                "nav":true,
                "responsive":{
                    "0":{"items":1},
                    "768":{"items":2},
                    "1230":{"items":3}
                }
            }'
        ),
        $output
    );
}
