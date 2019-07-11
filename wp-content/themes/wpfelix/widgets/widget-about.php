<?php defined( 'ABSPATH' ) or exit();
/**
 * Simple About Widget
 * This widget allows you to pick images, parse some text and show at the front-end.
 *
 * @author Stev Ngo <http://stevngodesign.com/>
 * @version 1.0
 */

class WPFelix_About_Widget extends WP_Widget {
    /**
     * Constructor
     *
     * @return void
     **/
    function __construct() {
        parent::__construct(
            'wpfelix_about', // Base ID
            esc_html__( 'Felix About', 'wpfelix' ), // Name
            array(
                'description' => esc_html__( 'Add about box.', 'wpfelix' ),
                'customize_selective_refresh' => true
            ) // Args
        );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts') );
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @return void
     */
    function admin_scripts() {
        wp_enqueue_style( 'wpfelix-widgets', get_template_directory_uri() . '/widgets/css/widgets.css' );
        wp_enqueue_media();
        wp_enqueue_script( 'wpfelix-widgets', get_template_directory_uri() . '/widgets/js/widgets.js', array( 'jquery', 'media-upload' ), '', true );
    }

    /**
     * Outputs the HTML for this widget.
     *
     * @param array  An array of standard parameters for widgets in this theme
     * @param array  An array of settings for this widget instance
     * @return void Echoes it's output
     **/
    function widget( $args, $instance )
    {
        extract( $args, EXTR_SKIP );
        
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'title' => '',
                'image' => '',
                'name'  => '',
                'url'   => '',
                'desc'  => '',
                'signature_img_url' => ''
            )
        );

        if ( ! empty( $instance['title'] ) ) {
            $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
        }
        else {
            $title = '';
        }

        echo $before_widget;

        if ( ! empty( $title ) )
        {
            echo $before_title . $title . $after_title;
        }

        echo '<div class="about-block">';
        
        if ( ! empty( $instance['image'] ) )
        {
            $image = ( is_numeric( $instance['image'] ) && $instance['image'] > 0 ) ? intval( $instance['image'] ) : 0;
            if ( $image > 0 ) {
                echo '<div class="my-image';
                if ( $instance['rounded'] )
                {
                    echo ' my-image-rounded';
                }
                echo ' vcard">';
                $alt = get_post_meta( $image, '_wp_attachment_image_alt', true );
                $alt = empty( $alt ) ? '' : strval( $alt );
                $image_src = wp_get_attachment_image_src( $image, 'medium' );
                
                if ( $image_src )
                {
                    if ( ! empty( $instance['url'] ) )
                    {
                        echo '<a href="' . esc_url( $instance['url'] ) . '">';
                        echo '<img src="' . esc_url( $image_src[0] ) . '" alt="' . esc_attr( $alt ) . '"/>';
                        echo '</a>';
                    }
                    else
                    {
                        echo '<img src="' . esc_url( $image_src[0] ) . '" alt="' . esc_attr( $alt ) . '"/>';
                    }
                }
                echo '</div>';
            }
        }
        
        if ( ! empty( $instance['desc'] ) )
        {
            printf( '<div class="my-desc">%s</div>', wpautop( $instance['desc'] ) );
        }

        if ( ! empty( $instance['name'] ) )
        {
            $name_markup = '';
            if ( ! empty( $instance['url'] ) )
            {
                $name_markup = sprintf( '<a href="%1$s">%2$s</a>', esc_url( $instance['url'] ), esc_html( $instance['name'] ) );
            }
            else
            {
                $name_markup = esc_html( $instance['name'] );
            }
            printf( '<h4 class="my-name">%s</h4>', $name_markup );
        }

        if ( ! empty( $instance['signature_img_url'] ) )
        {
            printf( '<div class="my-signature"><img src="%s" alt=""/></div>', esc_url( $instance['signature_img_url'] ) );
        }
        echo '</div>';

        echo $after_widget;
    }

    /**
     * Deals with the settings when they are saved by the admin. Here is
     * where any validation should be dealt with.
     *
     * @param array  An array of new settings as submitted by the admin
     * @param array  An array of the previous settings
     * @return array The validated and (if necessary) amended settings
     **/
    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['image'] = sanitize_text_field( $new_instance['image'] );
        $instance['rounded'] = $new_instance['rounded'];
        $instance['name'] = sanitize_text_field( $new_instance['name'] );
        $instance['name'] = sanitize_text_field( $new_instance['name'] );
        $instance['url'] = esc_url( $new_instance['url'] );
        $instance['desc'] = $new_instance['desc'];
        $instance['signature_img_url'] = esc_url( $new_instance['signature_img_url'] );

        return $instance;
    }

    /**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array  An array of the current settings for this widget
     * @return void Echoes it's output
     **/
    function form( $instance )
    {
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'title' => '',
                'image' => 0,
                'name' => '',
                'url' => '',
                'desc' => '',
                'rounded' => '',
                'signature_img_url' => ''
            )
        );

        $this_id    = $this->get_field_id( '' );
        $title      = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $image      = intval( $instance['image'] );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'wpfelix' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <div class="wpfelix-widget-image" id="<?php echo esc_attr( $this_id ); ?>">
            <label><?php esc_html_e( 'Image', 'wpfelix' ); ?></label>
            <ul class="image"><?php
                $attachment_image = wp_get_attachment_image_src( $image, 'thumbnail' );
                if ( ! empty( $attachment_image ) ) {
                    echo '<li data-id="' . esc_attr( $image ) . '"'. 
                        ' style="background-image:url(' . esc_url( $attachment_image[0] ) . ');">';
                    echo '<a class="image-edit" href="#" onclick="WPFelixImageWidget.edit_image(event,\'' . esc_attr( $this_id ) . '\',' . esc_attr( $image ) . ')">' .
                            '<i class="dashicons dashicons-edit"></i>' .
                        '</a>';
                    echo '<a class="image-delete" href="#" onclick="WPFelixImageWidget.remove_image(event,\'' . esc_attr( $this_id ) . '\',' . esc_attr( $image ) . ');return false;">' .
                            '<i class="dashicons dashicons-trash"></i>' .
                        '</a>';
                    echo '</li>';
                }
            echo '<li data-id="0">';
            echo '<a class="image-add" href="#" onclick="WPFelixImageWidget.add_image(event,\'' . esc_attr( $this_id ) . '\');">' .
                    '<i class="dashicons dashicons-plus-alt"></i>' .
                '</a>';
            echo '</li>';
            ?></ul>
        </div>
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'rounded' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'rounded' ) ); ?>" type="checkbox" value="1" <?php checked( "1", $instance['rounded'] );  ?>/><label for="<?php echo esc_attr( $this->get_field_id( 'rounded' ) ); ?>"><?php esc_html_e( 'Rounded Image?', 'wpfelix' ); ?></label>
        </p>
        <p class="howto"><?php echo esc_html__( 'For a perfect circle your image need to have the same height and width. For example: 240x240.', 'wpfelix' ); ?></p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>"><?php esc_html_e( 'Name:', 'wpfelix' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'name' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['name'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"><?php esc_html_e( 'Profile URL:', 'wpfelix' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>" type="url" value="<?php echo esc_url( $instance['url'] ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>"><?php echo esc_html__( 'Description', 'wpfelix' ); ?></label>
            <textarea rows="5"
                class="widefat"
                id="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'desc' ) ); ?>"><?php echo esc_textarea( $instance['desc'] ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'signature_img_url' ) ); ?>"><?php esc_html_e( 'Signature Image Url:', 'wpfelix' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'signature_img_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'signature_img_url' ) ); ?>" type="url" value="<?php echo esc_url( $instance['signature_img_url'] ); ?>" />
        </p>
        <p class="howto"><?php echo esc_html__( 'Optional signature image url.', 'wpfelix' ); ?></p>
        <input type="hidden" name="<?php echo $this->get_field_name( 'image' ); ?>" id="<?php echo $this->get_field_id( 'image' ); ?>" value="<?php echo esc_attr( $instance['image'] ); ?>"/>
        <?php
    }
}

add_action( 'widgets_init', create_function( '', "register_widget( 'WPFelix_About_Widget' );" ) );