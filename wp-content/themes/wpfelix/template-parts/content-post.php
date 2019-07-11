<?php
/**
 * Template part for displaying single post content.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPFelix
 */

$single_layout = get_theme_mod( 'layout_post', 'default' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-layout-' . esc_attr( $single_layout ) ); ?>>
    <header class="entry-header">
        <?php
        the_title( '<h2 class="entry-title">', '</h2>' );

        if ( 'post' === get_post_type() ) :
            wpfelix_entry_meta();
        endif; ?>
    </header><!-- .entry-header -->
    <div class="entry-content">
        <?php
            if ( has_post_thumbnail() )
            {
                echo '<div class="entry-featured">';
                if ( 'full' === get_theme_mod( 'layout_post' ) || '1' === get_theme_mod( 'sidebar_post_disabled' ) )
                {
                    the_post_thumbnail();
                }
                else
                {
                    wpfelix_the_post_thumbnail_caption( 'large' );
                    //the_post_thumbnail( 'large' );
                }
                echo '</div>';
            }
            the_content();
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php wpfelix_entry_footer(); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-## -->
