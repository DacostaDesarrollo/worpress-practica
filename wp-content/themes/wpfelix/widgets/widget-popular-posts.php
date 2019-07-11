<?php defined( 'ABSPATH' ) or exit( -1 );
/**
 * Popular posts widgets for jetpack.
 *
 * @package  WPFelix
 * @since    2.0
 */

class WPFelix_Popular_Posts_Widget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'wpfelix_popular_posts', // Base ID
            esc_html__( 'Felix Popular Posts', 'wpfelix' ), // Name
            array(
                'description' => esc_html__( 'Show most viewed posts.', 'wpfelix' ),
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
        $instance = wp_parse_args( (array) $instance, array(
            'title' => '',
            'number' => 5,
            'show_date' => false
        ) );

        $instance['number']    = absint( $instance['number'] );

        echo $args['before_widget'];

        if ( ! $instance['title'] )
        {
            $instance['title'] = esc_html__( 'Popular Posts', 'wpfelix' );
        }
        $title     = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
        $number    = $instance['number'] < 1 ? 5 : $instance['number'];
        $show_date = (bool) $instance['show_date'];

        echo $args['before_title'] . $title . $args['after_title'];

        $posts = $this->get_by_views( $number, $args );

        if ( ! $posts )
        {
            $posts = $this->get_fallback_posts();
        }

        if ( ! $posts )
        {
            if ( current_user_can( 'edit_theme_options' ) )
            {
                echo '<p>' . sprintf(
                    __( 'There are no posts to display. <a href="%s" target="_blank">Want more traffic?</a>', 'wpfelix' ),
                    'https://jetpack.com/support/getting-more-views-and-traffic/'
                ) . '</p>';
            }

            echo $args['after_widget'];
            return;
        }

        echo '<ul>';

        foreach ( $posts as $post )
        {
            $post_id   = $post['post_id'];
            $permalink = $post['permalink'];

            if ( has_post_thumbnail( $post_id ) )
            {
                echo '<li class="has-post-thumbnail">';
                echo    '<div class="entry-featured">';
                echo        '<a href="' . esc_url( $permalink ) . '">';
                echo            get_the_post_thumbnail( $post_id, 'medium' );
                echo        '</a>';
                echo    '</div>';
            }
            else
            {
                echo '<li>';
            }

            printf(
                '<h4 class="entry-title"><a href="%1$s" rel="bookmark">%2$s</a></h4>',
                esc_url( $permalink ),
                get_the_title( $post_id )
            );

            if ( $show_date )
            {
                printf(
                    '<div class="entry-posted-on"><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s">%3$s</time></a></div>',
                    esc_url( $permalink ),
                    get_the_date( 'c', $post_id ),
                    get_the_date( get_option( 'date_format' ), $post_id )
                );
            }

            echo '</li>';

        } // end foreach

        echo '</ul>';

        echo $args['after_widget'];
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
        $instance['number'] = (int) $new_instance['number'];
        $instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
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
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : true;

        ?>
        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'wpfelix' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_html_e( 'Number of posts to show:', 'wpfelix' ); ?></label>
        <input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

        <p><input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php esc_html_e( 'Display post date?', 'wpfelix' ); ?></label></p>
        <?php
    }


    function get_by_views( $count, $args )
    {
        if ( defined( 'IS_WPCOM' ) && IS_WPCOM )
        {
            global $wpdb;

            $post_views = wp_cache_get( "get_top_posts_$count", 'stats' );

            if ( false === $post_views )
            {
                $post_views = array_shift( stats_get_daily_history( false, get_current_blog_id(), 'postviews', 'post_id', false, 2, '', $count * 2 + 10, true ) );
                unset( $post_views[0] );
                wp_cache_add( "get_top_posts_$count", $post_views, 'stats', 1200 );
            }

            return $this->get_posts( array_keys( $post_views ), $count );
        }

        /**
         * Filter the number of days used to calculate Top Posts for the Top Posts widget.
         * We do not recommend accessing more than 10 days of results at one.
         * When more than 10 days of results are accessed at once, results should be cached via the WordPress transients API.
         * Querying for -1 days will give results for an infinite number of days.
         *
         * @module widgets
         *
         * @since 3.9.3
         *
         * @param int 2 Number of days. Default is 2.
         * @param array $args The widget arguments.
         */
        $days = (int) apply_filters( 'jetpack_top_posts_days', 2, $args );

        /** Handling situations where the number of days makes no sense - allows for unlimited days where $days = -1 */
        if ( 0 == $days || false == $days )
        {
            $days = 2;
        }

        $post_view_posts = stats_get_csv( 'postviews', array( 'days' => absint( $days ), 'limit' => 11 ) );

        if ( ! $post_view_posts )
        {
            return array();
        }

        $post_view_ids = array_filter( wp_list_pluck( $post_view_posts, 'post_id' ) );

        if ( ! $post_view_ids )
        {
            return array();
        }

        return $this->get_posts( $post_view_ids, $count );
    }

    /**
     * Get a post for fallback if we are not connected to wordpress.com
     * 
     * @return array Contains that one post
     */
    function get_fallback_posts()
    {
        if ( current_user_can( 'edit_theme_options' ) )
        {
            return array();
        }

        $post_query = new WP_Query;

        $posts = $post_query->query( array(
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'post_type' => 'post',
            'no_found_rows' => true,
        ) );

        if ( ! $posts )
        {
            return array();
        }

        $post = array_pop( $posts );

        return $this->get_posts( $post->ID, 1 );
    }

    /**
     * Get posts array
     * @param  array $post_ids
     * @param  int   $count
     * @return array
     */
    function get_posts( $post_ids, $count )
    {
        $counter = 0;

        $posts = array();

        foreach ( (array) $post_ids as $post_id )
        {
            $post = get_post( $post_id );

            if ( ! $post || 'post' !== get_post_type( $post ) )
            {
                continue;
            }

            /**
             * Attachment pages use the 'inherit' post status by default.
             * To be able to remove attachment pages from private and password protect posts,
             * we need to replace their post status by the parent post' status.
             */
            if ( 'inherit' == $post->post_status && 'attachment' == $post->post_type )
            {
                $post->post_status = get_post_status( $post_id );
            }

            // hide private and password protected posts
            if ( 'publish' != $post->post_status || ! empty( $post->post_password ) )
            {
                continue;
            }

            // Both get HTML stripped etc on display
            if ( empty( $post->post_title ) )
            {
                $title_source = $post->post_content;
                $title = wp_html_excerpt( $title_source, 50 );
                $title .= '&hellip;';
            }
            else
            {
                $title = $post->post_title;
            }

            $permalink = get_permalink( $post->ID );

            $post_type = $post->post_type;

            $posts[] = compact( 'title', 'permalink', 'post_id', 'post_type' );
            $counter++;

            if ( $counter == $count )
            {
                break; // only need to load and show x number of likes
            }
        }

        return $posts;
    }
}

add_action( 'widgets_init', create_function( '', "register_widget( 'WPFelix_Popular_Posts_Widget' );" ) );