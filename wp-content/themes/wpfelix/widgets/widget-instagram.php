<?php
/**
 * Simple instagram widget
 *
 * @package WPFelix
 * @since   WPFelix 1.0
 */

class WPFelix_Instagram_Widget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'wpfelix_instagram',
            __( 'Felix Instagram', 'wpfelix' ),
            array(
                'description' => __( 'Instagram Widget', 'wpfelix' ),
                'customize_selective_refresh' => true
            )
        );
    }
    

    /**
     * Outputs the HTML for this widget.
     *
     * @param array $args An array of standard parameters for widgets in this theme
     * @param array $instance An array of settings for this widget instance
     * @return void Echoes it's output
     **/
    function widget( $args, $instance )
    {
        extract( $args, EXTR_SKIP );

        $instance = wp_parse_args(
            (array) $instance,
            array(
                'title'         => 'Instagram',
                'usr'           => '',
                'usr_show'      => '1',
                'usr_text'      => '',
                'number'        => 9,
                'space'         => 0,
                'columns'       => 3,
                'size'          => 'thumbnail',
                'images_only'   => '1',
                'image_link'    => '1',
            )
        );



        $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
        $title = empty( $title ) ? esc_html__( 'Instagram', 'wpfelix' ) : $title;

        echo $before_widget;

        echo $before_title . $title . $after_title;

        $instance['usr'] = trim( $instance['usr'] );
        $instance['usr_show'] = (bool) $instance['usr_show'];
        $instance['usr_text'] = trim( $instance['usr_text'] );
        $instance['number'] = absint( $instance['number'] );
        $instance['space'] = absint( $instance['space'] );
        $instance['columns'] = absint( $instance['columns'] );
        $instance['images_only'] = (bool) $instance['images_only'];
        $instance['image_link'] = (bool) $instance['image_link'];

        $instance['number'] = ( 0 > $instance['number'] || 12 < $instance['number'] ) ? 12 : $instance['number'];

        if ( ! empty( $instance['usr'] ) && ! empty( $instance['usr_show'] ) )
        {
            $usr_link_text = empty( $instance['usr_text'] ) ? '@' . $instance['usr'] : $instance['usr_text'];
            printf(
                '<h4 class="wpfelix-insta-usr"><a href="//instagram.com/%1$s" target="_blank">%2$s</a></h4>',
                esc_attr( $instance['usr'] ),
                $usr_link_text
            );
        }

        // Opening
        printf(
            '<div class="wpfelix-insta-images wpfelix-insta-images-c%1$s"%2$s>',
            esc_attr( $instance['columns'] ),
            $instance['space'] > 0 ? ' style="margin-left:-' . $instance['space'] . 'px;margin-bottom:-' . $instance['space'] . 'px"' : ''
        );

        if ( ! empty( $instance['usr'] ) )
        {
            $media_array = $this->get_instagram_media( $instance['usr'] );

            if ( is_wp_error( $media_array ) )
            {
                echo wp_kses_post( $media_array->get_error_message() );
            }
            else
            {
                if ( $instance['images_only'] )
                {
                    $media_array = array_filter( $media_array, array( $this, 'images_only' ) );
                }

                // slice list down to required limit
                $media_array = array_slice( $media_array, 0, $instance['number'] );

                foreach ( $media_array as $item )
                {
                    printf(
                        '<div class="image" %s>',
                        $instance['space'] > 0 ? ' style="padding-left:' . $instance['space'] . 'px;padding-bottom:' . $instance['space'] . 'px;"' : ''
                    );

                    if ( $instance['image_link'] )
                    {
                        printf(
                            '<a href="%1$s" target="_blank"><img src="%2$s" alt="%3$s" title="%3$s"/></a>',
                            esc_url( $item['link'] ),
                            esc_url( $item[ $instance['size'] ] ),
                            esc_attr( $item['description'] )
                        );
                    }
                    else
                    {
                        printf(
                            '<img src="%1$s" alt="%2$s" title="%2$s"/>',
                            esc_url( $item[ $instance['size'] ] ),
                            esc_attr( $item['description'] )
                        );
                    }
                    echo '</div>';
                }
            }
        }

        // Close
        echo '</div>';
        echo $after_widget;
    }         
    

    /**
     * Deals with the settings when they are saved by the admin. Here is
     * where any validation should be dealt with.
     *
     * @param array $new_instance An array of new settings as submitted by the admin
     * @param array $old_instance An array of the previous settings
     * @return array The validated and (if necessary) amended settings
     **/
    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['usr'] = trim( strip_tags( $new_instance['usr'] ) );
        $instance['usr_show'] = (bool) $new_instance['usr_show'];
        $instance['usr_text'] = strip_tags( $new_instance['usr_text'] );
        $instance['number'] = intval( $new_instance['number'] );

        $new_instance['space'] = absint( $new_instance['space'] );

        if ( $new_instance['space'] > 20 )
        {
            $instance['space'] = 20;
        }
        else
        {
            $instance['space'] = $new_instance['space'];
        }

        $instance['columns'] = intval( $new_instance['columns'] );

        if ( $new_instance['size'] == 'thumbnail' || $new_instance['size'] == 'small' || $new_instance['size'] == 'large' || $new_instance['size'] == 'original' )
        {
            $instance['size'] = $new_instance['size'];
        }
        else
        {
            $instance['size'] = 'thumbnail';
        }
        
        $instance['images_only'] = (bool) $new_instance['images_only'];
        $instance['image_link'] = (bool) $new_instance['image_link'];
         
        return $instance;
    }
    

    /**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array $instance An array of the current settings for this widget
     * @return void Echoes it's output
     **/
    function form( $instance )
    {
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'title'         => 'Instagram',
                'usr'           => '',
                'usr_show'      => '1',
                'usr_text'      => '',
                'number'        => 9,
                'space'         => 0,
                'columns'       => 3,
                'size'          => 'thumbnail',
                'images_only'   => '1',
                'image_link'    => '1',
            )
        );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title', 'wpfelix' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'usr' ); ?>"><?php esc_html_e( 'Username', 'wpfelix' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'usr' ); ?>" name="<?php echo $this->get_field_name( 'usr' ); ?>" type="text" value="<?php echo esc_attr( $instance['usr'] ); ?>" />
        </p>
        <p class="howto">www.instagram.com/<strong style="color:red">your_username</strong>/</p>
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'usr_show' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'usr_show' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['usr_show'], "1" );  ?>/><label for="<?php echo esc_attr( $this->get_field_id( 'usr_show' ) ); ?>"><?php esc_html_e( 'Show profile url', 'wpfelix' ); ?></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'usr_text' ); ?>"><?php esc_html_e( 'Custom profile url text', 'wpfelix' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'usr_text' ); ?>" name="<?php echo $this->get_field_name( 'usr_text' ); ?>" type="text" value="<?php echo esc_attr( $instance['usr_text'] ); ?>" />
        </p>
        <p class="howto"><?php esc_html_e( 'Your custom profile link text, if left empty, the profile link will be @username.', 'wpfelix' ); ?></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_html_e( 'How many images?', 'wpfelix' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" value="<?php echo esc_attr( $instance['number'] ); ?>" />
        </p>
        <p class="howto"><?php esc_html_e( 'Number of images should greater than 0 and less than (or equals to) 12', 'wpfelix' ); ?></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'space' ); ?>"><?php esc_html_e( 'Space between images?', 'wpfelix' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'space' ); ?>" name="<?php echo $this->get_field_name( 'space' ); ?>" type="number" value="<?php echo esc_attr( $instance['space'] ); ?>" />
        </p>
        <p class="howto"><?php esc_html_e( 'Space should smaller than 20 and greater or equal zero.', 'wpfelix' );?></p>
        <p>
            <label for="<?php echo $this->get_field_id( 'columns' ); ?>"><?php esc_html_e( 'Columns', 'wpfelix' ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'columns' ); ?>" name="<?php echo $this->get_field_name( 'columns' ); ?>">
                <option value="1" <?php selected( $instance['columns'], "1" ); ?>>1</option>
                <option value="2" <?php selected( $instance['columns'], "2" ); ?>>2</option>
                <option value="3" <?php selected( $instance['columns'], "3" ); ?>>3</option>
                <option value="4" <?php selected( $instance['columns'], "4" ); ?>>4</option>
                <option value="5" <?php selected( $instance['columns'], "5" ); ?>>5</option>
                <option value="6" <?php selected( $instance['columns'], "6" ); ?>>6</option>
                <option value="7" <?php selected( $instance['columns'], "7" ); ?>>7</option>
                <option value="8" <?php selected( $instance['columns'], "8" ); ?>>8</option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php esc_html_e( 'Image Size', 'wpfelix' ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>">
                <option value="thumbnail" <?php selected( $instance['size'], "thumbnail" ); ?>><?php esc_html_e( 'Thumbnail', 'wpfelix' ); ?></option>
                <option value="small" <?php selected( $instance['size'], "small" ); ?>><?php esc_html_e( 'Small', 'wpfelix' ); ?></option>
                <option value="large" <?php selected( $instance['size'], "large" ); ?>><?php esc_html_e( 'Large', 'wpfelix' ); ?></option>
                <option value="original" <?php selected( $instance['size'], "original" ); ?>><?php esc_html_e( 'Original', 'wpfelix' ); ?></option>
            </select>
        </p>
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'images_only' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'images_only' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['images_only'], "1" );  ?>/><label for="<?php echo esc_attr( $this->get_field_id( 'images_only' ) ); ?>"><?php esc_html_e( 'Show only images', 'wpfelix' ); ?></label>
        </p>
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'image_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_link' ) ); ?>" type="checkbox" value="1" <?php checked( $instance['image_link'], "1" );  ?>/><label for="<?php echo esc_attr( $this->get_field_id( 'image_link' ) ); ?>"><?php esc_html_e( 'Add link to images', 'wpfelix' ); ?></label>
        </p>
        <?php
    }


    /**
     * Get images array
     * @param  string $usr Username
     * @return array
     */
    function get_instagram_media( $username )
    {
        $username = trim( strtolower( $username ) );

        switch ( substr( $username, 0, 1 ) ) {
            case '#':
                $url              = 'https://instagram.com/explore/tags/' . str_replace( '#', '', $username );
                $transient_prefix = 'h';
                break;

            default:
                $url              = 'https://instagram.com/' . str_replace( '@', '', $username );
                $transient_prefix = 'u';
                break;
        }

        if ( false === ( $instagram = get_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ) ) ) ) {

            $remote = wp_remote_get( $url );

            if ( is_wp_error( $remote ) ) {
                return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'wp-instagram-widget' ) );
            }

            if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
                return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'wp-instagram-widget' ) );
            }

            $shards      = explode( 'window._sharedData = ', $remote['body'] );
            $insta_json  = explode( ';</script>', $shards[1] );
            $insta_array = json_decode( $insta_json[0], true );

            if ( ! $insta_array ) {
                return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'wp-instagram-widget' ) );
            }

            if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
                $images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
            } elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
                $images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
            } else {
                return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'wp-instagram-widget' ) );
            }

            if ( ! is_array( $images ) ) {
                return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'wp-instagram-widget' ) );
            }

            $instagram = array();

            foreach ( $images as $image ) {
                if ( true === $image['node']['is_video'] ) {
                    $type = 'video';
                } else {
                    $type = 'image';
                }

                $caption = __( 'Instagram Image', 'wp-instagram-widget' );
                if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
                    $caption = wp_kses( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'], array() );
                }

                $instagram[] = array(
                    'description' => $caption,
                    'link'        => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
                    'time'        => $image['node']['taken_at_timestamp'],
                    'comments'    => $image['node']['edge_media_to_comment']['count'],
                    'likes'       => $image['node']['edge_liked_by']['count'],
                    'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
                    'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
                    'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
                    'original'    => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
                    'type'        => $type,
                );
            } // End foreach().

            // do not set an empty transient - should help catch private or empty accounts.
            if ( ! empty( $instagram ) ) {
                $instagram = base64_encode( serialize( $instagram ) );
                set_transient( 'insta-a10-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
            }
        }

        if ( ! empty( $instagram ) ) {

            return unserialize( base64_decode( $instagram ) );

        } else {

            return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'wp-instagram-widget' ) );

        }
    }


    /**
     * Ensure media type is image, we use this function to filter instagram array ouput.
     * @param  array $media_item
     * @return boolean
     */
    function images_only( $media_item )
    {
        if ( $media_item['type'] == 'image' )
        {
            return true;
        }

        return false;
    }
}

add_action( 'widgets_init', create_function( '', "register_widget( 'WPFelix_instagram_Widget' );" ) );