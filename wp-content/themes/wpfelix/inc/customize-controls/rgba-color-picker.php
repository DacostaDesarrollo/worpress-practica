<?php
/**
 * Alpha Color Picker Customizer Control
 *
 * This control adds a second slider for opacity to the stock WordPress color picker,
 * and it includes logic to seamlessly convert between RGBa and Hex color values as
 * opacity is added to or removed from a color.
 *
 * @package WPFelix
 * @since   WPFelix 1.0
 */

class WPFelix_Customize_RGBA_Color_Control extends WP_Customize_Control
{
    /**
     * Give the control a name.
     */
    public $type = 'rgbacolor';

    /**
     * Add support for palettes to be passed in.
     *
     * Palette values are true, false, or an array of RGBa and Hex colors.
     */
    public $palette;

    /**
     * Add support for showing the opacity value on the slider handle.
     */
    public $show_opacity;

    /**
     * Enqueue scripts and styles.
     */
    public function enqueue()
    {
        wp_enqueue_script(
            'wpfelix-customize-color-picker',
            get_template_directory_uri() . '/inc/customize-controls/js/rgba-color-picker.js',
            array( 'jquery', 'wp-color-picker' ),
            '1.0.0',
            true
        );
        wp_enqueue_style(
            'wpfelix-customize-color-picker',
            get_template_directory_uri() . '/inc/customize-controls/css/color-picker.css',
            array( 'wp-color-picker' ),
            '1.0.0'
        );
    }

    /**
     * Render the control.
     */
    public function render_content()
    {
        // Support passing show_opacity as string or boolean. Default to true.
        $this->show_opacity = ( false === $this->show_opacity || 'false' === $this->show_opacity ) ? 'false' : 'true';

        // Process the palette
        if ( is_array( $this->palette ) )
        {
            $this->palette = implode( '|', $this->palette );
        }
        else
        {
            // Default to true.
            $this->palette = ( false === $this->palette || 'false' === $this->palette ) ? 'false' : 'true';
        }

        // Begin the output.
        echo '<label>';

        // Output the label and description if they were passed in.
        if ( isset( $this->label ) && '' !== $this->label )
        {
            printf( '<span class="customize-control-title">%s</span>', sanitize_text_field( $this->label ) );
        }

        if ( isset( $this->description ) && '' !== $this->description )
        {
            printf( '<span class="description customize-control-description">%s</span>', sanitize_text_field( $this->description ) );
        }

        printf( '<input class="rgba-color-control" type="text" data-show-opacity="%1$s" data-palette="%2$s" data-default-color="%3$s" %4$s/>',
            $this->show_opacity,
            esc_attr( $this->palette ),
            esc_attr( $this->settings['default']->default ),
            $this->get_link()
        );

        // End the output.
        echo '</label>';
    }
}