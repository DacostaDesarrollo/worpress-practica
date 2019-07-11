<?php
/**
 * Social network widget
 *
 * @package WPFelix
 */
class WPFelix_Social_Widget extends WP_Widget
{
    /**
     * Constructor
     *
     * @return void
     **/
    function __construct()
    {
        parent::__construct(
            'wpfelix_social', // Base ID
            esc_html__( 'Felix Social', 'wpfelix' ), // Name
            array(
                'description' => esc_html__( 'Show icons with social profile urls. Go to Appearance/Customize for configuration.', 'wpfelix' ),
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
                'title' => ''
            )
        );

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
        
        wpfelix_social_markup();

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
        $instance['title'] = $new_instance['title'];
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
                'title' => ''
            )
        );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'wpfelix' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>
        <?php
    }
}

add_action( 'widgets_init', create_function( '', "register_widget( 'WPFelix_Social_Widget' );" ) );