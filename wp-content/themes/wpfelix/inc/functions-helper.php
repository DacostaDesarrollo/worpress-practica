<?php
/**
 * Helper functions for the theme
 *
 * @package WPFelix
 */


if ( ! function_exists( 'wpfelix_fonts_url' ) )
{
    /**
     * Register Google fonts for wpfelix.
     *
     * Create your own wpfelix_fonts_url() function to override in a child theme.
     *
     * @since 1.0.5
     *
     * @return string Google fonts URL for the theme.
     */
    function wpfelix_fonts_url()
    {
        $fonts_url = '';
        $fonts     = array();
        $subsets   = 'latin,latin-ext';

        /* translators: If there are characters in your language that are not supported by PT Serif, translate this to 'off'. Do not translate into your own language. */
        if ( 'off' !== _x( 'on', 'PT Serif font: on or off', 'wpfelix' ) )
        {
            $fonts[] = 'PT Serif:400,400italic,700,700italic';
        }

        /* translators: If there are characters in your language that are not supported by Poppins, translate this to 'off'. Do not translate into your own language. */
        if ( 'off' !== _x( 'on', 'Poppins font: on or off', 'wpfelix' ) )
        {
            $fonts[] = 'Poppins:400,500,600';
        }

        if ( $fonts ) {
            $fonts_url = add_query_arg( array(
                'family' => urlencode( implode( '|', $fonts ) ),
                'subset' => urlencode( $subsets ),
            ), 'https://fonts.googleapis.com/css' );
        }

        return $fonts_url;
    }
}


/**
 * Prints main navigation classes
 * @param  string $extra_classes
 */
function wpfelix_main_navigation_class( $extra_classes = '' )
{
    $classes = array();

    if ( strlen( $extra_classes ) > 0 )
    {
        $classes[] = $extra_classes;
    }

    if ( 'bottom' === get_theme_mod( 'header_nav_pos', 'bottom' ) )
    {
        $classes[] = 'main-nav-bottom';
    }
    else
    {
        $classes[] = 'main-nav-top';
    }

    switch ( get_theme_mod( 'header_nav_align' ) )
    {
        case 'right':
            $classes[] = 'main-nav-right';
            break;

        case 'left':
            $classes[] = 'main-nav-left';
            break;

        // Default to center
        default:
            $classes[] = 'main-nav-center';
            break;
    }

    switch ( get_theme_mod( 'header_social_align' ) )
    {
        case 'right':
            $classes[] = 'main-nav-social-right';
            break;

        // Default to left
        default:
            $classes[] = 'main-nav-social-left';
            break;
    }

    switch ( get_theme_mod( 'header_nav_extras_align' ) )
    {
        case 'left':
            $classes[] = 'main-nav-extras-left';
            break;

        // Default to right
        default:
            $classes[] = 'main-nav-extras-right';
            break;
    }

    $classes = implode( ' ', $classes );

    printf( 'class="main-navigation%s"', strlen( $classes ) > 0 ? ' ' . esc_attr( $classes ) : '' );
}


/**
 * Get titles of current view
 * @return array  Array keys contain 'title', 'subtitle'
 */
function wpfelix_get_page_titles()
{
    $title = $subtitle = '';

    //-- Default titles
    if ( ! is_archive() )
    {
        //-- Posts page view
        if ( is_home() )
        {
            $title = single_post_title( '', false );
        }
        //-- Single page view
        elseif ( is_page() )
        {
            $title = get_the_title();
        }
        //-- 404
        elseif ( is_404() )
        {
            $title = esc_html__( '404', 'wpfelix' );
        }
        //-- Search result
        elseif ( is_search() )
        {
            $subtitle = sprintf( esc_html__( 'You searched for: "%s"', 'wpfelix' ), get_search_query() );
            $title = esc_html__( 'Search results', 'wpfelix' );
        }
        //-- Anything else
        else {
            $title = get_the_title();
        }
    }
    else {
        //-- Category
        if ( is_category() )
        {
            $title = single_cat_title( '', false );
            $subtitle = category_description();
        }
        //-- Tag
        elseif ( is_tag() )
        {
            $title = single_tag_title( '', false );
        }
        //-- Author
        elseif ( is_author() )
        {
            $title = '<span class="vcard">' . get_the_author() . '</span>';
        }
        //-- Day
        elseif ( is_day() )
        {
            $title = get_the_date();
        }
        //-- Month
        elseif ( is_month() )
        {
            $title = get_the_date( 'F Y' );
        }
        //-- Year
        elseif ( is_year() )
        {
            $title = get_the_date( 'Y' );
        }
        //-- Post Format
        elseif ( is_tax( 'post_format', 'post-format-aside' ) )
        {
            $title = esc_html__( 'Asides', 'wpfelix' );
        }
        elseif ( is_tax( 'post_format', 'post-format-gallery' ) )
        {
            $title = esc_html__( 'Galleries', 'wpfelix' );
        }
        elseif ( is_tax( 'post_format', 'post-format-image' ) )
        {
            $title = esc_html__( 'Images', 'wpfelix' );
        }
        elseif ( is_tax( 'post_format', 'post-format-video' ) )
        {
            $title = esc_html__( 'Images', 'wpfelix' );
        }
        elseif ( is_tax( 'post_format', 'post-format-quote' ) )
        {
            $title = esc_html__( 'Quotes', 'wpfelix' );
        }
        elseif ( is_tax( 'post_format', 'post-format-link' ) )
        {
            $title = esc_html__( 'Links', 'wpfelix' );
        }
        elseif ( is_tax( 'post_format', 'post-format-status' ) )
        {
            $title = esc_html__( 'Statuses', 'wpfelix' );
        }
        elseif ( is_tax( 'post_format', 'post-format-audio' ) )
        {
            $title = esc_html__( 'Audios', 'wpfelix' );
        }
        elseif ( is_tax( 'post_format', 'post-format-chat' ) )
        {
            $title = esc_html__( 'Chats', 'wpfelix' );
        }
        else
        {
            /* other */
            $title = get_the_archive_title();
        }
    }

    return array(
        'title' => $title,
        'subtitle' => $subtitle
    );
}


/**
 * Generates an excerpt from the post content with custom length. Default length is 55 words.
 *
 * The excerpt word amount will be 55 words and if the amount is greater than
 * that, then the string '&hellip;' will be appended to the excerpt. If the string
 * is less than 55 words, then the content will be returned as is.
 *
 * @param int $length Optional. Custom excerpt length, default to 55.
 * @param int|WP_Post $post Optional. You will need to provide post id or post object if used outside loops.
 * @return string The excerpt with custom length.
 */
function wpfelix_get_the_excerpt( $length = 55, $post = null )
{
    $post = get_post( $post );

    if ( empty( $post ) || 0 >= $length )
    {
        return '';
    }

    if ( post_password_required( $post ) )
    {
        return esc_html__( 'Post password required.', 'wpfelix' );
    }

    $content = apply_filters( 'the_content', strip_shortcodes( $post->post_content ) );
    $content = str_replace( ']]>', ']]&gt;', $content );

    $excerpt_more = apply_filters( 'wpfelix_excerpt_more', '&hellip;' );
    $excerpt = wp_trim_words( $content, $length, $excerpt_more );

    return $excerpt;
}


/**
 * Get the first matched shortcode output from content
 * 
 * @param   string $shortcode_tag
 * @param   bool $first_only Only get the first match
 * @return  string|false Shortcode string or empty string on fail
 */
function wpfelix_get_shortcode_string( $shortcode_tag = '', $first_only = true )
{
    global $post;

    $result = '';
    $content = $post->post_content;

    $pattern = get_shortcode_regex( array( $shortcode_tag ) );

    preg_match( "/$pattern/", $content, $matches );

    if ( ! empty( $matches[0]) )
    {
        $result = $matches[0];
    }

    if ( $first_only && '' != $result )
    {
        if ( 0 !== strpos( $content, $result ) )
        {
            $result = '';
        }
    }

    return $result;
}


/**
 * Get the first matched element from content, we will use this to make featured content display at very first place of post content.
 * Supported for quote, link and image post format.
 * @param bool $first_only Only get the first match
 * @return  string|false Element string or emty string on fail
 */
function wpfelix_get_element_string( $first_only = true )
{
    global $post;

    $result = '';
    $content = $post->post_content;

    switch ( get_post_format( $post ) )
    {
        case 'quote':
            $pattern = '/<blockquote>(.*?)<\/blockquote>/s';
            break;

        case 'link':
            $pattern = '/<a.*?\>(.*?)<\/a>/m';
            break;

        default:
            $pattern = '';
            break;
    }

    if ( '' !== $pattern )
    {
        preg_match( $pattern, $content, $matches );

        $matches[0] = trim( $matches[0] );

        if ( ! empty( $matches[0] ) )
        {
            $result = $matches[0];
        }
    }

    if ( $first_only && '' != $result )
    {
        if ( 0 !== strpos( $content, $result ) )
        {
            $result = '';
        }
    }

    return trim( $result );
}


if ( ! function_exists( 'wpfelix_global_kses' ) ) :
/**
 * Global kses allowed HTMl use for some elements
 * @param  string $type Can be one of these 'blockquote', 'blockquote-footer'
 * @return array
 */
function wpfelix_global_kses( $type = '' )
{
    $result = array();

    switch ( $type )
    {
        case 'blockquote':
            $result = array(
                'p' => array( 'style' => array(), 'class' => array(), 'id' => array() ),
                'span' => array( 'style' => array(), 'class' => array(), 'id' => array() ),
                'strong' => array( 'style' => array(), 'class' => array(), 'id' => array() ),
                'em' => array( 'style' => array(), 'class' => array(), 'id' => array() ),
                'q' => array( 'style' => array(), 'class' => array(), 'id' => array() ),
                'a' => array( 'href' => array(), 'rel' => array(), 'style' => array(), 'class' => array(), 'id' => array() ),
                'i' => array( 'style' => array(), 'class' => array(), 'id' => array() )
            );
            break;

        case 'blockquote-footer':
            $result = array(
                'cite' => array( 'style' => array(), 'class' => array(), 'id' => array() ),
                'span' => array( 'style' => array(), 'class' => array(), 'id' => array() ),
                'strong' => array( 'style' => array(), 'class' => array(), 'id' => array() ),
                'em' => array( 'style' => array(), 'class' => array(), 'id' => array() ),
                'q' => array( 'style' => array(), 'class' => array(), 'id' => array() ),
                'a' => array( 'href' => array(), 'rel' => array(), 'style' => array(), 'class' => array(), 'id' => array() ),
                'i' => array( 'style' => array(), 'class' => array(), 'id' => array() )
            );
            break;

        case 'link':
            $result = array(
                'span' => array( 'style' => array(), 'class' => array(), 'id' => array() ),
                'strong' => array( 'style' => array(), 'class' => array(), 'id' => array() ),
                'em' => array( 'style' => array(), 'class' => array(), 'id' => array() ),
                'q' => array( 'style' => array(), 'class' => array(), 'id' => array() ),
                'i' => array( 'style' => array(), 'class' => array(), 'id' => array() )
            );
            break;

        default:
            
            break;
    }

    return $result;
}
endif; // wpfelix_global_kses


/**
 * Beautify numeric based on its value. For example 1000 = 1K, 1000000 = 1M and so on
 * @param  int      $number     The input number
 * @param  string   $suffix     Suffix to add after the number, for example "+" will get 10K+
 * @param  int      $min_value  Minimum value to be beaufity
 * @param  int      $decimal    Number of decimal number after "."
 * @return string
 */
function wpfelix_beautify_number( $number, $suffix = '+', $min_value = 1000, $decimal = 1 ) {
    if ( $number < $min_value )
    {
        return number_format_i18n( $number );
    }
    $alphabets = array( 1000000000 => 'B', 1000000 => 'M', 1000 => 'K' );
    foreach( $alphabets as $key => $value )
    {
        if ( $number >= $key )
        {
            return sprintf( '%1$s%2$s%3$s', round( $number / $key, $decimal ), $value, $suffix );
        }
    }
}


/**
 * Validate color string for hex and rgba;
 * @param  string $color_string
 * @return boolean
 */
function wpfelix_validate_color( $color_string = "" )
{
    $color_string = preg_replace( "/\s+/m", '', $color_string );

    if ( $color_string === 'transparent' )
    {
        return true;
    }

    if ( '' == $color_string ) return false;

    if ( preg_match( "/(?:^#[a-fA-F0-9]{6}$)|(?:^#[a-fA-F0-9]{3}$)/", $color_string ) ) return true;

    if ( preg_match( "/(?:^rgba\(\d+\,\d+\,\d+\,(?:\d*(?:\.\d+)?)\)$)|(?:^rgb\(\d+\,\d+\,\d+\)$)/", $color_string ) )
    {
        preg_match_all( "/\d+\.*\d*/", $color_string, $matches );
        if ( empty( $matches ) || empty( $matches[0] ) ) return false;

        $red = empty( $matches[0][0] ) ? $matches[0][0] : 0;
        $green = empty( $matches[0][1] ) ? $matches[0][1] : 0;
        $blue = empty( $matches[0][2] ) ? $matches[0][2] : 0;
        $alpha = empty( $matches[0][3] ) ? $matches[0][3] : 1;

        if ( $red < 0 || $red > 255 || $green < 0 || $green > 255 || $blue < 0 || $blue > 255 || $alpha < 0 || $alpha > 1.0 ) return false;
    }
    else
    {
        return false;
    }
    return true;
}


/**
 * Validate CSS unit string ( px, em, cm, mm, in etc. )
 *
 * @see http://www.w3schools.com/cssref/css_units.asp
 * @param  string $str      The CSS property string, eg: 10px or 1.5
 * @param  string $unit     Optional. Default unit just in case if input string has no unit and you want it to has one.
 * @return string|boolean
 */
function wpfelix_validate_css_unit( $str, $unit = 'px' )
{
    if ( empty( $str ) )
    {
        return false;
    }
    $pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
    $regexr = preg_match( $pattern, $str, $matches );
    $str = isset( $matches[1] ) ? (float) $matches[1] : (float) $str;
    $unit = isset( $matches[2] ) ? $matches[2] : $unit;
    $str = $str . $unit;
    return empty( $str ) ? false : $str;
}


/**
 * This function minify css
 * @param  string $css
 * @return string
 */
function wpfelix_css_minifier( $css )
{
    // Normalize whitespace
    $css = preg_replace( '/\s+/', ' ', $css );
    // Remove spaces before and after comment
    $css = preg_replace( '/(\s+)(\/\*(.*?)\*\/)(\s+)/', '$2', $css );
    // Remove comment blocks, everything between /* and */, unless
    // preserved with /*! ... */ or /** ... */
    $css = preg_replace( '~/\*(?![\!|\*])(.*?)\*/~', '', $css );
    // Remove ; before }
    $css = preg_replace( '/;(?=\s*})/', '', $css );
    // Remove space after , : ; { } */ >
    $css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );
    // Remove space before , ; { } ( ) >
    $css = preg_replace( '/ (,|;|\{|}|\(|\)|>)/', '$1', $css );
    // Strips leading 0 on decimal values (converts 0.5px into .5px)
    $css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
    // Strips units if value is 0 (converts 0px to 0)
    $css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );
    // Converts all zeros value into short-hand
    $css = preg_replace( '/0 0 0 0/', '0', $css );
    // Shortern 6-character hex color codes to 3-character where possible
    $css = preg_replace( '/#([a-f0-9])\\1([a-f0-9])\\2([a-f0-9])\\3/i', '#\1\2\3', $css );
    return trim( $css );
}
