<?php
/**
 * Template part for displaying page title. Apply only for archives
 *
 * @package WPFelix
 */

if ( is_archive() || is_search() )
{
    $titles = wpfelix_get_page_titles();
    ?>
    <div id="pagetitle" class="site-page-title">
        <div class="grid-container text-center">
        <?php
        if ( ! empty( $titles['title'] ) )
        {
            printf( '<h1 class="page-title"><span>%s</span></h1>', $titles['title'] );
        }
        if ( ! empty( $titles['subtitle'] ) )
        {
            printf( '<div class="page-title-desc">%s</div>', $titles['subtitle'] );
        }
        ?>
        </div>
    </div>
<?php
}
