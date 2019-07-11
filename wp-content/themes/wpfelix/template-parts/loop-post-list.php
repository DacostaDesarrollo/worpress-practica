<?php
/**
 * Template part for displaying posts list layout.
 *
 * @package WPFelix
 */
$excerpt_type = get_theme_mod( 'posts_summary', 'custom-excerpt' );
$excerpt_length = intval( get_theme_mod( 'posts_excerpt_length', 44 ) );

$excerpt_length = ( $excerpt_length < 10 || $excerpt_length > 1000 ) ? 44 : $excerpt_length;

$icon_support = get_theme_mod( 'post_format_icon', true );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-list' . ( $icon_support ? ' icon-support' : '' ) ); ?>>
    <div class="entry-featured">
    <?php
        if ( is_home() && is_sticky() && ! is_paged() )
        {
            echo '<span class="entry-sticky-icon"></span>';
        }
        if ( $icon_support )
        {
            echo '<span class="entry-icon"></span>';
        }
        if ( has_post_thumbnail() )
        {
            the_post_thumbnail( 'medium' );
        }
    ?>
    </div>
    <div class="entry-brief">
        <header class="entry-header">
            <?php
            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );

            if ( 'post' === get_post_type() )
            {
                wpfelix_entry_meta();
            }
            ?>
        </header><!-- .entry-header -->

        <div class="entry-summary">
            <?php
            switch ( $excerpt_type )
            {
                case 'custom-excerpt':
                    wpfelix_the_excerpt( $excerpt_length );
                    break;
                
                default:
                    the_excerpt();
                    break;
            }
            ?>
        </div><!-- .entry-summary -->
    </div>
</article><!-- #post-## -->