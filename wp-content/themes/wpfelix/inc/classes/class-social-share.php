<?php
/**
 * Social share links class
 *
 * @package WPFelix
 */

class WPFelix_Social_Share
{
    protected   $args;
    protected   $post_obj;
    public      $links;
    
    /*
     * Constructor
     * initialize the necessary variables
     */
    function __construct( $link_args = array(), $post_id = null )
    {
        $this->post_obj = isset( $post_id ) ? get_post( $post_id ) : get_post();

        $default_link_args = array(
            'facebook'      => false,
            'twitter'       => false,
            'google'        => false,
            'pinterest'     => false,
            'linkedin'      => false,
            'tumblr'        => false,
            'vk'            => false,
            'reddit'        => false,
            'email'         => false
        );

        $link_args = wp_parse_args( $link_args, $default_link_args );

        $args = array();

        if ( $link_args['facebook'] )
        {
            $args['facebook'] = array(
                'encode'        => true,
                'encode_urls'   => false,
                'pattern'       => 'http://www.facebook.com/sharer.php?u=[permalink]&amp;t=[title]',
                'label'         => esc_html( 'Share this', 'wpfelix' )
            );
        }

        if ( $link_args['twitter'] )
        {
            $args['twitter'] = array(
                'encode'        => true,
                'encode_urls'   => false,
                'pattern'       => 'https://twitter.com/share?text=[title]&url=[shortlink]',
                'label'         => esc_html( 'Tweet this', 'wpfelix' )
            );
        }

        if ( $link_args['google'] )
        {
            $args['google'] = array(
                'encode'        => true,
                'encode_urls'   => false,
                'pattern'       => 'https://plus.google.com/share?url=[permalink]',
                'label'         => esc_html( 'Share this', 'wpfelix' )
            );
        }

        if ( $link_args['pinterest'] )
        {
            $args['pinterest'] = array(
                'encode'        => true,
                'encode_urls'   => true,
                'pattern'       => 'http://pinterest.com/pin/create/button/?url=[permalink]&amp;description=[title]&amp;media=[thumbnail]',
                'label'         => esc_html( 'Pin this', 'wpfelix' )
            );
        }

        if ( $link_args['linkedin'] )
        {
            $args['linkedin'] = array(
                'encode'        => true,
                'encode_urls'   => false,
                'pattern'       => 'http://linkedin.com/shareArticle?mini=true&amp;title=[title]&amp;url=[permalink]',
                'label'         => esc_html( 'Share this', 'wpfelix' )
            );
        }

        if ( $link_args['tumblr'] )
        {
            $args['tumblr'] = array(
                'encode'        => true,
                'encode_urls'   => true,
                'pattern'       => 'http://www.tumblr.com/share/link?url=[permalink]&amp;name=[title]&amp;description=[excerpt]',
                'label'         => esc_html( 'Share this', 'wpfelix' )
            );
        }

        if ( $link_args['vk'] )
        {
            $args['vk'] = array(
                'encode'        => true,
                'encode_urls'   => false,
                'pattern'       => 'http://vk.com/share.php?url=[permalink]',
                'label'         => esc_html( 'Share this', 'wpfelix' )
            );
        }

        if ( $link_args['reddit'] )
        {
            $args['reddit'] = array(
                'encode'        => true,
                'encode_urls'   => false,
                'pattern'       => 'http://reddit.com/submit?url=[permalink]&amp;title=[title]',
                'label'         => esc_html( 'Share this', 'wpfelix' )
            );
        }

        if ( $link_args['email'] )
        {
            $args['email'] = array(
                'encode'        => true,
                'encode_urls'   => false,
                'pattern'       => 'mailto:?subject=[title]&amp;body=[permalink]',
                'label'         => esc_html( 'Share by Mail', 'wpfelix')
            );
        }
        
        $this->args = apply_filters( 'wpfelix_social_share_args', $args );
        $this->build_links();
    }
    
    
    /**
     * Build social share links
     */
    function build_links()
    {
        $thumb                  = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
        $replace['permalink']   = ! isset( $this->post_obj ) ? get_permalink() : get_permalink( $this->post_obj->ID );
        $replace['title']       = ! isset( $this->post_obj ) ? get_the_title() : $this->post_obj->post_title;
        $replace['excerpt']     = ! isset( $this->post_obj ) ? get_the_excerpt() : $this->post_obj->post_excerpt;
        $replace['shortlink']   = ! isset( $this->post_obj ) ? wp_get_shortlink() : wp_get_shortlink( $this->post_obj->ID );
        $replace['thumbnail']   = is_array( $thumb) && isset( $thumb[0] ) ? $thumb[0] : "";
        
        $replace = apply_filters( 'wpfelix_social_share_value_replacements', $replace );
        $charset = get_bloginfo( 'charset' );
        
        foreach ( $this->args as $key => $share )
        {
            $share_key  = 'share_' . $key;
            $url        = $share['pattern'];
            
            foreach ( $replace as $replace_key => $replace_value )
            {
                if ( ! empty( $share['encode'] ) && $replace_key != 'shortlink' && $replace_key != 'permalink' )
                {
                    $replace_value = rawurlencode( html_entity_decode( $replace_value, ENT_QUOTES, $charset ) );
                }
                if ( ! empty( $share['encode_urls'] ) && ( $replace_key == 'shortlink' || $replace_key == 'permalink' ) )
                {
                    $replace_value = rawurlencode( html_entity_decode( $replace_value, ENT_QUOTES, $charset ) );
                }
                
                $url = str_replace( "[{$replace_key}]", $replace_value, $url );
            }
            
            $this->links[$key]['url'] = esc_url( $url );
            $this->links[$key]['label'] = esc_attr( $share['label'] );
        }
    }
}