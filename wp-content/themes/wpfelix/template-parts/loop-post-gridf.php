
<?php
/**
 * Template part for displaying posts grid full layout.
 *
 * @package WPFelix
 */
$excerpt_type = get_theme_mod( 'posts_summary', 'custom-excerpt' );
$excerpt_length = intval( get_theme_mod( 'posts_excerpt_length', 44 ) );

$excerpt_length = ( $excerpt_length < 10 || $excerpt_length > 1000 ) ? 44 : $excerpt_length;

$icon_support = get_theme_mod( 'post_format_icon', true );
$grid_col = get_theme_mod( 'posts_layout_grid', 2 );

$post_class = 'post-full post-full-grid';

if ( $icon_support )
{
    $post_class .= ' icon-support';
}

switch ( $grid_col ) {
    case 3:
        $post_class .= ' post-grid-c3';
        break;
    
    default:
        $post_class .= ' post-grid-c2';
        break;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( esc_attr( $post_class ) ); ?>>
    <header class="entry-header">
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
        the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );

        if ( 'post' === get_post_type() )
        {
            wpfelix_entry_meta();
        }
        ?>
    </header><!-- .entry-header -->
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
        the_post_thumbnail( 'large' );
        echo '</div>';
    }
    ?>
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
    <footer class="entry-footer">
        <?php wpfelix_entry_footer(); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-## -->