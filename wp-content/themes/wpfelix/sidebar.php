<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package WPFelix
 */

if ( ! is_active_sidebar( 'sidebar-1' ) )
{
    return;
}

echo '<div class="sidebar-main"' . ( get_theme_mod( 'sticky_sidebar_on', true ) ? ' data-role="mainsidebar"' : '' ) . '>';
dynamic_sidebar( 'sidebar-1' );
echo '</div>';
