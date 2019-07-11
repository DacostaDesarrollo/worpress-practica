<?php
/**
 * Recent posts widget. This widget is followed by post view count system.
 *
 * @package WPFelix
 */

class WPFelix_Recent_Posts_Widget extends WP_Widget
{
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct(
            'wpfelix_recent_posts', // Base ID
            esc_html__( 'Felix Recent Posts', 'wpfelix' ), // Name
            array(
                'description' => esc_html__( 'Show recent posts with featured image. Go to Appearance/Customize for configuration.', 'wpfelix' ),
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

        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;

        if ( ! $number )
        {
            $number = 5;
        }

        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

        $r = new WP_Query( array(
            'posts_per_page' => $number,
            'no_found_rows' => true,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true
        ) );

        if ( $r->have_posts() )
        {
            echo '<ul>';
            while ( $r->have_posts() )
            {
                $r->the_post();
                if ( has_post_thumbnail() )
                {
                    echo '<li class="has-post-thumbnail">';
                    echo '<div class="entry-featured">';
                    echo '<a href="' . esc_url( get_permalink() ) . '">';
                    the_post_thumbnail( 'thumbnail' );
                    echo '</a>';
                    echo '</div>';
                }
                else
                {
                    echo '<li>';
                }

                echo '<div class="entry-summary">';
                the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' );

                if ( $show_date )
                {
                    printf(
                        '<div class="entry-posted-on"><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s">%3$s</time></a></div>',
                        esc_url( get_permalink() ),
                        get_the_date( 'c' ),
                        get_the_date( get_option( 'date_format' ) )
                    );
                }
                echo '</div>';
                echo '</li>';
            }
            echo '</ul>';
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

}

add_action( 'widgets_init', create_function( '', "register_widget( 'WPFelix_Recent_Posts_Widget' );" ) );