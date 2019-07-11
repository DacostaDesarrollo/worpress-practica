<?php
/**
 * Customize helper functions
 *
 * @package WPFelix
 */


/**
 * Sanitize Radio Buttons/Select
 * @param  mixed $input
 * @param  array $setting
 * @return mixed
 */
function wpfelix_sanitize_choices( $input, $setting )
{
    global $wp_customize;
 
    $control = $wp_customize->get_control( $setting->id );
 
    if ( array_key_exists( $input, $control->choices ) )
    {
        return $input;
    }
    return $setting->default;
}


/**
 * Sanitize checkbox
 * @param  mixed $input empty string or '1'
 * @return empty string or '1'
 */
function wpfelix_sanitize_checkbox( $input )
{
    if ( $input == 1 )
    {
        return 1;
    }
    return 0;
}


/**
 * Sanitization callback for paddings.
 *
 * @param string|int $value padding number value.
 * @return int padding value
 */
function wpfelix_sanitize_padding( $value )
{
    if ( is_numeric( $value ) )
    {
        $value = intval( $value );
        if ( $value <= 0 )
        {
            return '0';
        }
        else
        {
            return $value;
        }
    }
    else
    {
        return '0';
    }
}


/**
 * Sanitize callback for rgba colorpicker
 *
 * @param string $color
 * @param  array $setting
 * @return string color value
 */
function wpfelix_sanitize_color( $color, $settings )
{
    if ( wpfelix_validate_color( $color ) )
    {
        return $color;
    }
    else
    {
        return $setting->default;
    }
}


/**
 * Sanitize callback for textarea to allow some HTML
 *
 * @param string $value
 * @param  array $setting
 * @return string value
 */
function wpfelix_sanitize_textarea_html( $value, $setting )
{
    return wp_kses( $value, wpfelix_global_kses( 'blockquote' ) );
}


/**
 * Register color schemes
 *
 * @return array An associative array of color scheme options.
 */
function wpfelix_get_color_schemes()
{
    return apply_filters( 'wpfelix_color_schemes', array(
        'default' => array(
            'label'  => esc_html__( 'Default', 'wpfelix' ),
            'colors' => array(
                '#ffffff', // background_color
                '#f7f7f9', // background_color_alt
                '#636363', // text_color
                '#353535', // text_strong_color
                '#b2b2b2', // text_weak_color
                '#cea352', // accent_color
                '#eeeeee', // border_color
                '#ffffff', // button_text_color
                '#ffffff', // button_hover_text_color
                '#353535', // button_hover_background_color
                '#ffffff', // background_color_header
                '#ffffff', // background_color_navbar
                '#eeeeee', // border_color_navbar
                '#353535', // link_color_navbar
                '#cea352', // link_active_color_navbar
                '#eeeeee', // border_color_navsub
                '#ffffff', // background_color_navsub
                '#353535', // link_color_navsub
                '#cea352', // link_active_color_navsub
                '#f7f7f9', // link_active_background_color_navsub
                '#353535', // text_color_category
                '#cea352', // border_color_category
                'transparent', // background_color_category
                '#353535', // text_color_widget_title
                '#cea352', // border_color_widget_title
                'transparent', // background_color_widget_title
                '#ffffff', // background_color_footer
                '#636363', // text_color_footer
                '#353535', // text_strong_color_footer
                '#b2b2b2', // text_weak_color_footer
                '#eeeeee' // border_color_footer
            ),
        ),
        'dark-nav' => array(
            'label'  => esc_html__( 'Dark Navigation', 'wpfelix' ),
            'colors' => array(
                '#ffffff', // background_color
                '#f7f7f9', // background_alt_color
                '#636363', // text_color
                '#353535', // highlight_color
                '#b2b2b2', // muted_color
                '#cea352', // accent_color
                '#eeeeee', // border_color
                '#ffffff', // button_color
                '#ffffff', // button_hover_color
                '#353535', // button_hover_background_color
                '#ffffff', // header_background_color
                '#353535', // nav_background_color
                'rgba(0,0,0,0.07)', // nav_border_color
                '#ffffff', // nav_link_color
                '#cea352', // nav_link_active_color
                '#eeeeee', // nav_sub_border_color
                '#ffffff', // nav_sub_background_color
                '#353535', // nav_sub_link_color
                '#cea352', // nav_sub_link_active_color
                '#f7f7f9', // nav_sub_link_active_background_color
                '#353535', // cat_text_color
                '#cea352', // cat_border_color
                'transparent', // cat_bg_color
                '#353535', // widget_title_color
                '#cea352', // widget_title_border_color
                'transparent', // widget_title_background_color
                '#ffffff', // footer_background_color
                '#636363', // footer_text_color
                '#353535', // footer_highlight_color
                '#b2b2b2', // footer_muted_color
                '#eeeeee' // footer_border_color
            ),
        ),
        'dark-nav-footer' => array(
            'label'  => esc_html__( 'Dark Navigation and Footer', 'wpfelix' ),
            'colors' => array(
                '#ffffff', // background_color
                '#f7f7f9', // background_alt_color
                '#636363', // text_color
                '#353535', // highlight_color
                '#b2b2b2', // muted_color
                '#cea352', // accent_color
                '#eeeeee', // border_color
                '#ffffff', // button_color
                '#ffffff', // button_hover_color
                '#353535', // button_hover_background_color
                '#ffffff', // header_background_color
                '#353535', // nav_background_color
                'rgba(0,0,0,0.07)', // nav_border_color
                '#ffffff', // nav_link_color
                '#cea352', // nav_link_active_color
                '#eeeeee', // nav_sub_border_color
                '#ffffff', // nav_sub_background_color
                '#353535', // nav_sub_link_color
                '#cea352', // nav_sub_link_active_color
                '#f7f7f9', // nav_sub_link_active_background_color
                '#353535', // cat_text_color
                '#cea352', // cat_border_color
                'transparent', // cat_bg_color
                '#353535', // widget_title_color
                '#cea352', // widget_title_border_color
                'transparent', // widget_title_background_color
                '#353535', // footer_background_color
                '#c8c8c8', // footer_text_color
                '#ffffff', // footer_highlight_color
                '#8d8d8d', // footer_muted_color
                'rgba(255,255,255,0.07)' // footer_border_color
            ),
        ),
        'green'    => array(
            'label'  => esc_html__( 'Green', 'wpfelix' ),
            'colors' => array(
                '#f7f7f7', // background_color
                '#f7f7f9', // background_alt_color
                '#636363', // text_color
                '#242424', // highlight_color
                '#b2b2b2', // muted_color
                '#8a8f6a', // accent_color
                '#eeeeee', // border_color
                '#ffffff', // button_color
                '#ffffff', // button_hover_color
                '#242424', // button_hover_background_color
                '#f7f7f7', // header_background_color
                '#242424', // nav_background_color
                'rgba(0,0,0,0.07)', // nav_border_color
                '#ffffff', // nav_link_color
                '#8a8f6a', // nav_link_active_color
                '#eeeeee', // nav_sub_border_color
                '#ffffff', // nav_sub_background_color
                '#353535', // nav_sub_link_color
                '#8a8f6a', // nav_sub_link_active_color
                '#f7f7f9', // nav_sub_link_active_background_color
                '#ffffff', // cat_text_color
                '#8a8f6a', // cat_border_color
                '#8a8f6a', // cat_bg_color
                '#ffffff', // widget_title_color
                '#242424', // widget_title_border_color
                '#242424', // widget_title_background_color
                '#242424', // footer_background_color
                '#c8c8c8', // footer_text_color
                '#ffffff', // footer_highlight_color
                '#8d8d8d', // footer_muted_color
                'rgba(255,255,255,0.07)' // footer_border_color
            ),
        ),
        'pink'    => array(
            'label'  => esc_html__( 'Pink', 'wpfelix' ),
            'colors' => array(
                '#ffffff', // background_color
                '#f7f7f9', // background_alt_color
                '#636363', // text_color
                '#353535', // highlight_color
                '#b2b2b2', // muted_color
                '#cd6799', // accent_color
                '#eeeeee', // border_color
                '#ffffff', // button_color
                '#ffffff', // button_hover_color
                '#353535', // button_hover_background_color
                '#f5eff2', // header_background_color
                '#ffffff', // nav_background_color
                '#eeeeee', // nav_border_color
                '#353535', // nav_link_color
                '#cd6799', // nav_link_active_color
                '#eeeeee', // nav_sub_border_color
                '#ffffff', // nav_sub_background_color
                '#353535', // nav_sub_link_color
                '#cd6799', // nav_sub_link_active_color
                '#f7f7f9', // nav_sub_link_active_background_color
                '#353535', // cat_text_color
                '#cd6799', // cat_border_color
                'transparent', // cat_bg_color
                '#353535', // widget_title_color
                '#f5eff2', // widget_title_border_color
                '#f5eff2', // widget_title_background_color
                '#261d22', // footer_background_color
                '#c8c8c8', // footer_text_color
                '#ffffff', // footer_highlight_color
                '#8d8d8d', // footer_muted_color
                'rgba(255,255,255,0.07)' // footer_border_color
            ),
        ),
        'red'    => array(
            'label'  => esc_html__( 'Red', 'wpfelix' ),
            'colors' => array(
                '#ffffff', // background_color
                '#f7f7f9', // background_alt_color
                '#636363', // text_color
                '#000000', // highlight_color
                '#b2b2b2', // muted_color
                '#e61739', // accent_color
                '#eeeeee', // border_color
                '#ffffff', // button_color
                '#ffffff', // button_hover_color
                '#353535', // button_hover_background_color
                '#ffffff', // header_background_color
                '#000000', // nav_background_color
                '#525252', // nav_border_color
                '#ffffff', // nav_link_color
                '#e61739', // nav_link_active_color
                '#eeeeee', // nav_sub_border_color
                '#ffffff', // nav_sub_background_color
                '#353535', // nav_sub_link_color
                '#e61739', // nav_sub_link_active_color
                '#f7f7f9', // nav_sub_link_active_background_color
                '#ffffff', // cat_text_color
                '#e61739', // cat_border_color
                '#e61739', // cat_bg_color
                '#000000', // widget_title_color
                '#eeeeee', // widget_title_border_color
                'transparent', // widget_title_background_color
                '#ffffff', // footer_background_color
                '#636363', // footer_text_color
                '#353535', // footer_highlight_color
                '#b2b2b2', // footer_muted_color
                '#eeeeee' // footer_border_color
            ),
        )
    ) );
}


/**
 * Get the current WPFelix color scheme.
 * @return array An associative array of either the current or default color scheme hex values.
 */
function wpfelix_get_color_scheme()
{
    $color_scheme_option = get_theme_mod( 'color_scheme', 'default' );
    $color_schemes       = wpfelix_get_color_schemes();

    if ( array_key_exists( $color_scheme_option, $color_schemes ) )
    {
        return $color_schemes[ $color_scheme_option ]['colors'];
    }

    return $color_schemes['default']['colors'];
}


/**
 * Returns an array of color scheme choices registered for WPFelix.
 * @return array Array of color schemes.
 */
function wpfelix_get_color_scheme_choices()
{
    $color_schemes                = wpfelix_get_color_schemes();
    $color_scheme_control_options = array();

    foreach ( $color_schemes as $color_scheme => $value )
    {
        $color_scheme_control_options[ $color_scheme ] = $value['label'];
    }

    return $color_scheme_control_options;
}


/**
 * Sanitization callback for color schemes.
 *
 * @param string $value Color scheme name value.
 * @return string Color scheme name.
 */
function wpfelix_sanitize_color_scheme( $value )
{
    $color_schemes = wpfelix_get_color_scheme_choices();

    if ( ! array_key_exists( $value, $color_schemes ) )
    {
        $value = 'default';
    }

    return $value;
}

/*--------------------------------------------------------------
# Active callbacks
--------------------------------------------------------------*/

/**
 * Active callbacks for sharing section controls
 * 
 * @since  2.0
 * 
 * @param  WP_Customize_Control $control
 * @return boolean
 */
function wpfelix_customize_ac_sharing( $control )
{
    if ( ! $control->manager->get_setting( 'share_on' )->value() )
    {
        return false;
    }

    return true;
}

/**
 * Active callbacks for featured section controls
 * 
 * @since  2.0
 * 
 * @param  WP_Customize_Control $control
 * @return boolean
 */
function wpfelix_customize_ac_general( $control )
{
    $result = true;

    switch ( $control->id )
    {
        case 'posts_excerpt_length':
            if ( 'custom-excerpt' != $control->manager->get_setting( 'posts_summary' )->value() )
            {
                $result = false;
            }
            break;
        
        default:
            break;
    }

    return $result;
}

/**
 * Active callbacks for featured section controls
 * 
 * @since  2.0
 * 
 * @param  WP_Customize_Control $control
 * @return boolean
 */
function wpfelix_customize_ac_featured( $control )
{
    if ( ! $control->manager->get_setting( 'featured' )->value() )
    {
        return false;
    }
    return true;
}
