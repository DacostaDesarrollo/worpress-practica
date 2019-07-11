<?php
/**
 * Template part for displaying the main navigation for the theme
 *
 * @package WPFelix
 */
$fixed_desktop = $fixed_mobile = false;

if ( get_theme_mod( 'header_sticky', true ) )
{
    $fixed_desktop = true;
}
if ( get_theme_mod( 'header_sticky_mobile', true ) )
{
    $fixed_mobile = true;
}

?>
<nav id="site-navigation" <?php wpfelix_main_navigation_class(); ?> role="navigation"
    <?php echo ( $fixed_desktop ? ' data-fixed="true"' : '' );?>
    <?php echo ( $fixed_mobile ? ' data-fixed-mobile="true"' : '' );?>>
    <div class="grid-container">
        <div class="extra-menu-container">
            <ul class="list-extra-menu">
                <?php if ( get_theme_mod( 'header_search_enable', true ) ) : ?>
                <li>
                    <button class="button-toggle search-toggle" aria-controls="mastsearch" aria-expanded="false">
                        <span class="screen-reader-text"><?php esc_html_e( 'Search toggle', 'wpfelix' ); ?></span>
                    </button>
                    <div id="mastsearch" class="site-search" role="search" aria-expanded="false"><?php get_search_form(); ?></div>
                </li>
                <?php endif; ?>
                <?php if ( get_theme_mod( 'header_sidenav_enable', true ) ) : ?>
                <li>
                    <button class="button-toggle sidenav-toggle" aria-controls="mastsidenav" aria-expanded="false">
                        <span class="screen-reader-text"><?php esc_html_e( 'Aside Navigation Toggle', 'wpfelix' ); ?></span>
                    </button>
                </li>
                <?php endif; ?>
                <li class="grid-hide-l grid-hide-xl">
                    <button class="button-toggle menu-toggle" aria-controls="mastmenu" aria-expanded="false">
                        <span class="screen-reader-text"><?php esc_html_e( 'Primary Menu', 'wpfelix' ); ?></span>
                    </button>
                </li>
            </ul>
        </div>
        <?php if ( get_theme_mod( 'header_social_enable', true ) ) : ?>
        <div class="social-menu-container">
            <?php wpfelix_social_markup(); ?>
        </div>
        <?php endif; ?>
        <div id="mastmenu" role="menu" class="primary-menu-container" aria-expanded="false">
            <button class="button-toggle menu-toggle-close" aria-controls="mastmenu" aria-expanded="false">
                <span class="screen-reader-text"><?php esc_html_e( 'Close Primary Menu', 'wpfelix' ); ?></span>
            </button>
        <?php
            if ( has_nav_menu( 'primary' ) )
            {
                wp_nav_menu( array(
                    'theme_location'    => 'primary',
                    'menu_class'        => 'primary-menu',
                    'menu_id'           => 'primary-menu',
                    'container'         => ''
                ) );
            }
            else
            {
                echo '<div class="text-center" style="line-height: 60px;">';
                if ( is_user_logged_in() && current_user_can( 'edit_theme_options' ) )
                {
                    printf(
                        esc_html__( 'Main menu is not set, please %s and assign to "primary" location.', 'wpfelix' ),
                        '<a style="display:inline;" href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Create Menu', 'wpfelix' ) . '</a>'
                    );
                }
                else
                {
                    /* Translator: %s */
                    printf(
                        esc_html__( 'Main menu is not set. Please %s to create menu and assign to "primary" location.', 'wpfelix' ),
                        '<a style="display:inline;" href="' . esc_url( wp_login_url( admin_url( 'nav-menus.php' ) ) ) . '">' . esc_html__( 'Login', 'wpfelix' ) . '</a>'
                    );
                }
                echo '</div>';
            }
        ?>
        </div>
    </div>
</nav><!-- #site-navigation -->