<?php
/**
 * Template part for displaying results in search pages.
 *
 * @package WPFelix
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-search' ); ?>>
    <header class="entry-header">
        <?php

        the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );

        ?>
    </header><!-- .entry-header -->

    <div class="entry-summary"> 
        <?php

        printf( '<p class="result-link"><a href="%1$s"><em>%2$s</em></a></p>', esc_url( get_permalink() ), esc_url( get_permalink() ) );
        the_excerpt();
        ?>
    </div><!-- .entry-summary -->
</article><!-- #post-## -->