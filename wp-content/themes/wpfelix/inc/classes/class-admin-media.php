<?php
/**
 * Additional tweak for admin media settings
 *
 * @package WPFelix
 * @since   WPFelix 1.0
 */

if ( ! is_admin() || ! current_user_can( 'edit_theme_options' ) )
{
    return;
}

class WPFelix_Admin_Media
{
    /**
     * Constructor
     */
    function __construct()
    {
        add_action( 'admin_menu', array( $this, 'admin_menu_init' ) );
    }


    function admin_menu_init()
    {
        register_setting( 'media', 'medium_crop', 'intval' );
        add_settings_field(
            'medium_crop',
            '',
            array( $this, 'field_medium_crop' ),
            'media'
        );

        register_setting( 'media', 'large_crop', 'intval' );
        add_settings_field(
            'large_crop',
            '',
            array( $this, 'field_large_crop' ),
            'media'
        );
    }


    function field_medium_crop()
    {
        ?>
        <input name="medium_crop" type="checkbox" id="medium_crop" value="1" <?php checked( '1', get_option( 'medium_crop' ) ); ?>/>
        <label for="medium_crop"><?php esc_html_e( 'Crop medium size to exact dimensions', 'wpfelix' ) ?></label>
        <?php
    }


    function field_large_crop()
    {
        ?>
        <input name="large_crop" type="checkbox" id="large_crop" value="1" <?php checked( '1', get_option( 'large_crop' ) ); ?>/>
        <label for="large_crop"><?php esc_html_e( 'Crop large size to exact dimensions', 'wpfelix' ) ?></label>
        <?php
    }
}

new WPFelix_Admin_Media();
