<?php
/**
 * The sidebar containing widget area for pages.
 *
 * @package WPFelix
 */

if ( ! is_active_sidebar( 'sidebar-pages' ) )
{
    return;
}

echo '<div class="sidebar-main"' . ( get_theme_mod( 'sticky_sidebar_on', true ) ? ' data-role="mainsidebar"' : '' ) . '>';
dynamic_sidebar( 'sidebar-pages' );
echo '</div>';