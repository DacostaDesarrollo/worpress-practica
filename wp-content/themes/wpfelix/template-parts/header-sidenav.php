<?php
/**
 * Template part for displaying side navigation and widgets
 *
 * @package WPFelix
 */
?>
<div id="mastsidenav" class="site-sidenav" data-stop-body-scroll="true">
    <a href="javascript:void(0)" class="sidenav-close" aria-controls="mastsidenav" aria-expanded="false">
        <span class="screen-reader-text"><?php esc_html_e( 'Close side panel', 'wpfelix' ); ?></span>
    </a>
    <div class="sidenav-container">
        <?php
        if ( get_theme_mod( 'sidenav_search_enable', true ) || is_active_sidebar( 'sidebar-aside-top' ) || ( has_nav_menu( 'side' ) ) )
        {
            echo '<div class="sidenav-top">';
            echo    '<div class="sidenav-block">';

            if ( get_theme_mod( 'sidenav_search_enable', true ) )
            {
                get_search_form();
            }

            if ( has_nav_menu( 'side' ) )
            {
                echo '<nav id="sidenav" class="sidenav-main">';
                echo    '<div id="sidemenu" class="side-menu-container">';
                if ( has_nav_menu( 'side' ) )
                {
                    wp_nav_menu( array(
                        'theme_location'    => 'side',
                        'menu_class'        => 'side-menu',
                        'menu_id'           => 'side-menu',
                        'container'         => ''
                    ) );
                }
                echo    '</div>';
                echo '</nav>';
            }

            if ( is_active_sidebar( 'sidebar-aside-top' ) )
            {
                
                if ( is_active_sidebar( 'sidebar-aside-top' ) )
                {
                    dynamic_sidebar( 'sidebar-aside-top' );
                }
            }

            echo    '</div>';
            echo '</div>';
        }

        if ( is_active_sidebar( 'sidebar-aside-bot' ) )
        {
            echo '<div class="sidenav-bot">';
            echo    '<div class="sidenav-block">';
            dynamic_sidebar( 'sidebar-aside-bot' );
            echo    '</div>';
            echo '</div>';
        }
        ?>
    </div>
</div>