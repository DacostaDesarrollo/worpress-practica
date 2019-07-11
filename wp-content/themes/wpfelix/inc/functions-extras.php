<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package WPFelix
 * @since   WPFelix 1.0
 */


if ( ! is_customize_preview() )
{
    /**
     * Remove custom logo itemprop validation error
     * @todo delete this filter after wordpress custom logo itemprop and Validation update
     */
    function wpfelix_get_custom_logo( $html )
    {
        $html = str_replace( ' itemprop="url"', '', $html );
        $html = str_replace( ' itemprop="logo"', '', $html );
        return $html;
    }
    add_filter( 'get_custom_logo', 'wpfelix_get_custom_logo' );

}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function wpfelix_body_classes( $classes )
{
    // Adds a class of group-blog to blogs with more than 1 published author.
    if ( is_multi_author() )
    {
        $classes[] = 'group-blog';
    }

    // Adds a class of hfeed to non-singular pages.
    if ( ! is_singular() )
    {
        $classes[] = 'hfeed';
    }

    if ( is_home() )
    {
        if ( get_theme_mod( 'featured', false ) )
        {
            $classes[] = 'home-with-banner';
        } 
    }

    return $classes;
}
add_filter( 'body_class', 'wpfelix_body_classes' );


/**
 * Custom password form. We add a css class to form-field and change button.
 */
function wpfelix_password_form()
{
    global $post;

    $label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
    $output = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post">
    <p>' . esc_html__( 'This content is password protected. To view it please enter your password below:', 'wpfelix' ) . '</p>
    <p><label for="' . $label . '">' . esc_html__( 'Password:', 'wpfelix' ) . ' <input class="form-field" name="post_password" id="' . $label . '" type="password" size="20" /></label> <input class="button" type="submit" name="Submit" value="' . esc_attr_x( 'Enter', 'post password form', 'wpfelix' ) . '" /></p></form>
    ';
    return $output;
}
add_filter( 'the_password_form', 'wpfelix_password_form' );


/**
 * Allow child theme to override this
 * @since 1.0.1
 */
if ( ! function_exists( 'wpfelix_user_contactmethods' ) )
{
    /**
     * New contact methods for author - Social profiles
     */
    function wpfelix_user_contactmethods( $methods )
    {
        $methods['wpfelix_facebook'] = esc_html( 'Facebook' );
        $methods['wpfelix_twitter'] = esc_html( 'Twitter' );
        $methods['wpfelix_linkedin'] = esc_html( 'Linkedin' );
        $methods['wpfelix_pinterest'] = esc_html( 'Pinterest' );
        $methods['wpfelix_instagram'] = esc_html( 'Instagram' );
        $methods['wpfelix_google'] = esc_html( 'Google' );
        $methods['wpfelix_dribbble'] = esc_html( 'Dribbble' );
        $methods['wpfelix_flickr'] = esc_html( 'Flickr' );
        $methods['wpfelix_behance'] = esc_html( 'Behance' );
        $methods['wpfelix_tumblr'] = esc_html( 'Tumblr' );
        $methods['wpfelix_youtube'] = esc_html( 'Youtube' );
        $methods['wpfelix_vimeo'] = esc_html( 'Vimeo' );
        $methods['wpfelix_soundcloud'] = esc_html( 'Soundcloud' );
        $methods['wpfelix_vk'] = esc_html( 'Vk' );
        $methods['wpfelix_reddit'] = esc_html( 'Reddit' );
        $methods['wpfelix_yahoo'] = esc_html( 'Yahoo' );
        $methods['wpfelix_github'] = esc_html( 'Github' );
        $methods['wpfelix_rss'] = esc_html( 'Rss' );
        $methods['wpfelix_email'] = esc_html( 'Email' );

        return $methods;
    }
}
add_filter( 'user_contactmethods', 'wpfelix_user_contactmethods', 10, 1 );


/**
 * Default cropping for images
 */
function wpfelix_after_switch_theme()
{
    if ( get_theme_mod( 'first_active', false ) )
    {
        return;
    }
    else
    {
        set_theme_mod( 'first_active', '1' );
        update_option( 'thumbnail_size_w', 140 );
        update_option( 'thumbnail_size_h', 140 );
        update_option( 'thumbnail_crop', 1 );
        update_option( 'medium_size_w', 570 );
        update_option( 'medium_size_h', 422 );
        update_option( 'medium_crop', 1 );
        update_option( 'large_size_w', 770 ); 
        update_option( 'large_size_h', 570 );
        update_option( 'large_crop', 1 );
        update_option( 'date_format', 'j F Y' );
    }
}
add_action( 'after_switch_theme', 'wpfelix_after_switch_theme' );
