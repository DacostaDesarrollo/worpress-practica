<?php
/**
 * WPFelix back compat functionality
 *
 * Prevents WPFelix from running on WordPress versions prior to 4.5,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.5.
 *
 * @package WPFelix
 */


/**
 * Prevent switching to WPFelix on old versions of WordPress.
 *
 * Switches to the default theme.
 */
function wpfelix_switch_theme()
{
    switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );

    unset( $_GET['activated'] );

    add_action( 'admin_notices', 'wpfelix_upgrade_notice' );
}
add_action( 'after_switch_theme', 'wpfelix_switch_theme' );


/**
 * Adds a message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * WPFelix on WordPress versions prior to 4.5.
 *
 * @global string $wp_version WordPress version.
 */
function wpfelix_upgrade_notice() {
    $message = sprintf( __( 'WPFelix requires at least WordPress version 4.5. You are running version %s. Please upgrade and try again.', 'wpfelix' ), $GLOBALS['wp_version'] );
    printf( '<div class="error"><p>%s</p></div>', $message );
}


/**
 * Prevents the Customizer from being loaded on WordPress versions prior to 4.5.
 *
 * @global string $wp_version WordPress version.
 */
function wpfelix_customize() {
    wp_die( sprintf( __( 'WPFelix requires at least WordPress version 4.5. You are running version %s. Please upgrade and try again.', 'wpfelix' ), $GLOBALS['wp_version'] ), '', array(
        'back_link' => true,
    ) );
}
add_action( 'load-customize.php', 'wpfelix_customize' );


/**
 * Prevents the Theme Preview from being loaded on WordPress versions prior to 4.5.
 *
 * @global string $wp_version WordPress version.
 */
function wpfelix_preview() {
    if ( isset( $_GET['preview'] ) ) {
        wp_die( sprintf( __( 'WPFelix requires at least WordPress version 4.5. You are running version %s. Please upgrade and try again.', 'wpfelix' ), $GLOBALS['wp_version'] ) );
    }
}
add_action( 'template_redirect', 'wpfelix_preview' );
