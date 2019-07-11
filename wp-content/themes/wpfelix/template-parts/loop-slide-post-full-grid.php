<?php
/**
 * Template part for displaying full-grid slider post item.
 *
 * @package WPFelix
 */
$thumbnail = '';

if ( has_post_thumbnail() )
{
    $thumbnail = get_the_post_thumbnail_url( null, 'medium' );
}
?>
<article
    <?php post_class( 'slider-item-full-grid' ); ?>
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
        </div>
    </div>
</article>