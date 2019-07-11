<?php
/**
 * Template part for displaying posts full layout with Gallery Format.
 *
 * @package WPFelix
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-full' ); ?>>
    <header class="entry-header">
        <?php
        if ( is_home() && is_sticky() && ! is_paged() )
        {
            echo '<span class="entry-sticky-icon"></span>';
        }
        the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );

        if ( 'post' === get_post_type() )
        {
            wpfelix_entry_meta();
        }
        ?>
    </header><!-- .entry-header -->
    <div class="entry-summary">
        <?php
            the_content(
                sprintf(
                    /* translators: %s: Name of current post. */
                    wp_kses( __( 'Continue reading%s<span class="meta-nav">...</span>', 'wpfelix' ), array( 'span' => array( 'class' => array() ) ) ),
                    the_title( '<span class="screen-reader-text"> "', '"</span>', false )
                )
            );
        ?>
    </div><!-- .entry-summary -->

    <footer class="entry-footer">
        <?php wpfelix_entry_footer(); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-## -->
