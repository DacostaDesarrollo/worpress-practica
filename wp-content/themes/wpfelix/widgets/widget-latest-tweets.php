<?php
/**
 * Latest Tweets Twitter Widget
 *
 * @package WPFelix
 */

class WPFelix_Latest_Tweets_Widget extends WP_Widget
{
    /**
     * Constructor
     *
     * @return void
     **/
    function __construct()
    {
        parent::__construct(
            'wpfelix_latest_tweets', // Base ID
            esc_html__( 'Felix Latest Tweets', 'wpfelix' ), // Name
            array(
                'description' => esc_html__( 'Show Latest Tweets from Twitter. Go to Appearance/Customize for configuration.', 'wpfelix' ),
                'customize_selective_refresh' => true
            ) // Args
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
                'title' => '',
                'consumer_key' => '',
                'consumer_secret' => '',
                'access_token' => '',
                'access_token_secret' => '',
                'twitter_id' => '',
                'show_user' => '',
                'show_date' => '',
                'show_avatar' => '',
                'count' => 3
            )
        );

        $consumer_key = $instance['consumer_key'];
        $consumer_secret = $instance['consumer_secret'];
        $access_token = $instance['access_token'];
        $access_token_secret = $instance['access_token_secret'];
        $twitter_id = $instance['twitter_id'];
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
        $show_avatar = isset( $instance['show_avatar'] ) ? $instance['show_avatar'] : false;
        $show_user = isset( $instance['show_user'] ) ? $instance['show_user'] : false;
        $count = absint( $instance['count'] );

        echo $before_widget;

        if ( ! empty( $instance['title'] ) ) {
            $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
        }
        else {
            $title = '';
        }

        if ( ! empty( $title ) )
        {
            echo $before_title . $title . $after_title;
        }

        if ( $twitter_id && $consumer_key && $consumer_secret && $access_token && $access_token_secret && $count )
        {
            $transName = 'list_tweets_' . $args['widget_id'];
            $cacheTime = 10;

            if ( false === ( $twitterData = get_transient( $transName ) ) )
            {
                $token = get_option( 'cfTwitterToken_' . $args['widget_id'] );

                // get a new token anyways
                delete_option('cfTwitterToken_'.$args['widget_id']);

                // getting new auth bearer only if we don't have one
                if ( ! $token )
                {
                    // preparing credentials
                    $credentials = $consumer_key . ':' . $consumer_secret;
                    $toSend = base64_encode( $credentials );

                    // http post arguments
                    $args = array(
                        'method' => 'POST',
                        'httpversion' => '1.1',
                        'blocking' => true,
                        'headers' => array(
                            'Authorization' => 'Basic ' . $toSend,
                            'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
                        ),
                        'body' => array( 'grant_type' => 'client_credentials' )
                    );

                    add_filter( 'https_ssl_verify', '__return_false' );

                    $response = wp_remote_post( 'https://api.twitter.com/oauth2/token', $args );

                    $keys = json_decode( wp_remote_retrieve_body( $response ) );

                    if ( $keys )
                    {
                        // saving token to wp_options table
                        $token = $keys->access_token;
                    }
                }

                // we have bearer token wether we obtained it from API or from options
                $args = array(
                    'httpversion' => '1.1',
                    'blocking' => true,
                    'headers' => array(
                        'Authorization' => "Bearer $token"
                    )
                );

                add_filter( 'https_ssl_verify', '__return_false' );
                $api_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $twitter_id . '&count=' . $count;
                $response = wp_remote_get( $api_url, $args );

                set_transient( $transName, wp_remote_retrieve_body( $response ), 60 * $cacheTime );
            }

            @$twitter = json_decode( get_transient( $transName ), true );
            
            if ( $twitter && is_array( $twitter ) )
            {
                printf(
                    '<div class="carousel owl-carousel tweets-container" id="tweets_%1$s" data-theme-carousel="true" data-options="%2$s">',
                    esc_attr( $widget_id ),
                    esc_attr( '{"items":1,"dots":true,"nav":false}' )
                );
                foreach ( $twitter as $tweet )
                {
                    echo '<div class="tweet-item">';

                    if ( $show_avatar )
                    {
                        printf(
                            '<div class="avatar"><a href="%1$s" target="_blank"><img src="%2$s" alt="%3$s"/></a></div>',
                            esc_url( 'http://twitter.com/' . $tweet['user']['screen_name'] ),
                            esc_url( $tweet['user']['profile_image_url'] ),
                            esc_attr( $tweet['user']['screen_name'] )
                        );
                    }

                    if ( $show_user )
                    {
                        printf(
                            '<div class="user"><i class="fa fa-twitter tweet-icon"></i>%1$s <a href="%2$s" target="_blank">@%3$s</a></div>',
                            esc_html( $tweet['user']['name'] ),
                            esc_url( 'http://twitter.com/' . $tweet['user']['screen_name'] ),
                            esc_attr( $tweet['user']['screen_name'] )
                        );
                    }

                    $latestTweet = $tweet['text'];
                    $latestTweet = preg_replace( '/http:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '&nbsp;<a href="http://$1" target="_blank">http://$1</a>&nbsp;', $latestTweet );
                    $latestTweet = preg_replace( '/@([a-z0-9_]+)/i', '&nbsp;<a href="http://twitter.com/$1" target="_blank">@$1</a>&nbsp;', $latestTweet );

                    printf( '<div class="tweet">%s</div>', $latestTweet );

                    if ( $show_date )
                    {
                        $twitterTime = strtotime( $tweet['created_at'] );
                        $timeAgo = $this->time_period( $twitterTime );
                        printf(
                            '<div class="tweet-at"><a href="%1$s" target="_blank">%2$s</a></div>',
                            esc_url( 'twitter.com/' . $tweet['user']['screen_name'] . '/statuses/' . $tweet['id_str'] ),
                            esc_attr( $timeAgo )
                        );
                    }
                    echo '</div>';
                }
                echo '</div>';
            }
        }
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

        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['consumer_key'] = sanitize_text_field( $new_instance['consumer_key'] );
        $instance['consumer_secret'] = sanitize_text_field( $new_instance['consumer_secret'] );
        $instance['access_token'] = sanitize_text_field( $new_instance['access_token'] );
        $instance['access_token_secret'] = sanitize_text_field( $new_instance['access_token_secret'] );
        $instance['twitter_id'] = sanitize_text_field( $new_instance['twitter_id'] );
        $instance['show_user'] = isset( $instance['show_user'] ) ? (bool) $instance['show_user'] : true;
        $instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
        $instance['show_avatar'] = isset( $new_instance['show_avatar'] ) ? (bool) $new_instance['show_avatar'] : false;
        $instance['count'] = absint( $new_instance['count'] );

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
        $defaults = array(
            'title' => '',
            'twitter_id' => '',
            'count' => 3,
            'consumer_key' => '',
            'consumer_secret' => '',
            'access_token' => '',
            'access_token_secret' => ''
        );

        $instance = wp_parse_args( (array) $instance, $defaults );
        $show_user = isset( $instance['show_user'] ) ? (bool) $instance['show_user'] : true;
        $show_avatar = isset( $instance['show_avatar'] ) ? (bool) $instance['show_avatar'] : false;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : true;
        ?>
        <p><a href="<?php echo esc_url("http://dev.twitter.com/apps"); ?>" target="_blank"><?php esc_html_e( 'Find or Create your Twitter App.', 'wpfelix' ); ?></a></p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'wpfelix' ); ?></label>
            <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'consumer_key' ) ); ?>"><?php esc_html_e( 'Consumer Key:', 'wpfelix' ); ?></label>
            <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'consumer_key' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'consumer_key' ) ); ?>" value="<?php echo esc_attr( $instance['consumer_key'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'consumer_secret' ) ); ?>"><?php esc_html_e( 'Consumer Secret:', 'wpfelix' ); ?></label>
            <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'consumer_secret' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'consumer_secret' ) ); ?>" value="<?php echo esc_attr( $instance['consumer_secret'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>"><?php esc_html_e( 'Access Token:', 'wpfelix' ); ?></label>
            <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'access_token' ) ); ?>" value="<?php echo esc_attr( $instance['access_token'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'access_token_secret' ) ); ?>"><?php esc_html_e( 'Access Token Secret:', 'wpfelix' ); ?></label>
            <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'access_token_secret' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'access_token_secret' ) ); ?>" value="<?php echo esc_attr( $instance['access_token_secret'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'twitter_id' ) ); ?>"><?php esc_html_e( 'Twitter ID:', 'wpfelix' ); ?></label>
            <input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'twitter_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_id' ) ); ?>" value="<?php echo esc_attr( $instance['twitter_id'] ); ?>" />
        </p>
        <p>
            <input class="checkbox" type="checkbox"<?php checked( $show_avatar ); ?> id="<?php echo $this->get_field_id( 'show_avatar' ); ?>" name="<?php echo $this->get_field_name( 'show_avatar' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'show_avatar' ); ?>"><?php esc_html_e( 'Display avatar?', 'wpfelix' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox"<?php checked( $show_user ); ?> id="<?php echo $this->get_field_id( 'show_user' ); ?>" name="<?php echo $this->get_field_name( 'show_user' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'show_user' ); ?>"><?php esc_html_e( 'Display user?', 'wpfelix' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php esc_html_e( 'Display tweet date?', 'wpfelix' ); ?></label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Number of Tweets:', 'wpfelix' ); ?></label>
            <input class="widefat" type="number" id="<?php echo esc_attr( $this->get_field_id( 'count' )) ; ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" value="<?php echo esc_attr( $instance['count'] ); ?>" />
        </p>
        <?php
    }

    /**
     * Time period
     */
    function time_period( $time_str )
    {
        $periods = array(
            esc_html__( 'second', 'wpfelix' ),
            esc_html__( 'minute', 'wpfelix' ),
            esc_html__( 'hour', 'wpfelix' ),
            esc_html__( 'day', 'wpfelix' ),
            esc_html__( 'week', 'wpfelix' ),
            esc_html__( 'month', 'wpfelix' ),
            esc_html__( 'year', 'wpfelix' ),
            esc_html__( 'decad', 'wpfelix' )
        );
        $lengths = array( "60", "60", "24", "7", "4.35", "12", "10" );

        $now = time();

        $difference = $now - $time_str;
        $tense = esc_html__( 'ago', 'wpfelix' );

        for ( $j = 0; $difference >= $lengths[$j] && $j < count( $lengths ) - 1; $j++ )
        {
            $difference /= $lengths[$j];
        }

        $difference = round( $difference );

        if ( $difference != 1 )
        {
            $periods[$j] .= "s";
        }

        return "{$difference} {$periods[$j]} {$tense} ";
    }
}

add_action( 'widgets_init', create_function( '', "register_widget( 'WPFelix_Latest_Tweets_Widget' );" ) );
