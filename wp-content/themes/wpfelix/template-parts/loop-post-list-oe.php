<?php
/**
 * Template part for displaying posts grid layout.
 *
 * @package WPFelix
 */
$excerpt_type = get_theme_mod( 'posts_summary', 'custom-excerpt' );
$excerpt_length = intval( get_theme_mod( 'posts_excerpt_length', 44 ) );

$excerpt_length = ( $excerpt_length < 10 || $excerpt_length > 1000 ) ? 44 : $excerpt_length;

$icon_support = get_theme_mod( 'post_format_icon', true );

$post_class = 'post-list-oe';

if ( $wp_query->current_post %2 == 0 ) {
    $post_class .= ' even';
}
else {
    $post_class .= ' odd';
}

if ( $icon_support )
{
    $post_class .= ' icon-support';
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( esc_attr( $post_class ) ); ?>>
    <?php
    if ( has_post_thumbnail() )
    {
        echo '<div class="entry-featured">';
        if ( is_home() && is_sticky() && ! is_paged() )
        {
            echo '<span class="entry-sticky-icon"></span>';
        }
        if ( $icon_support )
        {
            echo '<span class="entry-icon"></span>';
        }
        the_post_thumbnail( 'medium' );
        echo '</div>';
    }
    ?>
    <div class="entry-brief">
        <?php
        if ( ! has_post_thumbnail() )
        {
            if ( is_home() && is_sticky() && ! is_paged() )
            {
                echo '<span class="entry-sticky-icon"></span>';
            }
            if ( $icon_support )
            {
                echo '<span class="entry-icon"></span>';
            }
        }
        ?>
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
            printf(
                '<div class="entry-read-more"><a class="button button-default" href="%1$s">%2$s</a></div>',
                esc_url( get_permalink() ),
                apply_filters( 'wpfelix_read_more_text', esc_html__( 'Read More', 'wpfelix' ) )
            );
            ?>
        </div><!-- .entry-summary -->
    </div><!-- .entry-brief -->
</article><!-- #post-## -->