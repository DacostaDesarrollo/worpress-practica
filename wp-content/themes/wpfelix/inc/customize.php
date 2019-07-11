<?php
/**
 * Register settings for the theme customizer.
 * Also register customizer style.
 *
 * @package WPFelix
 */


/**
 * Add customize theme support
 * @return void
 */
function wpfelix_customize_supports()
{
    $color_scheme  = wpfelix_get_color_scheme();
    $default_background_color = trim( $color_scheme[0], '#' );
    $default_text_color = trim( $color_scheme[2], '#' );

    add_theme_support( 'custom-header', apply_filters( 'wpfelix_custom_header_args', array(
        'default-text-color'     => $default_text_color,
        'width'                  => 1200,
        'height'                 => 210,
        'flex-height'            => true
    ) ) );

    add_theme_support( 'custom-background', apply_filters( 'wpfelix_custom_background_args', array(
        'default-color' => $default_background_color,
        'default-image' => ''
    ) ) );

    add_theme_support( 'custom-logo', array(
        'height'      => 240,
        'width'       => 480,
        'flex-height' => false,
        'flex-width'  => true,
        'header-text' => array( 'site-title', 'site-description' ),
    ) );
}
add_action( 'after_setup_theme', 'wpfelix_customize_supports' );


/**
 * Binds JS listener to make Customizer color_scheme control.
 * Passes color scheme data as wpFelixColorScheme global.
 */
function wpfelix_customize_control_scripts()
{
    wp_enqueue_script( 'wpfelix-customize-colors', get_template_directory_uri() . '/js/customize-colors.js', array( 'customize-controls', 'iris', 'underscore', 'wp-util' ), '', true );
    wp_localize_script( 'wpfelix-customize-colors', 'wpFelixColorScheme', wpfelix_get_color_schemes() );
}
add_action( 'customize_controls_enqueue_scripts', 'wpfelix_customize_control_scripts' );


/**
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 */
function wpfelix_customize_preview_scripts()
{
    wp_enqueue_script( 'wpfelix-customize-preview', get_template_directory_uri() . '/js/customize-preview.js', array( 'customize-preview' ), '', true );
}
add_action( 'customize_preview_init', 'wpfelix_customize_preview_scripts' );


/**
 * Register customizer controls
 * Hooked into: customize_register
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wpfelix_customize_register( $wp_customize )
{
    /**
     * Let's add our controls
     */
    require get_template_directory() . '/inc/customize-controls/rgba-color-picker.php';
    //require get_template_directory() . '/inc/customize-controls/multi-color-picker.php';

    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

    if ( isset( $wp_customize->selective_refresh ) )
    {
        $wp_customize->selective_refresh->add_partial( 'blogname', array(
            'selector' => '.site-title a',
            'container_inclusive' => false,
            'render_callback' => 'wpfelix_customize_partial_blogname',
        ) );
        $wp_customize->selective_refresh->add_partial( 'blogdescription', array(
            'selector' => '.site-description',
            'container_inclusive' => false,
            'render_callback' => 'wpfelix_customize_partial_blogdescription',
        ) );
    }

    /**
     * Declare some array for options, we do this to make it easier to translate
     */
    $categories = get_categories( array(
        'orderby' => 'name',
        'parent'  => 0
    ) );

    $categories_dropdown = array( '0' => esc_html__( '- Choose -', 'wpfelix' ) );

    if ( ! is_wp_error( $categories ) )
    {
        foreach ( $categories as $key => $category ) {
            $categories_dropdown[$category->term_id] = $category->name;
        }
    }

    /*--------------------------------------------------------------
    ## HEADER
    --------------------------------------------------------------*/

    $wp_customize->add_section( 'wpfelix_section_header', array(
        'title'     => esc_html__( 'Header', 'wpfelix' )
    ) );

        $wp_customize->add_setting( 'header_nav_pos', array( 'default' => 'bottom', 'sanitize_callback' => 'wpfelix_sanitize_choices' ) );
        $wp_customize->add_control( 'header_nav_pos', array(
            'type'          => 'radio',
            'label'         => esc_html__( 'Main Navigation Position', 'wpfelix' ),
            'section'       => 'wpfelix_section_header',
            'choices'       => array(
                'top'           => esc_html__( 'Before Logo', 'wpfelix' ),
                'bottom'        => esc_html__( 'After Logo', 'wpfelix' )
            )
        ) );

        $wp_customize->add_setting( 'header_social_enable', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'header_social_enable', array(
            'type'          => 'checkbox',
            'label'         => esc_html__( 'Enable Social Icons', 'wpfelix' ),
            'section'       => 'wpfelix_section_header'
        ) );

        $wp_customize->add_setting( 'header_search_enable', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'header_search_enable', array(
            'type'          => 'checkbox',
            'label'         => esc_html__( 'Enable Search', 'wpfelix' ),
            'section'       => 'wpfelix_section_header'
        ) );

        $wp_customize->add_setting( 'header_sidenav_enable', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'header_sidenav_enable', array(
            'type'          => 'checkbox',
            'label'         => esc_html__( 'Enable Side Navigation', 'wpfelix' ),
            'section'       => 'wpfelix_section_header'
        ) );

        $wp_customize->add_setting( 'sidenav_search_enable', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'sidenav_search_enable', array(
            'type'          => 'checkbox',
            'label'         => esc_html__( 'Enable Search on Side Navigation.', 'wpfelix' ),
            'section'       => 'wpfelix_section_header'
        ) );

        $wp_customize->add_setting( 'header_sticky', array( 'default' => false, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'header_sticky', array(
            'type'          => 'checkbox',
            'label'         => esc_html__( 'Sticky Navigation', 'wpfelix' ),
            'section'       => 'wpfelix_section_header'
        ) );

        $wp_customize->add_setting( 'header_sticky_mobile', array( 'default' => false, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'header_sticky_mobile', array(
            'type'          => 'checkbox',
            'label'         => esc_html__( 'Sticky Navigation on Mobile', 'wpfelix' ),
            'section'       => 'wpfelix_section_header'
        ) );

        $wp_customize->add_setting( 'header_nav_align', array( 'default' => 'center', 'sanitize_callback' => 'wpfelix_sanitize_choices' ) );
        $wp_customize->add_control( 'header_nav_align', array(
            'type'          => 'radio',
            'label'         => esc_html__( 'Main Navigation Alignment', 'wpfelix' ),
            'section'       => 'wpfelix_section_header',
            'choices'       => array(
                'left'          => esc_html__( 'Left', 'wpfelix' ),
                'right'         => esc_html__( 'Right', 'wpfelix' ),
                'center'        => esc_html__( 'Center', 'wpfelix' )
            )
        ) );

        $wp_customize->add_setting( 'header_nav_extras_align', array( 'default' => 'right', 'sanitize_callback' => 'wpfelix_sanitize_choices' ) );
        $wp_customize->add_control( 'header_nav_extras_align', array(
            'type'          => 'radio',
            'label'         => esc_html__( 'Extra Navigation Alignment', 'wpfelix' ),
            'description'   => esc_html__( 'Search toogle and Side Navigation Toggle.', 'wpfelix' ),
            'section'       => 'wpfelix_section_header',
            'choices'       => array(
                'left'          => esc_html__( 'Left', 'wpfelix' ),
                'right'         => esc_html__( 'Right', 'wpfelix' )
            )
        ) );

        $wp_customize->add_setting( 'header_social_align', array( 'default' => 'left', 'sanitize_callback' => 'wpfelix_sanitize_choices' ) );
        $wp_customize->add_control( 'header_social_align', array(
            'type'          => 'radio',
            'label'         => esc_html__( 'Social Icons Alignment', 'wpfelix' ),
            'section'       => 'wpfelix_section_header',
            'choices'       => array(
                'left'          => esc_html__( 'Left', 'wpfelix' ),
                'right'         => esc_html__( 'Right', 'wpfelix' )
            )
        ) );

        $wp_customize->add_setting( 'header_padding_top', array( 'default' => '48', 'sanitize_callback' => 'wpfelix_sanitize_padding' ) );
        $wp_customize->add_control( 'header_padding_top', array(
            'type'          => 'number',
            'label'         => esc_html__( 'Header Padding Top', 'wpfelix' ),
            'section'       => 'wpfelix_section_header',
            'input_attrs' => array(
                'min' => 0,
                'max' => 169,
                'step' => 1,
            )
        ) );

        $wp_customize->add_setting( 'header_padding_bottom', array( 'default' => '17', 'sanitize_callback' => 'wpfelix_sanitize_padding' ) );
        $wp_customize->add_control( 'header_padding_bottom', array(
            'type'          => 'number',
            'label'         => esc_html__( 'Header Padding Bottom', 'wpfelix' ),
            'section'       => 'wpfelix_section_header',
            'input_attrs' => array(
                'min' => 0,
                'max' => 169,
                'step' => 1,
            )
        ) );


    /*--------------------------------------------------------------
    ## SOCIAL MEDIA
    --------------------------------------------------------------*/
    
    $wp_customize->add_panel( 'wpfelix_panel_social', array(
        'title'         => esc_html__( 'Social', 'wpfelix' ),
        'description'   => esc_html__( 'If you prefer to use plugin then just disable this.', 'wpfelix' )
    ) );

        // Social URLS
        //--------------------------------------------------
        
        $wp_customize->add_section( 'wpfelix_section_social', array(
            'panel'         => 'wpfelix_panel_social',
            'title'         => esc_html__( 'Social Profile URLs', 'wpfelix' ),
            'description'   => esc_html__( 'Paste your social profile URLs here. If left blank, the icon will not show up.', 'wpfelix' )
        ) );

            $wp_customize->add_setting( 'facebook', array( 'default' => '#', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'facebook', array(
                'label'         => 'Facebook',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'twitter', array( 'default' => '#', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'twitter', array(
                'label'         => 'Twitter',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'linkedin', array( 'default' => '#', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'linkedin', array(
                'label'         => 'Linkedin',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'pinterest', array( 'default' => '#', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'pinterest', array(
                'label'         => 'Pinterest',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'instagram', array( 'default' => '', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'instagram', array(
                'label'         => 'Instagram',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'google', array( 'default' => '', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'google', array(
                'label'         => 'Google',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'dribbble', array( 'default' => '', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'dribbble', array(
                'label'         => 'Dribbble',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'flickr', array( 'default' => '', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'flickr', array(
                'label'         => 'Flickr',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'behance', array( 'default' => '', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'behance', array(
                'label'         => 'Behance',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'tumblr', array( 'default' => '', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'tumblr', array(
                'label'         => 'Tumblr',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'youtube', array( 'default' => '', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'youtube', array(
                'label'         => 'Youtube',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'vimeo', array( 'default' => '', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'vimeo', array(
                'label'         => 'Vimeo',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'soundcloud', array( 'default' => '', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'soundcloud', array(
                'label'         => 'Soundcloud',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'vk', array( 'default' => '', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'vk', array(
                'label'         => 'Vk',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'reddit', array( 'default' => '', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'reddit', array(
                'label'         => 'Reddit',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'yahoo', array( 'default' => '', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'yahoo', array(
                'label'         => 'Yahoo',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'github', array( 'default' => '', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'github', array(
                'label'         => 'Github',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'rss', array( 'default' => '', 'sanitize_callback' => 'esc_url' ) );
            $wp_customize->add_control( 'rss', array(
                'label'         => 'Rss',
                'section'       => 'wpfelix_section_social',
                'type'          => 'url'
            ) );

            $wp_customize->add_setting( 'email', array( 'default' => '', 'sanitize_callback' => 'sanitize_email' ) );
            $wp_customize->add_control( 'email', array(
                'label'         => 'Email',
                'section'       => 'wpfelix_section_social',
                'type'          => 'email'
            ) );

        // Sharing feature
        //--------------------------------------------------
        
        $wp_customize->add_section( 'wpfelix_section_social_share', array(
            'panel'         => 'wpfelix_panel_social',
            'title'         => esc_html__( 'Social Sharing', 'wpfelix' ),
            'description'   => esc_html__( 'If you prefer to use plugin for this feature then disable this.', 'wpfelix' )
        ) );

            $wp_customize->add_setting( 'share_on', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
            $wp_customize->add_control( 'share_on', array(
                'label'         => esc_html__( 'Enable Sharing Feature', 'wpfelix' ),
                'section'       => 'wpfelix_section_social_share',
                'type'          => 'checkbox'
            ) );

            $wp_customize->add_setting( 'share_facebook', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
            $wp_customize->add_control( 'share_facebook', array(
                'label'         => esc_html__( 'Facebook Sharing Support', 'wpfelix' ),
                'section'       => 'wpfelix_section_social_share',
                'type'          => 'checkbox',
                'active_callback' => 'wpfelix_customize_ac_sharing'
            ) );

            $wp_customize->add_setting( 'share_twitter', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
            $wp_customize->add_control( 'share_twitter', array(
                'label'         => esc_html__( 'Twitter Sharing Support', 'wpfelix' ),
                'section'       => 'wpfelix_section_social_share',
                'type'          => 'checkbox',
                'active_callback' => 'wpfelix_customize_ac_sharing'
            ) );

            $wp_customize->add_setting( 'share_google', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
            $wp_customize->add_control( 'share_google', array(
                'label'         => esc_html__( 'Google Sharing Support', 'wpfelix' ),
                'section'       => 'wpfelix_section_social_share',
                'type'          => 'checkbox',
                'active_callback' => 'wpfelix_customize_ac_sharing'
            ) );

            $wp_customize->add_setting( 'share_pinterest', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
            $wp_customize->add_control( 'share_pinterest', array(
                'label'         => esc_html__( 'Pinterest Sharing Support', 'wpfelix' ),
                'section'       => 'wpfelix_section_social_share',
                'type'          => 'checkbox',
                'active_callback' => 'wpfelix_customize_ac_sharing'
            ) );

            $wp_customize->add_setting( 'share_linkedin', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
            $wp_customize->add_control( 'share_linkedin', array(
                'label'         => esc_html__( 'Linkedin Sharing Support', 'wpfelix' ),
                'section'       => 'wpfelix_section_social_share',
                'type'          => 'checkbox',
                'active_callback' => 'wpfelix_customize_ac_sharing'
            ) );

            $wp_customize->add_setting( 'share_tumblr', array( 'default' => false, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
            $wp_customize->add_control( 'share_tumblr', array(
                'label'         => esc_html__( 'Tumblr Sharing Support', 'wpfelix' ),
                'section'       => 'wpfelix_section_social_share',
                'type'          => 'checkbox',
                'active_callback' => 'wpfelix_customize_ac_sharing'
            ) );

            $wp_customize->add_setting( 'share_vk', array( 'default' => false, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
            $wp_customize->add_control( 'share_vk', array(
                'label'         => esc_html__( 'Vk Sharing Support', 'wpfelix' ),
                'section'       => 'wpfelix_section_social_share',
                'type'          => 'checkbox',
                'active_callback' => 'wpfelix_customize_ac_sharing'
            ) );

            $wp_customize->add_setting( 'share_reddit', array( 'default' => false, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
            $wp_customize->add_control( 'share_reddit', array(
                'label'         => esc_html__( 'Reddit Sharing Support', 'wpfelix' ),
                'section'       => 'wpfelix_section_social_share',
                'type'          => 'checkbox',
                'active_callback' => 'wpfelix_customize_ac_sharing'
            ) );

            $wp_customize->add_setting( 'share_email', array( 'default' => false, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
            $wp_customize->add_control( 'share_email', array(
                'label'         => esc_html__( 'Email Sharing Support', 'wpfelix' ),
                'section'       => 'wpfelix_section_social_share',
                'type'          => 'checkbox',
                'active_callback' => 'wpfelix_customize_ac_sharing'
            ) );


    /*--------------------------------------------------------------
    ## FEATURED SECTION
    --------------------------------------------------------------*/

    $wp_customize->add_section( 'wpfelix_section_featured', array(
        'title'         => esc_html__( 'Featured Area', 'wpfelix' ),
        'description'   => ''
    ) );
    
        $wp_customize->add_setting( 'featured', array( 'default' => false, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'featured', array(
            'section'   => 'wpfelix_section_featured',
            'type'      => 'checkbox',
            'label'     => esc_html__( 'Enable Featured Area', 'wpfelix' )
        ) );

        $wp_customize->add_setting( 'featured_category', array( 'default' => '0', 'sanitize_callback' => 'absint' ) );
        $wp_customize->add_control( 'featured_category', array(
            'section'   => 'wpfelix_section_featured',
            'type'      => 'select',
            'label'     => esc_html__( 'Featured Category', 'wpfelix' ),
            'choices'   => $categories_dropdown,
            'active_callback' => 'wpfelix_customize_ac_featured'
        ) );

        $wp_customize->add_setting( 'featured_style', array( 'default' => 'full', 'sanitize_callback' => 'wpfelix_sanitize_choices' ) );
        $wp_customize->add_control( 'featured_style', array(
            'section'   => 'wpfelix_section_featured',
            'type'      => 'radio',
            'label'     => esc_html__( 'Featured Slider Style', 'wpfelix' ),
            'choices'   => array(
                'full'      => esc_html__( 'Full Width', 'wpfelix' ),
                'full_grid' => esc_html__( 'Full Width Grid', 'wpfelix' ),
                'full_box'  => esc_html__( 'Full Width With Big Centered Slide', 'wpfelix' ),
                'boxed'     => esc_html__( 'Boxed', 'wpfelix' )
            ),
            'active_callback' => 'wpfelix_customize_ac_featured'
        ) );

        $wp_customize->add_setting( 'featured_slides_num', array( 'default' => '4', 'sanitize_callback' => 'absint' ) );
        $wp_customize->add_control( 'featured_slides_num', array(
            'section'       => 'wpfelix_section_featured',
            'type'          => 'number',
            'label'         => esc_html__( 'Number of Slides.', 'wpfelix' ),
            'input_attrs'   => array(
                'min'   => 1,
                'max'   => 100,
                'step'  => 1,
            ),
            'active_callback' => 'wpfelix_customize_ac_featured'
        ) );

        $wp_customize->add_setting( 'featured_button_style', array( 'default' => 'default', 'sanitize_callback' => 'wpfelix_sanitize_choices' ) );
        $wp_customize->add_control( 'featured_button_style', array(
            'section'   => 'wpfelix_section_featured',
            'type'      => 'radio',
            'label'     => esc_html__( 'Featured Slide Button Style', 'wpfelix' ),
            'choices'   => array(
                'default'   => esc_html__( 'Default Style', 'wpfelix' ),
                'reverse'   => esc_html__( 'Reverse Style', 'wpfelix' )
            ),
            'active_callback' => 'wpfelix_customize_ac_featured'
        ) );

        $wp_customize->add_setting( 'featured_excerpt_length', array( 'default' => '18', 'sanitize_callback' => 'absint' ) );
        $wp_customize->add_control( 'featured_excerpt_length', array(
            'label'         => esc_html__( 'Custom Excerpt Length', 'wpfelix' ),
            'section'       => 'wpfelix_section_featured',
            'type'          => 'number',
            'input_attrs'   => array(
                'min'   => 0,
                'max'   => 1000,
                'step'  => 1,
            ),
            'active_callback' => 'wpfelix_customize_ac_featured'
        ) );

        $wp_customize->add_setting( 'featured_margin_top', array( 'default' => '0', 'sanitize_callback' => 'intval' ) );
        $wp_customize->add_control( 'featured_margin_top', array(
            'section'   => 'wpfelix_section_featured',
            'label'     => esc_html__( 'Space before', 'wpfelix' ),
            'type'          => 'number',
            'input_attrs'   => array(
                'min'   => -1000,
                'max'   => 1000,
                'step'  => 1,
            ),
            'active_callback' => 'wpfelix_customize_ac_featured'
        ) );

        $wp_customize->add_setting( 'featured_category_hide', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'featured_category_hide', array(
            'section'   => 'wpfelix_section_featured',
            'type'      => 'checkbox',
            'label'     => esc_html__( 'Hide Featured Category From All Posts Meta.', 'wpfelix' ),
            'active_callback' => 'wpfelix_customize_ac_featured'
        ) );


    /*--------------------------------------------------------------
    ## GENERAL
    --------------------------------------------------------------*/

    $wp_customize->add_section( 'wpfelix_section_general', array(
        'title'         => esc_html__( 'General', 'wpfelix' ),
        'description'   => ''
    ) );

        $wp_customize->add_setting( 'posts_layout_home', array( 'default' => 'full', 'sanitize_callback' => 'wpfelix_sanitize_choices' ) );
        $wp_customize->add_control( 'posts_layout_home', array(
            'label'         => esc_html( 'Home Posts Layout', 'wpfelix' ),
            'section'       => 'wpfelix_section_general',
            'type'          => 'radio',
            'choices'       => array(
                'full'          => esc_html__( 'Full', 'wpfelix' ),
                'grid'          => esc_html__( 'Grid', 'wpfelix' ),
                'grid_full'     => esc_html__( 'Grid With Full Style', 'wpfelix' ),
                'list'          => esc_html__( 'List', 'wpfelix' ),
                'list_odd_even' => esc_html__( 'List with odd/even Style', 'wpfelix' )
            )
        ) );

        $wp_customize->add_setting( 'posts_first_home', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'posts_first_home', array(
            'label'         => esc_html( 'First full post for Home', 'wpfelix' ),
            'section'       => 'wpfelix_section_general',
            'type'          => 'checkbox'
        ) );

        $wp_customize->add_setting( 'posts_layout_archive', array( 'default' => 'list', 'sanitize_callback' => 'wpfelix_sanitize_choices' ) );
        $wp_customize->add_control( 'posts_layout_archive', array(
            'label'         => esc_html( 'Archive Posts Layout', 'wpfelix' ),
            'section'       => 'wpfelix_section_general',
            'type'          => 'radio',
            'choices'       => array(
                'full'          => esc_html__( 'Full', 'wpfelix' ),
                'grid'          => esc_html__( 'Grid', 'wpfelix' ),
                'grid_full'     => esc_html__( 'Grid With Full Style', 'wpfelix' ),
                'list'          => esc_html__( 'List', 'wpfelix' ),
                'list_odd_even' => esc_html__( 'List with odd/even Style', 'wpfelix' )
            )
        ) );

        $wp_customize->add_setting( 'posts_first_archive', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'posts_first_archive', array(
            'label'         => esc_html( 'First full post for Archive', 'wpfelix' ),
            'section'       => 'wpfelix_section_general',
            'type'          => 'checkbox'
        ) );

        $wp_customize->add_setting( 'posts_nav_type', array( 'default' => 'default', 'sanitize_callback' => 'wpfelix_sanitize_choices' ) );
        $wp_customize->add_control( 'posts_nav_type', array(
            'label'         => esc_html( 'Posts navigation type', 'wpfelix' ),
            'section'       => 'wpfelix_section_general',
            'type'          => 'radio',
            'choices'       => array(
                'default'        => esc_html__( 'Default', 'wpfelix' ),
                'pages'          => esc_html__( 'Page links', 'wpfelix' ),
            )
        ) );

        $wp_customize->add_setting( 'posts_layout_grid', array( 'default' => '2', 'sanitize_callback' => 'absint' ) );
        $wp_customize->add_control( 'posts_layout_grid', array(
            'label'         => esc_html( 'Grid Columns', 'wpfelix' ),
            'section'       => 'wpfelix_section_general',
            'type'          => 'select',
            'choices'       => array(
                '2' => '2',
                '3' => '3'
            )
        ) );

        $wp_customize->add_setting( 'posts_summary', array( 'default' => 'custom-excerpt', 'sanitize_callback' => 'wpfelix_sanitize_choices' ) );
        $wp_customize->add_control( 'posts_summary', array(
            'label'         => esc_html__( 'Posts Summary', 'wpfelix' ),
            'description'   => esc_html__( 'Works with any posts layout except full.', 'wpfelix' ),
            'section'       => 'wpfelix_section_general',
            'type'          => 'radio',
            'choices'       => array(
                'excerpt'           => esc_html__( 'Use Excerpt', 'wpfelix' ),
                'custom-excerpt'    => esc_html__( 'Custom Excerpt', 'wpfelix' )
            )
        ) );

        $wp_customize->add_setting( 'posts_excerpt_length', array( 'default' => 44, 'sanitize_callback' => 'absint' ) );
        $wp_customize->add_control( 'posts_excerpt_length', array(
            'label'         => esc_html__( 'Custom Excerpt Length', 'wpfelix' ),
            'description'   => esc_html__( 'Works with any posts layout except full.', 'wpfelix' ),
            'section'       => 'wpfelix_section_general',
            'type'          => 'number',
            'input_attrs'   => array(
                'min'   => 10,
                'max'   => 1000,
                'step'  => 1,
            ),
            'active_callback' => 'wpfelix_customize_ac_general'
        ) );

        $wp_customize->add_setting( 'post_date', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'post_date', array(
            'label'         => esc_html__( 'Show Post Date', 'wpfelix' ),
            'section'       => 'wpfelix_section_general',
            'type'          => 'checkbox'
        ) );

        $wp_customize->add_setting( 'post_categories', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'post_categories', array(
            'label'         => esc_html__( 'Show Post Categories', 'wpfelix' ),
            'section'       => 'wpfelix_section_general',
            'type'          => 'checkbox'
        ) );

        $wp_customize->add_setting( 'post_author', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'post_author', array(
            'label'         => esc_html__( 'Show Post Author Name', 'wpfelix' ),
            'section'       => 'wpfelix_section_general',
            'type'          => 'checkbox',
            'description'   => esc_html__( 'Works for single view, Full and Grid With Full Style posts list layout.', 'wpfelix' )
        ) );

        $wp_customize->add_setting( 'post_sharing', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'post_sharing', array(
            'label'         => esc_html__( 'Show Post Sharing Icons', 'wpfelix' ),
            'section'       => 'wpfelix_section_general',
            'type'          => 'checkbox',
            'description'   => esc_html__( 'Works for single view, Full and Grid With Full Style posts list layout.', 'wpfelix' )
        ) );

        $wp_customize->add_setting( 'post_comment', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'post_comment', array(
            'label'         => esc_html__( 'Show Post Comments Count', 'wpfelix' ),
            'section'       => 'wpfelix_section_general',
            'type'          => 'checkbox',
            'description'   => esc_html__( 'Works for single view, Full and Grid With Full Style posts list layout.', 'wpfelix' )
        ) );

        $wp_customize->add_setting( 'post_format_icon', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'post_format_icon', array(
            'label'         => esc_html__( 'Show Post Format Icon', 'wpfelix' ),
            'description'   => esc_html__( 'Works with grid and list layout.', 'wpfelix' ),
            'section'       => 'wpfelix_section_general',
            'type'          => 'checkbox'
        ) );

        $wp_customize->add_setting( 'back_to_top', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'back_to_top', array(
            'label'         => esc_html__( 'Back To Top Button', 'wpfelix' ),
            'section'       => 'wpfelix_section_general',
            'type'          => 'checkbox'
        ) );

        $wp_customize->add_setting( 'jetpack_css', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'jetpack_css', array(
            'label'         => esc_html__( 'Jetpack styles support', 'wpfelix' ),
            'description'   => esc_html__( 'If you want to custom jetpack module styles or use default styles then disable this.', 'wpfelix' ),
            'section'       => 'wpfelix_section_general',
            'type'          => 'checkbox'
        ) );

    /*--------------------------------------------------------------
    ## SINGLE POST
    --------------------------------------------------------------*/

    $wp_customize->add_section( 'wpfelix_section_post', array(
        'title'         => esc_html__( 'Single Post', 'wpfelix' ),
        'description'   => ''
    ) );

        $wp_customize->add_setting( 'layout_post', array( 'default' => 'default', 'sanitize_callback' => 'wpfelix_sanitize_choices' ) );
        $wp_customize->add_control( 'layout_post', array(
            'label'         => esc_html__( 'Post Layout', 'wpfelix' ),
            'description'   => esc_html__( 'If "Full" is selected, then all Sidebar Options will be passed.', 'wpfelix' ),
            'section'       => 'wpfelix_section_post',
            'type'          => 'radio',
            'choices'       => array(
                'default'       => esc_html__( 'Default', 'wpfelix' ),
                'full'          => esc_html__( 'Full', 'wpfelix' )
            )
        ) );

        $wp_customize->add_setting( 'post_tags', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'post_tags', array(
            'label'         => esc_html__( 'Show Tags', 'wpfelix' ),
            'section'       => 'wpfelix_section_post',
            'type'          => 'checkbox'
        ) );

        $wp_customize->add_setting( 'post_author_box', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'post_author_box', array(
            'label'         => esc_html__( 'Show Author Info Box', 'wpfelix' ),
            'section'       => 'wpfelix_section_post',
            'type'          => 'checkbox'
        ) );

        $wp_customize->add_setting( 'post_related', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'post_related', array(
            'label'         => esc_html__( 'Show Related Posts', 'wpfelix' ),
            'section'       => 'wpfelix_section_post',
            'type'          => 'checkbox'
        ) );


    /*--------------------------------------------------------------
    ## SIDEBAR
    --------------------------------------------------------------*/

    $wp_customize->add_section( 'wpfelix_section_sidebar', array(
        'title'         => esc_html__( 'Sidebar', 'wpfelix' ),
        'description'   => ''
    ) );

        $wp_customize->add_setting( 'sidebar_pos', array( 'default' => 'right', 'sanitize_callback' => 'wpfelix_sanitize_choices' ) );
        $wp_customize->add_control( 'sidebar_pos', array(
            'label'         => esc_html__( 'Sidebar Position', 'wpfelix' ),
            'section'       => 'wpfelix_section_sidebar',
            'type'          => 'radio',
            'choices'       => array(
                'left'  => esc_html__( 'Left', 'wpfelix' ),
                'right' => esc_html__( 'Right', 'wpfelix' )
            )
        ) );

        $wp_customize->add_setting( 'sidebar_size', array( 'default' => 'wide', 'sanitize_callback' => 'wpfelix_sanitize_choices' ) );
        $wp_customize->add_control( 'sidebar_size', array(
            'label'         => esc_html__( 'Sidebar Size', 'wpfelix' ),
            'section'       => 'wpfelix_section_sidebar',
            'type'          => 'radio',
            'choices'       => array(
                'wide'          => esc_html__( 'Wide', 'wpfelix' ),
                'narrow'        => esc_html__( 'Narrow', 'wpfelix' )
            )
        ) );

        $wp_customize->add_setting( 'sticky_sidebar_on', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'sticky_sidebar_on', array(
            'label'         => esc_html__( 'Sticky sidebar', 'wpfelix' ),
            'section'       => 'wpfelix_section_sidebar',
            'type'          => 'checkbox'
        ) );

        $wp_customize->add_setting( 'sidebar_home_disabled', array( 'default' => false, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'sidebar_home_disabled', array(
            'label'         => esc_html__( 'Disable Home Sidebar', 'wpfelix' ),
            'section'       => 'wpfelix_section_sidebar',
            'type'          => 'checkbox'
        ) );

        $wp_customize->add_setting( 'sidebar_archive_disabled', array( 'default' => false, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'sidebar_archive_disabled', array(
            'label'         => esc_html__( 'Disable Archive Sidebar', 'wpfelix' ),
            'section'       => 'wpfelix_section_sidebar',
            'type'          => 'checkbox'
        ) );

        $wp_customize->add_setting( 'sidebar_search_disabled', array( 'default' => false, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'sidebar_search_disabled', array(
            'label'         => esc_html__( 'Disable Search Results Sidebar', 'wpfelix' ),
            'section'       => 'wpfelix_section_sidebar',
            'type'          => 'checkbox'
        ) );

        $wp_customize->add_setting( 'sidebar_post_disabled', array( 'default' => false, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'sidebar_post_disabled', array(
            'label'         => esc_html__( 'Disable Post Sidebar', 'wpfelix' ),
            'section'       => 'wpfelix_section_sidebar',
            'type'          => 'checkbox'
        ) );

        $wp_customize->add_setting( 'lwr_area_home_disabled', array( 'default' => false, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'lwr_area_home_disabled', array(
            'label'         => esc_html__( 'Disable Lower Area on Home.', 'wpfelix' ),
            'section'       => 'wpfelix_section_sidebar',
            'type'          => 'checkbox'
        ) );

        $wp_customize->add_setting( 'lwr_area_archive_disabled', array( 'default' => false, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'lwr_area_archive_disabled', array(
            'label'         => esc_html__( 'Disable Lower Area on Archive.', 'wpfelix' ),
            'section'       => 'wpfelix_section_sidebar',
            'type'          => 'checkbox'
        ) );

        $wp_customize->add_setting( 'lwr_area_post_disabled', array( 'default' => false, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'lwr_area_post_disabled', array(
            'label'         => esc_html__( 'Disable Lower Area on Post.', 'wpfelix' ),
            'section'       => 'wpfelix_section_sidebar',
            'type'          => 'checkbox'
        ) );

        $wp_customize->add_setting( 'lwr_area_page_disabled', array( 'default' => false, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'lwr_area_page_disabled', array(
            'label'         => esc_html__( 'Disable Lower Area on Page.', 'wpfelix' ),
            'section'       => 'wpfelix_section_sidebar',
            'type'          => 'checkbox'
        ) );
        

    /*--------------------------------------------------------------
    ## EXTRAS
    --------------------------------------------------------------*/
    
    $wp_customize->add_section( 'wpfelix_section_footer', array(
        'title'         => esc_html__( 'Footer', 'wpfelix' ),
        'description'   => ''
    ) );

        $wp_customize->add_setting( 'footer_logo', array( 'theme_supports' => array( 'custom-logo' ), 'transport' => 'postMessage', 'sanitize_callback' => 'absint' ) );
        $wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'footer_logo', array(
            'label'         => esc_html__( 'Footer Logo', 'wpfelix' ),
            'section'       => 'wpfelix_section_footer',
            'width'         => 480,
            'height'        => 240,
            'flex-width'    => false,
            'flex-height'   => true
        ) ) );
        $wp_customize->selective_refresh->add_partial( 'footer_logo', array(
            'selector' => '#colophon .footer-logo-link',
            'container_inclusive' => true,
            'render_callback' => 'wpfelix_customize_partial_footer_logo',
        ) );

        $wp_customize->add_setting( 'footer_intro', array( 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_textarea_html' ) );
        $wp_customize->add_control( 'footer_intro', array(
            'label'         => esc_html__( 'Footer Intro Text', 'wpfelix' ),
            'section'       => 'wpfelix_section_footer',
            'type'          => 'textarea'
        ) );
        $wp_customize->selective_refresh->add_partial( 'footer_intro', array(
            'selector' => '#colophon .footer-intro',
            'container_inclusive' => false,
            'render_callback' => 'wpfelix_customize_partial_footer_intro',
        ) );

        $wp_customize->add_setting( 'footer_copyright', array( 'default' => '', 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_textarea_html' ) );
        $wp_customize->add_control( 'footer_copyright', array(
            'label'         => esc_html__( 'Copyright Info', 'wpfelix' ),
            'section'       => 'wpfelix_section_footer',
            'type'          => 'textarea'
        ) );
        $wp_customize->selective_refresh->add_partial( 'footer_copyright', array(
            'selector' => '#colophon .footer-copyright',
            'container_inclusive' => false,
            'render_callback' => 'wpfelix_customize_partial_footer_copyright',
        ) );

        $wp_customize->add_setting( 'footer_show_social', array( 'default' => true, 'sanitize_callback' => 'wpfelix_sanitize_checkbox' ) );
        $wp_customize->add_control( 'footer_show_social', array(
            'label'         => esc_html__( 'Show Social Icons', 'wpfelix' ),
            'section'       => 'wpfelix_section_footer',
            'type'          => 'checkbox'
        ) );
        
        $wp_customize->add_setting( 'footer_padding_top', array( 'default' => '52', 'sanitize_callback' => 'wpfelix_sanitize_padding' ) );
        $wp_customize->add_control( 'footer_padding_top', array(
            'type'          => 'number',
            'label'         => esc_html__( 'Footer Padding Top', 'wpfelix' ),
            'section'       => 'wpfelix_section_footer',
            'input_attrs' => array(
                'min' => 0,
                'max' => 1000,
                'step' => 1,
            )
        ) );

        $wp_customize->add_setting( 'footer_padding_bottom', array( 'default' => '36', 'sanitize_callback' => 'wpfelix_sanitize_padding' ) );
        $wp_customize->add_control( 'footer_padding_bottom', array(
            'type'          => 'number',
            'label'         => esc_html__( 'Footer Padding Bottom', 'wpfelix' ),
            'section'       => 'wpfelix_section_footer',
            'input_attrs' => array(
                'min' => 0,
                'max' => 1000,
                'step' => 1,
            )
        ) );


    /*--------------------------------------------------------------
    ## COLORS: GENERAL
    --------------------------------------------------------------*/

    $color_scheme = wpfelix_get_color_scheme();

    // Remove the core header textcolor control, as it shares the main text color.
    $wp_customize->remove_control( 'header_textcolor' );

    // Add color scheme setting and control.
    $wp_customize->add_setting( 'color_scheme', array(
        'default'           => 'default',
        'sanitize_callback' => 'wpfelix_sanitize_color_scheme',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( 'color_scheme', array(
        'label'    => esc_html__( 'Base Color Scheme', 'wpfelix' ),
        'description' => esc_html__( 'We saved default presets in case you need to reset color settings. Choose any other color scheme to start customizing.', 'wpfelix' ),
        'section'  => 'colors',
        'type'     => 'select',
        'choices'  => wpfelix_get_color_scheme_choices(),
        'priority' => 1,
    ) );

    $wp_customize->add_setting( 'background_color_alt', array( 'default' => $color_scheme[1], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
    $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'background_color_alt', array(
        'label'         => esc_html__( 'Background Alternate', 'wpfelix' ),
        'description'   => esc_html__( 'Background color for form fields and some other elements.', 'wpfelix' ),
        'section'       => 'colors',
        'settings'      => 'background_color_alt'
    ) ) );

    $wp_customize->add_setting( 'text_color', array( 'default' => $color_scheme[2], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
    $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'text_color', array(
        'label'         => esc_html__( 'Text', 'wpfelix' ),
        'section'       => 'colors',
        'settings'      => 'text_color'
    ) ) );

    $wp_customize->add_setting( 'text_strong_color', array( 'default' => $color_scheme[3], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
    $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'text_strong_color', array(
        'label'         => esc_html__( 'Highlight', 'wpfelix' ),
        'section'       => 'colors',
        'settings'      => 'text_strong_color'
    ) ) );

    $wp_customize->add_setting( 'text_weak_color', array( 'default' => $color_scheme[4], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
    $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'text_weak_color', array(
        'label'         => esc_html__( 'Muted', 'wpfelix' ),
        'section'       => 'colors',
        'settings'      => 'text_weak_color'
    ) ) );

    $wp_customize->add_setting( 'accent_color', array( 'default' => $color_scheme[5], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
    $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'accent_color', array(
        'label'         => esc_html__( 'Accent', 'wpfelix' ),
        'section'       => 'colors',
        'settings'      => 'accent_color'
    ) ) );

    $wp_customize->add_setting( 'border_color', array( 'default' => $color_scheme[6], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
    $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'border_color', array(
        'label'         => esc_html__( 'Border', 'wpfelix' ),
        'section'       => 'colors',
        'settings'      => 'border_color'
    ) ) );

    $wp_customize->add_setting( 'button_text_color', array( 'default' => $color_scheme[7], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
    $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'button_text_color', array(
        'label'         => esc_html__( 'Button', 'wpfelix' ),
        'section'       => 'colors',
        'settings'      => 'button_text_color'
    ) ) );

    $wp_customize->add_setting( 'button_hover_text_color', array( 'default' => $color_scheme[8], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
    $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'button_hover_text_color', array(
        'label'         => esc_html__( 'Button Hover', 'wpfelix' ),
        'section'       => 'colors',
        'settings'      => 'button_hover_text_color'
    ) ) );

    $wp_customize->add_setting( 'button_hover_background_color', array( 'default' => $color_scheme[9], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
    $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'button_hover_background_color', array(
        'label'         => esc_html__( 'Button Hover Background', 'wpfelix' ),
        'section'       => 'colors',
        'settings'      => 'button_hover_background_color'
    ) ) );

    
    /*--------------------------------------------------------------
    ## COLORS: HEADER
    --------------------------------------------------------------*/

    $wp_customize->add_section( 'wpfelix_section_color_header', array(
        'title'         => esc_html__( 'Colors: Header', 'wpfelix' ),
        'description'   => esc_html__( 'Please note that these colors might changed if you change color preset (see "Colors" section).', 'wpfelix' )
    ) );

        $wp_customize->add_setting( 'background_color_header', array( 'default' => $color_scheme[10], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'background_color_header', array(
            'label'         => esc_html__( 'Header Background', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_header',
            'settings'      => 'background_color_header'
        ) ) );

        $wp_customize->add_setting( 'background_color_navbar', array( 'default' => $color_scheme[11], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'background_color_navbar', array(
            'label'         => esc_html__( 'Navigation Background', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_header',
            'settings'      => 'background_color_navbar'
        ) ) );

        $wp_customize->add_setting( 'border_color_navbar', array( 'default' => $color_scheme[12], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'border_color_navbar', array(
            'label'         => esc_html__( 'Navigation Border', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_header',
            'settings'      => 'border_color_navbar'
        ) ) );

        $wp_customize->add_setting( 'link_color_navbar', array( 'default' => $color_scheme[13], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'link_color_navbar', array(
            'label'         => esc_html__( 'Navigation Link', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_header',
            'settings'      => 'link_color_navbar'
        ) ) );

        $wp_customize->add_setting( 'link_active_color_navbar', array( 'default' => $color_scheme[14], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'link_active_color_navbar', array(
            'label'         => esc_html__( 'Navigation Link Hover/Active', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_header',
            'settings'      => 'link_active_color_navbar'
        ) ) );

        $wp_customize->add_setting( 'border_color_navsub', array( 'default' => $color_scheme[15], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'border_color_navsub', array(
            'label'         => esc_html__( 'Sub Menu Border', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_header',
            'settings'      => 'border_color_navsub'
        ) ) );

        $wp_customize->add_setting( 'background_color_navsub', array( 'default' => $color_scheme[16], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'background_color_navsub', array(
            'label'         => esc_html__( 'Sub Menu Background', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_header',
            'settings'      => 'background_color_navsub'
        ) ) );

        $wp_customize->add_setting( 'link_color_navsub', array( 'default' => $color_scheme[17], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'link_color_navsub', array(
            'label'         => esc_html__( 'Sub Menu link', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_header',
            'settings'      => 'link_color_navsub'
        ) ) );

        $wp_customize->add_setting( 'link_active_color_navsub', array( 'default' => $color_scheme[18], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'link_active_color_navsub', array(
            'label'         => esc_html__( 'Sub Menu link Hover/Active', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_header',
            'settings'      => 'link_active_color_navsub'
        ) ) );

        $wp_customize->add_setting( 'link_active_background_color_navsub', array( 'default' => $color_scheme[19], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'link_active_background_color_navsub', array(
            'label'         => esc_html__( 'Sub Menu link Background Hover/Active', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_header',
            'settings'      => 'link_active_background_color_navsub'
        ) ) );

    /*--------------------------------------------------------------
    ## COLOR: EXTRAS
    --------------------------------------------------------------*/
    $wp_customize->add_section( 'wpfelix_section_color_extras', array(
        'title'         => esc_html__( 'Colors: Extras', 'wpfelix' ),
        'description'   => esc_html__( 'Please note that these colors might changed if you change color preset (see "Colors" section). The slideshow category text color can not be changed here, it will always be white.', 'wpfelix' )
    ) );

        $wp_customize->add_setting( 'text_color_category', array( 'default' => $color_scheme[20], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'text_color_category', array(
            'label'         => esc_html__( 'Categories Text', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_extras',
            'settings'      => 'text_color_category'
        ) ) );

        $wp_customize->add_setting( 'border_color_category', array( 'default' => $color_scheme[21], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'border_color_category', array(
            'label'         => esc_html__( 'Categories Border', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_extras',
            'settings'      => 'border_color_category'
        ) ) );

        $wp_customize->add_setting( 'background_color_category', array( 'default' => $color_scheme[22], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'background_color_category', array(
            'label'         => esc_html__( 'Categories Background', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_extras',
            'settings'      => 'background_color_category'
        ) ) );

        $wp_customize->add_setting( 'text_color_widget_title', array( 'default' => $color_scheme[23], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'text_color_widget_title', array(
            'label'         => esc_html__( 'Widget Title Text', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_extras',
            'settings'      => 'text_color_widget_title'
        ) ) );

        $wp_customize->add_setting( 'border_color_widget_title', array( 'default' => $color_scheme[24], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'border_color_widget_title', array(
            'label'         => esc_html__( 'Widget Title Border', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_extras',
            'settings'      => 'border_color_widget_title'
        ) ) );

        $wp_customize->add_setting( 'background_color_widget_title', array( 'default' => $color_scheme[25], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'background_color_widget_title', array(
            'label'         => esc_html__( 'Widget Title Background', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_extras',
            'settings'      => 'background_color_widget_title'
        ) ) );

        $wp_customize->add_setting( 'background_color_footer', array( 'default' => $color_scheme[26], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'background_color_footer', array(
            'label'         => esc_html__( 'Footer Background', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_extras',
            'settings'      => 'background_color_footer'
        ) ) );

        $wp_customize->add_setting( 'text_color_footer', array( 'default' => $color_scheme[27], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'text_color_footer', array(
            'label'         => esc_html__( 'Footer Text', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_extras',
            'settings'      => 'text_color_footer'
        ) ) );

        $wp_customize->add_setting( 'text_strong_color_footer', array( 'default' => $color_scheme[28], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'text_strong_color_footer', array(
            'label'         => esc_html__( 'Footer Highlight', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_extras',
            'settings'      => 'text_strong_color_footer'
        ) ) );

        $wp_customize->add_setting( 'text_weak_color_footer', array( 'default' => $color_scheme[29], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'text_weak_color_footer', array(
            'label'         => esc_html__( 'Footer Muted', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_extras',
            'settings'      > 'text_weak_color_footer'
        ) ) );
        
        $wp_customize->add_setting( 'border_color_footer', array( 'default' => $color_scheme[30], 'transport' => 'postMessage', 'sanitize_callback' => 'wpfelix_sanitize_color' ) );
        $wp_customize->add_control( new WPFelix_Customize_RGBA_Color_Control ( $wp_customize, 'border_color_footer', array(
            'label'         => esc_html__( 'Footer Border', 'wpfelix' ),
            'section'       => 'wpfelix_section_color_extras',
            'settings'      > 'border_color_footer'
        ) ) );

    /*--------------------------------------------------------------
    ## ORDERING SECTIONS
    --------------------------------------------------------------*/

    $wp_customize->get_section( 'title_tagline' )->priority = 10;
    $wp_customize->get_section( 'wpfelix_section_header' )->priority = 11;
    $wp_customize->get_panel(   'wpfelix_panel_social' )->priority = 12;
    $wp_customize->get_section( 'wpfelix_section_featured' )->priority = 13;
    $wp_customize->get_section( 'wpfelix_section_general' )->priority = 14;
    $wp_customize->get_section( 'wpfelix_section_post' )->priority = 15;
    $wp_customize->get_section( 'wpfelix_section_sidebar' )->priority = 16;
    $wp_customize->get_section( 'wpfelix_section_footer' )->priority = 17;
    $wp_customize->get_section( 'background_image' )->priority = 18;
    $wp_customize->get_section( 'colors' )->priority = 19;
    $wp_customize->get_section( 'wpfelix_section_color_header' )->priority = 20;
    $wp_customize->get_section( 'wpfelix_section_color_extras' )->priority = 21;
    

    /*--------------------------------------------------------------
    ## ACTIVE CALLBACKS
    --------------------------------------------------------------*/
    // $wp_customize->get_section( 'wpfelix_section_featured' )->active_callback = 'is_home';


    /*--------------------------------------------------------------
    ## REMOVE UNWANTED SECTIONS
    --------------------------------------------------------------*/
    // $wp_customize->remove_section( 'static_front_page' );
}
add_action( 'customize_register', 'wpfelix_customize_register' );


/**
 * Render the site title for the selective refresh partial.
 * @return void
 */
function wpfelix_customize_partial_blogname()
{
    bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 * @return void
 */
function wpfelix_customize_partial_blogdescription()
{
    bloginfo( 'description' );
}

/**
 * Render footer logo for the selective refresh partial.
 * @return void
 */
function wpfelix_customize_partial_footer_logo()
{
    $footer_logo = wp_get_attachment_image_src( get_theme_mod( 'footer_logo', 0 ), 'full' );
    $html = '';

    // We have a logo. Logo is go.
    if ( $footer_logo )
    {
        $html = sprintf( '<a href="%1$s" class="footer-logo-link" rel="home"><img class="footer-logo-img" src="%2$s" alt="footer-logo-img"/></a>',
            esc_url( home_url( '/' ) ),
            esc_url( $footer_logo[0] )
        );
    }

    // If no logo is set but we're in the Customizer, leave a placeholder (needed for the live preview).
    elseif ( is_customize_preview() )
    {
        $html = sprintf( '<a href="%1$s" class="footer-logo-link" style="display:none;"><img class="footer-logo-img"/></a>',
            esc_url( home_url( '/' ) )
        );
    }

    return $html;
}


/**
 * Render footer intro text for the selective refresh partial.
 * @return void
 */
function wpfelix_customize_partial_footer_intro()
{
    $html = '';
    $intro = get_theme_mod( 'footer_intro', '' );
    
    if ( $intro )
    {
        $html = wp_kses( $intro, wpfelix_global_kses( 'blockquote' ) );
    }
    return $html;
}


/**
 * Render footer copyright text for the selective refresh partial.
 * @return void
 */
function wpfelix_customize_partial_footer_copyright()
{
    $html = '';
    $copyright = get_theme_mod( 'footer_copyright', '' );
    
    if ( $copyright )
    {
        $html = wp_kses( $copyright, wpfelix_global_kses( 'blockquote' ) );
    }
    return $html;
}


/**
 * Output an Underscore template for generating CSS for the color scheme.
 * The template generates the css dynamically for instant display in the Customizer preview.
 */
function wpfelix_color_scheme_css_template()
{
    $colors = array(
        'background_color'                      => '{{ data.background_color }}',
        'background_color_alt'                  => '{{ data.background_color_alt }}',
        'text_color'                            => '{{ data.text_color }}',
        'text_strong_color'                     => '{{ data.text_strong_color }}',
        'text_weak_color'                       => '{{ data.text_weak_color }}',
        'accent_color'                          => '{{ data.accent_color }}',
        'border_color'                          => '{{ data.border_color }}',
        'button_text_color'                     => '{{ data.button_text_color }}',
        'button_hover_text_color'               => '{{ data.button_hover_text_color }}',
        'button_hover_background_color'         => '{{ data.button_hover_background_color }}',
        'background_color_header'               => '{{ data.background_color_header }}',
        'background_color_navbar'               => '{{ data.background_color_navbar }}',
        'border_color_navbar'                   => '{{ data.border_color_navbar }}',
        'link_color_navbar'                     => '{{ data.link_color_navbar }}',
        'link_active_color_navbar'              => '{{ data.link_active_color_navbar }}',
        'border_color_navsub'                   => '{{ data.border_color_navsub }}',
        'background_color_navsub'               => '{{ data.background_color_navsub }}',
        'link_color_navsub'                     => '{{ data.link_color_navsub }}',
        'link_active_color_navsub'              => '{{ data.link_active_color_navsub }}',
        'link_active_background_color_navsub'   => '{{ data.link_active_background_color_navsub }}',
        'text_color_category'                   => '{{ data.text_color_category }}',
        'border_color_category'                 => '{{ data.border_color_category }}',
        'background_color_category'             => '{{ data.background_color_category }}',
        'text_color_widget_title'               => '{{ data.text_color_widget_title }}',
        'border_color_widget_title'             => '{{ data.border_color_widget_title }}',
        'background_color_widget_title'         => '{{ data.background_color_widget_title }}',
        'background_color_footer'               => '{{ data.background_color_footer }}',
        'text_color_footer'                     => '{{ data.text_color_footer }}',
        'text_strong_color_footer'              => '{{ data.text_strong_color_footer }}',
        'text_weak_color_footer'                => '{{ data.text_weak_color_footer }}',
        'border_color_footer'                   => '{{ data.border_color_footer }}'
    );
    ?>
    <script type="text/html" id="tmpl-wpfelix-color-scheme">
        <?php echo wpfelix_customizer_css_output( $colors ); ?>
    </script>
    <?php
}
add_action( 'customize_controls_print_footer_scripts', 'wpfelix_color_scheme_css_template' );
