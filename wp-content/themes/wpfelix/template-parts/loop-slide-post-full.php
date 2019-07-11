<?php
/**
 * Template part for displaying full with slider post item.
 *
 * @package WPFelix
 */
$thumbnail = '';

if ( has_post_thumbnail() )
{
    $thumbnail = get_the_post_thumbnail_url( null, 'full' );
}
?>
<article
    <?php post_class( 'slider-item-full' ); ?>
    <?php echo ( $thumbnail ? ' style="background-image:url(' . esc_url( $thumbnail ) . ');"' : '' ); ?>>
    <div class="item-inner">
        <div class="item-content">
            <header class="item-header">
                <?php
                    if ( 'post' === get_post_type() ) :
                        wpfelix_entry_meta();
                    endif;

                    the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                ?>
            </header>
            <div class="item-summary">
                <?php
                    $excerpt_length = get_theme_mod( 'featured_excerpt_length', '18' );

                    if ( 0 < $excerpt_length )
                    {
                        echo wpfelix_the_excerpt( $excerpt_length );
                    }
                    
                    $btn = get_theme_mod( 'featured_button_style', 'default' );
                    printf(
                        '<div class="item-more">
                            <a href="%1$s" title="%2$s" class="button %3$s">%4$s</a>
                        </div>',
                        esc_url( get_permalink() ),
                        esc_attr( get_the_title() ),
                        ( 'default' == $btn ? ' button-default' : ' button-alt' ),
                        esc_html__( 'Read More', 'wpfelix' )
                    )
                ?>
            </div>
        </div>
    </div>
</article>