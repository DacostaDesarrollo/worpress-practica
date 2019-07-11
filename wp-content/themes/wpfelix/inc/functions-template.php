<?php
/**
 * Template tag Functions
 *
 * @package WPFelix
 */


/**
 * Get content/widget area css classes based on customize
 * @return array {
 *     string ['primary'] Primary classes
 *     string ['secondary'] Secondary classes
 * }
 */
function wpfelix_content_sidebar_class( $index )
{
    $results = array(
        'primary' => '',
        'secondary' => ''
    );

    $sidebar_size = get_theme_mod( 'sidebar_size', 'wide' );
    $sidebar_pos = get_theme_mod( 'sidebar_pos', 'right' );
    $sidebar_disabled = get_theme_mod( 'sidebar_home_disabled' );

    if ( is_archive() )
    {
        $sidebar_disabled = get_theme_mod( 'sidebar_archive_disabled' );
    }

    if ( is_single() )
    {
        $single_layout = get_theme_mod( 'layout_post', 'default' );
        $sidebar_disabled = get_theme_mod( 'sidebar_post_disabled' );
    }

    if ( is_search() )
    {
        $sidebar_disabled = get_theme_mod( 'sidebar_search_disabled' );
    }

    if ( $sidebar_disabled )
    {
        $results['primary'] = ' without-sidebar';
        $sidebar_pos = '';
    }
    else
    {
        $results['primary'] = ' with-sidebar';

        if ( 'left' === $sidebar_pos )
        {
            $results['primary'] .= ' with-sidebar-left';
            $results['secondary'] = ' sidebar-left';
        }
        else
        {
            $results['primary'] .= ' with-sidebar-right';
            $results['secondary'] = ' sidebar-right';
        }

        if ( 'narrow' === $sidebar_size )
        {
            $results['primary'] .= ' with-sidebar-narrow grid-l9';
            $results['secondary'] .= ' sidebar-narrow grid-m8 grid-offset-m2 grid-l3 grid-offset-l0';
            if ( 'left' == $sidebar_pos ) {
                $results['primary'] .= ' grid-push-l3';
                $results['secondary'] .= ' grid-pull-l9';
            }
        }
        else
        {
            $results['primary'] .= ' grid-l8';
            $results['secondary'] .= ' grid-l4';
            if ( 'left' == $sidebar_pos ) {
                $results['primary'] .= ' grid-push-l4';
                $results['secondary'] .= ' grid-pull-l8';
            }
        }
    }

    if ( isset( $single_layout ) && 'full' == $single_layout )
    {
        $results['primary'] = ' without-sidebar grid-l10 grid-offset-l1 grid-xl8 grid-offset-xl2';
        $results['secondary'] = '';
    }

    if ( 'primary' === $index )
    {
        return $results['primary'];
    }
    if ( 'secondary' == $index )
    {
        return $results['secondary'];
    }

    return '';
}


/**
 * Prints excerpt based on content/excerpt with custom length
 * @param  integer $length Optional. Default to 55
 */
function wpfelix_the_excerpt( $length = 55 )
{
    $length = absint( $length );

    if ( 0 >= $length || 1000 < $length )
    {
        $length = 55;
    }
    echo wpfelix_get_the_excerpt( $length, get_the_ID() );
}


/**
 * Prints post thumbnail with caption HTML markup. Must used inside a loop
 * @param string $size Image size, can be 'thumbnail', 'medium', 'large', 'full' or user-defined size.
 * @param string $align Alignment, can be 'left', 'right', 'center', 'center'
 * @return void
 */
function wpfelix_the_post_thumbnail_caption( $size = 'post-thumbnail', $align = "center" )
{
    if ( ! has_post_thumbnail() )
    {
        return;
    }

    $thumbnail_id = get_post_thumbnail_id( get_the_ID() );
    $thumbnail_caption = wp_get_attachment_caption( $thumbnail_id );
    $thumbnail_meta = wp_get_attachment_metadata( $thumbnail_id );
    $thumbnail_width = 0;

    if ( ! $thumbnail_caption )
    {
        the_post_thumbnail( $size );
        return;
    }

    if ( ! empty( $thumbnail_meta['sizes'][$size]['width'] ) )
    {
        $thumbnail_width = $thumbnail_meta['sizes'][$size]['width'];
    }
    elseif ( ! empty( $thumbnail_meta['width'] ) )
    {
        $thumbnail_width = $thumbnail_meta['width'];
    }
    else
    {
        $thumbnail_width = get_option( 'thumbnail_size_w', 140 );
    }

    $shortcode = sprintf(
        '[caption id="attachment_%1$s" align="align%2$s" width="%3$s"]%4$s%5$s[/caption]',
        esc_attr( $thumbnail_id ),
        esc_attr( $align ),
        esc_attr( intval( $thumbnail_width ) ),
        get_the_post_thumbnail( get_the_ID(), $size ),
        $thumbnail_caption
    );

    echo do_shortcode( $shortcode );
}


/**
 * Strip first shortcode/element string from the content and prints the content
 * @param  string  $strip_string    Things to be stripped
 * @param  string  $more_link_text
 * @param  boolean $strip_teaser
 * @return void
 */
function wpfelix_the_content_strip_first_shortcode( $strip_string = '', $more_link_text = null, $strip_teaser = false )
{
    $cur_post = get_post( get_the_ID() );

    $content = $cur_post->post_content;

    if ( empty( $strip_string ) || 0 !== strpos( $content, $strip_string ) )
    {
        the_content( $more_link_text, $strip_teaser );
    }
    else
    {
        /**
         * @see the_content()
         */
        $content = get_the_content( $more_link_text, $strip_teaser );
        $content = substr_replace( $content, '', 0, strlen( $strip_string ) );
        $content = apply_filters( 'the_content', $content );

        echo str_replace( ']]>', ']]&gt;', $content );
    }
}


/**
 * Strip first shortcode/element string from the content and prints the content
 * @param  string  $strip_string    Things to be stripped
 * @param  string  $more_link_text
 * @param  boolean $strip_teaser
 * @return void
 */
function wpfelix_the_content_strip_shortcode_string( $strip_string = '', $more_link_text = null, $strip_teaser = false )
{
    $cur_post = get_post( get_the_ID() );

    $content = $cur_post->post_content;

    if ( empty( $strip_string ) || 0 !== strpos( $content, $strip_string ) )
    {
        the_content( $more_link_text, $strip_teaser );
    }
    else
    {
        /**
         * @see the_content()
         */
        $content = get_the_content( $more_link_text, $strip_teaser );
        $content = substr_replace( $content, '', 0, strlen( $strip_string ) );
        $content = apply_filters( 'the_content', $content );

        echo str_replace( ']]>', ']]&gt;', $content );
    }
}


/**
 * Prints social profile links output HTML markup
 * @param  string $extra_class
 */
function wpfelix_social_markup( $extra_class = '' )
{
    ob_start();

    $link = get_theme_mod( 'facebook', '#' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="facebook"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-facebook"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Facebook' )
        );
    }
    
    $link = get_theme_mod( 'twitter', '#' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="twitter"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-twitter"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Twitter' )
        );
    }

    $link = get_theme_mod( 'linkedin', '#' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="linkedin"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-linkedin"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Linkedin' )
        );
    }

    $link = get_theme_mod( 'pinterest', '#' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="pinterest"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-pinterest-p"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Pinterest' )
        );
    }

    $link = get_theme_mod( 'instagram' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="instagram"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-instagram"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Instagram' )
        );
    }

    $link = get_theme_mod( 'google' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="google"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-google-plus"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Google' )
        );
    }

    $link = get_theme_mod( 'dribbble' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="dribbble"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-dribbble"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Dribbble' )
        );
    }

    $link = get_theme_mod( 'flickr' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="flickr"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-flickr"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Flickr' )
        );
    }

    $link = get_theme_mod( 'behance' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="behance"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-behance"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Behance' )
        );
    }

    $link = get_theme_mod( 'tumblr' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="tumblr"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-tumblr"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Tumblr' )
        );
    }

    $link = get_theme_mod( 'youtube' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="youtube"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-youtube"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Youtube' )
        );
    }

    $link = get_theme_mod( 'vimeo' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="vimeo"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-vimeo"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Vimeo' )
        );
    }

    $link = get_theme_mod( 'soundcloud' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="soundcloud"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-soundcloud"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Soundcloud' )
        );
    }

    $link = get_theme_mod( 'vk' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="vk"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-vk"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Vk' )
        );
    }

    $link = get_theme_mod( 'reddit' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="reddit"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-reddit"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Reddit' )
        );
    }

    $link = get_theme_mod( 'yahoo' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="yahoo"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-yahoo"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Yahoo' )
        );
    }

    $link = get_theme_mod( 'github' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="github"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-github"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Github' )
        );
    }

    $link = get_theme_mod( 'rss' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="rss"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-rss"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Rss' )
        );
    }

    $link = get_theme_mod( 'email' );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="email"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-envelope"></i></a></li>',
            esc_url( 'mailto:' . $link ),
            esc_html( 'Email' )
        );
    }

    $output = ob_get_clean();

    printf(
        '<ul class="list-social%1$s">%2$s</ul>',
        ( strlen( $extra_class ) > 0 ? ' ' . esc_attr( $extra_class ) : '' ),
        $output
    );
}

/**
 * Prints social share links/icons based on theme mods.
 */
function wpfelix_social_shares()
{
    if ( ! class_exists( 'WPFelix_Social_Share' ) || ! get_theme_mod( 'share_on', true ) )
    {
        return;
    }
    
    $link_args['facebook'] = get_theme_mod( 'share_facebook', true ) ? true : false;
    $link_args['twitter'] = get_theme_mod( 'share_twitter', true ) ? true : false;
    $link_args['google'] = get_theme_mod( 'share_google', true ) ? true : false;
    $link_args['pinterest'] = get_theme_mod( 'share_pinterest', true ) ? true : false;
    $link_args['linkedin'] = get_theme_mod( 'share_linkedin', true ) ? true : false;
    $link_args['tumblr'] = get_theme_mod( 'share_tumblr' ) ? true : false;
    $link_args['vk'] = get_theme_mod( 'share_vk' ) ? true : false;
    $link_args['reddit'] = get_theme_mod( 'share_reddit' ) ? true : false;
    $link_args['email'] = get_theme_mod( 'share_email' ) ? true : false;

    $share = new WPFelix_Social_Share( $link_args );

    if ( ! empty( $share->links ) )
    {
        echo '<ul class="entry-share-links">';

        foreach ( $share->links as $key => $link ) :

            switch ( $key )
            {
                case 'facebook':
                    echo '<li class="' . esc_attr( $key ) . '">';
                    echo '<a href="' . esc_url( $link['url'] ) . '" title="' . esc_attr( $link['label'] ) . '" target="_blank"><i class="fa fa-facebook"></i></a>';
                    echo '</li>';
                    break;
                
                case 'twitter':
                    echo '<li class="' . esc_attr( $key ) . '">';
                    echo '<a href="' . esc_url( $link['url'] ) . '" title="' . esc_attr( $link['label'] ) . '" target="_blank"><i class="fa fa-twitter"></i></a>';
                    echo '</li>';
                    break;

                case 'google':
                    echo '<li class="google">';
                    echo '<a href="' . esc_url( $link['url'] ) . '" title="' . esc_attr( $link['label'] ) . '" target="_blank"><i class="fa fa-google-plus"></i></a>';
                    echo '</li>';
                    break;

                case 'pinterest':
                    echo '<li class="' . esc_attr( $key ) . '">';
                    echo '<a href="' . esc_url( $link['url'] ) . '" title="' . esc_attr( $link['label'] ) . '" target="_blank"><i class="fa fa-pinterest"></i></a>';
                    echo '</li>';
                    break;

                case 'linkedin':
                    echo '<li class="' . esc_attr( $key ) . '">';
                    echo '<a href="' . esc_url( $link['url'] ) . '" title="' . esc_attr( $link['label'] ) . '" target="_blank"><i class="fa fa-linkedin"></i></a>';
                    echo '</li>';
                    break;

                case 'tumblr':
                    echo '<li class="' . esc_attr( $key ) . '">';
                    echo '<a href="' . esc_url( $link['url'] ) . '" title="' . esc_attr( $link['label'] ) . '" target="_blank"><i class="fa fa-tumblr"></i></a>';
                    echo '</li>';
                    break;

                case 'vk':
                    echo '<li class="' . esc_attr( $key ) . '">';
                    echo '<a href="' . esc_url( $link['url'] ) . '" title="' . esc_attr( $link['label'] ) . '" target="_blank"><i class="fa fa-vk"></i></a>';
                    echo '</li>';
                    break;

                case 'reddit':
                    echo '<li class="' . esc_attr( $key ) . '">';
                    echo '<a href="' . esc_url( $link['url'] ) . '" title="' . esc_attr( $link['label'] ) . '" target="_blank"><i class="fa fa-reddit"></i></a>';
                    echo '</li>';
                    break;

                case 'email':
                    echo '<li class="' . esc_attr( $key ) . '">';
                    echo '<a href="' . esc_url( $link['url'] ) . '" title="' . esc_attr( $link['label'] ) . '" target="_blank"><i class="fa fa-envelope-o"></i></a>';
                    echo '</li>';
                    break;

                default:
                    break;
            }
        endforeach;

        echo '</ul>';
    }
}


/**
 * Allow child theme to overide this,
 * @since 1.0.2
 */
if ( ! function_exists( 'wpfelix_author_social' ) ) :
/**
 * Prints Social profile links for an user/author.
 * This function must be used inside loops
 */
function wpfelix_author_social( $extra_class = '' )
{
    $user_id = get_the_author_meta( 'ID' );

    ob_start();

    $link = get_user_meta( $user_id, 'wpfelix_facebook', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="facebook"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-facebook"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Facebook' )
        );
    }
    
    $link = get_user_meta( $user_id, 'wpfelix_twitter', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="twitter"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-twitter"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Twitter' )
        );
    }

    $link = get_user_meta( $user_id, 'wpfelix_linkedin', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="linkedin"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-linkedin"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Linkedin' )
        );
    }

    $link = get_user_meta( $user_id, 'wpfelix_pinterest', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="pinterest"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-pinterest-p"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Pinterest' )
        );
    }

    $link = get_user_meta( $user_id, 'wpfelix_instagram', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="instagram"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-instagram"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Instagram' )
        );
    }

    $link = get_user_meta( $user_id, 'wpfelix_google', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="google"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-google-plus"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Google' )
        );
    }

    $link = get_user_meta( $user_id, 'wpfelix_dribbble', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="dribbble"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-Dribbble"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Dribbble' )
        );
    }

    $link = get_user_meta( $user_id, 'wpfelix_flickr', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="flickr"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-flickr"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Flickr' )
        );
    }

    $link = get_user_meta( $user_id, 'wpfelix_behance', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="behance"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-behance"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Behance' )
        );
    }

    $link = get_user_meta( $user_id, 'wpfelix_tumblr', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="tumblr"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-tumblr"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Tumblr' )
        );
    }

    $link = get_user_meta( $user_id, 'wpfelix_youtube', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="youtube"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-youtube"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Youtube' )
        );
    }

    $link = get_user_meta( $user_id, 'wpfelix_vimeo', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="vimeo"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-vimeo"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Vimeo' )
        );
    }

    $link = get_user_meta( $user_id, 'wpfelix_soundcloud', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="soundcloud"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-soundcloud"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Soundcloud' )
        );
    }

    $link = get_user_meta( $user_id, 'wpfelix_vk', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="vk"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-vk"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Vk' )
        );
    }

    $link = get_user_meta( $user_id, 'wpfelix_reddit', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="reddit"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-reddit"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Reddit' )
        );
    }

    $link = get_user_meta( $user_id, 'wpfelix_yahoo', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="yahoo"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-yahoo"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Yahoo' )
        );
    }

    $link = get_user_meta( $user_id, 'wpfelix_github', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="github"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-github"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Github' )
        );
    }

    $link = get_user_meta( $user_id, 'wpfelix_rss', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="rss"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-rss"></i></a></li>',
            esc_url( $link ),
            esc_html( 'Rss' )
        );
    }

    $link = get_user_meta( $user_id, 'wpfelix_email', true );
    if ( is_string( $link ) && strlen( $link ) > 0 )
    {
        printf(
            '<li class="email"><a href="%1$s" title="%2$s" target="_blank"><i class="fa fa-envelope"></i></a></li>',
            esc_url( 'mailto:' . $link ),
            esc_html( 'Email' )
        );
    }

    $output = ob_get_clean();

    printf(
        '<ul class="list-social%1$s">%2$s</ul>',
        ( strlen( $extra_class ) > 0 ? ' ' . esc_attr( $extra_class ) : '' ),
        $output
    );
}
endif; // wpfelix_author_social()


/**
 * Prints meta info of a post
 */
function wpfelix_entry_meta()
{
    ob_start();

    if ( get_theme_mod( 'post_date', true ) || get_theme_mod( 'post_categories', true ) )
    {
        

        if ( get_theme_mod( 'post_date', true ) )
        {
            printf(
                '<li class="entry-posted-on">%1$s <a href="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></li>',
                esc_html__( 'Posted on', 'wpfelix' ),
                esc_url( get_permalink() ),
                get_the_date( 'c' ),
                get_the_date( get_option( 'date_format' ) )
            );
        }

        if ( get_theme_mod( 'post_categories', true ) )
        {
            $cat_list = wp_get_post_categories( get_the_ID() );

            $featured_cat = get_theme_mod( 'featured_category', 0 );
            $hide_featured = get_theme_mod( 'featured_category_hide', true );

            if ( $hide_featured )
            {
                $cat_list = array_diff( $cat_list, array( $featured_cat ) );
            }

            if ( ! empty( $cat_list ) )
            {
                echo '<li class="entry-cat-links">';
                echo    '<ul class="post-categories">';
                foreach ( $cat_list as $key => $cat )
                {
                    printf(
                        '<li><a href="%1$s">%2$s</a></li>',
                        esc_url( get_category_link( $cat ) ),
                        esc_html( get_cat_name( $cat ) )
                    );
                }
                echo    '</ul>';
                echo '</li>';
            }
        }
    }

    edit_post_link(
        sprintf(
            '<i class="fa fa-pencil"></i><span class="screen-reader-text"> %1$s %2$s</span>',
            esc_html( 'Edit', 'wpfelix' ),
            the_title( ' <span class="screen-reader-text">"', '"</span>', false )
        ),
        '<li class="entry-edit-link">',
        '</li>'
    );

    $output = ob_get_clean();

    if ( strlen( $output ) > 0 )
    {
        printf( '<ul class="entry-meta-above">%s</ul>', $output );
    }
}


/**
 * Everything under post content.
 */
function wpfelix_entry_footer()
{
    if ( is_single() )
    {
        wp_link_pages( array(
            'before'      => '<div class="page-links">',
            'after'       => '</div>',
            'link_before' => '<span>',
            'link_after'  => '</span>',
        ) );
        
        if ( 'post' == get_post_type() && ( get_theme_mod( 'post_tags', true ) ) )
        {
            the_tags(
                sprintf( '<div class="tag-links"><span class="screen-reader-text">%s</span>', esc_html__( 'Tagged in: ', 'wpfelix' ) ),
                '',
                '</div>'
            );
        }
    }

    if ( get_theme_mod( 'post_author', true ) || get_theme_mod( 'post_sharing', true ) || get_theme_mod( 'post_comment', true ) )
    {
        ob_start();

        $meta_entries = 0;

        if ( get_theme_mod( 'post_author', true ) )
        {
            $meta_entries++;
            printf(
                '<li class="entry-byline">%1$s <span class="author vcard"><a class="url fn n" href="%2$s">%3$s</a></span></li>',
                esc_html__( 'by ', 'wpfelix' ),
                esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                esc_html( get_the_author() )
            );
        }

        if ( get_theme_mod( 'post_sharing', true ) )
        {
            $meta_entries++;
            echo '<li class="entry-share">';
            wpfelix_social_shares();
            echo '</li>';
        }

        if ( get_theme_mod( 'post_comment', true ) && ! post_password_required() && ( comments_open() || get_comments_number() ) )
        {
            $meta_entries++;

            echo '<li class="entry-comments">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'wpfelix' ),
                        array( 'span' => array( 'class' => array() ) )
                    ),
                    get_the_title()
                ),
                esc_html__( '1 Comment', 'wpfelix' ), 
                esc_html__( '% Comments', 'wpfelix' )
            );
            echo '</li>';
        }

        $output = ob_get_clean();

        if ( $meta_entries > 0 )
        {
            printf( '<ul class="entry-meta-below meta-entries-%1$s">%2$s</ul>', $meta_entries, $output );
        }
    }
}


/**
 * Posts navigation - page-number style
 * @param object $query Current query, default to $wp_query
 * @param boolean $force_enabled If jetpack infinite scroll is enabled then this function will do nothing,
 *                                but if you want to show it somewhere without jetpack, then set this to true.
 * @return void
 */
function wpfelix_posts_pagination( $query = null, $force_enabled = false )
{
    if ( ! $force_enabled && class_exists( 'Jetpack' ) && in_array( 'infinite-scroll', Jetpack::get_active_modules() ) )
    {
        return;
    }
    
    if ( empty( $query ) )
    {
        $query = $GLOBALS['wp_query'];
    }
    if ( empty( $query->max_num_pages ) || ! is_numeric( $query->max_num_pages ) || $query->max_num_pages < 2 )
    {
        return;
    }

    $paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
    $pagenum_link = html_entity_decode( get_pagenum_link() );
    $query_args   = array();
    $url_parts    = explode( '?', $pagenum_link );

    if ( isset( $url_parts[1] ) )
    {
        wp_parse_str( $url_parts[1], $query_args );
    }

    $pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
    $pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

    // Set up paginated links.
    $links = paginate_links( array(
        'base'     => $pagenum_link,
        'total'    => $query->max_num_pages,
        'current'  => $paged,
        'mid_size' => 1,
        'add_args' => array_map( 'urlencode', $query_args ),
        'prev_text' => '<span class="screen-reader-text">' . esc_html__( 'Previous Page', 'wpfelix' ) . '</span><i class="fa fa-angle-double-left"></i>',
        'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next Page', 'wpfelix' ) . '</span><i class="fa fa-angle-double-right"></i>',
    ) );

    $template = '
    <nav class="navigation posts-pagination">
        <h2 class="screen-reader-text">%1$s</h2>
        <div class="posts-page-links">%2$s</div>
    </nav>';

    if ( $links )
    {
        printf( $template, esc_html__( 'Posts page', 'wpfelix' ), $links );
    }
}


/**
 * Custom callback function for wp_list_comments
 * @param  object $comment
 * @param  array  $args
 * @param  int    $depth
 * @return void
 */
function wpfelix_comment_template( $comment, $args, $depth )
{
    $GLOBALS['comment'] = $comment;
    $comment_date_format = apply_filters( 'wpfelix_comment_date_format', 'j F Y \a\t g:i a' );
    extract( $args, EXTR_SKIP );

    if ( 'div' == $args['style'] )
    {
        $tag = 'div';
        $add_below = 'comment';
    }
    else
    {
        $tag = 'li';
        $add_below = 'div-comment';
    }

    echo '<' . esc_attr( $tag ) . ' ';
    comment_class( empty( $args['has_children'] ) ? '' : 'parent' );
    echo ' id="comment-';
    comment_ID();
    echo '">';

    $avatar = get_avatar( $comment, 140 );
    
    if ( 'div' != $args['style'] )
    {
        // Open -->
        printf(
            '<div id="div-comment-%1$s" class="comment-body %2$s">',
            esc_attr( get_comment_ID() ),
            $avatar ? '' : 'no-avatar'
        );
    }
    printf( '<div class="comment-author-image vcard">%s</div>', $avatar );
    ?>
    <div class="comment-main">
        <div class="comment-header">
            <h5 class="comment-author"><?php echo get_comment_author_link(); ?></h5>
            <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
        </div>
        <div class="comment-date"><?php echo get_comment_date( $comment_date_format, get_comment_ID() ); ?></div>
        <?php if ( $comment->comment_approved == '0' ) : ?>
        <p class="comment-awaiting-moderation"><em><?php esc_html_e( 'Your comment is awaiting moderation.' , 'wpfelix' ); ?></em></p>
        <?php endif; ?>
        <div class="comment-content">
            <?php comment_text(); ?>
        </div>
    </div>
    <?php
    if ( 'div' != $args['style'] )
    {
        // <-- Close
        echo '</div>';
    }
}



/**
 * Display navigation to next/previous comments when applicable.
 * @return void
 */
function wpfelix_comments_navigation()
{
    // Do nothing if there's nothing to navigate
    if ( get_comment_pages_count() <= 1 || ! get_option( 'page_comments' ) )
    {
        return;
    }
    ?>
    <nav class="navigation comment-navigation" role="navigation">
        <h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'wpfelix' ); ?></h2>
        <div class="nav-links">
            <?php
                if ( $prev_link = get_previous_comments_link( esc_html__( 'Older Comments', 'wpfelix' ) ) )
                {
                    printf( '<div class="nav-previous">%s</div>', $prev_link );
                }

                if ( $next_link = get_next_comments_link( esc_html__( 'Newer Comments', 'wpfelix' ) ) )
                {
                    printf( '<div class="nav-next">%s</div>', $next_link );
                }
            ?>
        </div><!-- .nav-links -->
    </nav><!-- .comment-navigation -->
    <?php
}
