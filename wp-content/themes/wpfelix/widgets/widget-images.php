<?php
/**
 * Images Widget
 * This widget allows you to pick multiple images and show at the front-end with some options
 */
class WPFelix_Images_Widget extends WP_Widget
{
    /**
     * Constructor
     *
     * @return void
     **/
    function __construct()
    {
        parent::__construct(
            'wpfelix_images', // Base ID
            esc_html__( 'Felix Images', 'wpfelix' ), // Name
            array(
                'description' => esc_html__( 'Add multiple images with some options and optional links.', 'wpfelix' ),
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
    function admin_scripts()
    {
        wp_enqueue_style( 'wpfelix-widgets', get_template_directory_uri() . '/widgets/css/widgets.css' );
        wp_enqueue_media();
        wp_enqueue_script( 'wpfelix-widget-images', get_template_directory_uri() . '/widgets/js/widget-images.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'media-upload' ), '', true );
        wp_localize_script( 'wpfelix-widget-images', 'WPFelixImagesWidgetFrame', array(
            'frame_title' => esc_html__( 'Select Image(s)', 'wpfelix' ),
            'button_title' => esc_html__( 'Insert Into Widget', 'wpfelix' ),
            'frame_edit_title' => esc_html__( 'Change Image', 'wpfelix' ),
            'button_edit_title' => esc_html__( 'Change', 'wpfelix' )
        ) );
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
                'images' => '',
                'columns' => '4',
                'links' => '',
                'target' => '',
                'size' => 'thumbnail'
            )
        );

        $instance['target'] = (bool) $instance['target'];

        if ( ! empty( $instance['title'] ) )
        {
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

        $images = $links = array();

        if ( ! empty( $instance['images'] ) )
        {
            $images = explode( ",", $instance['images'] );
        }
        if ( ! empty( $instance['links'] ) )
        {
            $links = explode( "|", $instance['links'] );
        }

        if ( ! empty( $images ) )
        {
            echo '<ul class="images columns-' . esc_attr( $instance['columns'] ) . '">';

            for ( $i = 0; $i < count( $images ) ; $i++ )
            {
                echo '<li>';
                if ( isset( $links[$i] ) && ! empty( $links[$i] ) )
                {
                    echo '<a href="' . esc_url( $links[$i] ) . '" target="' . ( $instance['target'] ? '_blank' : '_self' ) . '">';
                }

                if ( isset( $images[$i] ) && ! empty( $images[$i] ) )
                {
                    echo wp_get_attachment_image( $images[$i], esc_attr( $instance['size'] ) );
                }

                if ( isset( $links[$i] ) && ! empty( $links[$i] ) )
                {
                    echo '</a>';
                }

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
        $instance['images'] = sanitize_text_field( $new_instance['images'] );
        $instance['columns'] = absint( $new_instance['columns'] );
        $links = array();

        $links_arr = explode( "\n", $new_instance['links'] );

        foreach ( $links_arr as $link )
        {
            $link = trim( $link );
            if ( empty( $link ) )
                continue;
            $links[] = esc_url( $link );
        }

        $instance['links'] = implode( "|", $links );
        $instance['size'] = sanitize_text_field( $new_instance['size'] );
        $instance['target'] = isset( $new_instance['target'] ) ? (bool) $new_instance['target'] : true;

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
                'images' => '',
                'columns' => '4',
                'links' => '',
                'target' => true,
                'size' => 'thumbnail'
            )
        );

        $this_id    = $this->get_field_id( '' );
        $title      = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $image_ids  = explode( ",", $instance['images'] );
        $links      = explode( "|", $instance['links'] );
        $target     = isset( $instance['target'] ) ? (bool) $instance['target'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'wpfelix' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
         <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php esc_html_e( 'Columns:', 'wpfelix' ); ?></label>
            <select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>">
                <option value="1" <?php selected( "1", $instance['columns'] ); ?>>1</option>
                <option value="2" <?php selected( "2", $instance['columns'] ); ?>>2</option>
                <option value="3" <?php selected( "3", $instance['columns'] ); ?>>3</option>
                <option value="4" <?php selected( "4", $instance['columns'] ); ?>>4</option>
                <option value="5" <?php selected( "5", $instance['columns'] ); ?>>5</option>
                <option value="6" <?php selected( "6", $instance['columns'] ); ?>>6</option>
            </select>
        </p>
        <div class="wpfelix-widget-image" id="<?php echo esc_attr( $this_id ); ?>">
            <label><?php esc_html_e( 'Images', 'wpfelix' ); ?></label>
            <ul class="images"><?php
            foreach ( $image_ids as $image ) {
                $attachment_image = wp_get_attachment_image_src( $image, 'thumbnail' );
                if ( ! empty( $attachment_image ) ) {
                    echo '<li data-id="' . esc_attr( $image ) . '"'. 
                        ' style="background-image:url(' . esc_url( $attachment_image[0] ) . ');">';
                    echo '<a class="image-edit" href="#" onclick="WPFelixImagesWidget.edit_image(event,\'' . esc_attr( $this_id ) . '\',' . esc_attr( $image ) . ')">' .
                            '<i class="dashicons dashicons-edit"></i>' .
                        '</a>';
                    echo '<a class="image-delete" href="#" onclick="WPFelixImagesWidget.remove_image(event,\'' . esc_attr( $this_id ) . '\',' . esc_attr( $image ) . ');return false;">' .
                            '<i class="dashicons dashicons-trash"></i>' .
                        '</a>';
                    echo '</li>';
                }
            }
            echo '<li data-id="0">';
            echo '<a class="image-add" href="#" onclick="WPFelixImagesWidget.add_images(event,\'' . esc_attr( $this_id ) . '\');">' .
                    '<i class="dashicons dashicons-plus-alt"></i>' .
                '</a>';
            echo '</li>';
            ?></ul>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Image Size:', 'wpfelix' ); ?></label>
                <select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>">
                <?php
                    $image_sizes = apply_filters( 'image_size_names_choose', array(
                        'thumbnail' => esc_html__( 'Thumbnail', 'wpfelix' ),
                        'medium'    => esc_html__( 'Medium', 'wpfelix' ),
                        'large'     => esc_html__( 'Large', 'wpfelix' ),
                        'full'      => esc_html__( 'Full Size', 'wpfelix' )
                    ) );
                    foreach ( $image_sizes as $size => $text )
                    {
                        printf(
                            '<option value="%1$s" %2$s">%3$s</option>',
                            esc_attr( $size ),
                            selected( $size, $instance['size'], false ),
                            esc_html( $text )
                        );
                    }
                ?>
                </select>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'links' ) ); ?>"><?php echo esc_html__( 'Links', 'wpfelix' ); ?></label>
                <textarea class="image-links widefat" id="<?php echo esc_attr( $this->get_field_id( 'links' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'links' ) ); ?>" rows="10"><?php
                    foreach ( $links as $link ) {
                        if ( empty( $link ) ) {
                            continue;
                        }
                        echo esc_url( $link );
                        echo "\n";
                    }
                ?></textarea>
            </p>
            <p class="howto"><?php echo esc_html__( 'Add links for images, seperate by newline.', 'wpfelix' ); ?></p>
            <input type="hidden" name="<?php echo $this->get_field_name( 'images' ); ?>" id="<?php echo $this->get_field_id( 'images' ); ?>" value="<?php echo esc_attr( $instance['images'] ); ?>"/>
        </div>
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" <?php checked( $target );  ?>/><label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>"><?php esc_html_e( 'Open links in new tab?', 'wpfelix' ); ?></label>
        </p>
        <?php
    }
}

add_action( 'widgets_init', create_function( '', "register_widget( 'WPFelix_Images_Widget' );" ) );