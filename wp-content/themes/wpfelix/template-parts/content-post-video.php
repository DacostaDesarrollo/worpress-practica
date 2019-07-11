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
            the_content();
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php wpfelix_entry_footer(); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-## -->
